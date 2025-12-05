<?php

namespace App\Services\Replacement;

use App\Repositories\Replacement\ReplacementRepositoryInterface;
use App\Helpers\ReplacementSerialGenerator;
use App\Services\Leaders\LeadersServiceInterface;
use App\Services\Role\RoleServiceInterface;
use App\Services\Workflow\WorkflowServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class ReplacementService implements ReplacementServiceInterface
{
    protected $replacementRepository;
    protected $workflowService;
    protected $leaderService;
    protected $roleService;

    public function __construct(
        ReplacementRepositoryInterface $replacementRepository,
        WorkflowServiceInterface $workflowService,
        LeadersServiceInterface $leaderService,
        RoleServiceInterface $roleService,
    ) {
        $this->replacementRepository = $replacementRepository;
        $this->workflowService = $workflowService;
        $this->leaderService = $leaderService;
        $this->roleService = $roleService;
    }

    public function getAllReplacement()
    {
        return $this->replacementRepository->all();
    }

    public function getReplacementList()
    {
        return $this->replacementRepository->replacmentList();
    }

    public function getHistoriesByReplacementId($replacementId)
    {
        $histories = $this->replacementRepository->findHistoriesByReplacementId($replacementId)
            ->keyBy('workflow_step_id'); // index by step_id untuk O(1) lookup

        $workflowSteps = $this->workflowService->getStepsByDefinitionId(1);

        return $workflowSteps->map(function ($step) use ($histories) {
            $history = $histories->get($step->id);

            return [
                "workflow_name" => $step->name,
                "created_by" => $history ? $history->createdBy->name . '(' . $history->createdBy->email . ')' :  '-',
                "step_order" => $step->step_order,
                "note" => $history->note ?? '',
                "is_final" => $step->is_final,
                "role" => $step->role->name ?? '-',
                "is_approved" => $history->is_approved ?? false,
                "created_at" => $history->formatted_created_at ?? null,
                "updated_at" => $history->formatted_update_at ?? null,
            ];
        });
    }

    public function getReplacementListWithPagination(array $filters)
    {
        $assignment = $this->leaderService->getLineActive(Auth::id());
        $replacement = $this->replacementRepository
            ->replacmentListWithPagination($filters, $assignment);

        if ($replacement->isEmpty()) {
            return $replacement;
        }

        return $replacement->through(function ($e) {
            $stepOrder = $this->workflowService->getWorkflowByStepId($e->current_step_id);
            $workflow = $this->workflowService->getWorkflowByStep($stepOrder->step_order, 1);
            return $this->transform($e, $workflow);
        });
    }

    public function getApprovalWithPagination(array $filters)
    {
        $roles = Auth::user()->roles->pluck('id')->toArray();
        $minIdRole = min($roles);
        if ($minIdRole <= 1) {
            $roles = $this->roleService->getAllRole()->pluck('id')->toArray();
        }

        $assignment = $this->leaderService->getLineActive(Auth::id());

        $replacement = $this->replacementRepository->replacementApprovalListWithPagination($filters, $assignment, $roles);

        if ($replacement->isEmpty()) {
            return $replacement;
        }

        return $replacement->through(function ($e) {
            $stepOrder = $this->workflowService->getWorkflowByStepId($e->current_step_id);
            $workflow = $this->workflowService->getWorkflowByStep($stepOrder->step_order, 1);
            return $this->transform($e, $workflow);
        });
    }

    public function createReplacementRequest(array $data)
    {
        $defectList = $data['defect_list'];
        $notes = $data['note'];
        $createdBy = Auth::id();
        $roles = Auth::user()->roles->pluck('id')->toArray();
        $workflow = $this->getWorkflowStepContextByRoles($roles);


        $replacement = $this->replacementRepository->create([
            "workflow_definition_id" => 1,
            "current_step_id" => $workflow['current_step_id'],
            "serial_number" => ReplacementSerialGenerator::generate($defectList[0]['gl_no']),
            "created_by" => $createdBy,
            "status" => "in_progress"
        ]);

        foreach ($defectList as $list) {
            $list['pcs'] = $list['total_defect'];
            $list['replacement_request_id'] = $replacement->id;
            $this->replacementRepository->createReplacementDetail($list);
        }

        $replacementNote = $this->replacementRepository->createReplacementNote([
            "replacement_request_id" => $replacement->id,
            "created_by" => $createdBy,
            "description" => $notes
        ]);


        /** Histories */
        $this->replacementRepository->createReplacementHistory([
            "replacement_request_id" => $replacement->id,
            "workflow_step_id" => $workflow['steps']['current']['id'],
            "action_by" => $createdBy,
            "note" => "",
            "is_approved" => true,
        ]);
        /** if step before exist */
        if (count($workflow['steps']['step_before'])  > 0) {
            foreach ($workflow['steps']['step_before'] as $history) {
                $this->replacementRepository->createReplacementHistory([
                    "replacement_request_id" => $replacement->id,
                    "workflow_step_id" => $history['id'],
                    "action_by" => $createdBy,
                    "note" => "",
                    "is_approved" => true,
                ]);
            }
        }
    }

    public function getDefectByGLNumber(string $glNumber)
    {
        $replacement = $this->replacementRepository->replacementGlNumber($glNumber);
        return $replacement;
    }

    /** Transfrom Replacement List */
    private function transform($e, $workflow = null)
    {
        $defectList = $e->replacementDetail
            ->groupBy('color')
            ->map(function ($d, $color) {

                return [
                    "color" => $color,
                    "laying_planning_id" => $d->first()->laying_planning_id,
                    "total_defect" => $d->sum('pcs'),
                    "total_size" => $d->count('size'),
                    "sizes" => $d->pluck('size')->unique()->implode(" • "),
                    "size_list" => $d->map(fn($s) => [
                        "size" => $s->size,
                        "defect_qty" => $s->pcs
                    ])
                ];
            })->values();

        switch ($e->status) {
            case "in_progress":
                $statusName = "In Progress";
                $statusClass = "bg-yellow-100";
                $statusType = "warning";
                break;
            case "rejected":
                $statusName = "'Rejected";
                $statusClass = "bg-red-100";
                $statusType = "error";
                break;
            case "completed":
                $statusName = "'Completed";
                $statusClass = "bg-green-100";
                $statusType = "success";
                break;
        }

        $notes  = $e->notes->map(function ($note) {
            return [
                "id" => $note->id,
                "note" => $note->description,
                "created_by" => $note->createdBy->name ? $note->createdBy->email : "-",
                "created_at" => $note->formatted_created_at,
                "updated_at" => $note->formatted_updated_at,
            ];
        });

        return [
            "id" => $e->id,
            "serial_number" => $e->serial_number,
            "gl_no" => $e->replacementDetail->first()->gl_no,
            "line_names" => $e->replacementDetail->pluck('line.name')->unique(),
            "colors" => $e->replacementDetail->pluck('color')->unique()->implode(" • "),
            "defect_sizes" => $e->replacementDetail->pluck('size')->unique()->implode(" • "),
            "defect_list" => $defectList,
            "defect_total" => $defectList->sum('total_defect'),
            "total_size" => $e->replacementDetail->count('total_size'),
            "is_approval" => false,
            "current_step" => $workflow['current']['step_order'] ?? 0,
            "status" => [
                "name" => $statusName,
                "type" => $statusType,
                "class" => $statusClass
            ],
            "requested_by" => $e->requestedBy ? $e->requestedBy->email : '-',
            "created_at" => Carbon::parse($e->created_at)->format("F d,Y H:i"),
            "updated_at" => Carbon::parse($e->updated_at)->format("F d,Y H:i"),
            "notes" => $notes,
            "workflow" => $workflow ? [
                "id" => $workflow['current']['workflow_definition_id'] ?? 0,
                "color" => $workflow['current']['role']['color'] ?? '#000',
                "current" => $workflow['current']?->name,
                "next" => $workflow['step_after']?->name,
                "previous" => $workflow['step_before']?->name,
            ] : null,
        ];
    }

    private function getWorkflowStepContextByRoles(array $roles)
    {
        $workFlowIds = [];
        $steps = [];
        foreach ($roles as $role) {
            $step  = $this->workflowService->getWorkflowStepByRoleId($role);


            $workFlowIds[] = $step ? $step['current']['id'] : null;
        }
        $maxStepId = max($workFlowIds);
        $steps = $this->workflowService->getWorkflowStepContext($maxStepId);

        /** Note 
         * current_step_id itu untuk step selanjutnya dari role yang action function ini
         */
        return [
            'current_step_id' => $step ? $steps['step_after']['id'] : 0,
            'steps' => $steps,
        ];
    }
}

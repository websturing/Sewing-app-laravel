<?php

namespace App\Services\Replacement;

use App\Repositories\Replacement\ReplacementRepositoryInterface;
use App\Helpers\ReplacementSerialGenerator;
use App\Services\Leaders\LeadersServiceInterface;
use App\Services\Workflow\WorkflowServiceInterface;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReplacementService implements ReplacementServiceInterface
{
    protected $replacementRepository;
    protected $workflowService;
    protected $leaderService;

    public function __construct(
        ReplacementRepositoryInterface $replacementRepository,
        WorkflowServiceInterface $workflowService,
        LeadersServiceInterface $leaderService,
    ) {
        $this->replacementRepository = $replacementRepository;
        $this->workflowService = $workflowService;
        $this->leaderService = $leaderService;
    }

    public function getAllReplacement()
    {
        return $this->replacementRepository->all();
    }

    public function getReplacementList()
    {
        return $this->replacementRepository->replacmentList();
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
            $workflow = $this->workflowService->getWorkflowByStep($stepOrder->step_order);
            return $this->transform($e, $workflow);
        });
    }

    public function getApprovalWithPagination(array $filters)
    {
        $roles = Auth::user()->roles->pluck('id')->toArray();
        $assignment = $this->leaderService->getLineActive(Auth::id());
        return $replacement = $this->replacementRepository->replacementApprovalListWithPagination($filters, $assignment, $roles);

        if ($replacement->isEmpty()) {
            return $replacement;
        }

        return $replacement->through(function ($e) {
            $stepOrder = $this->workflowService->getWorkflowByStepId($e->current_step_id);
            $workflow = $this->workflowService->getWorkflowByStep($stepOrder->step_order);
            return $this->transform($e, $workflow);
        });
    }

    public function createReplacementRequest(array $data)
    {
        $replacementRequest = [
            "workflow_definition_id" => 1,
            "current_step_id" => 1,
            "serial_number" => ReplacementSerialGenerator::generate($data[0]['gl_no']),
            "created_by" => Auth::id(),
            "status" => "in_progress"
        ];

        $replacementDetail = $data;

        return $this->replacementRepository->create($replacementRequest, $replacementDetail);
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
                    "size_list" => $d->map(fn($s) => [
                        "size" => $s->size,
                        "defect_qty" => $s->pcs
                    ])
                ];
            })->values();

        switch ($e->status) {
            case "in_progress":
                $statusName = "In Progress";
                $statusClass = "!bg-amber-100";
                $statusType = "warning";
                break;
            case "rejected":
                $statusName = "'Rejected";
                $statusClass = "!bg-red-100";
                $statusType = "error";
                break;
            case "completed":
                $statusName = "'Completed";
                $statusClass = "!bg-green-100";
                $statusType = "success";
                break;
        }

        return [
            "serial_number" => $e->serial_number,
            "gl_no" => $e->replacementDetail->first()->gl_no,
            "line_names" => $e->replacementDetail->pluck('line.name')->unique(),
            "colors" => $e->replacementDetail->pluck('color')->unique()->implode(","),
            "defect_list" => $defectList,
            "defect_total" => $defectList->sum('total_defect'),
            "total_size" => $e->replacementDetail->count('total_size'),
            "is_approval" => false,
            "current_step" => $workflow['current']['step_order'] ?? 0,
            "status_name" => $statusName,
            "status_type" => $statusType,
            "status_class" => $statusClass,
            "requested_by" => $e->requestedBy ? $e->requestedBy->name . '(' . $e->requestedBy->email . ')' : '-',
            "created_at" => Carbon::parse($e->created_at)->format("F d,Y H:i"),
            "updated_at" => Carbon::parse($e->updated_at)->format("F d,Y H:i"),
            "workflow" => $workflow ? [
                "id" => $workflow['current']['workflow_definition_id'] ?? 0,
                "current" => $workflow['current']?->name,
                "next" => $workflow['step_after']?->name,
                "previous" => $workflow['step_before']?->name,
            ] : null,
        ];
    }
}

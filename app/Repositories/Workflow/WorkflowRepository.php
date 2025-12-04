<?php

namespace App\Repositories\Workflow;

use App\Models\WorkflowDefinition;
use App\Models\WorkflowStep;


class WorkflowRepository implements WorkflowRepositoryInterface
{
    public function all()
    {
        return WorkflowStep::all();
    }

    public function findByStep(int $stepNumber, int $workflowId)
    {
        $steps = WorkflowStep::with(['definition', 'role'])
            ->where('workflow_definition_id', $workflowId)
            ->whereIn('step_order', [
                $stepNumber - 1,
                $stepNumber,
                $stepNumber + 1,
            ])->get()->keyBy('step_order');

        return [
            "step_before" => $steps[$stepNumber - 1] ?? null,
            "current"      => $steps[$stepNumber] ?? null,
            "step_after"   => $steps[$stepNumber + 1] ?? null,
        ];
    }

    public function findStepByRoleId(int $roleId)
    {
        $workFlowStep = WorkflowStep::where('role_id_responsible', $roleId)->first();
        if ($workFlowStep) {
            return $this->findByStep($workFlowStep->step_order, 1);
        }
        return $this->findByStep(1, 1);
    }

    public function findByStepId(int $stepId)
    {

        return WorkflowStep::find($stepId);
        return $this->findByStep($step->id, $step->workflow_definition_id);
    }

    public function findById(int $id)
    {

        return WorkflowDefinition::with(['steps', 'steps.role'])->find($id);
    }

    public function findWorkflowStepContext(int $stepId)
    {
        $currentStep = WorkflowStep::find($stepId);
        $currentOrder = $currentStep->step_order;

        // Get previous steps
        $previousSteps = WorkflowStep::where('step_order', '<', $currentOrder)
            ->orderBy('step_order')
            ->get();

        // Check if final
        $isFinal = $currentStep->is_final;

        // Calculate next order only if not final
        $nextOrder = null;
        if (! $isFinal) {
            $nextOrder = WorkflowStep::where('step_order', $currentOrder + 1)->first();
        }

        return [
            "step_before" => $previousSteps,
            "current" => $currentStep,
            "step_after" => $nextOrder
        ];
    }

    public function findStepsByDefinitionId(int $definitionId)
    {
        return WorkflowStep::with(['role'])->where('workflow_definition_id', $definitionId)->get();
    }
}

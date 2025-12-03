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

    public function findByStep(int $stepNumber)
    {
        $steps = WorkflowStep::with(['definition', 'role'])->whereIn('step_order', [
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

        return $this->findByStep($workFlowStep->step_order);
    }

    public function findByStepId(int $stepId)
    {
        return WorkflowStep::find($stepId);
    }

    public function findById(int $id)
    {

        return WorkflowDefinition::with(['steps', 'steps.role'])->find($id);
    }
}

<?php

namespace App\Repositories\Workflow;

use App\Models\WorkflowStep;

class WorkflowRepository implements WorkflowRepositoryInterface
{
    public function all()
    {
        return WorkflowStep::all();
    }

    public function findByStep(int $stepNumber)
    {
        $steps = WorkflowStep::with(['definition'])->whereIn('step_order', [
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
}

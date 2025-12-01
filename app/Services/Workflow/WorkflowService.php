<?php

namespace App\Services\Workflow;

use App\Repositories\Workflow\WorkflowRepositoryInterface;

class WorkflowService implements WorkflowServiceInterface
{
    protected $workflowRepository;

    public function __construct(WorkflowRepositoryInterface $workflowRepository)
    {
        $this->workflowRepository = $workflowRepository;
    }

    public function getAllWorkflow()
    {
        return $this->workflowRepository->all();
    }

    public function getWorkflowByStep(int $stepNumber)
    {
        return $this->workflowRepository->findByStep($stepNumber);
    }

    public function getWorkflowById(int $stepNumber)
    {
        $workflow = $this->workflowRepository->findById($stepNumber);

        $steps = $workflow->steps->map(function ($e) {
            return [
                "id" => $e->id,
                "name" => $e->name,
                "role" => $e->role->name ?? '-',
                "step" => $e->step_order,
                "is_final" => (bool)$e->is_final
            ];
        });

        return [
            "id" => $workflow->id,
            "name" => $workflow->name,
            "steps" => $steps
        ];
    }
}

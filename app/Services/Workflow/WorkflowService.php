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
}

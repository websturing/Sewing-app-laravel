<?php

namespace App\Repositories\Workflow;

interface WorkflowRepositoryInterface
{
    public function all();
    public function findByStep(int $stepNumber, int $workflowId);
    public function findByStepId(int $stepId);
    public function findById(int $id);
    public function findStepByRoleId(int $id);
    public function findWorkflowStepContext(int $stepId);
}

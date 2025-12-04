<?php

namespace App\Services\Workflow;

interface WorkflowServiceInterface
{
    public function getAllWorkflow();
    public function getWorkflowByStep(int $stepNumber, int $workflowId);
    public function getWorkflowById(int $stepNumber);
    public function getWorkflowByStepId(int $stepNumber);
    public function getWorkflowStepByRoleId(int $roleId);
    public function getWorkflowStepContext(int $stepId);
}

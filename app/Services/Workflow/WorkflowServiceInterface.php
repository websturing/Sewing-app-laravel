<?php

namespace App\Services\Workflow;

interface WorkflowServiceInterface
{
    public function getAllWorkflow();
    public function getWorkflowByStep(int $stepNumber);
    public function getWorkflowById(int $stepNumber);
    public function getWorkflowByStepId(int $stepNumber);
}

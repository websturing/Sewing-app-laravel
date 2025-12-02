<?php

namespace App\Repositories\Workflow;

interface WorkflowRepositoryInterface
{
    public function all();
    public function findByStep(int $stepNumber);
    public function findByStepId(int $stepId);
    public function findById(int $id);
}

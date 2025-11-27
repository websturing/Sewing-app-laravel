<?php

namespace App\Repositories\Workflow;

interface WorkflowRepositoryInterface
{
    public function all();
    public function findByStep(int $stepNumber);
}

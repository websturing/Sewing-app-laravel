<?php

namespace App\Repositories\Workflow;

use App\Models\Workflow;

class WorkflowRepository implements WorkflowRepositoryInterface
{
    public function all()
    {
        return Workflow::all();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowDefinition extends Model
{
    protected $table = 'workflow_definitions';

    public function steps()
    {
        return $this->hasMany(WorkflowStep::class);
    }
}

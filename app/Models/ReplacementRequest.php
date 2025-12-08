<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReplacementRequest extends Model
{
    protected $table = 'replacement_request';
    protected $fillable = [
        'workflow_definition_id',
        'current_step_id',
        'serial_number',
        'created_by',
        'status'
    ];

    function replacementDetail()
    {
        return $this->hasMany(ReplacementRequestDetail::class, 'replacement_request_id');
    }

    function requestedBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    function workflowStep()
    {
        return $this->belongsTo(WorkflowStep::class, 'current_step_id');
    }
}

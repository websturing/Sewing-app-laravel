<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ReplacementRequestHistory extends Model
{
    protected $table = 'replacement_histories';
    protected $fillable = [
        'is_approved',
        'note',
        'action_by',
        'workflow_step_id',
        'replacement_request_id'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected function formattedCreatedAt(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->created_at ? $this->created_at->format('F d, Y H:i') : null,
        );
    }

    protected function formattedUpdatedAt(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->updated_at ? $this->updated_at->format('F d, Y H:i') : null,
        );
    }

    function workflowStep()
    {
        return $this->belongsTo(WorkflowStep::class);
    }

    function createdBy()
    {
        return $this->belongsto(User::class, 'action_by');
    }
}

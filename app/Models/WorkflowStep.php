<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class WorkflowStep extends Model
{
    protected $table = 'workflow_steps';

    protected $casts = [
        'is_final' => 'boolean'
    ];

    // Custom accessor untuk formatted date
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

    function definition()
    {
        return $this->belongsTo(WorkflowDefinition::class, 'workflow_definition_id');
    }

    function role()
    {
        return $this->belongsTo(Role::class, 'role_id_responsible');
    }

    function replacementHistory()
    {
        return $this->hasMany(ReplacementRequestHistory::class, 'workflow_step_id');
    }
}

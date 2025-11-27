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
}

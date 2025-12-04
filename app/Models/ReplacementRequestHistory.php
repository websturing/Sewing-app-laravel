<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}

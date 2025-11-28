<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReplacementRequestDetail extends Model
{
    protected $table = 'replacement_request_detail';
    protected $fillable = [
        'gl_no',
        'size',
        'color',
        'pcs',
        'line_id',
        'laying_planning_id',
        'description',
        'replacement_request_id'
    ];

    function line()
    {
        return $this->belongsTo(Line::class, 'line_id');
    }
}

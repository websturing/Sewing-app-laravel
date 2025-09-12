<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assigmentline extends Model
{
    protected $table = 'assignment_lines';
    protected $fillable = [
        'user_id',
        'line_id',
        'gl_id',
        'date_start',
        'date_end',
    ];

    function line()
    {
        return $this->belongsTo(Line::class, 'line_id');
    }

    function layingPlanning()
    {
        return $this->hasMany(LayingPlanning::class, 'assignment_line_id');
    }

    function glNumber()
    {
        return $this->belongsTo(GlNumber::class, 'gl_id');
    }
}

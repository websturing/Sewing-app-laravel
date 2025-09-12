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
}

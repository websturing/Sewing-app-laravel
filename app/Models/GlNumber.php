<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlNumber extends Model
{
    protected $table = 'gls';

    protected $fillable = [
        'gl_number',
    ];
}

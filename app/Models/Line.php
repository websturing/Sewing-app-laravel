<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    function assignment()
    {
        return $this->hasMany(Assigmentline::class, 'line_id');
    }
}

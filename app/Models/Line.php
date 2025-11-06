<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    function assignment()
    {
        return $this->hasMany(Assigmentline::class, 'line_id');
    }

    function stockins()
    {
        return $this->hasMany(Stockin::class, 'line_id');
    }

    public function latestStockin()
    {
        return $this->hasOne(Stockin::class, 'line_id')->latestOfMany();
    }
}

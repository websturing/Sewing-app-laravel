<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stockin extends Model
{
    protected $table = "stock_ins";

    function line()
    {
        return $this->belongsTo(Line::class, 'line_id');
    }
}

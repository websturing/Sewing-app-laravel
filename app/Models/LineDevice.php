<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LineDevice extends Model
{
    protected $table = 'line_devices';


    function line()
    {
        return $this->belongsTo(Line::class, 'line_id', 'id');
    }

    function device()
    {
        return $this->belongsTo(Device::class, 'device_id', 'id');
    }
}

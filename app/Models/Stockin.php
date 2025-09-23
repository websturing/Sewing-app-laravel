<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stockin extends Model
{
    protected $table = "stock_ins";
    protected $fillable = [
        'serial_number',
        'ticket_no',
        'gl_no',
        'size',
        'user_dispatch_id',
        'color',
        'pcs',
        'date_stock_out',
        'cor_id',
        'user_id',
        'user_dispatch_name',
        'box_number',
        'line_id',
        'input_source',
        'container_scan_status'
    ];

    function line()
    {
        return $this->belongsTo(Line::class, 'line_id');
    }
}

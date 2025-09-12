<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LayingPlanning extends Model
{
    protected $table = 'laying_plannings';
    protected $fillable = [
        'assignment_line_id',
        'color',
        'type',
        'order_qty',
        'cut_qty',
    ];

    public function assigmentLine()
    {
        return $this->belongsTo(Assigmentline::class, 'assignment_line_id');
    }
}

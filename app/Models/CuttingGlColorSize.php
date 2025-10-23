<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CuttingGlColorSize extends Model
{
    protected $fillable = [
        'cutting_gl_color_id',
        'size',
        'order_qty',
        'cut_qty',
        'stock_out_qty',
        'replacement_qty',
    ];

    public function color(): BelongsTo
    {
        return $this->belongsTo(CuttingGlColor::class, 'cutting_gl_color_id');
    }
}

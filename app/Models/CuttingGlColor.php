<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CuttingGlColor extends Model
{
    protected $fillable = [
        'cutting_gl_summary_id',
        'color',
        'type',
        'order_qty',
        'cut_qty',
        'stock_out_qty',
        'replacement_qty',
        'last_sync_at',
    ];

    public function summary(): BelongsTo
    {
        return $this->belongsTo(CuttingGlSummary::class, 'cutting_gl_summary_id');
    }

    public function sizes(): HasMany
    {
        return $this->hasMany(CuttingGlColorSize::class);
    }
}

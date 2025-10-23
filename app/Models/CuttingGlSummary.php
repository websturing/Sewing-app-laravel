<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CuttingGlSummary extends Model
{
    protected $fillable = [
        'gl_number',
        'order_qty',
        'cut_qty',
        'stock_out_qty',
        'replacement_qty',
        'last_sync_at',
    ];

    public function colors(): HasMany
    {
        return $this->hasMany(CuttingGlColor::class);
    }
}

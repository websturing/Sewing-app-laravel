<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlNumber extends Model
{
    protected $table = 'gls';

    protected $fillable = [
        'gl_number',
    ];

    public function stockIns()
    {
        return $this->hasMany(StockIn::class, 'gl_no', 'gl_number');
    }
}

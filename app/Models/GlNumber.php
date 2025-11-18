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
    public function glNumberByStockIns($startDate = null, $endDate = null, $color = 'all')
    {
        if ($color != 'all') {
            $records =  StockIn::summaryGroupedByGlNo($startDate, $endDate)
                ->where('stock_ins.gl_no', $this->gl_number)
                ->where('stock_ins.color', $color)
                ->get();
        } else {
            $records =  StockIn::summaryGroupedByGlNo($startDate, $endDate)
                ->where('stock_ins.gl_no', $this->gl_number)
                ->get();
        }
        return $records;
    }
}

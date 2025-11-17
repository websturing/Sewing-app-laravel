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
    public function glNumberByStockIns($startDate = null, $endDate = null)
    {

        $records =  StockIn::summaryGroupedByGlNo($startDate, $endDate)
            ->where('stock_ins.gl_no', $this->gl_number)
            ->get();


        $result = $records
            ->groupBy('gl_no')
            ->map(function ($groupedByGl) {
                return [
                    'gl_no' => $groupedByGl->first()->gl_no,
                    'total_colors' => $groupedByGl->groupBy('color')->count(),
                    'total_pcs' => $groupedByGl->sum('total_pcs'),
                    'colors' => $groupedByGl
                        ->groupBy('color')
                        ->map(function ($byColor) {
                            return [
                                'color' => $byColor->first()->color,
                                'total_bundle' => $byColor->sum('total_bundle'),
                                'total_pcs' => $byColor->sum('total_pcs'),
                                'total_defect' => $byColor->sum('total_defect'),
                                'sizes' => $byColor->map(fn($r) => [
                                    'size' => $r->size,
                                    'bundle' => $r->total_bundle,
                                    'pcs' => $r->total_pcs,
                                    'defect' => $r->total_defect,
                                ])->values()
                            ];
                        })->values(),
                ];
            })
            ->first(); // <- THIS

        return $result;
    }
}

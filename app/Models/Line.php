<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    function assignment()
    {
        return $this->hasMany(Assigmentline::class, 'line_id');
    }

    function stockins()
    {
        return $this->hasMany(Stockin::class, 'line_id');
    }

    public function latestStockin()
    {
        return $this->hasOne(Stockin::class, 'line_id')->latestOfMany();
    }

    public function groupedStockIns($startDate, $endDate)
    {
        $records = Stockin::summaryGroupedByGlNo($startDate, $endDate)
            ->where('stock_ins.line_id', $this->id)
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
            ->values();
        return $result;
    }
    // app/Models/Line.php
    public function historyGLNumberByStockIns($startDate = null, $endDate = null)
    {
        // Ambil data dari scope (sudah grouped by gl_no, color, size)
        $records = Stockin::summaryGroupedByGlNo($startDate, $endDate)
            ->where('stock_ins.line_id', $this->id)
            ->get(); // tanpa orderBy di SQL

        // Group data berdasarkan gl_no
        $result = $records
            ->groupBy('gl_no')
            ->map(function ($groupedByGl) {
                $latest = $groupedByGl->sortByDesc('updated_at')->first();

                return [
                    'gl_no' => $groupedByGl->first()->gl_no,
                    'total_colors' => $groupedByGl->groupBy('color')->count(),
                    'total_pcs' => $groupedByGl->sum('total_pcs'),
                    // simpan raw timestamp utk sorting nanti
                    'updated_at_raw' => $latest->updated_at,
                    'updated_at' => \Carbon\Carbon::parse($latest->updated_at)
                        ->locale('id')
                        ->translatedFormat('F, d Y H:i'),
                ];
            })
            // sort hasil final pakai timestamp mentah, bukan string format
            ->sortByDesc('updated_at_raw')
            ->values()
            // lalu hilangkan field raw biar output clean
            ->map(fn($item) => collect($item)->except('updated_at_raw'));

        return $result;
    }
}

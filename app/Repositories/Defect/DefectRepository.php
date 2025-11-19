<?php

namespace App\Repositories\Defect;

use App\Models\Defect;
use App\Models\StockinDefect;
use Illuminate\Support\Facades\DB;

class DefectRepository implements DefectRepositoryInterface
{
    public function all()
    {
        return StockinDefect::all();
    }

    public function groupLines()
    {
        $records = DB::table('stock_in_defects')
            ->leftJoin('stock_ins', 'stock_ins.id', '=', 'stock_in_defects.stockin_id')
            ->leftJoin('lines', 'lines.id', '=', 'stock_ins.line_id')
            ->select(
                'stock_ins.gl_no as gl_no',
                'stock_ins.color as color',
                'stock_ins.size as size',
                'stock_ins.line_id as line_id',
                'lines.name as line_name',
                DB::raw('CAST(SUM(stock_in_defects.qty) AS UNSIGNED) as total_defect'),
                DB::raw('CAST(SUM(stock_ins.pcs) AS UNSIGNED) as total_pcs')
                // Hapus koma di akhir jika ini adalah elemen terakhir
            )
            ->groupBy(
                'gl_no',
                'color',
                'size',
                'line_id'
            )
            ->get();

        return $records;
    }
}

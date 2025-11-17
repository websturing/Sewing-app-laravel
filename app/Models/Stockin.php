<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Auditable;
use Illuminate\Support\Facades\DB;

class Stockin extends Model
{

    use Auditable;



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


    function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    function line()
    {
        return $this->belongsTo(Line::class, 'line_id');
    }

    public function scopeSummaryGroupedByGlNo($query, $startDate, $endDate, $glNo = null)
    {
        $defectSub = DB::table('stock_in_defects')
            ->select('stockin_id', DB::raw('SUM(qty) as total_defect'))
            ->groupBy('stockin_id');

        $query->leftJoinSub($defectSub, 'defects', 'stock_ins.id', '=', 'defects.stockin_id')
            ->join('lines', 'stock_ins.line_id', '=', 'lines.id')
            ->select(
                'stock_ins.gl_no',
                'stock_ins.color',
                DB::raw('MAX(stock_ins.updated_at) as updated_at'),
                DB::raw('MIN(stock_ins.updated_at) as start_updated_at'),
                'stock_ins.size',
                DB::raw('COUNT(*) as total_bundle'),
                DB::raw('COALESCE(SUM(stock_ins.pcs - COALESCE(defects.total_defect, 0)), 0) as total_pcs'),
                DB::raw('COALESCE(SUM(defects.total_defect), 0) as total_defect'),
                DB::raw('GROUP_CONCAT(DISTINCT lines.name ORDER BY lines.name SEPARATOR ", ") as line_names'),
                DB::raw('COUNT(DISTINCT stock_ins.color) as total_colors')
            )
            ->when(
                $startDate && $endDate,
                fn($q) =>
                $q->whereBetween('stock_ins.updated_at', [$startDate, $endDate])
            )
            ->when(
                $glNo,
                fn($q) => $q->where('stock_ins.gl_no', $glNo)
            )
            ->groupBy('stock_ins.gl_no', 'stock_ins.color', 'stock_ins.size')
            ->orderBy('stock_ins.gl_no', 'asc');

        return $query;
    }
}

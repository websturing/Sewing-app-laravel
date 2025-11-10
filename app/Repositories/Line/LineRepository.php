<?php

namespace App\Repositories\Line;

use App\Models\Line;
use App\Models\LineDevice;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LineRepository implements LineRepositoryInterface
{
    public function all(array $filters)
    {
        $query = Line::query();

        $query->when(
            $filters['q'] ?? null,
            fn($q, $name) => $q->where('name', 'LIKE', "%{$name}%")
        );


        return $query; // <--- jangan pakai get()
    }

    public function lines()
    {
        return Line::all();
    }

    public function linesWithStockin()
    {
        return Line::with(['stockins'])->get();
    }


    public function groupByLineGlNumber($searchTerm = null, $startDate = null, $endDate = null)
    {
        $query = DB::table('lines')
            ->leftJoin('stock_ins', 'lines.id', '=', 'stock_ins.line_id')
            ->select(
                'lines.name as line_name',
                'stock_ins.gl_no',
                'stock_ins.color',
                DB::raw('COUNT(stock_ins.id) as total_bundle'),
                DB::raw('COALESCE(SUM(stock_ins.pcs), 0) as total_pcs'),
                DB::raw('MAX(stock_ins.updated_at) as last_updated')
            )
            ->groupBy('lines.name', 'stock_ins.gl_no', 'stock_ins.color')
            // 🔽 natural sort berdasarkan prefix huruf dan angka di belakang
            ->orderByRaw("
            REGEXP_REPLACE(lines.name, '[^0-9]', '') + 0 ASC,
            REGEXP_REPLACE(lines.name, '[0-9]', '') ASC
        ")
            ->orderBy('stock_ins.gl_no')
            ->orderBy('stock_ins.color');

        // 🔍 Filter pencarian
        if (!empty($searchTerm)) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('lines.name', 'like', "%{$searchTerm}%")
                    ->orWhere('stock_ins.gl_no', 'like', "%{$searchTerm}%")
                    ->orWhere('stock_ins.color', 'like', "%{$searchTerm}%");
            });
        }

        // 📅 Filter tanggal
        if (!empty($startDate) && !empty($endDate)) {
            $query->whereBetween(DB::raw('DATE(stock_ins.updated_at)'), [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
        }

        $results = $query->get();

        // ✅ Kelompokkan per Line
        $grouped = $results->groupBy('line_name')->map(function ($items, $lineName) {
            $hasStock = $items->first()->gl_no !== null;

            return [
                'line_name' => $lineName,
                'last_updated' => $hasStock
                    ? Carbon::parse($items->max('last_updated'))->isoFormat('MMMM D, YYYY HH:mm')
                    : null,
                'details' => $hasStock
                    ? $items->map(function ($item) {
                        return [
                            'gl_no' => $item->gl_no,
                            'color' => $item->color,
                            'total_bundle' => (int) $item->total_bundle,
                            'total_pcs' => (int) $item->total_pcs,
                            'last_updated' => $item->last_updated
                                ? Carbon::parse($item->last_updated)->isoFormat('MMMM D, YYYY HH:mm')
                                : null,
                        ];
                    })->values()
                    : [],
            ];
        })->values();

        return $grouped;
    }

    /**
     * LINES
     * Get Line with last GL Number Transactions .
     *
     * @param string|null $sortBy
     * @param integer|null $perPage
     * @param integer|null $page
     * @param string $search
     * @return LengthAwarePaginator
     */

    public function linesWithLastGlTransactions($filters)
    {
        $sortBy   = $filters['sort_by'] ?? 'name';
        $perPage  = (int)($filters['per_page'] ?? 10);
        $page     = (int)($filters['page'] ?? 1);
        $search   = $filters['search'] ?? null;

        $query = Line::with('latestStockin');

        // 🔍 Filter by search
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('lines.name', 'like', "%{$search}%")
                    ->orWhereHas('latestStockin', function ($subQuery) use ($search) {
                        $subQuery->where('gl_no', 'like', "%{$search}%");
                    });
            });
        }

        // 🔢 Sorting
        if ($sortBy === 'name') {
            $query->orderByRaw("
            REGEXP_REPLACE(lines.name, '[0-9]', '') ASC,
            CAST(REGEXP_REPLACE(lines.name, '[^0-9]', '') AS UNSIGNED) ASC
        ");
        } else {
            $query->orderBy($sortBy, 'asc');
        }

        // 📄 Pagination
        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * LINES GET BY
     * Get Line with last GL Number Transactions .
     *
     * @param integer|null $lineId
     * @return LengthAwarePaginator
     */

    public function findById($filters)
    {
        $line =  Line::find($filters['line_id']);
        $grouped = $line->groupedStockIns($filters['start_date'], $filters['end_date']);
        return [
            'line' => $line,
            'stockin_summary' => $grouped
        ];
    }

    /**
     * LINES GET BY Devices
     *
     * @param integer|null $lineId
     */
    public function lineDevices($lineId)
    {
        return LineDevice::with('device')
            ->where('line_id', $lineId)
            ->get();
    }
}

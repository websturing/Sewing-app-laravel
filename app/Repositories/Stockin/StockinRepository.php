<?php

namespace App\Repositories\Stockin;

use App\Models\Stockin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class StockinRepository implements StockinRepositoryInterface
{

    protected $model;

    public function __construct(StockIn $model)
    {
        $this->model = $model;
    }


    public function all(array $params)
    {
        $query = $this->model->newQuery();

        if (!empty($params['filters'])) {
            $query = $this->applyFilters($query, $params['filters']);
        }

        // Apply sorting
        if (!empty($params['sorts'])) {
            foreach ($params['sorts'] as $field => $direction) {
                $query->orderBy($field, $direction);
            }
        }

        // Apply relations
        if ($params['with_relations'] && !empty($params['relations'])) {
            $query->with($params['relations']);
        }

        return $query->paginate(
            $params['per_page'],
            ['*'],
            'page',
            $params['page']
        );
    }

    public function query(): Builder
    {
        return $this->model::query();
    }

    public function groupByGlNumber($searchTerm, $perPage = 10, $sortBy = null, $sortOrder = 'desc', $page = 1)
    {
        $query = DB::table('stock_ins')
            ->join('lines', 'stock_ins.line_id', '=', 'lines.id')
            ->select(
                'stock_ins.gl_no',
                DB::raw('COUNT(*) as total_bundle'),
                DB::raw('COALESCE(SUM(pcs), 0) as total_pcs'),
                DB::raw('MAX(stock_ins.updated_at) as last_updated'),
                DB::raw('GROUP_CONCAT(DISTINCT lines.name ORDER BY lines.name SEPARATOR ", ") as line_names'),
                DB::raw('COUNT(DISTINCT stock_ins.color) as total_colors'),
                DB::raw('COUNT(DISTINCT stock_ins.size) as total_sizes')
            )
            ->groupBy('stock_ins.gl_no');

        // Jika sortBy tidak diisi, pakai default order by total_pcs desc (seperti code lama)
        if ($sortBy) {
            $allowedSortColumns = ['gl_no', 'total_pcs', 'total_bundle', 'last_updated'];
            $sortBy = in_array($sortBy, $allowedSortColumns) ? $sortBy : 'total_pcs';
            $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? $sortOrder : 'desc';
            $query->orderBy($sortBy, $sortOrder);
        } else {
            // Default behavior - sama persis dengan code lama
            $query->orderByDesc('total_pcs');
        }

        if (!empty($searchTerm)) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('stock_ins.gl_no', 'like', "%{$searchTerm}%")
                    ->orWhere('lines.name', 'like', "%{$searchTerm}%");
            });
        }

        // Paginate dengan Laravel Paginator
        $paginator = $query->paginate(
            $perPage,
            ['*'],
            'page',
            $page
        );

        // Transform items
        $paginator->getCollection()->transform(function ($item) {
            return [
                'gl_no' => $item->gl_no,
                'total_bundle' => (int) $item->total_bundle,
                'total_pcs' => (int) $item->total_pcs,
                'last_updated' => $item->last_updated
                    ? Carbon::parse($item->last_updated)->isoFormat('MMMM D, YYYY HH:mm')
                    : null,
                'line_names' => $item->line_names,
                'total_colors' => $item->total_colors,
                'total_sizes' => $item->total_sizes,
            ];
        });


        return $paginator;
    }

    public function groupByGlNumberColor($searchTerm, $startDate = null, $endDate = null)
    {
        $query = DB::table('stock_ins')
            ->join('lines', 'stock_ins.line_id', '=', 'lines.id')
            ->select(
                'stock_ins.gl_no',
                'stock_ins.color',
                DB::raw('COUNT(*) as total_bundle'),
                DB::raw('COALESCE(SUM(pcs), 0) as total_pcs'),
                DB::raw('MAX(stock_ins.updated_at) as last_updated'),
                DB::raw('GROUP_CONCAT(DISTINCT lines.name ORDER BY lines.name SEPARATOR ", ") as line_names')
            )
            ->groupBy('stock_ins.gl_no', 'stock_ins.color')
            ->orderBy('stock_ins.gl_no')
            ->orderBy('stock_ins.color');

        if (!empty($searchTerm)) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('stock_ins.gl_no', 'like', "%{$searchTerm}%")
                    ->orWhere('stock_ins.color', 'like', "%{$searchTerm}%")
                    ->orWhere('lines.name', 'like', "%{$searchTerm}%");
            });
        }

        // 📅 Filter berdasarkan tanggal (date range)
        if (!empty($startDate) && !empty($endDate)) {
            $query->whereBetween(DB::raw('DATE(stock_ins.updated_at)'), [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
        }



        $results = $query->get();

        // ✅ Kelompokkan per GL Number
        $grouped = $results->groupBy('gl_no')->map(function ($items, $glNo) {
            return [
                'gl_no' => $glNo,
                'last_updated' => Carbon::parse($items->max('last_updated'))->isoFormat('MMMM D, YYYY HH:mm'),
                'line_names' => $items->pluck('line_names')->unique()->implode(', '),
                'details' => $items->map(function ($item) {
                    return [
                        'color' => $item->color,
                        'total_bundle' => (int) $item->total_bundle,
                        'total_pcs' => (int) $item->total_pcs,
                        'line_names' => $item->line_names, // ✅ tambahkan line per warna
                    ];
                })->values(),
            ];
        })->values();

        return $grouped;
    }


    public function groupColorAndSizeBy($filters)
    {

        $query = Stockin::query();

        foreach ($filters as $column => $value) {
            if (is_array($value)) {
                $query->whereIn($column, $value);
            } else {
                $query->where($column, $value);
            }
        }
        return $query
            ->select('color', 'size', DB::raw('COALESCE(SUM(pcs),0) as total_qty'))
            ->groupBy('color', 'size')
            ->get()
            ->map(function ($item) {
                $item->total_qty = (int)$item->total_qty; // atau (float) jika butuh decimal
                return $item;
            });
    }




    public function findByLineId(int $lineId): ?Stockin
    {
        return $this->model::where('line_id', $lineId)->first();
    }

    public function findByLineIdAndDateRange(int $lineId, string $startDate, string $endDate)
    {
        return $this->model::where('line_id', $lineId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
    }

    public function findByLineIdAndDateRangeCount(int $lineId, string $startDate, string $endDate)
    {
        $result = $this->model::where('line_id', $lineId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('COUNT(*) as bundle, CAST(COALESCE(SUM(pcs), 0) AS UNSIGNED) as pcs, MAX(updated_at) as updated_at')
            ->first();

        return [
            'bundle' => $result->bundle ?? 0,
            'pcs' => $result->pcs ?? 0,
            'updated_at' => $result->updated_at ? $result->updated_at->diffForHumans() : null,
            'updated_at_full' => $result->updated_at ? $result->updated_at->isoFormat('dddd, D MMMM YYYY HH:mm') : null,
        ];
    }

    public function findBySerialNumber(string $serialNumber): ?Stockin
    {
        return $this->model::where('serial_number', $serialNumber)->first();
    }


    public function create(array $data): Stockin
    {

        return $this->model::create($data);
    }

    public function update(int $id, array $data): Stockin
    {
        $record = $this->model::findOrFail($id);
        $record->update($data);
        return $record;
    }

    public function delete(int $id): ?Stockin
    {
        $record = $this->model::find($id);

        if (!$record) {
            return null;
        }
        $deletedRecord = $record->replicate();
        $record->delete();

        return $deletedRecord;
    }

    public function paginateAll(array $filters)
    {
        $query = $this->model::query();

        $query->when(
            $filters['q'] ?? null,
            fn($q, $query) => $q->where('gl_no', 'LIKE', "%{$query}%")
                ->orWhere('serial_number', 'LIKE', "%{$query}%")
                ->orWhere('color', 'LIKE', "%{$query}%")
        );



        return $query;
    }

    protected function applyFilters($query, array $filters)
    {
        // Date range filter
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('created_at', [
                $filters['start_date'],
                $filters['end_date']
            ]);
        }

        // Other filters
        $filterableFields = ['user_id', 'gl_no', 'line_id'];
        foreach ($filterableFields as $field) {
            if (isset($filters[$field]) && $filters[$field] !== null) {
                $query->where($field, $filters[$field]);
            }
        }

        return $query;
    }


    /**
     * MATRIX
     * Get Matrix grouped stock data by GL Number .
     *
     * @param string|null $startDate
     * @param string $endDate
     * @param string $glNumber
     * @return LengthAwarePaginator
     */
    public function matrixDateByGLNumber($glNo, $startDate = null, $endDate = null)
    {

        $now = Carbon::now('Asia/Jakarta');

        // Get the last updated date for specific GL (or globally)
        $lastDateQuery = DB::table('stock_ins');
        if ($glNo) {
            $lastDateQuery->where('gl_no', $glNo);
        }

        $lastDate = $lastDateQuery->max('updated_at')
            ?? DB::table('stock_ins')->max('created_at')
            ?? $now->toDateTimeString();

        $lastDate = Carbon::parse($lastDate, 'Asia/Jakarta');
        $sevenDaysAgo = $lastDate->copy()->subDays(7);

        // ✅ If startDate & endDate are provided manually, skip auto-range logic
        if (!$startDate || !$endDate) {
            // Check if there’s data within the last 7 days
            $hasRecentData = DB::table('stock_ins')
                ->when($glNo, fn($q) => $q->where('gl_no', $glNo))
                ->whereBetween('updated_at', [
                    $now->copy()->subDays(7)->startOfDay(),
                    $now->copy()->endOfDay(),
                ])
                ->exists();

            if ($hasRecentData) {
                $startDate = $now->copy()->subDays(7)->startOfDay();
                $endDate = $now->copy()->endOfDay();
            } else {
                $startDate = $sevenDaysAgo->startOfDay();
                $endDate = $lastDate->copy()->endOfDay();
            }
        } else {
            // Convert provided strings to Carbon instances
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();
            $hasRecentData = true; // manual range, so treat as "has data"
        }

        // 🔍 Subquery defect per stockin_id
        $defectSub = DB::table('stock_in_defects')
            ->select('stockin_id', DB::raw('SUM(qty) as total_defect'))
            ->groupBy('stockin_id');

        // 🔍 Main Query
        $query = DB::table('stock_ins')
            ->leftJoinSub($defectSub, 'defects', 'stock_ins.id', '=', 'defects.stockin_id')
            ->join('lines', 'stock_ins.line_id', '=', 'lines.id')
            ->select(
                'stock_ins.gl_no',
                DB::raw('DATE(stock_ins.updated_at) as date'),
                'stock_ins.color',
                'stock_ins.size',
                DB::raw('COUNT(*) as total_bundle'),
                DB::raw('COALESCE(SUM(stock_ins.pcs - COALESCE(defects.total_defect, 0)), 0) as total_pcs'),
                DB::raw('COALESCE(SUM(defects.total_defect), 0) as total_defect'),
                DB::raw('GROUP_CONCAT(DISTINCT lines.name ORDER BY lines.name SEPARATOR ", ") as line_names'),
                DB::raw('COUNT(DISTINCT stock_ins.color) as total_colors')
            )
            ->when($glNo, fn($q) => $q->where('stock_ins.gl_no', $glNo))
            ->whereBetween('stock_ins.updated_at', [$startDate, $endDate])
            ->groupBy('stock_ins.gl_no', DB::raw('DATE(stock_ins.updated_at)'), 'stock_ins.color', 'stock_ins.size')
            ->orderBy('date', 'asc')
            ->get();

        // 📊 Summary
        $summary = collect($query)
            ->groupBy('gl_no')
            ->map(function ($itemsByGl) {
                return [
                    'gl_no' => $itemsByGl->first()->gl_no,
                    'sizes' => $itemsByGl
                        ->groupBy('size')
                        ->map(function ($itemsBySize) {
                            return [
                                'size' => $itemsBySize->first()->size,
                                'total_pcs' => $itemsBySize->sum('total_pcs'),
                                'total_bundle' => $itemsBySize->sum('total_bundle'),
                                'total_defect' => (int)$itemsBySize->sum('total_defect'),
                                'total_colors' => $itemsBySize->pluck('color')->unique()->count(),
                            ];
                        })
                        ->values(),
                    'colors' => $itemsByGl
                        ->groupBy('color')
                        ->map(function ($itemsByColor) {
                            $totalPcs = $itemsByColor->sum('total_pcs');
                            $totalBundle = $itemsByColor->sum('total_bundle');
                            $totalDefect = $itemsByColor->sum('total_defect');

                            return [
                                'color' => $itemsByColor->first()->color,
                                'total_pcs' => $totalPcs,
                                'total_bundle' => $totalBundle,
                                'total_defect' => $totalDefect,
                                'total_sizes' => $itemsByColor->pluck('size')->unique()->count(),
                                'sizes' => $itemsByColor->map(function ($item) {
                                    return [
                                        'size' => $item->size,
                                        'total_pcs' => (int)$item->total_pcs,
                                        'total_bundle' => (int)$item->total_bundle,
                                        'total_defect' => (int)$item->total_defect,
                                    ];
                                })->values(),
                            ];
                        })
                        ->values(),
                ];
            })
            ->values();

        // 📦 Final Return
        return [
            'gl_no' => $glNo,
            'hasRecentData' => $hasRecentData,
            'startDate' => Carbon::parse($startDate)->format('Y-m-d'),
            'endDate' => Carbon::parse($endDate)->format('Y-m-d'),
            'count' => $query->count(),
            'data' => $query,
            'summary' => $summary->first() ?? (object)[
                'gl_no' => $glNo,
                'sizes' => [],
                'colors' => [],
            ],
        ];
    }
}

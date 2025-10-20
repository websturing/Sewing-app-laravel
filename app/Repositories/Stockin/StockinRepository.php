<?php

namespace App\Repositories\Stockin;

use App\Models\Stockin;
use Illuminate\Database\Eloquent\Builder;
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

    public function groupByGlNumber($searchTerm)
    {
        $results = DB::table('stock_ins')
            ->join('lines', 'stock_ins.line_id', '=', 'lines.id')
            ->select(
                'stock_ins.gl_no',
                DB::raw('COUNT(*) as total_bundle'),
                DB::raw('SUM(pcs) as total_pcs'),
                DB::raw('MAX(stock_ins.updated_at) as last_updated'),
                DB::raw('GROUP_CONCAT(DISTINCT lines.name ORDER BY lines.name SEPARATOR ", ") as line_names')
            )
            ->groupBy('stock_ins.gl_no')
            ->orderBy('total_pcs', 'DESC')
            ->get()
            ->map(function ($result) {
                $result->last_updated = $result->last_updated
                    ? Carbon::parse($result->last_updated)->isoFormat('dddd, D MMMM YYYY HH:mm')
                    : null;

                return $result;
            });



        return $results;
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
}

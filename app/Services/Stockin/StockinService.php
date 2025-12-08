<?php

namespace App\Services\Stockin;

use App\Repositories\Stockin\StockinRepositoryInterface;
use Carbon\Carbon;

class StockinService implements StockinServiceInterface
{
    protected $stockinRepository;

    public function __construct(StockinRepositoryInterface $stockinRepository)
    {
        $this->stockinRepository = $stockinRepository;
    }

    public function getGroupBySizeAndColorBy(array $filters)
    {
        return $this->stockinRepository->groupColorAndSizeBy($filters);
    }

    public function getByLineId(int $lineId)
    {
        return $this->stockinRepository->findByLineId($lineId);
    }

    public function getByLineIdAndDateRange(int $lineId, string $startDate, string $endDate)
    {

        return $this->stockinRepository->findByLineIdAndDateRange($lineId, $startDate, $endDate);
    }

    public function getByLineIdAndDateRangeCount(int $lineId, string $startDate, string $endDate)
    {

        return $this->stockinRepository->findByLineIdAndDateRangeCount($lineId, $startDate, $endDate);
    }

    public function getByQuery(array $filter)
    {
        return $this->stockinRepository->query();
    }


    public function activity(?array $filters)
    {
        $query = $this->stockinRepository->query();
        $results = $query->get();

        return $results;
    }

    public function summary(array $filters)
    {

        $query = $this->stockinRepository->query()
            ->with(['line', 'user'])
            ->whereBetween('created_at', [
                Carbon::parse($filters['start_date'])->startOfDay(),
                Carbon::parse($filters['end_date'])->endOfDay()
            ]);

        $query->when(
            $filters['user_id'] ?? null,
            fn($q, $userId) => $q->where('user_id', $userId)
        );

        $query->when(
            $filters['line_id'] ?? null,
            fn($q, $lineId) => is_array($lineId)
                ? $q->whereIn('line_id', $lineId)
                : $q->where('line_id', $lineId)
        );

        // Tambahkan order by updated_at
        $query->orderBy('updated_at', 'desc');

        $results = $query->get();

        $summaryDetails = $results->groupBy('gl_no')->map(function ($group, $glNo) {
            return [
                'gl_number' => $glNo,
                'color_count' => $group->pluck('color')->unique()->count(),
                'size_count' => $group->pluck('size')->unique()->count(),
                'total_items' => $group->count(),
                'total_pcs' => $group->sum('pcs'),
                'colors' => $group->pluck('color')->unique()->values(),
                'sizes' => $group->pluck('size')->unique()->values(),
                'last_updated' => $group->sortByDesc('updated_at')->first()->updated_at?->format('F d, Y') ?? null
            ];
        })->values();

        // Urutkan summaryDetails berdasarkan last_updated (descending)
        $summaryDetails = $summaryDetails->sortByDesc(function ($item) {
            return Carbon::parse($item['last_updated'])->timestamp;
        })->values();

        $summary = [
            'gl_numbers' => $results->pluck('gl_no')->unique()->values(),
            'sizes' => $results->pluck('size')->unique()->values(),
            'cut_pieces' => $results->sum('pcs'),
            'users' => $results->map(function ($item) {
                return $item->user->name ?? 'System';
            })->unique()->values(),
            'details' => $summaryDetails
        ];

        return $summary;
    }

    public function lastStockInTicketByLine(int $lineId)
    {
        $query = $this->stockinRepository->query();

        $result = $query->orderBy('id', 'DESC')
            ->where('line_id', $lineId)
            ->first();

        return $result;
    }

    public function getAllStockin(array $params)
    {

        $defaultParams = [
            'sorts' => ['created_at' => 'desc'],
            'per_page' => 15,
            'page' => 1,
            'with_relations' => true,
            'relations' => ['user', 'line']
        ];

        $params = array_merge($defaultParams, $params);
        return $this->stockinRepository->all($params);
    }

    public function activityGroupByGL(array $params)
    {

        $params['no_pagination'] = true;
        $query = $this->getAllStockin($params);
        $results = $query->groupBy('gl_no')->map(
            function ($group, $glNo) {
                return [
                    "gl_number" => $glNo,
                    "ticket" => $group->first()
                ];
            }
        )->values();

        return $results;
    }

    public function getBySerialNumber(string $serialNumber)
    {
        return $this->stockinRepository->findBySerialNumber($serialNumber);
    }

    public function create(array $data)
    {
        try {
            return $this->stockinRepository->create($data);
        } catch (\Exception $e) {
            \Log::error('StockIn creation failed: ' . $e->getMessage(), [
                'data' => $data,
                'exception' => $e
            ]);
            throw $e; // Re-throw ke controller
        }
    }

    public function update(int $id, array $data)
    {
        return $this->stockinRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->stockinRepository->delete($id);
    }

    public function getPaginate(array $filters)
    {
        return $this->stockinRepository->paginateAll($filters)
            ->with('line')
            ->orderBy('created_at', 'DESC')
            ->paginate($filters['per_page'] ?? 100);
    }


    /**
     * Get tickets with optional pagination
     */

    public function getTickets(array $filters)
    {


        $query = $this->stockinRepository->query()
            ->orderBy('created_at', 'DESC');

        // Filter dengan approach yang lebih clean
        $query->when(isset($filters['line_id']), function ($q) use ($filters) {
            return $q->where('line_id', $filters['line_id']);
        });

        $query->when(isset($filters['limit']), function ($q) use ($filters) {
            return $q->limit($filters['limit']);
        });



        if (!empty($filters['is_paginate']) && $filters['is_paginate']) {
            $perPage = $filters['per_page'] ?? 15;
            return $query->paginate($perPage);
        } else {
            return $query->get();
        }
    }

    /** GROUP BY
     * 
     *  Get paginated list of GL Numbers grouped from StockIns.
     * @param  $search, $perPage, $sortBy, $sortOrder  $filters
     * 
     */
    public function getGroupByGlNumber($search, $perPage, $sortBy, $sortOrder, $page)
    {
        return $this->stockinRepository->groupByGlNumber($search, $perPage, $sortBy, $sortOrder, $page);
    }
}

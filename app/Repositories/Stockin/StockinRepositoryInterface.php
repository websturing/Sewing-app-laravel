<?php

namespace App\Repositories\Stockin;

use App\Models\Stockin;
use Illuminate\Database\Eloquent\Builder;

interface StockinRepositoryInterface
{
    public function all(array $params);
    public function query(): Builder;

    public function groupByGlNumber(string $searchTerm);
    public function groupByGlNumberColor(string $searchTerm, $startDate, $endDate);
    public function groupColorAndSizeBy(array $filters);


    public function findBySerialNumber(string $serialNumber): ?Stockin;
    public function findByLineId(int $lineId): ?Stockin;
    public function findByLineIdAndDateRange(int $lineId, string $startDate, string $endDate);
    public function findByLineIdAndDateRangeCount(int $lineId, string $startDate, string $endDate);

    public function paginateAll(array $filters);
    public function create(array $data): Stockin;
    public function update(int $id, array $data): Stockin;
    public function delete(int $id): ?Stockin;
}

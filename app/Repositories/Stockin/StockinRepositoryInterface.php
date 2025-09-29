<?php

namespace App\Repositories\Stockin;

use App\Models\Stockin;
use Illuminate\Database\Eloquent\Builder;

interface StockinRepositoryInterface
{
    public function all(array $params);
    public function query(): Builder;
    public function findBySerialNumber(string $serialNumber): ?Stockin;
    public function paginateAll(array $filters);
    public function create(array $data): Stockin;
    public function update(int $id, array $data): Stockin;
    public function delete(int $id): ?Stockin;
}

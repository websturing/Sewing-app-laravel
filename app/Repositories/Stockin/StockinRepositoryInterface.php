<?php

namespace App\Repositories\Stockin;

use App\Models\Stockin;

interface StockinRepositoryInterface
{
    public function all();
    public function paginateAll(array $filters);
    public function create(array $data): Stockin;
    public function update(int $id, array $data): Stockin;
    public function delete(int $id): ?Stockin;
}

<?php

namespace App\Repositories\Stockin;

interface StockinRepositoryInterface
{
    public function all();
    public function paginateAll(array $filters);
}

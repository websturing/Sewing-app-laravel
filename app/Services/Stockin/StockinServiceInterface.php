<?php

namespace App\Services\Stockin;

interface StockinServiceInterface
{
    public function getAllStockin();
    public function getPaginate(array $filters);
    public function create(array $data);
}

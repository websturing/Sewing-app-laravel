<?php

namespace App\Services\Stockin;

interface StockinServiceInterface
{
    public function getAllStockin();
    public function getPaginate(array $filters);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}

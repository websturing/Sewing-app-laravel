<?php

namespace App\Services\Stockin;

interface StockInSummaryServiceInterface
{
    public function chart(array $filters);

    public function groupByGlNumber(string $searchTerm);
}

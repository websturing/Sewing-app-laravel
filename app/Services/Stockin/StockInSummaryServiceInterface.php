<?php

namespace App\Services\Stockin;

use App\DTOs\StockInSummaryReportDTO;

interface StockInSummaryServiceInterface
{
    public function chart(array $filters);

    public function groupByGlNumber(string $searchTerm);
    public function groupByGlNumberColor(string $searchTerm, $startDate, $endDate);

    public function reportByGlNumber(StockInSummaryReportDTO $data);
}

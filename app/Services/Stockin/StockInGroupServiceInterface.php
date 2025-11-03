<?php

namespace App\Services\Stockin;

use App\Dto\StockInSummaryReportDTO;

interface StockInGroupServiceInterface
{

    /**
     * Get Matrix grouped stock data by GL Number .
     *
     * @param string|null $startDate
     * @param string $endDate
     * @param string $glNumber
     * @return LengthAwarePaginator
     */

    public function getMatrixDate(
        $glNumber,
        $startDate = null,
        $endDate = null
    );
}

<?php

namespace App\Services\Stockin;

use App\Dto\StockInSummaryReportDTO;
use App\Models\Stockin;
use App\Repositories\Stockin\StockinRepository;
use App\Repositories\Stockin\StockinRepositoryInterface;
use App\Services\CuttingGlnumber\CuttingGlnumberServiceInterface;
use App\Services\Line\LineServiceInterface;
use Carbon\Carbon;


class StockInGroupService implements StockInGroupServiceInterface
{

    protected $stockInRepository;

    public function __construct(
        StockinRepository $StockinRepository,
    ) {
        $this->stockInRepository = $StockinRepository;
    }

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
    ) {



        return $this->stockInRepository->matrixDateByGLNumber(
            $glNumber,
            $startDate,
            $endDate
        );
    }
}

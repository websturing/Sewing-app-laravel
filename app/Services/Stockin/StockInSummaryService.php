<?php

namespace App\Services\Stockin;

use App\Dto\StockInSummaryReportDTO;
use App\Models\Stockin;
use App\Repositories\Stockin\StockinRepositoryInterface;
use App\Services\CuttingGlnumber\CuttingGlnumberServiceInterface;
use App\Services\Line\LineServiceInterface;
use Carbon\Carbon;

class StockInSummaryService implements StockInSummaryServiceInterface
{
    protected $lineService;
    protected $stockinService;
    protected $cuttingGlSummaryRepository;
    protected $stockinRepository;

    public function __construct(
        LineServiceInterface $lineService,
        StockinServiceInterface $stockinService,
        StockinRepositoryInterface $stockinRepository,
        CuttingGlnumberServiceInterface $cuttingGlSummaryRepository
    ) {
        $this->lineService = $lineService;
        $this->stockinService = $stockinService;
        $this->stockinRepository = $stockinRepository;
        $this->cuttingGlSummaryRepository = $cuttingGlSummaryRepository;
    }

    public function chart(array $filters)
    {

        $lines = $this->lineService->getLines();


        foreach ($lines as $line) {
            $count = $this->stockinService->getByLineIdAndDateRangeCount(
                $line->id,
                Carbon::parse($filters['start_date'])->startOfDay(),
                Carbon::parse($filters['end_date'])->endOfDay()
            );
            $line->stockin_count = $count;
        }

        return $lines;
    }

    public function groupByGlNumber(string $searchTerm)
    {
        return $this->stockinRepository->groupByGlNumber($searchTerm);
    }

    public function groupByGlNumberColor(string $searchTerm, $startDate, $endDate)
    {
        return $this->stockinRepository->groupByGlNumberColor($searchTerm, $startDate, $endDate);
    }

    /** REPORT  */
    public function reportByGlNumber(StockInSummaryReportDTO $dto)
    {

        $filters = $dto->toFilters();

        return $cuttings = $this->cuttingGlSummaryRepository->findBy($filters);
    }
}

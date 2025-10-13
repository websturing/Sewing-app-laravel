<?php

namespace App\Services\Stockin;

use App\Repositories\Stockin\StockinRepositoryInterface;
use App\Services\Line\LineServiceInterface;
use Carbon\Carbon;

class StockInSummaryService implements StockInSummaryServiceInterface
{
    protected $lineService;
    protected $stockinService;

    public function __construct(
        LineServiceInterface $lineService,
        StockinServiceInterface $stockinService
    ) {
        $this->lineService = $lineService;
        $this->stockinService = $stockinService;
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
}

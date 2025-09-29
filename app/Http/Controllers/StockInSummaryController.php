<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Stockin\StockinServiceInterface;
use Carbon\Carbon;

class StockInSummaryController extends Controller
{
    public function __construct(
        private StockinServiceInterface $stockInService,
    ) {}

    public function summary(Request $request)
    {

        $filters = $request->get('filters');
        $filters['start_date'] = $filters['start_date'] ?? Carbon::today()->format('Y-m-d');
        $filters['end_date'] = $filters['end_date'] ?? Carbon::today()->format('Y-m-d');

        return $this->stockInService->summary($filters);
    }
}

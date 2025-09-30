<?php

namespace App\Http\Controllers;

use App\Http\Resources\StockInSummaryResource;
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

        $items =  $this->stockInService->summary($filters);

        return StockInSummaryResource::make($items)->additional([
            'status' => true,
            'message' => 'Succesfully Retrieved Stock-in Summary'
        ]);
    }
}

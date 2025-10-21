<?php

namespace App\Http\Controllers;

use App\Http\Resources\StockInSummaryGroupGlNumberResource;
use App\Http\Resources\StockInSummaryResource;
use Illuminate\Http\Request;
use App\Services\Stockin\StockinServiceInterface;
use App\Services\Stockin\StockInSummaryServiceInterface;
use Carbon\Carbon;

class StockInSummaryController extends Controller
{
    public function __construct(
        private StockinServiceInterface $stockInService,
        private StockInSummaryServiceInterface $stockInSummaryService,
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

    public function stockInChart(Request $request)
    {
        $filters = $request->get('filters');
        $filters['start_date'] = $filters['start_date'] ?? Carbon::today()->format('Y-m-d');
        $filters['end_date'] = $filters['end_date'] ?? Carbon::today()->format('Y-m-d');

        $charts = $this->stockInSummaryService->chart($filters);

        return response()->json([
            'status' => true,
            'message' => 'Succesfully Retrieved Stock-in Chart Data',
            'data' => $charts
        ]);
    }

    /**
     * STOCK IN BY GL NUMBER
     */
    public function stockInByGlNumber(Request $request)
    {

        $results = $this->stockInSummaryService->groupByGlNumber($request->get('search', ''));


        return StockInSummaryGroupGlNumberResource::make($results)->additional([
            'status' => true,
            'message' => 'Successfully Retrieved Stock-in by GL Number'
        ]);
    }
}

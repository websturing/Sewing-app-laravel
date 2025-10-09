<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockInTicketRequest;
use Illuminate\Http\Request;
use App\Services\Stockin\StockinServiceInterface;
use App\Services\Cutting\CuttingIntegrationServiceInterface;

class StockInTicketController extends Controller
{
    public function __construct(
        private StockinServiceInterface $stockInService,
        private CuttingIntegrationServiceInterface $cuttingIntegration,
    ) {}

    public function index(StockInTicketRequest $request)
    {
        return $tickets = $this->stockInService->getTickets($request->validated());
    }
}

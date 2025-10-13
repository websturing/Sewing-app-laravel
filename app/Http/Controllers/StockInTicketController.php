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

        return response()->json([
            'status' => true,
            'message' => 'Successfully Retrieved Tickets',
            'data' => $tickets = $this->stockInService->getTickets($request->validated()),
        ]);
    }

    public function ticketByserialNumber($serialNumber)
    {
        $ticket = $this->stockInService->getBySerialNumber($serialNumber);
        if (!$ticket) {
            return errorResponse('Ticket Not Found', 404);
        }

        return successResponse($ticket, 'Successfully Retrieved Ticket');
    }
}

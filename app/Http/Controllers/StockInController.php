<?php

namespace App\Http\Controllers;

use App\Http\Requests\QueryFilterRequest;
use App\Http\Requests\StockInByTicketNumberRequest;
use App\Http\Requests\StockInRequest;
use App\Http\Resources\StockInResource;
use App\Services\Stockin\StockinServiceInterface;
use App\Services\Cutting\CuttingIntegrationServiceInterface;
use Auth;
use Illuminate\Http\Request;

class StockInController extends Controller
{
    public function __construct(
        private StockinServiceInterface $stockIn,
        private CuttingIntegrationServiceInterface $cuttingIntegration,
    ) {}

    public function index()
    {

        $items = $this->stockIn
            ->getPaginate(
                request()->all()
            );

        if (!$items) {
            return errorResponse('List Line Not Found', 404);
        }

        return StockInResource::collection($items)->additional([
            'status' => true,
            'message' => 'Succesfully Retrieved StockIns'
        ]);
    }

    public function activity(QueryFilterRequest $request)
    {

        try {
            $params = [
                'filters' => $request->getFilters(),
                'sorts' => $request->getSorts(),
                'per_page' => $request->getPerPage(),
                'page' => $request->getPage(),
                'with_relations' => $request->getWithRelations(),
                'relations' => $request->getRelations()
            ];

            return $results = $this->stockIn->activityGroupByGL($params);

            return response()->json([
                'success' => true,
                'data' => $results->items(),
                'meta' => [
                    'current_page' => $results->currentPage(),
                    'per_page' => $results->perPage(),
                    'total' => $results->total(),
                    'last_page' => $results->lastPage()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch activity data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(StockInRequest $request)
    {
        $stockInCreate = $this->stockIn->create($request->validated());
        return StockInResource::make($stockInCreate)->additional([
            'status' => true,
            'message' => 'Successfully  Create Stock-in'
        ]);
    }

    public function storeByTicketNumber(StockInByTicketNumberRequest $request)
    {
        $validated = $request->validated();
        $ticketNumber = $validated['ticket_number'];

        $ticketResponse =  $this->cuttingIntegration->bundleByTicket($ticketNumber);
        $ticket = $ticketResponse['data'];

        $dataRequest = [
            "serial_number" => $ticket['serial_number'],
            "ticket_no" => $ticket['ticket_no'],
            "gl_no" => $ticket['gl_no'],
            "size" => $ticket['size'],
            "user_dispatch_id" =>  $ticket['user_id'] ?? 0,
            "color" =>  $ticket['color'],
            "pcs" =>  $ticket['pcs'],
            "date_stock_out" =>  $ticket['date_stock_out'],
            "cor_id" =>  $ticket['cor_id'],
            "user_id" =>  Auth::id(),
            "user_dispatch_name" =>  $ticket['user_name'] ?? '-',
            "box_number" =>  $ticket['box_number'] ?? '-',
            "line_id" => $validated['line_id'],
            "input_source" => "mobile_app",
            "container_scan_status" => "waiting"
        ];
        $stockInCreate = $this->stockIn->create($dataRequest);
        return StockInResource::make($stockInCreate)->additional([
            'status' => true,
            'message' => 'Successfully  Update Stock-in'
        ]);
    }

    public function update(StockInRequest $request, $stockInId)
    {

        $stockInUpdate = $this->stockIn->update($stockInId, $request->validated());
        return StockInResource::make($stockInUpdate)->additional([
            'status' => true,
            'message' => 'Successfully  Update Stock-in'
        ]);
    }

    public function delete($stockInId)
    {
        $stockInDelete = $this->stockIn->delete($stockInId);
        return StockInResource::make($stockInDelete)->additional([
            'status' => true,
            'message' => 'Successfully  Delete Stock-in With Serial Number ' . $stockInDelete['serial_number']
        ]);
    }
}

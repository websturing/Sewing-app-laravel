<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplacementFiltersRequest;
use App\Http\Resources\ReplacementPaginationResource;
use App\Services\Replacement\ReplacementServiceInterface;
use Illuminate\Http\Request;

class ReplacementRequestController extends Controller
{
    public function __construct(
        private ReplacementServiceInterface $replacementService,
    ) {}

    public function index(Request $request)
    {
        try {
            $result = $this->replacementService->getReplacementList();

            return response()->json([
                'status' => true,
                'data' => $result,
                'message' => 'Successfully Retrived Ticket Replacements '
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to Get Ticket Replacement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getReplacementListWithPagination(ReplacementFiltersRequest $request)
    {
        try {
            $results = $this->replacementService->getReplacementListWithPagination($request->validated());

            return ReplacementPaginationResource::collection($results)->additional([
                'status' => true,
                'message' => "Successfully Retrived Ticket Replacements"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to Get Ticket Replacement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function createTicketReplacement(Request $request)
    {
        try {
            $result = $this->replacementService->createReplacementRequest($request->get('data'));

            return response()->json([
                'status' => true,
                'data' => $result,
                'message' => 'Create Ticket Replacement Successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to Create Ticket Replacement',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\Replacement\ReplacementServiceInterface;
use Illuminate\Http\Request;

class ReplacementRequestController extends Controller
{
    public function __construct(
        private ReplacementServiceInterface $replacementService,
    ) {}

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

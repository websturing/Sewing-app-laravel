<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplacementApprovalDBRequest;
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

    public function getApprovalListPagination(ReplacementFiltersRequest $request)
    { {

            try {
                $results = $this->replacementService->getApprovalWithPagination($request->validated());

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
    }


    public function getReplacementGroupGlNumber(Request $request)
    {
        return $this->replacementService->getDefectByGLNumber($request->get('gl_number'));
    }

    public function getReplacementHistoriesByReplacementId($replacementRequestId)
    {
        try {
            $result = $this->replacementService->getHistoriesByReplacementId(
                $replacementRequestId
            );

            return response()->json([
                'status' => true,
                'data' => $result,
                'message' => 'Retrived Histories Replacement Successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to Retrived Histories Replacement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getTicketTrackingBySerial($serialNumber)
    {
        return $this->replacementService->getTicketTrackingBySerial($serialNumber);
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

    public function createApprovalTicketReplacement(ReplacementApprovalDBRequest $request)
    {
        try {
            $requestValidated = $request->validated();
            $note = $requestValidated['note'] ?? null;
            $action = $requestValidated['action'];
            $replacementRequestId = $requestValidated['replacement_request_id'];
            $results = $this->replacementService->createApprovalByRole($replacementRequestId, $action, $note);

            return  response()->json([
                'status' => true,
                'message' => "Successfully Created Approval Replacements",
                'data' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to Create Approval Replacment',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

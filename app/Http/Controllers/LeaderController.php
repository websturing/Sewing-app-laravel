<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignLeaderRequest;
use App\Http\Requests\UnassignLeaderRequest;
use App\Services\Leaders\LeadersServiceInterface;
use Illuminate\Http\Request;

class LeaderController extends Controller
{

    public function __construct(
        private LeadersServiceInterface $leaderService,
    ) {}

    public function createAssign(AssignLeaderRequest $request)
    {
        try {
            $result = $this->leaderService->createAssign($request->validated());

            return response()->json([
                'status' => true,
                'data' => $result,
                'message' => 'Leader assigned successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to assign leader',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function createUnassign(UnassignLeaderRequest $request)
    {
        try {
            $result = $this->leaderService->createUnassign($request->validated());

            return response()->json([
                'status' => true,
                'data' => $result,
                'message' => 'Leader unassigned successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to unassign leader',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAssignmentByUser()
    {
        try {
            $result = $this->leaderService->getAssignmentSummaryByLeader();

            return response()->json([
                'status' => true,
                'data' => $result,
                'message' => 'Leader unassigned successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to unassign leader',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

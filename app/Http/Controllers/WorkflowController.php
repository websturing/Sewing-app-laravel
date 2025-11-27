<?php

namespace App\Http\Controllers;

use App\Services\Workflow\WorkflowServiceInterface;
use Illuminate\Http\Request;

class WorkflowController extends Controller
{
    public function __construct(
        private WorkflowServiceInterface $workflowService,
    ) {}

    public function getWorkflowByStep(int $stepNumber)
    {
        try {
            $result = $this->workflowService->getWorkflowByStep($stepNumber);

            return response()->json([
                'status' => true,
                'data' => $result,
                'message' => 'Workflow Retrived successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to Workflow',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

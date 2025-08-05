<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserShiftAssignmentRequest;
use App\Http\Resources\ShiftUserAssignmentAllResource;
use App\Services\Shiftassignment\ShiftassignmentServiceInterface;
use Illuminate\Http\Request;

class UserShiftAssignmentController extends Controller
{

    public function __construct(
        private ShiftassignmentServiceInterface $userShiftAssignment
    ) {
    }


    public function index()
    {
        $result = $this->userShiftAssignment->getAllShiftassignment();
        $assignments = ShiftUserAssignmentAllResource::collection($result);
        return successResponse($assignments);
    }

    public function summaryAssigment()
    {
        $result = $this->userShiftAssignment->getSummaryShift();
        return successResponse($result, 'Successfully Received User Assignment Summary');
    }

    public function createUserShiftAssignment(UserShiftAssignmentRequest $request)
    {
        $result = $this->userShiftAssignment->createShiftAssignment($request->validated());
        // $shift = new ShiftResources($result);
        return successResponse($result, 'Succesfully Created User Assignment');
    }

    public function deleteUserShiftAssignment($id)
    {
        $deletedShift = $this->userShiftAssignment->deleteShiftAssignment($id);
        if (!$deletedShift) {
            return errorResponse('Shift Assignment not found', 404);
        }

        return successResponse(
            new ShiftUserAssignmentAllResource($deletedShift),
            "Successfully deleted"
        );
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserShiftAssignmentRequest;
use App\Services\Shiftassignment\ShiftassignmentServiceInterface;
use Illuminate\Http\Request;

class UserShiftAssignmentController extends Controller
{

    public function __construct(
        private ShiftassignmentServiceInterface $userShiftAssignment
    ) {}


    function index() {}

    function summaryAssigment()
    {
        $result = $this->userShiftAssignment->getSummaryShift();
        return successResponse($result, 'Successfully Received User Assignment Summary');
    }

    function createUserShiftAssignment(UserShiftAssignmentRequest $request)
    {
        $result = $this->userShiftAssignment->createShiftAssignment($request->validated());
        // $shift = new ShiftResources($result);
        return successResponse($result, 'Succesfully Created User Assignment');
    }
}

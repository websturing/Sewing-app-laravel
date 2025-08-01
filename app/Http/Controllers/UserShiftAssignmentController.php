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
        return $this->userShiftAssignment->getSummaryShift();
    }

    function createUserShiftAssignment(UserShiftAssignmentRequest $request)
    {
        $result = $this->userShiftAssignment->createShiftAssignment($request->validated());
        // $shift = new ShiftResources($result);
        return successResponse($result, 'Succesfully Created User Assignment');
    }
}

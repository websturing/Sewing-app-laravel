<?php

namespace App\Http\Controllers;

use App\Services\Shiftassignment\ShiftassignmentServiceInterface;
use Illuminate\Http\Request;

class UserShiftAssignmentController extends Controller
{

    public function __construct(
        private ShiftassignmentServiceInterface $userShiftAssignment
    ) {}

    function summaryAssigment()
    {
        return $this->userShiftAssignment->getSummaryShift();
    }
}

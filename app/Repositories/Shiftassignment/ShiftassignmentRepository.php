<?php

namespace App\Repositories\Shiftassignment;

use App\Models\UserShiftAssignment;

class ShiftassignmentRepository implements ShiftassignmentRepositoryInterface
{
    public function all()
    {
        return UserShiftAssignment::all();
    }

    public function withShiftUser()
    {
        return UserShiftAssignment::with(['user', 'shift'])->get();
    }
}

<?php

namespace App\Repositories\Shiftassignment;

use App\Models\UserShiftAssignment;

class ShiftassignmentRepository implements ShiftassignmentRepositoryInterface
{
    public function all()
    {
        return UserShiftAssignment::with(['user', 'shift'])
            ->get();
    }

    public function withShiftUser()
    {
        return UserShiftAssignment::with(['user', 'shift'])->get();
    }

    public function create(array $data)
    {
        return UserShiftAssignment::create($data);
    }

    public function delete(int $id)
    {
        $data = UserShiftAssignment::find($id);

        if (!$data) {
            return null;
        }

        $deletedData = clone $data;

        $data->delete();
        return $deletedData;
    }
}

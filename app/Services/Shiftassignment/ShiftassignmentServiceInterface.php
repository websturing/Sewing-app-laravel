<?php

namespace App\Services\Shiftassignment;

interface ShiftassignmentServiceInterface
{
    public function getAllShiftassignment();
    public function getSummaryShift();

    public function createShiftAssignment(array $shiftAssignmentData);
    public function deleteShiftAssignment(int $shiftAssignmentId);
}

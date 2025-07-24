<?php

namespace App\Services\Shift;

interface ShiftServiceInterface
{
    public function getAllShift();
    public function createShift(array $shiftData);
    public function updateShift(int $shiftId, array $shiftData);
    public function deleteShift(int $shiftId);
}

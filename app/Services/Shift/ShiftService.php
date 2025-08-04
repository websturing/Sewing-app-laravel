<?php

namespace App\Services\Shift;

use App\Services\Shift\ShiftServiceInterface;
use App\Repositories\Shift\ShiftRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ShiftService implements ShiftServiceInterface
{
    protected $shiftRepository;

    public function __construct(ShiftRepositoryInterface $shiftRepository)
    {
        $this->shiftRepository = $shiftRepository;
    }

    public function getAllShift()
    {
        return $this->shiftRepository->all();
    }

    public function getShiftWithAssignments(){
        return $this->shiftRepository->withAssignments();
    }

    public function createShift(array $shiftData)
    {

        return $this->shiftRepository->create($shiftData);
    }

    public function updateShift(int $shiftId, array $shiftData)
    {

        return $this->shiftRepository->update($shiftId, $shiftData);
    }

    public function deleteShift(int $shiftId)
    {
        return $this->shiftRepository->delete($shiftId);
    }
}

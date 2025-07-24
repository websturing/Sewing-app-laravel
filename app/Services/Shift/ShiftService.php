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

    public function createShift(array $shiftData)
    {

        // Validasi di Service Layer
        $validator = Validator::make($shiftData, [
            'name' => 'required|string|max:255|unique:roles,name',
            'guard_name' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->shiftRepository->create($shiftData);
    }

    public function updateShift(int $shiftId, array $shiftData)
    {

        // Validasi di Service Layer
        $validator = Validator::make($shiftData, [
            'name' => 'required|string|max:255|unique:roles,name',
            'guard_name' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->shiftRepository->update($shiftId, $shiftData);
    }

    public function deleteShift(int $shiftId)
    {
        return $this->shiftRepository->delete($shiftId);
    }
}

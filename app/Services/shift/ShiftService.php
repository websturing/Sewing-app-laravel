<?php

namespace App\Services\Shift;

use App\Services\Shift\ShiftServiceInterface;
use App\Repositories\Shift\ShiftRepositoryInterface;

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
}

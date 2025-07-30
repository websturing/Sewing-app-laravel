<?php

namespace App\Services\Shiftassignment;

use App\Services\Shiftassignment\ShiftassignmentServiceInterface;
use App\Repositories\Shiftassignment\ShiftassignmentRepositoryInterface;
use App\Services\Employee\EmployeeServiceInterface;

class ShiftassignmentService implements ShiftassignmentServiceInterface
{
    protected $shiftassignmentRepository;
    protected $employeeService;

    public function __construct(
        ShiftassignmentRepositoryInterface $shiftassignmentRepository,
        EmployeeServiceInterface $employeeService

    ) {
        $this->shiftassignmentRepository = $shiftassignmentRepository;
        $this->employeeService = $employeeService;
    }

    public function getAllShiftassignment()
    {
        return $this->shiftassignmentRepository->all();
    }

    public function getSummaryShift()
    {
        $employeeCount = $this->employeeService->getAllEmployee()->count();
        $assignments = $this->shiftassignmentRepository->withShiftUser();
        $unassigned = abs($assignments->count() - $employeeCount);

        return [
            "employee_count" => $employeeCount,
            "assignments" => $assignments,
            "unassigned" => $unassigned,
        ];
    }
}

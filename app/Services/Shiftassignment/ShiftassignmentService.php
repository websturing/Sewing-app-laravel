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
            "assignments" => $assignments->count(),
            "unassigned" => $unassigned,
        ];
    }

    /** Manipulate Database For Shift User Assignment
     * Create
     * Delete
     */

    public function createShiftAssignment(array $shiftAssignmentData)
    {
        foreach ($shiftAssignmentData['employee_selected_data'] as $item) {
            $result[] = $this->shiftassignmentRepository->create([
                "user_id"   => $item['id'],
                "shift_id"  => $shiftAssignmentData['shift_selected']['id'],
                'effective_date_start' => $shiftAssignmentData['effective_date']
            ]);
        }

        return $result;
    }
    public function deleteShiftAssignment(int $id)
    {
        return $this->shiftassignmentRepository->delete($id);
    }
}

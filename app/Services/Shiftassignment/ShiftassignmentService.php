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
        $assignmentGroupByShift =  $assignments->groupBy('shift.id')->map(function ($items) {
            return [
                'shift_id' => $items->first()->shift->id,
                'shift_name' => $items->first()->shift->name,
                'start_time' => $items->first()->shift->start_time,
                'end_time' => $items->first()->shift->end_time,
                'user_count' => $items->count(),
                'users' => $items->map(function ($item) {
                    return [
                        'user_id' => $item->user->id,
                        'user_name' => $item->user->name
                    ];
                })
            ];
        })->values();
        $unassigned = abs($assignments->count() - $employeeCount);

        return [
            "employee_count" => $employeeCount,
            "assignments" => $assignmentGroupByShift,
            "assigned" => $assignments->count(),
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

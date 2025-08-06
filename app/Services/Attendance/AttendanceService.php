<?php

namespace App\Services\Attendance;

use App\Services\Attendance\AttendanceServiceInterface;
use App\Repositories\Attendance\AttendanceRepositoryInterface;
use App\Models\Attendance;
use Carbon\Carbon;
class AttendanceService implements AttendanceServiceInterface
{
    protected $attendanceRepository;
    protected $statusOptions = ['present', 'late', 'absent', 'on_leave', 'wfh'];

    public function __construct(AttendanceRepositoryInterface $attendanceRepository)
    {
        $this->attendanceRepository = $attendanceRepository;
    }

    public function getAllAttendance()
    {
        return $this->attendanceRepository->all();
    }

    public function getAttendanceGroupDate()
    {
        $attendances = $this->attendanceRepository->all();
        $grouped = $attendances->groupBy('attendance_date');

        return $grouped;
    }

    public function getAttendanceToday()
    {
        $attendances = $this->attendanceRepository->today();
        $statusOptions = $this->statusOptions;

        // Group attendances once by status
        $groupedByStatus = $attendances->groupBy('status');

        $result = collect($statusOptions)->mapWithKeys(function ($status) use ($groupedByStatus) {
            $items = $groupedByStatus->get($status, collect());

            return [
                $status => [
                    'count' => $items->count(),
                    'items' => $items->values()
                ]
            ];
        });

        return [
            'summary' => $result->map(fn($data) => $data['count']),
            'items' => $attendances
        ];
    }
}

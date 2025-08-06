<?php

namespace App\Services\Attendance;

use App\Services\Attendance\AttendanceServiceInterface;
use App\Repositories\Attendance\AttendanceRepositoryInterface;

class AttendanceService implements AttendanceServiceInterface
{
    protected $attendanceRepository;

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

        return $attendances;
    }
}

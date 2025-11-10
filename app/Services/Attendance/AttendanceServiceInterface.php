<?php

namespace App\Services\Attendance;

interface AttendanceServiceInterface
{
    public function getAllAttendance();
    public function getAttendanceByRangeDate(string $startDate, string $endDate);
    public function getAttendanceGroupDate();
    public function getAttendanceToday();
    public function getAttendanceShiftSummary();

    public function getAttendanceByLineId(int $lineId);
}

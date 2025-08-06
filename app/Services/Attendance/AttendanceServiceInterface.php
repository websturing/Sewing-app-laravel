<?php

namespace App\Services\Attendance;

interface AttendanceServiceInterface
{
    public function getAllAttendance();
    public function getAttendanceGroupDate();
    public function getAttendanceToday();
}

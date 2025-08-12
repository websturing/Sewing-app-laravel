<?php

namespace App\Repositories\Attendance;

interface AttendanceRepositoryInterface
{
    public function all();
    public function today();
    public function byRangeDate(string $startDate, string $endDate);
}

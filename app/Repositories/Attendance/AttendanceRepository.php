<?php

namespace App\Repositories\Attendance;

use App\Models\Attendance;
use App\Models\AttendanceLog;
use Carbon\Carbon;

class AttendanceRepository implements AttendanceRepositoryInterface
{
    public function all()
    {
        return Attendance::with(['logs', 'user'])
            ->withCount('logs')
            ->get();
    }

    public function byRangeDate(string $startDate, string $endDate)
    {
        return Attendance::with(['logs', 'user'])
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->withCount('logs')
            ->get();
    }

    public function today()
    {
        return Attendance::with(['logs', 'user', 'user.shiftAssignments.shift'])
            ->whereDate('attendance_date', Carbon::today())
            ->withCount('logs')
            ->get();
    }

    public function byLineId(int $lineId)
    {
        return AttendanceLog::GroupByLineToday($lineId)
            ->get();
    }
}

<?php

namespace App\Repositories\Attendance;

use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceRepository implements AttendanceRepositoryInterface
{
    public function all()
    {
        return Attendance::with(['logs', 'user'])
            ->withCount('logs')
            ->get();
    }

    public function today()
    {
        return Attendance::with(['logs', 'user'])
            ->whereDate('attendance_date', Carbon::today())
            ->withCount('logs')
            ->get();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * @mixin IdeHelperAttendanceLog
 */
class AttendanceLog extends Model
{

    public function scopeGroupByLineToday($query, $lineId = null, $startDate = null, $endDate = null)
    {
        $today = Carbon::today()->toDateString();

        return $query
            ->join('attendances as a', 'attendance_logs.attendance_id', '=', 'a.id')
            ->join('users as u', 'a.user_id', '=', 'u.id')
            ->join('devices as d', 'attendance_logs.device_id', '=', 'd.id')
            ->join('line_devices as ld', 'd.id', '=', 'ld.device_id')
            ->join('lines as l', 'ld.line_id', '=', 'l.id')
            ->select(
                'attendance_logs.id as log_id',
                'attendance_logs.log_type',
                'attendance_logs.timestamp',
                'a.id as attendance_id',
                'a.check_in_time',
                'a.check_out_time',
                'a.attendance_date',
                'a.status',
                'u.id as user_id',
                'u.name as user_name',
                'l.id as line_id',
                'l.name as line_name',
                'd.id as device_id',
                'd.name as device_name'
            )
            ->when($lineId, fn($q) => $q->where('l.id', $lineId))
            ->when(
                $startDate && $endDate,
                fn($q) => $q->whereBetween('a.attendance_date', [$startDate, $endDate]),
                fn($q) => $q->whereDate('a.attendance_date', $today)
            )
            ->orderBy('l.name')
            ->orderBy('u.name');
    }
}

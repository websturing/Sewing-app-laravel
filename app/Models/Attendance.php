<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


/**
 * @mixin IdeHelperAttendance
 */
class Attendance extends Model
{
    public function logs()
    {
        return $this->hasMany(AttendanceLog::class, 'attendance_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function calculateWorkingHours()
    {
        if (!$this->check_in_time || !$this->check_out_time) {
            return null;
        }

        $start = Carbon::parse($this->check_in_time);
        $end = Carbon::parse($this->check_out_time);

        // Hitung selisih dalam jam:menit:detik
        $diff = $start->diff($end);

        return sprintf('%02d:%02d:%02d', $diff->h, $diff->i, $diff->s);

        // Atau jika ingin dalam format jam desimal (contoh: 8.5 jam)
        // return round($end->diffInMinutes($start) / 60, 2);
    }
}

<?php

namespace App\Services\Attendance;

use App\Services\Attendance\AttendanceServiceInterface;
use App\Repositories\Attendance\AttendanceRepositoryInterface;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Collection;

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

        return $this->calculateOntimeByShift($attendances);
        $statusOptions = $this->statusOptions;

        // * Group attendances once by status
        $groupedByStatus = $attendances->groupBy('status');
        $summaryResult = collect($statusOptions)->mapWithKeys(function ($status) use ($groupedByStatus) {
            $items = $groupedByStatus->get($status, collect());
            return [
                $status => [
                    'count' => $items->count(),
                    'items' => $items->values()
                ]
            ];
        });

        /**
         * * Average the summary result
         * * Return Clock In and Clock Out
         */

        $checkInTotalInSeconds = $attendances->sum(function ($item) {
            return Carbon::parse($item->check_in_time)->secondsSinceMidnight();
        });
        $checkInAverage = Carbon::createFromTime(0)->addSeconds(intval($checkInTotalInSeconds / $attendances->count()))->format('H:i:s');


        return [
            'summary' => $summaryResult->map(fn($data) => $data['count']),
            'check_in_average' => $checkInAverage,
            'items' => $attendances
        ];
    }

    public function calculateOntimeByShift(Collection $attendances): array
    {
        $total = 0;
        $ontime = 0;
        $data = [];

        foreach ($attendances as $attendance) {
            // Ambil shift yang ditugaskan untuk tanggal itu
            $shiftAssignment = $attendance->user->shiftAssignments
                ->filter(fn($sa) => Carbon::parse($sa->effective_date_start)->lte($attendance->attendance_date))
                ->sortByDesc('effective_date')
                ->first();
            $data[] = $shiftAssignment;
            if (!$shiftAssignment || !$attendance->check_in_time) {
                continue; // Skip kalau tidak ada shift atau belum check-in
            }

            $shift = $shiftAssignment->shift;
            if (!$shift) {
                continue;
            }

            $scheduledStart = Carbon::parse($shift->start_time);
            $tolerance = $shift->tolerance ?? 5; // default 5 menit

            $actualCheckIn = Carbon::parse($attendance->check_in_time);
            $toleratedStart = $scheduledStart->copy()->addMinutes($tolerance);

            $total++;

            if ($actualCheckIn->lte($toleratedStart)) {
                $ontime++;
            }
        }
        // return $data;
        if ($total === 0) {
            return [
                'ontime_percentage' => 0,
                'ontime_count' => 0,
                'total' => 0,
                'note' => 'Tidak ada data valid shift + check-in',
            ];
        }

        $percentage = round(($ontime / $total) * 100, 2);

        return [
            'ontime_percentage' => $percentage,
            'ontime_count' => $ontime,
            'data' => $data,
            'total' => $total,
        ];
    }

}

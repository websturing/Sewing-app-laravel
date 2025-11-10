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
        $attendancesAll = $this->attendanceRepository->all();



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
        $checkInPercentage = $this->calculateOntimeByShift($attendances);

        /**
         * 
         * * Return summary, average check-in time, and percentage of on-time check-ins
         */

        $shiftAverage = $this->averageCheckInPerShift($attendances);

        return [
            'summary' => $summaryResult->map(fn($data) => $data['count']),
            'check_in_average' => $checkInAverage,
            'check_in_percentage' => $checkInPercentage,
            'shift_average' => $shiftAverage,
            'items' => $attendances
        ];
    }

    public function getAttendanceByRangeDate(string $startDate, string $endDate)
    {

        $attendances = $this->attendanceRepository->byRangeDate($startDate, $endDate);
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
        $checkInPercentage = $this->calculateOntimeByShift($attendances);

        /**
         * 
         * * Return summary, average check-in time, and percentage of on-time check-ins
         */

        $shiftAverage = $this->averageCheckInPerShift($attendances);

        return [
            'summary' => $summaryResult->map(fn($data) => $data['count']),
            'check_in_average' => $checkInAverage,
            'check_in_percentage' => $checkInPercentage,
            'shift_average' => $shiftAverage,
            'items' => $attendances
        ];
    }

    public function getAttendanceShiftSummary()
    {
        $attendances = $this->attendanceRepository->all();
        $shiftSummary = $this->averageCheckInPerShift($attendances);

        return [
            'shift_summary' => $shiftSummary,
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

    public function averageCheckInPerShift(Collection $attendances): array
    {
        $result = [];
        $shiftInfoMap = [];

        // Group berdasarkan nama shift
        $grouped = $attendances->groupBy(function ($attendance) use (&$shiftInfoMap) {
            $shiftAssignment = $attendance->user->shiftAssignments
                ->filter(fn($sa) => Carbon::parse($sa->effective_date_start)->lte($attendance->attendance_date))
                ->sortByDesc('effective_date_start')
                ->first();

            $shiftName = $shiftAssignment?->shift?->name ?? 'Unknown';

            // Simpan shift reference di map
            if (!isset($shiftInfoMap[$shiftName]) && $shiftAssignment?->shift) {
                $shiftInfoMap[$shiftName] = [
                    'start_time' => $shiftAssignment->shift->start_time,
                    'end_time' => $shiftAssignment->shift->end_time,
                ];
            }

            return $shiftName;
        });

        foreach ($grouped as $shiftName => $items) {
            $valid = $items->filter(fn($a) => $a->check_in_time);
            $count = $valid->count();

            if ($count === 0) {
                $result[$shiftName] = [
                    'average_check_in' => null,
                    'count' => 0,
                    'on_time_percentage' => 0,
                    'start_time' => $shiftInfoMap[$shiftName]['start_time'] ?? null,
                    'end_time' => $shiftInfoMap[$shiftName]['end_time'] ?? null,
                    'employees' => [],
                ];
                continue;
            }

            $onTimeCount = $valid->filter(fn($a) => $a->status === 'present')->count();
            $onTimePercentage = round(($onTimeCount / $count) * 100, 2);

            $totalSeconds = $valid->sum(function ($a) {
                return Carbon::parse($a->check_in_time)->secondsSinceMidnight();
            });

            $average = Carbon::createFromTime(0)->addSeconds(intval($totalSeconds / $count));

            $employees = $valid->map(function ($a) {
                return [
                    'name' => $a->user->name,
                    'email' => $a->user->email,
                    'status' => $a->status,
                    'date' => $a->attendance_date,
                    'check_in' => $a->check_in_time ? Carbon::parse($a->check_in_time)->format('H:i:s') : null,
                ];
            })->values();

            $result[$shiftName] = [
                'average_check_in' => $average->format('H:i'),
                'count' => $count,
                'on_time_count' => $onTimeCount,
                'on_time_percentage' => $onTimePercentage,
                'start_time' => $shiftInfoMap[$shiftName]['start_time'] ?? null,
                'end_time' => $shiftInfoMap[$shiftName]['end_time'] ?? null,
                // 'employees' => $employees,
            ];
        }

        return $result;
    }

    public function getAttendanceByLineId(int $lineId)
    {
        return $this->attendanceRepository->byLineId($lineId);
    }
}

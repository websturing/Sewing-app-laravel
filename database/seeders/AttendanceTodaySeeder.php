<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Attendance;
use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\UserShiftAssignment;
use Carbon\Carbon;
use App\Models\Device;

class AttendanceTodaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $today = now()->toDateString();

        $employees = Employee::where('active', true)->with('user')->get();
        $logTypes = ['check_in', 'location', 'check_out'];
        $toleranceMinutes = 10;
        $devices = Device::all();

        foreach ($employees as $employee) {
            $user = $employee->user;

            $shiftAssignment = UserShiftAssignment::with('shift')
                ->where('user_id', $user->id)
                ->where('effective_date_start', '<=', $today)
                ->orderByDesc('effective_date_start')
                ->first();

            if (!$shiftAssignment || !$shiftAssignment->shift) {
                continue;
            }

            $shift = $shiftAssignment->shift;

            $shiftStart = Carbon::parse($today . ' ' . $shift->start_time);
            $shiftEnd = Carbon::parse($today . ' ' . $shift->end_time);

            if ($shiftStart->gt($shiftEnd)) {
                // Shift malam: jam keluar besok
                $shiftEnd->addDay();
            }

            // Randomkan status awal (untuk variasi data)
            $statusRoll = $faker->randomElement(['present', 'present', 'present', 'wfh', 'absent', 'on_leave']);

            // Skip pembuatan attendance jika tidak perlu
            if (in_array($statusRoll, ['absent', 'on_leave'])) {
                continue;
            }

            // Generate waktu check-in dan check-out
            $checkIn = $faker->dateTimeBetween($shiftStart, $shiftStart->copy()->addHour());
            $checkOut = (clone $checkIn)->modify('+8 hours');

            // Hitung apakah terlambat atau tidak
            $checkInCarbon = Carbon::parse($checkIn);
            $toleranceTime = $shiftStart->copy()->addMinutes($toleranceMinutes);

            $finalStatus = $statusRoll === 'wfh'
                ? 'wfh'
                : ($checkInCarbon->gt($toleranceTime) ? 'late' : 'present');

            // Bersihkan data lama
            $existingAttendance = Attendance::where('user_id', $user->id)
                ->where('attendance_date', $today)
                ->first();

            if ($existingAttendance) {
                $existingAttendance->logs()->delete();
                $existingAttendance->delete();
            }

            // Simpan Attendance
            $attendance = Attendance::create([
                'user_id' => $user->id,
                'attendance_date' => $today,
                'check_in_time' => $checkIn,
                'check_out_time' => $checkOut,
                'status' => $finalStatus,
                'note' => $faker->optional()->sentence(),
            ]);

            // Simpan Log
            foreach ($logTypes as $type) {
                AttendanceLog::create([
                    'attendance_id' => $attendance->id,
                    'log_type' => $type,
                    'timestamp' => match ($type) {
                        'check_in' => $checkIn,
                        'location' => $faker->dateTimeBetween($checkIn, $checkOut),
                        'check_out' => $checkOut,
                    },
                    'latitude' => $faker->latitude(),
                    'longitude' => $faker->longitude(),
                    'accuracy' => $faker->randomFloat(2, 1, 50),
                    'device_id' => $faker->randomElement($devices->pluck('id')->toArray()),
                    'notes' => $faker->optional()->sentence(),
                ]);
            }
        }

        $this->command->info('✅ Attendance data for today has been refreshed with realistic status and timing.');
    }
}

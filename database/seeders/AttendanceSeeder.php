<?php

namespace Database\Seeders;
use App\Models\Attendance;
use App\Models\Shift;
use App\Models\UserShiftAssignment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Carbon\Carbon;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AttendanceLog;


class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $employees = Employee::where('active', true)->with('user')->get();
        $logTypes = ['check_in', 'location', 'check_out'];
        $statusOptions = ['present', 'present', 'present', 'wfh', 'absent', 'on_leave'];
        $toleranceMinutes = 10;
        $today = now();

        foreach ($employees as $employee) {
            $user = $employee->user;

            for ($i = 0; $i < 30; $i++) {
                $date = $today->copy()->subDays($i)->toDateString();

                $shiftAssignment = UserShiftAssignment::with('shift')
                    ->where('user_id', $user->id)
                    ->where('effective_date_start', '<=', $date)
                    ->orderByDesc('effective_date_start')
                    ->first();

                if (!$shiftAssignment || !$shiftAssignment->shift) {
                    continue;
                }

                $shift = $shiftAssignment->shift;

                $shiftStart = Carbon::parse("$date {$shift->start_time}");
                $shiftEnd = Carbon::parse("$date {$shift->end_time}");

                if ($shiftStart->gt($shiftEnd)) {
                    $shiftEnd->addDay(); // shift malam
                }

                $statusRoll = $faker->randomElement($statusOptions);

                if (in_array($statusRoll, ['absent', 'on_leave'])) {
                    continue;
                }

                $checkIn = $faker->dateTimeBetween($shiftStart, $shiftStart->copy()->addHour());
                $checkOut = (clone $checkIn)->modify('+8 hours');

                $checkInCarbon = Carbon::parse($checkIn);
                $toleranceTime = $shiftStart->copy()->addMinutes($toleranceMinutes);

                $finalStatus = $statusRoll === 'wfh'
                    ? 'wfh'
                    : ($checkInCarbon->gt($toleranceTime) ? 'late' : 'present');

                $existingAttendance = Attendance::where('user_id', $user->id)
                    ->where('attendance_date', $date)
                    ->first();

                if ($existingAttendance) {
                    $existingAttendance->logs()->delete();
                    $existingAttendance->delete();
                }

                $attendance = Attendance::create([
                    'user_id' => $user->id,
                    'attendance_date' => $date,
                    'check_in_time' => $checkIn,
                    'check_out_time' => $checkOut,
                    'status' => $finalStatus,
                    'note' => $faker->optional()->sentence(),
                ]);

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
                        'device_id' => $employee->device_id,
                        'notes' => $faker->optional()->sentence(),
                    ]);
                }
            }
        }

        $this->command->info('✅ Attendance seeded for last 30 days using assigned shifts.');
    }

}

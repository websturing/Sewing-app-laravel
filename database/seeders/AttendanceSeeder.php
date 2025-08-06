<?php

namespace Database\Seeders;
use App\Models\Attendance;
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

        // Cek atau buat 50 user & employee
        if (User::count() < 50) {
            for ($i = 1; $i <= 50; $i++) {
                $user = User::create([
                    'name' => $faker->name,
                    'email' => "user$i@example.com",
                    'password' => bcrypt('password'),
                ]);

                Employee::create([
                    'user_id' => $user->id,
                    'employee_code' => 'EMP' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'position' => $faker->jobTitle,
                    'department' => $faker->randomElement(['IT', 'HR', 'Finance', 'Ops']),
                    'join_date' => $faker->dateTimeBetween('-5 years', '-1 month'),
                    'active' => true,
                    'device_id' => $faker->uuid,
                ]);
            }
        }

        $employees = Employee::where('active', true)->with('user')->get();
        $statusOptions = ['present', 'late', 'absent', 'on_leave', 'wfh'];
        $logTypes = ['check_in', 'location', 'check_out'];
        $attendanceCount = 0;

        while ($attendanceCount < 200) {
            $employee = $faker->randomElement($employees);
            $userId = $employee->user_id;
            $attendanceDate = $faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d');

            // Skip kalau sudah ada record untuk hari itu
            if (
                Attendance::where('user_id', $userId)
                    ->where('attendance_date', $attendanceDate)
                    ->exists()
            ) {
                continue;
            }

            $checkIn = $faker->dateTimeBetween($attendanceDate . ' 07:00:00', $attendanceDate . ' 10:00:00');
            $checkOut = (clone $checkIn)->modify('+8 hours');

            $attendance = Attendance::create([
                'user_id' => $userId,
                'attendance_date' => $attendanceDate,
                'check_in_time' => $checkIn,
                'check_out_time' => $checkOut,
                'status' => $faker->randomElement($statusOptions),
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
                    'device_id' => $employee->device_id, // ambil dari employee
                    'notes' => $faker->optional()->sentence(),
                ]);
            }

            $attendanceCount++;
        }
    }
}

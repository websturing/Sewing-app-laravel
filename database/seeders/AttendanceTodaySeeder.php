<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Attendance;
use App\Models\AttendanceLog;
use App\Models\Employee;

class AttendanceTodaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $today = now()->format('Y-m-d');

        // Ambil semua employee aktif
        $employees = Employee::where('active', true)->with('user')->get();
        $statusOptions = ['present', 'late', 'absent', 'on_leave', 'wfh'];
        $logTypes = ['check_in', 'location', 'check_out'];

        foreach ($employees as $employee) {
            $userId = $employee->user_id;

            // Cek apakah sudah ada attendance hari ini
            $existingAttendance = Attendance::where('user_id', $userId)
                ->where('attendance_date', $today)
                ->first();

            // Generate waktu check-in dan check-out
            $checkIn = $faker->dateTimeBetween($today . ' 07:00:00', $today . ' 10:00:00');
            $checkOut = (clone $checkIn)->modify('+8 hours');

            // Data attendance
            $attendanceData = [
                'user_id' => $userId,
                'attendance_date' => $today,
                'check_in_time' => $checkIn,
                'check_out_time' => $checkOut,
                'status' => $faker->randomElement($statusOptions),
                'note' => $faker->optional()->sentence(),
            ];

            // Jika sudah ada, update. Jika tidak, create baru
            if ($existingAttendance) {
                $existingAttendance->update($attendanceData);
                $attendance = $existingAttendance;

                // Hapus logs lama jika ada
                AttendanceLog::where('attendance_id', $attendance->id)->delete();
            } else {
                $attendance = Attendance::create($attendanceData);
            }

            // Buat logs baru
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
        $this->command->info('Today attendance data has been generated/updated!');
    }
}

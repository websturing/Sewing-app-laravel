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
        $shifts = Shift::all();

        // 2. Buat User & Employee jika kurang dari 50
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

        // 3. Ambil employee aktif
        $employees = Employee::where('active', true)->with('user')->get();
        $statusOptions = ['present', 'late', 'absent', 'on_leave', 'wfh'];
        $logTypes = ['check_in', 'location', 'check_out'];

        // 4. Generate shift assignment & attendance untuk 30 hari terakhir
        $today = now()->startOfDay();

        foreach ($employees as $employee) {
            $user = $employee->user;

            for ($i = 0; $i < 30; $i++) {
                $date = $today->copy()->subDays($i)->format('Y-m-d');

                // Hapus data lama
                Attendance::where('user_id', $user->id)
                    ->where('attendance_date', $date)
                    ->each(function ($a) {
                        $a->logs()->delete();
                        $a->delete();
                    });

                // Assign shift random
                $shift = $faker->randomElement($shifts);

                UserShiftAssignment::updateOrCreate(
                    ['user_id' => $user->id, 'effective_date_start' => $date],
                    ['shift_id' => $shift->id]
                );

                // Hitung waktu check-in & out berdasarkan shift
                $shiftStart = $date . ' ' . $shift->start_time;
                $shiftEnd = $shift->end_time;

                // Penanganan shift malam
                if (strtotime($shift->start_time) > strtotime($shift->end_time)) {
                    $shiftOut = date('Y-m-d H:i:s', strtotime("+1 day", strtotime($date . ' ' . $shift->end_time)));
                } else {
                    $shiftOut = $date . ' ' . $shift->end_time;
                }

                $checkIn = $faker->dateTimeBetween($shiftStart, date('Y-m-d H:i:s', strtotime($shiftStart . ' +1 hour')));
                $checkOut = (clone $checkIn)->modify('+8 hours');

                // Simpan attendance
                $attendance = Attendance::create([
                    'user_id' => $user->id,
                    'attendance_date' => $date,
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
                        'device_id' => $employee->device_id,
                        'notes' => $faker->optional()->sentence(),
                    ]);
                }
            }
        }
    }
}

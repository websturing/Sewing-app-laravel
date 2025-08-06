<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Carbon\Carbon;
class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key check sementara
        Schema::disableForeignKeyConstraints();

        // Kosongkan tabel dengan urutan yang benar
        DB::table('user_shift_assignments')->truncate();
        DB::table('shifts')->truncate();

        // Aktifkan kembali foreign key check
        Schema::enableForeignKeyConstraints();

        // Data shift
        $shifts = [
            [
                'name' => 'Shift Pagi',
                'start_time' => '08:00:00',
                'end_time' => '16:00:00',
                'tolerance' => 15,
                'is_night_shift' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Shift Siang',
                'start_time' => '13:00:00',
                'end_time' => '21:00:00',
                'tolerance' => 15,
                'is_night_shift' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Shift Malam',
                'start_time' => '21:00:00',
                'end_time' => '05:00:00',
                'tolerance' => 20,
                'is_night_shift' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Shift Fleksibel',
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'tolerance' => 30,
                'is_night_shift' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert data shift
        DB::table('shifts')->insert($shifts);

        // Ambil semua shift yang baru dibuat
        $shiftIds = DB::table('shifts')->pluck('id')->toArray();

        // Ambil semua user untuk di-assign shift
        $users = User::all();

        // Data assignment
        $assignments = [];
        $baseDate = Carbon::today(); // Tanggal dasar

        foreach ($users as $index => $user) {
            $shiftId = $shiftIds[$index % count($shiftIds)]; // Round robin assignment

            // Buat salinan tanggal dasar untuk setiap iterasi
            $startDate = $baseDate->copy()->subDays(rand(0, 30));
            $endDate = rand(0, 1) ? $startDate->copy()->addDays(rand(30, 90)) : null;

            $assignments[] = [
                'user_id' => $user->id,
                'shift_id' => $shiftId,
                'effective_date_start' => $startDate->format('Y-m-d'),
                'effective_date_end' => $endDate?->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert data assignment
        DB::table('user_shift_assignments')->insert($assignments);

        $this->command->info('Shifts and assignments seeded successfully!');
        $this->command->info('Total shifts created: ' . count($shifts));
        $this->command->info('Total assignments created: ' . count($assignments));
    }
}

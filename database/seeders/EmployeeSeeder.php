<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Str;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Contoh: buat 5 pegawai dengan user_id dan 5 tanpa user_id
        for ($i = 1; $i <= 10; $i++) {
            $hasUser = $i <= 5;

            $userId = null;
            if ($hasUser) {
                $user = User::factory()->create([
                    'name' => 'Pegawai ' . $i,
                    'email' => 'pegawai' . $i . '@example.com',
                    'password' => bcrypt('password'),
                ]);
                $userId = $user->id;
            }

            Employee::create([
                'user_id' => $userId,
                'employee_code' => 'EMP' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'position' => fake()->jobTitle(),
                'department' => fake()->randomElement(['HR', 'Finance', 'IT', 'Sales']),
                'join_date' => now()->subDays(rand(30, 1000)),
                'active' => true,
                'device_id' => $hasUser ? Str::uuid() : null,
            ]);
        }
    }
}

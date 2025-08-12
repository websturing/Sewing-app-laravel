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
    public function run()
    {
        $departments = ['HR', 'Finance', 'IT', 'Sales', 'Marketing', 'Operations', 'Customer Service', 'Production', 'R&D', 'Logistics'];
        $positions = [
            'Manager',
            'Supervisor',
            'Senior Developer',
            'Junior Developer',
            'Accountant',
            'HR Specialist',
            'Sales Executive',
            'Marketing Analyst',
            'IT Support',
            'Operations Coordinator',
            'Customer Representative',
            'Production Staff',
            'Research Assistant',
            'Logistics Coordinator'
        ];

        for ($i = 1; $i <= 200; $i++) {
            // Buat user untuk setiap karyawan
            $user = User::factory()->create([
                'name' => 'Pegawai ' . $i,
                'email' => 'pegawai' . $i . '@example.com',
                'password' => bcrypt('password'), // password default
            ]);

            // Buat data karyawan
            Employee::create([
                'user_id' => $user->id,
                'employee_code' => 'EMP' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'position' => fake()->randomElement($positions),
                'department' => fake()->randomElement($departments),
                'join_date' => now()->subDays(rand(30, 1000)),
                'active' => rand(0, 100) < 90, // 90% aktif, 10% non-aktif
                'device_id' => rand(0, 1) ? Str::uuid() : null, // 50% punya device
            ]);
        }
    }
}

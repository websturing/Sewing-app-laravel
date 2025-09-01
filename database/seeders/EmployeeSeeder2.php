<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Carbon\Carbon;

class EmployeeSeeder2 extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // menggunakan lokal Indonesia

        // Daftar departemen dan jabatan yang umum
        $departments = [
            'IT',
            'HRD',
            'Finance',
            'Marketing',
            'Sales',
            'Production',
            'Quality Control',
            'Logistics',
            'Purchasing',
            'Administration',
            'Research & Development',
            'Customer Service'
        ];

        $positions = [
            'Manager',
            'Supervisor',
            'Staff',
            'Senior Staff',
            'Junior Staff',
            'Director',
            'Assistant Manager',
            'Coordinator',
            'Analyst',
            'Specialist',
            'Officer',
            'Engineer',
            'Technician'
        ];

        $employees = [];

        for ($i = 1; $i <= 500; $i++) {
            $joinDate = $faker->dateTimeBetween('-10 years', 'now');
            $birthDate = $faker->dateTimeBetween('-55 years', '-20 years');

            $employees[] = [
                'user_id' => null, // bisa diisi nanti jika perlu relasi ke users
                'employee_code' => $this->generateEmployeeCode($i),
                'name' => $faker->name,
                'gender' => $faker->randomElement(['L', 'P']),
                'date_birth' => $birthDate->format('Y-m-d'),
                'position' => $faker->randomElement($positions),
                'department' => $faker->randomElement($departments),
                'join_date' => $joinDate->format('Y-m-d'),
                'active' => $faker->boolean(90), // 90% kemungkinan aktif
                'device_id' => $faker->boolean(30) ? $faker->uuid : null, // 30% punya device ID
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Insert dalam batch untuk performa
            if ($i % 100 === 0) {
                DB::table('employees')->insert($employees);
                $employees = [];
            }
        }

        // Insert sisa data
        if (!empty($employees)) {
            DB::table('employees')->insert($employees);
        }
    }

    /**
     * Generate kode karyawan yang unik
     */
    private function generateEmployeeCode($index): string
    {
        $prefix = 'EMP';
        $year = date('Y');
        $sequence = str_pad($index, 4, '0', STR_PAD_LEFT);

        return $prefix . $year . $sequence;
    }
}

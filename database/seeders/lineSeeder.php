<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class lineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            \DB::table('lines')->insert([
                'name' => 'Line A' . $i,
                'created_at' => now(),
                'updated_at' => now(),
                'location' => "Factory A"
            ]);
        }
    }
}

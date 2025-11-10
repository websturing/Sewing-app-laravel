<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Device;

class deviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 200; $i++) {

            // Buat data device
            Device::create([
                'name' => 'Device ' . $i,
                'description' => $faker->sentence(),
                'mac_address' => $faker->macAddress(),
            ]);
        }
    }
}

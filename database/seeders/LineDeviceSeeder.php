<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Device;
use App\Models\Line;
use App\Models\LineDevice;

class LineDeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::Create();
        $devices = Device::all();
        $line = Line::all();

        foreach ($line as $ln) {
            // Assign 1 to 3 devices randomly to each line
            $assignedDevices = $devices->random(rand(1, 3));

            foreach ($assignedDevices as $device) {
                LineDevice::create([
                    'line_id' => $ln->id,
                    'device_id' => $device->id,
                ]);
            }
        }
    }
}

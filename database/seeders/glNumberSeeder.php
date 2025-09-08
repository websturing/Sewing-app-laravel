<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class glNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('gl')->insert([
            'gl_number' => 'GL-65781',
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        \DB::table('gl')->insert([
            'gl_number' => 'GL-65732',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \DB::table('gl')->insert([
            'gl_number' => 'GL-65593',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

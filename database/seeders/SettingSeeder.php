<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'app_name', 'value' => 'Sewing App'],
            ['key' => 'app_title', 'value' => 'Sewing Production Control System'],
            ['key' => 'app_description', 'value' => 'Smart app for managing sewing'],
            ['key' => 'app_logo_url', 'value' => '/storage/icons/logo.png'],
            ['key' => 'favicon_url', 'value' => '/storage/icons/favicon.ico'],
            ['key' => 'theme_color', 'value' => '#0d6efd'],
            ['key' => 'default_locale', 'value' => 'id'],
            ['key' => 'timezone', 'value' => 'Asia/Jakarta'],
            ['key' => 'allow_register', 'value' => 'true'],
            ['key' => 'contact_email', 'value' => 'support@sewingapp.com'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}

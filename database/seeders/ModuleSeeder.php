<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Module;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $modules = [
            [
                "name" => "Dashboard",
                "slug" => "dashboard",
                "icon" => "icon-home-2",
                "order" => 1,
                "is_active" => true,
                "parent_id" => null,
            ],
            [
                "name" => "Permissions",
                "slug" => "permissions",
                "icon" => "icon-blockchain-3",
                "order" => 1,
                "is_active" => true,
                "parent_slug" => "users-management",
            ],
            [
                "name" => "Users",
                "slug" => "users",
                "icon" => "icon-profile",
                "order" => 2,
                "is_active" => true,
                "parent_slug" => "users-management",
            ],
            [
                "name" => "User Management",
                "slug" => "users-management",
                "icon" => "icon-profile",
                "order" => 3,
                "is_active" => true,
                "parent_id" => null,
            ],
            [
                "name" => "Employee",
                "slug" => "employee",
                "icon" => "icon-profile",
                "order" => 4,
                "is_active" => true,
                "parent_slug" => "users-management",
            ],
            [
                "name" => "Attendance Management",
                "slug" => "attendance-mn",
                "icon" => "icon-progress-2-3",
                "order" => 1,
                "is_active" => true,
                "parent_id" => null,
            ],
            [
                "name" => "Shift",
                "slug" => "shift",
                "icon" => "icon-stopwatch",
                "order" => 2,
                "is_active" => true,
                "parent_slug" => "attendance-mn",
            ],
            [
                "name" => "Shift Assignments",
                "slug" => "assignments",
                "icon" => "icon-algorithm-2",
                "order" => 3,
                "is_active" => true,
                "parent_slug" => "attendance-mn",
            ],
            [
                "name" => "Attendances",
                "slug" => "attendance",
                "icon" => "icon-calendar",
                "order" => 4,
                "is_active" => true,
                "parent_slug" => "attendance-mn",
            ],
        ];

        // Step 1: Insert parents first
        $slugToId = [];

        foreach ($modules as $data) {
            if (isset($data['parent_slug'])) {
                continue;
            }

            $module = Module::updateOrCreate(
                ['slug' => $data['slug']],
                Arr::except($data, ['parent_slug'])
            );

            $slugToId[$module->slug] = $module->id;
        }

        // Step 2: Insert children (with parent_slug)
        foreach ($modules as $data) {
            if (!isset($data['parent_slug'])) {
                continue;
            }

            $data['parent_id'] = $slugToId[$data['parent_slug']] ?? null;
            unset($data['parent_slug']);

            $module = Module::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );

            $slugToId[$module->slug] = $module->id;
        }

        // Simpan mapping untuk permission seeder
        Cache::put('module_slug_to_id', $slugToId, now()->addHours(1));

        $this->command->info('Modules seeded successfully!');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Module;
use Illuminate\Support\Arr;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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
                "parent_slug" => "users-management", // nanti kita proses ini
            ],
            [
                "name" => "users",
                "slug" => "users",
                "icon" => "icon-profile",
                "order" => 2,
                "is_active" => true,
                "parent_slug" => "users-management", // nanti kita proses ini
            ],
            [
                "name" => "User management",
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
            if (isset($data['parent_slug']))
                continue;

            $module = Module::updateOrCreate(
                ['slug' => $data['slug']],
                Arr::except($data, ['parent_slug'])
            );

            $slugToId[$module->slug] = $module->id;
        }

        // Step 2: Insert children (with parent_slug)
        foreach ($modules as $data) {
            if (!isset($data['parent_slug']))
                continue;

            $data['parent_id'] = $slugToId[$data['parent_slug']] ?? null;
            unset($data['parent_slug']);

            $module = Module::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );

            $slugToId[$module->slug] = $module->id;
        }

        // Simpan mapping buat dipakai di permission seeder
        cache()->put('module_slug_to_id', $slugToId);
    }
}

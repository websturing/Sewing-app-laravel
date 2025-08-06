<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use App\Models\Module;
use App\Models\ModulePermission;

class ModulePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Dapatkan mapping slug ke ID dari cache atau query database
        $slugToId = Cache::get('module_slug_to_id') ?? Module::pluck('id', 'slug')->toArray();

        // Daftar action default untuk setiap module
        $defaultActions = ['read', 'create', 'update', 'delete', 'upload', 'download'];

        // Konfigurasi khusus untuk module tertentu
        $moduleActions = [
            'dashboard' => ['read'], // Dashboard hanya perlu read
            // Tambahkan konfigurasi khusus lainnya di sini
        ];

        foreach ($slugToId as $slug => $moduleId) {
            // Gunakan action khusus jika ada, otherwise gunakan default
            $actions = $moduleActions[$slug] ?? $defaultActions;

            foreach ($actions as $action) {
                $permissionName = $slug . '.' . $action;

                ModulePermission::updateOrCreate(
                    ['permission_name' => $permissionName],
                    [
                        'action' => $action,
                        'module_id' => $moduleId
                    ]
                );
            }
        }

        $this->command->info('Module permissions seeded successfully!');
    }
}

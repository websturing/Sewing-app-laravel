<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\ModulePermission;

class ModulePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ["permission_name" => "dashboard.read",      "action" => "read"],
            ["permission_name" => "dashboard.create",    "action" => "create"],
            ["permission_name" => "dashboard.update",    "action" => "update"],
            ["permission_name" => "dashboard.delete",    "action" => "delete"],
            ["permission_name" => "dashboard.upload",    "action" => "upload"],
            ["permission_name" => "dashboard.download",  "action" => "download"],

            ["permission_name" => "users.read",      "action" => "read"],
            ["permission_name" => "users.create",    "action" => "create"],
            ["permission_name" => "users.update",    "action" => "update"],
            ["permission_name" => "users.delete",    "action" => "delete"],
            ["permission_name" => "users.upload",    "action" => "upload"],
            ["permission_name" => "users.download",  "action" => "download"],

            ["permission_name" => "permissions.read",      "action" => "read"],
            ["permission_name" => "permissions.create",    "action" => "create"],
            ["permission_name" => "permissions.update",    "action" => "update"],
            ["permission_name" => "permissions.delete",    "action" => "delete"],
            ["permission_name" => "permissions.upload",    "action" => "upload"],
            ["permission_name" => "permissions.download",  "action" => "download"],
        ];

        $slugToId = cache()->get('module_slug_to_id') ?? Module::pluck('id', 'slug')->toArray();

        foreach ($permissions as $data) {
            $parts = explode('.', $data['permission_name']);
            $slug = $parts[0] ?? null;
            $moduleId = $slugToId[$slug] ?? null;

            if (!$moduleId) {
                echo "Skipping permission: {$data['permission_name']} - module not found.\n";
                continue;
            }

            ModulePermission::updateOrCreate(
                ['permission_name' => $data['permission_name']],
                [
                    'action' => $data['action'],
                    'module_id' => $moduleId
                ]
            );
        }
    }
}

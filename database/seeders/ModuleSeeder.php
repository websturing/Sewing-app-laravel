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
                "icon" => "SmartHome",
                "order" => 1,
                "is_active" => true,
                "parent_id" => null,
            ],
            [
                "name" => "User Management",
                "slug" => "users-management",
                "icon" => "UsergroupAddOutlined",
                "order" => 4,
                "is_active" => true,
                "parent_id" => null,
            ],
            [
                "name" => "Transfers",
                "slug" => "transfers",
                "icon" => "Movement",
                "order" => 3,
                "is_active" => true,
                "parent_id" => null,
            ],
            [
                "name" => "Permissions",
                "slug" => "permissions",
                "icon" => "TreeView",
                "order" => 1,
                "is_active" => true,
                "parent_slug" => "users-management",
            ],
            [
                "name" => "Users",
                "slug" => "users",
                "icon" => "UsergroupAddOutlined",
                "order" => 2,
                "is_active" => true,
                "parent_slug" => "users-management",
            ],
            [
                "name" => "Employee",
                "slug" => "employee",
                "icon" => "IdCardOutline",
                "order" => 4,
                "is_active" => true,
                "parent_slug" => "users-management",
            ],
            [
                "name" => "Stock In",
                "slug" => "stock-ins",
                "icon" => "QrCodeScannerSharp",
                "order" => 1,
                "is_active" => true,
                "parent_slug" => "transfers",
            ],
            [
                "name" => "Stock Out",
                "slug" => "stockout",
                "icon" => "Scan",
                "order" => 2,
                "is_active" => true,
                "parent_slug" => "transfers",
            ],
            [
                "name" => "Defect",
                "slug" => "defect",
                "icon" => "CheckboxWarning20Regular",
                "order" => 3,
                "is_active" => true,
                "parent_slug" => "transfers",
            ],
            [
                "name" => "Lines",
                "slug" => "lines",
                "icon" => "BrandAirtable",
                "order" => 3,
                "is_active" => true,
                "parent_id" => null,
            ],
            [
                "name" => "Report",
                "slug" => "report",
                "icon" => "Report",
                "order" => 5,
                "is_active" => true,
                "parent_id" => null,
            ],
            [
                "name" => "Completion Report",
                "slug" => "report-completions",
                "icon" => "Report",
                "order" => 1,
                "is_active" => true,
                "parent_slug" => "report",
            ],
            [
                "name" => "Operation Planning",
                "slug" => "operation-planning",
                "icon" => "OperationsField",
                "order" => 2,
                "is_active" => true,
                "parent_id" => null,
            ],
            [
                "name" => "GL Number",
                "slug" => "gls",
                "icon" => "BorderLeft24Filled",
                "order" => 2,
                "is_active" => true,
                "parent_slug" => "operation-planning",
            ],
            [
                "name" => "Leaders",
                "slug" => "leaders",
                "icon" => "ChartPerson20Filled",
                "order" => 2,
                "is_active" => true,
                "parent_slug" => "operation-planning",
            ],
            [
                "name" => "Replacements",
                "slug" => "replacement-title",
                "icon" => "Forms",
                "order" => 3,
                "is_active" => true,
                "parent_id" => null,
            ],
            [
                "name" => "Ticket Request",
                "slug" => "replacement",
                "icon" => "Forms",
                "order" => 4,
                "is_active" => true,
                "parent_slug" => "replacement-title",
            ],
            [
                "name" => "Approval",
                "slug" => "replacement-approval",
                "icon" => "DesignIdeas20Filled",
                "order" => 5,
                "is_active" => true,
                "parent_slug" => "replacement-title",
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

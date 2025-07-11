<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ModulePermission;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SyncModulePermissions extends Command
{
    protected $signature = 'permissions:sync-from-db';
    protected $description = 'Sync permissions from module_permissions table to Spatie permissions table and assign them to roles';

    public function handle()
    {
        $this->info('🔁 Starting permission sync from `module_permissions` table...');

        $modulePermissions = ModulePermission::all();
        $roleName = 'admin'; // You can later make this dynamic

        $role = Role::firstOrCreate(['name' => $roleName]);
        $syncedCount = 0;
        $assignedCount = 0;

        foreach ($modulePermissions as $modulePerm) {
            $permName = $modulePerm->permission_name;

            // Sync to Spatie permissions
            $permission = Permission::firstOrCreate(['name' => $permName]);
            if ($permission->wasRecentlyCreated) {
                $this->line("✅ Created permission: $permName");
                $syncedCount++;
            } else {
                $this->line("⚠️  Already exists: $permName");
            }

            // Assign to role
            if (!$role->hasPermissionTo($permName)) {
                $role->givePermissionTo($permName);
                $this->line("🔗 Assigned [$permName] to [$roleName] role");
                $assignedCount++;
            } else {
                $this->line("⚠️  Role already has: $permName");
            }
        }

        $this->info("✅ Sync complete.");
        $this->info("→ New permissions created: $syncedCount");
        $this->info("→ Permissions assigned to [$roleName]: $assignedCount");

        return 0;
    }
}

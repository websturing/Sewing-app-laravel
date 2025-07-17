<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ModulePermission;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SyncModulePermissions extends Command
{
    protected $signature = 'permissions:sync-from-db';
    protected $description = 'Sync permissions from module_permissions table to Spatie permissions table';

    public function handle()
    {
        $this->info('🔁 Starting permission sync from `module_permissions` table...');

        $modulePermissions = ModulePermission::all();
        $syncedCount = 0;
        $deletedCount = 0;

        // Ambil semua nama permission dari module_permissions
        $modulePermissionNames = $modulePermissions->pluck('permission_name')->toArray();

        // Sync permission ke Spatie
        foreach ($modulePermissionNames as $permName) {
            $permission = Permission::firstOrCreate(
                ['name' => $permName],
                ['guard_name' => 'web']
            );

            if ($permission->wasRecentlyCreated) {
                $this->line("✅ Created permission: $permName");
                $syncedCount++;
            } else {
                $this->line("⚠️  Already exists: $permName");
            }
        }

        // Hapus permission dari Spatie jika tidak ada di module_permissions
        $spatiePermissions = Permission::pluck('name')->toArray();
        $permissionsToDelete = array_diff($spatiePermissions, $modulePermissionNames);

        foreach ($permissionsToDelete as $obsoletePerm) {
            $permission = Permission::where('name', $obsoletePerm)->first();

            if ($permission) {
                $permission->delete();
                $this->line("🗑️  Deleted obsolete permission: $obsoletePerm");
                $deletedCount++;
            }
        }

        $this->info("✅ Sync complete.");
        $this->info("→ New permissions created: $syncedCount");
        $this->info("→ Obsolete permissions deleted: $deletedCount");

        return 0;
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ModulePermission;
use Spatie\Permission\Models\Permission;

class SyncModulePermissions extends Command
{
    protected $signature = 'permissions:sync-from-db';
    protected $description = 'Sync permissions from module_permissions table to Spatie permissions table';

    public function handle()
    {
        $this->info('🔁 Syncing module permissions...');

        $modulePermissions = ModulePermission::all();
        $synced = 0;

        foreach ($modulePermissions as $modulePerm) {
            $permName = $modulePerm->permission_name;

            $exists = Permission::where('name', $permName)->first();
            if (!$exists) {
                Permission::create(['name' => $permName]);
                $this->line("✅ Created permission: $permName");
                $synced++;
            } else {
                $this->line("⚠️  Already exists: $permName");
            }
        }

        $this->info("✅ Sync complete. Total new permissions created: $synced");
        return 0;
    }
}

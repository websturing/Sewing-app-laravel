<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ModulePermission;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SpatiePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔁 Syncing permissions from `module_permissions` table...');

        $modulePermissions = ModulePermission::all();

        $modulePermissionNames = $modulePermissions->map(function ($perm) {
            return $perm->permission_name; // pastikan ini `users.read`, `dashboard.create`, dll
        })->toArray();

        $synced = 0;
        foreach ($modulePermissionNames as $permName) {
            $permission = Permission::firstOrCreate(
                ['name' => $permName],
                ['guard_name' => 'web']
            );

            if ($permission->wasRecentlyCreated) {
                $this->command->line("✅ Created permission: $permName");
                $synced++;
            } else {
                $this->command->line("⚠️  Exists: $permName");
            }
        }

        $spatiePermissions = Permission::pluck('name')->toArray();
        $permissionsToDelete = array_diff($spatiePermissions, $modulePermissionNames);

        $deleted = 0;
        foreach ($permissionsToDelete as $obsolete) {
            Permission::where('name', $obsolete)->delete();
            $this->command->line("🗑️  Deleted: $obsolete");
            $deleted++;
        }

        $this->command->info("🔁 Permission sync done. Created: $synced, Deleted: $deleted");

        // Buat role admin kalau belum ada
        $admin = Role::firstOrCreate(['name' => 'admin'], ['guard_name' => 'web']);
        $admin->syncPermissions(Permission::all());

        $this->command->info("👑 Role `admin` now has all permissions.");

        // 4. Buat user admin
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // ganti kalau perlu
            ]
        );

        // 5. Assign role admin ke user
        $adminUser->assignRole($admin);
    }
}

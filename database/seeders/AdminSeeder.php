<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat Role Admin
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // 2. Buat Permissions
        $permissions = [
            'manage users',
            'edit post',
            'delete post',
            'view dashboard'
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // 3. Assign semua permission ke role admin
        $adminRole->syncPermissions($permissions);

        // 4. Buat user admin
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // ganti kalau perlu
            ]
        );

        // 5. Assign role admin ke user
        $adminUser->assignRole($adminRole);
    }
}

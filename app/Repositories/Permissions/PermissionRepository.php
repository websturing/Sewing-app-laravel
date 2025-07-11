<?php

namespace App\Repositories\Permissions;

use App\Models\Module;
use App\Models\User;
use App\Repositories\Permissions\PermissionRepositoryInterface;
use Spatie\Permission\Models\Permission;

class PermissionRepository implements PermissionRepositoryInterface
{
    public function all()
    {
        return Permission::all();
    }

    public function getUserModulesWithPermissions($user)
    {
        $permissions = $user->getAllPermissions()->pluck('name');

        $modules = Module::with(['permissions' => function ($query) use ($permissions) {
            $query->whereIn('permission_name', $permissions);
        }])
            ->whereHas('permissions', function ($query) use ($permissions) {
                $query->whereIn('permission_name', $permissions);
            })
            ->get();

        return $modules;
    }
}

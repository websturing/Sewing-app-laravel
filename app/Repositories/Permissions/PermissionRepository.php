<?php

namespace App\Repositories\Permissions;

use App\Models\Module;
use App\Models\User;
use App\Repositories\Permissions\PermissionRepositoryInterface;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Collection;

class PermissionRepository implements PermissionRepositoryInterface
{
    public function all(): Collection
    {
        return Permission::all();
    }

    public function getAllModulesWithPermissions(): Collection
    {
        return Module::with('permissions')->get();
    }

    public function getModulePermission(): Collection
    {
        return Module::with('permissions')
            ->get()
            ->map(function ($module) {
                return [
                    'id' => $module->id,
                    'name' => $module->name,
                    'slug' => $module->slug,
                    'permissions' => $module->permissions->map(function ($permission) {
                        return [
                            'action' => $permission->action,
                            'permission_name' => $permission->permission_name,
                        ];
                    }),
                ];
            });
    }
}

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
}

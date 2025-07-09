<?php

namespace App\Repositories\Permissions;

use App\Models\User;
use App\Repositories\Permissions\PermissionRepositoryInterface;
use Spatie\Permission\Models\Permission;

class PermissionRepository implements PermissionRepositoryInterface
{
    public function all()
    {
        return Permission::all();
    }
}

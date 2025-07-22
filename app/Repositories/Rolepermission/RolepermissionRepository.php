<?php

namespace App\Repositories\Rolepermission;

use App\Models\Rolepermission;

class RolepermissionRepository implements RolepermissionRepositoryInterface
{
    public function all()
    {
        return Rolepermission::all();
    }

    public function createPermissions(array $permissions)
    {
        return Rolepermission::insert($permissions);
    }
}

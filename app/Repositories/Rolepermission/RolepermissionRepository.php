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

        foreach ($permissions as $item) {
            Rolepermission::updateOrCreate([
                'role_id' => $item['role_id'],
                'permission_id' => $item['permission_id'],
            ], $item);
        }
    }
}

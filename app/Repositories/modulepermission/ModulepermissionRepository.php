<?php

namespace App\Repositories\modulepermission;

use App\Models\ModulePermission;

class ModulepermissionRepository implements ModulepermissionRepositoryInterface
{
    public function all()
    {
        return Modulepermission::all();
    }

    public function bulkCreate(array $data)
    {
        return Modulepermission::insert($data);
    }
}

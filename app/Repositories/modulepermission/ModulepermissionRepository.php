<?php

namespace App\Repositories\Modulepermission;

use App\Models\Modulepermission;

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

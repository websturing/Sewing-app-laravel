<?php

namespace App\Repositories\modulepermission;

use App\Models\ModulePermission;
use Illuminate\Support\Facades\DB;

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

    public function bulkUpdate(int $moduleId, array $permissionsData)
    {
        return DB::transaction(function () use ($moduleId, $permissionsData) {
            foreach ($permissionsData as $permission) {
                ModulePermission::updateOrCreate(
                    [
                        'module_id' => $moduleId,
                        'action' => $permission['action']
                    ],
                    $permission
                );
            }
            return true;
        });
    }
}

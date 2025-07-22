<?php

namespace App\Services\Rolepermission;

use App\Services\Rolepermission\RolepermissionServiceInterface;
use App\Repositories\Rolepermission\RolepermissionRepositoryInterface;

class RolepermissionService implements RolepermissionServiceInterface
{
    protected $rolepermissionRepository;

    public function __construct(RolepermissionRepositoryInterface $rolepermissionRepository)
    {
        $this->rolepermissionRepository = $rolepermissionRepository;
    }

    public function getAllRolepermission()
    {
        return $this->rolepermissionRepository->all();
    }

    public function createRolePermissions(array $permissions, int $roleId)
    {

        $result = array_map(function ($permissions) use ($roleId) {
            return [
                'role_id' => $roleId,
                'permission_id' => $permissions
            ];
        }, $permissions);

        return $this->rolepermissionRepository->createPermissions($result);
    }
}

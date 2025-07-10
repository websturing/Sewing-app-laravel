<?php

namespace App\Services\Permissions;

use App\Repositories\Permissions\PermissionRepositoryInterface;
use App\Services\Permissions\PermissionServiceInterface;

class PermisssionService implements PermissionServiceInterface
{
    public function __construct(
        protected PermissionRepositoryInterface $PermissionRepository
    ) {}

    public function getAllPermissions()
    {
        return $this->PermissionRepository->all();
    }

    public function getPermission() {}
}

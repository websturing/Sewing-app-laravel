<?php

namespace App\Services\Rolepermission;

interface RolepermissionServiceInterface
{
    public function getAllRolepermission();
    public function createRolePermissions(array $permissions, int $roleId);
}

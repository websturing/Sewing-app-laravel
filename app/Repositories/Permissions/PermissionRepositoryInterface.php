<?php

namespace App\Repositories\Permissions;

interface PermissionRepositoryInterface
{
    public function all();
    public function getUserModulesWithPermissions($user);
}

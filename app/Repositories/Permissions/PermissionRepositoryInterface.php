<?php

namespace App\Repositories\Permissions;

use Illuminate\Support\Collection;

interface PermissionRepositoryInterface
{
    public function all();
    public function getAllModulesWithPermissions(): Collection;
}

<?php

namespace App\Repositories\Permissions;

use Illuminate\Support\Collection;

interface PermissionRepositoryInterface
{
    public function all(): Collection;
    public function getAllModulesWithPermissions(): Collection;
    public function getModulePermission(): Collection;
}

<?php

namespace App\Services\Permissions;

use Illuminate\Support\Collection;


interface PermissionServiceInterface
{
    public function getAllPermissions();
    public function getStructuredMenuForUser($user);
    public function getModulePermission(): Collection;
}

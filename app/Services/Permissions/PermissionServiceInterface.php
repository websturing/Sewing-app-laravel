<?php

namespace App\Services\Permissions;

interface PermissionServiceInterface
{
    public function getAllPermissions();
    public function getStructuredMenuForUser($user);
}

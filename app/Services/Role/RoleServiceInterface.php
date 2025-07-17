<?php

namespace App\Services\Role;

interface RoleServiceInterface
{
    public function getAllRole();
    public function createRole(array $data);
    public function deleteRole(int $id);
}

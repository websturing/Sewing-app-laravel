<?php

namespace App\Services\Role;

use App\Services\Role\RoleServiceInterface;
use App\Repositories\Role\RoleRepositoryInterface;

class RoleService implements RoleServiceInterface
{
    protected $role"Repository;

    public function __construct(RoleRepositoryInterface $role"Repository)
    {
        $this->{role}Repository = $role"Repository;
    }

    public function getAllRole()
    {
        return $this->{role}Repository->all();
    }
}

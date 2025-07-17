<?php

namespace App\Services\Role;

use App\Services\Role\RoleServiceInterface;
use App\Repositories\Role\RoleRepositoryInterface;

class RoleService implements RoleServiceInterface
{
    protected $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getAllRole()
    {
        return $this->roleRepository->all();
    }

    public function createRole(array $data)
    {
        return $this->roleRepository->create($data);
    }

    public function deleteRole(int $id)
    {
        return $this->roleRepository->delete($id);
    }
}

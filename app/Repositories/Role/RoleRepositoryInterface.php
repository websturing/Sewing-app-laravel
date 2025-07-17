<?php

namespace App\Repositories\Role;

interface RoleRepositoryInterface
{
    public function all();
    public function create(array $data);
    public function delete(int $id);
}

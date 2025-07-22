<?php

namespace App\Repositories\Rolepermission;

interface RolepermissionRepositoryInterface
{
    public function all();
    public function createPermissions(array $data);
}

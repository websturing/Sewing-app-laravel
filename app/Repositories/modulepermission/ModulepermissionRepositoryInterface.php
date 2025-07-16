<?php

namespace App\Repositories\Modulepermission;

interface ModulepermissionRepositoryInterface
{
    public function all();
    public function bulkCreate(array $data);
}

<?php

namespace App\Repositories\modulepermission;

interface ModulepermissionRepositoryInterface
{
    public function all();
    public function bulkCreate(array $data);
}

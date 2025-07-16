<?php

namespace App\Services\Modulepermission;

interface ModulepermissionServiceInterface
{
    public function getAllModulepermission();
    public function bulkCreateModulepermission(array $data);
}

<?php

namespace App\Services\modulepermission;

interface ModulepermissionServiceInterface
{
    public function getAllModulepermission();
    public function bulkCreateModulepermission(array $data);
}

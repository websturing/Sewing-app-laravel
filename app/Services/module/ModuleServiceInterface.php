<?php

namespace App\Services\module;

interface ModuleServiceInterface
{
    public function getAllModule();
    public function updateOrCreateModule(array $data);
    public function createModule(array $data);
    public function createModuleWithPermissions(array $data);
}

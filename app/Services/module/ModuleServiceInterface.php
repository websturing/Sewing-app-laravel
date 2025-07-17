<?php

namespace App\Services\module;

interface ModuleServiceInterface
{
    public function getAllModule();
    public function getModuleWithPermissions();
    public function updateOrCreateModule(array $data);
    public function createModule(array $data);
    public function createModuleWithPermissions(array $data);
    public function updateModuleWithPermissions(int $id, array $data);
    public function deleteModule(int $id);
}

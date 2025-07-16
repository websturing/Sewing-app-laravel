<?php

namespace App\Services\Module;

interface ModuleServiceInterface
{
    public function getAllModule();
    public function updateOrCreateModule(array $data);
    public function createModule(array $data);
}

<?php

namespace App\Repositories\Module;

interface ModuleRepositoryInterface
{
    public function all();
    public function updateOrCreate(array $data);
    public function create(array $data);
}

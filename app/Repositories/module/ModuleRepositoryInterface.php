<?php

namespace App\Repositories\module;

interface ModuleRepositoryInterface
{
    public function all();
    public function updateOrCreate(array $data);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}

<?php

namespace App\Repositories\module;

use App\Models\Module;

class ModuleRepository implements ModuleRepositoryInterface
{
    public function all()
    {
        return Module::orderBy('name', 'asc')->get();
    }

    public function create(array $data)
    {
        return Module::create($data);
    }

    public function updateOrCreate(array $data)
    {
        return Module::updateOrCreate(
            [
                'id' => $data['id'] ?? null,
            ],
            $data
        );
    }

    public function delete(int $id)
    {
        $module = Module::find($id);
        if ($module) {
            return $module->delete();
        }
        return false;
    }
}

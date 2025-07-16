<?php

namespace App\Repositories\Module;

use App\Models\Module;

class ModuleRepository implements ModuleRepositoryInterface
{
    public function all()
    {
        return Module::all();
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
}

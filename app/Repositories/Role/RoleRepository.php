<?php

namespace App\Repositories\Role;

use App\Models\Role;

class RoleRepository implements RoleRepositoryInterface
{
    public function all()
    {
        return Role::with(['permissions'])->get();
    }

    public function create(array $data)
    {
        return Role::create($data);
    }

    public function update(int $id, array $data)
    {
        $role = Role::findOrFail($id);
        $role->update($data);
        return $role;
    }


    public function delete(int $id)
    {
        $data = Role::find($id);
        if ($data) {
            return $data->delete();
        }
        return false;
    }
}

<?php

namespace App\Repositories\Shift;

use App\Models\Shift;

class ShiftRepository implements ShiftRepositoryInterface
{
    public function all()
    {
        return Shift::all();
    }

    public function create(array $shitfData)
    {
        return Shift::create($shitfData);
    }

    public function update(int $id, array $data)
    {
        $shift = Shift::findOrFail($id);
        $shift->update($data);
        return $shift;
    }


    public function delete(int $id)
    {
        $Shift = Shift::find($id);
        if ($Shift) {
            return $Shift->delete();
        }
        return false;
    }
}

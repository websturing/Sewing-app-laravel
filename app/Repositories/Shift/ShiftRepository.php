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

    public function update(int $shiftId, array $shiftData)
    {
        $shift = Shift::findOrFail($shiftId);
        $shift->update($shiftData);
        return $shift;
    }


    public function delete(int $shiftId)
    {
        $Shift = Shift::find($shiftId);
        if ($Shift) {
            return $Shift->delete();
        }
        return false;
    }
}

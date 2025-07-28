<?php

namespace App\Repositories\Shift;

use App\Models\Shift;

class ShiftRepository implements ShiftRepositoryInterface
{
    public function all()
    {
        return Shift::orderBy('start_time', 'ASC')->get();
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
        $shift = Shift::find($shiftId);

        if (!$shift) {
            return null;
        }

        // Simpan data shift sebelum dihapus
        $deletedShift = clone $shift;

        // Hapus shift
        $shift->delete();

        // Kembalikan data yang sudah dihapus
        return $deletedShift;
    }
}

<?php

namespace App\Repositories\Shift;

use App\Models\Shift;

class ShiftRepository implements ShiftRepositoryInterface
{
    public function all()
    {
        return Shift::all();
    }
}

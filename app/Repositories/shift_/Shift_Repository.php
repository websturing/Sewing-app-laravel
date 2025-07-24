<?php

namespace App\Repositories\Shift_;

use App\Models\Shift_;

class Shift_Repository implements Shift_RepositoryInterface
{
    public function all()
    {
        return Shift_::all();
    }
}

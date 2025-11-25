<?php

namespace App\Repositories\Replacement;

use App\Models\Replacement;

class ReplacementRepository implements ReplacementRepositoryInterface
{
    public function all()
    {
        return Replacement::all();
    }
}

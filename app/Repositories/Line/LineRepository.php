<?php

namespace App\Repositories\Line;

use App\Models\Line;

class LineRepository implements LineRepositoryInterface
{
    public function all()
    {
        return Line::all();
    }
}

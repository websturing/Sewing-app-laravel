<?php

namespace App\Repositories\Defect;

use App\Models\Defect;

class DefectRepository implements DefectRepositoryInterface
{
    public function all()
    {
        return Defect::all();
    }
}

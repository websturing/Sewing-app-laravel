<?php

namespace App\Repositories\Assigmentline;

use App\Models\Assigmentline;

class AssigmentlineRepository implements AssigmentlineRepositoryInterface
{
    public function all()
    {
        return  Assigmentline::All();
    }
}

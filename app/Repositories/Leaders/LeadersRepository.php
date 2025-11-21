<?php

namespace App\Repositories\Leaders;

use App\Models\Leaders;

class LeadersRepository implements LeadersRepositoryInterface
{
    public function all()
    {
        return Leaders::all();
    }
}

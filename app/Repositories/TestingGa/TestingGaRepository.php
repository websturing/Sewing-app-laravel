<?php

namespace App\Repositories\TestingGa;

use App\Models\TestingGa;

class TestingGaRepository implements TestingGaRepositoryInterface
{
    public function all()
    {
        return TestingGa::all();
    }
}

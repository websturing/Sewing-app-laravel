<?php

namespace App\Services\Shift_;

use App\Services\Shift_\Shift_ServiceInterface;
use App\Repositories\Shift_\Shift_RepositoryInterface;

class Shift_Service implements Shift_ServiceInterface
{
    protected $shift_Repository;

    public function __construct(Shift_RepositoryInterface $shift_Repository)
    {
        $this->shift_Repository = $shift_Repository;
    }

    public function getAllShift_()
    {
        return $this->shift_Repository->all();
    }
}

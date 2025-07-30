<?php

namespace App\Repositories\Shiftassignment;

interface ShiftassignmentRepositoryInterface
{
    public function all();
    public function withShiftUser();
}

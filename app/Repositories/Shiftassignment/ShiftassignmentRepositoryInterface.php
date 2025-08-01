<?php

namespace App\Repositories\Shiftassignment;

interface ShiftassignmentRepositoryInterface
{
    public function all();
    public function withShiftUser();
    public function create(array $data);
    public function delete(int $id);
}

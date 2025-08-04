<?php

namespace App\Repositories\Shift;

interface ShiftRepositoryInterface
{
    public function all();
    public function withAssignments();
    public function create(array $shiftData);
    public function update(int $shiftId, array $shiftData);
    public function delete(int $shiftId);
}

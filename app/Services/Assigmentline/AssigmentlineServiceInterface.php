<?php

namespace App\Services\Assigmentline;

interface AssigmentlineServiceInterface
{
    public function getAllAssigmentline(array $filters);
    public function create(array $createData);
    public function update(int $id, array $updateData);
    public function delete(int $id);
}

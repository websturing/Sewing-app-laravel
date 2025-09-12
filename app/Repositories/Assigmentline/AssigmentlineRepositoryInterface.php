<?php

namespace App\Repositories\Assigmentline;

interface AssigmentlineRepositoryInterface
{
    public function all(array $filters);
    public function create(array $filters);
    public function update(int $id, array $filters);
    public function delete(int $id);
}

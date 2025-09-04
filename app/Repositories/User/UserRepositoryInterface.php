<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    public function all(array $filters);
    public function findById(int $userId);
    public function userWithRoles();
    public function userWithActivities($id);
    public function create(array $userData);
    public function update(int $userId, array $userData);
    public function delete(int $userId);
}

<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    public function all();
    public function userWithRoles();
    public function create(array $userData);
    public function update(int $userId, array $userData);
    public function delete(int $userId);
}

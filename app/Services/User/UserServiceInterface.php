<?php

namespace App\Services\User;

interface UserServiceInterface
{
    public function getAllUser();
    public function getUserWithRoles();
    public function getUserActivities(int $id);
    public function createUser(array $UserData);
    public function updateUser(int $userId, array $userData);
    public function deleteUser(int $userId);
}

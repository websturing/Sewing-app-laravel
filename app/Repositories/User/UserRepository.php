<?php

namespace App\Repositories\User;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function all()
    {
        return User::all();
    }

    public function userWithRoles()
    {
        return User::with(['roles'])->get();
    }

    public function findById(int $userId)
    {
        return User::findOrFail($userId);
    }

    public function create(array $userData)
    {
        return User::create($userData);
    }

    public function update(int $userId, array $userData)
    {
        $user = User::findOrFail($userId);
        $user->update($userData);
        return $user;
    }

    public function delete(int $userId)
    {
        $user = User::findOrFail($userId);
        if ($user) {
            return $user->delete();
        }
        return false;
    }
}

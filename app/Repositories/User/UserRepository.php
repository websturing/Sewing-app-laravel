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
}

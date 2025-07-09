<?php

namespace App\Services\User;

use App\Services\User\UserServiceInterface;
use App\Repositories\User\UserRepositoryInterface;

class UserService implements UserServiceInterface
{
    protected ${user}Repository;

    public function __construct(UserRepositoryInterface ${user}Repository)
    {
        $this->{user}Repository = ${user}Repository;
    }

    public function getAllUser()
    {
        return $this->{user}Repository->all();
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\User\UserServiceInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private UserServiceInterface $userService,
    ) {}

    public function index()
    {
        return $this->userService->getAllUser();
    }

    public function getUserWithRole()
    {
        return $this->userService->getUserWithRoles();
    }
}

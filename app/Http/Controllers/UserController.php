<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Services\User\UserServiceInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private UserServiceInterface $userService,
    ) {}

    public function index(Request $request)
    {
        $users = $this->userService->getAllUser($request->all());

        if (!$users) {
            return errorResponse('Users Not Found', 404);
        }

        return $collection = UserResource::collection($users)->additional([
            'status' => true,
            'message' => 'Succesfully Retrieved Employees'
        ]);
    }

    public function getUserWithRole()
    {
        return $this->userService->getUserWithRoles();
    }

    public function createUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:users,name',
            'email' => 'required|email|max:255|unique:users,email',
            'role_names' => 'required|array|min:1',
            'role_names.*' => 'string'
        ]);

        return $this->userService->createUser($validated);
    }

    public function updateUser(Request $request, $userId)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role_names' => 'required|array|min:1',
            'role_names.*' => 'string'
        ]);
        return $this->userService->updateUser($userId, $validated);
    }

    public function deleteUser($id)
    {
        return $this->userService->deleteUser($id);
    }
}

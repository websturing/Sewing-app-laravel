<?php

namespace App\Services\User;

use App\Http\Resources\UserWithRolesResources;
use App\Services\User\UserServiceInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Log;

class UserService implements UserServiceInterface
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUser()
    {

        try {
            $users =   $this->userRepository->all();
            return successResponse($users);
        } catch (\Exception $e) {
            Log::error('Failed to Retrive User: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return errorResponse('Failed to Retrive User', 500, [
                'exception' => app()->environment('production') ? null : $e->getMessage(),
            ]);
        }
    }
    public function getUserWithRoles()
    {

        try {
            $users =  $this->userRepository->userWithRoles();
            return successResponse(UserWithRolesResources::collection($users));
        } catch (\Exception $e) {
            Log::error('Failed to fetch User With Roles: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return errorResponse('Failed to Retrieve User With Roles', 500, [
                'exception' => app()->environment('production') ? null : $e->getMessage(),
            ]);
        }
    }

    public function createUser(array $userData)
    {

        try {
            $users =  $this->userRepository->create($userData);
            return successResponse($users);
        } catch (\Exception $e) {
            Log::error('Failed to Create User: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return errorResponse('Failed to Create User', 500, [
                'exception' => app()->environment('production') ? null : $e->getMessage(),
            ]);
        }
    }

    public function updateUser(int $userId, array $userData)
    {

        try {
            $users =  $this->userRepository->update($userId, $userData);
            return successResponse($users);
        } catch (\Exception $e) {
            Log::error('Failed to Update User: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return errorResponse('Failed to Update User', 500, [
                'exception' => app()->environment('production') ? null : $e->getMessage(),
            ]);
        }
    }

    public function deleteUser(int $userId)
    {

        try {
            $users =  $this->userRepository->delete($userId);
            return successResponse($users);
        } catch (\Exception $e) {
            Log::error('Failed to Delete User: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return errorResponse('Failed to Delete User', 500, [
                'exception' => app()->environment('production') ? null : $e->getMessage(),
            ]);
        }
    }
}

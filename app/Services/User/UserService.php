<?php

namespace App\Services\User;

use App\Http\Resources\UserWithRolesResources;
use App\Services\User\UserServiceInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
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
            $userData['password'] = Hash::make('123456789');
            $users =  $this->userRepository->create($userData);

            foreach ($userData['role_names'] as $roleName) {
                if (!$users->hasRole($roleName)) {
                    $users->assignRole($roleName);
                }
            }
            return successResponse($users, 'Succesfully Created User' . $users->email);
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
            $user = $this->userRepository->findById($userId);
            if (!$user) {
                return errorResponse('User not found', 404);
            }

            /** 1. Update data User */
            $users =  $this->userRepository->update($userId, $userData);

            /** 2. Sync Roles make user role_names is array */
            if (isset($userData['role_names'])) {
                $user->syncRoles($userData['role_names']); // <- Ini kunci utamanya
            }
            return successResponse($users, 'Successfully Update Users');
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
            $user = $this->userRepository->findById($userId);

            if (!$user) {
                return errorResponse('User not found', 404);
            }

            // 2. Hapus relasi roles/permissions sebelum delete user
            $user->roles()->detach();
            $user->permissions()->detach();

            // 3. Eksekusi penghapusan user
            $this->userRepository->delete($userId);

            return successResponse('User deleted successfully');
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

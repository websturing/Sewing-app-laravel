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
        return $this->userRepository->all();
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
}

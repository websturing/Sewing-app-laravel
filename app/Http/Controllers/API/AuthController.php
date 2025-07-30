<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\Permissions\PermissionServiceInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    protected $permissionService;

    public function __construct(PermissionServiceInterface $permissionService)
    {
        $this->permissionService = $permissionService;
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        /** TOKEN GENERATE
         * 1. 1 User 1 Token
         * 2. Create Token
         */

        $user->tokens()->delete();
        $token = $user->createToken('spa-token')->plainTextToken;

        $response = [
            'status' => true,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ];
        return successResponse($response, 'Successfully login');
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Successfully logged out',
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Logout failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Logout failed. Please try again.',
                'error' => app()->environment('production') ? null : $e->getMessage(),
            ], 500);
        }
    }


    public function profile(Request $request)
    {
        $user = $request->user();
        $menus = $this->permissionService->getStructuredMenuForUser($user);

        return response()->json([
            'status' => true,
            'message' => 'User profile retrieved successfully',
            'data' => [
                'user' => $user,
                'roles' => $user->getRoleNames(),
                'menu' => $menus,
                'permissions' => $request->user()->getAllPermissions()->pluck('name'),
            ]
        ]);
    }
}

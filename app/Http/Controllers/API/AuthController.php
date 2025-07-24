<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\Permissions\PermissionServiceInterface;

class AuthController extends Controller
{

    protected $permissionService;

    public function __construct(PermissionServiceInterface $permissionService)
    {
        $this->permissionService = $permissionService;
    }


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credential'], 401);
        }

        $request->session()->regenerate();


        $user = [
            'user' => $request->user(),
            'roles' => $request->user()->getRoleNames(),
            'permissions' => $request->user()->getAllPermissions()->pluck('name'),
        ];

        return response()->json([
            'status' => 200,
            'message' => 'Logged in',
            'data' => $user
        ]);
    }

    public function logout(Request $request)
    {
        try {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\ModulePermission;
use App\Services\Permissions\PermissionServiceInterface;
use Illuminate\Http\JsonResponse;
use App\Helpers\ApiResponse;

class PermissionsController extends Controller
{
    protected $permissionService;

    public function __construct(PermissionServiceInterface $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    function menu(Request $request)
    {
        $user = $request->user();
        $menus = $this->permissionService->getStructuredMenuForUser($user);
        return response()->json($menus);
    }

    function allPermissions(): JsonResponse
    {
        try {
            $permissions = $this->permissionService->getAllPermissions();
            return successResponse($permissions);
        } catch (\Throwable $e) {
            // logger()->error($e);
            return errorResponse('Gagal ambil permissions', 500, [
                'exception' => app()->environment('production') ? null : $e->getMessage(),
            ]);
        }
    }
}

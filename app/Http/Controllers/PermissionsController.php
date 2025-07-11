<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\ModulePermission;
use App\Services\Permissions\PermissionServiceInterface;

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
        $permissions = $user->getAllPermissions()->pluck('name');
        $modules = Module::with(['permissions' => function ($query) use ($permissions) {
            $query->whereIn('permission_name', $permissions);
        }])
            ->whereHas('permissions', function ($query) use ($permissions) {
                $query->whereIn('permission_name', $permissions);
            })
            ->get();

        $menus = $this->permissionService->getStructuredMenuForUser($user);
        return response()->json($menus);
    }
}

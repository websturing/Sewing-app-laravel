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
        $menus = $this->permissionService->getStructuredMenuForUser($user);
        return response()->json($menus);
    }

    function allPermissions()
    {
        $permissions = $this->permissionService->getModulePermission();
        return response()->json($permissions);
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\Permissions\PermissionService;
use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\ModulePermission;

class PermissionsController extends Controller
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    function menu(Request $request)
    {
        $user = $request->user();

        $menus = $this->permissionService->getStructuredMenuForUser($user);
        return response()->json($menus);
    }
}

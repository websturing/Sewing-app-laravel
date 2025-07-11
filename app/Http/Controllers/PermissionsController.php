<?php

namespace App\Http\Controllers;

use App\Services\Permissions\PermissionService;
use Illuminate\Http\Request;
use App\Models\Module;

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

        return $permissions = $user->getAllPermissions()->pluck('name');

        return Module::with(['permissions' => function ($query) use ($permissions) {
            $query->whereIn('permission_name', $permissions)
                ->where('action', 'view');
        }])
            ->whereHas('permissions', function ($query) use ($permissions) {
                $query->whereIn('permission_name', $permissions)
                    ->where('action', 'view');
            })
            ->get();

        $menus = $this->permissionService->getStructuredMenuForUser($user);
        return response()->json($menus);
    }
}

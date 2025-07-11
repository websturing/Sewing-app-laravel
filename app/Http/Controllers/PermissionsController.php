<?php

namespace App\Http\Controllers;

use App\Services\Permissions\PermisssionService;
use Illuminate\Http\Request;

class PermissionsController extends Controller
{
    protected $permissionService;

    public function __construct(PermisssionService $permissionService)
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

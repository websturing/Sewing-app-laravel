<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Services\Role\RoleServiceInterface;
use App\Services\Rolepermission\RolepermissionServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{

    public function __construct(
        private RoleServiceInterface $roleService,
        private RolepermissionServiceInterface $rolePermissionService
    ) {}


    public function index()
    {
        try {
            $roles = $this->roleService->getAllRole();
            return successResponse($roles);
        } catch (\Exception $e) {
            Log::error('Failed to fetch roles: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return errorResponse('Failed to Retrieve Module', 500, [
                'exception' => app()->environment('production') ? null : $e->getMessage(),
            ]);
        }
    }
    public function createRole(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'guard_name' => 'nullable|string',
        ]);
        try {
            $role = $this->roleService->createRole($validated);
            $permissoins = $this->rolePermissionService->createRolePermissions($request->get('permissions'), $role->id);
            return successResponse($role, "Succesfully created role : " . $role->name);
        } catch (\Exception $e) {
            Log::error('Failed to fetch roles: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return errorResponse('Failed to Retrieve Role', 500, [
                'exception' => app()->environment('production') ? null : $e->getMessage(),
            ]);
        }
    }

    public function updateRole(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);


        try {
            // Gunakan service yang sudah di-inject via constructor
            $role = $this->roleService->updateRole($id, $validated);
            $permissoins = $this->rolePermissionService->createRolePermissions($request->get('permissions'), $role->id);
            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully',
                'data' => $role
            ], 201);
        } catch (\Exception $e) {
            Log::error('Role update failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' =>  $e->getMessage()
            ], 500);
        }
    }

    public function deleteRole(Request $request, int $id)
    {
        try {
            $deleted = $this->roleService->deleteRole($id);

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Role deleted successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Role not found or could not be deleted'
                ], 404);
            }
        } catch (\Exception $e) {
            Log::error('Module deletion failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' =>  $e->getMessage()
            ], 500);
        }
    }
}

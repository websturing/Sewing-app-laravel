<?php

namespace App\Http\Controllers;


use App\Services\module\ModuleServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ModuleController extends Controller
{

    public function __construct(
        private ModuleServiceInterface $moduleService // Dependency Injection
    ) {}


    public function index()
    {
        try {
            $modules = $this->moduleService->getAllModule();
            return successResponse($modules);
        } catch (\Exception $e) {
            Log::error('Failed to fetch modules: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return errorResponse('Failed to Retrieve Module', 500, [
                'exception' => app()->environment('production') ? null : $e->getMessage(),
            ]);
        }
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:modules,name',
            'slug' => 'required|string|max:255|unique:modules,slug',
            'parent_id' => 'nullable|integer'
        ]);


        try {
            // Gunakan service yang sudah di-inject via constructor
            $module = $this->moduleService->createModuleWithPermissions($validated);

            return response()->json([
                'success' => true,
                'message' => 'Module and permissions created successfully',
                'data' => $module // Tambahkan data module jika diperlukan
            ], 201);
        } catch (\Exception $e) {
            Log::error('Module creation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' =>  $e->getMessage()
                // Jangan expose error detail di production
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:modules,name',
            'slug' => 'required|string|max:255|unique:modules,slug',
            'parent_id' => 'nullable|integer'
        ]);


        try {
            // Gunakan service yang sudah di-inject via constructor
            $module = $this->moduleService->updateModuleWithPermissions($id, $validated);

            return response()->json([
                'success' => true,
                'message' => 'Module and permissions created successfully',
                'data' => $module // Tambahkan data module jika diperlukan
            ], 201);
        } catch (\Exception $e) {
            Log::error('Module creation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' =>  $e->getMessage()
                // Jangan expose error detail di production
            ], 500);
        }
    }


    public function delete(Request $request, int $id)
    {
        try {
            $deleted = $this->moduleService->deleteModule($id);

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Module deleted successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Module not found or could not be deleted'
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

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

    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:modules,name',
            'slug' => 'required|string|max:255|unique:modules,slug',
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
}

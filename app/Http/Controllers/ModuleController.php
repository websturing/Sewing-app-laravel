<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request data
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string',
            // Add other fields as necessary
        ]);

        // Call the service to update or create the module
        $module = app('App\Services\Module\ModuleService')->updateOrCreateModule($data);

        // Return a response
        return response()->json($module, 201);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PermissionsController extends Controller
{
    function menu(Request $request)
    {
        // Get the authenticated user
        $user = $request->user();

        // Get the user's permissions
        $permissions = $user->getAllPermissions()->pluck('name');

        // Return the permissions as a JSON response
        return response()->json([
            'status' => 200,
            'message' => 'Permissions retrieved successfully',
            'data' => $permissions,
        ]);
    }
}

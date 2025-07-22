<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use App\Models\Setting;


Route::prefix('auth')
    ->middleware(['web'])
    ->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
        Route::get('/profile', [AuthController::class, 'profile'])->middleware('auth:sanctum');
    });

Route::prefix('module')
    ->middleware(['web', 'auth:sanctum'])
    ->group(function () {
        Route::get('/', [ModuleController::class, 'index']);
        Route::get('/withpermissions', [ModuleController::class, 'getModuleWithPermissions']);

        Route::post('/', [ModuleController::class, 'store']);
        Route::post('/{id}', [ModuleController::class, 'update']);
        Route::delete('/{id}', [ModuleController::class, 'delete']);
    });

Route::prefix('roles')
    ->middleware(['web', 'auth:sanctum'])
    ->group(function () {
        Route::get('/', [RoleController::class, 'index']);

        Route::post('/', [RoleController::class, 'createRole']);
        Route::post('/{id}', [RoleController::class, 'updateRole']);
        Route::delete('/{id}', [RoleController::class, 'deleteRole']);
    });

Route::prefix('users')
    ->middleware(['web', 'auth:sanctum'])
    ->group(function () {
        Route::get('/role', [UserController::class, 'getUserWithRole']);
    });




Route::prefix('permissions')
    ->middleware(['web'])
    ->group(function () {
        Route::get('/menu', [PermissionsController::class, 'menu']);
        Route::get('/', [PermissionsController::class, 'allPermissions']);
    });


Route::prefix('application')
    ->group(function () {
        Route::get('/meta', function () {
            return Setting::pluck('value', 'key');
        });
    });

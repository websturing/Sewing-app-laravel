<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserShiftAssignmentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use App\Models\Setting;


Route::prefix('auth')
    ->middleware(['api'])
    ->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/password/{userId}', [AuthController::class, 'changePassword'])->middleware('auth:sanctum');
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
        Route::get('/profile', [AuthController::class, 'profile'])->middleware('auth:sanctum');
        Route::get('/activities', [AuthController::class, 'getActivitiesByUser'])->middleware('auth:sanctum');
    });

Route::prefix('activities')
    ->middleware(['api'])
    ->group(function () {
        Route::get('/', [ActivityController::class, 'getActivitiesByUser'])->middleware('auth:sanctum');
    });

Route::prefix('module')
    ->middleware(['api', 'auth:sanctum'])
    ->group(function () {
        Route::get('/', [ModuleController::class, 'index']);
        Route::get('/withpermissions', [ModuleController::class, 'getModuleWithPermissions']);

        Route::post('/', [ModuleController::class, 'store']);
        Route::post('/{id}', [ModuleController::class, 'update']);
        Route::delete('/{id}', [ModuleController::class, 'delete']);
    });

Route::prefix('roles')
    ->middleware(['api', 'auth:sanctum'])
    ->group(function () {
        Route::get('/', [RoleController::class, 'index']);

        Route::post('/', [RoleController::class, 'createRole']);
        Route::post('/{id}', [RoleController::class, 'updateRole']);
        Route::delete('/{id}', [RoleController::class, 'deleteRole']);
    });

Route::prefix('users')
    ->middleware(['api', 'auth:sanctum'])
    ->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'createUser']);
        Route::post('/{id}', [UserController::class, 'updateUser']);
        Route::get('/role', [UserController::class, 'getUserWithRole']);
        Route::delete('/{id}', [UserController::class, 'deleteUser']);
    });




Route::prefix('permissions')
    ->middleware(['api'])
    ->group(function () {
        Route::get('/menu', [PermissionsController::class, 'menu']);
        Route::get('/', [PermissionsController::class, 'allPermissions']);
    });


Route::prefix('assignment')
    ->middleware(['api', 'auth:sanctum'])
    ->group(function () {
        Route::get('/', [UserShiftAssignmentController::class, 'index']);
        Route::get('/summary', [UserShiftAssignmentController::class, 'summaryAssigment']);
        Route::post('/', [UserShiftAssignmentController::class, 'createUserShiftAssignment']);
        Route::delete('/{id}', [UserShiftAssignmentController::class, 'deleteUserShiftAssignment']);
    });

Route::prefix('shift')
    ->middleware(['api', 'auth:sanctum'])
    ->group(function () {
        Route::get('/', [ShiftController::class, 'index']);
        Route::get('/assignments', [ShiftController::class, 'getAssignments']);
        Route::post('/', [ShiftController::class, 'createShift']);
        Route::post('/{id}', [ShiftController::class, 'updateShift']);
        Route::delete('/{id}', [ShiftController::class, 'deleteShift']);
    });

Route::prefix('employee')
    ->middleware(['api', 'auth:sanctum'])
    ->group(function () {
        Route::get('/', [EmployeeController::class, 'index']);
        Route::get('/code', [EmployeeController::class, 'getEmployeeLastCode']);
        Route::post('/', [EmployeeController::class, 'createEmployee']);
        Route::post('/{id}', [EmployeeController::class, 'updateEmployee']);
        Route::delete('/{id}', [EmployeeController::class, 'deleteEmployee']);
    });


Route::prefix('attendance')
    ->middleware(['api', 'auth:sanctum'])
    ->group(function () {
        Route::get('/', [AttendanceController::class, 'index']);
        Route::get('/range-date', [AttendanceController::class, 'getAttendanceRangeDate']);
        Route::get('/today', [AttendanceController::class, 'getAttendanceToday']);
        Route::get('/shift/statistics', [AttendanceController::class, 'getAttendanceShiftStatistics']);
        Route::post('/', [EmployeeController::class, 'createEmployee']);
        Route::post('/{id}', [EmployeeController::class, 'updateEmployee']);
        Route::delete('/{id}', [EmployeeController::class, 'deleteEmployee']);
    });


Route::prefix('application')
    ->group(function () {
        Route::get('/meta', function () {
            return Setting::pluck('value', 'key');
        });
    });

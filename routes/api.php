<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\AssignmentLineController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\GlNumberController;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\lineController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\StockInSummaryController;
use App\Http\Controllers\StockInTicketController;
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
    });

Route::prefix('lines')
    ->middleware(['api', 'auth:sanctum'])
    ->group(function () {
        Route::get('/', [lineController::class, 'index']);
    });

Route::prefix('stock-ins')
    ->middleware(['api', 'auth:sanctum'])
    ->group(function () {
        Route::get('/', [StockInController::class, 'index']);
        Route::get('/line/last-ticket/{id}', [StockInController::class, 'lastTicketByLine']);
        Route::get('/activity', [StockInController::class, 'activity']);
        Route::post('/', [StockInController::class, 'store']);
        Route::post('/ticket-number', [StockInController::class, 'storeByTicketNumber']);
        Route::post('/{id}', [StockInController::class, 'update']);
        Route::delete('/{id}', [StockInController::class, 'delete']);

        // Summary 
        Route::get('/summaries', [StockInSummaryController::class, 'summary']);
        Route::get('/summaries/chart/stock-ins/chart', [StockInSummaryController::class, 'stockInChart']);
        Route::get('/summaries/group-glnumber', [StockInSummaryController::class, 'stockInByGlNumber']);
    });


Route::prefix('tickets')
    ->middleware(['api', 'auth:sanctum'])
    ->group(function () {
        Route::get('/', [StockInTicketController::class, 'index']);
        Route::get('/{serial_number}', [StockInTicketController::class, 'ticketByserialNumber']);
    });

Route::prefix('integration')
    ->middleware(['api', 'auth:sanctum'])
    ->group(function () {
        Route::get('/bundle/ticket/{ticket_number}', [IntegrationController::class, 'getBundlesByTicket']);
        Route::get('/bundle/container/{container_number}', [IntegrationController::class, 'getBundlesByContainer']);
        Route::get('/bundle/check-qrcode/{serial_number}', [IntegrationController::class, 'getBundleByQrcode']);
    });




Route::prefix('gls')
    ->middleware(['api', 'auth:sanctum'])
    ->group(function () {
        Route::get('/', [GlNumberController::class, 'index']);
        Route::get('/number/{glNumber}', [GlNumberController::class, 'show']);
        Route::get('/cutting-summary', [GlNumberController::class, 'cuttingSummary']);
    });

Route::prefix('assignment')
    ->middleware(['api', 'auth:sanctum'])
    ->group(function () {
        Route::get('/lines', [AssignmentLineController::class, 'index']);
        Route::post('/line', [AssignmentLineController::class, 'store']);
    });

Route::prefix('activities')
    ->middleware(['api'])
    ->group(function () {
        Route::get('/', [ActivityController::class, 'getActivities'])->middleware('auth:sanctum');
        Route::get('/user', [ActivityController::class, 'getActivitiesByUser'])->middleware('auth:sanctum');
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

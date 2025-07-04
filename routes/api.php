<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use App\Models\Setting;


Route::prefix('auth')
    ->middleware(['web'])
    ->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::get('/profile', [AuthController::class, 'profile'])->middleware('auth:sanctum');
    });


Route::middleware(['auth:sanctum', 'web'])
    ->group(function () {
        Route::get('/meta', function () {
            return Setting::pluck('value', 'key');
        });
    });

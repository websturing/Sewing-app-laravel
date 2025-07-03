<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;


Route::prefix('auth')
    ->middleware(['web', 'auth:sanctum'])
    ->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::get('/profile', [AuthController::class, 'profile'])->middleware('auth:sanctum');
    });

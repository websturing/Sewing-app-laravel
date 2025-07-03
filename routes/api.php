<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;


Route::prefix('auth')
    ->middleware('auth:sanctum')
    ->name('auth.api.')
    ->group(
        function () {
            Route::post('/login', [AuthController::class, 'login']);
            Route::get('/profile', [AuthController::class, 'profile']);
        }
    );

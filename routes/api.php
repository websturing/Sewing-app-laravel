<?php

use App\Http\Controllers\API\authController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;


Route::prefix('auth')
    ->middleware('auth:sanctum')
    ->name('auth.api.')
    ->group(
        function () {
            Route::post('/login', [authController::class, 'login']);
            Route::get('/profile', [authController::class, 'profile']);
        }
    );

<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Session\Middleware\StartSession;

Route::get('/sanctum/csrf-cookie', function () {
    return response()->noContent();
})->middleware([
    StartSession::class,
    \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
    \Illuminate\View\Middleware\ShareErrorsFromSession::class,
    \App\Http\Middleware\EncryptCookies::class,
]);


Route::get('/', function () {
    return view('welcome');
});

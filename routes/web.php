<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Session\Middleware\StartSession;

Route::get('/sanctum/csrf-cookie', function () {
    $token = csrf_token();

    return response('CSRF cookie set')->cookie(
        'XSRF-TOKEN',          // name
        $token,                // value
        120,                   // minutes
        '/',                   // path
        config('session.domain'), // domain
        true,                  // secure
        false,                 // httpOnly (❗ agar bisa diakses JS)
        false,                 // raw
        'None'                 // SameSite
    );
});


Route::get('/', function () {
    return view('welcome');
});

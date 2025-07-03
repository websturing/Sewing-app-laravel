<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    // Jika ingin ada pengecualian cookie yang tidak dienkripsi:
    protected $except = [
        // 'some_cookie_name',
    ];
}

<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Laravel will use this configuration to handle incoming CORS requests.
    | This config is critical when using SPA + Sanctum with cookies.
    |
    */

    // Aktifkan CORS hanya untuk path-path ini:
    'paths' => [
        'api/*',
        'auth/*',
        'sanctum/csrf-cookie',
    ],

    // Izinkan semua metode (GET, POST, PUT, DELETE, OPTIONS, dsb.)
    'allowed_methods' => ['*'],

    // IZINKAN HANYA frontend Vue-mu
    'allowed_origins' => [
        'https://attendify.localhost',
        'https://starter-vueapp-vo75-obhs7ks86-afriandis-projects.vercel.app',
        'https://attendances-vue.vercel.app',
        'http://vue.sewing.localhost',
        'http://sewing.glaindonesia.lan',
    ],

    // Tidak perlu pakai pattern, kecuali kamu pakai regex
    'allowed_origins_patterns' => [],

    // Header yang boleh dikirim dari frontend
    'allowed_headers' => ['*'],

    // Header yang boleh diekspos ke frontend (opsional)
    'exposed_headers' => [],

    // Berapa lama browser boleh cache preflight response (0 = tidak cache)
    'max_age' => 0,

    // WAJIB `true` agar bisa kirim cookie (Laravel session & XSRF)
    'supports_credentials' => true,

];

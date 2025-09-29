<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\QueryException;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Daftarkan middleware group 'web'
        $middleware->group('web', [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            // \App\Http\Middleware\CorsMiddleware::class,
        ]);
        $middleware->group('api', [
            // \App\Http\Middleware\CorsMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Validation error
        $exceptions->render(function (ValidationException $e, $request) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        });

        // Not found error
        $exceptions->render(function (NotFoundHttpException $e, $request) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        });

        // QueryException handler (otomatis)
        $exceptions->render(function (QueryException $e, $request) {
            $code = $e->errorInfo[1] ?? null;

            $messages = [
                1062 => ['Duplicate entry detected', 409],   // duplicate key
                1451 => ['Cannot delete or update: foreign key constraint fails', 409],
                1452 => ['Cannot add or update: foreign key constraint fails', 409],
            ];

            if (isset($messages[$code])) {
                [$msg, $status] = $messages[$code];
                return response()->json([
                    'success' => false,
                    'message' => $msg,
                ], $status);
            }

            return response()->json([
                'success' => false,
                'message' => 'Database error',
                'error'   => $e->getMessage(),
            ], 500);
        });

        // Fallback error
        $exceptions->render(function (Throwable $e, $request) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Server error',
            ], 500);
        });
    })
    ->create();

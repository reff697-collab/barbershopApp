<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
            $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'no-cache' => \App\Http\Middleware\PreventPageCaching::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Kalau user belum login coba akses halaman yang butuh auth,
        // tampilkan 404 (bukan redirect ke halaman login) - supaya
        // keberadaan sistem internal tidak mudah ditebak orang luar.
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if (! $request->expectsJson()) {
                abort(404);
            }
        });
    })->create();
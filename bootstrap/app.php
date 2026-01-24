<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// 🔥 তোমার middleware import করো
use App\Http\Middleware\JwtVerify;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // 🔥 CSRF exclude
        $middleware->validateCsrfTokens(except: [
            'backend/*',
        ]);

        // 🔥 এখানে JwtVerify group রেজিস্টার করো
        $middleware->group('jwt', [
            JwtVerify::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

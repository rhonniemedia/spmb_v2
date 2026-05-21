<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // Mengatur tujuan pengalihan (redirect) untuk middleware 'guest' jika user SUDAH login
        $middleware->redirectTo(
            '/auth/login',       // Parameter 1 (guest): Jika belum login, dilempar ke sini
            '/user/dashboard'    // Parameter 2 (auth): Jika sudah login tapi akses halaman 'guest', dilempar ke sini
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // 1. Jika belum login (Guest) mau akses halaman Auth, lempar ke login
        $middleware->redirectGuestsTo('/auth/login');

        // 2. Jika SUDAH login tapi mau akses halaman Guest (seperti /login atau /register), lempar sesuai Role!
        $middleware->redirectUsersTo(function (Request $request) {
            $user = Auth::user();

            if (in_array($user->role, ['superadmin', 'admin', 'verifikator', 'observator'])) {
                return route('admin.dashboard');
            }

            return route('dashboard'); // Route dashboard untuk user biasa
        });

        // 3. Daftarkan Alias Middleware buatan kita
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

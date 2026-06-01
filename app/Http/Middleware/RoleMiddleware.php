<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Jika belum login, lempar ke halaman login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Jika role user ada di dalam daftar akses yang diizinkan di Route, boleh lewat
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // JIKA DITOLAK (Role tidak sesuai), kembalikan ke habitatnya masing-masing
        if (in_array($user->role, ['superadmin', 'admin', 'verifikator', 'observator'])) {
            return redirect()->route('admin.dashboard');
        }

        // Jika user biasa mencoba masuk ke area admin
        return redirect()->route('dashboard');
    }
}

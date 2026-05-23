<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function create()
    {
        return view('pages.auth.login');
    }

    /**
     * Menangani request autentikasi / login.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ], [
            'email.required'    => 'Alamat email wajib diisi.',
            'email.email'       => 'Format alamat email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // 2. Coba Autentikasi (Fitur "Remember Me" opsional jika ada)
        $remember = $request->boolean('remember');

        if (!Auth::attempt($request->only('email', 'password'), $remember)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'), // atau teks kustom: 'Email atau password salah.'
            ]);
        }

        // 3. Regenerasi Session
        $request->session()->regenerate();

        // Ambil URL tujuan semula (misal link verifikasi), jika tidak ada baru arahkan ke dashboard
        $redirectUrl = redirect()->intended(route('dashboard'))->getTargetUrl();

        // 4. Pengalihan Response Sukses (Mendukung HTMX)
        if ($request->hasHeader('HX-Request')) {
            return response('', 200)
                ->header('HX-Redirect', $redirectUrl); // Mengalihkan HTMX ke URL tujuan asli
        }

        return redirect()->intended(route('dashboard'));
    }
}

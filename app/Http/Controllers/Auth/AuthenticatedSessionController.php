<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('pages.auth.login');
    }

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

        // 2. Coba Autentikasi
        $remember = $request->boolean('remember');

        if (!Auth::attempt($request->only('email', 'password'), $remember)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // 3. Regenerasi Session
        $request->session()->regenerate();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // PENENTUAN ARAH REDIRECT BERDASARKAN ROLE
        $dashboardRoute = in_array($user->role, ['superadmin', 'admin', 'verifikator', 'observator'])
            ? route('admin.dashboard')
            : route('dashboard'); // Ini nama rute untuk user biasa di web.php

        // Ambil URL tujuan semula, jika tidak ada arahkan ke dashboard sesuai role
        $redirectUrl = redirect()->intended($dashboardRoute)->getTargetUrl();

        // 4. Response Sukses (Mendukung HTMX)
        if ($request->hasHeader('HX-Request')) {
            return response('', 200)
                ->header('HX-Redirect', $redirectUrl);
        }

        return redirect()->intended($dashboardRoute);
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}

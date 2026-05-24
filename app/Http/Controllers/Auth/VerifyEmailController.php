<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    public function __invoke(Request $request, $id, $hash)
    {
        // Cari user berdasarkan ID
        $user = User::findOrFail($id);

        // Validasi hash
        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Link verifikasi tidak valid.');
        }

        // Validasi signature URL
        if (! $request->hasValidSignature()) {
            abort(403, 'Link verifikasi sudah kadaluarsa.');
        }

        // Tandai sebagai terverifikasi jika belum
        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        // Logout jika masih ada session aktif
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect()->route('verification.success');
    }
}

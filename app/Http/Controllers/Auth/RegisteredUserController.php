<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    public function store(Request $request)
    {
        try {
            // 1. Validasi Data
            $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|string|email|max:255|unique:users',
                'password' => [
                    'required',
                    'confirmed',
                    Password::min(8)
                        ->letters()
                        ->mixedCase()
                        ->numbers()
                        ->symbols()
                ],
                'agree'    => 'accepted',
            ], [
                'name.required'      => 'Nama lengkap wajib diisi.',
                'name.max'           => 'Nama terlalu panjang (maks. 255 karakter).',
                'email.required'     => 'Alamat email wajib diisi.',
                'email.email'        => 'Format alamat email tidak valid.',
                'email.unique'       => 'Alamat email ini sudah terdaftar.',
                'password.required'  => 'Password wajib diisi.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                // Pesan error kustom saat password tidak memenuhi syarat kompleksitas
                'password'           => 'Password minimal harus 8 karakter dan merupakan kombinasi dari huruf kapital, huruf kecil, angka, serta karakter khusus (simbol).',
                'agree.accepted'     => 'Anda harus menyetujui Syarat & Ketentuan.',
            ]);

            // 2. Buat User Baru
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // 3. Otomatis Login setelah daftar
            Auth::login($user);

            // 4. Kirim Email Verifikasi
            $user->sendEmailVerificationNotification();

            // 5. Respons HTMX: kirim header HX-Redirect agar browser berpindah halaman penuh
            if ($request->hasHeader('HX-Request')) {
                return response('', 200)
                    ->header('HX-Redirect', route('verification.notice'));
            }

            // Fallback untuk request non-HTMX
            return redirect()->route('verification.notice');
        } catch (ValidationException $e) {
            // Validasi gagal → kembalikan form beserta pesan error

            if ($request->hasHeader('HX-Request')) {
                /*
                 * Untuk request HTMX:
                 * Tambahkan passing data request (old input) langsung ke view 
                 * agar Alpine dapat menginisialisasi state 'fields' kembali.
                 */
                return response(
                    view('pages.auth.register', [
                        'errors' => $e->validator->errors(),
                        'name'   => $request->input('name'),
                        'email'  => $request->input('email'),
                        'agree'  => $request->has('agree'),
                    ])->render(),
                    422  // HTMX membutuhkan status 400/500-an untuk melakukan swap jika terdeteksi error
                )->withHeaders([
                    'HX-Trigger' => json_encode(['scrollToTop' => true]),
                ]);
            }

            // Fallback non-HTMX
            throw $e;
        }
    }

    public function destroy()
    {
        Auth::logout();

        // Mengarahkan ke nama route 'home'
        return redirect()->route('home');
    }
}

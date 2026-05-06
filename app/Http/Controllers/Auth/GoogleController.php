<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cari user, jika tidak ada maka buat baru
            $user = User::updateOrCreate([
                'email' => $googleUser->email,
            ], [
                'name' => $googleUser->name,
                'google_id' => $googleUser->id,
                'password' => bcrypt(Str::random(16)),
                'email_verified_at' => now(), // User Google dianggap verified otomatis
            ]);

            Auth::login($user);
            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            return redirect('/register')->with('error', 'Gagal login Google.');
        }
    }
}

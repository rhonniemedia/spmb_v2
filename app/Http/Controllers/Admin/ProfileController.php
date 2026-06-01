<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class ProfileController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $phone = null;
        $nip = null;

        // Dekripsi data jika tersedia
        try {
            if ($user->phone_number_encrypted) $phone = Crypt::decryptString($user->phone_number_encrypted);
            if ($user->nip_encrypted) $nip = Crypt::decryptString($user->nip_encrypted);
        } catch (DecryptException $e) {
            // Abaikan jika dekripsi gagal
        }

        // Siapkan data untuk dikirim ke Alpine.js di Blade
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'telephone' => $phone ?? '',
            'nip' => $nip ?? '',
            'status' => 'Aktif',
            'role' => ucfirst($user->role),
            'photo' => $user->photo ? asset('storage/' . $user->photo) : null,

            // TAMBAHAN: Cek apakah user menggunakan login Google
            'is_google_user' => !is_null($user->google_id),
        ];

        return view('pages.admin.profil.profil', compact('userData'));
    }

    public function updateData(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'telephone' => 'nullable|string|max:20',
            'nip'       => 'nullable|string|max:50',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        $user->phone_number_encrypted = !empty($validated['telephone']) ? Crypt::encryptString($validated['telephone']) : null;
        $user->nip_encrypted = !empty($validated['nip']) ? Crypt::encryptString($validated['nip']) : null;

        $user->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data profil berhasil diperbarui.'
        ]);
    }

    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // TAMBAHAN: Proteksi Backend
        // Tolak request ganti password jika user terdaftar menggunakan Google
        if (!is_null($user->google_id)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Akun Anda terdaftar menggunakan Google. Password tidak dapat diubah.'
            ], 403);
        }

        $request->validate([
            'current_password' => 'required|current_password',
            'new_password'     => 'required|min:8|confirmed',
        ], [
            'current_password.current_password' => 'Password lama yang Anda masukkan salah.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.'
        ]);

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Password berhasil diperbarui.'
        ]);
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'photo.image' => 'File harus berupa gambar.',
            'photo.max'   => 'Ukuran foto maksimal adalah 2MB.',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $path = $request->file('photo')->store('photos/profiles', 'public');
        $user->photo = $path;
        $user->save();

        return response()->json([
            'status'    => 'success',
            'message'   => 'Foto profil berhasil diperbarui.',
            'photo_url' => asset('storage/' . $path)
        ]);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RegistrationData;
use Illuminate\Validation\ValidationException;

class ApplicantAuthController extends Controller
{
    // Tampilkan halaman form cek kelulusan / login pendaftar
    public function showLoginForm()
    {
        return view('pages.pengumuman.cek-kelulusan');
    }

    // Proses autentikasi mencocokkan NISN (Hash) & Nomor Registrasi
    public function login(Request $request)
    {
        $request->validate([
            'nisn'                => 'required|numeric',
            'registration_number' => 'required|string',
        ], [
            'nisn.required'                => 'NISN wajib diisi.',
            'nisn.numeric'                 => 'NISN harus berupa angka.',
            'registration_number.required' => 'Nomor Registrasi wajib diisi.',
        ]);

        // Menggunakan SHA-256 sesuai dengan mutator enkripsi pada model PersonalData
        $nisnHash = hash('sha256', $request->nisn);

        // Cari data di database
        $registration = RegistrationData::where('registration_number', $request->registration_number)
            ->whereHas('personalData', function ($query) use ($nisnHash) {
                $query->where('nisn_hash', $nisnHash);
            })
            ->first();

        if (!$registration) {
            throw ValidationException::withMessages([
                'nisn' => 'Data tidak ditemukan. Periksa kembali NISN dan Nomor Registrasi Anda.',
            ]);
        }

        // Set session tanda lulus / tanda masuk area daftar ulang
        $request->session()->put('kelulusan_id', $registration->id);

        // Dukungan penuh untuk respons HTMX Redirect
        if ($request->hasHeader('HX-Request')) {
            return response('', 200)->header('HX-Redirect', route('daftar_ulang.hasil_seleksi'));
        }

        return redirect()->route('daftar_ulang.hasil_seleksi');
    }

    // Keluar dari session siswa
    public function logout(Request $request)
    {
        $request->session()->forget('kelulusan_id');
        return redirect()->route('applicant.login');
    }
}

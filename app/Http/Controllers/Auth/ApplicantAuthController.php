<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\RegistrationData;
use App\Models\SelectionResult;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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

        // Cari data di database beserta relasinya
        $registration = RegistrationData::with(['personalData.user', 'latestSelectionResult'])
            ->where('registration_number', $request->registration_number)
            ->whereHas('personalData', function ($query) use ($nisnHash) {
                $query->where('nisn_hash', $nisnHash);
            })
            ->first();

        if (!$registration) {
            throw ValidationException::withMessages([
                'nisn' => 'Data tidak ditemukan. Periksa kembali NISN dan Nomor Registrasi Anda.',
            ]);
        }

        // Hasil kelulusan sekarang berasal dari SelectionResult (batch penjenjangan terbaru)
        // via relasi resmi latestSelectionResult() — SUMBER TUNGGAL yang sama
        // dipakai di dashboard & cetak PDF, agar tidak ada lagi perbedaan data.
        $selectionResult = $registration->latestSelectionResult;

        if (!$selectionResult) {
            throw ValidationException::withMessages([
                'nisn' => 'Hasil seleksi untuk pendaftaran ini belum tersedia. Silakan cek kembali nanti.',
            ]);
        }

        // =========================================================================
        // ALUR BARU: Simpan data identitas penting ke session
        // =========================================================================
        $request->session()->put('kelulusan_id', $selectionResult->id);

        // Kita simpan plain NISN asli ke session agar nanti saat tombol "Lanjut" diklik,
        // kita tetap bisa generate email berbasis NISN pendaftar jika email biodatanya kosong.
        $request->session()->put('registration_nisn', $request->nisn);

        // Semua status (accepted, process, rejected) dilempar ke halaman pengumuman yang sama
        $redirectUrl = route('daftar_ulang.hasil_seleksi');
        // =========================================================================

        // Modal di landing page submit via fetch dengan Accept: application/json
        if ($request->expectsJson()) {
            return response()->json([
                'redirect' => $redirectUrl,
            ]);
        }

        // Dukungan penuh untuk respons HTMX Redirect
        if ($request->hasHeader('HX-Request')) {
            return response('', 200)->header('HX-Redirect', $redirectUrl);
        }

        return redirect($redirectUrl);
    }

    // Tampilkan halaman hasil seleksi berdasarkan session 'kelulusan_id'
    public function hasilSeleksi(Request $request)
    {
        $kelulusanId = $request->session()->get('kelulusan_id');

        if (!$kelulusanId) {
            return redirect()->route('applicant.login')
                ->withErrors(['nisn' => 'Sesi Anda telah berakhir. Silakan cek kelulusan kembali.']);
        }

        $selectionResult = SelectionResult::with(['registration.personalData', 'acceptedConcentration'])
            ->find($kelulusanId);

        if (!$selectionResult) {
            $request->session()->forget('kelulusan_id');
            return redirect()->route('applicant.login');
        }

        // Pemetaan status enum SelectionResult ('process' | 'accepted' | 'rejected')
        // ke status yang ditampilkan di halaman pengumuman.
        $statusKelulusan = match ($selectionResult->status) {
            'accepted' => 'diterima',
            'rejected' => 'ditolak',
            default    => 'proses', // status 'process' — belum final, masih dalam penjenjangan
        };

        $siswa = (object) [
            'nama'             => $selectionResult->registration->personalData->full_name ?? '-',
            'no_pendaftaran'   => $selectionResult->registration->registration_number ?? '-',
            // TODO: sesuaikan 'name' dengan nama kolom asli di tabel concentrations jika berbeda
            'pilihan_diterima' => $selectionResult->acceptedConcentration->name ?? '-',
        ];

        return view('pages.user.kelulusan', [
            'status_kelulusan' => $statusKelulusan,
            'siswa'            => $siswa,
        ]);
    }

    public function prosesMasukDaftarUlang(Request $request)
    {
        // Ambil data dari session yang di-set saat login/cek kelulusan
        $kelulusanId = session('kelulusan_id');
        $nisn = session('registration_nisn');

        if (!$kelulusanId) {
            return redirect()->route('home')->with('error', 'Sesi habis, silakan cek ulang.');
        }

        // Cari data SelectionResult beserta relasinya menuju RegistrationData
        // Catatan: Pastikan nama relasi di model SelectionResult menuju ke RegistrationData adalah 'registrationData'
        $selectionResult = SelectionResult::with('registration.personalData.user')->find($kelulusanId);

        if (!$selectionResult || $selectionResult->status !== 'accepted') {
            return abort(403, 'Akses ditolak.');
        }

        $registration = $selectionResult->registration;
        $user = $registration->personalData->user;

        // --- PROSES PEMBUATAN AKUN GAIB JIKA BELUM ADA ---
        if (!$user) {
            // Tentukan email: gunakan dari biodata, jika kosong gunakan plain NISN dari session
            $emailUser = $registration->personalData->email ?? ($nisn . '@smkn1rl.sch.id');

            // Pastikan email tidak duplikat di tabel users
            $user = User::where('email', $emailUser)->first();

            if (!$user) {
                $user = User::create([
                    'name'              => $registration->personalData->full_name ?? 'Pendaftar ' . $registration->registration_number,
                    'email'             => $emailUser,
                    'password'          => Hash::make(Str::random(16)),
                    'role'              => 'user',
                    'email_verified_at' => now(),
                ]);
            }

            // Hubungkan data pribadi dengan user_id baru
            $registration->personalData->update([
                'user_id' => $user->id,
            ]);
        }

        if (!$user->email_verified_at) {
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        // Berikan akses Auth penuh untuk masuk ke area internal siswa
        Auth::login($user);

        // Bersihkan session pengecekan kelulusan karena user sudah resmi login
        session()->forget(['kelulusan_id', 'registration_nisn']);

        // Arahkan ke dashboard internal khusus pendaftar yang lulus
        return redirect()->route('dashboard');
    }

    // Keluar dari session siswa
    public function logout(Request $request)
    {
        $request->session()->forget('kelulusan_id');
        return redirect()->route('home');
    }
}

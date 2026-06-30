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
        $registration = RegistrationData::with(['personalData.user'])
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
        $selectionResult = SelectionResult::where('registration_id', $registration->id)
            ->latestBatch()
            ->first();

        if (!$selectionResult) {
            throw ValidationException::withMessages([
                'nisn' => 'Hasil seleksi untuk pendaftaran ini belum tersedia. Silakan cek kembali nanti.',
            ]);
        }

        // Set session tanda lulus / tanda masuk area pengumuman (dipakai untuk status process/rejected)
        $request->session()->put('kelulusan_id', $selectionResult->id);

        // =========================================================================
        // STATUS DITERIMA ('accepted') → buat/tautkan akun, login, lalu LANGSUNG
        // ke Dashboard (route ber-middleware 'auth', BUKAN 'guest').
        //
        // Ini sengaja dipisah dari redirect default di bawah, karena route
        // 'daftar_ulang.hasil_seleksi' ada di dalam grup middleware 'guest' —
        // begitu user sudah di-Auth::login(), guest middleware akan MENOLAK
        // akses ke route itu dan menyebabkan redirect nyasar. Dashboard tidak
        // punya masalah ini karena memang dilindungi middleware 'auth'.
        // =========================================================================
        if ($selectionResult->status === 'accepted') {

            $user = $registration->personalData->user;

            if (!$user) {
                // Tentukan email: gunakan email dari biodata jika ada,
                // jika kosong, generate dari NISN @smkn1rl.sch.id
                $emailUser = $registration->personalData->email ?? ($request->nisn . '@smkn1rl.sch.id');

                // Pastikan email tidak duplikat di tabel users
                $user = User::where('email', $emailUser)->first();

                if (!$user) {
                    // Buat data user baru jika benar-benar belum ada
                    $user = User::create([
                        'name'              => $registration->personalData->full_name ?? 'Pendaftar ' . $request->registration_number,
                        'email'             => $emailUser,
                        'password'          => Hash::make(Str::random(16)),
                        'role'              => 'user',
                        'email_verified_at' => now(),
                    ]);
                }

                // Hubungkan data pribadi dengan user_id yang baru.
                // PENTING: linking di kolom personal_data.user_id, BUKAN registration_data,
                // karena tabel registration_data tidak punya kolom user_id sama sekali
                // (lihat migration create_registration_data_table) — seluruh dashboard
                // (UserDashboardController) juga mencari lewat PersonalData::where('user_id', ...).
                $registration->personalData->update([
                    'user_id' => $user->id,
                ]);
            }

            // Jaga-jaga: kalau akun ditemukan tapi belum pernah verified (misal dibuat
            // manual oleh admin tanpa email_verified_at), set di sini supaya tidak
            // terblokir middleware 'verified' saat masuk ke dashboard.
            if (!$user->email_verified_at) {
                $user->forceFill(['email_verified_at' => now()])->save();
            }

            // Berikan akses Auth penuh untuk masuk ke Dashboard Daftar Ulang
            Auth::login($user);

            $redirectUrl = route('dashboard');
        } else {
            // Status 'process' / 'rejected' → tetap alur lama berbasis session,
            // tanpa login, tetap di halaman publik (guest).
            $redirectUrl = route('daftar_ulang.hasil_seleksi');
        }
        // =========================================================================

        // Modal di landing page submit via fetch dengan Accept: application/json,
        // jadi kembalikan URL redirect dalam JSON agar JS bisa window.location ke sana.
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

    // Keluar dari session siswa
    public function logout(Request $request)
    {
        $request->session()->forget('kelulusan_id');
        return redirect()->route('home');
    }
}

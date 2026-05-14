<?php

use App\Http\Controllers\Admin\VerifikasiController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\ParentDataController;
use App\Http\Controllers\PersonalDataController;
use App\Models\Announcement;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// --- GOOGLE AUTH ---
Route::get('auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'callback']);

// --- REGISTRASI MANUAL ---
Route::get('/register', fn() => view('pages.auth.register'))->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

// --- VERIFIKASI EMAIL ---
Route::get('/email/verify', fn() => view('pages.auth.verify-email'))->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

// --- DASHBOARD ---
Route::get('/dashboard', fn() => view('dashboard'))->middleware(['auth', 'verified'])->name('user.dashboard');

// ADMIN DASHBOARD
Route::get('/admin/dashboard', fn() => view('pages.admin.dashboard'))->name('admin.dashboard');
Route::get('/admin/verification', fn() => view('pages.admin.verifikasi'))->name('admin.verifikasi');
Route::get('/admin/announcement', fn() => view('pages.admin.pengumuman'))->name('admin.pengumuman');

Route::prefix('verifikasi')->name('verifikasi.')->group(function () {
    Route::get('/', [VerifikasiController::class, 'index'])->name('index');
    Route::get('/{noPendaftaran}', [VerifikasiController::class, 'show'])->name('show');
    Route::post('/{noPendaftaran}/keputusan', [VerifikasiController::class, 'keputusan'])->name('keputusan');
    Route::post('/{noPendaftaran}/dokumen/{dokId}', [VerifikasiController::class, 'updateDokumen'])->name('dokumen.update');
});

Route::view('/user/dashboard', 'pages.user.dashboard')->name('dashboard');
Route::view('/user/personal-data', 'pages.user.biodata')->name('biodata');
Route::view('/user/re-registration', 'pages.user.daftar-ulang')->name('daftar-ulang');
Route::get('/user/support', [FaqController::class, 'index'])->name('bantuan');
Route::get('/user/announcements', [AnnouncementController::class, 'index'])->name('pengumuman');

Route::middleware(['auth', 'verified'])->prefix('biodata')->name('biodata.')->group(function () {

    // ④  Halaman utama biodata (load form + prefill jika ada data lama)
    Route::get('/', [PersonalDataController::class, 'index'])->name('index');

    // ⑤  Step 1 — Data Pribadi (identitas + keluarga + kondisi khusus)
    Route::post('/step/1', [PersonalDataController::class, 'saveStep1'])->name('step1');

    // ⑥  Step 2 — Alamat + Kontak + Transportasi
    Route::post('/step/2', [PersonalDataController::class, 'saveStep2'])->name('step2');

    // ⑦  Step 3 — Data Orang Tua / Wali
    Route::post('/step/3', [ParentDataController::class, 'saveStep3'])->name('step3');

    // ⑧  Step 4 — Riwayat Pendidikan
    Route::post('/step/4', [PersonalDataController::class, 'saveStep4'])->name('step4');

    // ⑨  Step 5 — Upload Pas Foto
    Route::post('/step/5', [PersonalDataController::class, 'saveStep5'])->name('step5');

    // ⑩  Summary — Partial HTML untuk step 6 (HTMX GET, swap #summary-container)
    Route::get('/summary', [PersonalDataController::class, 'summary'])->name('summary');

    // Simpan Draft — partial save tanpa validasi ketat (dari tombol "Simpan Draft")
    Route::post('/draft', [PersonalDataController::class, 'saveDraft'])->name('draft');

    // ⑪  Submit Final — ubah profile_status → final
    Route::post('/submit', [PersonalDataController::class, 'submit'])->name('submit');
});

// --- LOGOUT ---
Route::post('/logout', [RegisteredUserController::class, 'destroy'])->name('logout');

// Proses pengiriman ulang link email
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

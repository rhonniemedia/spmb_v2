<?php

use App\Http\Controllers\Admin\VerifikasiController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\ParentDataController;
use App\Http\Controllers\PersonalDataController;
use App\Http\Controllers\RegistrationDataController;
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

Route::middleware(['auth', 'verified'])->group(function () {

    // --- KELOMPOK USER ---
    Route::prefix('user')->group(function () {
        Route::view('/dashboard', 'pages.user.dashboard')->name('dashboard');

        // Halaman Utama Biodata (Tampilan Form)
        Route::get('/biodata', [PersonalDataController::class, 'index'])->name('biodata');

        // --- SUB-KELOMPOK PROSES BIODATA (POST & DATA) ---
        // Semua URL di bawah ini akan menjadi: /user/biodata/step/1, dst.
        Route::prefix('biodata')->name('biodata.')->group(function () {
            Route::post('/step/1', [PersonalDataController::class, 'saveStep1'])->name('step1');
            Route::post('/step/2', [PersonalDataController::class, 'saveStep2'])->name('step2');
            Route::post('/step/3', [ParentDataController::class, 'saveStep3'])->name('step3');
            Route::post('/step/4', [PersonalDataController::class, 'saveStep4'])->name('step4');
            Route::post('/step/5', [PersonalDataController::class, 'saveStep5'])->name('step5');

            Route::get('/summary', [PersonalDataController::class, 'summary'])->name('summary');
            Route::post('/draft', [PersonalDataController::class, 'saveDraft'])->name('draft');
            Route::post('/submit', [PersonalDataController::class, 'submit'])->name('submit');
        });

        // Halaman Utama Biodata (Tampilan Form)
        Route::get('/registration', [RegistrationDataController::class, 'index'])->name('registration');

        // --- SUB-KELOMPOK PROSES PENDAFTARAN (POST & DATA) ---
        // Semua URL di bawah ini akan menjadi: /user/pendaftaran/step/1, dst.
        Route::prefix('registration')->name('registration.')->group(function () {
            Route::post('/step/1', [RegistrationDataController::class, 'saveStepNilai'])->name('step1');
            Route::post('/step/2', [RegistrationDataController::class, 'saveStepJalur'])->name('step2');
            Route::post('/step/3', [RegistrationDataController::class, 'saveStepZonasi'])->name('step3');
            Route::post('/step/4', [RegistrationDataController::class, 'saveStepJurusan'])->name('step4');
            Route::post('/step/5', [RegistrationDataController::class, 'saveStepPrestasi'])->name('prestasi');
            Route::post('/step/6', [RegistrationDataController::class, 'saveStepAfirmasi'])->name('afirmasi');

            Route::post('/zoning/distance-calculation', [RegistrationDataController::class, 'hitungJarak'])->name('zonasi.hitung');
            Route::get('/summary', [RegistrationDataController::class, 'summary'])->name('summary');
            Route::post('/draft', [RegistrationDataController::class, 'saveDraft'])->name('draft');
            Route::post('/submit', [RegistrationDataController::class, 'submit'])->name('submit');
            Route::get('/success', [RegistrationDataController::class, 'successScreen'])->name('success');
        });

        Route::view('/re-registration', 'pages.user.daftar-ulang')->name('daftar-ulang');
        Route::get('/support', [FaqController::class, 'index'])->name('bantuan');
        Route::get('/announcements', [AnnouncementController::class, 'index'])->name('pengumuman');
    });
});

// --- LOGOUT ---
Route::post('/logout', [RegisteredUserController::class, 'destroy'])->name('logout');

// Proses pengiriman ulang link email
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

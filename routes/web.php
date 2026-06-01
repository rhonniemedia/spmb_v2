<?php

use App\Http\Controllers\Admin\CetakBuktiDaftarController;
use App\Http\Controllers\Admin\ObservationController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RegistrationDataController as AdminRegistrationDataController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VerifikasiController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ParentDataController;
use App\Http\Controllers\PersonalDataController;
use App\Http\Controllers\RegistrationDataController as UserRegistrationDataController;
use App\Http\Controllers\UserDashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ============================================================
// GUEST ROUTES
// ============================================================
Route::middleware('guest')->group(function () {

    // Landing Page
    Route::get('/', [LandingPageController::class, 'index'])->name('home');

    // Auth Manual
    Route::get('/auth/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/auth/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

    Route::get('/auth/register', fn() => view('pages.auth.register'))->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');

    // Google OAuth
    Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
});

// ============================================================
// AUTH ROUTES (login required, email tidak harus verified)
// ============================================================
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Halaman "silakan verifikasi email" — wajib ada, dipanggil middleware 'verified'
    Route::get('/email/verify', fn() => view('pages.auth.verify-email'))->name('verification.notice');

    // Kirim ulang link verifikasi email
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->middleware('throttle:6,1')->name('verification.send');
});

// ============================================================
// EMAIL VERIFICATION — di luar auth agar bisa diakses dari device/browser manapun
// ============================================================

// Verifikasi email via link — cukup signed, tidak butuh session login aktif
Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)
    ->middleware('signed')
    ->name('verification.verify');

// Halaman sukses verifikasi
Route::get('/email/verified', fn() => view('pages.auth.email-verified'))->name('verification.success');

// ============================================================
// ADMIN ROUTES (login required)
// ============================================================
Route::middleware(['auth', 'role:superadmin,admin,verifikator,observator'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', fn() => view('pages.admin.dashboard'))->name('dashboard');
    // Route::get('/observation', [ObservationController::class, 'index'])->name('observasi');
    // web.php
    Route::get('/verifikasi/create', fn() => view('pages.admin.verifikasi.partials.form'))->name('verifikasi.create');
    Route::get('/announcement', fn() => view('pages.admin.pengumuman'))->name('pengumuman');

    // Verifikasi Pendaftaran
    Route::prefix('verifikasi')->name('verifikasi.')->group(function () {
        Route::get('/{noPendaftaran}', [VerifikasiController::class, 'show'])->name('show');
        Route::post('/{noPendaftaran}/keputusan', [VerifikasiController::class, 'keputusan'])->name('keputusan');
        Route::post('/{noPendaftaran}/dokumen/{dokId}', [VerifikasiController::class, 'updateDokumen'])->name('dokumen.update');
    });

    // Mengelompokkan rute di bawah prefix 'data' dan name 'pendaftar'
    Route::prefix('data')->name('pendaftar.')->group(function () {
        Route::get('/',        [AdminRegistrationDataController::class, 'index'])->name('index');
        Route::get('/create',  [AdminRegistrationDataController::class, 'create'])->name('create');
        Route::post('/',       [AdminRegistrationDataController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminRegistrationDataController::class, 'edit'])->name('edit');
        Route::put('/{id}',    [AdminRegistrationDataController::class, 'update'])->name('update');
        Route::get('/{id}/cetak', [CetakBuktiDaftarController::class, 'cetakBukti'])->name('cetak');
    });

    Route::prefix('observation')->name('observasi.')->group(function () {
        Route::get('/',        [ObservationController::class, 'index'])->name('index');
        Route::get('/create',  [ObservationController::class, 'create'])->name('create');
        Route::post('/',       [ObservationController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ObservationController::class, 'edit'])->name('edit');
        Route::put('/{id}',    [ObservationController::class, 'update'])->name('update');
    });

    Route::middleware(['role:superadmin'])->prefix('user-data')->name('pengguna.')->group(function () {
        Route::get('/',        [UserController::class, 'index'])->name('index');
        Route::post('/',       [UserController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}',    [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('profile')->name('profil.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/data', [ProfileController::class, 'updateData'])->name('update-data');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('update-password');
        Route::post('/photo', [ProfileController::class, 'updatePhoto'])->name('update-photo');
    });
});


// ============================================================
// USER ROUTES (login + email verified)
// ============================================================
Route::middleware(['auth', 'verified', 'role:user'])->prefix('user')->group(function () {

    // Dashboard
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // Notifikasi
    Route::get('/notifications/dropdown', [NotificationController::class, 'dropdown'])->name('notifications.dropdown');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');

    // Biodata — Halaman Utama
    Route::get('/biodata', [PersonalDataController::class, 'index'])->name('biodata');

    // Biodata — Proses Step
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

    // Pendaftaran — Halaman Utama
    Route::get('/registration', [UserRegistrationDataController::class, 'index'])->name('registration');

    // Pendaftaran — Proses Step
    Route::prefix('registration')->name('registration.')->group(function () {
        Route::post('/step/1', [UserRegistrationDataController::class, 'saveStepNilai'])->name('step1');
        Route::post('/step/2', [UserRegistrationDataController::class, 'saveStepJalur'])->name('step2');
        Route::post('/step/3', [UserRegistrationDataController::class, 'saveStepZonasi'])->name('step3');
        Route::post('/step/4', [UserRegistrationDataController::class, 'saveStepJurusan'])->name('step4');
        Route::post('/step/5', [UserRegistrationDataController::class, 'saveStepPrestasi'])->name('prestasi');
        Route::post('/step/6', [UserRegistrationDataController::class, 'saveStepAfirmasi'])->name('afirmasi');

        Route::post('/zoning/distance-calculation', [UserRegistrationDataController::class, 'hitungJarak'])->name('zonasi.hitung');
        Route::get('/summary', [UserRegistrationDataController::class, 'summary'])->name('summary');
        Route::post('/draft', [UserRegistrationDataController::class, 'saveDraft'])->name('draft');
        Route::post('/submit', [UserRegistrationDataController::class, 'submit'])->name('submit');
        Route::get('/success', [UserRegistrationDataController::class, 'successScreen'])->name('success');
    });

    // Halaman Lainnya
    Route::view('/re-registration', 'pages.user.daftar-ulang')->name('daftar-ulang');
    Route::get('/support', [FaqController::class, 'index'])->name('bantuan');
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('pengumuman');
});

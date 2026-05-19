@extends('layouts.auth')

@section('title', 'Register')

@section('content')

<div class="flex-1 flex items-center justify-center p-6 py-12">
    <div class="w-full" style="max-width: 640px;">

        <!-- Mobile logo -->
        <div class="flex justify-center mb-8 lg:hidden fade-up">
            <a href="index.html" class="flex items-center gap-3">
                <div class="logo-badge"><i class="fa-solid fa-graduation-cap text-white text-xl"></i></div>
                <div>
                    <p style="font-weight:800;font-size:0.95rem;color:var(--foreground);">SMK Negeri 1</p>
                    <p style="font-size:0.72rem;color:var(--secondary);">Rejang Lebong</p>
                </div>
            </a>
        </div>

        <!-- Card -->
        <div class="auth-card p-8 fade-up delay-1">

            <!-- Header -->
            <div class="mb-7">
                <h2 style="font-size:1.6rem;font-weight:800;color:var(--foreground);margin-bottom:6px;">Buat Akun Baru</h2>
                <p style="font-size:0.88rem;color:var(--secondary);">Sudah punya akun? <a href="{{ route('login') }}" style="color:var(--primary);font-weight:600;text-decoration:none;">Masuk di sini</a></p>
            </div>

            <!-- Form -->
            <form onsubmit="handleRegister(event)" id="registerForm" novalidate>

                <!-- Grid 2 kolom -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <!-- Name -->
                    <div class="fade-up delay-3">
                        <label style="display:block;font-size:0.83rem;font-weight:600;color:var(--foreground);margin-bottom:8px;">Nama Lengkap</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-user input-icon"></i>
                            <input type="text" id="regName" class="input-field" placeholder="Nama sesuai KK / Ijazah" required autocomplete="name" oninput="validateName()" />
                            <span class="input-status" id="nameStatus"></span>
                        </div>
                        <p class="field-error" id="nameError" style="display:none;"><i class="fa-solid fa-circle-exclamation"></i> <span></span></p>
                    </div>

                    <!-- Email -->
                    <div class="fade-up delay-3">
                        <label style="display:block;font-size:0.83rem;font-weight:600;color:var(--foreground);margin-bottom:8px;">Alamat Email</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-envelope input-icon"></i>
                            <input type="email" id="regEmail" class="input-field" placeholder="contoh@email.com" required autocomplete="email" oninput="validateEmail()" />
                            <span class="input-status" id="emailStatus"></span>
                        </div>
                        <p class="field-error" id="emailError" style="display:none;"><i class="fa-solid fa-circle-exclamation"></i> <span></span></p>
                    </div>

                    <!-- Password -->
                    <div class="fade-up delay-4">
                        <label style="display:block;font-size:0.83rem;font-weight:600;color:var(--foreground);margin-bottom:8px;">Password</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-lock input-icon"></i>
                            <input type="password" id="regPassword" class="input-field" placeholder="Minimal 8 karakter" required style="padding-right:80px;" oninput="validatePassword()" />
                            <button type="button" class="toggle-password" style="right:40px;" onclick="togglePwd('regPassword', this)">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <span class="input-status" id="passStatus" style="right:16px;"></span>
                        </div>
                        <!-- Strength bar -->
                        <div id="strengthWrap" style="display:none;margin-top:8px;">
                            <div class="strength-bar-wrap">
                                <div class="strength-seg" id="seg1"></div>
                                <div class="strength-seg" id="seg2"></div>
                                <div class="strength-seg" id="seg3"></div>
                                <div class="strength-seg" id="seg4"></div>
                            </div>
                            <p class="strength-label" id="strengthLabel" style="color:var(--secondary);"></p>
                        </div>
                        <p class="field-error" id="passError" style="display:none;"><i class="fa-solid fa-circle-exclamation"></i> <span></span></p>
                    </div>

                    <!-- Confirm Password -->
                    <div class="fade-up delay-4">
                        <label style="display:block;font-size:0.83rem;font-weight:600;color:var(--foreground);margin-bottom:8px;">Konfirmasi Password</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-lock input-icon"></i>
                            <input type="password" id="regConfirm" class="input-field" placeholder="Ulangi password" required style="padding-right:80px;" oninput="validateConfirm()" />
                            <button type="button" class="toggle-password" style="right:40px;" onclick="togglePwd('regConfirm', this)">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <span class="input-status" id="confirmStatus" style="right:16px;"></span>
                        </div>
                        <p class="field-error" id="confirmError" style="display:none;"><i class="fa-solid fa-circle-exclamation"></i> <span></span></p>
                    </div>

                </div>

                <!-- Terms (full width) -->
                <div class="flex items-start gap-3 fade-up delay-5" style="margin-top:20px;">
                    <input type="checkbox" id="agreeTerms" class="custom-checkbox" style="margin-top:1px;" />
                    <label for="agreeTerms" style="font-size:0.83rem;color:var(--secondary);cursor:pointer;line-height:1.5;">
                        Saya menyetujui <a href="#" style="color:var(--primary);font-weight:600;text-decoration:none;">Syarat & Ketentuan</a> serta <a href="#" style="color:var(--primary);font-weight:600;text-decoration:none;">Kebijakan Privasi</a> SPMB SMK Negeri 1
                    </label>
                </div>

                <!-- Submit (full width) -->
                <div class="fade-up delay-6 pt-1" style="margin-top:20px;">
                    <button type="submit" class="btn-primary" id="btnRegister">
                        <span id="btnRegText">Buat Akun Sekarang</span>
                        <span id="btnRegSpinner" style="display:none;"><i class="fa-solid fa-circle-notch fa-spin mr-2"></i>Memproses...</span>
                    </button>
                </div>

            </form>

            <!-- Divider -->
            <div class="divider my-6 fade-up delay-2">atau daftar dengan</div>

            <!-- Google Button -->
            <a href="{{ route('google.login') }}" class="btn-google fade-up delay-2" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <svg width="20" height="20" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" />
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                </svg>
                Masuk dengan Google
            </a>

        </div>

        <!-- Back -->
        <p class="text-center mt-3 fade-up delay-6">
            <a href="{{ route('home') }}" style="font-size:0.82rem;color:var(--secondary);text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                <i class="fa-solid fa-arrow-left" style="font-size:0.75rem;"></i> Kembali ke Beranda
            </a>
        </p>
    </div>
</div>

@endsection
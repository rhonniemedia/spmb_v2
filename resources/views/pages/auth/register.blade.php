@extends('layouts.auth')

@section('title', 'Register')

@section('content')

<div class="flex-1 flex items-center justify-center p-6 py-12">
    <div class="w-full max-w-2xl">

        {{-- Mobile logo --}}
        <div class="flex justify-center mb-8 lg:hidden fade-up">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="logo-badge"><i class="fa-solid fa-graduation-cap text-white text-xl"></i></div>
                <div>
                    <p class="font-extrabold text-sm" style="color:var(--foreground)">SMK Negeri 1</p>
                    <p class="text-xs" style="color:var(--secondary)">Rejang Lebong</p>
                </div>
            </a>
        </div>

        {{-- Card Container Utama yang Membungkus Area Swap --}}
        <div class="auth-card p-8 fade-up delay-1" id="registerCard" x-data="registerForm()" x-init="init()">

            {{-- Header --}}
            <div class="mb-7">
                <h2 class="text-3xl font-extrabold mb-1" style="color:var(--foreground)">Buat Akun Baru</h2>
                <p class="text-sm" style="color:var(--secondary)">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-semibold no-underline" style="color:var(--primary)">Masuk di sini</a>
                </p>
            </div>

            {{-- Banner Error Server --}}
            @if ($errors->any())
            <div id="serverErrorBanner" class="flex items-start gap-3 p-3 mb-5 rounded-xl border text-red-500 bg-red-50 border-red-200 fade-up">
                <i class="fa-solid fa-triangle-exclamation mt-0.5 shrink-0"></i>
                <div>
                    <p class="font-semibold mb-1 text-sm">Pendaftaran gagal. Periksa kembali:</p>
                    <ul class="list-disc pl-4 text-sm space-y-0.5">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            {{-- Form: Target Swap diganti ke Outer HTML Card atau Form yang dibungkus ulang --}}
            <form
                method="POST"
                action="{{ route('register.store') }}"
                id="registerForm"
                novalidate
                @submit="submitForm($event)">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Nama Lengkap --}}
                    <div class="fade-up delay-3">
                        <label class="block text-sm font-semibold mb-2" style="color:var(--foreground)">Nama Lengkap</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-user input-icon"></i>
                            <input
                                type="text"
                                name="name"
                                id="regName"
                                class="input-field"
                                :class="{
                                    'is-error': errors.name || {{ $errors->has('name') ? 'true' : 'false' }},
                                    'is-valid': fields.name && !errors.name && !{{ $errors->has('name') ? 'true' : 'false' }}
                                }"
                                placeholder="Nama sesuai KK / Ijazah"
                                value="{{ old('name', $name ?? '') }}"
                                autocomplete="name"
                                x-model="fields.name"
                                @input="validateName()"
                                @blur="validateName()" />
                        </div>
                        <p x-show="errors.name" x-cloak class="flex items-center gap-1 mt-1.5 text-xs font-medium text-red-500">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span x-text="errors.name"></span>
                        </p>
                        @error('name')
                        <p class="flex items-center gap-1 mt-1.5 text-xs font-medium text-red-500">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span>{{ $message }}</span>
                        </p>
                        @enderror
                    </div>

                    {{-- Alamat Email --}}
                    <div class="fade-up delay-3">
                        <label class="block text-sm font-semibold mb-2" style="color:var(--foreground)">Alamat Email</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-envelope input-icon"></i>
                            <input
                                type="email"
                                name="email"
                                id="regEmail"
                                class="input-field"
                                :class="{
                                    'is-error': errors.email || {{ $errors->has('email') ? 'true' : 'false' }},
                                    'is-valid': fields.email && !errors.email && !{{ $errors->has('email') ? 'true' : 'false' }}
                                }"
                                placeholder="contoh@email.com"
                                value="{{ old('email', $email ?? '') }}"
                                autocomplete="email"
                                x-model="fields.email"
                                @input="validateEmail()"
                                @blur="validateEmail()" />
                        </div>
                        <p x-show="errors.email" x-cloak class="flex items-center gap-1 mt-1.5 text-xs font-medium text-red-500">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span x-text="errors.email"></span>
                        </p>
                        @error('email')
                        <p class="flex items-center gap-1 mt-1.5 text-xs font-medium text-red-500">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span>{{ $message }}</span>
                        </p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="fade-up delay-4">
                        <label class="block text-sm font-semibold mb-2" style="color:var(--foreground)">Password</label>
                        <div class="input-wrapper relative">
                            <i class="fa-solid fa-lock input-icon"></i>
                            <input
                                :type="showPassword ? 'text' : 'password'"
                                name="password"
                                id="regPassword"
                                class="input-field pr-12"
                                :class="{
                                    'is-error': errors.password || {{ $errors->has('password') ? 'true' : 'false' }},
                                    'is-valid': fields.password && !errors.password && strength >= 3 && !{{ $errors->has('password') ? 'true' : 'false' }}
                                }"
                                placeholder="Minimal 8 karakter"
                                x-model="fields.password"
                                @input="validatePassword(); validateConfirm()"
                                @blur="validatePassword()" />
                            <button
                                type="button"
                                tabindex="-1"
                                class="absolute right-3 top-1/2 -translate-y-1/2 bg-transparent border-0 cursor-pointer flex items-center justify-center p-1 transition-colors"
                                style="color:var(--secondary)"
                                @click="showPassword = !showPassword">
                                <i :class="showPassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                            </button>
                        </div>
                        <template x-if="fields.password.length > 0">
                            <div class="mt-1.5">
                                <div class="h-1 rounded-full bg-gray-200 overflow-hidden">
                                    <div
                                        class="h-full rounded-full transition-all duration-300"
                                        :style="{ width: (strength / 4 * 100) + '%', backgroundColor: strengthColor }"></div>
                                </div>
                                <p class="text-xs font-medium mt-0.5" :style="{ color: strengthColor }" x-text="strengthLabel"></p>
                            </div>
                        </template>
                        <p x-show="errors.password" x-cloak class="flex items-center gap-1 mt-1.5 text-xs font-medium text-red-500">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span x-text="errors.password"></span>
                        </p>
                        @error('password')
                        <p class="flex items-center gap-1 mt-1.5 text-xs font-medium text-red-500">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span>{{ $message }}</span>
                        </p>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="fade-up delay-4">
                        <label class="block text-sm font-semibold mb-2" style="color:var(--foreground)">Konfirmasi Password</label>
                        <div class="input-wrapper relative">
                            <i class="fa-solid fa-lock input-icon"></i>
                            <input
                                :type="showConfirm ? 'text' : 'password'"
                                name="password_confirmation"
                                id="regConfirm"
                                class="input-field pr-12"
                                :class="{
                                    'is-error': errors.confirm,
                                    'is-valid': fields.confirm && !errors.confirm && fields.confirm === fields.password
                                }"
                                placeholder="Ulangi password"
                                x-model="fields.confirm"
                                @input="validateConfirm()"
                                @blur="validateConfirm()" />
                            <button
                                type="button"
                                tabindex="-1"
                                class="absolute right-3 top-1/2 -translate-y-1/2 bg-transparent border-0 cursor-pointer flex items-center justify-center p-1 transition-colors"
                                style="color:var(--secondary)"
                                @click="showConfirm = !showConfirm">
                                <i :class="showConfirm ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                            </button>
                        </div>
                        <p x-show="errors.confirm" x-cloak class="flex items-center gap-1 mt-1.5 text-xs font-medium text-red-500">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span x-text="errors.confirm"></span>
                        </p>
                    </div>

                </div>

                {{-- Checkbox Syarat & Ketentuan --}}
                <div class="mt-5 fade-up delay-5">
                    <div class="flex items-start gap-3">
                        <input
                            type="checkbox"
                            name="agree"
                            id="agreeTerms"
                            class="mt-0.5 w-4 h-4 rounded border-gray-300 cursor-pointer shrink-0 accent-red-500"
                            x-model="fields.agree"
                            @change="validateAgree()" />
                        <label for="agreeTerms" class="text-sm cursor-pointer leading-relaxed" style="color:var(--secondary)">
                            Saya menyetujui
                            <a href="#" class="font-semibold no-underline" style="color:var(--primary)" @click.prevent="openTerms()">Syarat &amp; Ketentuan</a>
                            pendaftaran SPMB SMK Negeri 1 Rejang Lebong
                        </label>
                    </div>
                    <p x-show="errors.agree" x-cloak class="flex items-center gap-1 mt-1.5 text-xs font-medium text-red-500">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span x-text="errors.agree"></span>
                    </p>
                    @error('agree')
                    <p class="flex items-center gap-1 mt-1.5 text-xs font-medium text-red-500">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>{{ $message }}</span>
                    </p>
                    @enderror
                </div>

                {{-- Submit Button (Sudah dibersihkan dari duplikasi div) --}}
                <div class="mt-5 fade-up delay-6">
                    <button
                        type="submit"
                        id="btnRegister"
                        class="btn-primary w-full flex items-center justify-center gap-2"
                        :disabled="isSubmitting"
                        :class="{ 'opacity-70 cursor-not-allowed': isSubmitting }">
                        <template x-if="!isSubmitting">
                            <span>Buat Akun Sekarang</span>
                        </template>
                        <template x-if="isSubmitting">
                            <span><i class="fa-solid fa-circle-notch fa-spin mr-2"></i>Memproses...</span>
                        </template>
                    </button>
                </div>

            </form>

            {{-- Divider --}}
            <div class="divider my-6 fade-up delay-2">atau daftar dengan</div>

            {{-- Google Button --}}
            <a
                href="{{ route('google.login') }}"
                class="btn-google fade-up delay-2"
                style="text-decoration:none;display:inline-flex;align-items:center;gap:8px;">
                <svg width="20" height="20" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" />
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                </svg>
                Masuk dengan Google
            </a>

        </div>

        {{-- Back to Home --}}
        <p class="text-center mt-3 fade-up delay-6">
            <a href="{{ route('home') }}" class="text-xs no-underline inline-flex items-center gap-1.5" style="color:var(--secondary)">
                <i class="fa-solid fa-arrow-left text-xs"></i> Kembali ke Beranda
            </a>
        </p>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('registerForm', () => ({
            fields: {
                name: @json(old('name', $name ?? '')),
                email: @json(old('email', $email ?? '')),
                password: '',
                confirm: '',
                agree: @json((bool) old('agree', $agree ?? false)),
            },
            errors: {
                name: '',
                email: '',
                password: '',
                confirm: '',
                agree: '',
            },
            isSubmitting: false,
            showPassword: false,
            showConfirm: false,
            strength: 0,
            strengthLabel: '',
            strengthColor: '#e5e7eb',

            init() {
                const banner = document.getElementById('serverErrorBanner');
                if (banner) {
                    banner.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
                this.isSubmitting = false;
            },

            validateName() {
                const v = this.fields.name.trim();
                if (!v) this.errors.name = 'Nama lengkap wajib diisi.';
                else if (v.length < 3) this.errors.name = 'Nama minimal 3 karakter.';
                else if (v.length > 255) this.errors.name = 'Nama terlalu panjang.';
                else this.errors.name = '';
            },

            validateEmail() {
                const v = this.fields.email.trim();
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!v) this.errors.email = 'Alamat email wajib diisi.';
                else if (!re.test(v)) this.errors.email = 'Format email tidak valid.';
                else this.errors.email = '';
            },

            validatePassword() {
                const v = this.fields.password;

                // 1. Hitung Strength Meter (Logika visual tetap sama)
                let score = 0;
                if (v.length >= 8) score++;
                if (/[A-Z]/.test(v)) score++;
                if (/[0-9]/.test(v)) score++;
                if (/[^A-Za-z0-9]/.test(v)) score++;
                this.strength = score;

                const labels = ['', 'Lemah', 'Cukup', 'Kuat', 'Sangat Kuat'];
                const colors = ['', '#ef4444', '#f97316', '#22c55e', '#16a34a'];
                this.strengthLabel = labels[score] || '';
                this.strengthColor = colors[score] || '#e5e7eb';

                // 2. Validasi Aturan Ketat (Regex Kombinasi)
                // Memastikan ada: Huruf kecil (?=.*[a-z]), Huruf besar (?=.*[A-Z]), Angka (?=.*\d), dan Simbol (?=.*[@$!%*?&...])
                const strictRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?& #_+\-=\[\]{}();':"\\|,.\/<>'`~^]).{8,}$/;

                if (!v) {
                    this.errors.password = 'Password wajib diisi.';
                } else if (!strictRegex.test(v)) {
                    this.errors.password = 'Password harus minimal 8 karakter dengan kombinasi huruf kapital, huruf kecil, angka, dan karakter khusus.';
                } else {
                    this.errors.password = '';
                }
            },

            validateConfirm() {
                const v = this.fields.confirm;
                if (!v) this.errors.confirm = 'Konfirmasi password wajib diisi.';
                else if (v !== this.fields.password) this.errors.confirm = 'Konfirmasi password tidak cocok.';
                else this.errors.confirm = '';
            },

            validateAgree() {
                this.errors.agree = this.fields.agree ? '' : 'Anda harus menyetujui Syarat & Ketentuan.';
            },

            validateAll() {
                this.validateName();
                this.validateEmail();
                this.validatePassword();
                this.validateConfirm();
                this.validateAgree();
                return !this.errors.name && !this.errors.email && !this.errors.password && !this.errors.confirm && !this.errors.agree;
            },

            submitForm(event) {
                if (!this.validateAll()) {
                    event.preventDefault();
                    return;
                }
                this.isSubmitting = true;
            },

            openTerms() {
                window.open('/syarat-ketentuan', '_blank', 'noopener,noreferrer');
            },
        }));
    });
</script>
@endpush

@endsection
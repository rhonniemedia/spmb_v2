@extends('layouts.auth')

@section('title', 'Login')

@section('content')

{{--
  DEPENDENCIES (pastikan sudah ada di layouts.auth):
    - Alpine.js  : <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    - HTMX       : <script src="https://unpkg.com/htmx.org@2.0.0"></script>
--}}

<div
  class="flex-1 flex items-center justify-center p-6 py-12"
  x-data="loginForm()"
  x-init="init()">
  <div class="w-full max-w-md">

    {{-- Mobile logo --}}
    <div class="flex justify-center mb-8 lg:hidden fade-up">
      <a href="{{ route('home') }}" class="flex items-center gap-3">
        <div class="logo-badge">
          <i class="fa-solid fa-graduation-cap text-white text-xl"></i>
        </div>
        <div>
          <p style="font-weight:800;font-size:0.95rem;color:var(--foreground);">SMK Negeri 1</p>
          <p style="font-size:0.72rem;color:var(--secondary);">Rejang Lebong</p>
        </div>
      </a>
    </div>

    {{-- Card --}}
    <div class="auth-card p-8 fade-up delay-1">

      {{-- Header --}}
      <div class="mb-8">
        <h2 style="font-size:1.6rem;font-weight:800;color:var(--foreground);margin-bottom:6px;">
          Masuk ke Akun
        </h2>
        <p style="font-size:0.88rem;color:var(--secondary);">
          Belum punya akun?
          <a href="{{ route('register') }}" style="color:var(--primary);font-weight:600;text-decoration:none;">
            Daftar Sekarang →
          </a>
        </p>
      </div>

      {{--
        Alert Error — ditampilkan oleh Alpine saat:
          1. Server merespons dengan status 422 (validasi gagal), atau
          2. HTMX menerima respons error dari controller
        Alpine merender pesan dari `errorMessage`.
      --}}
      <div
        class="alert-error mb-5"
        x-show="hasError"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-cloak>
        <i class="fa-solid fa-circle-exclamation"></i>
        <span x-text="errorMessage"></span>
      </div>

      {{--
        FORM — HTMX
        hx-post        : kirim ke route login via HTMX (controller cek header HX-Request)
        hx-trigger     : submit form
        hx-on::before-request   : jalankan Alpine beforeSend() → aktifkan spinner
        hx-on::after-request    : jalankan Alpine afterSend(event) → proses respons
        hx-headers     : sertakan CSRF token sebagai header X-XSRF-TOKEN sudah otomatis
                          via meta tag, tapi kita tetap kirim _token di body lewat hidden input
      --}}
      <form
        hx-post="{{ route('login') }}"
        method="POST" hx-trigger="submit"
        hx-swap="none"
        hx-on::before-request="beforeSend()"
        hx-on::after-request="afterSend(event)"
        class="space-y-5">
        @csrf

        {{-- Email --}}
        <div class="fade-up delay-3">
          <label style="display:block;font-size:0.83rem;font-weight:600;color:var(--foreground);margin-bottom:8px;">
            Email
          </label>
          <div class="input-wrapper" :class="{ 'input-error': fieldErrors.email }">
            <i class="fa-solid fa-envelope input-icon"></i>
            <input
              type="email"
              name="email"
              id="loginEmail"
              class="input-field"
              placeholder="contoh@email.com"
              :value="form.email"
              @input="form.email = $event.target.value; clearError('email')"
              required />
          </div>
          {{-- Pesan error per-field dari validasi Laravel --}}
          <p
            class="field-error-msg"
            x-show="fieldErrors.email"
            x-text="fieldErrors.email"
            x-cloak></p>
        </div>

        {{-- Password --}}
        <div class="fade-up delay-3">
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
            <label style="font-size:0.83rem;font-weight:600;color:var(--foreground);">Password</label>
            <a href="#" style="font-size:0.8rem;color:var(--primary);font-weight:600;text-decoration:none;">
              Lupa password?
            </a>
          </div>
          <div class="input-wrapper" :class="{ 'input-error': fieldErrors.password }">
            <i class="fa-solid fa-lock input-icon"></i>
            <input
              :type="showPassword ? 'text' : 'password'"
              name="password"
              id="loginPassword"
              class="input-field"
              placeholder="Masukkan password"
              style="padding-right:48px;"
              @input="clearError('password')"
              required />
            {{-- Toggle show/hide password via Alpine --}}
            <button
              type="button"
              class="toggle-password"
              @click="showPassword = !showPassword"
              :aria-label="showPassword ? 'Sembunyikan password' : 'Tampilkan password'">
              <i class="fa-solid" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
            </button>
          </div>
          <p
            class="field-error-msg"
            x-show="fieldErrors.password"
            x-text="fieldErrors.password"
            x-cloak></p>
        </div>

        {{-- Remember me --}}
        <div class="flex items-center gap-3 fade-up delay-4">
          <input
            type="checkbox"
            id="rememberMe"
            name="remember"
            class="custom-checkbox"
            x-model="form.remember" />
          <label for="rememberMe" style="font-size:0.85rem;color:var(--secondary);cursor:pointer;">
            Ingat saya di perangkat ini
          </label>
        </div>

        {{-- Submit --}}
        <div class="fade-up delay-4 pt-1">
          <button
            type="submit"
            class="btn-primary"
            :disabled="loading"
            :class="{ 'opacity-70 cursor-not-allowed': loading }">
            {{-- Teks normal --}}
            <span x-show="!loading">Masuk Sekarang</span>
            {{-- Spinner saat loading (Alpine toggle) --}}
            <span x-show="loading" x-cloak>
              <i class="fa-solid fa-circle-notch fa-spin mr-2"></i>Memproses...
            </span>
          </button>
        </div>

      </form>

      {{-- Divider --}}
      <div class="divider my-6 fade-up delay-2">atau masuk dengan</div>

      {{-- Google Button --}}
      <a href="{{ route('google.login') }}" class="btn-google fade-up delay-2">
        <svg width="20" height="20" viewBox="0 0 24 24">
          <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
          <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
          <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" />
          <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
        </svg>
        Masuk dengan Google
      </a>

    </div>

    {{-- Back to landing --}}
    <p class="text-center mt-3 fade-up delay-5">
      <a href="{{ route('home') }}" style="font-size:0.82rem;color:var(--secondary);text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
        <i class="fa-solid fa-arrow-left" style="font-size:0.75rem;"></i> Kembali ke Beranda
      </a>
    </p>

  </div>
</div>

<script>
  /**
   * Alpine.js component untuk form login.
   *
   * Alur kerja:
   *  1. User submit → HTMX kirim POST (dengan header HX-Request: true)
   *  2. beforeSend()  → aktifkan loading spinner
   *  3. Controller memproses:
   *       - Sukses  → return response('', 200)->header('HX-Redirect', route('dashboard'))
   *       - Gagal   → throw ValidationException → Laravel return 422 JSON
   *  4. afterSend()   → cek status respons HTMX:
   *       - 200 + HX-Redirect → HTMX otomatis redirect (ditangani oleh HTMX sendiri)
   *       - 422               → parse JSON errors, tampilkan via Alpine
   *       - lainnya           → tampilkan pesan error generik
   */
  function loginForm() {
    return {
      // --- State ---
      loading: false,
      showPassword: false,
      hasError: false,
      errorMessage: '',
      fieldErrors: {
        email: '',
        password: ''
      },
      form: {
        email: '',
        remember: false
      },

      init() {
        // Tangkap error validasi dari respons 422 yang dikirim HTMX
        // HTMX menyimpan respons terakhir di htmx.lastResponse (tersedia setelah after-request)
      },

      // Dipanggil oleh hx-on::before-request
      beforeSend() {
        this.loading = true;
        this.hasError = false;
        this.errorMessage = '';
        this.fieldErrors = {
          email: '',
          password: ''
        };
      },

      // Dipanggil oleh hx-on::after-request
      afterSend(event) {
        this.loading = false;

        const xhr = event.detail.xhr;
        const status = xhr.status;

        // 200 → HTMX sudah menangani HX-Redirect secara otomatis, tidak perlu aksi
        if (status === 200) return;

        // 422 → Validasi gagal, parse error dari Laravel
        if (status === 422) {
          try {
            const data = JSON.parse(xhr.responseText);
            const errors = data.errors ?? {};

            // Tampilkan error per-field
            this.fieldErrors.email = errors.email?.[0] ?? '';
            this.fieldErrors.password = errors.password?.[0] ?? '';

            // Tampilkan alert utama dengan pesan pertama yang ditemukan
            const firstMsg = errors.email?.[0] ?? errors.password?.[0] ?? 'Email atau password salah.';
            this.errorMessage = firstMsg;
            this.hasError = true;

          } catch {
            this.errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
            this.hasError = true;
          }
          return;
        }

        // Error lainnya (500, 429, dll.)
        if (status === 429) {
          this.errorMessage = 'Terlalu banyak percobaan. Silakan tunggu beberapa saat.';
        } else {
          this.errorMessage = 'Terjadi kesalahan server. Silakan coba lagi.';
        }
        this.hasError = true;
      },

      // Hapus error pada field tertentu saat user mulai mengetik
      clearError(field) {
        this.fieldErrors[field] = '';
        // Sembunyikan alert utama jika tidak ada error field lain
        if (!this.fieldErrors.email && !this.fieldErrors.password) {
          this.hasError = false;
        }
      },
    };
  }
</script>

@endsection
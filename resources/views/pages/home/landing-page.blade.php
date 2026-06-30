@extends('layouts.home')

@section('title', 'SMK Negeri 1 Rejang Lebong')

@section('content')

<!-- PARTICLES -->
<div id="particles" class="fixed inset-0 pointer-events-none z-0 overflow-hidden"></div>

<!-- NAVBAR -->
@include ('pages.home.partials.navbar')

<!-- HERO -->
@include ('pages.home.partials.hero')

<!-- STATISTIK -->
@include ('pages.home.partials.statistik')

<!-- TENTANG SEKOLAH -->
@include ('pages.home.partials.profil')

<!-- JURUSAN UNGGULAN -->
@include ('pages.home.partials.jurusan')

<!-- LANGKAH PENDAFTARAN -->
@include ('pages.home.partials.langkah')

<!-- JADWAL PENDAFTARAN -->
@include ('pages.home.partials.jadwal')

<!-- FASILITAS -->
@include ('pages.home.partials.fasilitas')

<!-- FAQ -->
@include ('pages.home.partials.faq')

{{-- MODAL CEK KELULUSAN (Alpine + HTMX) --}}
<div
    x-data="cekKelulusanModal()"
    x-show="isOpen"
    @open-kelulusan-modal.window="openModal()"
    @keydown.escape.window="closeModal()"
    class="fixed inset-0 z-[100] flex items-center justify-center px-4"
    x-cloak>

    {{-- Backdrop --}}
    <div
        x-show="isOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-[#080C1A]/50 backdrop-blur-sm"
        @click="closeModal()"></div>

    {{-- Modal Content --}}
    <div
        x-show="isOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        class="relative w-full max-w-md z-10"
        style="background: #FFFFFF; outline: 1px solid #F3F4F3; border-radius: 24px; box-shadow: 0 24px 64px rgba(255,20,67,0.10), 0 4px 20px rgba(0,0,0,0.08); overflow: hidden;">

        {{-- Top accent bar --}}
        <div style="height: 4px; background: linear-gradient(90deg, #FF1443, #D90F38, #F59E0B);"></div>

        <div class="p-8">
            {{-- Close Button --}}
            <button
                @click="closeModal()"
                class="absolute top-6 right-6 w-8 h-8 flex items-center justify-center transition-colors"
                style="border-radius: 12px; background: #EFF2F7; color: #6A7686;"
                onmouseover="this.style.background='rgba(255,20,67,0.08)'; this.style.color='#FF1443'"
                onmouseout="this.style.background='#EFF2F7'; this.style.color='#6A7686'">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>

            {{-- Header --}}
            <div class="mb-6">
                {{-- Icon --}}
                <div class="w-12 h-12 flex items-center justify-center mb-4" style="background: rgba(255,20,67,0.08); border-radius: 16px; border: 1px solid rgba(255,20,67,0.15);">
                    <i class="fa-solid fa-clipboard-check" style="color: #FF1443; font-size: 1.1rem;"></i>
                </div>
                <h2 class="font-display font-bold text-lg mb-1" style="color: #080C1A;">Cek Kelulusan</h2>
                <p class="text-sm" style="color: #6A7686;">Masukkan NISN dan Nomor Registrasi Anda untuk melihat hasil seleksi.</p>
            </div>

            {{-- Alert Error --}}
            <div
                x-show="hasError"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-cloak
                class="flex items-start gap-3 mb-5 text-sm"
                style="background: #FEE2E2; border: 1px solid rgba(237,107,96,0.30); border-radius: 12px; padding: 12px 14px; color: #991B1B;">
                <i class="fa-solid fa-circle-exclamation mt-0.5 shrink-0" style="color: #ED6B60;"></i>
                <span x-text="errorMessage"></span>
            </div>

            {{-- Form --}}
            <form
                class="space-y-5"
                @submit.prevent="validateAndSubmit()">
                @csrf

                {{-- Field Nomor Registrasi --}}
                <div class="space-y-2">
                    <label class="block text-sm font-semibold" style="color: #080C1A;">
                        Nomor Registrasi
                    </label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4" style="color: #6A7686;">
                            <i class="fa-solid fa-file-lines"></i>
                        </span>
                        <input
                            type="text"
                            name="registration_number"
                            placeholder="XXXXXXXXXXXX-XXXX"
                            :value="form.registration_number"
                            @input="form.registration_number = $event.target.value; clearError('registration_number')"
                            class="w-full pl-11 pr-4 py-3.5 text-sm rounded-2xl border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400 outline-none transition-all duration-200 focus:border-red-400 focus:ring-2 focus:ring-red-400/20 focus:bg-white"
                            :class="fieldErrors.registration_number ? 'border-red-400 ring-2 ring-red-400/20 bg-red-50' : ''" />
                    </div>
                    <div x-show="fieldErrors.registration_number" x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="flex items-center gap-2 text-xs text-red-700">
                        <span class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-red-100 shrink-0">
                            <i class="fa-solid fa-exclamation" style="font-size: 0.55rem; color: #ED6B60;"></i>
                        </span>
                        <span x-text="fieldErrors.registration_number"></span>
                    </div>
                </div>

                {{-- Field NISN --}}
                <div class="space-y-2">
                    <label class="block text-sm font-semibold" style="color: #080C1A;">
                        Nomor Induk Siswa Nasional (NISN)
                    </label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4" style="color: #6A7686;">
                            <i class="fa-solid fa-id-card"></i>
                        </span>
                        <input
                            type="text"
                            name="nisn"
                            placeholder="10 digit NISN"
                            :value="form.nisn"
                            @input="form.nisn = $event.target.value; clearError('nisn')"
                            class="w-full pl-11 pr-4 py-3.5 text-sm rounded-2xl border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400 outline-none transition-all duration-200 focus:border-red-400 focus:ring-2 focus:ring-red-400/20 focus:bg-white"
                            :class="fieldErrors.nisn ? 'border-red-400 ring-2 ring-red-400/20 bg-red-50' : ''" />
                    </div>
                    <div x-show="fieldErrors.nisn" x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="flex items-center gap-2 text-xs text-red-700">
                        <span class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-red-100 shrink-0">
                            <i class="fa-solid fa-exclamation" style="font-size: 0.55rem; color: #ED6B60;"></i>
                        </span>
                        <span x-text="fieldErrors.nisn"></span>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="pt-1">
                    <button
                        type="submit"
                        :disabled="loading"
                        class="flex w-full items-center justify-center gap-2.5 py-3.5 rounded-full bg-red-500 hover:bg-red-600 text-white font-bold text-base transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-red-500/40 disabled:opacity-60 disabled:cursor-not-allowed disabled:hover:translate-y-0" style="color: #FFFFFF !important;">
                        <span x-show="!loading" class="flex items-center gap-2.5">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            Cek Hasil Kelulusan
                        </span>
                        <span x-show="loading" x-cloak class="flex items-center gap-2.5">
                            <i class="fa-solid fa-circle-notch fa-spin"></i>
                            Mencari Data...
                        </span>
                    </button>
                </div>
            </form>

            {{-- Footer note --}}
            <p class="text-center text-xs mt-5" style="color: #6A7686;">
                <i class="fa-solid fa-lock text-xs mr-1" style="color: rgba(255,20,67,0.5);"></i>
                Data Anda aman dan terenkripsi
            </p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('cekKelulusanModal', () => ({
            isOpen: false,
            loading: false,
            hasError: false,
            errorMessage: '',
            fieldErrors: {
                nisn: '',
                registration_number: ''
            },
            form: {
                nisn: '',
                registration_number: ''
            },

            openModal() {
                this.isOpen = true;
                document.body.style.overflow = 'hidden';
            },
            closeModal() {
                this.isOpen = false;
                document.body.style.overflow = '';
                this.hasError = false;
                this.errorMessage = '';
                this.fieldErrors = {
                    nisn: '',
                    registration_number: ''
                };
                this.form = {
                    nisn: '',
                    registration_number: ''
                };
            },

            validateAndSubmit() {
                this.fieldErrors = {
                    nisn: '',
                    registration_number: ''
                };
                this.hasError = false;
                let valid = true;

                // Validasi NISN
                if (!this.form.nisn.trim()) {
                    this.fieldErrors.nisn = 'NISN wajib diisi.';
                    valid = false;
                } else if (!/^\d{10}$/.test(this.form.nisn.trim())) {
                    this.fieldErrors.nisn = 'NISN harus berupa 10 digit angka.';
                    valid = false;
                }

                // Validasi Nomor Registrasi
                if (!this.form.registration_number.trim()) {
                    this.fieldErrors.registration_number = 'Nomor Registrasi wajib diisi.';
                    valid = false;
                }

                // Jika tidak lolos, stop di sini
                if (!valid) return;

                // Lolos validasi client-side → kirim ke server
                this.submitToServer();
            },

            async submitToServer() {
                this.loading = true;

                try {
                    const csrfInput = document.querySelector('input[name="_token"]');
                    const token = csrfInput ? csrfInput.value : '';

                    const response = await fetch("{{ route('applicant.login.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token,
                        },
                        body: JSON.stringify(this.form),
                    });

                    // Validasi gagal di server (422)
                    if (response.status === 422) {
                        const data = await response.json();
                        const errors = data.errors || {};

                        this.fieldErrors.nisn = errors.nisn ? errors.nisn[0] : '';
                        this.fieldErrors.registration_number = errors.registration_number ? errors.registration_number[0] : '';

                        this.hasError = true;
                        this.errorMessage = this.fieldErrors.nisn || this.fieldErrors.registration_number || data.message || 'Periksa kembali data Anda.';
                        return;
                    }

                    if (!response.ok) {
                        this.hasError = true;
                        this.errorMessage = 'Terjadi kesalahan pada server. Coba lagi nanti.';
                        return;
                    }

                    // Sukses → server kirim balik URL halaman hasil seleksi
                    const data = await response.json();
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                } catch (error) {
                    this.hasError = true;
                    this.errorMessage = 'Tidak dapat terhubung ke server. Periksa koneksi Anda.';
                } finally {
                    this.loading = false;
                }
            },

            clearError(field) {
                this.fieldErrors[field] = '';
                if (!this.fieldErrors.nisn && !this.fieldErrors.registration_number) {
                    this.hasError = false;
                }
            }
        }));
    });
</script>

@endsection
@extends('layouts.user')

@section('title', 'Data Pribadi')

@section('content')

@push('styles')
<style>
    @keyframes ring-expand {
        0% {
            transform: scale(0.5);
            opacity: 0;
        }

        60% {
            transform: scale(1.15);
            opacity: 1;
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    @keyframes check-draw {
        0% {
            stroke-dashoffset: 60;
            opacity: 0;
        }

        40% {
            opacity: 1;
        }

        100% {
            stroke-dashoffset: 0;
            opacity: 1;
        }
    }

    @keyframes ring-pulse {

        0%,
        100% {
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.25);
        }

        50% {
            box-shadow: 0 0 0 14px rgba(34, 197, 94, 0);
        }
    }

    @keyframes sparkle-pop {
        0% {
            transform: scale(0) rotate(0deg);
            opacity: 0;
        }

        60% {
            transform: scale(1.2) rotate(20deg);
            opacity: 1;
        }

        100% {
            transform: scale(1) rotate(15deg);
            opacity: 0.85;
        }
    }
</style>
@endpush

<div x-data="{
        step: 1,
        totalSteps: 6,
        isSubmitted: false,
        submitResult: {},
        showWali: false,
        sameAddress: false,
        files: { foto: null },
        get progressPct() {
            return Math.round((this.step / this.totalSteps) * 100);
        },
        stepLabels: ['Data Pribadi','Alamat','Orang Tua','Pendidikan','Pas Foto','Konfirmasi'],
        stepIcons: ['fa-user','fa-location-dot','fa-people-roof','fa-book-open-reader','fa-camera','fa-clipboard-check'],
        sidebarStatus(i) {
            if (i < this.step) return 'done';
            if (i === this.step) return 'active';
            return 'pending';
        },
        init() {
            this.$watch('step', (val) => {
                if (val === 6) {
                    htmx.ajax('GET', '{{ route('biodata.summary') }}', {
                        target: '#summary-container',
                        swap: 'innerHTML'
                    });
                }
            });
        }
    }"
    x-on:biodata-submitted.window="submitResult = $event.detail; isSubmitted = true">

    {{-- ══════════════════════════════════════════
            BREADCRUMB
    ══════════════════════════════════════════ --}}
    <div x-show="!isSubmitted" class="flex items-center gap-1.5 text-[13px] text-[#6A7686] mb-4">
        <a href="{{ route('dashboard') }}" class="text-[#FF1443] no-underline font-semibold">
            <i class="fa-solid fa-house"></i> Beranda
        </a>
        <span class="text-gray-300">/</span>
        <a href="{{ route('dashboard') }}" class="text-[#FF1443] no-underline font-semibold">Dashboard</a>
        <span class="text-gray-300">/</span>
        <span>Lengkapi Biodata</span>
    </div>

    {{-- ══════════════════════════════════════════
        HERO BANNER
══════════════════════════════════════════ --}}
    <div x-show="!isSubmitted"
        class="relative rounded-[20px] overflow-hidden mb-5 p-6 md:p-7 flex flex-col md:flex-row items-center justify-between gap-5"
        style="background: linear-gradient(135deg, #FF1443 0%, #D90F38 50%, #B00F30 100%);">
        {{-- Decorative circles --}}
        <div class="absolute -top-10 -right-10 w-[200px] h-[200px] bg-white/[0.06] rounded-full pointer-events-none"></div>
        <div class="absolute -bottom-[60px] right-24 w-[160px] h-[160px] bg-white/[0.04] rounded-full pointer-events-none"></div>

        {{-- Left --}}
        <div class="relative z-10 w-full md:flex-1 text-center md:text-left">
            <div class="inline-flex items-center gap-1.5 bg-white/15 text-white text-sm font-bold px-3 py-1 rounded-full mb-3 border border-white/25">
                <i class="fa-solid fa-id-card"></i> Formulir Biodata
            </div>
            <h1 class="text-xl md:text-2xl font-black text-white mb-1 leading-tight">Kelengkapan Data Diri</h1>
            <p class="text-sm text-white/80 leading-relaxed mb-5 max-w-[540px]">
                Lengkapi seluruh data berikut dengan benar dan jujur. Data akan digunakan dalam proses seleksi penerimaan peserta didik baru.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center md:justify-start">
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white text-[#FF1443] text-[13px] font-black rounded-full no-underline shadow-md hover:bg-gray-50 transition-all">
                    <i class="fa-solid fa-gauge"></i> Kembali ke Dashboard
                </a>
                <span class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white/15 text-white text-[13px] font-bold rounded-full border border-white/25 cursor-default">
                    <i class="fa-solid fa-id-badge"></i> Akun Terverifikasi: {{ auth()->user()?->email_verified_at?->format('d M Y, H:i') }} WIB
                </span>
            </div>
        </div>

        {{-- Right: Progress Card --}}
        <div class="relative z-10 w-full md:w-auto flex-shrink-0">
            <div class="bg-white/10 border border-white/20 rounded-[16px] px-6 py-5 backdrop-blur-md text-center shadow-xl min-w-[180px]">
                <div class="text-[12px] text-white/70 font-semibold uppercase tracking-widest mb-2">Progress Pengisian</div>
                <div class="text-[28px] font-black text-white leading-none mb-1" x-text="progressPct + '%'"></div>
                <div class="h-1.5 bg-white/25 rounded-full overflow-hidden mb-2">
                    <div class="h-full bg-white rounded-full transition-all duration-500"
                        :style="'width:' + progressPct + '%'"></div>
                </div>
                <div class="text-[12px] text-white/70 font-semibold" x-text="'Langkah ' + step + ' dari ' + totalSteps"></div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
            TWO-COLUMN LAYOUT
    ══════════════════════════════════════════ --}}
    <div class="lg:grid lg:grid-cols-[1fr_340px] lg:gap-6 lg:items-start" x-show="!isSubmitted">

        {{-- ── MAIN COLUMN ── --}}
        <div class="min-w-0">

            {{-- STEPPER --}}
            @include('pages.user.partials.biodata._stepper')

            <form id="biodata-form" enctype="multipart/form-data" hx-encoding="multipart/form-data" @submit.prevent>

                @include('pages.user.partials.biodata._step1_pribadi')
                @include('pages.user.partials.biodata._step2_alamat')
                @include('pages.user.partials.biodata._step3_orangtua')
                @include('pages.user.partials.biodata._step4_pendidikan')
                @include('pages.user.partials.biodata._step5_foto')
                @include('pages.user.partials.biodata._step6_konfirmasi')

            </form>
        </div>{{-- /main col --}}

        <!-- SIDEBAR -->

        <div class="hidden lg:block">
            <div class="sticky top-[80px] flex flex-col gap-4">

                {{-- Kelengkapan Biodata --}}
                <div class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">
                    <div class="px-5 py-4" style="background: linear-gradient(135deg,#FF1443,#D90F38);">
                        <h3 class="text-base font-black text-white mb-0.5">Kelengkapan Biodata</h3>
                        <p class="text-[13px] text-white/80">Update otomatis saat berpindah step</p>
                    </div>

                    {{-- MODIFIKASI LIST STEP --}}
                    <div class="px-5 py-3 space-y-1">
                        <template x-for="(label, idx) in stepLabels" :key="idx">
                            <div class="flex items-center gap-3 py-2 border-b border-gray-50 last:border-0">

                                {{-- Status icon (Lingkaran) --}}
                                <div :class="{
                                    'bg-[#FF1443] text-white': sidebarStatus(idx + 1) === 'active',
                                    'bg-green-500 text-white': sidebarStatus(idx + 1) === 'done',
                                    'bg-gray-100 text-[#B0B9C4]': sidebarStatus(idx + 1) === 'pending'
                                }" class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 transition-all duration-300">

                                    {{-- Jika Selesai: Tampilkan Icon Check --}}
                                    <template x-if="sidebarStatus(idx + 1) === 'done'">
                                        <i class="fa-solid fa-check text-[10px]"></i>
                                    </template>

                                    {{-- Jika Aktif: Tampilkan Icon Aslinya --}}
                                    <template x-if="sidebarStatus(idx + 1) === 'active'">
                                        <i :class="'fa-solid ' + stepIcons[idx] + ' text-[10px]'"></i>
                                    </template>

                                    {{-- PERUBAHAN DI SINI: Jika Belum Aktif, Tetap Tampilkan Icon Aslinya --}}
                                    <template x-if="sidebarStatus(idx + 1) === 'pending'">
                                        <i :class="'fa-solid ' + stepIcons[idx] + ' text-[10px]'"></i>
                                    </template>
                                </div>

                                {{-- Label --}}
                                <div class="flex-1 min-w-0">
                                    <span :class="{
                                        'text-[#080C1A] font-black': sidebarStatus(idx + 1) === 'active',
                                        'text-[#080C1A] font-bold': sidebarStatus(idx + 1) === 'done',
                                        'text-[#B0B9C4] font-medium': sidebarStatus(idx + 1) === 'pending'
                                    }" class="text-[13px] block truncate" x-text="label"></span>
                                </div>

                                {{-- Badge --}}
                                <template x-if="sidebarStatus(idx + 1) === 'done'">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-[10px] font-black flex-shrink-0">
                                        <i class="fa-solid fa-check text-[8px]"></i> Selesai
                                    </span>
                                </template>
                                <template x-if="sidebarStatus(idx + 1) === 'active'">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-red-100 text-[#FF1443] text-[10px] font-black flex-shrink-0">Aktif</span>
                                </template>
                            </div>
                        </template>
                    </div>

                    {{-- Progress Bar Bawah --}}
                    <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">
                        <div class="flex justify-between text-sm font-semibold text-[#6A7686] mb-2">
                            <span>Progress Total</span>
                            <span class="text-primary font-bold" x-text="progressPct + '%'">0%</span>
                        </div>
                        <div class="h-2.5 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500"
                                :style="'background: linear-gradient(90deg, #FF1443, #FF6B8A); width: ' + progressPct + '%'">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Butuh Bantuan --}}
                @include ('pages.user.partials.biodata._sidebar')

            </div>
        </div>
    </div>

    <!-- SUCCESS SCREEN -->
    @include('pages.user.partials.biodata._success_screen')

</div>{{-- /x-data --}}

<!-- JAVASCRIPT -->

@push('scripts')
<script>
    function saveDraft() {
        const t = document.createElement('div');
        t.className = 'fixed bottom-6 left-1/2 -translate-x-1/2 bg-[#080C1A] text-white px-[22px] py-[10px] rounded-full text-[13px] font-bold flex items-center gap-2 shadow-[0_4px_20px_rgba(0,0,0,0.18)] z-[9999] whitespace-nowrap';
        t.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg> Draft disimpan';
        document.body.appendChild(t);
        setTimeout(() => t.remove(), 2500);
    }
</script>
@endpush

@endsection
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
        <a href="{{ route('dashboard') }}" class="flex items-center gap-1.5 text-[#FF1443] no-underline font-semibold">
            <i data-lucide="layout-grid" class="w-3.5 h-3.5"></i> Beranda
        </a>
        <span class="text-gray-300">/</span>
        <a href="{{ route('dashboard') }}" class="text-[#FF1443] no-underline font-semibold">Dashboard</a>
        <span class="text-gray-300">/</span>
        <span>Lengkapi Biodata</span>
    </div>

    {{-- ══════════════════════════════════════════
        HERO BANNER
    ══════════════════════════════════════════ --}}
    <div x-show="!isSubmitted" class="relative overflow-hidden rounded-2xl bg-[#080c1a] mb-6">
        <!-- Decorative -->
        <div class="absolute -top-16 -right-16 w-72 h-72 rounded-full bg-[#ff1443]/20 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-60 h-60 rounded-full bg-[#ff1443]/10 blur-2xl pointer-events-none"></div>
        <!-- Confetti dots decoration -->
        <div class="absolute top-8 right-1/3 w-3 h-3 rounded-full bg-[#30b22d]/40 pointer-events-none"></div>
        <div class="absolute top-14 right-1/4 w-2 h-2 rounded-full bg-[#f59e0b]/40 pointer-events-none"></div>
        <div class="absolute top-6 right-1/2 w-2 h-2 rounded-full bg-[#3b82f6]/40 pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-8 p-8 md:p-10">
            <!-- Left Side -->
            <div class="w-full lg:flex-1 text-center md:text-left">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/20 px-4 py-1.5 text-xs text-white font-bold mb-5 backdrop-blur-md">
                    <i class="fa-solid fa-id-card"></i>
                    Formulir Biodata
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-white leading-tight">
                    Kelengkapan<br />
                    <span class="text-[#ff1443]">Data Diri</span>
                </h2>
                <p class="mt-4 text-[#6a7686] leading-7 max-w-2xl mx-auto md:mx-0">
                    Lengkapi seluruh data berikut dengan <span class="text-white font-semibold">benar dan jujur</span>. Data akan digunakan dalam proses seleksi penerimaan peserta didik baru.
                </p>
                <div class="mt-7 flex flex-wrap justify-center md:justify-start gap-3">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 rounded-xl bg-[#ff1443] hover:bg-[#c90e33] text-white px-6 py-3 font-bold transition-all duration-200 cursor-pointer no-underline">
                        <i class="fa-solid fa-house"></i>
                        Kembali ke Dashboard
                    </a>
                    <span class="flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 text-white/80 px-6 py-3 font-medium transition-all duration-200 cursor-default">
                        <i class="fa-solid fa-shield-check text-[#30b22d]"></i>
                        Akun Terverifikasi
                    </span>
                </div>
            </div>

            <!-- Right Side: Progress Card styled like Tahap Saat Ini -->
            <div class="hidden lg:block flex-shrink-0">
                <div class="bg-white/10 border border-white/20 rounded-[16px] px-6 py-8 backdrop-blur-md text-center shadow-xl w-[200px] flex flex-col justify-center items-center">
                    <div class="text-[12px] text-white/70 font-semibold uppercase tracking-widest mb-5">
                        Progress Pengisian
                    </div>

                    <!-- Circular Progress Visual -->
                    <div class="relative flex items-center justify-center w-20 h-20 mb-5">
                        <svg class="w-20 h-20 transform -rotate-90">
                            <circle cx="40" cy="40" r="36" stroke="currentColor" stroke-width="6" fill="transparent" class="text-white/10" />
                            <circle cx="40" cy="40" r="36" stroke="currentColor" stroke-width="6" fill="transparent" class="text-[#ff1443] transition-all duration-500" stroke-dasharray="226.19" :stroke-dashoffset="226.19 - (progressPct / 100) * 226.19" stroke-linecap="round" />
                        </svg>
                        <div class="absolute flex flex-col items-center justify-center">
                            <span class="text-xl font-bold text-white leading-none" x-text="progressPct + '%'"></span>
                        </div>
                    </div>

                    <div class="text-[14px] font-bold text-white leading-tight">
                        Langkah <span x-text="step"></span>
                    </div>
                    <div class="text-[11px] text-white/60 mt-2 font-medium">
                        dari <span x-text="totalSteps"></span> Tahapan
                    </div>
                </div>
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
                <div class="bg-white rounded-2xl border border-[#e5e7eb] flex flex-col overflow-hidden">

                    <!-- Header -->
                    <div class="p-6 border-b border-[#e5e7eb] flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-folder-open text-[#ff1443]"></i>
                                <h3 class="font-bold text-base text-[#080c1a]">Kelengkapan Biodata</h3>
                            </div>
                            <p class="text-sm text-[#6a7686] mt-0.5">Tahapan pengisian biodata.</p>
                        </div>
                        <span class="text-[11px] font-bold bg-[#ff1443]/10 text-[#ff1443] px-3 py-1 rounded-full shrink-0" x-text="step + ' / ' + totalSteps + ' Aktif'"></span>
                    </div>

                    <!-- Progress Bar -->
                    <div class="px-5 pt-4 pb-3">
                        <div class="flex justify-between text-xs text-[#6a7686] mb-1.5">
                            <span>Progress Total</span>
                            <span class="font-bold text-[#080c1a]" x-text="progressPct + '%'"></span>
                        </div>
                        <div class="h-2 rounded-full bg-[#eff2f7]">
                            <div class="h-2 rounded-full bg-gradient-to-r from-[#ff1443] to-[#f43f5e] transition-all duration-500 progress-bar" :style="'width: ' + progressPct + '%'"></div>
                        </div>
                    </div>

                    <!-- List Steps (Checklist Berkas Style) -->
                    <div class="divide-y divide-[#eff2f7] flex-1 pb-2">
                        <template x-for="(label, idx) in stepLabels" :key="idx">
                            <div class="flex items-center gap-3 px-5 py-3.5 transition-colors duration-200"
                                :class="{'bg-[#eff2f7]/30': sidebarStatus(idx + 1) === 'active'}">

                                <!-- Icon box -->
                                <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 transition-all duration-300"
                                    :class="{
                            'bg-[#dcfce7]': sidebarStatus(idx + 1) === 'done',
                            'bg-[#ff1443]/10': sidebarStatus(idx + 1) === 'active',
                            'bg-[#eff2f7]': sidebarStatus(idx + 1) === 'pending'
                         }">
                                    <i :class="[
                            'fa-solid ' + stepIcons[idx],
                            sidebarStatus(idx + 1) === 'done' ? 'text-[#30b22d]' :
                            sidebarStatus(idx + 1) === 'active' ? 'text-[#ff1443]' :
                            'text-[#6a7686]'
                        ]" class="text-[13px]"></i>
                                </div>

                                <!-- Text -->
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm leading-tight transition-colors"
                                        :class="{
                                'font-bold text-[#080c1a]': sidebarStatus(idx + 1) === 'active',
                                'font-semibold text-[#080c1a]': sidebarStatus(idx + 1) === 'done',
                                'font-semibold text-[#6a7686]': sidebarStatus(idx + 1) === 'pending'
                           }" x-text="label"></p>
                                    <p class="text-[11px] mt-0.5 transition-colors"
                                        :class="{
                                'text-[#30b22d] font-medium': sidebarStatus(idx + 1) === 'done',
                                'text-[#ff1443] font-medium': sidebarStatus(idx + 1) === 'active',
                                'text-[#6a7686]': sidebarStatus(idx + 1) === 'pending'
                           }"
                                        x-text="sidebarStatus(idx + 1) === 'done' ? 'Selesai diisi' : (sidebarStatus(idx + 1) === 'active' ? 'Sedang diisi' : 'Belum diisi')"></p>
                                </div>

                                <!-- Status indicator -->
                                <div class="w-6 h-6 rounded-full flex items-center justify-center shrink-0 transition-all duration-300"
                                    :class="{
                            'bg-[#30b22d]': sidebarStatus(idx + 1) === 'done',
                            'border-2 border-[#ff1443] bg-white': sidebarStatus(idx + 1) === 'active',
                            'border-2 border-dashed border-[#e5e7eb]': sidebarStatus(idx + 1) === 'pending'
                         }">
                                    <template x-if="sidebarStatus(idx + 1) === 'done'">
                                        <i class="fa-solid fa-check text-[10px] text-white"></i>
                                    </template>
                                    <template x-if="sidebarStatus(idx + 1) === 'active'">
                                        <div class="w-2 h-2 rounded-full bg-[#ff1443] animate-[pulse_1.5s_infinite]"></div>
                                    </template>
                                </div>

                            </div>
                        </template>
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
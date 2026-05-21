@extends('layouts.user')

@section('title', 'Pendaftaran')

@section('content')

@php
// Hitung jalur slug dari data yang sudah tersimpan (untuk prefill Alpine)
$savedJalurSlug = '';
if ($registration?->admission_path_id) {
$savedPath = $admissionPaths->find($registration->admission_path_id);
if ($savedPath) {
$savedJalurSlug = \Str::slug(\Str::replace('Jalur ', '', $savedPath->name));
}
}
@endphp

<div x-data="{
    jalur: @js($savedJalurSlug),
    step: 1,
    jarakSudahDicek: @js((bool) ($registrationZone ?? false)),
    sedangCek: false,
    alamatManual: false,
    isSubmitted: false,

    rataRapor: @js($registration ? number_format((float)($registration->report_average ?? 0), 2) : ''),
    rataTka: @js($registration ? number_format((float)($registration->tka_average ?? 0), 2) : ''),
    submitResult: {},

    // State pilihan jurusan (prefill dari DB jika sudah pernah menyimpan)
    pil1: @js($registration->choice_1 ?? ''),
    pil2: @js($registration->choice_2 ?? ''),
    pil3: @js($registration->choice_3 ?? ''),

    // Kembalikan state prestasiList agar komponen data prestasi tidak error
    prestasiList: [],

    // Inject data array kustom dari controller Anda
    jurusanList: @js($jurusanList),

    jalurList: @js($admissionPaths->mapWithKeys(fn($item) => [\Str::slug(\Str::replace('Jalur ', '', $item->name)) => $item->name])),

    // ── STEPPER MAP LOGIC ──
    get stepMap() {
        const byJalur = {
            zonasi:   [{ id: 'zonasi', label: 'Cek Domisili',    icon: 'fa-location-dot' }],
            prestasi: [{ id: 'prestasi',     label: 'Data Prestasi',   icon: 'fa-award' }],
            afirmasi: [{ id: 'afirmasi', label: 'Dok. Afirmasi',   icon: 'fa-file-shield' }],
            reguler:  [],
        };
        return [
            { id: 'nilai',      label: 'Nilai & TKA',   icon: 'fa-table-list' },
            { id: 'jalur',      label: 'Jalur Daftar',  icon: 'fa-road' },
            ...(this.jalur ? (byJalur[this.jalur] ?? []) : []),
            { id: 'jurusan',    label: 'Pilih Jurusan', icon: 'fa-building-columns' },
            { id: 'konfirmasi', label: 'Konfirmasi',    icon: 'fa-clipboard-check' },
        ];
    },

    get totalSteps()     { return this.stepMap.length; },
    get currentStepId()  { return this.stepMap[this.step - 1]?.id; },
    get stepLabels()     { return this.stepMap.map(s => s.label); },
    
    // PERBAIKAN 1: Kembalikan getter stepIcons untuk ikon stepper horizontal
    get stepIcons()      { return this.stepMap.map(s => s.icon); },

    // PERBAIKAN 2: Kembalikan kalkulasi progressPct untuk persentase progress bar
    get progressPct()    { return Math.round((this.step / this.totalSteps) * 100); },

    // PERBAIKAN 3: Kembalikan fungsi status sidebar tracker (Active, Done, Pending)
    sidebarStatus(targetStepIdx) {
        if (this.step === targetStepIdx) return 'active';
        return this.step > targetStepIdx ? 'done' : 'pending';
    },

    // ── LOGIKA KUNCI JURUSAN (SUDAH RINGKAS) ──
    isDisabled(id, currentModelName) {
        // Ambil ID yang dipilih di luar select box yang sedang aktif saat ini
        const terpilih = [this.pil1, this.pil2, this.pil3].filter(val => val !== this[currentModelName]);

        // 1. Aturan standar: Jika ID jurusan sudah dipilih di option lain, maka disabled
        if (terpilih.includes(id)) return true;

        // 2. Aturan Baru: Jika jurusan ini restrict_choice (TKJ/TKR/TSM) DAN form select saat ini adalah pilihan 2 atau pilihan 3
        if (this.jurusanList[id]?.restrict_choice && (currentModelName === 'pil2' || currentModelName === 'pil3')) {
            return true;
        }

        return false;
    },

    get adaError() {
        const uids = [this.pil1, this.pil2, this.pil3].filter(Boolean);
        // Error 1: Duplikasi pilihan jurusan
        const hasDup = new Set(uids).size < uids.length;
        
        // Error 2: Pelanggaran penempatan restrict_choice di pil2 atau pil3
        const hasRestrictionViolation = (this.pil2 && this.jurusanList[this.pil2]?.restrict_choice) || 
                                         (this.pil3 && this.jurusanList[this.pil3]?.restrict_choice);
        
        return hasDup || hasRestrictionViolation;
    },

    get pesanError() {
        const uids = [this.pil1, this.pil2, this.pil3].filter(Boolean);
        if (new Set(uids).size < uids.length) {
            return 'Setiap pilihan jurusan harus berbeda!';
        }
        return 'Jurusan TKJ, TKR, dan TSM hanya boleh dipilih pada Pilihan Pertama!';
    }
}"
    x-init="
        $nextTick(() => {
            // Prefill step awal dari data yang sudah tersimpan di DB
            const phpInitialStep = @js($initialStep ?? 1);
            if (phpInitialStep === 99) {
                // 99 = sentinel: tampilkan step konfirmasi (terakhir)
                step = totalSteps;
            } else if (phpInitialStep > 1) {
                step = phpInitialStep;
            }
        });
    "
    @pindah-step.window="
    const nextId = $event.detail.nextStep;
    const idx = stepMap.findIndex(s => s.id === nextId);
    if (idx !== -1) step = idx + 1;
    "
    @jarak-dihitung.window="jarakSudahDicek = true"
    @jarak-direset.window="jarakSudahDicek = false">

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
        <span>Formulir Pendaftaran</span>
    </div>

    {{-- ══════════════════════════════════════════
            HERO BANNER
    ══════════════════════════════════════════ --}}
    <div x-show="!isSubmitted"
        class="relative rounded-[20px] overflow-hidden mb-5 p-6 md:p-7 flex flex-col md:flex-row items-center justify-between gap-5"
        style="background: linear-gradient(135deg, #FF1443 0%, #D90F38 50%, #B00F30 100%);">
        <div class="absolute -top-10 -right-10 w-[200px] h-[200px] bg-white/[0.06] rounded-full pointer-events-none"></div>
        <div class="absolute -bottom-[60px] right-24 w-[160px] h-[160px] bg-white/[0.04] rounded-full pointer-events-none"></div>

        {{-- Kiri --}}
        <div class="relative z-10 w-full md:flex-1 text-center md:text-left">
            <div class="inline-flex items-center gap-1.5 bg-white/15 text-white text-sm font-bold px-3 py-1 rounded-full mb-3 border border-white/25">
                <i class="fa-solid fa-file-pen"></i> Formulir Pendaftaran
            </div>
            <h1 class="text-xl md:text-2xl font-black text-white mb-1 leading-tight">Tahapan Pendaftaran SPMB</h1>
            <p class="text-sm text-white/80 leading-relaxed mb-5 max-w-[540px]">
                Lengkapi seluruh tahapan pendaftaran berikut dengan data yang benar. Pastikan nilai rapor, prestasi, dan pilihan jurusan sudah sesuai.
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

        {{-- Kanan: Progress Card --}}
        <div class="relative z-10 w-full md:w-auto flex-shrink-0">
            <div class="bg-white/10 border border-white/20 rounded-[16px] px-6 py-5 backdrop-blur-md text-center shadow-xl min-w-[180px]">
                <div class="text-[12px] text-white/70 font-semibold uppercase tracking-widest mb-2">Progress Pendaftaran</div>
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
    <div class="lg:grid lg:grid-cols-[1fr_300px] lg:gap-6 lg:items-start" x-show="!isSubmitted">

        {{-- ── MAIN COLUMN ── --}}
        <div class="min-w-0">

            {{-- STEPPER --}}
            @include('pages.user.partials.pendaftaran._stepper')

            <form id="pendaftaran-form" enctype="multipart/form-data" hx-encoding="multipart/form-data" @submit.prevent>

                @include('pages.user.partials.pendaftaran._step_nilai')
                @include('pages.user.partials.pendaftaran._step_jalur')
                @include('pages.user.partials.pendaftaran._step_prestasi')
                @include('pages.user.partials.pendaftaran._step_zonasi')
                @include('pages.user.partials.pendaftaran._step_afirmasi')
                @include('pages.user.partials.pendaftaran._step_jurusan')
                @include('pages.user.partials.pendaftaran._step_konfirmasi')

            </form>
        </div>

        {{-- SIDEBAR --}}
        @include('pages.user.partials.pendaftaran._sidebar')

    </div>

    {{-- SUCCESS SCREEN --}}
    @include('pages.user.partials.pendaftaran._success_screen')

</div>

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
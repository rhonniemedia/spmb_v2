@extends('layouts.user')

@section('title', 'Resume Pendaftaran')

@section('content')

@php
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

    rataRapor: @js($registration ? number_format((float)($registration->report_average ?? 0), 2) : ''),
    rataTka: @js($registration ? number_format((float)($registration->tka_average ?? 0), 2) : ''),

    pil1: @js($registration->choice_1 ?? ''),
    pil2: @js($registration->choice_2 ?? ''),
    pil3: @js($registration->choice_3 ?? ''),

    prestasiList: [],
    jurusanList: @js($jurusanList),
    jalurList: @js($admissionPaths->mapWithKeys(fn($item) => [\Str::slug(\Str::replace('Jalur ', '', $item->name)) => $item->name])),

    get stepMap() {
        const byJalur = {
            zonasi:   [{ id: 'zonasi',   label: 'Cek Domisili',  icon: 'fa-location-dot' }],
            prestasi: [{ id: 'prestasi', label: 'Data Prestasi', icon: 'fa-award' }],
            afirmasi: [{ id: 'afirmasi', label: 'Dok. Afirmasi', icon: 'fa-file-shield' }],
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

    get totalSteps()  { return this.stepMap.length; },
    get currentStepId() { return this.stepMap[this.step - 1]?.id; },
    get stepLabels()  { return this.stepMap.map(s => s.label); },
    get stepIcons()   { return this.stepMap.map(s => s.icon); },
    get progressPct() { return 100; },

    sidebarStatus(targetStepIdx) {
        return 'done';
    },

    isDisabled(id, currentModelName) {
        const terpilih = [this.pil1, this.pil2, this.pil3].filter(val => val !== this[currentModelName]);
        if (terpilih.includes(id)) return true;
        if (this.jurusanList[id]?.restrict_choice && (currentModelName === 'pil2' || currentModelName === 'pil3')) return true;
        return false;
    },
}"
    x-init="$nextTick(() => { step = totalSteps; })">

    {{-- BREADCRUMB --}}
    <div class="flex items-center gap-1.5 text-[13px] text-[#6A7686] mb-4">
        <a href="{{ route('dashboard') }}" class="text-[#FF1443] no-underline font-semibold">
            <i class="fa-solid fa-house"></i> Beranda
        </a>
        <span class="text-gray-300">/</span>
        <a href="{{ route('dashboard') }}" class="text-[#FF1443] no-underline font-semibold">Dashboard</a>
        <span class="text-gray-300">/</span>
        <span>Formulir Pendaftaran</span>
    </div>

    {{-- HERO BANNER --}}
    <div class="relative rounded-[20px] overflow-hidden mb-5 p-6 md:p-7 flex flex-col md:flex-row items-center justify-between gap-5"
        style="background: linear-gradient(135deg, #FF1443 0%, #D90F38 50%, #B00F30 100%);">
        <div class="absolute -top-10 -right-10 w-[200px] h-[200px] bg-white/[0.06] rounded-full pointer-events-none"></div>
        <div class="absolute -bottom-[60px] right-24 w-[160px] h-[160px] bg-white/[0.04] rounded-full pointer-events-none"></div>

        <div class="relative z-10 w-full md:flex-1 text-center md:text-left">
            <div class="inline-flex items-center gap-1.5 bg-white/15 text-white text-sm font-bold px-3 py-1 rounded-full mb-3 border border-white/25">
                <i class="fa-solid fa-file-pen"></i> Formulir Pendaftaran
            </div>
            <h1 class="text-xl md:text-2xl font-black text-white mb-1 leading-tight">Tahapan Pendaftaran SPMB</h1>
            <p class="text-sm text-white/80 leading-relaxed mb-5 max-w-[540px]">
                Data pendaftaran Anda telah tersimpan. Cetak bukti pendaftaran untuk keperluan verifikasi.
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

        <div class="relative z-10 w-full md:w-auto flex-shrink-0">
            <div class="bg-white/10 border border-white/20 rounded-[16px] px-6 py-5 backdrop-blur-md text-center shadow-xl min-w-[180px]">
                <div class="text-[12px] text-white/70 font-semibold uppercase tracking-widest mb-2">Status Pendaftaran</div>
                <div class="text-[28px] font-black text-white leading-none mb-1">100%</div>
                <div class="h-1.5 bg-white/25 rounded-full overflow-hidden mb-2">
                    <div class="h-full bg-white rounded-full w-full"></div>
                </div>
                <div class="text-[12px] text-white/70 font-semibold">Pendaftaran Selesai</div>
            </div>
        </div>
    </div>

    {{-- TWO-COLUMN LAYOUT --}}
    <div class="lg:grid lg:grid-cols-[1fr_300px] lg:gap-6 lg:items-start">

        <div class="min-w-0">
            @include('pages.user.partials.pendaftaran._step_resume')
        </div>

        @include('pages.user.partials.pendaftaran._sidebar')

    </div>

</div>

@endsection
@extends('layouts.user')

@section('title', 'Beranda')

@section('content')
{{-- BREADCRUMB --}}
<div class="flex items-center gap-2 text-[13px] text-gray-500 mb-6 font-medium">
    <a href="/" class="text-blue-600 hover:text-blue-700 transition-colors">
        <i class="fa-solid fa-house"></i> Beranda
    </a>
    <i class="fa-solid fa-chevron-right text-[10px] text-gray-300"></i>
    <span class="text-gray-700">Dashboard Area</span>
</div>

<div class="space-y-6">

    {{-- 1. HERO BANNER (Elegan, Berwarna, Informatif) --}}
    <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-6 md:p-8 md:py-10 shadow-lg shadow-blue-200/50 flex flex-col md:flex-row items-center justify-between gap-6 border border-blue-500/30">
        {{-- Ornamen Dekoratif Halus --}}
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>

        <div class="relative z-10 w-full md:flex-1 text-center md:text-left text-white">
            <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-md text-white text-xs font-bold px-3 py-1.5 rounded-full mb-4 border border-white/20">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-400"></span>
                </span>
                Posisi Anda: {{ $currentActiveStepText ?? 'Pendaftaran' }}
            </div>

            <h1 class="text-2xl md:text-3xl font-bold mb-2 tracking-tight">
                Halo, {{ auth()->user()->first_name }}!
            </h1>
            <p class="text-blue-100 text-sm md:text-base mb-6 max-w-2xl leading-relaxed">
                Ini adalah pusat informasi seleksi Anda.
                @if($announcementDateText && $announcementDateText !== '-')
                Pengumuman resmi kelulusan akan dirilis pada <strong class="text-white bg-white/20 px-1.5 py-0.5 rounded">{{ $announcementDateText }}</strong>.
                @else
                Pengumuman kelulusan akan segera diinformasikan di sini.
                @endif
                Pastikan seluruh kelengkapan Anda terpenuhi sebelum waktu habis.
            </p>

            <div class="flex flex-col sm:flex-row gap-3 justify-center md:justify-start">
                <a href="{{ route('biodata') }}" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-white text-blue-700 text-sm font-bold rounded-xl hover:bg-blue-50 transition-all shadow-sm">
                    <i class="fa-solid fa-pen-to-square"></i> Cek & Lengkapi Data
                </a>
                <a href="{{ route('pengumuman') }}" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-blue-800/40 border border-blue-400/50 text-white text-sm font-bold rounded-xl hover:bg-blue-800/60 transition-all backdrop-blur-sm">
                    <i class="fa-solid fa-bullhorn"></i> Lihat Pengumuman
                </a>
            </div>
        </div>
    </div>

    {{-- 2. BENTO STATS (Warna-warni pastel yang segar) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        {{-- Stat 1: Biodata --}}
        <div class="bg-emerald-50/50 border border-emerald-100 rounded-2xl p-5 flex items-start justify-between group hover:bg-emerald-50 transition-colors">
            <div>
                <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-1">Status Biodata</p>
                <div class="flex items-baseline gap-1">
                    <h3 class="text-2xl font-black text-gray-800">{{ $biodataPercentage ?? 0 }}%</h3>
                    <span class="text-xs font-semibold text-gray-500">Terisi</span>
                </div>
                <p class="text-[11px] text-gray-500 mt-2">{{ $biodataPercentage == 100 ? 'Profil sudah lengkap & dikunci.' : 'Mohon lengkapi profil Anda.' }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-white text-emerald-500 flex items-center justify-center shadow-sm border border-emerald-100 group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-address-card text-xl"></i>
            </div>
        </div>

        {{-- Stat 2: Dokumen --}}
        <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-5 flex items-start justify-between group hover:bg-blue-50 transition-colors">
            <div>
                <p class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">Berkas Syarat</p>
                <div class="flex items-baseline gap-1">
                    <h3 class="text-2xl font-black text-gray-800">{{ $verifiedCount ?? 0 }}</h3>
                    <span class="text-xs font-semibold text-gray-500">/ {{ $totalRequirements ?? 0 }} Valid</span>
                </div>
                <p class="text-[11px] text-gray-500 mt-2">Menunggu jadwal verifikasi panitia.</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-white text-blue-500 flex items-center justify-center shadow-sm border border-blue-100 group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-folder-open text-xl"></i>
            </div>
        </div>

        {{-- Stat 3: Countdown/Tanggal --}}
        <div class="bg-amber-50/50 border border-amber-100 rounded-2xl p-5 flex items-start justify-between group hover:bg-amber-50 transition-colors">
            <div>
                <p class="text-xs font-bold text-amber-600 uppercase tracking-wider mb-1">Pengumuman</p>
                <div class="flex items-baseline gap-1">
                    <h3 class="text-2xl font-black text-gray-800">{{ $daysToAnnouncement ?? 0 }}</h3>
                    <span class="text-xs font-semibold text-gray-500">Hari Lagi</span>
                </div>
                <p class="text-[11px] text-gray-500 mt-2 font-medium">Est. {{ $announcementDateText ?? '-' }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-white text-amber-500 flex items-center justify-center shadow-sm border border-amber-100 group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-calendar-check text-xl"></i>
            </div>
        </div>
    </div>

    {{-- 3. KONTEN UTAMA: DOKUMEN & TIMELINE --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 items-start">

        {{-- KIRI: LIST DOKUMEN (Proporsi 3 dari 5 kolom) --}}
        <div class="lg:col-span-3 space-y-4">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <div>
                        <h3 class="text-base font-bold text-gray-800">Ceklis Persyaratan Dokumen</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Bawa berkas asli pada saat jadwal verifikasi tatap muka.</p>
                    </div>
                </div>

                <div class="p-5 flex flex-col gap-3">
                    @forelse($requirements ?? [] as $req)
                    @php
                    // Cek status berkas
                    $docStatus = $registration ? $registration->documents->where('requirement_id', $req->id)->first() : null;
                    $status = $docStatus ? $docStatus->verification_status : 'pending';

                    // Tema warna status
                    $theme = [
                    'verified' => ['bg' => 'bg-green-50', 'border' => 'border-green-200', 'badge' => 'bg-green-100 text-green-700', 'icon' => 'fa-check text-green-600', 'label' => 'Valid / Terverifikasi'],
                    'pending' => ['bg' => 'bg-white', 'border' => 'border-gray-200', 'badge' => 'bg-amber-100 text-amber-700', 'icon' => 'fa-clock text-amber-500', 'label' => 'Belum Diperiksa'],
                    'rejected' => ['bg' => 'bg-red-50', 'border' => 'border-red-200', 'badge' => 'bg-red-100 text-red-700', 'icon' => 'fa-xmark text-red-600', 'label' => 'Ada Revisi / Ditolak']
                    ][$status] ?? ['bg' => 'bg-white', 'border' => 'border-gray-200', 'badge' => 'bg-gray-100 text-gray-600', 'icon' => 'fa-minus text-gray-400', 'label' => 'Menunggu'];
                    @endphp

                    <div class="flex items-center gap-4 p-3 rounded-xl border {{ $theme['bg'] }} {{ $theme['border'] }} transition-colors hover:border-gray-300">
                        <div class="w-10 h-10 rounded-full bg-white border border-gray-100 flex items-center justify-center shrink-0 shadow-sm">
                            <i class="{{ $theme['icon'] }} text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <h4 class="text-sm font-semibold text-gray-800 truncate">{{ $req->name }}</h4>
                                @if($req->is_mandatory)
                                <span class="text-[9px] bg-rose-50 border border-rose-100 text-rose-600 font-bold px-1.5 py-0.5 rounded uppercase">Wajib</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 truncate">
                                {{ $status === 'rejected' && $docStatus->verification_notes ? 'Catatan: ' . $docStatus->verification_notes : ($req->description ?? 'Dokumen pendaftaran.') }}
                            </p>
                        </div>
                        <div class="shrink-0 text-right">
                            <span class="text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wide {{ $theme['badge'] }}">
                                {{ $theme['label'] }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-6">
                        <i class="fa-solid fa-folder-open text-3xl text-gray-200 mb-3"></i>
                        <p class="text-sm text-gray-500">Belum ada daftar dokumen yang dikonfigurasi sekolah.</p>
                    </div>
                    @endforelse
                </div>

                {{-- Alert Info Verifikasi --}}
                <div class="bg-blue-50/50 border-t border-blue-100 px-5 py-3 flex gap-3 items-start">
                    <i class="fa-solid fa-circle-info text-blue-500 mt-0.5 text-sm shrink-0"></i>
                    <p class="text-xs text-blue-800 leading-relaxed">
                        Proses verifikasi dokumen fisik dilakukan oleh panitia sekolah mulai tanggal <span class="font-bold">{{ $verificationDateText ?? '-' }}</span>. Silakan cetak bukti pendaftaran setelah biodata final.
                    </p>
                </div>
            </div>
        </div>

        {{-- KANAN: TRACKER ALUR SELEKSI (Proporsi 2 dari 5 kolom) --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="mb-5 flex justify-between items-end">
                    <div>
                        <h3 class="text-base font-bold text-gray-800">Timeline Seleksi</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Pantau posisi tahapan Anda</p>
                    </div>
                </div>

                <div class="relative space-y-0">
                    {{-- Garis Latar Timeline --}}
                    <div class="absolute left-[15px] top-2 bottom-2 w-0.5 bg-gray-100"></div>

                    @foreach($spmbSteps ?? [] as $step)
                    @php
                    $isStepDone = false;
                    $isStepActive = false;
                    $statusText = 'Belum Dimulai';

                    $startDate = $step->start_date ? \Carbon\Carbon::parse($step->start_date) : null;
                    $endDate = $step->end_date ? \Carbon\Carbon::parse($step->end_date) : null;
                    $now = now();

                    // Logika sederhana penentuan status tahapan
                    switch ($step->slug) {
                    case 'pendaftaran-akun':
                    $isStepDone = true; $statusText = 'Selesai'; break;
                    case 'pengisian-biodata':
                    if ($personalData && $personalData->profile_status === 'final') {
                    $isStepDone = true; $statusText = 'Dikunci';
                    } else {
                    $isStepActive = true; $statusText = 'Isi Sekarang';
                    }
                    break;
                    case 'pendaftaran-spmb':
                    if ($registration) { $isStepDone = true; $statusText = 'Terdaftar'; }
                    elseif ($personalData && $personalData->profile_status === 'final') { $isStepActive = true; $statusText = 'Pilih Jurusan'; }
                    break;
                    case 'verifikasi-dokumen':
                    if ($registration && $registration->verification_status === 'verified') { $isStepDone = true; $statusText = 'Selesai diverifikasi'; }
                    elseif ($registration && $registration->verification_status === 'pending') { $isStepActive = true; $statusText = 'Proses Panitia'; }
                    break;
                    default:
                    if ($startDate && $endDate) {
                    if ($now->between($startDate, $endDate)) { $isStepActive = true; $statusText = 'Tahap Aktif'; }
                    elseif ($now->greaterThan($endDate)) { $isStepDone = true; $statusText = 'Berakhir'; }
                    }
                    break;
                    }

                    // Warna Indikator
                    if ($isStepDone) {
                    $ring = 'ring-green-100 bg-green-500 border-green-500 text-white';
                    $titleColor = 'text-gray-800 font-bold';
                    } elseif ($isStepActive) {
                    $ring = 'ring-blue-100 bg-white border-blue-500 text-blue-600 ring-4';
                    $titleColor = 'text-blue-700 font-bold';
                    } else {
                    $ring = 'ring-transparent bg-white border-gray-300 text-gray-300';
                    $titleColor = 'text-gray-400 font-medium';
                    }
                    @endphp

                    <div class="relative flex gap-4 pb-6 last:pb-0 group">
                        {{-- Dot --}}
                        <div class="relative z-10 w-8 h-8 rounded-full border-2 flex items-center justify-center shrink-0 transition-all {{ $ring }}">
                            @if($isStepDone)
                            <i class="fa-solid fa-check text-xs"></i>
                            @elseif($isStepActive)
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-500 animate-pulse"></span>
                            @else
                            <span class="text-[10px] font-bold">{{ $step->step_order }}</span>
                            @endif
                        </div>

                        {{-- Teks Info --}}
                        <div class="flex-1 min-w-0 pt-1 pb-1">
                            <h4 class="text-sm {{ $titleColor }} mb-0.5 leading-tight">{{ $step->title }}</h4>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] text-gray-500"><i class="fa-regular fa-calendar mr-1"></i>{{ $step->period_text }}</span>
                            </div>

                            {{-- Teks Status Kecil --}}
                            @if($isStepActive)
                            <span class="inline-block mt-1.5 text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">
                                {{ $statusText }}
                            </span>
                            @elseif($isStepDone)
                            <span class="inline-block mt-1 text-[10px] font-bold text-green-600">
                                {{ $statusText }}
                            </span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
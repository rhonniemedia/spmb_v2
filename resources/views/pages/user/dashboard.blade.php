@extends('layouts.user')

@section('title', 'Beranda')

@section('content')
{{-- BREADCRUMB --}}
<div class="flex items-center gap-1.5 text-[13px] text-[#6A7686] mb-4">
    <a href="/" class="text-primary no-underline font-semibold hover:underline">
        <i class="fa-solid fa-house"></i> Beranda
    </a>
    <span class="text-gray-300">/</span>
    <span>Dashboard</span>
</div>

{{-- ══════════════════════════════════════════
        HERO BANNER
══════════════════════════════════════════ --}}
<div class="relative rounded-[20px] overflow-hidden mb-5 p-6 md:p-7 flex flex-col md:flex-row items-center justify-between gap-5"
    style="background: linear-gradient(135deg, #FF1443 0%, #D90F38 50%, #B00F30 100%);">
    {{-- Decorative circles --}}
    <div class="absolute -top-10 -right-10 w-[200px] h-[200px] bg-white/[0.06] rounded-full pointer-events-none"></div>
    <div class="absolute -bottom-[60px] right-24 w-[160px] h-[160px] bg-white/[0.04] rounded-full pointer-events-none"></div>

    {{-- Left --}}
    <div class="relative z-10 w-full md:flex-1 text-center md:text-left">
        <div class="inline-flex items-center gap-1.5 bg-white/15 text-white text-sm font-bold px-3 py-1 rounded-full mb-3 border border-white/25">
            <i class="fa-solid fa-circle-dot text-[10px] animate-pulse"></i> Status Pendaftaran
        </div>
        <h1 class="text-xl md:text-2xl font-black text-white mb-1 leading-tight">
            Selamat, {{ auth()->user()->first_name }}! Pendaftaran Kamu Aktif!
        </h1>
        <p class="text-sm text-white/80 leading-relaxed mb-5 max-w-[540px]">
            Dokumen sedang diverifikasi oleh panitia SPMB. Pengumuman hasil seleksi akan dirilis pada
            <span class="text-white font-bold">10 Juni 2026</span>.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center md:justify-start">
            <a href="{{ route('pengumuman') }}"
                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white text-[#FF1443] text-[13px] font-black rounded-full no-underline shadow-md hover:bg-gray-50 transition-all">
                <i class="fa-solid fa-trophy"></i> Lihat Pengumuman
            </a>
            <a href="{{ route('biodata') }}"
                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white/15 text-white text-[13px] font-bold rounded-full no-underline border border-white/25 hover:bg-white/25 transition-all">
                <i class="fa-solid fa-pencil"></i> Lengkapi Data
            </a>
        </div>
    </div>

    {{-- Right: Status Card --}}
    <div class="relative z-10 w-full md:w-auto">
        <div class="bg-white/10 border border-white/20 rounded-[16px] px-6 py-5 backdrop-blur-md text-center shadow-xl">
            <div class="text-[12px] text-white/70 font-semibold uppercase tracking-widest mb-2">Status Saat Ini</div>
            <div class="text-[16px] font-black text-white flex items-center justify-center gap-2">
                <span class="pulse-dot"></span> Verifikasi Dokumen
            </div>
            <div class="text-[12px] text-white/60 mt-2 italic font-medium">Diperbarui 2 jam lalu</div>
        </div>
    </div>
</div>


{{-- STATS GRID --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @php
    $stats = [
    ['label' => 'Dokumen Terverifikasi', 'val' => '3', 'total' => '/5', 'icon' => 'fa-file-lines', 'bg' => 'bg-red-50', 'text' => 'text-primary', 'status' => '2 dokumen pending', 'statusColor' => 'text-amber-500', 'statusIcon' => 'fa-clock'],
    ['label' => 'Kelengkapan Biodata', 'val' => '78', 'total' => '%', 'icon' => 'fa-percent', 'bg' => 'bg-green-50', 'text' => 'text-green-600', 'status' => 'Segera lengkapi', 'statusColor' => 'text-amber-500', 'statusIcon' => 'fa-triangle-exclamation'],
    ['label' => 'Hari ke Pengumuman', 'val' => '12', 'total' => '', 'icon' => 'fa-calendar-check', 'bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'status' => '10 Juni 2026', 'statusColor' => 'text-green-600', 'statusIcon' => 'fa-calendar'],
    ['label' => 'Total Pendaftar', 'val' => '847', 'total' => '', 'icon' => 'fa-users', 'bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'status' => 'Kuota 320 siswa', 'statusColor' => 'text-red-500', 'statusIcon' => 'fa-arrow-up'],
    ];
    @endphp

    @foreach($stats as $s)
    <div class="bg-white border border-gray-200 rounded-card p-5 shadow-sm hover:-translate-y-1 hover:shadow-md transition-all duration-300 animate-fade-in">
        <div class="w-10 h-10 rounded-xl {{ $s['bg'] }} flex items-center justify-center text-[16px] mb-4 {{ $s['text'] }}">
            <i class="fa-solid {{ $s['icon'] }}"></i>
        </div>
        <div class="text-[26px] font-black leading-none mb-1 text-[#080C1A]">
            {{ $s['val'] }}<span class="text-[14px] text-[#6A7686] font-bold">{{ $s['total'] }}</span>
        </div>
        <div class="text-[13px] text-[#6A7686] font-bold">{{ $s['label'] }}</div>
        <div class="text-[12px] font-bold mt-2 flex items-center gap-1 {{ $s['statusColor'] }}">
            <i class="fa-solid {{ $s['statusIcon'] }} text-[12px]"></i> {{ $s['status'] }}
        </div>
    </div>
    @endforeach
</div>

{{-- MAIN CONTENT AREA --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
    <div class="lg:col-span-2 space-y-6">
        {{-- Jadwal Seleksi --}}
        <div class="bg-white border border-gray-200 rounded-card shadow-sm overflow-hidden animate-fade-in">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-black text-[#080C1A]">Jadwal & Tahapan Seleksi</h3>
                <p class="text-[13px] text-[#6A7686] font-medium">Alur proses pendaftaran Anda</p>
            </div>
            <div class="p-6 space-y-6 relative">
                @php
                $steps = [
                ['title' => 'Pembukaan Pendaftaran', 'desc' => 'Akun dibuat dan biodata awal diisi', 'date' => '1 Mei 2026', 'status' => 'Selesai', 'active' => false, 'done' => true, 'icon' => 'fa-check'],
                ['title' => 'Pengisian Biodata Lengkap', 'desc' => 'Data pribadi, ortu, dan dokumen', 'date' => '1-20 Mei 2026', 'status' => 'Selesai', 'active' => false, 'done' => true, 'icon' => 'fa-check'],
                ['title' => 'Verifikasi Dokumen', 'desc' => 'Pemeriksaan keabsahan berkas oleh panitia', 'date' => '21 Mei - 5 Jun 2026', 'status' => 'Sedang Berlangsung', 'active' => true, 'done' => false, 'icon' => 'fa-magnifying-glass'],
                ['title' => 'Pengumuman Hasil', 'desc' => 'Daftar peserta diterima dipublikasikan', 'date' => '10 Juni 2026', 'status' => 'Mendatang', 'active' => false, 'done' => false, 'icon' => 'fa-bullhorn'],
                ];
                @endphp

                @foreach($steps as $index => $step)
                <div class="flex gap-4 relative">
                    @if(!$loop->last)
                    <div class="absolute left-4 top-8 w-0.5 h-full bg-gray-100"></div>
                    @endif
                    <div class="z-10 flex-shrink-0">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-[12px] {{ $step['done'] ? 'bg-green-100 text-green-600' : ($step['active'] ? 'bg-red-100 text-primary ring-4 ring-primary/10' : 'bg-gray-100 text-gray-400') }}">
                            <i class="fa-solid {{ $step['icon'] }}"></i>
                        </div>
                    </div>
                    <div class="pb-2">
                        <h4 class="text-[14px] font-bold {{ $step['active'] ? 'text-primary' : 'text-[#080C1A]' }}">{{ $step['title'] }}</h4>
                        <p class="text-[13px] text-[#6A7686] leading-relaxed">{{ $step['desc'] }}</p>
                        <div class="mt-2 text-[12px] font-bold {{ $step['done'] ? 'text-green-600' : ($step['active'] ? 'text-primary' : 'text-gray-500') }}">
                            @if($step['active']) <i class="fa-solid fa-spinner fa-spin mr-1"></i> @endif
                            {{ $step['date'] }} — {{ $step['status'] }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Status Dokumen --}}
        <div class="bg-white border border-gray-200 rounded-card shadow-sm overflow-hidden animate-fade-in">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-black text-[#080C1A]">Status Dokumen</h3>
                    <p class="text-[13px] text-[#6A7686] font-medium">Verifikasi berkas persyaratan</p>
                </div>
                <a href="{{ route('biodata') }}" class="text-[13px] font-black text-primary hover:underline">Kelola Berkas <i class="fa-solid fa-arrow-right ml-1"></i></a>
            </div>
            <div class="divide-y divide-gray-50">
                @php
                $docs = [
                ['name' => 'Akta Kelahiran / KK', 'file' => 'akta_kelahiran.pdf', 'status' => 'Valid', 'color' => 'text-green-600', 'bg' => 'bg-green-50'],
                ['name' => 'Ijazah / SKL SMP', 'file' => 'ijazah_smp.pdf', 'status' => 'Valid', 'color' => 'text-green-600', 'bg' => 'bg-green-50'],
                ['name' => 'Rapor Semester 1–5', 'file' => 'rapor_lengkap.pdf', 'status' => 'Diperiksa', 'color' => 'text-amber-600', 'bg' => 'bg-amber-50'],
                ['name' => 'Pas Foto 3×4', 'file' => 'Belum diunggah', 'status' => 'Kosong', 'color' => 'text-red-500', 'bg' => 'bg-red-50'],
                ];
                @endphp

                @foreach($docs as $doc)
                <div class="flex items-center gap-4 p-4 hover:bg-gray-50/50 transition-colors">
                    <div class="w-10 h-10 rounded-xl {{ $doc['bg'] }} {{ $doc['color'] }} flex items-center justify-center text-[16px] shrink-0">
                        <i class="fa-solid {{ $doc['status'] === 'Kosong' ? 'fa-image' : 'fa-file-lines' }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-[13px] font-bold text-[#080C1A] truncate">{{ $doc['name'] }}</div>
                        <div class="text-[13px] text-[#6A7686] mt-0.5 truncate">{{ $doc['file'] }}</div>
                    </div>
                    <div class="text-[12px] font-black px-2.5 py-1 rounded-full border {{ $doc['bg'] }} {{ $doc['color'] }} border-current/20">
                        {{ $doc['status'] }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- SIDEBAR --}}
    <div class="space-y-6">
        {{-- Notifikasi --}}
        <div class="bg-white border border-gray-200 rounded-card shadow-sm overflow-hidden animate-fade-in">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-base font-black text-[#080C1A]">Notifikasi</h3>
                <span class="bg-primary/10 text-primary text-[12px] font-black px-2 py-0.5 rounded uppercase">3 Baru</span>
            </div>
            <div class="max-h-[400px] overflow-y-auto">
                @php
                $notifs = [
                ['title' => 'Pas Foto Belum Ada', 'msg' => 'Segera unggah pas foto latar merah/biru.', 'time' => '2 jam lalu'],
                ['title' => 'Rapor Diperiksa', 'msg' => 'Estimasi verifikasi selesai dalam 1x24 jam.', 'time' => '1 hari lalu'],
                ['title' => 'Ijazah Valid', 'msg' => 'Dokumen ijazah Anda dinyatakan sah.', 'time' => '2 hari lalu'],
                ];
                @endphp
                @foreach($notifs as $n)
                <div class="p-4 border-b border-gray-50 hover:bg-gray-50 transition-colors cursor-pointer group">
                    <div class="flex gap-3">
                        <div class="mt-1 flex-shrink-0">
                            <div class="w-2 h-2 rounded-full bg-primary"></div>
                        </div>
                        <div>
                            <h4 class="text-[13px] font-bold text-[#080C1A] group-hover:text-primary">{{ $n['title'] }}</h4>
                            <p class="text-[13px] text-[#6A7686] leading-relaxed mt-1">{{ $n['msg'] }}</p>
                            <div class="text-xs text-[#B0B8C4] mt-1"><i class="fa-regular fa-clock mr-1"></i> {{ $n['time'] }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        @include('pages.user.partials.biodata._sidebar')

    </div>
</div>
@endsection
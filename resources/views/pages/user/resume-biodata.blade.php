@extends('layouts.user')

@section('title', 'Resume Biodata')

@section('content')

{{-- CSS Tambahan Khusus untuk Cetak & Font Monospace --}}
@push('styles')
<style>
    @media print {
        .no-print {
            display: none !important;
        }

        body {
            background: #fff !important;
        }
    }
</style>
@endpush

<div class="font-sans max-w-[1400px] mx-auto pb-20 text-[#080C1A]">

    {{-- ══════════════════════════════════════════
            BREADCRUMB
    ══════════════════════════════════════════ --}}
    <div class="flex items-center gap-1.5 text-[13px] text-[#6A7686] mb-4 animate-fade-in no-print">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-1.5 text-[#FF1443] no-underline font-semibold hover:opacity-80 transition-opacity">
            <i data-lucide="layout-grid" class="w-3.5 h-3.5"></i> Beranda
        </a>
        <span class="text-gray-300">/</span>
        <a href="{{ route('dashboard') }}" class="text-[#FF1443] no-underline font-semibold hover:opacity-80 transition-opacity">Dashboard</a>
        <span class="text-gray-300">/</span>
        <span>Resume Biodata</span>
    </div>

    {{-- ══════════════════════════════════════════
            HERO BANNER (DARK PREMIUM STYLE)
    ══════════════════════════════════════════ --}}
    <div class="relative overflow-hidden rounded-2xl bg-[#080c1a] mb-6 no-print animate-fade-in">
        <!-- Decorative Background -->
        <div class="absolute -top-16 -right-16 w-72 h-72 rounded-full bg-[#ff1443]/20 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-60 h-60 rounded-full bg-[#ff1443]/10 blur-2xl pointer-events-none"></div>
        <!-- Confetti dots decoration -->
        <div class="absolute top-8 right-1/3 w-3 h-3 rounded-full bg-[#30b22d]/40 pointer-events-none"></div>
        <div class="absolute top-14 right-1/4 w-2 h-2 rounded-full bg-[#f59e0b]/40 pointer-events-none"></div>
        <div class="absolute top-6 right-1/2 w-2 h-2 rounded-full bg-[#3b82f6]/40 pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-8 p-8 md:p-10">

            {{-- Kiri --}}
            <div class="w-full lg:flex-1 text-center md:text-left">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/20 px-4 py-1.5 text-xs text-white font-bold mb-5 backdrop-blur-md">
                    <i data-lucide="file-text" class="w-3.5 h-3.5 text-[#30b22d]"></i>
                    Resume Biodata Peserta
                </span>

                <h2 class="text-3xl md:text-4xl font-bold text-white leading-tight mb-3">
                    {{ $personalData->full_name ?? 'Calon Murid Baru' }}
                </h2>

                <p class="text-[#6a7686] leading-7 max-w-2xl mx-auto md:mx-0 mb-7">
                    Ringkasan lengkap biodata calon peserta didik baru. Pastikan semua data sudah <span class="text-white font-semibold">sesuai</span> sebelum melakukan daftar ulang.
                </p>

                <div class="flex flex-wrap justify-center md:justify-start gap-3">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 rounded-xl bg-[#ff1443] hover:bg-[#c90e33] text-white px-6 py-3 text-[13px] font-bold transition-all duration-200 cursor-pointer no-underline">
                        <i data-lucide="layout-dashboard" class="w-4 h-4 shrink-0"></i>
                        Kembali ke Dashboard
                    </a>
                    <span class="flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 text-white/80 px-6 py-3 text-[13px] font-medium transition-all duration-200 cursor-default">
                        <i data-lucide="shield-check" class="w-4 h-4 text-[#30b22d] shrink-0"></i>
                        Terverifikasi: {{ auth()->user()?->email_verified_at?->format('d M Y') }}
                    </span>
                </div>
            </div>

            {{-- Kanan: Kartu identitas ringkas --}}
            <div class="hidden lg:block flex-shrink-0">
                <div class="bg-white/10 border border-white/20 rounded-[16px] px-6 py-8 backdrop-blur-md text-center shadow-xl w-[220px] flex flex-col justify-center items-center">

                    <div class="w-16 h-16 rounded-full bg-white/10 border-2 border-white/20 flex items-center justify-center font-black text-white text-2xl mx-auto mb-4 select-none backdrop-blur-sm shadow-inner">
                        {{ strtoupper(substr($personalData->full_name ?? 'A', 0, 1)) }}{{ strtoupper(substr(strstr($personalData->full_name ?? 'F', ' '), 1, 1)) }}
                    </div>

                    <div class="text-[17px] font-bold text-white leading-tight text-center">
                        {{ $personalData->nick_name ?? 'Calon Murid' }}
                    </div>

                    <div class="text-[12px] text-white/60 font-medium text-center mt-1 mb-6">
                        {{ $personalData->nisn ?? 'NISN ···' }}
                    </div>

                    @php
                    $statusLabel = $personalData->profile_status ?? 'draft';
                    $isFinal = $statusLabel === 'final';
                    @endphp

                    <div class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-full w-full border transition-colors {{ $isFinal ? 'bg-[#30b22d]/20 border-[#30b22d]/30' : 'bg-[#f59e0b]/20 border-[#f59e0b]/30' }}">
                        <span class="w-2 h-2 rounded-full {{ $isFinal ? 'bg-[#30b22d]' : 'bg-[#f59e0b] animate-pulse' }}"></span>
                        <span class="text-[10px] font-bold uppercase tracking-widest {{ $isFinal ? 'text-[#30b22d]' : 'text-[#f59e0b]' }}">
                            {{ $isFinal ? 'Biodata Final' : 'Draft' }}
                        </span>
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- ══════════════════════════════════════════
            GRID DUA KOLOM
    ══════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 gap-6 items-start w-full lg:grid-cols-[1fr_300px]" id="resumeLayout">

        {{-- ── KOLOM KIRI (FORM DATA) ── --}}
        <div class="space-y-5">

            {{-- SEC 1 — IDENTITAS PRIBADI --}}
            <div id="sec-identitas" class="bg-white border border-[#E5E7EB] rounded-[20px] shadow-[0_1px_8px_rgba(0,0,0,0.04)] overflow-hidden scroll-mt-6 animate-fade-in">
                <div class="px-6 py-[18px] pb-[14px] border-b border-gray-100 flex items-center gap-[14px]">
                    <div class="w-[42px] h-[42px] rounded-[12px] bg-[#FFF1F3] flex items-center justify-center text-[20px] flex-shrink-0">
                        <i class="fa-solid fa-id-card text-[18px] text-[#FF1443]"></i>
                    </div>
                    <div>
                        <div class="text-[17px] font-black">1. Identitas Pribadi</div>
                        <div class="text-[14px] text-[#6A7686] mt-[2px]">Data utama calon peserta didik</div>
                    </div>
                    <span class="ml-auto inline-flex items-center gap-[5px] px-[10px] py-[3px] rounded-full text-[13px] font-bold bg-[#DCFCE7] text-[#166534] border border-[rgba(48,178,45,0.2)]">
                        <i class="fa-solid fa-circle-check text-[11px]"></i> Terisi
                    </span>
                </div>

                <div class="px-6 py-[22px]">
                    {{-- Foto + Detail Singkat Kepala Pasien --}}
                    <div class="flex gap-4 items-center mb-5">
                        @if(isset($personalData->photo) && $personalData->photo)
                        <img src="{{ asset('storage/'.$personalData->photo) }}" alt="Foto"
                            class="w-[84px] aspect-[9/11] rounded-[14px] object-cover border-2 border-white shadow-[0_0_0_2px_#E5E7EB,0_4px_12px_rgba(0,0,0,0.08)] bg-gray-100 shrink-0">
                        @else
                        <div class="w-[84px] aspect-[9/11] rounded-[14px] border-2 border-white shadow-[0_0_0_2px_#E5E7EB,0_4px_12px_rgba(0,0,0,0.08)] bg-gray-100 shrink-0 flex items-center justify-center text-2xl text-gray-400">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        @endif
                        <div>
                            <div class="text-lg font-black text-[#080C1A] leading-tight">
                                {{ $personalData->full_name ?? '—' }}
                            </div>
                            <div class="text-[13px] text-[#6A7686] my-1">
                                {{ $personalData->nick_name ?? '—' }}
                                &nbsp;·&nbsp;
                                {{ ($personalData->gender ?? '') === 'L' ? 'Laki-laki' : (($personalData->gender ?? '') === 'P' ? 'Perempuan' : '—') }}
                            </div>
                            <span class="inline-flex items-center gap-1 px-[10px] py-[2px] rounded-full text-[12px] font-bold border {{ isset($personalData->profile_status) && $personalData->profile_status === 'final' ? 'bg-[#DCFCE7] text-[#166534] border-[rgba(48,178,45,0.2)]' : 'bg-[#FEF3C7] text-[#92400E] border-orange-200' }}">
                                <i class="fa-solid {{ isset($personalData->profile_status) && $personalData->profile_status === 'final' ? 'fa-circle-check' : 'fa-clock' }} text-[10px]"></i>
                                {{ isset($personalData->profile_status) && $personalData->profile_status === 'final' ? 'Biodata Final' : 'Draft' }}
                            </span>
                        </div>
                    </div>

                    {{-- Grid Data --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">NISN</div>
                            <div class="text-[13px] font-bold font-mono tracking-wide">{{ $personalData->nisn ?? '—' }}</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">NIK</div>
                            <div class="text-[13px] font-bold font-mono tracking-wide">{{ isset($personalData->nik) ? '••••••••••' . substr($personalData->nik_hash ?? '', -4) : '—' }}</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Tempat Lahir</div>
                            <div class="text-[13px] font-bold">{{ $personalData->pob ?? '—' }}</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Tanggal Lahir</div>
                            <div class="text-[13px] font-bold">{{ isset($personalData->dob) ? \Carbon\Carbon::parse($personalData->dob_decrypted)->translatedFormat('d F Y') : ($personalData->dob_plain ?? '—') }}</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Agama</div>
                            <div class="text-[13px] font-bold">{{ $personalData->religion ?? '—' }}</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Golongan Darah</div>
                            <div class="text-[13px] font-bold">{{ $personalData->blood_type ?? '—' }}</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Anak Ke-</div>
                            <div class="text-[13px] font-bold">{{ $personalData->child_order ?? '—' }}</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Jumlah Saudara</div>
                            <div class="text-[13px] font-bold">{{ $personalData->number_of_siblings ?? '—' }}</div>
                        </div>
                        @if(isset($personalData->is_special_condition) && $personalData->is_special_condition === 'yes')
                        <div class="p-3 rounded-[16px] bg-[rgba(255,20,67,0.06)] border border-[rgba(255,20,67,0.15)] col-span-2">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Kondisi Khusus</div>
                            <div class="text-[13px] font-bold text-[#FF1443]">{{ $personalData->special_condition_type ?? '—' }}</div>
                            @if($personalData->condition_description)
                            <div class="text-[12px] text-[#6A7686] mt-1">{{ $personalData->condition_description }}</div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- SEC 2 — KONTAK & ALAMAT --}}
            <div id="sec-kontak" class="bg-white border border-[#E5E7EB] rounded-[20px] shadow-[0_1px_8px_rgba(0,0,0,0.04)] overflow-hidden scroll-mt-6 animate-fade-in">
                <div class="px-6 py-[18px] pb-[14px] border-b border-gray-100 flex items-center gap-[14px]">
                    <div class="w-[42px] h-[42px] rounded-[12px] bg-[#EFF6FF] flex items-center justify-center text-[20px] flex-shrink-0">
                        <i class="fa-solid fa-location-dot text-[18px] text-[#3B82F6]"></i>
                    </div>
                    <div>
                        <div class="text-[17px] font-black">2. Kontak &amp; Alamat</div>
                        <div class="text-[14px] text-[#6A7686] mt-[2px]">Domisili dan informasi kontak aktif</div>
                    </div>
                </div>

                <div class="px-6 py-[22px]">
                    <div class="text-[14px] font-bold text-[#080C1A] flex items-center gap-[7px] mb-3 pb-2 border-b border-gray-100">
                        <i class="fa-solid fa-phone text-[12px] text-[#FF1443]"></i> Informasi Kontak
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-5">
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Email</div>
                            <div class="text-[13px] font-bold">{{ $personalData->email ?? '—' }}</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Nomor Telepon / WA</div>
                            <div class="text-[13px] font-bold font-mono tracking-wide">0{{ $personalData->phone_number ?? '—' }}</div>
                        </div>
                    </div>

                    <div class="text-[14px] font-bold text-[#080C1A] flex items-center gap-[7px] mb-3 pb-2 border-b border-gray-100">
                        <i class="fa-solid fa-map-pin text-[12px] text-[#FF1443]"></i> Alamat Domisili
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 mb-5">
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB] sm:col-span-4">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Alamat Lengkap</div>
                            <div class="text-[13px] font-bold">{{ $personalData->address ?? '—' }}</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB] sm:col-span-1">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">RT</div>
                            <div class="text-[13px] font-bold">{{ $personalData->rt ?? '—' }}</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB] sm:col-span-1">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">RW</div>
                            <div class="text-[13px] font-bold">{{ $personalData->rw ?? '—' }}</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB] sm:col-span-2">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Kelurahan / Desa</div>
                            <div class="text-[13px] font-bold">{{ $personalData->village ?? '—' }}</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB] sm:col-span-2">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Kecamatan</div>
                            <div class="text-[13px] font-bold">{{ $personalData->district ?? '—' }}</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB] sm:col-span-2">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Kabupaten / Kota</div>
                            <div class="text-[13px] font-bold">{{ $personalData->regency ?? '—' }}</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB] sm:col-span-2">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Provinsi</div>
                            <div class="text-[13px] font-bold">{{ $personalData->province ?? '—' }}</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB] sm:col-span-2">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Kode Pos</div>
                            <div class="text-[13px] font-bold font-mono tracking-wide">{{ $personalData->postal_code ?? '—' }}</div>
                        </div>
                    </div>

                    <div class="text-[14px] font-bold text-[#080C1A] flex items-center gap-[7px] mb-3 pb-2 border-b border-gray-100">
                        <i class="fa-solid fa-car text-[12px] text-[#FF1443]"></i> Akomodasi &amp; Transportasi
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Tempat Tinggal</div>
                            <div class="text-[13px] font-bold">{{ $personalData->residence_type ? Str::title(str_replace('_', ' ', $personalData->residence_type)) : '—' }}</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Transportasi</div>
                            <div class="text-[13px] font-bold">{{ $personalData->transportation ? Str::title(str_replace('_', ' ', $personalData->transportation)) : '—' }}</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Jarak ke Sekolah</div>
                            <div class="text-[13px] font-bold">{{ $personalData->distance_to_school ? Str::title(str_replace('_', ' ', $personalData->distance_to_school)) : '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SEC 3 — PENDIDIKAN SEBELUMNYA --}}
            <div id="sec-pendidikan" class="bg-white border border-[#E5E7EB] rounded-[20px] shadow-[0_1px_8px_rgba(0,0,0,0.04)] overflow-hidden scroll-mt-6 animate-fade-in">
                <div class="px-6 py-[18px] pb-[14px] border-b border-gray-100 flex items-center gap-[14px]">
                    <div class="w-[42px] h-[42px] rounded-[12px] bg-[#F0FDF4] flex items-center justify-center text-[20px] flex-shrink-0">
                        <i class="fa-solid fa-graduation-cap text-[18px] text-[#22C55E]"></i>
                    </div>
                    <div>
                        <div class="text-[17px] font-black">3. Pendidikan Sebelumnya</div>
                        <div class="text-[14px] text-[#6A7686] mt-[2px]">Riwayat asal sekolah instansi sebelumnya</div>
                    </div>
                </div>

                <div class="px-6 py-[22px]">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB] md:col-span-2">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Nama Sekolah Asal</div>
                            <div class="text-[13px] font-bold">{{ $personalData->previous_school ?? '—' }}</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">NPSN Sekolah Asal</div>
                            <div class="text-[13px] font-bold font-mono tracking-wide">{{ $personalData->previous_school_npsn ?? '—' }}</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Status Sekolah</div>
                            <div class="text-[13px] font-bold">{{ $personalData->previous_school_status ? Str::title(str_replace('_', ' ', $personalData->previous_school_status)) : '—' }}</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Kota Asal</div>
                            <div class="text-[13px] font-bold">{{ $personalData->previous_school_city ?? '—' }}</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Provinsi Asal</div>
                            <div class="text-[13px] font-bold">{{ $personalData->previous_school_province ?? '—' }}</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Nomor Ijazah / SKL</div>
                            <div class="text-[13px] font-bold font-mono tracking-wide">{{ $personalData->graduation_certificate_number ?? '—' }}</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Tahun Lulus</div>
                            <div class="text-[13px] font-bold">{{ $personalData->graduation_year ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SEC 4-6 — DATA ORANG TUA / WALI --}}
            <div id="sec-ortu" class="bg-white border border-[#E5E7EB] rounded-[20px] shadow-[0_1px_8px_rgba(0,0,0,0.04)] overflow-hidden scroll-mt-6 animate-fade-in">
                <div class="px-6 py-[18px] pb-[14px] border-b border-gray-100 flex items-center gap-[14px]">
                    <div class="w-[42px] h-[42px] rounded-[12px] bg-[#FFF7ED] flex items-center justify-center text-[20px] flex-shrink-0">
                        <i class="fa-solid fa-people-roof text-[18px] text-[#F97316]"></i>
                    </div>
                    <div>
                        <div class="text-[17px] font-black">4. Data Orang Tua &amp; Wali</div>
                        <div class="text-[14px] text-[#6A7686] mt-[2px]">Informasi data Ayah, Ibu, dan Wali pendaftar</div>
                    </div>
                </div>

                <div class="px-6 py-[22px]">
                    @php
                    $ayah = $parentData->firstWhere('relationship', 'father');
                    $ibu = $parentData->firstWhere('relationship', 'mother');
                    $wali = $parentData->firstWhere('relationship', 'guardian');
                    @endphp

                    {{-- Tab Switcher dengan Ukuran Konsisten --}}
                    <div id="parentTabs" class="flex gap-1.5 mb-4">
                        <button class="px-4 py-2 rounded-full text-[13px] font-bold border border-gray-200 bg-gray-50 text-[#6A7686] cursor-pointer transition-all active [&.active]:bg-[#FF1443] [&.active]:border-[#FF1443] [&.active]:text-white shadow-sm" onclick="switchParent('ayah',this)">
                            <i class="fa-solid fa-person mr-1 text-[12px]"></i> Ayah
                        </button>
                        <button class="px-4 py-2 rounded-full text-[13px] font-bold border border-gray-200 bg-gray-50 text-[#6A7686] cursor-pointer transition-all [&.active]:bg-[#FF1443] [&.active]:border-[#FF1443] [&.active]:text-white shadow-sm" onclick="switchParent('ibu',this)">
                            <i class="fa-solid fa-person-dress mr-1 text-[12px]"></i> Ibu
                        </button>
                        @if($wali)
                        <button class="px-4 py-2 rounded-full text-[13px] font-bold border border-gray-200 bg-gray-50 text-[#6A7686] cursor-pointer transition-all [&.active]:bg-[#FF1443] [&.active]:border-[#FF1443] [&.active]:text-white shadow-sm" onclick="switchParent('wali',this)">
                            <i class="fa-solid fa-shield-halved mr-1 text-[12px]"></i> Wali
                        </button>
                        @endif
                    </div>

                    {{-- Pane Ayah --}}
                    <div id="pane-ayah" class="parent-pane block">
                        @if($ayah)
                        @include('pages.user.partials.biodata._parent-fields', ['p' => $ayah, 'label' => 'Ayah'])
                        @else
                        <div class="rounded-[16px] p-3 px-4 flex gap-[10px] items-start bg-[#FFFBEB] border border-[#FDE68A]">
                            <i class="fa-solid fa-triangle-exclamation text-[14px] text-[#D97706] mt-[1px] flex-shrink-0"></i>
                            <p class="text-[14px] font-medium leading-relaxed text-[#92400E]">Data mengenai Ayah Kandung belum diisi.</p>
                        </div>
                        @endif
                    </div>

                    {{-- Pane Ibu --}}
                    <div id="pane-ibu" class="parent-pane hidden">
                        @if($ibu)
                        @include('pages.user.partials.biodata._parent-fields', ['p' => $ibu, 'label' => 'Ibu'])
                        @else
                        <div class="rounded-[16px] p-3 px-4 flex gap-[10px] items-start bg-[#FFFBEB] border border-[#FDE68A]">
                            <i class="fa-solid fa-triangle-exclamation text-[14px] text-[#D97706] mt-[1px] flex-shrink-0"></i>
                            <p class="text-[14px] font-medium leading-relaxed text-[#92400E]">Data mengenai Ibu Kandung belum diisi.</p>
                        </div>
                        @endif
                    </div>

                    {{-- Pane Wali --}}
                    @if($wali)
                    <div id="pane-wali" class="parent-pane hidden">
                        @include('pages.user.partials.biodata._parent-fields', ['p' => $wali, 'label' => 'Wali'])
                    </div>
                    @endif
                </div>
            </div>

            {{-- SEC 7 — RINGKASAN & VALIDASI --}}
            <div id="sec-validasi" class="bg-white border border-[#E5E7EB] rounded-[20px] shadow-[0_1px_8px_rgba(0,0,0,0.04)] overflow-hidden scroll-mt-6 animate-fade-in">
                <div class="px-6 py-[18px] pb-[14px] border-b border-gray-100 flex items-center gap-[14px]">
                    <div class="w-[42px] h-[42px] rounded-[12px] bg-[#F3F0FF] flex items-center justify-center text-[20px] flex-shrink-0">
                        <i class="fa-solid fa-clipboard-check text-[18px] text-[#8B5CF6]"></i>
                    </div>
                    <div>
                        <div class="text-[17px] font-black">5. Ringkasan &amp; Validasi Data</div>
                        <div class="text-[14px] text-[#6A7686] mt-[2px]">Periksa kelengkapan seluruh berkas berkala sebelum registrasi ulang</div>
                    </div>
                </div>

                <div class="px-6 py-[22px]">
                    <div class="text-[14px] font-bold text-[#080C1A] flex items-center gap-[7px] mb-3 pb-2 border-b border-gray-100">
                        <i class="fa-solid fa-chart-pie text-[12px] text-[#FF1443]"></i> Status Kelengkapan Pengisian Biodata
                    </div>

                    @php
                    $sections = [
                    'Identitas Pribadi' => (bool)($personalData->full_name ?? null),
                    'Kontak & Alamat' => (bool)($personalData->email_encrypted ?? null),
                    'Pendidikan Sebelumnya' => (bool)($personalData->previous_school ?? null),
                    'Data Ayah' => (bool)$ayah,
                    'Data Ibu' => (bool)$ibu,
                    ];
                    $done = count(array_filter($sections));
                    $total = count($sections);
                    $pct = (int)(($done / $total) * 100);
                    @endphp

                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex-1">
                            <div class="flex justify-between mb-1.5 text-[13px] font-semibold text-[#6A7686]">
                                <span>Kelengkapan Berkas</span>
                                <span class="font-extrabold text-[#FF1443]" id="pctText">{{ $pct }}%</span>
                            </div>
                            <div class="h-[6px] bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full rounded-full bg-gradient-to-r from-[#FF1443] to-[#FF6B8A] transition-all duration-[600ms]" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                        @php
                        $pillType = $pct === 100 ? 'bg-[#DCFCE7] text-[#166534] border-[rgba(48,178,45,0.2)]' : ($pct >= 60 ? 'bg-[#FEF3C7] text-[#92400E] border-orange-200' : 'bg-[#FEE2E2] text-[#991B1B] border-red-200');
                        @endphp
                        <span class="inline-flex items-center gap-1 px-[10px] py-[4px] rounded-full text-[13px] font-bold border shrink-0 {{ $pillType }}">
                            {{ $done }}/{{ $total }} Bagian
                        </span>
                    </div>

                    <div class="border border-[#E5E7EB] rounded-[16px] overflow-hidden mb-4">
                        @foreach($sections as $name => $filled)
                        <div class="flex items-center justify-between py-2.5 px-4 border-b border-gray-100 last:border-b-0 text-[13px]">
                            <span class="font-semibold text-gray-700">{{ $name }}</span>
                            <span class="inline-flex items-center gap-1 px-[10px] py-[2px] rounded-full text-[12px] font-bold border {{ $filled ? 'bg-[#DCFCE7] text-[#166534] border-[rgba(48,178,45,0.2)]' : 'bg-[#FEE2E2] text-[#991B1B] border-red-200' }}">
                                <i class="fa-solid {{ $filled ? 'fa-circle-check' : 'fa-circle-xmark' }} text-[10px]"></i>
                                {{ $filled ? 'Terisi' : 'Belum' }}
                            </span>
                        </div>
                        @endforeach
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Terakhir Diperbarui</div>
                            <div class="text-[13px] font-bold">{{ isset($personalData->updated_at) ? $personalData->updated_at->translatedFormat('d F Y, H:i') . ' WIB' : '—' }}</div>
                        </div>
                        @php
                        $isFinal = ($personalData->profile_status ?? '') === 'final';
                        @endphp
                        <div class="p-3 rounded-[16px] border {{ $isFinal ? 'bg-[#DCFCE7] border-[rgba(48,178,45,0.2)]' : 'bg-[rgba(255,20,67,0.08)] border-[rgba(255,20,67,0.18)]' }}">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Status Kunci</div>
                            <div class="text-[13px] font-bold {{ $isFinal ? 'text-[#166534]' : 'text-[#FF1443]' }}">
                                {{ $isFinal ? 'Final — Siap Mendaftar' : 'Draft — Belum Final' }}
                            </div>
                        </div>
                    </div>

                    <hr class="border-0 border-t border-dashed border-gray-200 my-5">

                    @php
                    // Logika status otomatis mendeteksi start_date & end_date secara riil sampai ke jam/menitnya
                    $isSchedule = isset($spmbStep)
                    && $spmbStep->start_date !== null
                    && now()->between($spmbStep->start_date, $spmbStep->end_date);
                    $canRegister = $isFinal && $isSchedule;
                    @endphp

                    @if(!$isFinal)
                    <div class="rounded-[16px] p-3 px-4 flex gap-[10px] items-start mb-4 bg-[#FFFBEB] border border-[#FDE68A]">
                        <i class="fa-solid fa-triangle-exclamation text-[14px] text-[#D97706] mt-[1px] flex-shrink-0"></i>
                        <p class="text-[13px] font-medium leading-relaxed text-[#92400E]">
                            Biodata kamu masih berstatus <strong>Draft</strong>. Harap lakukan finalisasi pada panel pengisian biodata utama sebelum melanjutkan pendaftaran.
                        </p>
                    </div>
                    @elseif(!$isSchedule)
                    <div class="rounded-[16px] p-3 px-4 flex gap-[10px] items-start mb-4 bg-[#EFF6FF] border border-[#BFDBFE]">
                        <i class="fa-solid fa-circle-info text-[14px] text-[#3B82F6] mt-[1px] flex-shrink-0"></i>
                        <p class="text-[13px] font-medium leading-relaxed text-[#1E40AF]">
                            Jadwal pendaftaran belum dimulai. @if(isset($spmbStep) && $spmbStep->period_text) Periode aktif: <strong>{{ $spmbStep->period_text }}</strong>. @endif
                        </p>
                    </div>
                    @else
                    <div class="rounded-[16px] p-3 px-4 flex gap-[10px] items-start mb-4 bg-[#DCFCE7] border border-[rgba(48,178,45,0.20)]">
                        <i class="fa-solid fa-circle-check text-[14px] text-[#30B22D] mt-[1px] flex-shrink-0"></i>
                        <p class="text-[13px] font-medium leading-relaxed text-[#166534]">
                            Biodata terverifikasi <strong>Final</strong> dan jadwal pendaftaran sedang berlangsung. Silahkan lanjut ke langkah pengisian form kelengkapan berikutnya.
                        </p>
                    </div>
                    @endif

                    <div class="flex gap-3 items-center flex-wrap justify-between">
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center gap-[7px] px-[22px] py-[11px] rounded-full font-sans text-[13px] font-bold no-underline bg-white text-[#080C1A] border-[1.5px] border-[#E5E7EB] hover:border-[#080C1A] hover:-translate-y-px transition-all">
                            <i class="fa-solid fa-gauge"></i>Kembali ke Dashboard
                        </a>
                        <button class="inline-flex items-center gap-[7px] px-[22px] py-[11px] rounded-full font-sans text-[13px] font-bold bg-[#30B22D] text-white shadow-[0_4px_14px_rgba(48,178,45,0.25)] hover:bg-[#27A024] hover:-translate-y-[2px] transition-all cursor-pointer disabled:opacity-[0.45] disabled:cursor-not-allowed disabled:transform-none"
                            id="regBtn" disabled onclick="submitRegistration()"
                            data-can-register="{{ $canRegister ? 'true' : 'false' }}">
                            <i class="fa-solid fa-rotate-right text-[13px]"></i> Lanjut ke Pendaftaran
                        </button>
                    </div>
                </div>
            </div>
        </div>{{-- /main col --}}

        {{-- ── SIDEBAR (NAVIGASI & INFO KANAN) ── --}}
        <div class="sticky top-20 flex flex-col gap-4 no-print w-full">

            {{-- Table of Contents / Navigasi --}}
            <div class="bg-white border border-[#E5E7EB] rounded-[20px] overflow-hidden shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
                <div class="px-[18px] py-[14px] border-b border-gray-100">
                    <h3 class="text-sm font-black">Navigasi Resume</h3>
                    <p class="text-[13px] text-[#6A7686] mt-[2px]">Lompat ke bagian bagian data</p>
                </div>
                <div class="flex flex-col" x-data="{
                    // Menentukan item mana yang sedang aktif saat ini
                    activeTarget: 'sec-identitas', 
                    tocItems: [
                        { id: 'sec-identitas', label: 'Identitas Pribadi', icon: 'fa-id-card', color: 'text-[#FF1443]' },
                        { id: 'sec-kontak', label: 'Kontak & Alamat', icon: 'fa-location-dot', color: 'text-[#3B82F6]' },
                        { id: 'sec-pendidikan', label: 'Asal Sekolah', icon: 'fa-graduation-cap', color: 'text-[#22C55E]' },
                        { id: 'sec-ortu', label: 'Orang Tua / Wali', icon: 'fa-people-roof', color: 'text-[#F97316]' },
                        { id: 'sec-validasi', label: 'Ringkasan Validasi', icon: 'fa-clipboard-check', color: 'text-[#8B5CF6]' }
                    ]
                }">
                    <template x-for="(item, idx) in tocItems" :key="idx">
                        <a :href="'#' + item.id"
                            class="toc-item flex items-center gap-3 px-4 py-2.5 no-underline text-[#6A7686] text-[13px] font-semibold border-b border-gray-100 transition-all hover:bg-[#FFF1F3] hover:text-[#FF1443]"
                            :class="{ 'bg-[#FFF1F3] text-[#FF1443] active': activeTarget === item.id }"
                            :data-target="item.id"
                            @click="activeTarget = item.id">

                            {{-- Lingkaran Ikon (w-7 h-7 seperti komponen sebelumnya) --}}
                            <div :class="activeTarget === item.id ? 'bg-[#FF1443]' : 'bg-gray-100'"
                                class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 transition-all duration-300">

                                {{-- Ikon: Jika aktif jadi putih (text-white), jika tidak aktif pakai warna aslinya --}}
                                <i :class="'fa-solid ' + item.icon + ' text-[11px] ' + (activeTarget === item.id ? 'text-white' : item.color)"></i>
                            </div>

                            {{-- Label Teks --}}
                            <div class="flex-1 min-w-0">
                                <span :class="activeTarget === item.id ? 'text-[#080C1A] font-black' : 'text-[#6A7686] font-semibold'"
                                    class="text-[13px] block truncate"
                                    x-text="item.label"></span>
                            </div>

                        </a>
                    </template>
                </div>
            </div>

            {{-- Kontak Layanan Bantuan Pasien --}}
            @include ('pages.user.partials.biodata._sidebar')


        </div>{{-- /sidebar --}}

    </div>{{-- /grid wrapper --}}
</div>

@push('scripts')
<script>
    /* ══════ Parent Tab Switcher ══════ */
    function switchParent(pane, btn) {
        document.querySelectorAll('.parent-pane').forEach(p => {
            p.classList.remove('block');
            p.classList.add('hidden');
        });

        document.querySelectorAll('#parentTabs button').forEach(b => {
            b.classList.remove('active', 'bg-[#FF1443]', 'border-[#FF1443]', 'text-white');
            b.classList.add('bg-gray-50', 'text-[#6A7686]', 'border-gray-200');
        });

        const targetPane = document.getElementById('pane-' + pane);
        if (targetPane) {
            targetPane.classList.remove('hidden');
            targetPane.classList.add('block');
        }

        btn.classList.add('active', 'bg-[#FF1443]', 'border-[#FF1443]', 'text-white');
        btn.classList.remove('bg-gray-50', 'text-[#6A7686]', 'border-gray-200');
    }

    /* ══════ Aktivasi Tombol Lanjut Daftar ══════ */
    function checkFormEligibility() {
        const btn = document.getElementById('regBtn');
        if (!btn) return;

        const canRegister = btn.dataset.canRegister === 'true';

        if (canRegister) {
            btn.disabled = false;
            btn.style.opacity = '1';
            btn.classList.remove('cursor-not-allowed');
        } else {
            btn.disabled = true;
            btn.style.opacity = '0.45';
            btn.classList.add('cursor-not-allowed');
        }
    }

    // Pastikan fungsi berjalan baik saat reload halaman maupun saat HTMX selesai melakukan Swap data
    document.addEventListener('DOMContentLoaded', checkFormEligibility);
    document.addEventListener('htmx:afterSwap', checkFormEligibility);

    function submitRegistration() {
        window.location.href = '{{ route("registration") }}';
    }

    /* ══════ Scroll Spy untuk Navigasi Sidebar ══════ */
    const tocItems = document.querySelectorAll('.toc-item[data-target]');
    const sections = Array.from(tocItems).map(i => document.getElementById(i.dataset.target));

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                tocItems.forEach(t => t.classList.remove('active'));
                const match = document.querySelector(`.toc-item[data-target="${entry.target.id}"]`);
                if (match) match.classList.add('active');
            }
        });
    }, {
        rootMargin: '-20% 0px -60% 0px'
    });

    sections.forEach(s => {
        if (s) observer.observe(s);
    });

    // Jalankan fungsi inisialisasi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', () => {
        checkFormEligibility();

        // Atur tombol pertama aktif secara default
        const firstTab = document.querySelector('#parentTabs button');
        if (firstTab) firstTab.classList.add('active', 'bg-[#FF1443]', 'border-[#FF1443]', 'text-white');
    });
</script>
@endpush

@endsection
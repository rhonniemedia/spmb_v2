@extends('layouts.user')

@section('title', 'Daftar Ulang Berhasil')

@section('content')
<div class="font-sans max-w-2xl mx-auto py-12 px-4 text-[#080C1A]">
    <div class="bg-white border border-gray-200 rounded-[20px] shadow-sm px-8 py-16 text-center animate-fade-in">

        {{-- Animated check icon --}}
        <div style="position: relative; display: inline-flex; align-items: center; justify-content: center;" class="mb-6">

            {{-- Sparkles --}}
            <span class="absolute -top-2 -right-1.5 text-yellow-300 text-base animate-[sparkle-pop_0.5s_cubic-bezier(.34,1.56,.64,1)_0.75s_both]">✦</span>
            <span class="absolute -bottom-1 -right-3.5 text-green-300 text-xs animate-[sparkle-pop_0.5s_cubic-bezier(.34,1.56,.64,1)_0.85s_both]">✦</span>
            <span class="absolute -top-1 -left-3.5 text-emerald-300 text-xs animate-[sparkle-pop_0.5s_cubic-bezier(.34,1.56,.64,1)_0.95s_both]">✦</span>
            <span class="absolute -bottom-1.5 -left-1.5 text-yellow-200 text-[10px] animate-[sparkle-pop_0.5s_cubic-bezier(.34,1.56,.64,1)_1.0s_both]">✦</span>

            {{-- Ring --}}
            <div class="w-[88px] h-[88px] rounded-full bg-green-50 border-2 border-green-200 flex items-center justify-center
            animate-[ring-expand_0.5s_cubic-bezier(.34,1.56,.64,1)_0.1s_both,ring-pulse_2s_ease-in-out_0.7s_infinite]">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                    <polyline
                        class="animate-[check-draw_0.45s_ease-out_0.5s_both]"
                        points="8,21 17,30 33,12"
                        stroke="#22c55e" stroke-width="3.5"
                        stroke-linecap="round" stroke-linejoin="round"
                        stroke-dasharray="60" stroke-dashoffset="60" />
                </svg>
            </div>
        </div>

        <h2 class="text-2xl font-black text-[#080C1A] mb-2">Daftar Ulang Berhasil! 🎉</h2>
        <p class="text-base text-[#6A7686] max-w-lg mx-auto leading-relaxed mb-8">
            Data registrasi pendaftaran ulang Anda telah diverifikasi secara aman oleh sistem SPMB. Silakan lakukan pencetakan bukti pendaftaran fisik di bawah ini untuk dibawa saat masuk hari pertama sekolah.
        </p>

        {{-- Info Card — data riil dari DB --}}
        <div class="inline-flex flex-col gap-2 bg-gray-50 border border-gray-200 rounded-2xl px-8 py-5 mb-8 text-left w-full">
            <div class="flex gap-4 text-sm">
                <span class="text-[#6A7686] font-semibold min-w-[130px]">NISN</span>
                <span class="font-bold font-mono">{{ $personalData->nisn ?? '—' }}</span>
            </div>
            <div class="flex gap-4 text-sm">
                <span class="text-[#6A7686] font-semibold min-w-[130px]">Nama Lengkap</span>
                <span class="font-bold uppercase">{{ $personalData->full_name ?? '—' }}</span>
            </div>
            <div class="flex gap-4 text-sm">
                <span class="text-[#6A7686] font-semibold min-w-[130px]">Sekolah Asal</span>
                <span class="font-bold uppercase">{{ $personalData->previous_school ?? '—' }}</span>
            </div>
            <div class="flex gap-4 text-sm">
                <span class="text-[#6A7686] font-semibold min-w-[130px]">Tanggal Daftar Ulang</span>
                <span class="font-bold text-gray-800">
                    {{ \Carbon\Carbon::parse($reReg->re_registered_at)->translatedFormat('d F Y, H:i') }} WIB
                </span>
            </div>
            <div class="flex gap-4 text-sm">
                <span class="text-[#6A7686] font-semibold min-w-[130px]">Status Berkas</span>
                <span class="font-bold text-green-600 flex items-center gap-1">
                    <i class="fa-solid fa-circle-check"></i> Complete & Registered
                </span>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            {{-- Tombol Kembali ke Dashboard --}}
            <a href="{{ route('dashboard') }}"
                class="px-8 py-3 bg-gray-100 text-[#080C1A] rounded-full text-base font-bold hover:bg-gray-200 transition-all">
                <i class="fa-solid fa-gauge mr-2"></i> Kembali ke Dashboard
            </a>

            {{-- Tombol Cetak Bukti Daftar Ulang --}}
            <a href="{{ route('admin.pendaftar.cetak', $personalData->id) }}" target="_blank"
                class="px-8 py-3 bg-[#FF1443] text-white rounded-full text-base font-bold hover:bg-[#c90e33] transition-all shadow-[0_4px_14px_rgba(255,20,67,0.25)]">
                <i class="fa-solid fa-print mr-2"></i> Cetak Bukti Daftar Ulang
            </a>
        </div>
    </div>
</div>
@endsection
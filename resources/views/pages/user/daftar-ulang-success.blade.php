@extends('layouts.user')

@section('title', 'Daftar Ulang Berhasil')

@section('content')

<style>
    /* 1. Efek menggambar garis centang (SVG) */
    @keyframes check-draw {
        0% {
            stroke-dashoffset: 60;
        }

        100% {
            stroke-dashoffset: 0;
        }
    }

    /* 2. Efek lingkaran hijau membesar saat pertama kali muncul */
    @keyframes ring-expand {
        0% {
            transform: scale(0);
            opacity: 0;
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    /* 3. Efek denyut (pulse) halus yang berulang pada lingkaran */
    @keyframes ring-pulse {

        0%,
        100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.3);
        }

        50% {
            transform: scale(1.03);
            box-shadow: 0 0 0 12px rgba(34, 197, 94, 0);
        }
    }

    /* 4. Efek bintang (sparkles) bermunculan */
    @keyframes sparkle-pop {
        0% {
            transform: scale(0) rotate(-20deg);
            opacity: 0;
        }

        70% {
            transform: scale(1.2) rotate(10deg);
            opacity: 1;
        }

        100% {
            transform: scale(1) rotate(0deg);
            opacity: 1;
        }
    }

    /* (Opsional) Animasi pudar (fade-in) untuk kotak putih utama */
    .animate-fade-in {
        animation: fadeIn 0.6s ease-out forwards;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(15px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="font-sans max-w-lg mx-auto py-6 sm:py-12 px-4 sm:px-1 text-[#080C1A]">
    <div class="bg-white border border-gray-200 rounded-[20px] shadow-sm px-5 py-8 sm:px-8 sm:py-16 text-center animate-fade-in">

        {{-- Animated check icon (Persis seperti _success_screen) --}}
        <div style="position: relative; display: inline-flex; align-items: center; justify-content: center;" class="mb-6 sm:mb-8">

            {{-- Sparkles --}}
            <span class="absolute -top-2 -right-1.5 text-yellow-300 text-base animate-[sparkle-pop_0.5s_cubic-bezier(.34,1.56,.64,1)_0.75s_both]">✦</span>
            <span class="absolute -bottom-1 -right-3.5 text-green-300 text-xs animate-[sparkle-pop_0.5s_cubic-bezier(.34,1.56,.64,1)_0.85s_both]">✦</span>
            <span class="absolute -top-1 -left-3.5 text-emerald-300 text-xs animate-[sparkle-pop_0.5s_cubic-bezier(.34,1.56,.64,1)_0.95s_both]">✦</span>
            <span class="absolute -bottom-1.5 -left-1.5 text-yellow-200 text-[10px] animate-[sparkle-pop_0.5s_cubic-bezier(.34,1.56,.64,1)_1.0s_both]">✦</span>

            {{-- Ring: Diperkecil di HP --}}
            <div class="w-[72px] h-[72px] sm:w-[88px] sm:h-[88px] rounded-full bg-green-50 border-2 border-green-200 flex items-center justify-center
            animate-[ring-expand_0.5s_cubic-bezier(.34,1.56,.64,1)_0.1s_both,ring-pulse_2s_ease-in-out_0.7s_infinite]">
                <svg class="w-8 h-8 sm:w-10 sm:h-10" viewBox="0 0 40 40" fill="none">
                    <polyline
                        class="animate-[check-draw_0.45s_ease-out_0.5s_both]"
                        points="8,21 17,30 33,12"
                        stroke="#22c55e" stroke-width="3.5"
                        stroke-linecap="round" stroke-linejoin="round"
                        stroke-dasharray="60" stroke-dashoffset="60" />
                </svg>
            </div>
        </div>

        <h2 class="text-xl sm:text-2xl font-black text-[#080C1A] mb-2 sm:mb-3">Daftar Ulang Berhasil! 🎉</h2>
        <p class="text-sm sm:text-base text-[#6A7686] max-w-lg mx-auto leading-relaxed mb-6 sm:mb-8">
            Data registrasi pendaftaran ulang Anda telah diverifikasi secara aman oleh sistem SPMB. Silakan lakukan pencetakan bukti pendaftaran fisik di bawah ini untuk dibawa saat masuk hari pertama sekolah.
        </p>

        {{-- Info Card — data riil dari DB --}}
        <div class="flex flex-col gap-3 sm:gap-2 bg-gray-50 border border-gray-200 rounded-2xl px-4 py-5 sm:px-8 mb-8 text-left w-full">

            <div class="flex flex-col sm:flex-row sm:gap-4 text-sm pb-2 border-b border-gray-200/60 sm:border-0 sm:pb-0">
                <span class="text-[#6A7686] font-semibold sm:min-w-[150px] mb-1 sm:mb-0 text-xs sm:text-sm uppercase sm:normal-case tracking-wider sm:tracking-normal pt-0.5">NISN</span>
                <span class="font-bold font-mono text-[#080C1A] break-all sm:break-normal">{{ $personalData->nisn ?? '—' }}</span>
            </div>

            <div class="flex flex-col sm:flex-row sm:gap-4 text-sm pb-2 border-b border-gray-200/60 sm:border-0 sm:pb-0">
                <span class="text-[#6A7686] font-semibold sm:min-w-[150px] mb-1 sm:mb-0 text-xs sm:text-sm uppercase sm:normal-case tracking-wider sm:tracking-normal pt-0.5">Nama Lengkap</span>
                <span class="font-bold uppercase text-[#080C1A]">{{ $personalData->full_name ?? '—' }}</span>
            </div>

            <div class="flex flex-col sm:flex-row sm:gap-4 text-sm pb-2 border-b border-gray-200/60 sm:border-0 sm:pb-0">
                <span class="text-[#6A7686] font-semibold sm:min-w-[150px] mb-1 sm:mb-0 text-xs sm:text-sm uppercase sm:normal-case tracking-wider sm:tracking-normal pt-0.5">Sekolah Asal</span>
                <span class="font-bold uppercase text-[#080C1A]">{{ $personalData->previous_school ?? '—' }}</span>
            </div>

            <div class="flex flex-col sm:flex-row sm:gap-4 text-sm pb-2 border-b border-gray-200/60 sm:border-0 sm:pb-0">
                <span class="text-[#6A7686] font-semibold sm:min-w-[150px] mb-1 sm:mb-0 text-xs sm:text-sm uppercase sm:normal-case tracking-wider sm:tracking-normal pt-0.5">Tanggal Daftar Ulang</span>
                <span class="font-bold text-[#080C1A]">
                    {{ \Carbon\Carbon::parse($reReg->re_registered_at)->translatedFormat('d F Y, H:i') }} WIB
                </span>
            </div>

            <div class="flex flex-col sm:flex-row sm:gap-4 text-sm">
                <span class="text-[#6A7686] font-semibold sm:min-w-[150px] mb-1 sm:mb-0 text-xs sm:text-sm uppercase sm:normal-case tracking-wider sm:tracking-normal pt-0.5">Status Berkas</span>
                <span class="font-bold text-green-600 flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-check"></i> Complete & Registered
                </span>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
            {{-- Tombol Kembali ke Dashboard --}}
            <a href="{{ route('dashboard') }}"
                class="w-full sm:w-auto flex items-center justify-center px-6 sm:px-8 py-3.5 bg-gray-100 text-[#080C1A] rounded-full text-sm sm:text-base font-bold hover:bg-gray-200 transition-all">
                <i class="fa-solid fa-gauge mr-2"></i> Kembali
            </a>

            {{-- Tombol Cetak Bukti Daftar Ulang --}}
            <a href="{{ route('laporan-daftar-ulang') }}" target="_blank"
                class="w-full sm:w-auto flex items-center justify-center px-6 sm:px-8 py-3.5 bg-[#FF1443] text-white rounded-full text-sm sm:text-base font-bold hover:bg-[#c90e33] transition-all shadow-[0_4px_14px_rgba(255,20,67,0.25)]">
                <i class="fa-solid fa-print mr-2"></i> Cetak Bukti
            </a>
        </div>
    </div>
</div>
@endsection
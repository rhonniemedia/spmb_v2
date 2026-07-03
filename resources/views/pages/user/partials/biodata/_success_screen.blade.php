<div x-show="isSubmitted" x-transition
    class="bg-white border border-gray-200 rounded-[20px] shadow-sm px-5 py-8 sm:px-8 sm:py-16 text-center">

    {{-- Animated check icon --}}
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

    <h2 class="text-xl sm:text-2xl font-black text-[#080C1A] mb-2 sm:mb-3">Biodata Berhasil Dikirim! 🎉</h2>
    <p class="text-sm sm:text-base text-[#6A7686] max-w-lg mx-auto leading-relaxed mb-6 sm:mb-8">
        Data kamu telah berhasil dikirim dan tersimpan di sistem SPMB. Silakan melanjutkan ke tahapan berikutnya apabila jadwal pendaftaran telah dibuka, serta pantau informasi terbaru melalui dashboard peserta.
    </p>

    {{-- Info Card — data dari DB (Dibuat vertikal di HP, horizontal di PC) --}}
    <div class="flex flex-col gap-3 sm:gap-2 bg-gray-50 border border-gray-200 rounded-2xl px-4 py-5 sm:px-8 mb-8 text-left w-full">

        <div class="flex flex-col sm:flex-row sm:items-start gap-1 sm:gap-4 text-sm pb-3 border-b border-gray-200/60 sm:border-0 sm:pb-0">
            <span class="text-[#6A7686] font-semibold sm:w-[150px] shrink-0 text-[11px] sm:text-sm uppercase sm:normal-case tracking-wider sm:tracking-normal pt-0.5">NISN</span>
            <span class="font-bold font-mono text-[#080C1A] break-all sm:break-normal text-sm" x-text="submitResult.nisn || '{{ $personalData?->nisn ?? '—' }}'"></span>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-start gap-1 sm:gap-4 text-sm pb-3 border-b border-gray-200/60 sm:border-0 sm:pb-0">
            <span class="text-[#6A7686] font-semibold sm:w-[150px] shrink-0 text-[11px] sm:text-sm uppercase sm:normal-case tracking-wider sm:tracking-normal pt-0.5">Nama Lengkap</span>
            <span class="font-bold uppercase text-[#080C1A] text-sm" x-text="submitResult.full_name || '{{ $personalData->full_name ?? '—' }}'"></span>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-start gap-1 sm:gap-4 text-sm pb-3 border-b border-gray-200/60 sm:border-0 sm:pb-0">
            <span class="text-[#6A7686] font-semibold sm:w-[150px] shrink-0 text-[11px] sm:text-sm uppercase sm:normal-case tracking-wider sm:tracking-normal pt-0.5">Sekolah Asal</span>
            <span class="font-bold uppercase text-[#080C1A] text-sm" x-text="submitResult.previous_school || '{{ $personalData->previous_school ?? '—' }}'"></span>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-start gap-1 sm:gap-4 text-sm pb-3 border-b border-gray-200/60 sm:border-0 sm:pb-0">
            <span class="text-[#6A7686] font-semibold sm:w-[150px] shrink-0 text-[11px] sm:text-sm uppercase sm:normal-case tracking-wider sm:tracking-normal pt-0.5">No. Telepon</span>
            <span class="font-bold text-[#080C1A] text-sm" x-text="'0' + (submitResult.phone_number || '{{ $personalData->phone_number ?? '—' }}')"></span>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-start gap-1 sm:gap-4 text-sm">
            <span class="text-[#6A7686] font-semibold sm:w-[150px] shrink-0 text-[11px] sm:text-sm uppercase sm:normal-case tracking-wider sm:tracking-normal pt-0.5">Status</span>
            @php
            $resolvedStatus = $personalData->profile_status ?? 'draft';
            @endphp
            <span class="font-bold text-sm flex items-center gap-1.5"
                :class="(submitResult.profile_status || '{{ $resolvedStatus }}') === 'final'
                    ? 'text-green-600'
                    : 'text-amber-500'">
                <i class="fa-solid"
                    :class="(submitResult.profile_status || '{{ $resolvedStatus }}') === 'final'
                        ? 'fa-circle-check'
                        : 'fa-clock'"></i>
                <span x-text="(submitResult.profile_status || '{{ $resolvedStatus }}') === 'final'
                    ? 'Final – Siap Mendaftar'
                    : 'Draft – Belum Final'">
                </span>
            </span>
        </div>
    </div>

    {{-- Tombol Aksi (Vertikal di HP, Horizontal di PC) --}}
    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center items-stretch sm:items-center">

        <a href="{{ route('dashboard') }}"
            class="flex items-center justify-center w-full sm:w-auto px-6 sm:px-8 py-3.5 bg-gray-100 text-[#080C1A] rounded-full text-sm sm:text-base font-bold hover:bg-gray-200 transition-all">
            <i class="fa-solid fa-gauge mr-2"></i> Kembali
        </a>

        @php
        $registrationOpen = isset($registrationSchedule)
        && $registrationSchedule
        && now()->between(
        \Carbon\Carbon::parse($registrationSchedule->start_date),
        \Carbon\Carbon::parse($registrationSchedule->end_date)
        );
        @endphp

        @if($registrationOpen)
        {{-- Jadwal pendaftaran sedang dibuka → tombol aktif --}}
        <a href="{{ route('registration') }}"
            class="flex items-center justify-center w-full sm:w-auto px-6 sm:px-8 py-3.5 bg-primary text-white rounded-full text-sm sm:text-base font-bold hover:bg-primary-hover transition-all">
            <i class="fa-solid fa-arrow-right mr-2"></i> Lanjut ke Pendaftaran
        </a>
        @else
        {{-- Jadwal belum/sudah lewat → tombol nonaktif + tooltip kapan dibuka --}}
        <!-- Tambahan: group flex sm:inline-flex w-full sm:w-auto agar tetap merentang di HP -->
        <div class="relative group flex sm:inline-flex w-full sm:w-auto">
            <button disabled
                class="flex items-center justify-center w-full sm:w-auto px-6 sm:px-8 py-3.5 bg-gray-200 text-gray-400 rounded-full text-sm sm:text-base font-bold cursor-not-allowed transition-all">
                <i class="fa-solid fa-arrow-right mr-2"></i> Lanjut ke Pendaftaran
            </button>

            {{-- Tooltip jadwal --}}
            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[220px] bg-[#080C1A] text-white text-xs font-medium rounded-xl px-3 py-2 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none text-center leading-relaxed z-10">
                @if(isset($registrationSchedule) && $registrationSchedule)
                Pendaftaran dibuka<br>
                {{ \Carbon\Carbon::parse($registrationSchedule->start_date)->translatedFormat('d F Y') }}
                –
                {{ \Carbon\Carbon::parse($registrationSchedule->end_date)->translatedFormat('d F Y') }}
                @else
                Jadwal pendaftaran<br>belum ditentukan
                @endif
                <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-[#080C1A]"></div>
            </div>
        </div>
        @endif

    </div>
</div>
@extends('layouts.user')

@section('title', 'Beranda')

@section('content')
{{-- BREADCRUMB --}}

<div class="max-w-7xl pb-12">

    <!-- ═══════════════════════════════════════════
       HERO BANNER
  ═══════════════════════════════════════════ -->
    <section class="mt-1">
        <div class="relative overflow-hidden rounded-2xl bg-[#080c1a]">

            <!-- Decorative -->
            <div class="absolute -top-16 -right-16 w-72 h-72 rounded-full bg-[#ff1443]/20 blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-60 h-60 rounded-full bg-[#ff1443]/10 blur-2xl pointer-events-none"></div>
            <!-- Confetti dots decoration -->
            <div class="absolute top-8 right-1/3 w-3 h-3 rounded-full bg-[#30b22d]/40 pointer-events-none"></div>
            <div class="absolute top-14 right-1/4 w-2 h-2 rounded-full bg-[#f59e0b]/40 pointer-events-none"></div>
            <div class="absolute top-6 right-1/2 w-2 h-2 rounded-full bg-[#3b82f6]/40 pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-8 p-8 md:p-10">

                <div class="w-full lg:flex-1">
                    <span class="inline-flex items-center gap-2 rounded-full bg-[#30b22d]/20 border border-[#30b22d]/30 px-4 py-1.5 text-xs text-[#30b22d] font-bold mb-5">
                        <i data-lucide="trophy" class="w-3.5 h-3.5"></i>
                        Selamat! Anda Dinyatakan Lolos Seleksi
                    </span>
                    <h2 class="text-4xl md:text-5xl font-bold text-white leading-tight">
                        Halo,<br />
                        <span class="text-[#ff1443]">
                            {{ Str::title($personalData?->nick_name ?? $personalData?->full_name ?? Auth::user()->name ?? 'Calon Siswa') }}!
                        </span>
                    </h2>
                    <p class="mt-4 text-[#6a7686] leading-7 max-w-2xl">
                        Anda dinyatakan <span class="text-white font-semibold">lolos seleksi</span> SPMB SMK TA 2026/2027.
                        Segera selesaikan proses daftar ulang sebelum batas waktu yang telah ditentukan.
                    </p>
                    <div class="mt-7 flex flex-wrap gap-3">
                        @php
                        $isConfirmed = $reRegistrationData['isConfirmed'] ?? false;
                        @endphp

                        @if(!$isConfirmed)
                        <button @click="$dispatch('open-modal-konfirmasi')" class="flex items-center gap-2 rounded-xl bg-[#ff1443] hover:bg-[#c90e33] text-white px-6 py-3 font-bold transition-all duration-200 cursor-pointer">
                            <i data-lucide="clipboard-check" class="w-4 h-4"></i>
                            Mulai Daftar Ulang
                        </button>
                        @else
                        <a href="{{ route('biodata') }}" class="flex items-center gap-2 rounded-xl bg-[#ff1443] hover:bg-[#c90e33] text-white px-6 py-3 font-bold transition-all duration-200 cursor-pointer">
                            <i data-lucide="clipboard-check" class="w-4 h-4"></i>
                            Mulai Daftar Ulang
                        </a>
                        @endif

                        <button class="flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 hover:bg-white/20 text-white px-6 py-3 font-medium transition-all duration-200 cursor-pointer">
                            <i data-lucide="download" class="w-4 h-4"></i>
                            Unduh Bukti Lolos
                        </button>
                    </div>
                </div>

                <div class="hidden lg:block flex-shrink-0">
                    <div class="bg-white/10 border border-white/20 rounded-[16px] px-5 py-9 backdrop-blur-md text-center shadow-xl w-[190px] flex flex-col justify-center items-center">

                        <div class="text-[12px] text-white/70 font-semibold uppercase tracking-widest mb-5">
                            Tahap Saat Ini
                        </div>

                        <div class="w-[72px] h-[72px] rounded-full border-[3px] border-white/10 border-t-white border-r-white border-b-white/30 mb-5 flex items-center justify-center relative animate-[spin_6s_linear_infinite] shrink-0">
                            <div class="absolute w-[54px] h-[54px] rounded-full bg-white/10 flex items-center justify-center animate-[spin_6s_linear_infinite] [animation-direction:reverse]">
                                <i data-lucide="clock" class="w-6 h-6 text-white/90"></i>
                            </div>
                        </div>

                        <div class="text-[15px] font-bold text-white leading-tight">
                            Registrasi Ulang
                        </div>

                        <div class="text-[11px] text-white/60 mt-2 font-medium">
                            Sedang Berlangsung
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════
       STATUS REGISTRASI + COUNTDOWN
  ═══════════════════════════════════════════ -->
    <section class="mt-6 grid lg:grid-cols-3 gap-6">

        <!-- Status Registrasi Ulang -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-[#e5e7eb] overflow-hidden">
            <div class="p-6 border-b border-[#e5e7eb]">
                <h2 class="text-lg font-bold">Status Registrasi Ulang</h2>
                <p class="text-sm text-[#6a7686] mt-0.5">Ringkasan dan progres pendaftaran ulang Anda.</p>
            </div>

            <!-- Status badge -->
            @php
            $isReRegistered = $reRegistrationData['isConfirmed'] ?? false;
            $statusBadgeBg = $isReRegistered ? 'bg-[#dcfce7] border-[#30b22d]/30' : 'bg-[#fef9c3] border-[#f59e0b]/30';
            $statusIconBg = $isReRegistered ? 'bg-[#30b22d]/20' : 'bg-[#f59e0b]/20';
            $statusIconColor = $isReRegistered ? 'text-[#30b22d]' : 'text-[#f59e0b]';
            $statusIcon = $isReRegistered ? 'check-circle-2' : 'clock-4';
            $statusTitle = $isReRegistered ? 'Sudah Registrasi Ulang' : 'Belum Registrasi Ulang';
            $statusDesc = $isReRegistered
            ? 'Berkas Anda sedang dalam proses verifikasi oleh panitia.'
            : 'Selesaikan semua checklist untuk melengkapi daftar ulang.';
            $statusTextColor = $isReRegistered ? 'text-[#166534]' : 'text-[#92400e]';
            $statusSubColor = $isReRegistered ? 'text-[#166534]/70' : 'text-[#92400e]/70';
            $badgeBg = $isReRegistered ? 'bg-[#30b22d]' : 'bg-[#f59e0b]';
            $badgeLabel = $isReRegistered ? 'Selesai' : 'Segera';
            @endphp
            <div class="px-6 pt-5 pb-4">
                <div class="flex items-center gap-4 p-4 rounded-2xl {{ $statusBadgeBg }} border">
                    <div class="w-12 h-12 rounded-2xl {{ $statusIconBg }} flex items-center justify-center shrink-0">
                        <i data-lucide="{{ $statusIcon }}" class="w-6 h-6 {{ $statusIconColor }}"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold {{ $statusTextColor }}">{{ $statusTitle }}</p>
                        <p class="text-sm {{ $statusSubColor }} mt-0.5">{{ $statusDesc }}</p>
                    </div>
                    <span class="text-xs font-bold {{ $badgeBg }} text-white px-3 py-1.5 rounded-full shrink-0">{{ $badgeLabel }}</span>
                </div>
            </div>

            <!-- Progress steps -->
            <div class="px-6 pb-2">
                <p class="text-sm font-semibold text-[#6a7686] mb-4">Progress Registrasi</p>
                @php
                $reRegProgressSteps = $reRegistrationData['reRegProgressSteps'] ?? collect([]);
                $currentStepIndex = $reRegProgressSteps->search(fn($s) => !$s['done']);
                if ($currentStepIndex === false) $currentStepIndex = $reRegProgressSteps->count() - 1;
                @endphp
                <div
                    x-data="{
                        current: {{ $currentStepIndex }},
                        steps: [
                            @foreach($reRegProgressSteps as $step)
                            { title: '{{ $step['title'] }}', done: {{ $step['done'] ? 'true' : 'false' }}, desc: '{{ $step['desc'] }}' }{{ !$loop->last ? ',' : '' }}
                            @endforeach
                        ]
                    }"
                    class="relative">
                    <!-- Track desktop -->
                    <div class="hidden md:block relative mb-10">
                        <div class="absolute top-5 left-0 right-0 h-1 bg-[#eff2f7] rounded-full"></div>
                        <div class="absolute top-5 left-0 h-1 bg-[#ff1443] rounded-full progress-bar"
                            :style="'width:' + (current / (steps.length - 1)) * 100 + '%'"></div>
                        <div class="relative grid grid-cols-5">
                            <template x-for="(step, index) in steps" :key="index">
                                <div class="flex flex-col items-center gap-2">
                                    <div
                                        class="w-10 h-10 rounded-full border-4 flex items-center justify-center font-bold text-sm transition-all duration-300"
                                        :class="{
                                        'bg-[#30b22d] border-[#30b22d] text-white': step.done && index < current,
                                        'bg-[#ff1443] border-[#ff1443] text-white step-active': index === current,
                                        'bg-white border-[#e5e7eb] text-[#6a7686]': index > current && !step.done
                                        }">
                                        <template x-if="step.done && index < current">
                                            <i data-lucide="check" class="w-4 h-4"></i>
                                        </template>
                                        <template x-if="index === current">
                                            <span x-text="index + 1"></span>
                                        </template>
                                        <template x-if="index > current">
                                            <span x-text="index + 1"></span>
                                        </template>
                                    </div>
                                    <p class="text-[11px] font-semibold text-center leading-tight"
                                        :class="{ 'text-[#30b22d]': step.done && index < current, 'text-[#ff1443]': index === current, 'text-[#6a7686]': index > current }"
                                        x-text="step.title"></p>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Mobile steps -->
                    <div class="md:hidden space-y-3 pb-4">
                        <template x-for="(step, index) in steps" :key="index">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold shrink-0 transition-all"
                                    :class="{
                                        'bg-[#30b22d] text-white': step.done && index < current,
                                        'bg-[#ff1443] text-white': index === current,
                                        'bg-[#eff2f7] text-[#6a7686]': index > current
                                    }">
                                    <template x-if="step.done && index < current">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </template>
                                    <template x-if="!(step.done && index < current)">
                                        <span x-text="index + 1"></span>
                                    </template>
                                </div>
                                <p class="text-sm font-semibold"
                                    :class="{ 'text-[#30b22d]': step.done && index < current, 'text-[#ff1443]': index === current, 'text-[#6a7686]': index > current }"
                                    x-text="step.title"></p>
                                <template x-if="index === current">
                                    <span class="ml-auto text-[10px] font-bold bg-[#ff1443]/10 text-[#ff1443] px-2 py-0.5 rounded-full">Sedang Berjalan</span>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Status Verifikasi row -->
            @php
            $fileStatus = $reRegistrationData['fileStatus'] ?? 'Belum Lengkap';
            $verificationStatus = $reRegistrationData['verificationStatus'] ?? 'Menunggu';
            $registrationStatus = $reRegistrationData['registrationStatus'] ?? 'Menunggu';

            $fileIsOk = $fileStatus === 'Lengkap';
            $fileBg = $fileIsOk ? 'bg-[#dcfce7] border-[#30b22d]/20' : 'bg-[#fef9c3] border-[#f59e0b]/20';
            $fileDot = $fileIsOk ? 'bg-[#30b22d]' : 'bg-[#f59e0b]';
            $fileText = $fileIsOk ? 'text-[#166534]' : 'text-[#92400e]';

            $veriIsOk = $verificationStatus === 'Terverifikasi';
            $veriIsProc = $verificationStatus === 'Diproses';
            $veriIsRej = $verificationStatus === 'Ditolak';
            $veriBg = $veriIsOk ? 'bg-[#dcfce7] border-[#30b22d]/20'
            : ($veriIsProc ? 'bg-[#fef9c3] border-[#f59e0b]/20'
            : ($veriIsRej ? 'bg-[#fee2e2] border-[#ff1443]/20'
            : 'bg-[#eff2f7] border-[#e5e7eb]'));
            $veriDot = $veriIsOk ? 'bg-[#30b22d]'
            : ($veriIsProc ? 'bg-[#f59e0b]'
            : ($veriIsRej ? 'bg-[#ff1443]'
            : 'bg-[#6a7686]'));
            $veriText = $veriIsOk ? 'text-[#166534]'
            : ($veriIsProc ? 'text-[#92400e]'
            : ($veriIsRej ? 'text-[#991b1b]'
            : 'text-[#6a7686]'));

            $regIsOk = $registrationStatus === 'Diterima';
            $regBg = $regIsOk ? 'bg-[#dcfce7] border-[#30b22d]/20' : 'bg-[#fef9c3] border-[#f59e0b]/20';
            $regDot = $regIsOk ? 'bg-[#30b22d]' : 'bg-[#f59e0b]';
            $regText = $regIsOk ? 'text-[#166534]' : 'text-[#92400e]';
            @endphp
            <div class="mx-6 mb-6 mt-2 grid grid-cols-3 gap-3">
                <div class="rounded-2xl {{ $fileBg }} border p-4 text-center">
                    <p class="text-[11px] text-[#6a7686] font-medium mb-2">Data</p>
                    <div class="flex items-center justify-center gap-1.5">
                        <span class="w-2 h-2 rounded-full {{ $fileDot }}"></span>
                        <p class="text-sm font-bold {{ $fileText }}">{{ $fileStatus }}</p>
                    </div>
                </div>
                <div class="rounded-2xl {{ $veriBg }} border p-4 text-center">
                    <p class="text-[11px] text-[#6a7686] font-medium mb-2">Verifikasi</p>
                    <div class="flex items-center justify-center gap-1.5">
                        <span class="w-2 h-2 rounded-full {{ $veriDot }}"></span>
                        <p class="text-sm font-bold {{ $veriText }}">{{ $verificationStatus }}</p>
                    </div>
                </div>
                <div class="rounded-2xl {{ $regBg }} border p-4 text-center">
                    <p class="text-[11px] text-[#6a7686] font-medium mb-2">Registrasi</p>
                    <div class="flex items-center justify-center gap-1.5">
                        <span class="w-2 h-2 rounded-full {{ $regDot }}"></span>
                        <p class="text-sm font-bold {{ $regText }}">{{ $registrationStatus }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Countdown -->
        @php
        $reRegDeadline = $reRegistrationData['reRegDeadline'] ?? null;
        $reRegDeadlineText = $reRegistrationData['reRegDeadlineText'] ?? '-';
        $deadlineIso = $reRegDeadline ? $reRegDeadline->toIso8601String() : null;
        @endphp
        <div x-data="countdownDaftarUlang('{{ $deadlineIso }}')" x-init="init()" class="bg-white rounded-2xl border border-[#e5e7eb] flex flex-col">
            <div class="p-6 border-b border-[#e5e7eb]">
                <h2 class="text-lg font-bold">Batas Waktu</h2>
                <p class="text-sm text-[#6a7686] mt-0.5">Deadline registrasi ulang.</p>
            </div>
            <div class="p-6 flex flex-col gap-5 flex-1">

                <!-- Countdown box -->
                <template x-if="!expired">
                    <div class="countdown-box rounded-2xl p-5 text-white">
                        <p class="text-xs font-medium opacity-80 mb-4 flex items-center gap-1.5">
                            <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                            Sisa Waktu Registrasi Ulang
                        </p>
                        <div class="grid grid-cols-4 gap-2 text-center">
                            <div class="bg-white/10 rounded-xl p-2">
                                <div class="text-2xl font-bold" x-text="days"></div>
                                <div class="text-[10px] opacity-70 mt-0.5">Hari</div>
                            </div>
                            <div class="bg-white/10 rounded-xl p-2">
                                <div class="text-2xl font-bold" x-text="hours"></div>
                                <div class="text-[10px] opacity-70 mt-0.5">Jam</div>
                            </div>
                            <div class="bg-white/10 rounded-xl p-2">
                                <div class="text-2xl font-bold" x-text="minutes"></div>
                                <div class="text-[10px] opacity-70 mt-0.5">Menit</div>
                            </div>
                            <div class="bg-white/10 rounded-xl p-2">
                                <div class="text-2xl font-bold" x-text="seconds"></div>
                                <div class="text-[10px] opacity-70 mt-0.5">Detik</div>
                            </div>
                        </div>
                    </div>
                </template>
                <template x-if="expired">
                    <div class="countdown-box-expired rounded-2xl p-5 text-white">
                        <p class="text-xs font-medium opacity-80 mb-3 flex items-center gap-1.5">
                            <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                            Masa Registrasi Ulang
                        </p>
                        <p class="text-base font-bold">Masa registrasi ulang telah berakhir.</p>
                        <p class="text-xs opacity-70 mt-2">Hubungi panitia untuk informasi lebih lanjut.</p>
                    </div>
                </template>

                <!-- Milestone list -->
                @php
                $announcementStep = $spmbSteps->first(fn($s) => str_contains($s->slug, 'pengumuman') || str_contains($s->slug, 'kelulusan'));
                $mplsStep = $spmbSteps->first(fn($s) => str_contains($s->slug, 'mpls') || str_contains($s->slug, 'orientasi'));
                $announceText = $announcementStep?->start_date
                ? \Carbon\Carbon::parse($announcementStep->start_date)->translatedFormat('d F Y') . ' · 08.00 WIB'
                : '-';
                $mplsText = $mplsStep?->period_text ?? ($mplsStep?->start_date
                ? \Carbon\Carbon::parse($mplsStep->start_date)->translatedFormat('d F Y')
                : '13, 14 Juli 2026');
                @endphp
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-2.5 h-2.5 rounded-full bg-[#30b22d] mt-1.5 shrink-0"></div>
                        <div>
                            <p class="font-semibold text-sm">Pengumuman Hasil Seleksi</p>
                            <p class="text-xs text-[#6a7686] mt-0.5">{{ $announceText }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-2.5 h-2.5 rounded-full bg-[#ff1443] mt-1.5 shrink-0"></div>
                        <div>
                            <p class="font-semibold text-sm">Batas Daftar Ulang</p>
                            <p class="text-xs text-[#6a7686] mt-0.5">{{ $reRegDeadlineText }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-2.5 h-2.5 rounded-full bg-[#3b82f6] mt-1.5 shrink-0"></div>
                        <div>
                            <p class="font-semibold text-sm">MPLS (Masa Pengenalan)</p>
                            <p class="text-xs text-[#6a7686] mt-0.5">{{ $mplsText }}</p>
                        </div>
                    </div>
                </div>

                <!-- CTA -->
                @php
                $isConfirmed = $reRegistrationData['isConfirmed'] ?? false;
                @endphp

                @if(!$isConfirmed)
                <button @click="$dispatch('open-modal-konfirmasi')" class="mt-auto w-full flex items-center justify-center gap-2 rounded-xl bg-[#ff1443] hover:bg-[#c90e33] text-white py-3 text-sm font-bold transition-all duration-200 cursor-pointer">
                    <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                    Lengkapi Daftar Ulang
                </button>
                @else
                <a href="{{ route('biodata') }}" class="mt-auto w-full flex items-center justify-center gap-2 rounded-xl bg-[#ff1443] hover:bg-[#c90e33] text-white py-3 text-sm font-bold transition-all duration-200 cursor-pointer">
                    <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                    Lengkapi Daftar Ulang
                </a>
                @endif
            </div>
        </div>

    </section>

    <!-- ═══════════════════════════════════════════
    SIDEBAR INFO: Profil Singkat + Jadwal Registrasi
═══════════════════════════════════════════ -->
    <section class="mt-6 grid lg:grid-cols-2 gap-6">

        <!-- ── 1. PROFIL SINGKAT ── -->
        @php
        $profil = $personalData;
        $acceptedConc = $reRegistrationData['acceptedConcentration'] ?? null;
        $concAlias = $acceptedConc?->alias ?? $acceptedConc?->code ?? '-';
        $concIcon = $acceptedConc?->icon ?? 'laptop';
        $regNumber = $registration?->registration_number ?? '-';
        $jalurMasuk = $registration?->admissionPath?->name ?? '-';
        $asalSekolah = $profil?->previous_school ?? '-';

        // Avatar Dinamis
        $fotoUrl = $profil?->photo
        ? asset('storage/' . $profil->photo)
        : 'https://ui-avatars.com/api/?name=' . urlencode($profil?->full_name ?? 'S') . '&background=ff1443&color=fff&size=128';

        $updatedAt = $profil?->updated_at
        ? \Carbon\Carbon::parse($profil->updated_at)->translatedFormat('d F Y')
        : '-';

        // Status Verifikasi dari variabel khusus Daftar Ulang
        $verificationStatus = $reRegistrationData['verificationStatus'] ?? 'Menunggu';
        $veriColor = $verificationStatus === 'Terverifikasi' ? 'text-[#166534]' : 'text-[#92400e]';
        @endphp

        <div class="bg-white rounded-2xl border border-[#e5e7eb] flex flex-col">
            <!-- Header Profil -->
            <div class="p-6 border-b border-[#e5e7eb]">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i data-lucide="user-circle" class="w-4 h-4 text-[#ff1443]"></i>
                        <h3 class="font-bold text-lg">Profil Singkat</h3>
                    </div>
                    <button @click="$dispatch('open-modal-profil')" class="shrink-0 flex items-center gap-1.5 rounded-xl border border-[#e5e7eb] px-3 py-1 text-[10px] font-semibold text-[#6a7686] hover:bg-[#eff2f7] transition-colors cursor-pointer">
                        <i data-lucide="user-round-search" class="w-3 h-3"></i>
                        Lihat Profil
                    </button>
                </div>
                <p class="text-sm text-[#6a7686] mt-0.5">Data diri dan status penerimaan siswa.</p>
            </div>

            <!-- Avatar, Nama & Nomor Pendaftaran -->
            <div class="px-6 py-5 border-b border-[#eff2f7]">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <!-- Info Kiri -->
                    <div class="flex items-center gap-4">
                        <div class="relative shrink-0">
                            <img src="{{ $fotoUrl }}" alt="Foto {{ $profil?->full_name }}" class="w-16 aspect-[3/3.5] rounded-xl object-cover ring-2 ring-[#e5e7eb]" />
                            <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-[#30b22d] border-2 border-white"></span>
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-base leading-tight truncate max-w-[160px]">{{ $profil?->full_name ?? '-' }}</p>
                            <p class="text-xs text-[#6a7686] mt-0.5">Peserta Didik Baru</p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="inline-flex items-center gap-1.5 text-[10px] font-bold bg-[#30b22d]/10 text-[#166534] rounded-full px-2.5 py-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#30b22d]"></span>Diterima
                                </span>
                                @if($acceptedConc)
                                <span class="inline-flex items-center gap-1.5 text-[10px] font-bold bg-[#3b82f6]/10 text-[#1d4ed8] rounded-full px-2.5 py-1">
                                    <i data-lucide="{{ $concIcon }}" class="w-3 h-3"></i>{{ $concAlias }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Nomor Pendaftaran Kanan -->
                    <div class="flex items-center gap-3 bg-[#ff1443]/[0.03] p-3 rounded-xl border border-[#ff1443]/10 shrink-0"
                        x-data="{}"
                        @click="navigator.clipboard.writeText('{{ $regNumber }}').then(() => { $el.querySelector('[data-tip]').innerText = 'Disalin!'; setTimeout(() => $el.querySelector('[data-tip]').innerText = 'Salin', 1500) })">
                        <div class="w-9 h-9 rounded-xl bg-[#ff1443]/10 flex items-center justify-center shrink-0">
                            <i data-lucide="hash" class="w-4 h-4 text-[#ff1443]"></i>
                        </div>
                        <div>
                            <p class="text-[11px] text-[#6a7686] font-medium">Nomor Pendaftaran</p>
                            <p class="text-base font-bold tracking-wide">{{ $regNumber }}</p>
                        </div>
                        <button class="ml-2 flex flex-col items-center justify-center gap-1 text-[10px] text-[#6a7686] hover:text-[#ff1443] transition-colors cursor-pointer" title="Salin">
                            <i data-lucide="copy" class="w-4 h-4"></i>
                            <span data-tip class="text-[9px]">Salin</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Atribut Rangkuman Data -->
            <div class="p-5 grid grid-cols-2 gap-3 flex-1">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-[#eff2f7]/60">
                    <div class="w-8 h-8 rounded-xl bg-[#8b5cf6]/10 flex items-center justify-center shrink-0">
                        <i data-lucide="users" class="w-4 h-4 text-[#8b5cf6]"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] text-[#6a7686]">Rombel</p>
                        <p class="text-xs font-semibold text-[#6a7686] truncate">Belum ditentukan</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 rounded-xl bg-[#eff2f7]/60">
                    <div class="w-8 h-8 rounded-xl bg-[#f59e0b]/10 flex items-center justify-center shrink-0">
                        <i data-lucide="route" class="w-4 h-4 text-[#f59e0b]"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] text-[#6a7686]">Jalur Masuk</p>
                        <p class="text-xs font-semibold truncate">{{ $jalurMasuk }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 rounded-xl bg-[#eff2f7]/60">
                    <div class="w-8 h-8 rounded-xl bg-[#0ea5e9]/10 flex items-center justify-center shrink-0">
                        <i data-lucide="school" class="w-4 h-4 text-[#0ea5e9]"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] text-[#6a7686]">Asal Sekolah</p>
                        <p class="text-xs font-semibold truncate">{{ $asalSekolah }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 rounded-xl bg-[#eff2f7]/60">
                    <div class="w-8 h-8 rounded-xl bg-[#30b22d]/10 flex items-center justify-center shrink-0">
                        <i data-lucide="shield-check" class="w-4 h-4 text-[#30b22d]"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] text-[#6a7686]">Status Berkas</p>
                        <p class="text-xs font-semibold truncate {{ $veriColor }}">{{ $verificationStatus }}</p>
                    </div>
                </div>

                <div class="col-span-2 flex items-center gap-3 p-3 rounded-xl bg-[#eff2f7]/60">
                    <div class="w-8 h-8 rounded-xl bg-[#f59e0b]/10 flex items-center justify-center shrink-0">
                        <i data-lucide="clock-3" class="w-4 h-4 text-[#f59e0b]"></i>
                    </div>
                    <div class="flex-1 flex items-center justify-between gap-2">
                        <p class="text-[10px] text-[#6a7686]">Terakhir Diperbarui</p>
                        <p class="text-xs font-semibold">{{ $updatedAt }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── 2. JADWAL REGISTRASI ── -->
        @php
        $regStep = $reRegistrationStep;
        $regStartDate = $regStep?->start_date ? \Carbon\Carbon::parse($regStep->start_date) : null;
        $regEndDate = $regStep?->end_date ? \Carbon\Carbon::parse($regStep->end_date) : null;

        // Logika Format Tanggal
        if ($regStep?->period_text) {
        $jadwalTanggal = $regStep->period_text;
        } elseif ($regStartDate && $regEndDate) {
        $startDay = $regStartDate->translatedFormat('l');
        $endDay = $regEndDate->translatedFormat('l');
        $startFmt = $regStartDate->translatedFormat('d');
        $endFmt = $regEndDate->translatedFormat('d F Y');
        $jadwalTanggal = "{$startDay}\u2013{$endDay} / {$startFmt}\u2013{$endFmt}";
        } else {
        $jadwalTanggal = '-';
        }

        // Ekstraksi Jam dari Deskripsi jika ada (contoh: 08.00 - 14.00)
        $jadwalJam = '-';
        if ($regStartDate && $regEndDate) {
        // Akan menghasilkan contoh: 08.00 - 16.00 WIB
        $jadwalJam = $regStartDate->format('H.i') . ' - ' . $regEndDate->format('H.i') . ' WIB';
        }
        @endphp

        <div class="bg-white rounded-2xl border border-[#e5e7eb] flex flex-col">
            <div class="p-6 border-b border-[#e5e7eb]">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i data-lucide="calendar-clock" class="w-4 h-4 text-[#ff1443]"></i>
                        <h3 class="font-bold text-lg">Jadwal Registrasi</h3>
                    </div>
                    <span class="text-[10px] font-bold bg-[#f59e0b]/10 text-[#d97706] px-3 py-1 rounded-full">Wajib Hadir</span>
                </div>
                <p class="text-sm text-[#6a7686] mt-0.5">Verifikasi berkas fisik dan daftar ulang tatap muka.</p>
            </div>

            <div class="p-6 flex flex-col gap-5 flex-1">
                <div class="rounded-2xl bg-[#080c1a] p-6 flex-1">
                    <div class="flex items-center gap-2 mb-5">
                        <i data-lucide="clipboard-check" class="w-4 h-4 text-[#ff1443]"></i>
                        <p class="text-xs font-bold text-white uppercase tracking-widest">Registrasi Ulang</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2 flex items-start gap-3 pb-4 border-b border-white/10">
                            <div class="w-9 h-9 rounded-xl bg-[#ff1443]/20 flex items-center justify-center shrink-0">
                                <i data-lucide="calendar" class="w-4 h-4 text-[#ff1443]"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-[#6a7686] font-medium mb-0.5">Hari / Tanggal</p>
                                <p class="text-base font-bold text-white">{{ $jadwalTanggal }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-xl bg-[#f59e0b]/20 flex items-center justify-center shrink-0">
                                <i data-lucide="clock" class="w-4 h-4 text-[#f59e0b]"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-[#6a7686] font-medium mb-0.5">Jam</p>
                                @if($jadwalJam !== '-')
                                <p class="text-sm font-bold text-white">{{ $jadwalJam }}</p>
                                @else
                                <p class="text-sm font-bold text-white/50 italic">Lihat pengumuman</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-xl bg-[#30b22d]/20 flex items-center justify-center shrink-0">
                                <i data-lucide="map-pin" class="w-4 h-4 text-[#30b22d]"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-[#6a7686] font-medium mb-0.5">Lokasi</p>
                                <p class="text-sm font-bold text-white">Aula Sekolah</p>
                            </div>
                        </div>

                        <div class="col-span-2 flex items-start gap-3 pt-4 border-t border-white/10">
                            <div class="w-9 h-9 rounded-xl bg-[#3b82f6]/20 flex items-center justify-center shrink-0">
                                <i data-lucide="shirt" class="w-4 h-4 text-[#3b82f6]"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-[#6a7686] font-medium mb-0.5">Pakaian</p>
                                <p class="text-sm font-bold text-white">Seragam Asal Sekolah (Rapi & Bersepatu)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pesan Peringatan -->
                <div class="rounded-xl bg-[#fffbeb] border border-[#f59e0b]/20 px-4 py-3.5 flex items-start gap-3">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-[#f59e0b] shrink-0 mt-0.5"></i>
                    <p class="text-xs text-[#92400e] leading-5">Calon siswa beserta orang tua/wali <span class="font-bold">wajib hadir</span> untuk menyerahkan MAP BIOLA berisi dokumen fisik dan penandatanganan berkas.</p>
                </div>
            </div>
        </div>

    </section>

    <!-- ═══════════════════════════════════════════
       MODAL PROFIL LENGKAP
  ═══════════════════════════════════════════ -->
    <div
        x-data="{ open: false }"
        @open-modal-profil.window="open = true"
        x-show="open"
        x-cloak
        class="fixed inset-0 z-[999] flex items-center justify-center p-4"
        style="display: none;">

        <!-- Backdrop -->
        <div
            class="absolute inset-0 bg-[#080c1a]/60 backdrop-blur-sm"
            @click="open = false"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
        </div>

        <!-- Panel -->
        <div
            class="relative w-full max-w-2xl max-h-[90vh] bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95">

            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-[#e5e7eb] shrink-0">
                <div class="flex items-center gap-2">
                    <i data-lucide="user-circle" class="w-4 h-4 text-[#ff1443]"></i>
                    <h3 class="font-bold text-base">Profil Lengkap</h3>
                </div>
                <button @click="open = false" class="w-8 h-8 rounded-xl flex items-center justify-center text-[#6a7686] hover:bg-[#eff2f7] hover:text-[#080c1a] transition-colors cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Modal Body — scrollable -->
            <div class="overflow-y-auto flex-1">

                <!-- Hero: foto + nama + badge -->
                <div class="bg-[#080c1a] px-6 py-6 flex items-center gap-5">
                    <img src="{{ $fotoUrl }}" alt="Foto"
                        class="w-20 aspect-[3/3.5] rounded-xl object-cover ring-2 ring-white/20 shrink-0" />
                    <div class="min-w-0">
                        <p class="text-xl font-bold text-white leading-tight truncate">{{ $profil?->full_name ?? '-' }}</p>
                        @if($profil?->nick_name)
                        <p class="text-sm text-white/50 mt-0.5">"{{ $profil->nick_name }}"</p>
                        @endif
                        <div class="flex flex-wrap items-center gap-2 mt-3">
                            <span class="inline-flex items-center gap-1.5 text-[10px] font-bold bg-[#30b22d]/20 text-[#30b22d] rounded-full px-2.5 py-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#30b22d]"></span>Diterima
                            </span>
                            @if($acceptedConc)
                            <span class="inline-flex items-center gap-1.5 text-[10px] font-bold bg-[#3b82f6]/20 text-[#60a5fa] rounded-full px-2.5 py-1">
                                <i data-lucide="{{ $concIcon }}" class="w-3 h-3"></i>{{ $acceptedConc->name ?? $concAlias }}
                            </span>
                            @endif
                            @if($profil?->gender)
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold bg-white/10 text-white/70 rounded-full px-2.5 py-1">
                                {{ $profil->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- No. Pendaftaran -->
                <div class="px-6 py-4 border-b border-[#e5e7eb] flex items-center justify-between gap-4"
                    x-data="{}"
                    @click="navigator.clipboard.writeText('{{ $regNumber }}').then(() => { $el.querySelector('[data-tip2]').innerText = 'Disalin!'; setTimeout(() => $el.querySelector('[data-tip2]').innerText = 'Salin', 1500) })">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-[#ff1443]/10 flex items-center justify-center shrink-0">
                            <i data-lucide="hash" class="w-4 h-4 text-[#ff1443]"></i>
                        </div>
                        <div>
                            <p class="text-[11px] text-[#6a7686] font-medium">Nomor Pendaftaran</p>
                            <p class="text-lg font-bold tracking-wider">{{ $regNumber }}</p>
                        </div>
                    </div>
                    <button class="flex items-center gap-1.5 text-xs text-[#6a7686] hover:text-[#ff1443] transition-colors cursor-pointer border border-[#e5e7eb] rounded-lg px-3 py-1.5" title="Salin">
                        <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                        <span data-tip2>Salin</span>
                    </button>
                </div>

                <!-- Grid data diri -->
                <div class="p-6 space-y-6">

                    <!-- Section: Identitas -->
                    <div>
                        <p class="text-[11px] font-bold text-[#6a7686] uppercase tracking-widest mb-3">Identitas Diri</p>
                        <div class="grid grid-cols-2 gap-3">
                            @php
                            $rows1 = [
                            ['icon' => 'user', 'color' => 'text-[#ff1443]', 'bg' => 'bg-[#ff1443]/10', 'label' => 'Nama Lengkap', 'val' => $profil?->full_name ?? '-'],
                            ['icon' => 'at-sign', 'color' => 'text-[#8b5cf6]', 'bg' => 'bg-[#8b5cf6]/10', 'label' => 'Panggilan', 'val' => $profil?->nick_name ?? '-'],
                            ['icon' => 'venus-mars', 'color' => 'text-[#ec4899]', 'bg' => 'bg-[#ec4899]/10', 'label' => 'Jenis Kelamin', 'val' => $profil?->gender === 'L' ? 'Laki-laki' : ($profil?->gender === 'P' ? 'Perempuan' : '-')],
                            ['icon' => 'droplets', 'color' => 'text-[#ef4444]', 'bg' => 'bg-[#ef4444]/10', 'label' => 'Gol. Darah', 'val' => $profil?->blood_type ?? '-'],
                            ['icon' => 'heart', 'color' => 'text-[#f43f5e]', 'bg' => 'bg-[#f43f5e]/10', 'label' => 'Anak Ke', 'val' => $profil?->child_order ? 'Ke-' . $profil->child_order : '-'],
                            ['icon' => 'users', 'color' => 'text-[#0ea5e9]', 'bg' => 'bg-[#0ea5e9]/10', 'label' => 'Jml. Saudara', 'val' => $profil?->number_of_siblings ?? '-'],
                            ];
                            @endphp
                            @foreach($rows1 as $r)
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-[#eff2f7]/60">
                                <div class="w-8 h-8 rounded-xl {{ $r['bg'] }} flex items-center justify-center shrink-0">
                                    <i data-lucide="{{ $r['icon'] }}" class="w-4 h-4 {{ $r['color'] }}"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[10px] text-[#6a7686]">{{ $r['label'] }}</p>
                                    <p class="text-xs font-semibold truncate">{{ $r['val'] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Section: Fisik & Kesehatan -->
                    @if($profil?->height || $profil?->weight || $profil?->medical_history)
                    <div>
                        <p class="text-[11px] font-bold text-[#6a7686] uppercase tracking-widest mb-3">Fisik & Kesehatan</p>
                        <div class="grid grid-cols-3 gap-3">
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-[#eff2f7]/60">
                                <div class="w-8 h-8 rounded-xl bg-[#30b22d]/10 flex items-center justify-center shrink-0">
                                    <i data-lucide="ruler" class="w-4 h-4 text-[#30b22d]"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[10px] text-[#6a7686]">Tinggi</p>
                                    <p class="text-xs font-semibold">{{ $profil->height ? $profil->height . ' cm' : '-' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-[#eff2f7]/60">
                                <div class="w-8 h-8 rounded-xl bg-[#f59e0b]/10 flex items-center justify-center shrink-0">
                                    <i data-lucide="weight" class="w-4 h-4 text-[#f59e0b]"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[10px] text-[#6a7686]">Berat</p>
                                    <p class="text-xs font-semibold">{{ $profil->weight ? $profil->weight . ' kg' : '-' }}</p>
                                </div>
                            </div>
                            @if($profil?->medical_history)
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-[#eff2f7]/60">
                                <div class="w-8 h-8 rounded-xl bg-[#ef4444]/10 flex items-center justify-center shrink-0">
                                    <i data-lucide="stethoscope" class="w-4 h-4 text-[#ef4444]"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[10px] text-[#6a7686]">Riwayat Sakit</p>
                                    <p class="text-xs font-semibold truncate">{{ $profil->medical_history }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Section: Pendidikan Sebelumnya -->
                    <div>
                        <p class="text-[11px] font-bold text-[#6a7686] uppercase tracking-widest mb-3">Pendidikan Sebelumnya</p>
                        <div class="grid grid-cols-2 gap-3">
                            @php
                            $rows2 = [
                            ['icon' => 'school', 'color' => 'text-[#0ea5e9]', 'bg' => 'bg-[#0ea5e9]/10', 'label' => 'Asal Sekolah', 'val' => $profil?->previous_school ?? '-'],
                            ['icon' => 'map-pin', 'color' => 'text-[#f59e0b]', 'bg' => 'bg-[#f59e0b]/10', 'label' => 'Kota Sekolah', 'val' => $profil?->previous_school_city ?? '-'],
                            ['icon' => 'building-2', 'color' => 'text-[#8b5cf6]', 'bg' => 'bg-[#8b5cf6]/10', 'label' => 'Status Sekolah', 'val' => $profil?->previous_school_status ?? '-'],
                            ['icon' => 'graduation-cap', 'color' => 'text-[#30b22d]', 'bg' => 'bg-[#30b22d]/10', 'label' => 'Tahun Lulus', 'val' => $profil?->graduation_year ?? '-'],
                            ];
                            @endphp
                            @foreach($rows2 as $r)
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-[#eff2f7]/60">
                                <div class="w-8 h-8 rounded-xl {{ $r['bg'] }} flex items-center justify-center shrink-0">
                                    <i data-lucide="{{ $r['icon'] }}" class="w-4 h-4 {{ $r['color'] }}"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[10px] text-[#6a7686]">{{ $r['label'] }}</p>
                                    <p class="text-xs font-semibold truncate">{{ $r['val'] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Section: Minat & Bakat -->
                    @if($profil?->interest_art || $profil?->interest_sport || $profil?->interest_organization || $profil?->extracurricular_choice)
                    <div>
                        <p class="text-[11px] font-bold text-[#6a7686] uppercase tracking-widest mb-3">Minat & Bakat</p>
                        <div class="grid grid-cols-2 gap-3">
                            @php
                            $minat = [
                            ['icon' => 'music', 'color' => 'text-[#ec4899]', 'bg' => 'bg-[#ec4899]/10', 'label' => 'Seni', 'val' => $profil->interest_art],
                            ['icon' => 'trophy', 'color' => 'text-[#f59e0b]', 'bg' => 'bg-[#f59e0b]/10', 'label' => 'Olahraga', 'val' => $profil->interest_sport],
                            ['icon' => 'landmark', 'color' => 'text-[#8b5cf6]', 'bg' => 'bg-[#8b5cf6]/10', 'label' => 'Organisasi', 'val' => $profil->interest_organization],
                            ['icon' => 'star', 'color' => 'text-[#0ea5e9]', 'bg' => 'bg-[#0ea5e9]/10', 'label' => 'Ekskul Pilihan', 'val' => $profil->extracurricular_choice],
                            ];
                            @endphp
                            @foreach($minat as $m)
                            @if($m['val'])
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-[#eff2f7]/60">
                                <div class="w-8 h-8 rounded-xl {{ $m['bg'] }} flex items-center justify-center shrink-0">
                                    <i data-lucide="{{ $m['icon'] }}" class="w-4 h-4 {{ $m['color'] }}"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[10px] text-[#6a7686]">{{ $m['label'] }}</p>
                                    <p class="text-xs font-semibold truncate">{{ $m['val'] }}</p>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Section: Status Pendaftaran -->
                    <div>
                        <p class="text-[11px] font-bold text-[#6a7686] uppercase tracking-widest mb-3">Status Pendaftaran</p>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-[#eff2f7]/60">
                                <div class="w-8 h-8 rounded-xl bg-[#f59e0b]/10 flex items-center justify-center shrink-0">
                                    <i data-lucide="route" class="w-4 h-4 text-[#f59e0b]"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[10px] text-[#6a7686]">Jalur Masuk</p>
                                    <p class="text-xs font-semibold truncate">{{ $jalurMasuk }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-[#eff2f7]/60">
                                <div class="w-8 h-8 rounded-xl bg-[#30b22d]/10 flex items-center justify-center shrink-0">
                                    <i data-lucide="shield-check" class="w-4 h-4 text-[#30b22d]"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[10px] text-[#6a7686]">Status Berkas</p>
                                    <p class="text-xs font-semibold truncate {{ $veriColor ?? 'text-[#92400e]' }}">{{ $verificationStatus ?? 'Menunggu' }}</p>
                                </div>
                            </div>
                            <div class="col-span-2 flex items-center gap-3 p-3 rounded-xl bg-[#eff2f7]/60">
                                <div class="w-8 h-8 rounded-xl bg-[#f59e0b]/10 flex items-center justify-center shrink-0">
                                    <i data-lucide="clock-3" class="w-4 h-4 text-[#f59e0b]"></i>
                                </div>
                                <div class="flex-1 flex items-center justify-between gap-2">
                                    <p class="text-[10px] text-[#6a7686]">Terakhir Diperbarui</p>
                                    <p class="text-xs font-semibold">{{ $updatedAt }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-[#e5e7eb] shrink-0 flex justify-end">
                <button @click="open = false" class="flex items-center gap-2 rounded-xl bg-[#eff2f7] hover:bg-[#e5e7eb] text-[#080c1a] px-5 py-2.5 text-sm font-semibold transition-colors cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                    Tutup
                </button>
            </div>

        </div>
    </div>

    <!-- ═══════════════════════════════════════════
       PERSYARATAN DAFTAR ULANG
  ═══════════════════════════════════════════ -->
    <div class="mt-8">
        <div class="mb-6">
            <h2 class="font-bold text-xl text-[#080c1a]">Persyaratan Daftar Ulang</h2>
            <p class="text-sm text-[#6a7686] mt-1">Silakan lengkapi seluruh persyaratan berikut sebelum datang ke sekolah.</p>
        </div>

        <section class="grid lg:grid-cols-2 gap-6 items-stretch">

            <div class="bg-white rounded-2xl border border-[#e5e7eb] overflow-hidden flex flex-col h-full">

                <div class="p-5 border-b border-[#e5e7eb] flex items-center justify-between gap-3 shrink-0 min-h-[88px] bg-[#E3BEB8]">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-[#ff1443]/10 flex items-center justify-center shrink-0">
                            <i data-lucide="clipboard-list" class="w-4 h-4 text-[#ff1443]"></i>
                        </div>
                        <div>
                            <h2 class="font-bold text-base leading-tight text-[#080c1a]">Berkas yang Diperlukan</h2>
                            <p class="text-xs text-[#6a7686] mt-0.5">Siapkan seluruh berkas berikut.</p>
                        </div>
                    </div>
                    <span class="shrink-0 text-xs font-bold bg-[#eff2f7] text-[#6a7686] px-3 py-1 rounded-full">9 Berkas</span>
                </div>

                <div class="flex flex-col flex-1">

                    @php
                    $berkas = [
                    ['icon' => 'printer', 'color' => 'text-[#ff1443]', 'bg' => 'bg-[#ff1443]/10', 'label' => 'Bukti Daftar Ulang', 'desc' => 'Download & cetak'],
                    ['icon' => 'file-pen', 'color' => 'text-[#8b5cf6]', 'bg' => 'bg-[#8b5cf6]/10', 'label' => 'Formulir Pernyataan', 'desc' => 'Bermaterai & ditandatangani'],
                    ['icon' => 'file-text', 'color' => 'text-[#0ea5e9]', 'bg' => 'bg-[#0ea5e9]/10', 'label' => 'Formulir Data Pribadi', 'desc' => 'Data ortu, minat & bakat · ditandatangani'],
                    ['icon' => 'graduation-cap', 'color' => 'text-[#f59e0b]', 'bg' => 'bg-[#f59e0b]/10', 'label' => 'Ijazah / SKL', 'desc' => 'Fotokopi dilegalisir · 3 rangkap'],
                    ['icon' => 'users', 'color' => 'text-[#30b22d]', 'bg' => 'bg-[#30b22d]/10', 'label' => 'Kartu Keluarga (KK)', 'desc' => 'Fotokopi · 3 rangkap'],
                    ['icon' => 'baby', 'color' => 'text-[#06b6d4]', 'bg' => 'bg-[#06b6d4]/10', 'label' => 'Akta Kelahiran', 'desc' => 'Fotokopi · 3 rangkap'],
                    ['icon' => 'id-card', 'color' => 'text-[#ec4899]', 'bg' => 'bg-[#ec4899]/10', 'label' => 'KTP Orang Tua / Wali', 'desc' => 'Fotokopi · 3 rangkap'],
                    ['icon' => 'tag', 'color' => 'text-[#f97316]', 'bg' => 'bg-[#f97316]/10', 'label' => 'Kartu KIP / PKH', 'desc' => 'Jika ada · fotokopi · 3 rangkap'],
                    ['icon' => 'camera', 'color' => 'text-[#6366f1]', 'bg' => 'bg-[#6366f1]/10', 'label' => 'Pas Foto Berwarna 3×4', 'desc' => '3 lembar'],
                    ];
                    @endphp

                    <div class="flex flex-col flex-1 divide-y divide-[#eff2f7]">

                        @foreach($berkas as $item)
                        <div
                            class="flex flex-1 items-center gap-4 px-5 py-3 hover:bg-[#f8fafc] transition-all duration-200">

                            <div
                                class="w-10 h-10 rounded-xl {{ $item['bg'] }} flex items-center justify-center shrink-0">
                                <i data-lucide="{{ $item['icon'] }}"
                                    class="w-4 h-4 {{ $item['color'] }}"></i>
                            </div>

                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-[#080c1a] leading-tight">
                                    {{ $item['label'] }}
                                </h4>

                                <p class="text-xs text-[#6a7686] mt-1">
                                    {{ $item['desc'] }}
                                </p>
                            </div>

                            <div
                                class="w-7 h-7 rounded-full bg-[#eff2f7] flex items-center justify-center text-xs font-bold text-[#6a7686] shrink-0">
                                {{ $loop->iteration }}
                            </div>

                        </div>
                        @endforeach

                    </div>

                </div>
            </div>

            <div class="bg-white rounded-2xl border border-[#e5e7eb] overflow-hidden flex flex-col h-full">

                <div class="p-5 border-b border-[#e5e7eb] flex items-center justify-between gap-3 shrink-0 min-h-[88px] bg-[#E3BEB8]">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-[#f59e0b]/10 flex items-center justify-center shrink-0">
                            <i data-lucide="folder-open" class="w-4 h-4 text-[#f59e0b]"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-[#080c1a] leading-tight">Ketentuan MAP BIOLA</h2>
                            <p class="text-xs text-[#6a7686] mt-0.5">Kelompokkan berkas sesuai jurusan.</p>
                        </div>
                    </div>
                </div>

                <div class="p-5 flex flex-col flex-1">

                    <div class="flex items-start gap-2.5 p-3.5 rounded-xl bg-[#eff2f7]/80 border border-[#e5e7eb] shrink-0 mb-4">
                        <i data-lucide="info" class="w-4 h-4 text-[#6a7686] shrink-0 mt-0.5"></i>
                        <p class="text-xs text-[#6a7686] leading-5">
                            Semua persyaratan dimasukkan ke dalam <strong class="text-[#080c1a]">MAP BIOLA</strong> dengan warna sesuai Konsentrasi Keahlian masing-masing.
                        </p>
                    </div>

                    <div class="flex flex-col flex-1 gap-3">

                        <!-- DPIB -->
                        <div class="flex-1 flex items-center gap-4 p-4 rounded-2xl border border-[#fde68a] bg-[#fef9c3]">
                            <div class="w-11 h-11 rounded-xl bg-[#f59e0b] flex items-center justify-center shrink-0 shadow-sm">
                                <i data-lucide="folder" class="w-5 h-5 text-white"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-[#92400e]/70 font-medium">Warna Kuning</p>
                                <p class="font-bold text-sm text-[#92400e]">DPIB</p>
                            </div>
                            <span class="w-3.5 h-3.5 rounded-full bg-[#f59e0b] shrink-0"></span>
                        </div>

                        <!-- TEI -->
                        <div class="flex-1 flex items-center gap-4 p-4 rounded-2xl border border-[#d6c1a5] bg-[#f5ede4]">
                            <div class="w-11 h-11 rounded-xl bg-[#8b5e3c] flex items-center justify-center shrink-0 shadow-sm">
                                <i data-lucide="folder" class="w-5 h-5 text-white"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-[#6f4e37]/70 font-medium">Warna Coklat</p>
                                <p class="font-bold text-sm text-[#6f4e37]">TEI</p>
                            </div>
                            <span class="w-3.5 h-3.5 rounded-full bg-[#8b5e3c] shrink-0"></span>
                        </div>

                        <!-- TITL / TPTL -->
                        <div class="flex-1 flex items-center gap-4 p-4 rounded-2xl border border-[#fecaca] bg-[#fef2f2]">
                            <div class="w-11 h-11 rounded-xl bg-[#ff1443] flex items-center justify-center shrink-0 shadow-sm">
                                <i data-lucide="folder" class="w-5 h-5 text-white"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-[#991b1b]/70 font-medium">Warna Merah</p>
                                <p class="font-bold text-sm text-[#991b1b]">TITL & TPTL</p>
                            </div>
                            <span class="w-3.5 h-3.5 rounded-full bg-[#ff1443] shrink-0"></span>
                        </div>

                        <!-- TKJ -->
                        <div class="flex-1 flex items-center gap-4 p-4 rounded-2xl border border-[#d1d5db] bg-white">
                            <div class="w-11 h-11 rounded-xl bg-white border-2 border-[#d1d5db] flex items-center justify-center shrink-0 shadow-sm">
                                <i data-lucide="folder" class="w-5 h-5 text-[#6b7280]"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-[#6b7280] font-medium">Warna Putih</p>
                                <p class="font-bold text-sm text-[#374151]">TKJ</p>
                            </div>
                            <span class="w-3.5 h-3.5 rounded-full bg-white border border-[#9ca3af] shrink-0"></span>
                        </div>

                        <!-- TKR / TSM -->
                        <div class="flex-1 flex items-center gap-4 p-4 rounded-2xl border border-[#bbf7d0] bg-[#f0fdf4]">
                            <div class="w-11 h-11 rounded-xl bg-[#30b22d] flex items-center justify-center shrink-0 shadow-sm">
                                <i data-lucide="folder" class="w-5 h-5 text-white"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-[#166534]/70 font-medium">Warna Hijau</p>
                                <p class="font-bold text-sm text-[#166534]">TKR & TSM</p>
                            </div>
                            <span class="w-3.5 h-3.5 rounded-full bg-[#30b22d] shrink-0"></span>
                        </div>

                        <!-- TM / TLAS -->
                        <div class="flex-1 flex items-center gap-4 p-4 rounded-2xl border border-[#bfdbfe] bg-[#eff6ff]">
                            <div class="w-11 h-11 rounded-xl bg-[#3b82f6] flex items-center justify-center shrink-0 shadow-sm">
                                <i data-lucide="folder" class="w-5 h-5 text-white"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-[#1e40af]/70 font-medium">Warna Biru</p>
                                <p class="font-bold text-sm text-[#1e40af]">TM & TLAS</p>
                            </div>
                            <span class="w-3.5 h-3.5 rounded-full bg-[#3b82f6] shrink-0"></span>
                        </div>

                    </div>
                </div>

            </div>

        </section>
    </div>

    <!-- ═══════════════════════════════════════════
       CHECKLIST + DOKUMEN PENTING
  ═══════════════════════════════════════════ -->
    <section class="mt-6">
        <div class="mb-5">
            <h2 class="text-xl font-bold">Kelengkapan Berkas Daftar Ulang</h2>
            <p class="text-sm text-[#6a7686] mt-0.5">Pantau status upload dan kelengkapan dokumen Anda.</p>
        </div>

        <div class="grid xl:grid-cols-5 gap-6">

            <!-- Checklist Kelengkapan Data -->
            @php
            $pd = $personalData;
            $isFinal = $pd?->profile_status === 'final';

            // 1. Data Pribadi (Asumsi minimal nama, nisn, dan gender terisi)
            $isDataPribadiDone = $pd && $pd->full_name && $pd->nisn_hash && $pd->gender;
            $dataPribadiStatus = $isDataPribadiDone ? 'Selesai' : ($pd ? 'Sedang diisi' : 'Belum diisi');
            $dataPribadiState = $isDataPribadiDone ? 'done' : ($pd ? 'progress' : 'empty');

            // 2. Alamat (Asumsi minimal alamat dan kecamatan terisi)
            $isAlamatDone = $pd && $pd->address_encrypted && $pd->district_encrypted;
            $alamatStatus = $isAlamatDone ? 'Selesai' : ($pd && $pd->address_encrypted ? 'Sedang diisi' : 'Belum diisi');
            $alamatState = $isAlamatDone ? 'done' : ($pd && $pd->address_encrypted ? 'progress' : 'empty');

            // 3. Orang Tua (Berdasarkan count parent data)
            $isOrtuDone = $parentDataCount >= 2;
            $ortuStatus = $isOrtuDone ? 'Selesai' : ($parentDataCount == 1 ? 'Kurang 1 data' : 'Belum diisi');
            $ortuState = $isOrtuDone ? 'done' : ($parentDataCount == 1 ? 'progress' : 'empty');

            // 4. Pendidikan (Asumsi asal sekolah dan tahun lulus terisi)
            $isPendidikanDone = $pd && $pd->previous_school && $pd->graduation_year;
            $pendidikanStatus = $isPendidikanDone ? 'Selesai' : ($pd && $pd->previous_school ? 'Sedang diisi' : 'Belum diisi');
            $pendidikanState = $isPendidikanDone ? 'done' : ($pd && $pd->previous_school ? 'progress' : 'empty');

            // 5. Pas Foto
            $isFotoDone = !empty($pd?->photo);
            $fotoStatus = $isFotoDone ? 'Selesai' : 'Belum diupload';
            $fotoState = $isFotoDone ? 'done' : 'empty';

            // 6 & 7. Surat Pernyataan & Formulir Data Pribadi (Hanya aktif jika final)
            $isSuratDone = $isFinal;
            $suratStatus = $isSuratDone ? 'Siap download' : 'Belum siap download';
            $suratState = $isSuratDone ? 'done' : 'empty';

            $checklistItems = [
            ['icon' => 'user', 'label' => 'Data Pribadi', 'state' => $dataPribadiState, 'status' => $dataPribadiStatus],
            ['icon' => 'map-pin', 'label' => 'Alamat', 'state' => $alamatState, 'status' => $alamatStatus],
            ['icon' => 'users', 'label' => 'Orang Tua', 'state' => $ortuState, 'status' => $ortuStatus],
            ['icon' => 'graduation-cap', 'label' => 'Pendidikan', 'state' => $pendidikanState, 'status' => $pendidikanStatus],
            ['icon' => 'camera', 'label' => 'Pas Foto', 'state' => $fotoState, 'status' => $fotoStatus],
            ['icon' => 'file-signature', 'label' => 'Surat Pernyataan', 'state' => $suratState, 'status' => $suratStatus],
            ['icon' => 'file-down', 'label' => 'Formulir Data Pribadi', 'state' => $suratState, 'status' => $suratStatus],
            ];

            $doneCount = collect($checklistItems)->where('state', 'done')->count();
            $totalChecklist = count($checklistItems);
            $checklistPct = round(($doneCount / $totalChecklist) * 100);
            @endphp

            <div class="xl:col-span-2 bg-white rounded-2xl border border-[#e5e7eb] flex flex-col">

                <div class="p-6 border-b border-[#e5e7eb] flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="folder-check" class="w-5 h-5 text-[#ff1443]"></i>
                            <h3 class="font-bold text-base">Checklist Data</h3>
                        </div>
                        <p class="text-sm text-[#6a7686] mt-0.5">Pantau status pengisian data pribadi Anda.</p>
                    </div>
                    <span class="text-[11px] font-bold bg-[#ff1443]/10 text-[#ff1443] px-3 py-1 rounded-full shrink-0">
                        {{ $doneCount }} / {{ $totalChecklist }} Selesai
                    </span>
                </div>

                <div class="px-5 pt-4 pb-3">
                    <div class="flex justify-between text-xs text-[#6a7686] mb-1.5">
                        <span>Kelengkapan data</span>
                        <span class="font-bold text-[#080c1a]">{{ $checklistPct }}%</span>
                    </div>
                    <div class="h-2 rounded-full bg-[#eff2f7]">
                        <div class="h-2 rounded-full bg-gradient-to-r from-[#f59e0b] to-[#30b22d] progress-bar transition-all duration-500" style="width: {{ $checklistPct }}%"></div>
                    </div>
                </div>

                <div class="divide-y divide-[#eff2f7] flex-1">
                    @foreach($checklistItems as $item)
                    @php
                    // State: EMPTY (Belum diisi)
                    if($item['state'] === 'empty') {
                    $wrapperBg = 'bg-[#fee2e2]/30 hover:bg-[#fee2e2]/70';
                    $iconBg = 'bg-[#eff2f7]';
                    $iconColor = 'text-[#6a7686]';
                    $titleColor = 'text-[#6a7686]';
                    $statusColor = 'text-[#ed6b60]';
                    }
                    // State: PROGRESS (Sedang diisi)
                    elseif($item['state'] === 'progress') {
                    $wrapperBg = 'bg-[#fef9c3]/40 hover:bg-[#fef9c3]/80';
                    $iconBg = 'bg-[#fef9c3]';
                    $iconColor = 'text-[#f59e0b]';
                    $titleColor = 'text-[#080c1a]';
                    $statusColor = 'text-[#f59e0b]';
                    }
                    // State: DONE (Selesai)
                    else {
                    $wrapperBg = 'hover:bg-[#dcfce7]/40';
                    $iconBg = 'bg-[#dcfce7]';
                    $iconColor = 'text-[#30b22d]';
                    $titleColor = 'text-[#080c1a]';
                    $statusColor = 'text-[#166534]';
                    }
                    @endphp

                    <div class="flex items-center gap-3 px-5 py-3.5 {{ $wrapperBg }} transition-colors duration-200 cursor-pointer group">
                        <div class="w-8 h-8 rounded-xl {{ $iconBg }} flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform duration-200">
                            <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4 {{ $iconColor }}"></i>
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold leading-tight {{ $titleColor }}">{{ $item['label'] }}</p>
                            <p class="text-[11px] {{ $statusColor }} font-medium mt-0.5">{{ $item['status'] }}</p>
                        </div>

                        @if($item['state'] === 'empty')
                        <div class="w-6 h-6 rounded-full border-2 border-dashed border-[#ed6b60] shrink-0"></div>
                        @elseif($item['state'] === 'progress')
                        <div class="w-6 h-6 rounded-full border-2 border-dashed border-[#f59e0b] flex items-center justify-center shrink-0">
                            <div class="w-2 h-2 rounded-full bg-[#f59e0b]"></div>
                        </div>
                        @else
                        <div class="w-6 h-6 rounded-full bg-[#30b22d] flex items-center justify-center shrink-0 group-hover:bg-[#166534] transition-colors duration-200">
                            <i data-lucide="check" class="w-3 h-3 text-white"></i>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                <div class="p-4 border-t border-[#e5e7eb]">
                    @if($doneCount === $totalChecklist)
                    <button class="w-full flex items-center justify-center gap-2 rounded-xl bg-[#30b22d] hover:bg-[#166534] text-white py-2.5 text-sm font-bold transition-all duration-200 cursor-pointer">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        Seluruh Data Lengkap
                    </button>
                    @else
                    <a href="{{ route('biodata') }}"
                        class="w-full flex items-center justify-center gap-2 rounded-xl bg-[#ff1443] hover:bg-[#c90e33] text-white py-2.5 text-sm font-bold transition-all duration-200">
                        <i data-lucide="arrow-right-circle" class="w-4 h-4"></i>
                        Lanjutkan Pengisian
                    </a>
                    @endif
                </div>

            </div>

            <!-- Dokumen Penting -->
            <div class="xl:col-span-3 bg-white rounded-2xl border border-[#e5e7eb] flex flex-col">
                <div class="p-6 border-b border-[#e5e7eb] flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-base">Dokumen Penting</h3>
                        <p class="text-sm text-[#6a7686] mt-0.5">Unduh semua dokumen registrasi dari satu tempat.</p>
                    </div>
                    <span class="rounded-full bg-[#30b22d]/10 text-[#166534] text-[11px] font-bold px-3 py-1 shrink-0">4 Dokumen</span>
                </div>

                <div class="p-6 grid sm:grid-cols-2 gap-4 flex-1">

                    <div class="group rounded-2xl border border-[#e5e7eb] p-5 hover:border-[#30b22d] hover:bg-[#dcfce7]/30 transition-all duration-200 cursor-pointer flex flex-col">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-[#30b22d]/10 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i data-lucide="trophy" class="w-6 h-6 text-[#30b22d]"></i>
                            </div>
                            <span class="text-[10px] font-bold bg-[#30b22d]/10 text-[#166534] px-2 py-0.5 rounded-full">Tersedia</span>
                        </div>
                        <p class="font-bold text-[#080c1a]">Bukti Lolos Seleksi</p>
                        <p class="text-xs text-[#6a7686] mt-1 leading-5">Surat resmi pernyataan kelulusan seleksi SPMB.</p>
                        <div class="mt-auto pt-4 flex items-center gap-1 text-xs font-bold text-[#30b22d] group-hover:gap-2 transition-all duration-200">
                            <i data-lucide="download" class="w-3.5 h-3.5"></i> Unduh PDF
                        </div>
                    </div>

                    <div class="group rounded-2xl border border-[#e5e7eb] p-5 hover:border-[#3b82f6] hover:bg-[#dbeafe]/30 transition-all duration-200 cursor-pointer flex flex-col">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-[#3b82f6]/10 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i data-lucide="clipboard-check" class="w-6 h-6 text-[#3b82f6]"></i>
                            </div>
                            <span class="text-[10px] font-bold bg-[#f59e0b]/10 text-[#92400e] px-2 py-0.5 rounded-full">Proses</span>
                        </div>
                        <p class="font-bold text-[#080c1a]">Bukti Registrasi Ulang</p>
                        <p class="text-xs text-[#6a7686] mt-1 leading-5">Tersedia setelah proses verifikasi selesai.</p>
                        <div class="mt-auto pt-4 flex items-center gap-1 text-xs font-bold text-[#6a7686] transition-all duration-200">
                            <i data-lucide="lock" class="w-3.5 h-3.5"></i> Menunggu Verifikasi
                        </div>
                    </div>

                    <div class="group rounded-2xl border border-[#e5e7eb] p-5 hover:border-[#8b5cf6] hover:bg-[#f5f3ff]/30 transition-all duration-200 cursor-pointer flex flex-col">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-[#8b5cf6]/10 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i data-lucide="file-signature" class="w-6 h-6 text-[#8b5cf6]"></i>
                            </div>
                            <span class="text-[10px] font-bold bg-[#30b22d]/10 text-[#166534] px-2 py-0.5 rounded-full">Tersedia</span>
                        </div>
                        <p class="font-bold text-[#080c1a]">Surat Pernyataan</p>
                        <p class="text-xs text-[#6a7686] mt-1 leading-5">Template surat pernyataan orang tua/wali siswa.</p>
                        <div class="mt-auto pt-4 flex items-center gap-1 text-xs font-bold text-[#8b5cf6] group-hover:gap-2 transition-all duration-200">
                            <i data-lucide="download" class="w-3.5 h-3.5"></i> Unduh PDF
                        </div>
                    </div>

                    <div class="group rounded-2xl border border-[#e5e7eb] p-5 hover:border-[#ff1443] hover:bg-[#fee2e2]/20 transition-all duration-200 cursor-pointer flex flex-col">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-[#ff1443]/10 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i data-lucide="file-down" class="w-6 h-6 text-[#ff1443]"></i>
                            </div>
                            <span class="text-[10px] font-bold bg-[#30b22d]/10 text-[#166534] px-2 py-0.5 rounded-full">Tersedia</span>
                        </div>
                        <p class="font-bold text-[#080c1a]">Formulir Data Pribadi</p>
                        <p class="text-xs text-[#6a7686] mt-1 leading-5">Formulir PDF lengkap untuk proses daftar ulang.</p>
                        <div class="mt-auto pt-4 flex items-center gap-1 text-xs font-bold text-[#ff1443] group-hover:gap-2 transition-all duration-200">
                            <i data-lucide="download" class="w-3.5 h-3.5"></i> Unduh PDF
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>

    <!-- ═══════════════════════════════════════════
       QUICK ACCESS
  ═══════════════════════════════════════════ -->
    <section class="mt-8">
        <div class="mb-5">
            <h2 class="text-xl font-bold">Akses Cepat</h2>
            <p class="text-sm text-[#6a7686] mt-0.5">Fitur dan layanan yang tersedia selama proses daftar ulang.</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4">

            <a href="#" class="group relative overflow-hidden rounded-2xl bg-white border border-[#e5e7eb] card-hover cursor-pointer flex flex-col">
                <div class="h-1.5 bg-gradient-to-r from-[#ff1443] to-[#f43f5e]"></div>
                <div class="p-5 flex flex-col flex-1">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#ff1443]/10 flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shrink-0">
                            <i data-lucide="file" class="w-6 h-6 text-[#ff1443]"></i>
                        </div>
                        <span class="text-[10px] font-bold bg-[#fee2e2] text-[#ff1443] px-2 py-0.5 rounded-full">80%</span>
                    </div>
                    <p class="font-bold text-[#080c1a] leading-tight">Biodata</p>
                    <p class="text-xs text-[#6a7686] mt-1 leading-5 flex-1">Lengkapi data pribadi murid baru.</p>
                    <div class="mt-4 flex items-center gap-1 text-xs font-bold text-[#ff1443] group-hover:gap-2 transition-all duration-200">
                        Buka <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </a>

            <a href="#" class="group relative overflow-hidden rounded-2xl bg-white border border-[#e5e7eb] card-hover cursor-pointer flex flex-col">
                <div class="h-1.5 bg-gradient-to-r from-[#30b22d] to-[#4ade80]"></div>
                <div class="p-5 flex flex-col flex-1">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#30b22d]/10 flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shrink-0">
                            <i data-lucide="check-circle-2" class="w-6 h-6 text-[#30b22d]"></i>
                        </div>
                        <span class="text-[10px] font-bold bg-[#dcfce7] text-[#166534] px-2 py-0.5 rounded-full">Selesai</span>
                    </div>
                    <p class="font-bold text-[#080c1a] leading-tight">Konfirmasi</p>
                    <p class="text-xs text-[#6a7686] mt-1 leading-5 flex-1">Konfirmasi kesediaan daftar ulang.</p>
                    <div class="mt-4 flex items-center gap-1 text-xs font-bold text-[#30b22d] group-hover:gap-2 transition-all duration-200">
                        Buka <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </a>

            <a href="#" class="group relative overflow-hidden rounded-2xl bg-white border border-[#e5e7eb] card-hover cursor-pointer flex flex-col">
                <div class="h-1.5 bg-gradient-to-r from-[#f59e0b] to-[#fbbf24]"></div>
                <div class="p-5 flex flex-col flex-1">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#f59e0b]/10 flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shrink-0">
                            <i data-lucide="printer" class="w-6 h-6 text-[#f59e0b]"></i>
                        </div>
                        <span class="text-[10px] font-bold bg-[#30b22d]/10 text-[#166534] px-2 py-0.5 rounded-full">Siap Cetak</span>
                    </div>
                    <p class="font-bold text-[#080c1a] leading-tight">Cetak Bukti</p>
                    <p class="text-xs text-[#6a7686] mt-1 leading-5 flex-1">Cetak bukti lolos dan daftar ulang.</p>
                    <div class="mt-4 flex items-center gap-1 text-xs font-bold text-[#f59e0b] group-hover:gap-2 transition-all duration-200">
                        Buka <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </a>

            <a href="#" class="group relative overflow-hidden rounded-2xl bg-white border border-[#e5e7eb] card-hover cursor-pointer flex flex-col">
                <div class="h-1.5 bg-gradient-to-r from-[#0ea5e9] to-[#38bdf8]"></div>
                <div class="p-5 flex flex-col flex-1">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#0ea5e9]/10 flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shrink-0">
                            <i data-lucide="calendar-days" class="w-6 h-6 text-[#0ea5e9]"></i>
                        </div>
                        <span class="text-[10px] font-bold bg-[#0ea5e9]/10 text-[#0ea5e9] px-2 py-0.5 rounded-full">21 Jul</span>
                    </div>
                    <p class="font-bold text-[#080c1a] leading-tight">Jadwal</p>
                    <p class="text-xs text-[#6a7686] mt-1 leading-5 flex-1">Detail jadwal kegiatan registrasi ulang.</p>
                    <div class="mt-4 flex items-center gap-1 text-xs font-bold text-[#0ea5e9] group-hover:gap-2 transition-all duration-200">
                        Buka <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </a>

        </div>
    </section>

    <!-- ═══════════════════════════════════════════
       PENGUMUMAN + FAQ DAFTAR ULANG
  ═══════════════════════════════════════════ -->
    <section class="mt-8 grid lg:grid-cols-2 gap-6">

        <!-- Pengumuman Daftar Ulang -->
        <div x-data="{ open: 0 }" class="bg-white rounded-2xl border border-[#e5e7eb]">
            <div class="p-6 border-b border-[#e5e7eb]">
                <h2 class="font-bold text-lg">Pengumuman</h2>
                <p class="text-sm text-[#6a7686] mt-0.5">Informasi penting seputar daftar ulang.</p>
            </div>
            <div class="divide-y divide-[#e5e7eb]">
                <template x-for="(item, index) in [
          { icon: 'calendar-check', color: 'text-[#30b22d]', bg: 'bg-[#30b22d]/10', title: 'MPLS dimulai 21 Juli 2026', date: '12 Juli 2026', body: 'Masa Pengenalan Lingkungan Sekolah (MPLS) akan dilaksanakan pada tanggal 21–23 Juli 2026 mulai pukul 07.00 WIB di Aula Utama SMK.' },
          { icon: 'users', color: 'text-[#3b82f6]', bg: 'bg-[#3b82f6]/10', title: 'Orang tua wajib hadir saat daftar ulang', date: '12 Juli 2026', body: 'Orang tua atau wali siswa diwajibkan hadir pada saat proses daftar ulang untuk penandatanganan berkas dan verifikasi dokumen asli.' },
          { icon: 'file-search', color: 'text-[#f59e0b]', bg: 'bg-[#f59e0b]/10', title: 'Bawa dokumen asli saat verifikasi', date: '13 Juli 2026', body: 'Seluruh peserta wajib membawa dokumen asli (KK, Akta Kelahiran, Ijazah/SKL, Rapor) pada saat verifikasi tatap muka di sekolah.' }
        ]">
                    <div>
                        <button @click="open == index ? open = -1 : open = index"
                            class="w-full p-5 flex items-center justify-between gap-3 hover:bg-[#eff2f7] transition-colors cursor-pointer">
                            <div class="flex items-center gap-3 text-left">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" :class="item.bg">
                                    <i :data-lucide="item.icon" class="w-3.5 h-3.5" :class="item.color"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-sm" x-text="item.title"></p>
                                    <p class="text-xs text-[#6a7686] mt-0.5" x-text="item.date"></p>
                                </div>
                            </div>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-[#6a7686] shrink-0 transition-transform duration-300"
                                :class="{ 'rotate-180': open === index }"></i>
                        </button>
                        <div x-show="open === index" x-transition
                            class="px-5 pb-5 text-sm text-[#6a7686] leading-6 ml-11" style="display:none">
                            <span x-text="item.body"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- FAQ Daftar Ulang -->
        <div x-data="{ open: null }" class="bg-white rounded-2xl border border-[#e5e7eb]">
            <div class="p-6 border-b border-[#e5e7eb]">
                <h2 class="font-bold text-lg">Pertanyaan Umum</h2>
                <p class="text-sm text-[#6a7686] mt-0.5">FAQ seputar proses registrasi ulang.</p>
            </div>
            <div class="divide-y divide-[#e5e7eb]">
                <template x-for="(faq, index) in [
                { q: 'Apa saja dokumen yang harus dibawa saat daftar ulang?', a: 'Dokumen yang wajib dibawa meliputi: KK asli, Akta Kelahiran asli, Ijazah/SKL asli, Rapor asli, Pas Foto 3x4 (4 lembar), dan Surat Pernyataan yang sudah ditandatangani orang tua.' },
                { q: 'Bagaimana jika salah upload berkas daftar ulang?', a: 'Anda dapat mengunggah ulang berkas sebelum batas waktu daftar ulang. Pastikan format file PDF/JPG dengan ukuran maksimal 2 MB per berkas.' },
                { q: 'Apakah orang tua wajib hadir saat daftar ulang?', a: 'Ya, orang tua atau wali siswa wajib hadir untuk penandatanganan formulir pernyataan dan verifikasi dokumen asli di sekolah.' },
                { q: 'Apa yang terjadi jika melewati batas waktu daftar ulang?', a: 'Peserta yang tidak melakukan daftar ulang sampai batas waktu yang ditentukan dianggap mengundurkan diri dan posisinya dapat digantikan oleh peserta cadangan.' }
                ]">
                    <div>
                        <button @click="open === index ? open = null : open = index"
                            class="w-full p-5 flex items-center justify-between gap-3 hover:bg-[#eff2f7] transition-colors cursor-pointer">
                            <p class="font-semibold text-sm text-left" x-text="faq.q"></p>
                            <div class="w-6 h-6 rounded-full border border-[#e5e7eb] flex items-center justify-center shrink-0 transition-all duration-300"
                                :class="{ 'bg-[#ff1443] border-[#ff1443] rotate-45': open === index }">
                                <i data-lucide="plus" class="w-3 h-3"
                                    :class="{ 'text-white': open === index, 'text-[#6a7686]': open !== index }"></i>
                            </div>
                        </button>
                        <div x-show="open === index" x-transition
                            class="px-5 pb-5 text-sm text-[#6a7686] leading-6" style="display:none">
                            <span x-text="faq.a"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

    </section>

    <!-- Modal Konfirmasi -->

    <div
        x-data="{ open: false }"
        @open-modal-konfirmasi.window="open = true"
        x-show="open"
        x-cloak
        class="fixed inset-0 z-[999] flex items-center justify-center p-4"
        style="display: none;">

        <div
            class="absolute inset-0 bg-[#080c1a]/60"
            @click="open = false"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
        </div>

        <div
            class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95">

            <div class="p-6 text-center">
                <div class="w-16 h-16 rounded-full bg-[#30b22d]/10 flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="check-circle-2" class="w-8 h-8 text-[#30b22d]"></i>
                </div>
                <h3 class="font-bold text-xl text-[#080c1a] mb-2">Konfirmasi Daftar Ulang</h3>
                <p class="text-sm text-[#6a7686]">
                    Dengan mengklik tombol konfirmasi, Anda menyatakan bersedia mengikuti proses daftar ulang sesuai ketentuan yang berlaku.
                </p>
            </div>

            <form action="{{ route('daftar-ulang.konfirmasi') }}" method="POST" class="p-6 pt-0 flex gap-3 w-full">
                @csrf

                <button type="button" @click="open = false" class="flex-1 px-4 py-2.5 rounded-xl border border-[#e5e7eb] text-[#6a7686] font-semibold hover:bg-[#eff2f7] transition-colors cursor-pointer">
                    Batal
                </button>

                <button type="submit" class="flex-1 px-4 py-2.5 rounded-xl bg-[#30b22d] text-white font-semibold hover:bg-[#166534] transition-colors cursor-pointer">
                    Ya, Konfirmasi
                </button>
            </form>
        </div>
    </div>

</div><!-- /max-w-7xl -->

@endsection

@push('scripts')
<script>
    function countdownDaftarUlang(deadline) {
        return {
            days: 0,
            hours: 0,
            minutes: 0,
            seconds: 0,
            expired: false,
            init() {
                const target = deadline ? new Date(deadline).getTime() : null;
                if (!target || isNaN(target)) {
                    this.expired = true;
                    return;
                }
                const tick = () => {
                    const dist = target - Date.now();
                    if (dist <= 0) {
                        this.days = this.hours = this.minutes = this.seconds = 0;
                        this.expired = true;
                        return;
                    }
                    this.expired = false;
                    this.days = Math.floor(dist / 86400000);
                    this.hours = Math.floor((dist % 86400000) / 3600000);
                    this.minutes = Math.floor((dist % 3600000) / 60000);
                    this.seconds = Math.floor((dist % 60000) / 1000);
                };
                tick();
                setInterval(tick, 1000);
            }
        };
    }
</script>
@endpush
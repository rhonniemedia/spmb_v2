@extends('layouts.user')

@section('title', 'Beranda')

@section('content')

@php
$rd = $reRegistrationData; // shortcut
$isRegistered = $rd['isReRegistered'] ?? false;
@endphp

<div class="max-w-7xl pb-12">

    <!-- ═══════════════════════════════════════════
       HERO BANNER
    ═══════════════════════════════════════════ -->
    <section class="mt-1">
        <div class="relative overflow-hidden rounded-2xl bg-[#080c1a]">

            <!-- Decorative -->
            <div class="absolute -top-16 -right-16 w-72 h-72 rounded-full bg-[#ff1443]/20 blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-60 h-60 rounded-full bg-[#ff1443]/10 blur-2xl pointer-events-none"></div>
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
                        <span class="text-[#ff1443]">{{ $personalData->full_name ?? 'Peserta' }}!</span>
                    </h2>
                    <p class="mt-4 text-[#6a7686] leading-7 max-w-2xl">
                        Anda dinyatakan <span class="text-white font-semibold">lolos seleksi</span> SPMB SMK TA {{ $reRegistrationStep->academic_year ?? date('Y') . '/' . (date('Y') + 1) }}.
                        Segera selesaikan proses daftar ulang sebelum batas waktu yang telah ditentukan.
                    </p>
                    <div class="mt-7 flex flex-wrap gap-3">
                        @if (!$isRegistered)
                        <a href="{{ route('konfirmasi') }}"
                            class="flex items-center gap-2 rounded-xl bg-[#ff1443] hover:bg-[#c90e33] text-white px-6 py-3 font-bold transition-all duration-200 cursor-pointer">
                            <i data-lucide="clipboard-check" class="w-4 h-4"></i>
                            Mulai Daftar Ulang
                        </a>
                        @else
                        <span class="flex items-center gap-2 rounded-xl bg-[#30b22d] text-white px-6 py-3 font-bold">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                            Daftar Ulang Selesai
                        </span>
                        @endif
                        <a href="{{ route('cetak-bukti') }}"
                            class="flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 hover:bg-white/20 text-white px-6 py-3 font-medium transition-all duration-200 cursor-pointer">
                            <i data-lucide="download" class="w-4 h-4"></i>
                            Unduh Bukti Lolos
                        </a>
                    </div>
                </div>

                <div class="hidden lg:block flex-shrink-0">
                    <div class="bg-white/10 border border-white/20 rounded-[16px] px-5 py-9 backdrop-blur-md text-center shadow-xl w-[190px] flex flex-col justify-center items-center">
                        <div class="text-[12px] text-white/70 font-semibold uppercase tracking-widest mb-5">
                            Tahap Saat Ini
                        </div>
                        <div class="w-[72px] h-[72px] rounded-full border-[3px] border-white/10 border-t-white border-r-white border-b-white/30 mb-5 flex items-center justify-center relative animate-[spin_6s_linear_infinite] shrink-0">
                            <div class="absolute w-[54px] h-[54px] rounded-full bg-white/10 flex items-center justify-center animate-[spin_6s_linear_infinite] [animation-direction:reverse]">
                                <i data-lucide="{{ $isRegistered ? 'check-circle' : 'clock' }}" class="w-6 h-6 text-white/90"></i>
                            </div>
                        </div>
                        <div class="text-[15px] font-bold text-white leading-tight">
                            Registrasi Ulang
                        </div>
                        <div class="text-[11px] text-white/60 mt-2 font-medium">
                            {{ $isRegistered ? 'Selesai' : 'Sedang Berlangsung' }}
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

            <!-- Status badge — dinamis berdasarkan re_registered_at -->
            <div class="px-6 pt-5 pb-4">
                @if ($isRegistered)
                <div class="flex items-center gap-4 p-4 rounded-2xl bg-[#dcfce7] border border-[#30b22d]/30">
                    <div class="w-12 h-12 rounded-2xl bg-[#30b22d]/20 flex items-center justify-center shrink-0">
                        <i data-lucide="check-circle-2" class="w-6 h-6 text-[#30b22d]"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-[#166534]">Sudah Registrasi Ulang</p>
                        <p class="text-sm text-[#166534]/70 mt-0.5">Selesai pada {{ $rd['reRegisteredAt'] }}. Tunggu verifikasi dari panitia.</p>
                    </div>
                    <span class="text-xs font-bold bg-[#30b22d] text-white px-3 py-1.5 rounded-full shrink-0">Selesai</span>
                </div>
                @else
                <div class="flex items-center gap-4 p-4 rounded-2xl bg-[#fef9c3] border border-[#f59e0b]/30">
                    <div class="w-12 h-12 rounded-2xl bg-[#f59e0b]/20 flex items-center justify-center shrink-0">
                        <i data-lucide="clock-4" class="w-6 h-6 text-[#f59e0b]"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-[#92400e]">Belum Registrasi Ulang</p>
                        <p class="text-sm text-[#92400e]/70 mt-0.5">Selesaikan semua checklist untuk melengkapi daftar ulang.</p>
                    </div>
                    <span class="text-xs font-bold bg-[#f59e0b] text-white px-3 py-1.5 rounded-full shrink-0">Segera</span>
                </div>
                @endif
            </div>

            <!-- Progress steps — dari SpmbStep yang difilter controller -->
            <div class="px-6 pb-2">
                <p class="text-sm font-semibold text-[#6a7686] mb-4">Progress Registrasi</p>
                @php
                $progressSteps = $rd['reRegProgressSteps'] ?? collect([]);
                $doneCount = $progressSteps->where('done', true)->count();
                $currentIndex = $doneCount < $progressSteps->count() ? $doneCount : $progressSteps->count() - 1;
                    $stepsJs = $progressSteps->map(fn($s) => [
                    'title' => $s['title'],
                    'desc' => $s['desc'],
                    'done' => $s['done'],
                    ])->values()->toJson();
                    @endphp

                    <div
                        x-data="{
                        current: {{ $currentIndex }},
                        steps: {{ $stepsJs }}
                    }"
                        class="relative">

                        <!-- Track desktop -->
                        <div class="hidden md:block relative mb-10">
                            <div class="absolute top-5 left-0 right-0 h-1 bg-[#eff2f7] rounded-full"></div>
                            <div class="absolute top-5 left-0 h-1 bg-[#ff1443] rounded-full progress-bar"
                                :style="'width:' + (current / (steps.length - 1)) * 100 + '%'"></div>
                            <div class="relative" :class="'grid grid-cols-' + steps.length">
                                <template x-for="(step, index) in steps" :key="index">
                                    <div class="flex flex-col items-center gap-2">
                                        <div class="w-10 h-10 rounded-full border-4 flex items-center justify-center font-bold text-sm transition-all duration-300"
                                            :class="{
                                            'bg-[#30b22d] border-[#30b22d] text-white': step.done && index < current,
                                            'bg-[#ff1443] border-[#ff1443] text-white step-active': index === current,
                                            'bg-white border-[#e5e7eb] text-[#6a7686]': index > current && !step.done
                                        }">
                                            <template x-if="step.done && index < current">
                                                <i data-lucide="check" class="w-4 h-4"></i>
                                            </template>
                                            <template x-if="!(step.done && index < current)">
                                                <span x-text="index + 1"></span>
                                            </template>
                                        </div>
                                        <p class="text-[11px] font-semibold text-center leading-tight"
                                            :class="{
                                            'text-[#30b22d]': step.done && index < current,
                                            'text-[#ff1443]': index === current,
                                            'text-[#6a7686]': index > current
                                        }"
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
                                        :class="{
                                        'text-[#30b22d]': step.done && index < current,
                                        'text-[#ff1443]': index === current,
                                        'text-[#6a7686]': index > current
                                    }"
                                        x-text="step.title"></p>
                                    <template x-if="index === current">
                                        <span class="ml-auto text-[10px] font-bold bg-[#ff1443]/10 text-[#ff1443] px-2 py-0.5 rounded-full">Sedang Berjalan</span>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
            </div>

            <!-- Status Verifikasi row — dinamis dari controller -->
            @php
            $fileStatus = $rd['fileStatus'] ?? 'Belum Lengkap';
            $verifStatus = $rd['verificationStatus'] ?? 'Menunggu';
            $regisStatus = $rd['registrationStatus'] ?? 'Menunggu';

            $fileIsGreen = $fileStatus === 'Lengkap';
            $verifIsGreen = $verifStatus === 'Terverifikasi';
            $regisIsGreen = $regisStatus === 'Diterima';
            @endphp
            <div class="mx-6 mb-6 mt-2 grid grid-cols-3 gap-3">
                <div class="rounded-2xl p-4 text-center border
                    {{ $fileIsGreen ? 'bg-[#dcfce7] border-[#30b22d]/20' : 'bg-[#fef9c3] border-[#f59e0b]/20' }}">
                    <p class="text-[11px] text-[#6a7686] font-medium mb-2">Berkas</p>
                    <div class="flex items-center justify-center gap-1.5">
                        <span class="w-2 h-2 rounded-full {{ $fileIsGreen ? 'bg-[#30b22d]' : 'bg-[#f59e0b]' }}"></span>
                        <p class="text-sm font-bold {{ $fileIsGreen ? 'text-[#166534]' : 'text-[#92400e]' }}">{{ $fileStatus }}</p>
                    </div>
                </div>
                <div class="rounded-2xl p-4 text-center border
                    {{ $verifIsGreen ? 'bg-[#dcfce7] border-[#30b22d]/20' : 'bg-[#fef9c3] border-[#f59e0b]/20' }}">
                    <p class="text-[11px] text-[#6a7686] font-medium mb-2">Verifikasi</p>
                    <div class="flex items-center justify-center gap-1.5">
                        <span class="w-2 h-2 rounded-full {{ $verifIsGreen ? 'bg-[#30b22d]' : 'bg-[#f59e0b]' }}"></span>
                        <p class="text-sm font-bold {{ $verifIsGreen ? 'text-[#166534]' : 'text-[#92400e]' }}">{{ $verifStatus }}</p>
                    </div>
                </div>
                <div class="rounded-2xl p-4 text-center border
                    {{ $regisIsGreen ? 'bg-[#dcfce7] border-[#30b22d]/20' : 'bg-[#fef9c3] border-[#f59e0b]/20' }}">
                    <p class="text-[11px] text-[#6a7686] font-medium mb-2">Registrasi</p>
                    <div class="flex items-center justify-center gap-1.5">
                        <span class="w-2 h-2 rounded-full {{ $regisIsGreen ? 'bg-[#30b22d]' : 'bg-[#f59e0b]' }}"></span>
                        <p class="text-sm font-bold {{ $regisIsGreen ? 'text-[#166534]' : 'text-[#92400e]' }}">{{ $regisStatus }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Countdown — deadline dari SpmbStep->end_date -->
        @php
        $deadline = $rd['reRegDeadline'] ?? null; // Carbon instance
        @endphp
        <div
            x-data="{
                days: 0, hours: 0, minutes: 0, seconds: 0,
                expired: false,
                deadline: '{{ $deadline ? $deadline->toIso8601String() : '' }}',
                init() {
                    if (!this.deadline) { this.expired = true; return; }
                    this.tick();
                    setInterval(() => this.tick(), 1000);
                },
                tick() {
                    const diff = new Date(this.deadline) - new Date();
                    if (diff <= 0) { this.expired = true; return; }
                    this.days    = Math.floor(diff / 86400000);
                    this.hours   = Math.floor((diff % 86400000) / 3600000);
                    this.minutes = Math.floor((diff % 3600000)  / 60000);
                    this.seconds = Math.floor((diff % 60000)    / 1000);
                }
            }"
            x-init="init()"
            class="bg-white rounded-2xl border border-[#e5e7eb] flex flex-col">

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
                                <div class="text-2xl font-bold" x-text="String(days).padStart(2,'0')"></div>
                                <div class="text-[10px] opacity-70 mt-0.5">Hari</div>
                            </div>
                            <div class="bg-white/10 rounded-xl p-2">
                                <div class="text-2xl font-bold" x-text="String(hours).padStart(2,'0')"></div>
                                <div class="text-[10px] opacity-70 mt-0.5">Jam</div>
                            </div>
                            <div class="bg-white/10 rounded-xl p-2">
                                <div class="text-2xl font-bold" x-text="String(minutes).padStart(2,'0')"></div>
                                <div class="text-[10px] opacity-70 mt-0.5">Menit</div>
                            </div>
                            <div class="bg-white/10 rounded-xl p-2">
                                <div class="text-2xl font-bold" x-text="String(seconds).padStart(2,'0')"></div>
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

                <!-- Milestone list — dari SpmbStep -->
                <div class="space-y-4">
                    @foreach ($rd['reRegProgressSteps'] ?? [] as $ms)
                    @php
                    $color = $ms['done'] ? 'bg-[#30b22d]' : 'bg-[#e5e7eb]';
                    @endphp
                    <div class="flex items-start gap-3">
                        <div class="w-2.5 h-2.5 rounded-full {{ $color }} mt-1.5 shrink-0"></div>
                        <div>
                            <p class="font-semibold text-sm">{{ $ms['title'] }}</p>
                            <p class="text-xs text-[#6a7686] mt-0.5">{{ $ms['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- CTA -->
                @if (!$isRegistered)
                <a href="{{ route('konfirmasi') }}"
                    class="mt-auto w-full flex items-center justify-center gap-2 rounded-xl bg-[#ff1443] hover:bg-[#c90e33] text-white py-3 text-sm font-bold transition-all duration-200 cursor-pointer">
                    <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                    Lengkapi Daftar Ulang
                </a>
                @else
                <div class="mt-auto w-full flex items-center justify-center gap-2 rounded-xl bg-[#30b22d]/10 text-[#166534] py-3 text-sm font-bold">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    Daftar Ulang Selesai
                </div>
                @endif
            </div>
        </div>

    </section>

    <!-- ═══════════════════════════════════════════
       PROFIL SINGKAT + JADWAL REGISTRASI
    ═══════════════════════════════════════════ -->
    <section class="mt-6 grid lg:grid-cols-2 gap-6">

        <!-- Profil Singkat -->
        <div class="bg-white rounded-2xl border border-[#e5e7eb] flex flex-col">
            <div class="p-6 border-b border-[#e5e7eb]">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i data-lucide="user-circle" class="w-4 h-4 text-[#ff1443]"></i>
                        <h3 class="font-bold text-lg">Profil Singkat</h3>
                    </div>
                    <a href="{{ route('biodata') }}"
                        class="shrink-0 flex items-center gap-1.5 rounded-xl border border-[#e5e7eb] px-3 py-1 text-[10px] font-semibold text-[#6a7686] hover:bg-[#eff2f7] transition-colors cursor-pointer">
                        <i data-lucide="external-link" class="w-3 h-3"></i>
                        Lihat Profil
                    </a>
                </div>
                <p class="text-sm text-[#6a7686] mt-0.5">Data diri dan status penerimaan siswa.</p>
            </div>

            <!-- Avatar + Nama + Nomor Pendaftaran -->
            <div class="px-6 py-5 border-b border-[#eff2f7]">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                    <!-- Kiri: foto & nama -->
                    <div class="flex items-center gap-4">
                        <div class="relative shrink-0">
                            @if ($personalData && $personalData->photo)
                            <img src="{{ asset('storage/' . $personalData->photo) }}" alt="Foto"
                                class="w-16 h-16 rounded-2xl object-cover ring-2 ring-[#e5e7eb]" />
                            @else
                            <div class="w-16 h-16 rounded-2xl bg-[#eff2f7] flex items-center justify-center ring-2 ring-[#e5e7eb]">
                                <i data-lucide="user" class="w-7 h-7 text-[#6a7686]"></i>
                            </div>
                            @endif
                            <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-[#30b22d] border-2 border-white"></span>
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-base leading-tight">{{ $personalData->full_name ?? '-' }}</p>
                            <p class="text-xs text-[#6a7686] mt-0.5">Peserta Didik Baru</p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="inline-flex items-center gap-1.5 text-[10px] font-bold bg-[#30b22d]/10 text-[#166534] rounded-full px-2.5 py-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#30b22d]"></span>Diterima
                                </span>
                                @if ($rd['acceptedConcentration'])
                                <span class="inline-flex items-center gap-1.5 text-[10px] font-bold bg-[#3b82f6]/10 text-[#1d4ed8] rounded-full px-2.5 py-1">
                                    <i data-lucide="laptop" class="w-3 h-3"></i>
                                    {{ $rd['acceptedConcentration']->code ?? $rd['acceptedConcentration']->name }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Kanan: nomor pendaftaran -->
                    <div class="flex items-center gap-3 bg-[#ff1443]/[0.03] p-3 rounded-xl border border-[#ff1443]/10 shrink-0">
                        <div class="w-9 h-9 rounded-xl bg-[#ff1443]/10 flex items-center justify-center shrink-0">
                            <i data-lucide="hash" class="w-4 h-4 text-[#ff1443]"></i>
                        </div>
                        <div>
                            <p class="text-[11px] text-[#6a7686] font-medium">Nomor Pendaftaran</p>
                            <p class="text-base font-bold tracking-wide">{{ $registration->registration_number ?? '-' }}</p>
                        </div>
                        <button onclick="navigator.clipboard.writeText('{{ $registration->registration_number ?? '' }}')"
                            class="ml-2 text-[#6a7686] hover:text-[#ff1443] transition-colors cursor-pointer" title="Salin">
                            <i data-lucide="copy" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Data rows -->
            <div class="p-5 grid grid-cols-2 gap-3 flex-1">

                <div class="flex items-center gap-3 p-3 rounded-xl bg-[#eff2f7]/60">
                    <div class="w-8 h-8 rounded-xl bg-[#8b5cf6]/10 flex items-center justify-center shrink-0">
                        <i data-lucide="users" class="w-4 h-4 text-[#8b5cf6]"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] text-[#6a7686]">Rombel</p>
                        <p class="text-xs font-semibold text-[#6a7686] truncate">
                            {{ $registration->class_group ?? 'Belum ditentukan' }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 rounded-xl bg-[#eff2f7]/60">
                    <div class="w-8 h-8 rounded-xl bg-[#f59e0b]/10 flex items-center justify-center shrink-0">
                        <i data-lucide="route" class="w-4 h-4 text-[#f59e0b]"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] text-[#6a7686]">Jalur Masuk</p>
                        <p class="text-xs font-semibold truncate">
                            {{ $registration->admissionPath->name ?? '-' }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 rounded-xl bg-[#eff2f7]/60">
                    <div class="w-8 h-8 rounded-xl bg-[#0ea5e9]/10 flex items-center justify-center shrink-0">
                        <i data-lucide="school" class="w-4 h-4 text-[#0ea5e9]"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] text-[#6a7686]">Asal Sekolah</p>
                        <p class="text-xs font-semibold truncate">
                            {{ $registration->origin_school ?? '-' }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 rounded-xl bg-[#eff2f7]/60">
                    <div class="w-8 h-8 rounded-xl bg-[#30b22d]/10 flex items-center justify-center shrink-0">
                        <i data-lucide="shield-check" class="w-4 h-4 text-[#30b22d]"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] text-[#6a7686]">Status</p>
                        <p class="text-xs font-semibold text-[#166534] truncate">
                            {{ $isRegistered ? 'Terdaftar' : 'Terverifikasi' }}
                        </p>
                    </div>
                </div>

                <div class="col-span-2 flex items-center gap-3 p-3 rounded-xl bg-[#eff2f7]/60">
                    <div class="w-8 h-8 rounded-xl bg-[#f59e0b]/10 flex items-center justify-center shrink-0">
                        <i data-lucide="clock-3" class="w-4 h-4 text-[#f59e0b]"></i>
                    </div>
                    <div class="flex-1 flex items-center justify-between gap-2">
                        <p class="text-[10px] text-[#6a7686]">Terakhir Diperbarui</p>
                        <p class="text-xs font-semibold">
                            {{ $personalData?->updated_at?->translatedFormat('d F Y') ?? '-' }}
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Jadwal Registrasi — statis per desain, data tanggal dari SpmbStep -->
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
                                <p class="text-base font-bold text-white">
                                    {{ $reRegistrationStep
                                        ? \Carbon\Carbon::parse($reRegistrationStep->start_date)->translatedFormat('l') . '–' .
                                          \Carbon\Carbon::parse($reRegistrationStep->end_date)->translatedFormat('l') . ' / ' .
                                          \Carbon\Carbon::parse($reRegistrationStep->start_date)->translatedFormat('d') . '–' .
                                          \Carbon\Carbon::parse($reRegistrationStep->end_date)->translatedFormat('d F Y')
                                        : '-' }}
                                </p>
                            </div>
                        </div>

                        {{-- Jam, Lokasi, Pakaian — tetap statis sesuai keputusan --}}
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-xl bg-[#f59e0b]/20 flex items-center justify-center shrink-0">
                                <i data-lucide="clock" class="w-4 h-4 text-[#f59e0b]"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-[#6a7686] font-medium mb-0.5">Jam</p>
                                <p class="text-sm font-bold text-white">08.00 – 14.00 WIB</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-xl bg-[#30b22d]/20 flex items-center justify-center shrink-0">
                                <i data-lucide="map-pin" class="w-4 h-4 text-[#30b22d]"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-[#6a7686] font-medium mb-0.5">Lokasi</p>
                                <p class="text-sm font-bold text-white">Ruang Panitia / TU</p>
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

                <div class="rounded-xl bg-[#fffbeb] border border-[#f59e0b]/20 px-4 py-3.5 flex items-start gap-3">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-[#f59e0b] shrink-0 mt-0.5"></i>
                    <p class="text-xs text-[#92400e] leading-5">
                        Calon siswa beserta orang tua/wali <span class="font-bold">wajib hadir</span> untuk menyerahkan MAP BIOLA berisi dokumen fisik dan penandatanganan berkas.
                    </p>
                </div>
            </div>
        </div>

    </section>

    <!-- ═══════════════════════════════════════════
       PERSYARATAN DAFTAR ULANG
    ═══════════════════════════════════════════ -->
    <div class="mt-8">
        <div class="mb-6">
            <h2 class="font-bold text-xl text-[#080c1a]">Persyaratan Daftar Ulang</h2>
            <p class="text-sm text-[#6a7686] mt-1">Silakan lengkapi seluruh persyaratan berikut sebelum datang ke sekolah.</p>
        </div>

        <section class="grid lg:grid-cols-2 gap-6 items-stretch">

            <!-- Berkas yang Diperlukan — dari $requirements -->
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
                    <span class="shrink-0 text-xs font-bold bg-[#eff2f7] text-[#6a7686] px-3 py-1 rounded-full">
                        {{ $totalRequirements }} Berkas
                    </span>
                </div>

                <div class="divide-y divide-[#eff2f7] flex-1">
                    @forelse ($requirements as $req)
                    <div class="flex items-center gap-3 px-5 py-3.5 hover:bg-[#eff2f7]/40 transition-colors">
                        <div class="w-9 h-9 rounded-xl bg-[#eff2f7] flex items-center justify-center shrink-0">
                            <i data-lucide="{{ $req->icon ?? 'file' }}" class="w-4 h-4 text-[#6a7686]"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-[#080c1a] leading-tight">{{ $req->name }}</p>
                            <p class="text-xs text-[#6a7686] mt-0.5">{{ $req->description ?? '' }}</p>
                        </div>
                        <span class="shrink-0 min-w-[26px] h-[26px] px-2 rounded-full bg-[#eff2f7] flex items-center justify-center text-xs font-semibold text-[#6a7686]">
                            {{ $loop->iteration }}
                        </span>
                    </div>
                    @empty
                    <div class="px-5 py-8 text-center text-sm text-[#6a7686]">
                        Belum ada berkas yang dikonfigurasi.
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Ketentuan MAP BIOLA — statis per desain -->
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
                        @php
                        $mapColors = [
                        ['label' => 'Warna Kuning', 'group' => 'DPIB', 'bg' => 'bg-[#fef9c3]', 'border' => 'border-[#fde68a]', 'icon_bg' => 'bg-[#f59e0b]', 'dot' => 'bg-[#f59e0b]', 'text' => 'text-[#92400e]', 'sub' => 'text-[#92400e]/70'],
                        ['label' => 'Warna Merah', 'group' => 'TEI, TITL, TKJ', 'bg' => 'bg-[#fef2f2]', 'border' => 'border-[#fecaca]', 'icon_bg' => 'bg-[#ff1443]', 'dot' => 'bg-[#ff1443]', 'text' => 'text-[#991b1b]', 'sub' => 'text-[#991b1b]/70'],
                        ['label' => 'Warna Hijau', 'group' => 'TKR, TSM', 'bg' => 'bg-[#f0fdf4]', 'border' => 'border-[#bbf7d0]', 'icon_bg' => 'bg-[#30b22d]', 'dot' => 'bg-[#30b22d]', 'text' => 'text-[#166534]', 'sub' => 'text-[#166534]/70'],
                        ['label' => 'Warna Biru', 'group' => 'TM, TLAS', 'bg' => 'bg-[#eff6ff]', 'border' => 'border-[#bfdbfe]', 'icon_bg' => 'bg-[#3b82f6]', 'dot' => 'bg-[#3b82f6]', 'text' => 'text-[#1e40af]', 'sub' => 'text-[#1e40af]/70'],
                        ];
                        @endphp
                        @foreach ($mapColors as $mc)
                        <div class="flex-1 flex items-center gap-4 p-4 rounded-2xl border {{ $mc['border'] }} {{ $mc['bg'] }}">
                            <div class="w-11 h-11 rounded-xl {{ $mc['icon_bg'] }} flex items-center justify-center shrink-0 shadow-sm">
                                <i data-lucide="folder" class="w-5 h-5 text-white"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs {{ $mc['sub'] }} font-medium">{{ $mc['label'] }}</p>
                                <p class="font-bold text-sm {{ $mc['text'] }}">{{ $mc['group'] }}</p>
                            </div>
                            <span class="w-3.5 h-3.5 rounded-full {{ $mc['dot'] }} shrink-0"></span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </section>
    </div>

    <!-- ═══════════════════════════════════════════
       CHECKLIST DATA + DOKUMEN PENTING
    ═══════════════════════════════════════════ -->
    <section class="mt-6">
        <div class="mb-5">
            <h2 class="text-xl font-bold">Kelengkapan Berkas Daftar Ulang</h2>
            <p class="text-sm text-[#6a7686] mt-0.5">Pantau status upload dan kelengkapan dokumen Anda.</p>
        </div>

        <div class="grid xl:grid-cols-5 gap-6">

            <!-- Checklist Kelengkapan Data -->
            @php
            $checklistRows = [
            ['icon' => 'user', 'label' => 'Data Pribadi', 'done' => $isPersonalDataComplete, 'url' => route('biodata')],
            ['icon' => 'map-pin', 'label' => 'Alamat', 'done' => !empty($personalData?->address_encrypted), 'url' => route('biodata')],
            ['icon' => 'users', 'label' => 'Orang Tua', 'done' => $isParentDataComplete, 'url' => route('biodata')],
            ['icon' => 'graduation-cap', 'label' => 'Pendidikan', 'done' => !empty($registration?->origin_school), 'url' => route('biodata')],
            ['icon' => 'camera', 'label' => 'Pas Foto', 'done' => $isPhotoUploaded, 'url' => route('biodata')],
            ['icon' => 'file-signature', 'label' => 'Surat Pernyataan', 'done' => $isPersonalDataComplete && $isParentDataComplete, 'url' => route('cetak-bukti')],
            ['icon' => 'file-down', 'label' => 'Formulir Data Pribadi', 'done' => true, 'url' => route('cetak-bukti')],
            ];
            $doneItems = collect($checklistRows)->where('done', true)->count();
            $totalItems = count($checklistRows);
            $pctChecklist = round(($doneItems / $totalItems) * 100);
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
                        {{ $doneItems }} / {{ $totalItems }} Selesai
                    </span>
                </div>

                <div class="px-5 pt-4 pb-3">
                    <div class="flex justify-between text-xs text-[#6a7686] mb-1.5">
                        <span>Kelengkapan data</span>
                        <span class="font-bold text-[#080c1a]">{{ $pctChecklist }}%</span>
                    </div>
                    <div class="h-2 rounded-full bg-[#eff2f7]">
                        <div class="h-2 rounded-full bg-gradient-to-r from-[#f59e0b] to-[#30b22d] progress-bar"
                            style="width:{{ $pctChecklist }}%"></div>
                    </div>
                </div>

                <div class="divide-y divide-[#eff2f7] flex-1">
                    @foreach ($checklistRows as $row)
                    @php
                    $done = $row['done'];
                    $rowBg = $done ? 'bg-[#dcfce7]/40 hover:bg-[#dcfce7]/80' : 'bg-[#fee2e2]/30 hover:bg-[#fee2e2]/70';
                    $iconBg = $done ? 'bg-[#dcfce7]' : 'bg-[#eff2f7]';
                    $iconColor = $done ? 'text-[#30b22d]' : 'text-[#6a7686]';
                    $labelColor = $done ? 'text-[#080c1a]' : 'text-[#6a7686]';
                    $subText = $done ? 'Selesai' : 'Belum diisi';
                    $subColor = $done ? 'text-[#166534]' : 'text-[#ed6b60]';
                    @endphp
                    <a href="{{ $row['url'] }}"
                        class="flex items-center gap-3 px-5 py-3.5 {{ $rowBg }} transition-colors duration-200 cursor-pointer group">
                        <div class="w-8 h-8 rounded-xl {{ $iconBg }} flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform duration-200">
                            <i data-lucide="{{ $row['icon'] }}" class="w-4 h-4 {{ $iconColor }}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold leading-tight {{ $labelColor }}">{{ $row['label'] }}</p>
                            <p class="text-[11px] {{ $subColor }} font-medium mt-0.5">{{ $subText }}</p>
                        </div>
                        @if ($done)
                        <div class="w-6 h-6 rounded-full bg-[#30b22d] flex items-center justify-center shrink-0 group-hover:bg-[#166534] transition-colors duration-200">
                            <i data-lucide="check" class="w-3 h-3 text-white"></i>
                        </div>
                        @else
                        <div class="w-6 h-6 rounded-full border-2 border-dashed border-[#ed6b60] shrink-0"></div>
                        @endif
                    </a>
                    @endforeach
                </div>

                <div class="p-4 border-t border-[#e5e7eb]">
                    <a href="{{ route('biodata') }}"
                        class="w-full flex items-center justify-center gap-2 rounded-xl bg-[#ff1443] hover:bg-[#c90e33] text-white py-2.5 text-sm font-bold transition-all duration-200 cursor-pointer">
                        <i data-lucide="arrow-right-circle" class="w-4 h-4"></i>
                        Lanjutkan Pengisian
                    </a>
                </div>
            </div>

            <!-- Dokumen Penting — statis per desain, route dinamis -->
            <div class="xl:col-span-3 bg-white rounded-2xl border border-[#e5e7eb] flex flex-col">
                <div class="p-6 border-b border-[#e5e7eb] flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-base">Dokumen Penting</h3>
                        <p class="text-sm text-[#6a7686] mt-0.5">Unduh semua dokumen registrasi dari satu tempat.</p>
                    </div>
                    <span class="rounded-full bg-[#30b22d]/10 text-[#166534] text-[11px] font-bold px-3 py-1 shrink-0">4 Dokumen</span>
                </div>

                <div class="p-6 grid sm:grid-cols-2 gap-4 flex-1">

                    {{-- Bukti Lolos Seleksi --}}
                    <a href="{{ route('cetak-bukti') }}"
                        class="group rounded-2xl border border-[#e5e7eb] p-5 hover:border-[#30b22d] hover:bg-[#dcfce7]/30 transition-all duration-200 cursor-pointer flex flex-col">
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
                    </a>

                    {{-- Bukti Registrasi Ulang — terkunci jika belum daftar ulang --}}
                    @if ($isRegistered)
                    <a href="{{ route('cetak-bukti') }}"
                        class="group rounded-2xl border border-[#e5e7eb] p-5 hover:border-[#3b82f6] hover:bg-[#dbeafe]/30 transition-all duration-200 cursor-pointer flex flex-col">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-[#3b82f6]/10 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i data-lucide="clipboard-check" class="w-6 h-6 text-[#3b82f6]"></i>
                            </div>
                            <span class="text-[10px] font-bold bg-[#30b22d]/10 text-[#166534] px-2 py-0.5 rounded-full">Tersedia</span>
                        </div>
                        <p class="font-bold text-[#080c1a]">Bukti Registrasi Ulang</p>
                        <p class="text-xs text-[#6a7686] mt-1 leading-5">Bukti resmi telah menyelesaikan daftar ulang.</p>
                        <div class="mt-auto pt-4 flex items-center gap-1 text-xs font-bold text-[#3b82f6] group-hover:gap-2 transition-all duration-200">
                            <i data-lucide="download" class="w-3.5 h-3.5"></i> Unduh PDF
                        </div>
                    </a>
                    @else
                    <div class="group rounded-2xl border border-[#e5e7eb] p-5 flex flex-col opacity-60 cursor-not-allowed">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-[#3b82f6]/10 flex items-center justify-center">
                                <i data-lucide="clipboard-check" class="w-6 h-6 text-[#3b82f6]"></i>
                            </div>
                            <span class="text-[10px] font-bold bg-[#f59e0b]/10 text-[#92400e] px-2 py-0.5 rounded-full">Proses</span>
                        </div>
                        <p class="font-bold text-[#080c1a]">Bukti Registrasi Ulang</p>
                        <p class="text-xs text-[#6a7686] mt-1 leading-5">Tersedia setelah proses verifikasi selesai.</p>
                        <div class="mt-auto pt-4 flex items-center gap-1 text-xs font-bold text-[#6a7686]">
                            <i data-lucide="lock" class="w-3.5 h-3.5"></i> Menunggu Verifikasi
                        </div>
                    </div>
                    @endif

                    {{-- Surat Pernyataan --}}
                    <a href="#"
                        class="group rounded-2xl border border-[#e5e7eb] p-5 hover:border-[#8b5cf6] hover:bg-[#f5f3ff]/30 transition-all duration-200 cursor-pointer flex flex-col">
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
                    </a>

                    {{-- Formulir Daftar Ulang --}}
                    <a href="#"
                        class="group rounded-2xl border border-[#e5e7eb] p-5 hover:border-[#ff1443] hover:bg-[#fee2e2]/20 transition-all duration-200 cursor-pointer flex flex-col">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-[#ff1443]/10 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i data-lucide="file-down" class="w-6 h-6 text-[#ff1443]"></i>
                            </div>
                            <span class="text-[10px] font-bold bg-[#30b22d]/10 text-[#166534] px-2 py-0.5 rounded-full">Tersedia</span>
                        </div>
                        <p class="font-bold text-[#080c1a]">Formulir Daftar Ulang</p>
                        <p class="text-xs text-[#6a7686] mt-1 leading-5">Formulir PDF lengkap untuk proses daftar ulang.</p>
                        <div class="mt-auto pt-4 flex items-center gap-1 text-xs font-bold text-[#ff1443] group-hover:gap-2 transition-all duration-200">
                            <i data-lucide="download" class="w-3.5 h-3.5"></i> Unduh PDF
                        </div>
                    </a>

                </div>
            </div>

        </div>
    </section>

    <!-- ═══════════════════════════════════════════
       AKSES CEPAT
    ═══════════════════════════════════════════ -->
    <section class="mt-8">
        <div class="mb-5">
            <h2 class="text-xl font-bold">Akses Cepat</h2>
            <p class="text-sm text-[#6a7686] mt-0.5">Fitur dan layanan yang tersedia selama proses daftar ulang.</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ($rd['checklistItems'] ?? [] as $item)
            <a href="{{ $item['url'] }}"
                class="group relative overflow-hidden rounded-2xl bg-white border border-[#e5e7eb] card-hover cursor-pointer flex flex-col">
                <div class="h-1.5 bg-gradient-to-r {{ $item['gradient'] }}"></div>
                <div class="p-5 flex flex-col flex-1">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shrink-0"
                            style="background-color: {{ $item['color'] }}1a;">
                            <i data-lucide="{{ $item['icon'] }}" class="w-6 h-6" style="color: {{ $item['color'] }};"></i>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $item['badge_bg'] }}">
                            {{ $item['status'] }}
                        </span>
                    </div>
                    <p class="font-bold text-[#080c1a] leading-tight">{{ $item['label'] }}</p>
                    <p class="text-xs text-[#6a7686] mt-1 leading-5 flex-1">{{ $item['desc'] }}</p>
                    <div class="mt-4 flex items-center gap-1 text-xs font-bold group-hover:gap-2 transition-all duration-200"
                        style="color: {{ $item['color'] }};">
                        Buka <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </section>

    <!-- ═══════════════════════════════════════════
       PENGUMUMAN + FAQ
    ═══════════════════════════════════════════ -->
    <section class="mt-8 grid lg:grid-cols-2 gap-6">

        <!-- Pengumuman — dari model Announcement -->
        <div x-data="{ open: 0 }" class="bg-white rounded-2xl border border-[#e5e7eb]">
            <div class="p-6 border-b border-[#e5e7eb]">
                <h2 class="font-bold text-lg">Pengumuman</h2>
                <p class="text-sm text-[#6a7686] mt-0.5">Informasi penting seputar daftar ulang.</p>
            </div>
            <div class="divide-y divide-[#e5e7eb]">
                @forelse ($rd['announcements'] ?? [] as $i => $ann)
                <div>
                    <button @click="open == {{ $i }} ? open = -1 : open = {{ $i }}"
                        class="w-full p-5 flex items-center justify-between gap-3 hover:bg-[#eff2f7] transition-colors cursor-pointer">
                        <div class="flex items-center gap-3 text-left">
                            <div class="w-8 h-8 rounded-lg {{ $ann->icon_bg ?? 'bg-[#eff2f7]' }} flex items-center justify-center shrink-0">
                                <i data-lucide="{{ $ann->icon ?? 'bell' }}" class="w-3.5 h-3.5 {{ $ann->icon_color ?? 'text-[#6a7686]' }}"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-sm">{{ $ann->title }}</p>
                                <p class="text-xs text-[#6a7686] mt-0.5">
                                    {{ $ann->published_at ? \Carbon\Carbon::parse($ann->published_at)->translatedFormat('d F Y') : '' }}
                                </p>
                            </div>
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-[#6a7686] shrink-0 transition-transform duration-300"
                            :class="{ 'rotate-180': open === {{ $i }} }"></i>
                    </button>
                    <div x-show="open === {{ $i }}" x-transition
                        class="px-5 pb-5 text-sm text-[#6a7686] leading-6 ml-11" style="display:none">
                        {{ $ann->body }}
                    </div>
                </div>
                @empty
                <div class="p-6 text-sm text-center text-[#6a7686]">Belum ada pengumuman.</div>
                @endforelse
            </div>
        </div>

        <!-- FAQ — dari model Faq -->
        <div x-data="{ open: null }" class="bg-white rounded-2xl border border-[#e5e7eb]">
            <div class="p-6 border-b border-[#e5e7eb]">
                <h2 class="font-bold text-lg">Pertanyaan Umum</h2>
                <p class="text-sm text-[#6a7686] mt-0.5">FAQ seputar proses registrasi ulang.</p>
            </div>
            <div class="divide-y divide-[#e5e7eb]">
                @forelse ($rd['faqs'] ?? [] as $i => $faq)
                <div>
                    <button @click="open === {{ $i }} ? open = null : open = {{ $i }}"
                        class="w-full p-5 flex items-center justify-between gap-3 hover:bg-[#eff2f7] transition-colors cursor-pointer">
                        <p class="font-semibold text-sm text-left">{{ $faq->question }}</p>
                        <div class="w-6 h-6 rounded-full border border-[#e5e7eb] flex items-center justify-center shrink-0 transition-all duration-300"
                            :class="{ 'bg-[#ff1443] border-[#ff1443] rotate-45': open === {{ $i }} }">
                            <i data-lucide="plus" class="w-3 h-3"
                                :class="{ 'text-white': open === {{ $i }}, 'text-[#6a7686]': open !== {{ $i }} }"></i>
                        </div>
                    </button>
                    <div x-show="open === {{ $i }}" x-transition
                        class="px-5 pb-5 text-sm text-[#6a7686] leading-6" style="display:none">
                        {{ $faq->answer }}
                    </div>
                </div>
                @empty
                <div class="p-6 text-sm text-center text-[#6a7686]">Belum ada FAQ.</div>
                @endforelse
            </div>
        </div>

    </section>

</div><!-- /max-w-7xl -->

@endsection
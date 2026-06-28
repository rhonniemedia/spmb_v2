@extends('layouts.user')

@section('title', 'Beranda')

@section('content')
{{-- BREADCRUMB --}}
<div class="max-w-7xl pb-12">

    <!-- ═══════════════════════════════════════════
       HERO BANNER
  ═══════════════════════════════════════════ -->
    <section class="mt-6">
        <div class="relative overflow-hidden rounded-2xl bg-[#080c1a]">

            <div class="absolute -top-16 -right-16 w-72 h-72 rounded-full bg-[#ff1443]/20 blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-60 h-60 rounded-full bg-[#ff1443]/10 blur-2xl pointer-events-none"></div>
            <div class="absolute top-8 right-1/3 w-3 h-3 rounded-full bg-[#30b22d]/40 pointer-events-none"></div>
            <div class="absolute top-14 right-1/4 w-2 h-2 rounded-full bg-[#f59e0b]/40 pointer-events-none"></div>
            <div class="absolute top-6 right-1/2 w-2 h-2 rounded-full bg-[#3b82f6]/40 pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-8 p-8 md:p-10">

                <div class="w-full lg:flex-1">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/20 px-4 py-1.5 text-xs text-white font-medium mb-5">
                        <i data-lucide="check-circle" class="w-3.5 h-3.5 text-[#30b22d]"></i>
                        Berkas Anda Telah Diverifikasi
                    </span>
                    <h2 class="text-4xl md:text-5xl font-bold text-white leading-tight">
                        Selamat datang,<br />
                        <span class="text-[#ff1443]">Roni Saputra!</span>
                    </h2>
                    <p class="mt-4 text-[#6a7686] leading-7 max-w-2xl">
                        Seluruh dokumen Anda telah berhasil diverifikasi oleh panitia.
                        Tahapan selanjutnya adalah menunggu pengumuman hasil seleksi sesuai jadwal.
                    </p>
                    <div class="mt-7 flex flex-wrap gap-3">
                        <button class="flex items-center gap-2 rounded-xl bg-[#ff1443] hover:bg-[#c90e33] text-white px-6 py-3 font-bold transition-all duration-200 cursor-pointer">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                            Lihat Status
                        </button>
                        <button class="flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 hover:bg-white/20 text-white px-6 py-3 font-medium transition-all duration-200 cursor-pointer">
                            <i data-lucide="printer" class="w-4 h-4"></i>
                            Cetak Bukti
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
                            Verifikasi
                        </div>

                        <div class="text-[11px] text-white/60 mt-2 font-medium">
                            Sedang Berjalan
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════
       PROGRESS TAHAPAN
  ═══════════════════════════════════════════ -->
    <section
        x-data="{
      current: 3,
      steps: [
        { title:'Akun',         date:'12 Jun 2026', status:'Selesai',        desc:'Akun berhasil dibuat.' },
        { title:'Biodata',      date:'12 Jun 2026', status:'Selesai',        desc:'Biodata telah lengkap.' },
        { title:'Upload Berkas',date:'13 Jun 2026', status:'Selesai',        desc:'Semua berkas berhasil diunggah.' },
        { title:'Verifikasi',   date:'20 Jun 2026', status:'Sedang Berjalan',desc:'Panitia sedang memeriksa dokumen Anda.' },
        { title:'Seleksi',      date:'10 Jul 2026', status:'Belum Dimulai',  desc:'Menunggu jadwal seleksi.' },
        { title:'Pengumuman',   date:'15 Jul 2026', status:'Belum Dimulai',  desc:'Hasil seleksi akan diumumkan.' },
        { title:'Daftar Ulang', date:'18 Jul 2026', status:'Belum Dimulai',  desc:'Silakan daftar ulang jika dinyatakan lulus.' }
      ]
    }"
        class="mt-6">
        <div class="bg-white rounded-2xl border border-[#e5e7eb] p-6 md:p-8">
            <div class="flex items-center justify-between mb-8 flex-wrap gap-3">
                <div>
                    <h2 class="text-xl font-bold">Progress Pendaftaran</h2>
                    <p class="text-sm text-[#6a7686] mt-0.5">Ikuti perkembangan proses penerimaan Anda.</p>
                </div>
                <span class="rounded-full bg-[#ff1443]/10 text-[#ff1443] px-4 py-1.5 text-sm font-semibold">
                    Tahap <span x-text="current + 1"></span> dari <span x-text="steps.length"></span>
                </span>
            </div>

            <!-- Desktop stepper -->
            <div class="relative hidden md:block">
                <!-- Track -->
                <div class="absolute top-6 left-0 right-0 h-1 bg-[#eff2f7] rounded-full"></div>
                <div class="absolute top-6 left-0 h-1 bg-[#ff1443] rounded-full progress-bar"
                    :style="'width:' + (current / (steps.length - 1)) * 100 + '%'"></div>

                <div class="relative grid grid-cols-7">
                    <template x-for="(step, index) in steps" :key="index">
                        <div class="relative flex flex-col items-center" x-data="{ open: false }">
                            <div @mouseenter="open=true" @mouseleave="open=false" class="relative">

                                <!-- Circle -->
                                <button
                                    class="w-12 h-12 rounded-full border-4 flex items-center justify-center font-bold transition-all duration-300"
                                    :class="{
                    'bg-[#30b22d] border-[#30b22d] text-white': index < current,
                    'bg-[#ff1443] border-[#ff1443] text-white step-active': index === current,
                    'bg-white border-[#e5e7eb] text-[#6a7686]': index > current
                  }">
                                    <template x-if="index < current">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </template>
                                    <template x-if="index === current">
                                        <div class="w-3 h-3 rounded-full bg-white"></div>
                                    </template>
                                    <template x-if="index > current">
                                        <span class="text-xs" x-text="index + 1"></span>
                                    </template>
                                </button>

                                <!-- Tooltip -->
                                <div x-show="open" x-transition
                                    class="absolute left-1/2 -translate-x-1/2 top-14 w-56 bg-[#080c1a] text-white rounded-xl p-4 shadow-2xl z-30"
                                    style="display:none">
                                    <p class="font-bold text-sm" x-text="step.title"></p>
                                    <p class="text-[10px] text-[#6a7686] mt-1" x-text="step.date"></p>
                                    <p class="text-xs text-white/80 mt-2 leading-5" x-text="step.desc"></p>
                                    <span class="mt-2 inline-flex rounded-full text-[10px] px-2 py-0.5"
                                        :class="{
                      'bg-[#30b22d]/20 text-[#30b22d]': step.status === 'Selesai',
                      'bg-[#ff1443]/20 text-[#ff1443]': step.status === 'Sedang Berjalan',
                      'bg-white/10 text-white/60': step.status === 'Belum Dimulai'
                    }"
                                        x-text="step.status">
                                    </span>
                                </div>
                            </div>

                            <!-- Label -->
                            <div class="mt-4 text-center px-1">
                                <p class="font-semibold text-xs leading-tight"
                                    :class="{
                    'text-[#30b22d]': index < current,
                    'text-[#ff1443]': index === current,
                    'text-[#6a7686]': index > current
                  }"
                                    x-text="step.title">
                                </p>
                                <p class="text-[10px] text-[#6a7686] mt-0.5" x-text="step.date"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Mobile stepper (vertical) -->
            <div class="md:hidden relative pl-6">
                <div class="absolute left-2 top-0 bottom-0 w-0.5 bg-[#eff2f7]"></div>
                <template x-for="(step, index) in steps" :key="'m'+index">
                    <div class="relative pb-6">
                        <div class="absolute -left-6 w-5 h-5 rounded-full border-2 flex items-center justify-center"
                            :class="{
                'bg-[#30b22d] border-[#30b22d]': index < current,
                'bg-[#ff1443] border-[#ff1443]': index === current,
                'bg-white border-[#e5e7eb]': index > current
              }">
                            <template x-if="index < current">
                                <svg class="w-2.5 h-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </template>
                            <template x-if="index === current">
                                <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                            </template>
                        </div>
                        <div class="ml-2">
                            <p class="font-semibold text-sm"
                                :class="{
                  'text-[#30b22d]': index < current,
                  'text-[#ff1443]': index === current,
                  'text-[#6a7686]': index > current
                }"
                                x-text="step.title"></p>
                            <p class="text-xs text-[#6a7686]" x-text="step.date"></p>
                        </div>
                    </div>
                </template>
            </div>

        </div>
    </section>

    <!-- ═══════════════════════════════════════════
       INFORMATION: Ringkasan + Jadwal
  ═══════════════════════════════════════════ -->
    <section class="mt-6 grid lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 bg-white rounded-2xl border border-[#e5e7eb] flex flex-col">

            <div class="p-6 border-b border-[#e5e7eb]">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i data-lucide="user-circle" class="w-4 h-4 text-[#ff1443]"></i>
                        <h3 class="font-bold text-lg">Profil Singkat</h3>
                    </div>
                    <button class="shrink-0 flex items-center gap-1.5 rounded-xl border border-[#e5e7eb] px-3 py-1 text-[10px] font-semibold text-[#6a7686] hover:bg-[#eff2f7] transition-colors cursor-pointer">
                        <i data-lucide="external-link" class="w-3 h-3"></i>
                        Lihat Profil
                    </button>
                </div>
                <p class="text-sm text-[#6a7686] mt-0.5">Data diri dan status penerimaan siswa.</p>
            </div>

            <div class="px-6 py-5 border-b border-[#eff2f7]">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                    <div class="flex items-center gap-4">
                        <div class="relative shrink-0">
                            <img src="https://i.pravatar.cc/100?img=12" alt="Foto"
                                class="w-16 h-16 rounded-2xl object-cover ring-2 ring-[#e5e7eb]" />
                            <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-[#30b22d] border-2 border-white"></span>
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-base leading-tight">Roni Saputra</p>
                            <p class="text-xs text-[#6a7686] mt-0.5">Calon Peserta Didik</p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="inline-flex items-center gap-1.5 text-[10px] font-bold bg-[#30b22d]/10 text-[#166534] rounded-full px-2.5 py-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#30b22d]"></span>Terverifikasi
                                </span>
                                <span class="inline-flex items-center gap-1.5 text-[10px] font-bold bg-[#3b82f6]/10 text-[#1d4ed8] rounded-full px-2.5 py-1">
                                    <i data-lucide="laptop" class="w-3 h-3"></i>RPL
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 bg-[#ff1443]/[0.03] p-3 rounded-xl border border-[#ff1443]/10 shrink-0">
                        <div class="w-9 h-9 rounded-xl bg-[#ff1443]/10 flex items-center justify-center shrink-0">
                            <i data-lucide="hash" class="w-4 h-4 text-[#ff1443]"></i>
                        </div>
                        <div>
                            <p class="text-[11px] text-[#6a7686] font-medium">Nomor Pendaftaran</p>
                            <p class="text-base font-bold tracking-wide">2026000123</p>
                        </div>
                        <button class="ml-2 flex flex-col items-center justify-center gap-1 text-[10px] text-[#6a7686] hover:text-[#ff1443] transition-colors cursor-pointer" title="Salin">
                            <i data-lucide="copy" class="w-4 h-4"></i>
                        </button>
                    </div>

                </div>
            </div>

            <div class="p-5 grid grid-cols-2 gap-3 flex-1">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-[#eff2f7]/60">
                    <div class="w-8 h-8 rounded-xl bg-[#8b5cf6]/10 flex items-center justify-center shrink-0">
                        <i data-lucide="route" class="w-4 h-4 text-[#8b5cf6]"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] text-[#6a7686]">Jalur Masuk</p>
                        <p class="text-xs font-semibold text-[#6a7686] truncate">Reguler</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 rounded-xl bg-[#eff2f7]/60">
                    <div class="w-8 h-8 rounded-xl bg-[#0ea5e9]/10 flex items-center justify-center shrink-0">
                        <i data-lucide="school" class="w-4 h-4 text-[#0ea5e9]"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] text-[#6a7686]">Asal Sekolah</p>
                        <p class="text-xs font-semibold truncate">SMP N 1 Curup</p>
                    </div>
                </div>

                <div class="col-span-2 flex items-center gap-3 p-3 rounded-xl bg-[#eff2f7]/60">
                    <div class="w-8 h-8 rounded-xl bg-[#f59e0b]/10 flex items-center justify-center shrink-0">
                        <i data-lucide="clock-3" class="w-4 h-4 text-[#f59e0b]"></i>
                    </div>
                    <div class="flex-1 flex items-center justify-between gap-2">
                        <p class="text-[10px] text-[#6a7686]">Terakhir Diperbarui</p>
                        <p class="text-xs font-semibold">20 Juni 2026</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Jadwal Penting + Countdown -->
        <div x-data="countdown()" x-init="init()" class="bg-white rounded-2xl border border-[#e5e7eb] flex flex-col">
            <div class="p-6 border-b border-[#e5e7eb]">
                <h2 class="text-lg font-bold">Jadwal Penting</h2>
                <p class="text-sm text-[#6a7686] mt-0.5">Tahapan yang harus diperhatikan.</p>
            </div>
            <div class="p-6 flex flex-col gap-5 flex-1">
                <!-- Countdown -->
                <div class="countdown-box rounded-2xl p-5 text-white">
                    <p class="text-xs font-medium opacity-80 mb-4 flex items-center gap-1.5">
                        <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                        Menuju Pengumuman Hasil Seleksi
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

                <!-- Jadwal list -->
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-2.5 h-2.5 rounded-full bg-[#ff1443] mt-1.5 shrink-0"></div>
                        <div>
                            <p class="font-semibold text-sm">Pengumuman Hasil Seleksi</p>
                            <p class="text-xs text-[#6a7686] mt-0.5">10 Juli 2026 · 08.00 WIB</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-2.5 h-2.5 rounded-full bg-[#f59e0b] mt-1.5 shrink-0"></div>
                        <div>
                            <p class="font-semibold text-sm">Daftar Ulang</p>
                            <p class="text-xs text-[#6a7686] mt-0.5">12–15 Juli 2026</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-2.5 h-2.5 rounded-full bg-[#30b22d] mt-1.5 shrink-0"></div>
                        <div>
                            <p class="font-semibold text-sm">MPLS (Masa Pengenalan)</p>
                            <p class="text-xs text-[#6a7686] mt-0.5">18 Juli 2026</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <!-- ═══════════════════════════════════════════
       ACTIVITY CENTER: Checklist + Riwayat
  ═══════════════════════════════════════════ -->
    <section class="mt-6">
        <div class="mb-5">
            <h2 class="text-xl font-bold">Aktivitas Saya</h2>
            <p class="text-sm text-[#6a7686] mt-0.5">Pantau perkembangan berkas dan aktivitas pendaftaran Anda.</p>
        </div>

        <div class="grid xl:grid-cols-5 gap-6">

            <!-- Checklist Berkas -->
            <div class="xl:col-span-2 bg-white rounded-2xl border border-[#e5e7eb] flex flex-col">

                <!-- Header -->
                <div class="p-6 border-b border-[#e5e7eb] flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="folder-check" class="w-5 h-5 text-[#ff1443]"></i>
                            <h3 class="font-bold text-base">Checklist Berkas</h3>
                        </div>
                        <p class="text-sm text-[#6a7686] mt-0.5">Pantau status unggahan berkas Anda.</p>
                    </div>
                    <span class="text-[11px] font-bold bg-[#ff1443]/10 text-[#ff1443] px-3 py-1 rounded-full shrink-0">4 / 6 Selesai</span>
                </div>

                <!-- Progress bar ringkasan -->
                <div class="px-5 pt-4 pb-3">
                    <div class="flex justify-between text-xs text-[#6a7686] mb-1.5">
                        <span>Kelengkapan berkas</span>
                        <span class="font-bold text-[#080c1a]">67%</span>
                    </div>
                    <div class="h-2 rounded-full bg-[#eff2f7]">
                        <div class="h-2 rounded-full bg-gradient-to-r from-[#ff1443] to-[#f59e0b] progress-bar" style="width:67%"></div>
                    </div>
                </div>

                <!-- Item list: tidak ada gap, memakai divider -->
                <div class="divide-y divide-[#eff2f7] flex-1">

                    <!-- Kartu Keluarga — selesai -->
                    <div class="flex items-center gap-3 px-5 py-3.5">
                        <div class="w-8 h-8 rounded-xl bg-[#dcfce7] flex items-center justify-center shrink-0">
                            <i data-lucide="users" class="w-4 h-4 text-[#30b22d]"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold leading-tight">Kartu Keluarga</p>
                            <p class="text-[11px] text-[#6a7686] mt-0.5">Diunggah · 13 Jun 2026</p>
                        </div>
                        <div class="w-6 h-6 rounded-full bg-[#30b22d] flex items-center justify-center shrink-0">
                            <i data-lucide="check" class="w-3 h-3 text-white"></i>
                        </div>
                    </div>

                    <!-- Akta Kelahiran — selesai -->
                    <div class="flex items-center gap-3 px-5 py-3.5">
                        <div class="w-8 h-8 rounded-xl bg-[#dcfce7] flex items-center justify-center shrink-0">
                            <i data-lucide="file-text" class="w-4 h-4 text-[#30b22d]"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold leading-tight">Akta Kelahiran</p>
                            <p class="text-[11px] text-[#6a7686] mt-0.5">Diunggah · 13 Jun 2026</p>
                        </div>
                        <div class="w-6 h-6 rounded-full bg-[#30b22d] flex items-center justify-center shrink-0">
                            <i data-lucide="check" class="w-3 h-3 text-white"></i>
                        </div>
                    </div>

                    <!-- Pas Foto — selesai -->
                    <div class="flex items-center gap-3 px-5 py-3.5">
                        <div class="w-8 h-8 rounded-xl bg-[#dcfce7] flex items-center justify-center shrink-0">
                            <i data-lucide="image" class="w-4 h-4 text-[#30b22d]"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold leading-tight">Pas Foto</p>
                            <p class="text-[11px] text-[#6a7686] mt-0.5">Diunggah · 13 Jun 2026</p>
                        </div>
                        <div class="w-6 h-6 rounded-full bg-[#30b22d] flex items-center justify-center shrink-0">
                            <i data-lucide="check" class="w-3 h-3 text-white"></i>
                        </div>
                    </div>

                    <!-- Rapor — selesai -->
                    <div class="flex items-center gap-3 px-5 py-3.5">
                        <div class="w-8 h-8 rounded-xl bg-[#dcfce7] flex items-center justify-center shrink-0">
                            <i data-lucide="book-open" class="w-4 h-4 text-[#30b22d]"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold leading-tight">Rapor</p>
                            <p class="text-[11px] text-[#6a7686] mt-0.5">Diunggah · 18 Jun 2026</p>
                        </div>
                        <div class="w-6 h-6 rounded-full bg-[#30b22d] flex items-center justify-center shrink-0">
                            <i data-lucide="check" class="w-3 h-3 text-white"></i>
                        </div>
                    </div>

                    <!-- Surat Keterangan Lulus — sebagian -->
                    <div class="flex items-center gap-3 px-5 py-3.5 bg-[#fef9c3]/50">
                        <div class="w-8 h-8 rounded-xl bg-[#fef9c3] flex items-center justify-center shrink-0">
                            <i data-lucide="award" class="w-4 h-4 text-[#f59e0b]"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <p class="text-sm font-semibold leading-tight">Surat Ket. Lulus</p>
                                <span class="text-[11px] font-bold text-[#f59e0b]">60%</span>
                            </div>
                            <div class="h-1.5 rounded-full bg-[#e5e7eb]">
                                <div class="h-1.5 rounded-full bg-[#f59e0b] progress-bar" style="width:60%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Surat Pernyataan — belum -->
                    <div class="flex items-center gap-3 px-5 py-3.5">
                        <div class="w-8 h-8 rounded-xl bg-[#eff2f7] flex items-center justify-center shrink-0">
                            <i data-lucide="file-signature" class="w-4 h-4 text-[#6a7686]"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold leading-tight text-[#6a7686]">Surat Pernyataan</p>
                            <p class="text-[11px] text-[#6a7686]/70 mt-0.5">Belum diunggah</p>
                        </div>
                        <div class="w-6 h-6 rounded-full border-2 border-dashed border-[#e5e7eb] shrink-0"></div>
                    </div>

                </div>

                <!-- Footer action -->
                <div class="p-4 border-t border-[#e5e7eb]">
                    <button class="w-full flex items-center justify-center gap-2 rounded-xl bg-[#ff1443] hover:bg-[#c90e33] text-white py-2.5 text-sm font-bold transition-all duration-200 cursor-pointer">
                        <i data-lucide="upload" class="w-4 h-4"></i>
                        Upload Berkas
                    </button>
                </div>

            </div>

            <!-- Riwayat Aktivitas -->
            <div class="xl:col-span-3 bg-white rounded-2xl border border-[#e5e7eb] flex flex-col h-full">
                <div class="p-6 border-b border-[#e5e7eb] flex items-center justify-between shrink-0">
                    <div>
                        <h3 class="font-bold text-base">Riwayat Aktivitas</h3>
                        <p class="text-sm text-[#6a7686] mt-0.5">Aktivitas terbaru pada akun Anda.</p>
                    </div>
                    <button class="shrink-0 rounded-xl border border-[#e5e7eb] px-4 py-2 text-sm font-medium hover:bg-[#eff2f7] transition-colors cursor-pointer">
                        Lihat Semua
                    </button>
                </div>
                <div class="p-6 flex-1">
                    <div class="relative h-full">
                        <div class="absolute left-3.5 top-0 bottom-0 w-px bg-[#e5e7eb]"></div>
                        <template x-for="activity in [
                        { icon: 'check', color: 'bg-[#30b22d]',   title: 'Berkas berhasil diverifikasi', date: '20 Juni 2026', desc: 'Semua dokumen telah diperiksa oleh panitia.' },
                        { icon: 'upload', color: 'bg-[#ff1443]',   title: 'Upload Rapor',                 date: '18 Juni 2026', desc: 'Nilai rapor semester berhasil diunggah.' },
                        { icon: 'file',   color: 'bg-[#3b82f6]',   title: 'Upload Kartu Keluarga',        date: '17 Juni 2026', desc: 'Dokumen berhasil diunggah.' },
                        { icon: 'user',   color: 'bg-[#8b5cf6]',   title: 'Melengkapi Biodata',           date: '15 Juni 2026', desc: 'Informasi pribadi telah lengkap.' }
                        ]">
                            <div class="relative flex gap-5 pb-6 last:pb-0">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs shrink-0 z-10"
                                    :class="activity.color">
                                    <i :data-lucide="activity.icon" class="w-3.5 h-3.5"></i>
                                </div>
                                <div class="flex-1 rounded-2xl border border-[#e5e7eb] p-4 card-hover cursor-pointer">
                                    <div class="flex items-start justify-between gap-2 flex-wrap">
                                        <p class="font-semibold text-sm" x-text="activity.title"></p>
                                        <p class="text-xs text-[#6a7686] shrink-0" x-text="activity.date"></p>
                                    </div>
                                    <p class="text-xs text-[#6a7686] mt-1 leading-5" x-text="activity.desc"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

        </div>

        <!-- Action Required Banner -->
        <div class="mt-6 rounded-2xl overflow-hidden border border-[#f59e0b]/40 bg-[#fef9c3]">
            <div class="p-6 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-[#f59e0b]/20 flex items-center justify-center shrink-0">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-[#92400e]"></i>
                    </div>
                    <div>
                        <p class="font-bold text-[#92400e]">Tindakan yang Harus Dilakukan</p>
                        <p class="text-sm text-[#92400e]/80 mt-0.5">
                            Surat Keterangan Lulus belum diunggah. Silakan unggah sebelum
                            <strong>8 Juli 2026</strong>.
                        </p>
                    </div>
                </div>
                <button class="shrink-0 rounded-xl bg-[#f59e0b] text-white px-6 py-3 font-bold text-sm hover:bg-[#d97706] transition-all duration-200 cursor-pointer">
                    Upload Sekarang →
                </button>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════
       COMMAND CENTER (Quick Access)
  ═══════════════════════════════════════════ -->
    <section class="mt-8">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-xl font-bold">Menu Layanan</h2>
                <p class="text-sm text-[#6a7686] mt-0.5">Akses cepat ke seluruh layanan SPMB.</p>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-4">

            <!-- Profil Saya -->
            <a href="#" class="group relative overflow-hidden rounded-2xl bg-white border border-[#e5e7eb] card-hover cursor-pointer flex flex-col">
                <div class="h-1.5 bg-gradient-to-r from-[#3b82f6] to-[#6366f1]"></div>
                <div class="p-5 flex flex-col flex-1">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#3b82f6]/10 flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shrink-0">
                            <i data-lucide="user-circle" class="w-6 h-6 text-[#3b82f6]"></i>
                        </div>
                        <span class="text-[10px] font-bold bg-[#3b82f6]/10 text-[#3b82f6] px-2 py-0.5 rounded-full">Akun</span>
                    </div>
                    <p class="font-bold text-[#080c1a] leading-tight">Profil Saya</p>
                    <p class="text-xs text-[#6a7686] mt-1 leading-5 flex-1">Perbarui data diri dan foto profil Anda.</p>
                    <div class="mt-4 flex items-center gap-1 text-xs font-bold text-[#3b82f6] group-hover:gap-2 transition-all duration-200">
                        Buka <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </a>

            <!-- Biodata -->
            <a href="#" class="group relative overflow-hidden rounded-2xl bg-white border border-[#e5e7eb] card-hover cursor-pointer flex flex-col">
                <div class="h-1.5 bg-gradient-to-r from-[#8b5cf6] to-[#a78bfa]"></div>
                <div class="p-5 flex flex-col flex-1">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#8b5cf6]/10 flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shrink-0">
                            <i data-lucide="clipboard-list" class="w-6 h-6 text-[#8b5cf6]"></i>
                        </div>
                        <span class="text-[10px] font-bold bg-[#8b5cf6]/10 text-[#8b5cf6] px-2 py-0.5 rounded-full">Data Diri</span>
                    </div>
                    <p class="font-bold text-[#080c1a] leading-tight">Lengkapi Biodata</p>
                    <p class="text-xs text-[#6a7686] mt-1 leading-5 flex-1">Isi dan perbarui informasi biodata peserta.</p>
                    <div class="mt-4 flex items-center gap-1 text-xs font-bold text-[#8b5cf6] group-hover:gap-2 transition-all duration-200">
                        Buka <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </a>

            <!-- Upload Berkas -->
            <a href="#" class="group relative overflow-hidden rounded-2xl bg-white border border-[#e5e7eb] card-hover cursor-pointer flex flex-col">
                <div class="h-1.5 bg-gradient-to-r from-[#30b22d] to-[#4ade80]"></div>
                <div class="p-5 flex flex-col flex-1">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#30b22d]/10 flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shrink-0">
                            <i data-lucide="upload-cloud" class="w-6 h-6 text-[#30b22d]"></i>
                        </div>
                        <span class="text-[10px] font-bold bg-[#f59e0b]/15 text-[#92400e] px-2 py-0.5 rounded-full">Perlu Aksi</span>
                    </div>
                    <p class="font-bold text-[#080c1a] leading-tight">Upload Berkas</p>
                    <p class="text-xs text-[#6a7686] mt-1 leading-5 flex-1">Unggah dokumen persyaratan pendaftaran.</p>
                    <div class="mt-4 flex items-center gap-1 text-xs font-bold text-[#30b22d] group-hover:gap-2 transition-all duration-200">
                        Buka <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </a>

            <!-- Cetak Bukti -->
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
                    <p class="text-xs text-[#6a7686] mt-1 leading-5 flex-1">Download dan cetak bukti pendaftaran resmi.</p>
                    <div class="mt-4 flex items-center gap-1 text-xs font-bold text-[#f59e0b] group-hover:gap-2 transition-all duration-200">
                        Buka <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </a>

            <!-- Pengumuman -->
            <a href="#" class="group relative overflow-hidden rounded-2xl bg-white border border-[#e5e7eb] card-hover cursor-pointer flex flex-col">
                <div class="h-1.5 bg-gradient-to-r from-[#ff1443] to-[#f43f5e]"></div>
                <div class="p-5 flex flex-col flex-1">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#ff1443]/10 flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shrink-0">
                            <i data-lucide="megaphone" class="w-6 h-6 text-[#ff1443]"></i>
                        </div>
                        <span class="text-[10px] font-bold bg-[#ff1443]/10 text-[#ff1443] px-2 py-0.5 rounded-full">3 Baru</span>
                    </div>
                    <p class="font-bold text-[#080c1a] leading-tight">Pengumuman</p>
                    <p class="text-xs text-[#6a7686] mt-1 leading-5 flex-1">Lihat informasi dan pengumuman terbaru.</p>
                    <div class="mt-4 flex items-center gap-1 text-xs font-bold text-[#ff1443] group-hover:gap-2 transition-all duration-200">
                        Buka <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </a>

            <!-- Jadwal -->
            <a href="#" class="group relative overflow-hidden rounded-2xl bg-white border border-[#e5e7eb] card-hover cursor-pointer flex flex-col">
                <div class="h-1.5 bg-gradient-to-r from-[#0ea5e9] to-[#38bdf8]"></div>
                <div class="p-5 flex flex-col flex-1">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#0ea5e9]/10 flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shrink-0">
                            <i data-lucide="calendar-days" class="w-6 h-6 text-[#0ea5e9]"></i>
                        </div>
                        <span class="text-[10px] font-bold bg-[#0ea5e9]/10 text-[#0ea5e9] px-2 py-0.5 rounded-full">Timeline</span>
                    </div>
                    <p class="font-bold text-[#080c1a] leading-tight">Jadwal SPMB</p>
                    <p class="text-xs text-[#6a7686] mt-1 leading-5 flex-1">Pantau jadwal dan tahapan kegiatan.</p>
                    <div class="mt-4 flex items-center gap-1 text-xs font-bold text-[#0ea5e9] group-hover:gap-2 transition-all duration-200">
                        Buka <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </a>

            <!-- Helpdesk -->
            <a href="#" class="group relative overflow-hidden rounded-2xl bg-white border border-[#e5e7eb] card-hover cursor-pointer flex flex-col">
                <div class="h-1.5 bg-gradient-to-r from-[#14b8a6] to-[#2dd4bf]"></div>
                <div class="p-5 flex flex-col flex-1">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#14b8a6]/10 flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shrink-0">
                            <i data-lucide="headphones" class="w-6 h-6 text-[#14b8a6]"></i>
                        </div>
                        <span class="text-[10px] font-bold bg-[#30b22d]/10 text-[#166534] px-2 py-0.5 rounded-full">Online</span>
                    </div>
                    <p class="font-bold text-[#080c1a] leading-tight">Helpdesk</p>
                    <p class="text-xs text-[#6a7686] mt-1 leading-5 flex-1">Hubungi tim panitia untuk bantuan.</p>
                    <div class="mt-4 flex items-center gap-1 text-xs font-bold text-[#14b8a6] group-hover:gap-2 transition-all duration-200">
                        Buka <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </a>

            <!-- FAQ -->
            <a href="#" class="group relative overflow-hidden rounded-2xl bg-white border border-[#e5e7eb] card-hover cursor-pointer flex flex-col">
                <div class="h-1.5 bg-gradient-to-r from-[#6a7686] to-[#94a3b8]"></div>
                <div class="p-5 flex flex-col flex-1">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#6a7686]/10 flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shrink-0">
                            <i data-lucide="message-circle-question" class="w-6 h-6 text-[#6a7686]"></i>
                        </div>
                        <span class="text-[10px] font-bold bg-[#6a7686]/10 text-[#6a7686] px-2 py-0.5 rounded-full">Panduan</span>
                    </div>
                    <p class="font-bold text-[#080c1a] leading-tight">FAQ</p>
                    <p class="text-xs text-[#6a7686] mt-1 leading-5 flex-1">Temukan jawaban atas pertanyaan umum.</p>
                    <div class="mt-4 flex items-center gap-1 text-xs font-bold text-[#6a7686] group-hover:gap-2 transition-all duration-200">
                        Buka <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </a>

        </div>
    </section>

    <!-- ═══════════════════════════════════════════
       ANNOUNCEMENT + FAQ
  ═══════════════════════════════════════════ -->
    <section class="mt-8 grid lg:grid-cols-2 gap-6">

        <!-- Announcement Center -->
        <div x-data="{ open: 0 }" class="bg-white rounded-2xl border border-[#e5e7eb]">
            <div class="p-6 border-b border-[#e5e7eb]">
                <h2 class="font-bold text-lg">Pengumuman</h2>
                <p class="text-sm text-[#6a7686] mt-0.5">Informasi terbaru dari panitia.</p>
            </div>
            <div class="divide-y divide-[#e5e7eb]">
                <template x-for="(item, index) in [
          { title: 'Pengumuman hasil seleksi',  date: '10 Juli 2026', body: 'Pengumuman hasil seleksi dapat dilihat mulai pukul 08.00 WIB pada laman resmi sekolah dan dashboard ini.' },
          { title: 'Jadwal daftar ulang',       date: '12 Juli 2026', body: 'Peserta yang dinyatakan lulus wajib melakukan daftar ulang pada tanggal 12–15 Juli 2026.' },
          { title: 'Verifikasi dokumen asli',   date: '18 Juli 2026', body: 'Membawa dokumen asli saat proses verifikasi akhir. Pastikan semua berkas dalam kondisi baik.' }
        ]">
                    <div>
                        <button @click="open == index ? open = -1 : open = index"
                            class="w-full p-5 flex items-center justify-between gap-3 hover:bg-[#eff2f7] transition-colors cursor-pointer">
                            <div class="flex items-center gap-3 text-left">
                                <div class="w-8 h-8 rounded-lg bg-[#ff1443]/10 flex items-center justify-center shrink-0">
                                    <i data-lucide="megaphone" class="w-3.5 h-3.5 text-[#ff1443]"></i>
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

        <!-- FAQ -->
        <div x-data="{ open: null }" class="bg-white rounded-2xl border border-[#e5e7eb]">
            <div class="p-6 border-b border-[#e5e7eb]">
                <h2 class="font-bold text-lg">Pertanyaan Umum</h2>
                <p class="text-sm text-[#6a7686] mt-0.5">Pertanyaan yang sering diajukan peserta.</p>
            </div>
            <div class="divide-y divide-[#e5e7eb]">
                <template x-for="(faq, index) in [
          { q: 'Bagaimana jika salah upload berkas?',      a: 'Anda dapat mengunggah ulang sebelum batas waktu pendaftaran. Pastikan ukuran dan format file sesuai ketentuan.' },
          { q: 'Kapan hasil seleksi diumumkan?',           a: 'Pengumuman akan dipublikasikan sesuai jadwal pada menu Pengumuman. Aktifkan notifikasi agar tidak ketinggalan.' },
          { q: 'Bagaimana jika lupa password?',            a: 'Gunakan menu " Lupa Password" pada halaman login. Link reset akan dikirim ke email yang terdaftar.' },
                    { q: 'Apakah data masih bisa diubah?' , a: 'Selama proses verifikasi belum selesai, data masih dapat diperbarui melalui menu Biodata.' }
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

</div><!-- /max-w-7xl -->
@endsection
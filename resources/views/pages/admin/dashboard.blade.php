@extends('layouts.admin')

@section('title', 'Beranda')
@section('page_title', 'Beranda')
@section('page_subtitle', 'Beranda Admin')

@section('content')

<div class="flex-1 overflow-y-auto p-5 md:p-8 bg-white" x-data="dashboardApp()">

    <!-- ── Header ── -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-foreground text-2xl md:text-3xl font-bold mb-1">Dashboard</h1>
            <p class="text-secondary text-sm">Selamat datang kembali, <span class="font-semibold text-foreground">Siti Aminah</span> — SPMB 2025/2026</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="flex items-center gap-2 px-4 py-3 ring-1 ring-border hover:ring-primary rounded-full text-foreground font-semibold text-sm transition-all duration-300 cursor-pointer bg-white">
                <i data-lucide="download-cloud" class="w-4 h-4"></i>
                <span>Ekspor</span>
            </button>
            <button class="flex items-center gap-2 px-4 py-3 bg-primary text-white rounded-full font-bold text-sm hover:bg-primary-hover transition-all duration-300 cursor-pointer">
                <i data-lucide="shield-check" class="w-4 h-4"></i>
                <span>Verifikasi</span>
            </button>
        </div>
    </div>

    <!-- ── Stat Cards ── -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <div class="flex flex-col rounded-2xl border border-border p-5 gap-3 bg-white hover:shadow-md transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between">
                <div class="size-11 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="users" class="size-5 text-primary"></i>
                </div>
                <span class="inline-flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-full bg-success/10 text-success-dark">
                    <i data-lucide="trending-up" class="size-3"></i>+127
                </span>
            </div>
            <div>
                <p class="font-bold text-3xl text-foreground">1.284</p>
                <p class="text-sm text-secondary mt-0.5">Total Pendaftar</p>
                <p class="text-xs text-secondary mt-1.5 flex items-center gap-1">
                    <i data-lucide="calendar" class="size-3"></i>Sejak 1 Januari 2026
                </p>
            </div>
        </div>

        <div class="flex flex-col rounded-2xl border border-border p-5 gap-3 bg-white hover:shadow-md transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between">
                <div class="size-11 bg-success/10 rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="check-circle" class="size-5 text-success"></i>
                </div>
                <span class="inline-flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-full bg-success/10 text-success-dark">
                    76.2%
                </span>
            </div>
            <div>
                <p class="font-bold text-3xl text-foreground">979</p>
                <p class="text-sm text-secondary mt-0.5">Dokumen Terverifikasi</p>
                <p class="text-xs text-secondary mt-1.5 flex items-center gap-1">
                    <i data-lucide="check" class="size-3"></i>305 belum diproses
                </p>
            </div>
        </div>

        <div class="flex flex-col rounded-2xl border border-border p-5 gap-3 bg-white hover:shadow-md transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between">
                <div class="size-11 bg-info/10 rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="hourglass" class="size-5 text-info"></i>
                </div>
                <span class="inline-flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-full bg-blue-50 text-blue-700">
                    <i data-lucide="clock" class="size-3"></i>Aktif
                </span>
            </div>
            <div>
                <p class="font-bold text-3xl text-foreground">187</p>
                <p class="text-sm text-secondary mt-0.5">Sudah Daftar Ulang</p>
                <p class="text-xs text-secondary mt-1.5 flex items-center gap-1">
                    <i data-lucide="alert-circle" class="size-3"></i>Batas: 15 Juni 2026
                </p>
            </div>
        </div>

        <div class="flex flex-col rounded-2xl border border-border p-5 gap-3 bg-white hover:shadow-md transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between">
                <div class="size-11 bg-error/10 rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="alert-triangle" class="size-5 text-error"></i>
                </div>
                <span class="inline-flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-full bg-error/10 text-error-dark">
                    <i data-lucide="trending-up" class="size-3"></i>+3
                </span>
            </div>
            <div>
                <p class="font-bold text-3xl text-foreground">24</p>
                <p class="text-sm text-secondary mt-0.5">Dokumen Bermasalah</p>
                <p class="text-xs text-error mt-1.5 flex items-center gap-1 font-semibold">
                    <i data-lucide="circle-alert" class="size-3"></i>Perlu tindakan segera
                </p>
            </div>
        </div>

    </div>

    <!-- ── Chart + Distribusi Jurusan ── -->
    <div class="grid grid-cols-1 lg:grid-cols-[70%_30%] gap-4 mb-4">

        <!-- Line Chart -->
        <div class="flex flex-col rounded-2xl border border-border p-6 gap-4 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="font-bold text-lg text-foreground">Tren Pendaftar Harian</h3>
                    <p class="text-sm text-secondary">30 hari terakhir — total masuk per hari</p>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="setChartRange('week')" id="btn-week"
                        class="px-3 py-1.5 rounded-full border border-border text-xs font-bold text-secondary hover:border-primary hover:text-primary transition-all cursor-pointer">
                        7 Hari
                    </button>
                    <button onclick="setChartRange('month')" id="btn-month"
                        class="px-3 py-1.5 rounded-full border border-primary bg-primary/10 text-xs font-bold text-primary cursor-pointer">
                        30 Hari
                    </button>
                </div>
            </div>
            <div class="w-full overflow-x-auto">
                <div class="w-full h-[320px]">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Donut + Legenda -->
        <div class="flex flex-col rounded-2xl border border-border p-6 gap-4 bg-white">
            <div>
                <h3 class="font-bold text-lg text-foreground">Distribusi Jurusan</h3>
                <p class="text-sm text-secondary">Peminat per program keahlian</p>
            </div>
            <div class="flex justify-center">
                <div style="width:160px;height:160px;position:relative">
                    <canvas id="donutChart"></canvas>
                </div>
            </div>
            <div class="flex flex-col gap-2 mt-1">
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-sm shrink-0" style="background:#FF1443"></div><span class="text-secondary">RPL</span>
                    </div>
                    <span class="font-bold text-foreground">347</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-sm shrink-0" style="background:#3B82F6"></div><span class="text-secondary">TKJ</span>
                    </div>
                    <span class="font-bold text-foreground">289</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-sm shrink-0" style="background:#F59E0B"></div><span class="text-secondary">Multimedia</span>
                    </div>
                    <span class="font-bold text-foreground">218</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-sm shrink-0" style="background:#30B22D"></div><span class="text-secondary">Akuntansi</span>
                    </div>
                    <span class="font-bold text-foreground">241</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-sm shrink-0" style="background:#8B5CF6"></div><span class="text-secondary">TKRO</span>
                    </div>
                    <span class="font-bold text-foreground">189</span>
                </div>
            </div>
        </div>

    </div>

    <!-- ── Kuota Jurusan + Aktivitas ── -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">

        <!-- Kuota per Jurusan -->
        <div class="flex flex-col rounded-2xl border border-border p-6 gap-5 bg-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-lg text-foreground">Kuota & Peminat</h3>
                    <p class="text-sm text-secondary">Perbandingan peminat vs kuota tersedia</p>
                </div>
                <button class="size-9 rounded-xl border border-border flex items-center justify-center text-secondary hover:border-primary hover:text-primary transition-colors cursor-pointer">
                    <i data-lucide="arrow-right" class="size-4"></i>
                </button>
            </div>
            <div class="flex flex-col divide-y divide-border">

                <div class="py-3 first:pt-0 last:pb-0">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-semibold text-foreground">Rekayasa Perangkat Lunak</span>
                        <span class="text-xs text-secondary">347 / 64 kursi <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-error/10 text-error-dark ml-1">5.4x</span></span>
                    </div>
                    <div class="h-1.5 rounded-full bg-border overflow-hidden">
                        <div class="h-full rounded-full bg-primary" style="width:100%"></div>
                    </div>
                </div>

                <div class="py-3 first:pt-0 last:pb-0">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-semibold text-foreground">Teknik Komputer & Jaringan</span>
                        <span class="text-xs text-secondary">289 / 64 kursi <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-error/10 text-error-dark ml-1">4.5x</span></span>
                    </div>
                    <div class="h-1.5 rounded-full bg-border overflow-hidden">
                        <div class="h-full rounded-full" style="width:100%;background:#3B82F6"></div>
                    </div>
                </div>

                <div class="py-3 first:pt-0 last:pb-0">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-semibold text-foreground">Multimedia / DKV</span>
                        <span class="text-xs text-secondary">218 / 64 kursi <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-warning/10 text-warning-dark ml-1">3.4x</span></span>
                    </div>
                    <div class="h-1.5 rounded-full bg-border overflow-hidden">
                        <div class="h-full rounded-full bg-warning" style="width:100%"></div>
                    </div>
                </div>

                <div class="py-3 first:pt-0 last:pb-0">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-semibold text-foreground">Akuntansi & Keuangan</span>
                        <span class="text-xs text-secondary">241 / 64 kursi <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-warning/10 text-warning-dark ml-1">3.8x</span></span>
                    </div>
                    <div class="h-1.5 rounded-full bg-border overflow-hidden">
                        <div class="h-full rounded-full bg-success" style="width:95%"></div>
                    </div>
                </div>

                <div class="py-3 first:pt-0 last:pb-0">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-semibold text-foreground">Teknik Kendaraan Ringan</span>
                        <span class="text-xs text-secondary">189 / 64 kursi <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-success/10 text-success-dark ml-1">3.0x</span></span>
                    </div>
                    <div class="h-1.5 rounded-full bg-border overflow-hidden">
                        <div class="h-full rounded-full" style="width:88%;background:#8B5CF6"></div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Aktivitas Terbaru -->
        <div class="flex flex-col rounded-2xl border border-border p-6 gap-4 bg-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-lg text-foreground">Aktivitas Terbaru</h3>
                    <p class="text-sm text-secondary">Log aksi admin &amp; sistem</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold">
                    <span class="size-1.5 rounded-full bg-primary animate-pulse inline-block"></span>Live
                </span>
            </div>
            <div class="flex flex-col divide-y divide-border">

                <div class="flex gap-3 py-3 first:pt-0">
                    <div class="size-8 rounded-xl bg-success/10 flex items-center justify-center shrink-0 mt-0.5">
                        <i data-lucide="circle-check" class="size-4 text-success"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-foreground">Dokumen Ahmad Fauzi diverifikasi</p>
                        <p class="text-xs text-secondary mt-0.5">Ijazah &amp; rapor dinyatakan valid — RPL</p>
                        <p class="text-[10px] text-secondary mt-1 flex items-center gap-1">
                            <i data-lucide="clock" class="size-3"></i>10 menit lalu · Admin Budi
                        </p>
                    </div>
                </div>

                <div class="flex gap-3 py-3">
                    <div class="size-8 rounded-xl bg-error/10 flex items-center justify-center shrink-0 mt-0.5">
                        <i data-lucide="circle-x" class="size-4 text-error"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-foreground">Dokumen Rini Kartika ditolak</p>
                        <p class="text-xs text-secondary mt-0.5">Pas foto tidak memenuhi syarat (blur)</p>
                        <p class="text-[10px] text-secondary mt-1 flex items-center gap-1">
                            <i data-lucide="clock" class="size-3"></i>34 menit lalu · Admin Siti
                        </p>
                    </div>
                </div>

                <div class="flex gap-3 py-3">
                    <div class="size-8 rounded-xl bg-info/10 flex items-center justify-center shrink-0 mt-0.5">
                        <i data-lucide="megaphone" class="size-4 text-info"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-foreground">Pengumuman jadwal wawancara diterbitkan</p>
                        <p class="text-xs text-secondary mt-0.5">Broadcast ke 847 peserta via email &amp; WA</p>
                        <p class="text-[10px] text-secondary mt-1 flex items-center gap-1">
                            <i data-lucide="clock" class="size-3"></i>2 jam lalu · Admin Siti
                        </p>
                    </div>
                </div>

                <div class="flex gap-3 py-3">
                    <div class="size-8 rounded-xl bg-warning/10 flex items-center justify-center shrink-0 mt-0.5">
                        <i data-lucide="alert-triangle" class="size-4 text-warning"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-foreground">7 peserta belum upload pas foto</p>
                        <p class="text-xs text-secondary mt-0.5">Pengingat otomatis terkirim via WhatsApp</p>
                        <p class="text-[10px] text-secondary mt-1 flex items-center gap-1">
                            <i data-lucide="clock" class="size-3"></i>4 jam lalu · Sistem
                        </p>
                    </div>
                </div>

                <div class="flex gap-3 py-3 last:pb-0">
                    <div class="size-8 rounded-xl bg-primary/10 flex items-center justify-center shrink-0 mt-0.5">
                        <i data-lucide="user-plus" class="size-4 text-primary"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-foreground">14 peserta baru mendaftar</p>
                        <p class="text-xs text-secondary mt-0.5">Gelombang terakhir sebelum penutupan</p>
                        <p class="text-[10px] text-secondary mt-1 flex items-center gap-1">
                            <i data-lucide="clock" class="size-3"></i>6 jam lalu · Sistem
                        </p>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- ── Tabel Peserta Terbaru ── -->
    <div class="flex flex-col rounded-2xl border border-border p-6 bg-white gap-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-lg text-foreground">Peserta Terbaru</h3>
                <p class="text-sm text-secondary">10 pendaftar terakhir masuk sistem</p>
            </div>
            <div class="flex items-stretch gap-2">
                <div class="relative flex">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-secondary"></i>
                    <input type="text" placeholder="Cari peserta..." oninput="filterTable(this.value)"
                        class="pl-9 pr-4 h-10 rounded-xl border border-border bg-white text-sm focus:ring-1 focus:ring-primary outline-none w-[180px] transition-all" />
                </div>

                <button class="flex items-center justify-center size-10 rounded-xl border border-border bg-white hover:bg-muted cursor-pointer transition-colors">
                    <i data-lucide="filter" class="size-4 text-secondary"></i>
                </button>

                <button class="flex items-center gap-1.5 px-4 h-10 bg-primary text-white rounded-full font-bold text-xs hover:bg-primary-hover transition-all cursor-pointer">
                    <i data-lucide="shield-check" class="size-3.5"></i>Verifikasi
                </button>

            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[800px] border-collapse" id="pesertaTable">
                <thead class="border-b border-border">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-secondary uppercase tracking-wide">Peserta</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-secondary uppercase tracking-wide">No. Peserta</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-secondary uppercase tracking-wide">Jurusan</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-secondary uppercase tracking-wide">Dokumen</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-secondary uppercase tracking-wide">Nilai</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-secondary uppercase tracking-wide">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-bold text-secondary uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border" id="tableBody">

                    <tr class="border-b border-border hover:bg-muted/50 transition-colors">
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="size-9 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0" style="background:linear-gradient(135deg,#FF1443,#FF6B8A)">AF</div>
                                <div>
                                    <div class="text-sm font-semibold text-foreground">Ahmad Fauzi</div>
                                    <div class="text-xs text-secondary">Palembang, 14 Mar 2010</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-xs font-bold text-secondary font-mono">SPMB-2026-004821</td>
                        <td class="px-4 py-3.5"><span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-primary/10 text-primary">RPL</span></td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-2">
                                <div class="w-16 h-1.5 rounded-full bg-border overflow-hidden">
                                    <div class="h-full rounded-full bg-warning" style="width:80%"></div>
                                </div>
                                <span class="text-xs text-secondary">4/5</span>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-sm font-bold text-foreground">88.50</td>
                        <td class="px-4 py-3.5"><span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-bold bg-warning/10 text-warning-dark"><i data-lucide="clock" class="size-3"></i>Verifikasi</span></td>
                        <td class="px-4 py-3.5 text-right">
                            <button class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-primary/10 hover:bg-primary/20 text-primary text-xs font-bold transition-colors cursor-pointer">
                                <i data-lucide="eye" class="size-3.5"></i>Detail
                            </button>
                        </td>
                    </tr>

                    <tr class="border-b border-border hover:bg-muted/50 transition-colors">
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="size-9 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0" style="background:linear-gradient(135deg,#3B82F6,#60A5FA)">RK</div>
                                <div>
                                    <div class="text-sm font-semibold text-foreground">Rini Kartika</div>
                                    <div class="text-xs text-secondary">Palembang, 22 Sep 2009</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-xs font-bold text-secondary font-mono">SPMB-2026-004820</td>
                        <td class="px-4 py-3.5"><span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700">TKJ</span></td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-2">
                                <div class="w-16 h-1.5 rounded-full bg-border overflow-hidden">
                                    <div class="h-full rounded-full bg-error" style="width:60%"></div>
                                </div>
                                <span class="text-xs text-secondary">3/5</span>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-sm font-bold text-foreground">82.30</td>
                        <td class="px-4 py-3.5"><span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-bold bg-error/10 text-error-dark"><i data-lucide="x" class="size-3"></i>Ditolak</span></td>
                        <td class="px-4 py-3.5 text-right">
                            <button class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-primary/10 hover:bg-primary/20 text-primary text-xs font-bold transition-colors cursor-pointer">
                                <i data-lucide="eye" class="size-3.5"></i>Detail
                            </button>
                        </td>
                    </tr>

                    <tr class="border-b border-border hover:bg-muted/50 transition-colors">
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="size-9 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0" style="background:linear-gradient(135deg,#30B22D,#4ADE80)">BW</div>
                                <div>
                                    <div class="text-sm font-semibold text-foreground">Budi Wijaya</div>
                                    <div class="text-xs text-secondary">Banyuasin, 5 Jan 2010</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-xs font-bold text-secondary font-mono">SPMB-2026-004819</td>
                        <td class="px-4 py-3.5"><span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-success/10 text-success-dark">Akuntansi</span></td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-2">
                                <div class="w-16 h-1.5 rounded-full bg-border overflow-hidden">
                                    <div class="h-full rounded-full bg-success" style="width:100%"></div>
                                </div>
                                <span class="text-xs text-secondary">5/5</span>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-sm font-bold text-foreground">91.20</td>
                        <td class="px-4 py-3.5"><span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-bold bg-success/10 text-success-dark"><i data-lucide="check" class="size-3"></i>Verified</span></td>
                        <td class="px-4 py-3.5 text-right">
                            <button class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-primary/10 hover:bg-primary/20 text-primary text-xs font-bold transition-colors cursor-pointer">
                                <i data-lucide="eye" class="size-3.5"></i>Detail
                            </button>
                        </td>
                    </tr>

                    <tr class="border-b border-border hover:bg-muted/50 transition-colors">
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="size-9 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0" style="background:linear-gradient(135deg,#F59E0B,#FCD34D)">DP</div>
                                <div>
                                    <div class="text-sm font-semibold text-foreground">Dina Pratiwi</div>
                                    <div class="text-xs text-secondary">Palembang, 17 Jul 2009</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-xs font-bold text-secondary font-mono">SPMB-2026-004818</td>
                        <td class="px-4 py-3.5"><span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold" style="background:#FFF7ED;color:#C2410C">Multimedia</span></td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-2">
                                <div class="w-16 h-1.5 rounded-full bg-border overflow-hidden">
                                    <div class="h-full rounded-full bg-success" style="width:100%"></div>
                                </div>
                                <span class="text-xs text-secondary">5/5</span>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-sm font-bold text-foreground">85.70</td>
                        <td class="px-4 py-3.5"><span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-bold bg-success/10 text-success-dark"><i data-lucide="check" class="size-3"></i>Verified</span></td>
                        <td class="px-4 py-3.5 text-right">
                            <button class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-primary/10 hover:bg-primary/20 text-primary text-xs font-bold transition-colors cursor-pointer">
                                <i data-lucide="eye" class="size-3.5"></i>Detail
                            </button>
                        </td>
                    </tr>

                    <tr class="border-b border-border hover:bg-muted/50 transition-colors">
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="size-9 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0" style="background:linear-gradient(135deg,#8B5CF6,#A78BFA)">HS</div>
                                <div>
                                    <div class="text-sm font-semibold text-foreground">Hendra Saputra</div>
                                    <div class="text-xs text-secondary">Ogan Ilir, 3 Apr 2010</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-xs font-bold text-secondary font-mono">SPMB-2026-004817</td>
                        <td class="px-4 py-3.5"><span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold" style="background:#F5F3FF;color:#6D28D9">TKRO</span></td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-2">
                                <div class="w-16 h-1.5 rounded-full bg-border overflow-hidden">
                                    <div class="h-full rounded-full bg-error" style="width:40%"></div>
                                </div>
                                <span class="text-xs text-secondary">2/5</span>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-sm font-bold text-foreground">79.40</td>
                        <td class="px-4 py-3.5"><span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-bold bg-muted text-secondary"><i data-lucide="upload" class="size-3"></i>Belum Lengkap</span></td>
                        <td class="px-4 py-3.5 text-right">
                            <button class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-primary/10 hover:bg-primary/20 text-primary text-xs font-bold transition-colors cursor-pointer">
                                <i data-lucide="eye" class="size-3.5"></i>Detail
                            </button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 pt-4">

            <span class="text-sm text-secondary text-center">
                Menampilkan <span class="font-semibold text-foreground">5</span> dari <span class="font-semibold text-foreground">1.284</span> peserta
            </span>

            <div class="flex items-center gap-2">
                <button class="p-2 rounded-lg border border-border bg-white hover:bg-muted cursor-pointer disabled:opacity-50 transition-colors" disabled>
                    <i data-lucide="chevron-left" class="size-4"></i>
                </button>
                <button class="px-3 py-1.5 rounded-lg bg-primary text-white text-sm font-bold cursor-pointer">1</button>
                <button class="px-3 py-1.5 rounded-lg border border-border text-sm text-secondary hover:bg-muted cursor-pointer transition-colors">2</button>
                <button class="px-3 py-1.5 rounded-lg border border-border text-sm text-secondary hover:bg-muted cursor-pointer transition-colors">3</button>
                <button class="p-2 rounded-lg border border-border bg-white hover:bg-muted cursor-pointer transition-colors">
                    <i data-lucide="chevron-right" class="size-4"></i>
                </button>
            </div>

        </div>
    </div>
    <!-- /tabel -->

</div>

@push('scripts')
<script>
    function dashboardApp() {
        return {};
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (window.lucide) lucide.createIcons();

        // Line Chart
        const labels30 = Array.from({
            length: 30
        }, (_, i) => {
            const d = new Date('2026-01-01');
            d.setDate(d.getDate() + i);
            return d.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'short'
            })
        });
        const data30 = [12, 18, 24, 31, 27, 45, 52, 38, 61, 48, 55, 70, 66, 58, 72, 80, 75, 90, 88, 84, 95, 102, 98, 110, 107, 115, 121, 118, 130, 127];
        const labels7 = labels30.slice(-7),
            data7 = data30.slice(-7);

        const trendCtx = document.getElementById('trendChart');
        if (!trendCtx) return;

        let trendChart = new Chart(trendCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels30,
                datasets: [{
                    label: 'Pendaftar',
                    data: data30,
                    backgroundColor: 'rgba(255,20,67,0.12)',
                    borderColor: '#FF1443',
                    borderWidth: 1.5,
                    borderRadius: 10,
                    barThickness: 18,
                    hoverBackgroundColor: 'rgba(255,20,67,0.2)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#080C1A',
                        titleFont: {
                            family: 'Roboto',
                            size: 12
                        },
                        bodyFont: {
                            family: 'Roboto',
                            size: 11
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: 'Roboto',
                                size: 10
                            },
                            maxRotation: 0,
                            maxTicksLimit: 8
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.04)'
                        },
                        ticks: {
                            font: {
                                family: 'Roboto',
                                size: 10
                            }
                        }
                    }
                }
            }
        });

        window.setChartRange = function(r) {
            trendChart.data.labels = r === 'week' ? labels7 : labels30;
            trendChart.data.datasets[0].data = r === 'week' ? data7 : data30;
            trendChart.update();
            document.getElementById('btn-week').className = r === 'week' ?
                'px-3 py-1.5 rounded-full border border-primary bg-primary/10 text-xs font-bold text-primary cursor-pointer' :
                'px-3 py-1.5 rounded-full border border-border text-xs font-bold text-secondary hover:border-primary hover:text-primary transition-all cursor-pointer';
            document.getElementById('btn-month').className = r === 'month' ?
                'px-3 py-1.5 rounded-full border border-primary bg-primary/10 text-xs font-bold text-primary cursor-pointer' :
                'px-3 py-1.5 rounded-full border border-border text-xs font-bold text-secondary hover:border-primary hover:text-primary transition-all cursor-pointer';
        };

        // Donut Chart
        new Chart(document.getElementById('donutChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['RPL', 'TKJ', 'Multimedia', 'Akuntansi', 'TKRO'],
                datasets: [{
                    data: [347, 289, 218, 241, 189],
                    backgroundColor: ['#FF1443', '#3B82F6', '#F59E0B', '#30B22D', '#8B5CF6'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                cutout: '72%'
            }
        });

        // Table filter
        window.filterTable = function(q) {
            document.querySelectorAll('#tableBody tr').forEach(tr => {
                tr.style.display = tr.textContent.toLowerCase().includes(q.toLowerCase()) ? '' : 'none';
            });
        };
    });
</script>
@endpush

@endsection
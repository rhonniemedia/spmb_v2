@extends('layouts.admin')

@section('title', 'Verifikasi')
@section('page_title', 'Verifikasi')
@section('page_subtitle', 'Verifikasi Data')

@section('content')

<div class="flex-1 overflow-y-auto p-5 md:p-8 bg-white">

    <!-- Header row -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-foreground text-2xl md:text-3xl font-bold mb-1">Antrian Verifikasi</h1>
            <p class="text-secondary text-sm">Kelola dan verifikasi dokumen peserta SPMB 2025/2026.</p>
        </div>
        <div class="grid grid-cols-2 gap-2 w-full md:w-auto md:flex md:items-center md:gap-3">
            <button class="flex items-center justify-center gap-2 px-4 py-3 ring-1 ring-border hover:ring-primary rounded-full text-foreground font-semibold text-sm transition-all duration-300 cursor-pointer bg-white">
                <i data-lucide="download" class="w-4 h-4"></i>
                <span>Ekspor</span>
            </button>
            <button class="flex items-center justify-center gap-2 px-4 py-3 bg-primary text-white rounded-full font-bold text-sm hover:bg-primary-hover transition-all duration-300 cursor-pointer">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                <span>Segarkan</span>
            </button>
        </div>
    </div>

    <!-- Stat cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="flex flex-col rounded-2xl border border-border p-5 gap-3 bg-white">
            <div class="flex items-center gap-2">
                <div class="size-10 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="users" class="size-5 text-primary"></i>
                </div>
                <p class="font-medium text-xs text-secondary">Total Peserta</p>
            </div>
            <p class="font-bold text-3xl">248</p>
        </div>
        <div class="flex flex-col rounded-2xl border border-border p-5 gap-3 bg-white">
            <div class="flex items-center gap-2">
                <div class="size-10 bg-warning/10 rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="clock" class="size-5 text-warning"></i>
                </div>
                <p class="font-medium text-xs text-secondary">Pending</p>
            </div>
            <div class="flex items-center gap-2">
                <p class="font-bold text-3xl">24</p>
                <span class="text-warning text-xs font-semibold">Perlu tindakan</span>
            </div>
        </div>
        <div class="flex flex-col rounded-2xl border border-border p-5 gap-3 bg-white">
            <div class="flex items-center gap-2">
                <div class="size-10 bg-success/10 rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="check-circle" class="size-5 text-success"></i>
                </div>
                <p class="font-medium text-xs text-secondary">Terverifikasi</p>
            </div>
            <div class="flex items-center gap-2">
                <p class="font-bold text-3xl">189</p>
                <span class="text-success text-xs font-semibold">76.2%</span>
            </div>
        </div>
        <div class="flex flex-col rounded-2xl border border-border p-5 gap-3 bg-white">
            <div class="flex items-center gap-2">
                <div class="size-10 bg-error/10 rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="x-circle" class="size-5 text-error"></i>
                </div>
                <p class="font-medium text-xs text-secondary">Ditolak</p>
            </div>
            <p class="font-bold text-3xl">35</p>
        </div>
    </div>

    <!-- Table card -->
    <div class="flex flex-col rounded-2xl border border-border p-6 bg-white gap-6" x-data="verifikasiApp()">

        <!-- Table header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-lg text-foreground">Antrian Verifikasi Dokumen</h3>
                <p class="text-sm text-secondary">10 pendaftar terakhir masuk sistem</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <!-- Search -->
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-secondary"></i>
                    <input type="text" placeholder="Cari peserta..." x-model="search"
                        class="pl-9 pr-4 py-2 rounded-xl border border-border bg-white text-sm focus:ring-1 focus:ring-primary outline-none w-[180px] transition-all" />
                </div>
                <!-- Filter status -->
                <select x-model="filterStatus" class="py-2 pl-3 rounded-xl border border-border bg-white text-sm focus:ring-1 focus:ring-primary outline-none text-secondary">
                    <option value="all">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="verified">Terverifikasi</option>
                    <option value="rejected">Ditolak</option>
                    <option value="incomplete">Kurang Dokumen</option>
                </select>
                <!-- Filter jurusan -->
                <select x-model="filterJurusan" class="py-2 pl-3 rounded-xl border border-border bg-white text-sm focus:ring-1 focus:ring-primary outline-none text-secondary">
                    <option value="all">Semua Jurusan</option>
                    <option value="RPL">RPL</option>
                    <option value="TKJ">TKJ</option>
                    <option value="Akuntansi">Akuntansi</option>
                    <option value="Multimedia">Multimedia</option>
                    <option value="TKRO">TKRO</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full min-w-[860px] border-collapse">
                <thead class="border-b border-border">
                    <tr>
                        <th class="px-4 py-4 text-left text-sm font-bold text-foreground w-[28%]">Peserta</th>
                        <th class="px-4 py-4 text-left text-sm font-bold text-foreground w-[12%]">No. Daftar</th>
                        <th class="px-4 py-4 text-left text-sm font-bold text-foreground w-[12%]">Jurusan</th>
                        <th class="px-4 py-4 text-left text-sm font-bold text-foreground w-[12%]">Dokumen</th>
                        <th class="px-4 py-4 text-left text-sm font-bold text-foreground w-[14%]">Status</th>
                        <th class="px-4 py-4 text-left text-sm font-bold text-foreground w-[12%]">Tgl Daftar</th>
                        <th class="px-4 py-4 text-right text-sm font-bold text-foreground w-[10%]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border bg-white">
                    <template x-for="p in filteredData" :key="p.id">
                        <tr class="border-b border-border hover:bg-muted/50 transition-colors">
                            <!-- Peserta -->
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0" :style="`background: ${p.color}`" x-text="p.init"></div>
                                    <div>
                                        <div class="font-semibold text-foreground text-sm" x-text="p.name"></div>
                                        <div class="text-xs text-secondary" x-text="p.email"></div>
                                    </div>
                                </div>
                            </td>
                            <!-- No daftar -->
                            <td class="px-4 py-4">
                                <div class="text-sm font-medium text-foreground font-mono" x-text="`SPMB-${p.id}`"></div>
                            </td>
                            <!-- Jurusan -->
                            <td class="px-4 py-4">
                                <div class="text-sm text-secondary" x-text="p.jurusan"></div>
                            </td>
                            <!-- Dokumen progress -->
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-16 h-1.5 rounded-full bg-border overflow-hidden">
                                        <div class="h-full rounded-full transition-all"
                                            :class="p.docsComplete === p.docsTotal ? 'bg-success' : p.docsComplete >= p.docsTotal - 1 ? 'bg-warning' : 'bg-error'"
                                            :style="`width: ${(p.docsComplete/p.docsTotal)*100}%`"></div>
                                    </div>
                                    <span class="text-xs text-secondary" x-text="`${p.docsComplete}/${p.docsTotal}`"></span>
                                </div>
                            </td>
                            <!-- Status -->
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-bold"
                                    :class="{
                          'bg-warning/10 text-warning-dark': p.status === 'pending',
                          'bg-success/10 text-success-dark': p.status === 'verified',
                          'bg-error/10 text-error-dark': p.status === 'rejected',
                          'bg-blue-50 text-blue-700': p.status === 'incomplete'
                        }">
                                    <i :data-lucide="p.status === 'pending' ? 'clock' : p.status === 'verified' ? 'check' : p.status === 'rejected' ? 'x' : 'alert-circle'" class="size-3"></i>
                                    <span x-text="p.statusLabel"></span>
                                </span>
                            </td>
                            <!-- Tanggal -->
                            <td class="px-4 py-4 text-sm text-secondary" x-text="p.tanggal"></td>
                            <!-- Aksi -->
                            <td class="px-4 py-4 text-right">
                                <button @click="openDetail(p)"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary/10 hover:bg-primary/20 text-primary font-semibold text-xs transition-colors cursor-pointer">
                                    <i data-lucide="eye" class="size-3.5"></i>
                                    Verifikasi
                                </button>
                            </td>
                        </tr>
                    </template>

                    <!-- Empty state -->
                    <tr x-show="filteredData.length === 0">
                        <td colspan="7" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-secondary">
                                <i data-lucide="inbox" class="size-10 text-border"></i>
                                <p class="font-medium">Tidak ada data ditemukan</p>
                                <p class="text-sm">Coba ubah filter atau kata kunci pencarian</p>
                            </div>
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

        <!-- ══ MODAL DETAIL VERIFIKASI ══ -->
        <div x-show="modalOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-foreground/60 z-[200] flex items-center justify-center p-4" style="display:none" @click.self="modalOpen = false">

            <div x-show="modalOpen"
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="bg-white rounded-2xl w-full max-w-3xl max-h-[90vh] flex flex-col overflow-hidden shadow-2xl">

                <!-- Modal header -->
                <div class="flex items-center justify-between px-6 py-5 border-b border-border shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0"
                            :style="`background: ${activePeserta?.color}`" x-text="activePeserta?.init"></div>
                        <div>
                            <h3 class="font-bold text-foreground" x-text="activePeserta?.name"></h3>
                            <p class="text-xs text-secondary font-mono" x-text="`SPMB-${activePeserta?.id} · ${activePeserta?.jurusan}`"></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-bold"
                            :class="{
                      'bg-warning/10 text-warning-dark': activePeserta?.status === 'pending',
                      'bg-success/10 text-success-dark': activePeserta?.status === 'verified',
                      'bg-error/10 text-error-dark': activePeserta?.status === 'rejected',
                      'bg-blue-50 text-blue-700': activePeserta?.status === 'incomplete'
                    }" x-text="activePeserta?.statusLabel"></span>
                        <button @click="modalOpen = false" class="size-9 rounded-xl bg-muted hover:bg-border flex items-center justify-center cursor-pointer transition-colors">
                            <i data-lucide="x" class="size-4 text-secondary"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal body scroll -->
                <div class="overflow-y-auto flex-1 p-6 scrollbar-hide">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- KOLOM KIRI: Data Peserta -->
                        <div class="flex flex-col gap-5">

                            <!-- Info Pribadi -->
                            <div class="rounded-xl border border-border p-4 flex flex-col gap-3">
                                <h4 class="font-semibold text-sm text-foreground flex items-center gap-2">
                                    <i data-lucide="user" class="size-4 text-primary"></i>
                                    Informasi Peserta
                                </h4>
                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div>
                                        <p class="text-secondary text-xs mb-0.5">Nama Lengkap</p>
                                        <p class="font-semibold text-foreground" x-text="activePeserta?.name"></p>
                                    </div>
                                    <div>
                                        <p class="text-secondary text-xs mb-0.5">No. Pendaftaran</p>
                                        <p class="font-semibold text-foreground font-mono" x-text="`SPMB-${activePeserta?.id}`"></p>
                                    </div>
                                    <div>
                                        <p class="text-secondary text-xs mb-0.5">Jurusan Pilihan</p>
                                        <p class="font-semibold text-foreground" x-text="activePeserta?.jurusan"></p>
                                    </div>
                                    <div>
                                        <p class="text-secondary text-xs mb-0.5">Tanggal Daftar</p>
                                        <p class="font-semibold text-foreground" x-text="activePeserta?.tanggal"></p>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-secondary text-xs mb-0.5">Email</p>
                                        <p class="font-semibold text-foreground" x-text="activePeserta?.email"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Nilai -->
                            <div class="rounded-xl border border-border p-4 flex flex-col gap-3">
                                <h4 class="font-semibold text-sm text-foreground flex items-center gap-2">
                                    <i data-lucide="bar-chart-2" class="size-4 text-primary"></i>
                                    Nilai Terverifikasi
                                </h4>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="text-xs text-secondary block mb-1">Rata-rata Rapor</label>
                                        <input type="number" value="88.50" step="0.01" min="0" max="100"
                                            class="w-full px-3 py-2 rounded-xl border border-border text-sm font-semibold text-foreground focus:ring-1 focus:ring-primary outline-none transition-all" />
                                    </div>
                                    <div>
                                        <label class="text-xs text-secondary block mb-1">Nilai UN / ASPD</label>
                                        <input type="number" value="87.20" step="0.01" min="0" max="100"
                                            class="w-full px-3 py-2 rounded-xl border border-border text-sm font-semibold text-foreground focus:ring-1 focus:ring-primary outline-none transition-all" />
                                    </div>
                                </div>
                            </div>

                            <!-- Catatan -->
                            <div class="rounded-xl border border-border p-4 flex flex-col gap-3">
                                <h4 class="font-semibold text-sm text-foreground flex items-center gap-2">
                                    <i data-lucide="file-text" class="size-4 text-primary"></i>
                                    Catatan Verifikasi
                                </h4>
                                <textarea rows="4" placeholder="Tambahkan catatan untuk peserta atau panitia..."
                                    class="w-full px-3 py-2 rounded-xl border border-border text-sm text-foreground focus:ring-1 focus:ring-primary outline-none transition-all resize-none">Akta dan ijazah valid. Rapor perlu dicek halaman terakhir. Pas foto belum ada — sudah dikirim pengingat WA.</textarea>
                            </div>

                        </div>

                        <!-- KOLOM KANAN: Cek Dokumen & Keputusan -->
                        <div class="flex flex-col gap-5">

                            <!-- Checklist Dokumen -->
                            <div class="rounded-xl border border-border p-4 flex flex-col gap-3">
                                <h4 class="font-semibold text-sm text-foreground flex items-center gap-2">
                                    <i data-lucide="check-square" class="size-4 text-primary"></i>
                                    Verifikasi Dokumen
                                </h4>

                                <div class="flex flex-col gap-2" x-data="{
                        docs: [
                          { key: 'akta', label: 'Akta Kelahiran', icon: 'file', color: 'bg-blue-50', iconColor: 'text-info', status: 'approve' },
                          { key: 'kk', label: 'Kartu Keluarga', icon: 'users', color: 'bg-green-50', iconColor: 'text-success', status: 'approve' },
                          { key: 'ijazah', label: 'Ijazah / SKL', icon: 'award', color: 'bg-yellow-50', iconColor: 'text-warning', status: 'approve' },
                          { key: 'rapor', label: 'Rapor Kelas 4-6', icon: 'book-open', color: 'bg-purple-50', iconColor: 'text-purple-500', status: null },
                          { key: 'foto', label: 'Pas Foto 3x4', icon: 'image', color: 'bg-red-50', iconColor: 'text-error', status: 'reject' },
                          { key: 'sertif', label: 'Sertifikat Prestasi', icon: 'star', color: 'bg-green-50', iconColor: 'text-success', status: 'approve' }
                        ]
                      }">
                                    <template x-for="doc in docs" :key="doc.key">
                                        <div class="flex items-center justify-between py-2 px-3 rounded-xl hover:bg-muted transition-colors">
                                            <div class="flex items-center gap-2.5">
                                                <div class="size-8 rounded-lg flex items-center justify-center shrink-0" :class="doc.color">
                                                    <i :data-lucide="doc.icon" class="size-4" :class="doc.iconColor"></i>
                                                </div>
                                                <span class="text-sm font-medium text-foreground" x-text="doc.label"></span>
                                            </div>
                                            <div class="flex items-center gap-1.5">
                                                <!-- Preview -->
                                                <button @click="previewDoc(doc.label)"
                                                    class="size-7 rounded-lg bg-muted hover:bg-border flex items-center justify-center cursor-pointer transition-colors" title="Preview">
                                                    <i data-lucide="eye" class="size-3.5 text-secondary"></i>
                                                </button>
                                                <!-- Approve -->
                                                <button @click="doc.status = 'approve'"
                                                    class="size-7 rounded-lg flex items-center justify-center cursor-pointer transition-all"
                                                    :class="doc.status === 'approve' ? 'bg-success text-white' : 'bg-success/10 text-success hover:bg-success/20'">
                                                    <i data-lucide="check" class="size-3.5"></i>
                                                </button>
                                                <!-- Reject -->
                                                <button @click="doc.status = 'reject'"
                                                    class="size-7 rounded-lg flex items-center justify-center cursor-pointer transition-all"
                                                    :class="doc.status === 'reject' ? 'bg-error text-white' : 'bg-error/10 text-error hover:bg-error/20'">
                                                    <i data-lucide="x" class="size-3.5"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Info box -->
                            <div class="rounded-xl bg-warning/10 border border-warning/20 p-3 flex gap-2.5">
                                <i data-lucide="alert-triangle" class="size-4 text-warning-dark shrink-0 mt-0.5"></i>
                                <p class="text-xs text-warning-dark">Pas foto belum ada. Disarankan <strong>tunda</strong> hingga peserta melengkapi dokumen.</p>
                            </div>

                            <!-- Keputusan Final -->
                            <div class="rounded-xl border border-border p-4 flex flex-col gap-3">
                                <h4 class="font-semibold text-sm text-foreground flex items-center gap-2">
                                    <i data-lucide="gavel" class="size-4 text-primary"></i>
                                    Keputusan Final
                                </h4>
                                <div class="flex flex-col gap-2">
                                    <button @click="finalDecision('approve')"
                                        class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-success text-white rounded-xl font-bold text-sm hover:bg-success-dark transition-colors cursor-pointer">
                                        <i data-lucide="circle-check" class="size-4"></i>
                                        Setujui Semua Dokumen
                                    </button>
                                    <button @click="finalDecision('hold')"
                                        class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-warning text-white rounded-xl font-bold text-sm hover:opacity-90 transition-colors cursor-pointer">
                                        <i data-lucide="clock" class="size-4"></i>
                                        Tunda — Tunggu Kelengkapan
                                    </button>
                                    <button @click="finalDecision('reject')"
                                        class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-white ring-1 ring-error text-error rounded-xl font-bold text-sm hover:bg-error/5 transition-colors cursor-pointer">
                                        <i data-lucide="circle-x" class="size-4"></i>
                                        Tolak & Notifikasi Peserta
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Modal footer nav -->
                <div class="flex items-center justify-between px-6 py-4 border-t border-border bg-muted/50 shrink-0">
                    <button @click="navPeserta(-1)" class="flex items-center gap-1.5 px-4 py-2 rounded-xl border border-border bg-white text-sm font-semibold text-secondary hover:bg-muted hover:text-foreground transition-colors cursor-pointer">
                        <i data-lucide="chevron-left" class="size-4"></i>
                        Sebelumnya
                    </button>
                    <span class="text-xs text-secondary">
                        <span class="font-semibold text-foreground" x-text="currentIndex + 1"></span>
                        dari
                        <span class="font-semibold text-foreground" x-text="filteredData.length || pesertaData.length"></span>
                        peserta
                    </span>
                    <button @click="navPeserta(1)" class="flex items-center gap-1.5 px-4 py-2 rounded-xl border border-border bg-white text-sm font-semibold text-secondary hover:bg-muted hover:text-foreground transition-colors cursor-pointer">
                        Berikutnya
                        <i data-lucide="chevron-right" class="size-4"></i>
                    </button>
                </div>

            </div>
        </div>

        <!-- Modal Preview Dokumen -->
        <div x-show="previewOpen" @click.self="previewOpen = false"
            x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-foreground/70 z-[300] flex items-center justify-center p-4" style="display:none">
            <div x-show="previewOpen"
                x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                class="bg-white rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-border">
                    <h3 class="font-bold text-foreground text-sm" x-text="`Preview — ${previewDocName}`"></h3>
                    <button @click="previewOpen = false" class="size-8 rounded-lg bg-muted hover:bg-border flex items-center justify-center cursor-pointer transition-colors">
                        <i data-lucide="x" class="size-4 text-secondary"></i>
                    </button>
                </div>
                <div class="p-6">
                    <div class="rounded-xl bg-muted border border-dashed border-border flex flex-col items-center justify-center gap-3 py-14">
                        <i data-lucide="file-text" class="size-12 text-primary"></i>
                        <p class="font-semibold text-foreground text-sm" x-text="previewDocName + '.pdf'"></p>
                        <p class="text-xs text-secondary">Preview akan tampil di sini</p>
                    </div>
                    <div class="flex gap-2 mt-4">
                        <button @click="previewOpen = false" class="flex-1 flex items-center justify-center gap-2 py-2.5 bg-success text-white rounded-xl font-bold text-sm cursor-pointer hover:bg-success-dark transition-colors">
                            <i data-lucide="check" class="size-4"></i>Approve
                        </button>
                        <button @click="previewOpen = false" class="flex-1 flex items-center justify-center gap-2 py-2.5 bg-white ring-1 ring-error text-error rounded-xl font-bold text-sm cursor-pointer hover:bg-error/5 transition-colors">
                            <i data-lucide="x" class="size-4"></i>Tolak
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- end table card -->

</div>

@push('scripts')
<script>
    function verifikasiApp() {
        return {
            search: '',
            filterStatus: 'all',
            filterJurusan: 'all',
            modalOpen: false,
            previewOpen: false,
            previewDocName: '',
            activePeserta: null,
            currentIndex: 0,

            pesertaData: [{
                    id: '2026-004821',
                    name: 'Ahmad Fauzi',
                    init: 'AF',
                    email: 'ahmad.fauzi@gmail.com',
                    jurusan: 'RPL',
                    status: 'pending',
                    statusLabel: 'Pending',
                    tanggal: '2 Jan 2026',
                    docsComplete: 4,
                    docsTotal: 6,
                    color: 'linear-gradient(135deg,#FF1443,#FF6B8A)'
                },
                {
                    id: '2026-004820',
                    name: 'Rini Kartika',
                    init: 'RK',
                    email: 'rini.kartika@gmail.com',
                    jurusan: 'TKJ',
                    status: 'rejected',
                    statusLabel: 'Ditolak',
                    tanggal: '3 Jan 2026',
                    docsComplete: 3,
                    docsTotal: 6,
                    color: 'linear-gradient(135deg,#3B82F6,#60A5FA)'
                },
                {
                    id: '2026-004819',
                    name: 'Budi Wijaya',
                    init: 'BW',
                    email: 'budi.wijaya@gmail.com',
                    jurusan: 'Akuntansi',
                    status: 'verified',
                    statusLabel: 'Terverifikasi',
                    tanggal: '3 Jan 2026',
                    docsComplete: 6,
                    docsTotal: 6,
                    color: 'linear-gradient(135deg,#30B22D,#4ADE80)'
                },
                {
                    id: '2026-004818',
                    name: 'Dina Pratiwi',
                    init: 'DP',
                    email: 'dina.pratiwi@gmail.com',
                    jurusan: 'Multimedia',
                    status: 'verified',
                    statusLabel: 'Terverifikasi',
                    tanggal: '4 Jan 2026',
                    docsComplete: 6,
                    docsTotal: 6,
                    color: 'linear-gradient(135deg,#F59E0B,#FCD34D)'
                },
                {
                    id: '2026-004817',
                    name: 'Hendra Saputra',
                    init: 'HS',
                    email: 'hendra.saputra@gmail.com',
                    jurusan: 'TKRO',
                    status: 'pending',
                    statusLabel: 'Pending',
                    tanggal: '5 Jan 2026',
                    docsComplete: 5,
                    docsTotal: 6,
                    color: 'linear-gradient(135deg,#8B5CF6,#A78BFA)'
                },
                {
                    id: '2026-004816',
                    name: 'Sari Indah Lestari',
                    init: 'SI',
                    email: 'sari.indah@gmail.com',
                    jurusan: 'RPL',
                    status: 'incomplete',
                    statusLabel: 'Kurang Dok.',
                    tanggal: '5 Jan 2026',
                    docsComplete: 2,
                    docsTotal: 6,
                    color: 'linear-gradient(135deg,#EC4899,#F9A8D4)'
                },
                {
                    id: '2026-004815',
                    name: 'Maulana Yusuf',
                    init: 'MY',
                    email: 'maulana.yusuf@gmail.com',
                    jurusan: 'TKJ',
                    status: 'pending',
                    statusLabel: 'Pending',
                    tanggal: '6 Jan 2026',
                    docsComplete: 4,
                    docsTotal: 6,
                    color: 'linear-gradient(135deg,#14B8A6,#5EEAD4)'
                },
                {
                    id: '2026-004814',
                    name: 'Fitri Handayani',
                    init: 'FH',
                    email: 'fitri.handa@gmail.com',
                    jurusan: 'Akuntansi',
                    status: 'verified',
                    statusLabel: 'Terverifikasi',
                    tanggal: '6 Jan 2026',
                    docsComplete: 6,
                    docsTotal: 6,
                    color: 'linear-gradient(135deg,#F97316,#FBA06A)'
                },
                {
                    id: '2026-004813',
                    name: 'Rizky Pratama',
                    init: 'RP',
                    email: 'rizky.pratama@gmail.com',
                    jurusan: 'TKRO',
                    status: 'pending',
                    statusLabel: 'Pending',
                    tanggal: '7 Jan 2026',
                    docsComplete: 3,
                    docsTotal: 6,
                    color: 'linear-gradient(135deg,#6366F1,#818CF8)'
                },
                {
                    id: '2026-004812',
                    name: 'Nadia Kusuma',
                    init: 'NK',
                    email: 'nadia.kusuma@gmail.com',
                    jurusan: 'Multimedia',
                    status: 'pending',
                    statusLabel: 'Pending',
                    tanggal: '7 Jan 2026',
                    docsComplete: 5,
                    docsTotal: 6,
                    color: 'linear-gradient(135deg,#D946EF,#E879F9)'
                },
            ],

            get filteredData() {
                return this.pesertaData.filter(p => {
                    const matchSearch = p.name.toLowerCase().includes(this.search.toLowerCase()) || p.id.includes(this.search);
                    const matchStatus = this.filterStatus === 'all' || p.status === this.filterStatus;
                    const matchJurusan = this.filterJurusan === 'all' || p.jurusan === this.filterJurusan;
                    return matchSearch && matchStatus && matchJurusan;
                });
            },

            openDetail(p) {
                this.activePeserta = p;
                this.currentIndex = this.filteredData.findIndex(x => x.id === p.id);
                this.modalOpen = true;
                this.$nextTick(() => {
                    if (window.lucide) lucide.createIcons();
                });
            },

            navPeserta(dir) {
                const list = this.filteredData.length > 0 ? this.filteredData : this.pesertaData;
                const newIdx = this.currentIndex + dir;
                if (newIdx >= 0 && newIdx < list.length) {
                    this.currentIndex = newIdx;
                    this.activePeserta = list[newIdx];
                    this.$nextTick(() => {
                        if (window.lucide) lucide.createIcons();
                    });
                }
            },

            previewDoc(name) {
                this.previewDocName = name;
                this.previewOpen = true;
                this.$nextTick(() => {
                    if (window.lucide) lucide.createIcons();
                });
            },

            finalDecision(type) {
                const msgs = {
                    approve: '✓ Semua dokumen disetujui! Peserta akan dinotifikasi.',
                    hold: '⏸ Status ditangguhkan. Pengingat dikirim ke peserta.',
                    reject: '✗ Dokumen ditolak. Peserta akan menerima notifikasi.'
                };
                const colors = {
                    approve: '#30B22D',
                    hold: '#F59E0B',
                    reject: '#ED6B60'
                };
                this.modalOpen = false;
                setTimeout(() => showToast(msgs[type], colors[type]), 200);
            }
        };
    }

    function showToast(msg, bg = '#080C1A') {
        const t = document.getElementById('toast');
        const tm = document.getElementById('toastMsg');
        t.style.background = bg;
        tm.textContent = msg;
        t.style.opacity = '1';
        t.style.transform = 'translateY(0)';
        setTimeout(() => {
            t.style.opacity = '0';
            t.style.transform = 'translateY(80px)';
        }, 2800);
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (window.lucideLoaded) window.initLucide();
        document.querySelectorAll('a[href="#"]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
            });
        });
    });
</script>
@endpush

@endsection
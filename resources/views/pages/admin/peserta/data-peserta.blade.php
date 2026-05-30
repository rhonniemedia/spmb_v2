@extends('layouts.admin')

@section('title', 'Data Peserta')
@section('page_title', 'Data Peserta')
@section('page_subtitle', 'Data Peserta')

@section('content')

<div class="flex-1 overflow-y-auto p-5 md:p-8 bg-white">

    {{-- ── Flash toast sukses dari store() ── --}}
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            showToast('{{ session("success") }}', '#30B22D');
        });
    </script>
    @endif

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-foreground text-2xl md:text-3xl font-bold mb-1">Daftar Peserta</h1>
            <p class="text-secondary text-sm">Kelola data peserta SPMB.</p>
        </div>
        <div class="grid grid-cols-2 gap-2 w-full md:w-auto md:flex md:items-center md:gap-3">
            <button
                onclick="document.getElementById('refresh-icon').classList.add('animate-spin'); window.location.reload();"
                class="flex items-center justify-center gap-2 px-4 py-3 ring-1 ring-border hover:ring-primary rounded-full text-foreground font-semibold text-sm transition-all duration-300 cursor-pointer bg-white">

                <i id="refresh-icon" data-lucide="refresh-cw" class="w-4 h-4"></i>

                <span>Segarkan</span>
            </button>

            <button
                @click="
                    $dispatch('open-modal', {
                        title:    'Tambah Pendaftar',
                        subtitle: 'Entri data dan verifikasi kelengkapan berkas fisik calon siswa',
                        size:     'lg',
                        step:     1,
                        steps: [
                            { label: 'Biodata',  icon: 'user' },
                            { label: 'Akademik', icon: 'bar-chart-2' },
                            { label: 'Jalur',    icon: 'trophy' },
                            { label: 'Jurusan',  icon: 'layout-grid' },
                            { label: 'Berkas',   icon: 'file-check' },
                            { label: 'Simpan',   icon: 'save' },
                        ]
                    });
                    setTimeout(() => {
                        htmx.ajax('GET', '{{ route('admin.pendaftar.create', ['step' => 1]) }}', {
                            target: '#modal-body',
                            swap:   'innerHTML'
                        });
                    }, 50);
                "
                class="flex items-center justify-center gap-2 px-4 py-3 bg-primary text-white rounded-full font-bold text-sm hover:bg-primary-hover transition-all duration-300 cursor-pointer">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Tambah Pendaftar
            </button>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="flex flex-col rounded-2xl border border-border p-5 gap-3 bg-white">
            <div class="flex items-center gap-2">
                <div class="size-10 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="users" class="size-5 text-primary"></i>
                </div>
                <p class="font-medium text-xs text-secondary">Total Peserta</p>
            </div>
            <p class="font-bold text-3xl">{{ number_format($peserta->total() ?? 0, 0, ',', '.') }}</p>
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

    {{-- ── PHP BLOCK ── --}}
    @php
    $colors = [
    'linear-gradient(135deg,#FF1443,#FF6B6B)',
    'linear-gradient(135deg,#3B82F6,#93C5FD)',
    'linear-gradient(135deg,#F59E0B,#FCD34D)',
    'linear-gradient(135deg,#8B5CF6,#A78BFA)'
    ];

    $mappedPeserta = [];
    foreach ($peserta as $index => $p) {
    $fullName = $p->personalData->full_name ?? 'Tanpa Nama';

    // Mengambil relasi dengan aman (fallback jika kosong)
    $gender = ($p->personalData->gender ?? '') === 'L' ? 'Laki-laki' : 'Perempuan';
    $nisn = $p->personalData->nisn ?? '-'; // Menggunakan accessor mutator NISN
    $jalur = $p->admissionPath->name ?? 'Jalur Reguler'; // Relasi ke admissionPath

    // Dummy Berkas (Bisa diganti dengan $p->documents jika tabelnya sudah siap)
    $berkasDiterima = [
    'Akta Kelahiran', 'Ijazah SMP / Sederajat', 'Surat Keterangan Lulus (SKL)',
    'Rapor Semester 1-5', 'Pas Foto 3x4', 'Surat Keterangan Domisili'
    ];

    $mappedPeserta[] = [
    'id' => $p->id,
    'reg_number' => $p->registration_number ?? '-',
    'name' => $fullName,
    'init' => strtoupper(substr($fullName, 0, 2)),
    'sekolah' => $p->personalData->previous_school ?? '-',
    'phone' => $p->personalData->phone_number ?? '-',

    // Menampilkan nama jurusan lengkap
    'jurusan1' => $p->choice1->alias ?? '-',
    'jurusan2' => $p->choice2->alias ?? '-',
    'jurusan3' => $p->choice3->alias ?? '-',

    'status' => $p->verification_status ?? 'pending',
    'statusLabel' => ucfirst($p->verification_status ?? 'pending'),
    'color' => $colors[$index % 4],

    // Data Baru Untuk Modal Detail
    'gender' => $gender,
    'nisn' => $nisn,
    'jalur' => $jalur,
    'rata_rapor' => number_format($p->report_average ?? 0, 2),
    'rata_tka' => number_format($p->tka_average ?? 0, 2),
    'berkas' => $berkasDiterima,
    ];
    }

    // ENCODE AMAN
    $pesertaJson = htmlspecialchars(json_encode($mappedPeserta), ENT_QUOTES, 'UTF-8');
    @endphp


    <div id="peserta-container"
        class="flex flex-col rounded-2xl border border-border p-6 bg-white gap-6"
        x-data="verifikasiApp()"
        data-peserta="{!! $pesertaJson !!}"
        x-init="loadData()">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-lg text-foreground">Daftar Peserta SPMB</h3>
                <p class="text-sm text-secondary">Kelola data peserta Sistem Penerimaan Murid Baru</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>
                    <input type="text" placeholder="Cari peserta..." x-model="search" class="pl-9 pr-9 py-2 rounded-xl border border-border bg-white text-sm focus:ring-1 focus:ring-primary outline-none w-[180px] transition-all" />
                    <button type="button" x-show="search.length > 0" @click="search = ''" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 transition-colors focus:outline-none cursor-pointer">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[860px] border-collapse">
                <thead class="border-b border-border">
                    <tr>
                        <th class="px-4 py-4 text-left text-sm font-bold text-foreground w-[30%]">
                            Peserta
                            <p class="font-normal text-xs text-secondary">Nama Peserta & Nomor Pendaftaran</p>
                        </th>
                        <th class="px-4 py-4 text-left text-sm font-bold text-foreground w-[30%]">
                            Sekolah
                            <p class="font-normal text-xs text-secondary">Asal & Kontak</p>
                        </th>
                        <th class="px-4 py-4 text-left text-sm font-bold text-foreground w-[30%]">
                            Konsentrasi Keahlian
                            <p class="font-normal text-xs text-secondary">Jurusan Pilihan</p>
                        </th>
                        <th class="px-4 py-4 text-left text-sm font-bold text-foreground w-[10%]">
                            Aksi
                            <p class="font-normal text-xs text-secondary">Detail & Edit</p>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border bg-white">
                    <template x-for="p in filteredData" :key="p.id">
                        <tr class="border-b border-border hover:bg-muted/50 transition-colors">

                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0" :style="`background: ${p.color}`" x-text="p.init"></div>
                                    <div>
                                        <div class="font-semibold text-foreground text-sm uppercase" x-text="p.name"></div>
                                        <div class="text-xs text-secondary font-mono" x-text="p.reg_number"></div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-4">
                                <div class="text-sm font-medium text-foreground uppercase" x-text="p.sekolah"></div>
                                <a :href="`https://wa.me/62${p.phone.replace(/^0/, '')}`" target="_blank" x-show="p.phone !== '-'" class="mt-1 inline-flex items-center gap-x-1.5 text-xs text-secondary hover:text-green-600 hover:underline transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-3 h-3 fill-current">
                                        <path d="M164.9 24.6c-7.7-18.6-28-28.5-47.4-23.2l-88 24C12.1 30.2 0 46 0 64C0 311.4 200.6 512 448 512c18 0 33.8-12.1 38.6-29.5l24-88c5.3-19.4-4.6-39.7-23.2-47.4l-96-40c-16.3-6.8-35.2-2.1-46.3 11.6L304.7 368C234.3 334.7 177.3 277.7 144 207.3L193.3 167c13.7-11.2 18.4-30 11.6-46.3l-40-96z" />
                                    </svg>
                                    <span x-text="p.phone"></span>
                                </a>
                            </td>

                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <span x-show="p.jurusan1" class="inline-flex items-center justify-center gap-1 w-[60px] py-1 rounded-md text-xs font-bold border bg-red-100 text-red-700 border-red-200">
                                        <span class="font-normal opacity-75">1.</span>
                                        <span class="truncate" x-text="p.jurusan1"></span>
                                    </span>
                                    <span x-show="p.jurusan2" class="inline-flex items-center justify-center gap-1 w-[60px] py-1 rounded-md text-xs font-bold border bg-yellow-100 text-yellow-800 border-yellow-300">
                                        <span class="font-normal opacity-75">2.</span>
                                        <span class="truncate" x-text="p.jurusan2"></span>
                                    </span>
                                    <span x-show="p.jurusan3" class="inline-flex items-center justify-center gap-1 w-[60px] py-1 rounded-md text-xs font-bold border bg-gray-700 text-white border-gray-800">
                                        <span class="font-normal opacity-75">3.</span>
                                        <span class="truncate" x-text="p.jurusan3"></span>
                                    </span>
                                </div>
                            </td>

                            <td class="px-4 py-4 text-left">
                                <div class="flex items-center gap-2">
                                    <button @click="openDetail(p)" title="Lihat Detail" class="flex items-center justify-center p-2 rounded-lg bg-primary/10 hover:bg-primary/20 text-primary transition-colors cursor-pointer">
                                        <i data-lucide="eye" class="size-4"></i>
                                    </button>
                                    <button @click="editData(p)" title="Edit Data" class="flex items-center justify-center p-2 rounded-lg bg-amber-100 hover:bg-amber-200 text-amber-700 transition-colors cursor-pointer">
                                        <i data-lucide="edit" class="size-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>

                    <tr x-show="filteredData.length === 0" style="display: none;">
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

        <div class="flex flex-col md:flex-row items-center justify-between gap-4 pt-4">

            <span class="text-sm text-secondary text-center">
                Menampilkan <span class="font-semibold text-foreground">{{ $peserta->firstItem() ?? 0 }}</span>
                sampai <span class="font-semibold text-foreground">{{ $peserta->lastItem() ?? 0 }}</span>
                dari <span class="font-semibold text-foreground">{{ number_format($peserta->total() ?? 0, 0, ',', '.') }}</span> peserta
            </span>

            <div class="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0">

                {{-- Tombol Previous --}}
                @if ($peserta->onFirstPage())
                <button class="p-2 rounded-lg border border-border bg-white hover:bg-muted cursor-not-allowed opacity-50 transition-colors" disabled>
                    <i data-lucide="chevron-left" class="size-4"></i>
                </button>
                @else
                <button type="button" hx-get="{{ $peserta->previousPageUrl() }}" hx-target="#peserta-container" hx-select="#peserta-container" hx-swap="outerHTML" hx-push-url="true" class="p-2 rounded-lg border border-border bg-white hover:bg-muted cursor-pointer transition-colors">
                    <i data-lucide="chevron-left" class="size-4"></i>
                </button>
                @endif

                {{-- Custom Compact Pagination (Memaksa format: 1 ... 4 5 6 ... 10) --}}
                @php
                $curr = $peserta->currentPage();
                $last = $peserta->lastPage();

                // Ambil angka 1, {sebelum}, {sekarang}, {sesudah}, dan {terakhir}
                $pages = collect([1, $curr - 1, $curr, $curr + 1, $last])
                ->filter(fn($p) => $p >= 1 && $p <= $last)
                    ->unique()
                    ->sort()
                    ->values()
                    ->toArray();

                    $elements = [];
                    for ($i = 0; $i < count($pages); $i++) {
                        $elements[]=$pages[$i];
                        // Sisipkan elipsis (...) jika jarak antar halaman melompat lebih dari 1 angka
                        if (isset($pages[$i + 1]) && $pages[$i + 1] - $pages[$i]> 1) {
                        $elements[] = '...';
                        }
                        }
                        @endphp

                        @foreach ($elements as $el)
                        @if ($el === '...')
                        <span class="px-3 py-1.5 rounded-lg text-sm text-secondary cursor-default">...</span>
                        @elseif ($el == $curr)
                        <button class="px-3 py-1.5 rounded-lg bg-primary text-white text-sm font-bold cursor-default">{{ $el }}</button>
                        @else
                        <button type="button" hx-get="{{ $peserta->url($el) }}" hx-target="#peserta-container" hx-select="#peserta-container" hx-swap="outerHTML" hx-push-url="true" class="px-3 py-1.5 rounded-lg border border-border text-sm text-secondary hover:bg-muted cursor-pointer transition-colors">{{ $el }}</button>
                        @endif
                        @endforeach

                        {{-- Tombol Next --}}
                        @if ($peserta->hasMorePages())
                        <button type="button" hx-get="{{ $peserta->nextPageUrl() }}" hx-target="#peserta-container" hx-select="#peserta-container" hx-swap="outerHTML" hx-push-url="true" class="p-2 rounded-lg border border-border bg-white hover:bg-muted cursor-pointer transition-colors">
                            <i data-lucide="chevron-right" class="size-4"></i>
                        </button>
                        @else
                        <button class="p-2 rounded-lg border border-border bg-white hover:bg-muted cursor-not-allowed opacity-50 transition-colors" disabled>
                            <i data-lucide="chevron-right" class="size-4"></i>
                        </button>
                        @endif

            </div>
        </div>

        {{-- ── Modal Detail Peserta ── --}}
        <div x-show="modalOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-foreground/60 z-[200] flex items-center justify-center p-4" style="display:none" @click.self="modalOpen = false">

            <div x-show="modalOpen"
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="bg-white rounded-2xl w-full max-w-3xl max-h-[90vh] flex flex-col overflow-hidden shadow-2xl">

                {{-- Header (asli) --}}
                <div class="flex items-center justify-between px-6 py-5 border-b border-border shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0"
                            :style="`background: ${activePeserta?.color}`" x-text="activePeserta?.init"></div>
                        <div>
                            <h3 class="font-bold text-foreground" x-text="activePeserta?.name"></h3>
                            <p class="text-xs text-secondary font-mono" x-text="`${activePeserta?.reg_number}`"></p>
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
                        <button @click="modalOpen = false" class="size-8 rounded-lg border border-border flex items-center justify-center hover:bg-muted transition-colors cursor-pointer">
                            <i data-lucide="x" class="size-4 text-secondary"></i>
                        </button>
                    </div>
                </div>

                {{-- Body --}}
                <div class="flex-1 overflow-y-auto scrollbar-hide p-6 md:p-8">

                    {{-- Chip: Jalur & Jurusan Utama --}}
                    <div class="flex items-center gap-2 flex-wrap mb-6">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium border"
                            style="background:#EEEDFE;color:#3C3489;border-color:#AFA9EC">
                            <i data-lucide="route" class="size-3"></i>
                            <span x-text="activePeserta?.jalur"></span>
                        </span>
                        <template x-if="activePeserta?.jurusan1 && activePeserta?.jurusan1 !== '-'">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium border"
                                style="background:#EEEDFE;color:#3C3489;border-color:#AFA9EC">
                                <i data-lucide="monitor" class="size-3"></i>
                                <span x-text="activePeserta?.jurusan1"></span>
                            </span>
                        </template>
                    </div>

                    {{-- Grup: Data Diri --}}
                    <div class="mb-6">
                        <div class="flex items-center gap-2 mb-3">
                            <i data-lucide="user" class="size-3 text-secondary shrink-0"></i>
                            <span class="text-[10px] font-medium tracking-[0.08em] uppercase text-secondary">Data diri</span>
                            <div class="flex-1 h-px bg-border"></div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-xl px-3 py-2.5 bg-muted/50">
                                <p class="text-[11px] text-secondary mb-1">Jenis Kelamin</p>
                                <p class="text-sm font-medium text-foreground" x-text="activePeserta?.gender"></p>
                            </div>
                            <div class="rounded-xl px-3 py-2.5 bg-muted/50">
                                <p class="text-[11px] text-secondary mb-1">NISN</p>
                                <p class="text-sm font-medium text-foreground font-mono" x-text="activePeserta?.nisn"></p>
                            </div>
                            <div class="rounded-xl px-3 py-2.5 bg-muted/50">
                                <p class="text-[11px] text-secondary mb-1">Sekolah Asal</p>
                                <p class="text-sm font-medium text-foreground" x-text="activePeserta?.sekolah"></p>
                            </div>
                            <div class="rounded-xl px-3 py-2.5 bg-muted/50">
                                <p class="text-[11px] text-secondary mb-1">WhatsApp</p>
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-medium text-foreground font-mono" x-text="`+62${activePeserta?.phone?.replace(/^0/, '')}`"></p>
                                    <a :href="`https://wa.me/62${activePeserta?.phone?.replace(/^0/, '')}`"
                                        target="_blank"
                                        x-show="activePeserta?.phone !== '-'"
                                        class="inline-flex items-center justify-center size-5 rounded-md hover:opacity-80 transition-opacity shrink-0"
                                        style="background:#dcfce7;color:#16a34a"
                                        title="Chat WhatsApp">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-2.5 h-2.5 fill-current">
                                            <path d="M164.9 24.6c-7.7-18.6-28-28.5-47.4-23.2l-88 24C12.1 30.2 0 46 0 64C0 311.4 200.6 512 448 512c18 0 33.8-12.1 38.6-29.5l24-88c5.3-19.4-4.6-39.7-23.2-47.4l-96-40c-16.3-6.8-35.2-2.1-46.3 11.6L304.7 368C234.3 334.7 177.3 277.7 144 207.3L193.3 167c13.7-11.2 18.4-30 11.6-46.3l-40-96z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Grup: Pilihan Jurusan --}}
                    <div class="mb-6">
                        <div class="flex items-center gap-2 mb-3">
                            <i data-lucide="layout-grid" class="size-3 text-secondary shrink-0"></i>
                            <span class="text-[10px] font-medium tracking-[0.08em] uppercase text-secondary">Pilihan jurusan</span>
                            <div class="flex-1 h-px bg-border"></div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-xl px-3 py-2.5 bg-muted/50 col-span-2">
                                <p class="text-[11px] text-secondary mb-1">Pilihan 1</p>
                                <p class="text-sm font-medium"
                                    :class="activePeserta?.jurusan1 === '-' ? 'text-secondary italic' : 'text-foreground'"
                                    x-text="activePeserta?.jurusan1 === '-' ? 'Tidak memilih' : activePeserta?.jurusan1"></p>
                            </div>
                            <div class="rounded-xl px-3 py-2.5 bg-muted/50">
                                <p class="text-[11px] text-secondary mb-1">Pilihan 2</p>
                                <p class="text-sm font-medium text-foreground" x-text="activePeserta?.jurusan2 === '-' ? '—' : activePeserta?.jurusan2"></p>
                            </div>
                            <div class="rounded-xl px-3 py-2.5 bg-muted/50">
                                <p class="text-[11px] text-secondary mb-1">Pilihan 3</p>
                                <p class="text-sm font-medium"
                                    :class="activePeserta?.jurusan3 === '-' ? 'text-secondary italic' : 'text-foreground'"
                                    x-text="activePeserta?.jurusan3 === '-' ? 'Tidak memilih' : activePeserta?.jurusan3"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Grup: Nilai --}}
                    <div class="mb-6">
                        <div class="flex items-center gap-2 mb-3">
                            <i data-lucide="bar-chart-2" class="size-3 text-secondary shrink-0"></i>
                            <span class="text-[10px] font-medium tracking-[0.08em] uppercase text-secondary">Nilai</span>
                            <div class="flex-1 h-px bg-border"></div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-xl px-4 py-3" style="background:#EEEDFE;border:0.5px solid #AFA9EC">
                                <p class="text-[22px] font-medium font-mono" style="color:#3C3489" x-text="activePeserta?.rata_rapor"></p>
                                <p class="text-[11px] mt-0.5" style="color:#534AB7">Rata-rata Rapor</p>
                            </div>
                            <div class="rounded-xl px-4 py-3" style="background:#E1F5EE;border:0.5px solid #9FE1CB">
                                <p class="text-[22px] font-medium font-mono" style="color:#085041" x-text="activePeserta?.rata_tka"></p>
                                <p class="text-[11px] mt-0.5" style="color:#0F6E56">Rata-rata TKA</p>
                            </div>
                        </div>
                    </div>


                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <i data-lucide="file-check" class="size-3 text-secondary shrink-0"></i>
                            <span class="text-[10px] font-medium tracking-[0.08em] uppercase text-secondary">Berkas fisik diterima</span>
                            <div class="flex-1 h-px bg-border"></div>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="berkas in activePeserta?.berkas" :key="berkas">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium"
                                    style="background:#EAF3DE;color:#27500A;border:0.5px solid #C0DD97">
                                    <i data-lucide="check" class="size-3" style="color:#3B6D11"></i>
                                    <span x-text="berkas"></span>
                                </span>
                            </template>
                            <template x-if="!activePeserta?.berkas || activePeserta?.berkas.length === 0">
                                <span class="text-xs text-secondary italic">Belum ada berkas yang diverifikasi</span>
                            </template>
                        </div>
                    </div>

                </div>

                {{-- Footer (asli) dengan counter --}}
                <div class="flex items-center justify-between gap-3 px-6 py-4 border-t border-border bg-white shrink-0">
                    <p class="text-xs text-secondary">
                        <span class="font-semibold text-foreground" x-text="activePeserta?.berkas?.length ?? 0"></span>
                        dari <span class="font-semibold text-foreground">6</span> berkas diterima
                    </p>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="modalOpen = false"
                            class="px-5 py-2.5 rounded-xl border border-border text-sm font-bold text-secondary hover:bg-muted hover:text-foreground transition-colors cursor-pointer shadow-sm">
                            Tutup
                        </button>
                        <a :href="`/admin/data/${activePeserta?.id}/cetak`" target="_blank"
                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary-hover shadow-sm transition-colors cursor-pointer">
                            <i data-lucide="printer" class="size-4"></i>
                            Cetak Bukti Daftar
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

</div>

</div>

@endsection

@push('scripts')
<script>
    function verifikasiApp() {
        return {
            search: '',
            filterStatus: 'all',
            modalOpen: false,
            activePeserta: null,
            pesertaData: [],

            // Fungsi ini otomatis dipanggil oleh x-init setiap kali HTMX melakukan render ulang tabel
            loadData() {
                // Ambil data JSON murni dari atribut data-peserta milik elemen ini ($el)
                if (this.$el.dataset.peserta) {
                    this.pesertaData = JSON.parse(this.$el.dataset.peserta);
                }

                // Minta Lucide untuk merender ulang ikon (berguna setelah navigasi pagination)
                this.$nextTick(() => {
                    if (window.lucide) lucide.createIcons();
                });
            },

            // Fitur pencarian aman, meminimalisir error huruf besar/kecil dan nilai null
            get filteredData() {
                return this.pesertaData.filter(p => {
                    const s = this.search.toLowerCase();
                    const name = p.name ? p.name.toLowerCase() : '';
                    const reg = p.reg_number ? p.reg_number.toLowerCase() : '';

                    const matchSearch = name.includes(s) || reg.includes(s);
                    const matchStatus = this.filterStatus === 'all' || p.status === this.filterStatus;

                    return matchSearch && matchStatus;
                });
            },

            openDetail(p) {
                this.activePeserta = p;
                this.modalOpen = true;
                this.$nextTick(() => {
                    if (window.lucide) lucide.createIcons();
                });
            }, // <--- Pastikan ada koma di sini

            // ─── FUNGSI EDIT DITAMBAHKAN DI SINI ───
            editData(p) {
                // Buka modal dengan konfigurasi yang sama persis seperti Create
                const url = "{{ route('admin.pendaftar.edit', ['id' => 'PLACEHOLDER']) }}".replace('PLACEHOLDER', p.id) + '?step=1';

                this.$dispatch('open-modal', {
                    title: 'Edit Data Pendaftar',
                    subtitle: 'Ubah entri biodata dan status verifikasi berkas pendaftar',
                    size: 'lg',
                    step: 1,
                    steps: [{
                            label: 'Biodata',
                            icon: 'user'
                        },
                        {
                            label: 'Akademik',
                            icon: 'bar-chart-2'
                        },
                        {
                            label: 'Jalur',
                            icon: 'trophy'
                        },
                        {
                            label: 'Jurusan',
                            icon: 'layout-grid'
                        },
                        {
                            label: 'Berkas',
                            icon: 'file-check'
                        },
                        {
                            label: 'Simpan',
                            icon: 'save'
                        },
                    ]
                });

                // Minta HTMX untuk mengambil form Edit (Step 1)
                setTimeout(() => {
                    htmx.ajax('GET', url, {
                        target: '#modal-body',
                        swap: 'innerHTML'
                    });
                }, 50);
            }
            // ───────────────────────────────────────
        };
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) lucide.createIcons();
    });
</script>
@endpush
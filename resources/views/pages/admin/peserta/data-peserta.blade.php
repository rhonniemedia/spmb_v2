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

            @canany(['superadmin', 'admin', 'verifikator'])
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
            @endcanany
        </div>
    </div>

    {{-- ── Statistik Cards ── --}}
    @include('pages.admin.peserta.partials._stats-cards')

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
    $nisn = $p->personalData->nisn ?? '-';
    $jalur = $p->admissionPath->name ?? 'Jalur Reguler';

    // Dummy Berkas (Bisa diganti dengan $p->documents jika tabelnya sudah siap)
    $berkasDiterima = [
    'Akta Kelahiran', 'Ijazah SMP / Sederajat', 'Surat Keterangan Lulus (SKL)',
    'Rapor Semester 1-5', 'Pas Foto 3x4', 'Surat Keterangan Domisili'
    ];

    // ── MENCARI NAMA ADMIN YANG MEMVERIFIKASI & MENGUBAH ──
    $verifierName = 'Sistem / Belum';
    if ($p->verified_by) {
    $verifier = \App\Models\User::find($p->verified_by);
    if ($verifier) $verifierName = $verifier->name;
    }

    $updaterName = '-';
    if ($p->updated_by) {
    $updater = \App\Models\User::find($p->updated_by);
    if ($updater) $updaterName = $updater->name;
    }
    // ──────────────────────────────────────────────────────

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

    // ── DATA LOG SISTEM UNTUK MODAL (BARU) ──
    'verified_by' => $verifierName,
    'verified_at' => $p->created_at ? $p->created_at->translatedFormat('d M Y, H:i') : '-',
    'updated_by' => $updaterName,
    'updated_at' => ($p->updated_at && $p->updated_at != $p->created_at) ? $p->updated_at->translatedFormat('d M Y, H:i') : '-',
    ];
    }

    // ENCODE AMAN
    $pesertaJson = htmlspecialchars(json_encode($mappedPeserta), ENT_QUOTES, 'UTF-8');
    @endphp


    <div id="peserta-container"
        class="flex flex-col rounded-2xl border border-border p-6 bg-white gap-6"
        x-data="verifikasiApp()"
        data-peserta="{!! $pesertaJson !!}"
        x-on:show-success-registration.window="openSuccessModal($event.detail)"
        x-on:refresh-table.window="refreshTable()"
        x-init="loadData()">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-lg text-foreground">Daftar Peserta SPMB</h3>
                <p class="text-sm text-secondary">Kelola data peserta Sistem Penerimaan Murid Baru</p>
            </div>
            {{-- FITUR PENCARIAN LIVE SEARCH HTMX --}}
            <div class="flex items-center gap-2 flex-wrap">
                <form action="{{ url()->current() }}" method="GET" class="relative">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>

                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari peserta..."
                        hx-get="{{ url()->current() }}"
                        hx-include="closest form"
                        hx-trigger="keyup changed delay:500ms, search"
                        hx-target="#peserta-container"
                        hx-select="#peserta-container"
                        hx-swap="outerHTML"
                        hx-push-url="true"
                        class="pl-9 pr-9 py-2 rounded-xl border border-border bg-white text-sm focus:ring-1 focus:ring-primary outline-none w-[180px] md:w-[220px] transition-all" />

                    @if(request('search'))
                    <button type="button"
                        hx-get="{{ request()->fullUrlWithQuery(['search' => null]) }}"
                        hx-target="#peserta-container"
                        hx-select="#peserta-container"
                        hx-swap="outerHTML"
                        hx-push-url="true"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 transition-colors focus:outline-none cursor-pointer">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                    @endif
                </form>
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
                                <a :href="'https://wa.me/' + p.phone.replace(/\D/g, '').replace(/^0/, '62')" target="_blank" x-show="p.phone !== '-'" class="mt-1 inline-flex items-center gap-x-1.5 text-xs text-secondary hover:text-green-600 hover:underline transition-colors">
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
                                    @canany(['superadmin', 'admin', 'verifikator'])
                                    <button @click="editData(p)" title="Edit Data" class="flex items-center justify-center p-2 rounded-lg bg-amber-100 hover:bg-amber-200 text-amber-700 transition-colors cursor-pointer">
                                        <i data-lucide="edit" class="size-4"></i>
                                    </button>
                                    @endcanany
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
        @include ('pages.admin.peserta.partials._detail-modal')

        {{-- ── Animasi Khusus Modal SweetAlert ── --}}
        <style>
            @keyframes sa-scale-in {
                0% {
                    opacity: 0;
                    transform: scale(0.85) translateY(12px);
                }

                100% {
                    opacity: 1;
                    transform: scale(1) translateY(0);
                }
            }

            @keyframes sa-ripple {
                0% {
                    transform: scale(0);
                    opacity: 0.6;
                }

                100% {
                    transform: scale(2.5);
                    opacity: 0;
                }
            }

            @keyframes sa-bounce-icon {

                0%,
                100% {
                    transform: scale(1);
                }

                50% {
                    transform: scale(1.15);
                }
            }

            @keyframes sa-checkmark {
                0% {
                    stroke-dashoffset: 50;
                }

                100% {
                    stroke-dashoffset: 0;
                }
            }

            .animate-sa-scale-in {
                animation: sa-scale-in 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
            }

            .animate-sa-ripple {
                animation: sa-ripple 0.6s ease-out forwards;
            }

            .animate-sa-bounce-icon {
                animation: sa-bounce-icon 0.6s ease forwards;
            }

            .sa-stroke-dash {
                stroke-dasharray: 50;
                stroke-dashoffset: 50;
                animation: sa-checkmark 0.5s ease 0.25s forwards;
            }
        </style>

        {{-- ── Modal Sukses Custom (Tema SweetAlert Hijau) ── --}}
        <div x-show="successModalOpen" x-cloak
            class="fixed inset-0 z-[300] flex items-center justify-center p-4 sm:p-0"
            style="background: rgba(0,0,0,0.45); backdrop-filter: blur(6px); display:none;"
            x-transition:enter="transition-opacity duration-250 ease-out"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity duration-200"
            x-transition:leave-end="opacity-0"
            @click.self="closeSuccessModal()">

            <div x-show="successModalOpen"
                x-transition:enter="animate-sa-scale-in"
                x-transition:leave="transition-all duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative w-full max-w-[400px] shadow-2xl overflow-hidden"
                style="border-radius: 2rem; background: radial-gradient(ellipse 75% 65% at 110% -10%, rgba(110,231,183,0.8) 0%, rgba(167,243,208,0.33) 45%, transparent 70%), radial-gradient(ellipse 50% 45% at -10% 110%, rgba(167,243,208,0.4) 0%, transparent 65%), #ffffff;">

                <div class="relative z-10 px-8 pt-10 pb-4 text-center">

                    <div class="flex justify-center mb-6">
                        <div class="relative">
                            <div class="absolute inset-0 animate-sa-ripple" style="border-radius:9999px; background: #6ee7b7; opacity: 0.25"></div>
                            <div class="relative w-20 h-20 flex items-center justify-center animate-sa-bounce-icon" style="border-radius:9999px; background: linear-gradient(135deg, #34d399, #059669); box-shadow: 0 8px 24px rgba(5,150,105,0.28)">
                                <svg width="36" height="36" viewBox="0 0 36 36" fill="none">
                                    <path class="sa-stroke-dash" d="M8 18 L15 25 L28 11" stroke="white" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <h2 class="text-[22px] font-bold mb-2 leading-tight text-gray-800 tracking-tight">Data Disimpan!</h2>

                    <p class="text-[14px] leading-relaxed text-gray-500 mb-6 px-2">
                        Data peserta <strong class="text-gray-800" x-text="successData.full_name"></strong> dengan nomor registrasi
                        <strong class="text-gray-800 font-mono" x-text="successData.reg_number"></strong> berhasil disimpan.
                    </p>
                </div>

                <div class="relative z-10 px-8 pb-10 flex flex-col gap-3">
                    <button @click="cetakBukti()"
                        class="w-full py-3.5 px-5 text-[15px] font-bold transition-all duration-200 active:scale-95 flex items-center justify-center gap-2 text-white hover:opacity-90 cursor-pointer"
                        style="border-radius:999px; background: linear-gradient(135deg, #34d399, #059669); box-shadow: 0 8px 20px rgba(5,150,105,0.28)">
                        <i data-lucide="printer" class="size-[18px]"></i>
                        <span>Cetak Bukti Daftar</span>
                    </button>

                    <button @click="closeSuccessModal()"
                        class="w-full py-3.5 px-5 text-[15px] font-bold transition-all duration-200 active:scale-95 border border-gray-200 text-gray-500 hover:bg-gray-50 hover:text-gray-800 cursor-pointer"
                        style="border-radius:999px">
                        <span>OK, Tutup</span>
                    </button>
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

            successModalOpen: false,
            successData: {},

            openSuccessModal(data) {
                this.successData = data;
                this.successModalOpen = true;
                this.$nextTick(() => {
                    if (window.lucide) lucide.createIcons();
                });
            },

            // Tambahkan fungsi baru ini
            refreshTable() {
                htmx.ajax('GET', window.location.href, {
                    target: '#peserta-container',
                    select: '#peserta-container',
                    swap: 'outerHTML'
                });
            },

            closeSuccessModal() {
                this.successModalOpen = false;

                // Panggil refresh setelah modal sukses ditutup
                this.refreshTable();
            },

            cetakBukti() {
                // Buka link cetak di tab baru menggunakan ID dari DB
                window.open(`/admin/data/${this.successData.id}/cetak`, '_blank');
                // Langsung tutup modal suksesnya
                this.refreshTable();
                this.closeSuccessModal();
            },

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
@extends('layouts.admin')

@section('title', 'Data Observasi')
@section('page_title', 'Data Observasi')
@section('page_subtitle', 'Data Observasi Peserta')

@section('content')

<div class="flex-1 overflow-y-auto p-5 md:p-8 bg-white">

    {{-- ── Flash toast sukses ── --}}
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            showToast('{{ session("success") }}', '#30B22D');
        });
    </script>
    @endif

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-foreground text-2xl md:text-3xl font-bold mb-1">Observasi Peserta</h1>
            <p class="text-secondary text-sm">Kelola data observasi peserta SPMB.</p>
        </div>
        <div class="grid grid-cols-2 gap-2 w-full md:w-auto md:flex md:items-center md:gap-3">
            <button
                onclick="document.getElementById('refresh-icon').classList.add('animate-spin'); window.location.reload();"
                class="flex items-center justify-center gap-2 px-4 py-3 ring-1 ring-border hover:ring-primary rounded-full text-foreground font-semibold text-sm transition-all duration-300 cursor-pointer bg-white">
                <i id="refresh-icon" data-lucide="refresh-cw" class="w-4 h-4"></i>
                <span>Segarkan</span>
            </button>
        </div>
    </div>

    {{-- ── Statistik Cards ── --}}
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

    {{-- ── PHP BLOCK mapping data ── --}}
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
    $gender = ($p->personalData->gender ?? '') === 'L' ? 'Laki-laki' : 'Perempuan';
    $nisn = $p->personalData->nisn ?? '-';
    $jalur = $p->admissionPath->name ?? 'Jalur Reguler';

    $berkasDiterima = [
    'Akta Kelahiran', 'Ijazah SMP / Sederajat', 'Surat Keterangan Lulus (SKL)',
    'Rapor Semester 1-5', 'Pas Foto 3x4', 'Surat Keterangan Domisili'
    ];

    $hasObservation = $p->observationData !== null;
    $achievements = $p->achievements ?? collect();
    $hasAchievement = $achievements->isNotEmpty();
    $obsData = $p->observationData;

    $mappedPeserta[] = [
    'id' => $p->id,
    'reg_number' => $p->registration_number ?? '-',
    'name' => $fullName,
    'init' => strtoupper(substr($fullName, 0, 2)),
    'sekolah' => $p->personalData->previous_school ?? '-',
    'phone' => $p->personalData->phone_number ?? '-',
    'jurusan1' => $p->choice1->alias ?? '-',
    'jurusan2' => $p->choice2->alias ?? '-',
    'jurusan3' => $p->choice3->alias ?? '-',
    'status' => $p->verification_status ?? 'pending',
    'statusLabel' => ucfirst($p->verification_status ?? 'pending'),
    'color' => $colors[$index % 4],
    'gender' => $gender,
    'nisn' => $nisn,
    'jalur' => $jalur,
    'rata_rapor' => number_format($p->report_average ?? 0, 2),
    'rata_tka' => number_format($p->tka_average ?? 0, 2),
    'berkas' => $berkasDiterima,

    'has_observation' => $hasObservation,
    'has_achievement' => $hasAchievement,
    'obs_status' => $obsData?->observation_status ?? 'pending',

    'achievements' => $achievements->map(function ($a) {
    $typeLabel = match($a->achievement_type) {
    'kejuaraan' => 'Kejuaraan',
    'tahfiz' => 'Tahfiz',
    'kepemimpinan' => 'Kepemimpinan',
    'peringkat' => 'Peringkat Kelas',
    default => ucfirst($a->achievement_type),
    };
    $levelLabel = match($a->level ?? '') {
    'internasional' => 'Internasional',
    'nasional' => 'Nasional',
    'provinsi' => 'Provinsi',
    'kabupaten' => 'Kabupaten/Kota',
    default => null,
    };
    $ranksFormatted = null;
    if ($a->achievement_type === 'peringkat' && !empty($a->class_ranks)) {
    $ranks = is_string($a->class_ranks) ? json_decode($a->class_ranks, true) : (array) $a->class_ranks;
    $parts = [];
    foreach ($ranks as $sem => $rank) {
    if ($rank !== '' && $rank !== null) {
    $parts[] = "Sem {$sem}: Peringkat {$rank}";
    }
    }
    $ranksFormatted = implode(' · ', $parts);
    }
    return [
    'type' => $a->achievement_type,
    'type_label' => $typeLabel,
    'level' => $a->level,
    'level_label'=> $levelLabel,
    'position' => $a->leadership_position,
    'ranks' => $ranksFormatted,
    ];
    })->values()->toArray(),

    'hearing_check' => $obsData?->hearing_check ?? 'no',
    'hearing_score' => $obsData?->hearing_score ?? 0,
    'vision_check' => $obsData?->vision_check ?? 'no',
    'vision_score' => $obsData?->vision_score ?? 0,
    'color_blind_check' => $obsData?->color_blind_check ?? 'no',
    'color_blind_score' => $obsData?->color_blind_score ?? 0,
    'physical_activity' => $obsData?->physical_activity ?? 'no',
    'physical_activity_score'=> $obsData?->physical_activity_score ?? 0,

    'tattoo' => $obsData?->tattoo ?? 'no',
    'tattoo_score' => $obsData?->tattoo_score ?? 0,
    'tattoo_scar' => $obsData?->tattoo_scar ?? 'no',
    'tattoo_scar_score' => $obsData?->tattoo_scar_score ?? 0,
    'piercing' => $obsData?->piercing ?? 'no',
    'piercing_score' => $obsData?->piercing_score ?? 0,
    'keloid' => $obsData?->keloid ?? 'no',
    'keloid_score' => $obsData?->keloid_score ?? 0,
    'minor_disability' => $obsData?->minor_disability ?? 'no',
    'minor_disability_score'=> $obsData?->minor_disability_score ?? 0,
    'aid_tool' => $obsData?->aid_tool ?? 'no',
    'aid_tool_score' => $obsData?->aid_tool_score ?? 0,

    'total_score' => $obsData?->total_score ?? 0,
    'observation_notes' => $obsData?->observation_notes ?? '',
    ];
    }

    $pesertaJson = htmlspecialchars(json_encode($mappedPeserta), ENT_QUOTES, 'UTF-8');
    @endphp

    {{-- ═══════════════════════════════════════════
         TABEL PESERTA
    ════════════════════════════════════════════ --}}
    <div id="peserta-container"
        class="flex flex-col rounded-2xl border border-border p-6 bg-white gap-6"
        x-data="observasiApp()"
        data-peserta="{!! $pesertaJson !!}"
        x-init="loadData()">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-lg text-foreground">Daftar Observasi Peserta SPMB</h3>
                <p class="text-sm text-secondary">Kelola data peserta Observasi Sistem Penerimaan Murid Baru</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>
                    <input type="text" placeholder="Cari peserta..." x-model="search"
                        class="pl-9 pr-9 py-2 rounded-xl border border-border bg-white text-sm focus:ring-1 focus:ring-primary outline-none w-[180px] transition-all" />
                    <button type="button" x-show="search.length > 0" @click="search = ''"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 transition-colors focus:outline-none cursor-pointer">
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
                            <p class="font-normal text-xs text-secondary">Detail & Observasi</p>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border bg-white">
                    <template x-for="p in filteredData" :key="p.id">
                        <tr class="border-b border-border hover:bg-muted/50 transition-colors">

                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0"
                                        :style="`background: ${p.color}`" x-text="p.init"></div>
                                    <div>
                                        <div class="font-semibold text-foreground text-sm uppercase" x-text="p.name"></div>
                                        <div class="text-xs text-secondary font-mono" x-text="p.reg_number"></div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-4">
                                <div class="text-sm font-medium text-foreground uppercase" x-text="p.sekolah"></div>
                                <a :href="`https://wa.me/62${p.phone.replace(/^0/, '')}`" target="_blank"
                                    x-show="p.phone !== '-'"
                                    class="mt-1 inline-flex items-center gap-x-1.5 text-xs text-secondary hover:text-green-600 hover:underline transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-3 h-3 fill-current">
                                        <path d="M164.9 24.6c-7.7-18.6-28-28.5-47.4-23.2l-88 24C12.1 30.2 0 46 0 64C0 311.4 200.6 512 448 512c18 0 33.8-12.1 38.6-29.5l24-88c5.3-19.4-4.6-39.7-23.2-47.4l-96-40c-16.3-6.8-35.2-2.1-46.3 11.6L304.7 368C234.3 334.7 177.3 277.7 144 207.3L193.3 167c13.7-11.2 18.4-30 11.6-46.3l-40-96z" />
                                    </svg>
                                    <span x-text="p.phone"></span>
                                </a>
                            </td>

                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <span x-show="p.jurusan1"
                                        class="inline-flex items-center justify-center gap-1 w-[60px] py-1 rounded-md text-xs font-bold border bg-red-100 text-red-700 border-red-200">
                                        <span class="font-normal opacity-75">1.</span>
                                        <span class="truncate" x-text="p.jurusan1"></span>
                                    </span>
                                    <span x-show="p.jurusan2"
                                        class="inline-flex items-center justify-center gap-1 w-[60px] py-1 rounded-md text-xs font-bold border bg-yellow-100 text-yellow-800 border-yellow-300">
                                        <span class="font-normal opacity-75">2.</span>
                                        <span class="truncate" x-text="p.jurusan2"></span>
                                    </span>
                                    <span x-show="p.jurusan3"
                                        class="inline-flex items-center justify-center gap-1 w-[60px] py-1 rounded-md text-xs font-bold border bg-gray-700 text-white border-gray-800">
                                        <span class="font-normal opacity-75">3.</span>
                                        <span class="truncate" x-text="p.jurusan3"></span>
                                    </span>
                                </div>
                            </td>

                            <td class="px-4 py-4 text-left">
                                <div class="flex items-center gap-2">
                                    <button @click="openDetail(p)" title="Lihat Detail"
                                        class="flex items-center justify-center p-2 rounded-lg bg-primary/10 hover:bg-primary/20 text-primary transition-colors cursor-pointer">
                                        <i data-lucide="eye" class="size-4"></i>
                                    </button>

                                    <template x-if="!p.has_observation">
                                        <button @click="openObservasi(p)" title="Mulai Observasi"
                                            class="flex items-center justify-center gap-1.5 p-2 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 transition-colors cursor-pointer text-xs font-semibold">
                                            <i data-lucide="clipboard-list" class="size-4"></i>
                                        </button>
                                    </template>

                                    <template x-if="p.has_observation">
                                        <button @click="openObservasi(p)" title="Edit Data Observasi"
                                            class="flex items-center justify-center p-2 rounded-lg bg-amber-100 hover:bg-amber-200 text-amber-700 transition-colors cursor-pointer">
                                            <i data-lucide="edit" class="size-4"></i>
                                        </button>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    </template>

                    <tr x-show="filteredData.length === 0" style="display: none;">
                        <td colspan="5" class="px-4 py-16 text-center">
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

        {{-- Pagination --}}
        @include ('pages.admin.observasi.partials._pagination')

        <!-- MODAL DETAIL (Lihat detail info peserta) -->
        @include ('pages.admin.observasi.partials._detail-modal')

        <!-- MODAL OBSERVASI MULTI-STEP (DESAIN GEMINI + SKOR KATEGORI) -->
        @include ('pages.admin.observasi.partials._observasi-modal')

    </div>
</div>

@endsection

@push('scripts')
<script>
    function observasiApp() {
        return {
            // ── State Umum ──
            search: '',
            filterStatus: 'all',
            pesertaData: [],
            activePeserta: null,

            // ── State Modal ──
            modalOpen: false,
            obsModalOpen: false,
            obsStep: 1,
            obsSteps: [],
            obsLoading: false,

            // ── Form State ──
            obsForm: {
                // Fisik
                hearing_check: '',
                vision_check: '',
                physical_activity: '',
                color_blind_check: '',

                // Ciri Khusus
                tattoo: '',
                tattoo_scar: '',
                piercing: '',
                keloid: '',
                minor_disability: '',
                aid_tool: '',

                // Kategori Skor Akhir
                fisik_score: '',
                ciri_score: '',
                prestasi_score: '',

                // Lain-lain
                observation_status: 'pending',
                observation_notes: '',
            },

            // ── Computed Properties (Auto Score & Diskualifikasi) ──
            get isButaWarna() {
                return this.obsForm.color_blind_check === 'yes';
            },

            get isDisqualifiedCiri() {
                return this.obsForm.tattoo === 'yes' ||
                    this.obsForm.tattoo_scar === 'yes' ||
                    this.obsForm.piercing === 'yes';
            },

            get autoScoreFisik() {
                // Jika buta warna atau diskualifikasi ciri, skor otomatis 0
                if (this.isButaWarna || this.isDisqualifiedCiri) return 0;

                let s = 0;
                // Asumsi: "yes" (normal/mampu) = 25 poin
                if (this.obsForm.hearing_check === 'yes') s += 25;
                if (this.obsForm.vision_check === 'yes') s += 25;
                if (this.obsForm.physical_activity === 'yes') s += 25;
                // Khusus buta warna: "no" (tidak buta warna) = 25 poin
                if (this.obsForm.color_blind_check === 'no') s += 25;
                return s;
            },

            get autoScoreCiri() {
                // Jika buta warna atau diskualifikasi ciri, skor otomatis 0
                if (this.isButaWarna || this.isDisqualifiedCiri) return 0;

                let s = 0;
                // Asumsi: "no" (tidak memiliki ciri tersebut) = dapat poin penuh
                if (this.obsForm.tattoo === 'no') s += 20;
                if (this.obsForm.piercing === 'no') s += 20;
                if (this.obsForm.tattoo_scar === 'no') s += 15;
                if (this.obsForm.keloid === 'no') s += 15;
                if (this.obsForm.minor_disability === 'no') s += 15;
                if (this.obsForm.aid_tool === 'no') s += 15;
                return s;
            },

            // ── Fungsi-fungsi ──
            loadData() {
                if (this.$el.dataset.peserta) {
                    this.pesertaData = JSON.parse(this.$el.dataset.peserta);
                }
                this.$nextTick(() => {
                    if (window.lucide) lucide.createIcons();
                });
            },

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
            },

            openObservasi(p) {
                this.activePeserta = p;
                this.obsStep = 1;

                // Tentukan jumlah tab step dinamis berdasarkan data prestasi
                if (p.has_achievement) {
                    this.obsSteps = [{
                            label: 'Kondisi Fisik'
                        },
                        {
                            label: 'Ciri Khusus'
                        },
                        {
                            label: 'Prestasi'
                        },
                        {
                            label: 'Konfirmasi'
                        }
                    ];
                } else {
                    this.obsSteps = [{
                            label: 'Kondisi Fisik'
                        },
                        {
                            label: 'Ciri Khusus'
                        },
                        {
                            label: 'Konfirmasi'
                        }
                    ];
                }

                // Inisialisasi Form
                if (p.has_observation) {
                    // Mapping data observasi yang sudah ada di database
                    this.obsForm = {
                        hearing_check: p.hearing_check || '',
                        vision_check: p.vision_check || '',
                        physical_activity: p.physical_activity || '',
                        color_blind_check: p.color_blind_check || '',

                        tattoo: p.tattoo || '',
                        tattoo_scar: p.tattoo_scar || '',
                        piercing: p.piercing || '',
                        keloid: p.keloid || '',
                        minor_disability: p.minor_disability || '',
                        aid_tool: p.aid_tool || '',

                        fisik_score: p.fisik_score || '',
                        ciri_score: p.ciri_score || '',
                        prestasi_score: p.prestasi_score || '',

                        observation_status: p.obs_status || 'pending',
                        observation_notes: p.observation_notes || '',
                    };
                } else {
                    // Reset form bersih untuk pendaftar yang belum diobservasi
                    this.obsForm = {
                        hearing_check: '',
                        vision_check: '',
                        physical_activity: '',
                        color_blind_check: '',

                        tattoo: '',
                        tattoo_scar: '',
                        piercing: '',
                        keloid: '',
                        minor_disability: '',
                        aid_tool: '',

                        fisik_score: '',
                        ciri_score: '',
                        prestasi_score: '',

                        observation_status: 'pending',
                        observation_notes: '',
                    };
                }

                this.obsModalOpen = true;
                this.$nextTick(() => {
                    if (window.lucide) lucide.createIcons();
                });
            },

            closeObsModal() {
                this.obsModalOpen = false;
                this.obsStep = 1;
            },

            obsNext() {
                if (this.obsStep < this.obsSteps.length) {
                    this.obsStep++;
                    this.$nextTick(() => {
                        if (window.lucide) lucide.createIcons();
                    });
                }
            },

            obsPrev() {
                if (this.obsStep > 1) {
                    this.obsStep--;
                    this.$nextTick(() => {
                        if (window.lucide) lucide.createIcons();
                    });
                }
            },

            calcTotalScore() {
                // Aturan Gugur Mutlak (Otomatis nilainya menjadi 0)
                if (this.isButaWarna || this.isDisqualifiedCiri) return 0;

                // Hanya hitung rata-rata dari nilai fisik dan ciri khusus
                let total = 0;
                let count = 0;

                if (this.obsForm.fisik_score) {
                    total += Number(this.obsForm.fisik_score);
                    count++;
                }
                if (this.obsForm.ciri_score) {
                    total += Number(this.obsForm.ciri_score);
                    count++;
                }

                if (count === 0) return 0;
                return Math.round(total / count);
            },

            async submitObservasi() {
                this.obsLoading = true;
                try {
                    const payload = {
                        ...this.obsForm,
                        total_score: this.calcTotalScore(), // akan mengirimkan rata-rata fisik & ciri
                        registration_id: this.activePeserta.id,
                        _token: document.querySelector('meta[name="csrf-token"]')?.content,
                    };

                    const url = this.activePeserta.has_observation ?
                        `/admin/observasi/${this.activePeserta.id}` :
                        `/admin/observasi`;

                    const method = this.activePeserta.has_observation ? 'PUT' : 'POST';

                    const res = await fetch(url, {
                        method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': payload._token
                        },
                        body: JSON.stringify(payload),
                    });

                    const data = await res.json();

                    if (res.ok) {
                        const idx = this.pesertaData.findIndex(p => p.id === this.activePeserta.id);
                        if (idx !== -1) {
                            this.pesertaData[idx].has_observation = true;
                            this.pesertaData[idx].obs_status = this.obsForm.observation_status;
                        }
                        this.closeObsModal();
                        if (window.showToast) showToast(data.message || 'Observasi berhasil disimpan.', '#30B22D');
                    } else {
                        if (window.showToast) showToast(data.message || 'Terjadi kesalahan, coba lagi.', '#EF4444');
                    }
                } catch (e) {
                    if (window.showToast) showToast('Gagal menghubungi server.', '#EF4444');
                } finally {
                    this.obsLoading = false;
                }
            },
        };
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) lucide.createIcons();
    });
</script>
@endpush
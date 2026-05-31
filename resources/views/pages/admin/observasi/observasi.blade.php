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
    @include('pages.admin.observasi.partials._stats-cards')

    {{-- ═══════════════════════════════════════════
         TABEL PESERTA
    ════════════════════════════════════════════ --}}
    <div id="peserta-container"
        class="flex flex-col rounded-2xl border border-border p-6 bg-white gap-6"
        x-data="observasiApp()">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-lg text-foreground">Daftar Observasi Peserta SPMB</h3>
                <p class="text-sm text-secondary">Kelola data peserta Observasi Sistem Penerimaan Murid Baru</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <div class="relative">
                    <form action="{{ url()->current() }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">

                        <div class="relative w-full sm:w-auto">
                            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>

                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari peserta..."
                                hx-get="{{ url()->current() }}"
                                hx-include="closest form"
                                hx-trigger="keyup changed delay:500ms, search"
                                hx-target="#peserta-container"
                                hx-select="#peserta-container"
                                hx-swap="outerHTML"
                                hx-push-url="true"
                                class="pl-9 pr-9 py-2 rounded-xl border border-border bg-white text-sm focus:ring-1 focus:ring-primary outline-none w-full sm:w-[220px] transition-all" />

                            {{-- Tombol X hanya muncul JIKA ada teks pencarian saja --}}
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
                        </div>

                        <div class="relative w-full sm:w-auto">
                            <i data-lucide="filter" class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>

                            <select name="filter_status"
                                hx-get="{{ url()->current() }}"
                                hx-include="closest form"
                                hx-target="#peserta-container"
                                hx-select="#peserta-container"
                                hx-swap="outerHTML"
                                hx-push-url="true"
                                class="w-full sm:w-auto py-2 pl-9 pr-8 rounded-xl border border-border bg-white text-sm focus:ring-1 focus:ring-primary outline-none transition-all cursor-pointer font-medium text-gray-700">
                                <option value="">Semua Status</option>
                                <option value="sudah" {{ request('filter_status') === 'sudah' ? 'selected' : '' }}>Sudah Observasi</option>
                                <option value="belum" {{ request('filter_status') === 'belum' ? 'selected' : '' }}>Belum Observasi</option>
                            </select>
                        </div>

                    </form>

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

                    {{-- LOOP MENGGUNAKAN BLADE (Bukan Alpine lagi) --}}
                    @forelse($peserta as $p)
                    @include('pages.admin.observasi.partials._row-peserta', ['p' => $p])
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-secondary">
                                <i data-lucide="inbox" class="size-10 text-border"></i>
                                <p class="font-medium">Tidak ada data ditemukan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @include ('pages.admin.observasi.partials._pagination')

        @include ('pages.admin.observasi.partials._detail-modal')

        @include ('pages.admin.observasi.partials._observasi-modal')

    </div>
</div>

@endsection

@push('scripts')
<script>
    function observasiApp() {
        return {
            // ── State Umum ──
            activePeserta: null,
            physical_manual: false,
            special_trait_manual: false,
            status_manual: false,

            // ── State Modal ──
            modalOpen: false,
            obsModalOpen: false,
            obsStep: 1,
            obsSteps: [],
            obsLoading: false,
            obsErrors: [],

            // ── Form State ──
            obsForm: {
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
                physical_score: '',
                special_trait_score: '',
                achievement_score: '',
                observation_status: 'pending',
                observation_notes: '',
            },

            get isButaWarna() {
                return this.obsForm.color_blind_check === 'yes';
            },
            get isDisqualifiedCiri() {
                return this.obsForm.tattoo === 'yes' || this.obsForm.tattoo_scar === 'yes' || this.obsForm.piercing === 'yes';
            },

            get autoScorePhysical() {
                if (this.isButaWarna || this.isDisqualifiedCiri) return 0;
                let s = 0;
                if (this.obsForm.hearing_check === 'yes') s += 25;
                if (this.obsForm.vision_check === 'yes') s += 25;
                if (this.obsForm.physical_activity === 'yes') s += 25;
                if (this.obsForm.color_blind_check === 'no') s += 25;
                return s;
            },

            get autoScoreSpecialTrait() {
                if (this.isButaWarna || this.isDisqualifiedCiri) return 0;
                let s = 0;
                if (this.obsForm.tattoo === 'no') s += 20;
                if (this.obsForm.piercing === 'no') s += 20;
                if (this.obsForm.tattoo_scar === 'no') s += 15;
                if (this.obsForm.keloid === 'no') s += 15;
                if (this.obsForm.minor_disability === 'no') s += 15;
                if (this.obsForm.aid_tool === 'no') s += 15;
                return s;
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

                if (p.has_achievement) {
                    this.obsSteps = [{
                        label: 'Kondisi Fisik'
                    }, {
                        label: 'Ciri Khusus'
                    }, {
                        label: 'Prestasi'
                    }, {
                        label: 'Konfirmasi'
                    }];
                } else {
                    this.obsSteps = [{
                        label: 'Kondisi Fisik'
                    }, {
                        label: 'Ciri Khusus'
                    }, {
                        label: 'Konfirmasi'
                    }];
                }

                if (p.has_observation) {
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
                        physical_score: p.physical_score !== null && p.physical_score !== '' ? Number(p.physical_score) : '',
                        special_trait_score: p.special_trait_score !== null && p.special_trait_score !== '' ? Number(p.special_trait_score) : '',
                        achievement_score: p.achievement_score !== null && p.achievement_score !== '' ? Number(p.achievement_score) : '',
                        observation_status: p.obs_status || 'pending',
                        observation_notes: p.observation_notes || '',
                    };
                    this.physical_manual = this.obsForm.physical_score !== this.autoScorePhysical;
                    this.special_trait_manual = this.obsForm.special_trait_score !== this.autoScoreSpecialTrait;
                    this.status_manual = this.obsForm.observation_status !== (this.calcTotalScore() >= 50 ? 'passed' : 'failed');
                } else {
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
                        physical_score: '',
                        special_trait_score: '',
                        achievement_score: '',
                        observation_status: 'pending',
                        observation_notes: '',
                    };
                    this.physical_manual = false;
                    this.special_trait_manual = false;
                    this.status_manual = false;
                }

                this.obsErrors = [];
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
                this.obsErrors = [];
                if (this.obsStep < this.obsSteps.length) {
                    this.obsStep++;
                    this.$nextTick(() => {
                        if (window.lucide) lucide.createIcons();
                    });
                }
            },

            obsPrev() {
                this.obsErrors = [];
                if (this.obsStep > 1) {
                    this.obsStep--;
                    this.$nextTick(() => {
                        if (window.lucide) lucide.createIcons();
                    });
                }
            },

            calcTotalScore() {
                if (this.isButaWarna || this.isDisqualifiedCiri) return 0;
                let total = 0,
                    count = 0;
                if (this.obsForm.physical_score) {
                    total += Number(this.obsForm.physical_score);
                    count++;
                }
                if (this.obsForm.special_trait_score) {
                    total += Number(this.obsForm.special_trait_score);
                    count++;
                }
                if (count === 0) return 0;
                return Math.round(total / count);
            },

            async submitObservasi() {
                this.obsLoading = true;
                this.obsErrors = [];

                try {
                    const payload = {
                        ...this.obsForm,
                        total_score: this.calcTotalScore(),
                        registration_id: this.activePeserta.id,
                        _token: document.querySelector('meta[name="csrf-token"]')?.content,
                    };

                    const url = this.activePeserta.has_observation ? `/admin/observation/${this.activePeserta.id}` : `/admin/observation`;
                    const method = this.activePeserta.has_observation ? 'PUT' : 'POST';

                    const res = await fetch(url, {
                        method,
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': payload._token
                        },
                        body: JSON.stringify(payload),
                    });

                    const contentType = res.headers.get("content-type");

                    if (res.ok) {
                        // 1. KONDISI INFO (Tidak ada data berubah)
                        if (contentType && contentType.includes("application/json")) {
                            const data = await res.json();
                            if (data.status === 'info') {
                                this.closeObsModal();

                                setTimeout(() => {
                                    // Sudah diubah ke window.ShowAlert
                                    window.ShowAlert({
                                        type: 'info',
                                        title: 'Tidak Ada Perubahan',
                                        message: data.message || 'Sistem mengabaikan pembaruan karena isian sama.',
                                        confirmText: 'Mengerti'
                                    });
                                }, 300);
                            }
                        }
                        // 2. KONDISI SUKSES (Data berhasil disimpan/diperbarui)
                        else {
                            const htmlResponse = await res.text();

                            const tableWrapper = document.createElement('div');
                            tableWrapper.innerHTML = `<table><tbody>${htmlResponse}</tbody></table>`;
                            const newRow = tableWrapper.querySelector('tr[id^="row-"]');

                            const statsWrapper = document.createElement('div');
                            statsWrapper.innerHTML = htmlResponse;
                            const newStats = statsWrapper.querySelector('#stats-container');

                            const targetRow = document.getElementById('row-' + this.activePeserta.id);
                            if (targetRow && newRow) {
                                targetRow.outerHTML = newRow.outerHTML;
                            }

                            const oldStats = document.getElementById('stats-container');
                            if (oldStats && newStats) {
                                oldStats.outerHTML = newStats.outerHTML;
                            }

                            const isUpdate = this.activePeserta.has_observation;
                            this.closeObsModal();

                            setTimeout(() => {
                                // Sudah diubah ke window.ShowAlert
                                window.ShowAlert({
                                    type: 'success',
                                    title: isUpdate ? 'Data Diperbarui!' : 'Data Disimpan!',
                                    message: 'Observasi peserta berhasil ' + (isUpdate ? 'diperbarui.' : 'disimpan.'),
                                    confirmText: 'OK'
                                });
                            }, 300);

                            this.$nextTick(() => {
                                if (window.lucide) lucide.createIcons();
                            });
                        }
                    } else {
                        // 3. KONDISI ERROR DARI SERVER (Validasi / 500)
                        const data = await res.json();
                        if (res.status === 422 && data.errors) {
                            this.obsErrors = Object.values(data.errors).map(err => err[0]);
                            document.querySelector('.overflow-y-auto.flex-1').scrollTo({
                                top: 0,
                                behavior: 'smooth'
                            });
                        } else {
                            // Sudah diubah ke window.ShowAlert
                            window.ShowAlert({
                                type: 'error',
                                title: 'Gagal Menyimpan!',
                                message: data.message || 'Terjadi kesalahan pada server.'
                            });
                        }
                    }
                } catch (e) {
                    console.error(e);
                    // Sudah diubah ke window.ShowAlert
                    window.ShowAlert({
                        type: 'error',
                        title: 'Gagal Koneksi!',
                        message: 'Gagal menghubungi server. Periksa jaringan Anda.'
                    });
                } finally {
                    this.obsLoading = false;
                }
            },
        };
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) lucide.createIcons();
    });

    document.body.addEventListener('htmx:afterSwap', () => {
        if (window.lucide) lucide.createIcons();
    });
</script>
@endpush
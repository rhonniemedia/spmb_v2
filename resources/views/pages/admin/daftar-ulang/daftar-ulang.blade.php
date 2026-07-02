@extends('layouts.admin')

@section('title', 'Verifikasi Daftar Ulang')
@section('page_title', 'Daftar Ulang')
@section('page_subtitle', 'Verifikasi berkas daftar ulang peserta')

@section('content')
<div class="flex-1 overflow-y-auto p-5 md:p-8 bg-white" x-data="daftarUlangApp()">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-foreground text-2xl md:text-3xl font-bold mb-1">Daftar Ulang Peserta</h1>
            <p class="text-secondary text-sm">Kelola verifikasi berkas daftar ulang SPMB.</p>
        </div>
        {{-- Bungkus tombol-tombol di sisi kanan dengan div flex --}}
        <div class="flex items-center gap-3">

            {{-- TOMBOL CETAK LAPORAN (Hanya Superadmin & Admin) --}}
            @canany(['superadmin', 'admin', 'verifikator'])
            <a href="{{ route('admin.laporan.daftar-ulang') }}" target="_blank"
                class="flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full font-semibold text-sm transition-all duration-300 cursor-pointer shadow-sm shadow-emerald-600/30">
                <i data-lucide="printer" class="size-4"></i>
                <span>Cetak Laporan</span>
            </a>
            @endcanany

            {{-- TOMBOL SEGARKAN --}}
            <button
                onclick="document.getElementById('refresh-icon').classList.add('animate-spin'); window.location.reload();"
                class="flex items-center gap-2 px-4 py-2.5 ring-1 ring-border hover:ring-primary rounded-full text-foreground font-semibold text-sm transition-all bg-white cursor-pointer">
                <i id="refresh-icon" data-lucide="refresh-cw" class="size-4"></i>
                <span>Segarkan</span>
            </button>

        </div>
    </div>

    {{-- Statistik Cards --}}
    @include('pages.admin.daftar-ulang.partials._stats-cards')

    {{-- Container Data --}}
    <div id="daftar-ulang-container" class="flex flex-col rounded-2xl border border-border p-6 bg-white gap-6">

        {{-- Wrapper Form Pencarian & Filter --}}
        <!-- Header Tabel & Form Pencarian -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">

            {{-- Bagian Kiri: Judul Daftar Ulang --}}
            <div>
                <h3 class="font-bold text-lg text-foreground">Data Daftar Ulang</h3>
                <p class="text-sm text-secondary">Kelola verifikasi berkas daftar ulang SPMB</p>
            </div>

            {{-- Bagian Kanan: Form Pencarian & Filter --}}
            <div class="flex items-center">
                <form id="filter-form" action="{{ url()->current() }}" method="GET" class="flex flex-row items-center gap-2">

                    {{-- 1. Input Pencarian Nama --}}
                    <div class="relative w-full sm:w-[240px]">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>

                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari peserta..."
                            hx-get="{{ url()->current() }}"
                            hx-include="closest form"
                            hx-trigger="keyup changed delay:500ms, search"
                            hx-target="#daftar-ulang-container"
                            hx-select="#daftar-ulang-container"
                            hx-swap="outerHTML"
                            hx-push-url="true"
                            class="pl-9 pr-9 py-2 rounded-xl border border-border bg-white text-sm focus:ring-1 focus:ring-primary outline-none w-full transition-all" />

                        @if(request('search'))
                        <button type="button"
                            hx-get="{{ request()->fullUrlWithQuery(['search' => null]) }}"
                            hx-target="#daftar-ulang-container"
                            hx-select="#daftar-ulang-container"
                            hx-swap="outerHTML"
                            hx-push-url="true"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 transition-colors focus:outline-none cursor-pointer">
                            <i data-lucide="x" class="size-4"></i>
                        </button>
                        @endif
                    </div>

                    {{-- 2. Tombol Filter (Buka Modal) --}}
                    <button type="button" @click="filterModalOpen = true" class="relative flex items-center justify-center w-[42px] h-[42px] rounded-xl border border-border bg-white hover:bg-gray-50 transition-colors cursor-pointer group" title="Filter Data">
                        <i data-lucide="filter" class="size-4 text-secondary group-hover:text-primary transition-colors"></i>

                        {{-- Indikator Titik Aktif --}}
                        @if(request('filter_berkas') || request('filter_status') || request('filter_concentration'))
                        <span class="absolute top-2 right-2 flex size-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                            <span class="relative inline-flex rounded-full size-2 bg-primary"></span>
                        </span>
                        @endif
                    </button>

                    {{-- 3. Modal Multi-Filter --}}
                    <div x-show="filterModalOpen" style="display: none;" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 p-4">

                        {{-- Elemen Inner Modal --}}
                        <div x-show="filterModalOpen"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            @click.outside="filterModalOpen = false"
                            class="bg-white rounded-2xl w-full max-w-sm overflow-hidden shadow-xl border border-border text-left">

                            <div class="flex items-center justify-between px-5 py-4 border-b border-border bg-gray-50/50">
                                <h3 class="font-bold text-foreground">Filter Pendaftar</h3>
                                <button type="button" @click="filterModalOpen = false" class="text-secondary hover:text-error transition-colors p-1 rounded-lg hover:bg-red-50">
                                    <i data-lucide="x" class="size-4"></i>
                                </button>
                            </div>

                            <div class="p-5 flex flex-col gap-5">

                                {{-- Filter Status Data (select) --}}
                                <div>
                                    <label for="filter_berkas" class="text-xs font-bold text-secondary uppercase tracking-wider mb-2 block">Status Data</label>
                                    <div class="relative">
                                        <i data-lucide="folder-check" class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>
                                        <select id="filter_berkas" name="filter_berkas"
                                            hx-get="{{ url()->current() }}"
                                            hx-include="#filter-form"
                                            hx-target="#daftar-ulang-container"
                                            hx-select="#daftar-ulang-container"
                                            hx-swap="outerHTML"
                                            hx-push-url="true"
                                            class="w-full py-2 pl-9 pr-8 rounded-xl border border-border bg-white text-sm focus:ring-1 focus:ring-primary outline-none transition-all cursor-pointer">
                                            <option value="">Semua Status Data</option>
                                            <option value="complete" {{ request('filter_berkas') === 'complete' ? 'selected' : '' }}>Lengkap</option>
                                            <option value="incomplete" {{ request('filter_berkas') === 'incomplete' ? 'selected' : '' }}>Belum Lengkap</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Filter Status Verifikasi (select) --}}
                                <div>
                                    <label for="filter_status" class="text-xs font-bold text-secondary uppercase tracking-wider mb-2 block">Status Verifikasi</label>
                                    <div class="relative">
                                        <i data-lucide="filter" class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>
                                        <select id="filter_status" name="filter_status"
                                            hx-get="{{ url()->current() }}"
                                            hx-include="#filter-form"
                                            hx-target="#daftar-ulang-container"
                                            hx-select="#daftar-ulang-container"
                                            hx-swap="outerHTML"
                                            hx-push-url="true"
                                            class="w-full py-2 pl-9 pr-8 rounded-xl border border-border bg-white text-sm focus:ring-1 focus:ring-primary outline-none transition-all cursor-pointer">
                                            <option value="">Semua Status Verifikasi</option>
                                            <option value="pending" {{ request('filter_status') === 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                            <option value="verified" {{ request('filter_status') === 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                                            <option value="rejected" {{ request('filter_status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Filter Konsentrasi Keahlian (select) --}}
                                <div>
                                    <label for="filter_concentration" class="text-xs font-bold text-secondary uppercase tracking-wider mb-2 block">Konsentrasi Keahlian</label>
                                    <div class="relative">
                                        <i data-lucide="graduation-cap" class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>
                                        <select id="filter_concentration" name="filter_concentration"
                                            hx-get="{{ url()->current() }}"
                                            hx-include="#filter-form"
                                            hx-target="#daftar-ulang-container"
                                            hx-select="#daftar-ulang-container"
                                            hx-swap="outerHTML"
                                            hx-push-url="true"
                                            class="w-full py-2 pl-9 pr-8 rounded-xl border border-border bg-white text-sm focus:ring-1 focus:ring-primary outline-none transition-all cursor-pointer">
                                            <option value="">Semua Konsentrasi</option>
                                            @foreach($concentrations as $concentration)
                                            <option value="{{ $concentration->id }}" {{ request('filter_concentration') === $concentration->id ? 'selected' : '' }}>{{ $concentration->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Aksi Modal --}}
                                <div class="flex items-center justify-between pt-2 border-t border-border">
                                    <button type="button"
                                        hx-get="{{ request()->fullUrlWithQuery(['filter_status' => null, 'filter_berkas' => null, 'filter_concentration' => null]) }}"
                                        hx-target="#daftar-ulang-container"
                                        hx-select="#daftar-ulang-container"
                                        hx-swap="outerHTML"
                                        hx-push-url="true"
                                        @click="filterModalOpen = false"
                                        class="text-sm font-semibold text-secondary hover:text-error transition-colors cursor-pointer">
                                        Reset Filter
                                    </button>
                                    <button type="button" @click="filterModalOpen = false"
                                        class="px-4 py-2 rounded-xl bg-primary text-white text-sm font-semibold hover:opacity-90 transition-all cursor-pointer">
                                        Tutup
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[860px] border-collapse">
                <thead class="border-b border-border">
                    <tr>
                        <th class="px-4 py-4 text-left text-sm font-bold text-foreground w-[30%]">Peserta</th>
                        <th class="px-4 py-4 text-left text-sm font-bold text-foreground w-[30%]">Asal Sekolah & Jalur</th>
                        <th class="px-4 py-4 text-left text-sm font-bold text-foreground w-[30%]">Status Verifikasi</th>
                        <th class="px-4 py-4 text-left text-sm font-bold text-foreground w-[10%]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border bg-white">
                    @forelse($daftarUlang as $r)
                    @include('pages.admin.daftar-ulang.partials._row-peserta', ['r' => $r])
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-16 text-center text-secondary">
                            <div class="flex flex-col items-center gap-3">
                                <i data-lucide="inbox" class="size-10 text-border"></i>
                                <p class="font-medium">Tidak ada data peserta ditemukan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('pages.admin.daftar-ulang.partials._pagination')

    </div>

    {{-- Include Modal --}}
    @include('pages.admin.daftar-ulang.partials._status-modal')

</div>
@endsection

@push('scripts')
<script>
    function daftarUlangApp() {
        return {
            filterModalOpen: false, // State untuk modal filter (status berkas & verifikasi)
            modalOpen: false,
            confirmResetOpen: false, // State untuk modal konfirmasi
            loading: false,
            activePeserta: null,
            form: {
                verification_status: 'verified',
                verification_notes: ''
            },

            openStatus(data) {
                this.activePeserta = data;
                this.form.verification_status = data.verification_status === 'pending' ? 'verified' : data.verification_status;
                this.form.verification_notes = data.verification_notes || '';
                this.modalOpen = true;
                this.$nextTick(() => {
                    if (window.lucide) lucide.createIcons();
                });
            },

            closeModal() {
                this.modalOpen = false;
                this.activePeserta = null;
            },

            // --- FUNGSI TRANSISI MODAL ---

            // Membuka konfirmasi & menyembunyikan modal utama
            confirmReset() {
                this.modalOpen = false;
                setTimeout(() => {
                    this.confirmResetOpen = true;
                    this.$nextTick(() => {
                        if (window.lucide) lucide.createIcons();
                    });
                }, 300); // Jeda sedikit agar animasi tutup modal utama selesai
            },

            // Membatalkan reset & menampilkan kembali modal utama
            cancelReset() {
                this.confirmResetOpen = false;
                setTimeout(() => {
                    this.modalOpen = true;
                }, 300);
            },

            // ----------------------------------------

            async submitDecision() {
                this.loading = true;
                try {
                    const payload = {
                        verification_status: this.form.verification_status,
                        verification_notes: this.form.verification_notes,
                        _token: document.querySelector('meta[name="csrf-token"]').content,
                    };

                    const res = await fetch(`/admin/re-registration/${this.activePeserta.id}/decision`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': payload._token
                        },
                        body: JSON.stringify(payload),
                    });

                    await this.handleResponse(res, 'Keputusan Verifikasi Berhasil Disimpan!');
                } catch (e) {
                    window.ShowAlert({
                        type: 'error',
                        title: 'Gagal Koneksi!',
                        message: 'Periksa jaringan Anda.'
                    });
                } finally {
                    this.loading = false;
                }
            },

            // Eksekusi Reset (Dipanggil dari tombol 'Ya, Reset' di modal konfirmasi)
            async executeReset() {
                this.loading = true;
                try {
                    const res = await fetch(`/admin/re-registration/${this.activePeserta.id}/reset`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    this.confirmResetOpen = false; // Tutup modal konfirmasi
                    await this.handleResponse(res, 'Data Berhasil Direset!');
                } catch (e) {
                    window.ShowAlert({
                        type: 'error',
                        title: 'Gagal Koneksi!',
                        message: 'Periksa jaringan Anda.'
                    });
                } finally {
                    this.loading = false;
                }
            },

            async handleResponse(res, successMsg) {
                if (res.ok) {
                    const htmlResponse = await res.text();

                    const tableWrapper = document.createElement('div');
                    tableWrapper.innerHTML = `<table><tbody>${htmlResponse}</tbody></table>`;
                    const newRow = tableWrapper.querySelector('tr[id^="row-"]');
                    const targetRow = document.getElementById('row-' + this.activePeserta.id);
                    if (targetRow && newRow) targetRow.outerHTML = newRow.outerHTML;

                    const statsWrapper = document.createElement('div');
                    statsWrapper.innerHTML = htmlResponse;
                    const newStats = statsWrapper.querySelector('#stats-container');
                    const oldStats = document.getElementById('stats-container');
                    if (oldStats && newStats) oldStats.outerHTML = newStats.outerHTML;

                    this.closeModal();
                    window.ShowAlert({
                        type: 'success',
                        title: 'Sukses!',
                        message: successMsg
                    });
                    this.$nextTick(() => {
                        if (window.lucide) lucide.createIcons();
                    });
                } else {
                    const data = await res.json();
                    window.ShowAlert({
                        type: 'error',
                        title: 'Gagal!',
                        message: data.message || 'Terjadi kesalahan.'
                    });
                }
            }
        };
    }
    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) lucide.createIcons();
    });

    // Render ulang ikon lucide setelah htmx melakukan swap (search, filter select, dsb)
    document.body.addEventListener('htmx:afterSwap', () => {
        if (window.lucide) lucide.createIcons();
    });
</script>
@endpush
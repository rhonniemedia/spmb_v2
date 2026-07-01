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
        <button
            onclick="document.getElementById('refresh-icon').classList.add('animate-spin'); window.location.reload();"
            class="flex items-center gap-2 px-4 py-2.5 ring-1 ring-border hover:ring-primary rounded-full text-foreground font-semibold text-sm transition-all bg-white cursor-pointer">
            <i id="refresh-icon" data-lucide="refresh-cw" class="size-4"></i>
            <span>Segarkan</span>
        </button>
    </div>

    {{-- Statistik Cards --}}
    @include('pages.admin.daftar-ulang.partials._stats-cards')

    {{-- Container Data --}}
    <div id="daftar-ulang-container" class="flex flex-col rounded-2xl border border-border p-6 bg-white gap-6">

        {{-- Wrapper Form Pencarian & Filter --}}
        <div class="flex flex-col sm:flex-row justify-between gap-4">
            <form action="{{ url()->current() }}" method="GET" class="flex flex-col sm:flex-row justify-between w-full gap-4">

                {{-- Bagian Kiri: Filter (Status & Berkas) --}}
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    {{-- Filter Status Verifikasi --}}
                    <div class="relative w-full sm:w-auto">
                        <i data-lucide="filter" class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>
                        <select name="filter_status"
                            hx-get="{{ url()->current() }}" hx-include="closest form"
                            hx-target="#daftar-ulang-container" hx-select="#daftar-ulang-container" hx-swap="outerHTML" hx-push-url="true"
                            class="w-full sm:w-auto py-2 pl-9 pr-8 rounded-xl border border-border bg-white text-sm focus:ring-1 focus:ring-primary outline-none transition-all cursor-pointer">
                            <option value="">Semua Status Verifikasi</option>
                            <option value="pending" {{ request('filter_status') === 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                            <option value="verified" {{ request('filter_status') === 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                            <option value="rejected" {{ request('filter_status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    {{-- Filter Status Berkas --}}
                    <div class="relative w-full sm:w-auto">
                        <i data-lucide="folder-check" class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>
                        <select name="filter_berkas"
                            hx-get="{{ url()->current() }}" hx-include="closest form"
                            hx-target="#daftar-ulang-container" hx-select="#daftar-ulang-container" hx-swap="outerHTML" hx-push-url="true"
                            class="w-full sm:w-auto py-2 pl-9 pr-8 rounded-xl border border-border bg-white text-sm focus:ring-1 focus:ring-primary outline-none transition-all cursor-pointer">
                            <option value="">Semua Status Berkas</option>
                            <option value="complete" {{ request('filter_berkas') === 'complete' ? 'selected' : '' }}>Berkas Lengkap</option>
                            <option value="incomplete" {{ request('filter_berkas') === 'incomplete' ? 'selected' : '' }}>Belum Lengkap</option>
                        </select>
                    </div>
                </div>

                {{-- Bagian Kanan: Search Bar --}}
                <div class="relative w-full sm:w-[280px]">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>

                    {{-- Waktu tunda (delay) diubah menjadi 1 detik (1s) agar ada jeda waktu ngetik --}}
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / no registrasi..."
                        hx-get="{{ url()->current() }}" hx-include="closest form" hx-trigger="keyup changed delay:1s, search"
                        hx-target="#daftar-ulang-container" hx-select="#daftar-ulang-container" hx-swap="outerHTML" hx-push-url="true"
                        class="pl-9 pr-10 py-2 rounded-xl border border-border bg-white text-sm focus:ring-1 focus:ring-primary outline-none w-full transition-all" />

                    {{-- Tombol X untuk reset search (Hanya muncul jika ada request search) --}}
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

            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[860px] border-collapse">
                <thead class="border-b border-border">
                    <tr>
                        <th class="px-4 py-4 text-left text-sm font-bold text-foreground w-[30%]">Peserta</th>
                        <th class="px-4 py-4 text-left text-sm font-bold text-foreground w-[25%]">Asal Sekolah & Jalur</th>
                        <th class="px-4 py-4 text-left text-sm font-bold text-foreground w-[20%]">Status Berkas</th>
                        <th class="px-4 py-4 text-left text-sm font-bold text-foreground w-[20%]">Verifikasi</th>
                        <th class="px-4 py-4 text-left text-sm font-bold text-foreground w-[5%]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border bg-white">
                    @forelse($daftarUlang as $r)
                    @include('pages.admin.daftar-ulang.partials._row-peserta', ['r' => $r])
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-16 text-center text-secondary">
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
</script>
@endpush
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
            <p class="text-secondary text-sm">Selamat datang kembali, <span class="font-semibold text-foreground">{{ auth()->user()->name }}</span> — SPMB {{ date('Y') }}/{{ date('Y') + 1 }}</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="flex items-center gap-2 px-4 py-3 ring-1 ring-border hover:ring-primary rounded-full text-foreground font-semibold text-sm transition-all duration-300 cursor-pointer bg-white">
                <i data-lucide="download-cloud" class="w-4 h-4"></i>
                <span>Ekspor</span>
            </button>
            {{-- Dropdown Laporan --}}
            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button type="button" @click="open = !open"
                    class="flex items-center gap-2 px-4 py-3 bg-primary text-white rounded-full font-bold text-sm hover:bg-primary-hover transition-all duration-300 cursor-pointer">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                    <span>Laporan</span>
                    <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-2"
                    style="display: none;"
                    class="absolute right-0 mt-2 w-56 bg-white border border-border rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] z-50 p-2">

                    <div class="px-3 pt-2 pb-1">
                        <span class="text-xs font-bold text-secondary uppercase tracking-wider">Cetak Data</span>
                    </div>
                    <div class="h-px bg-border my-2"></div>

                    <div class="flex flex-col gap-1">
                        {{-- Rekapitulasi: Memicu Modal Alpine ($dispatch) --}}
                        <a href="#" @click.prevent="open = false; $dispatch('open-modal-rekap')" class="group flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-foreground rounded-xl hover:bg-muted transition-colors">
                            <i data-lucide="file-spreadsheet" class="size-4 text-secondary group-hover:text-primary transition-colors"></i>
                            <span>Rekapitulasi</span>
                        </a>

                        {{-- Sisanya langsung direct ke link dengan target="_blank" --}}
                        <a href="{{ route('admin.laporan.peminat') }}" target="_blank" class="group flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-foreground rounded-xl hover:bg-muted transition-colors">
                            <i data-lucide="users" class="size-4 text-secondary group-hover:text-primary transition-colors"></i>
                            <span>Peminat</span>
                        </a>

                        <a href="{{ route('admin.laporan.peminat-jurusan') }}" target="_blank" class="group flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-foreground rounded-xl hover:bg-muted transition-colors">
                            <i data-lucide="book-open" class="size-4 text-secondary group-hover:text-primary transition-colors"></i>
                            <span>Peminat Jurusan</span>
                        </a>

                        <a href="{{ route('admin.laporan.tanda-terima') }}" target="_blank" class="group flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-foreground rounded-xl hover:bg-muted transition-colors">
                            <i data-lucide="receipt" class="size-4 text-secondary group-hover:text-primary transition-colors"></i>
                            <span>Tanda Terima</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Stat Cards ── -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        {{-- Total Pendaftar --}}
        <div class="flex flex-col rounded-2xl border border-border p-5 gap-3 bg-white hover:shadow-md transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between">
                <div class="size-11 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="users" class="size-5 text-primary"></i>
                </div>
                <span class="inline-flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-full bg-success/10 text-success-dark">
                    <i data-lucide="trending-up" class="size-3"></i>{{ $totalApplicants }}
                </span>
            </div>
            <div>
                <p class="font-bold text-3xl text-foreground">{{ number_format($totalApplicants) }}</p>
                <p class="text-sm text-secondary mt-0.5">Total Pendaftar</p>
                <p class="text-xs text-secondary mt-1.5 flex items-center gap-1">
                    <i data-lucide="calendar" class="size-3"></i>Tahun ajaran {{ date('Y') }}/{{ date('Y') + 1 }}
                </p>
            </div>
        </div>

        {{-- Terverifikasi --}}
        <div class="flex flex-col rounded-2xl border border-border p-5 gap-3 bg-white hover:shadow-md transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between">
                <div class="size-11 bg-success/10 rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="check-circle" class="size-5 text-success"></i>
                </div>
                <span class="inline-flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-full bg-success/10 text-success-dark">
                    @if($totalApplicants > 0)
                    {{ number_format(($verifiedCount / $totalApplicants) * 100, 1) }}%
                    @else
                    0%
                    @endif
                </span>
            </div>
            <div>
                <p class="font-bold text-3xl text-foreground">{{ number_format($verifiedCount) }}</p>
                <p class="text-sm text-secondary mt-0.5">Dokumen Terverifikasi</p>
                <p class="text-xs text-secondary mt-1.5 flex items-center gap-1">
                    <i data-lucide="check" class="size-3"></i>{{ number_format($pendingCount) }} belum diproses
                </p>
            </div>
        </div>

        {{-- Daftar Ulang --}}
        <div class="flex flex-col rounded-2xl border border-border p-5 gap-3 bg-white hover:shadow-md transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between">
                <div class="size-11 bg-info/10 rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="clipboard-check" class="size-5 text-info"></i>
                </div>
                <span class="inline-flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-full bg-blue-50 text-blue-700">
                    <i data-lucide="activity" class="size-3"></i>Seleksi
                </span>
            </div>
            <div>
                <p class="font-bold text-3xl text-foreground">{{ number_format($reRegisteredCount) }}</p>
                <p class="text-sm text-secondary mt-0.5">Sudah Observasi</p>
                <p class="text-xs text-secondary mt-1.5 flex items-center gap-1">
                    <i data-lucide="calendar-check" class="size-3"></i>Jadwal: 23–24 Juni {{ date('Y') }}
                </p>
            </div>
        </div>

        {{-- Dokumen Bermasalah --}}
        <div class="flex flex-col rounded-2xl border border-border p-5 gap-3 bg-white hover:shadow-md transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between">
                <div class="size-11 bg-error/10 rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="alert-triangle" class="size-5 text-error"></i>
                </div>
                @if($problematicCount > 0)
                <span class="inline-flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-full bg-error/10 text-error-dark">
                    <i data-lucide="trending-up" class="size-3"></i>{{ $problematicCount }}
                </span>
                @else
                <span class="inline-flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-full bg-success/10 text-success-dark">
                    Aman
                </span>
                @endif
            </div>
            <div>
                <p class="font-bold text-3xl text-foreground">{{ number_format($problematicCount) }}</p>
                <p class="text-sm text-secondary mt-0.5">Dokumen Bermasalah</p>
                @if($problematicCount > 0)
                <p class="text-xs text-error mt-1.5 flex items-center gap-1 font-semibold">
                    <i data-lucide="circle-alert" class="size-3"></i>Perlu tindakan segera
                </p>
                @else
                <p class="text-xs text-success mt-1.5 flex items-center gap-1 font-semibold">
                    <i data-lucide="circle-check" class="size-3"></i>Semua dokumen oke
                </p>
                @endif
            </div>
        </div>

    </div>

    <!-- ── Chart + Distribusi Jurusan ── -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">

        <div class="lg:col-span-2 flex flex-col h-full rounded-2xl border border-border p-6 gap-4 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 shrink-0">
                <div>
                    <h3 class="font-bold text-lg text-foreground">Tren Pendaftar Harian</h3>
                    <p class="text-sm text-secondary" id="chartSubtitle">{{ $spmbChartRange['label'] ?? 'Periode SPMB' }} — total masuk per hari</p>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="setChartRange('week')" id="btn-week"
                        class="px-3 py-1.5 rounded-full border border-border text-xs font-bold text-secondary hover:border-primary hover:text-primary transition-all cursor-pointer">
                        7 Hari
                    </button>
                    <button onclick="setChartRange('month')" id="btn-month"
                        class="px-3 py-1.5 rounded-full border border-primary bg-primary/10 text-xs font-bold text-primary cursor-pointer">
                        {{ $spmbChartRange['btn_label'] ?? 'Periode SPMB' }}
                    </button>
                </div>
            </div>
            <div class="w-full relative flex-1 min-h-[250px] mt-2">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <div class="lg:col-span-1 flex flex-col rounded-2xl border border-border p-6 gap-4 bg-white">
            <div>
                <h3 class="font-bold text-lg text-foreground">Distribusi Jurusan</h3>
                <p class="text-sm text-secondary">Peminat per program keahlian</p>
            </div>
            <div class="flex justify-center">
                <div style="width:160px;height:160px;position:relative">
                    <canvas id="donutChart"></canvas>
                </div>
            </div>
            @php
            $twColors = [
            'cyan' => '#06B6D4', 'emerald' => '#10B981', 'blue' => '#3B82F6',
            'amber' => '#F59E0B', 'yellow' => '#EAB308', 'indigo' => '#6366F1',
            'orange' => '#F97316', 'rose' => '#F43F5E', 'red' => '#EF4444',
            'sky' => '#0EA5E9', 'purple' => '#8B5CF6', 'teal' => '#14B8A6',
            'green' => '#22C55E', 'pink' => '#EC4899', 'violet' => '#7C3AED',
            ];
            @endphp
            <div class="flex flex-col gap-1.5 mt-1">
                @foreach($concentrations as $concentration)
                @php
                $raw = $concentration->color ?? '';
                if (str_starts_with($raw, '#')) {
                $dotColor = $raw;
                } elseif (isset($twColors[$raw])) {
                $dotColor = $twColors[$raw];
                } else {
                $dotColor = '#6B7280';
                }
                @endphp
                <div class="flex items-center justify-between text-sm py-0.5">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full shrink-0" style="background:{{ $dotColor }}"></div>
                        <span class="{{ $concentration->applicant_count > 0 ? 'text-foreground font-medium' : 'text-secondary' }}">
                            {{ $concentration->alias ?? $concentration->name }}
                        </span>
                    </div>
                    <span class="{{ $concentration->applicant_count > 0 ? 'font-bold text-foreground' : 'font-medium text-secondary' }}">
                        {{ number_format($concentration->applicant_count) }}
                    </span>
                </div>
                @endforeach
                @if($concentrations->isEmpty())
                <p class="text-xs text-secondary text-center py-2">Belum ada data konsentrasi</p>
                @endif
            </div>
        </div>

    </div>

    <!-- ── Kuota Jurusan + Aktivitas ── -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">

        <!-- Kuota per Jurusan — dari $concentrations -->
        <div class="flex flex-col rounded-2xl border border-border p-6 gap-5 bg-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-lg text-foreground">Kuota & Peminat</h3>
                    <p class="text-sm text-secondary">Perbandingan peminat vs kuota tersedia</p>
                </div>
                <a href="{{ route('admin.pendaftar.index') }}"
                    class="size-9 rounded-xl border border-border flex items-center justify-center text-secondary hover:border-primary hover:text-primary transition-colors cursor-pointer">
                    <i data-lucide="arrow-right" class="size-4"></i>
                </a>
            </div>
            <div class="flex flex-col divide-y divide-border">

                @forelse($concentrations as $concentration)
                @php
                $ratio = $concentration->demand_ratio;
                $percent = $concentration->quota_bar_percent;

                // Variabel diubah menjadi $jmlPeminat agar tidak bentrok dengan Paginator
                $quota = $concentration->quota ?? 0;
                $jmlPeminat = $concentration->applicant_count ?? 0;
                $displayPercentage = $quota > 0 ? ($jmlPeminat / $quota) * 100 : 0;

                // Tentukan warna badge berdasarkan rasio bawaan
                $badgeClass = $ratio >= 4 ? 'bg-error/10 text-error-dark'
                : ($ratio >= 3 ? 'bg-warning/10 text-warning-dark'
                : 'bg-success/10 text-success-dark');

                // Warna progress bar disamakan dengan konversi Donut Chart
                $rawColor = $concentration->color ?? '';
                if (str_starts_with($rawColor, '#')) {
                $barColor = $rawColor;
                } elseif (isset($twColors[$rawColor])) {
                $barColor = $twColors[$rawColor];
                } else {
                $barColor = '#6B7280'; // Default gray
                }
                @endphp
                <div class="py-3 first:pt-0 last:pb-0">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-semibold text-foreground">{{ $concentration->name }}</span>
                        <span class="text-xs text-secondary">
                            {{ number_format($jmlPeminat) }} / {{ number_format($quota) }} kursi
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-bold {{ $badgeClass }} ml-1">
                                {{ number_format($displayPercentage, 0) }}%
                            </span>
                        </span>
                    </div>
                    <div class="h-1.5 rounded-full bg-border overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-500"
                            style="width:{{ $percent }}%;background:{{ $barColor }}"></div>
                    </div>
                </div>
                @empty
                <div class="py-4 text-center text-sm text-secondary">Belum ada data konsentrasi aktif</div>
                @endforelse

            </div>
        </div>

        <!-- Aktivitas Terbaru — dari $activities, HTMX polling tiap 30 detik -->
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

            {{-- Wrapper di-refresh HTMX tiap 30s --}}
            <div id="activity-feed"
                hx-get="{{ route('admin.dashboard.activities') }}"
                hx-trigger="every 30s"
                hx-swap="innerHTML">
                @include('pages.admin.dashboard.partials.activity-feed', ['activities' => $activities])
            </div>
        </div>

    </div>

    <!-- ── Tabel Peserta ── -->
    <div class="flex flex-col rounded-2xl border border-border p-6 bg-white gap-6">
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-lg text-foreground">Peserta Terbaru</h3>
                <p class="text-sm text-secondary">Pendaftar terakhir masuk sistem</p>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                {{--
                    CATATAN: id pada setiap input (applicant-search, applicant-status, applicant-concentration)
                    wajib ada karena digunakan oleh hx-vals di tombol pagination pada partial applicant-table.
                    Jangan ubah id-id tersebut tanpa mengubah partial yang bersangkutan.
                --}}
                <form id="applicant-filter-form" action="{{ route('admin.dashboard') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3 w-full xl:w-auto" onsubmit="event.preventDefault();">

                    {{-- Search Input --}}
                    <div class="relative w-full sm:w-auto">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>
                        <input
                            id="applicant-search"
                            type="text"
                            name="search"
                            placeholder="Cari peserta..."
                            hx-get="{{ route('admin.dashboard.applicants') }}"
                            hx-include="#applicant-filter-form"
                            hx-trigger="input changed delay:500ms"
                            hx-target="#applicant-table-wrapper"
                            hx-swap="innerHTML"
                            class="pl-9 pr-9 py-2 h-10 rounded-xl border border-border bg-white text-sm focus:ring-1 focus:ring-primary outline-none w-full sm:w-[220px] transition-all" />

                        {{-- Tombol X Clear Search (menggunakan AlpineJS) --}}
                        <button type="button"
                            x-data="{ show: false }"
                            x-init="
                                const input = $el.previousElementSibling;
                                show = input.value.length > 0;
                                input.addEventListener('input', () => show = input.value.length > 0);
                            "
                            x-show="show"
                            style="display: none;"
                            @click="
                                const input = $el.previousElementSibling;
                                input.value = '';
                                input.dispatchEvent(new Event('input'));
                            "
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 transition-colors focus:outline-none cursor-pointer">
                            <i data-lucide="x" class="size-4"></i>
                        </button>
                    </div>

                    {{-- Filter Status --}}
                    <div class="relative w-full sm:w-auto">
                        <i data-lucide="filter" class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>
                        <select
                            id="applicant-status"
                            name="status"
                            hx-get="{{ route('admin.dashboard.applicants') }}"
                            hx-include="#applicant-filter-form"
                            hx-trigger="change"
                            hx-target="#applicant-table-wrapper"
                            hx-swap="innerHTML"
                            class="w-full sm:w-auto h-10 pl-9 pr-8 rounded-xl border border-border bg-white text-sm text-secondary focus:ring-1 focus:ring-primary outline-none transition-all cursor-pointer font-medium">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="verified">Verified</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>

                    {{-- Filter Konsentrasi --}}
                    <div class="relative w-full sm:w-auto">
                        <i data-lucide="book-open" class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>
                        <select
                            id="applicant-concentration"
                            name="concentration"
                            hx-get="{{ route('admin.dashboard.applicants') }}"
                            hx-include="#applicant-filter-form"
                            hx-trigger="change"
                            hx-target="#applicant-table-wrapper"
                            hx-swap="innerHTML"
                            class="w-full sm:w-auto h-10 pl-9 pr-8 rounded-xl border border-border bg-white text-sm text-secondary focus:ring-1 focus:ring-primary outline-none transition-all cursor-pointer font-medium">
                            <option value="">Semua Jurusan</option>
                            @foreach($concentrations as $c)
                            <option value="{{ $c->id }}">{{ $c->alias ?? $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <a href="{{ route('admin.pendaftar.index') }}"
                        class="flex items-center justify-center gap-1.5 px-5 h-10 bg-primary text-white rounded-xl sm:rounded-full font-bold text-xs hover:bg-primary-hover transition-all cursor-pointer w-full sm:w-auto">
                        <i data-lucide="shield-check" class="size-3.5"></i>Verifikasi
                    </a>
                </form>
            </div>
        </div>

        {{-- Tabel di-swap oleh HTMX saat filter/search/pagination berubah --}}
        <div id="applicant-table-wrapper">
            @include('pages.admin.dashboard.partials.applicant-table', ['applicants' => $applicants])
        </div>

    </div>
    <!-- /tabel -->

</div>

@include ('pages.admin.dashboard.partials._rekapitulasi')

@push('scripts')
<script>
    function dashboardApp() {
        return {};
    }

    // ── Data konsentrasi dari PHP ke JS ─────────────────────────────────────
    const concentrationData = @json($concentrationChartData);

    // Map nama warna Tailwind → hex (sesuai data konsentrasi)
    const twColors = {
        'cyan': '#06B6D4',
        'emerald': '#10B981',
        'blue': '#3B82F6',
        'amber': '#F59E0B',
        'yellow': '#EAB308',
        'indigo': '#6366F1',
        'orange': '#F97316',
        'rose': '#F43F5E',
        'red': '#EF4444',
        'sky': '#0EA5E9',
        'purple': '#8B5CF6',
        'teal': '#14B8A6',
        'green': '#22C55E',
        'pink': '#EC4899',
        'violet': '#7C3AED',
    };

    function resolveColor(c) {
        if (!c.color) return '#6B7280';
        if (c.color.startsWith('#')) return c.color;
        return twColors[c.color] ?? '#6B7280';
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (window.lucide) lucide.createIcons();

        // ── Donut Chart — data dari $concentrations ──────────────────────────
        const donutCtx = document.getElementById('donutChart');
        if (donutCtx && concentrationData.length) {
            // Gunakan nilai kecil (0.0001) untuk entri count=0 agar warna tetap sesuai posisinya
            const donutData = concentrationData.map(d => d.count > 0 ? d.count : 0.0001);
            const hasAnyData = concentrationData.some(d => d.count > 0);
            new Chart(donutCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: concentrationData.map(d => d.label),
                    datasets: [{
                        data: donutData,
                        backgroundColor: concentrationData.map(d => resolveColor(d)),
                        borderWidth: hasAnyData ? 2 : 0,
                        borderColor: '#fff',
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    const real = concentrationData[ctx.dataIndex].count;
                                    return ' ' + ctx.label + ': ' + real + ' peminat';
                                }
                            }
                        }
                    },
                    cutout: '72%'
                }
            });
        }

        // ── Line Chart — fetch dari endpoint /admin/dashboard/chart ──────────
        const trendCtx = document.getElementById('trendChart');
        if (!trendCtx) return;

        let trendChart = null;

        async function loadChart(range) {
            const res = await fetch(`{{ route('admin.dashboard.chart') }}?range=${range}`);
            const json = await res.json();

            if (!trendChart) {
                trendChart = new Chart(trendCtx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: json.labels,
                        datasets: [{
                            label: 'Pendaftar',
                            data: json.data,
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
                                    family: 'Sora',
                                    size: 12
                                },
                                bodyFont: {
                                    family: 'Sora',
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
                                        family: 'Sora',
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
                                        family: 'Sora',
                                        size: 10
                                    }
                                }
                            }
                        }
                    }
                });
            } else {
                trendChart.data.labels = json.labels;
                trendChart.data.datasets[0].data = json.data;
                trendChart.update();
            }

            // Perbarui subtitle
            const subtitle = document.getElementById('chartSubtitle');
            if (subtitle) {
                subtitle.textContent = range == 7 ?
                    '7 hari terakhir — total masuk per hari' :
                    spmbRange.label + ' — total masuk per hari';
            }
        }

        // Rentang dari SpmbStep 'pendaftaran-spmb'
        const spmbRange = @json($spmbChartRange ?? ['days' => 30, 'label' => '30 hari terakhir']);
        loadChart(spmbRange.days);

        window.setChartRange = function(r) {
            const days = r === 'week' ? 7 : spmbRange.days;
            loadChart(days);

            const active = 'px-3 py-1.5 rounded-full border border-primary bg-primary/10 text-xs font-bold text-primary cursor-pointer';
            const inactive = 'px-3 py-1.5 rounded-full border border-border text-xs font-bold text-secondary hover:border-primary hover:text-primary transition-all cursor-pointer';
            document.getElementById('btn-week').className = r === 'week' ? active : inactive;
            document.getElementById('btn-month').className = r === 'month' ? active : inactive;
        };
    });
</script>
@endpush

@endsection
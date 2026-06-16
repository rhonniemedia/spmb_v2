@extends('layouts.admin')

@section('title', 'Penjenjangan')
@section('page_title', 'Penjenjangan')
@section('page_subtitle', 'Hasil Penjenjangan Peserta SPMB')

@section('content')

<div class="flex-1 overflow-y-auto p-5 md:p-8 bg-white">

    {{-- Flash Toast --}}
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            showToast('{{ session("success") }}', '#30B22D');
        });
    </script>
    @endif
    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            showToast('{{ session("error") }}', '#EF4444');
        });
    </script>
    @endif

    {{-- ── Header ── --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-foreground text-2xl md:text-3xl font-bold mb-1">Hasil Penjenjangan</h1>
            <p class="text-secondary text-sm">
                @if($latestBatch > 0)
                Menampilkan hasil batch ke-<span class="font-semibold text-foreground">{{ $latestBatch }}</span>
                @else
                Belum ada penjenjangan yang dijalankan.
                @endif
            </p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.penjenjangan.history') }}"
                class="flex items-center gap-2 px-4 py-2.5 ring-1 ring-border hover:ring-primary rounded-full text-foreground font-semibold text-sm transition-all duration-300 cursor-pointer bg-white">
                <i data-lucide="history" class="size-4"></i>
                <span>Riwayat Batch</span>
            </a>
            <a href="{{ route('admin.penjenjangan.rejected') }}"
                class="flex items-center gap-2 px-4 py-2.5 ring-1 ring-border hover:ring-red-400 rounded-full text-foreground font-semibold text-sm transition-all duration-300 cursor-pointer bg-white">
                <i data-lucide="user-x" class="size-4"></i>
                <span>Peserta Ditolak</span>
            </a>
            {{-- DROPDOWN CETAK HASIL (ALPINE.JS) --}}
            @if($latestBatch > 0)
            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                {{-- Tombol Utama --}}
                <button type="button" @click="open = !open"
                    class="flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full font-semibold text-sm transition-all duration-300 cursor-pointer shadow-sm shadow-emerald-600/30">
                    <i data-lucide="printer" class="size-4"></i>
                    <span>Laporan</span>
                    <i data-lucide="chevron-down" class="size-4 transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                </button>

                {{-- Isi Dropdown dengan Animasi Transisi --}}
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
                        <span class="text-xs font-bold text-secondary uppercase tracking-wider">Cetak Laporan</span>
                    </div>
                    <div class="h-px bg-border my-2"></div>

                    <div class="flex flex-col gap-1">
                        <a href="{{ route('admin.laporan.penjenjangan') }}" target="_blank" @click="open = false"
                            class="group flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-foreground rounded-xl hover:bg-muted transition-colors">
                            <i data-lucide="file-check-2" class="size-4 text-secondary group-hover:text-emerald-600 transition-colors"></i>
                            <span>Peserta Diterima</span>
                        </a>

                        <a href="{{ route('admin.laporan.penjenjangan-ditolak') }}" target="_blank" @click="open = false"
                            class="group flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-foreground rounded-xl hover:bg-muted transition-colors">
                            <i data-lucide="file-x-2" class="size-4 text-secondary group-hover:text-red-500 transition-colors"></i>
                            <span>Peserta Ditolak</span>
                        </a>
                    </div>
                </div>
            </div>
            @endif
            @canany(['superadmin', 'admin'])
            <button onclick="confirmRun()"
                class="flex items-center gap-2 px-5 py-2.5 bg-primary hover:bg-primary-hover text-white rounded-full font-semibold text-sm transition-all duration-300 cursor-pointer shadow-sm shadow-primary/30">
                <i data-lucide="play-circle" class="size-4"></i>
                <span>Jenjang</span>
            </button>
            @endcanany
        </div>
    </div>

    {{-- ── Stats Cards ── --}}
    @php
    // Gunakan isset() agar aman jika $summary berupa array kosong (belum ada batch)
    $totalAccepted = isset($summary['accepted']) ? count($summary['accepted']) : 0;
    $totalRejected = isset($summary['rejected']) ? count($summary['rejected']) : 0;
    $totalProcess = isset($summary['process']) ? count($summary['process']) : 0;

    $totalAll = $totalAccepted + $totalRejected + $totalProcess;
    $pctAccepted = $totalAll > 0 ? round($totalAccepted / $totalAll * 100) : 0;
    @endphp

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Total --}}
        <div class="relative overflow-hidden flex flex-col rounded-2xl border border-border p-5 gap-4 bg-white min-h-[130px] hover:-translate-y-1 hover:shadow-lg hover:shadow-black/5 transition-all duration-200 cursor-default">
            <div class="absolute -top-5 -right-5 w-20 h-20 rounded-full bg-purple-400 opacity-[0.07]"></div>
            <div class="flex items-center gap-2">
                <div class="size-10 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="users" class="size-5 text-primary"></i>
                </div>
                <p class="font-medium text-xs text-secondary">Total Peserta</p>
            </div>
            <div class="border-t border-dashed border-border pt-3">
                <p class="font-bold text-3xl">{{ number_format($totalAll, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Diterima --}}
        <div class="relative overflow-hidden flex flex-col rounded-2xl border border-border p-5 gap-4 bg-white min-h-[130px] hover:-translate-y-1 hover:shadow-lg hover:shadow-black/5 transition-all duration-200 cursor-default">
            <div class="absolute -top-5 -right-5 w-20 h-20 rounded-full bg-success opacity-[0.07]"></div>
            <div class="flex items-center gap-2">
                <div class="size-10 bg-success/10 rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="check-circle" class="size-5 text-success"></i>
                </div>
                <p class="font-medium text-xs text-secondary">Diterima</p>
            </div>
            <div class="border-t border-dashed border-border pt-3 flex items-baseline gap-2">
                <p class="font-bold text-3xl">{{ number_format($totalAccepted, 0, ',', '.') }}</p>
                <span class="text-success text-xs font-semibold bg-success/10 px-2 py-0.5 rounded-md">{{ $pctAccepted }}%</span>
            </div>
        </div>

        {{-- Ditolak --}}
        <div class="relative overflow-hidden flex flex-col rounded-2xl border border-border p-5 gap-4 bg-white min-h-[130px] hover:-translate-y-1 hover:shadow-lg hover:shadow-black/5 transition-all duration-200 cursor-default">
            <div class="absolute -top-5 -right-5 w-20 h-20 rounded-full bg-error opacity-[0.07]"></div>
            <div class="flex items-center gap-2">
                <div class="size-10 bg-error/10 rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="x-circle" class="size-5 text-error"></i>
                </div>
                <p class="font-medium text-xs text-secondary">Ditolak</p>
            </div>
            <div class="border-t border-dashed border-border pt-3">
                <p class="font-bold text-3xl">{{ number_format($totalRejected, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Batch --}}
        <div class="relative overflow-hidden flex flex-col rounded-2xl border border-border p-5 gap-4 bg-white min-h-[130px] hover:-translate-y-1 hover:shadow-lg hover:shadow-black/5 transition-all duration-200 cursor-default">
            <div class="absolute -top-5 -right-5 w-20 h-20 rounded-full bg-blue-400 opacity-[0.07]"></div>
            <div class="flex items-center gap-2">
                <div class="size-10 bg-blue-50 rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="layers" class="size-5 text-blue-600"></i>
                </div>
                <p class="font-medium text-xs text-secondary">Batch Terbaru</p>
            </div>
            <div class="border-t border-dashed border-border pt-3">
                <p class="font-bold text-3xl">#{{ $latestBatch > 0 ? $latestBatch : '-' }}</p>
            </div>
        </div>
    </div>

    {{-- ── Tabel Per Jurusan ── --}}
    <div class="flex flex-col rounded-2xl border border-border p-6 bg-white gap-6">
        <div>
            <h3 class="font-bold text-lg text-foreground">Rekapitulasi Per Jurusan</h3>
            <p class="text-sm text-secondary">Klik jurusan untuk melihat detail peserta yang diterima.</p>
        </div>

        @if($latestBatch === 0)
        <div class="flex flex-col items-center gap-3 text-secondary py-16">
            <i data-lucide="inbox" class="size-10 text-border"></i>
            <p class="font-medium">Belum ada data penjenjangan</p>
            @canany(['superadmin'])
            <button onclick="confirmRun()" class="mt-2 px-5 py-2.5 bg-primary text-white rounded-full font-semibold text-sm cursor-pointer">
                Jalankan Sekarang
            </button>
            @endcanany
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full min-w-[640px] border-collapse">
                <thead class="border-b border-border">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-bold text-foreground">Jurusan</th>
                        <th class="px-4 py-3 text-center text-sm font-bold text-foreground">Total Kuota</th>
                        <th class="px-4 py-3 text-center text-sm font-bold text-foreground">Diterima</th>
                        <th class="px-4 py-3 text-center text-sm font-bold text-foreground">Sisa Kuota</th>
                        <th class="px-4 py-3 text-center text-sm font-bold text-foreground">Terisi</th>
                        <th class="px-4 py-3 text-center text-sm font-bold text-foreground">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($concentrations as $c)
                    @php
                    $sisa = $c->quota - $c->accepted_count;
                    $pct = $c->quota > 0 ? round($c->accepted_count / $c->quota * 100) : 0;
                    @endphp
                    <tr class="hover:bg-muted/50 transition-colors">
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                @php
                                // Mapping warna dari database ke class solid Tailwind
                                $bgClass = match($c->color) {
                                'cyan' => 'bg-cyan-500',
                                'emerald' => 'bg-emerald-500',
                                'blue' => 'bg-blue-500',
                                'amber' => 'bg-amber-500',
                                'yellow' => 'bg-yellow-500',
                                'indigo' => 'bg-indigo-500',
                                'orange' => 'bg-orange-500',
                                'rose' => 'bg-rose-500',
                                'red' => 'bg-red-500',
                                'sky' => 'bg-sky-500',
                                default => 'bg-slate-500' // Fallback color
                                };
                                @endphp

                                {{-- Menggunakan rounded-full untuk lingkaran sempurna, text-white untuk icon, dan ukuran sedikit lebih besar (size-10) --}}
                                <div class="{{ $bgClass }} size-10 rounded-full flex items-center justify-center shrink-0 text-white shadow-sm">
                                    <i data-lucide="{{ $c->lucide_icon }}" class="size-5"></i>
                                </div>

                                <div>
                                    <p class="font-semibold text-sm text-foreground">{{ $c->name }}</p>
                                    <p class="text-xs text-secondary">{{ $c->code }} {{ $c->alias }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="font-bold text-foreground">{{ $c->quota }}</span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="font-bold text-success">{{ $c->accepted_count }}</span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="font-bold {{ $sisa <= 0 ? 'text-error' : 'text-foreground' }}">{{ max(0, $sisa) }}</span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2 w-full overflow-hidden">
                                <div class="flex-1 bg-muted rounded-full h-2 overflow-hidden">
                                    <div @class([ 'h-2 rounded-full transition-all duration-500' , 'bg-red-500'=> $pct >= 100,
                                        'bg-amber-500' => $pct >= 75 && $pct < 100, 'bg-[#30B22D]'=> $pct < 75,
                                                ])
                                                @style([ "width: {$pct}%" , "max-width: 100%"
                                                ])>
                                    </div>
                                </div>
                                <span class="text-xs font-semibold text-secondary w-8 shrink-0 text-right">{{ $pct }}%</span>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <a href="{{ route('admin.penjenjangan.detail', $c->id) }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary/10 hover:bg-primary/20 text-primary text-xs font-semibold transition-colors">
                                <i data-lucide="eye" class="size-3.5"></i>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-secondary">Tidak ada jurusan aktif.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>

{{-- Form hidden untuk jalankan penjenjangan --}}
@canany(['superadmin'])
<form id="form-run-placement" action="{{ route('admin.penjenjangan.run') }}" method="POST" class="hidden">
    @csrf
</form>
@endcanany

@endsection

@push('scripts')
<script>
    function confirmRun() {
        window.ShowAlert({
            type: 'warning',
            title: 'Jalankan Penjenjangan?',
            message: 'Sistem akan memproses ulang seluruh peserta dan menyimpan hasilnya sebagai batch baru. Proses ini tidak dapat dibatalkan.',
            confirmText: 'Ya, Jalankan',
            cancelText: 'Batal',
            onConfirm: () => document.getElementById('form-run-placement').submit(),
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) lucide.createIcons();
    });
</script>
@endpush
@extends('layouts.admin')

@section('title', 'Detail Penjenjangan — ' . $concentration->name)
@section('page_title', 'Detail Penjenjangan')
@section('page_subtitle', $concentration->name)

@section('content')

<div class="flex-1 overflow-y-auto p-5 md:p-8 bg-white min-w-0">

    {{-- ── Breadcrumb & Header ── --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <a href="{{ route('admin.penjenjangan.index') }}"
                class="inline-flex items-center gap-1.5 text-sm text-secondary hover:text-primary transition-colors mb-2">
                <i data-lucide="arrow-left" class="size-4"></i>
                Kembali ke Rekapitulasi
            </a>
            <h1 class="text-foreground text-2xl md:text-3xl font-bold mb-1">{{ $concentration->name }}</h1>
            <p class="text-secondary text-sm">Detail peserta diterima per jalur — Batch #{{ $latestBatch }}</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex flex-col items-end">
                <span class="text-xs text-secondary">Total Kuota</span>
                <span class="font-bold text-2xl text-foreground">{{ $concentration->quota }}</span>
            </div>
        </div>
    </div>

    {{-- ── Tabs Per Jalur ── --}}
    @php
    $firstKey = str_replace(' ', '_', strtolower($admissionPaths->first()?->name ?? ''));
    @endphp
    <div x-data="{ 
            activeTab: localStorage.getItem('detail_tab_{{ $concentration->id }}') || '{{ $firstKey }}',
            setTab(tab) {
                this.activeTab = tab;
                localStorage.setItem('detail_tab_{{ $concentration->id }}', tab);
            }
        }">

        {{-- Tab Navigation --}}
        <div class="flex items-center gap-1 border-b border-border mb-6 overflow-x-auto">
            @foreach($admissionPaths as $path)
            @php $key = str_replace(' ', '_', strtolower($path->name)); @endphp
            <button
                @click="setTab('{{ $key }}')"
                :class="activeTab === '{{ $key }}'
                    ? 'border-b-2 border-primary text-primary font-bold'
                    : 'text-secondary hover:text-foreground'"
                class="px-4 py-3 text-sm font-medium transition-colors whitespace-nowrap cursor-pointer shrink-0">
                {{ $path->name }}
                <span class="ml-1.5 px-1.5 py-0.5 rounded-md text-[10px] font-bold"
                    :class="activeTab === '{{ $key }}' ? 'bg-primary/10 text-primary' : 'bg-muted text-secondary'">
                    {{-- Gunakan total() bukan count() agar badge menampilkan semua data, bukan hanya halaman ini --}}
                    {{ $resultsByPath[$key]?->total() ?? 0 }}/{{ $quotaPerJalur[$key] ?? 0 }}
                </span>
            </button>
            @endforeach
        </div>

        {{-- Tab Content Per Jalur --}}
        @foreach($admissionPaths as $path)
        @php
        $key = str_replace(' ', '_', strtolower($path->name));
        $results = $resultsByPath[$key] ?? collect();
        $kuota = $quotaPerJalur[$key] ?? 0;
        $terisi = $results->total();
        $sisa = max(0, $kuota - $terisi);
        $pct = $kuota > 0 ? round($terisi / $kuota * 100) : 0;
        $barColor = $pct >= 100 ? '#EF4444' : ($pct >= 75 ? '#F59E0B' : '#30B22D');
        @endphp
        <div id="tab-{{ $key }}" x-show="activeTab === '{{ $key }}'" x-cloak>

            {{-- ── 4 Stat Cards ── --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
                <div class="flex flex-col rounded-2xl border border-border p-4 bg-white gap-1 min-w-0">
                    <span class="text-xs text-secondary">Kuota Jalur</span>
                    <span class="font-bold text-2xl text-foreground">{{ $kuota }}</span>
                </div>
                <div class="flex flex-col rounded-2xl border border-border p-4 bg-white gap-1 min-w-0">
                    <span class="text-xs text-secondary">Terisi</span>
                    <span class="font-bold text-2xl text-success">{{ $terisi }}</span>
                </div>
                <div class="flex flex-col rounded-2xl border border-border p-4 bg-white gap-1 min-w-0">
                    <span class="text-xs text-secondary">Sisa Kuota</span>
                    <span class="font-bold text-2xl {{ $sisa <= 0 ? 'text-error' : 'text-foreground' }}">{{ $sisa }}</span>
                </div>
                <div class="flex flex-col rounded-2xl border border-border p-4 bg-white gap-1 min-w-0">
                    <span class="text-xs text-secondary">Persentase</span>
                    <span class="font-bold text-2xl {{ $pct >= 100 ? 'text-error' : 'text-foreground' }}">{{ $pct }}%</span>
                </div>
            </div>

            {{-- ── Progress Bar (dalam container aman) ── --}}
            <div class="w-full overflow-hidden mb-6">
                <div class="w-full bg-border rounded-full h-2.5" style="max-width:100%">
                    <div class="h-2.5 rounded-full transition-all duration-500"
                        style="width: {{ $pct }}%; background: {{ $barColor }}; max-width:100%">
                    </div>
                </div>
                <p class="text-xs text-secondary mt-1.5">Terisi {{ $terisi }} dari {{ $kuota }} kuota</p>
            </div>

            {{-- ── Card Tabel — wrapper dengan id untuk target AJAX pagination ── --}}
            <div id="tab-content-{{ $key }}" class="flex flex-col rounded-2xl border border-border p-6 bg-white gap-6">

                {{-- Header --}}
                <div>
                    <h3 class="font-bold text-lg text-foreground">Peserta Diterima — {{ $path->name }}</h3>
                    <p class="text-sm text-secondary">{{ $terisi }} peserta lolos di jalur ini</p>
                </div>

                {{-- Tabel --}}
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[640px] border-collapse">
                        <thead class="border-b border-border">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-bold text-foreground w-10">#</th>
                                <th class="px-4 py-3 text-left text-sm font-bold text-foreground">Peserta</th>
                                <th class="px-4 py-3 text-left text-sm font-bold text-foreground">Sekolah Asal</th>
                                <th class="px-4 py-3 text-center text-sm font-bold text-foreground">Pilihan</th>
                                @if(in_array($key, ['reguler', 'prestasi']))
                                <th class="px-4 py-3 text-center text-sm font-bold text-foreground">Skor</th>
                                @endif
                                <th class="px-4 py-3 text-center text-sm font-bold text-foreground">Ranking</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @forelse($results as $i => $r)
                            @php
                            $rowNum = ($results->firstItem() ?? 1) + $loop->index;
                            $fullName = $r->registration->personalData->full_name ?? 'Tanpa Nama';
                            $init = strtoupper(substr($fullName, 0, 2));
                            $grads = ['linear-gradient(135deg,#FF1443,#FF6B6B)', 'linear-gradient(135deg,#3B82F6,#93C5FD)', 'linear-gradient(135deg,#F59E0B,#FCD34D)', 'linear-gradient(135deg,#8B5CF6,#A78BFA)'];
                            $grad = $grads[$loop->index % 4];
                            $choiceColors = [1 => 'bg-red-100 text-red-700 border-red-200', 2 => 'bg-yellow-100 text-yellow-800 border-yellow-300', 3 => 'bg-gray-700 text-white border-gray-800'];
                            @endphp
                            <tr class="hover:bg-muted/50 transition-colors">
                                <td class="px-4 py-4 text-sm font-bold text-secondary">{{ $rowNum }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0"
                                            style="background: {{ $grad }}">{{ $init }}</div>
                                        <div>
                                            <p class="font-semibold text-sm text-foreground uppercase">{{ $fullName }}</p>
                                            <p class="text-xs text-secondary font-mono">{{ $r->registration->registration_number ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <p class="text-sm text-foreground font-medium uppercase">{{ $r->registration->personalData->previous_school ?? '-' }}</p>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold border {{ $choiceColors[$r->accepted_in_choice] ?? 'bg-muted text-secondary border-border' }}">
                                        Pilihan {{ $r->accepted_in_choice }}
                                    </span>
                                </td>
                                @if(in_array($key, ['reguler', 'prestasi']))
                                <td class="px-4 py-4 text-center">
                                    <span class="font-mono font-bold text-sm text-foreground">{{ number_format($r->final_score, 2) }}</span>
                                </td>
                                @endif
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center justify-center size-8 rounded-full text-xs font-bold
                                        {{ ($r->rank_in_concentration ?? 99) <= 3 ? 'bg-amber-100 text-amber-700' : 'bg-muted text-secondary' }}">
                                        {{ $r->rank_in_concentration ?? '-' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-secondary">
                                        <i data-lucide="inbox" class="size-10 text-border"></i>
                                        <p class="font-medium">Tidak ada peserta diterima di jalur ini</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- ── Pagination (No-Reload AJAX Berfungsi Sempurna) ── --}}
                @if($results->hasPages())
                <div class="flex flex-col md:flex-row items-center justify-between gap-4 pt-2 border-t border-border w-full">
                    {{-- Informasi data yang ditampilkan --}}
                    <span class="text-sm text-secondary">
                        Menampilkan
                        <span class="font-semibold text-foreground">{{ $results->firstItem() }}</span>
                        sampai
                        <span class="font-semibold text-foreground">{{ $results->lastItem() }}</span>
                        dari
                        <span class="font-semibold text-foreground">{{ number_format($results->total(), 0, ',', '.') }}</span>
                        peserta
                    </span>

                    {{-- Tombol Navigasi Pagination --}}
                    <div class="flex items-center gap-2">
                        {{-- Button Prev --}}
                        @if($results->onFirstPage())
                        <button class="p-2 rounded-lg border border-border bg-white opacity-50 cursor-not-allowed" disabled>
                            <i data-lucide="chevron-left" class="size-4"></i>
                        </button>
                        @else
                        <button type="button"
                            onclick="navigateTabPagination(event, '{{ $results->previousPageUrl() }}', '{{ $key }}')"
                            class="p-2 rounded-lg border border-border bg-white hover:bg-muted cursor-pointer transition-colors">
                            <i data-lucide="chevron-left" class="size-4"></i>
                        </button>
                        @endif

                        {{-- Angka Halaman --}}
                        @php
                        $curr = $results->currentPage();
                        $last = $results->lastPage();
                        $pages = collect([1, $curr - 1, $curr, $curr + 1, $last])
                        ->filter(fn($p) => $p >= 1 && $p <= $last)
                            ->unique()->sort()->values();
                            @endphp

                            @foreach($pages as $idx => $page)
                            @if($idx > 0 && $page - $pages[$idx - 1] > 1)
                            <span class="px-1 text-secondary text-sm">…</span>
                            @endif

                            @if($page === $curr)
                            <button class="w-9 h-9 rounded-lg bg-primary text-white text-sm font-bold" disabled>{{ $page }}</button>
                            @else
                            <button type="button"
                                onclick="navigateTabPagination(event, '{{ $results->url($page) }}', '{{ $key }}')"
                                class="w-9 h-9 rounded-lg border border-border bg-white hover:bg-muted text-sm flex items-center justify-center cursor-pointer transition-colors">
                                {{ $page }}
                            </button>
                            @endif
                            @endforeach

                            {{-- Button Next --}}
                            @if($results->hasMorePages())
                            <button type="button"
                                onclick="navigateTabPagination(event, '{{ $results->nextPageUrl() }}', '{{ $key }}')"
                                class="p-2 rounded-lg border border-border bg-white hover:bg-muted cursor-pointer transition-colors">
                                <i data-lucide="chevron-right" class="size-4"></i>
                            </button>
                            @else
                            <button class="p-2 rounded-lg border border-border bg-white opacity-50 cursor-not-allowed" disabled>
                                <i data-lucide="chevron-right" class="size-4"></i>
                            </button>
                            @endif
                    </div>
                </div>
                @endif

            </div>{{-- end card --}}

        </div>
        @endforeach

    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) lucide.createIcons();
    });

    /**
     * Navigasi pagination per-tab via AJAX tanpa reload halaman.
     *
     * Perbaikan dari versi sebelumnya:
     * 1. Tidak mengubah URL browser (pushState dihilangkan) agar tab aktif
     *    tidak hilang saat user refresh.
     * 2. Hanya mengganti inner HTML dari wrapper tabel + pagination saja,
     *    bukan seluruh #tab-xxx, sehingga Alpine.js x-show tetap bekerja.
     * 3. Re-init Lucide setelah inject HTML baru.
     * 4. Menangani error fetch dengan alert yang informatif.
     *
     * @param {Event}  event    - Click event dari tombol pagination
     * @param {string} url      - URL halaman berikutnya dari Laravel paginator
     * @param {string} tabKey   - Key tab (misal: 'reguler', 'jalur_prestasi')
     */
    function navigateTabPagination(event, url, tabKey) {
        event.preventDefault();

        // Selector wrapper konten dalam tab (tabel + pagination)
        const wrapperSelector = '#tab-content-' + tabKey;
        const wrapperEl = document.querySelector(wrapperSelector);
        if (!wrapperEl) {
            console.error('Tab wrapper tidak ditemukan:', wrapperSelector);
            return;
        }

        // Tampilkan efek loading
        wrapperEl.style.opacity = '0.45';
        wrapperEl.style.transition = 'opacity 0.15s ease';
        wrapperEl.style.pointerEvents = 'none';

        fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('HTTP ' + response.status);
                return response.text();
            })
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                // Ambil hanya inner wrapper dari respons (bukan seluruh tab)
                const newWrapper = doc.querySelector(wrapperSelector);
                if (newWrapper) {
                    wrapperEl.innerHTML = newWrapper.innerHTML;
                } else {
                    // Fallback: ambil seluruh #tab-xxx jika wrapper tidak ketemu
                    const newTab = doc.querySelector('#tab-' + tabKey);
                    if (newTab) {
                        const newWrapperFallback = newTab.querySelector(wrapperSelector);
                        if (newWrapperFallback) {
                            wrapperEl.innerHTML = newWrapperFallback.innerHTML;
                        }
                    }
                }

                // Re-init Lucide icons agar ikon tidak blank setelah inject
                if (window.lucide) lucide.createIcons();

                // Scroll halus ke atas tabel agar user tahu konten berganti
                wrapperEl.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            })
            .catch(err => {
                console.error('Gagal memuat halaman pagination:', err);
                alert('Gagal memuat data. Silakan coba lagi.\n\nDetail: ' + err.message);
            })
            .finally(() => {
                wrapperEl.style.opacity = '1';
                wrapperEl.style.pointerEvents = 'auto';
            });
    }
</script>
@endpush
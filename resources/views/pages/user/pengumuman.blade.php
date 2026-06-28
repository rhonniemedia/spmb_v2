@extends('layouts.user')

@section('title', 'Pengumuman')

@section('content')

{{-- ═══════════════════════════════════════════════════
        BREADCRUMB
════════════════════════════════════════════════════ --}}
<div class="flex items-center gap-1.5 text-[13px] text-[#6A7686] mb-4">
    <a href="{{ route('dashboard') }}" class="flex items-center gap-1.5 text-primary no-underline font-semibold hover:underline">
        <i data-lucide="layout-grid" class="w-3.5 h-3.5"></i> Beranda
    </a>
    <span class="text-gray-300">/</span>
    <a href="{{ route('dashboard') }}" class="text-primary no-underline font-semibold hover:underline">Dashboard</a>
    <span class="text-gray-300">/</span>
    <span>Pengumuman</span>
</div>

{{-- ══════════════════════════════════════════
            HERO BANNER
    ══════════════════════════════════════════ --}}
<div class="relative overflow-hidden rounded-2xl bg-[#080c1a] mb-6">
    <div class="absolute -top-16 -right-16 w-72 h-72 rounded-full bg-[#ff1443]/20 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-60 h-60 rounded-full bg-[#ff1443]/10 blur-2xl pointer-events-none"></div>
    <div class="absolute top-8 right-1/3 w-3 h-3 rounded-full bg-[#30b22d]/40 pointer-events-none"></div>
    <div class="absolute top-14 right-1/4 w-2 h-2 rounded-full bg-[#f59e0b]/40 pointer-events-none"></div>
    <div class="absolute top-6 right-1/2 w-2 h-2 rounded-full bg-[#3b82f6]/40 pointer-events-none"></div>

    <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-8 p-8 md:p-10">

        <div class="w-full lg:flex-1 text-center md:text-left">
            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/20 px-4 py-1.5 text-xs text-white font-bold mb-5 backdrop-blur-md">
                <i class="fa-solid fa-circle-dot text-[#30b22d] animate-pulse"></i>
                Info SPMB 2026
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-white leading-tight">
                Pengumuman Hasil<br class="hidden md:block" />
                <span class="text-[#ff1443]">Seleksi Segera Hadir!</span>
            </h2>
            <p class="mt-4 text-[#6a7686] leading-7 max-w-2xl mx-auto md:mx-0">
                Pantau terus halaman ini. Pengumuman penerimaan peserta didik baru akan dipublikasikan pada <span class="text-white font-bold">10 Juni 2026</span>.
            </p>
            <div class="mt-7 flex flex-wrap justify-center md:justify-start gap-3">
                <a href="#daftar-pengumuman" class="flex items-center gap-2 rounded-xl bg-[#ff1443] hover:bg-[#c90e33] text-white px-6 py-3 font-bold transition-all duration-200 cursor-pointer no-underline">
                    <i class="fa-solid fa-list-ul"></i>
                    Lihat Semua Pengumuman
                </a>
            </div>
        </div>

        <div class="hidden lg:block flex-shrink-0">
            <div class="bg-white/10 border border-white/20 rounded-[16px] px-6 py-8 backdrop-blur-md text-center shadow-xl w-[220px] flex flex-col justify-center items-center">
                <div class="text-[12px] text-white/70 font-semibold uppercase tracking-widest mb-4">
                    Total Pengumuman
                </div>

                <div class="text-[46px] font-black text-white leading-none mb-2">
                    {{ $announcements->count() }}
                </div>

                <div class="text-[12px] text-white/60 font-medium mb-6">
                    Pengumuman Aktif
                </div>

                @php
                $newToday = $announcements->filter(fn($a) => $a->created_at->isToday())->count();
                @endphp
                <div class="inline-flex items-center justify-center gap-2 bg-[#30b22d]/20 border border-[#30b22d]/30 px-4 py-2 rounded-full w-full">
                    <span class="w-2 h-2 rounded-full bg-[#30b22d] animate-[pulse_1.5s_infinite]"></span>
                    <span class="text-[10px] font-bold text-[#30b22d] uppercase tracking-widest">
                        {{ $newToday }} Baru Hari Ini
                    </span>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ═══════════════════════════════════════════════════
        MAIN CONTENT — dua kolom
════════════════════════════════════════════════════ --}}
<div id="daftar-pengumuman" class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

    {{-- ── KOLOM KIRI (daftar pengumuman) ─────────── --}}
    <div class="lg:col-span-2 space-y-5 animate-fade-in">

        {{-- Filter & Search --}}
        <div class="bg-white border border-gray-200 rounded-[20px] shadow-sm p-4 flex flex-col sm:flex-row gap-3">
            {{-- Search --}}
            <div class="relative flex-1">
                {{-- Container Ikon --}}
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-[#6A7686] text-[13px]"></i>
                </div>

                {{-- Input Field --}}
                <input type="text" id="searchInput" placeholder="Cari pengumuman..."
                    oninput="filterCards()"
                    class="w-full pl-10 pr-4 py-2.5 text-[13px] border border-gray-200 rounded-full focus:outline-none focus:border-primary transition-colors bg-gray-50 placeholder:text-[#B0B8C4]">
            </div>

            {{-- Filter pills --}}
            <div class="flex items-center gap-1.5 flex-wrap" id="filterPills">
                {{-- Tombol "Semua" (Kondisi Awal: Aktif Merah) --}}
                <button onclick="setFilter('Semua')"
                    data-filter="Semua"
                    class="filter-pill text-[12px] font-bold px-3 py-1.5 rounded-full border border-[#FF1443] bg-[rgba(255,20,67,.04)] text-[#FF1443] shadow-[0_0_0_1px_rgba(255,20,67,.07)] whitespace-nowrap transition-all">
                    Semua
                </button>

                {{-- Ambil kategori langsung dari variabel $summary (Kondisi Awal: Default Abu-abu) --}}
                @foreach($summary as $s)
                <button onclick="setFilter('{{ $s['label'] }}')"
                    data-filter="{{ $s['label'] }}"
                    class="filter-pill bg-gray-50 text-[#6A7686] border border-gray-200 text-[12px] font-bold px-3 py-1.5 rounded-full whitespace-nowrap transition-all hover:bg-gray-100">
                    {{ $s['label'] }}
                </button>
                @endforeach
            </div>
        </div>

        {{-- ── DAFTAR KARTU PENGUMUMAN ── --}}
        <div class="space-y-4" id="annList">
            @forelse($announcements as $announcement)
            @php
            /*
            * Ambil setting warna & ikon dari $categorySettings yang dikirim controller.
            * Jika kategori tidak dikenali, gunakan default abu-abu.
            */
            $setting = $categorySettings[$announcement->category] ?? [
            'color' => 'gray',
            'icon' => 'fa-circle-info',
            'label' => $announcement->category,
            ];

            // Pemetaan warna per kategori → class Tailwind
            $colorMap = [
            'red' => [
            'katColor' => 'bg-red-50 text-primary border-primary/20',
            'katDot' => 'bg-primary',
            'iconBg' => 'bg-red-50 text-primary',
            'border' => 'border-red-300',
            ],
            'green' => [
            'katColor' => 'bg-green-50 text-green-700 border-green-200',
            'katDot' => 'bg-green-500',
            'iconBg' => 'bg-green-50 text-green-600',
            'border' => 'border-green-300',
            ],
            'blue' => [
            'katColor' => 'bg-blue-50 text-blue-700 border-blue-200',
            'katDot' => 'bg-blue-500',
            'iconBg' => 'bg-blue-50 text-blue-600',
            'border' => 'border-blue-300',
            ],
            'amber' => [
            'katColor' => 'bg-amber-50 text-amber-700 border-amber-200',
            'katDot' => 'bg-amber-500',
            'iconBg' => 'bg-amber-50 text-amber-600',
            'border' => 'border-amber-300',
            ],
            'gray' => [
            'katColor' => 'bg-gray-100 text-[#6A7686] border-gray-200',
            'katDot' => 'bg-gray-400',
            'iconBg' => 'bg-gray-100 text-[#6A7686]',
            'border' => 'border-gray-300',
            ],
            ];

            $colors = $colorMap[$setting['color']] ?? $colorMap['gray'];
            $isNew = $announcement->created_at->diffInDays(now()) <= 3;
                $isUrgent=(bool) $announcement->is_urgent;

                // Border sesuai warna kategori
                $borderClass = $colors['border'];

                // Label waktu relatif
                $diffDays = $announcement->created_at->diffInDays(now());
                if ($announcement->created_at->isToday()) {
                $waktu = 'Hari ini, ' . $announcement->created_at->format('H:i') . ' WIB';
                } elseif ($diffDays === 1) {
                $waktu = 'Kemarin';
                } elseif ($diffDays < 30) {
                    $waktu=$diffDays . ' hari lalu' ;
                    } elseif ($diffDays < 365) {
                    $waktu=floor($diffDays / 30) . ' bulan lalu' ;
                    } else {
                    $waktu=floor($diffDays / 365) . ' tahun lalu' ;
                    }

                    // Label kategori untuk filter (mapping 'Informasi' → 'Umum' )
                    $filterLabel=$setting['label']==='Umum' ? 'Umum' : $announcement->category;
                    @endphp

                    <div class="ann-card bg-white border {{ $borderClass }} rounded-[20px] overflow-hidden shadow-[0_1px_6px_rgba(0,0,0,0.04)]"
                        data-kategori="{{ $filterLabel }}"
                        data-judul="{{ strtolower($announcement->title) }} {{ strtolower($announcement->content ?? '') }}">

                        <div class="p-5 flex gap-4">
                            {{-- Ikon kategori --}}
                            <div class="w-12 h-12 rounded-[14px] {{ $colors['iconBg'] }} flex items-center justify-center flex-shrink-0 text-[18px]">
                                <i class="fa-solid {{ $setting['icon'] }}"></i>
                            </div>

                            {{-- Konten --}}
                            <div class="flex-1 min-w-0">
                                {{-- Badge kategori + badge tambahan + waktu --}}
                                <div class="flex items-center gap-2 flex-wrap mb-2">
                                    {{-- Badge kategori --}}
                                    <span class="inline-flex items-center gap-1.5 text-[11px] font-black px-2.5 py-1 rounded-full border uppercase tracking-wide {{ $colors['katColor'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $colors['katDot'] }} flex-shrink-0"></span>
                                        {{ $setting['label'] }}
                                    </span>

                                    {{-- Badge BARU (≤ 3 hari) --}}
                                    @if($isNew)
                                    <span class="inline-flex items-center text-[11px] font-black px-2.5 py-1 rounded-full border bg-primary/10 text-primary border-primary/20 uppercase">
                                        Baru
                                    </span>
                                    @endif

                                    {{-- Badge PENTING (is_urgent) --}}
                                    @if($isUrgent)
                                    <span class="inline-flex items-center text-[11px] font-black px-2.5 py-1 rounded-full border bg-amber-50 text-amber-600 border-amber-200 uppercase">
                                        Penting
                                    </span>
                                    @endif

                                    {{-- Waktu relatif --}}
                                    <span class="text-[12px] text-[#B0B8C4] font-semibold ml-auto flex items-center gap-1">
                                        <i class="fa-regular fa-clock text-[11px]"></i> {{ $waktu }}
                                    </span>
                                </div>

                                {{-- Judul --}}
                                <h3 class="text-[15px] font-black text-[#080C1A] leading-snug mb-1.5 group-hover:text-primary transition-colors">
                                    {{ $announcement->title }}
                                </h3>

                                {{-- Ringkasan --}}
                                <p class="text-[13px] text-[#6A7686] leading-relaxed line-clamp-2">
                                    {{ $announcement->excerpt ?? Str::limit(strip_tags($announcement->content ?? ''), 160) }}
                                </p>

                                {{-- Footer kartu --}}
                                <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-50">
                                    <span class="text-[12px] text-[#B0B8C4] font-semibold flex items-center gap-1.5">
                                        <i class="fa-regular fa-calendar text-[11px]"></i>
                                        {{ $announcement->created_at->translatedFormat('d M Y') }}
                                    </span>
                                    <a href="#"
                                        class="inline-flex items-center gap-1.5 text-[13px] font-black text-primary hover:underline">
                                        Baca Selengkapnya <i class="fa-solid fa-arrow-right text-[11px]"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    {{-- Jika tidak ada pengumuman sama sekali dari DB --}}
                    <div class="text-center py-16 bg-white border border-gray-200 rounded-[20px] shadow-sm">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-bell-slash text-[#B0B8C4] text-[22px]"></i>
                        </div>
                        <p class="text-[14px] font-bold text-[#080C1A] mb-1">Belum ada pengumuman</p>
                        <p class="text-[13px] text-[#6A7686]">Pengumuman akan tampil di sini jika sudah tersedia.</p>
                    </div>
                    @endforelse
        </div>

        {{-- Empty state — muncul via JS saat filter/search tidak ada hasil --}}
        <div id="emptyState" class="hidden text-center py-16 bg-white border border-gray-200 rounded-[20px] shadow-sm">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-magnifying-glass text-[#B0B8C4] text-[22px]"></i>
            </div>
            <p class="text-[14px] font-bold text-[#080C1A] mb-1">Pengumuman tidak ditemukan</p>
            <p class="text-[13px] text-[#6A7686]">Coba kata kunci atau kategori lain.</p>
        </div>

    </div>

    {{-- ── SIDEBAR KANAN ───────────────────────────── --}}
    <div class="space-y-5 animate-fade-in lg:sticky lg:top-[76px]">

        {{-- Ringkasan Kategori --}}
        <div class="bg-white rounded-2xl border border-[#e5e7eb] flex flex-col overflow-hidden mb-6">
            <div class="p-6 border-b border-[#e5e7eb] flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-chart-pie text-[#ff1443]"></i>
                        <h3 class="font-bold text-base text-[#080c1a]">Ringkasan</h3>
                    </div>
                    <p class="text-sm text-[#6a7686] mt-0.5">Jumlah pengumuman per kategori.</p>
                </div>
            </div>

            <div class="p-6 flex flex-col gap-5 flex-1">
                @forelse($summary as $key => $s)
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[13px] font-bold text-[#080c1a]">{{ $s['label'] }}</span>
                        <span class="text-[12px] font-bold text-[#6a7686]">{{ $s['count'] }} pengumuman</span>
                    </div>
                    <div class="h-2 bg-[#eff2f7] rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-500 {{ $s['color'] }} progress-bar"
                            style="width: {{ $s['total'] > 0 ? ($s['count'] / $s['total']) * 100 : 0 }}%"></div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <p class="text-[13px] text-[#6a7686]">Tidak ada data.</p>
                </div>
                @endforelse
            </div>
        </div>

        @include ('pages.user.partials.biodata._sidebar')

    </div>
</div>

{{-- ═══════════════════════════════════════════════════
        JAVASCRIPT — filter & search
════════════════════════════════════════════════════ --}}
@push('scripts')
<script>
    let activeFilter = 'Semua';

    function setFilter(f) {
        activeFilter = f;

        document.querySelectorAll('.filter-pill').forEach(p => {
            if (p.dataset.filter === f) {
                // 1. PASANG CLASS AKTIF (MERAH)
                p.classList.remove('bg-gray-50', 'text-[#6A7686]', 'border-gray-200', 'hover:bg-gray-100');
                p.classList.add('border-[#FF1443]', 'bg-[rgba(255,20,67,.04)]', 'text-[#FF1443]', 'shadow-[0_0_0_1px_rgba(255,20,67,.07)]');
            } else {
                // 2. KEMBALIKAN KE CLASS TIDAK AKTIF (ABU-ABU)
                p.classList.remove('border-[#FF1443]', 'bg-[rgba(255,20,67,.04)]', 'text-[#FF1443]', 'shadow-[0_0_0_1px_rgba(255,20,67,.07)]');
                p.classList.add('bg-gray-50', 'text-[#6A7686]', 'border-gray-200', 'hover:bg-gray-100');
            }
        });

        // Jalankan pencarian & penyaringan kartu
        filterCards();
    }

    function filterCards() {
        const q = document.getElementById('searchInput').value.toLowerCase().trim();
        const cards = document.querySelectorAll('#annList [data-kategori]');
        let visible = 0;

        cards.forEach(card => {
            const katMatch = activeFilter === 'Semua' || card.dataset.kategori === activeFilter;
            const textMatch = !q || card.dataset.judul.includes(q);
            const show = katMatch && textMatch;

            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        document.getElementById('emptyState').classList.toggle('hidden', visible > 0);
    }
</script>
@endpush

@endsection
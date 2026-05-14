@extends('layouts.user')

@section('title', 'Pengumuman')

@section('content')

{{-- Breadcrumb --}}
<div class="flex items-center gap-1.5 text-[13px] text-[#6A7686] mb-4">
    <a href="{{ route('dashboard') }}" class="text-primary no-underline font-semibold hover:underline">
        <i class="fa-solid fa-house"></i> Beranda
    </a>
    <span class="text-gray-300">/</span>
    <span>Pengumuman</span>
</div>

{{-- Hero Banner --}}
<div class="relative rounded-[20px] overflow-hidden mb-5 p-6 md:p-7 flex flex-col md:flex-row items-center justify-between gap-5"
    style="background: linear-gradient(135deg, #FF1443 0%, #D90F38 50%, #B00F30 100%);">
    <div class="absolute -top-10 -right-10 w-[200px] h-[200px] bg-white/[0.06] rounded-full pointer-events-none"></div>
    <div class="absolute -bottom-[60px] right-24 w-[160px] h-[160px] bg-white/[0.04] rounded-full pointer-events-none"></div>

    <div class="relative z-10 w-full md:flex-1 text-center md:text-left">
        <div class="inline-flex items-center gap-1.5 bg-white/15 text-white text-sm font-bold px-3 py-1 rounded-full mb-3 border border-white/25">
            <i class="fa-solid fa-circle-dot text-[10px] animate-pulse"></i> Info Akademik
        </div>
        <h1 class="text-xl md:text-2xl font-black text-white mb-1 leading-tight">
            Pusat Informasi Pengumuman
        </h1>
        <p class="text-sm text-white/80 leading-relaxed mb-5 max-w-[540px]">
            Dapatkan informasi terbaru mengenai pendaftaran, seleksi, dan kegiatan akademik lainnya di sini secara real-time.
        </p>
        <a href="#daftar-pengumuman"
            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white text-[#FF1443] text-[13px] font-black rounded-full no-underline shadow-md hover:bg-gray-50 transition-all">
            <i class="fa-solid fa-list-ul"></i> Lihat Semua Pengumuman
        </a>
    </div>

    <div class="relative z-10 w-full md:w-auto">
        <div class="bg-white/10 border border-white/20 rounded-[16px] px-6 py-5 backdrop-blur-md text-center shadow-xl">
            <div class="text-[12px] text-white/70 font-semibold uppercase tracking-widest mb-3">Total Pengumuman</div>
            <div class="text-[36px] font-black text-white leading-none mb-1">{{ $announcements->count() }}</div>
            @php
            $newToday = $announcements->filter(fn($a) => $a->created_at->isToday())->count();
            @endphp
            @if($newToday > 0)
            <div class="inline-flex items-center justify-center gap-1.5 bg-white/15 px-3 py-1.5 rounded-full mt-3">
                <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                <span class="text-[12px] font-bold text-white">{{ $newToday }} baru hari ini</span>
            </div>
            @endif
        </div>
    </div>
</div>

<div id="daftar-pengumuman" class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

    {{-- Main Content --}}
    <div class="lg:col-span-2 space-y-5 animate-fade-in">

        {{-- Filter & Search --}}
        <div class="bg-white border border-gray-200 rounded-card shadow-sm p-4 flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-[#6A7686] text-[13px]"></i>
                </div>
                <input type="text" id="searchInput" placeholder="Cari pengumuman..."
                    oninput="filterCards()"
                    class="w-full pl-10 pr-4 py-2.5 text-[13px] border border-gray-200 rounded-full focus:outline-none focus:border-primary transition-colors bg-gray-50">
            </div>
            <div class="flex items-center gap-1.5 flex-wrap" id="filterPills">
                <button onclick="setFilter('Semua')" data-filter="Semua"
                    class="filter-pill active text-[12px] font-bold px-3 py-1.5 rounded-full border">Semua</button>
                @foreach($summary as $s)
                <button onclick="setFilter('{{ $s['label'] }}')" data-filter="{{ $s['label'] }}"
                    class="filter-pill bg-gray-50 text-[#6A7686] border border-gray-200 text-[12px] font-bold px-3 py-1.5 rounded-full whitespace-nowrap">
                    {{ $s['label'] }}
                </button>
                @endforeach
            </div>
        </div>

        {{-- Daftar Kartu --}}
        <div class="space-y-4" id="annList">
            @forelse($announcements as $ann)
            @php
            $setting = $categorySettings[$ann->category] ?? ['color' => 'gray', 'icon' => 'fa-circle-info', 'label' => $ann->category];

            $colorMap = [
            'red' => ['kat' => 'bg-red-50 text-primary border-primary/20', 'dot' => 'bg-primary', 'icon' => 'bg-red-50 text-primary', 'border' => 'border-red-200'],
            'green' => ['kat' => 'bg-green-50 text-green-700 border-green-200', 'dot' => 'bg-green-500', 'icon' => 'bg-green-50 text-green-600', 'border' => 'border-green-200'],
            'blue' => ['kat' => 'bg-blue-50 text-blue-700 border-blue-200', 'dot' => 'bg-blue-500', 'icon' => 'bg-blue-50 text-blue-600', 'border' => 'border-blue-200'],
            'amber' => ['kat' => 'bg-amber-50 text-amber-700 border-amber-200', 'dot' => 'bg-amber-500', 'icon' => 'bg-amber-50 text-amber-600', 'border' => 'border-amber-200'],
            'gray' => ['kat' => 'bg-gray-100 text-[#6A7686] border-gray-200', 'dot' => 'bg-gray-400', 'icon' => 'bg-gray-100 text-[#6A7686]', 'border' => 'border-gray-200'],
            'purple' => ['kat' => 'bg-purple-50 text-purple-700 border-purple-200', 'dot' => 'bg-purple-500', 'icon' => 'bg-purple-50 text-purple-600', 'border' => 'border-purple-200'],
            ];

            $cls = $colorMap[$setting['color']] ?? $colorMap['gray'];
            $isNew = $ann->created_at->diffInDays(now()) <= 3;
                $filterLabel=$setting['label'];
                @endphp

                <div class="ann-card bg-white border {{ $cls['border'] }} rounded-[20px] overflow-hidden shadow-sm hover:shadow-md transition-all"
                data-kategori="{{ $filterLabel }}"
                data-judul="{{ strtolower($ann->title) }} {{ strtolower(strip_tags($ann->content)) }}">

                <div class="p-5 flex gap-4">
                    <div class="w-12 h-12 rounded-[14px] {{ $cls['icon'] }} flex items-center justify-center flex-shrink-0 text-[18px]">
                        <i class="fa-solid {{ $setting['icon'] }}"></i>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap mb-2">
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-black px-2.5 py-1 rounded-full border uppercase tracking-wide {{ $cls['kat'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $cls['dot'] }} flex-shrink-0"></span>
                                {{ $setting['label'] }}
                            </span>

                            @if($isNew)
                            <span class="inline-flex items-center text-[11px] font-black px-2.5 py-1 rounded-full border bg-primary/10 text-primary border-primary/20 uppercase">Baru</span>
                            @endif

                            @if($ann->is_urgent)
                            <span class="inline-flex items-center text-[11px] font-black px-2.5 py-1 rounded-full border bg-amber-50 text-amber-600 border-amber-200 uppercase">Penting</span>
                            @endif

                            <span class="text-[12px] text-[#B0B8C4] font-semibold ml-auto">
                                <i class="fa-regular fa-clock"></i> {{ $ann->created_at->diffForHumans() }}
                            </span>
                        </div>

                        <h3 class="text-[15px] font-black text-[#080C1A] leading-snug mb-1.5">{{ $ann->title }}</h3>
                        <p class="text-[13px] text-[#6A7686] leading-relaxed line-clamp-2">{{ strip_tags($ann->content) }}</p>

                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-50">
                            <span class="text-[12px] text-[#B0B8C4] font-semibold">
                                <i class="fa-regular fa-calendar"></i> {{ $ann->created_at->translatedFormat('d M Y') }}
                            </span>
                            <a href="{{ $ann->action_link ?? '#' }}" class="inline-flex items-center gap-1.5 text-[13px] font-black text-primary hover:underline">
                                {{ $ann->action_label ?? 'Baca Selengkapnya' }} <i class="fa-solid fa-arrow-right text-[11px]"></i>
                            </a>
                        </div>
                    </div>
                </div>
        </div>
        @empty
        <div class="text-center py-16 bg-white border border-gray-200 rounded-[20px]">
            <p class="text-[14px] font-bold text-[#080C1A]">Belum ada pengumuman</p>
        </div>
        @endforelse
    </div>

    <div id="emptyState" class="hidden text-center py-16 bg-white border border-gray-200 rounded-[20px]">
        <p class="text-[14px] font-bold text-[#080C1A]">Pengumuman tidak ditemukan</p>
    </div>
</div>

{{-- Sidebar --}}
<div class="space-y-5 animate-fade-in lg:sticky lg:top-[76px]">
    {{-- Ringkasan --}}
    <div class="bg-white border border-gray-200 rounded-card shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-base font-black text-[#080C1A]">Ringkasan</h3>
        </div>
        <div class="p-5 space-y-3">
            @foreach($summary as $s)
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[13px] font-bold text-[#080C1A]">{{ $s['label'] }}</span>
                    <span class="text-[12px] font-black {{ $s['text'] }}">{{ $s['count'] }}</span>
                </div>
                <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                    <div class="{{ $s['color'] }} h-full transition-all duration-500" style="width: {{ $s['total'] > 0 ? ($s['count'] / $s['total']) * 100 : 0 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Urgent Highlight --}}
    @if($urgentAnnouncement)
    @php
    $us = $categorySettings[$urgentAnnouncement->category] ?? ['color' => 'gray', 'icon' => 'fa-circle-info', 'label' => $urgentAnnouncement->category];
    $uc = $colorMap[$us['color']] ?? $colorMap['gray'];
    @endphp
    <div class="bg-white border border-gray-200 rounded-card shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-base font-black text-[#080C1A]">Wajib Dibaca</h3>
            <span class="text-[11px] font-black text-amber-600 bg-amber-50 px-2 py-1 rounded-full uppercase">Penting</span>
        </div>
        <div class="p-5">
            <h4 class="text-[14px] font-black text-[#080C1A] leading-snug">{{ $urgentAnnouncement->title }}</h4>
            <p class="text-[13px] text-[#6A7686] mt-2 mb-3 line-clamp-3">{{ strip_tags($urgentAnnouncement->content) }}</p>
            <a href="{{ $urgentAnnouncement->action_link ?? '#' }}" class="block w-full text-center py-2 rounded-full text-[13px] font-black text-primary bg-primary/5 border border-primary/20 hover:bg-primary/10 transition-all">
                Buka Pengumuman
            </a>
        </div>
    </div>
    @endif
</div>
</div>

@push('scripts')
<script>
    let activeFilter = 'Semua';

    function setFilter(f) {
        activeFilter = f;
        document.querySelectorAll('.filter-pill').forEach(p => {
            const isActive = p.dataset.filter === f;
            p.classList.toggle('active', isActive);
            if (!isActive) p.classList.add('bg-gray-50', 'text-[#6A7686]', 'border-gray-200');
            else p.classList.remove('bg-gray-50', 'text-[#6A7686]', 'border-gray-200');
        });
        filterCards();
    }

    function filterCards() {
        const q = document.getElementById('searchInput').value.toLowerCase().trim();
        const cards = document.querySelectorAll('#annList .ann-card');
        let visible = 0;
        cards.forEach(card => {
            const matchKat = activeFilter === 'Semua' || card.dataset.kategori === activeFilter;
            const matchText = !q || card.dataset.judul.includes(q);
            const show = matchKat && matchText;
            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        document.getElementById('emptyState').classList.toggle('hidden', visible > 0);
    }
</script>
@endpush
@endsection
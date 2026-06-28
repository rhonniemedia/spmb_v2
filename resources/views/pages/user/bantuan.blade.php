@extends('layouts.user')

@section('title', 'Bantuan')

@section('content')

{{-- ══════════════════════════════════════════
        BREADCRUMB
══════════════════════════════════════════ --}}
<div class="flex items-center gap-1.5 text-[13px] text-[#6A7686] mb-4">
    <a href="{{ route('dashboard') }}" class="flex items-center gap-1.5 text-[#FF1443] no-underline font-semibold">
        <i data-lucide="layout-grid" class="w-3.5 h-3.5"></i> Beranda
    </a>
    <span class="text-gray-300">/</span>
    <a href="{{ route('dashboard') }}" class="text-[#FF1443] no-underline font-semibold">Dashboard</a>
    <span class="text-gray-300">/</span>
    <span>Pusat Bantuan</span>
</div>

{{-- ══════════════════════════════════════════
            HERO BANNER
    ══════════════════════════════════════════ --}}
<div class="relative overflow-hidden rounded-2xl bg-[#080c1a] mb-6">
    <!-- Decorative -->
    <div class="absolute -top-16 -right-16 w-72 h-72 rounded-full bg-[#ff1443]/20 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-60 h-60 rounded-full bg-[#ff1443]/10 blur-2xl pointer-events-none"></div>
    <!-- Confetti dots decoration -->
    <div class="absolute top-8 right-1/3 w-3 h-3 rounded-full bg-[#30b22d]/40 pointer-events-none"></div>
    <div class="absolute top-14 right-1/4 w-2 h-2 rounded-full bg-[#f59e0b]/40 pointer-events-none"></div>
    <div class="absolute top-6 right-1/2 w-2 h-2 rounded-full bg-[#3b82f6]/40 pointer-events-none"></div>

    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8 p-8 md:p-10">
        <!-- Left -->
        <div class="w-full text-center md:text-left">
            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/20 px-4 py-1.5 text-xs text-white font-bold mb-5 backdrop-blur-md">
                <i class="fa-solid fa-circle-question text-[#30b22d]"></i>
                Pusat Bantuan SPMB
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-white leading-tight mb-2">
                Ada yang bisa <br class="md:hidden" /><span class="text-[#ff1443]">kami bantu?</span>
            </h2>
            <p class="mt-4 text-[#6a7686] leading-7 max-w-2xl mx-auto md:mx-0">
                Temukan jawaban seputar Sistem Penerimaan Murid Baru SMK Negeri 1. Cari pertanyaan kamu di kolom pencarian, atau pilih topik di bawah.
            </p>

            {{-- Search bar --}}
            <div class="relative max-w-[480px] mt-7 mx-auto md:mx-0">
                <!-- Tambahkan z-10 di sini -->
                <i class="fa-solid fa-magnifying-glass absolute z-10 left-4 top-1/2 -translate-y-1/2 text-[#ff1443] text-[15px] pointer-events-none"></i>

                <input
                    id="searchInput"
                    type="text"
                    placeholder="Cari pertanyaan… misal: syarat dokumen"
                    autocomplete="off"
                    class="relative w-full pl-11 pr-5 py-3.5 rounded-xl bg-white/10 border border-white/20 text-[14px] font-semibold text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-[#ff1443]/50 focus:bg-white/20 transition-all backdrop-blur-md">
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
        TWO-COLUMN LAYOUT
══════════════════════════════════════════ --}}
<div class="lg:grid lg:grid-cols-[1fr_340px] lg:gap-6 lg:items-start">

    {{-- ── MAIN COLUMN ── --}}
    <div class="min-w-0">

        {{-- KATEGORI --}}
        <p class="text-[11px] font-bold text-[#6A7686] uppercase tracking-[.06em] mb-3">Pilih topik bantuan</p>

        @php
        $cats = [
        ['key' => 'all', 'icon' => 'fa-layer-group', 'label' => 'Semua', 'count' => 22],
        ['key' => 'pendaftaran', 'icon' => 'fa-user-plus', 'label' => 'Pendaftaran', 'count' => 5],
        ['key' => 'biodata', 'icon' => 'fa-id-card', 'label' => 'Biodata', 'count' => 4],
        ['key' => 'seleksi', 'icon' => 'fa-ranking-star', 'label' => 'Seleksi', 'count' => 4],
        ['key' => 'daftarulang', 'icon' => 'fa-rotate-right', 'label' => 'Daftar Ulang', 'count' => 5],
        ['key' => 'pembayaran', 'icon' => 'fa-credit-card', 'label' => 'Pembayaran', 'count' => 4],
        ];
        @endphp

        <div class="grid grid-cols-3 sm:grid-cols-6 gap-3 mb-6">
            {{-- Tombol "Semua" tetap ada secara manual atau statis --}}
            {{-- Tombol "Semua" secara Statis --}}
            <button type="button" onclick="filterCat('all')" data-cat="all"
                class="cat-card border-[#FF1443] bg-[rgba(255,20,67,.04)] shadow-[0_0_0_3px_rgba(255,20,67,.07)] flex flex-col items-center gap-2 border rounded-[16px] px-2 py-3.5 text-center transition-all hover:-translate-y-px">
                <div class="cat-icon-wrap w-9 h-9 rounded-[10px] bg-[rgba(255,20,67,.15)] flex items-center justify-center">
                    <i class="fa-solid fa-layer-group text-[#FF1443] text-[15px]"></i>
                </div>
                <div>
                    <div class="cat-title text-[12px] font-bold leading-tight text-[#FF1443]">
                        Semua
                    </div>
                    <div class="text-[11px] text-[#6A7686]">Pusat FAQ</div>
                </div>
            </button>
            @foreach($categories as $cat)
            <button type="button" onclick="filterCat('{{ $cat->slug }}')" data-cat="{{ $cat->slug }}"
                class="cat-card flex flex-col items-center gap-2 bg-white border border-gray-200 rounded-[16px] px-2 py-3.5 text-center transition-all hover:-translate-y-px">
                <div class="cat-icon-wrap w-9 h-9 rounded-[10px] bg-[rgba(255,20,67,.08)] flex items-center justify-center">
                    <i class="fa-solid {{ $cat->icon }} text-[#FF1443] text-[15px]"></i>
                </div>
                <div>
                    <div class="cat-title text-[12px] font-bold leading-tight text-[#080C1A]">
                        {{ $cat->name }}
                    </div>
                    <div class="text-[11px] text-[#6A7686]">{{ $cat->faqs_count }} FAQ</div>
                </div>
            </button>
            @endforeach
        </div>

        {{-- FAQ SECTIONS --}}
        <p class="text-[11px] font-bold text-[#6A7686] uppercase tracking-[.06em] mb-3">Pertanyaan yang sering ditanyakan</p>

        {{-- No result --}}
        <div id="noResult" class="hidden bg-white border border-gray-200 rounded-[20px] px-6 py-12 text-center shadow-sm mb-4">
            <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-magnifying-glass text-gray-400 text-xl"></i>
            </div>
            <p class="text-[15px] font-bold text-[#080C1A] mb-1">Pertanyaan tidak ditemukan</p>
            <p class="text-[13px] text-[#6A7686]">Coba kata kunci lain, atau hubungi panitia langsung via WhatsApp.</p>
        </div>

        @foreach($categories as $group)
        <div class="faq-section-block mb-8" data-cat="{{ $group->slug }}">

            <div class="p-6 rounded-t-2xl relative overflow-hidden" style="background: linear-gradient(135deg, #ff1443 0%, #940b25 100%);">
                <div class="absolute -top-12 -right-12 w-44 h-44 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
                <div class="absolute -bottom-16 -left-16 w-36 h-36 rounded-full bg-black/10 blur-xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center shrink-0 backdrop-blur-md shadow-sm">
                            <i class="fa-solid {{ $group->icon }} text-white text-base"></i>
                        </div>
                        <div>
                            <h2 class="font-bold text-base md:text-lg text-white tracking-wide leading-tight">{{ $group->name }}</h2>
                            <p class="text-xs text-white/70 mt-1 font-medium">Topik panduan dan bantuan sistem</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center text-[11px] font-bold bg-white/15 border border-white/20 text-white px-3 py-1.5 rounded-full backdrop-blur-md self-start sm:self-center">
                        <i class="fa-solid fa-circle-question mr-1.5 text-[#4ade80]"></i> {{ $group->faqs->count() }} Pertanyaan
                    </span>
                </div>
            </div>

            <div class="bg-[#f8fafc] rounded-b-2xl border-x border-b border-[#e5e7eb] p-4 space-y-3">
                @foreach($group->faqs as $item)
                <div class="faq-item bg-white rounded-xl border border-[#e5e7eb] shadow-[0_1px_2px_rgba(0,0,0,0.02)] hover:shadow-[0_6px_16px_rgba(255,20,67,0.05)] hover:border-[#ff1443]/20 transition-all duration-300" data-q="{{ strtolower($item->question) }}">

                    <button type="button" class="faq-btn w-full px-5 py-4 flex items-center justify-between gap-4 text-left cursor-pointer group" aria-expanded="false" onclick="toggleFaq(this)">
                        <p class="font-bold text-sm text-[#080c1a] group-hover:text-[#ff1443] transition-colors duration-300 leading-snug">{{ $item->question }}</p>

                        <div class="icon-container w-7 h-7 rounded-full border border-[#e5e7eb] bg-slate-50 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:border-[#ff1443]/20 group-hover:bg-[#ff1443]/5">
                            <i class="fa-solid fa-plus text-[10px] text-[#6a7686] transition-colors group-hover:text-[#ff1443]"></i>
                        </div>
                    </button>

                    <div class="faq-answer overflow-hidden transition-all duration-300" style="max-height:0;">
                        <div class="px-5 pb-5 text-sm text-[#526071] leading-relaxed border-t border-gray-50 pt-3.5 bg-slate-50/40 rounded-b-xl">
                            {!! $item->answer !!}
                        </div>
                    </div>

                </div>
                @endforeach
            </div>

        </div>
        @endforeach

        {{-- ══════════════════════════════════════════
                TIMELINE ALUR SPMB
        ══════════════════════════════════════════ --}}
        <p class="text-[11px] font-bold text-[#6A7686] uppercase tracking-[.06em] mb-3 mt-7">Alur tahapan SPMB 2026</p>
        <div class="bg-white border border-gray-200 rounded-[20px] shadow-sm px-6 py-6">

            @foreach($steps as $step)
            <div class="flex gap-4 relative {{ !$loop->last ? 'pb-5' : '' }}">
                {{-- Garis konektor --}}
                @if(!$loop->last) <div class="absolute left-[15px] top-[36px] bottom-0 w-0.5 bg-gray-200"></div> @endif

                {{-- Ikon Status (Logika warna berdasarkan $step->status) --}}
                <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center z-10 
                    {{ $step->status === 'done' ? 'bg-green-100 border-green-500' : ($step->status === 'active' ? 'bg-primary text-white' : 'bg-gray-100') }}">
                    @if($step->status === 'done') <i class="fa-solid fa-check text-green-600"></i> @else {{ $step->step_order }} @endif
                </div>

                <div class="flex-1 pt-0.5">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 mb-0.5">
                        <span class="text-[14px] font-black">{{ $step->title }}</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $step->status === 'active' ? 'bg-red-100 text-primary' : 'bg-gray-100' }}">
                            {{ $step->status === 'active' ? 'Sedang Berlangsung' : ($step->status === 'done' ? 'Selesai' : 'Menunggu') }}
                        </span>
                    </div>
                    <p class="text-[12.5px] text-[#6A7686]">{{ $step->description }}</p>
                    <span class="text-[11px] font-semibold text-[#9CA3AF]"><i class="fa-regular fa-calendar mr-1"></i>{{ $step->period_text }}</span>
                </div>
            </div>
            @endforeach

        </div>{{-- /timeline --}}

    </div>{{-- /main col --}}

    {{-- ── SIDEBAR ── --}}
    <div class="hidden lg:block">
        <div class="sticky top-[80px] flex flex-col gap-4">

            {{-- Topik Bantuan --}}
            <div class="bg-white rounded-2xl border border-[#e5e7eb] flex flex-col overflow-hidden mb-6">
                <!-- Header -->
                <div class="p-6 border-b border-[#e5e7eb] flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-layer-group text-[#ff1443]"></i>
                            <h3 class="font-bold text-base text-[#080c1a]">Topik Bantuan</h3>
                        </div>
                        <p class="text-sm text-[#6a7686] mt-0.5">Filter berdasarkan kategori FAQ.</p>
                    </div>
                </div>

                <!-- List Categories -->
                <div class="divide-y divide-[#eff2f7] flex-1 pb-2">
                    @foreach($cats as $cat)
                    <button type="button" data-category="{{ $cat['key'] }}" onclick="filterCat(this.dataset.category)" class="w-full group flex items-center justify-between px-5 py-3.5 transition-colors duration-200 hover:bg-[#eff2f7]/50 cursor-pointer">

                        <div class="flex items-center gap-3">
                            <!-- Icon box -->
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 bg-[#eff2f7] group-hover:bg-[#ff1443]/10 transition-all duration-300">
                                <i class="fa-solid {{ $cat['icon'] }} text-[#6a7686] group-hover:text-[#ff1443] text-[13px] transition-colors"></i>
                            </div>

                            <!-- Text -->
                            <div class="text-left">
                                <p class="text-sm font-semibold text-[#6a7686] group-hover:text-[#080c1a] leading-tight transition-colors">
                                    {{ $cat['label'] }}
                                </p>
                            </div>
                        </div>

                        <!-- Count indicator -->
                        <div class="w-6 h-6 rounded-full flex items-center justify-center shrink-0 bg-white border border-[#e5e7eb] group-hover:border-[#ff1443]/30 transition-all duration-300">
                            <span class="text-[10px] font-bold text-[#6a7686] group-hover:text-[#ff1443]">{{ $cat['count'] }}</span>
                        </div>

                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Butuh Bantuan Langsung --}}
            @include ('pages.user.partials.biodata._sidebar')

        </div>
    </div>{{-- /sidebar --}}

</div>{{-- /two-col grid --}}

@endsection

{{-- ══════════════════════════════════════════
        JAVASCRIPT
══════════════════════════════════════════ --}}
@push('scripts')
<script>
    // ── Toggle FAQ accordion ──────────────────────────────
    function toggleFaq(btn) {
        const answer = btn.nextElementSibling;
        const isOpen = btn.getAttribute('aria-expanded') === 'true';

        // Tutup semua yang terbuka
        document.querySelectorAll('.faq-btn[aria-expanded="true"]').forEach(b => {
            b.setAttribute('aria-expanded', 'false');
            b.nextElementSibling.style.maxHeight = '0';

            const iconContainer = b.querySelector('.icon-container');
            iconContainer.classList.remove('bg-[#ff1443]', 'border-[#ff1443]', 'rotate-45');
            iconContainer.classList.add('border-[#e5e7eb]', 'bg-slate-50');

            const icon = b.querySelector('i.fa-plus');
            icon.classList.remove('text-white');
            icon.classList.add('text-[#6a7686]');
        });

        // Buka yang diklik (jika sebelumnya tertutup)
        if (!isOpen) {
            btn.setAttribute('aria-expanded', 'true');
            answer.style.maxHeight = answer.scrollHeight + 'px';

            const iconContainer = btn.querySelector('.icon-container');
            iconContainer.classList.remove('border-[#e5e7eb]', 'bg-slate-50');
            iconContainer.classList.add('bg-[#ff1443]', 'border-[#ff1443]', 'rotate-45');

            const icon = btn.querySelector('i.fa-plus');
            icon.classList.remove('text-[#6a7686]');
            icon.classList.add('text-white');
        }
    }

    // ── Filter per kategori ───────────────────────────────
    function filterCat(cat) {
        // Update tombol kategori
        document.querySelectorAll('.cat-card').forEach(c => {
            const isActive = c.dataset.cat === cat;
            c.classList.toggle('border-[#FF1443]', isActive);
            c.classList.toggle('bg-[rgba(255,20,67,.04)]', isActive);
            c.classList.toggle('shadow-[0_0_0_3px_rgba(255,20,67,.07)]', isActive);
            c.classList.toggle('border-gray-200', !isActive);
            c.querySelector('.cat-icon-wrap').style.background =
                isActive ? 'rgba(255,20,67,.15)' : 'rgba(255,20,67,.08)';
            c.querySelector('.cat-title').style.color = isActive ? '#FF1443' : '';
        });

        // Tampilkan/sembunyikan section FAQ
        document.querySelectorAll('.faq-section-block').forEach(sec => {
            sec.classList.toggle('hidden', cat !== 'all' && sec.dataset.cat !== cat);
        });

        // Reset search & noResult
        document.getElementById('searchInput').value = '';
        document.getElementById('noResult').classList.add('hidden');
    }

    // ── Live search ───────────────────────────────────────
    document.getElementById('searchInput').addEventListener('input', function() {
        const q = this.value.toLowerCase().trim();

        if (!q) {
            filterCat('all');
            return;
        }

        // Reset semua tombol kategori
        document.querySelectorAll('.cat-card').forEach(c => {
            c.classList.remove('border-[#FF1443]', 'bg-[rgba(255,20,67,.04)]', 'shadow-[0_0_0_3px_rgba(255,20,67,.07)]');
            c.classList.add('border-gray-200');
            c.querySelector('.cat-icon-wrap').style.background = 'rgba(255,20,67,.08)';
            c.querySelector('.cat-title').style.color = '';
        });

        let totalVisible = 0;

        document.querySelectorAll('.faq-section-block').forEach(sec => {
            let secVisible = 0;
            sec.querySelectorAll('.faq-item').forEach(item => {
                const match = (item.dataset.q || '').includes(q);
                item.style.display = match ? '' : 'none';
                if (match) secVisible++;
            });
            sec.classList.toggle('hidden', secVisible === 0);
            totalVisible += secVisible;
        });

        document.getElementById('noResult').classList.toggle('hidden', totalVisible > 0);
    });
</script>
@endpush
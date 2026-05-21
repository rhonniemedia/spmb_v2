@extends('layouts.user')

@section('title', 'Bantuan')

@section('content')

{{-- ══════════════════════════════════════════
        BREADCRUMB
══════════════════════════════════════════ --}}
<div class="flex items-center gap-1.5 text-[13px] text-[#6A7686] mb-4">
    <a href="{{ route('dashboard') }}" class="text-[#FF1443] no-underline font-semibold">
        <i class="fa-solid fa-house"></i> Beranda
    </a>
    <span class="text-gray-300">/</span>
    <a href="{{ route('dashboard') }}" class="text-[#FF1443] no-underline font-semibold">Dashboard</a>
    <span class="text-gray-300">/</span>
    <span>Pusat Bantuan</span>
</div>

{{-- ══════════════════════════════════════════
        HERO BANNER
══════════════════════════════════════════ --}}
<div class="relative rounded-[20px] overflow-hidden mb-5 p-6 md:p-7 flex flex-col md:flex-row items-center justify-between gap-5"
    style="background: linear-gradient(135deg, #FF1443 0%, #D90F38 50%, #B00F30 100%);">
    {{-- Decorative circles --}}
    <div class="absolute -top-10 -right-10 w-[200px] h-[200px] bg-white/[0.06] rounded-full pointer-events-none"></div>
    <div class="absolute -bottom-[60px] right-24 w-[160px] h-[160px] bg-white/[0.04] rounded-full pointer-events-none"></div>

    {{-- Left --}}
    <div class="relative z-10 w-full md:flex-1 text-center md:text-left">
        <div class="inline-flex items-center gap-1.5 bg-white/15 text-white text-sm font-bold px-3 py-1 rounded-full mb-3 border border-white/25">
            <i class="fa-solid fa-circle-question"></i> Pusat Bantuan SPMB
        </div>
        <h1 class="text-xl md:text-2xl font-black text-white mb-1">Ada yang bisa kami bantu?</h1>
        <p class="text-sm text-white/80 leading-relaxed mb-5 max-w-[540px]">
            Temukan jawaban seputar Sistem Penerimaan Murid Baru SMK Negeri 1.
            Cari pertanyaan kamu di kolom pencarian, atau pilih topik di bawah.
        </p>
        {{-- Search bar --}}
        <div class="relative max-w-[480px]">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-[#FF1443] text-[15px] pointer-events-none"></i>
            <input
                id="searchInput"
                type="text"
                placeholder="Cari pertanyaan… misal: syarat dokumen, jadwal seleksi"
                autocomplete="off"
                class="w-full pl-11 pr-5 py-3.5 rounded-[14px] bg-white text-[14px] font-semibold text-[#080C1A] placeholder-[#9CA3AF] border-0 focus:outline-none focus:ring-2 focus:ring-white/40">
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
        <div class="faq-section-block mb-4" data-cat="{{ $group->slug }}">
            <div class="bg-white border border-gray-200 rounded-[20px] overflow-hidden shadow-sm">
                {{-- Header Kategori --}}
                <div class="px-5 py-4 flex items-center gap-2.5" style="background: linear-gradient(135deg, #FF1443, #D90F38);">
                    <i class="fa-solid {{ $group->icon }} text-white text-[14px]"></i>
                    <span class="text-[14px] font-black text-white">{{ $group->name }}</span>
                </div>

                {{-- Looping Isi FAQ --}}
                @foreach($group->faqs as $item)
                <div class="faq-item border-t border-gray-100" data-q="{{ strtolower($item->question) }}">
                    <button type="button" class="faq-btn w-full flex items-start justify-between gap-3 px-5 py-4 text-left text-[13.5px] font-bold text-[#080C1A]" onclick="toggleFaq(this)">
                        <span>{{ $item->question }}</span>
                        <i class="fa-solid fa-chevron-down text-[#6A7686] text-[12px] mt-[3px]"></i>
                    </button>
                    <div class="faq-answer overflow-hidden" style="max-height:0;">
                        <div class="px-5 pb-4 pt-3 text-[13px] text-[#6A7686] leading-[1.75] border-t border-gray-100">
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
            <div class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">
                <div class="px-5 py-4" style="background: linear-gradient(135deg,#FF1443,#D90F38);">
                    <h3 class="text-base font-black text-white mb-0.5">Topik Bantuan</h3>
                    <p class="text-[13px] text-white/80">Klik untuk filter pertanyaan</p>
                </div>
                <div class="px-5 py-3 divide-y divide-gray-100">
                    @foreach($cats as $cat)
                    <button
                        type="button"
                        data-category="{{ $cat['key'] }}"
                        onclick="filterCat(this.dataset.category)"
                        class="w-full flex justify-between items-center py-2.5 text-left transition-colors hover:text-primary">
                        <span class="text-sm font-semibold text-[#6A7686]">
                            <i class="fa-solid {{ $cat['icon'] }} text-[#FF1443] mr-1.5"></i>
                            {{ $cat['label'] }}
                        </span>
                        <span class="text-[12px] font-bold text-[#9CA3AF]">{{ $cat['count'] }}</span>
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
            b.querySelector('i.fa-chevron-down').style.transform = '';
            b.nextElementSibling.style.maxHeight = '0';
        });

        // Buka yang diklik (jika sebelumnya tertutup)
        if (!isOpen) {
            btn.setAttribute('aria-expanded', 'true');
            btn.querySelector('i.fa-chevron-down').style.transform = 'rotate(180deg)';
            answer.style.maxHeight = answer.scrollHeight + 'px';
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
@extends('layouts.user')

@section('title', 'Pengumuman')

@section('content')

{{-- ═══════════════════════════════════════════════════
        BREADCRUMB
════════════════════════════════════════════════════ --}}
<div class="flex items-center gap-1.5 text-[13px] text-[#6A7686] mb-4">
    <a href="{{ route('dashboard') }}" class="text-primary no-underline font-semibold hover:underline">
        <i class="fa-solid fa-house"></i> Beranda
    </a>
    <span class="text-gray-300">/</span>
    <a href="{{ route('dashboard') }}" class="text-primary no-underline font-semibold hover:underline">Dashboard</a>
    <span class="text-gray-300">/</span>
    <span>Pengumuman</span>
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
            <i class="fa-solid fa-circle-dot text-[10px] animate-pulse"></i> Info SPMB 2026
        </div>
        <h1 class="text-xl md:text-2xl font-black text-white mb-1 leading-tight">
            Pengumuman Hasil Seleksi Segera Hadir!
        </h1>
        <p class="text-sm text-white/80 leading-relaxed mb-5 max-w-[540px]">
            Pantau terus halaman ini. Pengumuman penerimaan peserta didik baru akan dipublikasikan pada
            <span class="text-white font-bold">10 Juni 2026</span>.
        </p>
        <a href="#daftar-pengumuman"
            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white text-[#FF1443] text-[13px] font-black rounded-full no-underline shadow-md hover:bg-gray-50 transition-all">
            <i class="fa-solid fa-list-ul"></i> Lihat Semua Pengumuman
        </a>
    </div>

    {{-- Right: Stats Card --}}
    <div class="relative z-10 w-full md:w-auto">
        <div class="bg-white/10 border border-white/20 rounded-[16px] px-6 py-5 backdrop-blur-md text-center shadow-xl">
            <div class="text-[12px] text-white/70 font-semibold uppercase tracking-widest mb-3">Total Pengumuman</div>
            <div class="text-[36px] font-black text-white leading-none mb-1">5</div>
            <div class="text-sm text-white/70 mb-3">pengumuman aktif</div>
            <div class="inline-flex items-center justify-center gap-1.5 bg-white/15 px-3 py-1.5 rounded-full">
                <span class="pulse-dot"></span>
                <span class="text-[12px] font-bold text-white">1 baru hari ini</span>
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
        <div class="bg-white border border-gray-200 rounded-card shadow-sm p-4 flex flex-col sm:flex-row gap-3">
            {{-- Search --}}
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-[#6A7686] text-[13px]"></i>
                <input type="text" id="searchInput" placeholder="Cari pengumuman..."
                    oninput="filterCards()"
                    class="w-full pl-9 pr-4 py-2 text-[13px] border border-gray-200 rounded-full focus:outline-none focus:border-primary transition-colors bg-gray-50 placeholder:text-[#B0B8C4]">
            </div>
            {{-- Filter pills --}}
            <div class="flex items-center gap-1.5 flex-wrap" id="filterPills">
                @php
                $filters = ['Semua', 'PPDB', 'Kelulusan', 'Jadwal', 'Umum'];
                @endphp
                @foreach($filters as $f)
                <button onclick="setFilter('{{ $f }}')"
                    data-filter="{{ $f }}"
                    class="filter-pill {{ $loop->first ? 'active' : 'bg-gray-50 text-[#6A7686] border border-gray-200' }} text-[12px] font-bold px-3 py-1.5 rounded-full border whitespace-nowrap">
                    {{ $f }}
                </button>
                @endforeach
            </div>
        </div>

        {{-- ── DAFTAR KARTU PENGUMUMAN ── --}}
        @php
        $announcements = [
        [
        'id' => 1,
        'kategori' => 'PPDB',
        'katColor' => 'bg-red-50 text-primary border-primary/20',
        'katDot' => 'bg-primary',
        'icon' => 'fa-bullhorn',
        'iconBg' => 'bg-red-50 text-primary',
        'judul' => 'Pengumuman Resmi Pembukaan PPDB 2026/2027',
        'ringkas' => 'Pendaftaran peserta didik baru jalur zonasi dan prestasi resmi dibuka. Kuota terbatas, segera lengkapi berkas dan daftarkan diri Anda sebelum batas waktu yang ditentukan.',
        'tanggal' => '12 Mei 2026',
        'waktu' => 'Hari ini, 08:30 WIB',
        'badge' => ['label' => 'BARU', 'class' => 'bg-primary/10 text-primary border-primary/20'],
        'penting' => false,
        'border' => 'border-primary/30',
        ],
        [
        'id' => 2,
        'kategori' => 'Kelulusan',
        'katColor' => 'bg-green-50 text-green-700 border-green-200',
        'katDot' => 'bg-green-500',
        'icon' => 'fa-award',
        'iconBg' => 'bg-green-50 text-green-600',
        'judul' => 'Pengumuman Hasil Kelulusan Tahun Pelajaran 2025/2026',
        'ringkas' => 'Seluruh siswa kelas XII dinyatakan lulus ujian akhir tahun pelajaran 2025/2026. Sertifikat kelulusan dapat diambil mulai 20 Mei 2026 di sekretariat sekolah.',
        'tanggal' => '02 Mei 2026',
        'waktu' => '10 hari lalu',
        'badge' => ['label' => 'PENTING', 'class' => 'bg-amber-50 text-amber-600 border-amber-200'],
        'penting' => true,
        'border' => 'border-amber-300',
        ],
        [
        'id' => 3,
        'kategori' => 'Jadwal',
        'katColor' => 'bg-blue-50 text-blue-700 border-blue-200',
        'katDot' => 'bg-blue-500',
        'icon' => 'fa-calendar-check',
        'iconBg' => 'bg-blue-50 text-blue-600',
        'judul' => 'Jadwal Pelaksanaan Yudisium dan Wisuda 2026',
        'ringkas' => 'Yudisium akan dilaksanakan pada 5 Juni 2026 di Aula Utama, diikuti prosesi wisuda pada 12 Juni 2026. Peserta wajib hadir tepat waktu dengan mengenakan seragam resmi.',
        'tanggal' => '28 Apr 2026',
        'waktu' => '14 hari lalu',
        'badge' => null,
        'penting' => false,
        'border' => 'border-gray-200',
        ],
        [
        'id' => 4,
        'kategori' => 'PPDB',
        'katColor' => 'bg-red-50 text-primary border-primary/20',
        'katDot' => 'bg-primary',
        'icon' => 'fa-file-lines',
        'iconBg' => 'bg-red-50 text-primary',
        'judul' => 'Persyaratan dan Dokumen Wajib PPDB 2026',
        'ringkas' => 'Daftar lengkap dokumen yang harus disiapkan calon peserta didik baru, termasuk akta kelahiran, ijazah SMP, rapor, dan pas foto terbaru.',
        'tanggal' => '20 Apr 2026',
        'waktu' => '22 hari lalu',
        'badge' => null,
        'penting' => false,
        'border' => 'border-gray-200',
        ],
        [
        'id' => 5,
        'kategori' => 'Umum',
        'katColor' => 'bg-gray-100 text-[#6A7686] border-gray-200',
        'katDot' => 'bg-gray-400',
        'icon' => 'fa-circle-info',
        'iconBg' => 'bg-gray-100 text-[#6A7686]',
        'judul' => 'Informasi Libur Sekolah dan Kegiatan Akhir Tahun',
        'ringkas' => 'Sekolah akan libur pada 15–16 Mei 2026 dalam rangka peringatan Hari Raya. Seluruh kegiatan administrasi PPDB tetap berjalan melalui portal online.',
        'tanggal' => '10 Apr 2026',
        'waktu' => '1 bulan lalu',
        'badge' => null,
        'penting' => false,
        'border' => 'border-gray-200',
        ],
        ];
        @endphp

        <div class="space-y-4" id="annList">
            @foreach($announcements as $ann)
            <div class="ann-card bg-white border {{ $ann['border'] }} {{ $ann['penting'] ? 'border-l-4 border-l-amber-400' : '' }} rounded-[20px] overflow-hidden shadow-[0_1px_6px_rgba(0,0,0,0.04)]"
                data-kategori="{{ $ann['kategori'] }}"
                data-judul="{{ strtolower($ann['judul']) }} {{ strtolower($ann['ringkas']) }}">

                <div class="p-5 flex gap-4">
                    {{-- Ikon kategori --}}
                    <div class="w-12 h-12 rounded-[14px] {{ $ann['iconBg'] }} flex items-center justify-center flex-shrink-0 text-[18px]">
                        <i class="fa-solid {{ $ann['icon'] }}"></i>
                    </div>

                    {{-- Konten --}}
                    <div class="flex-1 min-w-0">
                        {{-- Badge + tanggal --}}
                        <div class="flex items-center gap-2 flex-wrap mb-2">
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-black px-2.5 py-1 rounded-full border uppercase tracking-wide {{ $ann['katColor'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $ann['katDot'] }} flex-shrink-0"></span>
                                {{ $ann['kategori'] }}
                            </span>
                            @if($ann['badge'])
                            <span class="inline-flex items-center text-[11px] font-black px-2.5 py-1 rounded-full border {{ $ann['badge']['class'] }}">
                                {{ $ann['badge']['label'] }}
                            </span>
                            @endif
                            <span class="text-[12px] text-[#B0B8C4] font-semibold ml-auto flex items-center gap-1">
                                <i class="fa-regular fa-clock text-[11px]"></i> {{ $ann['waktu'] }}
                            </span>
                        </div>

                        {{-- Judul --}}
                        <h3 class="text-[15px] font-black text-[#080C1A] leading-snug mb-1.5 group-hover:text-primary transition-colors">
                            {{ $ann['judul'] }}
                        </h3>

                        {{-- Ringkasan --}}
                        <p class="text-[13px] text-[#6A7686] leading-relaxed line-clamp-2">
                            {{ $ann['ringkas'] }}
                        </p>

                        {{-- Footer kartu --}}
                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-50">
                            <span class="text-[12px] text-[#B0B8C4] font-semibold flex items-center gap-1.5">
                                <i class="fa-regular fa-calendar text-[11px]"></i> {{ $ann['tanggal'] }}
                            </span>
                            <a href="#" class="inline-flex items-center gap-1.5 text-[13px] font-black text-primary hover:underline">
                                Baca Selengkapnya <i class="fa-solid fa-arrow-right text-[11px]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Empty state (tersembunyi secara default) --}}
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
        <div class="bg-white border border-gray-200 rounded-card shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="text-base font-black text-[#080C1A]">Ringkasan</h3>
                <p class="text-[13px] text-[#6A7686] font-medium">Jumlah per kategori</p>
            </div>
            <div class="p-5 space-y-3">
                @php
                $summary = [
                ['label' => 'PPDB', 'count' => 2, 'total' => 5, 'color' => 'bg-primary', 'text' => 'text-primary'],
                ['label' => 'Kelulusan', 'count' => 1, 'total' => 5, 'color' => 'bg-green-500', 'text' => 'text-green-600'],
                ['label' => 'Jadwal', 'count' => 1, 'total' => 5, 'color' => 'bg-blue-500', 'text' => 'text-blue-600'],
                ['label' => 'Umum', 'count' => 1, 'total' => 5, 'color' => 'bg-gray-400', 'text' => 'text-[#6A7686]'],
                ];
                @endphp
                @foreach($summary as $s)
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-[13px] font-bold text-[#080C1A]">{{ $s['label'] }}</span>
                        <span class="text-[12px] font-black {{ $s['text'] }}">{{ $s['count'] }} pengumuman</span>
                    </div>
                    <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="{{ $s['color'] }} h-full rounded-full transition-all duration-500"
                            style="width: {{ ($s['count'] / $s['total']) * 100 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Highlight — Wajib Dibaca --}}
        <div class="bg-white border border-gray-200 rounded-card shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-base font-black text-[#080C1A]">Wajib Dibaca</h3>
                <span class="text-[12px] font-black text-amber-600 bg-amber-50 border border-amber-200 px-2.5 py-1 rounded-full uppercase">Penting</span>
            </div>
            <div class="p-5">
                <div class="flex gap-3 mb-3">
                    <div class="w-10 h-10 rounded-[12px] bg-green-50 text-green-600 flex items-center justify-center flex-shrink-0 text-[16px]">
                        <i class="fa-solid fa-award"></i>
                    </div>
                    <div>
                        <span class="text-[11px] font-black text-green-700 bg-green-50 border border-green-200 px-2 py-0.5 rounded-full uppercase tracking-wide">Kelulusan</span>
                        <h4 class="text-[14px] font-black text-[#080C1A] leading-snug mt-1">
                            Pengumuman Hasil Kelulusan TP 2025/2026
                        </h4>
                    </div>
                </div>
                <p class="text-[13px] text-[#6A7686] leading-relaxed mb-3">
                    Seluruh siswa kelas XII dinyatakan lulus. Sertifikat dapat diambil mulai <strong class="text-[#080C1A]">20 Mei 2026</strong>.
                </p>
                <a href="#" class="inline-flex w-full items-center justify-center gap-2 px-4 py-[9px] rounded-full text-[13px] font-black text-primary bg-primary/5 border border-primary/20 no-underline hover:bg-primary/10 transition-all">
                    <i class="fa-solid fa-arrow-up-right-from-square text-[11px]"></i> Buka Pengumuman
                </a>
            </div>
        </div>

        {{-- Butuh Bantuan --}}
        <div class="bg-white border border-gray-200 rounded-card shadow-sm overflow-hidden">
            <div class="px-5 py-4">
                <p class="text-[13px] font-bold text-[#080C1A] flex items-center gap-1.5 mb-1">
                    <i class="fa-solid fa-circle-question text-primary text-[14px]"></i> Butuh Bantuan?
                </p>
                <p class="text-[13px] text-[#6A7686] leading-relaxed mb-3">
                    Panitia SPMB siap membantu selama jam kerja <strong class="text-[#080C1A]">08:00–16:00 WIB</strong>.
                </p>
                <a href="https://wa.me/6281234567890" target="_blank"
                    class="inline-flex w-full items-center justify-center gap-[7px] px-4 py-[9px] rounded-full text-[14px] font-bold no-underline bg-white border-[1.5px] border-[#25D366] text-[#25D366] hover:-translate-y-px transition-all">
                    <i class="fa-brands fa-whatsapp text-[15px]"></i> Chat WhatsApp Panitia
                </a>
            </div>
        </div>

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
            p.classList.toggle('active', p.dataset.filter === f);
            if (p.dataset.filter !== f) {
                p.classList.add('bg-gray-50', 'text-[#6A7686]', 'border-gray-200');
            } else {
                p.classList.remove('bg-gray-50', 'text-[#6A7686]', 'border-gray-200');
            }
        });
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
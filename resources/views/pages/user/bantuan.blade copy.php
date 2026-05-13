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
            @foreach($cats as $cat)
            <button
                type="button"
                onclick="filterCat('{{ $cat['key'] }}')"
                data-cat="{{ $cat['key'] }}"
                class="cat-card flex flex-col items-center gap-2 bg-white border rounded-[16px] px-2 py-3.5 text-center transition-all hover:-translate-y-px
                    {{ $loop->first ? 'border-[#FF1443] bg-[rgba(255,20,67,.04)] shadow-[0_0_0_3px_rgba(255,20,67,.07)]' : 'border-gray-200 hover:border-[#FF1443] hover:shadow-[0_0_0_3px_rgba(255,20,67,.07)]' }}">
                <div class="cat-icon-wrap w-9 h-9 rounded-[10px] flex items-center justify-center
                    {{ $loop->first ? 'bg-[rgba(255,20,67,.15)]' : 'bg-[rgba(255,20,67,.08)]' }}">
                    <i class="fa-solid {{ $cat['icon'] }} text-[#FF1443] text-[15px]"></i>
                </div>
                <div>
                    <div class="cat-title text-[12px] font-bold leading-tight
                        {{ $loop->first ? 'text-[#FF1443]' : 'text-[#080C1A]' }}">
                        {{ $cat['label'] }}
                    </div>
                    <div class="text-[11px] text-[#6A7686]">{{ $cat['count'] }} FAQ</div>
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

        @php
        $faqGroups = [
        [
        'cat' => 'pendaftaran',
        'icon' => 'fa-user-plus',
        'title' => 'Pendaftaran',
        'items' => [
        ['q' => 'Siapa saja yang bisa mendaftar SPMB?',
        'a' => 'Calon peserta didik yang telah lulus atau sedang menempuh kelas 9 SMP/MTs/sederajat dengan nilai rapor memenuhi syarat minimum. Usia maksimal <strong>21 tahun</strong> per 1 Juli tahun ajaran baru.'],
        ['q' => 'Bagaimana cara membuat akun pendaftaran?',
        'a' => 'Kunjungi halaman utama SPMB, klik <strong>Daftar Sekarang</strong>, masukkan nomor NISN dan tanggal lahir. Sistem akan mengirimkan kode OTP ke nomor HP yang terdaftar di Data Pokok Pendidikan (Dapodik).'],
        ['q' => 'Berapa jurusan yang bisa dipilih?',
        'a' => 'Setiap calon peserta dapat memilih <strong>maksimal 2 jurusan</strong>. Pastikan pilihan pertama adalah jurusan yang paling diminati karena seleksi diprioritaskan berdasarkan pilihan pertama.'],
        ['q' => 'Apakah pendaftaran dikenakan biaya?',
        'a' => 'Pendaftaran <strong>tidak dipungut biaya apapun (gratis)</strong>. Jika ada pihak yang meminta pembayaran di luar ketentuan resmi, segera laporkan ke panitia SPMB.'],
        ['q' => 'Kapan batas akhir pendaftaran?',
        'a' => 'Pendaftaran dibuka <strong>1–31 Mei 2026</strong>. Setelah tanggal tersebut sistem otomatis menutup dan tidak ada perpanjangan. Pastikan semua data terisi sebelum batas waktu.'],
        ],
        ],
        [
        'cat' => 'biodata',
        'icon' => 'fa-id-card',
        'title' => 'Pengisian Biodata',
        'items' => [
        ['q' => 'Dokumen apa saja yang harus diunggah?',
        'a' => 'Lima dokumen wajib: <strong>Akta Kelahiran</strong>, <strong>Ijazah/SKL SMP</strong>, <strong>Rapor semester 1–5</strong>, <strong>Pas foto terbaru</strong> (3×4, latar merah), dan <strong>sertifikat prestasi</strong> (jika ada). Format: JPG/PNG/PDF, maks 2 MB per file.'],
        ['q' => 'Bolehkah data biodata diedit setelah disimpan?',
        'a' => 'Data dapat diedit <strong>selama status masih "Draft"</strong>. Setelah kamu klik <em>Kirim Biodata</em>, data terkunci dan hanya bisa diubah dengan menghubungi panitia secara langsung.'],
        ['q' => 'Bagaimana jika upload dokumen gagal terus?',
        'a' => 'Pastikan ukuran file <strong>di bawah 2 MB</strong> dan format JPG, PNG, atau PDF. Kompres gambar menggunakan tools seperti ilovepdf.com atau squoosh.app, lalu coba unggah kembali.'],
        ['q' => 'Apa fungsi tombol "Simpan Draft"?',
        'a' => 'Tombol Simpan Draft menyimpan progres pengisian <strong>tanpa mengirimkan data</strong> ke panitia. Data akan tersimpan dan bisa dilanjutkan kapan saja sebelum batas waktu pendaftaran.'],
        ],
        ],
        [
        'cat' => 'seleksi',
        'icon' => 'fa-ranking-star',
        'title' => 'Proses Seleksi',
        'items' => [
        ['q' => 'Apa saja kriteria seleksi penerimaan?',
        'a' => 'Seleksi menggunakan 3 komponen: <strong>nilai rapor rata-rata (60%)</strong>, <strong>tes akademik online (30%)</strong>, dan <strong>prestasi/sertifikat (10%)</strong>. Peserta dengan nilai tertinggi diterima sesuai kuota jurusan.'],
        ['q' => 'Kapan jadwal tes seleksi dilaksanakan?',
        'a' => 'Tes seleksi akademik dijadwalkan pada <strong>5–7 Juni 2026</strong> secara online melalui sistem SPMB. Peserta akan mendapatkan notifikasi jadwal dan token tes melalui email dan dashboard.'],
        ['q' => 'Kapan pengumuman hasil seleksi?',
        'a' => 'Hasil seleksi diumumkan pada <strong>10 Juni 2026 pukul 08.00 WIB</strong> melalui dashboard peserta. Peserta diterima akan mendapat notifikasi untuk melanjutkan proses daftar ulang.'],
        ['q' => 'Apakah ada jalur khusus atau beasiswa?',
        'a' => 'Ada <strong>Jalur Prestasi</strong> untuk calon peserta dengan piagam kejuaraan tingkat kabupaten/kota ke atas, dan <strong>Jalur Afirmasi</strong> untuk peserta dari keluarga kurang mampu (dilengkapi DTKS atau SKTM).'],
        ],
        ],
        [
        'cat' => 'daftarulang',
        'icon' => 'fa-rotate-right',
        'title' => 'Daftar Ulang',
        'items' => [
        ['q' => 'Apa saja tahapan dalam proses daftar ulang?',
        'a' => 'Ada 5 tahap: <strong>(1)</strong> Konfirmasi kehadiran, <strong>(2)</strong> Pemilihan seragam, <strong>(3)</strong> Pembayaran biaya daftar ulang, <strong>(4)</strong> Pilih jadwal kehadiran ke sekolah, <strong>(5)</strong> Tanda tangan pernyataan &amp; kirim. Semua dilakukan secara online.'],
        ['q' => 'Batas waktu daftar ulang sampai kapan?',
        'a' => 'Daftar ulang harus diselesaikan paling lambat <strong>15 Juni 2026</strong>. Peserta yang tidak menyelesaikan tepat waktu dianggap mengundurkan diri dan kursi diberikan ke peserta cadangan.'],
        ['q' => 'Apa yang terjadi jika tidak bisa hadir pada jadwal yang dipilih?',
        'a' => 'Perubahan jadwal bisa dilakukan <strong>maksimal H-1 sebelum jadwal</strong> dengan menghubungi panitia via WhatsApp. Di luar itu, hubungi operator sekolah langsung untuk konfirmasi ulang.'],
        ['q' => 'Dokumen apa yang dibawa saat hadir ke sekolah?',
        'a' => 'Bawa dokumen asli: <strong>Ijazah/SKL</strong>, <strong>Akta Kelahiran</strong>, <strong>Kartu Keluarga</strong>, <strong>pas foto 3×4 (5 lembar, latar merah)</strong>, dan <strong>bukti pembayaran daftar ulang</strong> yang sudah dicetak.'],
        ['q' => 'Apakah orang tua wajib hadir saat daftar ulang?',
        'a' => '<strong>Ya, wajib.</strong> Setidaknya salah satu orang tua atau wali yang terdaftar di biodata harus hadir untuk menandatangani surat pernyataan orang tua/wali di hadapan panitia.'],
        ],
        ],
        [
        'cat' => 'pembayaran',
        'icon' => 'fa-credit-card',
        'title' => 'Pembayaran',
        'items' => [
        ['q' => 'Metode pembayaran apa yang tersedia?',
        'a' => 'Tersedia 3 metode: <strong>Transfer Bank</strong> (BNI/BRI/Mandiri/BCA), <strong>QRIS</strong> (scan dari semua e-wallet), dan <strong>Virtual Account</strong> otomatis yang digenerate sistem. Bukti transfer wajib diunggah.'],
        ['q' => 'Berapa total biaya yang harus dibayar?',
        'a' => 'Rincian biaya: Daftar ulang <strong>Rp 250.000</strong>, Seragam wajib <strong>Rp 650.000</strong>, Perlengkapan <strong>±Rp 145.000</strong>, SPP pertama <strong>Rp 200.000</strong>. <strong>Total estimasi Rp 1.245.000</strong> (bisa berbeda sesuai pilihan seragam).'],
        ['q' => 'Bagaimana jika bukti pembayaran hilang atau tidak tersimpan?',
        'a' => 'Hubungi panitia via WhatsApp dengan menyertakan <strong>nomor peserta</strong> dan <strong>tanggal transaksi</strong>. Panitia akan memverifikasi pembayaran melalui rekening koran sekolah.'],
        ['q' => 'Apakah ada cicilan atau keringanan biaya?',
        'a' => 'Peserta dari keluarga tidak mampu (pemegang KIP/DTKS) dapat mengajukan <strong>keringanan biaya</strong> dengan menyerahkan surat permohonan ke TU sekolah. Keputusan keringanan ditentukan kepala sekolah.'],
        ],
        ],
        ];
        @endphp

        @foreach($faqGroups as $group)
        <div class="faq-section-block mb-4" data-cat="{{ $group['cat'] }}">
            <div class="bg-white border border-gray-200 rounded-[20px] overflow-hidden shadow-sm">

                {{-- Group header --}}
                <div class="px-5 py-4 flex items-center gap-2.5"
                    style="background: linear-gradient(135deg, #FF1443, #D90F38);">
                    <i class="fa-solid {{ $group['icon'] }} text-white text-[14px]"></i>
                    <span class="text-[14px] font-black text-white">{{ $group['title'] }}</span>
                </div>

                {{-- FAQ items --}}
                @foreach($group['items'] as $item)
                <div class="faq-item border-t border-gray-100"
                    data-q="{{ strtolower($item['q']) }} {{ strtolower(strip_tags($item['a'])) }}">
                    <button
                        type="button"
                        class="faq-btn w-full flex items-start justify-between gap-3 px-5 py-4 text-left text-[13.5px] font-bold text-[#080C1A] hover:bg-gray-50/70 transition-colors"
                        onclick="toggleFaq(this)"
                        aria-expanded="false">
                        <span class="leading-snug">{{ $item['q'] }}</span>
                        <i class="fa-solid fa-chevron-down text-[#6A7686] text-[12px] mt-[3px] flex-shrink-0 transition-transform duration-200"></i>
                    </button>
                    <div class="faq-answer overflow-hidden" style="max-height:0; transition: max-height .3s ease;">
                        <div class="px-5 pb-4 pt-3 text-[13px] text-[#6A7686] leading-[1.75] border-t border-gray-100">
                            {!! $item['a'] !!}
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

            @php
            $steps = [
            ['no' => 1, 'status' => 'done', 'title' => 'Pendaftaran Akun', 'desc' => 'Buat akun dengan NISN + verifikasi OTP. Pilih jurusan 1 & 2.', 'period' => '1 – 31 Mei 2026', 'badge' => 'Selesai'],
            ['no' => 2, 'status' => 'done', 'title' => 'Pengisian Biodata', 'desc' => 'Isi 6 langkah data diri, orang tua, riwayat pendidikan & unggah dokumen.', 'period' => 'S.d. 31 Mei 2026', 'badge' => 'Selesai'],
            ['no' => 3, 'status' => 'active', 'title' => 'Seleksi Akademik', 'desc' => 'Tes online berbasis nilai rapor dan soal akademik secara daring.', 'period' => '5 – 7 Juni 2026', 'badge' => 'Sedang berlangsung'],
            ['no' => 4, 'status' => 'pending', 'title' => 'Pengumuman Hasil', 'desc' => 'Cek status diterima/tidak langsung di dashboard peserta.', 'period' => '10 Juni 2026', 'badge' => 'Menunggu'],
            ['no' => 5, 'status' => 'pending', 'title' => 'Daftar Ulang', 'desc' => 'Bayar biaya, pilih seragam, dan tentukan jadwal hadir ke sekolah.', 'period' => '11 – 15 Juni 2026', 'badge' => 'Menunggu'],
            ];
            @endphp

            @foreach($steps as $step)
            <div class="flex gap-4 relative {{ !$loop->last ? 'pb-5' : '' }}">

                {{-- Connector line --}}
                @if(!$loop->last)
                <div class="absolute left-[15px] top-[36px] bottom-0 w-0.5 bg-gray-200"></div>
                @endif

                {{-- Dot --}}
                @if($step['status'] === 'done')
                <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center z-10 bg-green-100 border-2 border-green-500">
                    <i class="fa-solid fa-check text-green-600 text-[11px]"></i>
                </div>
                @elseif($step['status'] === 'active')
                <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center z-10 text-white text-[13px] font-black"
                    style="background: linear-gradient(135deg,#FF1443,#D90F38)">
                    {{ $step['no'] }}
                </div>
                @else
                <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center z-10 bg-gray-100 border-2 border-gray-200 text-[#6A7686] text-[13px] font-black">
                    {{ $step['no'] }}
                </div>
                @endif

                {{-- Content --}}
                <div class="flex-1 pt-0.5">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 mb-0.5">
                        <span class="text-[14px] font-black {{ $step['status'] === 'active' ? 'text-[#FF1443]' : 'text-[#080C1A]' }}">
                            {{ $step['title'] }}
                        </span>
                        @if($step['status'] === 'done')
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-green-50 text-green-700">
                            <i class="fa-solid fa-check text-[9px]"></i> {{ $step['badge'] }}
                        </span>
                        @elseif($step['status'] === 'active')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold text-[#FF1443]"
                            style="background:rgba(255,20,67,.1)">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#FF1443] animate-pulse inline-block"></span>
                            {{ $step['badge'] }}
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-gray-100 text-[#6A7686]">
                            {{ $step['badge'] }}
                        </span>
                        @endif
                    </div>
                    <p class="text-[12.5px] text-[#6A7686] leading-relaxed mb-0.5">{{ $step['desc'] }}</p>
                    <span class="text-[11px] font-semibold text-[#9CA3AF]">
                        <i class="fa-regular fa-calendar mr-1"></i>{{ $step['period'] }}
                    </span>
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
                        onclick="filterCat('{{ $cat['key'] }}')"
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
            <div class="bg-white border border-gray-200 rounded-[20px] overflow-hidden shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
                <div class="px-[18px] py-[14px]">
                    <p class="text-[14px] font-bold mb-2 flex items-center gap-[6px]">
                        <i class="fa-solid fa-circle-question text-[14px] text-[#FF1443]"></i> Butuh Bantuan Langsung?
                    </p>
                    <p class="text-[13px] text-[#6A7686] leading-relaxed mb-3">
                        Panitia SPMB siap membantu selama jam kerja <strong class="text-[#080C1A]">08:00–16:00 WIB</strong>.
                    </p>
                    <a href="https://wa.me/6281234567890" target="_blank"
                        class="inline-flex w-full items-center justify-center gap-[7px] px-4 py-[9px] rounded-full font-sans text-[14px] font-bold no-underline bg-white border-[1.5px] border-[#25D366] text-[#25D366] hover:-translate-y-px transition-all">
                        <i class="fa-brands fa-whatsapp text-[15px]"></i> Chat WhatsApp Panitia
                    </a>
                    <a href="mailto:spmb@smkn1.sch.id"
                        class="mt-2 inline-flex w-full items-center justify-center gap-[7px] px-4 py-[9px] rounded-full font-sans text-[14px] font-bold no-underline bg-white border-[1.5px] border-[#FF1443] text-[#FF1443] hover:-translate-y-px transition-all">
                        <i class="fa-solid fa-envelope text-[13px]"></i> Kirim Email Panitia
                    </a>
                </div>
                <div class="border-t border-gray-100 bg-gray-50/50 px-[18px] py-3">
                    <p class="text-sm text-[#6A7686] leading-relaxed">
                        <i class="fa-regular fa-clock text-[#FF1443] mr-1"></i>
                        <strong class="text-[#080C1A]">Jam Operasional:</strong><br>
                        Senin–Jumat 08:00–16:00 WIB<br>
                        Sabtu 08:00–12:00 WIB · Libur: Tutup
                    </p>
                </div>
            </div>

            {{-- Peringatan --}}
            <div class="bg-[#FFF8F9] border border-[rgba(255,20,67,.15)] rounded-[16px] px-4 py-3.5 flex items-start gap-2.5">
                <i class="fa-solid fa-triangle-exclamation text-[#FF1443] text-[13px] mt-0.5 flex-shrink-0"></i>
                <p class="text-[12px] text-[#6A7686] leading-relaxed">
                    <strong class="text-[#080C1A]">Perhatian:</strong>
                    Panitia tidak pernah meminta biaya pendaftaran. Waspada penipuan yang mengatasnamakan SPMB.
                </p>
            </div>

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
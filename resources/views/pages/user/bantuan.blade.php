<x-app-layout>
    <x-slot name="title">Pusat Bantuan — SPMB SMK Negeri 1</x-slot>

    {{-- ═══════════════════════════════════════════════════
         STYLE: scrollbar, accordion, timeline, animasi
    ════════════════════════════════════════════════════ --}}
    @push('styles')
    <style>
        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f1f3f6; }
        ::-webkit-scrollbar-thumb { background: #ff1443; border-radius: 3px; }

        /* Fade-in */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeInUp .35s ease both; }
        .delay-1 { animation-delay: .05s; }
        .delay-2 { animation-delay: .10s; }
        .delay-3 { animation-delay: .15s; }
        .delay-4 { animation-delay: .20s; }

        /* Kategori card */
        .cat-card {
            cursor: pointer;
            transition: border-color .18s, box-shadow .18s, background .18s;
        }
        .cat-card:hover {
            border-color: #FF1443 !important;
            box-shadow: 0 0 0 3px rgba(255,20,67,.07);
        }
        .cat-card.active {
            border-color: #FF1443 !important;
            background: rgba(255,20,67,.04);
        }
        .cat-card.active .cat-icon-wrap {
            background: rgba(255,20,67,.15);
        }
        .cat-card.active .cat-title {
            color: #FF1443;
        }

        /* FAQ accordion */
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height .32s ease, padding .22s ease;
        }
        .faq-answer.open {
            max-height: 400px;
        }
        .faq-btn .arrow-icon {
            transition: transform .22s ease;
            flex-shrink: 0;
        }
        .faq-btn.open .arrow-icon {
            transform: rotate(180deg);
        }
        .faq-btn.open {
            color: #FF1443;
        }
        .faq-section-block { transition: opacity .2s; }
        .faq-section-block.hidden { display: none; }

        /* Timeline connector */
        .timeline-step { position: relative; }
        .timeline-step:not(:last-child)::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 36px;
            bottom: 0;
            width: 2px;
            background: #E5E7EB;
        }

        /* Highlight search match */
        mark {
            background: rgba(255,20,67,.15);
            color: #D90F38;
            border-radius: 3px;
            padding: 0 2px;
        }

        /* Contact card hover */
        .contact-btn { transition: transform .15s, box-shadow .15s; }
        .contact-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,.08); }

        /* Search focus glow */
        #searchInput:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(255,255,255,.4);
        }

        /* No-result */
        #noResult { display: none; }
    </style>
    @endpush

    <div class="max-w-[900px] mx-auto pb-20">

        {{-- ══════════════════════════════════════════
             BREADCRUMB
        ══════════════════════════════════════════ --}}
        <div class="animate-fade-in flex items-center gap-1.5 text-[13px] text-[#6A7686] mb-4">
            <a href="{{ route('dashboard') }}" class="text-[#FF1443] no-underline font-semibold">
                <i class="fa-solid fa-house"></i> Beranda
            </a>
            <span class="text-gray-300">/</span>
            <a href="{{ route('dashboard') }}" class="text-[#FF1443] no-underline font-semibold">Dashboard</a>
            <span class="text-gray-300">/</span>
            <span>Pusat Bantuan</span>
        </div>

        {{-- ══════════════════════════════════════════
             HERO BANNER + SEARCH
        ══════════════════════════════════════════ --}}
        <div class="animate-fade-in delay-1 relative rounded-[20px] overflow-hidden mb-6 p-7 md:p-9"
             style="background: linear-gradient(135deg, #FF1443 0%, #D90F38 50%, #B00F30 100%);">
            {{-- Decorative circles --}}
            <div class="absolute -top-10 -right-10 w-[200px] h-[200px] bg-white/[0.06] rounded-full pointer-events-none"></div>
            <div class="absolute -bottom-[60px] right-24 w-[160px] h-[160px] bg-white/[0.04] rounded-full pointer-events-none"></div>
            <div class="absolute top-1/2 -translate-y-1/2 -left-8 w-[120px] h-[120px] bg-white/[0.04] rounded-full pointer-events-none"></div>

            <div class="relative z-10">
                <div class="inline-flex items-center gap-1.5 bg-white/15 text-white text-xs font-bold px-3 py-1 rounded-full mb-4 border border-white/25">
                    <i class="fa-solid fa-circle-question"></i> Pusat Bantuan SPMB
                </div>
                <h1 class="text-2xl md:text-3xl font-black text-white mb-2 leading-tight">
                    Ada yang bisa kami bantu?
                </h1>
                <p class="text-[14px] text-white/80 leading-relaxed mb-6 max-w-[520px]">
                    Temukan jawaban seputar Sistem Penerimaan Murid Baru SMK Negeri 1.
                    Cari pertanyaan kamu, atau pilih topik di bawah.
                </p>

                {{-- Search bar --}}
                <div class="relative max-w-[540px]">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-[#FF1443] text-[15px] pointer-events-none"></i>
                    <input
                        id="searchInput"
                        type="text"
                        placeholder="Cari pertanyaan… misal: syarat dokumen, jadwal seleksi"
                        autocomplete="off"
                        class="w-full pl-11 pr-5 py-3.5 rounded-[14px] bg-white text-[14px] font-semibold text-[#080C1A] placeholder-[#9CA3AF] border-0"
                    >
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════
             KATEGORI CEPAT
        ══════════════════════════════════════════ --}}
        <div class="animate-fade-in delay-2 mb-7">
            <p class="text-[11px] font-bold text-[#6A7686] uppercase tracking-[.06em] mb-3">Pilih topik bantuan</p>
            <div class="grid grid-cols-3 sm:grid-cols-6 gap-3" id="catGrid">
                @php
                $cats = [
                    ['key'=>'all',        'icon'=>'fa-layer-group',    'label'=>'Semua',       'count'=>22],
                    ['key'=>'pendaftaran','icon'=>'fa-user-plus',      'label'=>'Pendaftaran', 'count'=>5],
                    ['key'=>'biodata',    'icon'=>'fa-id-card',        'label'=>'Biodata',     'count'=>4],
                    ['key'=>'seleksi',    'icon'=>'fa-ranking-star',   'label'=>'Seleksi',     'count'=>4],
                    ['key'=>'daftarulang','icon'=>'fa-rotate-right',   'label'=>'Daftar Ulang','count'=>5],
                    ['key'=>'pembayaran', 'icon'=>'fa-credit-card',    'label'=>'Pembayaran',  'count'=>4],
                ];
                @endphp
                @foreach($cats as $cat)
                <button
                    type="button"
                    onclick="filterCat('{{ $cat['key'] }}')"
                    data-cat="{{ $cat['key'] }}"
                    class="cat-card flex flex-col items-center gap-2 bg-white border border-[#E5E7EB] rounded-[16px] px-2 py-3.5 text-center {{ $loop->first ? 'active' : '' }}">
                    <div class="cat-icon-wrap w-9 h-9 rounded-[10px] {{ $loop->first ? 'bg-[rgba(255,20,67,.15)]' : 'bg-[rgba(255,20,67,.08)]' }} flex items-center justify-center">
                        <i class="fa-solid {{ $cat['icon'] }} text-[#FF1443] text-[15px]"></i>
                    </div>
                    <div>
                        <div class="cat-title text-[12px] font-bold text-[#080C1A] leading-tight {{ $loop->first ? 'text-[#FF1443]' : '' }}">{{ $cat['label'] }}</div>
                        <div class="text-[11px] text-[#6A7686]">{{ $cat['count'] }} FAQ</div>
                    </div>
                </button>
                @endforeach
            </div>
        </div>

        {{-- ══════════════════════════════════════════
             FAQ SECTIONS
        ══════════════════════════════════════════ --}}
        <div class="animate-fade-in delay-3 mb-7">
            <p class="text-[11px] font-bold text-[#6A7686] uppercase tracking-[.06em] mb-3">Pertanyaan yang sering ditanyakan</p>

            {{-- No result --}}
            <div id="noResult" class="bg-white border border-[#E5E7EB] rounded-[20px] px-6 py-12 text-center shadow-sm">
                <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-magnifying-glass text-gray-400 text-xl"></i>
                </div>
                <p class="text-[15px] font-bold text-[#080C1A] mb-1">Pertanyaan tidak ditemukan</p>
                <p class="text-[13px] text-[#6A7686]">Coba kata kunci lain, atau hubungi panitia langsung via WhatsApp.</p>
            </div>

            @php
            $faqGroups = [
                [
                    'cat'   => 'pendaftaran',
                    'icon'  => 'fa-user-plus',
                    'title' => 'Pendaftaran',
                    'items' => [
                        [
                            'q' => 'Siapa saja yang bisa mendaftar SPMB?',
                            'a' => 'Calon peserta didik yang telah lulus atau sedang menempuh kelas 9 SMP/MTs/sederajat dengan nilai rapor memenuhi syarat minimum. Usia maksimal <strong>21 tahun</strong> per 1 Juli tahun ajaran baru.',
                        ],
                        [
                            'q' => 'Bagaimana cara membuat akun pendaftaran?',
                            'a' => 'Kunjungi halaman utama SPMB, klik <strong>Daftar Sekarang</strong>, masukkan nomor NISN dan tanggal lahir. Sistem akan mengirimkan kode OTP ke nomor HP yang terdaftar di Data Pokok Pendidikan (Dapodik).',
                        ],
                        [
                            'q' => 'Berapa jurusan yang bisa dipilih?',
                            'a' => 'Setiap calon peserta dapat memilih <strong>maksimal 2 jurusan</strong>. Pastikan pilihan pertama adalah jurusan yang paling diminati karena seleksi diprioritaskan berdasarkan pilihan pertama.',
                        ],
                        [
                            'q' => 'Apakah pendaftaran dikenakan biaya?',
                            'a' => 'Pendaftaran <strong>tidak dipungut biaya apapun (gratis)</strong>. Jika ada pihak yang meminta pembayaran di luar ketentuan resmi, segera laporkan ke panitia SPMB.',
                        ],
                        [
                            'q' => 'Kapan batas akhir pendaftaran?',
                            'a' => 'Pendaftaran dibuka <strong>1–31 Mei 2026</strong>. Setelah tanggal tersebut sistem otomatis menutup dan tidak ada perpanjangan. Pastikan semua data terisi sebelum batas waktu.',
                        ],
                    ],
                ],
                [
                    'cat'   => 'biodata',
                    'icon'  => 'fa-id-card',
                    'title' => 'Pengisian Biodata',
                    'items' => [
                        [
                            'q' => 'Dokumen apa saja yang harus diunggah?',
                            'a' => 'Lima dokumen wajib: <strong>Akta Kelahiran</strong>, <strong>Ijazah/SKL SMP</strong>, <strong>Rapor semester 1–5</strong>, <strong>Pas foto terbaru</strong> (3×4, latar merah), dan <strong>sertifikat prestasi</strong> (jika ada). Format: JPG/PNG/PDF, maks 2 MB per file.',
                        ],
                        [
                            'q' => 'Bolehkah data biodata diedit setelah disimpan?',
                            'a' => 'Data dapat diedit <strong>selama status masih "Draft"</strong>. Setelah kamu klik <em>Kirim Biodata</em>, data terkunci dan hanya bisa diubah dengan menghubungi panitia secara langsung.',
                        ],
                        [
                            'q' => 'Bagaimana jika upload dokumen gagal terus?',
                            'a' => 'Pastikan ukuran file <strong>di bawah 2 MB</strong> dan format JPG, PNG, atau PDF. Kompres gambar menggunakan tools seperti ilovepdf.com atau squoosh.app, lalu coba unggah kembali.',
                        ],
                        [
                            'q' => 'Apa fungsi tombol "Simpan Draft"?',
                            'a' => 'Tombol Simpan Draft menyimpan progres pengisian <strong>tanpa mengirimkan data</strong> ke panitia. Data akan tersimpan dan bisa dilanjutkan kapan saja sebelum batas waktu pendaftaran.',
                        ],
                    ],
                ],
                [
                    'cat'   => 'seleksi',
                    'icon'  => 'fa-ranking-star',
                    'title' => 'Proses Seleksi',
                    'items' => [
                        [
                            'q' => 'Apa saja kriteria seleksi penerimaan?',
                            'a' => 'Seleksi menggunakan 3 komponen: <strong>nilai rapor rata-rata (60%)</strong>, <strong>tes akademik online (30%)</strong>, dan <strong>prestasi/sertifikat (10%)</strong>. Peserta dengan nilai tertinggi diterima sesuai kuota jurusan.',
                        ],
                        [
                            'q' => 'Kapan jadwal tes seleksi dilaksanakan?',
                            'a' => 'Tes seleksi akademik dijadwalkan pada <strong>5–7 Juni 2026</strong> secara online melalui sistem SPMB. Peserta akan mendapatkan notifikasi jadwal dan token tes melalui email dan dashboard.',
                        ],
                        [
                            'q' => 'Kapan pengumuman hasil seleksi?',
                            'a' => 'Hasil seleksi diumumkan pada <strong>10 Juni 2026 pukul 08.00 WIB</strong> melalui dashboard peserta. Peserta diterima akan mendapat notifikasi untuk melanjutkan proses daftar ulang.',
                        ],
                        [
                            'q' => 'Apakah ada jalur khusus atau beasiswa?',
                            'a' => 'Ada <strong>Jalur Prestasi</strong> untuk calon peserta dengan piagam kejuaraan tingkat kabupaten/kota ke atas, dan <strong>Jalur Afirmasi</strong> untuk peserta dari keluarga kurang mampu (dilengkapi DTKS atau SKTM).',
                        ],
                    ],
                ],
                [
                    'cat'   => 'daftarulang',
                    'icon'  => 'fa-rotate-right',
                    'title' => 'Daftar Ulang',
                    'items' => [
                        [
                            'q' => 'Apa saja tahapan dalam proses daftar ulang?',
                            'a' => 'Ada 5 tahap: <strong>(1)</strong> Konfirmasi kehadiran, <strong>(2)</strong> Pemilihan seragam, <strong>(3)</strong> Pembayaran biaya daftar ulang, <strong>(4)</strong> Pilih jadwal kehadiran ke sekolah, <strong>(5)</strong> Tanda tangan pernyataan &amp; kirim. Semua dilakukan secara online.',
                        ],
                        [
                            'q' => 'Batas waktu daftar ulang sampai kapan?',
                            'a' => 'Daftar ulang harus diselesaikan paling lambat <strong>15 Juni 2026</strong>. Peserta yang tidak menyelesaikan tepat waktu dianggap mengundurkan diri dan kursi diberikan ke peserta cadangan.',
                        ],
                        [
                            'q' => 'Apa yang terjadi jika tidak bisa hadir pada jadwal yang dipilih?',
                            'a' => 'Perubahan jadwal bisa dilakukan <strong>maksimal H-1 sebelum jadwal</strong> dengan menghubungi panitia via WhatsApp. Di luar itu, hubungi operator sekolah langsung untuk konfirmasi ulang.',
                        ],
                        [
                            'q' => 'Dokumen apa yang dibawa saat hadir ke sekolah?',
                            'a' => 'Bawa dokumen asli: <strong>Ijazah/SKL</strong>, <strong>Akta Kelahiran</strong>, <strong>Kartu Keluarga</strong>, <strong>pas foto 3×4 (5 lembar, latar merah)</strong>, dan <strong>bukti pembayaran daftar ulang</strong> yang sudah dicetak.',
                        ],
                        [
                            'q' => 'Apakah orang tua wajib hadir saat daftar ulang?',
                            'a' => '<strong>Ya, wajib.</strong> Setidaknya salah satu orang tua atau wali yang terdaftar di biodata harus hadir untuk menandatangani surat pernyataan orang tua/wali di hadapan panitia.',
                        ],
                    ],
                ],
                [
                    'cat'   => 'pembayaran',
                    'icon'  => 'fa-credit-card',
                    'title' => 'Pembayaran',
                    'items' => [
                        [
                            'q' => 'Metode pembayaran apa yang tersedia?',
                            'a' => 'Tersedia 3 metode: <strong>Transfer Bank</strong> (BNI/BRI/Mandiri/BCA), <strong>QRIS</strong> (scan dari semua e-wallet), dan <strong>Virtual Account</strong> otomatis yang digenerate sistem. Bukti transfer wajib diunggah.',
                        ],
                        [
                            'q' => 'Berapa total biaya yang harus dibayar?',
                            'a' => 'Rincian biaya: Daftar ulang <strong>Rp 250.000</strong>, Seragam wajib <strong>Rp 650.000</strong>, Perlengkapan <strong>±Rp 145.000</strong>, SPP pertama <strong>Rp 200.000</strong>. <strong>Total estimasi Rp 1.245.000</strong> (bisa berbeda sesuai pilihan seragam).',
                        ],
                        [
                            'q' => 'Bagaimana jika bukti pembayaran hilang atau tidak tersimpan?',
                            'a' => 'Hubungi panitia via WhatsApp dengan menyertakan <strong>nomor peserta</strong> dan <strong>tanggal transaksi</strong>. Panitia akan memverifikasi pembayaran melalui rekening koran sekolah.',
                        ],
                        [
                            'q' => 'Apakah ada cicilan atau keringanan biaya?',
                            'a' => 'Peserta dari keluarga tidak mampu (pemegang KIP/DTKS) dapat mengajukan <strong>keringanan biaya</strong> dengan menyerahkan surat permohonan ke TU sekolah. Keputusan keringanan ditentukan kepala sekolah.',
                        ],
                    ],
                ],
            ];
            @endphp

            @foreach($faqGroups as $gi => $group)
            <div class="faq-section-block mb-4" data-cat="{{ $group['cat'] }}">
                <div class="bg-white border border-[#E5E7EB] rounded-[20px] overflow-hidden shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
                    {{-- Group header --}}
                    <div class="px-5 py-4 flex items-center gap-2.5"
                         style="background: linear-gradient(135deg, #FF1443, #D90F38);">
                        <i class="fa-solid {{ $group['icon'] }} text-white text-[14px]"></i>
                        <span class="text-[14px] font-black text-white">{{ $group['title'] }}</span>
                    </div>

                    {{-- FAQ items --}}
                    @foreach($group['items'] as $ii => $item)
                    <div class="faq-item border-t border-[#F3F4F6]" data-q="{{ strtolower($item['q']) }} {{ strtolower(strip_tags($item['a'])) }}">
                        <button
                            type="button"
                            class="faq-btn w-full flex items-start justify-between gap-3 px-5 py-4 text-left text-[13.5px] font-bold text-[#080C1A] hover:bg-gray-50/70 transition-colors"
                            onclick="toggleFaq(this)"
                            aria-expanded="false">
                            <span class="faq-q-text leading-snug">{{ $item['q'] }}</span>
                            <i class="fa-solid fa-chevron-down arrow-icon text-[#6A7686] text-[12px] mt-[3px]"></i>
                        </button>
                        <div class="faq-answer px-5">
                            <div class="pb-4 text-[13px] text-[#6A7686] leading-[1.75] border-t border-[#F3F4F6] pt-3">
                                {!! $item['a'] !!}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        {{-- ══════════════════════════════════════════
             TIMELINE ALUR SPMB
        ══════════════════════════════════════════ --}}
        <div class="animate-fade-in delay-4 mb-7">
            <p class="text-[11px] font-bold text-[#6A7686] uppercase tracking-[.06em] mb-3">Alur tahapan SPMB 2026</p>
            <div class="bg-white border border-[#E5E7EB] rounded-[20px] shadow-[0_1px_8px_rgba(0,0,0,0.04)] px-6 py-6">
                @php
                $steps = [
                    [
                        'no'     => 1,
                        'status' => 'done',
                        'title'  => 'Pendaftaran Akun',
                        'desc'   => 'Buat akun dengan NISN + verifikasi OTP. Pilih jurusan 1 & 2.',
                        'period' => '1 – 31 Mei 2026',
                        'badge'  => 'Selesai',
                    ],
                    [
                        'no'     => 2,
                        'status' => 'done',
                        'title'  => 'Pengisian Biodata',
                        'desc'   => 'Isi 6 langkah data diri, orang tua, riwayat pendidikan & unggah dokumen.',
                        'period' => 'S.d. 31 Mei 2026',
                        'badge'  => 'Selesai',
                    ],
                    [
                        'no'     => 3,
                        'status' => 'active',
                        'title'  => 'Seleksi Akademik',
                        'desc'   => 'Tes online berbasis nilai rapor dan soal akademik secara daring.',
                        'period' => '5 – 7 Juni 2026',
                        'badge'  => 'Sedang berlangsung',
                    ],
                    [
                        'no'     => 4,
                        'status' => 'pending',
                        'title'  => 'Pengumuman Hasil',
                        'desc'   => 'Cek status diterima/tidak langsung di dashboard peserta.',
                        'period' => '10 Juni 2026',
                        'badge'  => 'Menunggu',
                    ],
                    [
                        'no'     => 5,
                        'status' => 'pending',
                        'title'  => 'Daftar Ulang',
                        'desc'   => 'Bayar biaya, pilih seragam, dan tentukan jadwal hadir ke sekolah.',
                        'period' => '11 – 15 Juni 2026',
                        'badge'  => 'Menunggu',
                    ],
                ];
                $dotClass = [
                    'done'    => 'bg-green-100 border-2 border-green-500 text-green-600',
                    'active'  => 'text-white',
                    'pending' => 'bg-gray-100 border-2 border-[#E5E7EB] text-[#6A7686]',
                ];
                $badgeClass = [
                    'done'    => 'bg-green-50 text-green-700',
                    'active'  => 'text-white',
                    'pending' => 'bg-gray-100 text-[#6A7686]',
                ];
                @endphp

                @foreach($steps as $step)
                <div class="timeline-step flex gap-4 {{ !$loop->last ? 'pb-5' : '' }}">
                    {{-- Dot --}}
                    <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-[13px] font-black z-10
                        {{ $dotClass[$step['status']] }}
                        {{ $step['status'] === 'active' ? '' : '' }}"
                        @if($step['status'] === 'active')
                        style="background: linear-gradient(135deg,#FF1443,#D90F38)"
                        @endif>
                        @if($step['status'] === 'done')
                            <i class="fa-solid fa-check text-green-600 text-[12px]"></i>
                        @else
                            {{ $step['no'] }}
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 pt-0.5">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 mb-0.5">
                            <span class="text-[14px] font-black text-[#080C1A] {{ $step['status'] === 'active' ? 'text-[#FF1443]' : '' }}">
                                {{ $step['title'] }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold
                                {{ $badgeClass[$step['status']] }}"
                                @if($step['status'] === 'active') style="background:rgba(255,20,67,.1);color:#FF1443" @endif>
                                @if($step['status'] === 'done')
                                    <i class="fa-solid fa-check mr-1 text-[9px]"></i>
                                @elseif($step['status'] === 'active')
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#FF1443] mr-1.5 animate-pulse inline-block"></span>
                                @endif
                                {{ $step['badge'] }}
                            </span>
                        </div>
                        <p class="text-[12.5px] text-[#6A7686] leading-relaxed mb-1">{{ $step['desc'] }}</p>
                        <span class="text-[11px] font-semibold text-[#9CA3AF]">
                            <i class="fa-regular fa-calendar mr-1"></i>{{ $step['period'] }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ══════════════════════════════════════════
             KONTAK PANITIA
        ══════════════════════════════════════════ --}}
        <div class="animate-fade-in delay-4">
            <p class="text-[11px] font-bold text-[#6A7686] uppercase tracking-[.06em] mb-3">Masih butuh bantuan? Hubungi panitia</p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">

                {{-- WhatsApp --}}
                <div class="bg-white border border-[#E5E7EB] rounded-[20px] p-5 shadow-[0_1px_8px_rgba(0,0,0,0.04)] flex flex-col gap-3">
                    <div class="w-10 h-10 rounded-[12px] bg-[rgba(37,211,102,.1)] flex items-center justify-center">
                        <i class="fa-brands fa-whatsapp text-[#25D366] text-[20px]"></i>
                    </div>
                    <div>
                        <p class="text-[14px] font-black text-[#080C1A] mb-0.5">WhatsApp Panitia</p>
                        <p class="text-[12px] text-[#6A7686]">Respons cepat saat jam kerja</p>
                    </div>
                    <a href="https://wa.me/6281234567890" target="_blank"
                       class="contact-btn mt-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-full text-[13px] font-bold no-underline bg-white border-[1.5px] border-[#25D366] text-[#25D366]">
                        <i class="fa-brands fa-whatsapp text-[14px]"></i> Chat Sekarang
                    </a>
                </div>

                {{-- Email --}}
                <div class="bg-white border border-[#E5E7EB] rounded-[20px] p-5 shadow-[0_1px_8px_rgba(0,0,0,0.04)] flex flex-col gap-3">
                    <div class="w-10 h-10 rounded-[12px] bg-[rgba(255,20,67,.08)] flex items-center justify-center">
                        <i class="fa-solid fa-envelope text-[#FF1443] text-[18px]"></i>
                    </div>
                    <div>
                        <p class="text-[14px] font-black text-[#080C1A] mb-0.5">Email Panitia</p>
                        <p class="text-[12px] text-[#6A7686]">spmb@smkn1.sch.id<br>Dibalas 1×24 jam kerja</p>
                    </div>
                    <a href="mailto:spmb@smkn1.sch.id"
                       class="contact-btn mt-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-full text-[13px] font-bold no-underline bg-white border-[1.5px] border-[#FF1443] text-[#FF1443]">
                        <i class="fa-solid fa-paper-plane text-[12px]"></i> Kirim Email
                    </a>
                </div>

                {{-- Jam operasional --}}
                <div class="bg-white border border-[#E5E7EB] rounded-[20px] p-5 shadow-[0_1px_8px_rgba(0,0,0,0.04)] flex flex-col gap-3">
                    <div class="w-10 h-10 rounded-[12px] bg-[rgba(59,130,246,.08)] flex items-center justify-center">
                        <i class="fa-regular fa-clock text-blue-500 text-[18px]"></i>
                    </div>
                    <div>
                        <p class="text-[14px] font-black text-[#080C1A] mb-1.5">Jam Operasional</p>
                        <div class="flex flex-col gap-1 text-[12px]">
                            <div class="flex justify-between">
                                <span class="text-[#6A7686]">Senin – Jumat</span>
                                <span class="font-bold text-[#080C1A]">08:00 – 16:00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[#6A7686]">Sabtu</span>
                                <span class="font-bold text-[#080C1A]">08:00 – 12:00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[#6A7686]">Hari Libur</span>
                                <span class="font-bold text-red-500">Tutup</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-auto text-[11px] text-[#6A7686] bg-gray-50 rounded-[10px] px-3 py-2">
                        <i class="fa-solid fa-circle-info text-blue-400 mr-1"></i>
                        Di luar jam kerja? Tinggalkan pesan, kami balas besok pagi.
                    </div>
                </div>

            </div>

            {{-- Info tambahan --}}
            <div class="bg-[#FFF8F9] border border-[rgba(255,20,67,.15)] rounded-[16px] px-5 py-4 flex items-start gap-3">
                <i class="fa-solid fa-triangle-exclamation text-[#FF1443] text-[14px] mt-0.5 flex-shrink-0"></i>
                <p class="text-[13px] text-[#6A7686] leading-relaxed">
                    <strong class="text-[#080C1A]">Perhatian:</strong>
                    Panitia SPMB tidak pernah meminta biaya pendaftaran dalam bentuk apapun.
                    Waspada terhadap penipuan yang mengatasnamakan panitia SPMB SMK Negeri 1.
                </p>
            </div>
        </div>

    </div>{{-- /wrapper --}}

    {{-- ══════════════════════════════════════════
         JAVASCRIPT
    ══════════════════════════════════════════ --}}
    @push('scripts')
    <script>
        // ── Toggle FAQ accordion ──────────────────────────────
        function toggleFaq(btn) {
            const answer = btn.nextElementSibling;
            const isOpen = btn.classList.contains('open');

            // Tutup semua yang terbuka
            document.querySelectorAll('.faq-btn.open').forEach(b => {
                b.classList.remove('open');
                b.setAttribute('aria-expanded', 'false');
                b.nextElementSibling.classList.remove('open');
            });

            // Buka yang diklik (jika sebelumnya tertutup)
            if (!isOpen) {
                btn.classList.add('open');
                btn.setAttribute('aria-expanded', 'true');
                answer.classList.add('open');
            }
        }

        // ── Filter per kategori ───────────────────────────────
        function filterCat(cat) {
            // Update tombol kategori
            document.querySelectorAll('.cat-card').forEach(c => {
                const isSelf = c.dataset.cat === cat;
                c.classList.toggle('active', isSelf);
                c.querySelector('.cat-icon-wrap').style.background =
                    isSelf ? 'rgba(255,20,67,.15)' : 'rgba(255,20,67,.08)';
                c.querySelector('.cat-title').style.color =
                    isSelf ? '#FF1443' : '';
            });

            // Tampilkan/sembunyikan section FAQ
            document.querySelectorAll('.faq-section-block').forEach(sec => {
                sec.classList.toggle('hidden', cat !== 'all' && sec.dataset.cat !== cat);
            });

            // Reset search
            document.getElementById('searchInput').value = '';
            document.getElementById('noResult').style.display = 'none';
        }

        // ── Live search ───────────────────────────────────────
        document.getElementById('searchInput').addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();

            if (!q) {
                filterCat('all');
                return;
            }

            // Reset kategori button ke "all"
            document.querySelectorAll('.cat-card').forEach(c => {
                c.classList.toggle('active', c.dataset.cat === 'all');
            });

            let totalVisible = 0;

            document.querySelectorAll('.faq-section-block').forEach(sec => {
                let secVisible = 0;

                sec.querySelectorAll('.faq-item').forEach(item => {
                    const text = (item.dataset.q || '').toLowerCase();
                    const match = text.includes(q);
                    item.style.display = match ? '' : 'none';
                    if (match) secVisible++;
                });

                sec.classList.toggle('hidden', secVisible === 0);
                if (secVisible > 0) totalVisible += secVisible;
            });

            document.getElementById('noResult').style.display =
                totalVisible === 0 ? 'block' : 'none';
        });
    </script>
    @endpush

</x-app-layout>

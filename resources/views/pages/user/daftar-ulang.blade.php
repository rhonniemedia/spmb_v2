@extends('layouts.user')

@section('title', 'Daftar Ulang')

@section('content')

<div class="max-w-[1400px] mx-auto pb-20">

    {{-- ══════════════════════════════════════════
            BREADCRUMB
    ══════════════════════════════════════════ --}}
    <div class="flex items-center gap-1.5 text-[13px] text-[#6A7686] mb-4 animate-fade-in">
        <a href="{{ route('dashboard') }}" class="text-[#FF1443] no-underline font-semibold">
            <i class="fa-solid fa-house"></i> Beranda
        </a>
        <span class="text-gray-300">/</span>
        <a href="{{ route('dashboard') }}" class="text-[#FF1443] no-underline font-semibold">Dashboard</a>
        <span class="text-gray-300">/</span>
        <span>Daftar Ulang</span>
    </div>

    {{-- ══════════════════════════════════════════
            HERO BANNER
    ══════════════════════════════════════════ --}}
    <div class="animate-fade-in relative rounded-[20px] overflow-hidden mb-5 p-6 md:p-7 flex flex-col md:flex-row items-center justify-between gap-5"
        style="background: linear-gradient(135deg, #FF1443 0%, #D90F38 50%, #B00F30 100%);">
        <div class="absolute -top-10 -right-10 w-[200px] h-[200px] bg-white/[0.06] rounded-full pointer-events-none"></div>
        <div class="absolute -bottom-[60px] right-24 w-[160px] h-[160px] bg-white/[0.04] rounded-full pointer-events-none"></div>

        {{-- Kiri --}}
        <div class="relative z-10 w-full md:flex-1">
            <div class="inline-flex items-center gap-1.5 bg-white/15 text-white text-xs font-bold px-3 py-1 rounded-full mb-3 border border-white/25">
                <i class="fa-solid fa-rotate-right"></i> Proses Daftar Ulang
            </div>
            <h1 class="text-xl md:text-2xl font-black text-white mb-1">Daftar Ulang Peserta Didik Baru</h1>
            <p class="text-[13px] text-white/80 leading-relaxed mb-4 max-w-[520px]">
                Selesaikan seluruh tahapan daftar ulang sebelum <strong class="text-white">15 Juni 2026</strong> untuk mengamankan tempat kamu.
            </p>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white text-[#FF1443] text-[13px] font-black rounded-full no-underline shadow-md">
                    <i class="fa-solid fa-gauge"></i> Kembali ke Dashboard
                </a>
                <span class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white/15 text-white text-[13px] font-bold rounded-full border border-white/30">
                    <i class="fa-solid fa-id-badge"></i> SPMB-2026-004821
                </span>
            </div>
        </div>

        {{-- Kanan: kartu identitas peserta --}}
        <div class="relative z-10 w-full md:w-auto flex-shrink-0">
            <div class="bg-white/15 border-2 border-white/30 rounded-[20px] px-6 py-4 backdrop-blur-[10px] min-w-[200px]">
                <div class="w-12 h-12 rounded-full bg-white/20 border-2 border-white/30 flex items-center justify-center font-black text-white text-[20px] mx-auto mb-3">AF</div>
                <div class="text-[17px] font-black text-white leading-tight text-center">Ahmad Fauzi</div>
                <div class="text-[12px] text-white/65 text-center mt-1 mb-2">SPMB-2026-004821</div>
                <div class="inline-flex items-center justify-center gap-1.5 w-full bg-white/15 text-white text-[12px] font-bold px-3 py-1.5 rounded-full">
                    <i class="fa-solid fa-code text-[10px]"></i> Rekayasa Perangkat Lunak
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
            GRID DUA KOLOM
    ══════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_300px] gap-6 items-start w-full" id="mainWrapper">

        {{-- ── KOLOM KIRI ── --}}
        <div id="formCol">

            {{-- SEC 1 — KONFIRMASI DATA --}}
            <div id="sec1" class="bg-white border border-[#E5E7EB] rounded-[20px] shadow-[0_1px_8px_rgba(0,0,0,0.04)] overflow-hidden mb-5 animate-fade-in">
                <div class="px-6 py-[18px] pb-[14px] border-b border-gray-100 flex items-center gap-[14px]">
                    <div class="w-[42px] h-[42px] rounded-[12px] bg-[#FFF1F3] flex items-center justify-center text-[20px] flex-shrink-0">
                        <i class="fa-solid fa-id-card text-[18px] text-[#FF1443]"></i>
                    </div>
                    <div>
                        <div class="text-[17px] font-black">1 — Konfirmasi Data Peserta</div>
                        <div class="text-[14px] text-[#6A7686] mt-[2px]">Verifikasi ulang data yang akan digunakan</div>
                    </div>
                    <span class="ml-auto inline-flex items-center gap-[5px] px-[10px] py-[3px] rounded-full text-[13px] font-bold bg-[#DCFCE7] text-[#166534] border border-[rgba(48,178,45,0.2)]">
                        <i class="fa-solid fa-circle-check text-[11px]"></i> Terisi
                    </span>
                </div>
                <div class="px-6 py-[22px]">
                    {{-- Alert --}}
                    <div class="rounded-[16px] p-3 px-4 flex gap-[10px] items-start mb-4 bg-[#DCFCE7] border border-[rgba(48,178,45,0.20)]">
                        <i class="fa-solid fa-circle-check text-[14px] text-[#30B22D] mt-[1px] flex-shrink-0"></i>
                        <p class="text-[14px] font-medium leading-relaxed text-[#166534]">
                            Data berikut diambil dari biodata yang telah kamu isi dan diverifikasi panitia. Jika ada ketidaksesuaian, hubungi panitia sebelum melanjutkan.
                        </p>
                    </div>
                    {{-- Grid data peserta --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Nama Lengkap</div>
                            <div class="text-[13px] font-bold">Ahmad Fauzi</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">NIK</div>
                            <div class="text-[13px] font-bold">1671XXXXXXXXXXXX</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">NISN</div>
                            <div class="text-[13px] font-bold">0074821639</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Tempat &amp; Tanggal Lahir</div>
                            <div class="text-[13px] font-bold">Palembang, 14 Maret 2010</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Asal Sekolah</div>
                            <div class="text-[13px] font-bold">SMP Negeri 2 Palembang</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">No. HP / WhatsApp</div>
                            <div class="text-[13px] font-bold">+62 812-XXXX-XXXX</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-[rgba(255,20,67,0.08)] border border-[rgba(255,20,67,0.18)]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Jurusan Diterima</div>
                            <div class="text-[13px] font-bold text-[#FF1443]">Rekayasa Perangkat Lunak (RPL)</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-[#DCFCE7] border border-[rgba(48,178,45,0.18)]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Status Seleksi</div>
                            <div class="text-[13px] font-bold text-[#166534] flex items-center gap-1">
                                <i class="fa-solid fa-circle-check text-[11px]"></i>DITERIMA — Gelombang I
                            </div>
                        </div>
                    </div>

                    <hr class="border-0 border-t border-dashed border-gray-200 my-5">

                    {{-- Data orang tua --}}
                    <div class="text-[14px] font-bold text-[#080C1A] flex items-center gap-[7px] mb-3 pb-2 border-b border-gray-100">
                        <i class="fa-solid fa-shield text-[12px] text-[#FF1443]"></i> Data Orang Tua / Wali
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Nama Ayah</div>
                            <div class="text-[13px] font-bold">Fauzi Rahmat, S.T.</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">HP Ayah</div>
                            <div class="text-[13px] font-bold">+62 811-XXXX-XXXX</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Nama Ibu</div>
                            <div class="text-[13px] font-bold">Dewi Sartika</div>
                        </div>
                        <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
                            <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Pekerjaan Ayah</div>
                            <div class="text-[13px] font-bold">PNS / Pegawai Negeri</div>
                        </div>
                    </div>

                    <hr class="border-0 border-t border-dashed border-gray-200 my-5">

                    {{-- Checkbox konfirmasi --}}
                    <label class="check-item flex items-start gap-[9px] cursor-pointer py-[1px]">
                        <input type="checkbox" id="confirmCheck" onchange="updateChecklist()">
                        <div class="check-box w-[17px] h-[17px] rounded-[5px] border-[1.5px] border-[#E5E7EB] bg-gray-50 flex items-center justify-center flex-shrink-0 mt-[1px] transition-all"></div>
                        <span class="text-[13px] font-medium leading-relaxed">Saya menyatakan data di atas adalah <strong>benar dan sesuai</strong> dengan dokumen asli.</span>
                    </label>
                </div>
            </div>

            {{-- SEC 2 — SERAGAM --}}
            <div id="sec2" class="bg-white border border-[#E5E7EB] rounded-[20px] shadow-[0_1px_8px_rgba(0,0,0,0.04)] overflow-hidden mb-5 animate-fade-in-1">
                <div class="px-6 py-[18px] pb-[14px] border-b border-gray-100 flex items-center gap-[14px]">
                    <div class="w-[42px] h-[42px] rounded-[12px] bg-[#F3F0FF] flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-shirt text-[18px] text-[#8B5CF6]"></i>
                    </div>
                    <div>
                        <div class="text-[17px] font-black">2 — Seragam &amp; Perlengkapan</div>
                        <div class="text-[14px] text-[#6A7686] mt-[2px]">Pilih ukuran dan item yang dibutuhkan</div>
                    </div>
                </div>
                <div class="px-6 py-[22px]">
                    {{-- Info alert --}}
                    <div class="rounded-[16px] p-3 px-4 flex gap-[10px] items-start mb-4 bg-[#EFF6FF] border border-[#BFDBFE]">
                        <i class="fa-solid fa-circle-info text-[14px] text-[#3B82F6] mt-[1px] flex-shrink-0"></i>
                        <p class="text-[14px] font-medium leading-relaxed text-[#1E40AF]">
                            Pengambilan seragam dilakukan saat hadir daftar ulang di sekolah. Pastikan ukuran yang dipilih sudah sesuai untuk menghindari penukaran.
                        </p>
                    </div>

                    <div class="text-[14px] font-bold text-[#080C1A] flex items-center gap-[7px] mb-3 pb-2 border-b border-gray-100">
                        <i class="fa-solid fa-shirt text-[12px] text-[#FF1443]"></i> Paket Seragam Wajib
                    </div>

                    {{-- Kartu seragam --}}
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        {{-- Kemeja --}}
                        <div class="uniform-card selected border-[1.5px] border-[#E5E7EB] rounded-[16px] p-4 cursor-pointer transition-all select-none bg-gray-50 hover:border-[#FF1443] hover:bg-white relative"
                            onclick="selectUniform(this, 'kemeja')">
                            <div class="uc-check absolute top-3 right-3 w-[22px] h-[22px] rounded-full border-[1.5px] border-[#E5E7EB] bg-white flex items-center justify-center text-[12px] text-transparent transition-all">
                                <i class="fa-solid fa-check text-[10px]"></i>
                            </div>
                            <div class="text-[28px] mb-[10px]">👔</div>
                            <div class="text-[13px] font-extrabold mb-[3px]">Kemeja Putih (2 pcs)</div>
                            <div class="text-[13px] text-[#6A7686] leading-snug">Kemeja lengan pendek seragam harian</div>
                            <div class="uniform-size-wrap mt-[10px]">
                                <label class="text-[10px] font-bold uppercase tracking-[0.05em] text-[#6A7686] block mb-1">Ukuran Kemeja</label>
                                <select class="w-full px-[13px] py-[10px] font-sans text-[13px] font-medium text-[#080C1A] bg-gray-50 border-[1.5px] border-[#E5E7EB] rounded-[12px] outline-none transition-all cursor-pointer appearance-none focus:border-[#FF1443] focus:bg-white focus:shadow-[0_0_0_3px_rgba(255,20,67,0.09)] pr-9">
                                    <option value="">Pilih Ukuran</option>
                                    <option>XS (32)</option>
                                    <option>S (34)</option>
                                    <option selected>M (36)</option>
                                    <option>L (38)</option>
                                    <option>XL (40)</option>
                                    <option>XXL (42)</option>
                                </select>
                            </div>
                        </div>
                        {{-- Celana/Rok --}}
                        <div class="uniform-card selected border-[1.5px] border-[#E5E7EB] rounded-[16px] p-4 cursor-pointer transition-all select-none bg-gray-50 hover:border-[#FF1443] hover:bg-white relative"
                            onclick="selectUniform(this, 'celana')">
                            <div class="uc-check absolute top-3 right-3 w-[22px] h-[22px] rounded-full border-[1.5px] border-[#E5E7EB] bg-white flex items-center justify-center text-[12px] text-transparent transition-all">
                                <i class="fa-solid fa-check text-[10px]"></i>
                            </div>
                            <div class="text-[28px] mb-[10px]">👖</div>
                            <div class="text-[13px] font-extrabold mb-[3px]">Celana / Rok Abu-abu (2 pcs)</div>
                            <div class="text-[13px] text-[#6A7686] leading-snug">Celana panjang abu-abu (putra) / rok (putri)</div>
                            <div class="uniform-size-wrap mt-[10px]">
                                <label class="text-[10px] font-bold uppercase tracking-[0.05em] text-[#6A7686] block mb-1">Ukuran Bawahan</label>
                                <select class="w-full px-[13px] py-[10px] font-sans text-[13px] font-medium text-[#080C1A] bg-gray-50 border-[1.5px] border-[#E5E7EB] rounded-[12px] outline-none transition-all cursor-pointer appearance-none focus:border-[#FF1443] focus:bg-white focus:shadow-[0_0_0_3px_rgba(255,20,67,0.09)] pr-9">
                                    <option value="">Pilih Ukuran</option>
                                    <option>27</option>
                                    <option>28</option>
                                    <option selected>29</option>
                                    <option>30</option>
                                    <option>31</option>
                                    <option>32</option>
                                </select>
                            </div>
                        </div>
                        {{-- Batik --}}
                        <div class="uniform-card selected border-[1.5px] border-[#E5E7EB] rounded-[16px] p-4 cursor-pointer transition-all select-none bg-gray-50 hover:border-[#FF1443] hover:bg-white relative"
                            onclick="selectUniform(this, 'batik')">
                            <div class="uc-check absolute top-3 right-3 w-[22px] h-[22px] rounded-full border-[1.5px] border-[#E5E7EB] bg-white flex items-center justify-center text-[12px] text-transparent transition-all">
                                <i class="fa-solid fa-check text-[10px]"></i>
                            </div>
                            <div class="text-[28px] mb-[10px]">🎽</div>
                            <div class="text-[13px] font-extrabold mb-[3px]">Seragam Batik (1 pcs)</div>
                            <div class="text-[13px] text-[#6A7686] leading-snug">Batik khas sekolah untuk hari Kamis</div>
                            <div class="uniform-size-wrap mt-[10px]">
                                <label class="text-[10px] font-bold uppercase tracking-[0.05em] text-[#6A7686] block mb-1">Ukuran Batik</label>
                                <select class="w-full px-[13px] py-[10px] font-sans text-[13px] font-medium text-[#080C1A] bg-gray-50 border-[1.5px] border-[#E5E7EB] rounded-[12px] outline-none transition-all cursor-pointer appearance-none focus:border-[#FF1443] focus:bg-white focus:shadow-[0_0_0_3px_rgba(255,20,67,0.09)] pr-9">
                                    <option value="">Pilih Ukuran</option>
                                    <option>S</option>
                                    <option selected>M</option>
                                    <option>L</option>
                                    <option>XL</option>
                                </select>
                            </div>
                        </div>
                        {{-- Olahraga --}}
                        <div class="uniform-card selected border-[1.5px] border-[#E5E7EB] rounded-[16px] p-4 cursor-pointer transition-all select-none bg-gray-50 hover:border-[#FF1443] hover:bg-white relative"
                            onclick="selectUniform(this, 'olahraga')">
                            <div class="uc-check absolute top-3 right-3 w-[22px] h-[22px] rounded-full border-[1.5px] border-[#E5E7EB] bg-white flex items-center justify-center text-[12px] text-transparent transition-all">
                                <i class="fa-solid fa-check text-[10px]"></i>
                            </div>
                            <div class="text-[28px] mb-[10px]">🏃</div>
                            <div class="text-[13px] font-extrabold mb-[3px]">Baju Olahraga (1 set)</div>
                            <div class="text-[13px] text-[#6A7686] leading-snug">Kaos + celana olahraga berlogo sekolah</div>
                            <div class="uniform-size-wrap mt-[10px]">
                                <label class="text-[10px] font-bold uppercase tracking-[0.05em] text-[#6A7686] block mb-1">Ukuran Olahraga</label>
                                <select class="w-full px-[13px] py-[10px] font-sans text-[13px] font-medium text-[#080C1A] bg-gray-50 border-[1.5px] border-[#E5E7EB] rounded-[12px] outline-none transition-all cursor-pointer appearance-none focus:border-[#FF1443] focus:bg-white focus:shadow-[0_0_0_3px_rgba(255,20,67,0.09)] pr-9">
                                    <option value="">Pilih Ukuran</option>
                                    <option>S</option>
                                    <option selected>M</option>
                                    <option>L</option>
                                    <option>XL</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SEC 3 — PEMBAYARAN --}}
            <div id="sec3" class="bg-white border border-[#E5E7EB] rounded-[20px] shadow-[0_1px_8px_rgba(0,0,0,0.04)] overflow-hidden mb-5 animate-fade-in-2">
                <div class="px-6 py-[18px] pb-[14px] border-b border-gray-100 flex items-center gap-[14px]">
                    <div class="w-[42px] h-[42px] rounded-[12px] bg-[#EFF6FF] flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-credit-card text-[18px] text-[#3B82F6]"></i>
                    </div>
                    <div>
                        <div class="text-[17px] font-black">3 — Pembayaran</div>
                        <div class="text-[14px] text-[#6A7686] mt-[2px]">Pilih metode dan unggah bukti pembayaran</div>
                    </div>
                </div>
                <div class="px-6 py-[22px]">
                    {{-- Rincian tagihan --}}
                    <div class="text-[14px] font-bold text-[#080C1A] flex items-center gap-[7px] mb-3 pb-2 border-b border-gray-100">
                        <i class="fa-solid fa-receipt text-[12px] text-[#FF1443]"></i> Rincian Tagihan
                    </div>
                    <div class="bg-gray-50 border border-[#E5E7EB] rounded-[16px] p-4 mb-5">
                        <div class="flex justify-between py-[6px] border-b border-gray-100 text-[14px]">
                            <span class="text-[#6A7686] font-medium">Biaya Pendaftaran Ulang</span>
                            <span class="font-bold">Rp 250.000</span>
                        </div>
                        <div class="flex justify-between py-[6px] border-b border-gray-100 text-[14px]">
                            <span class="text-[#6A7686] font-medium">Paket Seragam Wajib (4 item)</span>
                            <span class="font-bold">Rp 650.000</span>
                        </div>
                        <div class="flex justify-between py-[6px] border-b border-gray-100 text-[14px]">
                            <span class="text-[#6A7686] font-medium">Topi &amp; Dasi (1 set)</span>
                            <span class="font-bold">Rp 85.000</span>
                        </div>
                        <div class="flex justify-between py-[6px] border-b border-gray-100 text-[14px]">
                            <span class="text-[#6A7686] font-medium">Buku Agenda</span>
                            <span class="font-bold">Rp 35.000</span>
                        </div>
                        <div class="flex justify-between py-[6px] border-b border-gray-100 text-[14px]">
                            <span class="text-[#6A7686] font-medium">Kartu Pelajar</span>
                            <span class="font-bold">Rp 25.000</span>
                        </div>
                        <div class="flex justify-between py-[6px] text-[14px]">
                            <span class="text-[#6A7686] font-medium">SPP Bulan Juli 2026</span>
                            <span class="font-bold">Rp 200.000</span>
                        </div>
                        <div class="flex justify-between pt-3 border-t-2 border-[#080C1A] mt-1">
                            <span class="text-[16px] font-black">Total Pembayaran</span>
                            <span class="text-[20px] font-black text-[#FF1443]" id="billTotal">Rp 1.245.000</span>
                        </div>
                    </div>

                    {{-- Metode pembayaran --}}
                    <div class="text-[14px] font-bold text-[#080C1A] flex items-center gap-[7px] mb-3 pb-2 border-b border-gray-100">
                        <i class="fa-solid fa-credit-card text-[12px] text-[#FF1443]"></i> Metode Pembayaran
                    </div>
                    <div class="flex flex-col gap-[10px] mb-5">

                        {{-- Transfer Bank --}}
                        <div class="pay-method selected border-[1.5px] border-[#E5E7EB] rounded-[16px] overflow-hidden cursor-pointer hover:border-[#FF1443] transition-colors"
                            id="pm-bank" onclick="selectPayMethod('bank')">
                            <div class="pm-header flex items-center gap-3 px-4 py-[14px]">
                                <div class="pm-radio w-[18px] h-[18px] rounded-full border-2 border-[#E5E7EB] flex-shrink-0 transition-all relative"></div>
                                <div class="w-9 h-9 rounded-[10px] bg-[#EFF6FF] flex items-center justify-center flex-shrink-0">
                                    <i class="fa-solid fa-landmark text-base text-[#3B82F6]"></i>
                                </div>
                                <div>
                                    <div class="text-[13px] font-black">Transfer Bank</div>
                                    <div class="text-[13px] text-[#6A7686]">BRI / BNI / Mandiri / BSB</div>
                                </div>
                                <span class="ml-auto text-[12px] font-bold px-2 py-[3px] rounded-full bg-[#EFF6FF] text-[#1E40AF] border border-[#BFDBFE]">Rekomen</span>
                            </div>
                            <div class="pm-body hidden px-4 pb-[14px]">
                                <div class="bg-gray-50 border border-[#E5E7EB] rounded-[12px] p-[14px]">
                                    <div class="text-[13px] font-bold text-[#6A7686] mb-[10px] uppercase tracking-[0.05em]">Rekening Sekolah</div>
                                    <div class="flex justify-between items-center py-[5px] border-b border-gray-100 text-[14px]">
                                        <span class="text-[#6A7686] font-medium">Bank</span>
                                        <span class="font-bold">Bank Sumselbabel (BSB)</span>
                                    </div>
                                    <div class="flex justify-between items-center py-[5px] border-b border-gray-100 text-[14px]">
                                        <span class="text-[#6A7686] font-medium">No. Rekening</span>
                                        <span class="font-bold flex items-center gap-[6px]">1234-5678-9012
                                            <button class="bg-[rgba(255,20,67,0.08)] border-none cursor-pointer text-[#FF1443] text-[13px] px-2 py-[3px] rounded-[6px] font-bold hover:bg-[#FF1443] hover:text-white transition-all"
                                                onclick="copyToClipboard('1234567890012', this)">
                                                <i class="fa-regular fa-copy text-[11px]"></i>
                                            </button>
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center py-[5px] border-b border-gray-100 text-[14px]">
                                        <span class="text-[#6A7686] font-medium">Atas Nama</span>
                                        <span class="font-bold">SMK NEGERI 1 KOTA PALEMBANG</span>
                                    </div>
                                    <div class="flex justify-between items-center py-[5px] text-[14px]">
                                        <span class="text-[#6A7686] font-medium">Nominal Tepat</span>
                                        <span class="font-bold text-[#FF1443]">Rp 1.245.000</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Virtual Account --}}
                        <div class="pay-method border-[1.5px] border-[#E5E7EB] rounded-[16px] overflow-hidden cursor-pointer hover:border-[#FF1443] transition-colors"
                            id="pm-va" onclick="selectPayMethod('va')">
                            <div class="pm-header flex items-center gap-3 px-4 py-[14px]">
                                <div class="pm-radio w-[18px] h-[18px] rounded-full border-2 border-[#E5E7EB] flex-shrink-0 transition-all relative"></div>
                                <div class="w-9 h-9 rounded-[10px] bg-[#F0FDF4] flex items-center justify-center flex-shrink-0">
                                    <i class="fa-solid fa-barcode text-base text-[#30B22D]"></i>
                                </div>
                                <div>
                                    <div class="text-[13px] font-black">Virtual Account</div>
                                    <div class="text-[13px] text-[#6A7686]">BRI / BNI / Mandiri</div>
                                </div>
                                <span class="ml-auto text-[12px] font-bold px-2 py-[3px] rounded-full bg-[#DCFCE7] text-[#166534] border border-[rgba(48,178,45,0.2)]">Otomatis</span>
                            </div>
                            <div class="pm-body hidden px-4 pb-[14px]">
                                <div class="bg-gray-50 border border-[#E5E7EB] rounded-[12px] p-[14px]">
                                    <div class="flex justify-between items-center py-[5px] border-b border-gray-100 text-[14px]">
                                        <span class="text-[#6A7686] font-medium">Kode VA BRI</span>
                                        <span class="font-bold flex items-center gap-[6px]">8807-004821-0011
                                            <button class="bg-[rgba(255,20,67,0.08)] border-none cursor-pointer text-[#FF1443] text-[13px] px-2 py-[3px] rounded-[6px] font-bold hover:bg-[#FF1443] hover:text-white transition-all"
                                                onclick="copyToClipboard('880700482100011', this)">
                                                <i class="fa-regular fa-copy text-[11px]"></i>
                                            </button>
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center py-[5px] border-b border-gray-100 text-[14px]">
                                        <span class="text-[#6A7686] font-medium">Berlaku Hingga</span>
                                        <span class="font-bold text-[#F59E0B]">15 Jun 2026, 23:59</span>
                                    </div>
                                    <div class="flex justify-between items-center py-[5px] text-[14px]">
                                        <span class="text-[#6A7686] font-medium">Nominal</span>
                                        <span class="font-bold text-[#FF1443]">Rp 1.245.000</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tunai --}}
                        <div class="pay-method border-[1.5px] border-[#E5E7EB] rounded-[16px] overflow-hidden cursor-pointer hover:border-[#FF1443] transition-colors"
                            id="pm-tunai" onclick="selectPayMethod('tunai')">
                            <div class="pm-header flex items-center gap-3 px-4 py-[14px]">
                                <div class="pm-radio w-[18px] h-[18px] rounded-full border-2 border-[#E5E7EB] flex-shrink-0 transition-all relative"></div>
                                <div class="w-9 h-9 rounded-[10px] bg-[#FFFBEB] flex items-center justify-center flex-shrink-0">
                                    <i class="fa-solid fa-money-bill-wave text-base text-[#F59E0B]"></i>
                                </div>
                                <div>
                                    <div class="text-[13px] font-black">Tunai di Sekolah</div>
                                    <div class="text-[13px] text-[#6A7686]">Bayar langsung saat hadir daftar ulang</div>
                                </div>
                            </div>
                            <div class="pm-body hidden px-4 pb-[14px]">
                                <div class="rounded-[16px] p-3 px-4 flex gap-[10px] items-start bg-[#FFFBEB] border border-[#FDE68A]">
                                    <i class="fa-solid fa-triangle-exclamation text-[14px] text-[#D97706] mt-[1px] flex-shrink-0"></i>
                                    <p class="text-[14px] font-medium leading-relaxed text-[#92400E]">
                                        Siapkan uang <strong>pas Rp 1.245.000</strong>. Tanda terima akan diberikan di tempat.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Upload Bukti --}}
                    <div id="uploadSection">
                        <hr class="border-0 border-t border-dashed border-gray-200 my-5">
                        <div class="text-[14px] font-bold text-[#080C1A] flex items-center gap-[7px] mb-3 pb-2 border-b border-gray-100">
                            <i class="fa-solid fa-cloud-arrow-up text-[12px] text-[#FF1443]"></i> Unggah Bukti Pembayaran
                        </div>
                        <div class="border-2 border-dashed border-[#E5E7EB] rounded-[16px] p-6 text-center cursor-pointer transition-all bg-gray-50 hover:border-[#FF1443] hover:bg-[rgba(255,20,67,0.08)]"
                            onclick="document.getElementById('buktiFile').click()">
                            <i class="fa-solid fa-cloud-arrow-up text-[28px] text-[#6A7686] mx-auto mb-[10px] block hover:text-[#FF1443]"></i>
                            <p class="text-[13px] font-semibold text-[#080C1A] mb-1">Klik atau seret file bukti transfer</p>
                            <span class="text-[13px] text-[#6A7686]">JPG, PNG, PDF — Maks. 2MB</span>
                            <input type="file" id="buktiFile" class="hidden" accept=".jpg,.jpeg,.png,.pdf" onchange="handleUpload(this)">
                        </div>
                        <div class="hidden items-center gap-3 p-[10px] px-[14px] bg-[#DCFCE7] border border-[rgba(48,178,45,0.25)] rounded-[10px] mt-[10px]"
                            id="buktiPreview">
                            <i class="fa-solid fa-file-circle-check text-base text-[#30B22D]"></i>
                            <span class="text-[13px] font-semibold text-[#166534] flex-1" id="buktiName">bukti_transfer.jpg</span>
                            <button class="bg-transparent border-none cursor-pointer text-[#ED6B60]" onclick="removeUpload()">
                                <i class="fa-solid fa-xmark text-[14px]"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SEC 4 — JADWAL --}}
            <div id="sec4" class="bg-white border border-[#E5E7EB] rounded-[20px] shadow-[0_1px_8px_rgba(0,0,0,0.04)] overflow-hidden mb-5 animate-fade-in-3">
                <div class="px-6 py-[18px] pb-[14px] border-b border-gray-100 flex items-center gap-[14px]">
                    <div class="w-[42px] h-[42px] rounded-[12px] bg-[#EFF6FF] flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-calendar-check text-[18px] text-[#3B82F6]"></i>
                    </div>
                    <div>
                        <div class="text-[17px] font-black">4 — Jadwal Hadir di Sekolah</div>
                        <div class="text-[14px] text-[#6A7686] mt-[2px]">Pilih sesi kedatangan untuk pengumpulan berkas fisik</div>
                    </div>
                </div>
                <div class="px-6 py-[22px]">
                    <div class="rounded-[16px] p-3 px-4 flex gap-[10px] items-start mb-4 bg-[#EFF6FF] border border-[#BFDBFE]">
                        <i class="fa-solid fa-circle-info text-[14px] text-[#3B82F6] mt-[1px] flex-shrink-0"></i>
                        <p class="text-[14px] font-medium leading-relaxed text-[#1E40AF]">
                            Pilih satu sesi kedatangan. Orang tua atau wali <strong>wajib hadir</strong> untuk menandatangani dokumen penerimaan. Bawa seluruh berkas fisik asli dan fotokopi.
                        </p>
                    </div>

                    {{-- Slot grid --}}
                    <div class="grid grid-cols-3 gap-[10px] mb-4" id="slotGrid">
                        <div class="slot-card border-[1.5px] border-[#E5E7EB] rounded-[16px] p-[14px] cursor-pointer transition-all text-center bg-gray-50 hover:border-[#FF1443] hover:bg-white" onclick="selectSlot(this)">
                            <div class="slot-date text-[14px] font-bold mb-1">Sen, 16 Juni 2026</div>
                            <div class="slot-time text-[16px] font-extrabold text-[#080C1A] mb-[6px]">08:00 – 10:00</div>
                            <div class="slot-avail flex items-center justify-center gap-1 text-[12px] font-bold text-[#30B22D]">
                                <i class="fa-solid fa-circle text-[8px]"></i> 18 kursi tersisa
                            </div>
                        </div>
                        <div class="slot-card border-[1.5px] border-[#E5E7EB] rounded-[16px] p-[14px] cursor-pointer transition-all text-center bg-gray-50 hover:border-[#FF1443] hover:bg-white" onclick="selectSlot(this)">
                            <div class="slot-date text-[14px] font-bold mb-1">Sen, 16 Juni 2026</div>
                            <div class="slot-time text-[16px] font-extrabold text-[#080C1A] mb-[6px]">10:00 – 12:00</div>
                            <div class="slot-avail flex items-center justify-center gap-1 text-[12px] font-bold text-[#F59E0B]">
                                <i class="fa-solid fa-circle text-[8px]"></i> 5 kursi tersisa
                            </div>
                        </div>
                        <div class="slot-card full opacity-50 cursor-not-allowed border-[1.5px] border-[#E5E7EB] rounded-[16px] p-[14px] text-center bg-gray-50">
                            <div class="slot-date text-[14px] font-bold mb-1">Sen, 16 Juni 2026</div>
                            <div class="slot-time text-[16px] font-extrabold text-[#080C1A] mb-[6px]">13:00 – 15:00</div>
                            <div class="slot-avail flex items-center justify-center gap-1 text-[12px] font-bold text-[#ED6B60]">
                                <i class="fa-solid fa-lock text-[10px]"></i> Penuh
                            </div>
                        </div>
                        <div class="slot-card border-[1.5px] border-[#E5E7EB] rounded-[16px] p-[14px] cursor-pointer transition-all text-center bg-gray-50 hover:border-[#FF1443] hover:bg-white" onclick="selectSlot(this)">
                            <div class="slot-date text-[14px] font-bold mb-1">Sel, 17 Juni 2026</div>
                            <div class="slot-time text-[16px] font-extrabold text-[#080C1A] mb-[6px]">08:00 – 10:00</div>
                            <div class="slot-avail flex items-center justify-center gap-1 text-[12px] font-bold text-[#30B22D]">
                                <i class="fa-solid fa-circle text-[8px]"></i> 24 kursi tersisa
                            </div>
                        </div>
                        <div class="slot-card border-[1.5px] border-[#E5E7EB] rounded-[16px] p-[14px] cursor-pointer transition-all text-center bg-gray-50 hover:border-[#FF1443] hover:bg-white" onclick="selectSlot(this)">
                            <div class="slot-date text-[14px] font-bold mb-1">Sel, 17 Juni 2026</div>
                            <div class="slot-time text-[16px] font-extrabold text-[#080C1A] mb-[6px]">10:00 – 12:00</div>
                            <div class="slot-avail flex items-center justify-center gap-1 text-[12px] font-bold text-[#30B22D]">
                                <i class="fa-solid fa-circle text-[8px]"></i> 20 kursi tersisa
                            </div>
                        </div>
                        <div class="slot-card border-[1.5px] border-[#E5E7EB] rounded-[16px] p-[14px] cursor-pointer transition-all text-center bg-gray-50 hover:border-[#FF1443] hover:bg-white" onclick="selectSlot(this)">
                            <div class="slot-date text-[14px] font-bold mb-1">Rab, 18 Juni 2026</div>
                            <div class="slot-time text-[16px] font-extrabold text-[#080C1A] mb-[6px]">08:00 – 11:00</div>
                            <div class="slot-avail flex items-center justify-center gap-1 text-[12px] font-bold text-[#30B22D]">
                                <i class="fa-solid fa-circle text-[8px]"></i> 30 kursi tersisa
                            </div>
                        </div>
                    </div>

                    <hr class="border-0 border-t border-dashed border-gray-200 my-5">

                    {{-- Berkas yang harus dibawa --}}
                    <div class="text-[14px] font-bold text-[#080C1A] flex items-center gap-[7px] mb-3 pb-2 border-b border-gray-100">
                        <i class="fa-solid fa-clipboard-list text-[12px] text-[#FF1443]"></i> Berkas yang Harus Dibawa
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="flex items-center gap-2 text-[14px] font-semibold px-3 py-2 bg-gray-50 border border-[#E5E7EB] rounded-[10px]">
                            <i class="fa-solid fa-file-lines text-[12px] text-[#FF1443] flex-shrink-0"></i> Surat Penerimaan (cetak)
                        </div>
                        <div class="flex items-center gap-2 text-[14px] font-semibold px-3 py-2 bg-gray-50 border border-[#E5E7EB] rounded-[10px]">
                            <i class="fa-solid fa-id-card text-[12px] text-[#3B82F6] flex-shrink-0"></i> Fotokopi KK (3 lembar)
                        </div>
                        <div class="flex items-center gap-2 text-[14px] font-semibold px-3 py-2 bg-gray-50 border border-[#E5E7EB] rounded-[10px]">
                            <i class="fa-solid fa-award text-[12px] text-[#8B5CF6] flex-shrink-0"></i> Ijazah / SKL Asli + Fotokopi
                        </div>
                        <div class="flex items-center gap-2 text-[14px] font-semibold px-3 py-2 bg-gray-50 border border-[#E5E7EB] rounded-[10px]">
                            <i class="fa-solid fa-camera text-[12px] text-[#F59E0B] flex-shrink-0"></i> Pas Foto 3×4 (6 lembar)
                        </div>
                        <div class="flex items-center gap-2 text-[14px] font-semibold px-3 py-2 bg-gray-50 border border-[#E5E7EB] rounded-[10px]">
                            <i class="fa-solid fa-book-open text-[12px] text-[#30B22D] flex-shrink-0"></i> Rapor Asli (bawa saja)
                        </div>
                        <div class="flex items-center gap-2 text-[14px] font-semibold px-3 py-2 bg-gray-50 border border-[#E5E7EB] rounded-[10px]">
                            <i class="fa-solid fa-money-bill-wave text-[12px] text-[#30B22D] flex-shrink-0"></i> Bukti / Uang Pembayaran
                        </div>
                    </div>
                </div>
            </div>

            {{-- SEC 5 — PERNYATAAN & KIRIM --}}
            <div id="sec5" class="bg-white border border-[#E5E7EB] rounded-[20px] shadow-[0_1px_8px_rgba(0,0,0,0.04)] overflow-hidden animate-fade-in-4">
                <div class="px-6 py-[18px] pb-[14px] border-b border-gray-100 flex items-center gap-[14px]">
                    <div class="w-[42px] h-[42px] rounded-[12px] bg-[#F0FDF4] flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-pen-line text-[18px] text-[#30B22D]"></i>
                    </div>
                    <div>
                        <div class="text-[17px] font-black">5 — Pernyataan &amp; Kirim</div>
                        <div class="text-[14px] text-[#6A7686] mt-[2px]">Baca dan setujui sebelum mengirim formulir</div>
                    </div>
                </div>
                <div class="px-6 py-[22px]">
                    {{-- Surat pernyataan --}}
                    <div class="bg-gray-50 border-[1.5px] border-[#E5E7EB] rounded-[16px] p-5 mb-5">
                        <p class="text-sm font-bold mb-[14px]">Surat Pernyataan Peserta Didik Baru</p>
                        <div class="text-[14px] text-[#6A7686] leading-relaxed mb-4">
                            Dengan mengirimkan formulir ini, saya sebagai calon peserta didik baru menyatakan:
                            <ol class="mt-[10px] ml-[18px] flex flex-col gap-[6px] list-decimal">
                                <li>Bersedia menaati seluruh tata tertib dan peraturan sekolah yang berlaku.</li>
                                <li>Sanggup mengikuti proses belajar mengajar secara aktif dan disiplin.</li>
                                <li>Bersedia mengikuti kegiatan MPLS (Masa Pengenalan Lingkungan Sekolah).</li>
                                <li>Data yang saya berikan adalah <strong>benar</strong> dan dapat dipertanggungjawabkan.</li>
                                <li>Memahami bahwa ketidakhadiran pada jadwal daftar ulang tanpa konfirmasi dapat berakibat pembatalan penerimaan.</li>
                            </ol>
                        </div>
                        <div class="flex flex-col gap-[10px]">
                            <label class="check-item flex items-start gap-[9px] cursor-pointer py-[1px]">
                                <input type="checkbox" id="ck1" onchange="updateSubmitBtn()">
                                <div class="check-box w-[17px] h-[17px] rounded-[5px] border-[1.5px] border-[#E5E7EB] bg-gray-50 flex items-center justify-center flex-shrink-0 mt-[1px] transition-all"></div>
                                <span class="text-[13px] font-medium leading-relaxed">Saya <strong>menyetujui</strong> seluruh pernyataan di atas atas nama diri sendiri.</span>
                            </label>
                            <label class="check-item flex items-start gap-[9px] cursor-pointer py-[1px]">
                                <input type="checkbox" id="ck2" onchange="updateSubmitBtn()">
                                <div class="check-box w-[17px] h-[17px] rounded-[5px] border-[1.5px] border-[#E5E7EB] bg-gray-50 flex items-center justify-center flex-shrink-0 mt-[1px] transition-all"></div>
                                <span class="text-[13px] font-medium leading-relaxed">Orang tua/wali saya <strong>menyetujui dan mendukung</strong> keputusan mendaftar di sekolah ini.</span>
                            </label>
                            <label class="check-item flex items-start gap-[9px] cursor-pointer py-[1px]">
                                <input type="checkbox" id="ck3" onchange="updateSubmitBtn()">
                                <div class="check-box w-[17px] h-[17px] rounded-[5px] border-[1.5px] border-[#E5E7EB] bg-gray-50 flex items-center justify-center flex-shrink-0 mt-[1px] transition-all"></div>
                                <span class="text-[13px] font-medium leading-relaxed">Saya memahami bahwa <strong>daftar ulang bersifat final</strong> dan tidak dapat dibatalkan sepihak.</span>
                            </label>
                        </div>
                    </div>

                    {{-- Input tanda tangan nama --}}
                    <div class="grid grid-cols-2 gap-[14px] mb-4">
                        <div class="flex flex-col gap-[5px]">
                            <label class="text-[13px] font-bold uppercase tracking-[0.04em]">
                                Nama Lengkap (ketik ulang) <span class="text-[#FF1443]">*</span>
                            </label>
                            <input type="text" placeholder="Ahmad Fauzi" id="namaSigns"
                                class="w-full px-[13px] py-[10px] font-sans text-[13px] font-medium text-[#080C1A] bg-gray-50 border-[1.5px] border-[#E5E7EB] rounded-[12px] outline-none transition-all placeholder:text-[#b0b8c4] placeholder:font-normal focus:border-[#FF1443] focus:bg-white focus:shadow-[0_0_0_3px_rgba(255,20,67,0.09)]">
                        </div>
                        <div class="flex flex-col gap-[5px]">
                            <label class="text-[13px] font-bold uppercase tracking-[0.04em]">
                                Nama Orang Tua / Wali <span class="text-[#FF1443]">*</span>
                            </label>
                            <input type="text" placeholder="Fauzi Rahmat" id="namaOrtus"
                                class="w-full px-[13px] py-[10px] font-sans text-[13px] font-medium text-[#080C1A] bg-gray-50 border-[1.5px] border-[#E5E7EB] rounded-[12px] outline-none transition-all placeholder:text-[#b0b8c4] placeholder:font-normal focus:border-[#FF1443] focus:bg-white focus:shadow-[0_0_0_3px_rgba(255,20,67,0.09)]">
                        </div>
                    </div>
                    <div class="flex flex-col gap-[5px]">
                        <label class="text-[13px] font-bold uppercase tracking-[0.04em]">
                            Catatan <span class="text-[#6A7686] font-medium normal-case text-[12px]">(opsional)</span>
                        </label>
                        <textarea placeholder="Tuliskan jika ada pertanyaan atau catatan untuk panitia..."
                            class="w-full px-[13px] py-[10px] font-sans text-[13px] font-medium text-[#080C1A] bg-gray-50 border-[1.5px] border-[#E5E7EB] rounded-[12px] outline-none transition-all resize-y min-h-[80px] leading-relaxed placeholder:text-[#b0b8c4] placeholder:font-normal focus:border-[#FF1443] focus:bg-white focus:shadow-[0_0_0_3px_rgba(255,20,67,0.09)]"></textarea>
                    </div>
                </div>
                {{-- Footer tombol --}}
                <div class="px-6 py-[14px] border-t border-gray-100 bg-gray-50 flex justify-between items-center flex-wrap gap-[10px]">
                    <a href="{{ route('pengumuman') }}"
                        class="inline-flex items-center gap-[7px] px-[22px] py-[11px] rounded-full font-sans text-[13px] font-bold no-underline bg-white text-[#080C1A] border-[1.5px] border-[#E5E7EB] hover:border-[#080C1A] hover:-translate-y-px transition-all">
                        <i class="fa-solid fa-arrow-left text-[13px]"></i> Kembali
                    </a>
                    <div class="flex gap-[10px] items-center">
                        <button class="inline-flex items-center gap-[7px] px-[22px] py-[11px] rounded-full font-sans text-[13px] font-bold bg-white text-[#080C1A] border-[1.5px] border-[#E5E7EB] hover:border-[#080C1A] hover:-translate-y-px transition-all cursor-pointer"
                            onclick="saveDraft()">
                            <i class="fa-solid fa-floppy-disk text-[13px]"></i> Simpan Draft
                        </button>
                        <button class="inline-flex items-center gap-[7px] px-[22px] py-[11px] rounded-full font-sans text-[13px] font-bold bg-[#30B22D] text-white shadow-[0_4px_14px_rgba(48,178,45,0.25)] hover:bg-[#27A024] hover:-translate-y-[2px] transition-all cursor-pointer opacity-[0.45]"
                            id="submitBtn" onclick="submitForm()" disabled>
                            <i class="fa-solid fa-paper-plane text-[13px]"></i> Kirim Daftar Ulang
                        </button>
                    </div>
                </div>
            </div>

            {{-- SUCCESS SCREEN --}}
            <div id="successScreen"
                class="hidden text-center bg-white border border-[#E5E7EB] rounded-[20px] p-12 shadow-[0_2px_16px_rgba(0,0,0,0.05)] animate-[popUp_0.5s_cubic-bezier(0.175,0.885,0.32,1.275)_both]">
                <div class="w-20 h-20 rounded-full bg-[#DCFCE7] flex items-center justify-center mx-auto mb-5">
                    <i class="fa-solid fa-circle-check text-[36px] text-[#30B22D]"></i>
                </div>
                <h2 class="text-[24px] font-black mb-2">Daftar Ulang Berhasil! 🎉</h2>
                <p class="text-[13px] text-[#6A7686] max-w-[380px] mx-auto mb-6 leading-relaxed">
                    Formulir daftar ulang kamu telah diterima. Cek email dan WhatsApp untuk konfirmasi jadwal kedatangan di sekolah.
                </p>
                <div class="bg-gray-50 border border-[#E5E7EB] rounded-[16px] p-5 text-left mb-6">
                    <div class="flex items-center gap-3 mb-4 pb-3 border-b border-[#E5E7EB]">
                        <div class="w-10 h-10 rounded-[10px] bg-[#FF1443] flex items-center justify-center">
                            <i class="fa-solid fa-graduation-cap text-base text-white"></i>
                        </div>
                        <div>
                            <div class="text-sm font-black">SMK Negeri 1 Kota Palembang</div>
                            <div class="text-[13px] text-[#6A7686]">Bukti Daftar Ulang — TP 2026/2027</div>
                        </div>
                        <span class="ml-auto inline-flex items-center gap-[5px] px-[10px] py-[3px] rounded-full text-[13px] font-bold bg-[#DCFCE7] text-[#166534] border border-[rgba(48,178,45,0.2)]">
                            <i class="fa-solid fa-circle-check text-[11px]"></i> Sah
                        </span>
                    </div>
                    <div class="text-center py-1 pb-3">
                        <div class="text-[12px] font-bold text-[#6A7686] uppercase tracking-[0.08em] mb-[6px]">Nomor Bukti Daftar Ulang</div>
                        <code class="block text-center text-[18px] font-extrabold bg-[#080C1A] text-white rounded-[10px] py-[10px] px-4 my-[14px] tracking-[0.08em]">DU-2026-004821-RPL</code>
                    </div>
                    <div class="grid grid-cols-2 gap-[10px]">
                        <div>
                            <div class="text-[12px] font-bold uppercase tracking-[0.05em] text-[#6A7686] mb-[3px]">Nama</div>
                            <div class="text-[13px] font-bold">Ahmad Fauzi</div>
                        </div>
                        <div>
                            <div class="text-[12px] font-bold uppercase tracking-[0.05em] text-[#6A7686] mb-[3px]">No. Peserta</div>
                            <div class="text-[13px] font-bold">SPMB-2026-004821</div>
                        </div>
                        <div>
                            <div class="text-[12px] font-bold uppercase tracking-[0.05em] text-[#6A7686] mb-[3px]">Jurusan</div>
                            <div class="text-[13px] font-bold">Rekayasa Perangkat Lunak</div>
                        </div>
                        <div>
                            <div class="text-[12px] font-bold uppercase tracking-[0.05em] text-[#6A7686] mb-[3px]">Total Dibayar</div>
                            <div class="text-[13px] font-bold text-[#FF1443]">Rp 1.245.000</div>
                        </div>
                        <div>
                            <div class="text-[12px] font-bold uppercase tracking-[0.05em] text-[#6A7686] mb-[3px]">Jadwal Hadir</div>
                            <div class="text-[13px] font-bold" id="receiptJadwal">—</div>
                        </div>
                    </div>
                </div>
                <div class="flex gap-[10px] justify-center flex-wrap">
                    <button class="inline-flex items-center gap-[7px] px-[22px] py-[11px] rounded-full font-sans text-[13px] font-bold bg-white text-[#080C1A] border-[1.5px] border-[#E5E7EB] hover:border-[#080C1A] hover:-translate-y-px transition-all cursor-pointer"
                        onclick="window.print()">
                        <i class="fa-solid fa-print text-[13px]"></i> Cetak Bukti
                    </button>
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center gap-[7px] px-[22px] py-[11px] rounded-full font-sans text-[13px] font-bold bg-[#FF1443] text-white no-underline shadow-[0_4px_14px_rgba(255,20,67,0.28)] hover:bg-[#D90F38] hover:-translate-y-[2px] hover:shadow-[0_7px_22px_rgba(255,20,67,0.38)] transition-all">
                        <i class="fa-solid fa-gauge text-[13px]"></i> Ke Dashboard
                    </a>
                </div>
            </div>

        </div>{{-- /formCol --}}

        {{-- ── SIDEBAR ── --}}
        <div class="sticky top-20 flex flex-col gap-4">

            {{-- Progress Daftar Ulang --}}
            <div class="bg-white border border-[#E5E7EB] rounded-[20px] overflow-hidden shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
                <div class="px-[18px] py-[14px] border-b border-gray-100">
                    <h3 class="text-sm font-black">Progress Daftar Ulang</h3>
                    <p class="text-[13px] text-[#6A7686] mt-[2px]">Langkah yang harus diselesaikan</p>
                </div>
                <div class="px-[18px] py-[14px]">
                    <div class="flex items-center gap-[10px] py-2 border-b border-gray-100 text-[14px]">
                        <div class="w-[22px] h-[22px] rounded-full bg-[#DCFCE7] text-[#30B22D] flex items-center justify-center text-[12px] flex-shrink-0">
                            <i class="fa-solid fa-check text-[10px]"></i>
                        </div>
                        <span class="flex-1 font-semibold text-[#6A7686] line-through">Konfirmasi Data</span>
                    </div>
                    <div class="flex items-center gap-[10px] py-2 border-b border-gray-100 text-[14px]">
                        <div class="w-[22px] h-[22px] rounded-full bg-[rgba(255,20,67,0.08)] text-[#FF1443] flex items-center justify-center text-[12px] flex-shrink-0">
                            <i class="fa-solid fa-shirt text-[10px]"></i>
                        </div>
                        <span class="flex-1 font-semibold text-[#FF1443]">Pilih Seragam</span>
                    </div>
                    <div class="flex items-center gap-[10px] py-2 border-b border-gray-100 text-[14px]">
                        <div class="w-[22px] h-[22px] rounded-full bg-gray-100 text-[#6A7686] flex items-center justify-center text-[12px] flex-shrink-0 font-bold">3</div>
                        <span class="flex-1 font-semibold text-[#6A7686]">Pembayaran</span>
                    </div>
                    <div class="flex items-center gap-[10px] py-2 border-b border-gray-100 text-[14px]">
                        <div class="w-[22px] h-[22px] rounded-full bg-gray-100 text-[#6A7686] flex items-center justify-center text-[12px] flex-shrink-0 font-bold">4</div>
                        <span class="flex-1 font-semibold text-[#6A7686]">Jadwal Kehadiran</span>
                    </div>
                    <div class="flex items-center gap-[10px] py-2 text-[14px]">
                        <div class="w-[22px] h-[22px] rounded-full bg-gray-100 text-[#6A7686] flex items-center justify-center text-[12px] flex-shrink-0 font-bold">5</div>
                        <span class="flex-1 font-semibold text-[#6A7686]">Pernyataan &amp; Kirim</span>
                    </div>
                </div>
                <div class="px-[18px] py-3 border-t border-gray-100">
                    <div class="flex justify-between text-[13px] font-semibold text-[#6A7686] mb-[7px]">
                        <span>Progress</span>
                        <span id="progressPct" class="text-[#FF1443]">40%</span>
                    </div>
                    <div class="h-[6px] bg-gray-200 rounded-full overflow-hidden">
                        <div id="progressBar"
                            class="h-full rounded-full transition-all duration-[400ms]"
                            style="width: 40%; background: linear-gradient(90deg, #FF1443, #FF6B8A)"></div>
                    </div>
                </div>
            </div>

            {{-- Ringkasan Biaya --}}
            <div class="bg-white border border-[#E5E7EB] rounded-[20px] overflow-hidden shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
                <div class="px-[18px] py-[14px] border-b border-gray-100 bg-[#080C1A]">
                    <h3 class="text-sm font-black text-white">Ringkasan Biaya</h3>
                    <p class="text-[13px] text-white/55 mt-[2px]">Estimasi total pembayaran</p>
                </div>
                <div class="px-[18px] py-[14px]">
                    <div class="flex justify-between py-[7px] border-b border-gray-100 text-[14px]">
                        <span class="text-[#6A7686] font-medium">Biaya Daftar Ulang</span>
                        <span class="font-bold">Rp 250.000</span>
                    </div>
                    <div class="flex justify-between py-[7px] border-b border-gray-100 text-[14px]">
                        <span class="text-[#6A7686] font-medium">Paket Seragam Wajib</span>
                        <span class="font-bold">Rp 650.000</span>
                    </div>
                    <div class="flex justify-between py-[7px] border-b border-gray-100 text-[14px]">
                        <span class="text-[#6A7686] font-medium">Perlengkapan Tambahan</span>
                        <span class="font-bold" id="sideExtra">Rp 145.000</span>
                    </div>
                    <div class="flex justify-between py-[7px] text-[14px]">
                        <span class="text-[#6A7686] font-medium">SPP Juli 2026</span>
                        <span class="font-bold">Rp 200.000</span>
                    </div>
                    <div class="flex justify-between pt-[10px] border-t-2 border-[#080C1A] mt-1">
                        <span class="text-[13px] font-black">Total</span>
                        <span class="text-[17px] font-black text-[#FF1443]" id="sideTotal">Rp 1.245.000</span>
                    </div>
                </div>
            </div>

            {{-- Bantuan --}}
            <div class="bg-white border border-[#E5E7EB] rounded-[20px] overflow-hidden shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
                <div class="px-[18px] py-[14px]">
                    <p class="text-[14px] font-bold mb-2 flex items-center gap-[6px]">
                        <i class="fa-solid fa-circle-question text-[14px] text-[#FF1443]"></i>Butuh Bantuan?
                    </p>
                    <p class="text-[13px] text-[#6A7686] leading-relaxed mb-3">
                        Panitia SPMB siap membantu selama jam kerja 08:00–16:00 WIB.
                    </p>
                    <a href="https://wa.me/6281234567890" target="_blank"
                        class="inline-flex w-full items-center justify-center gap-[7px] px-4 py-[9px] rounded-full font-sans text-[14px] font-bold no-underline bg-white border-[1.5px] border-[#25D366] text-[#25D366] hover:-translate-y-px transition-all">
                        <i class="fa-solid fa-comment text-[13px]"></i> Chat WhatsApp Panitia
                    </a>
                </div>
            </div>

        </div>{{-- /sidebar --}}

    </div>{{-- /grid --}}
</div>{{-- /wrapper --}}

{{-- ══════════════════════════════════════════
        JAVASCRIPT
══════════════════════════════════════════ --}}

@push('scripts')
<script>
    function selectPayMethod(id) {
        document.querySelectorAll('.pay-method').forEach(m => m.classList.remove('selected'));
        document.getElementById('pm-' + id).classList.add('selected');
        document.getElementById('uploadSection').style.display = id === 'bank' ? 'block' : 'none';
    }

    let selectedSlot = null;

    function selectSlot(el) {
        if (el.classList.contains('full')) return;
        document.querySelectorAll('.slot-card').forEach(s => s.classList.remove('selected'));
        el.classList.add('selected');
        selectedSlot = el.querySelector('.slot-date').textContent + ' ' + el.querySelector('.slot-time').textContent;
        document.getElementById('receiptJadwal').textContent = selectedSlot;
    }

    function selectUniform(el) {
        el.classList.toggle('selected');
    }

    function copyToClipboard(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            const orig = btn.innerHTML;
            btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>';
            setTimeout(() => {
                btn.innerHTML = orig;
            }, 1500);
        });
    }

    function handleUpload(input) {
        if (!input.files[0]) return;
        const prev = document.getElementById('buktiPreview');
        prev.style.display = 'flex';
        document.getElementById('buktiName').textContent = input.files[0].name;
    }

    function removeUpload() {
        document.getElementById('buktiPreview').style.display = 'none';
        document.getElementById('buktiFile').value = '';
    }

    function updateSubmitBtn() {
        const allChecked = ['ck1', 'ck2', 'ck3'].every(id => document.getElementById(id).checked);
        const btn = document.getElementById('submitBtn');
        btn.disabled = !allChecked;
        btn.style.opacity = allChecked ? '1' : '0.45';
    }

    function updateChecklist() {}

    function saveDraft() {
        const t = document.createElement('div');
        t.className = 'fixed bottom-6 left-1/2 -translate-x-1/2 bg-[#080C1A] text-white px-[22px] py-[10px] rounded-full text-[13px] font-bold flex items-center gap-2 shadow-[0_4px_20px_rgba(0,0,0,0.18)] z-[9999]';
        t.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg> Draft disimpan';
        document.body.appendChild(t);
        setTimeout(() => t.remove(), 2500);
    }

    function submitForm() {
        const allChecked = ['ck1', 'ck2', 'ck3'].every(id => document.getElementById(id).checked);
        if (!allChecked) return;
        document.querySelectorAll('#sec1,#sec2,#sec3,#sec4,#sec5').forEach(s => s.style.display = 'none');
        const ss = document.getElementById('successScreen');
        ss.style.display = 'block';
        document.getElementById('progressBar').style.width = '100%';
        document.getElementById('progressPct').textContent = '100%';
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    // Default: tampilkan upload section (bank transfer aktif)
    document.getElementById('uploadSection').style.display = 'block';

    // Responsive: collapse sidebar on mobile
    function handleResize() {
        const w = document.getElementById('mainWrapper');
        if (window.innerWidth < 960) {
            w.classList.remove('lg:grid-cols-[1fr_300px]');
            w.classList.add('grid-cols-1', 'px-4');
        } else {
            w.classList.add('lg:grid-cols-[1fr_300px]');
            w.classList.remove('grid-cols-1');
        }
    }
    window.addEventListener('resize', handleResize);
    handleResize();
</script>
@endpush

@endsection
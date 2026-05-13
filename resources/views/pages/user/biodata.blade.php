@extends('layouts.user')

@section('title', 'Data Pribadi')

@section('content')

<div x-data="{
    step: 1,
    totalSteps: 6,
    isSubmitted: false,
    showWali: false,
    sameAddress: false,
    files: {
        akta: null,
        ijazah: null,
        rapor: null,
        foto: null,
        sertif: null
    },
    get progressPct() {
        return Math.round((this.step / this.totalSteps) * 100);
    },
    stepLabels: ['Data Pribadi','Alamat','Orang Tua','Pendidikan','Dokumen','Konfirmasi'],
    stepIcons: ['fa-user','fa-location-dot','fa-people-roof','fa-book-open-reader','fa-folder-open','fa-clipboard-check'],
    sidebarStatus(i) {
        if (i < this.step) return 'done';
        if (i === this.step) return 'active';
        return 'pending';
    }
}">

    {{-- ══════════════════════════════════════════
            BREADCRUMB
    ══════════════════════════════════════════ --}}
    <div x-show="!isSubmitted" class="flex items-center gap-1.5 text-[13px] text-[#6A7686] mb-4">
        <a href="{{ route('dashboard') }}" class="text-[#FF1443] no-underline font-semibold">
            <i class="fa-solid fa-house"></i> Beranda
        </a>
        <span class="text-gray-300">/</span>
        <a href="{{ route('dashboard') }}" class="text-[#FF1443] no-underline font-semibold">Dashboard</a>
        <span class="text-gray-300">/</span>
        <span>Lengkapi Biodata</span>
    </div>

    {{-- ══════════════════════════════════════════
        HERO BANNER
══════════════════════════════════════════ --}}
    <div x-show="!isSubmitted"
        class="relative rounded-[20px] overflow-hidden mb-5 p-6 md:p-7 flex flex-col md:flex-row items-center justify-between gap-5"
        style="background: linear-gradient(135deg, #FF1443 0%, #D90F38 50%, #B00F30 100%);">
        {{-- Decorative circles --}}
        <div class="absolute -top-10 -right-10 w-[200px] h-[200px] bg-white/[0.06] rounded-full pointer-events-none"></div>
        <div class="absolute -bottom-[60px] right-24 w-[160px] h-[160px] bg-white/[0.04] rounded-full pointer-events-none"></div>

        {{-- Left --}}
        <div class="relative z-10 w-full md:flex-1 text-center md:text-left">
            <div class="inline-flex items-center gap-1.5 bg-white/15 text-white text-sm font-bold px-3 py-1 rounded-full mb-3 border border-white/25">
                <i class="fa-solid fa-id-card"></i> Formulir Biodata
            </div>
            <h1 class="text-xl md:text-2xl font-black text-white mb-1 leading-tight">Kelengkapan Data Diri</h1>
            <p class="text-sm text-white/80 leading-relaxed mb-5 max-w-[540px]">
                Lengkapi seluruh data berikut dengan benar dan jujur. Data akan digunakan dalam proses seleksi penerimaan peserta didik baru.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center md:justify-start">
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white text-[#FF1443] text-[13px] font-black rounded-full no-underline shadow-md hover:bg-gray-50 transition-all">
                    <i class="fa-solid fa-gauge"></i> Kembali ke Dashboard
                </a>
                <span class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white/15 text-white text-[13px] font-bold rounded-full border border-white/25 cursor-default">
                    <i class="fa-solid fa-id-badge"></i> No. Peserta: SPMB-2026-004821
                </span>
            </div>
        </div>

        {{-- Right: Progress Card --}}
        <div class="relative z-10 w-full md:w-auto flex-shrink-0">
            <div class="bg-white/10 border border-white/20 rounded-[16px] px-6 py-5 backdrop-blur-md text-center shadow-xl min-w-[180px]">
                <div class="text-[12px] text-white/70 font-semibold uppercase tracking-widest mb-2">Progress Pengisian</div>
                <div class="text-[28px] font-black text-white leading-none mb-1" x-text="progressPct + '%'"></div>
                <div class="h-1.5 bg-white/25 rounded-full overflow-hidden mb-2">
                    <div class="h-full bg-white rounded-full transition-all duration-500"
                        :style="'width:' + progressPct + '%'"></div>
                </div>
                <div class="text-[12px] text-white/70 font-semibold" x-text="'Langkah ' + step + ' dari ' + totalSteps"></div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
            TWO-COLUMN LAYOUT
    ══════════════════════════════════════════ --}}
    <div class="lg:grid lg:grid-cols-[1fr_340px] lg:gap-6 lg:items-start" x-show="!isSubmitted">

        {{-- ── MAIN COLUMN ── --}}
        <div class="min-w-0">

            {{-- STEPPER --}}
            {{--
                Pendekatan: setiap node dibungkus flex-1, lalu garis antar node
                dirender sebagai elemen absolut di dalam wrapper node kiri,
                memanjang dari tengah node kiri ke tengah node kanan (100% parent flex-1).
                Dengan begitu garis selalu tepat mulai/berakhir di pusat node.
            --}}
            <div class="bg-white border border-gray-200 rounded-[20px] px-7 py-6 mb-6 shadow-sm">
                <div class="flex items-start justify-between">
                    <template x-for="i in totalSteps" :key="i">
                        <div class="flex flex-col items-center flex-1 relative"
                            :class="i < totalSteps ? 'pr-0' : ''">

                            {{-- Garis segmen: dari tengah node ini ke tengah node berikutnya --}}
                            {{-- Hanya render untuk node bukan terakhir --}}
                            <template x-if="i < totalSteps">
                                <div class="absolute top-[21px] left-1/2 w-full h-0.5 z-0"
                                    :class="step > i ? 'bg-primary' : 'bg-gray-200'"></div>
                            </template>

                            {{-- Node (dot) --}}
                            <div class="relative z-10 w-11 h-11 rounded-full flex items-center justify-center font-bold text-base border-2 transition-all duration-300 cursor-pointer bg-white"
                                :class="{
                                    'bg-green-500 border-green-500 text-white': step > i,
                                    'bg-primary border-primary text-white shadow-lg shadow-primary/20': step === i,
                                    'bg-white border-gray-200 text-gray-400': step < i
                                }"
                                @click="step = i">
                                <template x-if="step > i"><i class="fa-solid fa-check text-sm"></i></template>
                                <template x-if="step === i"><i :class="'fa-solid ' + stepIcons[i-1] + ' text-sm'"></i></template>
                                <template x-if="step < i"><span x-text="i"></span></template>
                            </div>

                            {{-- Label --}}
                            <span class="hidden sm:block text-[12px] font-semibold text-center max-w-[72px] leading-tight mt-2.5 transition-colors"
                                :class="step >= i ? 'text-primary' : 'text-gray-400'"
                                x-text="stepLabels[i-1]"></span>
                        </div>
                    </template>
                </div>

                <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-100">
                    <span class="text-sm font-semibold text-[#6A7686]" x-text="'Langkah ' + step + ' dari ' + totalSteps"></span>
                    <div class="flex-1 h-2 bg-gray-200 rounded-full mx-4 overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-500"
                            style="background: linear-gradient(90deg,#FF1443,#FF6B8A)"
                            :style="'width:' + progressPct + '%'"></div>
                    </div>
                    <span class="text-base font-bold text-primary" x-text="progressPct + '%'"></span>
                </div>
            </div>

            <form @submit.prevent="isSubmitted = true">

                {{-- ══════════════════════════════
                    STEP 1 — DATA PRIBADI
            ══════════════════════════════ --}}
                <div x-show="step === 1"
                    class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">
                    <div class="px-8 pt-6 pb-5 border-b border-gray-100 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-primary-light flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-user text-primary text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-black text-[#080C1A]">Data Pribadi</h2>
                            <p class="text-sm text-[#6A7686]">Informasi dasar calon peserta didik sesuai dokumen resmi</p>
                        </div>
                    </div>
                    <div class="px-8 py-7 space-y-5">
                        {{-- Info box --}}
                        <div class="flex gap-3 items-start bg-blue-50 border border-blue-200 rounded-2xl px-4 py-3.5">
                            <i class="fa-solid fa-circle-info text-blue-500 text-base mt-0.5 flex-shrink-0"></i>
                            <p class="text-sm font-medium text-blue-800 leading-relaxed">Isi data sesuai dengan <strong>Kartu Keluarga (KK)</strong> atau <strong>Akta Kelahiran</strong>. Pastikan tidak ada kesalahan penulisan nama.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-[1fr_2fr] gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">NIK <span class="text-primary">*</span></label>
                                <input type="text" maxlength="16" placeholder="16 digit NIK" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                                <p class="text-[13px] text-[#6A7686]">Sesuai KTP/KK</p>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">NISN <span class="text-primary">*</span></label>
                                <input type="text" maxlength="10" placeholder="Nomor Induk Siswa Nasional" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-[2fr_1fr] gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Nama Lengkap <span class="text-primary">*</span></label>
                                <input type="text" placeholder="Sesuai akta kelahiran, tanpa singkatan" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Nama Panggilan</label>
                                <input type="text" placeholder="Nama sehari-hari" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                            </div>
                        </div>

                        <div class="border-t border-dashed border-gray-200 pt-5">
                            <p class="text-sm font-bold flex items-center gap-2 mb-4 pb-2.5 border-b border-gray-100">
                                <i class="fa-solid fa-calendar text-primary"></i> Informasi Kelahiran
                            </p>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Tempat Lahir <span class="text-primary">*</span></label>
                                    <input type="text" placeholder="Kota/Kabupaten" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Tanggal Lahir <span class="text-primary">*</span></label>
                                    <input type="date" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Jenis Kelamin <span class="text-primary">*</span></label>
                                    <select class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] appearance-none cursor-pointer pr-10">
                                        <option value="">Pilih</option>
                                        <option>Laki-laki</option>
                                        <option>Perempuan</option>
                                    </select>

                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Agama <span class="text-primary">*</span></label>
                                <select class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] appearance-none cursor-pointer pr-10">
                                    <option value="">Pilih Agama</option>
                                    <option>Islam</option>
                                    <option>Kristen</option>
                                    <option>Katolik</option>
                                    <option>Hindu</option>
                                    <option>Budha</option>
                                    <option>Konghucu</option>
                                </select>

                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Kewarganegaraan <span class="text-primary">*</span></label>
                                <select class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] appearance-none cursor-pointer pr-10">
                                    <option>WNI</option>
                                    <option>WNA</option>
                                </select>

                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Golongan Darah</label>
                                <select class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] appearance-none cursor-pointer pr-10">
                                    <option value="">Tidak tahu</option>
                                    <option>A</option>
                                    <option>B</option>
                                    <option>AB</option>
                                    <option>O</option>
                                </select>

                            </div>
                        </div>

                        <div class="border-t border-dashed border-gray-200 pt-5">
                            <p class="text-sm font-bold flex items-center gap-2 mb-4 pb-2.5 border-b border-gray-100">
                                <i class="fa-solid fa-phone text-primary"></i> Kontak
                            </p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">No. HP / WhatsApp <span class="text-primary">*</span></label>
                                    <div class="flex">
                                        <span class="inline-flex items-center px-4 bg-gray-100 border border-r-0 border-gray-200 rounded-l-xl text-base font-bold text-[#6A7686]">+62</span>
                                        <input type="tel" placeholder="81234567890" class="flex-1 min-w-0 px-4 py-3 rounded-r-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Email Aktif <span class="text-primary">*</span></label>
                                    <div class="relative">
                                        <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-[#6A7686] text-sm pointer-events-none"></i>
                                        <input type="email" placeholder="nama@email.com" class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                                    </div>
                                    <p class="text-[13px] text-green-600 font-semibold"><i class="fa-solid fa-check"></i> Email terverifikasi</p>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-dashed border-gray-200 pt-5">
                            <p class="text-sm font-bold flex items-center gap-2 mb-4 pb-2.5 border-b border-gray-100">
                                <i class="fa-solid fa-heart-pulse text-primary"></i> Kondisi Fisik & Kebutuhan Khusus
                            </p>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Tinggi Badan <span class="text-[13px] text-[#6A7686] normal-case font-normal">(opsional)</span></label>
                                    <div class="flex">
                                        <input type="number" placeholder="165" class="flex-1 min-w-0 px-4 py-3 rounded-l-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                                        <span class="inline-flex items-center px-4 bg-gray-100 border border-l-0 border-gray-200 rounded-r-xl text-xs font-bold text-[#6A7686]">cm</span>
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Berat Badan <span class="text-[13px] text-[#6A7686] normal-case font-normal">(opsional)</span></label>
                                    <div class="flex">
                                        <input type="number" placeholder="55" class="flex-1 min-w-0 px-4 py-3 rounded-l-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                                        <span class="inline-flex items-center px-4 bg-gray-100 border border-l-0 border-gray-200 rounded-r-xl text-xs font-bold text-[#6A7686]">kg</span>
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Kebutuhan Khusus</label>
                                    <select class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] appearance-none cursor-pointer pr-10">
                                        <option value="">Tidak ada</option>
                                        <option>Tunanetra</option>
                                        <option>Tunarungu</option>
                                        <option>Tunadaksa</option>
                                        <option>Lainnya</option>
                                    </select>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-8 py-5 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between rounded-b-[20px]">
                        <button type="button" onclick="saveDraft()" class="text-sm font-semibold text-[#6A7686] flex items-center gap-1.5 hover:text-primary transition-colors">
                            <i class="fa-regular fa-floppy-disk"></i> Simpan Draft
                        </button>
                        <button type="button" @click="step++"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-full hover:bg-primary-hover hover:-translate-y-px transition-all shadow-lg shadow-primary/30">
                            Lanjut: Alamat <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                {{-- ══════════════════════════════
                    STEP 2 — ALAMAT
            ══════════════════════════════ --}}
                <div x-show="step === 2"
                    class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">
                    <div class="px-8 pt-6 pb-5 border-b border-gray-100 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-location-dot text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-black text-[#080C1A]">Data Alamat</h2>
                            <p class="text-sm text-[#6A7686]">Alamat tempat tinggal saat ini dan asal daerah</p>
                        </div>
                    </div>
                    <div class="px-8 py-7 space-y-5">
                        <p class="text-sm font-bold flex items-center gap-2 pb-2.5 border-b border-gray-100">
                            <i class="fa-solid fa-house text-primary"></i> Alamat Domisili (Saat Ini)
                        </p>
                        <div class="space-y-1.5">
                            <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Alamat Lengkap <span class="text-primary">*</span></label>
                            <textarea rows="3" placeholder="Nama jalan, nomor rumah, RT/RW, nama dusun/kampung..."
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal resize-y min-h-[90px]"></textarea>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Provinsi <span class="text-primary">*</span></label>
                                <select class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] appearance-none cursor-pointer pr-10">
                                    <option value="">Pilih Provinsi</option>
                                    <option>Bengkulu</option>
                                    <option>Sumatera Selatan</option>
                                    <option>DKI Jakarta</option>
                                    <option>Jawa Barat</option>
                                    <option>Jawa Tengah</option>
                                    <option>Jawa Timur</option>
                                </select>

                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Kota / Kabupaten <span class="text-primary">*</span></label>
                                <select class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] appearance-none cursor-pointer pr-10">
                                    <option value="">Pilih Kota/Kab</option>
                                    <option>Kota Bengkulu</option>
                                    <option>Kab. Rejang Lebong</option>
                                </select>

                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Kecamatan <span class="text-primary">*</span></label>
                                <input type="text" placeholder="Nama kecamatan" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Kelurahan / Desa <span class="text-primary">*</span></label>
                                <input type="text" placeholder="Nama kelurahan" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Kode Pos <span class="text-primary">*</span></label>
                                <input type="text" placeholder="3XXXX" maxlength="5" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">RT</label>
                                <input type="text" placeholder="001" maxlength="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">RW</label>
                                <input type="text" placeholder="002" maxlength="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                            </div>
                        </div>

                        <div class="border-t border-dashed border-gray-200 pt-5 space-y-5">
                            <p class="text-sm font-bold flex items-center gap-2 pb-2.5 border-b border-gray-100">
                                <i class="fa-solid fa-location-crosshairs text-primary"></i> Jarak & Transportasi ke Sekolah
                            </p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Jarak Rumah ke Sekolah</label>
                                    <select class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] appearance-none cursor-pointer pr-10">
                                        <option value="">Pilih kisaran</option>
                                        <option>Kurang dari 1 km</option>
                                        <option>1 – 5 km</option>
                                        <option>5 – 10 km</option>
                                        <option>10 – 20 km</option>
                                        <option>Lebih dari 20 km</option>
                                    </select>

                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Moda Transportasi</label>
                                    <select class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] appearance-none cursor-pointer pr-10">
                                        <option value="">Pilih</option>
                                        <option>Jalan kaki</option>
                                        <option>Sepeda</option>
                                        <option>Sepeda motor</option>
                                        <option>Angkutan umum</option>
                                        <option>Diantar orang tua</option>
                                    </select>

                                </div>
                            </div>
                        </div>

                        <div class="border-t border-dashed border-gray-200 pt-5 space-y-4">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-bold flex items-center gap-2">
                                    <i class="fa-solid fa-map-pin text-primary"></i> Alamat Asal (KK) — jika berbeda
                                </p>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" x-model="sameAddress" class="w-4 h-4 accent-primary">
                                    <span class="text-sm text-[#6A7686]">Sama dengan domisili</span>
                                </label>
                            </div>
                            <div x-show="!sameAddress" class="space-y-5">
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Alamat Asal Lengkap</label>
                                    <textarea rows="2" placeholder="Isi jika berbeda dengan domisili..."
                                        class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal resize-y min-h-[72px]"></textarea>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div class="space-y-1.5">
                                        <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Provinsi Asal</label>
                                        <select class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] appearance-none cursor-pointer pr-10">
                                            <option>Pilih Provinsi</option>
                                            <option>Bengkulu</option>
                                        </select>

                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Kota / Kab. Asal</label>
                                        <select class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] appearance-none cursor-pointer pr-10">
                                            <option>Pilih Kota/Kab</option>
                                            <option>Kota Bengkulu</option>
                                        </select>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-8 py-5 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between rounded-b-[20px]">
                        <button type="button" @click="step--"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-[#6A7686] border border-gray-200 rounded-full hover:border-[#080C1A] hover:text-[#080C1A] transition-all">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </button>
                        <div class="flex items-center gap-3">
                            <button type="button" onclick="saveDraft()" class="text-sm font-semibold text-[#6A7686] flex items-center gap-1.5 hover:text-primary transition-colors">
                                <i class="fa-regular fa-floppy-disk"></i> Simpan Draft
                            </button>
                            <button type="button" @click="step++"
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-full hover:bg-primary-hover hover:-translate-y-px transition-all shadow-lg shadow-primary/30">
                                Lanjut: Data Orang Tua <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ══════════════════════════════
                    STEP 3 — DATA ORANG TUA
            ══════════════════════════════ --}}
                <div x-show="step === 3"
                    class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">
                    <div class="px-8 pt-6 pb-5 border-b border-gray-100 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-people-roof text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-black text-[#080C1A]">Data Orang Tua / Wali</h2>
                            <p class="text-sm text-[#6A7686]">Data ayah, ibu, dan wali (jika ada)</p>
                        </div>
                    </div>
                    <div class="px-8 py-7 space-y-5">

                        {{-- Ayah --}}
                        <p class="text-sm font-bold flex items-center gap-2 pb-2.5 border-b border-gray-100">
                            <i class="fa-solid fa-person text-blue-500"></i> Data Ayah Kandung
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="space-y-1.5 sm:col-span-2">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Nama Lengkap Ayah <span class="text-primary">*</span></label>
                                <input type="text" placeholder="Sesuai KTP" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">NIK Ayah</label>
                                <input type="text" maxlength="16" placeholder="16 digit NIK" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Tanggal Lahir Ayah</label>
                                <input type="date" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Pendidikan Terakhir</label>
                                <select class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] appearance-none cursor-pointer pr-10">
                                    <option>Pilih</option>
                                    <option>Tidak sekolah</option>
                                    <option>SD/Sederajat</option>
                                    <option>SMP/Sederajat</option>
                                    <option>SMA/SMK/Sederajat</option>
                                    <option>D1/D2/D3</option>
                                    <option>S1/D4</option>
                                    <option>S2/S3</option>
                                </select>

                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Pekerjaan</label>
                                <select class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] appearance-none cursor-pointer pr-10">
                                    <option>Pilih</option>
                                    <option>PNS/TNI/Polri</option>
                                    <option>Pegawai Swasta</option>
                                    <option>Wiraswasta/Pedagang</option>
                                    <option>Petani/Nelayan</option>
                                    <option>Buruh</option>
                                    <option>Tidak bekerja</option>
                                    <option>Lainnya</option>
                                </select>

                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Penghasilan Per Bulan</label>
                                <select class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] appearance-none cursor-pointer pr-10">
                                    <option>Pilih kisaran</option>
                                    <option>Tidak berpenghasilan</option>
                                    <option>Di bawah Rp 1.000.000</option>
                                    <option>Rp 1.000.000 – 3.000.000</option>
                                    <option>Rp 3.000.000 – 5.000.000</option>
                                    <option>Di atas Rp 5.000.000</option>
                                </select>

                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">No. HP Ayah</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-4 bg-gray-100 border border-r-0 border-gray-200 rounded-l-xl text-base font-bold text-[#6A7686]">+62</span>
                                    <input type="tel" placeholder="81234567890" class="flex-1 min-w-0 px-4 py-3 rounded-r-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-dashed border-gray-200 pt-5 space-y-5">
                            {{-- Ibu --}}
                            <p class="text-sm font-bold flex items-center gap-2 pb-2.5 border-b border-gray-100">
                                <i class="fa-solid fa-person-dress text-pink-500"></i> Data Ibu Kandung
                            </p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div class="space-y-1.5 sm:col-span-2">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Nama Lengkap Ibu <span class="text-primary">*</span></label>
                                    <input type="text" placeholder="Sesuai KTP" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">NIK Ibu</label>
                                    <input type="text" maxlength="16" placeholder="16 digit NIK" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Tanggal Lahir Ibu</label>
                                    <input type="date" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Pendidikan Terakhir</label>
                                    <select class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] appearance-none cursor-pointer pr-10">
                                        <option>Pilih</option>
                                        <option>Tidak sekolah</option>
                                        <option>SD/Sederajat</option>
                                        <option>SMP/Sederajat</option>
                                        <option>SMA/SMK/Sederajat</option>
                                        <option>D1/D2/D3</option>
                                        <option>S1/D4</option>
                                        <option>S2/S3</option>
                                    </select>

                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Pekerjaan</label>
                                    <select class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] appearance-none cursor-pointer pr-10">
                                        <option>Pilih</option>
                                        <option>PNS/TNI/Polri</option>
                                        <option>Pegawai Swasta</option>
                                        <option>Wiraswasta/Pedagang</option>
                                        <option>Ibu Rumah Tangga</option>
                                        <option>Petani/Nelayan</option>
                                        <option>Tidak bekerja</option>
                                        <option>Lainnya</option>
                                    </select>

                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Penghasilan Per Bulan</label>
                                    <select class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] appearance-none cursor-pointer pr-10">
                                        <option>Pilih kisaran</option>
                                        <option>Tidak berpenghasilan</option>
                                        <option>Di bawah Rp 1.000.000</option>
                                        <option>Rp 1.000.000 – 3.000.000</option>
                                        <option>Rp 3.000.000 – 5.000.000</option>
                                        <option>Di atas Rp 5.000.000</option>
                                    </select>

                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">No. HP Ibu</label>
                                    <div class="flex">
                                        <span class="inline-flex items-center px-4 bg-gray-100 border border-r-0 border-gray-200 rounded-l-xl text-base font-bold text-[#6A7686]">+62</span>
                                        <input type="tel" placeholder="81234567890" class="flex-1 min-w-0 px-4 py-3 rounded-r-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Wali --}}
                        <div class="border-t border-dashed border-gray-200 pt-5 space-y-4">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-bold flex items-center gap-2">
                                    <i class="fa-solid fa-user-shield text-violet-500"></i> Data Wali (Jika Ada)
                                </p>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" x-model="showWali" class="w-4 h-4 accent-primary">
                                    <span class="text-sm text-[#6A7686]">Saya memiliki wali</span>
                                </label>
                            </div>
                            <div x-show="showWali" class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div class="space-y-1.5 sm:col-span-2">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Nama Lengkap Wali <span class="text-primary">*</span></label>
                                    <input type="text" placeholder="Sesuai KTP" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Hubungan dengan Peserta <span class="text-primary">*</span></label>
                                    <select class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] appearance-none cursor-pointer pr-10">
                                        <option>Pilih</option>
                                        <option>Kakek/Nenek</option>
                                        <option>Paman/Bibi</option>
                                        <option>Kakak kandung</option>
                                        <option>Lainnya</option>
                                    </select>

                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">No. HP Wali <span class="text-primary">*</span></label>
                                    <div class="flex">
                                        <span class="inline-flex items-center px-4 bg-gray-100 border border-r-0 border-gray-200 rounded-l-xl text-base font-bold text-[#6A7686]">+62</span>
                                        <input type="tel" placeholder="81234567890" class="flex-1 min-w-0 px-4 py-3 rounded-r-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Pekerjaan Wali</label>
                                    <input type="text" placeholder="Contoh: Pedagang" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                                </div>
                                <div class="space-y-1.5 sm:col-span-2">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Alamat Wali</label>
                                    <input type="text" placeholder="Jika berbeda dengan peserta" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                                </div>
                            </div>
                        </div>

                        {{-- Kontak Darurat --}}
                        <div class="border-t border-dashed border-gray-200 pt-5 space-y-4">
                            <p class="text-sm font-bold flex items-center gap-2 pb-2.5 border-b border-gray-100">
                                <i class="fa-solid fa-phone-volume text-red-400"></i> Kontak Darurat
                            </p>
                            <div class="flex gap-3 items-start bg-amber-50 border border-amber-200 rounded-2xl px-4 py-3.5">
                                <i class="fa-solid fa-triangle-exclamation text-amber-500 text-base mt-0.5 flex-shrink-0"></i>
                                <p class="text-sm font-medium text-amber-800 leading-relaxed">Kontak darurat akan dihubungi jika terjadi kondisi mendesak di sekolah. Pastikan nomor selalu aktif.</p>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Nama Kontak Darurat <span class="text-primary">*</span></label>
                                    <input type="text" placeholder="Nama lengkap" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Hubungan <span class="text-primary">*</span></label>
                                    <select class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] appearance-none cursor-pointer pr-10">
                                        <option>Ayah</option>
                                        <option>Ibu</option>
                                        <option>Kakak</option>
                                        <option>Wali</option>
                                        <option>Lainnya</option>
                                    </select>

                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">No. HP <span class="text-primary">*</span></label>
                                    <div class="flex">
                                        <span class="inline-flex items-center px-4 bg-gray-100 border border-r-0 border-gray-200 rounded-l-xl text-base font-bold text-[#6A7686]">+62</span>
                                        <input type="tel" placeholder="81234567890" class="flex-1 min-w-0 px-4 py-3 rounded-r-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-8 py-5 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between rounded-b-[20px]">
                        <button type="button" @click="step--"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-[#6A7686] border border-gray-200 rounded-full hover:border-[#080C1A] hover:text-[#080C1A] transition-all">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </button>
                        <div class="flex items-center gap-3">
                            <button type="button" onclick="saveDraft()" class="text-sm font-semibold text-[#6A7686] flex items-center gap-1.5 hover:text-primary transition-colors">
                                <i class="fa-regular fa-floppy-disk"></i> Simpan Draft
                            </button>
                            <button type="button" @click="step++"
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-full hover:bg-primary-hover hover:-translate-y-px transition-all shadow-lg shadow-primary/30">
                                Lanjut: Data Pendidikan <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ══════════════════════════════
                    STEP 4 — DATA PENDIDIKAN
            ══════════════════════════════ --}}
                <div x-show="step === 4"
                    class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">
                    <div class="px-8 pt-6 pb-5 border-b border-gray-100 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-book-open-reader text-amber-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-black text-[#080C1A]">Data Pendidikan Sebelumnya</h2>
                            <p class="text-sm text-[#6A7686]">Riwayat sekolah asal dan prestasi akademik</p>
                        </div>
                    </div>
                    <div class="px-8 py-7 space-y-5">
                        <p class="text-sm font-bold flex items-center gap-2 pb-2.5 border-b border-gray-100">
                            <i class="fa-solid fa-school text-primary"></i> Asal Sekolah SMP/MTs
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="space-y-1.5 sm:col-span-2">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Nama Sekolah Asal <span class="text-primary">*</span></label>
                                <input type="text" placeholder="Contoh: SMP Negeri 1 Bengkulu" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">NPSN Sekolah</label>
                                <input type="text" maxlength="8" placeholder="8 digit NPSN" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                                <p class="text-[13px] text-[#6A7686]">Nomor Pokok Sekolah Nasional</p>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Status Sekolah <span class="text-primary">*</span></label>
                                <div class="flex gap-3 flex-wrap pt-1">
                                    <label class="flex items-center gap-2 px-4 py-2.5 border-2 border-gray-200 rounded-full cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary-light has-[:checked]:text-primary text-sm font-semibold text-[#6A7686] transition-all">
                                        <input type="radio" name="statusSekolah" value="negeri" checked class="accent-primary"> <i class="fa-solid fa-landmark text-sm"></i> Negeri
                                    </label>
                                    <label class="flex items-center gap-2 px-4 py-2.5 border-2 border-gray-200 rounded-full cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary-light has-[:checked]:text-primary text-sm font-semibold text-[#6A7686] transition-all">
                                        <input type="radio" name="statusSekolah" value="swasta" class="accent-primary"> <i class="fa-solid fa-building text-sm"></i> Swasta
                                    </label>
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Tahun Lulus <span class="text-primary">*</span></label>
                                <select class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] appearance-none cursor-pointer pr-10">
                                    <option>2026</option>
                                    <option>2025</option>
                                    <option>2024</option>
                                    <option>2023</option>
                                </select>

                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">No. Ijazah / SKL</label>
                                <input type="text" placeholder="Nomor ijazah/SKL" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Kota Sekolah Asal</label>
                                <input type="text" placeholder="Nama kota/kab" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Provinsi</label>
                                <select class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] appearance-none cursor-pointer pr-10">
                                    <option>Bengkulu</option>
                                    <option>Lainnya</option>
                                </select>

                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Jurusan <span class="text-[13px] text-[#6A7686] normal-case font-normal">(jika ada)</span></label>
                                <input type="text" placeholder="Kosongkan jika tidak ada" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                            </div>
                        </div>

                        <div class="border-t border-dashed border-gray-200 pt-5 space-y-5">
                            <p class="text-sm font-bold flex items-center gap-2 pb-2.5 border-b border-gray-100">
                                <i class="fa-solid fa-star text-primary"></i> Nilai & Prestasi
                            </p>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Nilai Rata-rata Rapor <span class="text-primary">*</span></label>
                                    <input type="number" placeholder="0.00" min="0" max="100" step="0.01" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                                    <p class="text-[13px] text-[#6A7686]">Rata-rata semester 1–5</p>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Nilai UN / ASPD</label>
                                    <input type="number" placeholder="0.00" min="0" max="100" step="0.01" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Peringkat di Kelas</label>
                                    <input type="number" placeholder="Contoh: 5" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Prestasi / Penghargaan <span class="text-[13px] normal-case font-normal">(opsional)</span></label>
                                <textarea rows="3" placeholder="Tuliskan prestasi akademik maupun non-akademik yang pernah diraih..."
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal resize-y min-h-[90px]"></textarea>
                            </div>
                        </div>

                        <div class="border-t border-dashed border-gray-200 pt-5 space-y-4">
                            <p class="text-sm font-bold flex items-center gap-2 pb-2.5 border-b border-gray-100">
                                <i class="fa-solid fa-compass-drafting text-primary"></i> Pilihan Jurusan
                            </p>
                            <div class="flex gap-3 items-start bg-red-50 border border-red-200 rounded-2xl px-4 py-3.5">
                                <i class="fa-solid fa-circle-info text-primary text-base mt-0.5 flex-shrink-0"></i>
                                <p class="text-sm font-medium text-red-800 leading-relaxed">Pilih jurusan sesuai minat dan bakat. Penempatan berdasarkan hasil seleksi dan kuota yang tersedia.</p>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Pilihan Jurusan 1 <span class="text-primary">*</span></label>
                                    <select class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] appearance-none cursor-pointer pr-10">
                                        <option value="">Pilih Jurusan</option>
                                        <option>Rekayasa Perangkat Lunak (RPL)</option>
                                        <option>Teknik Komputer & Jaringan (TKJ)</option>
                                        <option>Multimedia / Desain Komunikasi Visual</option>
                                        <option>Akuntansi & Keuangan Lembaga</option>
                                        <option>Teknik Kendaraan Ringan Otomotif (TKRO)</option>
                                    </select>

                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Pilihan Jurusan 2 <span class="text-[13px] text-[#6A7686] normal-case font-normal">(cadangan)</span></label>
                                    <select class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] appearance-none cursor-pointer pr-10">
                                        <option value="">Pilih Jurusan</option>
                                        <option>Rekayasa Perangkat Lunak (RPL)</option>
                                        <option>Teknik Komputer & Jaringan (TKJ)</option>
                                        <option>Multimedia / Desain Komunikasi Visual</option>
                                        <option>Akuntansi & Keuangan Lembaga</option>
                                        <option>Teknik Kendaraan Ringan Otomotif (TKRO)</option>
                                    </select>

                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Alasan Memilih Jurusan</label>
                                <textarea rows="3" placeholder="Ceritakan mengapa Anda memilih jurusan tersebut..."
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal resize-y min-h-[90px]"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="px-8 py-5 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between rounded-b-[20px]">
                        <button type="button" @click="step--"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-[#6A7686] border border-gray-200 rounded-full hover:border-[#080C1A] hover:text-[#080C1A] transition-all">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </button>
                        <div class="flex items-center gap-3">
                            <button type="button" onclick="saveDraft()" class="text-sm font-semibold text-[#6A7686] flex items-center gap-1.5 hover:text-primary transition-colors">
                                <i class="fa-regular fa-floppy-disk"></i> Simpan Draft
                            </button>
                            <button type="button" @click="step++"
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-full hover:bg-primary-hover hover:-translate-y-px transition-all shadow-lg shadow-primary/30">
                                Lanjut: Dokumen <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ══════════════════════════════
                    STEP 5 — DOKUMEN
            ══════════════════════════════ --}}
                <div x-show="step === 5"
                    class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">
                    <div class="px-8 pt-6 pb-5 border-b border-gray-100 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-violet-50 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-folder-open text-violet-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-black text-[#080C1A]">Unggah Dokumen</h2>
                            <p class="text-sm text-[#6A7686]">Format PDF/JPG/PNG, maksimal 2MB per file</p>
                        </div>
                    </div>
                    <div class="px-8 py-7 space-y-6">
                        <div class="flex gap-3 items-start bg-blue-50 border border-blue-200 rounded-2xl px-4 py-3.5">
                            <i class="fa-solid fa-circle-info text-blue-500 text-base mt-0.5 flex-shrink-0"></i>
                            <p class="text-sm font-medium text-blue-800 leading-relaxed">Pastikan dokumen terbaca dengan jelas, tidak buram, dan tidak terpotong. Dokumen yang tidak valid akan ditolak saat verifikasi.</p>
                        </div>

                        {{-- Upload: Akta --}}
                        <div class="space-y-2">
                            <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Akta Kelahiran / Kartu Keluarga <span class="text-primary">*</span></label>
                            <div x-show="!files.akta"
                                @click="$refs.uploadAkta.click()"
                                class="border-2 border-dashed border-gray-200 rounded-2xl p-7 text-center cursor-pointer hover:border-primary hover:bg-primary-light transition-all group">
                                <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-300 group-hover:text-primary mb-3 block transition-colors"></i>
                                <p class="text-sm font-bold text-[#080C1A]">Klik atau seret file ke sini</p>
                                <p class="text-[13px] text-[#6A7686] mt-1">PDF, JPG, PNG — Maks. 2MB</p>
                            </div>
                            <input type="file" x-ref="uploadAkta" accept=".pdf,.jpg,.jpeg,.png" class="hidden"
                                @change="files.akta = $event.target.files[0]?.name">
                            <div x-show="files.akta" class="flex items-center gap-3 px-4 py-3 bg-green-50 border border-green-200 rounded-xl">
                                <i class="fa-solid fa-file-check text-green-500"></i>
                                <span class="text-sm font-semibold text-green-700 flex-1 truncate" x-text="files.akta"></span>
                                <button type="button" @click="files.akta = null" class="text-red-400 hover:text-red-600 text-base"><i class="fa-solid fa-times"></i></button>
                            </div>
                        </div>

                        {{-- Upload: Ijazah --}}
                        <div class="space-y-2">
                            <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Ijazah atau SKL SMP/MTs <span class="text-primary">*</span></label>
                            <div x-show="!files.ijazah"
                                @click="$refs.uploadIjazah.click()"
                                class="border-2 border-dashed border-gray-200 rounded-2xl p-7 text-center cursor-pointer hover:border-primary hover:bg-primary-light transition-all group">
                                <i class="fa-solid fa-certificate text-3xl text-gray-300 group-hover:text-primary mb-3 block transition-colors"></i>
                                <p class="text-sm font-bold text-[#080C1A]">Klik atau seret file ke sini</p>
                                <p class="text-[13px] text-[#6A7686] mt-1">PDF, JPG, PNG — Maks. 2MB</p>
                            </div>
                            <input type="file" x-ref="uploadIjazah" accept=".pdf,.jpg,.jpeg,.png" class="hidden"
                                @change="files.ijazah = $event.target.files[0]?.name">
                            <div x-show="files.ijazah" class="flex items-center gap-3 px-4 py-3 bg-green-50 border border-green-200 rounded-xl">
                                <i class="fa-solid fa-file-check text-green-500"></i>
                                <span class="text-sm font-semibold text-green-700 flex-1 truncate" x-text="files.ijazah"></span>
                                <button type="button" @click="files.ijazah = null" class="text-red-400 hover:text-red-600 text-base"><i class="fa-solid fa-times"></i></button>
                            </div>
                        </div>

                        {{-- Upload: Rapor --}}
                        <div class="space-y-2">
                            <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Rapor Semester 1–5 <span class="text-primary">*</span></label>
                            <div x-show="!files.rapor"
                                @click="$refs.uploadRapor.click()"
                                class="border-2 border-dashed border-gray-200 rounded-2xl p-7 text-center cursor-pointer hover:border-primary hover:bg-primary-light transition-all group">
                                <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-300 group-hover:text-primary mb-3 block transition-colors"></i>
                                <p class="text-sm font-bold text-[#080C1A]">Klik atau seret file ke sini</p>
                                <p class="text-[13px] text-[#6A7686] mt-1">PDF — Maks. 2MB</p>
                            </div>
                            <input type="file" x-ref="uploadRapor" accept=".pdf" class="hidden"
                                @change="files.rapor = $event.target.files[0]?.name">
                            <div x-show="files.rapor" class="flex items-center gap-3 px-4 py-3 bg-green-50 border border-green-200 rounded-xl">
                                <i class="fa-solid fa-file-check text-green-500"></i>
                                <span class="text-sm font-semibold text-green-700 flex-1 truncate" x-text="files.rapor"></span>
                                <button type="button" @click="files.rapor = null" class="text-red-400 hover:text-red-600 text-base"><i class="fa-solid fa-times"></i></button>
                            </div>
                        </div>

                        {{-- Upload: Foto --}}
                        <div class="space-y-2">
                            <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Pas Foto Terbaru (3×4 atau 4×6) <span class="text-primary">*</span></label>
                            <div x-show="!files.foto"
                                @click="$refs.uploadFoto.click()"
                                class="border-2 border-dashed border-gray-200 rounded-2xl p-7 text-center cursor-pointer hover:border-primary hover:bg-primary-light transition-all group">
                                <i class="fa-solid fa-camera text-3xl text-gray-300 group-hover:text-primary mb-3 block transition-colors"></i>
                                <p class="text-sm font-bold text-[#080C1A]">Foto formal, latar merah atau biru</p>
                                <p class="text-[13px] text-[#6A7686] mt-1">JPG, PNG — Maks. 1MB</p>
                            </div>
                            <input type="file" x-ref="uploadFoto" accept=".jpg,.jpeg,.png" class="hidden"
                                @change="files.foto = $event.target.files[0]?.name">
                            <div x-show="files.foto" class="flex items-center gap-3 px-4 py-3 bg-green-50 border border-green-200 rounded-xl">
                                <i class="fa-solid fa-file-check text-green-500"></i>
                                <span class="text-sm font-semibold text-green-700 flex-1 truncate" x-text="files.foto"></span>
                                <button type="button" @click="files.foto = null" class="text-red-400 hover:text-red-600 text-base"><i class="fa-solid fa-times"></i></button>
                            </div>
                        </div>

                        {{-- Upload: Sertifikat --}}
                        <div class="space-y-2">
                            <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Sertifikat Prestasi <span class="text-[13px] text-[#6A7686] normal-case font-normal">(opsional, jika ada)</span></label>
                            <div x-show="!files.sertif"
                                @click="$refs.uploadSertif.click()"
                                class="border-2 border-dashed border-gray-200 rounded-2xl p-7 text-center cursor-pointer hover:border-primary hover:bg-primary-light transition-all group">
                                <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-300 group-hover:text-primary mb-3 block transition-colors"></i>
                                <p class="text-sm font-bold text-[#080C1A]">Olimpiade, lomba, atau penghargaan</p>
                                <p class="text-[13px] text-[#6A7686] mt-1">PDF, JPG, PNG — Maks. 2MB</p>
                            </div>
                            <input type="file" x-ref="uploadSertif" accept=".pdf,.jpg,.jpeg,.png" class="hidden"
                                @change="files.sertif = $event.target.files[0]?.name">
                            <div x-show="files.sertif" class="flex items-center gap-3 px-4 py-3 bg-green-50 border border-green-200 rounded-xl">
                                <i class="fa-solid fa-file-check text-green-500"></i>
                                <span class="text-sm font-semibold text-green-700 flex-1 truncate" x-text="files.sertif"></span>
                                <button type="button" @click="files.sertif = null" class="text-red-400 hover:text-red-600 text-base"><i class="fa-solid fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="px-8 py-5 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between rounded-b-[20px]">
                        <button type="button" @click="step--"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-[#6A7686] border border-gray-200 rounded-full hover:border-[#080C1A] hover:text-[#080C1A] transition-all">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </button>
                        <div class="flex items-center gap-3">
                            <button type="button" onclick="saveDraft()" class="text-sm font-semibold text-[#6A7686] flex items-center gap-1.5 hover:text-primary transition-colors">
                                <i class="fa-regular fa-floppy-disk"></i> Simpan Draft
                            </button>
                            <button type="button" @click="step++"
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-full hover:bg-primary-hover hover:-translate-y-px transition-all shadow-lg shadow-primary/30">
                                Lanjut: Konfirmasi <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ══════════════════════════════
                    STEP 6 — KONFIRMASI
            ══════════════════════════════ --}}
                <div x-show="step === 6"
                    class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">
                    <div class="px-8 pt-6 pb-5 border-b border-gray-100 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-clipboard-check text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-black text-[#080C1A]">Konfirmasi & Kirim</h2>
                            <p class="text-sm text-[#6A7686]">Tinjau kembali data sebelum dikirimkan</p>
                        </div>
                    </div>
                    <div class="px-8 py-7 space-y-5">
                        <div class="flex gap-3 items-start bg-amber-50 border border-amber-200 rounded-2xl px-4 py-3.5">
                            <i class="fa-solid fa-triangle-exclamation text-amber-500 text-base mt-0.5 flex-shrink-0"></i>
                            <p class="text-sm font-medium text-amber-800 leading-relaxed">Setelah data dikirim, perubahan hanya dapat dilakukan melalui panitia SPMB. Pastikan semua data sudah benar.</p>
                        </div>

                        {{-- Summary cards --}}
                        <div class="space-y-3">
                            <div class="border border-gray-200 rounded-2xl overflow-hidden">
                                <div class="bg-gray-50 px-5 py-3 flex justify-between items-center border-b border-gray-200">
                                    <span class="text-sm font-bold"><i class="fa-solid fa-user text-primary mr-2"></i>Data Pribadi</span>
                                    <span class="text-[13px] text-green-600 font-bold flex items-center gap-1"><i class="fa-solid fa-circle-check"></i> Lengkap</span>
                                </div>
                                <div class="px-5 py-4 grid grid-cols-2 gap-3">
                                    <div class="text-sm"><span class="text-[#6A7686] font-semibold">Nama Lengkap</span><br><strong>Ahmad Fauzi</strong></div>
                                    <div class="text-sm"><span class="text-[#6A7686] font-semibold">NISN</span><br><strong>0074821639</strong></div>
                                    <div class="text-sm"><span class="text-[#6A7686] font-semibold">TTL</span><br><strong>Bengkulu, 14 Maret 2010</strong></div>
                                    <div class="text-sm"><span class="text-[#6A7686] font-semibold">Jenis Kelamin</span><br><strong>Laki-laki</strong></div>
                                </div>
                            </div>
                            <div class="border border-gray-200 rounded-2xl overflow-hidden">
                                <div class="bg-gray-50 px-5 py-3 flex justify-between items-center border-b border-gray-200">
                                    <span class="text-sm font-bold"><i class="fa-solid fa-location-dot text-blue-500 mr-2"></i>Alamat</span>
                                    <span class="text-[13px] text-green-600 font-bold flex items-center gap-1"><i class="fa-solid fa-circle-check"></i> Lengkap</span>
                                </div>
                                <div class="px-5 py-4 text-sm">
                                    <span class="text-[#6A7686] font-semibold">Alamat Domisili</span><br>
                                    <strong>Jl. Merapi No. 8, RT 002/RW 004, Kel. Panorama, Kec. Singaran Pati, Kota Bengkulu 38225</strong>
                                </div>
                            </div>
                            <div class="border border-gray-200 rounded-2xl overflow-hidden">
                                <div class="bg-gray-50 px-5 py-3 flex justify-between items-center border-b border-gray-200">
                                    <span class="text-sm font-bold"><i class="fa-solid fa-people-roof text-green-600 mr-2"></i>Orang Tua</span>
                                    <span class="text-[13px] text-green-600 font-bold flex items-center gap-1"><i class="fa-solid fa-circle-check"></i> Lengkap</span>
                                </div>
                                <div class="px-5 py-4 grid grid-cols-2 gap-3">
                                    <div class="text-sm"><span class="text-[#6A7686] font-semibold">Nama Ayah</span><br><strong>Fauzi Rahmat, S.T.</strong></div>
                                    <div class="text-sm"><span class="text-[#6A7686] font-semibold">Nama Ibu</span><br><strong>Dewi Sartika</strong></div>
                                </div>
                            </div>
                            <div class="border border-gray-200 rounded-2xl overflow-hidden">
                                <div class="bg-gray-50 px-5 py-3 flex justify-between items-center border-b border-gray-200">
                                    <span class="text-sm font-bold"><i class="fa-solid fa-book-open-reader text-amber-600 mr-2"></i>Pendidikan & Jurusan</span>
                                    <span class="text-[13px] text-green-600 font-bold flex items-center gap-1"><i class="fa-solid fa-circle-check"></i> Lengkap</span>
                                </div>
                                <div class="px-5 py-4 grid grid-cols-2 gap-3">
                                    <div class="text-sm"><span class="text-[#6A7686] font-semibold">Asal Sekolah</span><br><strong>SMP Negeri 1 Bengkulu</strong></div>
                                    <div class="text-sm"><span class="text-[#6A7686] font-semibold">Pilihan Jurusan 1</span><br><strong>Rekayasa Perangkat Lunak</strong></div>
                                </div>
                            </div>
                            <div class="border border-gray-200 rounded-2xl overflow-hidden">
                                <div class="bg-gray-50 px-5 py-3 flex justify-between items-center border-b border-gray-200">
                                    <span class="text-sm font-bold"><i class="fa-solid fa-folder-open text-violet-600 mr-2"></i>Dokumen</span>
                                    <span class="text-[13px] text-amber-600 font-bold flex items-center gap-1 bg-amber-50 px-2.5 py-0.5 rounded-full border border-amber-200"><i class="fa-solid fa-clock"></i> 3/4 Terunggah</span>
                                </div>
                                <div class="px-5 py-4 flex flex-wrap gap-2">
                                    <span class="text-[13px] px-3 py-1 rounded-full bg-green-100 text-green-700 font-semibold border border-green-200"><i class="fa-solid fa-check mr-1"></i>Akta Kelahiran</span>
                                    <span class="text-[13px] px-3 py-1 rounded-full bg-green-100 text-green-700 font-semibold border border-green-200"><i class="fa-solid fa-check mr-1"></i>Ijazah/SKL</span>
                                    <span class="text-[13px] px-3 py-1 rounded-full bg-green-100 text-green-700 font-semibold border border-green-200"><i class="fa-solid fa-check mr-1"></i>Rapor</span>
                                    <span class="text-[13px] px-3 py-1 rounded-full bg-red-100 text-red-700 font-semibold border border-red-200"><i class="fa-solid fa-xmark mr-1"></i>Pas Foto</span>
                                </div>
                            </div>
                        </div>

                        {{-- Pernyataan --}}
                        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5 space-y-3">
                            <p class="text-sm font-bold text-[#080C1A]">Pernyataan Peserta</p>
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" required class="mt-1 w-4 h-4 accent-primary">
                                <span class="text-sm text-[#6A7686] leading-relaxed">Saya menyatakan bahwa semua data dan dokumen yang saya isi adalah <strong>benar dan sesuai aslinya</strong>.</span>
                            </label>
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" required class="mt-1 w-4 h-4 accent-primary">
                                <span class="text-sm text-[#6A7686] leading-relaxed">Saya menyetujui <strong>Syarat & Ketentuan</strong> serta <strong>Kebijakan Privasi</strong> SPMB SMK Negeri 1.</span>
                            </label>
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" required class="mt-1 w-4 h-4 accent-primary">
                                <span class="text-sm text-[#6A7686] leading-relaxed">Saya bersedia menerima sanksi jika dikemudian hari ditemukan <strong>pemalsuan data</strong>.</span>
                            </label>
                        </div>
                    </div>
                    <div class="px-8 py-5 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between rounded-b-[20px]">
                        <button type="button" @click="step--"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-[#6A7686] border border-gray-200 rounded-full hover:border-[#080C1A] hover:text-[#080C1A] transition-all">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </button>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-8 py-2.5 bg-green-600 text-white text-sm font-black rounded-full hover:bg-green-700 hover:-translate-y-px transition-all shadow-lg shadow-green-500/30">
                            <i class="fa-solid fa-paper-plane"></i> Kirim Biodata Sekarang
                        </button>
                    </div>
                </div>

            </form>
        </div>{{-- /main col --}}

        {{-- ── SIDEBAR ── --}}
        <div class="hidden lg:block">
            <div class="sticky top-[80px] flex flex-col gap-4">

                {{-- Kelengkapan Biodata --}}
                <div class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">
                    <div class="px-5 py-4" style="background: linear-gradient(135deg,#FF1443,#D90F38);">
                        <h3 class="text-base font-black text-white mb-0.5">Kelengkapan Biodata</h3>
                        <p class="text-[13px] text-white/80">Update otomatis saat berpindah step</p>
                    </div>
                    <div class="px-5 py-3 divide-y divide-gray-100">
                        <template x-for="(label, idx) in [
                            {name: 'Data Pribadi', icon: 'fa-user', color: 'text-primary'},
                            {name: 'Alamat', icon: 'fa-location-dot', color: 'text-blue-500'},
                            {name: 'Orang Tua', icon: 'fa-people-roof', color: 'text-green-600'},
                            {name: 'Pendidikan', icon: 'fa-book', color: 'text-amber-600'},
                            {name: 'Dokumen', icon: 'fa-folder', color: 'text-violet-600'},
                            {name: 'Konfirmasi', icon: 'fa-clipboard-check', color: 'text-green-600'}
                        ]" :key="idx">
                            <div class="flex justify-between items-center py-2.5">
                                <span class="text-sm font-semibold text-[#6A7686]">
                                    <i :class="'fa-solid ' + label.icon + ' ' + label.color + ' mr-1.5'"></i>
                                    <span x-text="label.name"></span>
                                </span>
                                <span class="text-[13px] font-bold"
                                    :class="{
                                            'text-green-600 flex items-center gap-1': step > idx+1,
                                            'text-primary': step === idx+1,
                                            'text-gray-400 italic': step < idx+1
                                        }">
                                    <template x-if="step > idx+1">
                                        <span><i class="fa-solid fa-check"></i> Selesai</span>
                                    </template>
                                    <template x-if="step === idx+1">
                                        <span>● Sedang diisi</span>
                                    </template>
                                    <template x-if="step < idx+1">
                                        <span>Belum diisi</span>
                                    </template>
                                </span>
                            </div>
                        </template>
                    </div>
                    <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">
                        <div class="flex justify-between text-sm font-semibold text-[#6A7686] mb-2">
                            <span>Progress Total</span>
                            <span class="text-primary font-bold" x-text="progressPct + '%'"></span>
                        </div>
                        <div class="h-2.5 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500"
                                style="background: linear-gradient(90deg,#FF1443,#FF6B8A)"
                                :style="'width:' + progressPct + '%'"></div>
                        </div>
                    </div>
                </div>

                {{-- Butuh Bantuan --}}
                <div class="bg-white border border-gray-200 rounded-[20px] overflow-hidden shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
                    <div class="px-[18px] py-[14px]">
                        <p class="text-[14px] font-bold mb-2 flex items-center gap-[6px]">
                            <i class="fa-solid fa-circle-question text-[14px] text-[#FF1443]"></i>Butuh Bantuan?
                        </p>
                        <p class="text-[13px] text-[#6A7686] leading-relaxed mb-3">
                            Panitia SPMB siap membantu selama jam kerja <strong class="text-[#080C1A]">08:00–16:00 WIB</strong>.
                        </p>
                        <a href="https://wa.me/6281234567890" target="_blank"
                            class="inline-flex w-full items-center justify-center gap-[7px] px-4 py-[9px] rounded-full font-sans text-[14px] font-bold no-underline bg-white border-[1.5px] border-[#25D366] text-[#25D366] hover:-translate-y-px transition-all">
                            <i class="fa-brands fa-whatsapp text-[15px]"></i> Chat WhatsApp Panitia
                        </a>
                    </div>
                </div>

            </div>
        </div>{{-- /sidebar --}}

    </div>{{-- /two-col grid --}}

    {{-- ══════════════════════════════════════════
            SUCCESS SCREEN
    ══════════════════════════════════════════ --}}
    <div x-show="isSubmitted" x-transition
        class="bg-white border border-gray-200 rounded-[20px] shadow-sm px-8 py-16 text-center">
        <div class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-circle-check text-green-500 text-4xl"></i>
        </div>
        <h2 class="text-2xl font-black text-[#080C1A] mb-2">Biodata Berhasil Dikirim! 🎉</h2>
        <p class="text-base text-[#6A7686] max-w-md mx-auto leading-relaxed mb-8">
            Data kamu telah diterima sistem dan sedang dalam proses verifikasi oleh panitia SPMB. Pantau status melalui dashboard peserta.
        </p>
        <div class="inline-flex flex-col gap-2 bg-gray-50 border border-gray-200 rounded-2xl px-8 py-5 mb-8 text-left">
            <div class="flex gap-4 text-sm"><span class="text-[#6A7686] font-semibold min-w-[130px]">No. Peserta</span><span class="font-bold">SPMB-2026-004821</span></div>
            <div class="flex gap-4 text-sm"><span class="text-[#6A7686] font-semibold min-w-[130px]">Nama</span><span class="font-bold">Ahmad Fauzi</span></div>
            <div class="flex gap-4 text-sm"><span class="text-[#6A7686] font-semibold min-w-[130px]">Pilihan Jurusan</span><span class="font-bold">Rekayasa Perangkat Lunak</span></div>
            <div class="flex gap-4 text-sm"><span class="text-[#6A7686] font-semibold min-w-[130px]">Status</span><span class="font-bold text-amber-500"><i class="fa-solid fa-clock"></i> Menunggu Verifikasi</span></div>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('dashboard') }}"
                class="px-8 py-3 bg-gray-100 text-[#080C1A] rounded-full text-base font-bold hover:bg-gray-200 transition-all">
                <i class="fa-solid fa-gauge mr-2"></i> Kembali ke Dashboard
            </a>
            <button @click="window.print()"
                class="px-8 py-3 bg-primary text-white rounded-full text-base font-bold hover:bg-primary-hover transition-all">
                <i class="fa-solid fa-print mr-2"></i> Cetak Bukti Pendaftaran
            </button>
        </div>
    </div>

</div>{{-- /x-data --}}

{{-- ══════════════════════════════════════════
        JAVASCRIPT
══════════════════════════════════════════ --}}
@push('scripts')
<script>
    function saveDraft() {
        const t = document.createElement('div');
        t.className = 'fixed bottom-6 left-1/2 -translate-x-1/2 bg-[#080C1A] text-white px-[22px] py-[10px] rounded-full text-[13px] font-bold flex items-center gap-2 shadow-[0_4px_20px_rgba(0,0,0,0.18)] z-[9999] whitespace-nowrap';
        t.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg> Draft disimpan';
        document.body.appendChild(t);
        setTimeout(() => t.remove(), 2500);
    }
</script>
@endpush

@endsection
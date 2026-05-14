@extends('layouts.user')

@section('title', 'Data Pribadi')

@section('content')

<div x-data="{
    step: 1,
    totalSteps: 6,
    isSubmitted: false,
    showWali: false,
    sameAddress: false,
    files: { foto: null },
    get progressPct() {
        return Math.round((this.step / this.totalSteps) * 100);
    },
    stepLabels: ['Data Pribadi','Alamat','Orang Tua','Pendidikan','Pas Foto','Konfirmasi'],
    stepIcons: ['fa-user','fa-location-dot','fa-people-roof','fa-book-open-reader','fa-camera','fa-clipboard-check'],
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

                <!-- STEP 1 — DATA PRIBADI -->

                <div x-show="step === 1" class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">
                    {{-- Header Utama (TETAP MERAH) --}}
                    <div class="px-8 pt-6 border-gray-100 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-primary-light flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-user text-primary text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-black text-[#080C1A]">Data Pribadi</h2>
                            <p class="text-sm text-[#6A7686]">Informasi dasar calon peserta didik sesuai dokumen resmi</p>
                        </div>
                    </div>

                    <div class="px-8 py-7 space-y-6">

                        {{-- Info box (TETAP SAMA) --}}
                        <div class="flex gap-3 items-start bg-blue-50 border border-blue-200 rounded-2xl px-4 py-3.5">
                            <i class="fa-solid fa-circle-info text-blue-500 text-base mt-0.5 flex-shrink-0"></i>
                            <p class="text-sm font-medium text-blue-800 leading-relaxed">Isi data sesuai dengan <strong>Kartu Keluarga (KK)</strong> atau <strong>Akta Kelahiran</strong>. Pastikan tidak ada kesalahan penulisan nama.</p>
                        </div>

                        <!-- GROUP 1 — IDENTITAS DIRI -->

                        <div class="relative rounded-2xl border-2 border-dashed transition-all duration-300 border-gray-300 bg-gray-50/30 shadow-sm">

                            <div class="px-6 py-4 border-b border-dashed border-gray-200 bg-gray-100/50">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-gray-500 flex items-center justify-center shadow-sm">
                                        <i class="fa-solid fa-id-card text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-black text-gray-700">Identitas Diri</h3>
                                        <p class="text-[11px] text-gray-500">Data sesuai dokumen resmi</p>
                                    </div>
                                    <div class="ml-auto">
                                        <span class="text-[10px] font-bold text-white bg-gray-500 px-2.5 py-1 rounded-full">
                                            <i class="fa-regular fa-circle-check mr-1"></i> Wajib
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="px-6 py-5 space-y-4">
                                {{-- NIK & NISN (1 baris di layar besar) --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">NIK <span class="text-primary">*</span></label>
                                        <input type="text" maxlength="16" placeholder="16 digit NIK" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px] font-medium text-[#080C1A]">
                                        <p class="text-[10px] text-gray-400 mt-1">16 digit angka (sesuai KTP/KK)</p>
                                    </div>
                                    <div>
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">NISN <span class="text-primary">*</span></label>
                                        <input type="text" maxlength="10" placeholder="Nomor Induk Siswa Nasional" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px]">
                                        <p class="text-[10px] text-gray-400 mt-1">10 digit angka</p>
                                    </div>
                                </div>

                                {{-- Nama Lengkap & Nama Panggilan (1 baris di layar besar) --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Nama Lengkap <span class="text-primary">*</span></label>
                                        <input type="text" placeholder="Sesuai akta kelahiran, tanpa singkatan" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px]">
                                    </div>
                                    <div>
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Nama Panggilan</label>
                                        <input type="text" placeholder="Nama sehari-hari" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px]">
                                    </div>
                                </div>

                                {{-- Tempat Lahir & Tanggal Lahir (1 baris di layar besar) --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Tempat Lahir <span class="text-primary">*</span></label>
                                        <input type="text" placeholder="Contoh: Bengkulu, Jakarta, dll" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px]">
                                    </div>
                                    <div>
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Tanggal Lahir <span class="text-primary">*</span></label>
                                        <input type="date" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px]">
                                    </div>
                                </div>

                                {{-- Jenis Kelamin, Agama, Golongan Darah (3 kolom di layar besar) --}}
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Jenis Kelamin <span class="text-primary">*</span></label>
                                        <select class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                                            <option value="">Pilih</option>
                                            <option>Laki-laki</option>
                                            <option>Perempuan</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Agama <span class="text-primary">*</span></label>
                                        <select class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                                            <option value="">Pilih Agama</option>
                                            <option>Islam</option>
                                            <option>Kristen</option>
                                            <option>Katolik</option>
                                            <option>Hindu</option>
                                            <option>Buddha</option>
                                            <option>Konghucu</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Golongan Darah</label>
                                        <select class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                                            <option value="">Tidak tahu</option>
                                            <option>A</option>
                                            <option>B</option>
                                            <option>AB</option>
                                            <option>O</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- GROUP 2 — DATA KELUARGA -->

                        <div class="relative rounded-2xl border-2 border-dashed transition-all duration-300 border-gray-300 bg-gray-50/30 shadow-sm">

                            <div class="px-6 py-4 border-b border-dashed border-gray-200 bg-gray-100/50">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-gray-500 flex items-center justify-center shadow-sm">
                                        <i class="fa-solid fa-users text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-black text-gray-700">Data Keluarga</h3>
                                        <p class="text-[11px] text-gray-500">Urutan anak dan jumlah saudara</p>
                                    </div>
                                    <div class="ml-auto">
                                        <span class="text-[10px] font-bold text-white bg-gray-500 px-2.5 py-1 rounded-full">
                                            <i class="fa-regular fa-circle-check mr-1"></i> Wajib
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="px-6 py-5">
                                {{-- Anak ke- & Jumlah Saudara Kandung (1 baris di layar besar) --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Anak ke- <span class="text-primary">*</span></label>
                                        <input type="number" name="child_order" min="1" max="20" placeholder="Contoh: 2" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px]">
                                    </div>
                                    <div>
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Jumlah Saudara Kandung <span class="text-primary">*</span></label>
                                        <input type="number" name="number_of_siblings" min="0" max="20" placeholder="Contoh: 3" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px]">
                                        <p class="text-[10px] text-gray-400 mt-1">Tidak termasuk diri sendiri</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- GROUP 3 — KONDISI KHUSUS -->

                        <div class="relative rounded-2xl border-2 border-dashed transition-all duration-300 border-gray-300 bg-gray-50/30 shadow-sm" x-data="{ hasSpecial: 'no' }">

                            <div class="px-6 py-4 border-b border-dashed border-gray-200 bg-gray-100/50">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-gray-500 flex items-center justify-center shadow-sm">
                                        <i class="fa-solid fa-heart-pulse text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-black text-gray-700">Kondisi Khusus</h3>
                                        <p class="text-[11px] text-gray-500">Informasi kebutuhan khusus (jika ada)</p>
                                    </div>
                                    <div class="ml-auto">
                                        <span class="text-[10px] font-bold text-gray-500 bg-gray-200 px-2.5 py-1 rounded-full">
                                            <i class="fa-regular fa-circle mr-1"></i> Opsional
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="px-6 py-5 space-y-4">
                                {{-- Radio button kondisi --}}
                                <div>
                                    <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">
                                        Apakah memiliki kondisi/kebutuhan khusus?
                                    </label>
                                    <div class="flex gap-3 flex-wrap pt-1">
                                        <label class="flex items-center gap-2 px-4 py-2 border-2 border-gray-200 rounded-full cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary/5 has-[:checked]:text-primary text-sm font-semibold text-[#6A7686] transition-all bg-white">
                                            <input type="radio" name="is_special_condition" value="no" x-model="hasSpecial" checked class="accent-primary w-3.5 h-3.5">
                                            <i class="fa-solid fa-check-circle text-sm"></i>
                                            Tidak Ada
                                        </label>
                                        <label class="flex items-center gap-2 px-4 py-2 border-2 border-gray-200 rounded-full cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary/5 has-[:checked]:text-primary text-sm font-semibold text-[#6A7686] transition-all bg-white">
                                            <input type="radio" name="is_special_condition" value="yes" x-model="hasSpecial" class="accent-primary w-3.5 h-3.5">
                                            <i class="fa-solid fa-circle-exclamation text-sm"></i>
                                            Ada
                                        </label>
                                    </div>
                                </div>

                                {{-- Jenis Kondisi (muncul jika Ada) --}}
                                <div x-show="hasSpecial === 'yes'" x-transition.duration.200ms>
                                    <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Jenis Kondisi</label>
                                    <select name="special_condition_type" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                                        <option value="">Pilih jenis kondisi</option>
                                        <option value="tunanetra">Tunanetra</option>
                                        <option value="tunarungu">Tunarungu</option>
                                        <option value="tunawicara">Tunawicara</option>
                                        <option value="tunadaksa">Tunadaksa</option>
                                        <option value="tunagrahita">Tunagrahita</option>
                                        <option value="autisme">Autisme</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                </div>

                                {{-- Keterangan Tambahan (muncul jika Ada) --}}
                                <div x-show="hasSpecial === 'yes'" x-transition.duration.200ms>
                                    <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Keterangan Tambahan</label>
                                    <textarea name="condition_description" rows="2" placeholder="Jelaskan kondisi lebih detail jika diperlukan..." class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px] resize-none"></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Info tambahan --}}
                        <div class="flex gap-3 items-start bg-blue-50 border border-blue-100 rounded-xl px-4 py-3">
                            <i class="fa-solid fa-circle-info text-blue-500 text-sm mt-0.5 flex-shrink-0"></i>
                            <p class="text-xs text-blue-700 leading-relaxed">
                                <strong class="font-black">Catatan:</strong> Data pribadi akan digunakan untuk administrasi sekolah dan dokumen resmi lainnya. Pastikan semua data diisi dengan benar.
                            </p>
                        </div>

                    </div>

                    {{-- Footer navigasi --}}
                    <div class="px-8 py-5 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end rounded-b-[20px]">
                        <button type="button" @click="step++" class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-lg hover:bg-primary-hover transition-all">
                            Berikutnya <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- STEP 2 — ALAMAT -->

                <div x-show="step === 2" class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">
                    <div class="px-8 pt-6 border-gray-100 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-location-dot text-primary text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-black text-[#080C1A]">Data Alamat</h2>
                            <p class="text-sm text-[#6A7686]">Alamat tempat tinggal saat ini dan asal daerah</p>
                        </div>
                    </div>

                    <div class="px-8 py-7 space-y-6">

                        <!-- CARD ALAMAT DOMISILI -->
                        <div class="relative rounded-2xl border-2 border-dashed transition-all duration-300 border-gray-300 bg-gray-50/30 shadow-sm">
                            <div class="px-6 py-4 border-b border-dashed border-gray-200 bg-gray-100/50">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-gray-500 flex items-center justify-center shadow-sm">
                                        <i class="fa-solid fa-house text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-black text-gray-700">Alamat Domisili</h3>
                                        <p class="text-[11px] text-gray-500">Alamat tempat tinggal saat ini</p>
                                    </div>
                                    <div class="ml-auto">
                                        <span class="text-[10px] font-bold text-white bg-gray-500 px-2.5 py-1 rounded-full">
                                            <i class="fa-regular fa-circle-check mr-1"></i> Wajib
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="px-6 py-5 space-y-4">
                                {{-- Alamat Jalan --}}
                                <div>
                                    <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Alamat Jalan <span class="text-primary">*</span></label>
                                    <input type="text" placeholder="Nama jalan dan nomor rumah" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px] font-medium text-[#080C1A]">
                                </div>

                                {{-- RT + RW + Desa/Kelurahan (1 baris di mobile, 3-3-6 di desktop) --}}
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                    <div class="md:col-span-3">
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">RT</label>
                                        <input type="text" placeholder="001" maxlength="3" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px]">
                                    </div>
                                    <div class="md:col-span-3">
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">RW</label>
                                        <input type="text" placeholder="002" maxlength="3" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px]">
                                    </div>
                                    <div class="md:col-span-6">
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Desa / Kelurahan <span class="text-primary">*</span></label>
                                        <input type="text" placeholder="Nama desa/kelurahan" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px]">
                                    </div>
                                </div>

                                {{-- Kecamatan + Kabupaten (1 baris di mobile, 6-6 di desktop) --}}
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                    <div class="md:col-span-6">
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Kecamatan <span class="text-primary">*</span></label>
                                        <input type="text" placeholder="Nama kecamatan" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px]">
                                    </div>
                                    <div class="md:col-span-6">
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Kabupaten / Kota <span class="text-primary">*</span></label>
                                        <input type="text" placeholder="Nama kabupaten/kota" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px]">
                                    </div>
                                </div>

                                {{-- Provinsi + Kode Pos (1 baris di mobile, 6-6 di desktop) --}}
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                    <div class="md:col-span-6">
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Provinsi <span class="text-primary">*</span></label>
                                        <input type="text" placeholder="Contoh: Jawa Barat, DKI Jakarta, dll" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px]">
                                    </div>
                                    <div class="md:col-span-6">
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Kode Pos <span class="text-primary">*</span></label>
                                        <input type="text" placeholder="3XXXX" maxlength="5" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px]">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CARD KONTAK -->
                        <div class="relative rounded-2xl border-2 border-dashed transition-all duration-300 border-gray-300 bg-gray-50/30 shadow-sm">
                            <div class="px-6 py-4 border-b border-dashed border-gray-200 bg-gray-100/50">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-gray-500 flex items-center justify-center shadow-sm">
                                        <i class="fa-solid fa-phone text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-black text-gray-700">Kontak</h3>
                                        <p class="text-[11px] text-gray-500">Nomor yang dapat dihubungi</p>
                                    </div>
                                    <div class="ml-auto">
                                        <span class="text-[10px] font-bold text-white bg-gray-500 px-2.5 py-1 rounded-full">
                                            <i class="fa-regular fa-circle-check mr-1"></i> Wajib
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="px-6 py-5 space-y-4">
                                {{-- No HP + Email (1 baris di mobile, 6-6 di desktop) --}}
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                    <div class="md:col-span-6">
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">No. HP / WhatsApp <span class="text-primary">*</span></label>
                                        <div class="flex">
                                            <span class="inline-flex items-center px-3 bg-gray-100 border border-r-0 border-gray-200 rounded-l-xl text-sm font-bold text-[#6A7686]">+62</span>
                                            <input type="tel" placeholder="81234567890" class="flex-1 min-w-0 px-4 py-2.5 rounded-r-xl border border-gray-200 bg-white focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px]">
                                        </div>
                                    </div>
                                    <div class="md:col-span-6">
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Email Aktif <span class="text-primary">*</span></label>
                                        <div class="relative flex items-center">
                                            <i class="fa-solid fa-envelope absolute left-3 text-[#6A7686] text-sm pointer-events-none"></i>
                                            <input type="email" placeholder="nama@email.com" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px]">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CARD JARAK & TRANSPORTASI -->
                        <div class="relative rounded-2xl border-2 border-dashed transition-all duration-300 border-gray-300 bg-gray-50/30 shadow-sm">
                            <div class="px-6 py-4 border-b border-dashed border-gray-200 bg-gray-100/50">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-gray-500 flex items-center justify-center shadow-sm">
                                        <i class="fa-solid fa-location-crosshairs text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-black text-gray-700">Jarak & Transportasi ke Sekolah</h3>
                                        <p class="text-[11px] text-gray-500">Informasi akses menuju sekolah</p>
                                    </div>
                                    <div class="ml-auto">
                                        <span class="text-[10px] font-bold text-white bg-gray-500 px-2.5 py-1 rounded-full">
                                            <i class="fa-regular fa-circle-check mr-1"></i> Wajib
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="px-6 py-5 space-y-4">
                                {{-- Jarak + Transportasi (1 baris di mobile, 6-6 di desktop) --}}
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                    <div class="md:col-span-6">
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Jarak Rumah ke Sekolah</label>
                                        <select class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                                            <option value="">Pilih kisaran</option>
                                            <option>Kurang dari 1 km</option>
                                            <option>1 – 5 km</option>
                                            <option>5 – 10 km</option>
                                            <option>10 – 20 km</option>
                                            <option>Lebih dari 20 km</option>
                                        </select>
                                    </div>
                                    <div class="md:col-span-6">
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Moda Transportasi</label>
                                        <select name="transportation" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                                            <option value="">Pilih</option>
                                            <option value="jalan_kaki">🚶 Jalan kaki</option>
                                            <option value="sepeda">🚲 Sepeda</option>
                                            <option value="motor">🏍️ Sepeda motor</option>
                                            <option value="angkutan_umum">🚌 Angkutan umum</option>
                                            <option value="diantar">🚗 Diantar orang tua/wali</option>
                                            <option value="ojek_online">🛵 Ojek online</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Jenis Tempat Tinggal (full width) --}}
                                <div>
                                    <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Jenis Tempat Tinggal</label>
                                    <div class="flex gap-3 flex-wrap pt-1">
                                        <label class="flex items-center gap-2 px-4 py-2 border-2 border-gray-200 rounded-full cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary/5 has-[:checked]:text-primary text-sm font-semibold text-[#6A7686] transition-all bg-white">
                                            <input type="radio" name="residence_type" value="rumah_orang_tua" checked class="accent-primary w-3.5 h-3.5">
                                            <i class="fa-solid fa-house-user text-sm"></i> Rumah Orang Tua
                                        </label>
                                        <label class="flex items-center gap-2 px-4 py-2 border-2 border-gray-200 rounded-full cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary/5 has-[:checked]:text-primary text-sm font-semibold text-[#6A7686] transition-all bg-white">
                                            <input type="radio" name="residence_type" value="kos" class="accent-primary w-3.5 h-3.5">
                                            <i class="fa-solid fa-building text-sm"></i> Kos/Kontrak
                                        </label>
                                        <label class="flex items-center gap-2 px-4 py-2 border-2 border-gray-200 rounded-full cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary/5 has-[:checked]:text-primary text-sm font-semibold text-[#6A7686] transition-all bg-white">
                                            <input type="radio" name="residence_type" value="asrama" class="accent-primary w-3.5 h-3.5">
                                            <i class="fa-solid fa-bed text-sm"></i> Asrama
                                        </label>
                                        <label class="flex items-center gap-2 px-4 py-2 border-2 border-gray-200 rounded-full cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary/5 has-[:checked]:text-primary text-sm font-semibold text-[#6A7686] transition-all bg-white">
                                            <input type="radio" name="residence_type" value="lainnya" class="accent-primary w-3.5 h-3.5">
                                            <i class="fa-solid fa-ellipsis text-sm"></i> Lainnya
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Info tambahan --}}
                        <div class="flex gap-3 items-start bg-blue-50 border border-blue-100 rounded-xl px-4 py-3">
                            <i class="fa-solid fa-circle-info text-blue-500 text-sm mt-0.5 flex-shrink-0"></i>
                            <p class="text-xs text-blue-700 leading-relaxed">
                                <strong class="font-black">Catatan:</strong> Pastikan alamat yang diisi adalah alamat domisili saat ini. Data ini akan digunakan untuk keperluan administrasi dan komunikasi sekolah.
                            </p>
                        </div>

                    </div>

                    {{-- Footer navigasi --}}
                    <div class="px-8 py-5 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end rounded-b-[20px] gap-2">
                        <button type="button" @click="step--" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-[#6A7686] border border-gray-200 rounded-lg hover:border-[#080C1A] hover:text-[#080C1A] transition-all">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </button>
                        <div class="flex items-center gap-3">
                            <button type="button" @click="step++" class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-lg hover:bg-primary-hover transition-all">
                                Berikutnya <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- STEP 3 — DATA ORANG TUA / WALI -->

                <div x-show="step === 3" class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">
                    <div class="px-8 pt-6 border-gray-100 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-people-roof text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-black text-[#080C1A]">Data Orang Tua / Wali</h2>
                            <p class="text-sm text-[#6A7686]">Data ayah, ibu, dan wali (jika ada)</p>
                        </div>
                    </div>

                    <div class="px-8 py-7 space-y-6">

                        <!-- SECTION AYAH -->

                        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200"
                            x-data="{ ayahStatus: 'alive' }">

                            {{-- Header --}}
                            <div class="bg-blue-50 px-6 py-4 border-b border-blue-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-blue-500 flex items-center justify-center shadow-sm">
                                        <i class="fa-solid fa-person text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-bold text-blue-800">Ayah Kandung</h3>
                                        <p class="text-[11px] text-blue-600">Data sesuai Kartu Keluarga</p>
                                    </div>
                                    <div class="ml-auto">
                                        <span class="text-[10px] font-bold text-blue-600 bg-blue-100 px-2.5 py-1 rounded-full">
                                            <i class="fa-regular fa-circle-check mr-1"></i> Wajib
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Body --}}
                            <div class="px-6 py-5 space-y-4">
                                {{-- Nama Lengkap (tetap aktif) --}}
                                <div>
                                    <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Nama Lengkap Ayah <span class="text-primary">*</span></label>
                                    <input type="text" name="ayah_name" placeholder="Contoh: Ahmad Suryono, S.Pd" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:bg-white transition-all outline-none text-[14px] font-medium text-[#080C1A]">
                                </div>

                                {{-- Status & NIK (NIK ikut di-disable saat meninggal) --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Status Ayah <span class="text-primary">*</span></label>
                                        <select x-model="ayahStatus" name="ayah_living_status" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                                            <option value="alive">✅ Masih Hidup</option>
                                            <option value="deceased">🕊️ Meninggal</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">NIK Ayah</label>
                                        <input type="text" maxlength="16" name="ayah_nik" placeholder="16 digit NIK"
                                            :disabled="ayahStatus === 'deceased'"
                                            :class="ayahStatus === 'deceased' ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200' : 'bg-gray-50 focus:border-blue-400 focus:ring-2 focus:ring-blue-100'"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:bg-white transition-all outline-none text-[14px]">
                                        <p class="text-[10px] text-gray-400 mt-1" :class="ayahStatus === 'deceased' ? 'text-gray-300' : 'text-gray-400'">16 digit angka (sesuai KTP)</p>
                                    </div>
                                </div>

                                {{-- Pendidikan & No. Telepon (di-disable saat meninggal) --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Pendidikan Terakhir Ayah</label>
                                        <select name="ayah_education"
                                            :disabled="ayahStatus === 'deceased'"
                                            :class="ayahStatus === 'deceased' ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200' : 'bg-gray-50 focus:border-blue-400 focus:ring-2 focus:ring-blue-100'"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                                            <option value="">Pilih Pendidikan</option>
                                            <option>Tidak Sekolah</option>
                                            <option>SD/MI</option>
                                            <option>SMP/MTs</option>
                                            <option>SMA/SMK/MA</option>
                                            <option>D1/D2/D3</option>
                                            <option>S1/D4</option>
                                            <option>S2</option>
                                            <option>S3</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">No. Telepon / HP Ayah</label>
                                        <div class="flex">
                                            <span class="inline-flex items-center px-3 bg-gray-100 border border-r-0 border-gray-200 rounded-l-xl text-sm font-bold text-[#6A7686]"
                                                :class="ayahStatus === 'deceased' ? 'bg-gray-100 text-gray-400' : 'bg-gray-100 text-[#6A7686]'">+62</span>
                                            <input type="tel" name="ayah_phone" placeholder="81234567890"
                                                :disabled="ayahStatus === 'deceased'"
                                                :class="ayahStatus === 'deceased' ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200' : 'bg-gray-50 focus:border-blue-400 focus:ring-2 focus:ring-blue-100'"
                                                class="flex-1 min-w-0 px-4 py-2.5 rounded-r-xl border border-gray-200 focus:bg-white transition-all outline-none text-[14px]">
                                        </div>
                                    </div>
                                </div>

                                {{-- Pekerjaan & Penghasilan (di-disable saat meninggal) --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Pekerjaan Ayah</label>
                                        <select name="ayah_job"
                                            :disabled="ayahStatus === 'deceased'"
                                            :class="ayahStatus === 'deceased' ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200' : 'bg-gray-50 focus:border-blue-400 focus:ring-2 focus:ring-blue-100'"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                                            <option value="">Pilih Pekerjaan</option>
                                            <option>PNS/TNI/Polri</option>
                                            <option>Wiraswasta</option>
                                            <option>Buruh</option>
                                            <option>Petani</option>
                                            <option>Nelayan</option>
                                            <option>Guru/Dosen</option>
                                            <option>Dokter/Perawat</option>
                                            <option>Karyawan Swasta</option>
                                            <option>Pensiunan</option>
                                            <option>Tidak Bekerja</option>
                                            <option>Lainnya</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Penghasilan per Bulan</label>
                                        <select name="ayah_income"
                                            :disabled="ayahStatus === 'deceased'"
                                            :class="ayahStatus === 'deceased' ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200' : 'bg-gray-50 focus:border-blue-400 focus:ring-2 focus:ring-blue-100'"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                                            <option value="">Pilih Kisaran Penghasilan</option>
                                            <option>Kurang dari Rp 1.000.000</option>
                                            <option>Rp 1.000.000 - Rp 2.000.000</option>
                                            <option>Rp 2.000.000 - Rp 3.000.000</option>
                                            <option>Rp 3.000.000 - Rp 5.000.000</option>
                                            <option>Rp 5.000.000 - Rp 7.000.000</option>
                                            <option>Rp 7.000.000 - Rp 10.000.000</option>
                                            <option>Lebih dari Rp 10.000.000</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Alamat (full width) - di-disable saat meninggal --}}
                                <div>
                                    <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Alamat Ayah (jika berbeda)</label>
                                    <textarea name="ayah_address" rows="2" placeholder="Isi alamat lengkap ayah jika berbeda dengan alamat domisili siswa"
                                        :disabled="ayahStatus === 'deceased'"
                                        :class="ayahStatus === 'deceased' ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200' : 'bg-gray-50 focus:border-blue-400 focus:ring-2 focus:ring-blue-100'"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:bg-white transition-all outline-none text-[14px] resize-none"></textarea>
                                    <p class="text-[10px] text-gray-400" :class="ayahStatus === 'deceased' ? 'text-gray-300' : 'text-gray-400'">Kosongkan jika alamat sama dengan alamat domisili siswa</p>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION IBU -->

                        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200"
                            x-data="{ ibuStatus: 'alive' }">

                            {{-- Header --}}
                            <div class="bg-pink-50 px-6 py-4 border-b border-pink-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-pink-500 flex items-center justify-center shadow-sm">
                                        <i class="fa-solid fa-person-dress text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-bold text-pink-800">Ibu Kandung</h3>
                                        <p class="text-[11px] text-pink-600">Data sesuai Kartu Keluarga</p>
                                    </div>
                                    <div class="ml-auto">
                                        <span class="text-[10px] font-bold text-pink-600 bg-pink-100 px-2.5 py-1 rounded-full">
                                            <i class="fa-regular fa-circle-check mr-1"></i> Wajib
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Body --}}
                            <div class="px-6 py-5 space-y-4">
                                {{-- Nama Lengkap (tetap aktif) --}}
                                <div>
                                    <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Nama Lengkap Ibu <span class="text-primary">*</span></label>
                                    <input type="text" name="ibu_name" placeholder="Contoh: Siti Aminah, S.E" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:border-pink-400 focus:ring-2 focus:ring-pink-100 focus:bg-white transition-all outline-none text-[14px] font-medium text-[#080C1A]">
                                </div>

                                {{-- Status & NIK (NIK ikut di-disable saat meninggal) --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Status Ibu <span class="text-primary">*</span></label>
                                        <select x-model="ibuStatus" name="ibu_living_status" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:border-pink-400 focus:ring-2 focus:ring-pink-100 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                                            <option value="alive">✅ Masih Hidup</option>
                                            <option value="deceased">🕊️ Meninggal</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">NIK Ibu</label>
                                        <input type="text" maxlength="16" name="ibu_nik" placeholder="16 digit NIK"
                                            :disabled="ibuStatus === 'deceased'"
                                            :class="ibuStatus === 'deceased' ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200' : 'bg-gray-50 focus:border-pink-400 focus:ring-2 focus:ring-pink-100'"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:bg-white transition-all outline-none text-[14px]">
                                        <p class="text-[10px] text-gray-400 mt-1" :class="ibuStatus === 'deceased' ? 'text-gray-300' : 'text-gray-400'">16 digit angka (sesuai KTP)</p>
                                    </div>
                                </div>

                                {{-- Pendidikan & No. Telepon (di-disable saat meninggal) --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Pendidikan Terakhir Ibu</label>
                                        <select name="ibu_education"
                                            :disabled="ibuStatus === 'deceased'"
                                            :class="ibuStatus === 'deceased' ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200' : 'bg-gray-50 focus:border-pink-400 focus:ring-2 focus:ring-pink-100'"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                                            <option value="">Pilih Pendidikan</option>
                                            <option>Tidak Sekolah</option>
                                            <option>SD/MI</option>
                                            <option>SMP/MTs</option>
                                            <option>SMA/SMK/MA</option>
                                            <option>D1/D2/D3</option>
                                            <option>S1/D4</option>
                                            <option>S2</option>
                                            <option>S3</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">No. Telepon / HP Ibu</label>
                                        <div class="flex">
                                            <span class="inline-flex items-center px-3 bg-gray-100 border border-r-0 border-gray-200 rounded-l-xl text-sm font-bold text-[#6A7686]"
                                                :class="ibuStatus === 'deceased' ? 'bg-gray-100 text-gray-400' : 'bg-gray-100 text-[#6A7686]'">+62</span>
                                            <input type="tel" name="ibu_phone" placeholder="81234567890"
                                                :disabled="ibuStatus === 'deceased'"
                                                :class="ibuStatus === 'deceased' ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200' : 'bg-gray-50 focus:border-pink-400 focus:ring-2 focus:ring-pink-100'"
                                                class="flex-1 min-w-0 px-4 py-2.5 rounded-r-xl border border-gray-200 focus:bg-white transition-all outline-none text-[14px]">
                                        </div>
                                    </div>
                                </div>

                                {{-- Pekerjaan & Penghasilan (di-disable saat meninggal) --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Pekerjaan Ibu</label>
                                        <select name="ibu_job"
                                            :disabled="ibuStatus === 'deceased'"
                                            :class="ibuStatus === 'deceased' ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200' : 'bg-gray-50 focus:border-pink-400 focus:ring-2 focus:ring-pink-100'"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                                            <option value="">Pilih Pekerjaan</option>
                                            <option>Ibu Rumah Tangga</option>
                                            <option>PNS/TNI/Polri</option>
                                            <option>Wiraswasta</option>
                                            <option>Guru/Dosen</option>
                                            <option>Dokter/Perawat</option>
                                            <option>Karyawan Swasta</option>
                                            <option>Petani</option>
                                            <option>Buruh</option>
                                            <option>Pensiunan</option>
                                            <option>Lainnya</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Penghasilan per Bulan</label>
                                        <select name="ibu_income"
                                            :disabled="ibuStatus === 'deceased'"
                                            :class="ibuStatus === 'deceased' ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200' : 'bg-gray-50 focus:border-pink-400 focus:ring-2 focus:ring-pink-100'"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                                            <option value="">Pilih Kisaran Penghasilan</option>
                                            <option>Kurang dari Rp 1.000.000</option>
                                            <option>Rp 1.000.000 - Rp 2.000.000</option>
                                            <option>Rp 2.000.000 - Rp 3.000.000</option>
                                            <option>Rp 3.000.000 - Rp 5.000.000</option>
                                            <option>Rp 5.000.000 - Rp 7.000.000</option>
                                            <option>Rp 7.000.000 - Rp 10.000.000</option>
                                            <option>Lebih dari Rp 10.000.000</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Alamat (full width) - di-disable saat meninggal --}}
                                <div>
                                    <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Alamat Ibu (jika berbeda)</label>
                                    <textarea name="ibu_address" rows="2" placeholder="Isi alamat lengkap ibu jika berbeda dengan alamat domisili siswa"
                                        :disabled="ibuStatus === 'deceased'"
                                        :class="ibuStatus === 'deceased' ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200' : 'bg-gray-50 focus:border-pink-400 focus:ring-2 focus:ring-pink-100'"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:bg-white transition-all outline-none text-[14px] resize-none"></textarea>
                                    <p class="text-[10px] text-gray-400" :class="ibuStatus === 'deceased' ? 'text-gray-300' : 'text-gray-400'">Kosongkan jika alamat sama dengan alamat domisili siswa</p>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION WALI (OPSIONAL) - COLLAPSIBLE -->

                        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm transition-all duration-200"
                            :class="showWali ? 'border-violet-300 shadow-md' : 'border-gray-200'">

                            {{-- Header dengan toggle --}}
                            <div class="bg-gray-50 px-6 py-3 border-b border-gray-100 cursor-pointer hover:bg-gray-100 transition-colors"
                                @click="showWali = !showWali">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-gray-400 flex items-center justify-center"
                                            :class="showWali ? 'bg-violet-500' : 'bg-gray-400'">
                                            <i class="fa-solid fa-user-shield text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-base font-bold" :class="showWali ? 'text-violet-800' : 'text-gray-600'">
                                                Data Wali
                                            </h3>
                                            <p class="text-[11px]" :class="showWali ? 'text-violet-600' : 'text-gray-400'">
                                                Isi jika siswa tidak tinggal bersama orang tua kandung
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-bold text-gray-400 bg-gray-200 px-2 py-1 rounded-full">Opsional</span>
                                        <i class="fa-solid text-sm transition-transform duration-200"
                                            :class="showWali ? 'fa-chevron-up text-violet-500' : 'fa-chevron-down text-gray-400'"></i>
                                    </div>
                                </div>
                            </div>

                            {{-- Body (collapsible) --}}
                            <div x-show="showWali" x-transition.duration.200ms class="px-6 py-5 space-y-4" x-data="{ waliStatus: 'alive' }">
                                {{-- Nama Lengkap --}}
                                <div>
                                    <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Nama Lengkap Wali <span class="text-primary">*</span></label>
                                    <input type="text" name="wali_name" placeholder="Contoh: H. Rahmat Hidayat, S.H" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:border-violet-400 focus:ring-2 focus:ring-violet-100 focus:bg-white transition-all outline-none text-[14px]">
                                </div>

                                {{-- Status & NIK (NIK ikut di-disable saat meninggal) --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Status Wali</label>
                                        <select x-model="waliStatus" name="wali_status" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:border-violet-400 focus:ring-2 focus:ring-violet-100 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                                            <option value="alive">✅ Masih Hidup</option>
                                            <option value="deceased">🕊️ Meninggal</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">NIK Wali</label>
                                        <input type="text" maxlength="16" name="wali_nik" placeholder="16 digit NIK"
                                            :disabled="waliStatus === 'deceased'"
                                            :class="waliStatus === 'deceased' ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200' : 'bg-gray-50 focus:border-violet-400 focus:ring-2 focus:ring-violet-100'"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:bg-white transition-all outline-none text-[14px]">
                                        <p class="text-[10px] text-gray-400 mt-1" :class="waliStatus === 'deceased' ? 'text-gray-300' : 'text-gray-400'">16 digit angka (sesuai KTP)</p>
                                    </div>
                                </div>

                                {{-- Pendidikan & No. Telepon (di-disable saat meninggal) --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Pendidikan Terakhir Wali</label>
                                        <select name="wali_education"
                                            :disabled="waliStatus === 'deceased'"
                                            :class="waliStatus === 'deceased' ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200' : 'bg-gray-50 focus:border-violet-400 focus:ring-2 focus:ring-violet-100'"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                                            <option value="">Pilih Pendidikan</option>
                                            <option>Tidak Sekolah</option>
                                            <option>SD/MI</option>
                                            <option>SMP/MTs</option>
                                            <option>SMA/SMK/MA</option>
                                            <option>D1/D2/D3</option>
                                            <option>S1/D4</option>
                                            <option>S2</option>
                                            <option>S3</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">No. Telepon / HP Wali</label>
                                        <div class="flex">
                                            <span class="inline-flex items-center px-3 bg-gray-100 border border-r-0 border-gray-200 rounded-l-xl text-sm font-bold text-[#6A7686]"
                                                :class="waliStatus === 'deceased' ? 'bg-gray-100 text-gray-400' : 'bg-gray-100 text-[#6A7686]'">+62</span>
                                            <input type="tel" name="wali_phone" placeholder="81234567890"
                                                :disabled="waliStatus === 'deceased'"
                                                :class="waliStatus === 'deceased' ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200' : 'bg-gray-50 focus:border-violet-400 focus:ring-2 focus:ring-violet-100'"
                                                class="flex-1 min-w-0 px-4 py-2.5 rounded-r-xl border border-gray-200 focus:bg-white transition-all outline-none text-[14px]">
                                        </div>
                                    </div>
                                </div>

                                {{-- Pekerjaan & Penghasilan (di-disable saat meninggal) --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Pekerjaan Wali</label>
                                        <select name="wali_job"
                                            :disabled="waliStatus === 'deceased'"
                                            :class="waliStatus === 'deceased' ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200' : 'bg-gray-50 focus:border-violet-400 focus:ring-2 focus:ring-violet-100'"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                                            <option value="">Pilih Pekerjaan</option>
                                            <option>PNS/TNI/Polri</option>
                                            <option>Wiraswasta</option>
                                            <option>Buruh</option>
                                            <option>Petani</option>
                                            <option>Guru/Dosen</option>
                                            <option>Karyawan Swasta</option>
                                            <option>Pensiunan</option>
                                            <option>Tidak Bekerja</option>
                                            <option>Lainnya</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Penghasilan per Bulan</label>
                                        <select name="wali_income"
                                            :disabled="waliStatus === 'deceased'"
                                            :class="waliStatus === 'deceased' ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200' : 'bg-gray-50 focus:border-violet-400 focus:ring-2 focus:ring-violet-100'"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                                            <option value="">Pilih Kisaran Penghasilan</option>
                                            <option>Kurang dari Rp 1.000.000</option>
                                            <option>Rp 1.000.000 - Rp 2.000.000</option>
                                            <option>Rp 2.000.000 - Rp 3.000.000</option>
                                            <option>Rp 3.000.000 - Rp 5.000.000</option>
                                            <option>Rp 5.000.000 - Rp 7.000.000</option>
                                            <option>Rp 7.000.000 - Rp 10.000.000</option>
                                            <option>Lebih dari Rp 10.000.000</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Alamat full width --}}
                                <div>
                                    <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Alamat Wali</label>
                                    <textarea name="wali_address" rows="2" placeholder="Isi alamat lengkap wali"
                                        :disabled="waliStatus === 'deceased'"
                                        :class="waliStatus === 'deceased' ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200' : 'bg-gray-50 focus:border-violet-400 focus:ring-2 focus:ring-violet-100'"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:bg-white transition-all outline-none text-[14px] resize-none"></textarea>
                                    <p class="text-[10px] text-gray-400" :class="waliStatus === 'deceased' ? 'text-gray-300' : 'text-gray-400'">Kosongkan jika alamat sama dengan alamat domisili siswa</p>
                                </div>
                            </div>
                        </div>

                        {{-- Info tambahan --}}
                        <div class="flex gap-3 items-start bg-blue-50 border border-blue-100 rounded-xl px-4 py-3">
                            <i class="fa-solid fa-circle-info text-blue-500 text-sm mt-0.5 flex-shrink-0"></i>
                            <p class="text-xs text-blue-700 leading-relaxed">
                                <strong class="font-black">Catatan:</strong> Data orang tua akan digunakan untuk keperluan administrasi sekolah dan beasiswa.
                                Pastikan nama sesuai dengan Kartu Keluarga (KK) terbaru. Jika orang tua sudah meninggal, pilih status "Meninggal" dan isi nama lengkapnya saja.
                                Data penghasilan digunakan sebagai pertimbangan bantuan pendidikan.
                            </p>
                        </div>

                    </div>

                    {{-- Footer navigasi --}}
                    <div class="px-8 py-5 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end rounded-b-[20px] gap-2">
                        <button type="button" @click="step--" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-[#6A7686] border border-gray-200 rounded-lg hover:border-[#080C1A] hover:text-[#080C1A] transition-all">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </button>
                        <div class="flex items-center gap-3">
                            <button type="button" @click="step++" class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-lg hover:bg-primary-hover transition-all">
                                Berikutnya <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- STEP 4 — DATA PENDIDIKAN -->

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
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Kota Sekolah Asal</label>
                                <input type="text" placeholder="Nama kota/kab" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Provinsi Sekolah Asal</label>
                                <input type="text" placeholder="Nama provinsi" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                            </div>
                        </div>
                    </div>
                    <div class="px-8 py-5 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end rounded-b-[20px] gap-2">
                        <button type="button" @click="step--" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-[#6A7686] border border-gray-200 rounded-lg hover:border-[#080C1A] hover:text-[#080C1A] transition-all">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </button>
                        <div class="flex items-center gap-3">
                            <button type="button" @click="step++" class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-lg hover:bg-primary-hover transition-all">
                                Berikutnya <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- STEP 5 — PAS FOTO -->

                <div x-show="step === 5"
                    class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">
                    <div class="px-8 pt-6 border-gray-100 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-violet-50 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-camera text-violet-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-black text-[#080C1A]">Upload Pas Foto</h2>
                            <p class="text-sm text-[#6A7686]">Pas foto terbaru ukuran 3×4 atau 4×6, format JPG/PNG</p>
                        </div>
                    </div>
                    <div class="px-8 py-8 space-y-6">
                        <div class="flex gap-3 items-start bg-blue-50 border border-blue-200 rounded-2xl px-4 py-3.5">
                            <i class="fa-solid fa-circle-info text-blue-500 text-base mt-0.5 flex-shrink-0"></i>
                            <p class="text-sm font-medium text-blue-800 leading-relaxed">
                                Foto harus berupa foto formal terbaru (maks. 6 bulan terakhir), dengan latar belakang <strong>merah atau biru</strong>.
                                Wajah terlihat jelas, tidak memakai kacamata hitam atau topi.
                            </p>
                        </div>

                        {{-- Ketentuan pas foto --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <div class="flex flex-col items-center gap-2 p-4 bg-gray-50 rounded-2xl border border-gray-200 text-center">
                                <div class="w-10 h-10 rounded-full bg-violet-100 flex items-center justify-center">
                                    <i class="fa-solid fa-image text-violet-600 text-base"></i>
                                </div>
                                <span class="text-[12px] font-bold text-[#080C1A]">Format</span>
                                <span class="text-[12px] text-[#6A7686]">JPG atau PNG</span>
                            </div>
                            <div class="flex flex-col items-center gap-2 p-4 bg-gray-50 rounded-2xl border border-gray-200 text-center">
                                <div class="w-10 h-10 rounded-full bg-violet-100 flex items-center justify-center">
                                    <i class="fa-solid fa-weight-hanging text-violet-600 text-base"></i>
                                </div>
                                <span class="text-[12px] font-bold text-[#080C1A]">Ukuran File</span>
                                <span class="text-[12px] text-[#6A7686]">Maks. 1 MB</span>
                            </div>
                            <div class="flex flex-col items-center gap-2 p-4 bg-gray-50 rounded-2xl border border-gray-200 text-center">
                                <div class="w-10 h-10 rounded-full bg-violet-100 flex items-center justify-center">
                                    <i class="fa-solid fa-expand text-violet-600 text-base"></i>
                                </div>
                                <span class="text-[12px] font-bold text-[#080C1A]">Dimensi</span>
                                <span class="text-[12px] text-[#6A7686]">3×4 atau 4×6 cm</span>
                            </div>
                            <div class="flex flex-col items-center gap-2 p-4 bg-gray-50 rounded-2xl border border-gray-200 text-center">
                                <div class="w-10 h-10 rounded-full bg-violet-100 flex items-center justify-center">
                                    <i class="fa-solid fa-palette text-violet-600 text-base"></i>
                                </div>
                                <span class="text-[12px] font-bold text-[#080C1A]">Latar</span>
                                <span class="text-[12px] text-[#6A7686]">Merah / Biru</span>
                            </div>
                        </div>

                        {{-- Upload area --}}
                        <div class="space-y-3" x-data="{
                            fotoFile: null,
                            fotoPreview: null,
                            handleFoto(e) {
                                const file = e.target.files[0];
                                if (!file) return;
                                this.fotoFile = file.name;
                                const reader = new FileReader();
                                reader.onload = (ev) => { this.fotoPreview = ev.target.result; };
                                reader.readAsDataURL(file);
                            }
                        }">
                            <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Pas Foto Terbaru <span class="text-primary">*</span></label>

                            {{-- Empty state --}}
                            <div x-show="!fotoPreview"
                                @click="$refs.inputFoto.click()"
                                class="border-2 border-dashed border-gray-200 rounded-2xl p-10 text-center cursor-pointer hover:border-violet-400 hover:bg-violet-50/50 transition-all group">
                                <div class="w-16 h-16 rounded-full bg-gray-100 group-hover:bg-violet-100 flex items-center justify-center mx-auto mb-4 transition-colors">
                                    <i class="fa-solid fa-camera text-2xl text-gray-300 group-hover:text-violet-500 transition-colors"></i>
                                </div>
                                <p class="text-[15px] font-bold text-[#080C1A] mb-1">Klik untuk memilih foto</p>
                                <p class="text-sm text-[#6A7686]">atau seret & lepas file ke sini</p>
                                <p class="text-[12px] text-[#6A7686] mt-2 font-semibold">JPG, PNG — Maksimal 1 MB</p>
                            </div>

                            {{-- Preview state --}}
                            <div x-show="fotoPreview" class="relative">
                                <div class="flex flex-col sm:flex-row items-center gap-6 p-6 bg-violet-50 border-2 border-violet-200 rounded-2xl">
                                    <div class="flex-shrink-0">
                                        <img :src="fotoPreview" alt="Preview Pas Foto"
                                            class="w-[100px] h-[130px] object-cover rounded-xl border-2 border-violet-300 shadow-md">
                                    </div>
                                    <div class="flex-1 text-center sm:text-left">
                                        <div class="flex items-center justify-center sm:justify-start gap-2 mb-2">
                                            <i class="fa-solid fa-circle-check text-green-500 text-lg"></i>
                                            <span class="text-sm font-black text-green-700">Foto berhasil diunggah</span>
                                        </div>
                                        <p class="text-sm font-semibold text-[#080C1A] truncate max-w-[200px]" x-text="fotoFile"></p>
                                        <p class="text-[13px] text-[#6A7686] mt-1">Pastikan wajah terlihat jelas dan foto sesuai ketentuan</p>
                                        <button type="button" @click="fotoFile = null; fotoPreview = null; $refs.inputFoto.value = ''"
                                            class="mt-3 inline-flex items-center gap-1.5 px-4 py-2 text-[13px] font-bold text-red-600 border border-red-200 rounded-full hover:bg-red-50 transition-all">
                                            <i class="fa-solid fa-trash-alt text-xs"></i> Ganti Foto
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <input type="file" x-ref="inputFoto" name="photo" accept=".jpg,.jpeg,.png" class="hidden"
                                @change="handleFoto($event)">
                        </div>
                    </div>
                    <div class="px-8 py-5 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end rounded-b-[20px] gap-2">
                        <button type="button" @click="step--" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-[#6A7686] border border-gray-200 rounded-lg hover:border-[#080C1A] hover:text-[#080C1A] transition-all">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </button>
                        <div class="flex items-center gap-3">
                            <button type="button" @click="step++" class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-lg hover:bg-primary-hover transition-all">
                                Berikutnya <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- STEP 6 — KONFIRMASI -->

                <div x-show="step === 6"
                    class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">
                    <div class="px-8 pt-6 border-gray-100 flex items-center gap-4">
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
                                    <span class="text-[13px] text-green-600 font-bold flex items-center gap-1"><i class="fa-solid fa-circle-check"></i> Lengkap</span>
                                </div>
                                <div class="px-5 py-4 flex flex-wrap gap-2">
                                    <span class="text-[13px] px-3 py-1 rounded-full bg-green-100 text-green-700 font-semibold border border-green-200"><i class="fa-solid fa-check mr-1"></i>Pas Foto</span>
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
                    <div class="px-8 py-5 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end rounded-b-[20px] gap-2">
                        <button type="button" @click="step--"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-[#6A7686] border border-gray-200 rounded-lg hover:border-[#080C1A] hover:text-[#080C1A] transition-all">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </button>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-8 py-2.5 bg-green-600 text-white text-sm font-black rounded-lg hover:bg-green-700 hover:-translate-y-px transition-all">
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
                            {name: 'Pas Foto', icon: 'fa-camera', color: 'text-violet-600'},
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

    <!-- SUCCESS SCREEN -->

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

<!-- JAVASCRIPT -->

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
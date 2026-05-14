@extends('layouts.user')

@section('title', 'Data Pribadi')

@section('content')

<div x-data="{
    step: 1,
    totalSteps: 6,
    isSubmitted: false,
    showWali: false,
    ayah_status: '1', {{-- 1: Hidup, 2: Meninggal --}}
    ibu_status: '1',
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
        <div class="absolute -top-10 -right-10 w-[200px] h-[200px] bg-white/[0.06] rounded-full pointer-events-none"></div>
        <div class="absolute -bottom-[60px] right-24 w-[160px] h-[160px] bg-white/[0.04] rounded-full pointer-events-none"></div>

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

    <div class="lg:grid lg:grid-cols-[1fr_340px] lg:gap-6 lg:items-start" x-show="!isSubmitted">

        <div class="min-w-0">
            {{-- STEPPER --}}
            <div class="bg-white border border-gray-200 rounded-[20px] px-7 py-6 mb-6 shadow-sm">
                <div class="flex items-start justify-between">
                    <template x-for="i in totalSteps" :key="i">
                        <div class="flex flex-col items-center flex-1 relative">
                            <template x-if="i < totalSteps">
                                <div class="absolute top-[21px] left-1/2 w-full h-0.5 z-0"
                                    :class="step > i ? 'bg-primary' : 'bg-gray-200'"></div>
                            </template>

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
                            <span class="hidden sm:block text-[12px] font-semibold text-center max-w-[72px] leading-tight mt-2.5 transition-colors"
                                :class="step >= i ? 'text-primary' : 'text-gray-400'"
                                x-text="stepLabels[i-1]"></span>
                        </div>
                    </template>
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
                        <div class="grid grid-cols-1 sm:grid-cols-[1fr_2fr] gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">NIK <span class="text-primary">*</span></label>
                                <input type="text" maxlength="16" placeholder="16 digit NIK" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">NISN <span class="text-primary">*</span></label>
                                <input type="text" maxlength="10" placeholder="Nomor Induk Siswa Nasional" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-[2fr_1fr] gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Nama Lengkap <span class="text-primary">*</span></label>
                                <input type="text" placeholder="Sesuai akta kelahiran" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Nama Panggilan</label>
                                <input type="text" placeholder="Nama sehari-hari" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Tempat Lahir <span class="text-primary">*</span></label>
                                <input type="text" placeholder="Kota/Kabupaten" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Tanggal Lahir <span class="text-primary">*</span></label>
                                <input type="date" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Jenis Kelamin <span class="text-primary">*</span></label>
                                <select class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                                    <option value="">Pilih</option>
                                    <option>Laki-laki</option>
                                    <option>Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Agama <span class="text-primary">*</span></label>
                                <select class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
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
                                <select class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                                    <option>WNI</option>
                                    <option>WNA</option>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Golongan Darah</label>
                                <select class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                                    <option value="">Tidak tahu</option>
                                    <option>A</option>
                                    <option>B</option>
                                    <option>AB</option>
                                    <option>O</option>
                                </select>
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
                    STEP 2 — ALAMAT & KONTAK
                ══════════════════════════════ --}}
                <div x-show="step === 2"
                    class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">
                    <div class="px-8 pt-6 pb-5 border-b border-gray-100 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-location-dot text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-black text-[#080C1A]">Data Alamat & Kontak</h2>
                            <p class="text-sm text-[#6A7686]">Informasi tempat tinggal dan kontak person aktif</p>
                        </div>
                    </div>
                    <div class="px-8 py-7 space-y-5">
                        <div class="space-y-1.5">
                            <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Alamat Jalan <span class="text-primary">*</span></label>
                            <input type="text" placeholder="Nama jalan, nomor rumah, atau nama dusun/kampung"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">RT</label>
                                <input type="text" placeholder="001" maxlength="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">RW</label>
                                <input type="text" placeholder="002" maxlength="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                            </div>
                            <div class="sm:col-span-2 space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Desa / Kelurahan <span class="text-primary">*</span></label>
                                <input type="text" placeholder="Nama kelurahan/desa" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Kecamatan <span class="text-primary">*</span></label>
                                <input type="text" placeholder="Nama kecamatan" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Kota / Kabupaten <span class="text-primary">*</span></label>
                                <input type="text" placeholder="Nama kota/kabupaten" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Provinsi <span class="text-primary">*</span></label>
                                <input type="text" placeholder="Nama provinsi" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Kode Pos <span class="text-primary">*</span></label>
                                <input type="text" placeholder="3XXXX" maxlength="5" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                            </div>
                        </div>

                        <div class="border-t border-dashed border-gray-200 pt-5">
                            <p class="text-sm font-bold mb-4">Jenis Tempat Tinggal</p>
                            <div class="flex gap-3 flex-wrap pt-1">
                                <label class="flex items-center gap-2 px-4 py-2.5 border-2 border-gray-200 rounded-full cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary-light has-[:checked]:text-primary text-sm font-semibold text-[#6A7686] transition-all">
                                    <input type="radio" name="residence_type" value="rumah_orang_tua" checked class="accent-primary"> Rumah Orang Tua
                                </label>
                                <label class="flex items-center gap-2 px-4 py-2.5 border-2 border-gray-200 rounded-full cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary-light has-[:checked]:text-primary text-sm font-semibold text-[#6A7686] transition-all">
                                    <input type="radio" name="residence_type" value="kos" class="accent-primary"> Kos/Kontrak
                                </label>
                                <label class="flex items-center gap-2 px-4 py-2.5 border-2 border-gray-200 rounded-full cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary-light has-[:checked]:text-primary text-sm font-semibold text-[#6A7686] transition-all">
                                    <input type="radio" name="residence_type" value="asrama" class="accent-primary"> Asrama
                                </label>
                                <label class="flex items-center gap-2 px-4 py-2.5 border-2 border-gray-200 rounded-full cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary-light has-[:checked]:text-primary text-sm font-semibold text-[#6A7686] transition-all">
                                    <input type="radio" name="residence_type" value="lainnya" class="accent-primary"> Lainnya
                                </label>
                            </div>
                        </div>

                        <div class="border-t border-dashed border-gray-200 pt-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">No. HP / WhatsApp <span class="text-primary">*</span></label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-4 bg-gray-100 border border-r-0 border-gray-200 rounded-l-xl text-base font-bold text-[#6A7686]">+62</span>
                                    <input type="tel" placeholder="81234567890" class="flex-1 min-w-0 px-4 py-3 rounded-r-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Email Aktif <span class="text-primary">*</span></label>
                                <div class="relative flex items-center">
                                    <i class="fa-solid fa-envelope absolute left-4 text-[#6A7686] text-sm pointer-events-none"></i>
                                    <input type="email" placeholder="nama@email.com" class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-8 py-5 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between rounded-b-[20px]">
                        <button type="button" @click="step--"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-[#6A7686] border border-gray-200 rounded-full hover:border-[#080C1A] hover:text-[#080C1A] transition-all">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </button>
                        <button type="button" @click="step++"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-full hover:bg-primary-hover hover:-translate-y-px transition-all shadow-lg shadow-primary/30">
                            Lanjut: Data Orang Tua <i class="fa-solid fa-arrow-right"></i>
                        </button>
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
                                <input type="text" placeholder="Sesuai KTP" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Status Ayah <span class="text-primary">*</span></label>
                                <div class="flex gap-3 pt-1">
                                    <label class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 border-2 border-gray-200 rounded-full cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary-light has-[:checked]:text-primary text-sm font-bold text-[#6A7686] transition-all">
                                        <input type="radio" name="ayah_living_status" value="1" x-model="ayah_status" class="accent-primary"> 1. Masih Hidup
                                    </label>
                                    <label class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 border-2 border-gray-200 rounded-full cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary-light has-[:checked]:text-primary text-sm font-bold text-[#6A7686] transition-all">
                                        <input type="radio" name="ayah_living_status" value="2" x-model="ayah_status" class="accent-primary"> 2. Meninggal
                                    </label>
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">NIK Ayah</label>
                                <input type="text" maxlength="16" :disabled="ayah_status === '2'" :class="ayah_status === '2' ? 'opacity-50 cursor-not-allowed' : ''" placeholder="16 digit NIK" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Pendidikan Terakhir</label>
                                <select :disabled="ayah_status === '2'" :class="ayah_status === '2' ? 'opacity-50 cursor-not-allowed' : ''" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                                    <option>Pilih</option>
                                    <option>SD/Sederajat</option>
                                    <option>SMP/Sederajat</option>
                                    <option>SMA/SMK/Sederajat</option>
                                    <option>S1/D4</option>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Pekerjaan</label>
                                <input type="text" :disabled="ayah_status === '2'" :class="ayah_status === '2' ? 'opacity-50 cursor-not-allowed' : ''" placeholder="Pekerjaan Ayah" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                            </div>
                        </div>

                        {{-- Ibu --}}
                        <div class="border-t border-dashed border-gray-200 pt-5 space-y-5">
                            <p class="text-sm font-bold flex items-center gap-2 pb-2.5 border-b border-gray-100">
                                <i class="fa-solid fa-person-dress text-pink-500"></i> Data Ibu Kandung
                            </p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div class="space-y-1.5 sm:col-span-2">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Nama Lengkap Ibu <span class="text-primary">*</span></label>
                                    <input type="text" placeholder="Sesuai KTP" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Status Ibu <span class="text-primary">*</span></label>
                                    <div class="flex gap-3 pt-1">
                                        <label class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 border-2 border-gray-200 rounded-full cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary-light has-[:checked]:text-primary text-sm font-bold text-[#6A7686] transition-all">
                                            <input type="radio" name="ibu_living_status" value="1" x-model="ibu_status" class="accent-primary"> 1. Masih Hidup
                                        </label>
                                        <label class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 border-2 border-gray-200 rounded-full cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary-light has-[:checked]:text-primary text-sm font-bold text-[#6A7686] transition-all">
                                            <input type="radio" name="ibu_living_status" value="2" x-model="ibu_status" class="accent-primary"> 2. Meninggal
                                        </label>
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">NIK Ibu</label>
                                    <input type="text" maxlength="16" :disabled="ibu_status === '2'" :class="ibu_status === '2' ? 'opacity-50 cursor-not-allowed' : ''" placeholder="16 digit NIK" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Pekerjaan</label>
                                    <input type="text" :disabled="ibu_status === '2'" :class="ibu_status === '2' ? 'opacity-50 cursor-not-allowed' : ''" placeholder="Pekerjaan Ibu" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
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
                                    <input type="text" placeholder="Sesuai KTP" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">No. HP Wali <span class="text-primary">*</span></label>
                                    <div class="flex">
                                        <span class="inline-flex items-center px-4 bg-gray-100 border border-r-0 border-gray-200 rounded-l-xl text-base font-bold text-[#6A7686]">+62</span>
                                        <input type="tel" placeholder="81234567890" class="flex-1 min-w-0 px-4 py-3 rounded-r-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
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
                        <button type="button" @click="step++"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-full hover:bg-primary-hover hover:-translate-y-px transition-all shadow-lg shadow-primary/30">
                            Lanjut: Data Pendidikan <i class="fa-solid fa-arrow-right"></i>
                        </button>
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
                            <p class="text-sm text-[#6A7686]">Riwayat sekolah asal</p>
                        </div>
                    </div>
                    <div class="px-8 py-7 space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="space-y-1.5 sm:col-span-2">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Nama Sekolah Asal <span class="text-primary">*</span></label>
                                <input type="text" placeholder="Contoh: SMP Negeri 1 Bengkulu" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">NPSN Sekolah</label>
                                <input type="text" maxlength="8" placeholder="8 digit NPSN" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Tahun Lulus <span class="text-primary">*</span></label>
                                <select class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                                    <option>2026</option>
                                    <option>2025</option>
                                    <option>2024</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Kota Sekolah Asal</label>
                                <input type="text" placeholder="Nama kota/kab" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Provinsi</label>
                                <input type="text" placeholder="Provinsi sekolah" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-primary focus:ring-4 focus:ring-primary/5 focus:bg-white transition-all outline-none text-[15px] font-medium text-[#080C1A]">
                            </div>
                        </div>
                    </div>
                    <div class="px-8 py-5 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between rounded-b-[20px]">
                        <button type="button" @click="step--"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-[#6A7686] border border-gray-200 rounded-full hover:border-[#080C1A] hover:text-[#080C1A] transition-all">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </button>
                        <button type="button" @click="step++"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-full hover:bg-primary-hover hover:-translate-y-px transition-all shadow-lg shadow-primary/30">
                            Lanjut: Pas Foto <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                {{-- ══════════════════════════════
                    STEP 5 — PAS FOTO
                ══════════════════════════════ --}}
                <div x-show="step === 5"
                    class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">
                    <div class="px-8 pt-6 pb-5 border-b border-gray-100 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-violet-50 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-camera text-violet-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-black text-[#080C1A]">Upload Pas Foto</h2>
                            <p class="text-sm text-[#6A7686]">Pas foto terbaru format JPG/PNG</p>
                        </div>
                    </div>
                    <div class="px-8 py-8 space-y-6">
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
                            <div x-show="!fotoPreview"
                                @click="$refs.inputFoto.click()"
                                class="border-2 border-dashed border-gray-200 rounded-2xl p-10 text-center cursor-pointer hover:border-violet-400 hover:bg-violet-50/50 transition-all group">
                                <div class="w-16 h-16 rounded-full bg-gray-100 group-hover:bg-violet-100 flex items-center justify-center mx-auto mb-4 transition-colors">
                                    <i class="fa-solid fa-camera text-2xl text-gray-300 group-hover:text-violet-500 transition-colors"></i>
                                </div>
                                <p class="text-[15px] font-bold text-[#080C1A] mb-1">Klik untuk memilih foto</p>
                                <p class="text-sm text-[#6A7686]">JPG, PNG — Maksimal 1 MB</p>
                            </div>

                            <div x-show="fotoPreview" class="relative">
                                <div class="flex flex-col sm:flex-row items-center gap-6 p-6 bg-violet-50 border-2 border-violet-200 rounded-2xl">
                                    <div class="flex-shrink-0">
                                        <img :src="fotoPreview" alt="Preview" class="w-[100px] h-[130px] object-cover rounded-xl border-2 border-violet-300">
                                    </div>
                                    <div class="flex-1 text-center sm:text-left">
                                        <p class="text-sm font-black text-green-700">Foto berhasil diunggah</p>
                                        <button type="button" @click="fotoFile = null; fotoPreview = null; $refs.inputFoto.value = ''"
                                            class="mt-3 inline-flex items-center gap-1.5 px-4 py-2 text-[13px] font-bold text-red-600 border border-red-200 rounded-full">
                                            Ganti Foto
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <input type="file" x-ref="inputFoto" name="photo" accept=".jpg,.jpeg,.png" class="hidden" @change="handleFoto($event)">
                        </div>
                    </div>
                    <div class="px-8 py-5 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between rounded-b-[20px]">
                        <button type="button" @click="step--"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-[#6A7686] border border-gray-200 rounded-full hover:border-[#080C1A] hover:text-[#080C1A] transition-all">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </button>
                        <button type="button" @click="step++"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-full hover:bg-primary-hover hover:-translate-y-px transition-all shadow-lg shadow-primary/30">
                            Lanjut: Konfirmasi <i class="fa-solid fa-arrow-right"></i>
                        </button>
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
                    <div class="px-8 py-7 space-y-5 text-center">
                        <p class="text-[#6A7686]">Pastikan semua data sudah terisi dengan benar sesuai dokumen asli.</p>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-8 py-2.5 bg-green-600 text-white text-sm font-black rounded-full hover:bg-green-700 hover:-translate-y-px transition-all shadow-lg shadow-green-500/30">
                            <i class="fa-solid fa-paper-plane"></i> Kirim Biodata Sekarang
                        </button>
                    </div>
                    <div class="px-8 py-5 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between rounded-b-[20px]">
                        <button type="button" @click="step--"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-[#6A7686] border border-gray-200 rounded-full">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </button>
                    </div>
                </div>

            </form>
        </div>

        {{-- ── SIDEBAR ── --}}
        <div class="hidden lg:block">
            <div class="sticky top-[80px] flex flex-col gap-4">
                <div class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">
                    <div class="px-5 py-4" style="background: linear-gradient(135deg,#FF1443,#D90F38);">
                        <h3 class="text-base font-black text-white mb-0.5">Kelengkapan Biodata</h3>
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
                                    :class="step > idx+1 ? 'text-green-600' : (step === idx+1 ? 'text-primary' : 'text-gray-400')">
                                    <template x-if="step > idx+1"><span>Selesai</span></template>
                                    <template x-if="step === idx+1"><span>Proses</span></template>
                                    <template x-if="step < idx+1"><span>Belum</span></template>
                                </span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- SUCCESS SCREEN (Keep as is) --}}
    <div x-show="isSubmitted" x-transition
        class="bg-white border border-gray-200 rounded-[20px] shadow-sm px-8 py-16 text-center">
        <div class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-circle-check text-green-500 text-4xl"></i>
        </div>
        <h2 class="text-2xl font-black text-[#080C1A] mb-2">Biodata Berhasil Dikirim! 🎉</h2>
        <div class="flex flex-col sm:flex-row gap-3 justify-center mt-8">
            <a href="{{ route('dashboard') }}" class="px-8 py-3 bg-gray-100 text-[#080C1A] rounded-full text-base font-bold">Dashboard</a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function saveDraft() {
        const t = document.createElement('div');
        t.className = 'fixed bottom-6 left-1/2 -translate-x-1/2 bg-[#080C1A] text-white px-[22px] py-[10px] rounded-full text-[13px] font-bold flex items-center gap-2 z-[9999]';
        t.innerHTML = 'Draft disimpan';
        document.body.appendChild(t);
        setTimeout(() => t.remove(), 2500);
    }
</script>
@endpush

@endsection
{{--
|==========================================================================
| STEP 3 — DATA ORANG TUA / WALI
|==========================================================================
| Perubahan (dari versi sebelumnya):
|   + Perbaikan akses variabel scope `showWali` langsung tanpa `$root`
|   + Restrukturisasi :class binding agar Tailwind CSS terbaca sempurna
|   + Sinkronisasi transisi Alpine.js pada accordion Wali
|==========================================================================
--}}

@php
$father = $personalData?->parents?->firstWhere('relationship', 'father');
$mother = $personalData?->parents?->firstWhere('relationship', 'mother');
$guardian = $personalData?->parents?->firstWhere('relationship', 'guardian');

$pendidikanOptions = ['Tidak Sekolah','SD/MI','SMP/MTs','SMA/SMK/MA','D1/D2/D3','S1/D4','S2','S3'];
$pekerjaanOptions = ['PNS/TNI/Polri','Wiraswasta','Buruh','Petani','Nelayan','Guru/Dosen','Dokter/Perawat','Karyawan Swasta','Pensiunan','Tidak Bekerja','Lainnya'];
$penghasilanOptions = [
'Kurang dari Rp 1.000.000',
'Rp 1.000.000 - Rp 2.000.000',
'Rp 2.000.000 - Rp 3.000.000',
'Rp 3.000.000 - Rp 5.000.000',
'Rp 5.000.000 - Rp 7.000.000',
'Rp 7.000.000 - Rp 10.000.000',
'Lebih dari Rp 10.000.000',
];
@endphp

<div x-show="step === 3"
    x-data="{
        errors3: {},
        hasError3: false,
        showWali: {{ $guardian ? 'true' : 'false' }},

        setErrors3(xhr) {
            if (xhr.status === 422) {
                try {
                    const body = JSON.parse(xhr.response);
                    this.errors3   = body.errors ?? {};
                    this.hasError3 = Object.keys(this.errors3).length > 0;

                    /* Jika ada error di field wali → buka accordion otomatis */
                    const waliFields = ['wali_name','wali_status','wali_nik','wali_birth_year',
                                        'wali_education','wali_job','wali_income','wali_phone','wali_address'];
                    const hasWaliError = waliFields.some(f => this.errors3[f]);
                    if (hasWaliError) this.showWali = true;

                } catch(e) {
                    this.errors3   = {};
                    this.hasError3 = false;
                }
            } else {
                this.errors3   = {};
                this.hasError3 = false;
            }
        },

        err3(field) {
            return this.errors3[field]?.[0] ?? null;
        }
    }"
    class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">

    {{-- Header --}}
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

        {{-- ── ERROR SUMMARY BOX ─────────────────────────────────────── --}}
        <div x-show="hasError3"
            x-transition
            class="flex gap-3 items-start bg-red-50 border border-red-200 rounded-2xl px-4 py-3.5">
            <i class="fa-solid fa-circle-exclamation text-red-500 text-base mt-0.5 flex-shrink-0"></i>
            <div>
                <p class="text-sm font-black text-red-700 mb-1">Harap periksa kembali isian Anda:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    <template x-for="(msgs, field) in errors3" :key="field">
                        <template x-for="msg in msgs" :key="msg">
                            <li class="text-sm text-red-600" x-text="msg"></li>
                        </template>
                    </template>
                </ul>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════ --}}
        {{-- SECTION AYAH                                                   --}}
        {{-- ═══════════════════════════════════════════════════════════════ --}}
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200"
            x-data="{ ayahStatus: '{{ old('ayah_status', $father?->living_status ?? 'alive') }}' }">

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

            <div class="px-6 py-5 space-y-4">
                {{-- Nama Lengkap Ayah --}}
                <div>
                    <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">
                        Nama Lengkap Ayah <span class="text-primary">*</span>
                    </label>
                    <input type="text" name="ayah_name"
                        placeholder="Contoh: Ahmad Suryono, S.Pd"
                        value="{{ old('ayah_name', $father?->name) }}"
                        :class="err3('ayah_name')
                            ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100'
                            : 'border-gray-200 bg-gray-50 focus:border-blue-400 focus:ring-blue-100'"
                        class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px] font-medium text-[#080C1A]">
                    <p class="text-[11px] text-red-600 font-semibold mt-1"
                        x-show="err3('ayah_name')" x-text="err3('ayah_name')">
                    </p>
                </div>

                {{-- Status & NIK --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">
                            Status Ayah <span class="text-primary">*</span>
                        </label>
                        <select x-model="ayahStatus" name="ayah_status"
                            :class="err3('ayah_status')
                                ? 'border-red-400 bg-red-50/50'
                                : 'border-gray-200 bg-gray-50 focus:border-blue-400 focus:ring-blue-100'"
                            class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                            <option value="alive">✅ Masih Hidup</option>
                            <option value="deceased">🕊️ Meninggal</option>
                        </select>
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err3('ayah_status')" x-text="err3('ayah_status')">
                        </p>
                    </div>
                    <div>
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">NIK Ayah</label>
                        <input type="text" name="ayah_nik" maxlength="16" placeholder="16 digit NIK"
                            value="{{ old('ayah_nik', $father?->nik) }}"
                            :disabled="ayahStatus === 'deceased'"
                            :class="ayahStatus === 'deceased'
                                ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200 focus:ring-0'
                                : (err3('ayah_nik')
                                    ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100'
                                    : 'border-gray-200 bg-gray-50 focus:border-blue-400 focus:ring-blue-100')"
                            class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px]">
                        <p class="text-[10px] mt-1"
                            x-show="!err3('ayah_nik')"
                            :class="ayahStatus === 'deceased' ? 'text-gray-300' : 'text-gray-400'">16 digit angka (sesuai KTP)</p>
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err3('ayah_nik') && ayahStatus !== 'deceased'" x-text="err3('ayah_nik')">
                        </p>
                    </div>
                </div>

                {{-- Tahun Lahir & No. Telepon --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Tahun Lahir Ayah</label>
                        <input type="text" name="ayah_birth_year" maxlength="4" placeholder="Contoh: 1975"
                            value="{{ old('ayah_birth_year', $father?->birth_year) }}"

                            {{-- 1. Tambahkan attribute disabled dinamis dari Alpine.js --}}
                            :disabled="ayahStatus === 'deceased'"

                            {{-- 2. Sesuaikan kelas kondisi warna agar berubah abu-abu saat dinonaktifkan --}}
                            :class="ayahStatus === 'deceased'
                                    ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200 focus:ring-0'
                                    : (err3('ayah_birth_year')
                                        ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100'
                                        : 'border-gray-200 bg-gray-50 focus:border-blue-400 focus:ring-blue-100')"
                            class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px]">

                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err3('ayah_birth_year') && ayahStatus !== 'deceased'" x-text="err3('ayah_birth_year')">
                        </p>
                    </div>
                    <div>
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">No. Telepon / HP Ayah</label>
                        <div class="flex rounded-xl overflow-hidden">
                            <span class="inline-flex items-center px-3 border border-r-0 border-gray-200 rounded-l-xl text-sm font-bold bg-gray-100 text-[#6A7686]"
                                :class="ayahStatus === 'deceased' && 'text-gray-400 bg-gray-100'">+62</span>
                            <input type="tel" name="ayah_phone" placeholder="81234567890"
                                value="{{ old('ayah_phone', $father?->phone_number) }}"
                                :disabled="ayahStatus === 'deceased'"
                                :class="ayahStatus === 'deceased'
                                    ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200 focus:ring-0'
                                    : (err3('ayah_phone')
                                        ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100'
                                        : 'border-gray-200 bg-gray-50 focus:border-blue-400 focus:ring-blue-100')"
                                class="flex-1 min-w-0 px-4 py-2.5 rounded-r-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px]">
                        </div>
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err3('ayah_phone') && ayahStatus !== 'deceased'" x-text="err3('ayah_phone')">
                        </p>
                    </div>
                </div>

                {{-- Pendidikan & Pekerjaan --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Pendidikan Terakhir Ayah</label>
                        <select name="ayah_education"
                            :disabled="ayahStatus === 'deceased'"
                            :class="ayahStatus === 'deceased'
                                ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200 focus:ring-0'
                                : (err3('ayah_education')
                                    ? 'border-red-400 bg-red-50/50'
                                    : 'border-gray-200 bg-gray-50 focus:border-blue-400 focus:ring-blue-100')"
                            class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                            <option value="">Pilih Pendidikan</option>
                            @foreach($pendidikanOptions as $opt)
                            <option value="{{ $opt }}" @selected(old('ayah_education', $father?->education) === $opt)>{{ $opt }}</option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err3('ayah_education') && ayahStatus !== 'deceased'" x-text="err3('ayah_education')">
                        </p>
                    </div>
                    <div>
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Pekerjaan Ayah</label>
                        <select name="ayah_job"
                            :disabled="ayahStatus === 'deceased'"
                            :class="ayahStatus === 'deceased'
                                ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200 focus:ring-0'
                                : (err3('ayah_job')
                                    ? 'border-red-400 bg-red-50/50'
                                    : 'border-gray-200 bg-gray-50 focus:border-blue-400 focus:ring-blue-100')"
                            class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                            <option value="">Pilih Pekerjaan</option>
                            @foreach($pekerjaanOptions as $opt)
                            <option value="{{ $opt }}" @selected(old('ayah_job', $father?->occupation) === $opt)>{{ $opt }}</option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err3('ayah_job') && ayahStatus !== 'deceased'" x-text="err3('ayah_job')">
                        </p>
                    </div>
                </div>

                {{-- Penghasilan --}}
                <div>
                    <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Penghasilan per Bulan</label>
                    <select name="ayah_income"
                        :disabled="ayahStatus === 'deceased'"
                        :class="ayahStatus === 'deceased'
                            ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200 focus:ring-0'
                            : (err3('ayah_income')
                                ? 'border-red-400 bg-red-50/50'
                                : 'border-gray-200 bg-gray-50 focus:border-blue-400 focus:ring-blue-100')"
                        class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                        <option value="">Pilih Kisaran Penghasilan</option>
                        @foreach($penghasilanOptions as $opt)
                        <option value="{{ $opt }}" @selected(old('ayah_income', $father?->income_range) === $opt)>{{ $opt }}</option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-red-600 font-semibold mt-1"
                        x-show="err3('ayah_income') && ayahStatus !== 'deceased'" x-text="err3('ayah_income')">
                    </p>
                </div>

                {{-- Alamat --}}
                <div>
                    <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Alamat Ayah (jika berbeda)</label>
                    <textarea name="ayah_address" rows="2"
                        placeholder="Isi alamat lengkap ayah jika berbeda dengan alamat domisili siswa"
                        :disabled="ayahStatus === 'deceased'"
                        :class="ayahStatus === 'deceased'
                            ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200 focus:ring-0'
                            : (err3('ayah_address')
                                ? 'border-red-400 bg-red-50/50'
                                : 'border-gray-200 bg-gray-50 focus:border-blue-400 focus:ring-blue-100')"
                        class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px] resize-none">{{ old('ayah_address', $father?->address) }}</textarea>
                    <p class="text-[10px]"
                        x-show="!err3('ayah_address') || ayahStatus === 'deceased'"
                        :class="ayahStatus === 'deceased' ? 'text-gray-300' : 'text-gray-400'">
                        Alamat harus diisi sesui Kartu Keluarga (KK) atau Kartu Tanda Penduduk (KTP)
                    </p>
                    <p class="text-[11px] text-red-600 font-semibold mt-1"
                        x-show="err3('ayah_address') && ayahStatus !== 'deceased'" x-text="err3('ayah_address')">
                    </p>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════ --}}
        {{-- SECTION IBU                                                    --}}
        {{-- ═══════════════════════════════════════════════════════════════ --}}
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200"
            x-data="{ ibuStatus: '{{ old('ibu_status', $mother?->living_status ?? 'alive') }}' }">

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

            <div class="px-6 py-5 space-y-4">
                {{-- Nama Lengkap Ibu --}}
                <div>
                    <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">
                        Nama Lengkap Ibu <span class="text-primary">*</span>
                    </label>
                    <input type="text" name="ibu_name"
                        placeholder="Contoh: Siti Aminah, S.E"
                        value="{{ old('ibu_name', $mother?->name) }}"
                        :class="err3('ibu_name')
                            ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100'
                            : 'border-gray-200 bg-gray-50 focus:border-pink-400 focus:ring-pink-100'"
                        class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px] font-medium text-[#080C1A]">
                    <p class="text-[11px] text-red-600 font-semibold mt-1"
                        x-show="err3('ibu_name')" x-text="err3('ibu_name')">
                    </p>
                </div>

                {{-- Status & NIK --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">
                            Status Ibu <span class="text-primary">*</span>
                        </label>
                        <select x-model="ibuStatus" name="ibu_status"
                            :class="err3('ibu_status')
                                ? 'border-red-400 bg-red-50/50'
                                : 'border-gray-200 bg-gray-50 focus:border-pink-400 focus:ring-pink-100'"
                            class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                            <option value="alive">✅ Masih Hidup</option>
                            <option value="deceased">🕊️ Meninggal</option>
                        </select>
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err3('ibu_status')" x-text="err3('ibu_status')">
                        </p>
                    </div>
                    <div>
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">NIK Ibu</label>
                        <input type="text" name="ibu_nik" maxlength="16" placeholder="16 digit NIK"
                            value="{{ old('ibu_nik', $mother?->nik) }}"
                            :disabled="ibuStatus === 'deceased'"
                            :class="ibuStatus === 'deceased'
                                ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200 focus:ring-0'
                                : (err3('ibu_nik')
                                    ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100'
                                    : 'border-gray-200 bg-gray-50 focus:border-pink-400 focus:ring-pink-100')"
                            class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px]">
                        <p class="text-[10px] mt-1"
                            x-show="!err3('ibu_nik')"
                            :class="ibuStatus === 'deceased' ? 'text-gray-300' : 'text-gray-400'">16 digit angka (sesuai KTP)</p>
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err3('ibu_nik') && ibuStatus !== 'deceased'" x-text="err3('ibu_nik')">
                        </p>
                    </div>
                </div>

                {{-- Tahun Lahir & No. Telepon --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Tahun Lahir Ibu</label>
                        <input type="text" name="ibu_birth_year" maxlength="4" placeholder="Contoh: 1975"
                            value="{{ old('ibu_birth_year', $father?->birth_year) }}"

                            {{-- 1. Tambahkan attribute disabled dinamis dari Alpine.js --}}
                            :disabled="ibuStatus === 'deceased'"

                            {{-- 2. Sesuaikan kelas kondisi warna agar berubah abu-abu saat dinonaktifkan --}}
                            :class="ibuStatus === 'deceased'
                                    ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200 focus:ring-0'
                                    : (err3('ibu_birth_year')
                                        ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100'
                                        : 'border-gray-200 bg-gray-50 focus:border-blue-400 focus:ring-blue-100')"
                            class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px]">

                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err3('ibu_birth_year') && ibuStatus !== 'deceased'" x-text="err3('ibu_birth_year')">
                        </p>
                    </div>
                    <div>
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">No. Telepon / HP Ibu</label>
                        <div class="flex rounded-xl overflow-hidden">
                            <span class="inline-flex items-center px-3 border border-r-0 border-gray-200 rounded-l-xl text-sm font-bold bg-gray-100 text-[#6A7686]"
                                :class="ibuStatus === 'deceased' && 'text-gray-400 bg-gray-100'">+62</span>
                            <input type="tel" name="ibu_phone" placeholder="81234567890"
                                value="{{ old('ibu_phone', $mother?->phone_number) }}"
                                :disabled="ibuStatus === 'deceased'"
                                :class="ibuStatus === 'deceased'
                                    ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200 focus:ring-0'
                                    : (err3('ibu_phone')
                                        ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100'
                                        : 'border-gray-200 bg-gray-50 focus:border-pink-400 focus:ring-pink-100')"
                                class="flex-1 min-w-0 px-4 py-2.5 rounded-r-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px]">
                        </div>
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err3('ibu_phone') && ibuStatus !== 'deceased'" x-text="err3('ibu_phone')">
                        </p>
                    </div>
                </div>

                {{-- Pendidikan & Pekerjaan --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Pendidikan Terakhir Ibu</label>
                        <select name="ibu_education"
                            :disabled="ibuStatus === 'deceased'"
                            :class="ibuStatus === 'deceased'
                                ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200 focus:ring-0'
                                : (err3('ibu_education')
                                    ? 'border-red-400 bg-red-50/50'
                                    : 'border-gray-200 bg-gray-50 focus:border-pink-400 focus:ring-pink-100')"
                            class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                            <option value="">Pilih Pendidikan</option>
                            @foreach($pendidikanOptions as $opt)
                            <option value="{{ $opt }}" @selected(old('ibu_education', $mother?->education) === $opt)>{{ $opt }}</option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err3('ibu_education') && ibuStatus !== 'deceased'" x-text="err3('ibu_education')">
                        </p>
                    </div>
                    <div>
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Pekerjaan Ibu</label>
                        <select name="ibu_job"
                            :disabled="ibuStatus === 'deceased'"
                            :class="ibuStatus === 'deceased'
                                ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200 focus:ring-0'
                                : (err3('ibu_job')
                                    ? 'border-red-400 bg-red-50/50'
                                    : 'border-gray-200 bg-gray-50 focus:border-pink-400 focus:ring-pink-100')"
                            class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                            <option value="">Pilih Pekerjaan</option>
                            <option value="Ibu Rumah Tangga" @selected(old('ibu_job', $mother?->occupation) === 'Ibu Rumah Tangga')>Ibu Rumah Tangga</option>
                            @foreach($pekerjaanOptions as $opt)
                            <option value="{{ $opt }}" @selected(old('ibu_job', $mother?->occupation) === $opt)>{{ $opt }}</option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err3('ibu_job') && ibuStatus !== 'deceased'" x-text="err3('ibu_job')">
                        </p>
                    </div>
                </div>

                {{-- Penghasilan --}}
                <div>
                    <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Penghasilan per Bulan</label>
                    <select name="ibu_income"
                        :disabled="ibuStatus === 'deceased'"
                        :class="ibuStatus === 'deceased'
                            ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200 focus:ring-0'
                            : (err3('ibu_income')
                                ? 'border-red-400 bg-red-50/50'
                                : 'border-gray-200 bg-gray-50 focus:border-pink-400 focus:ring-pink-100')"
                        class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                        <option value="">Pilih Kisaran Penghasilan</option>
                        @foreach($penghasilanOptions as $opt)
                        <option value="{{ $opt }}" @selected(old('ibu_income', $mother?->income_range) === $opt)>{{ $opt }}</option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-red-600 font-semibold mt-1"
                        x-show="err3('ibu_income') && ibuStatus !== 'deceased'" x-text="err3('ibu_income')">
                    </p>
                </div>

                {{-- Alamat --}}
                <div>
                    <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Alamat Ibu (jika berbeda)</label>
                    <textarea name="ibu_address" rows="2"
                        placeholder="Isi alamat lengkap ibu jika berbeda dengan alamat domisili siswa"
                        :disabled="ibuStatus === 'deceased'"
                        :class="ibuStatus === 'deceased'
                            ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200 focus:ring-0'
                            : (err3('ibu_address')
                                ? 'border-red-400 bg-red-50/50'
                                : 'border-gray-200 bg-gray-50 focus:border-pink-400 focus:ring-pink-100')"
                        class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px] resize-none">{{ old('ibu_address', $mother?->address) }}</textarea>
                    <p class="text-[10px]"
                        x-show="!err3('ibu_address') || ibuStatus === 'deceased'"
                        :class="ibuStatus === 'deceased' ? 'text-gray-300' : 'text-gray-400'">
                        Alamat harus diisi sesui Kartu Keluarga (KK) atau Kartu Tanda Penduduk (KTP)
                    </p>
                    <p class="text-[11px] text-red-600 font-semibold mt-1"
                        x-show="err3('ibu_address') && ibuStatus !== 'deceased'" x-text="err3('ibu_address')">
                    </p>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════ --}}
        {{-- SECTION WALI (COLLAPSIBLE, OPSIONAL)                          --}}
        {{-- ═══════════════════════════════════════════════════════════════ --}}
        <div class="bg-white rounded-2xl border overflow-hidden shadow-sm transition-all duration-200"
            :class="showWali ? 'border-violet-300 shadow-md' : 'border-gray-200'"
            x-data="{ waliStatus: '{{ old('wali_status', $guardian?->living_status ?? 'alive') }}' }">

            {{-- Header toggle (Klik di sini untuk collapse/expand) --}}
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 cursor-pointer hover:bg-gray-100/80 transition-colors select-none"
                @click="showWali = !showWali">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center transition-colors"
                            :class="showWali ? 'bg-violet-500' : 'bg-gray-400'">
                            <i class="fa-solid fa-user-shield text-white text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold transition-colors"
                                :class="showWali ? 'text-violet-800' : 'text-gray-600'">Data Wali</h3>
                            <p class="text-[11px] transition-colors"
                                :class="showWali ? 'text-violet-600' : 'text-gray-400'">
                                Isi jika siswa tidak tinggal bersama orang tua kandung
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        {{-- Badge error wali --}}
                        <template x-if="!showWali && (err3('wali_name') || err3('wali_nik') || err3('wali_phone'))">
                            <span class="text-[10px] font-bold text-red-600 bg-red-100 px-2 py-1 rounded-full flex items-center gap-1">
                                <i class="fa-solid fa-triangle-exclamation"></i> Ada error
                            </span>
                        </template>
                        <span class="text-[10px] font-bold text-gray-400 bg-gray-200 px-2 py-1 rounded-full">Opsional</span>
                        <i class="fa-solid text-sm transition-transform duration-200"
                            :class="showWali ? 'fa-chevron-up text-violet-500' : 'fa-chevron-down text-gray-400'"></i>
                    </div>
                </div>
            </div>

            {{-- Body Form Wali --}}
            <div x-show="showWali" x-collapse class="px-6 py-5 space-y-4">

                {{-- Nama Lengkap Wali --}}
                <div>
                    <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">
                        Nama Lengkap Wali <span class="text-primary">*</span>
                    </label>
                    <input type="text" name="wali_name"
                        placeholder="Contoh: H. Rahmat Hidayat, S.H"
                        value="{{ old('wali_name', $guardian?->name) }}"
                        :class="err3('wali_name')
                            ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100'
                            : 'border-gray-200 bg-gray-50 focus:border-violet-400 focus:ring-violet-100'"
                        class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px]">
                    <p class="text-[11px] text-red-600 font-semibold mt-1"
                        x-show="err3('wali_name')" x-text="err3('wali_name')">
                    </p>
                </div>

                {{-- Tahun Lahir & NIK --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Tahun Lahir Wali</label>
                        <input type="text" name="wali_birth_year" maxlength="4" placeholder="Contoh: 1970"
                            value="{{ old('wali_birth_year', $guardian?->birth_year) }}"
                            :class="err3('wali_birth_year')
                                ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100'
                                : 'border-gray-200 bg-gray-50 focus:border-violet-400 focus:ring-violet-100'"
                            class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px]">
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err3('wali_birth_year')" x-text="err3('wali_birth_year')">
                        </p>
                    </div>
                    <div>
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">NIK Wali</label>
                        <input type="text" name="wali_nik" maxlength="16" placeholder="16 digit NIK"
                            value="{{ old('wali_nik', $guardian?->nik) }}"
                            :disabled="waliStatus === 'deceased'"
                            :class="waliStatus === 'deceased'
                                ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200 focus:ring-0'
                                : (err3('wali_nik')
                                    ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100'
                                    : 'border-gray-200 bg-gray-50 focus:border-violet-400 focus:ring-violet-100')"
                            class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px]">
                        <p class="text-[10px] mt-1"
                            x-show="!err3('wali_nik')"
                            :class="waliStatus === 'deceased' ? 'text-gray-300' : 'text-gray-400'">16 digit angka (sesuai KTP)</p>
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err3('wali_nik') && waliStatus !== 'deceased'" x-text="err3('wali_nik')">
                        </p>
                    </div>
                </div>

                {{-- Pendidikan & No Telepon --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Pendidikan Terakhir Wali</label>
                        <select name="wali_education"
                            :disabled="waliStatus === 'deceased'"
                            :class="waliStatus === 'deceased'
                                ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200 focus:ring-0'
                                : (err3('wali_education')
                                    ? 'border-red-400 bg-red-50/50'
                                    : 'border-gray-200 bg-gray-50 focus:border-violet-400 focus:ring-violet-100')"
                            class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                            <option value="">Pilih Pendidikan</option>
                            @foreach($pendidikanOptions as $opt)
                            <option value="{{ $opt }}" @selected(old('wali_education', $guardian?->education) === $opt)>{{ $opt }}</option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err3('wali_education') && waliStatus !== 'deceased'" x-text="err3('wali_education')">
                        </p>
                    </div>
                    <div>
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">No. Telepon / HP Wali</label>
                        <div class="flex rounded-xl overflow-hidden">
                            <span class="inline-flex items-center px-3 border border-r-0 border-gray-200 rounded-l-xl text-sm font-bold bg-gray-100 text-[#6A7686]"
                                :class="waliStatus === 'deceased' && 'text-gray-400 bg-gray-100'">+62</span>
                            <input type="tel" name="wali_phone" placeholder="81234567890"
                                value="{{ old('wali_phone', $guardian?->phone_number) }}"
                                :disabled="waliStatus === 'deceased'"
                                :class="waliStatus === 'deceased'
                                    ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200 focus:ring-0'
                                    : (err3('wali_phone')
                                        ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100'
                                        : 'border-gray-200 bg-gray-50 focus:border-violet-400 focus:ring-violet-100')"
                                class="flex-1 min-w-0 px-4 py-2.5 rounded-r-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px]">
                        </div>
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err3('wali_phone') && waliStatus !== 'deceased'" x-text="err3('wali_phone')">
                        </p>
                    </div>
                </div>

                {{-- Pekerjaan & Penghasilan --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Pekerjaan Wali</label>
                        <select name="wali_job"
                            :disabled="waliStatus === 'deceased'"
                            :class="waliStatus === 'deceased'
                                ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200 focus:ring-0'
                                : (err3('wali_job')
                                    ? 'border-red-400 bg-red-50/50'
                                    : 'border-gray-200 bg-gray-50 focus:border-violet-400 focus:ring-violet-100')"
                            class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                            <option value="">Pilih Pekerjaan</option>
                            @foreach($pekerjaanOptions as $opt)
                            <option value="{{ $opt }}" @selected(old('wali_job', $guardian?->occupation) === $opt)>{{ $opt }}</option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err3('wali_job') && waliStatus !== 'deceased'" x-text="err3('wali_job')">
                        </p>
                    </div>
                    <div>
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Penghasilan per Bulan</label>
                        <select name="wali_income"
                            :disabled="waliStatus === 'deceased'"
                            :class="waliStatus === 'deceased'
                                ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200 focus:ring-0'
                                : (err3('wali_income')
                                    ? 'border-red-400 bg-red-50/50'
                                    : 'border-gray-200 bg-gray-50 focus:border-violet-400 focus:ring-violet-100')"
                            class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                            <option value="">Pilih Kisaran Penghasilan</option>
                            @foreach($penghasilanOptions as $opt)
                            <option value="{{ $opt }}" @selected(old('wali_income', $guardian?->income_range) === $opt)>{{ $opt }}</option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err3('wali_income') && waliStatus !== 'deceased'" x-text="err3('wali_income')">
                        </p>
                    </div>
                </div>

                {{-- Alamat Wali --}}
                <div>
                    <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Alamat Wali</label>
                    <textarea name="wali_address" rows="2" placeholder="Isi alamat lengkap wali"
                        :disabled="waliStatus === 'deceased'"
                        :class="waliStatus === 'deceased'
                            ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200 focus:ring-0'
                            : (err3('wali_address')
                                ? 'border-red-400 bg-red-50/50'
                                : 'border-gray-200 bg-gray-50 focus:border-violet-400 focus:ring-violet-100')"
                        class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px] resize-none">{{ old('wali_address', $guardian?->address) }}</textarea>
                    <p class="text-[10px]"
                        x-show="!err3('wali_address') || waliStatus === 'deceased'"
                        :class="waliStatus === 'deceased' ? 'text-gray-300' : 'text-gray-400'">
                        Alamat harus diisi sesui Kartu Keluarga (KK) atau Kartu Tanda Penduduk (KTP)
                    </p>
                    <p class="text-[11px] text-red-600 font-semibold mt-1"
                        x-show="err3('wali_address') && waliStatus !== 'deceased'" x-text="err3('wali_address')">
                    </p>
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

    {{-- ── Footer Navigasi Step 3 ──────────────────────────────────────── --}}
    <div class="px-8 py-5 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between rounded-b-[20px]">
        <button type="button" @click="step--"
            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-[#6A7686] border border-gray-200 rounded-full hover:border-[#080C1A] hover:text-[#080C1A] transition-all">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </button>
        <div class="flex items-center gap-3">
            <button type="button"
                hx-post="{{ route('biodata.step3') }}"
                hx-indicator="#biodata-form"
                hx-swap="none"
                @htmx:after-request="
                    const xhr = $event.detail.xhr;
                    if (xhr.status === 422) {
                        setErrors3(xhr);
                    } else if (xhr.status === 200) {
                        setErrors3(xhr);
                        let r = JSON.parse(xhr.response);
                        if (r.success) step = r.step;
                    }
                "
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-full hover:bg-primary-hover shadow-lg shadow-primary/30 transition-all">
                <span hx-dis-indicator>
                    Berikutnya <i class="fa-solid fa-arrow-right"></i>
                </span>
                <span id="next-indicator" class="htmx-indicator gap-2">
                    <i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...
                </span>
            </button>
        </div>
    </div>
</div>
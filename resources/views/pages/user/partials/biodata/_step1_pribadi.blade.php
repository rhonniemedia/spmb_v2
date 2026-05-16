<!-- STEP 1 — DATA PRIBADI -->

{{--
|==========================================================================
| STEP 1 — DATA PRIBADI
|==========================================================================
| Perubahan (dari versi sebelumnya):
|   + Error validasi: ditangkap dari JSON 422 HTMX via @htmx:after-request
|   + Tiap field punya :class binding border merah saat error
|   + Error summary box muncul di atas form saat ada error
|   + Inline pesan error di bawah tiap field
|==========================================================================
--}}

{{--
    ╔══════════════════════════════════════════════════════════════════════╗
    ║  CARA KERJA ERROR VALIDASI                                          ║
    ║                                                                      ║
    ║  1. HTMX POST → controller $request->validate() gagal               ║
    ║  2. Laravel return HTTP 422 + JSON:                                  ║
    ║     { "message": "...", "errors": { "nik": ["NIK wajib diisi."] } } ║
    ║  3. @htmx:after-request mendeteksi status 422                       ║
    ║  4. errors1 diisi dari JSON.parse(xhr.response).errors              ║
    ║  5. Alpine x-show / :class binding bereaksi otomatis                ║
    ╚══════════════════════════════════════════════════════════════════════╝
--}}

<div x-show="step === 1"
    x-data="{
        errors1: {},
        hasError1: false,

        setErrors1(xhr) {
            if (xhr.status === 422) {
                try {
                    const body = JSON.parse(xhr.response);
                    this.errors1   = body.errors ?? {};
                    this.hasError1 = Object.keys(this.errors1).length > 0;
                } catch(e) {
                    this.errors1   = {};
                    this.hasError1 = false;
                }
            } else {
                this.errors1   = {};
                this.hasError1 = false;
            }
        },

        /* helper: ambil pesan pertama dari field tertentu */
        err1(field) {
            return this.errors1[field]?.[0] ?? null;
        }
    }"
    class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">

    {{-- Header --}}
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

        {{-- ── ERROR SUMMARY BOX ─────────────────────────────────────── --}}
        {{-- Muncul di atas form saat ada error dari server --}}
        <div x-show="hasError1"
            x-transition
            class="flex gap-3 items-start bg-red-50 border border-red-200 rounded-2xl px-4 py-3.5">
            <i class="fa-solid fa-circle-exclamation text-red-500 text-base mt-0.5 flex-shrink-0"></i>
            <div>
                <p class="text-sm font-black text-red-700 mb-1">Harap periksa kembali isian Anda:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    <template x-for="(msgs, field) in errors1" :key="field">
                        <template x-for="msg in msgs" :key="msg">
                            <li class="text-sm text-red-600" x-text="msg"></li>
                        </template>
                    </template>
                </ul>
            </div>
        </div>

        {{-- Info box --}}
        <div class="flex gap-3 items-start bg-blue-50 border border-blue-200 rounded-2xl px-4 py-3.5">
            <i class="fa-solid fa-circle-info text-blue-500 text-base mt-0.5 flex-shrink-0"></i>
            <p class="text-sm font-medium text-blue-800 leading-relaxed">
                Isi data sesuai dengan <strong>Kartu Keluarga (KK)</strong> atau <strong>Akta Kelahiran</strong>.
                Pastikan tidak ada kesalahan penulisan nama.
            </p>
        </div>

        <!-- ── GROUP 1 — IDENTITAS DIRI ───────────────────────────────── -->
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

                {{-- NIK & NISN --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">
                            NIK <span class="text-primary">*</span>
                        </label>
                        <input
                            type="text"
                            name="nik"
                            maxlength="16"
                            placeholder="16 digit NIK"
                            value="{{ old('nik', $personalData?->nik) }}"
                            :class="err1('nik') ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100' : 'border-gray-200 bg-white focus:border-gray-400 focus:ring-gray-100'"
                            class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px] font-medium text-[#080C1A]">
                        <p class="text-[10px] text-gray-400 mt-1" x-show="!err1('nik')">16 digit angka (sesuai KTP/KK)</p>
                        <p class="text-[11px] text-red-600 font-semibold mt-1 flex items-center gap-1"
                            x-show="err1('nik')" x-text="err1('nik')">
                        </p>
                    </div>
                    <div>
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">
                            NISN <span class="text-primary">*</span>
                        </label>
                        <input
                            type="text"
                            name="nisn"
                            maxlength="10"
                            placeholder="Nomor Induk Siswa Nasional"
                            value="{{ old('nisn', $personalData?->nisn) }}"
                            :class="err1('nisn') ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100' : 'border-gray-200 bg-white focus:border-gray-400 focus:ring-gray-100'"
                            class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px]">
                        <p class="text-[10px] text-gray-400 mt-1" x-show="!err1('nisn')">10 digit angka</p>
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err1('nisn')" x-text="err1('nisn')">
                        </p>
                    </div>
                </div>

                {{-- Nama Lengkap & Nama Panggilan --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">
                            Nama Lengkap <span class="text-primary">*</span>
                        </label>
                        <input
                            type="text"
                            name="full_name"
                            placeholder="Sesuai akta kelahiran, tanpa singkatan"
                            value="{{ old('full_name', $personalData?->full_name) }}"
                            :class="err1('full_name') ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100' : 'border-gray-200 bg-white focus:border-gray-400 focus:ring-gray-100'"
                            class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px]">
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err1('full_name')" x-text="err1('full_name')">
                        </p>
                    </div>
                    <div>
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">
                            Nama Panggilan
                        </label>
                        <input
                            type="text"
                            name="nick_name"
                            placeholder="Nama sehari-hari"
                            value="{{ old('nick_name', $personalData?->nick_name) }}"
                            :class="err1('nick_name') ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100' : 'border-gray-200 bg-white focus:border-gray-400 focus:ring-gray-100'"
                            class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px]">
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err1('nick_name')" x-text="err1('nick_name')">
                        </p>
                    </div>
                </div>

                {{-- Tempat Lahir & Tanggal Lahir --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">
                            Tempat Lahir <span class="text-primary">*</span>
                        </label>
                        <input
                            type="text"
                            name="pob"
                            placeholder="Contoh: Bengkulu, Jakarta, dll"
                            value="{{ old('pob', $personalData?->pob) }}"
                            :class="err1('pob') ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100' : 'border-gray-200 bg-white focus:border-gray-400 focus:ring-gray-100'"
                            class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px]">
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err1('pob')" x-text="err1('pob')">
                        </p>
                    </div>
                    <div>
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">
                            Tanggal Lahir <span class="text-primary">*</span>
                        </label>
                        <input
                            type="date"
                            name="dob"
                            value="{{ old('dob', $personalData?->dob ? \Carbon\Carbon::parse($personalData->dob)->format('Y-m-d') : '') }}"
                            :class="err1('dob') ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100' : 'border-gray-200 bg-white focus:border-gray-400 focus:ring-gray-100'"
                            class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px]">
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err1('dob')" x-text="err1('dob')">
                        </p>
                    </div>
                </div>

                {{-- Jenis Kelamin & Agama --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">
                            Jenis Kelamin <span class="text-primary">*</span>
                        </label>
                        <select
                            name="gender"
                            :class="err1('gender') ? 'border-red-400 bg-red-50/50' : 'border-gray-200 bg-white'"
                            class="w-full px-4 py-2.5 rounded-xl border focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                            <option value="">Pilih</option>
                            <option value="L" @selected(old('gender', $personalData?->gender) === 'L')>Laki-laki</option>
                            <option value="P" @selected(old('gender', $personalData?->gender) === 'P')>Perempuan</option>
                        </select>
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err1('gender')" x-text="err1('gender')">
                        </p>
                    </div>
                    <div>
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">
                            Agama <span class="text-primary">*</span>
                        </label>
                        <select
                            name="religion"
                            :class="err1('religion') ? 'border-red-400 bg-red-50/50' : 'border-gray-200 bg-white'"
                            class="w-full px-4 py-2.5 rounded-xl border focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                            <option value="">Pilih agama</option>
                            @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $agama)
                            <option value="{{ $agama }}" @selected(old('religion', $personalData?->religion) === $agama)>{{ $agama }}</option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err1('religion')" x-text="err1('religion')">
                        </p>
                    </div>
                </div>

                {{-- Golongan Darah --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">
                            Golongan Darah
                        </label>
                        <select
                            name="blood_type"
                            :class="err1('blood_type') ? 'border-red-400 bg-red-50/50' : 'border-gray-200 bg-white'"
                            class="w-full px-4 py-2.5 rounded-xl border focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                            <option value="">Pilih / Tidak tahu</option>
                            @foreach(['A','B','AB','O'] as $gol)
                            <option value="{{ $gol }}" @selected(old('blood_type', $personalData?->blood_type) === $gol)>{{ $gol }}</option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err1('blood_type')" x-text="err1('blood_type')">
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <!-- ── GROUP 2 — DATA KELUARGA ────────────────────────────────── -->
        <div class="relative rounded-2xl border-2 border-dashed transition-all duration-300 border-gray-300 bg-gray-50/30 shadow-sm">

            <div class="px-6 py-4 border-b border-dashed border-gray-200 bg-gray-100/50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-gray-500 flex items-center justify-center shadow-sm">
                        <i class="fa-solid fa-people-group text-white text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-gray-700">Data Keluarga</h3>
                        <p class="text-[11px] text-gray-500">Informasi posisi dalam keluarga</p>
                    </div>
                    <div class="ml-auto">
                        <span class="text-[10px] font-bold text-white bg-gray-500 px-2.5 py-1 rounded-full">
                            <i class="fa-regular fa-circle-check mr-1"></i> Wajib
                        </span>
                    </div>
                </div>
            </div>

            <div class="px-6 py-5 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">
                            Anak Ke- <span class="text-primary">*</span>
                        </label>
                        <input
                            type="number"
                            name="child_order"
                            min="1" max="20"
                            placeholder="Contoh: 2"
                            value="{{ old('child_order', $personalData?->child_order) }}"
                            :class="err1('child_order') ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100' : 'border-gray-200 bg-white focus:border-gray-400 focus:ring-gray-100'"
                            class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px]">
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err1('child_order')" x-text="err1('child_order')">
                        </p>
                    </div>
                    <div>
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">
                            Jumlah Saudara Kandung <span class="text-primary">*</span>
                        </label>
                        <input
                            type="number"
                            name="number_of_siblings"
                            min="0" max="20"
                            placeholder="Contoh: 3"
                            value="{{ old('number_of_siblings', $personalData?->number_of_siblings) }}"
                            :class="err1('number_of_siblings') ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100' : 'border-gray-200 bg-white focus:border-gray-400 focus:ring-gray-100'"
                            class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px]">
                        <p class="text-[10px] text-gray-400 mt-1" x-show="!err1('number_of_siblings')">Tidak termasuk diri sendiri</p>
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err1('number_of_siblings')" x-text="err1('number_of_siblings')">
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── GROUP 3 — KONDISI KHUSUS ──────────────────────────────── -->
        <div class="relative rounded-2xl border-2 border-dashed transition-all duration-300 border-gray-300 bg-gray-50/30 shadow-sm"
            x-data="{ hasSpecial: '{{ old('is_special_condition', $personalData?->is_special_condition ?? 'no') }}' }">

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
                <div>
                    <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">
                        Apakah memiliki kondisi/kebutuhan khusus?
                    </label>
                    <div class="flex gap-3 flex-wrap pt-1">
                        <label class="flex items-center gap-2 px-4 py-2 border-2 border-gray-200 rounded-full cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary/5 has-[:checked]:text-primary text-sm font-semibold text-[#6A7686] transition-all bg-white">
                            <input type="radio" name="is_special_condition" value="no" x-model="hasSpecial" class="accent-primary w-3.5 h-3.5">
                            <i class="fa-solid fa-check-circle text-sm"></i> Tidak Ada
                        </label>
                        <label class="flex items-center gap-2 px-4 py-2 border-2 border-gray-200 rounded-full cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary/5 has-[:checked]:text-primary text-sm font-semibold text-[#6A7686] transition-all bg-white">
                            <input type="radio" name="is_special_condition" value="yes" x-model="hasSpecial" class="accent-primary w-3.5 h-3.5">
                            <i class="fa-solid fa-circle-exclamation text-sm"></i> Ada
                        </label>
                    </div>
                    {{-- Error untuk radio is_special_condition (jarang tapi bisa) --}}
                    <p class="text-[11px] text-red-600 font-semibold mt-1"
                        x-show="err1('is_special_condition')" x-text="err1('is_special_condition')">
                    </p>
                </div>

                <div x-show="hasSpecial === 'yes'" x-transition.duration.200ms>
                    <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Jenis Kondisi</label>
                    <select
                        name="special_condition_type"
                        :class="err1('special_condition_type') ? 'border-red-400 bg-red-50/50' : 'border-gray-200 bg-white'"
                        class="w-full px-4 py-2.5 rounded-xl border focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                        <option value="">Pilih jenis kondisi</option>
                        @foreach(['tunanetra','tunarungu','tunawicara','tunadaksa','tunagrahita','autisme','lainnya'] as $kondisi)
                        <option value="{{ $kondisi }}" @selected(old('special_condition_type', $personalData?->special_condition_type) === $kondisi)>
                            {{ ucfirst($kondisi) }}
                        </option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-red-600 font-semibold mt-1"
                        x-show="err1('special_condition_type')" x-text="err1('special_condition_type')">
                    </p>
                </div>

                <div x-show="hasSpecial === 'yes'" x-transition.duration.200ms>
                    <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">Keterangan Tambahan</label>
                    <textarea
                        name="condition_description"
                        rows="2"
                        placeholder="Jelaskan kondisi lebih detail jika diperlukan..."
                        :class="err1('condition_description') ? 'border-red-400 bg-red-50/50' : 'border-gray-200 bg-white'"
                        class="w-full px-4 py-2.5 rounded-xl border focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px] resize-none">{{ old('condition_description', $personalData?->condition_description) }}</textarea>
                    <p class="text-[11px] text-red-600 font-semibold mt-1"
                        x-show="err1('condition_description')" x-text="err1('condition_description')">
                    </p>
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

    {{-- ── Footer Navigasi Step 1 ──────────────────────────────────────── --}}
    {{--
        @htmx:after-request:
        - Jika sukses (r.success)  → step++ ke 2
        - Jika 422 (validasi gagal) → setErrors1(xhr) → Alpine update error binding
        - Jika error lain           → setErrors1(xhr) (errors1 akan kosong)
    --}}
    <div class="px-8 py-5 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between rounded-b-[20px]">
        <button type="button"
            hx-post="{{ route('biodata.draft') }}"
            hx-include="#biodata-form"
            hx-swap="none"
            @htmx:after-request="saveDraft()"
            class="text-sm font-semibold text-[#6A7686] flex items-center gap-1.5 hover:text-primary transition-colors">
            <i class="fa-regular fa-floppy-disk"></i> Simpan Draft
        </button>

        <button type="button"
            hx-post="{{ route('biodata.step1') }}"
            hx-indicator="#biodata-form"
            hx-swap="none"
            @htmx:after-request="
                const xhr = $event.detail.xhr;
                if (xhr.status === 422) {
                    setErrors1(xhr);   // panggil helper, biarkan Alpine urus scope
                } else if (xhr.status === 200) {
                    setErrors1(xhr);
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

</div>{{-- /step 1 --}}
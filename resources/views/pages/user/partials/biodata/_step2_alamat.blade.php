<!-- STEP 2 — ALAMAT -->

{{--
|==========================================================================
| STEP 2 — DATA ALAMAT
|==========================================================================
| Perubahan (dari versi sebelumnya):
|   + Error validasi: ditangkap dari JSON 422 HTMX via @htmx:after-request
|   + Tiap field punya :class binding border merah saat error
|   + Error summary box muncul di atas form saat ada error
|   + Inline pesan error di bawah tiap field
|==========================================================================
--}}

<div x-show="step === 2"
    x-data="{
        errors2: {},
        hasError2: false,
        residenceType: '{{ old('residence_type', $personalData?->residence_type ?? '') }}',

        setErrors2(xhr) {
            if (xhr.status === 422) {
                try {
                    const body = JSON.parse(xhr.response);
                    this.errors2   = body.errors ?? {};
                    this.hasError2 = Object.keys(this.errors2).length > 0;
                } catch(e) {
                    this.errors2   = {};
                    this.hasError2 = false;
                }
            } else {
                this.errors2   = {};
                this.hasError2 = false;
            }
        },

        err2(field) {
            return this.errors2[field]?.[0] ?? null;
        }
    }"
    class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">

    {{-- Header --}}
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

        {{-- ── ERROR SUMMARY BOX ─────────────────────────────────────── --}}
        <div x-show="hasError2"
            x-transition
            class="flex gap-3 items-start bg-red-50 border border-red-200 rounded-2xl px-4 py-3.5">
            <i class="fa-solid fa-circle-exclamation text-red-500 text-base mt-0.5 flex-shrink-0"></i>
            <div>
                <p class="text-sm font-black text-red-700 mb-1">Harap periksa kembali isian Anda:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    <template x-for="(msgs, field) in errors2" :key="field">
                        <template x-for="msg in msgs" :key="msg">
                            <li class="text-sm text-red-600" x-text="msg"></li>
                        </template>
                    </template>
                </ul>
            </div>
        </div>

        <!-- ── CARD ALAMAT DOMISILI ───────────────────────────────────── -->
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
                    <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">
                        Alamat Jalan <span class="text-primary">*</span>
                    </label>
                    <input
                        type="text"
                        name="address"
                        placeholder="Nama jalan dan nomor rumah"
                        value="{{ old('address', $personalData?->address) }}"
                        :class="err2('address') ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100' : 'border-gray-200 bg-white focus:border-gray-400 focus:ring-gray-100'"
                        class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px] font-medium text-[#080C1A]">
                    <p class="text-[11px] text-red-600 font-semibold mt-1"
                        x-show="err2('address')" x-text="err2('address')">
                    </p>
                </div>

                {{-- RT + RW + Desa/Kelurahan --}}
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-3">
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">RT</label>
                        <input
                            type="text"
                            name="rt"
                            placeholder="001"
                            maxlength="3"
                            value="{{ old('rt', $personalData?->rt) }}"
                            :class="err2('rt') ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100' : 'border-gray-200 bg-white focus:border-gray-400 focus:ring-gray-100'"
                            class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px]">
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err2('rt')" x-text="err2('rt')">
                        </p>
                    </div>
                    <div class="md:col-span-3">
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">RW</label>
                        <input
                            type="text"
                            name="rw"
                            placeholder="002"
                            maxlength="3"
                            value="{{ old('rw', $personalData?->rw) }}"
                            :class="err2('rw') ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100' : 'border-gray-200 bg-white focus:border-gray-400 focus:ring-gray-100'"
                            class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px]">
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err2('rw')" x-text="err2('rw')">
                        </p>
                    </div>
                    <div class="md:col-span-6">
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">
                            Desa / Kelurahan
                        </label>
                        <input
                            type="text"
                            name="village"
                            placeholder="Nama desa/kelurahan"
                            value="{{ old('village', $personalData?->village) }}"
                            :class="err2('village') ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100' : 'border-gray-200 bg-white focus:border-gray-400 focus:ring-gray-100'"
                            class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px]">
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err2('village')" x-text="err2('village')">
                        </p>
                    </div>
                </div>

                {{-- Kecamatan + Kabupaten --}}
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-6">
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">
                            Kecamatan <span class="text-primary">*</span>
                        </label>
                        <input
                            type="text"
                            name="district"
                            placeholder="Nama kecamatan"
                            value="{{ old('district', $personalData?->district) }}"
                            :class="err2('district') ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100' : 'border-gray-200 bg-white focus:border-gray-400 focus:ring-gray-100'"
                            class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px]">
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err2('district')" x-text="err2('district')">
                        </p>
                    </div>
                    <div class="md:col-span-6">
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">
                            Kabupaten / Kota <span class="text-primary">*</span>
                        </label>
                        <input
                            type="text"
                            name="regency"
                            placeholder="Nama kabupaten/kota"
                            value="{{ old('regency', $personalData?->regency) }}"
                            :class="err2('regency') ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100' : 'border-gray-200 bg-white focus:border-gray-400 focus:ring-gray-100'"
                            class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px]">
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err2('regency')" x-text="err2('regency')">
                        </p>
                    </div>
                </div>

                {{-- Provinsi + Kode Pos --}}
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-6">
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">
                            Provinsi <span class="text-primary">*</span>
                        </label>
                        <input
                            type="text"
                            name="province"
                            placeholder="Contoh: Jawa Barat, DKI Jakarta, dll"
                            value="{{ old('province', $personalData?->province) }}"
                            :class="err2('province') ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100' : 'border-gray-200 bg-white focus:border-gray-400 focus:ring-gray-100'"
                            class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px]">
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err2('province')" x-text="err2('province')">
                        </p>
                    </div>
                    <div class="md:col-span-6">
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">
                            Kode Pos
                        </label>
                        <input
                            type="text"
                            name="postal_code"
                            placeholder="Contoh: 38111"
                            maxlength="10"
                            value="{{ old('postal_code', $personalData?->postal_code) }}"
                            :class="err2('postal_code') ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100' : 'border-gray-200 bg-white focus:border-gray-400 focus:ring-gray-100'"
                            class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px]">
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err2('postal_code')" x-text="err2('postal_code')">
                        </p>
                    </div>
                </div>

                {{-- No. HP + Email --}}
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-6">
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">
                            Nomor Telepon / HP
                        </label>

                        <div class="flex">
                            <span class="inline-flex items-center px-3 border border-r-0 border-gray-200 rounded-l-xl text-sm font-bold bg-gray-100 text-[#6A7686]">
                                +62
                            </span>
                            <input
                                type="tel"
                                name="phone_number"
                                placeholder="8123456789"
                                maxlength="20"
                                value="{{ old('phone_number', $personalData?->phone_number) }}"
                                :class="err2('phone_number') 
                                ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100' 
                                : 'border-gray-200 bg-gray-50 focus:border-gray-400 focus:ring-gray-100'"
                                class="flex-1 min-w-0 px-4 py-2.5 rounded-r-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px]">
                        </div>
                        <p class="text-[11px] text-red-600 font-semibold mt-1 pl-1"
                            x-show="err2('phone_number')"
                            x-text="err2('phone_number')"
                            x-cloak>
                        </p>
                    </div>

                    <div class="md:col-span-6">
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">
                            Email
                        </label>
                        <input
                            type="email"
                            name="email"
                            placeholder="Contoh: nama@email.com"
                            value="{{ old('email', $personalData?->email) }}"
                            :class="err2('email') ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100' : 'border-gray-200 bg-white focus:border-gray-400 focus:ring-gray-100'"
                            class="w-full px-4 py-2.5 rounded-xl border focus:ring-2 focus:bg-white transition-all outline-none text-[14px]">
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err2('email')" x-text="err2('email')">
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <!-- ── CARD JARAK & TRANSPORTASI ─────────────────────────────── -->
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

                {{-- Jarak + Transportasi --}}
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-6">
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">
                            Jarak Rumah ke Sekolah
                        </label>
                        <select
                            name="distance_to_school"
                            :class="err2('distance_to_school') ? 'border-red-400 bg-red-50/50' : 'border-gray-200 bg-white'"
                            class="w-full px-4 py-2.5 rounded-xl border focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                            <option value="">Pilih kisaran</option>
                            @foreach([
                            'Kurang dari 1 km',
                            '1 – 5 km',
                            '5 – 10 km',
                            '10 – 20 km',
                            'Lebih dari 20 km',
                            ] as $jarak)
                            <option value="{{ $jarak }}" @selected(old('distance_to_school', $personalData?->distance_to_school) === $jarak)>
                                {{ $jarak }}
                            </option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err2('distance_to_school')" x-text="err2('distance_to_school')">
                        </p>
                    </div>
                    <div class="md:col-span-6">
                        <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">
                            Moda Transportasi
                        </label>
                        <select
                            name="transportation"
                            :class="err2('transportation') ? 'border-red-400 bg-red-50/50' : 'border-gray-200 bg-white'"
                            class="w-full px-4 py-2.5 rounded-xl border focus:border-gray-400 focus:ring-2 focus:ring-gray-100 focus:bg-white transition-all outline-none text-[14px] appearance-none">
                            <option value="">Pilih</option>
                            @foreach([
                            'jalan_kaki' => 'Jalan kaki',
                            'sepeda' => 'Sepeda',
                            'sepeda_motor' => 'Sepeda motor',
                            'angkutan_umum' => 'Angkutan umum',
                            'antar_jemput' => 'Antar jemput orang tua/wali',
                            ] as $val => $label)
                            <option value="{{ $val }}" @selected(old('transportation', $personalData?->transportation) === $val)>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-red-600 font-semibold mt-1"
                            x-show="err2('transportation')" x-text="err2('transportation')">
                        </p>
                    </div>
                </div>

                {{-- Jenis Tempat Tinggal — radio prefill via Alpine x-model --}}
                <div>
                    <label class="text-[12px] font-black text-[#6A7686] uppercase tracking-wide block mb-1.5">
                        Jenis Tempat Tinggal
                    </label>
                    <div class="flex gap-3 flex-wrap pt-1">
                        @foreach([
                        'rumah_orang_tua' => ['icon' => 'fa-house-user', 'label' => 'Rumah Orang Tua'],
                        'kos' => ['icon' => 'fa-building', 'label' => 'Kos/Kontrak'],
                        'asrama' => ['icon' => 'fa-bed', 'label' => 'Asrama'],
                        'lainnya' => ['icon' => 'fa-ellipsis', 'label' => 'Lainnya'],
                        ] as $val => $item)
                        <label class="flex items-center gap-2 px-4 py-2 border-2 border-gray-200 rounded-full cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary/5 has-[:checked]:text-primary text-sm font-semibold text-[#6A7686] transition-all bg-white">
                            <input
                                type="radio"
                                name="residence_type"
                                value="{{ $val }}"
                                x-model="residenceType"
                                class="accent-primary w-3.5 h-3.5">
                            <i class="fa-solid {{ $item['icon'] }} text-sm"></i>
                            {{ $item['label'] }}
                        </label>
                        @endforeach
                    </div>
                    <p class="text-[11px] text-red-600 font-semibold mt-1"
                        x-show="err2('residence_type')" x-text="err2('residence_type')">
                    </p>
                </div>

            </div>
        </div>

        {{-- Info tambahan --}}
        <div class="flex gap-3 items-start bg-blue-50 border border-blue-100 rounded-xl px-4 py-3">
            <i class="fa-solid fa-circle-info text-blue-500 text-sm mt-0.5 flex-shrink-0"></i>
            <p class="text-xs text-blue-700 leading-relaxed">
                <strong class="font-black">Catatan:</strong> Pastikan alamat yang diisi adalah alamat domisili saat ini.
                Data ini akan digunakan untuk keperluan administrasi dan komunikasi sekolah.
            </p>
        </div>

    </div>

    {{-- ── Footer Navigasi Step 2 ──────────────────────────────────────── --}}
    <div class="px-8 py-5 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between rounded-b-[20px]">
        <button type="button" @click="step--"
            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-[#6A7686] border border-gray-200 rounded-full hover:border-[#080C1A] hover:text-[#080C1A] transition-all">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </button>
        <div class="flex items-center gap-3">
            <button type="button"
                hx-post="{{ route('biodata.step2') }}"
                hx-indicator="#biodata-form"
                hx-swap="none"
                @htmx:after-request="
                    const xhr = $event.detail.xhr;
                    if (xhr.status === 422) {
                        setErrors2(xhr);   // panggil helper, biarkan Alpine urus scope
                    } else if (xhr.status === 200) {
                        setErrors2(xhr);
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

</div>{{-- /step 2 --}}
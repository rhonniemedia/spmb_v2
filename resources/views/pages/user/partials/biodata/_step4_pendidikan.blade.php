<!-- STEP 4 — DATA PENDIDIKAN -->

{{--
|==========================================================================
| STEP 4 — DATA PENDIDIKAN SEBELUMNYA
|==========================================================================
| Perubahan (dari versi sebelumnya):
|   + Error validasi: ditangkap dari JSON 422 HTMX via @htmx:after-request
|   + Tiap field punya :class binding border merah saat error
|   + Error summary box muncul di atas form saat ada error
|   + Inline pesan error di bawah tiap field
|==========================================================================
--}}

<div x-show="step === 4"
    x-data="{
        errors4: {},
        hasError4: false,
        schoolStatus: '{{ old('previous_school_status', $personalData?->previous_school_status ?? 'negeri') }}',

        setErrors4(xhr) {
            if (xhr.status === 422) {
                try {
                    const body = JSON.parse(xhr.response);
                    this.errors4   = body.errors ?? {};
                    this.hasError4 = Object.keys(this.errors4).length > 0;
                } catch(e) {
                    this.errors4   = {};
                    this.hasError4 = false;
                }
            } else {
                this.errors4   = {};
                this.hasError4 = false;
            }
        },

        err4(field) {
            return this.errors4[field]?.[0] ?? null;
        }
    }"
    class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">

    {{-- Header --}}
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

        {{-- ── ERROR SUMMARY BOX ─────────────────────────────────────── --}}
        <div x-show="hasError4"
            x-transition
            class="flex gap-3 items-start bg-red-50 border border-red-200 rounded-2xl px-4 py-3.5">
            <i class="fa-solid fa-circle-exclamation text-red-500 text-base mt-0.5 flex-shrink-0"></i>
            <div>
                <p class="text-sm font-black text-red-700 mb-1">Harap periksa kembali isian Anda:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    <template x-for="(msgs, field) in errors4" :key="field">
                        <template x-for="msg in msgs" :key="msg">
                            <li class="text-sm text-red-600" x-text="msg"></li>
                        </template>
                    </template>
                </ul>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            {{-- Nama Sekolah Asal (full width) --}}
            <div class="space-y-1.5 sm:col-span-2">
                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">
                    Nama Sekolah Asal <span class="text-primary">*</span>
                </label>
                <input
                    type="text"
                    name="previous_school"
                    placeholder="Contoh: SMP Negeri 1 Bengkulu"
                    value="{{ old('previous_school', $personalData?->previous_school) }}"
                    :class="err4('previous_school') ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100' : 'border-gray-200 bg-gray-50 focus:border-primary focus:ring-primary/5 focus:bg-white'"
                    class="w-full px-4 py-3 rounded-xl border focus:ring-4 transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                <p class="text-[11px] text-red-600 font-semibold"
                    x-show="err4('previous_school')" x-text="err4('previous_school')">
                </p>
            </div>

            {{-- NPSN --}}
            <div class="space-y-1.5">
                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">NPSN Sekolah</label>
                <input
                    type="text"
                    name="previous_school_npsn"
                    maxlength="8"
                    placeholder="8 digit NPSN"
                    value="{{ old('previous_school_npsn', $personalData?->previous_school_npsn) }}"
                    :class="err4('previous_school_npsn') ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100' : 'border-gray-200 bg-gray-50 focus:border-primary focus:ring-primary/5 focus:bg-white'"
                    class="w-full px-4 py-3 rounded-xl border focus:ring-4 transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                <p class="text-[13px] text-[#6A7686]" x-show="!err4('previous_school_npsn')">Nomor Pokok Sekolah Nasional</p>
                <p class="text-[11px] text-red-600 font-semibold"
                    x-show="err4('previous_school_npsn')" x-text="err4('previous_school_npsn')">
                </p>
            </div>

            {{-- Status Sekolah — radio --}}
            <div class="space-y-1.5">
                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">
                    Status Sekolah <span class="text-primary">*</span>
                </label>
                <div class="flex gap-3 flex-wrap pt-1">
                    <label class="flex items-center gap-2 px-4 py-2.5 border-2 border-gray-200 rounded-full cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary-light has-[:checked]:text-primary text-sm font-semibold text-[#6A7686] transition-all"
                        :class="err4('previous_school_status') ? 'border-red-300' : ''">
                        <input
                            type="radio"
                            name="previous_school_status"
                            value="negeri"
                            x-model="schoolStatus"
                            class="accent-primary">
                        <i class="fa-solid fa-landmark text-sm"></i> Negeri
                    </label>
                    <label class="flex items-center gap-2 px-4 py-2.5 border-2 border-gray-200 rounded-full cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary-light has-[:checked]:text-primary text-sm font-semibold text-[#6A7686] transition-all"
                        :class="err4('previous_school_status') ? 'border-red-300' : ''">
                        <input
                            type="radio"
                            name="previous_school_status"
                            value="swasta"
                            x-model="schoolStatus"
                            class="accent-primary">
                        <i class="fa-solid fa-building text-sm"></i> Swasta
                    </label>
                </div>
                <p class="text-[11px] text-red-600 font-semibold"
                    x-show="err4('previous_school_status')" x-text="err4('previous_school_status')">
                </p>
            </div>

            {{-- Tahun Lulus --}}
            <div class="space-y-1.5">
                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">
                    Tahun Lulus <span class="text-primary">*</span>
                </label>
                <select
                    name="graduation_year"
                    :class="err4('graduation_year') ? 'border-red-400 bg-red-50/50' : 'border-gray-200 bg-gray-50 focus:border-primary focus:ring-primary/5 focus:bg-white'"
                    class="w-full px-4 py-3 rounded-xl border focus:ring-4 transition-all outline-none text-[15px] font-medium text-[#080C1A] appearance-none cursor-pointer pr-10">
                    <option value="">Pilih tahun</option>
                    @for($y = now()->year; $y >= now()->year - 4; $y--)
                    <option value="{{ $y }}" @selected(old('graduation_year', $personalData?->graduation_year) == $y)>
                        {{ $y }}
                    </option>
                    @endfor
                </select>
                <p class="text-[11px] text-red-600 font-semibold"
                    x-show="err4('graduation_year')" x-text="err4('graduation_year')">
                </p>
            </div>

            {{-- No. Ijazah / SKL --}}
            <div class="space-y-1.5">
                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">No. Ijazah / SKL</label>
                <input
                    type="text"
                    name="graduation_certificate_number"
                    placeholder="Nomor ijazah/SKL"
                    value="{{ old('graduation_certificate_number', $personalData?->graduation_certificate_number) }}"
                    :class="err4('graduation_certificate_number') ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100' : 'border-gray-200 bg-gray-50 focus:border-primary focus:ring-primary/5 focus:bg-white'"
                    class="w-full px-4 py-3 rounded-xl border focus:ring-4 transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                <p class="text-[11px] text-red-600 font-semibold"
                    x-show="err4('graduation_certificate_number')" x-text="err4('graduation_certificate_number')">
                </p>
            </div>
        </div>

        {{-- Kota & Provinsi Sekolah --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="space-y-1.5">
                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Kota Sekolah Asal <span class="text-primary">*</span></label>
                <input
                    type="text"
                    name="previous_school_city"
                    placeholder="Nama kota/kab"
                    value="{{ old('previous_school_city', $personalData?->previous_school_city) }}"
                    :class="err4('previous_school_city') ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100' : 'border-gray-200 bg-gray-50 focus:border-primary focus:ring-primary/5 focus:bg-white'"
                    class="w-full px-4 py-3 rounded-xl border focus:ring-4 transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                <p class="text-[11px] text-red-600 font-semibold"
                    x-show="err4('previous_school_city')" x-text="err4('previous_school_city')">
                </p>
            </div>
            <div class="space-y-1.5">
                <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">Provinsi Sekolah Asal <span class="text-primary">*</span></label>
                <input
                    type="text"
                    name="previous_school_province"
                    placeholder="Nama provinsi"
                    value="{{ old('previous_school_province', $personalData?->previous_school_province) }}"
                    :class="err4('previous_school_province') ? 'border-red-400 bg-red-50/50 focus:border-red-500 focus:ring-red-100' : 'border-gray-200 bg-gray-50 focus:border-primary focus:ring-primary/5 focus:bg-white'"
                    class="w-full px-4 py-3 rounded-xl border focus:ring-4 transition-all outline-none text-[15px] font-medium text-[#080C1A] placeholder:text-gray-400 placeholder:font-normal">
                <p class="text-[11px] text-red-600 font-semibold"
                    x-show="err4('previous_school_province')" x-text="err4('previous_school_province')">
                </p>
            </div>
        </div>

    </div>

    {{-- ── Footer Navigasi Step 4 ──────────────────────────────────────── --}}
    <div class="px-8 py-5 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between rounded-b-[20px]">
        <button type="button" @click="step--"
            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-[#6A7686] border border-gray-200 rounded-full hover:border-[#080C1A] hover:text-[#080C1A] transition-all">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </button>
        <div class="flex items-center gap-3">
            <button type="button"
                hx-post="{{ route('biodata.draft') }}"
                hx-include="#biodata-form"
                hx-swap="none"
                @htmx:after-request="saveDraft()"
                class="text-sm font-semibold text-[#6A7686] flex items-center gap-1.5 hover:text-primary transition-colors">
                <i class="fa-regular fa-floppy-disk"></i> Simpan Draft
            </button>

            <button type="button"
                hx-post="{{ route('biodata.step4') }}"
                hx-indicator="#biodata-form"
                hx-swap="none"
                @htmx:after-request="
                    const xhr = $event.detail.xhr;
                    if (xhr.status === 422) {
                        setErrors4(xhr);   // panggil helper, biarkan Alpine urus scope
                    } else if (xhr.status === 200) {
                        setErrors4(xhr);
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

</div>{{-- /step 4 --}}
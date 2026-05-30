{{--
    PARTIAL: pendaftar/step-1-biodata.blade.php
--}}

<div
    x-data="{
        regNumber:    '{{ addslashes(request('reg_number',    '')) }}',
        fullName:     '{{ addslashes(request('full_name',     '')) }}',
        nickname:     '{{ addslashes(request('nickname',      '')) }}',
        gender:       '{{ addslashes(request('gender',        '')) }}',
        nisn:         '{{ addslashes(request('nisn',          '')) }}',
        
        {{-- JS otomatis menghapus +62, 62, atau 0 di awal saat inisialisasi dari request() --}}
        phone:        '{{ addslashes(request('phone',         '')) }}'.replace(/^(?:\+62|62|0)/, ''),
        
        schoolOrigin: '{{ addslashes(request('school_origin', '')) }}'
    }"
    id="step1-data"
    class="flex flex-col max-h-[70vh]">

    {{-- AREA SCROLL KONTEN --}}
    <div class="flex-1 overflow-y-auto pr-2 pb-4 space-y-4">

        @foreach ($hiddenFields as $field)
        <input type="hidden" name="{{ $field }}" value="{{ request($field) }}">
        @endforeach

        {{-- Nomor Registrasi --}}
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                Nomor Registrasi <span class="text-rose-500">*</span>
            </label>
            <input type="text"
                name="reg_number"
                x-model="regNumber"
                placeholder="Contoh: REG-2025-0001"
                required
                class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm font-mono font-bold text-gray-800
                       focus:outline-none focus:border-rose-400 transition-all
                       placeholder:font-normal placeholder:text-gray-400">
            <p class="text-[11px] text-gray-400 mt-1.5 flex items-center gap-1">
                <i data-lucide="info" class="size-3 shrink-0"></i>
                Isi sesuai nomor registrasi berkas fisik yang diterima di loket.
            </p>
        </div>

        {{-- Nama Lengkap & Nama Panggilan --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                    name="full_name"
                    x-model="fullName"
                    placeholder="Sesuai ijazah..."
                    required
                    class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm
                           focus:outline-none focus:border-rose-400 transition-all">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                    Nama Panggilan <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                    name="nickname"
                    x-model="nickname"
                    placeholder="Nama sehari-hari..."
                    required
                    class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm
                           focus:outline-none focus:border-rose-400 transition-all">
            </div>
        </div>

        {{-- Jenis Kelamin --}}
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                Jenis Kelamin <span class="text-rose-500">*</span>
            </label>
            <div class="flex gap-3">
                <label class="flex-1 flex items-center gap-3 border border-gray-200 rounded-xl px-4 py-2.5 cursor-pointer
                              hover:border-rose-300 hover:bg-rose-50/50 transition-all"
                    :class="gender === 'L' ? 'border-rose-400 bg-rose-50/60' : ''">
                    <input type="radio" name="gender" value="L" x-model="gender" class="accent-rose-600" required>
                    <span class="text-sm font-semibold text-gray-700">Laki-laki</span>
                </label>
                <label class="flex-1 flex items-center gap-3 border border-gray-200 rounded-xl px-4 py-2.5 cursor-pointer
                              hover:border-rose-300 hover:bg-rose-50/50 transition-all"
                    :class="gender === 'P' ? 'border-rose-400 bg-rose-50/60' : ''">
                    <input type="radio" name="gender" value="P" x-model="gender" class="accent-rose-600">
                    <span class="text-sm font-semibold text-gray-700">Perempuan</span>
                </label>
            </div>
        </div>

        {{-- NISN & No. HP --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                    NISN <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                    name="nisn"
                    x-model="nisn"
                    maxlength="10"
                    placeholder="10 digit NISN..."
                    required
                    class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm
                           focus:outline-none focus:border-rose-400 transition-all">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                    Nomor HP / WhatsApp Aktif <span class="text-rose-500">*</span>
                </label>
                <div class="flex shadow-sm rounded-xl">
                    {{-- Kotak Addon +62 --}}
                    <span class="inline-flex items-center px-3.5 rounded-l-xl border border-r-0 border-gray-200 bg-gray-50 text-gray-700 font-bold text-sm shrink-0 select-none">
                        +62
                    </span>

                    {{-- Input Visual --}}
                    <input type="tel"
                        x-model="phone"
                        @input="phone = phone.replace(/\D/g, '').replace(/^0/, '')"
                        placeholder="812XXXXXXXX"
                        required
                        class="w-full border border-gray-200 rounded-r-xl px-3.5 py-2.5 text-sm
                               focus:outline-none focus:border-rose-400 focus:ring-1 focus:ring-rose-400 transition-all bg-white flex-1 min-w-0">

                    {{-- Hidden Input: Inilah yang akan dikirim ke Backend/HTMX (menggabungkan +62 dan angka) --}}
                    <input type="hidden" name="phone" x-bind:value="phone ? '+62' + phone : ''">
                </div>
            </div>
        </div>

        {{-- Sekolah Asal --}}
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                Sekolah Asal (SMP/MTs) <span class="text-rose-500">*</span>
            </label>
            <input type="text"
                name="school_origin"
                x-model="schoolOrigin"
                placeholder="Masukkan nama sekolah asal lengkap..."
                required
                class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm
                       focus:outline-none focus:border-rose-400 transition-all">
        </div>

    </div>
    {{-- AKHIR AREA SCROLL KONTEN --}}

    {{-- ── Navigasi (Fixed Footer) ── --}}
    <div class="flex-none pt-4 mt-2 bg-white border-t border-gray-200 flex justify-end">
        <button
            type="button"
            :disabled="!regNumber || !fullName || !nickname || !gender || nisn.length < 10 || !phone || !schoolOrigin"
            :class="(regNumber && fullName && nickname && gender && nisn.length >= 10 && phone && schoolOrigin)
                ? 'bg-gradient-to-r from-rose-600 to-orange-400 text-white shadow-md cursor-pointer hover:opacity-90'
                : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
            class="px-6 py-2.5 text-sm font-bold rounded-xl flex items-center gap-2 transition-all"
            hx-get="{{ $stepUrls[2] }}"
            hx-target="#modal-body"
            hx-swap="innerHTML"
            hx-include="#step1-data [name]"
            @htmx:before-request="$dispatch('modal-step', { step: 2 })">
            Lanjut ke Akademik
            <i data-lucide="arrow-right" class="size-4"></i>
        </button>
    </div>

</div>
{{--
    PARTIAL: pendaftar/step-4-jurusan.blade.php
--}}

<div
    x-data="{
        pil1: '{{ request('pil1', '') }}',
        pil2: '{{ request('pil2', '') }}',
        pil3: '{{ request('pil3', '') }}'
    }"
    id="step4-data"
    class="flex flex-col max-h-[70vh]">

    {{-- AREA SCROLL KONTEN --}}
    <div class="flex-1 overflow-y-auto pr-2 pb-4 space-y-4">

        @foreach ($hiddenFields as $field)
        <input type="hidden" name="{{ $field }}" value="{{ request($field) }}">
        @endforeach

        {{-- ── Pilihan 1 (Wajib) ── --}}
        <div class="flex items-center gap-3">
            <div class="bg-gradient-to-br from-rose-600 to-orange-400 text-white size-9 rounded-xl
                    flex items-center justify-center text-sm font-black shrink-0">1</div>
            <div class="relative flex-1">
                <select name="pil1"
                    x-model="pil1"
                    required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm
                       focus:outline-none focus:border-rose-400 focus:ring-1 focus:ring-rose-400
                       transition-all appearance-none bg-white cursor-pointer">
                    <option value="">-- Pilih Jurusan Utama (Pilihan 1) * --</option>
                    @foreach ($jurusanList as $jurusan)
                    <option value="{{ $jurusan->id }}" {{ request('pil1') == $jurusan->id ? 'selected' : '' }}>
                        {{ $jurusan->name }}
                    </option>
                    @endforeach
                </select>
                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                    <i data-lucide="chevron-down" class="size-4"></i>
                </div>
            </div>
        </div>

        {{-- ── Pilihan 2 (Opsional) ── --}}
        <div class="flex items-center gap-3">
            <div class="bg-gray-600 text-white size-9 rounded-xl
                    flex items-center justify-center text-sm font-black shrink-0">2</div>
            <div class="relative flex-1">
                <select name="pil2"
                    x-model="pil2"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm
                       focus:outline-none focus:border-gray-500 transition-all appearance-none bg-white cursor-pointer">
                    <option value="">-- Pilihan Cadangan 2 (Opsional) --</option>
                    @foreach ($jurusanList as $jurusan)
                    <option value="{{ $jurusan->id }}" {{ request('pil2') == $jurusan->id ? 'selected' : '' }}>
                        {{ $jurusan->name }}
                    </option>
                    @endforeach
                </select>
                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                    <i data-lucide="chevron-down" class="size-4"></i>
                </div>
            </div>
        </div>

        {{-- ── Pilihan 3 (Opsional) ── --}}
        <div class="flex items-center gap-3">
            <div class="bg-gray-900 text-white size-9 rounded-xl
                    flex items-center justify-center text-sm font-black shrink-0">3</div>
            <div class="relative flex-1">
                <select name="pil3"
                    x-model="pil3"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm
                       focus:outline-none focus:border-gray-900 transition-all appearance-none bg-white cursor-pointer">
                    <option value="">-- Pilihan Cadangan 3 (Opsional) --</option>
                    @foreach ($jurusanList as $jurusan)
                    <option value="{{ $jurusan->id }}" {{ request('pil3') == $jurusan->id ? 'selected' : '' }}>
                        {{ $jurusan->name }}
                    </option>
                    @endforeach
                </select>
                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                    <i data-lucide="chevron-down" class="size-4"></i>
                </div>
            </div>
        </div>

        {{-- Catatan --}}
        <div class="flex gap-2 bg-blue-50 border border-blue-100 p-3 rounded-xl text-xs text-blue-800 mt-2">
            <i data-lucide="info" class="size-3.5 mt-0.5 shrink-0"></i>
            <p>Pilihan 1 <strong>wajib</strong> diisi. Pilihan 2 dan 3 bersifat opsional sebagai alternatif bila kuota pilihan utama penuh.</p>
        </div>

    </div>
    {{-- AKHIR AREA SCROLL KONTEN --}}

    {{-- ── Navigasi (Fixed Footer) ── --}}
    <div class="flex-none pt-4 mt-2 bg-white border-t border-gray-200 flex items-center justify-between">
        <button type="button"
            class="px-5 py-2.5 border border-gray-300 rounded-xl text-sm font-bold text-gray-600
                   hover:text-gray-900 hover:bg-gray-100 flex items-center gap-2 bg-white transition-all"
            hx-get="{{ $stepUrls[3] }}"
            hx-target="#modal-body"
            hx-swap="innerHTML"
            hx-include="#step4-data [name]"
            @htmx:before-request="$dispatch('modal-step', { step: 3 })">
            <i data-lucide="arrow-left" class="size-4"></i> Kembali
        </button>

        <button type="button"
            :disabled="pil1 === ''"
            :class="pil1 !== ''
                ? 'bg-gradient-to-r from-rose-600 to-orange-400 text-white shadow-md cursor-pointer hover:opacity-90'
                : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
            class="px-6 py-2.5 text-sm font-bold rounded-xl flex items-center gap-2 transition-all"
            hx-get="{{ $stepUrls[5] }}"
            hx-target="#modal-body"
            hx-swap="innerHTML"
            hx-include="#step4-data [name]"
            @htmx:before-request="$dispatch('modal-step', { step: 5 })">
            Lanjut ke Berkas <i data-lucide="arrow-right" class="size-4"></i>
        </button>
    </div>

</div>
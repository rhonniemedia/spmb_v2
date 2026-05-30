{{--
    PARTIAL: pendaftar/step-2-akademik.blade.php
--}}

<div
    x-data="{
        r1:       '{{ request('r1',       '') }}',
        r2:       '{{ request('r2',       '') }}',
        r3:       '{{ request('r3',       '') }}',
        r4:       '{{ request('r4',       '') }}',
        r5:       '{{ request('r5',       '') }}',
        tka_mtk:  '{{ request('tka_mtk',  '') }}',
        tka_indo: '{{ request('tka_indo', '') }}',
        get rataRapor() {
            let vals = [this.r1, this.r2, this.r3, this.r4, this.r5]
                .map(v => parseFloat(v)).filter(v => !isNaN(v) && String(v) !== '');
            if (!vals.length) return '';
            return (vals.reduce((a, b) => a + b, 0) / vals.length).toFixed(2);
        },
        get rataTka() {
            let vals = [this.tka_mtk, this.tka_indo]
                .map(v => parseFloat(v)).filter(v => !isNaN(v) && String(v) !== '');
            if (!vals.length) return '';
            return (vals.reduce((a, b) => a + b, 0) / vals.length).toFixed(2);
        }
    }"
    id="step2-data"
    class="flex flex-col max-h-[70vh]">

    {{-- AREA SCROLL KONTEN --}}
    <div class="flex-1 overflow-y-auto pr-2 pb-4 space-y-6">

        {{-- Hidden dari Controller --}}
        @foreach ($hiddenFields as $field)
        <input type="hidden" name="{{ $field }}" value="{{ request($field) }}">
        @endforeach

        {{-- ── Nilai Rapor Per Semester ── --}}
        <div>
            <div class="flex items-center gap-3 mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Nilai Rapor Per Semester</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            <div class="bg-gray-50/50 border border-gray-200 rounded-2xl p-5 space-y-3 shadow-sm">

                @foreach ([
                ['field' => 'r1', 'label' => 'Semester 1'],
                ['field' => 'r2', 'label' => 'Semester 2'],
                ['field' => 'r3', 'label' => 'Semester 3'],
                ['field' => 'r4', 'label' => 'Semester 4'],
                ['field' => 'r5', 'label' => 'Semester 5'],
                ] as $s)
                <div class="flex items-center justify-between gap-4">
                    <label class="text-sm font-semibold text-gray-700">{{ $s['label'] }}</label>
                    <input type="number"
                        name="{{ $s['field'] }}"
                        min="0" max="100" step="0.01"
                        x-model="{{ $s['field'] }}"
                        placeholder="0 – 100"
                        class="w-32 border border-gray-200 rounded-xl px-3 py-2 text-sm text-center
                               focus:outline-none focus:border-rose-400 transition-all bg-white">
                </div>
                @endforeach

                {{-- Rata-rata Rapor --}}
                <div class="flex items-center justify-between gap-4 pt-3 border-t border-gray-200 mt-2">
                    <label class="text-sm font-bold text-gray-900">Rata-rata Rapor</label>
                    <div>
                        <input type="text" readonly :value="rataRapor || '—'" placeholder="0.00"
                            class="w-32 bg-rose-50 border border-rose-200 rounded-xl px-3 py-2
                                   text-sm font-bold text-center text-rose-700 focus:outline-none cursor-default">
                        <input type="hidden" name="rata_rapor" x-bind:value="rataRapor">
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Nilai TKA ── --}}
        <div>
            <div class="flex items-center gap-3 mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Nilai TKA</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            <div class="bg-gray-50/50 border border-gray-200 rounded-2xl p-5 space-y-3 shadow-sm">

                <div class="flex items-center justify-between gap-4">
                    <label class="text-sm font-semibold text-gray-700">Matematika</label>
                    <input type="number"
                        name="tka_mtk"
                        min="0" max="100" step="0.01"
                        x-model="tka_mtk"
                        placeholder="0 – 100"
                        class="w-32 border border-gray-200 rounded-xl px-3 py-2 text-sm text-center
                               focus:outline-none focus:border-rose-400 transition-all bg-white">
                </div>
                <div class="flex items-center justify-between gap-4">
                    <label class="text-sm font-semibold text-gray-700">Bahasa Indonesia</label>
                    <input type="number"
                        name="tka_indo"
                        min="0" max="100" step="0.01"
                        x-model="tka_indo"
                        placeholder="0 – 100"
                        class="w-32 border border-gray-200 rounded-xl px-3 py-2 text-sm text-center
                               focus:outline-none focus:border-rose-400 transition-all bg-white">
                </div>

                {{-- Rata-rata TKA --}}
                <div class="flex items-center justify-between gap-4 pt-3 border-t border-gray-200 mt-2">
                    <label class="text-sm font-bold text-gray-900">Rata-rata TKA</label>
                    <div>
                        <input type="text" readonly :value="rataTka || '—'" placeholder="0.00"
                            class="w-32 bg-blue-50 border border-blue-200 rounded-xl px-3 py-2
                                   text-sm font-bold text-center text-blue-700 focus:outline-none cursor-default">
                        <input type="hidden" name="rata_tka" x-bind:value="rataTka">
                    </div>
                </div>
            </div>
        </div>

    </div>
    {{-- AKHIR AREA SCROLL KONTEN --}}

    {{-- ── Navigasi (Fixed Footer) ── --}}
    <div class="flex-none pt-4 mt-2 bg-white border-t border-gray-200 flex items-center justify-between">
        <button type="button"
            class="px-5 py-2.5 border border-gray-300 rounded-xl text-sm font-bold text-gray-600
                   hover:text-gray-900 hover:bg-gray-100 flex items-center gap-2 bg-white transition-all"
            hx-get="{{ $stepUrls[1] }}"
            hx-target="#modal-body"
            hx-swap="innerHTML"
            hx-include="#step2-data [name]"
            @htmx:before-request="$dispatch('modal-step', { step: 1 })">
            <i data-lucide="arrow-left" class="size-4"></i> Kembali
        </button>

        <button type="button"
            class="px-6 py-2.5 bg-gradient-to-r from-rose-600 to-orange-400 text-white text-sm font-bold
                   rounded-xl shadow-md hover:opacity-90 flex items-center gap-2 transition-all cursor-pointer"
            hx-get="{{ $stepUrls[3] }}"
            hx-target="#modal-body"
            hx-swap="innerHTML"
            hx-include="#step2-data [name]"
            @htmx:before-request="$dispatch('modal-step', { step: 3 })">
            Lanjut ke Jalur <i data-lucide="arrow-right" class="size-4"></i>
        </button>
    </div>

</div>
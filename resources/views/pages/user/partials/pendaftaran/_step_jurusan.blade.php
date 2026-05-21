{{-- _step_jurusan.blade.php --}}
{{--
|==========================================================================
| STEP JURUSAN — PEMILIHAN JURUSAN (DATA DINAMIS + VALIDASI LIVE LOCK)
|==========================================================================
--}}

<div x-show="currentStepId === 'jurusan'"
    class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">

    {{-- ── HEADER ── --}}
    <div class="px-8 pt-6 pb-5 border-b border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-violet-50 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-building-columns text-violet-500 text-xl"></i>
        </div>
        <div>
            <h2 class="text-lg font-black text-[#080C1A]">Pemilihan Jurusan</h2>
            <p class="text-sm text-[#6A7686]">Pilih 3 jurusan secara berurutan sesuai prioritas kamu</p>
        </div>
    </div>

    <div class="px-8 py-7 space-y-7">

        {{-- ── Info Banner (Teks Sejajar & Rapi) ── --}}
        <div class="flex gap-3 items-start bg-violet-50 border border-violet-200 rounded-2xl px-5 py-4">
            <i class="fa-solid fa-circle-info text-violet-500 text-base mt-0.5 flex-shrink-0"></i>
            <div class="text-sm font-medium text-violet-900 leading-relaxed w-full">
                <p class="font-black text-[14px] mb-2 text-[#080C1A]">Ketentuan Pemilihan Jurusan:</p>

                <ul class="space-y-2 text-xs text-violet-800">
                    {{-- Bullet 1 --}}
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-violet-400 flex-shrink-0"></span>
                        <span>Wajib memilih <strong class="text-violet-950">3 jurusan berbeda</strong>.</span>
                    </li>

                    {{-- Bullet 2 (Disesuaikan dengan aturan baru) --}}
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-violet-400 flex-shrink-0"></span>
                        <span class="leading-normal">
                            Jurusan kelompok kompetensi keahlian teknik
                            <strong class="text-violet-950 font-black uppercase">(TKJ, TKR, TSM)</strong>
                            <strong class="text-red-600 font-bold">hanya boleh dipilih sebagai Pilihan Pertama</strong> dan tidak diperkenankan pada pilihan kedua maupun ketiga.
                        </span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Alert Banner Error jika Validasi Gagal --}}
        <div x-show="adaError"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="flex gap-3 items-start bg-red-50 border border-red-200 rounded-2xl px-4 py-3.5">
            <i class="fa-solid fa-triangle-exclamation text-red-500 text-base mt-0.5 flex-shrink-0"></i>
            <p class="text-sm font-semibold text-red-800" x-text="pesanError"></p>
        </div>

        {{-- ── SELECTION FIELDS ── --}}
        <div class="space-y-6">

            {{-- LOOPING CONFIGURATION UNTUK 3 PILIHAN JURUSAN --}}
            @foreach([
            1 => ['label' => 'Pilihan Pertama', 'model' => 'pil1', 'sub' => 'Prioritas utama', 'badgeBg' => 'bg-[#FF1443]', 'focusRing' => 'focus:ring-[#FF1443]/20 focus:border-[#FF1443]'],
            2 => ['label' => 'Pilihan Kedua', 'model' => 'pil2', 'sub' => 'Alternatif pertama', 'badgeBg' => 'bg-gray-700', 'focusRing' => 'focus:ring-gray-700/20 focus:border-gray-700'],
            3 => ['label' => 'Pilihan Ketiga', 'model' => 'pil3', 'sub' => 'Alternatif kedua', 'badgeBg' => 'bg-gray-400', 'focusRing' => 'focus:ring-gray-400/20 focus:border-gray-400']
            ] as $stepNum => $config)

            <div>
                <div class="flex items-center gap-2 mb-2.5">
                    <div class="w-7 h-7 rounded-lg {{ $config['badgeBg'] }} flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-[11px] font-black">{{ $stepNum }}</span>
                    </div>
                    <label for="pilihan_jurusan_{{ $stepNum }}" class="text-[14px] font-black text-[#080C1A]">
                        {{ $config['label'] }} <span class="text-[#FF1443]">*</span>
                    </label>
                    <span class="ml-auto text-[11px] text-[#6A7686] font-medium">{{ $config['sub'] }}</span>
                </div>

                <div class="relative">
                    <select id="pilihan_jurusan_{{ $stepNum }}"
                        name="pilihan_jurusan_{{ $stepNum }}"
                        x-model="{{ $config['model'] }}"
                        required
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-[14px] font-bold text-[#080C1A] focus:outline-none {{ $config['focusRing'] }} transition-all bg-white cursor-pointer appearance-none">
                        <option value="" selected>— Pilih jurusan pilihan ke-{{ $stepNum }} —</option>

                        @foreach($concentrations as $c)
                        <option value="{{ $c->id }}"
                            :disabled="isDisabled('{{ $c->id }}', '{{ $config['model'] }}')">
                            {{ $c->name }} ({{ strtoupper($c->alias) }})
                            <template x-if="jurusanList['{{ $c->id }}']?.restrict_choice && ('{{ $config['model'] }}' === 'pil2' || '{{ $config['model'] }}' === 'pil3')">
                                <span> — [Khusus Pil 1]</span>
                            </template>
                        </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400 text-xs">
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                </div>
            </div>

            @endforeach

        </div>

        {{-- ── SUMMARY PREVIEW TIMELINE ── --}}
        <template x-if="pil1 && pil2 && pil3 && !adaError">
            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0">
                <p class="text-[12px] font-black uppercase tracking-widest text-[#6A7686] mb-4">Ringkasan Prioritas Pilihan Anda</p>
                <div class="relative pl-6 border-l-2 border-dashed border-gray-200 space-y-5 ml-2">

                    <div class="relative">
                        <span class="absolute -left-[31px] top-0.5 w-4 h-4 rounded-full bg-[#FF1443] border-4 border-white ring-2 ring-[#FF1443]/20 flex items-center justify-center"></span>
                        <div class="text-sm font-bold text-[#080C1A]" x-text="jurusanList[pil1]?.nama"></div>
                        <div class="text-xs text-[#6A7686]">Pilihan Utama (Prioritas I)</div>
                    </div>

                    <div class="relative">
                        <span class="absolute -left-[31px] top-0.5 w-4 h-4 rounded-full bg-gray-700 border-4 border-white ring-2 ring-gray-700/20 flex items-center justify-center"></span>
                        <div class="text-sm font-bold text-[#080C1A]" x-text="jurusanList[pil2]?.nama"></div>
                        <div class="text-xs text-[#6A7686]">Cadangan Pertama (Prioritas II)</div>
                    </div>

                    <div class="relative">
                        <span class="absolute -left-[31px] top-0.5 w-4 h-4 rounded-full bg-gray-400 border-4 border-white ring-2 ring-gray-400/20 flex items-center justify-center"></span>
                        <div class="text-sm font-bold text-[#080C1A]" x-text="jurusanList[pil3]?.nama"></div>
                        <div class="text-xs text-[#6A7686]">Cadangan Kedua (Prioritas III)</div>
                    </div>

                </div>
            </div>
        </template>

    </div>

    {{-- ── FOOTER NAVIGATION ── --}}
    <div class="px-8 py-5 border-t border-gray-200 bg-gray-50/50 flex items-center justify-between rounded-b-[20px]">
        <button type="button" @click="step--"
            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-[#6A7686] border border-gray-200 rounded-full hover:border-[#080C1A] hover:text-[#080C1A] transition-all">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </button>

        <div class="flex items-center gap-3">
            <button type="button"
                hx-post="{{ route('registration.step4') }}"
                hx-include="[name='pilihan_jurusan_1'], [name='pilihan_jurusan_2'], [name='pilihan_jurusan_3']"
                hx-target="this"
                hx-swap="none"
                hx-indicator="#loading-jurusan"
                hx-on::after-request="
                    const res = JSON.parse(event.detail.xhr.responseText);
                    if (res.success) window.dispatchEvent(new CustomEvent('pindah-step', { detail: { nextStep: 'konfirmasi' } }))
                "
                :disabled="!(pil1 && pil2 && pil3 && !adaError)"
                :class="(pil1 && pil2 && pil3 && !adaError)
                    ? 'bg-[#FF1443] hover:bg-[#D90F38] hover:-translate-y-px shadow-lg shadow-red-500/30 cursor-pointer'
                    : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
                class="inline-flex items-center gap-2 px-8 py-2.5 text-white text-sm font-black rounded-full transition-all">
                <span id="loading-jurusan" class="htmx-indicator mr-1"><i class="fa-solid fa-circle-notch animate-spin"></i></span>
                Lanjut <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>
    </div>

</div>
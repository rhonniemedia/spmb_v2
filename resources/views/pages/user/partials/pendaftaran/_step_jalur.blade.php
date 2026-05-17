{{-- _step_jalur.blade.php --}}
{{--
|==========================================================================
| STEP JALUR — PILIH JALUR PENDAFTARAN (DINAMIS)
|==========================================================================
--}}

<div x-show="currentStepId === 'jalur'"
    class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">

    {{-- ── HEADER ── --}}
    <div class="px-8 pt-6 pb-5 border-b border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-sky-50 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-road text-sky-500 text-xl"></i>
        </div>
        <div>
            <h2 class="text-lg font-black text-[#080C1A]">Jalur Pendaftaran</h2>
            <p class="text-sm text-[#6A7686]">Pilih satu jalur yang sesuai dengan kondisimu — pilihan bersifat permanen</p>
        </div>
    </div>

    <div class="px-8 py-7 space-y-6">

        {{-- Info Alert --}}
        <div class="flex gap-3 items-start bg-blue-50 border border-blue-200 rounded-2xl px-4 py-3.5">
            <i class="fa-solid fa-circle-info text-blue-500 text-base mt-0.5 flex-shrink-0"></i>
            <p class="text-sm font-medium text-blue-800 leading-relaxed">
                Setiap jalur memiliki <strong>kuota dan syarat berbeda</strong>. Pastikan kamu memenuhi syarat jalur yang dipilih sebelum melanjutkan. Pilihan <strong>tidak dapat diubah</strong> setelah formulir dikirim.
            </p>
        </div>

        {{-- ── CARD GRID (DINAMIS) ── --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            @foreach($admissionPaths as $path)
            @php
            // Normalisasi warna theme dari database agar sesuai dengan utility classes Tailwind
            $theme = $path->color_theme ?? 'sky';

            // Definisikan map warna agar Tailwind JIT compiler membaca class secara utuh
            $colorMap = [
            'red' => ['border' => 'border-[#FF1443]', 'bg' => 'bg-red-50', 'ring' => 'ring-red-200', 'hover' => 'hover:border-red-300 hover:bg-red-50/40', 'bg-badge' => 'bg-[#FF1443]', 'bg-icon' => 'bg-red-100', 'text-icon' => 'text-[#FF1443]', 'text-tag' => 'text-[#FF1443]', 'bg-tag' => 'bg-red-100', 'text-quota' => 'text-[#FF1443]'],
            'emerald' => ['border' => 'border-emerald-400', 'bg' => 'bg-emerald-50', 'ring' => 'ring-emerald-200', 'hover' => 'hover:border-emerald-300 hover:bg-emerald-50/40', 'bg-badge' => 'bg-emerald-500', 'bg-icon' => 'bg-emerald-100', 'text-icon' => 'text-emerald-600', 'text-tag' => 'text-emerald-700', 'bg-tag' => 'bg-emerald-100', 'text-quota' => 'text-emerald-600'],
            'amber' => ['border' => 'border-amber-400', 'bg' => 'bg-amber-50', 'ring' => 'ring-amber-200', 'hover' => 'hover:border-amber-300 hover:bg-amber-50/40', 'bg-badge' => 'bg-amber-500', 'bg-icon' => 'bg-amber-100', 'text-icon' => 'text-amber-600', 'text-tag' => 'text-amber-700', 'bg-tag' => 'bg-amber-100', 'text-quota' => 'text-amber-600'],
            'indigo' => ['border' => 'border-indigo-400', 'bg' => 'bg-indigo-50', 'ring' => 'ring-indigo-200', 'hover' => 'hover:border-indigo-300 hover:bg-indigo-50/40', 'bg-badge' => 'bg-indigo-500', 'bg-icon' => 'bg-indigo-100', 'text-icon' => 'text-indigo-600', 'text-tag' => 'text-indigo-700', 'bg-tag' => 'bg-indigo-100', 'text-quota' => 'text-indigo-600'],
            ];

            $c = $colorMap[$theme] ?? $colorMap['red'];

            // Kita asumsikan value pencocokan string (misal name: "Jalur Reguler" -> value: "reguler")
            // Menggunakan Str::slug() bawaan Laravel untuk binding data ke Alpine.js
            $slugValue = Str::slug(str_replace('Jalur ', '', $path->name));
            @endphp

            <label class="cursor-pointer group">
                <input type="radio" name="jalur_pendaftaran" value="{{ $slugValue }}" x-model="jalur" class="sr-only">

                <div :class="jalur === '{{ $slugValue }}'
                            ? '{{ $c['border'] }} {{ $c['bg'] }} ring-2 {{ $c['ring'] }}'
                            : 'border-gray-200 bg-white {{ $c['hover'] }}'"
                    class="relative border-2 rounded-2xl p-5 transition-all duration-200 h-full">

                    {{-- Check badge --}}
                    <div x-show="jalur === '{{ $slugValue }}'"
                        class="absolute top-3.5 right-3.5 w-6 h-6 rounded-full {{ $c['bg-badge'] }} flex items-center justify-center shadow-sm">
                        <i class="fa-solid fa-check text-white text-[10px]"></i>
                    </div>

                    <div class="flex items-start gap-3 mb-3">
                        <div class="w-11 h-11 rounded-xl {{ $c['bg-icon'] }} flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid {{ $path->icon }} {{ $c['text-icon'] }} text-lg"></i>
                        </div>
                        <div>
                            <div class="text-[15px] font-black text-[#080C1A] leading-tight">{{ $path->name }}</div>
                            <div class="text-[12px] text-[#6A7686] mt-0.5">{{ $path->subtitle }}</div>
                        </div>
                    </div>

                    <p class="text-[12px] text-[#6A7686] leading-relaxed mb-3">
                        {{ $path->description }}
                    </p>

                    {{-- Tags / Badges --}}
                    <div class="flex flex-wrap gap-1.5 mb-3">
                        @php
                        // Cast JSON string ke array jika belum otomatis ter-cast oleh model
                        $tags = is_string($path->tags) ? json_decode($path->tags, true) : $path->tags;
                        @endphp
                        @foreach($tags ?? [] as $tag)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold {{ $c['bg-tag'] }} {{ $c['text-tag'] }}">
                            {{ $tag }}
                        </span>
                        @endforeach
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                        <span class="text-[11px] text-[#6A7686] font-medium">Kuota</span>
                        <span class="text-[13px] font-black {{ $c['text-quota'] }}">{{ $path->quota_percentage }}% total kursi</span>
                    </div>
                </div>
            </label>
            @endforeach

        </div>{{-- /grid --}}

        {{-- ── INFO LANJUTAN: reguler loncat step ── --}}
        <div x-show="jalur === 'reguler'"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="flex gap-3 items-start bg-red-50 border border-red-200 rounded-2xl px-4 py-2">
            <i class="fa-solid fa-bolt text-[#FF1443] text-base mt-0.5 flex-shrink-0"></i>
            <p class="text-sm font-medium text-red-800 leading-relaxed">
                Jalur Reguler <strong>tidak memerlukan step tambahan</strong>. Setelah ini kamu akan langsung diarahkan ke pemilihan jurusan.
            </p>
        </div>

        {{-- Placeholder saat belum memilih --}}
        <div x-show="jalur === ''"
            class="border-2 border-dashed border-gray-200 rounded-2xl py-6 flex items-center justify-center gap-2 text-[13px] font-medium text-[#B0B9C4]">
            <i class="fa-solid fa-hand-pointer text-[12px]"></i> Pilih jalur untuk melihat dokumen yang diperlukan
        </div>

    </div>{{-- /space-y-6 --}}

    {{-- ── FOOTER NAV ── --}}
    <div class="px-8 py-5 border-t border-gray-200 bg-gray-50/50 flex items-center justify-between rounded-b-[20px]">
        <button type="button" @click="step--"
            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-[#6A7686] border border-gray-200 rounded-full hover:border-[#080C1A] hover:text-[#080C1A] transition-all">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </button>

        <div class="flex items-center gap-3">
            <button type="button"
                hx-post="{{ route('registration.step2') }}"
                hx-include="[name='jalur_pendaftaran']"
                hx-target="this"
                hx-swap="none"
                hx-indicator="#loading-jalur"
                hx-on::after-request="
                    const res = JSON.parse(event.detail.xhr.responseText);
                    if (res.success) window.dispatchEvent(new CustomEvent('pindah-step', { detail: { nextStep: res.nextStep } }))
                "
                :disabled="jalur === ''"
                :class="jalur !== '' ? 'bg-[#FF1443] hover:bg-[#D90F38] hover:-translate-y-px shadow-lg shadow-red-500/30 cursor-pointer' : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
                class="inline-flex items-center gap-2 px-8 py-2.5 text-white text-sm font-black rounded-full transition-all">
                <span id="loading-jalur" class="htmx-indicator mr-1"><i class="fa-solid fa-circle-notch animate-spin"></i></span>
                Lanjut <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>
    </div>

</div>
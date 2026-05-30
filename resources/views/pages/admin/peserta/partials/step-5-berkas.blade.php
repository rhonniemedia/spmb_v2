{{--
    PARTIAL: pendaftar/step-5-berkas.blade.php
--}}

<div id="step5-data"
    x-data="{
        @foreach($berkasList as $berkas)
            @php $slugName = 'berkas_' . str_replace('-', '_', $berkas->slug); @endphp
            {{ $slugName }}: {{ request($slugName) ? 'true' : 'false' }},
        @endforeach
    }"
    class="flex flex-col max-h-[70vh]">

    {{-- AREA SCROLL KONTEN --}}
    <div class="flex-1 overflow-y-auto pr-2 pb-4 space-y-4">

        @foreach ($hiddenFields as $field)
        <input type="hidden" name="{{ $field }}" value="{{ request($field) }}">
        @endforeach

        {{-- ── Tabel Checklist Berkas Dinamis ── --}}
        <div class="border border-gray-200 rounded-2xl overflow-hidden shadow-sm bg-white">
            <table class="w-full text-left border-collapse text-xs">
                <thead class="bg-gray-50 text-gray-700 font-bold border-b border-gray-200">
                    <tr>
                        <th class="p-3.5 text-center w-20">Ada</th>
                        <th class="p-3.5">Nama Berkas Persyaratan Fisik / Asli</th>
                        <th class="p-3.5 text-center w-28">Bentuk Fisik</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700 bg-white">

                    @foreach($berkasList as $berkas)
                    @php
                    $field = 'berkas_' . str_replace('-', '_', $berkas->slug);
                    $theme = $berkas->color_theme ?? 'blue';
                    @endphp
                    <tr class="hover:bg-gray-50/50">
                        <td class="p-3 text-center">
                            <input type="checkbox" name="{{ $field }}" value="1" x-model="{{ $field }}" class="w-4 h-4 accent-amber-600 rounded cursor-pointer">
                        </td>
                        <td class="p-3 font-semibold text-gray-800">
                            {{ $berkas->name }}
                            @if($berkas->is_mandatory)
                            <span class="text-rose-500 ml-1">*</span>
                            @endif
                        </td>
                        <td class="p-3 text-center">
                            <span class="px-2 py-0.5 bg-{{ $theme }}-50 text-{{ $theme }}-700 border border-{{ $theme }}-100 rounded text-[10px] font-medium">Dokumen Asli</span>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

    </div>
    {{-- AKHIR AREA SCROLL KONTEN --}}

    {{-- ── Navigasi (Fixed Footer) ── --}}
    <div class="flex-none pt-4 mt-2 bg-white border-t border-gray-200 flex items-center justify-between">
        <button type="button"
            class="px-5 py-2.5 border border-gray-300 rounded-xl text-sm font-bold text-gray-600 hover:text-gray-900 hover:bg-gray-100 flex items-center gap-2 bg-white transition-all"
            hx-get="{{ $stepUrls[4] }}"
            hx-target="#modal-body"
            hx-swap="innerHTML"
            hx-include="#step5-data [name]"
            @htmx:before-request="$dispatch('modal-step', { step: 4 })">
            <i data-lucide="arrow-left" class="size-4"></i> Kembali
        </button>

        <button type="button"
            class="px-6 py-2.5 bg-gradient-to-r from-rose-600 to-orange-400 text-white text-sm font-bold rounded-xl shadow-md hover:opacity-90 flex items-center gap-2 transition-all cursor-pointer"
            hx-get="{{ $stepUrls[6] }}"
            hx-target="#modal-body"
            hx-swap="innerHTML"
            hx-include="#step5-data [name]"
            @htmx:before-request="$dispatch('modal-step', { step: 6 })">
            Lanjut ke Konfirmasi <i data-lucide="arrow-right" class="size-4"></i>
        </button>
    </div>

</div>
{{--
    PARTIAL: pendaftar/step-3-jalur.blade.php
--}}

<div x-data="{
        jalur:             '{{ request('jalur',             '') }}',
        jalur_name:        '{{ request('jalur_name',        '') }}',
        jenis_p:           '{{ request('jenis_prestasi',    '') }}',
        tingkat_kejuaraan: '{{ request('tingkat_kejuaraan', '') }}',
        juz_hafalan:       '{{ request('juz_hafalan',       '') }}',
        jabatan_org:       '{{ request('jabatan_org',       '') }}',
        peringkats: {
            '1': '{{ request('peringkat_sem_1', '') }}',
            '2': '{{ request('peringkat_sem_2', '') }}',
            '3': '{{ request('peringkat_sem_3', '') }}',
            '4': '{{ request('peringkat_sem_4', '') }}',
            '5': '{{ request('peringkat_sem_5', '') }}'
        }
    }"
    id="step3-data"
    class="flex flex-col max-h-[70vh]">

    {{-- AREA SCROLL KONTEN --}}
    <div class="flex-1 overflow-y-auto pr-2 pb-4 space-y-6">

        @foreach ($hiddenFields as $field)
        <input type="hidden" name="{{ $field }}" value="{{ request($field) }}">
        @endforeach

        {{-- ── Pilihan Jalur Dinamis ── --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($jalurList as $j)
            @php
            $theme = $j->color_theme ?? 'blue';
            $namaJalur = strtolower($j->name);
            @endphp
            <label class="cursor-pointer block" @click="jalur_name = '{{ $namaJalur }}'">
                <input type="radio" name="jalur" value="{{ $j->id }}" x-model="jalur" class="sr-only">

                <div :class="jalur === '{{ $j->id }}' ? 'border-{{$theme}}-400 bg-{{$theme}}-50 ring-2 ring-{{$theme}}-200' : 'border-gray-200 bg-white hover:border-{{$theme}}-300'"
                    class="relative border-2 rounded-2xl p-4 transition-all h-full">
                    <div class="flex items-start gap-3 mb-1">
                        <div class="w-8 h-8 rounded-lg bg-{{$theme}}-100 flex items-center justify-center shrink-0">
                            <i data-lucide="{{ $j->icon ?? 'route' }}" class="size-4 text-{{$theme}}-600"></i>
                        </div>
                        <div class="text-sm font-black text-gray-900">{{ $j->name }}</div>
                    </div>
                    <p class="text-[11px] text-gray-500">{{ $j->subtitle }}</p>
                </div>
            </label>
            @endforeach
        </div>

        <input type="hidden" name="jalur_name" x-model="jalur_name">

        {{-- ── Sub-form Prestasi ── --}}
        <div x-show="jalur_name.includes('prestasi')" x-transition class="p-4 border border-amber-200 bg-amber-50/30 rounded-xl space-y-4">

            <h4 class="text-xs font-black text-amber-900 uppercase tracking-wider flex items-center gap-1.5">
                <i data-lucide="crown" class="size-3.5"></i>
                Pengaturan Kategori Formulir Prestasi
            </h4>

            {{-- Pilihan jenis prestasi --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                <template x-for="item in [
                    { val: 'kejuaraan',    label: 'Kejuaraan' },
                    { val: 'tahfiz',       label: 'Tahfiz Quran' },
                    { val: 'kepemimpinan', label: 'OSIS / Inti' },
                    { val: 'peringkat',    label: 'Juara Kelas' }
                ]" :key="item.val">
                    <label class="p-2 border rounded-xl text-center bg-white block cursor-pointer text-xs font-bold transition-all"
                        :class="jenis_p === item.val ? 'border-amber-500 text-amber-700 bg-amber-50' : 'border-gray-200 text-gray-500'">
                        <input type="radio" name="jenis_prestasi" x-model="jenis_p" :value="item.val" class="sr-only">
                        <span x-text="item.label"></span>
                    </label>
                </template>
            </div>

            {{-- Kejuaraan: tingkat --}}
            <div x-show="jenis_p === 'kejuaraan'" x-cloak>
                <label class="block text-[11px] text-gray-500 font-bold mb-1">Tingkat Kejuaraan</label>
                <select name="tingkat_kejuaraan" x-model="tingkat_kejuaraan"
                    class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none">
                    <option value="">-- Pilih Tingkat --</option>
                    <option value="kabupaten">Kabupaten / Kota</option>
                    <option value="provinsi">Provinsi</option>
                    <option value="nasional">Nasional</option>
                </select>
            </div>

            {{-- Tahfiz: jumlah juz --}}
            <div x-show="jenis_p === 'tahfiz'" x-cloak>
                <label class="block text-[11px] text-gray-500 font-bold mb-1">Jumlah Juz yang Dihafal</label>
                <input type="number" name="juz_hafalan" x-model="juz_hafalan"
                    min="1" max="30" placeholder="Contoh: 5"
                    class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none">
            </div>

            {{-- Kepemimpinan: jabatan --}}
            <div x-show="jenis_p === 'kepemimpinan'" x-cloak>
                <label class="block text-[11px] text-gray-500 font-bold mb-1">Jabatan Struktur Utama Organisasi</label>
                <input type="text" name="jabatan_org" x-model="jabatan_org"
                    placeholder="Contoh: Ketua OSIS SMP"
                    class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none">
            </div>

            {{-- Peringkat kelas per semester --}}
            <div x-show="jenis_p === 'peringkat'" x-cloak class="mt-4 pt-3 border-t border-amber-200 space-y-3">
                <p class="text-[11px] font-black text-amber-800 uppercase tracking-widest flex items-center gap-1.5">
                    <i data-lucide="medal" class="size-3 text-amber-500"></i> Peringkat per Semester
                </p>
                <div class="rounded-xl border border-amber-200 overflow-hidden bg-white divide-y divide-gray-100">
                    <template x-for="s in [
                        { sem: '1', label: 'Semester 1', kelas: 'Kelas VII' },
                        { sem: '2', label: 'Semester 2', kelas: 'Kelas VII' },
                        { sem: '3', label: 'Semester 3', kelas: 'Kelas VIII' },
                        { sem: '4', label: 'Semester 4', kelas: 'Kelas VIII' },
                        { sem: '5', label: 'Semester 5', kelas: 'Kelas IX' }
                    ]" :key="s.sem">
                        <div class="flex items-center px-3 py-2.5 gap-3">
                            <div class="w-24 shrink-0">
                                <p class="text-[12px] font-black text-gray-900 leading-tight" x-text="s.label"></p>
                                <p class="text-[10px] text-gray-400 leading-tight" x-text="s.kelas"></p>
                            </div>
                            <span class="text-gray-300 shrink-0">:</span>
                            <div class="flex gap-1.5 flex-1">
                                <template x-for="j in ['1','2','3']" :key="j">
                                    <button type="button"
                                        @click="peringkats[s.sem] = (peringkats[s.sem] === j) ? '' : j"
                                        :class="peringkats[s.sem] === j
                                                ? 'bg-amber-500 text-white border-amber-500 font-black'
                                                : 'bg-white text-gray-400 border-gray-200 font-semibold hover:border-amber-300 hover:text-amber-600'"
                                        class="flex-1 border-2 rounded-lg py-2 text-[11px] text-center transition-all leading-none"
                                        x-text="'Juara ' + j">
                                    </button>
                                </template>
                            </div>
                            <div class="w-5 shrink-0 flex items-center justify-center">
                                <i x-show="peringkats[s.sem] !== ''"
                                    data-lucide="circle-check"
                                    class="size-4 text-amber-500"
                                    style="display:none;"></i>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="flex gap-3 items-center bg-red-50 border border-red-200 rounded-xl px-4 py-2.5 mt-3">
                    <div class="w-7 h-7 rounded-lg bg-red-200 flex items-center justify-center shrink-0">
                        <i data-lucide="file-signature" class="size-3.5 text-red-600"></i>
                    </div>
                    <p class="text-[12px] text-red-950 leading-snug">
                        <span class="font-normal text-red-700">Dibuktikan dengan </span>
                        <span class="font-black">Rapor / Keterangan Peringkat dari Wali Kelas.</span>
                        Dokumen pendukung harus dibawa saat verifikasi.
                    </p>
                </div>
            </div>

        </div>

        <input type="hidden" name="peringkat_sem_1" x-bind:value="peringkats['1']">
        <input type="hidden" name="peringkat_sem_2" x-bind:value="peringkats['2']">
        <input type="hidden" name="peringkat_sem_3" x-bind:value="peringkats['3']">
        <input type="hidden" name="peringkat_sem_4" x-bind:value="peringkats['4']">
        <input type="hidden" name="peringkat_sem_5" x-bind:value="peringkats['5']">

        {{-- Info jalur non-prestasi --}}
        <div x-show="!jalur_name.includes('prestasi') && jalur !== ''" x-transition
            class="flex gap-2 bg-blue-50 border border-blue-100 p-3 rounded-xl text-xs text-blue-800">
            <i data-lucide="info" class="size-3.5 mt-0.5 shrink-0"></i>
            <p>Jalur yang dipilih tidak membutuhkan sub-form tambahan.</p>
        </div>

    </div>
    {{-- AKHIR AREA SCROLL KONTEN --}}

    {{-- ── Navigasi (Fixed Footer) ── --}}
    <div class="flex-none pt-4 mt-2 bg-white border-t border-gray-200 flex items-center justify-between">
        <button type="button"
            class="px-5 py-2.5 border border-gray-300 rounded-xl text-sm font-bold text-gray-600
                   hover:text-gray-900 hover:bg-gray-100 flex items-center gap-2 bg-white transition-all"
            hx-get="{{ $stepUrls[2] }}"
            hx-target="#modal-body"
            hx-swap="innerHTML"
            hx-include="#step3-data [name]"
            @htmx:before-request="$dispatch('modal-step', { step: 2 })">
            <i data-lucide="arrow-left" class="size-4"></i> Kembali
        </button>

        <button type="button"
            :disabled="jalur === ''"
            :class="jalur !== ''
                    ? 'bg-gradient-to-r from-rose-600 to-orange-400 text-white shadow-md cursor-pointer hover:opacity-90'
                    : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
            class="px-6 py-2.5 text-sm font-bold rounded-xl flex items-center gap-2 transition-all"
            hx-get="{{ $stepUrls[4] }}"
            hx-target="#modal-body"
            hx-swap="innerHTML"
            hx-include="#step3-data [name]"
            @htmx:before-request="$dispatch('modal-step', { step: 4 })">
            Lanjut ke Jurusan <i data-lucide="arrow-right" class="size-4"></i>
        </button>
    </div>

</div>
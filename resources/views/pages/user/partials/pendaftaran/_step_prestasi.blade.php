{{-- _step_prestasi.blade.php --}}
{{--
|==========================================================================
| STEP PRESTASI — JENIS & DATA PRESTASI
|==========================================================================
| Hanya muncul jika jalur === 'prestasi' (dikontrol stepMap di parent).
|
| Arsitektur v2 — sistem pilih satu jenis (card list, radio):
|   1. Kejuaraan → pilih tingkat (kab/prov/nas/int) + kurasi
|   2. Tahfiz    → pilih lembaga kurasi
|   3. Kepemimpinan → pilih jabatan dari daftar + info SK
|   Semua jenis → verifikasi dokumen dilakukan langsung oleh panitia (tidak ada upload).
|   Tombol Lanjut aktif setelah jenis + detail selesai dipilih.
|
| State lokal (x-data di div ini — tidak perlu di parent):
|   jenis, tingkat, kurasi, jabatan
|==========================================================================
--}}

<div x-show="currentStepId === 'prestasi'"
    x-data="{
        jenis: '',
        tingkat: '',
        kurasi: '',
        jabatan: '',

        get detailLengkap() {
            if (this.jenis === 'kejuaraan')    return this.tingkat !== '' && this.kurasi !== '';
            if (this.jenis === 'tahfiz')       return this.kurasi  !== '';
            if (this.jenis === 'kepemimpinan') return this.jabatan !== '';
            return false;
        },

        resetDetail() {
            this.tingkat = ''; this.kurasi = ''; this.jabatan = '';
        },
    }"
    class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">

    {{-- ── HEADER ── --}}
    <div class="px-8 pt-6 pb-5 border-b border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-award text-amber-500 text-xl"></i>
        </div>
        <div class="flex-1">
            <h2 class="text-lg font-black text-[#080C1A]">Prestasi Akademik & Non-Akademik</h2>
            <p class="text-sm text-[#6A7686]">Pilih satu jenis prestasi terbaik yang akan dilaporkan</p>
        </div>
    </div>

    <div class="px-8 py-7 space-y-6">

        {{-- Info Alert --}}
        <div class="flex gap-3 items-start bg-amber-50 border border-amber-200 rounded-2xl px-4 py-3.5">
            <i class="fa-solid fa-lightbulb text-amber-500 text-base mt-0.5 flex-shrink-0"></i>
            <p class="text-sm font-medium text-amber-900 leading-relaxed">
                Pilih <strong>satu jenis prestasi terbaik</strong> yang ingin dilaporkan. Pastikan bukti atau
                sertifikat tersedia untuk dibawa saat verifikasi oleh panitia.
            </p>
        </div>

        {{-- ══════════════════════════════════════════════
             BAGIAN A — PILIH JENIS PRESTASI
             ══════════════════════════════════════════════ --}}
        <div>
            <div class="flex items-center gap-2 mb-4">
                <div class="w-6 h-6 rounded-md bg-amber-500 flex items-center justify-center flex-shrink-0">
                    <span class="text-white text-[10px] font-black">A</span>
                </div>
                <h3 class="text-[15px] font-black text-[#080C1A]">Jenis Prestasi</h3>
            </div>

            <div class="space-y-3">

                {{-- ① KEJUARAAN --}}
                <label class="cursor-pointer block">
                    <input type="radio" name="prestasi_jenis" value="kejuaraan"
                        x-model="jenis" @change="resetDetail()" class="sr-only">
                    <div :class="jenis === 'kejuaraan'
                            ? 'border-amber-400 bg-amber-50 ring-2 ring-amber-200'
                            : 'border-gray-200 bg-gray-50 hover:border-amber-300 hover:bg-amber-50/40'"
                        class="relative border-2 rounded-2xl p-4 transition-all duration-200">

                        <div x-show="jenis === 'kejuaraan'"
                            class="absolute top-4 right-4 w-6 h-6 rounded-full bg-amber-500 flex items-center justify-center shadow-sm">
                            <i class="fa-solid fa-check text-white text-[10px]"></i>
                        </div>

                        <div class="flex items-start gap-4 pr-8">
                            <div :class="jenis === 'kejuaraan' ? 'bg-amber-100' : 'bg-gray-200'"
                                class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 transition-colors duration-200">
                                <i :class="jenis === 'kejuaraan' ? 'text-amber-600' : 'text-gray-400'"
                                    class="fa-solid fa-trophy text-lg transition-colors duration-200"></i>
                            </div>
                            <div class="flex-1">
                                <div :class="jenis === 'kejuaraan' ? 'text-[#080C1A]' : 'text-gray-400'"
                                    class="text-[15px] font-black transition-colors duration-200">Kejuaraan Akademik / Non-Akademik</div>
                                <div :class="jenis === 'kejuaraan' ? 'text-[#6A7686]' : 'text-gray-400'"
                                    class="text-[12px] mt-0.5 leading-relaxed transition-colors duration-200">
                                    Kompetisi, olimpiade, atau lomba yang diselenggarakan secara resmi
                                </div>
                                <div class="flex flex-wrap gap-1.5 mt-2.5">
                                    @foreach(['Kab/Kota','Provinsi','Nasional','Internasional'] as $t)
                                    <span :class="jenis === 'kejuaraan' ? 'bg-amber-100 text-amber-700' : 'bg-gray-200 text-gray-400'"
                                        class="px-2 py-0.5 rounded-full text-[11px] font-bold transition-colors duration-200">{{ $t }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Detail tingkat & kurasi inline di dalam card --}}
                        <template x-if="jenis === 'kejuaraan'">
                            <div class="mt-4 pt-3 border-t border-amber-100 space-y-4">
                                {{-- Baris 1: Tingkat Kejuaraan --}}
                                <div>
                                    <p class="text-[11px] font-black text-amber-800 uppercase tracking-widest mb-2 flex items-center gap-1.5">
                                        <i class="fa-solid fa-layer-group text-amber-500 text-[10px]"></i> Tingkat Kejuaraan
                                    </p>
                                    <div class="grid grid-cols-2 gap-2">
                                        @php
                                        $tingkats = [
                                        ['value' => 'kabupaten', 'label' => 'Kabupaten / Kota', 'icon' => 'fa-city'],
                                        ['value' => 'provinsi', 'label' => 'Provinsi', 'icon' => 'fa-map'],
                                        ['value' => 'nasional', 'label' => 'Nasional', 'icon' => 'fa-flag'],
                                        ['value' => 'internasional','label' => 'Internasional', 'icon' => 'fa-earth-asia'],
                                        ];
                                        @endphp
                                        @foreach($tingkats as $t)
                                        <label class="cursor-pointer" @click.stop>
                                            <input type="radio" name="prestasi_tingkat"
                                                value="{{ $t['value'] }}" x-model="tingkat" class="sr-only">
                                            <div :class="tingkat === '{{ $t['value'] }}'
                                                    ? 'border-amber-400 bg-amber-100 ring-1 ring-amber-300'
                                                    : 'border-gray-200 bg-white hover:border-amber-300'"
                                                class="border-2 rounded-xl px-3 py-2 transition-all flex items-center gap-2">
                                                <i :class="tingkat === '{{ $t['value'] }}' ? 'text-amber-600' : 'text-gray-400'"
                                                    class="fa-solid {{ $t['icon'] }} text-[12px] flex-shrink-0 transition-colors"></i>
                                                <span :class="tingkat === '{{ $t['value'] }}' ? 'text-amber-900 font-black' : 'text-gray-500 font-semibold'"
                                                    class="text-[12px] transition-colors">{{ $t['label'] }}</span>
                                                <div x-show="tingkat === '{{ $t['value'] }}'"
                                                    class="ml-auto w-4 h-4 rounded-full bg-amber-500 flex items-center justify-center flex-shrink-0">
                                                    <i class="fa-solid fa-check text-white text-[7px]"></i>
                                                </div>
                                            </div>
                                        </label>
                                        @endforeach
                                    </div>
                                    <input type="hidden" name="prestasi_tingkat" :value="tingkat">
                                </div>
                                {{-- Baris 2: Jenis Kurasi (muncul setelah tingkat dipilih) --}}
                                <template x-if="tingkat !== ''">
                                    <div class="space-y-4">
                                        <div>
                                            <p class="text-[11px] font-black text-amber-800 uppercase tracking-widest mb-2 flex items-center gap-1.5">
                                                <i class="fa-solid fa-shield-halved text-amber-500 text-[10px]"></i> Jenis Kurasi
                                            </p>
                                            <div class="grid grid-cols-2 gap-2">
                                                @php
                                                $kurasisKej = [
                                                ['value' => 'simt_pusprenas', 'label' => 'SIMT / Pusprenas', 'icon' => 'fa-landmark'],
                                                ['value' => 'dikbudprov', 'label' => 'Dikbudprov', 'icon' => 'fa-school'],
                                                ];
                                                @endphp
                                                @foreach($kurasisKej as $k)
                                                <label class="cursor-pointer" @click.stop>
                                                    <input type="radio" name="prestasi_kurasi"
                                                        value="{{ $k['value'] }}" x-model="kurasi" class="sr-only">
                                                    <div :class="kurasi === '{{ $k['value'] }}'
                                                            ? 'border-amber-400 bg-amber-100 ring-1 ring-amber-300'
                                                            : 'border-gray-200 bg-white hover:border-amber-300'"
                                                        class="border-2 rounded-xl px-3 py-2 transition-all flex items-center gap-2">
                                                        <i :class="kurasi === '{{ $k['value'] }}' ? 'text-amber-600' : 'text-gray-400'"
                                                            class="fa-solid {{ $k['icon'] }} text-[12px] flex-shrink-0 transition-colors"></i>
                                                        <span :class="kurasi === '{{ $k['value'] }}' ? 'text-amber-900 font-black' : 'text-gray-500 font-semibold'"
                                                            class="text-[12px] transition-colors">{{ $k['label'] }}</span>
                                                        <div x-show="kurasi === '{{ $k['value'] }}'"
                                                            class="ml-auto w-4 h-4 rounded-full bg-amber-500 flex items-center justify-center flex-shrink-0">
                                                            <i class="fa-solid fa-check text-white text-[7px]"></i>
                                                        </div>
                                                    </div>
                                                </label>
                                                @endforeach
                                            </div>
                                            <input type="hidden" name="prestasi_kurasi" :value="kurasi">
                                        </div>

                                        {{-- Penegasan Dokumen Kejuaraan --}}
                                        <div class="flex gap-3 items-center bg-red-50 border border-red-200 rounded-xl px-4 py-2.5">
                                            <div class="w-7 h-7 rounded-lg bg-red-200 flex items-center justify-center flex-shrink-0">
                                                <i class="fa-solid fa-file-signature text-red-600 text-[11px]"></i>
                                            </div>
                                            <div class="flex flex-col space-y-0.5">
                                                <p class="text-[12px] text-red-950 leading-tight">
                                                    <span class="font-normal text-red-700">Dibuktikan dengan</span>
                                                    <span class="font-black">Sertifikat / Piagam Kejuaraan Resmi</span>
                                                </p>
                                                <p class="text-[12px] text-red-950 leading-tight">
                                                    <span class="font-normal text-red-700">Dokumen pendukung harus dibawa saat verifikasi</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <template x-if="jenis !== 'kejuaraan'">
                            <div class="mt-3 pt-3 border-t border-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-shield-halved text-gray-300 text-[11px]"></i>
                                <span class="text-[11px] text-gray-400 font-medium">Kurasi: SIMT / Pusprenas · Dikbudprov</span>
                            </div>
                        </template>
                    </div>
                </label>

                {{-- ② TAHFIZ --}}
                <label class="cursor-pointer block">
                    <input type="radio" name="prestasi_jenis" value="tahfiz"
                        x-model="jenis" @change="resetDetail()" class="sr-only">
                    <div :class="jenis === 'tahfiz'
                            ? 'border-amber-400 bg-amber-50 ring-2 ring-amber-200'
                            : 'border-gray-200 bg-gray-50 hover:border-amber-300 hover:bg-amber-50/40'"
                        class="relative border-2 rounded-2xl p-4 transition-all duration-200">

                        <div x-show="jenis === 'tahfiz'"
                            class="absolute top-4 right-4 w-6 h-6 rounded-full bg-amber-500 flex items-center justify-center shadow-sm">
                            <i class="fa-solid fa-check text-white text-[10px]"></i>
                        </div>

                        <div class="flex items-start gap-4 pr-8">
                            <div :class="jenis === 'tahfiz' ? 'bg-amber-100' : 'bg-gray-200'"
                                class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 transition-colors duration-200">
                                <i :class="jenis === 'tahfiz' ? 'text-amber-600' : 'text-gray-400'"
                                    class="fa-solid fa-book-quran text-lg transition-colors duration-200"></i>
                            </div>
                            <div class="flex-1">
                                <div :class="jenis === 'tahfiz' ? 'text-[#080C1A]' : 'text-gray-400'"
                                    class="text-[15px] font-black transition-colors duration-200">Tahfiz Al-Qur'an</div>
                                <div :class="jenis === 'tahfiz' ? 'text-[#6A7686]' : 'text-gray-400'"
                                    class="text-[12px] mt-0.5 leading-relaxed transition-colors duration-200">
                                    Hafalan Al-Qur'an yang dibuktikan dengan sertifikat dari lembaga resmi
                                </div>
                                <div class="flex flex-wrap gap-1.5 mt-2.5">
                                    <span :class="jenis === 'tahfiz' ? 'bg-amber-100 text-amber-700' : 'bg-gray-200 text-gray-400'"
                                        class="px-2 py-0.5 rounded-full text-[11px] font-bold transition-colors duration-200">Akademik & Non-Akademik</span>
                                </div>
                            </div>
                        </div>

                        {{-- Detail lembaga kurasi langsung di dalam card --}}
                        <template x-if="jenis === 'tahfiz'">
                            <div class="mt-4 pt-3 border-t border-amber-100 space-y-4">
                                <div>
                                    <p class="text-[11px] font-black text-amber-800 uppercase tracking-widest mb-2 flex items-center gap-1.5">
                                        <i class="fa-solid fa-shield-halved text-amber-500 text-[10px]"></i> Pilih Lembaga Kurasi
                                    </p>
                                    <div class="grid grid-cols-2 gap-2">
                                        @php
                                        $kurasisTahfiz = [
                                        ['value' => 'simt_pusprenas', 'label' => 'SIMT / Pusprenas', 'icon' => 'fa-landmark', 'sub' => 'Pusat Prestasi Nasional'],
                                        ['value' => 'dikbudprov', 'label' => 'Dikbudprov', 'icon' => 'fa-school', 'sub' => 'Dinas Pendidikan Provinsi'],
                                        ];
                                        @endphp
                                        @foreach($kurasisTahfiz as $k)
                                        <label class="cursor-pointer" @click.stop>
                                            <input type="radio" name="prestasi_kurasi"
                                                value="{{ $k['value'] }}" x-model="kurasi" class="sr-only">
                                            <div :class="kurasi === '{{ $k['value'] }}'
                                                    ? 'border-amber-400 bg-amber-100 ring-1 ring-amber-300'
                                                    : 'border-gray-200 bg-white hover:border-amber-300'"
                                                class="border-2 rounded-xl px-3 py-2 transition-all">
                                                <div class="flex items-center gap-2">
                                                    <i :class="kurasi === '{{ $k['value'] }}' ? 'text-amber-600' : 'text-gray-400'"
                                                        class="fa-solid {{ $k['icon'] }} text-[12px] flex-shrink-0 transition-colors"></i>
                                                    <div class="flex-1 min-w-0">
                                                        <div :class="kurasi === '{{ $k['value'] }}' ? 'text-amber-900 font-black' : 'text-gray-500 font-semibold'"
                                                            class="text-[12px] transition-colors">{{ $k['label'] }}</div>
                                                        <div class="text-[10px] text-gray-400 leading-tight truncate">{{ $k['sub'] }}</div>
                                                    </div>
                                                    <div x-show="kurasi === '{{ $k['value'] }}'"
                                                        class="w-4 h-4 rounded-full bg-amber-500 flex items-center justify-center flex-shrink-0">
                                                        <i class="fa-solid fa-check text-white text-[7px]"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                        @endforeach
                                    </div>
                                    <input type="hidden" name="prestasi_kurasi" :value="kurasi">
                                </div>

                                {{-- Penegasan Dokumen Tahfiz --}}
                                <div class="flex gap-3 items-center bg-red-50 border border-red-200 rounded-xl px-4 py-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-red-200 flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid fa-file-signature text-red-600 text-[11px]"></i>
                                    </div>
                                    <div class="flex flex-col space-y-0.5">
                                        <p class="text-[12px] text-red-950 leading-tight">
                                            <span class="font-normal text-red-700">Dibuktikan dengan</span>
                                            <span class="font-black">Piagam / Sertifikat Tahfiz Resmi</span>
                                        </p>
                                        <p class="text-[12px] text-red-950 leading-tight">
                                            <span class="font-normal text-red-700">Dokumen pendukung harus dibawa saat verifikasi</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <template x-if="jenis !== 'tahfiz'">
                            <div class="mt-3 pt-3 border-t border-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-shield-halved text-gray-300 text-[11px]"></i>
                                <span class="text-[11px] text-gray-400 font-medium">Kurasi: SIMT / Pusprenas · Dikbudprov</span>
                            </div>
                        </template>
                    </div>
                </label>

                {{-- ③ KEPEMIMPINAN --}}
                <label class="cursor-pointer block">
                    <input type="radio" name="prestasi_jenis" value="kepemimpinan"
                        x-model="jenis" @change="resetDetail()" class="sr-only">
                    <div :class="jenis === 'kepemimpinan'
                            ? 'border-amber-400 bg-amber-50 ring-2 ring-amber-200'
                            : 'border-gray-200 bg-gray-50 hover:border-amber-300 hover:bg-amber-50/40'"
                        class="relative border-2 rounded-2xl p-4 transition-all duration-200">

                        <div x-show="jenis === 'kepemimpinan'"
                            class="absolute top-4 right-4 w-6 h-6 rounded-full bg-amber-500 flex items-center justify-center shadow-sm">
                            <i class="fa-solid fa-check text-white text-[10px]"></i>
                        </div>

                        <div class="flex items-start gap-4 pr-8">
                            <div :class="jenis === 'kepemimpinan' ? 'bg-amber-100' : 'bg-gray-200'"
                                class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 transition-colors duration-200">
                                <i :class="jenis === 'kepemimpinan' ? 'text-amber-600' : 'text-gray-400'"
                                    class="fa-solid fa-users-gear text-lg transition-colors duration-200"></i>
                            </div>
                            <div class="flex-1">
                                <div :class="jenis === 'kepemimpinan' ? 'text-[#080C1A]' : 'text-gray-400'"
                                    class="text-[15px] font-black transition-colors duration-200">Kepemimpinan Siswa</div>
                                <div :class="jenis === 'kepemimpinan' ? 'text-[#6A7686]' : 'text-gray-400'"
                                    class="text-[12px] mt-0.5 leading-relaxed transition-colors duration-200">
                                    Jabatan ketua dalam organisasi kesiswaan yang diakui sekolah / madrasah
                                </div>
                                <div class="flex flex-wrap gap-1.5 mt-2.5">
                                    @foreach(['OSIS','OSIM','MPK','Pramuka','Dewan Ambalan','BES'] as $org)
                                    <span :class="jenis === 'kepemimpinan' ? 'bg-amber-100 text-amber-700' : 'bg-gray-200 text-gray-400'"
                                        class="px-2 py-0.5 rounded-full text-[11px] font-bold transition-colors duration-200">{{ $org }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Detail jabatan langsung di dalam card --}}
                        <template x-if="jenis === 'kepemimpinan'">
                            <div class="mt-4 pt-3 border-t border-amber-100 space-y-4">
                                <div>
                                    <p class="text-[11px] font-black text-amber-800 uppercase tracking-widest flex items-center gap-1.5 mb-2">
                                        <i class="fa-solid fa-user-tie text-amber-500 text-[10px]"></i> Pilih Jabatan Organisasi
                                    </p>
                                    <div class="space-y-2">
                                        @php
                                        $jabatansInCard = [
                                        ['value' => 'ketua_osis', 'label' => 'Ketua OSIS', 'sub' => 'Organisasi Siswa Intra Sekolah'],
                                        ['value' => 'ketua_osim', 'label' => 'Ketua OSIM', 'sub' => 'Organisasi Siswa Intra Madrasah'],
                                        ['value' => 'ketua_mpk', 'label' => 'Ketua MPK', 'sub' => 'Majelis Perwakilan Kelas'],
                                        ['value' => 'ketua_pramuka', 'label' => 'Ketua Pramuka / Kepanduan', 'sub' => 'Ketua gugus atau racana'],
                                        ['value' => 'ketua_ambalan', 'label' => 'Ketua Dewan Ambalan Pramuka', 'sub' => 'Dewan Ambalan Penegak'],
                                        ['value' => 'ketua_bes', 'label' => 'Ketua Badan Eksekutif Siswa', 'sub' => 'BES setara OSIS di satuan pendidikan'],
                                        ];
                                        @endphp
                                        @foreach($jabatansInCard as $j)
                                        <label class="cursor-pointer block" @click.stop>
                                            <input type="radio" name="prestasi_jabatan"
                                                value="{{ $j['value'] }}" x-model="jabatan" class="sr-only">
                                            <div :class="jabatan === '{{ $j['value'] }}'
                                                    ? 'border-amber-400 bg-amber-100 ring-1 ring-amber-300'
                                                    : 'border-gray-200 bg-white hover:border-amber-300'"
                                                class="border-2 rounded-xl px-4 py-2.5 transition-all flex items-center gap-3">
                                                <div :class="jabatan === '{{ $j['value'] }}' ? 'bg-amber-200' : 'bg-gray-100'"
                                                    class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 transition-colors">
                                                    <i :class="jabatan === '{{ $j['value'] }}' ? 'text-amber-700' : 'text-gray-400'"
                                                        class="fa-solid fa-user-tie text-[11px] transition-colors"></i>
                                                </div>
                                                <div class="flex-1">
                                                    <div :class="jabatan === '{{ $j['value'] }}' ? 'text-amber-900 font-black' : 'text-gray-500 font-semibold'"
                                                        class="text-[12px] transition-colors">{{ $j['label'] }}</div>
                                                    <div class="text-[10px] text-gray-400 mt-0.5">{{ $j['sub'] }}</div>
                                                </div>
                                                <div x-show="jabatan === '{{ $j['value'] }}'"
                                                    class="w-4 h-4 rounded-full bg-amber-500 flex items-center justify-center flex-shrink-0">
                                                    <i class="fa-solid fa-check text-white text-[7px]"></i>
                                                </div>
                                            </div>
                                        </label>
                                        @endforeach
                                    </div>
                                    <input type="hidden" name="prestasi_jabatan" :value="jabatan">
                                </div>

                                {{-- Penegasan Dokumen Kepemimpinan (Ikon Dirapikan Berkotak) --}}
                                <div class="flex gap-3 items-center bg-red-50 border border-red-200 rounded-xl px-4 py-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-red-200 flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid fa-file-signature text-red-600 text-[11px]"></i>
                                    </div>
                                    <div class="flex flex-col space-y-0.5">
                                        <p class="text-[12px] text-red-950 leading-tight">
                                            <span class="font-normal text-red-700">Dibuktikan dengan</span>
                                            <span class="font-black">SK Kepala Sekolah / Madrasah</span>
                                        </p>
                                        <p class="text-[12px] text-red-950 leading-tight">
                                            <span class="font-normal text-red-700">Dokumen pendukung harus dibawa saat verifikasi</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <template x-if="jenis !== 'kepemimpinan'">
                            <div class="mt-3 pt-3 border-t border-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-file-signature text-gray-300 text-[11px]"></i>
                                <span class="text-[11px] text-gray-400 font-medium">Dibuktikan dengan SK Kepala Sekolah / Madrasah</span>
                            </div>
                        </template>
                    </div>
                </label>

            </div>
        </div>

    </div>{{-- /space-y-6 --}}

    {{-- ── FOOTER NAV ── --}}
    <div class="px-8 py-5 border-t border-gray-200 bg-gray-50/50 flex items-center justify-between rounded-b-[20px]">
        <button type="button" @click="step--"
            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-[#6A7686] border border-gray-200 rounded-full hover:border-[#080C1A] hover:text-[#080C1A] transition-all">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </button>
        <div class="flex items-center gap-3">
            <button type="button" onclick="saveDraft()"
                class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-[#6A7686] border border-gray-200 rounded-full hover:border-[#080C1A] hover:text-[#080C1A] transition-all">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Draft
            </button>
            <button type="button"
                @click="step++"
                :disabled="!detailLengkap"
                :class="detailLengkap
                    ? 'bg-[#FF1443] hover:bg-[#D90F38] hover:-translate-y-px shadow-lg shadow-red-500/30 cursor-pointer'
                    : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
                class="inline-flex items-center gap-2 px-8 py-2.5 text-white text-sm font-black rounded-full transition-all">
                Lanjut <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>
    </div>

</div>{{-- /step prestasi --}}
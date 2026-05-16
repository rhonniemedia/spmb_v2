{{-- _step_afirmasi_dok.blade.php --}}
{{--
|==========================================================================
| STEP AFIRMASI — DOKUMEN AFIRMASI
|==========================================================================
| Hanya muncul jika jalur === 'afirmasi' (dikontrol stepMap di parent).
|
| Alur:
|   1. User memilih jenis dokumen: KIP, PKH, atau Surat Keterangan
|   2. Upload file dokumen → preview nama file + validasi format/ukuran
|   3. Opsional: upload surat keterangan tidak mampu dari kelurahan
|   4. HTMX POST /pendaftaran/afirmasi/upload saat file dipilih
|      → backend validasi & simpan sementara → return status
|   5. Tombol Lanjut aktif setelah dokumen utama berhasil diupload
|
| State yang harus ada di parent x-data:
|   dokumenAfirmasiOk: false   ← di-set true setelah upload berhasil
|==========================================================================
--}}

<div x-show="currentStepId === 'afirmasi_dok'"
    x-data="{
        nomorSktm: '',
        pakaiKartu: false,
        jenisKartu: '',
        nomorKartu: '',

        get bolehLanjut() {
            return this.nomorSktm.trim() !== '';
        },
    }"
    class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">

    {{-- ── HEADER ── --}}
    <div class="px-8 pt-6 pb-5 border-b border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-file-shield text-indigo-600 text-xl"></i>
        </div>
        <div>
            <h2 class="text-lg font-black text-[#080C1A]">Dokumen Afirmasi</h2>
            <p class="text-sm text-[#6A7686]">Unggah bukti kelayakan jalur afirmasi untuk diverifikasi panitia</p>
        </div>
    </div>

    <div class="px-8 py-7 space-y-6">

        {{-- ── BAGIAN A: DOKUMEN YANG DIPERLUKAN ── --}}
        <div>
            <div class="flex items-center gap-2 mb-4">
                <div class="w-6 h-6 rounded-md bg-indigo-600 flex items-center justify-center flex-shrink-0">
                    <span class="text-white text-[10px] font-black">A</span>
                </div>
                <h3 class="text-[15px] font-black text-[#080C1A]">Surat Keterangan Tidak Mampu (SKTM)</h3>
            </div>

            <div class="space-y-3">

                {{-- ① SKTM — WAJIB --}}
                <div class="border-2 border-indigo-300 bg-indigo-50 rounded-2xl overflow-hidden">
                    <div class="flex items-center gap-4 px-5 py-4">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-file-lines text-indigo-600 text-base"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-[14px] font-black text-[#080C1A]">SKTM</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-indigo-600 text-white tracking-wide">WAJIB</span>
                            </div>
                            <div class="text-[12px] text-[#6A7686] mt-0.5">Surat Keterangan Tidak Mampu dari Kelurahan / Desa</div>
                        </div>
                        <i class="fa-solid fa-circle-check text-indigo-500 text-xl flex-shrink-0"></i>
                    </div>
                    <div class="px-5 pb-4">
                        <label class="block text-[11px] font-black text-indigo-700 uppercase tracking-widest mb-1.5">
                            Nomor Surat <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                            name="nomor_sktm"
                            x-model="nomorSktm"
                            placeholder="Contoh: 470/123/KEL/2024"
                            autocomplete="off"
                            class="w-full border-2 rounded-xl px-4 py-2.5 text-[13px] font-medium text-[#080C1A] placeholder-gray-400 outline-none transition-all
                                   focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                            :class="nomorSktm.trim() !== '' ? 'border-indigo-400 bg-white' : 'border-gray-200 bg-white'">
                        <p x-show="nomorSktm.trim() === ''" class="mt-1.5 text-[11px] text-red-500 flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation text-[10px]"></i> Nomor surat wajib diisi
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 mt-4 mb-2">
                <div class="w-6 h-6 rounded-md bg-indigo-600 flex items-center justify-center flex-shrink-0">
                    <span class="text-white text-[10px] font-black">B</span>
                </div>
                <h3 class="text-[15px] font-black text-[#080C1A]">Kartu Penerima Bantuan Sosial</h3>
            </div>

            <div class="space-y-3">

                {{-- ② KARTU (PKH / KIP / sejenisnya) — OPSIONAL --}}
                <div :class="pakaiKartu
                        ? 'border-indigo-300 bg-indigo-50'
                        : 'border-gray-200 bg-gray-50 hover:border-indigo-200 hover:bg-indigo-50/30'"
                    class="border-2 rounded-2xl overflow-hidden transition-all duration-200">

                    {{-- Toggle header --}}
                    <label class="flex items-center gap-4 px-5 py-4 cursor-pointer select-none">
                        <div :class="pakaiKartu ? 'bg-indigo-100' : 'bg-gray-200'"
                            class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 transition-colors duration-200">
                            <i :class="pakaiKartu ? 'text-indigo-600' : 'text-gray-400'"
                                class="fa-solid fa-id-card text-base transition-colors duration-200"></i>
                        </div>
                        <input type="hidden" name="pakai_kartu" :value="pakaiKartu ? 1 : 0">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span :class="pakaiKartu ? 'text-[#080C1A]' : 'text-gray-500'"
                                    class="text-[14px] font-black transition-colors duration-200">Kartu Bantuan Sosial</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-gray-300 text-gray-600 tracking-wide">OPSIONAL</span>
                            </div>
                            <div :class="pakaiKartu ? 'text-[#6A7686]' : 'text-gray-400'"
                                class="text-[12px] mt-0.5 transition-colors duration-200">PKH, KIP, KPS, DTKS, atau kartu sejenisnya</div>
                        </div>
                        <div class="flex-shrink-0">
                            <input type="checkbox" x-model="pakaiKartu" class="sr-only">
                            <div :class="pakaiKartu ? 'bg-indigo-500' : 'bg-gray-300'"
                                class="w-11 h-6 rounded-full transition-colors duration-200 relative">
                                <div :class="pakaiKartu ? 'translate-x-5' : 'translate-x-0.5'"
                                    class="absolute top-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200"></div>
                            </div>
                        </div>
                    </label>

                    {{-- Input nomor kartu, muncul jika toggle aktif --}}
                    <div x-show="pakaiKartu"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="px-5 pb-4 space-y-3">

                        {{-- Pilih jenis kartu --}}
                        <div class="flex flex-wrap gap-2">
                            @foreach([
                            ['value' => 'pkh', 'label' => 'PKH'],
                            ['value' => 'kip', 'label' => 'KIP'],
                            ['value' => 'kps', 'label' => 'KPS'],
                            ['value' => 'dtks','label' => 'DTKS'],
                            ['value' => 'lain','label' => 'Lainnya'],
                            ] as $k)
                            <label class="cursor-pointer" @click.stop>
                                <input type="radio" name="jenis_kartu" value="{{ $k['value'] }}"
                                    x-model="jenisKartu" class="sr-only">
                                <span :class="jenisKartu === '{{ $k['value'] }}'
                                        ? 'bg-indigo-600 text-white border-indigo-600'
                                        : 'bg-white text-gray-600 border-gray-300 hover:border-indigo-400'"
                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-[12px] font-black border-2 transition-all">
                                    {{ $k['label'] }}
                                </span>
                            </label>
                            @endforeach
                        </div>

                        {{-- Input nomor kartu --}}
                        <div>
                            <label class="block text-[11px] font-black text-indigo-700 uppercase tracking-widest mb-1.5">
                                Nomor Kartu
                            </label>
                            <input type="text"
                                name="nomor_kartu"
                                x-model="nomorKartu"
                                placeholder="Nomor yang tertera pada kartu"
                                autocomplete="off"
                                class="w-full border-2 rounded-xl px-4 py-2.5 text-[13px] font-medium text-[#080C1A] placeholder-gray-400 outline-none transition-all
                                       focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                                :class="nomorKartu.trim() !== '' ? 'border-indigo-400 bg-white' : 'border-gray-200 bg-white'">
                        </div>
                        <input type="hidden" name="jenis_kartu" :value="jenisKartu">
                    </div>
                </div>

            </div>
        </div>

        {{-- Alert verifikasi dokumen --}}
        <div class="flex gap-3 items-start bg-red-50 border border-red-300 rounded-2xl px-4 py-3.5">
            <i class="fa-solid fa-triangle-exclamation text-red-500 text-base mt-0.5 flex-shrink-0"></i>
            <p class="text-sm font-semibold text-red-800 leading-relaxed">
                Seluruh dokumen pendukung <strong>(SKTM, kartu, dan sejenisnya) harus dibawa saat verifikasi</strong>
            </p>
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

            {{-- Disabled sampai nomor SKTM diisi --}}
            <button type="button"
                @click="step++"
                :disabled="!bolehLanjut"
                :class="bolehLanjut
                    ? 'bg-[#FF1443] hover:bg-[#D90F38] hover:-translate-y-px shadow-lg shadow-red-500/30 cursor-pointer'
                    : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
                class="inline-flex items-center gap-2 px-8 py-2.5 text-white text-sm font-black rounded-full transition-all">
                Lanjut <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>
    </div>

</div>{{-- /step afirmasi dok --}}
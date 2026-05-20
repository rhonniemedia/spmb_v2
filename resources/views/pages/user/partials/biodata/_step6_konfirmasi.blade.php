<!-- STEP 6 — KONFIRMASI -->

{{--
|==========================================================================
| STEP 6 — KONFIRMASI & KIRIM
|==========================================================================
| Perubahan besar dari blade original:
|   - Summary card yang hardcode (nama "Ahmad Fauzi", dll) DIHAPUS
|   - Diganti dengan #summary-container yang diisi oleh HTMX
|     saat Alpine step === 6 via GET /biodata/summary
|   - Konten ringkasan dirender server dari data DB yang sebenarnya
|   - Pernyataan checkbox dan tombol kirim tetap ada
|   - id="step6-submit" untuk HTMX POST /biodata/submit (⑫-C)
|
| Perubahan v2:
|   - Checkbox pernyataan kini custom styled (Alpine x-model)
|   - Tombol "Kirim Biodata Sekarang" disabled sampai ketiga checkbox dicentang
|   - Hint muncul saat belum semua dicentang
|   - Badge progress "x/3 disetujui" di header pernyataan
|==========================================================================
--}}

<div x-show="step === 6"
    class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">

    {{-- Header --}}
    <div class="px-8 pt-6 border-gray-200 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-clipboard-check text-green-600 text-xl"></i>
        </div>
        <div>
            <h2 class="text-lg font-black text-[#080C1A]">Konfirmasi & Kirim</h2>
            <p class="text-sm text-[#6A7686]">Tinjau kembali data sebelum dikirimkan</p>
        </div>
    </div>

    <div class="px-8 py-7 space-y-5">

        {{-- Warning --}}
        <div class="flex gap-3 items-start bg-red-50 border border-red-200 rounded-2xl px-4 py-3.5">
            <i class="fa-solid fa-triangle-exclamation text-red-500 text-base mt-0.5 flex-shrink-0"></i>
            <p class="text-sm font-medium text-red-800 leading-relaxed">
                Setelah data dikirim, perubahan hanya dapat dilakukan melalui panitia SPMB.
                Pastikan semua data sudah benar.
            </p>
        </div>

        {{--
            Summary container — diisi HTMX GET /biodata/summary
        --}}
        <div id="summary-container"
            hx-get="{{ route('biodata.summary') }}"
            hx-trigger="refreshResume from:body"
            hx-swap="innerHTML"
            hx-indicator="#summary-container"
            class="space-y-6">

            {{-- Loading skeleton --}}
            <div class="htmx-indicator space-y-3">
                <div class="h-10 bg-gray-200 rounded-xl animate-pulse"></div>
                <div class="h-24 bg-gray-200 rounded-xl animate-pulse"></div>
                <div class="h-24 bg-gray-200 rounded-xl animate-pulse"></div>
                <div class="h-16 bg-gray-200 rounded-xl animate-pulse"></div>
            </div>

        </div>

        {{--
            Pernyataan Peserta + Footer — dalam satu x-data scope
            agar tombol submit bisa langsung baca state check1/2/3
        --}}
        <div x-data="{ check1: false, check2: false, check3: false }"
            class="space-y-0">

            {{-- Card Pernyataan --}}
            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5 space-y-4">

                {{-- Header pernyataan + badge progress --}}
                <div class="flex items-center justify-between">
                    <p class="text-sm font-bold text-[#080C1A]">Pernyataan Peserta</p>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full transition-all duration-300"
                        :class="(check1 && check2 && check3)
                            ? 'bg-green-100 text-green-700'
                            : 'bg-gray-200 text-gray-500'">
                        <i class="fa-solid fa-circle-check mr-1"
                            x-show="check1 && check2 && check3"></i>
                        <span x-text="[check1, check2, check3].filter(Boolean).length"></span>/3 disetujui
                    </span>
                </div>

                {{-- Checkbox 1 --}}
                <label class="flex items-start gap-3 cursor-pointer group">
                    <div class="mt-0.5 flex-shrink-0 w-5 h-5 rounded border-2 transition-all duration-200 flex items-center justify-center"
                        :class="check1
                            ? 'bg-green-600 border-green-600'
                            : 'border-gray-300 bg-white group-hover:border-green-400'">
                        <i class="fa-solid fa-check text-white text-[10px] transition-opacity"
                            :class="check1 ? 'opacity-100' : 'opacity-0'"></i>
                    </div>
                    <input type="checkbox" x-model="check1" class="sr-only">
                    <span class="text-sm leading-relaxed transition-colors duration-200"
                        :class="check1 ? 'text-[#080C1A]' : 'text-[#6A7686]'">
                        Saya menyatakan bahwa semua data dan dokumen yang saya isi adalah
                        <strong>benar dan sesuai aslinya</strong>.
                    </span>
                </label>

                {{-- Checkbox 2 --}}
                <label class="flex items-start gap-3 cursor-pointer group">
                    <div class="mt-0.5 flex-shrink-0 w-5 h-5 rounded border-2 transition-all duration-200 flex items-center justify-center"
                        :class="check2
                            ? 'bg-green-600 border-green-600'
                            : 'border-gray-300 bg-white group-hover:border-green-400'">
                        <i class="fa-solid fa-check text-white text-[10px] transition-opacity"
                            :class="check2 ? 'opacity-100' : 'opacity-0'"></i>
                    </div>
                    <input type="checkbox" x-model="check2" class="sr-only">
                    <span class="text-sm leading-relaxed transition-colors duration-200"
                        :class="check2 ? 'text-[#080C1A]' : 'text-[#6A7686]'">
                        Saya menyetujui <strong>Syarat & Ketentuan</strong> serta
                        <strong>Kebijakan Privasi</strong> SPMB SMK Negeri 1 Rejang Lebong.
                    </span>
                </label>

                {{-- Checkbox 3 --}}
                <label class="flex items-start gap-3 cursor-pointer group">
                    <div class="mt-0.5 flex-shrink-0 w-5 h-5 rounded border-2 transition-all duration-200 flex items-center justify-center"
                        :class="check3
                            ? 'bg-green-600 border-green-600'
                            : 'border-gray-300 bg-white group-hover:border-green-400'">
                        <i class="fa-solid fa-check text-white text-[10px] transition-opacity"
                            :class="check3 ? 'opacity-100' : 'opacity-0'"></i>
                    </div>
                    <input type="checkbox" x-model="check3" class="sr-only">
                    <span class="text-sm leading-relaxed transition-colors duration-200"
                        :class="check3 ? 'text-[#080C1A]' : 'text-[#6A7686]'">
                        Saya bersedia menerima sanksi jika dikemudian hari ditemukan
                        <strong>pemalsuan data</strong>.
                    </span>
                </label>

            </div>{{-- /card pernyataan --}}

            {{-- ── Footer Navigasi Step 6 — masih dalam x-data scope ── --}}
            <div class="pt-5 border-t border-gray-200 -mx-8 px-8 pb-0 mt-5">

                {{-- Hint saat belum semua dicentang --}}
                <div x-show="!(check1 && check2 && check3)"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-1"
                    class="flex items-center gap-2 text-xs text-amber-600 font-medium mb-4">
                    <i class="fa-solid fa-circle-info flex-shrink-0"></i>
                    Centang semua pernyataan di atas untuk mengaktifkan tombol kirim.
                </div>

                <div class="flex items-center justify-between py-5">
                    <button type="button" @click="step--"
                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-[#6A7686] border border-gray-200 rounded-full hover:border-[#080C1A] hover:text-[#080C1A] transition-all">
                        <i class="fa-solid fa-arrow-left"></i> Kembali
                    </button>

                    <button type="button"
                        hx-post="{{ route('biodata.submit') }}"
                        hx-indicator="#biodata-form"
                        hx-swap="none"
                        @htmx:after-request="if($event.detail.successful){ let r=JSON.parse($event.detail.xhr.response); if(r.success){ submitResult=r; isSubmitted=true } }"
                        :disabled="!(check1 && check2 && check3)"
                        :class="(check1 && check2 && check3)
                            ? 'bg-green-600 hover:bg-green-700 hover:-translate-y-px shadow-lg shadow-green-500/30 cursor-pointer'
                            : 'bg-gray-200 text-gray-400 cursor-not-allowed shadow-none pointer-events-none'"
                        class="inline-flex items-center gap-2 px-8 py-2.5 text-sm font-black rounded-full text-white transition-all duration-200">
                        <span hx-dis-indicator>
                            <i class="fa-solid fa-paper-plane mr-2"></i> Kirim Biodata Sekarang
                        </span>
                        <span id="next-indicator" class="htmx-indicator gap-2">
                            <i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...
                        </span>
                    </button>
                </div>

            </div>{{-- /footer --}}

        </div>{{-- /x-data pernyataan --}}

    </div>

</div>{{-- /step 6 --}}
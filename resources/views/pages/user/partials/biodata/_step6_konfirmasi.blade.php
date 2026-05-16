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
            saat Alpine step berubah ke 6 (lihat implementasi di ⑫-C).

            Loading state ditampilkan selama HTMX request berlangsung
            via class htmx-indicator yang dikelola HTMX otomatis.
        --}}

        <div id="summary-container"
            hx-get="{{ route('biodata.summary') }}"
            hx-trigger="refreshResume from:body"
            hx-swap="innerHTML"
            hx-indicator="#summary-container"
            class="space-y-6">

            {{-- Loading skeleton — tampil sebelum HTMX response datang --}}
            <div class="htmx-indicator space-y-3">
                <div class="h-10 bg-gray-200 rounded-xl animate-pulse"></div>
                <div class="h-24 bg-gray-200 rounded-xl animate-pulse"></div>
                <div class="h-24 bg-gray-200 rounded-xl animate-pulse"></div>
                <div class="h-16 bg-gray-200 rounded-xl animate-pulse"></div>
            </div>

        </div>

        {{-- Pernyataan Peserta --}}
        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5 space-y-3">
            <p class="text-sm font-bold text-[#080C1A]">Pernyataan Peserta</p>
            <label class="flex items-start gap-3 cursor-pointer">
                <input type="checkbox" id="check1" required class="mt-1 w-4 h-4 accent-primary">
                <span class="text-sm text-[#6A7686] leading-relaxed">
                    Saya menyatakan bahwa semua data dan dokumen yang saya isi adalah
                    <strong>benar dan sesuai aslinya</strong>.
                </span>
            </label>
            <label class="flex items-start gap-3 cursor-pointer">
                <input type="checkbox" id="check2" required class="mt-1 w-4 h-4 accent-primary">
                <span class="text-sm text-[#6A7686] leading-relaxed">
                    Saya menyetujui <strong>Syarat & Ketentuan</strong> serta
                    <strong>Kebijakan Privasi</strong> SPMB SMK Negeri 1.
                </span>
            </label>
            <label class="flex items-start gap-3 cursor-pointer">
                <input type="checkbox" id="check3" required class="mt-1 w-4 h-4 accent-primary">
                <span class="text-sm text-[#6A7686] leading-relaxed">
                    Saya bersedia menerima sanksi jika dikemudian hari ditemukan
                    <strong>pemalsuan data</strong>.
                </span>
            </label>
        </div>

    </div>

    {{-- ── Footer Navigasi Step 6 ──────────────────────────────────────── --}}
    <div class="px-8 py-5 border-t border-gray-200 bg-gray-50/50 flex items-center justify-between rounded-b-[20px]">
        <button type="button" @click="step--"
            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-[#6A7686] border border-gray-200 rounded-full hover:border-[#080C1A] hover:text-[#080C1A] transition-all">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </button>

        <button type="button"
            hx-post="{{ route('biodata.submit') }}"
            hx-indicator="#biodata-form"
            hx-swap="none"
            @htmx:after-request="if($event.detail.successful){ let r=JSON.parse($event.detail.xhr.response); if(r.success) isSubmitted=true }"
            class="inline-flex items-center gap-2 px-8 py-2.5 bg-green-600 text-white text-sm font-black rounded-full hover:bg-green-700 hover:-translate-y-px transition-all shadow-lg shadow-green-500/30">
            <span hx-dis-indicator>
                <i class="fa-solid fa-paper-plane mr-2"></i> Kirim Biodata Sekarang
            </span>

            <span id="next-indicator" class="htmx-indicator gap-2">
                <i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...
            </span>
        </button>
    </div>

</div>{{-- /step 6 --}}
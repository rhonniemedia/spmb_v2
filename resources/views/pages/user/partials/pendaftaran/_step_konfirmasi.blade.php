{{-- _step_konfirmasi.blade.php --}}
{{--
|==========================================================================
| STEP KONFIRMASI — KONFIRMASI & KIRIM
|==========================================================================
| Step terakhir, dilalui semua jalur.
|
| Perubahan dari versi lama (_step5_konfirmasi.blade.php):
|   1. x-show="step === 5" → x-show="currentStepId === 'konfirmasi'"
|   2. HTMX summary di-trigger saat currentStepId berubah ke 'konfirmasi'
|      menggunakan x-effect (bukan hardcode trigger step 5).
|   3. Ringkasan jalur & jurusan ditampilkan LANGSUNG dari Alpine state
|      (jalur, pil1, pil2, pil3, jurusanList) — tidak perlu tunggu server.
|      Server summary tetap dipakai untuk bagian nilai rapor & TKA
|      karena data nilai tidak disimpan di Alpine.
|   4. Blok prestasi muncul kondisional: hanya jika jalur === 'prestasi'.
|   5. Checkbox pernyataan dikontrol Alpine: tombol Kirim disabled
|      sampai ketiga checkbox dicentang.
|
| State dari parent yang dipakai:
|   jalur, pil1, pil2, pil3, jurusanList, prestasiList, isSubmitted
|==========================================================================
--}}

<div x-show="currentStepId === 'konfirmasi'"
    x-data="{
        ck1: false, ck2: false, ck3: false,
        get semuaSetuju() { return this.ck1 && this.ck2 && this.ck3; },

        jalurLabel: {
            reguler:  { label: 'Jalur Reguler',  icon: 'fa-user-graduate',      color: 'text-[#FF1443]', bg: 'bg-red-100'     },
            zonasi:   { label: 'Jalur Zonasi',   icon: 'fa-map-location-dot',   color: 'text-emerald-600', bg: 'bg-emerald-100' },
            prestasi: { label: 'Jalur Prestasi', icon: 'fa-trophy',             color: 'text-amber-600',  bg: 'bg-amber-100'   },
            afirmasi: { label: 'Jalur Afirmasi', icon: 'fa-hand-holding-heart', color: 'text-indigo-600', bg: 'bg-indigo-100'  },
        },
    }"
    {{--
        x-effect: dijalankan setiap kali currentStepId berubah.
        Saat masuk ke konfirmasi → dispatch event HTMX untuk load summary nilai.
        Menggunakan $dispatch agar tidak ada polling; hanya trigger sekali saat tiba.
    --}}
    x-effect="if (currentStepId === 'konfirmasi') { $nextTick(() => htmx.trigger('#pendaftaran-summary', 'loadSummary')) }"
    class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">

    {{-- ── HEADER ── --}}
    <div class="px-8 pt-6 pb-5 border-b border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-clipboard-check text-green-600 text-xl"></i>
        </div>
        <div>
            <h2 class="text-lg font-black text-[#080C1A]">Konfirmasi & Kirim</h2>
            <p class="text-sm text-[#6A7686]">Periksa kembali seluruh data sebelum formulir dikirimkan</p>
        </div>
    </div>

    <div class="px-8 py-7 space-y-5">

        {{-- Warning --}}
        <div class="flex gap-3 items-start bg-red-50 border border-red-200 rounded-2xl px-4 py-3.5">
            <i class="fa-solid fa-triangle-exclamation text-red-500 text-base mt-0.5 flex-shrink-0"></i>
            <p class="text-sm font-medium text-red-800 leading-relaxed">
                Setelah formulir dikirim, perubahan data <strong>tidak dapat dilakukan secara mandiri</strong>. Hubungi panitia SPMB jika terdapat kesalahan data.
            </p>
        </div>

        {{-- ── RINGKASAN JALUR (dari Alpine state, instan) ── --}}
        <div class="border border-gray-200 rounded-2xl overflow-hidden">
            <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-road text-sky-500 text-[12px]"></i>
                <span class="text-[13px] font-black text-[#080C1A]">Jalur Pendaftaran</span>
            </div>
            <div class="px-5 py-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                    :class="jalurLabel[jalur]?.bg ?? 'bg-gray-100'">
                    <i :class="'fa-solid ' + (jalurLabel[jalur]?.icon ?? 'fa-question') + ' ' + (jalurLabel[jalur]?.color ?? 'text-gray-400')"></i>
                </div>
                <div>
                    <div class="text-[14px] font-black text-[#080C1A]"
                        x-text="jalurLabel[jalur]?.label ?? '—'"></div>
                    <div class="text-[12px] text-[#6A7686]"
                        x-text="jalur ? 'Dipilih pada step jalur pendaftaran' : 'Belum dipilih'"></div>
                </div>
            </div>
        </div>

        {{-- ── RINGKASAN JURUSAN (dari Alpine state, instan) ── --}}
        <div class="border border-gray-200 rounded-2xl overflow-hidden">
            <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-building-columns text-violet-500 text-[12px]"></i>
                <span class="text-[13px] font-black text-[#080C1A]">Pilihan Jurusan</span>
            </div>
            <div class="px-5 py-4 space-y-3">
                <template x-for="(key, idx) in [pil1, pil2, pil3]" :key="idx">
                    <div class="flex items-center gap-3">
                        <span :class="idx === 0 ? 'bg-[#FF1443]' : (idx === 1 ? 'bg-gray-700' : 'bg-gray-400')"
                            class="w-6 h-6 rounded-full text-white text-[10px] font-black flex items-center justify-center flex-shrink-0"
                            x-text="idx + 1"></span>
                        <div class="flex-1">
                            <span class="text-[13px] font-bold text-[#080C1A]"
                                x-text="jurusanList[key]?.nama ?? '—'"></span>
                            <span class="text-[11px] text-[#6A7686] ml-1.5"
                                x-text="jurusanList[key] ? '(' + jurusanList[key].singkat + ')' : ''"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- ── RINGKASAN PRESTASI (kondisional: hanya jalur prestasi) ── --}}
        <div x-show="jalur === 'prestasi'" class="border border-gray-200 rounded-2xl overflow-hidden">
            <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-award text-amber-500 text-[12px]"></i>
                <span class="text-[13px] font-black text-[#080C1A]">Prestasi Dilaporkan</span>
                <span class="ml-auto text-[11px] font-bold text-[#6A7686]"
                    x-text="prestasiList.length + ' prestasi'"></span>
            </div>
            <div class="px-5 py-4 space-y-2">
                <template x-if="prestasiList.length === 0">
                    <p class="text-[13px] text-[#6A7686] italic">Belum ada prestasi ditambahkan.</p>
                </template>
                <template x-for="(p, idx) in prestasiList" :key="p.id">
                    <div class="flex items-center gap-3 py-1.5 border-b border-gray-100 last:border-0">
                        <div class="w-6 h-6 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-medal text-amber-500 text-[10px]"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-[13px] font-bold text-[#080C1A] truncate"
                                x-text="p.nama || '(nama belum diisi)'"></div>
                            <div class="text-[11px] text-[#6A7686]"
                                x-text="p.tingkat + (p.peringkat ? ' · ' + p.peringkat : '')"></div>
                        </div>
                        <span class="text-[11px] font-bold text-amber-600 flex-shrink-0" x-text="p.tahun"></span>
                    </div>
                </template>
            </div>
        </div>

        {{-- ── SUMMARY NILAI RAPOR & TKA (dari server via HTMX) ── --}}
        {{--
            Diisi oleh HTMX GET /pendaftaran/summary saat step konfirmasi aktif.
            Trigger 'loadSummary' dikirim via x-effect di atas.
            Server membaca session/DB dan merender nilai rapor + TKA.
        --}}
        <div id="pendaftaran-summary"
            hx-get="{{ route('registration.summary') }}"
            hx-trigger="loadSummary"
            hx-swap="innerHTML"
            class="space-y-4">

            {{-- Skeleton loading (tampil sebelum HTMX response masuk) --}}
            <div class="space-y-3">
                <div class="h-8 bg-gray-100 rounded-xl animate-pulse w-1/3"></div>
                <div class="h-24 bg-gray-100 rounded-xl animate-pulse"></div>
                <div class="h-20 bg-gray-100 rounded-xl animate-pulse"></div>
            </div>

        </div>{{-- /#pendaftaran-summary --}}

        {{-- ── IDENTITAS PESERTA (dari Blade server, statis) ── --}}
        <div class="border border-gray-200 rounded-2xl overflow-hidden">
            <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-user text-[#FF1443] text-[12px]"></i>
                <span class="text-[13px] font-black text-[#080C1A]">Identitas Peserta</span>
            </div>
            <div class="grid grid-cols-2 gap-3 px-5 py-4">
                <div>
                    <div class="text-[11px] font-black uppercase tracking-widest text-[#6A7686] mb-1">Nama Lengkap</div>
                    <div class="text-[13px] font-bold text-[#080C1A]">{{ $peserta->nama_lengkap ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-[11px] font-black uppercase tracking-widest text-[#6A7686] mb-1">No. Peserta</div>
                    <div class="text-[13px] font-bold text-[#080C1A]">{{ $peserta->no_peserta ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-[11px] font-black uppercase tracking-widest text-[#6A7686] mb-1">NISN</div>
                    <div class="text-[13px] font-bold text-[#080C1A]">{{ $peserta->nisn ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-[11px] font-black uppercase tracking-widest text-[#6A7686] mb-1">Asal Sekolah</div>
                    <div class="text-[13px] font-bold text-[#080C1A]">{{ $peserta->asal_sekolah ?? '—' }}</div>
                </div>
            </div>
        </div>

        {{-- ── PERNYATAAN PESERTA ── --}}
        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5 space-y-3">
            <p class="text-sm font-black text-[#080C1A]">Pernyataan Peserta</p>

            <label class="flex items-start gap-3 cursor-pointer group">
                <input type="checkbox" x-model="ck1"
                    class="mt-1 w-4 h-4 accent-[#FF1443] flex-shrink-0">
                <span class="text-sm text-[#6A7686] leading-relaxed group-hover:text-[#080C1A] transition-colors">
                    Saya menyatakan bahwa seluruh data yang saya masukkan adalah
                    <strong class="text-[#080C1A]">benar dan dapat dipertanggungjawabkan</strong>.
                </span>
            </label>

            <label class="flex items-start gap-3 cursor-pointer group">
                <input type="checkbox" x-model="ck2"
                    class="mt-1 w-4 h-4 accent-[#FF1443] flex-shrink-0">
                <span class="text-sm text-[#6A7686] leading-relaxed group-hover:text-[#080C1A] transition-colors">
                    Saya memahami bahwa <strong class="text-[#080C1A]">jalur dan pilihan jurusan bersifat final</strong>
                    setelah formulir dikirim dan tidak dapat diubah secara mandiri.
                </span>
            </label>

            <label class="flex items-start gap-3 cursor-pointer group">
                <input type="checkbox" x-model="ck3"
                    class="mt-1 w-4 h-4 accent-[#FF1443] flex-shrink-0">
                <span class="text-sm text-[#6A7686] leading-relaxed group-hover:text-[#080C1A] transition-colors">
                    Saya bersedia menerima sanksi berupa <strong class="text-[#080C1A]">pembatalan keikutsertaan</strong>
                    apabila ditemukan pemalsuan data atau dokumen.
                </span>
            </label>
        </div>

    </div>{{-- /space-y-5 --}}

    {{-- ── FOOTER NAV ── --}}
    <div class="px-8 py-5 border-t border-gray-200 bg-gray-50/50 flex items-center justify-between rounded-b-[20px]">
        <button type="button" @click="step--"
            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-[#6A7686] border border-gray-200 rounded-full hover:border-[#080C1A] hover:text-[#080C1A] transition-all">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </button>

        {{-- Disabled sampai ketiga checkbox dicentang --}}
        <button type="button"
            :disabled="!semuaSetuju"
            hx-post="{{ route('registration.submit') }}"
            hx-indicator="#pendaftaran-form"
            hx-swap="none"
            @htmx:after-request="if($event.detail.successful){
                let r = JSON.parse($event.detail.xhr.response);
                if(r.success) isSubmitted = true;
            }"
            :class="semuaSetuju
                ? 'bg-green-600 hover:bg-green-700 hover:-translate-y-px shadow-lg shadow-green-500/30 cursor-pointer'
                : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
            class="inline-flex items-center gap-2 px-8 py-2.5 text-white text-sm font-black rounded-full transition-all">
            <span x-show="!$el.classList.contains('htmx-request')" class="inline-flex items-center gap-2">
                <i class="fa-solid fa-paper-plane text-[12px]"></i> Kirim Formulir Pendaftaran
            </span>
            <span x-show="$el.classList.contains('htmx-request')" class="inline-flex items-center gap-2">
                <i class="fa-solid fa-circle-notch fa-spin text-[12px]"></i> Mengirim...
            </span>
        </button>
    </div>

</div>{{-- /step konfirmasi --}}
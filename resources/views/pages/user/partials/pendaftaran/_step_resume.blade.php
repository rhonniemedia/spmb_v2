<div x-data="{
        ck1: false, ck2: false, ck3: false,
        summaryLoaded: false,
        get semuaSetuju() { return this.ck1 && this.ck2 && this.ck3; },

        jalurLabel: {
            reguler:  { label: 'Jalur Reguler',  icon: 'fa-user-graduate',      color: 'text-[#FF1443]', bg: 'bg-red-100'     },
            zonasi:   { label: 'Jalur Zonasi',   icon: 'fa-map-location-dot',   color: 'text-emerald-600', bg: 'bg-emerald-100' },
            prestasi: { label: 'Jalur Prestasi', icon: 'fa-trophy',             color: 'text-amber-600',  bg: 'bg-amber-100'   },
            afirmasi: { label: 'Jalur Afirmasi', icon: 'fa-hand-holding-heart', color: 'text-indigo-600', bg: 'bg-indigo-100'  },
        },
    }"

    x-effect="if (!summaryLoaded) { summaryLoaded = true; $nextTick(() => htmx.trigger('#pendaftaran-summary', 'loadSummary')) }"
    class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">

    {{-- ── HEADER ── --}}
    <div class="px-8 pt-6 pb-5 border-b border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-clipboard-check text-green-600 text-xl"></i>
        </div>
        <div>
            <h2 class="text-lg font-black text-[#080C1A]">Ringkasan Data Pendaftaran</h2>
            <p class="text-sm text-[#6A7686]">Data pendaftaran telah final dan formulir yang dikirim tidak dapat diubah kembali</p>
        </div>
    </div>

    <div class="px-8 py-7 space-y-5">

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
                    <div class="text-[13px] font-bold text-[#080C1A]">{{ $personalData->full_name ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-[11px] font-black uppercase tracking-widest text-[#6A7686] mb-1">NIK</div>
                    <div class="text-[13px] font-bold text-[#080C1A]">{{ $personalData->nik ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-[11px] font-black uppercase tracking-widest text-[#6A7686] mb-1">NISN</div>
                    <div class="text-[13px] font-bold text-[#080C1A]">{{ $personalData->nisn ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-[11px] font-black uppercase tracking-widest text-[#6A7686] mb-1">Asal Sekolah</div>
                    <div class="text-[13px] font-bold text-[#080C1A]">{{ $personalData->previous_school ?? '—' }}</div>
                </div>
            </div>
        </div>

    </div>{{-- /space-y-5 --}}

    {{-- ── FOOTER NAV ── --}}
    <div class="px-8 py-5 border-t border-gray-200 bg-gray-50/50 flex items-center justify-between rounded-b-[20px]">
        {{-- Tombol Kembali (Ikon Diperbarui Menjadi fa-house-user) --}}
        <button type="button" @click="step--"
            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-[#6A7686] border border-gray-200 rounded-full hover:border-[#080C1A] hover:text-[#080C1A] transition-all">
            <i class="fa-solid fa-house-user text-[13px]"></i> Kembali ke Dashboard
        </button>

        {{-- Tombol Cetak Bukti Pendaftaran Statis (Halaman Resume) --}}
        <a href="{{ route('registration.success') }}"
            class="inline-flex items-center gap-2 px-8 py-2.5 bg-blue-600 text-white text-sm font-black rounded-full shadow-lg shadow-blue-500/30 hover:bg-blue-700 hover:-translate-y-px cursor-pointer transition-all no-underline">
            <i class="fa-solid fa-print text-[13px]"></i> Cetak Bukti Pendaftaran
        </a>
    </div>

</div>{{-- /step konfirmasi --}}
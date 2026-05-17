{{-- _success_screen.blade.php --}}
{{--
|==========================================================================
| SUCCESS SCREEN — PENDAFTARAN
|==========================================================================
| Ditampilkan saat Alpine isSubmitted === true setelah HTMX POST berhasil.
| Menampilkan:
|   - Animasi konfirmasi
|   - Nomor pendaftaran & nomor antrian verifikasi
|   - Ringkasan jalur & jurusan yang dipilih
|   - Langkah selanjutnya (next steps)
|   - Tombol cetak & kembali ke dashboard
|==========================================================================
--}}

<div x-show="isSubmitted"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 translate-y-6"
    x-transition:enter-end="opacity-100 translate-y-0"
    class="flex flex-col items-center py-6">

    {{-- ── HERO SUCCESS CARD ── --}}
    <div class="w-full max-w-[768px] bg-white border border-gray-200 rounded-[24px] shadow-xl overflow-hidden">

        {{-- Top banner --}}
        <div class="relative px-8 py-10 flex flex-col items-center text-center overflow-hidden"
            style="background: linear-gradient(135deg, #059669 0%, #047857 60%, #065F46 100%);">
            {{-- Decorative circles --}}
            <div class="absolute -top-8 -right-8 w-40 h-40 bg-white/[0.07] rounded-full pointer-events-none"></div>
            <div class="absolute -bottom-12 -left-8 w-32 h-32 bg-white/[0.05] rounded-full pointer-events-none"></div>

            {{-- Icon --}}
            <div class="relative z-10 w-20 h-20 rounded-full bg-white/15 border-4 border-white/30 flex items-center justify-center mb-5 shadow-xl">
                <i class="fa-solid fa-circle-check text-white text-[36px]"></i>
            </div>

            <div class="relative z-10">
                <div class="inline-flex items-center gap-1.5 bg-white/15 text-white text-[12px] font-bold px-3 py-1 rounded-full border border-white/25 mb-3">
                    <i class="fa-solid fa-check text-[10px]"></i> Formulir Terkirim
                </div>
                <h1 class="text-[24px] font-black text-white mb-2 leading-tight">Pendaftaran Berhasil!</h1>
                <p class="text-[14px] text-white/80 leading-relaxed max-w-[420px]">
                    Formulir pendaftaran kamu telah berhasil dikirimkan dan sedang menunggu verifikasi dokumen oleh panitia SPMB.
                </p>
            </div>

            {{-- Nomor pendaftaran --}}
            <div class="relative z-10 mt-6 bg-white/10 border border-white/20 rounded-[16px] px-6 py-4 backdrop-blur-sm w-full max-w-sm">
                <div class="text-[11px] text-white/60 font-bold uppercase tracking-widest mb-1">Nomor Pendaftaran</div>
                <div class="text-[22px] font-black text-white tracking-wider" x-text="submitResult.noPeserta"></div>
                <div class="text-[11px] text-white/60 mt-1">Simpan nomor ini untuk keperluan verifikasi</div>
            </div>
        </div>

        {{-- Body --}}
        <div class="px-8 py-7 space-y-6">

            {{-- Status timeline --}}
            <div>
                <p class="text-[12px] font-black uppercase tracking-widest text-[#6A7686] mb-4">Status Proses</p>
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-check text-green-600 text-[11px]"></i>
                        </div>
                        <div class="flex-1 pt-1">
                            <div class="text-[13px] font-black text-[#080C1A]">Formulir Dikirim</div>
                            <div class="text-[11px] text-[#6A7686]">Hari ini, {{ now()->format('d M Y') }}</div>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-[10px] font-black">Selesai</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-hourglass-half text-amber-500 text-[11px]"></i>
                        </div>
                        <div class="flex-1 pt-1">
                            <div class="text-[13px] font-black text-[#080C1A]">Verifikasi Dokumen Panitia</div>
                            <div class="text-[11px] text-[#6A7686]">16–18 Juni 2026 · 1–3 hari kerja</div>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 text-[10px] font-black">Menunggu</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-bullhorn text-gray-400 text-[11px]"></i>
                        </div>
                        <div class="flex-1 pt-1">
                            <div class="text-[13px] font-black text-[#080C1A]">Pengumuman Seleksi</div>
                            <div class="text-[11px] text-[#6A7686]">20 Juni 2026</div>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-gray-100 text-[#6A7686] text-[10px] font-black">Belum</span>
                    </div>
                </div>
            </div>

            <hr class="border-dashed border-gray-200">

            {{-- Ringkasan singkat --}}
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4">
                    <div class="text-[11px] font-black uppercase tracking-widest text-[#6A7686] mb-2 flex items-center gap-1.5">
                        <i class="fa-solid fa-road text-sky-400 text-[10px]"></i> Jalur Dipilih
                    </div>
                    <div class="text-[14px] font-black text-[#080C1A]" x-text="submitResult.jalur ?? '—'"></div>
                    <div class="text-[11px] text-[#6A7686] mt-0.5">Menunggu verifikasi</div>
                </div>
                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4">
                    <div class="text-[11px] font-black uppercase tracking-widest text-[#6A7686] mb-2 flex items-center gap-1.5">
                        <i class="fa-solid fa-building-columns text-violet-400 text-[10px]"></i> Pilihan 1
                    </div>
                    <div class="text-[14px] font-black text-[#080C1A]" x-text="submitResult.pilihan1 ?? '—'"></div>
                    <div class="text-[11px] text-[#6A7686] mt-0.5">Jurusan prioritas utama</div>
                </div>
                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4">
                    <div class="text-[11px] font-black uppercase tracking-widest text-[#6A7686] mb-2 flex items-center gap-1.5">
                        <i class="fa-solid fa-building-columns text-indigo-400 text-[10px]"></i> Pilihan 2
                    </div>
                    <div class="text-[14px] font-black text-[#080C1A]" x-text="submitResult.pilihan2 ?? '—'"></div>
                    <div class="text-[11px] text-[#6A7686] mt-0.5">Jurusan prioritas kedua</div>
                </div>
                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4">
                    <div class="text-[11px] font-black uppercase tracking-widest text-[#6A7686] mb-2 flex items-center gap-1.5">
                        <i class="fa-solid fa-building-columns text-purple-400 text-[10px]"></i> Pilihan 3
                    </div>
                    <div class="text-[14px] font-black text-[#080C1A]" x-text="submitResult.pilihan3 ?? '—'"></div>
                    <div class="text-[11px] text-[#6A7686] mt-0.5">Jurusan prioritas ketiga</div>
                </div>
            </div>

            {{-- Info penting --}}
            <div class="bg-blue-50 border border-blue-200 rounded-2xl px-5 py-4 space-y-2">
                <p class="text-[13px] font-black text-blue-900 flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-blue-500 text-[12px]"></i> Yang Perlu Kamu Lakukan Selanjutnya
                </p>
                <ul class="space-y-1.5 pl-1">
                    <li class="flex items-start gap-2 text-[12px] text-blue-800">
                        <i class="fa-solid fa-chevron-right text-blue-400 text-[9px] mt-1 flex-shrink-0"></i>
                        Simpan atau cetak bukti pendaftaran ini sebagai arsip.
                    </li>
                    <li class="flex items-start gap-2 text-[12px] text-blue-800">
                        <i class="fa-solid fa-chevron-right text-blue-400 text-[9px] mt-1 flex-shrink-0"></i>
                        Siapkan dokumen fisik asli untuk verifikasi pada <strong>16–18 Juni 2026</strong>.
                    </li>
                    <li class="flex items-start gap-2 text-[12px] text-blue-800">
                        <i class="fa-solid fa-chevron-right text-blue-400 text-[9px] mt-1 flex-shrink-0"></i>
                        Pantau pengumuman seleksi pada <strong>20 Juni 2026</strong> melalui dashboard.
                    </li>
                    <li class="flex items-start gap-2 text-[12px] text-blue-800">
                        <i class="fa-solid fa-chevron-right text-blue-400 text-[9px] mt-1 flex-shrink-0"></i>
                        Hubungi panitia jika ada perbaikan data sebelum verifikasi.
                    </li>
                </ul>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-3 pt-1">
                <button type="button" onclick="window.print()"
                    class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-3 text-[13px] font-black text-[#080C1A] border-2 border-gray-200 rounded-full hover:border-[#080C1A] transition-all">
                    <i class="fa-solid fa-print text-[12px]"></i> Cetak Bukti Pendaftaran
                </button>
                <a href="{{ route('dashboard') }}"
                    class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-3 text-[13px] font-black text-white bg-[#FF1443] rounded-full no-underline hover:bg-[#D90F38] hover:-translate-y-px transition-all shadow-lg shadow-red-500/25">
                    <i class="fa-solid fa-gauge text-[12px]"></i> Kembali ke Dashboard
                </a>
            </div>

        </div>{{-- /body --}}

    </div>{{-- /card --}}

    {{-- Contact info --}}
    <p class="text-[12px] text-[#6A7686] mt-5 text-center">
        Ada pertanyaan? Hubungi panitia di
        <a href="https://wa.me/6281234567890" target="_blank" class="text-[#FF1443] font-bold no-underline">WhatsApp</a>
        atau email <a href="mailto:spmb@smkn1example.sch.id" class="text-[#FF1443] font-bold no-underline">spmb@smkn1example.sch.id</a>
    </p>

</div>{{-- /success screen --}}
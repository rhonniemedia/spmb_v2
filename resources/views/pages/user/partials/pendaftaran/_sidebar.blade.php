{{-- _sidebar.blade.php --}}
{{--
|==========================================================================
| SIDEBAR — PENDAFTARAN
|==========================================================================
| Menampilkan:
|   - Progress tracker per langkah (status done/active/pending)
|   - Ringkasan pilihan yang sudah diisi
|   - Timeline & jadwal seleksi
|   - Card bantuan panitia
|==========================================================================
--}}

<div class="space-y-4 mt-5 lg:mt-0">

    {{-- ── PROGRESS TRACKER ── --}}
    <div class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <p class="text-[13px] font-black text-[#080C1A] flex items-center gap-2">
                <i class="fa-solid fa-list-check text-[12px] text-[#FF1443]"></i> Tahapan Pendaftaran
            </p>
        </div>
        <div class="px-5 py-4 space-y-1">
            <template x-for="(label, idx) in stepLabels" :key="idx">
                <div class="flex items-center gap-3 py-2.5 border-b border-gray-50 last:border-0">
                    {{-- Status icon --}}
                    <div :class="{
                        'bg-[#FF1443] text-white': sidebarStatus(idx + 1) === 'active',
                        'bg-green-500 text-white': sidebarStatus(idx + 1) === 'done',
                        'bg-gray-100 text-[#B0B9C4]': sidebarStatus(idx + 1) === 'pending'
                    }" class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 transition-all duration-300">
                        <template x-if="sidebarStatus(idx + 1) === 'done'">
                            <i class="fa-solid fa-check text-[10px]"></i>
                        </template>
                        <template x-if="sidebarStatus(idx + 1) === 'active'">
                            <i :class="'fa-solid ' + stepIcons[idx] + ' text-[10px]'"></i>
                        </template>
                        <template x-if="sidebarStatus(idx + 1) === 'pending'">
                            <span class="text-[11px] font-black" x-text="idx + 1"></span>
                        </template>
                    </div>
                    {{-- Label --}}
                    <div class="flex-1 min-w-0">
                        <span :class="{
                            'text-[#080C1A] font-black': sidebarStatus(idx + 1) === 'active',
                            'text-[#080C1A] font-bold': sidebarStatus(idx + 1) === 'done',
                            'text-[#B0B9C4] font-medium': sidebarStatus(idx + 1) === 'pending'
                        }" class="text-[13px] block truncate" x-text="label"></span>
                    </div>
                    {{-- Badge --}}
                    <template x-if="sidebarStatus(idx + 1) === 'done'">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-[10px] font-black flex-shrink-0">
                            <i class="fa-solid fa-check text-[8px]"></i> Selesai
                        </span>
                    </template>
                    <template x-if="sidebarStatus(idx + 1) === 'active'">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-red-100 text-[#FF1443] text-[10px] font-black flex-shrink-0">Aktif</span>
                    </template>
                </div>
            </template>
        </div>
    </div>

    {{-- ── RINGKASAN DATA (live preview murni Alpine) ── --}}
    <div class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <p class="text-[13px] font-black text-[#080C1A] flex items-center gap-2">
                <i class="fa-solid fa-eye text-[12px] text-[#FF1443]"></i> Ringkasan Pilihan
            </p>
        </div>
        <div class="px-5 py-4 space-y-3">
            {{-- Rata-Rata Rapor --}}
            <div>
                <div class="text-[10px] font-black uppercase tracking-widest text-[#6A7686] mb-1">Rata-Rata Rapor</div>
                <div class="text-[14px] font-black text-[#080C1A]"
                    x-text="rataRapor ? rataRapor + ' / 100' : '—'"></div>
            </div>

            {{-- Rata-Rata TKA --}}
            <div>
                <div class="text-[10px] font-black uppercase tracking-widest text-[#6A7686] mb-1">Rata-Rata TKA</div>
                <div class="text-[14px] font-black text-[#080C1A]"
                    x-text="rataTka ? rataTka + ' / 100' : '—'"></div>
            </div>

            <hr class="border-dashed border-gray-200">

            {{-- Jalur Dipilih (Otomatis singkron nama aslinya dari DB) --}}
            <div>
                <div class="text-[10px] font-black uppercase tracking-widest text-[#6A7686] mb-1">Jalur Dipilih</div>
                <div class="text-[13px] font-bold text-[#080C1A]"
                    x-text="jalurList[jalur] || '—'"></div>
            </div>

            {{-- Pilihan Jurusan (Otomatis singkron nama dari DB via ID/UUID) --}}
            <div>
                <div class="text-[10px] font-black uppercase tracking-widest text-[#6A7686] mb-1.5">Pilihan Jurusan</div>
                <div class="space-y-1.5">
                    <div class="flex items-center gap-2">
                        <span class="w-4 h-4 rounded-full bg-[#FF1443] text-white text-[9px] font-black flex items-center justify-center flex-shrink-0">1</span>
                        <span class="text-[12px] font-bold text-[#080C1A]"
                            x-text="jurusanList[pil1]?.nama || '—'"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-4 h-4 rounded-full bg-gray-700 text-white text-[9px] font-black flex items-center justify-center flex-shrink-0">2</span>
                        <span class="text-[12px] font-bold text-[#080C1A]"
                            x-text="jurusanList[pil2]?.nama || '—'"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-4 h-4 rounded-full bg-gray-400 text-white text-[9px] font-black flex items-center justify-center flex-shrink-0">3</span>
                        <span class="text-[12px] font-bold text-[#080C1A]"
                            x-text="jurusanList[pil3]?.nama || '—'"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Butuh Bantuan Langsung --}}
    <div class="bg-white border border-gray-200 rounded-[20px] overflow-hidden shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
        <div class="px-[18px] py-[14px]">
            <p class="text-[14px] font-bold mb-2 flex items-center gap-[6px]">
                <i class="fa-solid fa-circle-question text-[14px] text-[#FF1443]"></i> Butuh Bantuan Langsung?
            </p>
            <p class="text-[13px] text-[#6A7686] text-center leading-relaxed mb-3">
                Panitia SPMB siap membantu selama jam kerja <strong class="text-[#080C1A]">08:00–16:00 WIB</strong>.
            </p>
            {{-- Tombol Chat WhatsApp → buka modal --}}
            <button @click="modalWhatsapp = true"
                class="inline-flex w-full items-center justify-center gap-[7px] px-4 py-[9px] rounded-full font-sans text-[14px] font-bold bg-white border-[1.5px] border-[#25D366] text-[#25D366] hover:-translate-y-px transition-all cursor-pointer">
                <i class="fa-brands fa-whatsapp text-[15px]"></i> Chat WhatsApp Panitia
            </button>
            <a href="mailto:spmb@smkn1.sch.id"
                class="mt-2 inline-flex w-full items-center justify-center gap-[7px] px-4 py-[9px] rounded-full font-sans text-[14px] font-bold no-underline bg-white border-[1.5px] border-[#FF1443] text-[#FF1443] hover:-translate-y-px transition-all">
                <i class="fa-solid fa-envelope text-[13px]"></i> Kirim Email Panitia
            </a>
        </div>
        <div class="border-t border-gray-100 bg-gray-50/50 px-[18px] py-3 text-center">
            <p class="flex items-center justify-center gap-1 text-sm font-semibold text-[#080C1A]">
                <i class="fa-regular fa-clock text-[#FF1443]"></i>
                Jam Operasional
            </p>

            <div class="mt-1 space-y-0.5">
                <p class="text-xs text-[#6A7686]">
                    Senin–Jumat 08:00–16:00 WIB
                </p>

                <p class="text-xs text-red-500 font-medium">
                    Sabtu & Minggu: Tutup
                </p>
            </div>
        </div>
    </div>

    {{-- Peringatan --}}
    <div class="bg-[#FFF8F9] border border-[rgba(255,20,67,.15)] rounded-[16px] px-4 py-3.5 flex items-start gap-2.5">
        <i class="fa-solid fa-triangle-exclamation text-[#FF1443] text-[13px] mt-0.5 flex-shrink-0"></i>
        <p class="text-[12px] text-[#6A7686] leading-relaxed">
            <strong class="text-[#080C1A]">Perhatian:</strong>
            Panitia tidak pernah meminta biaya pendaftaran. Waspada penipuan yang mengatasnamakan SPMB.
        </p>
    </div>

</div>{{-- /sidebar --}}
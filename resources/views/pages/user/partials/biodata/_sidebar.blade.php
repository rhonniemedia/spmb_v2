<div class="hidden lg:block">
    <div class="sticky top-[80px] flex flex-col gap-4">

        {{-- Kelengkapan Biodata --}}
        <div class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">
            <div class="px-5 py-4" style="background: linear-gradient(135deg,#FF1443,#D90F38);">
                <h3 class="text-base font-black text-white mb-0.5">Kelengkapan Biodata</h3>
                <p class="text-[13px] text-white/80">Update otomatis saat berpindah step</p>
            </div>

            {{-- MODIFIKASI LIST STEP --}}
            <div class="px-5 py-3 space-y-1">
                <template x-for="(label, idx) in stepLabels" :key="idx">
                    <div class="flex items-center gap-3 py-2 border-b border-gray-50 last:border-0">

                        {{-- Status icon (Lingkaran) --}}
                        <div :class="{
                            'bg-[#FF1443] text-white': sidebarStatus(idx + 1) === 'active',
                            'bg-green-500 text-white': sidebarStatus(idx + 1) === 'done',
                            'bg-gray-100 text-[#B0B9C4]': sidebarStatus(idx + 1) === 'pending'
                        }" class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 transition-all duration-300">

                            {{-- Jika Selesai: Tampilkan Icon Check --}}
                            <template x-if="sidebarStatus(idx + 1) === 'done'">
                                <i class="fa-solid fa-check text-[10px]"></i>
                            </template>

                            {{-- Jika Aktif: Tampilkan Icon Aslinya --}}
                            <template x-if="sidebarStatus(idx + 1) === 'active'">
                                <i :class="'fa-solid ' + stepIcons[idx] + ' text-[10px]'"></i>
                            </template>

                            {{-- PERUBAHAN DI SINI: Jika Belum Aktif, Tetap Tampilkan Icon Aslinya --}}
                            <template x-if="sidebarStatus(idx + 1) === 'pending'">
                                <i :class="'fa-solid ' + stepIcons[idx] + ' text-[10px]'"></i>
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

            {{-- Progress Bar Bawah --}}
            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">
                <div class="flex justify-between text-sm font-semibold text-[#6A7686] mb-2">
                    <span>Progress Total</span>
                    <span class="text-primary font-bold" x-text="progressPct + '%'">0%</span>
                </div>
                <div class="h-2.5 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500"
                        :style="'background: linear-gradient(90deg, #FF1443, #FF6B8A); width: ' + progressPct + '%'">
                    </div>
                </div>
            </div>
        </div>

        {{-- Butuh Bantuan --}}
        <div class="bg-white border border-gray-200 rounded-[20px] overflow-hidden shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
            <div class="px-[18px] py-[14px]">
                <p class="text-[14px] font-bold mb-2 flex items-center gap-[6px]">
                    <i class="fa-solid fa-circle-question text-[14px] text-[#FF1443]"></i>Butuh Bantuan?
                </p>
                <p class="text-[13px] text-[#6A7686] leading-relaxed mb-3">
                    Panitia SPMB siap membantu selama jam kerja <strong class="text-[#080C1A]">08:00–16:00 WIB</strong>.
                </p>
                <a href="https://wa.me/6281234567890" target="_blank"
                    class="inline-flex w-full items-center justify-center gap-[7px] px-4 py-[9px] rounded-full font-sans text-[14px] font-bold no-underline bg-white border-[1.5px] border-[#25D366] text-[#25D366] hover:-translate-y-px transition-all">
                    <i class="fa-brands fa-whatsapp text-[15px]"></i> Chat WhatsApp Panitia
                </a>
            </div>
        </div>

    </div>
</div>
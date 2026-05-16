<div class="hidden lg:block">
    <div class="sticky top-[80px] flex flex-col gap-4">

        {{-- Kelengkapan Biodata --}}
        <div class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">
            <div class="px-5 py-4" style="background: linear-gradient(135deg,#FF1443,#D90F38);">
                <h3 class="text-base font-black text-white mb-0.5">Kelengkapan Biodata</h3>
                <p class="text-[13px] text-white/80">Update otomatis saat berpindah step</p>
            </div>
            <div class="px-5 py-3 divide-y divide-gray-100">
                <template x-for="(label, idx) in [
                            {name: 'Data Pribadi', icon: 'fa-user', color: 'text-primary'},
                            {name: 'Alamat', icon: 'fa-location-dot', color: 'text-blue-500'},
                            {name: 'Orang Tua', icon: 'fa-people-roof', color: 'text-green-600'},
                            {name: 'Pendidikan', icon: 'fa-book', color: 'text-amber-600'},
                            {name: 'Pas Foto', icon: 'fa-camera', color: 'text-violet-600'},
                            {name: 'Konfirmasi', icon: 'fa-clipboard-check', color: 'text-green-600'}
                        ]" :key="idx">
                    <div class="flex justify-between items-center py-2.5">
                        <span class="text-sm font-semibold text-[#6A7686]">
                            <i :class="'fa-solid ' + label.icon + ' ' + label.color + ' mr-1.5'"></i>
                            <span x-text="label.name"></span>
                        </span>
                        <span class="text-[13px] font-bold"
                            :class="{
                                            'text-green-600 flex items-center gap-1': step > idx+1,
                                            'text-primary': step === idx+1,
                                            'text-gray-400 italic': step < idx+1
                                        }">
                            <template x-if="step > idx+1">
                                <span><i class="fa-solid fa-check"></i> Selesai</span>
                            </template>
                            <template x-if="step === idx+1">
                                <span>● Sedang diisi</span>
                            </template>
                            <template x-if="step < idx+1">
                                <span>Belum diisi</span>
                            </template>
                        </span>
                    </div>
                </template>
            </div>
            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">
                <div class="flex justify-between text-sm font-semibold text-[#6A7686] mb-2">
                    <span>Progress Total</span>
                    <span class="text-primary font-bold" x-text="progressPct + '%'"></span>
                </div>
                <div class="h-2.5 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500"
                        style="background: linear-gradient(90deg,#FF1443,#FF6B8A)"
                        :style="'width:' + progressPct + '%'"></div>
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
</div>{{-- /sidebar --}}
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
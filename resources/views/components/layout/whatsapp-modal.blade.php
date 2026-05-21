{{-- ══════════════════════════════════════
       MODAL: WHATSAPP PANITIA
  ══════════════════════════════════════ --}}
{{-- BACKDROP --}}

{{-- BACKDROP & CONTAINER --}}
<div
    x-show="modalWhatsapp"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[300] flex items-center justify-center sm:p-4 bg-black/50"
    style="display:none"
    @click.self="modalWhatsapp = false">

    {{-- DIALOG (Efek Slide Down khas Bootstrap) --}}
    <div
        x-show="modalWhatsapp"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-10 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 -translate-y-10 scale-95"
        class="bg-white w-full h-full sm:h-auto sm:max-w-md sm:rounded-3xl shadow-2xl overflow-y-auto flex flex-col"
        @click.stop>

        {{-- Header hijau --}}
        <div class="bg-gradient-to-r from-[#25D366] to-[#1da851] px-6 pt-6 pb-8">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <div class="size-12 bg-white/20 rounded-2xl flex items-center justify-center">
                        <i class="fa-brands fa-whatsapp text-white text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-white font-black text-lg leading-tight">Chat WhatsApp Panitia</h3>
                        <p class="text-green-100 text-xs mt-0.5">Pilih kontak panitia yang tersedia</p>
                    </div>
                </div>
                <button @click="modalWhatsapp = false"
                    class="size-8 bg-white/20 hover:bg-white/30 rounded-xl flex items-center justify-center transition-colors cursor-pointer">
                    <i class="fa-solid fa-xmark text-white text-sm"></i>
                </button>
            </div>

            {{-- Jam operasional pill --}}
            <div class="mt-4 inline-flex items-center gap-1.5 bg-white/20 rounded-full px-3 py-1.5">
                <i class="fa-regular fa-clock text-white text-[11px]"></i>
                <span class="text-white text-[11px] font-semibold">Senin–Jumat 08:00–16:00 WIB</span>
            </div>
        </div>

        {{-- Body --}}
        <div class="px-6 py-5 flex flex-col gap-3">

            {{-- Daftar nomor dari view composer --}}
            @php
            $waNumbers = is_array($g_schoolInfo->whatsapp_numbers)
            ? $g_schoolInfo->whatsapp_numbers
            : json_decode($g_schoolInfo->whatsapp_numbers ?? '[]', true);
            @endphp

            @forelse ($waNumbers as $index => $wa)
            <a href="https://wa.me/{{ preg_replace('/\D/', '', $wa['number']) }}"
                target="_blank" rel="noopener noreferrer"
                class="group flex items-center gap-4 border border-gray-200 hover:border-[#25D366] rounded-2xl px-4 py-3.5 transition-all duration-200 hover:shadow-md hover:-translate-y-px no-underline">

                <div class="size-11 rounded-2xl bg-[#25D366]/10 group-hover:bg-[#25D366]/20 flex items-center justify-center shrink-0 transition-colors">
                    <span class="text-[#25D366] font-black text-sm">{{ $index + 1 }}</span>
                </div>

                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-bold text-[#080C1A] truncate">
                        {{ $wa['name'] ?? 'Panitia SPMB' }}
                    </p>
                    <p class="text-[12px] text-[#6A7686] tracking-wide mt-0.5">
                        {{ $wa['number'] }}
                    </p>
                </div>

                <div class="size-8 rounded-xl bg-gray-50 group-hover:bg-[#25D366] flex items-center justify-center transition-colors shrink-0">
                    <i class="fa-solid fa-arrow-right text-[11px] text-gray-400 group-hover:text-white transition-colors"></i>
                </div>
            </a>
            @empty
            <div class="flex flex-col items-center gap-2 py-8 text-center">
                <div class="size-12 bg-gray-100 rounded-2xl flex items-center justify-center">
                    <i class="fa-solid fa-phone-slash text-gray-400"></i>
                </div>
                <p class="text-sm font-semibold text-[#080C1A]">Nomor belum tersedia</p>
                <p class="text-xs text-[#6A7686]">Silakan hubungi sekolah secara langsung.</p>
            </div>
            @endforelse

            {{-- Disclaimer --}}
            <div class="flex items-start gap-2.5 bg-amber-50 border border-amber-200 rounded-xl px-3 py-2.5 mt-1">
                <i class="fa-solid fa-triangle-exclamation text-amber-500 text-[12px] mt-0.5 shrink-0"></i>
                <p class="text-amber-800 text-xs leading-relaxed">
                    Panitia <strong>tidak pernah</strong> meminta biaya pendaftaran lewat WhatsApp.
                    Waspada penipuan yang mengatasnamakan SPMB.
                </p>
            </div>

            <button @click="modalWhatsapp = false"
                class="w-full py-3 bg-gray-100 hover:bg-gray-200 text-[#080C1A] rounded-xl text-sm font-bold transition-colors cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>
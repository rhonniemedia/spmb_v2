<div x-show="jarakSudahDicek" class="border border-emerald-200 bg-emerald-50 rounded-2xl px-5 py-4 space-y-3">
    <div class="flex items-center gap-2">
        <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
        <span class="text-[14px] font-black text-emerald-900">Hasil Kalkulasi Jarak</span>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div class="bg-white border border-emerald-100 rounded-xl px-4 py-3">
            <div class="text-[11px] font-black uppercase tracking-widest text-[#6A7686] mb-1">Jarak Udara</div>
            <div class="text-[20px] font-black text-emerald-700">{{ $jarak }} <span class="text-[13px] font-bold">km</span></div>
            <div class="text-[11px] text-[#6A7686]">{{ $jarakMeter }} meter</div>
        </div>
        <div class="bg-white border border-emerald-100 rounded-xl px-4 py-3">
            <div class="text-[11px] font-black uppercase tracking-widest text-[#6A7686] mb-1">Status Zona</div>
            @if($jarakMeter <= 2000)
                <div class="text-[14px] font-black text-emerald-600"><i class="fa-solid fa-circle-check"></i> Dalam Zona
        </div>
        <div class="text-[11px] text-[#6A7686]">Memenuhi syarat jalur zonasi</div>
        @else
        <div class="text-[14px] font-black text-red-500"><i class="fa-solid fa-circle-xmark"></i> Luar Zona</div>
        <div class="text-[11px] text-[#6A7686]">Jarak melebihi batas zonasi</div>
        @endif
    </div>
</div>
</div>
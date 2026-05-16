{{-- _stepper.blade.php --}}
<div class="bg-white border border-gray-200 rounded-[20px] px-7 py-6 mb-6 shadow-sm overflow-x-auto">
    <div class="flex items-start justify-between min-w-max sm:min-w-0">
        <template x-for="i in totalSteps" :key="i">
            <div class="flex flex-col items-center flex-1 relative">

                {{-- Garis segmen: dari tengah node ini ke tengah node berikutnya --}}
                <template x-if="i < totalSteps">
                    <div class="absolute top-[21px] left-1/2 w-full h-0.5 z-0 transition-all duration-500"
                        :class="step > i ? 'bg-[#FF1443]' : 'bg-gray-200'"></div>
                </template>

                {{-- Node (dot) --}}
                <div class="relative z-10 w-11 h-11 rounded-full flex items-center justify-center border-[2px] transition-all duration-300 cursor-pointer focus:outline-none"
                    :class="{
                        'bg-[#FF1443] border-[#FF1443] text-white shadow-lg shadow-red-200': step === i,
                        'bg-[#FF1443] border-[#FF1443] text-white': step > i,
                        'bg-white border-gray-200 text-[#B0B9C4]': step < i
                    }"
                    @click="if(i < step) step = i">

                    {{-- Jika langkah sudah terlewati (Selesai), tampilkan ikon check --}}
                    <template x-if="step > i">
                        <i class="fa-solid fa-check text-sm"></i>
                    </template>

                    {{-- Jika langkah aktif ATAU belum dicapai, selalu tampilkan ikon bawaannya --}}
                    <template x-if="step <= i">
                        <i :class="'fa-solid ' + stepIcons[i-1] + ' text-sm'"></i>
                    </template>
                </div>

                {{-- Label di bawah dot --}}
                <span class="text-[12px] text-center max-w-[85px] leading-tight mt-2.5 transition-colors whitespace-normal"
                    :class="{
                        'text-[#FF1443] font-bold': step === i,
                        'text-[#080C1A] font-medium': step > i,
                        'text-[#B0B9C4] font-medium': step < i
                    }"
                    x-text="stepLabels[i-1]"></span>
            </div>
        </template>
    </div>
</div>
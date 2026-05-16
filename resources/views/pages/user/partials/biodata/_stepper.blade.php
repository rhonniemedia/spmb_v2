{{--
                Pendekatan: setiap node dibungkus flex-1, lalu garis antar node
                dirender sebagai elemen absolut di dalam wrapper node kiri,
                memanjang dari tengah node kiri ke tengah node kanan (100% parent flex-1).
                Dengan begitu garis selalu tepat mulai/berakhir di pusat node.
            --}}
<div class="bg-white border border-gray-200 rounded-[20px] px-7 py-6 mb-6 shadow-sm">
    <div class="flex items-start justify-between">
        <template x-for="i in totalSteps" :key="i">
            <div class="flex flex-col items-center flex-1 relative"
                :class="i < totalSteps ? 'pr-0' : ''">

                {{-- Garis segmen: dari tengah node ini ke tengah node berikutnya --}}
                {{-- Hanya render untuk node bukan terakhir --}}
                <template x-if="i < totalSteps">
                    <div class="absolute top-[21px] left-1/2 w-full h-0.5 z-0"
                        :class="step > i ? 'bg-primary' : 'bg-gray-200'"></div>
                </template>

                {{-- Node (dot) --}}
                <div class="relative z-10 w-11 h-11 rounded-full flex items-center justify-center font-bold text-base border-2 transition-all duration-300 cursor-pointer bg-white"
                    :class="{
                                    'bg-green-500 border-green-500 text-white': step > i,
                                    'bg-primary border-primary text-white shadow-lg shadow-primary/20': step === i,
                                    'bg-white border-gray-200 text-gray-400': step < i
                                }"
                    @click="step = i">
                    <template x-if="step > i"><i class="fa-solid fa-check text-sm"></i></template>
                    <template x-if="step === i"><i :class="'fa-solid ' + stepIcons[i-1] + ' text-sm'"></i></template>
                    <template x-if="step < i"><span x-text="i"></span></template>
                </div>

                {{-- Label --}}
                <span class="hidden sm:block text-[12px] font-semibold text-center max-w-[72px] leading-tight mt-2.5 transition-colors"
                    :class="step >= i ? 'text-primary' : 'text-gray-400'"
                    x-text="stepLabels[i-1]"></span>
            </div>
        </template>
    </div>

    <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-100">
        <span class="text-sm font-semibold text-[#6A7686]" x-text="'Langkah ' + step + ' dari ' + totalSteps"></span>
        <div class="flex-1 h-2 bg-gray-200 rounded-full mx-4 overflow-hidden">
            <div class="h-full rounded-full transition-all duration-500"
                style="background: linear-gradient(90deg,#FF1443,#FF6B8A)"
                :style="'width:' + progressPct + '%'"></div>
        </div>
        <span class="text-base font-bold text-primary" x-text="progressPct + '%'"></span>
    </div>
</div>
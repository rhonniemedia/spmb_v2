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
</div>
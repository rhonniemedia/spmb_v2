<div class="relative rounded-xl p-4 border transition-all duration-300 ease-out flex flex-col justify-between min-h-[160px] cursor-default group
    {{-- TAHAP SELESAI -> HIJAU & HOVER HIJAU --}}
    {{ $step['done'] ? 'bg-green-50/40 border-green-100 text-gray-700 hover:bg-green-50 hover:border-green-300 hover:shadow-md hover:shadow-green-100/60 hover:-translate-y-0.5' : '' }}
    
    {{-- TAHAP BERLANGSUNG -> BIRU (Paling mencolok & HOVER BIRU AKTOR) --}}
    {{ $step['active'] ? 'bg-gradient-to-b from-blue-600 to-indigo-700 text-white border-blue-600 shadow-lg shadow-blue-100 hover:from-blue-500 hover:to-indigo-600 hover:shadow-xl hover:shadow-blue-200 hover:-translate-y-1' : '' }}
    
    {{-- TAHAP MENDATANG -> KUNING & HOVER KUNING --}}
    {{ !$step['done'] && !$step['active'] ? 'bg-amber-50/20 border-amber-100/70 text-gray-500 hover:bg-amber-50/60 hover:border-amber-200 hover:shadow-md hover:shadow-amber-100/40 hover:-translate-y-0.5' : '' }}">

    <div class="flex items-center justify-between">
        <span class="text-[10px] font-black tracking-wider uppercase px-2 py-0.5 rounded transition-colors duration-300
            {{ $step['active'] ? 'bg-white/20 text-white' : ($step['done'] ? 'bg-green-100 text-green-700 group-hover:bg-green-200' : 'bg-amber-100 text-amber-700 group-hover:bg-amber-200') }}">
            {{ $step['no'] }}
        </span>
        <div class="w-7 h-7 rounded-lg flex items-center justify-center transition-transform duration-300 group-hover:scale-110
            {{ $step['active'] ? 'bg-white/10 text-white' : ($step['done'] ? 'bg-green-100 text-green-600' : 'bg-amber-50 border border-amber-100 text-amber-500') }}">
            <i class="fa-solid {{ $step['icon'] }} text-xs"></i>
        </div>
    </div>

    <div class="my-3 flex-1 flex flex-col justify-center">
        <h4 class="text-xs font-black tracking-tight transition-colors duration-300
            {{ $step['active'] ? 'text-white' : ($step['done'] ? 'text-gray-800 group-hover:text-green-700' : 'text-gray-700 group-hover:text-amber-700') }}">
            {{ $step['title'] }}
        </h4>
        <p class="text-[11px] mt-0.5 leading-snug transition-colors duration-300
            {{ $step['active'] ? 'text-blue-100/90' : ($step['done'] ? 'text-gray-400 group-hover:text-gray-500' : 'text-gray-400 group-hover:text-gray-500') }}">
            {{ $step['desc'] }}
        </p>
    </div>

    <div class="pt-2 border-t flex flex-col gap-1 transition-colors duration-300
        {{ $step['active'] ? 'border-white/10' : ($step['done'] ? 'border-green-100/60' : 'border-amber-100/60') }}">
        <div class="flex items-center justify-between text-[10px] font-bold">
            <span class="{{ $step['active'] ? 'text-blue-200' : 'text-gray-400' }}">{{ $step['date'] }}</span>
        </div>

        @if($step['active'])
        {{-- LABEL BIRU AKTIF (POSISI AKUN) --}}
        <div class="mt-1 flex items-center justify-center gap-1 bg-white text-blue-700 text-[10px] font-black py-1 rounded-md shadow-xs animate-pulse">
            <i class="fa-solid fa-location-dot"></i> POSISI AKUN
        </div>
        @elseif($step['done'])
        {{-- LABEL HIJAU SELESAI --}}
        <div class="text-[10px] text-green-600 font-bold flex items-center gap-1 mt-0.5">
            <i class="fa-solid fa-circle-check"></i> {{ $step['status'] }}
        </div>
        @else
        {{-- LABEL KUNING MENDATANG --}}
        <div class="text-[10px] text-amber-600 font-bold flex items-center gap-1 mt-0.5">
            <i class="fa-solid fa-circle-ellipsis text-[9px]"></i> {{ $step['status'] }}
        </div>
        @endif
    </div>
</div>
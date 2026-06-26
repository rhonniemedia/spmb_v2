@php
$cardBase = 'relative rounded-xl p-4 border flex flex-col justify-between min-h-[148px] transition-colors duration-200';

if ($step['active']) {
$cardStyle = $cardBase . ' bg-blue-600 border-blue-600 text-white';
$badgeCls = 'bg-white/20 text-white';
$iconCls = 'bg-white/15 text-white';
$titleCls = 'text-white font-semibold';
$descCls = 'text-blue-100/80';
$dividerCls = 'border-white/15';
$dateCls = 'text-blue-200';
} elseif ($step['done']) {
$cardStyle = $cardBase . ' bg-green-50 border-green-100 hover:border-green-200';
$badgeCls = 'bg-green-100 text-green-700';
$iconCls = 'bg-green-100 text-green-600';
$titleCls = 'text-gray-800 font-medium';
$descCls = 'text-gray-400';
$dividerCls = 'border-green-100';
$dateCls = 'text-gray-400';
} else {
$cardStyle = $cardBase . ' bg-gray-50 border-gray-100 hover:border-gray-200';
$badgeCls = 'bg-gray-100 text-gray-500';
$iconCls = 'bg-white border border-gray-100 text-gray-400';
$titleCls = 'text-gray-600 font-medium';
$descCls = 'text-gray-400';
$dividerCls = 'border-gray-100';
$dateCls = 'text-gray-400';
}
@endphp

<div class="{{ $cardStyle }}">

    {{-- Badge & Icon --}}
    <div class="flex items-center justify-between mb-3">
        <span class="text-[10px] font-bold tracking-widest uppercase px-2 py-0.5 rounded {{ $badgeCls }}">
            {{ $step['no'] }}
        </span>
        <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ $iconCls }}">
            <i class="fa-solid {{ $step['icon'] }} text-xs"></i>
        </div>
    </div>

    {{-- Judul & Deskripsi --}}
    <div class="flex-1">
        <h4 class="text-xs leading-snug {{ $titleCls }}">{{ $step['title'] }}</h4>
        <p class="text-[11px] mt-1 leading-snug {{ $descCls }}">{{ $step['desc'] }}</p>
    </div>

    {{-- Footer: Tanggal & Status --}}
    <div class="pt-2.5 mt-2 border-t {{ $dividerCls }}">
        <span class="text-[10px] {{ $dateCls }}">{{ $step['date'] }}</span>

        @if($step['active'])
        <div class="mt-1.5 flex items-center justify-center gap-1 bg-white text-blue-600 text-[10px] font-semibold py-1 rounded-md">
            <i class="fa-solid fa-location-dot text-[9px]"></i> Posisi kamu
        </div>
        @elseif($step['done'])
        <div class="text-[10px] text-green-600 font-medium flex items-center gap-1 mt-1">
            <i class="fa-solid fa-circle-check text-[9px]"></i> {{ $step['status'] }}
        </div>
        @else
        <div class="text-[10px] text-gray-400 flex items-center gap-1 mt-1">
            <i class="fa-regular fa-clock text-[9px]"></i> {{ $step['status'] }}
        </div>
        @endif
    </div>

</div>
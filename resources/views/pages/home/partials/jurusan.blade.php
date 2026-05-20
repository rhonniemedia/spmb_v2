<section id="jurusan" class="py-24">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-6">

        <div class="text-center mb-16 fade-up">
            <div class="inline-flex items-center gap-2 px-4 py-2 glass rounded-full text-xs font-semibold text-cyan-400 border border-cyan-400/20 mb-6">
                <i class="fa-solid fa-compass text-cyan-400"></i> Program Keahlian
            </div>
            <h2 class="font-display text-4xl lg:text-5xl font-extrabold text-white mb-4">Jurusan <span class="text-gradient-cyan">Unggulan</span></h2>
            <p class="text-slate-400 max-w-xl mx-auto">Pilih program keahlian sesuai minat dan bakat, dirancang bersama mitra industri untuk mempersiapkan karier terbaik.</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach($concentrations as $item)
            @php
            $colorMap = [
            'cyan' => '#22d3ee',
            'emerald' => '#34d399',
            'blue' => '#60a5fa',
            'amber' => '#fbbf24',
            'yellow' => '#facc15',
            'indigo' => '#818cf8',
            'orange' => '#fb923c',
            'rose' => '#f43f5e',
            'red' => '#f87171',
            'sky' => '#38bdf8',
            ];

            $colorName = $item->color ?? 'cyan';
            $hexColor = str_starts_with($colorName, '#') ? $colorName : ($colorMap[$colorName] ?? '#22d3ee');
            @endphp

            <div class="jurusan-card fade-up group cursor-pointer p-8 relative overflow-hidden"
                style="--card-theme-color: {{ $hexColor }};">

                {{-- Glow blob di sudut kanan bawah, muncul saat hover --}}
                <div class="absolute -bottom-8 -right-8 w-36 h-36 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-500 pointer-events-none"
                    style="background: radial-gradient(circle, {{ $hexColor }}33 0%, transparent 70%); filter: blur(12px);"></div>

                {{-- Icon wrapper — scale + rotate ringan saat hover --}}
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-5 transition-all duration-300 group-hover:scale-110 group-hover:-rotate-3"
                    style="background-color: {{ $hexColor }}1a;">
                    <i class="fa-solid {{ $item->icon }} text-2xl transition-transform duration-300 group-hover:scale-110"
                        style="color: {{ $hexColor }};"></i>
                </div>

                <div class="flex items-start justify-between mb-3">
                    <h3 class="font-display font-bold text-white text-lg leading-tight transition-all duration-300 group-hover:text-[--card-theme-color]">
                        {!! nl2br(e($item->name)) !!}
                    </h3>
                    <span class="text-xs px-3 py-1 rounded-full shrink-0 ml-2 border font-medium transition-all duration-300 group-hover:scale-105"
                        style="background-color: {{ $hexColor }}1a; color: {{ $hexColor }}; border-color: {{ $hexColor }}33;">
                        {{ $item->alias }}
                    </span>
                </div>

                <p class="text-slate-400 text-sm leading-relaxed mb-6">{{ $item->description }}</p>

                <div class="flex items-center justify-between">
                    <div class="flex flex-wrap gap-2">
                        @if(is_array($item->tags))
                        @foreach($item->tags as $tag)
                        <span class="text-[11px] font-medium px-2.5 py-1 rounded-lg border transition-all duration-200 cursor-default"
                            style="background-color: {{ $hexColor }}05; color: {{ $hexColor }}e6; border-color: {{ $hexColor }}33;"
                            onmouseover="this.style.backgroundColor='{{ $hexColor }}20'; this.style.borderColor='{{ $hexColor }}66'; this.style.transform='translateY(-2px)';"
                            onmouseout="this.style.backgroundColor='{{ $hexColor }}05'; this.style.borderColor='{{ $hexColor }}33'; this.style.transform='';">
                            # {{ $tag }}
                        </span>
                        @endforeach
                        @endif
                    </div>

                    {{-- Tombol arrow — slide masuk dari kanan saat hover --}}
                    <div class="flex items-center gap-1 text-xs font-semibold opacity-0 group-hover:opacity-100 translate-x-3 group-hover:translate-x-0 transition-all duration-300"
                        style="color: {{ $hexColor }};">
                        Lihat detail
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</section>

<div class="section-divider mx-8 lg:mx-20"></div>
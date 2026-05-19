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
                // Pemetaan string warna dari seeder menjadi kode warna HEX Tailwind v4 standar
                $colorMap = [
                    'cyan'    => '#22d3ee',
                    'emerald' => '#34d399',
                    'blue'    => '#60a5fa',
                    'amber'   => '#fbbf24',
                    'yellow'  => '#facc15',
                    'indigo'  => '#818cf8',
                    'orange'  => '#fb923c',
                    'rose'    => '#f43f5e',
                    'red'     => '#f87171',
                    'sky'     => '#38bdf8',
                ];

                $colorName = $item->color ?? 'cyan';
                $hexColor = str_starts_with($colorName, '#') ? $colorName : ($colorMap[$colorName] ?? '#22d3ee');
            @endphp

            <div class="fade-up glass rounded-3xl p-8 group cursor-pointer border border-transparent transition-all duration-300 hover:border-[{{ $hexColor }}]/30" 
                 style="--card-theme-color: {{ $hexColor }};">
                
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-5 transition-colors"
                     style="background-color: {{ $hexColor }}1a; text-color: {{ $hexColor }};"
                     data-hover-bg="{{ $hexColor }}33">
                    <i class="fa-solid {{ $item->icon }} text-2xl" style="color: {{ $hexColor }};"></i>
                </div>

                <div class="flex items-start justify-between mb-3">
                    <h3 class="font-display font-bold text-white text-lg leading-tight">{!! nl2br(e($item->name)) !!}</h3>
                    <span class="text-xs px-3 py-1 rounded-full shrink-0 ml-2 border font-medium"
                          style="background-color: {{ $hexColor }}1a; color: {{ $hexColor }}; border-color: {{ $hexColor }}33;">
                        {{ $item->alias }}
                    </span>
                </div>

                <p class="text-slate-400 text-sm leading-relaxed mb-6">{{ $item->description }}</p>

                <div class="flex items-center justify-between">
                    <div class="flex flex-wrap gap-2">
                        @if(is_array($item->tags))
                        @foreach($item->tags as $tag)
                        <span class="text-[11px] font-medium px-2.5 py-1 rounded-lg border transition-all"
                              style="background-color: {{ $hexColor }}05; color: {{ $hexColor }}e6; border-color: {{ $hexColor }}33;"
                              onmouseover="this.style.borderColor='{{ $hexColor }}66'"
                              onmouseout="this.style.borderColor='{{ $hexColor }}33'">
                            # {{ $tag }}
                        </span>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</section>

<div class="section-divider mx-8 lg:mx-20"></div>
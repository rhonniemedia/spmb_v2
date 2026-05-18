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
                // Ambil warna dari DB, jika kosong default ke cyan
                $c = $item->color ?? 'cyan';
                @endphp

                <div class="jurusan-card fade-up glass rounded-3xl p-8 group cursor-pointer border border-transparent">
                    <div class="w-14 h-14 rounded-2xl bg-{{ $c }}-400/10 flex items-center justify-center mb-5 group-hover:bg-{{ $c }}-400/20 transition-colors">
                        <i class="fa-solid {{ $item->icon }} text-{{ $c }}-400 text-2xl"></i>
                    </div>

                    <div class="flex items-start justify-between mb-3">
                        <h3 class="font-display font-bold text-white text-lg leading-tight">{!! nl2br(e($item->name)) !!}</h3>
                        <span class="text-xs px-3 py-1 rounded-full bg-{{ $c }}-400/10 text-{{ $c }}-400 border border-{{ $c }}-400/20 shrink-0 ml-2">
                            {{ $item->alias }}
                        </span>
                    </div>

                    <p class="text-slate-400 text-sm leading-relaxed mb-6">{{ $item->description }}</p>

                    <div class="flex items-center justify-between">
                        <div class="flex flex-wrap gap-2">
                            @if(is_array($item->tags))
                            @foreach($item->tags as $tag)
                            <span class="text-[11px] font-medium px-2.5 py-1 rounded-lg border border-{{ $c }}-400/20 text-{{ $c }}-400/90 bg-{{ $c }}-400/[0.02] transition-all group-hover:border-{{ $c }}-400/40">
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

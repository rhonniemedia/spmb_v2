<section id="pendaftaran" class="py-24 relative overflow-hidden">

    {{-- Ambient background --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="glow-orb orb-cyan w-[600px] h-[600px] -left-60 top-1/3 opacity-30"></div>
        <div class="glow-orb orb-gold w-[400px] h-[400px] -right-40 bottom-1/4 opacity-25"></div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative">

        {{-- Header --}}
        <div class="text-center mb-20 fade-up">
            <div class="inline-flex items-center gap-2 px-4 py-2 glass rounded-full text-xs font-semibold text-cyan-400 border border-cyan-400/20 mb-6">
                <i class="fa-solid fa-list-check text-cyan-400"></i> Alur Pendaftaran
            </div>
            <h2 class="font-display text-4xl lg:text-5xl font-extrabold text-white mb-4">
                Langkah-Langkah <span class="text-gradient-cyan">Pendaftaran</span>
            </h2>
            <p class="text-slate-400 max-w-xl mx-auto">{{ $spmbSteps->count() }} langkah mudah menuju bangku SMK impianmu. Ikuti proses ini dengan seksama.</p>
        </div>

        {{-- Steps --}}
        <div class="relative">

            {{-- Vertical connector line --}}
            <div class="absolute left-[27px] top-10 bottom-10 w-px hidden md:block"
                style="background: linear-gradient(180deg, transparent 0%, var(--primary) 10%, var(--primary) 90%, transparent 100%); opacity: 0.2;"></div>

            <div class="space-y-4">

                @php
                $colorMap = [
                'cyan' => '#22d3ee',
                'blue' => '#60a5fa',
                'purple' => '#a78bfa',
                'amber' => '#fbbf24',
                'emerald' => '#34d399',
                'indigo' => '#818cf8',
                'teal' => '#2dd4bf',
                'red' => '#ff1443',
                'orange' => '#fb923c',
                ];
                $delays = ['', 'delay-100', 'delay-200', '', 'delay-100', 'delay-200', '', 'delay-100', 'delay-200'];
                @endphp

                @foreach($spmbSteps as $i => $step)
                @php
                $hex = $colorMap[$step->color] ?? '#60a5fa';
                $delay = $delays[$i % 3] ?? '';
                $num = str_pad($step->step_order, 2, '0', STR_PAD_LEFT);
                @endphp

                <div class="step-card {{ $step->is_highlight ? 'glass-gold' : 'glass' }} fade-up {{ $delay }} rounded-2xl p-5 relative overflow-hidden group"
                    style="--step-color: {{ $hex }};">

                    {{-- Hover glow --}}
                    <div class="absolute -right-10 -bottom-10 w-44 h-44 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-500 pointer-events-none"
                        style="background: radial-gradient(circle, {{ $hex }}22 0%, transparent 70%); filter: blur(16px);"></div>

                    <div class="flex gap-4 items-center relative z-10">

                        {{-- Step number badge --}}
                        <div class="shrink-0 flex flex-col items-center gap-2">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center font-display font-extrabold text-sm transition-all duration-300 group-hover:scale-110 group-hover:rotate-3"
                                style="background: {{ $hex }}20; color: {{ $hex }}; border: 1.5px solid {{ $hex }}33;">
                                {{ $step->step_order }}
                            </div>
                        </div>

                        {{-- Icon --}}
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 transition-all duration-300 group-hover:scale-110 group-hover:-rotate-6"
                            style="background: {{ $hex }}15; border: 1px solid {{ $hex }}25;">
                            <i class="fa-solid {{ $step->icon }} text-lg" style="color: {{ $hex }};"></i>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-1">
                                <h3 class="font-display font-bold text-white text-base leading-tight transition-all duration-300 group-hover:translate-x-1"
                                    style="transition: color 0.3s, transform 0.3s;"
                                    onmouseenter="this.style.color='{{ $hex }}'"
                                    onmouseleave="this.style.color=''">
                                    {{ $step->title }}
                                </h3>
                                <span class="text-[10px] font-bold tracking-wider opacity-30 font-mono" style="color: {{ $hex }};">
                                    {{ $num }}
                                </span>
                            </div>

                            <p class="text-slate-400 text-sm leading-relaxed {{ $step->tags || $step->show_statuses || $step->show_result_badges || $step->alert_text ? 'mb-3' : '' }}">
                                {{ $step->description }}
                            </p>

                            {{-- Tags --}}
                            @if($step->tags)
                            <div class="flex flex-wrap gap-1.5">
                                @foreach((is_array($step->tags) ? $step->tags : json_decode($step->tags, true)) as $tag)
                                <span class="text-[11px] font-medium px-2.5 py-1 rounded-lg border transition-all duration-200 cursor-default"
                                    style="background: {{ $hex }}0d; color: {{ $hex }}cc; border-color: {{ $hex }}2a;"
                                    onmouseenter="this.style.background='{{ $hex }}22'; this.style.transform='translateY(-1px)';"
                                    onmouseleave="this.style.background='{{ $hex }}0d'; this.style.transform='';">
                                    {{ $tag }}
                                </span>
                                @endforeach
                            </div>
                            @endif

                            {{-- Note --}}
                            @if($step->note)
                            <p class="text-xs text-slate-500 mt-2">{{ $step->note }}</p>
                            @endif

                            {{-- Alert text --}}
                            @if($step->alert_text)
                            <div class="inline-flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold {{ $step->tags ? 'mt-3' : '' }}"
                                style="background: {{ $hex }}12; border: 1px solid {{ $hex }}30; color: {{ $hex }};">
                                <i class="fa-solid fa-calendar-days text-[11px]"></i>
                                {{ is_array($step->alert_text) ? implode(' ', $step->alert_text) : $step->alert_text }}
                            </div>
                            @endif

                            {{-- Status badges --}}
                            @if($step->show_statuses)
                            <div class="flex flex-wrap gap-2 {{ $step->tags ? 'mt-3' : '' }}">
                                <span class="text-xs px-3 py-1.5 rounded-full border font-medium flex items-center gap-1.5"
                                    style="background: rgba(251,191,36,0.1); color: #fbbf24; border-color: rgba(251,191,36,0.25);">
                                    <i class="fa-solid fa-clock text-[10px]"></i> Menunggu
                                </span>
                                <span class="text-xs px-3 py-1.5 rounded-full border font-medium flex items-center gap-1.5"
                                    style="background: rgba(52,211,153,0.1); color: #34d399; border-color: rgba(52,211,153,0.25);">
                                    <i class="fa-solid fa-check text-[10px]"></i> Diverifikasi
                                </span>
                                <span class="text-xs px-3 py-1.5 rounded-full border font-medium flex items-center gap-1.5"
                                    style="background: rgba(248,113,113,0.1); color: #f87171; border-color: rgba(248,113,113,0.25);">
                                    <i class="fa-solid fa-exclamation text-[10px]"></i> Perlu Perbaikan
                                </span>
                            </div>
                            @endif

                            {{-- Result badges --}}
                            @if($step->show_result_badges)
                            <div class="flex flex-wrap gap-2 mt-3">
                                <span class="badge-lulus text-xs px-3 py-1.5 rounded-full font-semibold flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-check text-xs"></i> Lulus
                                </span>
                                <span class="badge-tidak text-xs px-3 py-1.5 rounded-full font-semibold flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-xmark text-xs"></i> Tidak Lulus
                                </span>
                            </div>
                            @endif

                        </div>

                        {{-- Arrow indicator --}}
                        <div class="shrink-0 opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all duration-300">
                            <i class="fa-solid fa-chevron-right text-xs" style="color: {{ $hex }};"></i>
                        </div>

                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>
</section>

<div class="section-divider mx-8 lg:mx-20"></div>

<section id="daftar" class="py-24 relative overflow-hidden">
    <div class="glow-orb orb-cyan w-[500px] h-[500px] -left-40 top-1/2 -translate-y-1/2 opacity-40"></div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- CTA below steps -->
        <div class="text-center fade-up">
            <div class="flex items-center justify-center gap-4 flex-wrap">
                <a href="{{route('register')}}" class="btn-primary inline-flex items-center gap-3 px-10 py-4 rounded-2xl font-bold text-lg">
                    <i class="fa-solid fa-rocket"></i>
                    Mulai Pendaftaran Sekarang
                </a>
                <a href="{{route('login')}}" class="btn-outline inline-flex items-center gap-3 px-10 py-4 rounded-2xl font-bold text-lg">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    Masuk ke Akun
                </a>
            </div>
            <p class="text-slate-500 text-sm mt-4">Gratis biaya pendaftaran · Proses 100% online</p>
        </div>
    </div>
</section>

<div class="section-divider mx-8 lg:mx-20"></div>
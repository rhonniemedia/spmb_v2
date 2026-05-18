<section id="jadwal" class="py-24">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-16 fade-up">
                <div class="inline-flex items-center gap-2 px-4 py-2 glass rounded-full text-xs font-semibold text-gold-400 border border-yellow-500/20 mb-6">
                    <i class="fa-solid fa-calendar-days text-yellow-400"></i> Jadwal Kegiatan
                </div>
                <h2 class="font-display text-4xl lg:text-5xl font-extrabold text-white mb-4">Jadwal <span class="text-gradient-gold">Pendaftaran</span></h2>
                <p class="text-slate-400">Catat tanggal-tanggal penting berikut agar tidak melewatkan proses pendaftaran.</p>
            </div>

            <div class="glass rounded-3xl overflow-hidden fade-up">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/10">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-cyan-400 uppercase tracking-wider">Kegiatan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-cyan-400 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-cyan-400 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($spmbSteps as $step)
                        @php
                        $c = $step->color ?? 'cyan';
                        $currentDate = now();

                        // Logika penentuan status otomatis berdasarkan range tanggal
                        $isOpen = false;
                        $isPast = false;

                        if ($step->start_date && $step->end_date) {
                        $isOpen = $currentDate->between($step->start_date, $step->end_date);
                        $isPast = $currentDate->greaterThan($step->end_date);
                        }
                        @endphp

                        <tr class="jadwal-row transition-colors cursor-default">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-{{ $c }}-400/10 flex items-center justify-center shrink-0">
                                        <i class="fa-solid {{ $step->icon ?? 'fa-circle-dot' }} text-{{ $c }}-400 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-white text-sm">{{ $step->title }}</p>
                                        <p class="text-slate-500 text-xs">{{ $step->description }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-slate-300 text-sm text-right">{{ $step->period_text }}</td>
                            <td class="px-6 py-5">
                                @if($isOpen)
                                <span class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-full bg-green-400/10 text-green-400 border border-green-400/20 font-semibold">
                                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>Berlangsung
                                </span>
                                @elseif($isPast)
                                <span class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-full bg-red-400/10 text-red-400 border border-red-400/20 font-semibold">
                                    Selesai
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-full bg-slate-100/50 text-slate-400 border border-slate-600/30 font-semibold">
                                    Menunggu
                                </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </section>

    <div class="section-divider mx-8 lg:mx-20"></div>

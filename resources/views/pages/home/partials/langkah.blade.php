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
            <p class="text-slate-400 max-w-xl mx-auto">9 langkah mudah menuju bangku SMK impianmu. Ikuti proses ini dengan seksama.</p>
        </div>

        {{-- Steps --}}
        <div class="relative">

            {{-- Vertical connector line --}}
            <div class="absolute left-[27px] top-10 bottom-10 w-px hidden md:block"
                style="background: linear-gradient(180deg, transparent 0%, var(--primary) 10%, var(--primary) 90%, transparent 100%); opacity: 0.2;"></div>

            <div class="space-y-4">

                @php
                $steps = [
                [
                'num' => '01',
                'title' => 'Buat Akun',
                'desc' => 'Daftarkan diri menggunakan email aktif dan buat password. Sistem akan mengirimkan link verifikasi otomatis ke email Anda.',
                'icon' => 'fa-user-plus',
                'color' => 'var(--primary)',
                'hex' => '#ff1443',
                'tags' => ['Email Aktif', 'Verifikasi Otomatis'],
                ],
                [
                'num' => '02',
                'title' => 'Login ke Sistem',
                'desc' => 'Masuk menggunakan email dan password yang telah terdaftar. Dashboard peserta didik langsung tersedia.',
                'icon' => 'fa-right-to-bracket',
                'color' => '#60a5fa',
                'hex' => '#60a5fa',
                'tags' => ['Dashboard Peserta'],
                ],
                [
                'num' => '03',
                'title' => 'Lengkapi Biodata',
                'desc' => 'Isi formulir data diri secara lengkap dan benar.',
                'icon' => 'fa-id-card',
                'color' => '#34d399',
                'hex' => '#34d399',
                'tags' => ['Nama Lengkap', 'NISN', 'TTL', 'Alamat', 'Asal Sekolah', 'Data Ortu'],
                'grid' => true,
                ],
                [
                'num' => '04',
                'title' => 'Pilih Jurusan',
                'desc' => 'Pilih jurusan sesuai minat dan kemampuan. Informasi kuota dan detail jurusan tersedia untuk membantu keputusanmu.',
                'icon' => 'fa-compass-drafting',
                'color' => '#a78bfa',
                'hex' => '#a78bfa',
                'tags' => ['Info Kuota', '5 Pilihan'],
                ],
                [
                'num' => '05',
                'title' => 'Upload Berkas',
                'desc' => 'Unggah dokumen pendaftaran dalam format PDF atau JPG.',
                'icon' => 'fa-cloud-arrow-up',
                'color' => 'var(--primary)',
                'hex' => '#ff1443',
                'tags' => ['Pas Foto', 'Kartu Keluarga', 'Ijazah / SKL', 'Raport', 'Sertifikat*'],
                'grid' => true,
                'note' => '*Sertifikat pendukung bersifat opsional',
                'progress' => true,
                ],
                [
                'num' => '06',
                'title' => 'Verifikasi Data',
                'desc' => 'Panitia melakukan pengecekan data dan berkas. Status pendaftaran dapat dipantau secara real-time.',
                'icon' => 'fa-shield-halved',
                'color' => '#fbbf24',
                'hex' => '#fbbf24',
                'statuses' => [
                ['icon' => 'fa-clock', 'label' => 'Menunggu', 'bg' => 'rgba(251,191,36,0.1)', 'clr' => '#fbbf24', 'br' => 'rgba(251,191,36,0.25)'],
                ['icon' => 'fa-check', 'label' => 'Diverifikasi', 'bg' => 'rgba(52,211,153,0.1)', 'clr' => '#34d399', 'br' => 'rgba(52,211,153,0.25)'],
                ['icon' => 'fa-exclamation', 'label' => 'Perlu Perbaikan', 'bg' => 'rgba(248,113,113,0.1)', 'clr' => '#f87171', 'br' => 'rgba(248,113,113,0.25)'],
                ],
                ],
                [
                'num' => '07',
                'title' => 'Seleksi',
                'desc' => 'Peserta mengikuti tes akademik, wawancara, atau seleksi administrasi sesuai jurusan.',
                'icon' => 'fa-pen-to-square',
                'color' => '#fb923c',
                'hex' => '#fb923c',
                'alert' => 'Jadwal Seleksi: 14–18 Juli 2026',
                ],
                [
                'num' => '08',
                'title' => 'Pengumuman',
                'desc' => 'Hasil seleksi dapat dilihat melalui dashboard peserta. Notifikasi dikirimkan via email.',
                'icon' => 'fa-bullhorn',
                'color' => '#34d399',
                'hex' => '#34d399',
                'badges' => true,
                ],
                [
                'num' => '09',
                'title' => 'Daftar Ulang 🎉',
                'desc' => 'Peserta yang dinyatakan lulus wajib melakukan daftar ulang dengan mengunggah bukti pembayaran atau konfirmasi kehadiran.',
                'icon' => 'fa-flag-checkered',
                'color' => '#fbbf24',
                'hex' => '#fbbf24',
                'tags' => ['Bukti Pembayaran', 'Konfirmasi Hadir'],
                'gold' => true,
                ],
                ];
                @endphp

                @foreach($steps as $i => $step)
                @php $delay = ['', 'delay-100', 'delay-200', '', 'delay-100', 'delay-200', '', 'delay-100', 'delay-200'][$i]; @endphp

                <div class="step-card {{ $step['gold'] ?? false ? 'glass-gold' : 'glass' }} fade-up {{ $delay }} rounded-2xl p-5 relative overflow-hidden group"
                    style="--step-color: {{ $step['hex'] }};">

                    {{-- Hover glow --}}
                    <div class="absolute -right-10 -bottom-10 w-44 h-44 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-500 pointer-events-none"
                        style="background: radial-gradient(circle, {{ $step['hex'] }}22 0%, transparent 70%); filter: blur(16px);"></div>

                    <div class="flex gap-4 items-center relative z-10">

                        {{-- Step number badge --}}
                        <div class="shrink-0 flex flex-col items-center gap-2">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center font-display font-extrabold text-sm transition-all duration-300 group-hover:scale-110 group-hover:rotate-3"
                                style="background: {{ $step['hex'] }}20; color: {{ $step['hex'] }}; border: 1.5px solid {{ $step['hex'] }}33;">
                                {{ intval($step['num']) }}
                            </div>
                        </div>

                        {{-- Icon --}}
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 transition-all duration-300 group-hover:scale-110 group-hover:-rotate-6"
                            style="background: {{ $step['hex'] }}15; border: 1px solid {{ $step['hex'] }}25;">
                            <i class="fa-solid {{ $step['icon'] }} text-lg" style="color: {{ $step['hex'] }};"></i>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-1">
                                <h3 class="font-display font-bold text-white text-base leading-tight transition-all duration-300 group-hover:translate-x-1"
                                    style="transition: color 0.3s, transform 0.3s;"
                                    onmouseenter="this.style.color='{{ $step['hex'] }}'"
                                    onmouseleave="this.style.color=''">
                                    {{ $step['title'] }}
                                </h3>
                                <span class="text-[10px] font-bold tracking-wider opacity-30 font-mono" style="color: {{ $step['hex'] }};">
                                    {{ $step['num'] }}
                                </span>
                            </div>
                            <p class="text-slate-400 text-sm leading-relaxed {{ isset($step['tags']) || isset($step['statuses']) || isset($step['badges']) || isset($step['alert']) ? 'mb-3' : '' }}">
                                {{ $step['desc'] }}
                            </p>

                            {{-- Tags / grid --}}
                            @if(isset($step['tags']))
                            <div class="{{ ($step['grid'] ?? false) ? 'flex flex-wrap' : 'flex flex-wrap' }} gap-1.5">
                                @foreach($step['tags'] as $tag)
                                <span class="text-[11px] font-medium px-2.5 py-1 rounded-lg border transition-all duration-200 cursor-default"
                                    style="background: {{ $step['hex'] }}0d; color: {{ $step['hex'] }}cc; border-color: {{ $step['hex'] }}2a;"
                                    onmouseenter="this.style.background='{{ $step['hex'] }}22'; this.style.transform='translateY(-1px)';"
                                    onmouseleave="this.style.background='{{ $step['hex'] }}0d'; this.style.transform='';">
                                    {{ $tag }}
                                </span>
                                @endforeach
                            </div>
                            @if(isset($step['note']))
                            <p class="text-xs text-slate-500 mt-2">{{ $step['note'] }}</p>
                            @endif
                            @if(isset($step['progress']))
                            <div class="flex items-center gap-3 mt-3">
                                <div class="flex-1 h-1.5 rounded-full overflow-hidden" style="background: rgba(255,255,255,0.08);">
                                    <div class="progress-bar h-full rounded-full"></div>
                                </div>
                                <span class="text-xs font-semibold shrink-0" style="color: {{ $step['hex'] }};">85%</span>
                            </div>
                            @endif
                            @endif

                            {{-- Status badges --}}
                            @if(isset($step['statuses']))
                            <div class="flex flex-wrap gap-2">
                                @foreach($step['statuses'] as $s)
                                <span class="text-xs px-3 py-1.5 rounded-full border font-medium flex items-center gap-1.5 transition-all duration-200"
                                    style="background: {{ $s['bg'] }}; color: {{ $s['clr'] }}; border-color: {{ $s['br'] }};">
                                    <i class="fa-solid {{ $s['icon'] }} text-[10px]"></i>
                                    {{ $s['label'] }}
                                </span>
                                @endforeach
                            </div>
                            @endif

                            {{-- Alert box --}}
                            @if(isset($step['alert']))
                            <div class="inline-flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold"
                                style="background: {{ $step['hex'] }}12; border: 1px solid {{ $step['hex'] }}30; color: {{ $step['hex'] }};">
                                <i class="fa-solid fa-calendar-days text-[11px]"></i>
                                {{ $step['alert'] }}
                            </div>
                            @endif

                            {{-- Result badges --}}
                            @if(isset($step['badges']))
                            <div class="flex flex-wrap gap-2">
                                <span class="badge-lulus text-xs px-3 py-1.5 rounded-full font-semibold flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-check text-xs"></i> Lulus
                                </span>
                                <span class="badge-cadangan text-xs px-3 py-1.5 rounded-full font-semibold flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-minus text-xs"></i> Cadangan
                                </span>
                                <span class="badge-tidak text-xs px-3 py-1.5 rounded-full font-semibold flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-xmark text-xs"></i> Tidak Lulus
                                </span>
                            </div>
                            @endif
                        </div>

                        {{-- Arrow indicator --}}
                        <div class="shrink-0 opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all duration-300">
                            <i class="fa-solid fa-chevron-right text-xs" style="color: {{ $step['hex'] }};"></i>
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
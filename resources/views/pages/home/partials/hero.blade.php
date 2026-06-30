<section class="hero-bg grid-pattern min-h-screen flex items-center relative overflow-hidden pt-20">
    <!-- Orbs -->
    <div class="glow-orb orb-cyan w-[700px] h-[700px] -top-40 -left-40"></div>
    <div class="glow-orb orb-gold w-[500px] h-[500px] top-1/2 right-0"></div>
    <div class="glow-orb orb-cyan w-[300px] h-[300px] bottom-0 left-1/3"></div>

    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-6 py-20 relative z-10">
        <div class="grid lg:grid-cols-2 gap-16 items-center">

            <!-- Left Content -->
            <div class="parallax-hero">
                <div class="fade-up mb-6 inline-flex items-center gap-2 px-4 py-2 glass rounded-full text-xs font-semibold text-cyan-400 border border-cyan-400/20">
                    <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                    Pendaftaran Dibuka · 2026/2027
                </div>

                <h1 class="fade-up delay-100 font-display font-extrabold text-5xl sm:text-6xl lg:text-7xl leading-[1.05] mb-6">
                    <span class="text-white block">SPMB SMK</span>
                    <span class="text-gradient-cyan block">Tahun 2026</span>
                </h1>

                <p class="fade-up delay-200 text-slate-400 text-lg leading-relaxed mb-8 max-w-xl">
                    Wujudkan masa depanmu bersama kami. Daftar secara online, pilih jurusan impianmu, dan mulai perjalanan karier di SMK Negeri 1 Rejang Lebong.
                </p>

                <div class="fade-up delay-300 flex flex-wrap gap-4 mb-12">
                    @if($isPengumumanActive)
                    <button x-data @click="$dispatch('open-kelulusan-modal')" class="btn-primary px-8 py-4 rounded-2xl font-bold text-base flex items-center gap-3">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        Cek Kelulusan
                    </button>
                    @else
                    <a href="#daftar" class="btn-primary px-8 py-4 rounded-2xl font-bold text-base flex items-center gap-3">
                        <i class="fa-solid fa-rocket"></i>
                        Daftar Sekarang
                    </a>
                    @endif
                    <a href="#jurusan" class="btn-outline px-8 py-4 rounded-2xl font-bold text-base flex items-center gap-3">
                        <i class="fa-solid fa-book-open"></i>
                        Lihat Jurusan
                    </a>
                </div>

                <!-- Mini stats -->
                <div class="fade-up delay-400 grid grid-cols-3 gap-4">
                    <div class="glass rounded-2xl p-4 text-center">
                        <p class="font-display text-2xl font-bold text-gradient-gold counter" data-target="9">0</p>
                        <p class="text-slate-400 text-xs mt-1">Jurusan</p>
                    </div>
                    <div class="glass rounded-2xl p-4 text-center">
                        <p class="font-display text-2xl font-bold text-gradient-cyan counter">1.000+</p>
                        <p class="text-slate-400 text-xs mt-1">Pelajar Aktif</p>
                    </div>
                    <div class="glass rounded-2xl p-4 text-center">
                        <p class="font-display text-2xl font-bold text-white">99<span class="text-cyan-400">%</span></p>
                        <p class="text-slate-400 text-xs mt-1">Kelulusan</p>
                    </div>
                </div>
            </div>

            <!-- Right Illustration -->
            <div class="fade-right delay-200 hidden lg:flex items-center justify-center relative">
                <div class="relative hero-illustration max-w-[450px]">

                    <div class="relative z-10 drop-shadow-[0_20px_50px_rgba(255,20,67,0.15)] bg-gradient-to-b from-transparent to-white/5 rounded-3xl p-4">
                        <img src="{{ asset('imgs/maskot.png') }}"
                            alt="Maskot SPMB SMK"
                            class="w-full h-auto object-contain max-h-[480px] pointer-events-none select-none" />
                    </div>

                    <div class="absolute -top-4 -right-6 glass rounded-2xl px-5 py-3 text-sm font-semibold text-gold-400 border border-gold-500/20 shadow-xl z-20 backdrop-blur-md">
                        <i class="fa-solid fa-star text-yellow-400 mr-2 animate-spin-slow"></i>Akreditasi B
                    </div>

                    <div class="absolute -bottom-2 -left-8 glass rounded-2xl px-5 py-3 text-sm font-semibold text-green-400 border border-green-500/20 shadow-xl z-20 backdrop-blur-md">
                        <i class="fa-solid fa-industry mr-2"></i>50+ Mitra Industri
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Scroll indicator -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-slate-500 text-xs animate-bounce">
        <span>Scroll</span>
        <i class="fa-solid fa-chevron-down"></i>
    </div>
</section>
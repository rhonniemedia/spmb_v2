<nav id="navbar" class="fixed top-0 inset-x-0 z-50 py-4">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-6">
            <div class="flex items-center justify-between">

                <!-- Logo -->
                <a href="#" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-400 to-cyan-600 flex items-center justify-center shadow-lg shadow-cyan-500/30">
                        <i class="fa-solid fa-graduation-cap text-navy-950 text-lg"></i>
                    </div>
                    <div>
                        <p class="font-display font-bold text-white leading-tight text-sm">SMK Negeri 1</p>
                        <p class="text-xs text-cyan-400/70 leading-tight font-medium tracking-wide">Rejang Lebong</p>
                    </div>
                </a>

                <!-- Desktop Nav -->
                <div class="hidden lg:flex items-center gap-8">
                    <a href="#tentang" class="text-sm text-slate-300 hover:text-cyan-400 transition-colors font-medium">Tentang</a>
                    <a href="#jurusan" class="text-sm text-slate-300 hover:text-cyan-400 transition-colors font-medium">Jurusan</a>
                    <a href="#pendaftaran" class="text-sm text-slate-300 hover:text-cyan-400 transition-colors font-medium">Cara Daftar</a>
                    <a href="#jadwal" class="text-sm text-slate-300 hover:text-cyan-400 transition-colors font-medium">Jadwal</a>
                    <a href="#fasilitas" class="text-sm text-slate-300 hover:text-cyan-400 transition-colors font-medium">Fasilitas</a>
                    <a href="#faq" class="text-sm text-slate-300 hover:text-cyan-400 transition-colors font-medium">FAQ</a>
                </div>

                <!-- CTA & Dark Mode -->
                <div class="flex items-center gap-3">
                    <a href="#daftar" class="hidden lg:block btn-primary px-5 py-2 rounded-xl text-sm font-bold">
                        <i class="fa-solid fa-user-plus mr-2"></i>Daftar Sekarang
                    </a>
                    <!-- Hamburger -->
                    <button id="hamburger" onclick="toggleMenu()" class="lg:hidden w-9 h-9 flex items-center justify-center glass rounded-lg">
                        <i class="fa-solid fa-bars text-slate-300 text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="lg:hidden">
                <div class="pt-4 pb-2 flex flex-col gap-1">
                    <a href="#tentang" onclick="closeMenu()" class="px-4 py-3 text-sm text-slate-300 hover:text-cyan-400 hover:bg-white/5 rounded-lg transition-colors">Tentang Sekolah</a>
                    <a href="#jurusan" onclick="closeMenu()" class="px-4 py-3 text-sm text-slate-300 hover:text-cyan-400 hover:bg-white/5 rounded-lg transition-colors">Jurusan</a>
                    <a href="#pendaftaran" onclick="closeMenu()" class="px-4 py-3 text-sm text-slate-300 hover:text-cyan-400 hover:bg-white/5 rounded-lg transition-colors">Cara Daftar</a>
                    <a href="#jadwal" onclick="closeMenu()" class="px-4 py-3 text-sm text-slate-300 hover:text-cyan-400 hover:bg-white/5 rounded-lg transition-colors">Jadwal</a>
                    <a href="#fasilitas" onclick="closeMenu()" class="px-4 py-3 text-sm text-slate-300 hover:text-cyan-400 hover:bg-white/5 rounded-lg transition-colors">Fasilitas</a>
                    <a href="#faq" onclick="closeMenu()" class="px-4 py-3 text-sm text-slate-300 hover:text-cyan-400 hover:bg-white/5 rounded-lg transition-colors">FAQ</a>
                    <div class="mt-2 px-2">
                        <a href="#daftar" onclick="closeMenu()" class="block btn-primary px-5 py-3 rounded-xl text-sm font-bold text-center">
                            <i class="fa-solid fa-user-plus mr-2"></i>Daftar Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
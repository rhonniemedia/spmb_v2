    <!-- ══ SIDEBAR ══ -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="flex flex-col w-[280px] shrink-0 h-screen fixed inset-y-0 left-0 z-50 bg-white border-r border-border transform lg:translate-x-0 transition-transform duration-300 overflow-hidden">

        <!-- Logo -->
        <div class="flex items-center justify-between border-b border-border h-[90px] px-5 gap-3">
            <div class="flex items-center gap-3">
                <div class="w-11 h-9 bg-primary rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" y1="13" x2="8" y2="13" />
                        <line x1="16" y1="17" x2="8" y2="17" />
                        <polyline points="10 9 9 9 8 9" />
                    </svg>
                </div>
                <div>
                    <h1 class="font-bold text-base text-foreground leading-tight">SPMB</h1>
                    <p class="text-xs text-secondary">SMK Negeri 1 Rejang Lebong</p>
                </div>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden size-11 flex shrink-0 bg-white rounded-xl p-[10px] items-center justify-center ring-1 ring-border hover:ring-primary transition-all duration-300 cursor-pointer">
                <i data-lucide="x" class="size-6 text-secondary"></i>
            </button>
        </div>

        <!-- Nav -->
        <div class="flex flex-col p-5 pb-28 gap-6 overflow-y-auto flex-1 scrollbar-hide">

            <div class="flex flex-col gap-4">
                <h3 class="font-medium text-sm text-secondary">Dashboard</h3>
                <div class="flex flex-col gap-1">
                    <a href="{{route('admin.dashboard')}}" class="group cursor-pointer {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <div class="flex items-center rounded-xl p-3 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
                            <i data-lucide="square-dashed-mouse-pointer" class="size-5 text-secondary group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300"></i>
                            <span class="font-medium text-sm text-secondary group-[.active]:font-semibold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Beranda</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="flex flex-col gap-4">
                <h3 class="font-medium text-sm text-secondary">Manajemen Peserta</h3>
                <div class="flex flex-col gap-1">
                    <a href="{{route('admin.verifikasi')}}" class="group cursor-pointer {{ request()->routeIs('admin.verifikasi') ? 'active' : '' }}">
                        <div class="flex items-center rounded-xl p-3 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
                            <i data-lucide="file-check" class="size-5 text-secondary group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300"></i>
                            <span class="font-medium text-sm text-secondary group-[.active]:font-semibold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Verifikasi</span>
                            <span class="ml-auto h-5 min-w-5 px-1.5 rounded-full bg-primary text-white text-[10px] font-bold flex items-center justify-center">24</span>
                        </div>
                    </a>
                    <a href="#" class="group cursor-pointer">
                        <div class="flex items-center rounded-xl p-3 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
                            <i data-lucide="users" class="size-5 text-secondary group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300"></i>
                            <span class="font-medium text-sm text-secondary group-[.active]:font-semibold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Data Peserta</span>
                        </div>
                    </a>
                    <a href="#" class="group cursor-pointer">
                        <div class="flex items-center rounded-xl p-3 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
                            <i data-lucide="bar-chart-3" class="size-5 text-secondary group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300"></i>
                            <span class="font-medium text-sm text-secondary group-[.active]:font-semibold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Peringkat & Seleksi</span>
                        </div>
                    </a>
                    <a href="#" class="group cursor-pointer">
                        <div class="flex items-center rounded-xl p-3 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
                            <i data-lucide="log-in" class="size-5 text-secondary group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300"></i>
                            <span class="font-medium text-sm text-secondary group-[.active]:font-semibold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Daftar Ulang</span>
                            <span class="ml-auto h-5 min-w-5 px-1.5 rounded-full bg-success text-white text-[10px] font-bold flex items-center justify-center">4</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="flex flex-col gap-4">
                <h3 class="font-medium text-sm text-secondary">Pengumuman</h3>
                <div class="flex flex-col gap-1">
                    <a href="{{route('admin.pengumuman')}}" class="group cursor-pointer {{ request()->routeIs('admin.pengumuman') ? 'active' : '' }}">
                        <div class="flex items-center rounded-xl p-3 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
                            <i data-lucide="megaphone" class="size-5 text-secondary group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300"></i>
                            <span class="font-medium text-sm text-secondary group-[.active]:font-semibold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Pengumuman</span>
                        </div>
                    </a>
                    <a href="#" class="group cursor-pointer">
                        <div class="flex items-center rounded-xl p-3 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
                            <i data-lucide="bell-ring" class="size-5 text-secondary group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300"></i>
                            <span class="font-medium text-sm text-secondary group-[.active]:font-semibold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Notifikasi Massal</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="flex flex-col gap-4">
                <h3 class="font-medium text-sm text-secondary">Sistem</h3>
                <div class="flex flex-col gap-1">
                    <a href="#" class="group cursor-pointer">
                        <div class="flex items-center rounded-xl p-3 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
                            <i data-lucide="calendar" class="size-5 text-secondary group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300"></i>
                            <span class="font-medium text-sm text-secondary group-[.active]:font-semibold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Jadwal SPMB</span>
                        </div>
                    </a>
                    <a href="#" class="group cursor-pointer">
                        <div class="flex items-center rounded-xl p-3 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
                            <i data-lucide="settings" class="size-5 text-secondary group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300"></i>
                            <span class="font-medium text-sm text-secondary group-[.active]:font-semibold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Pengaturan</span>
                        </div>
                    </a>
                    <a href="#" class="group cursor-pointer">
                        <div class="flex items-center rounded-xl p-3 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
                            <i data-lucide="download-cloud" class="size-5 text-secondary group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300"></i>
                            <span class="font-medium text-sm text-secondary group-[.active]:font-semibold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Export Data</span>
                        </div>
                    </a>
                </div>
            </div>

        </div>

        <!-- Footer -->
        <div class="absolute bottom-0 left-0 w-[280px]">
            <div class="flex items-center justify-between border-t bg-white border-border p-5 gap-3">
                <div class="min-w-0">
                    <p class="font-semibold text-foreground text-sm">Admin Panitia</p>
                    <p class="text-xs text-secondary mt-0.5">SPMB 2025/2026</p>
                </div>
                <div class="size-11 bg-primary/10 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i data-lucide="life-buoy" class="size-6 text-primary"></i>
                </div>
            </div>
        </div>
    </aside>
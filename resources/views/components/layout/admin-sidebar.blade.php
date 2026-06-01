    <!-- ══ SIDEBAR ══ -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="flex flex-col w-[280px] shrink-0 h-screen fixed inset-y-0 left-0 z-50 bg-white border-r border-border transform lg:translate-x-0 transition-transform duration-300 overflow-hidden">

        <!-- Logo -->
        <div class="flex items-center justify-between border-b border-border h-[90px] px-5 gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 shrink-0 rounded-xl flex items-center justify-center bg-gradient-to-br from-red-500 to-purple-700 shadow-sm">
                    <i data-lucide="graduation-cap" class="size-5 text-white"></i>
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
                    <a href="{{route('admin.pendaftar.index')}}" class="group cursor-pointer {{ request()->routeIs('admin.pendaftar.index') ? 'active' : '' }}">
                        <div class="flex items-center rounded-xl p-3 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
                            <i data-lucide="users" class="size-5 text-secondary group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300"></i>
                            <span class="font-medium text-sm text-secondary group-[.active]:font-semibold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Data Peserta</span>
                        </div>
                    </a>
                    <a href="{{route('admin.observasi.index')}}" class="group cursor-pointer {{ request()->routeIs('admin.observasi.index') ? 'active' : '' }}">
                        <div class="flex items-center rounded-xl p-3 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
                            <i data-lucide="file-check" class="size-5 text-secondary group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300"></i>
                            <span class="font-medium text-sm text-secondary group-[.active]:font-semibold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Observasi</span>
                            <span class="ml-auto h-5 min-w-5 px-1.5 rounded-full bg-primary text-white text-[10px] font-bold flex items-center justify-center">24</span>
                        </div>
                    </a>
                    <a href="#" class="group cursor-pointer">
                        <div class="flex items-center rounded-xl p-3 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
                            <i data-lucide="bar-chart-3" class="size-5 text-secondary group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300"></i>
                            <span class="font-medium text-sm text-secondary group-[.active]:font-semibold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Hasil Seleksi</span>
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
                <h3 class="font-medium text-sm text-secondary">Pengguna</h3>
                <div class="flex flex-col gap-1">
                    @can('superadmin')
                    <a href="{{route('admin.pengguna.index')}}" class="group cursor-pointer {{ request()->routeIs('admin.pengguna.index') ? 'active' : '' }}">
                        <div class="flex items-center rounded-xl p-3 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
                            <i data-lucide="users-round" class="size-5 text-secondary group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300"></i>
                            <span class="font-medium text-sm text-secondary group-[.active]:font-semibold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Daftar Pengguna</span>
                        </div>
                    </a>
                    @endcan
                    <a href="{{route('admin.profil.index')}}" class="group cursor-pointer {{ request()->routeIs('admin.profil.index') ? 'active' : '' }}">
                        <div class="flex items-center rounded-xl p-3 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
                            <i data-lucide="circle-user" class="size-5 text-secondary group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300"></i>
                            <span class="font-medium text-sm text-secondary group-[.active]:font-semibold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Akun Pengguna</span>
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
                    <p class="text-xs text-secondary mt-0.5">SPMB {{ date('Y') }}</p>
                </div>
                <div class="size-11 bg-primary/10 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i data-lucide="life-buoy" class="size-6 text-primary"></i>
                </div>
            </div>
        </div>
    </aside>
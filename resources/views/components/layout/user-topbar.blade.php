<nav class="bg-white border-b border-gray-200 sticky top-0 z-[200] shadow-sm">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 h-[60px] flex items-center justify-between">

        <a href="{{ url('/') }}" class="flex items-center gap-2 sm:gap-[10px] no-underline flex-shrink-0 mr-4 lg:mr-8">
            <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-[10px] bg-primary flex items-center justify-center shadow-primary-sm">
                <i data-lucide="graduation-cap" class="text-white w-4 h-4"></i>
            </div>
            <div class="flex flex-col justify-center">
                <div class="text-sm font-black leading-tight text-[#080C1A]">Portal SPMB</div>
                <div class="text-[10px] sm:text-xs text-[#6A7686] hidden sm:block">{{ $g_schoolInfo->name }}</div>
                <div class="text-[10px] text-[#6A7686] sm:hidden truncate max-w-[120px]">{{ $g_schoolInfo->name }}</div>
            </div>
        </a>

        <div class="hidden lg:flex items-center gap-0.5 flex-1 overflow-x-auto scrollbar-hide">
            @php
            $navLinks = [
            ['label' => 'Dashboard', 'icon' => 'layout-dashboard', 'route' => 'dashboard', 'active' => request()->routeIs('dashboard')],
            ['label' => 'Biodata', 'icon' => 'id-card', 'route' => 'biodata', 'active' => request()->routeIs('biodata')],
            ];

            if (isset($g_isDaftarUlangActive) && $g_isDaftarUlangActive) {
            $navLinks[] = [
            'label' => 'Daftar Ulang',
            'icon' => 'clipboard-check',
            'route' => 'daftar-ulang',
            'active' => request()->routeIs('daftar-ulang')
            ];
            }

            $navLinks[] = ['label' => 'Bantuan', 'icon' => 'circle-help', 'route' => 'bantuan', 'active' => request()->routeIs('bantuan')];
            $navLinks[] = ['label' => 'Pengumuman', 'icon' => 'megaphone', 'route' => 'pengumuman', 'active' => request()->routeIs('pengumuman')];
            @endphp

            @foreach($navLinks as $link)
            <a href="{{ $link['route'] !== '#' ? route($link['route']) : '#' }}"
                class="inline-flex items-center gap-[7px] px-[14px] py-[7px] rounded-full text-[13px] font-semibold no-underline transition-all whitespace-nowrap {{ $link['active'] ? 'bg-primary-light text-primary' : 'text-[#6A7686] hover:bg-gray-50 hover:text-[#080C1A]' }}">
                <i data-lucide="{{ $link['icon'] }}" class="w-[14px] h-[14px] shrink-0"></i>
                {{ $link['label'] }}
                @if(isset($link['badge']))
                <span class="{{ $link['badgeColor'] }} text-white text-[10px] font-bold px-1.5 py-0 rounded-full min-w-[18px] text-center">
                    {{ $link['badge'] }}
                </span>
                @endif
            </a>
            @endforeach
        </div>

        <div class="flex items-center gap-2 sm:gap-3">
            @auth
            @php
            $unreadNotifications = auth()->user()->unreadNotifications;
            $allNotifications = auth()->user()->notifications()->take(5)->get();
            @endphp

            <div class="relative hidden sm:block" x-data="{ openNotif: false }">

                <button @click="openNotif = !openNotif"
                    hx-get="{{ route('notifications.dropdown') }}"
                    hx-trigger="click, every 15s, refresh-notifications from:body"
                    hx-target="#notification-list"
                    class="relative w-[34px] h-[34px] rounded-[10px] bg-gray-50 border border-gray-200 flex items-center justify-center cursor-pointer text-[#6A7686] hover:border-primary hover:text-primary transition-all focus:outline-none">
                    <i data-lucide="bell" class="w-4 h-4"></i>

                    <div id="notification-badge">
                        @if($unreadNotifications->count() > 0)
                        <div class="absolute top-[5px] right-[5px] w-[8px] h-[8px] rounded-full bg-primary border-2 border-white animate-pulse"></div>
                        @endif
                    </div>
                </button>

                <div x-show="openNotif"
                    @click.away="openNotif = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-[15px] shadow-xl py-2 z-[300]"
                    style="display: none;">

                    <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center">
                        <span class="text-xs font-black text-[#080C1A]">Pemberitahuan</span>
                        <span id="notification-count-header">
                            @if($unreadNotifications->count() > 0)
                            <span class="text-[10px] bg-red-50 text-red-500 px-2 py-0.5 rounded-full font-bold">
                                {{ $unreadNotifications->count() }} Baru
                            </span>
                            @endif
                        </span>
                    </div>

                    <div id="notification-list" class="max-h-64 overflow-y-auto custom-scrollbar">
                        @forelse($allNotifications as $notification)
                        <a href="{{ route('notifications.read', $notification->id) }}"
                            class="flex gap-3 px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-50 {{ $notification->read_at ? 'opacity-60' : 'bg-primary/5' }}">
                            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                                <i data-lucide="{{ str_replace('fa-', '', $notification->data['icon'] ?? 'info') }}" class="{{ $notification->data['color'] ?? 'text-gray-500' }} w-4 h-4"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-800 {{ $notification->read_at ? 'font-normal' : 'font-bold' }}">
                                    {{ $notification->data['title'] }}
                                </p>
                                <p class="text-[11px] text-[#6A7686] mt-0.5 line-clamp-2 leading-relaxed">
                                    {{ $notification->data['message'] }}
                                </p>
                                <span class="text-[9px] text-gray-400 block mt-1 flex items-center">
                                    <i data-lucide="clock" class="w-2.5 h-2.5 mr-1"></i> {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </a>
                        @empty
                        <div class="text-center py-8 text-gray-400 flex flex-col items-center">
                            <i data-lucide="bell-off" class="w-6 h-6 mb-2 text-gray-300"></i>
                            <span class="text-xs">Belum ada pemberitahuan</span>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            @endauth

            <div class="relative" x-data="{ profileOpen: false }">
                <div @click="profileOpen = !profileOpen"
                    class="flex items-center gap-1.5 sm:gap-2 bg-gray-50 border border-gray-200 rounded-full py-1 pl-1 pr-2 sm:pr-3 cursor-pointer hover:border-primary transition-all">

                    <div class="w-[26px] h-[26px] sm:w-[28px] sm:h-[28px] rounded-full flex items-center justify-center font-black text-white text-[10px] sm:text-[11px] flex-shrink-0"
                        style="background: linear-gradient(135deg, #FF1443, #FF6B8A);">
                        @php
                        $name = Auth::user()->real_name;
                        $initials = collect(explode(' ', $name))
                        ->map(fn($segment) => mb_substr($segment, 0, 1))
                        ->take(2)
                        ->join('');
                        @endphp
                        {{ strtoupper($initials) }}
                    </div>

                    <span class="hidden sm:inline text-[13px] font-bold text-[#080C1A] whitespace-nowrap">
                        {{ Auth::user()->real_name }}
                    </span>

                    <i data-lucide="chevron-down" class="w-3 h-3 text-[#6A7686] transition-transform"
                        :class="profileOpen ? 'rotate-180' : ''"></i>
                </div>

                <div x-show="profileOpen"
                    x-cloak
                    @click.away="profileOpen = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="absolute right-0 mt-2 w-48 sm:w-52 bg-white border border-gray-200 rounded-xl shadow-lg py-2 px-2 z-[210]">
                    <a href="#" class="flex items-center gap-2 px-3 py-2 text-[12px] sm:text-[13px] text-[#6A7686] hover:bg-gray-50 hover:text-primary font-medium rounded-lg transition-all">
                        <i data-lucide="user-cog" class="w-4 h-4 shrink-0"></i> Pengaturan Profil
                    </a>
                    <hr class="my-1 border-gray-100">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-[12px] sm:text-[13px] text-red-500 hover:bg-red-50 font-bold rounded-lg transition-all">
                            <i data-lucide="log-out" class="w-4 h-4 shrink-0"></i> Keluar Sistem
                        </button>
                    </form>
                </div>
            </div>

            <button @click="mobileMenu = !mobileMenu"
                class="lg:hidden w-[32px] h-[32px] sm:w-[34px] sm:h-[34px] rounded-[10px] bg-gray-50 border border-gray-200 flex items-center justify-center cursor-pointer text-[#6A7686] hover:border-primary transition-all">
                <i data-lucide="menu" class="w-4 h-4 sm:w-5 sm:h-5" x-show="!mobileMenu"></i>
                <i data-lucide="x" class="w-4 h-4 sm:w-5 sm:h-5" x-show="mobileMenu" x-cloak></i>
            </button>
        </div>
    </div>

    <div x-show="mobileMenu"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="lg:hidden border-t border-gray-200 bg-white px-4 pb-4 pt-2 shadow-inner max-h-[calc(100vh-60px)] overflow-y-auto">
        <div class="flex flex-col gap-1">
            @foreach($navLinks as $link)
            <a href="{{ $link['route'] !== '#' ? route($link['route']) : '#' }}"
                class="flex items-center gap-3 px-3 py-[10px] rounded-[10px] text-[13px] font-semibold transition-all {{ $link['active'] ? 'bg-primary/10 text-primary' : 'text-[#6A7686] hover:bg-gray-50' }}">
                <i data-lucide="{{ $link['icon'] }}" class="w-5 h-5 shrink-0"></i>
                {{ $link['label'] }}
            </a>
            @endforeach

            <div class="border-t border-gray-100 my-2 pt-2">
                <div class="flex justify-between items-center px-3 mb-2">
                    <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Notifikasi Terbaru</span>
                    <span id="notification-count-mobile">
                        @if($unreadNotifications->count() > 0)
                        <span class="text-[10px] text-primary font-bold bg-primary-light px-2 py-0.5 rounded-full">
                            {{ $unreadNotifications->count() }} Baru
                        </span>
                        @endif
                    </span>
                </div>

                <div id="notification-list-mobile" class="flex flex-col gap-2">
                    @auth
                    @php
                    $mobileNotifs = auth()->user()->unreadNotifications()->take(3)->get();
                    @endphp
                    @forelse($mobileNotifs as $notif)
                    <a href="{{ route('notifications.read', $notif->id) }}"
                        class="flex items-start gap-3 px-3 py-2.5 rounded-[10px] bg-primary/5 border border-primary/10 mx-1 transition-colors hover:bg-primary/10">
                        <i data-lucide="{{ str_replace('fa-', '', $notif->data['icon'] ?? 'info') }}" class="{{ $notif->data['color'] ?? 'text-gray-500' }} w-3.5 h-3.5 mt-0.5 shrink-0"></i>
                        <div class="flex-1">
                            <div class="text-xs font-bold text-gray-800 leading-tight">{{ $notif->data['title'] }}</div>
                            <div class="text-[11px] text-[#6A7686] line-clamp-1 mt-0.5">{{ $notif->data['message'] }}</div>
                        </div>
                    </a>
                    @empty
                    <div class="text-center py-4 text-gray-400 text-xs">
                        Tidak ada pemberitahuan baru
                    </div>
                    @endforelse
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>
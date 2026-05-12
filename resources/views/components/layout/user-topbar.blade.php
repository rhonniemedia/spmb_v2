<nav class="bg-white border-b border-gray-200 sticky top-0 z-[200] shadow-sm">
    <div class="max-w-[1400px] mx-auto px-6 h-[60px] flex items-center">

        <a href="{{ url('/') }}" class="flex items-center gap-[10px] no-underline flex-shrink-0 mr-8">
            <div class="w-9 h-9 rounded-[10px] bg-primary flex items-center justify-center shadow-primary-sm">
                <i class="fa-solid fa-graduation-cap text-white text-sm"></i>
            </div>
            <div>
                <div class="text-sm font-black leading-tight text-[#080C1A]">Portal SPMB</div>
                <div class="text-xs text-[#6A7686] hidden xs:block sm:block">SMK Negeri 1 Rejang Lebong</div>
                <div class="text-xs text-[#6A7686] sm:hidden">SMKN 1 Rejang Lebong</div>
            </div>
        </a>

        <div class="hidden lg:flex items-center gap-0.5 flex-1 overflow-x-auto scrollbar-hide">
            @php
            $navLinks = [
            ['label' => 'Dashboard', 'icon' => 'fa-gauge', 'route' => 'dashboard', 'active' => request()->routeIs('dashboard')],
            ['label' => 'Biodata', 'icon' => 'fa-id-card', 'route' => 'biodata', 'active' => request()->routeIs('biodata'), 'badge' => '!', 'badgeColor' => 'bg-amber-400'],
            ['label' => 'Pengumuman', 'icon' => 'fa-bullhorn', 'route' => 'pengumuman', 'active' => request()->routeIs('pengumuman')],
            ['label' => 'Daftar Ulang', 'icon' => 'fa-rotate-right', 'route' => 'daftar-ulang', 'active' => request()->routeIs('daftar-ulang')],
            ['label' => 'Bantuan', 'icon' => 'fa-circle-question', 'route' => '#', 'active' => false],
            ];
            @endphp

            @foreach($navLinks as $link)
            <a href="{{ $link['route'] !== '#' ? route($link['route']) : '#' }}"
                class="inline-flex items-center gap-[7px] px-[14px] py-[7px] rounded-full text-[13px] font-semibold no-underline transition-all whitespace-nowrap {{ $link['active'] ? 'bg-primary-light text-primary' : 'text-[#6A7686] hover:bg-gray-50 hover:text-[#080C1A]' }}">
                <i class="fa-solid {{ $link['icon'] }} text-xs w-[14px] text-center"></i>
                {{ $link['label'] }}
                @if(isset($link['badge']))
                <span class="{{ $link['badgeColor'] }} text-white text-[10px] font-bold px-1.5 py-0 rounded-full min-w-[18px] text-center">
                    {{ $link['badge'] }}
                </span>
                @endif
            </a>
            @endforeach
        </div>

        <div class="flex items-center gap-3 ml-auto">
            <div class="relative w-[34px] h-[34px] rounded-[10px] bg-gray-50 border border-gray-200 flex items-center justify-center cursor-pointer text-[#6A7686] text-[13px] hover:border-primary hover:text-primary transition-all">
                <i class="fa-solid fa-bell"></i>
                <div class="absolute top-[5px] right-[5px] w-[7px] h-[7px] rounded-full bg-primary border-2 border-white"></div>
            </div>

            <div class="relative" x-data="{ profileOpen: false }">
                <div @click="profileOpen = !profileOpen"
                    class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-full py-1 pl-1 pr-3 cursor-pointer hover:border-primary transition-all">
                    <div class="w-[26px] h-[26px] rounded-full bg-gradient-to-br from-primary to-[#FF6B8A] flex items-center justify-center font-black text-white text-[11px]">AF</div>
                    <span class="hidden md:block text-[13px] font-bold text-[#080C1A]">Ahmad Fauzi</span>
                    <i class="fa-solid fa-chevron-down text-[10px] text-[#6A7686] transition-transform" :class="profileOpen ? 'rotate-180' : ''"></i>
                </div>

                <div x-show="profileOpen"
                    @click.away="profileOpen = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-xl shadow-lg py-2 z-[210]">
                    <a href="#" class="block px-4 py-2 text-[13px] text-[#6A7686] hover:bg-gray-50 hover:text-primary font-medium">
                        <i class="fa-solid fa-user-gear mr-2"></i> Pengaturan Profil
                    </a>
                    <hr class="my-1 border-gray-100">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-[13px] text-red-500 hover:bg-red-50 font-bold">
                            <i class="fa-solid fa-right-from-bracket mr-2"></i> Keluar Sistem
                        </button>
                    </form>
                </div>
            </div>

            <button @click="mobileMenu = !mobileMenu"
                class="lg:hidden w-[34px] h-[34px] rounded-[10px] bg-gray-50 border border-gray-200 flex items-center justify-center cursor-pointer text-[#6A7686] hover:border-primary transition-all">
                <i class="fa-solid" :class="mobileMenu ? 'fa-xmark' : 'fa-bars'"></i>
            </button>
        </div>
    </div>

    <div x-show="mobileMenu"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="lg:hidden border-t border-gray-200 bg-white px-4 pb-4 pt-2 shadow-inner">
        <div class="flex flex-col gap-1">
            @foreach($navLinks as $link)
            <a href="{{ $link['route'] !== '#' ? route($link['route']) : '#' }}"
                class="flex items-center gap-3 px-3 py-[10px] rounded-[10px] text-[13px] font-semibold transition-all {{ $link['active'] ? 'bg-primary-light text-primary' : 'text-[#6A7686] hover:bg-gray-50' }}">
                <i class="fa-solid {{ $link['icon'] }} w-5"></i>
                {{ $link['label'] }}
                @if(isset($link['badge']))
                <span class="ml-auto {{ $link['badgeColor'] }} text-white text-[10px] font-bold px-1.5 py-0 rounded-full min-w-[18px] text-center">
                    {{ $link['badge'] }}
                </span>
                @endif
            </a>
            @endforeach
        </div>
    </div>
</nav>
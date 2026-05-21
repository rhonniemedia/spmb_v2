<nav class="bg-white border-b border-gray-200 sticky top-0 z-[200] shadow-sm">
    <div class="max-w-[1200px] mx-auto px-6 h-[60px] flex items-center">

        <a href="{{ url('/') }}" class="flex items-center gap-[10px] no-underline flex-shrink-0 mr-8">
            <div class="w-9 h-9 rounded-[10px] bg-primary flex items-center justify-center shadow-primary-sm">
                <i class="fa-solid fa-graduation-cap text-white text-sm"></i>
            </div>
            <div>
                <div class="text-sm font-black leading-tight text-[#080C1A]">Portal SPMB</div>
                <div class="text-xs text-[#6A7686] hidden xs:block sm:block">{{ $g_schoolInfo->name }}</div>
                <div class="text-xs text-[#6A7686] sm:hidden">{{ $g_schoolInfo->name }}</div>
            </div>
        </a>

        <div class="hidden lg:flex items-center gap-0.5 flex-1 overflow-x-auto scrollbar-hide">
            @php
            // 1. Definisikan rute awal yang selalu muncul di depan
            $navLinks = [
            ['label' => 'Dashboard', 'icon' => 'fa-gauge', 'route' => 'dashboard', 'active' => request()->routeIs('dashboard')],
            ['label' => 'Biodata', 'icon' => 'fa-id-card', 'route' => 'biodata', 'active' => request()->routeIs('biodata')],
            ];

            // 2. LOGIKABARU: Sisipkan Daftar Ulang secara dinamis di sini (sebelum Bantuan & Pengumuman)
            if (isset($g_isDaftarUlangActive) && $g_isDaftarUlangActive) {
            $navLinks[] = [
            'label' => 'Daftar Ulang',
            'icon' => 'fa-clipboard-check',
            'route' => 'daftar-ulang',
            'active' => request()->routeIs('daftar-ulang')
            ];
            }

            // 3. Tambahkan rute penutup di bagian akhir array
            $navLinks[] = ['label' => 'Bantuan', 'icon' => 'fa-circle-question', 'route' => 'bantuan', 'active' => request()->routeIs('bantuan')];
            $navLinks[] = ['label' => 'Pengumuman', 'icon' => 'fa-bullhorn', 'route' => 'pengumuman', 'active' => request()->routeIs('pengumuman')];
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

                    <div class="w-[26px] h-[26px] rounded-full flex items-center justify-center font-black text-white text-[11px] flex-shrink-0"
                        style="background: linear-gradient(135deg, #FF1443, #FF6B8A);">
                        {{-- Logika mengambil inisial dari real_name user --}}
                        @php
                        // Menggunakan real_name yang sudah kita buat di Model User
                        $name = Auth::user()->real_name;

                        $initials = collect(explode(' ', $name))
                        ->map(fn($segment) => mb_substr($segment, 0, 1))
                        ->take(2)
                        ->join('');
                        @endphp
                        {{ strtoupper($initials) }}
                    </div>

                    {{-- Menampilkan nama asli yang dinamis --}}
                    <span class="hidden sm:inline text-[13px] font-bold text-[#080C1A] whitespace-nowrap">
                        {{ Auth::user()->real_name }}
                    </span>

                    <i class="fa-solid fa-chevron-down text-[10px] text-[#6A7686] transition-transform"
                        :class="profileOpen ? 'rotate-180' : ''"></i>
                </div>

                <div x-show="profileOpen"
                    x-cloak
                    @click.away="profileOpen = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="absolute right-0 mt-2 w-52 bg-white border border-gray-200 rounded-xl shadow-lg py-2 px-2 z-[210]">
                    <a href="#" class="flex items-center gap-2 px-3 py-2 text-[13px] text-[#6A7686] hover:bg-gray-50 hover:text-primary font-medium rounded-lg transition-all">
                        <i class="fa-solid fa-user-gear w-4 text-center"></i> Pengaturan Profil
                    </a>
                    <hr class="my-1 border-gray-100">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-[13px] text-red-500 hover:bg-red-50 font-bold rounded-lg transition-all">
                            <i class="fa-solid fa-right-from-bracket w-4 text-center"></i> Keluar Sistem
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
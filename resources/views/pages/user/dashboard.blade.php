@extends('layouts.user')

@section('title', 'Beranda')

@section('content')
{{-- BREADCRUMB --}}
<div class="flex items-center gap-1.5 text-[13px] text-[#6A7686] mb-4">
    <a href="/" class="text-primary no-underline font-semibold hover:underline">
        <i class="fa-solid fa-house"></i> Beranda
    </a>
    <span class="text-gray-300">/</span>
    <span>Dashboard</span>
</div>

{{-- ══════════════════════════════════════════
        HERO BANNER
══════════════════════════════════════════ --}}
<div class="relative rounded-[20px] overflow-hidden mb-5 p-6 md:p-7 flex flex-col md:flex-row items-center justify-between gap-5"
    style="background: linear-gradient(135deg, #FF1443 0%, #D90F38 50%, #B00F30 100%);">
    {{-- Decorative circles --}}
    <div class="absolute -top-10 -right-10 w-[200px] h-[200px] bg-white/[0.06] rounded-full pointer-events-none"></div>
    <div class="absolute -bottom-[60px] right-24 w-[160px] h-[160px] bg-white/[0.04] rounded-full pointer-events-none"></div>

    {{-- Left --}}
    <div class="relative z-10 w-full md:flex-1 text-center md:text-left">
        <div class="inline-flex items-center gap-1.5 bg-white/15 text-white text-sm font-bold px-3 py-1 rounded-full mb-3 border border-white/25">
            <i class="fa-solid fa-circle-dot text-[10px] animate-pulse"></i> Status Pendaftaran
        </div>
        <h1 class="text-xl md:text-2xl font-black text-white mb-1 leading-tight">
            Selamat, {{ auth()->user()->first_name }}! Pendaftaran Kamu Aktif!
        </h1>
        <p class="text-sm text-white/80 leading-relaxed mb-5 max-w-[540px]">
            Silakan pantau halaman ini secara berkala untuk melihat perkembangan status dokumen Anda.
            @if($announcementDateText && $announcementDateText !== '-')
            Seluruh hasil verifikasi berkas dan pengumuman resmi kelulusan seleksi akan dirilis secara serentak pada
            <span class="text-white font-bold">{{ $announcementDateText }}</span>.
            @else
            Seluruh hasil verifikasi berkas dan pengumuman resmi kelulusan seleksi akan diumumkan <span class="text-white font-bold">segera</span>.
            @endif
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center md:justify-start">
            <a href="{{ route('pengumuman') }}"
                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white text-[#FF1443] text-[13px] font-black rounded-full no-underline shadow-md hover:bg-gray-50 transition-all">
                <i class="fa-solid fa-trophy"></i> Lihat Pengumuman
            </a>
            <a href="{{ route('biodata') }}"
                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white/15 text-white text-[13px] font-bold rounded-full no-underline border border-white/25 hover:bg-white/25 transition-all">
                <i class="fa-solid fa-pencil"></i> Lengkapi Data
            </a>
        </div>
    </div>

    {{-- Right: Status Card --}}
    <div class="relative z-10 w-full md:w-auto flex-shrink-0">
        <div class="bg-white/10 border border-white/20 rounded-[16px] px-6 py-5 backdrop-blur-md text-center shadow-xl min-w-[180px]">

            <div class="text-[12px] text-white/70 font-semibold uppercase tracking-widest mb-3">
                Tahap Saat Ini
            </div>

            {{-- Lingkaran Animasi & Icon --}}
            <div class="w-[70px] h-[70px] rounded-full border-[3px] border-white/10 border-t-white border-r-white border-b-white/30 mx-auto mb-3 flex items-center justify-center relative {{ $latestActiveStep ? 'animate-[spin_6s_linear_infinite]' : '' }}">
                <div class="absolute w-[52px] h-[52px] rounded-full bg-white/10 flex items-center justify-center text-xl {{ $latestActiveStep ? 'animate-[spin_6s_linear_infinite] [animation-direction:reverse]' : '' }}">
                    {{-- Mengambil icon dari database, jika kosong gunakan default fa-clock --}}
                    <i class="fa-solid {{ $latestActiveStep->icon ?? 'fa-clock' }} text-[18px] text-white/90"></i>
                </div>
            </div>

            {{-- Judul Tahapan --}}
            <div class="text-[14px] font-black text-white leading-tight">
                {{ $latestActiveStep->title ?? 'Tidak Ada Tahap Aktif' }}
            </div>

            {{-- Keterangan Waktu/Status --}}
            <div class="text-[11px] text-white/60 mt-1 font-medium">
                @if($latestActiveStep)
                Tahap Ke-{{ $latestActiveStep->step_order }}
                @else
                Silakan Pantau Berkala
                @endif
            </div>

        </div>
    </div>
</div>


{{-- STATS GRID BERGAYA CARD JURUSAN INTERAKTIF --}}
<div x-data="{ openModal: null }" class="mb-6">

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @php
        $statsCards = [
        [
        'id' => 'persyaratan',
        'label' => 'Berkas Persyaratan',
        'val' => $totalRequirements ?? 0,
        'total' => ' Dokumen',
        'bgGradient' => 'from-rose-600 to-pink-700',
        'shadowColor' => 'shadow-rose-200/80',
        'icon' => 'fa-file-shield',
        'status' => $isPhotoUploaded ? 'Pas Foto Terunggah' : 'Pas Foto Belum Ada',
        'badgeBg' => 'bg-white/20 text-white',
        'actionText' => 'Lihat Berkas'
        ],
        [
        'id' => 'biodata',
        'label' => 'Kelengkapan Biodata',
        'val' => $biodataPercentage ?? 0,
        'total' => '%',
        'bgGradient' => 'from-emerald-500 to-teal-600',
        'shadowColor' => 'shadow-emerald-200/80',
        'icon' => 'fa-user-check',
        'status' => $biodataStatusText ?? 'Belum Lengkap',
        'badgeBg' => 'bg-white/20 text-white',
        'actionText' => 'Lengkapi Sekarang'
        ],
        [
        'id' => 'jadwal',
        'label' => 'Menuju Pengumuman',
        'val' => $daysToAnnouncement ?? 0,
        'total' => ' Hari',
        'bgGradient' => 'from-amber-500 to-orange-600',
        'shadowColor' => 'shadow-amber-200/80',
        'icon' => 'fa-solid fa-calendar',
        'status' => 'Rilis: ' . ($announcementDateText ?? '-'),
        'badgeBg' => 'bg-white/20 text-white',
        'actionText' => 'Lihat Timeline'
        ],
        [
        'id' => 'kuota',
        'label' => 'Kuota & Kompetisi',
        'val' => $totalQuota ?? 0,
        'total' => ' Kursi',
        'bgGradient' => 'from-violet-600 to-indigo-700',
        'shadowColor' => 'shadow-violet-200/80',
        'icon' => 'fa-users-viewfinder',
        'status' => 'Daya Tampung Sekolah',
        'badgeBg' => 'bg-white/20 text-white',
        'actionText' => 'Analisis Peluang'
        ]
        ];
        @endphp

        @foreach($statsCards as $card)
        {{-- Card Item --}}
        <div @click="openModal = '{{ $card['id'] }}'"
            class="relative overflow-hidden rounded-2xl p-5 text-white bg-gradient-to-br {{ $card['bgGradient'] }} shadow-lg {{ $card['shadowColor'] }} cursor-pointer transform transition-all duration-300 hover:-translate-y-2 hover:shadow-xl hover:brightness-110 group">

            <div class="absolute -right-6 -bottom-6 text-white/10 text-7xl transform rotate-12 transition-transform duration-500 group-hover:scale-125 group-hover:rotate-45 pointer-events-none">
                <i class="fa-solid {{ $card['icon'] }}"></i>
            </div>

            <div class="flex items-center justify-between mb-4">
                <div class="w-9 h-9 rounded-xl bg-white/15 backdrop-blur-md flex items-center justify-center text-sm shadow-inner">
                    <i class="fa-solid {{ $card['icon'] }}"></i>
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest {{ $card['badgeBg'] }} px-2 py-0.5 rounded-md">
                    {{ $card['actionText'] }} ↗
                </span>
            </div>

            <div class="text-2xl sm:text-3xl font-black leading-none mb-1 tracking-tight">
                {{ $card['val'] }}<span class="text-sm font-bold opacity-80">{{ $card['total'] }}</span>
            </div>

            <div class="text-[13px] font-black tracking-wide opacity-90 group-hover:opacity-100 transition-opacity">{{ $card['label'] }}</div>

            <div class="mt-3 pt-2 border-t border-white/10 flex items-center gap-1.5 text-[11px] font-bold text-white/85">
                <i class="fa-solid fa-circle-dot text-[8px] text-white animate-pulse"></i>
                <span>{{ $card['status'] }}</span>
            </div>
        </div>
        @endforeach
    </div>

    {{-- DYNAMIC ALPINE.JS MODAL POP-UP --}}
        @include ('pages.user.partials.dashboard._modal')

</div>

{{-- MAIN CONTENT AREA --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
    <div class="lg:col-span-2 space-y-6">
        {{-- Jadwal Seleksi --}}
        @include ('pages.user.partials.dashboard._jadwal-seleksi')

        {{-- Status Dokumen --}}
        @include ('pages.user.partials.dashboard._status-dokumen')
    </div>

    {{-- SIDEBAR --}}
    @include ('pages.user.partials.dashboard._sidebar')
</div>

@endsection

@push('scripts')
<script>
    function switchTab(tab) {
        ['dokumen', 'akademik', 'administratif'].forEach(t => {
            document.getElementById('panel-' + t).classList.add('hidden');
            const btn = document.getElementById('tab-' + t);
            btn.classList.remove('bg-gray-900', 'text-white');
            btn.classList.add('bg-gray-100', 'text-gray-500');
        });
        document.getElementById('panel-' + tab).classList.remove('hidden');
        const active = document.getElementById('tab-' + tab);
        active.classList.remove('bg-gray-100', 'text-gray-500');
        active.classList.add('bg-gray-900', 'text-white');
    }
</script>
@endpush
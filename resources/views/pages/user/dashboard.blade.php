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
            Seluruh hasil verifikasi berkas dan pengumuman resmi kelulusan seleksi akan dirilis secara serentak pada
            <span class="text-white font-bold">10 Juni 2026</span>.
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

            <div class="w-[70px] h-[70px] rounded-full border-[3px] border-white/10 border-t-white border-r-white border-b-white/30 mx-auto mb-3 flex items-center justify-center relative animate-[spin_6s_linear_infinite]">

                <div class="absolute w-[52px] h-[52px] rounded-full bg-white/10 flex items-center justify-center text-xl animate-[spin_6s_linear_infinite] [animation-direction:reverse]">
                    <i class="fa-solid fa-file-magnifying-glass text-[18px] text-white/90"></i>
                </div>

            </div>

            <div class="text-[14px] font-black text-white leading-tight">
                Verifikasi Dokumen
            </div>
            <div class="text-[11px] text-white/60 mt-1 font-medium">
                Diperbarui 2 jam lalu
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
    <div x-show="openModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-950/60 backdrop-blur-sm"
        style="display: none;">

        <div @click.away="openModal = null"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translateY-4"
            x-transition:enter-end="opacity-100 scale-100 translateY-0"
            class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-gray-100 relative overflow-hidden">

            <button @click="openModal = null" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            {{-- ══════════════════════════════════════════
            Isi Konten Modal 1: Persyaratan (Daftar Berkas)
            ══════════════════════════════════════════ --}}
            <div x-show="openModal === 'persyaratan'">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-base">
                        <i class="fa-solid fa-file-shield"></i>
                    </div>
                    <div>
                        <h4 class="text-base font-black text-gray-900">Daftar Berkas Persyaratan</h4>
                        <p class="text-xs text-gray-400">Berkas yang wajib dipersiapkan saat verifikasi</p>
                    </div>
                </div>

                <div class="space-y-2 max-h-[240px] overflow-y-auto pr-1 mb-4">
                    @forelse($requirements as $req)
                    @php
                    // Gunakan warna dari database, fallback ke 'rose' jika null
                    $reqColor = $req->color ?? 'rose';
                    @endphp
                    <div class="p-2.5 bg-gray-50 border border-gray-100 hover:border-{{ $reqColor }}-200 hover:bg-{{ $reqColor }}-50/30 rounded-xl flex justify-between items-center transition-all duration-200 group">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-7 h-7 rounded-lg bg-white shadow-3xs border border-gray-100 flex items-center justify-center text-xs text-gray-600 group-hover:text-{{ $reqColor }}-600 transition-colors">
                                {{-- Render ikon dinamis dari database, fallback ke ikon file-shield jika null --}}
                                <i class="{{ $req->icon ?? 'fa-solid fa-file-shield' }}"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-gray-800 truncate">{{ $req->name }}</p>
                                <span class="text-[10px] text-gray-400 font-medium block truncate">{{ $req->description ?? 'Persyaratan dokumen pendaftaran' }}</span>
                            </div>
                        </div>
                        <span class="text-[9px] font-black px-2 py-0.5 rounded shrink-0 {{ $req->is_mandatory ? 'bg-rose-50 text-rose-600 border border-rose-100' : 'bg-gray-100 text-gray-500' }}">
                            {{ $req->is_mandatory ? 'WAJIB' : 'OPSIONAL' }}
                        </span>
                    </div>
                    @empty
                    <p class="text-xs text-gray-400 text-center py-4">Belum ada daftar persyaratan dokumen.</p>
                    @endforelse
                </div>
                <p class="text-[11px] text-gray-500 border-t border-dashed pt-3">* Silakan lengkapi berkas di atas dalam bentuk fisik/digital sesuai instruksi panitia pendaftaran.</p>
            </div>

            {{-- ══════════════════════════════════════════
                    Isi Konten Modal 2: Biodata
            ══════════════════════════════════════════ --}}
            <div x-show="openModal === 'biodata'">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                    <div>
                        <h4 class="text-base font-black text-gray-900">Validasi Data Pendaftar</h4>
                        <p class="text-xs text-gray-400">Status kelengkapan isian formulir siswa</p>
                    </div>
                </div>

                <div class="space-y-2.5 mb-4 text-xs">
                    {{-- Validasi Identitas Inti --}}
                    <div class="flex justify-between items-center p-2 bg-gray-50 rounded-lg">
                        <span class="text-gray-600 font-semibold">1. Identitas Utama (Nama, NISN, NIK)</span>
                        @if($personalData && $personalData->full_name && $personalData->nisn_hash)
                        <span class="text-emerald-600 font-bold"><i class="fa-solid fa-circle-check"></i> Terisi</span>
                        @else
                        <span class="text-rose-500 font-bold"><i class="fa-solid fa-triangle-exclamation"></i> Belum Lengkap</span>
                        @endif
                    </div>

                    {{-- Validasi Pas Foto --}}
                    <div class="flex justify-between items-center p-2 bg-gray-50 rounded-lg">
                        <span class="text-gray-600 font-semibold">2. Unggah Pas Foto Resmi</span>
                        @if($personalData && $personalData->photo)
                        <span class="text-emerald-600 font-bold"><i class="fa-solid fa-circle-check"></i> Terunggah</span>
                        @else
                        <span class="text-amber-500 font-bold"><i class="fa-solid fa-clock"></i> Belum Ada</span>
                        @endif
                    </div>

                    {{-- Validasi Orang Tua --}}
                    <div class="flex justify-between items-center p-2 bg-gray-50 rounded-lg">
                        <span class="text-gray-600 font-semibold">3. Data Orang Tua / Wali</span>
                        @if($parentDataCount >= 2)
                        <span class="text-emerald-600 font-bold"><i class="fa-solid fa-circle-check"></i> Lengkap ({{ $parentDataCount }} Data)</span>
                        @else
                        <span class="text-amber-500 font-bold"><i class="fa-solid fa-circle-exclamation"></i> Kurang ({{ $parentDataCount }}/2)</span>
                        @endif
                    </div>

                    {{-- Status Finalisasi --}}
                    <div class="flex justify-between items-center p-2 bg-gray-50 rounded-lg">
                        <span class="text-gray-600 font-semibold">4. Kunci / Finalisasi Formulir</span>
                        @if($personalData && $personalData->profile_status === 'final')
                        <span class="px-2 py-0.5 bg-emerald-600 text-white font-bold text-[10px] rounded">FINAL</span>
                        @else
                        <span class="px-2 py-0.5 bg-amber-400 text-gray-900 font-bold text-[10px] rounded">DRAFT</span>
                        @endif
                    </div>
                </div>
                <a href="{{ route('biodata') }}" class="block text-center text-xs font-bold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 p-2.5 rounded-xl transition-colors">Menuju Halaman Pengisian Biodata →</a>
            </div>

            {{-- ══════════════════════════════════════════
                    Isi Konten Modal 3: Jadwal / Timeline
            ══════════════════════════════════════════ --}}
            <div x-show="openModal === 'jadwal'">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-base">
                        <i class="fa-solid fa-calendar-days"></i>
                    </div>
                    <div>
                        <h4 class="text-base font-black text-gray-900">Timeline & Langkah SPMB</h4>
                        <p class="text-xs text-gray-400">Pantau proses seleksi berkala Anda</p>
                    </div>
                </div>

                <div class="space-y-4 max-h-[260px] overflow-y-auto pr-1 mb-4 border-l-2 border-dashed border-gray-200 ml-4 pl-4 relative">
                    @forelse($spmbSteps as $step)
                    @php
                    // Ambil warna dasar step pendaftaran (misal: amber, emerald, blue, dll)
                    $stepColor = $step->color ?? 'amber';
                    @endphp
                    <div class="relative group">
                        {{-- Dot Indikator Berwarna Dinamis Sesuai Database --}}
                        <div class="absolute -left-[21px] top-1 w-2.5 h-2.5 rounded-full bg-{{ $stepColor }}-500 ring-4 ring-white group-hover:scale-125 transition-transform"></div>

                        <div class="flex items-start gap-2 min-w-0">
                            {{-- Ikon Dinamis Berwarna dari Database --}}
                            <div class="w-5 h-5 rounded bg-{{ $stepColor }}-50 text-{{ $stepColor }}-600 flex items-center justify-center text-[10px] shrink-0 mt-0.5">
                                <i class="{{ $step->icon ?? 'fa-solid fa-circle-dot' }}"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-black text-gray-800 leading-tight group-hover:text-{{ $stepColor }}-600 transition-colors">{{ $step->title }}</p>
                                <p class="text-[10px] font-bold text-{{ $stepColor }}-600 mt-0.5 tracking-wide">{{ $step->period_text }}</p>
                                <p class="text-[11px] text-gray-400 mt-1 leading-relaxed">{{ $step->description }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-6">
                        <p class="text-xs text-gray-400">Belum ada alur timeline yang dikonfigurasi.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- ══════════════════════════════════════════
                    Isi Konten Modal 4: Kuota
            ══════════════════════════════════════════ --}}
            <div x-show="openModal === 'kuota'">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-base">
                        <i class="fa-solid fa-users-viewfinder"></i>
                    </div>
                    <div>
                        <h4 class="text-base font-black text-gray-900">Analisis Pagu & Daya Tampung</h4>
                        <p class="text-xs text-gray-400">Rincian Kuota per Kompetensi Keahlian</p>
                    </div>
                </div>

                <div class="space-y-2 max-h-[240px] overflow-y-auto pr-1 mb-4">
                    @forelse($concentrations ?? [] as $con)
                    <div class="p-2.5 bg-gray-50 border border-gray-100 hover:border-indigo-200 hover:bg-indigo-50/30 rounded-xl flex justify-between items-center transition-all duration-200 group">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-7 h-7 rounded-lg bg-white shadow-3xs border border-gray-100 flex items-center justify-center text-xs text-gray-600 group-hover:text-indigo-600 transition-colors">
                                <i class="fa-solid {{ $con->icon ?? 'fa-graduation-cap' }}"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-gray-800 tracking-tight">{{ $con->name }}</p>
                                <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mt-0.5">{{ $con->alias }} ({{ $con->code }})</p>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="text-xs font-black text-gray-900">{{ $con->quota }}</span>
                            <span class="text-[10px] text-gray-400 font-bold block">Kursi</span>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-gray-400 text-center py-4">Belum ada data kompetensi keahlian.</p>
                    @endforelse
                </div>

                <p class="text-[11px] text-gray-500 leading-relaxed border-t border-dashed border-gray-100 pt-3">
                    * Total daya tampung murni dialokasikan untuk <span class="font-bold text-gray-700">{{ $totalQuota ?? 0 }} siswa baru</span> di seluruh jurusan. Seleksi dilakukan ketat berdasarkan pemenuhan berkas serta nilai kompetensi pendaftaran.
                </p>
            </div>

        </div>
    </div>

</div>

{{-- MAIN CONTENT AREA --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
    <div class="lg:col-span-2 space-y-6">
        {{-- Jadwal Seleksi --}}
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden animate-fade-in">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50/50 to-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                <div class="flex items-center gap-3.5">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white flex items-center justify-center shrink-0 shadow-sm shadow-indigo-100">
                        <i class="fa-solid fa-route text-xs tracking-wide"></i>
                    </div>

                    <div>
                        <h3 class="text-sm font-black text-gray-900 tracking-tight sm:text-base">
                            Peta Alur Seleksi PPDB
                        </h3>
                        <p class="text-[11px] font-medium text-gray-400 mt-0.5">
                            Rangkaian tahapan pendaftaran di <span class="text-gray-500 font-semibold">SMK Negeri 1 Rejang Lebong</span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3.5 bg-white border border-gray-100/80 px-4 py-2 rounded-xl shadow-3xs self-start sm:self-center transition-all duration-300 hover:border-blue-100">
                    <div class="text-right">
                        <p class="text-[9px] uppercase font-black text-gray-400 tracking-widest">Status Akun Anda</p>
                        <p class="text-xs font-black text-blue-600 mt-0.5">
                            {{ $currentActiveStepText }}
                        </p>
                    </div>
                    <div class="relative flex h-2 w-2 shrink-0">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-600 shadow-[0_0_6px_rgba(37,99,235,0.6)]"></span>
                    </div>
                </div>

            </div>

            <div class="p-6 space-y-4">

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($topSteps as $step)
                    @include('pages.user.partials.dashboard.step-card', ['step' => $step])
                    @endforeach
                </div>

                <div class="pt-4 border-t border-dashed border-gray-100">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($bottomSteps as $step)
                        @include('pages.user.partials.dashboard.step-card', ['step' => $step])
                        @endforeach
                    </div>
                </div>

            </div>

            <div class="mx-6 mb-6 p-4 rounded-xl bg-blue-50 border border-blue-100 flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center shrink-0 mt-0.5 shadow-sm shadow-blue-100">
                    <i class="fa-solid fa-circle-info text-xs"></i>
                </div>
                <div>
                    <h5 class="text-xs font-bold text-gray-900">Catatan Validasi Alur Pendaftaran:</h5>
                    <p class="text-xs text-gray-600 mt-0.5 leading-relaxed">
                        Sistem mendeteksi kemajuan langkah pendaftaran secara real-time berdasarkan kalender akademik **SMK Negeri 1 Rejang Lebong**. Pastikan seluruh berkas pendukung Anda sudah siap sebelum tenggat waktu tahapan aktif berakhir.
                    </p>
                </div>
            </div>
        </div>

        {{-- Status Dokumen --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50/50 to-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                <div class="flex items-center gap-3.5">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-500 to-green-600 text-white flex items-center justify-center shrink-0 shadow-sm shadow-green-100">
                        <i class="fa-solid fa-file-circle-check text-xs tracking-wide"></i>
                    </div>

                    <div>
                        <h3 class="text-sm font-black text-gray-900 tracking-tight sm:text-base">
                            Kelengkapan Data dan Berkas Pendaftaran
                        </h3>
                        <p class="text-[11px] font-medium text-gray-400 mt-0.5">
                            Pastikan semua persyaratan terpenuhi sebelum batas waktu
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 bg-white border border-gray-100/80 px-4 py-2.5 rounded-xl shadow-3xs self-start sm:self-center transition-all duration-300 hover:border-green-100">
                    <div class="text-right">
                        <p class="text-[9px] uppercase font-black text-gray-400 tracking-widest">Progress Berkas</p>
                        <p class="text-xs font-black text-green-600 mt-0.5">
                            3 / 6 terpenuhi
                        </p>
                    </div>
                    <div class="w-20 h-2 bg-gray-100 rounded-full overflow-hidden shadow-inner shrink-0">
                        <div class="h-full bg-gradient-to-r from-emerald-400 to-green-500 rounded-full" style="width:50%"></div>
                    </div>
                </div>

            </div>

            <div class="px-6 pt-4 flex gap-2" id="req-tabs">
                <button onclick="switchTab('dokumen')" id="tab-dokumen" class="text-xs font-semibold px-3.5 py-1.5 rounded-lg bg-gray-900 text-white transition-all">Dokumen</button>
                <button onclick="switchTab('akademik')" id="tab-akademik" class="text-xs font-semibold px-3.5 py-1.5 rounded-lg bg-gray-100 text-gray-500 transition-all">Akademik</button>
                <button onclick="switchTab('administratif')" id="tab-administratif" class="text-xs font-semibold px-3.5 py-1.5 rounded-lg bg-gray-100 text-gray-500 transition-all">Administratif</button>
            </div>

            <div id="panel-dokumen" class="px-4 py-4 space-y-2">

                <div class="flex items-center gap-3 p-3 rounded-xl bg-green-50 border border-green-100">
                    <div class="w-9 h-9 rounded-lg bg-green-100 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-file-lines text-green-600 text-[15px]"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-gray-800">Akta Kelahiran</p>
                        <p class="text-xs text-gray-400 mt-0.5">Akta kelahiran Asli calon murid baru</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-green-600 bg-white border border-green-200 px-2.5 py-1 rounded-lg">Terverifikasi</span>
                        <i class="fa-solid fa-circle-check text-green-500 text-[16px]"></i>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 rounded-xl bg-green-50 border border-green-100">
                    <div class="w-9 h-9 rounded-lg bg-green-100 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-certificate text-green-600 text-[15px]"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-gray-800">Ijazah SMP</p>
                        <p class="text-xs text-gray-400 mt-0.5">Ijazah SMP Asli dari satuan pendidikan sebelumnya</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-green-600 bg-white border border-green-200 px-2.5 py-1 rounded-lg">Terverifikasi</span>
                        <i class="fa-solid fa-circle-check text-green-500 text-[16px]"></i>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 rounded-xl bg-green-50 border border-green-100">
                    <div class="w-9 h-9 rounded-lg bg-green-100 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-certificate text-green-600 text-[15px]"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-gray-800">Surat Keterangan Lulus (SKL)</p>
                        <p class="text-xs text-gray-400 mt-0.5">Surat Keterangan Lulus (SKL) dari satuan pendidikan sebelumnya</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-green-600 bg-white border border-green-200 px-2.5 py-1 rounded-lg">Terverifikasi</span>
                        <i class="fa-solid fa-circle-check text-green-500 text-[16px]"></i>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 rounded-xl bg-amber-50 border border-amber-100">
                    <div class="w-9 h-9 rounded-lg bg-amber-100 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-school text-amber-600 text-[14px]"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-gray-800">Rapor Semester 1–5</p>
                        <p class="text-xs text-gray-400 mt-0.5">Buku Rapor Asli yang dikeluarkan dari satuan pendidikan sebelumnya</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-amber-600 bg-white border border-amber-200 px-2.5 py-1 rounded-lg">Diperiksa</span>
                        <i class="fa-solid fa-clock text-amber-500 text-[16px]"></i>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 rounded-xl bg-amber-50 border border-amber-100">
                    <div class="w-9 h-9 rounded-lg bg-amber-100 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-school text-amber-600 text-[14px]"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-gray-800">Surat Keterangan Rata-rata Nilai Rapor</p>
                        <p class="text-xs text-gray-400 mt-0.5">Surat Keterangan Rata-rata Nilai Rapor Asli yang dikeluarkan dari satuan pendidikan sebelumnya</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-amber-600 bg-white border border-amber-200 px-2.5 py-1 rounded-lg">Diperiksa</span>
                        <i class="fa-solid fa-clock text-amber-500 text-[16px]"></i>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 rounded-xl bg-red-50 border border-red-100">
                    <div class="w-9 h-9 rounded-lg bg-red-100 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-image text-red-500 text-[15px]"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-gray-800">Pas Foto 3×4</p>
                        <p class="text-xs text-red-400 mt-0.5">Unggahan Pas Foto terbaru berlatar belakang merah/biru.</p>
                    </div>
                    <button class="flex items-center gap-1.5 text-xs font-bold text-white bg-red-500 hover:bg-red-600 px-3 py-1.5 rounded-lg transition-colors shrink-0">
                        <i class="fa-solid fa-upload text-[11px]"></i> Unggah
                    </button>
                </div>

                <div class="flex items-center gap-3 p-3 rounded-xl bg-red-50 border border-red-100">
                    <div class="w-9 h-9 rounded-lg bg-red-100 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-file-invoice text-red-500 text-[15px]"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-gray-800">Surat Keterangan Domisili</p>
                        <p class="text-xs text-red-400 mt-0.5">Surat Keterangan Domisili yang dikeluarkan dinas setempat dari asal calon murid baru</p>
                    </div>
                    <button class="flex items-center gap-1.5 text-xs font-bold text-white bg-red-500 hover:bg-red-600 px-3 py-1.5 rounded-lg transition-colors shrink-0">
                        <i class="fa-solid fa-upload text-[11px]"></i> Unggah
                    </button>
                </div>

            </div>

            <div id="panel-akademik" class="px-4 py-4 space-y-2 hidden">

                <div class="flex items-center gap-3 p-3 rounded-xl bg-green-50 border border-green-100">
                    <div class="w-9 h-9 rounded-lg bg-green-100 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-chart-simple text-green-600 text-[15px]"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-gray-800">Nilai rata-rata Rapor semeter 1 s.d. 5</p>
                        <p class="text-xs text-gray-400 mt-0.5">Rata-rata nilai kamu: <span class="font-bold text-green-600">82.5</span></p>
                    </div>
                    <i class="fa-solid fa-circle-check text-green-500 text-[16px]"></i>
                </div>

                <div class="flex items-center gap-3 p-3 rounded-xl bg-green-50 border border-green-100">
                    <div class="w-9 h-9 rounded-lg bg-green-100 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-pen-to-square text-green-600 text-[15px]"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-gray-800">Nilai rata-rata Tes Kemampuan Akademik (TKA)</p>
                        <p class="text-xs text-gray-400 mt-0.5">Rata-rata nilai kamu: <span class="font-bold text-green-600">78</span></p>
                    </div>
                    <i class="fa-solid fa-circle-check text-green-500 text-[16px]"></i>
                </div>

            </div>

            <div id="panel-administratif" class="px-4 py-4 space-y-2 hidden">

                <div class="flex items-center gap-3 p-3 rounded-xl bg-green-50 border border-green-100">
                    <div class="w-9 h-9 rounded-lg bg-green-100 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-user-check text-green-600 text-[14px]"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-gray-800">Biodata diri lengkap</p>
                        <p class="text-xs text-gray-400 mt-0.5">Semua kolom wajib sudah terisi</p>
                    </div>
                    <i class="fa-solid fa-circle-check text-green-500 text-[16px]"></i>
                </div>

                <div class="flex items-center gap-3 p-3 rounded-xl bg-red-50 border border-red-100">
                    <div class="w-9 h-9 rounded-lg bg-red-100 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-users text-red-500 text-[14px]"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-gray-800">Data orang tua / wali</p>
                        <p class="text-xs text-red-400 mt-0.5">Pekerjaan &amp; penghasilan belum diisi</p>
                    </div>
                    <button class="text-xs font-bold text-white bg-red-500 hover:bg-red-600 px-3 py-1.5 rounded-lg transition-colors">Lengkapi</button>
                </div>

            </div>

            <div class="mx-4 mb-4 p-3 rounded-xl bg-gray-50 border border-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-gray-400 text-[14px]"></i>
                <p class="text-xs text-gray-400">Semua dokumen harus terverifikasi sebelum <span class="font-semibold text-gray-600">5 Juni 2026</span></p>
            </div>
        </div>
    </div>

    {{-- SIDEBAR --}}
    <div class="space-y-6">
        {{-- Notifikasi --}}
        <div class="bg-white border border-gray-200 rounded-card shadow-sm overflow-hidden animate-fade-in">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-base font-black text-[#080C1A]">Notifikasi</h3>
                <span class="bg-primary/10 text-primary text-xs font-black px-2 py-0.5 rounded uppercase">3 Baru</span>
            </div>
            <div class="max-h-[400px] overflow-y-auto">
                @php
                $notifs = [
                ['title' => 'Pas Foto Belum Ada', 'msg' => 'Segera unggah pas foto latar merah/biru.', 'time' => '2 jam lalu'],
                ['title' => 'Rapor Diperiksa', 'msg' => 'Estimasi verifikasi selesai dalam 1x24 jam.', 'time' => '1 hari lalu'],
                ['title' => 'Ijazah Valid', 'msg' => 'Dokumen ijazah Anda dinyatakan sah.', 'time' => '2 hari lalu'],
                ];
                @endphp
                @foreach($notifs as $n)
                <div class="p-4 border-b border-gray-50 hover:bg-gray-50 transition-colors cursor-pointer group">
                    <div class="flex gap-3">
                        <div class="mt-1 flex-shrink-0">
                            <div class="w-2 h-2 rounded-full bg-primary"></div>
                        </div>
                        <div>
                            <h4 class="text-[13px] font-bold text-[#080C1A] group-hover:text-primary">{{ $n['title'] }}</h4>
                            <p class="text-[13px] text-[#6A7686] leading-relaxed mt-1">{{ $n['msg'] }}</p>
                            <div class="text-xs text-[#B0B8C4] mt-1"><i class="fa-regular fa-clock mr-1"></i> {{ $n['time'] }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        @include('pages.user.partials.biodata._sidebar')

    </div>
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
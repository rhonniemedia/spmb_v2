<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman Seleksi · MySch</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background-color: #0f172a;
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            color: #0f172a;
        }

        /* ── ANIMATED MESH GRADIENT BACKGROUND ── */
        .mesh-bg {
            position: fixed;
            inset: 0;
            z-index: -2;
            background: #f8fafc;
            overflow: hidden;
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.7;
            animation: float 20s infinite alternate cubic-bezier(0.45, 0.05, 0.55, 0.95);
        }

        /* Tema Lulus (Blue & Indigo Premium) */
        .theme-lulus .orb-1 {
            width: 50vw;
            height: 50vw;
            background: #3b82f6;
            top: -10%;
            left: -10%;
            animation-delay: 0s;
        }

        .theme-lulus .orb-2 {
            width: 45vw;
            height: 45vw;
            background: #6366f1;
            bottom: -20%;
            right: -10%;
            animation-delay: -5s;
        }

        .theme-lulus .orb-3 {
            width: 40vw;
            height: 40vw;
            background: #38bdf8;
            top: 30%;
            left: 30%;
            animation-delay: -10s;
        }

        /* Tema Ditolak (Merah/Oranye) */
        .theme-tolak .orb-1 {
            width: 50vw;
            height: 50vw;
            background: #e11d48;
            top: -10%;
            left: -10%;
            animation-delay: 0s;
        }

        .theme-tolak .orb-2 {
            width: 45vw;
            height: 45vw;
            background: #ea580c;
            bottom: -20%;
            right: -10%;
            animation-delay: -5s;
        }

        .theme-tolak .orb-3 {
            width: 40vw;
            height: 40vw;
            background: #64748b;
            top: 30%;
            left: 30%;
            animation-delay: -10s;
        }

        @keyframes float {
            0% {
                transform: translate(0, 0) scale(1) rotate(0deg);
            }

            33% {
                transform: translate(5vw, -5vh) scale(1.1) rotate(10deg);
            }

            66% {
                transform: translate(-3vw, 5vh) scale(0.9) rotate(-5deg);
            }

            100% {
                transform: translate(2vw, -2vh) scale(1.05) rotate(5deg);
            }
        }

        /* ── GLASSMORPHISM CARD ── */
        .glass-card {
            position: relative;
            z-index: 10;
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow:
                0 25px 50px -12px rgba(0, 0, 0, 0.15),
                inset 0 0 0 1px rgba(255, 255, 255, 0.5);
            border-radius: 2.5rem;
            width: 100%;
            max-width: 480px;
            padding: 2.5rem;
            margin: 1rem;
            overflow: hidden;
        }

        .glass-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top left, rgba(255, 255, 255, 0.8), transparent 60%);
            z-index: -1;
            border-radius: 2.5rem;
        }

        /* ── ANIMATIONS ── */
        .animate-in {
            animation: slideUpFade 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        .delay-100 {
            animation-delay: 100ms;
        }

        .delay-200 {
            animation-delay: 200ms;
        }

        .delay-300 {
            animation-delay: 300ms;
        }

        .delay-400 {
            animation-delay: 400ms;
        }

        .delay-500 {
            animation-delay: 500ms;
        }

        @keyframes slideUpFade {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes softPulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        .icon-pulse {
            animation: softPulse 2.5s infinite ease-in-out;
        }
    </style>
</head>

@php
// ==========================================
// DUMMY DATA: Ubah status menjadi 'ditolak' atau 'diterima'
// ==========================================
$status_kelulusan = 'diterima';

$siswa = (object)[
'nama' => 'Andi Saputra',
'no_pendaftaran' => 'SPMB-2026-00123',
'pilihan_diterima' => 'Teknik Komputer dan Jaringan'
];

$themeClass = $status_kelulusan === 'diterima' ? 'theme-lulus' : 'theme-tolak';
@endphp

<body class="{{ $themeClass }}">

    <div class="mesh-bg">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E');"></div>
    </div>

    <div class="glass-card text-center relative flex flex-col">

        @if($status_kelulusan === 'diterima')
        <div class="animate-in delay-100 mb-6 mt-2">
            <div class="icon-pulse mx-auto w-24 h-24 bg-white/80 backdrop-blur-md rounded-full shadow-[0_8px_30px_rgb(0,0,0,0.1)] flex items-center justify-center border border-white">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center shadow-inner">
                    <i data-lucide="check" class="w-10 h-10 text-white" stroke-width="3"></i>
                </div>
            </div>
        </div>

        <div class="animate-in delay-200">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-100/50 border border-blue-200/50 text-blue-800 text-xs font-extrabold tracking-widest uppercase mb-3 backdrop-blur-sm">
                Penerimaan Resmi
            </span>
            <h1 class="text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Selamat! 🎉</h1>
            <p class="text-slate-600 font-medium mb-8">Anda dinyatakan <strong class="text-blue-600 font-bold">LULUS</strong> pada Sistem Penerimaan Murid Baru (SPMB 2026).</p>
        </div>

        <div class="bg-white/60 border border-white/80 rounded-2xl p-5 text-left mb-8 animate-in delay-300 shadow-sm backdrop-blur-sm">
            <div class="grid grid-cols-2 gap-y-4 gap-x-2">
                <div class="col-span-2 sm:col-span-1">
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mb-1">No. Registrasi</p>
                    <p class="font-bold text-slate-900 text-sm">{{ $siswa->no_pendaftaran }}</p>
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mb-1">Nama Lengkap</p>
                    <p class="font-bold text-slate-900 text-sm uppercase">{{ $siswa->nama }}</p>
                </div>
                <div class="col-span-2 pt-4 border-t border-slate-200/60 mt-1">
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mb-1">Diterima Kompetensi Keahlian</p>
                    <p class="font-bold text-blue-700 text-base flex items-center gap-2">
                        <i data-lucide="award" class="w-4 h-4"></i>
                        {{ $siswa->pilihan_diterima }}
                    </p>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 animate-in delay-400">
            <a href="/dashboard" class="flex-1 flex items-center justify-center gap-2 px-6 py-3.5 bg-slate-900 text-white rounded-xl font-bold text-sm transition-all duration-300 hover:bg-slate-800 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-900/20 cursor-pointer">
                Masuk Dashboard
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
            <form action="/logout" method="POST" class="sm:w-1/3">
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3.5 bg-white/80 text-slate-700 rounded-xl font-bold text-sm border border-slate-200 transition-all duration-300 hover:bg-white hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-200/60 cursor-pointer">
                    Keluar
                </button>
            </form>
        </div>

        @else
        <div class="animate-in delay-100 mb-6 mt-2">
            <div class="icon-pulse mx-auto w-24 h-24 bg-white/80 backdrop-blur-md rounded-full shadow-[0_8px_30px_rgb(0,0,0,0.1)] flex items-center justify-center border border-white">
                <div class="w-20 h-20 bg-gradient-to-br from-rose-400 to-red-500 rounded-full flex items-center justify-center shadow-inner">
                    <i data-lucide="x" class="w-10 h-10 text-white" stroke-width="3"></i>
                </div>
            </div>
        </div>

        <div class="animate-in delay-200">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100/50 border border-slate-200/50 text-slate-700 text-xs font-extrabold tracking-widest uppercase mb-3 backdrop-blur-sm">
                Hasil Seleksi
            </span>
            <h1 class="text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Mohon Maaf</h1>
            <p class="text-slate-600 font-medium mb-8">Dengan berat hati kami sampaikan bahwa Anda <strong class="text-rose-600 font-bold">BELUM LULUS</strong> seleksi tahun ini.</p>
        </div>

        <div class="bg-white/40 border border-white/60 rounded-2xl p-5 text-left mb-8 animate-in delay-300 shadow-sm backdrop-blur-sm opacity-80 grayscale-[20%]">
            <div class="grid grid-cols-2 gap-y-4 gap-x-2">
                <div class="col-span-2 sm:col-span-1">
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mb-1">No. Registrasi</p>
                    <p class="font-bold text-slate-800 text-sm">{{ $siswa->no_pendaftaran }}</p>
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mb-1">Nama Lengkap</p>
                    <p class="font-bold text-slate-800 text-sm uppercase">{{ $siswa->nama }}</p>
                </div>
            </div>
        </div>

        <div class="animate-in delay-400">
            <form action="/logout" method="POST">
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3.5 bg-slate-900 text-white rounded-xl font-bold text-sm transition-all duration-300 hover:bg-slate-800 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-900/20 cursor-pointer">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    Keluar dari Sistem
                </button>
            </form>
        </div>

        @endif

        <div class="mt-8 pt-6 border-t border-slate-200/50 animate-in delay-500">
            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest opacity-80">
                SPMB - SMK Negeri 1 Rejang Lebong
            </p>
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>

</html>
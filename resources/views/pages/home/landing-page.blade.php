<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SPMB SMK Negeri 1 — Tahun Ajaran 2026/2027</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        display: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            DEFAULT: '#FF1443',
                            hover: '#D90F38',
                        },
                        foreground: '#080C1A',
                        secondary: '#6A7686',
                        muted: '#EFF2F7',
                        border: '#F3F4F3',
                        /* navy tetap untuk footer */
                        navy: {
                            950: '#020B18',
                            900: '#040F1F',
                            800: '#071526',
                        },
                        gold: {
                            300: '#FDE68A',
                            400: '#FBBF24',
                            500: '#F59E0B',
                            600: '#D97706',
                        },
                        /* cyan dialihkan ke merah Shayna agar class lama tetap jalan */
                        cyan: {
                            400: '#FF1443',
                            500: '#D90F38',
                            600: '#B50D2F',
                        },
                    },
                },
            },
        };
    </script>
    <style>
        /* ════════════════════════════════════════════════════
       SPMB SMK — Design System ala Shayna (Light Mode)
       Token warna, tipografi, komponen dari dashboardshayna
    ════════════════════════════════════════════════════ */

        :root {
            /* Token utama Shayna */
            --primary: #FF1443;
            --primary-hover: #D90F38;
            --foreground: #080C1A;
            --secondary: #6A7686;
            --muted: #EFF2F7;
            --border: #F3F4F3;
            --card-bg: #FFFFFF;
            --success: #30B22D;
            --success-light: #DCFCE7;
            --success-dark: #166534;
            --error: #ED6B60;
            --warning: #F59E0B;
            --warning-dark: #854D0E;
            --gray-50: #F9FAFB;
            --gray-100: #F1F3F6;
            --gray-200: #E5E7EB;
            --gray-500: #6A7686;

            /* Border radius ala Shayna */
            --radius-card: 24px;
            --radius-xl: 16px;
            --radius-button: 50px;
            --radius-icon: 12px;
        }

        * {
            box-sizing: border-box;
        }

        /* ── BODY — putih bersih seperti Shayna ── */
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--gray-50);
            color: var(--foreground);
            overflow-x: hidden;
        }

        /* ── SCROLLBAR ala Shayna ── */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: var(--gray-100);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 3px;
        }

        /* ── NAVBAR ── */
        #navbar {
            transition: all 0.3s ease;
        }

        #navbar.scrolled {
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        }

        /* ── HERO BG — putih dengan aksen merah tipis ── */
        .hero-bg {
            background:
                radial-gradient(ellipse 70% 50% at 50% -5%, rgba(255, 20, 67, 0.08) 0%, transparent 60%),
                radial-gradient(ellipse 40% 40% at 85% 60%, rgba(245, 158, 11, 0.06) 0%, transparent 50%),
                linear-gradient(180deg, #FFFFFF 0%, #F9FAFB 60%, #F1F3F6 100%);
        }

        /* ── GLOW ORBS — sangat halus di light mode ── */
        .glow-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
        }

        .orb-cyan {
            background: rgba(255, 20, 67, 0.06);
        }

        .orb-gold {
            background: rgba(245, 158, 11, 0.06);
        }

        /* ── GRID PATTERN — sangat tipis ── */
        .grid-pattern {
            background-image:
                linear-gradient(var(--border) 1px, transparent 1px),
                linear-gradient(90deg, var(--border) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        /* ── CARD / GLASS — style Shayna: putih, border tipis, shadow ringan ── */
        .glass {
            background: var(--card-bg);
            border: 1px solid var(--border);
            box-shadow: 0 1px 8px rgba(0, 0, 0, 0.05);
        }

        .glass-gold {
            background: var(--card-bg);
            border: 1px solid rgba(245, 158, 11, 0.25);
            box-shadow: 0 1px 8px rgba(245, 158, 11, 0.06);
        }

        /* ── GRADIENT TEXT — merah Shayna ── */
        .text-gradient-cyan {
            background: linear-gradient(135deg, #FF1443 0%, #D90F38 60%, #F59E0B 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .text-gradient-gold {
            background: linear-gradient(135deg, #F59E0B 0%, #FBBF24 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ── BUTTON PRIMARY — merah Shayna, pill rounded ── */
        .btn-primary {
            background: var(--primary);
            color: #FFFFFF;
            font-weight: 700;
            transition: all 0.25s ease;
            box-shadow: 0 4px 14px rgba(255, 20, 67, 0.30);
            border-radius: var(--radius-button);
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255, 20, 67, 0.40);
        }

        /* ── BUTTON OUTLINE — border tipis ala Shayna ── */
        .btn-outline {
            border: 1.5px solid var(--border);
            color: var(--foreground);
            transition: all 0.25s ease;
            border-radius: var(--radius-button);
            font-weight: 600;
        }

        .btn-outline:hover {
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-2px);
        }

        /* ── COUNTER ── */
        .counter {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* ── JURUSAN CARD ── */
        .jurusan-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius-card);
            transition: all 0.3s ease;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.04);
        }

        .jurusan-card:hover {
            transform: translateY(-6px);
            border-color: rgba(255, 20, 67, 0.35);
            box-shadow: 0 16px 48px rgba(255, 20, 67, 0.10);
        }

        /* ── TIMELINE ── */
        .timeline-line {
            background: linear-gradient(180deg, transparent 0%, var(--primary) 20%, var(--primary) 80%, transparent 100%);
        }

        .step-card {
            background: var(--card-bg);
            border: 1.5px solid rgba(255, 20, 67, 0.18);
            border-left: 4px solid var(--primary);
            border-radius: var(--radius-xl);
            transition: all 0.25s ease;
            box-shadow: 0 2px 12px rgba(255, 20, 67, 0.07), 0 1px 4px rgba(0, 0, 0, 0.04);
        }

        .step-card:hover {
            transform: translateX(6px);
            border-color: rgba(255, 20, 67, 0.55);
            border-left-color: var(--primary);
            box-shadow: 0 8px 28px rgba(255, 20, 67, 0.15);
        }

        /* Step 9 (glass-gold) left border kuning */
        .step-card.glass-gold {
            border: 1.5px solid rgba(245, 158, 11, 0.30);
            border-left: 4px solid #F59E0B;
            box-shadow: 0 2px 12px rgba(245, 158, 11, 0.09), 0 1px 4px rgba(0, 0, 0, 0.04);
        }

        .step-card.glass-gold:hover {
            border-color: rgba(245, 158, 11, 0.55);
            border-left-color: #F59E0B;
            box-shadow: 0 8px 28px rgba(245, 158, 11, 0.18);
        }

        /* ── JADWAL TABLE ── */
        .jadwal-row:hover {
            background: rgba(255, 20, 67, 0.04);
        }

        /* ── FASILITAS ── */
        .fasilitas-item {
            transition: all 0.4s ease;
            overflow: hidden;
        }

        .fasilitas-item:hover .fasilitas-overlay {
            opacity: 1;
        }

        .fasilitas-overlay {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        /* ── TESTIMONIAL ── */
        .testi-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius-card);
            transition: all 0.25s ease;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.04);
        }

        .testi-card:hover {
            transform: translateY(-4px);
            border-color: rgba(255, 20, 67, 0.30);
            box-shadow: 0 12px 36px rgba(255, 20, 67, 0.08);
        }

        /* ── FAQ ACCORDION ── */
        .faq-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease, padding 0.3s ease;
        }

        .faq-content.open {
            max-height: 300px;
        }

        .faq-icon {
            transition: transform 0.3s ease;
        }

        .faq-item.open .faq-icon {
            transform: rotate(45deg);
        }

        /* ── FADE IN ANIMATIONS ── */
        .fade-up {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .fade-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .fade-left {
            opacity: 0;
            transform: translateX(-40px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .fade-left.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .fade-right {
            opacity: 0;
            transform: translateX(40px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .fade-right.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .delay-100 {
            transition-delay: 0.1s;
        }

        .delay-200 {
            transition-delay: 0.2s;
        }

        .delay-300 {
            transition-delay: 0.3s;
        }

        .delay-400 {
            transition-delay: 0.4s;
        }

        .delay-500 {
            transition-delay: 0.5s;
        }

        /* ── PARALLAX ── */
        .parallax-hero {
            will-change: transform;
        }

        /* ── BADGE ala Shayna ── */
        .badge-lulus {
            background: var(--success-light);
            color: var(--success-dark);
            border: 1px solid rgba(48, 178, 45, 0.25);
        }

        .badge-cadangan {
            background: #FEF3C7;
            color: var(--warning-dark);
            border: 1px solid rgba(245, 158, 11, 0.25);
        }

        .badge-tidak {
            background: #FEE2E2;
            color: #991B1B;
            border: 1px solid rgba(237, 107, 96, 0.25);
        }

        /* ── LOADING SCREEN ── */
        #loader {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: #FFFFFF;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: opacity 0.6s ease;
        }

        .loader-bar {
            width: 200px;
            height: 3px;
            background: var(--gray-200);
            border-radius: 2px;
            overflow: hidden;
            margin-top: 16px;
        }

        .loader-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary), var(--warning));
            border-radius: 2px;
            animation: loaderAnim 1.4s ease forwards;
        }

        @keyframes loaderAnim {
            from {
                width: 0;
            }

            to {
                width: 100%;
            }
        }

        .loader-pulse {
            width: 50px;
            height: 50px;
            border: 2px solid rgba(255, 20, 67, 0.15);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* ── PARTICLES — merah halus ── */
        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 20, 67, 0.35);
            border-radius: 50%;
            animation: float linear infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(100vh) scale(0);
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                transform: translateY(-100px) scale(1);
                opacity: 0;
            }
        }

        /* ── MOBILE NAV ── */
        #mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease;
        }

        #mobile-menu.open {
            max-height: 500px;
        }

        /* ── HERO ILLUSTRATION ── */
        .hero-illustration {
            animation: floatY 4s ease-in-out infinite;
        }

        @keyframes floatY {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-16px);
            }
        }

        /* ── PROGRESS BAR ── */
        .progress-bar {
            background: linear-gradient(90deg, var(--primary), var(--primary-hover));
            animation: progressAnim 2s ease-in-out infinite;
        }

        @keyframes progressAnim {
            0% {
                width: 0%;
            }

            70% {
                width: 85%;
            }

            100% {
                width: 85%;
            }
        }

        /* ── SECTION DIVIDER ── */
        .section-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border), transparent);
        }

        /* ── SLIDER ── */
        #testi-track {
            transition: transform 0.5s ease;
        }

        /* ══════════════════════════════════════════════
       OVERRIDE TAILWIND COLOR CLASSES
       Sesuaikan semua warna dalam HTML ke palet Shayna
    ══════════════════════════════════════════════ */

        /* Background & teks dasar */
        body {
            background-color: #F9FAFB;
            color: var(--foreground);
        }

        /* text-white → hitam Shayna */
        .text-white {
            color: var(--foreground) !important;
        }

        /* Teks abu → sekunder Shayna */
        .text-slate-300 {
            color: var(--secondary) !important;
        }

        .text-slate-400 {
            color: var(--secondary) !important;
        }

        .text-slate-500 {
            color: var(--gray-500) !important;
        }

        /* Cyan accent → merah primary Shayna */
        .text-cyan-400 {
            color: var(--primary) !important;
        }

        .text-cyan-400\/70 {
            color: rgba(255, 20, 67, 0.7) !important;
        }

        .hover\:text-cyan-400:hover {
            color: var(--primary) !important;
        }

        /* Border cyan → border Shayna */
        .border-cyan-400\/20 {
            border-color: rgba(255, 20, 67, 0.15) !important;
        }

        .border-cyan-400\/30 {
            border-color: rgba(255, 20, 67, 0.22) !important;
        }

        .border-green-500\/20 {
            border-color: rgba(48, 178, 45, 0.20) !important;
        }

        .border-gold-500\/20 {
            border-color: rgba(245, 158, 11, 0.20) !important;
        }

        /* BG transparan ala Shayna */
        .bg-white\/5 {
            background: rgba(255, 20, 67, 0.04) !important;
        }

        .bg-white\/10 {
            background: rgba(255, 20, 67, 0.07) !important;
        }

        .hover\:bg-white\/5:hover {
            background: var(--muted) !important;
        }

        /* Hover nav mobile */
        .hover\:bg-white\/5:hover {
            background: var(--muted) !important;
        }

        /* Icon bg accent */
        .bg-cyan-400\/10 {
            background: rgba(255, 20, 67, 0.08) !important;
        }

        .bg-cyan-400\/20 {
            background: rgba(255, 20, 67, 0.14) !important;
        }

        .hover\:bg-cyan-400\/20:hover {
            background: rgba(255, 20, 67, 0.18) !important;
        }

        /* Navbar logo icon bg */
        .from-cyan-400 {
            --tw-gradient-from: #FF1443 !important;
        }

        .to-cyan-600 {
            --tw-gradient-to: #D90F38 !important;
        }

        /* Logo icon text (graduation cap) */
        .text-navy-950 {
            color: #FFFFFF !important;
        }

        /* Shadow cyan → merah */
        .shadow-cyan-500\/30 {
            --tw-shadow-color: rgba(255, 20, 67, 0.25) !important;
        }

        .shadow-cyan-500\/10 {
            --tw-shadow-color: rgba(255, 20, 67, 0.08) !important;
        }

        /* Navbar scrolled backdrop */
        #navbar.scrolled {
            background: rgba(255, 255, 255, 0.97);
            border-bottom: 1px solid var(--border);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        }

        /* Section bg alternating ala Shayna */
        section:nth-child(even) {
            background-color: var(--gray-50);
        }

        section:nth-child(odd) {
            background-color: #FFFFFF;
        }

        /* Footer → warna gelap ala Shayna primary */
        footer {
            background: #080C1A !important;
        }

        footer .text-white {
            color: #FFFFFF !important;
        }

        footer .text-slate-400 {
            color: #9CA3AF !important;
        }

        footer .text-slate-600 {
            color: #6B7280 !important;
        }

        footer .text-cyan-400 {
            color: var(--primary) !important;
        }

        footer .hover\:text-cyan-400:hover {
            color: var(--primary) !important;
        }

        footer .border-white\/5 {
            border-color: rgba(255, 255, 255, 0.08) !important;
        }

        footer .bg-cyan-400\/10 {
            background: rgba(255, 20, 67, 0.10) !important;
        }
    </style>
</head>

<body class="antialiased">

    <!-- ═══════════════════════════════════════
  LOADING SCREEN
═══════════════════════════════════════ -->
    <div id="loader">
        <div class="text-center">
            <div class="loader-pulse mx-auto mb-4"></div>
            <p class="font-display text-cyan-400 font-semibold tracking-widest text-sm uppercase">SPMB SMK</p>
            <p class="text-slate-500 text-xs mt-1">Memuat halaman…</p>
            <div class="loader-bar mt-4">
                <div class="loader-fill"></div>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════
  PARTICLES
═══════════════════════════════════════ -->
    <div id="particles" class="fixed inset-0 pointer-events-none z-0 overflow-hidden"></div>

    <!-- ═══════════════════════════════════════
  NAVBAR
═══════════════════════════════════════ -->
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

    <!-- ═══════════════════════════════════════
  HERO
═══════════════════════════════════════ -->
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
                        <a href="#daftar" class="btn-primary px-8 py-4 rounded-2xl font-bold text-base flex items-center gap-3">
                            <i class="fa-solid fa-rocket"></i>
                            Daftar Sekarang
                        </a>
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

    <!-- ═══════════════════════════════════════
  STATISTIK
═══════════════════════════════════════ -->
    <section id="statistik" class="py-20 relative">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-6">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="fade-up glass rounded-3xl p-8 text-center group hover:border-cyan-400/30 transition-all">
                    <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-cyan-400/10 flex items-center justify-center group-hover:bg-cyan-400/20 transition-colors">
                        <i class="fa-solid fa-layer-group text-cyan-400 text-2xl"></i>
                    </div>
                    <p class="font-display text-4xl font-extrabold text-gradient-cyan counter mb-2" data-target="9">0</p>
                    <p class="text-slate-400 font-medium">Jurusan Unggulan</p>
                </div>

                <div class="fade-up delay-100 glass rounded-3xl p-8 text-center group hover:border-gold-500/30 transition-all">
                    <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-yellow-400/10 flex items-center justify-center group-hover:bg-yellow-400/20 transition-colors">
                        <i class="fa-solid fa-users text-yellow-400 text-2xl"></i>
                    </div>
                    <p class="font-display text-4xl font-extrabold text-gradient-gold counter mb-2">1.000+</p>
                    <p class="text-slate-400 font-medium">Pelajar Aktif</p>
                </div>

                <div class="fade-up delay-200 glass rounded-3xl p-8 text-center group hover:border-green-400/30 transition-all">
                    <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-green-400/10 flex items-center justify-center group-hover:bg-green-400/20 transition-colors">
                        <i class="fa-solid fa-chart-line text-green-400 text-2xl"></i>
                    </div>
                    <p class="font-display text-4xl font-extrabold text-white mb-2">99<span class="text-green-400">%</span></p>
                    <p class="text-slate-400 font-medium">Tingkat Kelulusan</p>
                </div>

                <div class="fade-up delay-300 glass rounded-3xl p-8 text-center group hover:border-purple-400/30 transition-all">
                    <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-purple-400/10 flex items-center justify-center group-hover:bg-purple-400/20 transition-colors">
                        <i class="fa-solid fa-handshake text-purple-400 text-2xl"></i>
                    </div>
                    <p class="font-display text-4xl font-extrabold text-purple-400 counter mb-2" data-target="50">0</p>
                    <p class="text-slate-400 font-medium">Mitra Industri</p>
                </div>

            </div>
        </div>
    </section>

    <div class="section-divider mx-8 lg:mx-20"></div>

    <!-- ═══════════════════════════════════════
  TENTANG SEKOLAH
═══════════════════════════════════════ -->
    <section id="tentang" class="py-24 relative overflow-hidden">
        <div class="glow-orb orb-gold w-[400px] h-[400px] top-0 right-0 opacity-50"></div>

        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-6">
            <div class="grid lg:grid-cols-2 gap-16 items-center">

                <div class="fade-left">
                    <div class="inline-flex items-center gap-2 px-4 py-2 glass rounded-full text-xs font-semibold text-gold-400 border border-yellow-500/20 mb-6">
                        <i class="fa-solid fa-school text-yellow-400"></i> Profil Sekolah
                    </div>
                    <h2 class="font-display text-4xl lg:text-5xl font-extrabold leading-tight mb-6">
                        <span class="text-white">Mendidik Generasi</span><br>
                        <span class="text-gradient-gold">Industri 4.0</span>
                    </h2>
                    <p class="text-slate-400 text-lg leading-relaxed mb-8">
                        SMK Negeri 1 Rejang Lebong adalah sekolah kejuruan unggulan yang telah berdiri sejak 1979. Kami berkomitmen mencetak lulusan yang siap kerja, adaptif terhadap teknologi, dan berdaya saing global.
                    </p>

                    <div class="space-y-4 mb-8">
                        <div class="glass rounded-2xl p-5 flex gap-4">
                            <div class="w-10 h-10 rounded-xl bg-cyan-400/10 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-eye text-cyan-400"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-white mb-1">Visi</p>
                                <p class="text-slate-400 text-sm leading-relaxed">Menjadi SMK Unggul dan Berdaya Saing di Tingkat Global Tahun 2033</p>
                            </div>
                        </div>
                        <div class="glass rounded-2xl p-5 flex gap-4">
                            <div class="w-10 h-10 rounded-xl bg-yellow-400/10 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-bullseye text-yellow-400"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-white mb-2">Misi</p>
                                <ol class="text-slate-400 text-sm leading-relaxed list-decimal pl-4 space-y-1">
                                    <li>Mewujudkan pendidik dan tenaga kependidikan yang mampu memanfaatkan teknologi terkini dan profesional.</li>
                                    <li>Mewujudkan proses pembelajaran yang berkualitas dan terintegrasi untuk membentuk karakter siswa.</li>
                                    <li>Menumbuhkan lingkungan belajar yang kreatif dan inovatif bagi siswa.</li>
                                    <li>Mewujudkan sarana prasarana berstandar industri dan berwawasan lingkungan.</li>
                                    <li>Mengembangkan kerjasama yang luas dan bermakna dengan dunia kerja nasional dan internasional.</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: highlight cards -->
                <div class="fade-right delay-200 grid grid-cols-2 gap-4">
                    <div class="glass rounded-3xl p-6 col-span-2 flex gap-5 items-start hover:border-cyan-400/30 transition-all">
                        <div class="w-12 h-12 rounded-2xl bg-cyan-400/10 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-microchip text-cyan-400 text-xl"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-white mb-2">Berbasis Teknologi Terkini</p>
                            <p class="text-slate-400 text-sm leading-relaxed">Kurikulum terintegrasi dengan AI, IoT, Cloud Computing, dan kebutuhan industri masa kini.</p>
                        </div>
                    </div>
                    <div class="glass rounded-3xl p-6 hover:border-yellow-400/30 transition-all">
                        <i class="fa-solid fa-award text-yellow-400 text-2xl mb-3"></i>
                        <p class="font-semibold text-white text-sm mb-1">Akreditasi B</p>
                        <p class="text-slate-500 text-xs">1347/BAN-SM/SK/2021</p>
                    </div>
                    <div class="glass rounded-3xl p-6 hover:border-green-400/30 transition-all">
                        <i class="fa-solid fa-certificate text-green-400 text-2xl mb-3"></i>
                        <p class="font-semibold text-white text-sm mb-1">Sertifikasi LSP</p>
                        <p class="text-slate-500 text-xs">Sertifikat Kompetensi</p>
                    </div>
                    <div class="glass rounded-3xl p-6 hover:border-purple-400/30 transition-all">
                        <i class="fa-solid fa-briefcase text-purple-400 text-2xl mb-3"></i>
                        <p class="font-semibold text-white text-sm mb-1">Bursa Kerja</p>
                        <p class="text-slate-500 text-xs">BKK Aktif</p>
                    </div>
                    <div class="glass rounded-3xl p-6 hover:border-cyan-400/30 transition-all">
                        <i class="fa-solid fa-globe text-cyan-400 text-2xl mb-3"></i>
                        <p class="font-semibold text-white text-sm mb-1">Kelas Industri</p>
                        <p class="text-slate-500 text-xs">Program Magang Luar Negeri</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <div class="section-divider mx-8 lg:mx-20"></div>

    <!-- ═══════════════════════════════════════
  JURUSAN UNGGULAN
═══════════════════════════════════════ -->
    <section id="jurusan" class="py-24">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-6">

            <div class="text-center mb-16 fade-up">
                <div class="inline-flex items-center gap-2 px-4 py-2 glass rounded-full text-xs font-semibold text-cyan-400 border border-cyan-400/20 mb-6">
                    <i class="fa-solid fa-compass text-cyan-400"></i> Program Keahlian
                </div>
                <h2 class="font-display text-4xl lg:text-5xl font-extrabold text-white mb-4">Jurusan <span class="text-gradient-cyan">Unggulan</span></h2>
                <p class="text-slate-400 max-w-xl mx-auto">Pilih program keahlian sesuai minat dan bakat, dirancang bersama mitra industri untuk mempersiapkan karier terbaik.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach($concentrations as $item)
                @php
                // Ambil warna dari DB, jika kosong default ke cyan
                $c = $item->color ?? 'cyan';
                @endphp

                <div class="jurusan-card fade-up glass rounded-3xl p-8 group cursor-pointer border border-transparent">
                    <div class="w-14 h-14 rounded-2xl bg-{{ $c }}-400/10 flex items-center justify-center mb-5 group-hover:bg-{{ $c }}-400/20 transition-colors">
                        <i class="fa-solid {{ $item->icon }} text-{{ $c }}-400 text-2xl"></i>
                    </div>

                    <div class="flex items-start justify-between mb-3">
                        <h3 class="font-display font-bold text-white text-lg leading-tight">{!! nl2br(e($item->name)) !!}</h3>
                        <span class="text-xs px-3 py-1 rounded-full bg-{{ $c }}-400/10 text-{{ $c }}-400 border border-{{ $c }}-400/20 shrink-0 ml-2">
                            {{ $item->alias }}
                        </span>
                    </div>

                    <p class="text-slate-400 text-sm leading-relaxed mb-6">{{ $item->description }}</p>

                    <div class="flex items-center justify-between">
                        <div class="flex flex-wrap gap-2">
                            @if(is_array($item->tags))
                            @foreach($item->tags as $tag)
                            <span class="text-[11px] font-medium px-2.5 py-1 rounded-lg border border-{{ $c }}-400/20 text-{{ $c }}-400/90 bg-{{ $c }}-400/[0.02] transition-all group-hover:border-{{ $c }}-400/40">
                                # {{ $tag }}
                            </span>
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </section>
    <div class="section-divider mx-8 lg:mx-20"></div>

    <!-- ═══════════════════════════════════════
  LANGKAH PENDAFTARAN
═══════════════════════════════════════ -->
    <section id="pendaftaran" class="py-24 relative overflow-hidden">
        <div class="glow-orb orb-cyan w-[500px] h-[500px] -left-40 top-1/2 -translate-y-1/2 opacity-40"></div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-16 fade-up">
                <div class="inline-flex items-center gap-2 px-4 py-2 glass rounded-full text-xs font-semibold text-cyan-400 border border-cyan-400/20 mb-6">
                    <i class="fa-solid fa-list-check text-cyan-400"></i> Alur Pendaftaran
                </div>
                <h2 class="font-display text-4xl lg:text-5xl font-extrabold text-white mb-4">Langkah-Langkah <span class="text-gradient-cyan">Pendaftaran</span></h2>
                <p class="text-slate-400 max-w-xl mx-auto">9 langkah mudah menuju bangku SMK impianmu. Ikuti proses ini dengan seksama.</p>
            </div>

            <!-- Steps -->
            <div class="space-y-6">

                <!-- Step 1 -->
                <div class="step-card fade-up glass rounded-3xl p-6 relative overflow-hidden group border border-transparent">
                    <span class="absolute right-2 bottom-0 font-display font-extrabold text-slate-100 text-[6rem] leading-none pointer-events-none select-none">01</span>
                    <div class="flex gap-4 items-start relative z-10">
                        <span class="w-8 h-8 rounded-full bg-cyan-400 flex items-center justify-center text-navy-950 font-display font-extrabold text-sm shrink-0 shadow-lg shadow-cyan-500/40 mt-1">1</span>
                        <div class="flex-1">
                            <h3 class="font-display font-bold text-white mb-1">Buat Akun</h3>
                            <p class="text-slate-400 text-sm leading-relaxed">Daftarkan diri menggunakan email aktif dan buat password. Sistem akan mengirimkan link verifikasi otomatis ke email Anda.</p>
                            <div class="flex gap-2 mt-3">
                                <span class="text-xs px-3 py-1 rounded-full bg-cyan-400/10 text-cyan-400 border border-cyan-400/20">Email Aktif</span>
                                <span class="text-xs px-3 py-1 rounded-full bg-cyan-400/10 text-cyan-400 border border-cyan-400/20">Verifikasi Otomatis</span>
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-cyan-400/10 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-user-plus text-cyan-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="step-card fade-up delay-100 glass rounded-3xl p-6 relative overflow-hidden group border border-transparent">
                    <span class="absolute right-2 bottom-0 font-display font-extrabold text-slate-100 text-[6rem] leading-none pointer-events-none select-none">02</span>
                    <div class="flex gap-4 items-start relative z-10">
                        <span class="w-8 h-8 rounded-full bg-cyan-500 flex items-center justify-center text-navy-950 font-display font-extrabold text-sm shrink-0 shadow-lg shadow-cyan-500/40 mt-1">2</span>
                        <div class="flex-1">
                            <h3 class="font-display font-bold text-white mb-1">Login ke Sistem</h3>
                            <p class="text-slate-400 text-sm leading-relaxed">Masuk menggunakan email dan password yang telah terdaftar. Dashboard peserta didik langsung tersedia.</p>
                            <div class="flex gap-2 mt-3">
                                <span class="text-xs px-3 py-1 rounded-full bg-blue-400/10 text-blue-400 border border-blue-400/20">Dashboard Peserta</span>
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-blue-400/10 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-right-to-bracket text-blue-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="step-card fade-up delay-200 glass rounded-3xl p-6 relative overflow-hidden group border border-transparent">
                    <span class="absolute right-2 bottom-0 font-display font-extrabold text-slate-100 text-[6rem] leading-none pointer-events-none select-none">03</span>
                    <div class="flex gap-4 items-start relative z-10">
                        <span class="w-8 h-8 rounded-full bg-cyan-400 flex items-center justify-center text-navy-950 font-display font-extrabold text-sm shrink-0 shadow-lg shadow-cyan-500/40 mt-1">3</span>
                        <div class="flex-1">
                            <h3 class="font-display font-bold text-white mb-2">Lengkapi Biodata</h3>
                            <p class="text-slate-400 text-sm leading-relaxed mb-3">Isi formulir data diri secara lengkap dan benar.</p>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                <span class="text-xs px-3 py-1 rounded-lg bg-green-400/10 text-green-400 border border-green-400/20 text-center">Nama Lengkap</span>
                                <span class="text-xs px-3 py-1 rounded-lg bg-green-400/10 text-green-400 border border-green-400/20 text-center">NISN</span>
                                <span class="text-xs px-3 py-1 rounded-lg bg-green-400/10 text-green-400 border border-green-400/20 text-center">TTL</span>
                                <span class="text-xs px-3 py-1 rounded-lg bg-green-400/10 text-green-400 border border-green-400/20 text-center">Alamat</span>
                                <span class="text-xs px-3 py-1 rounded-lg bg-green-400/10 text-green-400 border border-green-400/20 text-center">Asal Sekolah</span>
                                <span class="text-xs px-3 py-1 rounded-lg bg-green-400/10 text-green-400 border border-green-400/20 text-center">Data Ortu</span>
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-green-400/10 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-id-card text-green-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="step-card fade-up glass rounded-3xl p-6 relative overflow-hidden group border border-transparent">
                    <span class="absolute right-2 bottom-0 font-display font-extrabold text-slate-100 text-[6rem] leading-none pointer-events-none select-none">04</span>
                    <div class="flex gap-4 items-start relative z-10">
                        <span class="w-8 h-8 rounded-full bg-cyan-400 flex items-center justify-center text-navy-950 font-display font-extrabold text-sm shrink-0 shadow-lg shadow-cyan-500/40 mt-1">4</span>
                        <div class="flex-1">
                            <h3 class="font-display font-bold text-white mb-1">Pilih Jurusan</h3>
                            <p class="text-slate-400 text-sm leading-relaxed">Pilih jurusan sesuai minat dan kemampuan. Informasi kuota dan detail jurusan tersedia untuk membantu keputusanmu.</p>
                            <div class="flex gap-2 mt-3">
                                <span class="text-xs px-3 py-1 rounded-full bg-purple-400/10 text-purple-400 border border-purple-400/20">Info Kuota</span>
                                <span class="text-xs px-3 py-1 rounded-full bg-purple-400/10 text-purple-400 border border-purple-400/20">5 Pilihan</span>
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-purple-400/10 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-compass-drafting text-purple-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Step 5 -->
                <div class="step-card fade-up delay-100 glass rounded-3xl p-6 relative overflow-hidden group border border-transparent">
                    <span class="absolute right-2 bottom-0 font-display font-extrabold text-slate-100 text-[6rem] leading-none pointer-events-none select-none">05</span>
                    <div class="flex gap-4 items-start relative z-10">
                        <span class="w-8 h-8 rounded-full bg-cyan-400 flex items-center justify-center text-navy-950 font-display font-extrabold text-sm shrink-0 shadow-lg shadow-cyan-500/40 mt-1">5</span>
                        <div class="flex-1">
                            <h3 class="font-display font-bold text-white mb-2">Upload Berkas</h3>
                            <p class="text-slate-400 text-sm leading-relaxed mb-4">Unggah dokumen pendaftaran yang diperlukan dalam format PDF atau JPG.</p>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 mb-4">
                                <span class="text-xs px-3 py-1 rounded-lg bg-cyan-400/10 text-cyan-400 border border-cyan-400/20 text-center">Pas Foto</span>
                                <span class="text-xs px-3 py-1 rounded-lg bg-cyan-400/10 text-cyan-400 border border-cyan-400/20 text-center">Kartu Keluarga</span>
                                <span class="text-xs px-3 py-1 rounded-lg bg-cyan-400/10 text-cyan-400 border border-cyan-400/20 text-center">Ijazah / SKL</span>
                                <span class="text-xs px-3 py-1 rounded-lg bg-cyan-400/10 text-cyan-400 border border-cyan-400/20 text-center">Raport</span>
                                <span class="text-xs px-3 py-1 rounded-lg bg-slate-700/50 text-slate-400 border border-slate-600/20 text-center">Sertifikat*</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="flex-1 h-2 rounded-full bg-white/10 overflow-hidden">
                                    <div class="progress-bar h-full rounded-full"></div>
                                </div>
                                <span class="text-xs text-cyan-400 font-semibold shrink-0">85%</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">*Sertifikat pendukung bersifat opsional</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-cyan-400/10 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-cloud-upload-alt text-cyan-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Step 6 -->
                <div class="step-card fade-up delay-200 glass rounded-3xl p-6 relative overflow-hidden group border border-transparent">
                    <span class="absolute right-2 bottom-0 font-display font-extrabold text-slate-100 text-[6rem] leading-none pointer-events-none select-none">06</span>
                    <div class="flex gap-4 items-start relative z-10">
                        <span class="w-8 h-8 rounded-full bg-cyan-400 flex items-center justify-center text-navy-950 font-display font-extrabold text-sm shrink-0 shadow-lg shadow-cyan-500/40 mt-1">6</span>
                        <div class="flex-1">
                            <h3 class="font-display font-bold text-white mb-2">Verifikasi Data</h3>
                            <p class="text-slate-400 text-sm leading-relaxed mb-3">Panitia melakukan pengecekan data dan berkas yang diunggah. Status pendaftaran dapat dipantau real-time.</p>
                            <div class="flex flex-wrap gap-2">
                                <span class="text-xs px-3 py-1.5 rounded-full bg-yellow-400/10 text-yellow-400 border border-yellow-400/20 flex items-center gap-1"><i class="fa-solid fa-clock text-xs"></i> Menunggu Verifikasi</span>
                                <span class="text-xs px-3 py-1.5 rounded-full bg-green-400/10 text-green-400 border border-green-400/20 flex items-center gap-1"><i class="fa-solid fa-check text-xs"></i> Diverifikasi</span>
                                <span class="text-xs px-3 py-1.5 rounded-full bg-red-400/10 text-red-400 border border-red-400/20 flex items-center gap-1"><i class="fa-solid fa-exclamation text-xs"></i> Perlu Perbaikan</span>
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-yellow-400/10 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-shield-check text-yellow-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Step 7 -->
                <div class="step-card fade-up glass rounded-3xl p-6 relative overflow-hidden group border border-transparent">
                    <span class="absolute right-2 bottom-0 font-display font-extrabold text-slate-100 text-[6rem] leading-none pointer-events-none select-none">07</span>
                    <div class="flex gap-4 items-start relative z-10">
                        <span class="w-8 h-8 rounded-full bg-cyan-400 flex items-center justify-center text-navy-950 font-display font-extrabold text-sm shrink-0 shadow-lg shadow-cyan-500/40 mt-1">7</span>
                        <div class="flex-1">
                            <h3 class="font-display font-bold text-white mb-1">Seleksi</h3>
                            <p class="text-slate-400 text-sm leading-relaxed mb-3">Peserta mengikuti proses seleksi berupa tes akademik, wawancara, atau seleksi administrasi sesuai jurusan.</p>
                            <div class="p-3 rounded-xl bg-orange-400/5 border border-orange-400/20">
                                <p class="text-orange-400 text-xs font-semibold"><i class="fa-solid fa-calendar mr-2"></i>Jadwal Seleksi: 14–18 Juli 2026</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-orange-400/10 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-pen-to-square text-orange-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Step 8 -->
                <div class="step-card fade-up delay-100 glass rounded-3xl p-6 relative overflow-hidden group border border-transparent">
                    <span class="absolute right-2 bottom-0 font-display font-extrabold text-slate-100 text-[6rem] leading-none pointer-events-none select-none">08</span>
                    <div class="flex gap-4 items-start relative z-10">
                        <span class="w-8 h-8 rounded-full bg-cyan-400 flex items-center justify-center text-navy-950 font-display font-extrabold text-sm shrink-0 shadow-lg shadow-cyan-500/40 mt-1">8</span>
                        <div class="flex-1">
                            <h3 class="font-display font-bold text-white mb-2">Pengumuman</h3>
                            <p class="text-slate-400 text-sm leading-relaxed mb-3">Hasil seleksi dapat dilihat langsung melalui dashboard peserta. Notifikasi dikirimkan via email.</p>
                            <div class="flex flex-wrap gap-2">
                                <span class="badge-lulus text-xs px-3 py-1.5 rounded-full font-semibold flex items-center gap-1"><i class="fa-solid fa-circle-check text-xs"></i> Lulus</span>
                                <span class="badge-cadangan text-xs px-3 py-1.5 rounded-full font-semibold flex items-center gap-1"><i class="fa-solid fa-circle-minus text-xs"></i> Cadangan</span>
                                <span class="badge-tidak text-xs px-3 py-1.5 rounded-full font-semibold flex items-center gap-1"><i class="fa-solid fa-circle-xmark text-xs"></i> Tidak Lulus</span>
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-green-400/10 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-bullhorn text-green-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Step 9 -->
                <div class="step-card fade-up delay-200 glass-gold rounded-3xl p-6 relative overflow-hidden group border border-yellow-500/20">
                    <span class="absolute right-2 bottom-0 font-display font-extrabold text-yellow-200/40 text-[6rem] leading-none pointer-events-none select-none">09</span>
                    <div class="flex gap-4 items-start relative z-10">
                        <span class="w-8 h-8 rounded-full bg-yellow-400 flex items-center justify-center text-navy-950 font-display font-extrabold text-sm shrink-0 shadow-lg shadow-yellow-500/40 mt-1">9</span>
                        <div class="flex-1">
                            <h3 class="font-display font-bold text-white mb-1">Daftar Ulang 🎉</h3>
                            <p class="text-slate-400 text-sm leading-relaxed">Peserta yang dinyatakan lulus wajib melakukan daftar ulang dengan mengunggah bukti pembayaran atau konfirmasi kehadiran.</p>
                            <div class="flex gap-2 mt-3">
                                <span class="text-xs px-3 py-1 rounded-full bg-yellow-400/10 text-yellow-400 border border-yellow-400/20">Bukti Pembayaran</span>
                                <span class="text-xs px-3 py-1 rounded-full bg-yellow-400/10 text-yellow-400 border border-yellow-400/20">Konfirmasi Hadir</span>
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-yellow-400/10 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-flag-checkered text-yellow-400"></i>
                        </div>
                    </div>
                </div>

            </div>

            <!-- CTA below steps -->
            <div class="text-center mt-12 fade-up">
                <a id="daftar" href="#" class="btn-primary inline-flex items-center gap-3 px-10 py-4 rounded-2xl font-bold text-lg">
                    <i class="fa-solid fa-rocket"></i>
                    Mulai Pendaftaran Sekarang
                </a>
                <p class="text-slate-500 text-sm mt-4">Gratis biaya pendaftaran · Proses 100% online</p>
            </div>
        </div>
    </section>

    <div class="section-divider mx-8 lg:mx-20"></div>

    <!-- ═══════════════════════════════════════
  JADWAL PENDAFTARAN
═══════════════════════════════════════ -->
    <section id="jadwal" class="py-24">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-16 fade-up">
                <div class="inline-flex items-center gap-2 px-4 py-2 glass rounded-full text-xs font-semibold text-gold-400 border border-yellow-500/20 mb-6">
                    <i class="fa-solid fa-calendar-days text-yellow-400"></i> Jadwal Kegiatan
                </div>
                <h2 class="font-display text-4xl lg:text-5xl font-extrabold text-white mb-4">Jadwal <span class="text-gradient-gold">Pendaftaran</span></h2>
                <p class="text-slate-400">Catat tanggal-tanggal penting berikut agar tidak melewatkan proses pendaftaran.</p>
            </div>

            <div class="glass rounded-3xl overflow-hidden fade-up">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/10">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-cyan-400 uppercase tracking-wider">Kegiatan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-cyan-400 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-cyan-400 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($spmbSteps as $step)
                        @php
                        $c = $step->color ?? 'cyan';
                        $currentDate = now();

                        // Logika penentuan status otomatis berdasarkan range tanggal
                        $isOpen = false;
                        $isPast = false;

                        if ($step->start_date && $step->end_date) {
                        $isOpen = $currentDate->between($step->start_date, $step->end_date);
                        $isPast = $currentDate->greaterThan($step->end_date);
                        }
                        @endphp

                        <tr class="jadwal-row transition-colors cursor-default">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-{{ $c }}-400/10 flex items-center justify-center shrink-0">
                                        <i class="fa-solid {{ $step->icon ?? 'fa-circle-dot' }} text-{{ $c }}-400 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-white text-sm">{{ $step->title }}</p>
                                        <p class="text-slate-500 text-xs">{{ $step->description }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-slate-300 text-sm text-right">{{ $step->period_text }}</td>
                            <td class="px-6 py-5">
                                @if($isOpen)
                                <span class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-full bg-green-400/10 text-green-400 border border-green-400/20 font-semibold">
                                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>Berlangsung
                                </span>
                                @elseif($isPast)
                                <span class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-full bg-red-400/10 text-red-400 border border-red-400/20 font-semibold">
                                    Selesai
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-full bg-slate-100/50 text-slate-400 border border-slate-600/30 font-semibold">
                                    Menunggu
                                </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </section>

    <div class="section-divider mx-8 lg:mx-20"></div>

    <!-- ═══════════════════════════════════════
  FASILITAS
═══════════════════════════════════════ -->
    <section id="fasilitas" class="py-24 relative overflow-hidden">
        <div class="glow-orb orb-gold w-[500px] h-[500px] right-0 top-1/3 opacity-30"></div>

        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-6">

            <div class="text-center mb-16 fade-up">
                <div class="inline-flex items-center gap-2 px-4 py-2 glass rounded-full text-xs font-semibold text-gold-400 border border-yellow-500/20 mb-6">
                    <i class="fa-solid fa-building-columns text-yellow-400"></i> Fasilitas
                </div>
                <h2 class="font-display text-4xl lg:text-5xl font-extrabold text-white mb-4">Fasilitas <span class="text-gradient-gold">Modern</span></h2>
                <p class="text-slate-400 max-w-xl mx-auto">Didukung infrastruktur terkini untuk mendukung proses belajar yang optimal dan menyenangkan.</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">

                <div class="fasilitas-item glass rounded-3xl overflow-hidden aspect-square group cursor-pointer relative">
                    <div class="w-full h-full bg-gradient-to-br from-cyan-400/20 to-blue-600/20 flex flex-col items-center justify-center p-5 text-center">
                        <i class="fa-solid fa-desktop text-cyan-400 text-4xl mb-3"></i>
                        <p class="font-display font-bold text-white text-base">Lab Komputer</p>
                        <p class="text-slate-400 text-xs mt-1">Smart classroom AC</p>
                    </div>
                    <div class="fasilitas-overlay absolute inset-0 bg-gradient-to-br from-cyan-600/40 to-blue-800/40 backdrop-blur-sm flex items-center justify-center">
                        <div class="text-center">
                            <i class="fa-solid fa-eye text-white text-2xl mb-1"></i>
                            <p class="text-white text-xs font-semibold">Lihat Detail</p>
                        </div>
                    </div>
                </div>

                <div class="fasilitas-item glass rounded-3xl overflow-hidden aspect-square group cursor-pointer relative">
                    <div class="w-full h-full bg-gradient-to-br from-orange-400/20 to-red-600/20 flex flex-col items-center justify-center p-5 text-center">
                        <i class="fa-solid fa-wrench text-orange-400 text-4xl mb-3"></i>
                        <p class="font-display font-bold text-white text-base">Bengkel Praktik</p>
                        <p class="text-slate-400 text-xs mt-1">Peralatan industri standar</p>
                    </div>
                    <div class="fasilitas-overlay absolute inset-0 bg-gradient-to-br from-orange-600/40 to-red-800/40 backdrop-blur-sm flex items-center justify-center">
                        <i class="fa-solid fa-eye text-white text-2xl"></i>
                    </div>
                </div>

                <div class="fasilitas-item glass rounded-3xl overflow-hidden aspect-square group cursor-pointer relative">
                    <div class="w-full h-full bg-gradient-to-br from-purple-400/20 to-pink-600/20 flex flex-col items-center justify-center p-5 text-center">
                        <i class="fa-solid fa-book text-purple-400 text-4xl mb-3"></i>
                        <p class="font-display font-bold text-white text-base">Perpustakaan</p>
                        <p class="text-slate-400 text-xs mt-1">5000+ buku & e-library</p>
                    </div>
                    <div class="fasilitas-overlay absolute inset-0 bg-gradient-to-br from-purple-600/40 to-pink-800/40 backdrop-blur-sm flex items-center justify-center">
                        <i class="fa-solid fa-eye text-white text-2xl"></i>
                    </div>
                </div>

                <div class="fasilitas-item glass rounded-3xl overflow-hidden aspect-square group cursor-pointer relative">
                    <div class="w-full h-full bg-gradient-to-br from-green-400/20 to-teal-600/20 flex flex-col items-center justify-center p-5 text-center">
                        <i class="fa-solid fa-chalkboard text-green-400 text-4xl mb-3"></i>
                        <p class="font-display font-bold text-white text-base">Ruang Kelas</p>
                        <p class="text-slate-400 text-xs mt-1">Bersih dan nyaman</p>
                    </div>
                    <div class="fasilitas-overlay absolute inset-0 bg-gradient-to-br from-green-600/40 to-teal-800/40 backdrop-blur-sm flex items-center justify-center">
                        <i class="fa-solid fa-eye text-white text-2xl"></i>
                    </div>
                </div>

                <div class="fasilitas-item glass rounded-3xl overflow-hidden aspect-square group cursor-pointer relative">
                    <div class="w-full h-full bg-gradient-to-br from-yellow-400/20 to-amber-600/20 flex flex-col items-center justify-center p-5 text-center">
                        <i class="fa-solid fa-video text-yellow-400 text-4xl mb-3"></i>
                        <p class="font-display font-bold text-white text-base">Studio Medsos</p>
                        <p class="text-slate-400 text-xs mt-1">Green screen & kamera</p>
                    </div>
                    <div class="fasilitas-overlay absolute inset-0 bg-gradient-to-br from-yellow-600/40 to-amber-800/40 backdrop-blur-sm flex items-center justify-center">
                        <i class="fa-solid fa-eye text-white text-2xl"></i>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <div class="section-divider mx-8 lg:mx-20"></div>

    <!-- ═══════════════════════════════════════
  TESTIMONI
═══════════════════════════════════════ -->
    <section id="testimoni" class="py-24 overflow-hidden">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-6">

            <div class="text-center mb-16 fade-up">
                <div class="inline-flex items-center gap-2 px-4 py-2 glass rounded-full text-xs font-semibold text-cyan-400 border border-cyan-400/20 mb-6">
                    <i class="fa-solid fa-comments text-cyan-400"></i> Testimoni
                </div>
                <h2 class="font-display text-4xl lg:text-5xl font-extrabold text-white mb-4">Kata <span class="text-gradient-cyan">Alumni</span></h2>
                <p class="text-slate-400">Mereka sudah membuktikan, sekarang giliran kamu.</p>
            </div>

            <div class="relative overflow-hidden">
                <div id="testi-track" class="flex gap-6">
                    <!-- Card 1 -->
                    <div class="testi-card glass rounded-3xl p-7 min-w-[320px] sm:min-w-[380px] border border-transparent shrink-0">
                        <div class="flex items-center gap-1 mb-4">
                            <i class="fa-solid fa-star text-yellow-400 text-sm"></i>
                            <i class="fa-solid fa-star text-yellow-400 text-sm"></i>
                            <i class="fa-solid fa-star text-yellow-400 text-sm"></i>
                            <i class="fa-solid fa-star text-yellow-400 text-sm"></i>
                            <i class="fa-solid fa-star text-yellow-400 text-sm"></i>
                        </div>
                        <p class="text-slate-300 text-sm leading-relaxed mb-6 italic">"Berkat SMK ini, saya berhasil diterima bekerja di perusahaan IT terkemuka bahkan sebelum wisuda. Kurikulumnya sangat relevan dengan dunia industri."</p>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-cyan-400 to-blue-600 flex items-center justify-center font-display font-bold text-white">RA</div>
                            <div>
                                <p class="font-semibold text-white">Rizky Aditya</p>
                                <p class="text-slate-500 text-xs">Alumni RPL · Kini Software Engineer di Gojek</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="testi-card glass rounded-3xl p-7 min-w-[320px] sm:min-w-[380px] border border-transparent shrink-0">
                        <div class="flex items-center gap-1 mb-4">
                            <i class="fa-solid fa-star text-yellow-400 text-sm"></i>
                            <i class="fa-solid fa-star text-yellow-400 text-sm"></i>
                            <i class="fa-solid fa-star text-yellow-400 text-sm"></i>
                            <i class="fa-solid fa-star text-yellow-400 text-sm"></i>
                            <i class="fa-solid fa-star text-yellow-400 text-sm"></i>
                        </div>
                        <p class="text-slate-300 text-sm leading-relaxed mb-6 italic">"Lab komputer yang lengkap dan guru yang berpengalaman membuat saya bisa bersaing di tingkat nasional. Sertifikasi kompetensi dari sini sangat diakui."</p>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-400 to-pink-600 flex items-center justify-center font-display font-bold text-white">DP</div>
                            <div>
                                <p class="font-semibold text-white">Dinda Putri</p>
                                <p class="text-slate-500 text-xs">Alumni TKJ · Network Engineer di Telkom</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="testi-card glass rounded-3xl p-7 min-w-[320px] sm:min-w-[380px] border border-transparent shrink-0">
                        <div class="flex items-center gap-1 mb-4">
                            <i class="fa-solid fa-star text-yellow-400 text-sm"></i>
                            <i class="fa-solid fa-star text-yellow-400 text-sm"></i>
                            <i class="fa-solid fa-star text-yellow-400 text-sm"></i>
                            <i class="fa-solid fa-star text-yellow-400 text-sm"></i>
                            <i class="fa-solid fa-star-half-stroke text-yellow-400 text-sm"></i>
                        </div>
                        <p class="text-slate-300 text-sm leading-relaxed mb-6 italic">"Saya mendirikan startup desain grafis sendiri setelah lulus dari jurusan Multimedia. Ilmu yang didapat di sini jadi fondasi utama bisnis saya sekarang."</p>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-yellow-400 to-orange-600 flex items-center justify-center font-display font-bold text-white">MF</div>
                            <div>
                                <p class="font-semibold text-white">Muhammad Farhan</p>
                                <p class="text-slate-500 text-xs">Alumni MM · Founder Studio Kreatif</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4 -->
                    <div class="testi-card glass rounded-3xl p-7 min-w-[320px] sm:min-w-[380px] border border-transparent shrink-0">
                        <div class="flex items-center gap-1 mb-4">
                            <i class="fa-solid fa-star text-yellow-400 text-sm"></i>
                            <i class="fa-solid fa-star text-yellow-400 text-sm"></i>
                            <i class="fa-solid fa-star text-yellow-400 text-sm"></i>
                            <i class="fa-solid fa-star text-yellow-400 text-sm"></i>
                            <i class="fa-solid fa-star text-yellow-400 text-sm"></i>
                        </div>
                        <p class="text-slate-300 text-sm leading-relaxed mb-6 italic">"Program magang bersertifikat yang difasilitasi sekolah membuka jalan saya ke karier yang saya impikan. Sangat merekomendasikan SMK ini untuk calon siswa baru!"</p>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-green-400 to-teal-600 flex items-center justify-center font-display font-bold text-white">SR</div>
                            <div>
                                <p class="font-semibold text-white">Sari Rahayu</p>
                                <p class="text-slate-500 text-xs">Alumni AKL · Staff Keuangan Bank BRI</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slider controls -->
            <div class="flex items-center justify-center gap-4 mt-8">
                <button onclick="slideTesti(-1)" class="w-11 h-11 rounded-full glass border border-white/10 flex items-center justify-center hover:border-cyan-400/40 transition-all">
                    <i class="fa-solid fa-chevron-left text-slate-400 text-sm"></i>
                </button>
                <div class="flex gap-2" id="testi-dots">
                    <span class="w-2 h-2 rounded-full bg-cyan-400 cursor-pointer" onclick="goTesti(0)"></span>
                    <span class="w-2 h-2 rounded-full bg-slate-600 cursor-pointer" onclick="goTesti(1)"></span>
                    <span class="w-2 h-2 rounded-full bg-slate-600 cursor-pointer" onclick="goTesti(2)"></span>
                    <span class="w-2 h-2 rounded-full bg-slate-600 cursor-pointer" onclick="goTesti(3)"></span>
                </div>
                <button onclick="slideTesti(1)" class="w-11 h-11 rounded-full glass border border-white/10 flex items-center justify-center hover:border-cyan-400/40 transition-all">
                    <i class="fa-solid fa-chevron-right text-slate-400 text-sm"></i>
                </button>
            </div>
        </div>
    </section>

    <div class="section-divider mx-8 lg:mx-20"></div>

    <!-- ═══════════════════════════════════════
  FAQ
═══════════════════════════════════════ -->
    <section id="faq" class="py-24">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-16 fade-up">
                <div class="inline-flex items-center gap-2 px-4 py-2 glass rounded-full text-xs font-semibold text-cyan-400 border border-cyan-400/20 mb-6">
                    <i class="fa-solid fa-circle-question text-cyan-400"></i> Bantuan
                </div>
                <h2 class="font-display text-4xl lg:text-5xl font-extrabold text-white mb-4">Pertanyaan <span class="text-gradient-cyan">Umum</span></h2>
                <p class="text-slate-400">Temukan jawaban dari pertanyaan yang paling sering ditanyakan.</p>
            </div>

            <div class="space-y-3 fade-up">

                @forelse($faqs as $faq)
                <div class="faq-item glass rounded-2xl overflow-hidden">
                    <button onclick="toggleFaq(this)" class="w-full flex items-center justify-between px-6 py-5 text-left group">
                        <span class="font-semibold text-white group-hover:text-cyan-400 transition-colors">
                            {{ $faq->question }}
                        </span>
                        <i class="fa-solid fa-plus faq-icon text-cyan-400 shrink-0 ml-4"></i>
                    </button>
                    <div class="faq-content px-6">
                        <div class="text-slate-400 text-sm leading-relaxed pb-5">
                            {!! $faq->answer !!}
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 glass rounded-2xl">
                    <p class="text-slate-500 text-sm">Belum ada pertanyaan untuk kategori pendaftaran saat ini.</p>
                </div>
                @endforelse

            </div>

        </div>
    </section>

    <!-- ═══════════════════════════════════════
  FOOTER
═══════════════════════════════════════ -->
    @include ('components.layout.user-footbar')

    <!-- ─── SCRIPTS ─── -->
    <script>
        // ── LOADER ──
        window.addEventListener('load', () => {
            setTimeout(() => {
                const loader = document.getElementById('loader');
                loader.style.opacity = '0';
                setTimeout(() => loader.style.display = 'none', 600);
            }, 1500);
        });

        // ── PARTICLES ──
        function createParticles() {
            const container = document.getElementById('particles');
            for (let i = 0; i < 25; i++) {
                const p = document.createElement('div');
                p.classList.add('particle');
                p.style.left = Math.random() * 100 + 'vw';
                p.style.animationDuration = (8 + Math.random() * 15) + 's';
                p.style.animationDelay = Math.random() * 10 + 's';
                p.style.width = p.style.height = (2 + Math.random() * 4) + 'px';
                p.style.opacity = (0.2 + Math.random() * 0.5).toString();
                container.appendChild(p);
            }
        }
        createParticles();

        // ── NAVBAR SCROLL ──
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) navbar.classList.add('scrolled');
            else navbar.classList.remove('scrolled');
        });

        // ── MOBILE MENU ──
        function toggleMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('open');
        }

        function closeMenu() {
            document.getElementById('mobile-menu').classList.remove('open');
        }

        // ── DARK MODE ──
        function toggleDark() {
            document.documentElement.classList.toggle('dark');
            const icon = document.getElementById('dark-icon');
            icon.classList.toggle('fa-moon');
            icon.classList.toggle('fa-sun');
        }

        // ── INTERSECTION OBSERVER (fade in) ──
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    // trigger counter if inside viewport
                    const counters = e.target.querySelectorAll('.counter[data-target]');
                    counters.forEach(animateCounter);
                }
            });
        }, {
            threshold: 0.15
        });

        document.querySelectorAll('.fade-up, .fade-left, .fade-right').forEach(el => observer.observe(el));

        // ── COUNTER ANIMATION ──
        function animateCounter(el) {
            if (el.dataset.animated) return;
            el.dataset.animated = '1';
            const target = parseInt(el.dataset.target);
            const duration = 1800;
            const step = target / (duration / 16);
            let current = 0;
            const timer = setInterval(() => {
                current = Math.min(current + step, target);
                el.textContent = Math.floor(current).toLocaleString('id-ID');
                if (current >= target) clearInterval(timer);
            }, 16);
        }

        // also trigger counters in sections that may already be visible
        document.querySelectorAll('.counter[data-target]').forEach(el => {
            const rect = el.getBoundingClientRect();
            if (rect.top < window.innerHeight) animateCounter(el);
        });

        // ── FAQ ACCORDION ──
        function toggleFaq(btn) {
            const item = btn.closest('.faq-item');
            const content = item.querySelector('.faq-content');
            const isOpen = item.classList.contains('open');

            // close all
            document.querySelectorAll('.faq-item').forEach(i => {
                i.classList.remove('open');
                i.querySelector('.faq-content').classList.remove('open');
            });

            if (!isOpen) {
                item.classList.add('open');
                content.classList.add('open');
            }
        }

        // ── TESTIMONIAL SLIDER ──
        let testiIndex = 0;
        const cardWidth = () => {
            const track = document.getElementById('testi-track');
            const card = track.querySelector('.testi-card');
            return card ? card.offsetWidth + 24 : 400; // 24 = gap
        };

        function updateTesti() {
            const track = document.getElementById('testi-track');
            const dots = document.querySelectorAll('#testi-dots span');
            track.style.transform = `translateX(-${testiIndex * cardWidth()}px)`;
            dots.forEach((d, i) => {
                d.className = i === testiIndex ? 'w-2 h-2 rounded-full bg-red-500 cursor-pointer' : 'w-2 h-2 rounded-full bg-gray-300 cursor-pointer';
            });
        }

        function slideTesti(dir) {
            const total = document.querySelectorAll('.testi-card').length;
            testiIndex = (testiIndex + dir + total) % total;
            updateTesti();
        }

        function goTesti(i) {
            testiIndex = i;
            updateTesti();
        }

        // auto slide
        setInterval(() => slideTesti(1), 5000);

        // ── PARALLAX HERO ──
        window.addEventListener('scroll', () => {
            const el = document.querySelector('.parallax-hero');
            if (el) el.style.transform = `translateY(${window.scrollY * 0.08}px)`;
        });

        // ── SMOOTH SCROLL ──
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                const target = document.querySelector(a.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>

</body>

</html>
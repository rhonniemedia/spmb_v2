<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SPMB - @yield('title')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/home.css') }}" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
</head>

<body class="antialiased">

    <!-- LOADING SCREEN -->
    <div id="loader">
        <div class="text-center">
            <div class="loader-pulse mx-auto mb-4"></div>
            <p class="font-display text-cyan-400 font-semibold tracking-widest text-sm">SPMB - SMK Negeri 1 Rejang Lebong</p>
            <p class="text-slate-500 text-xs mt-1">Memuat halaman…</p>
            <div class="loader-bar mt-4">
                <div class="loader-fill"></div>
            </div>
        </div>
    </div>

    <!-- CONTENT -->
    @yield('content')

    <!-- FOOTER -->
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
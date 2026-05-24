<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SPMB - Akun Berhasil Diaktivasi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen font-['Plus_Jakarta_Sans',sans-serif] antialiased relative overflow-x-hidden bg-[linear-gradient(180deg,#FFFFFF_0%,#F9FAFB_60%,#F1F3F6_100%)] before:absolute before:inset-0 before:z-0 before:bg-[linear-gradient(#F3F4F3_1px,transparent_1px),linear-gradient(90deg,#F3F4F3_1px,transparent_1px)] before:bg-[size:60px_60px] before:content-['']">

    {{-- Background Orbs --}}
    <div class="absolute -top-40 -left-40 z-0 h-[600px] w-[600px] rounded-full bg-[#FF1443]/[0.07] blur-[80px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 z-0 h-[400px] w-[400px] rounded-full bg-[#F59E0B]/[0.06] blur-[80px] pointer-events-none"></div>
    <div class="absolute bottom-1/3 left-1/2 z-0 h-[300px] w-[300px] rounded-full bg-[#FF1443]/[0.07] blur-[80px] pointer-events-none"></div>

    {{-- Minimal navbar --}}
    <nav class="fixed top-0 inset-x-0 z-50 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#FF1443] to-[#D90F38] flex items-center justify-center shadow-[0_4px_14px_rgba(255,20,67,0.30)]">
                    <i class="fa-solid fa-graduation-cap text-white text-sm"></i>
                </div>
                <div>
                    <p class="font-bold text-[#080C1A] leading-tight text-sm">SMK Negeri 1</p>
                    <p class="text-[10px] text-[#FF1443]/70 leading-tight font-medium tracking-wide">Rejang Lebong</p>
                </div>
            </a>
            <a href="{{ route('home') }}" class="text-[#6A7686] font-medium transition-all duration-200 rounded-full border border-[#F3F4F3] hover:text-[#FF1443] hover:border-[#FF1443]/30 hover:bg-[#FF1443]/[0.03] text-xs px-4 py-2 flex items-center gap-2">
                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                Kembali ke Beranda
            </a>
        </div>
    </nav>

    {{-- Main --}}
    <main class="min-h-screen flex items-center justify-center px-4 pt-20 pb-12 relative z-10">
        <div class="w-full max-w-md">

            {{-- Card --}}
            <div id="card-main" class="bg-white border border-[#F3F4F3] rounded-[24px] shadow-[0_4px_24px_rgba(0,0,0,0.06),0_1px_4px_rgba(0,0,0,0.03)] p-8 opacity-0 translate-y-5 transition-all duration-600 ease-out">

                {{-- Icon --}}
                <div class="flex justify-center mb-6">
                    <div class="w-20 h-20 flex items-center justify-center rounded-full bg-gradient-to-br from-[#FF1443]/[0.08] to-[#FF1443]/[0.12] border border-[#FF1443]/[0.18] relative [animation:floatY_4s_ease-in-out_infinite]">
                        <i class="fa-solid fa-circle-check text-3xl text-[#FF1443]"></i>
                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-check text-white text-[8px] font-black"></i>
                        </span>
                    </div>
                </div>

                {{-- Heading --}}
                <div class="text-center mb-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-green-200 shadow-[0_1px_8px_rgba(0,0,0,0.05)] rounded-full text-[10px] font-semibold text-green-600 mb-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-[pulse_2s_ease-in-out_infinite]"></span>
                        Verifikasi Berhasil
                    </div>
                    <h1 class="font-black text-2xl text-[#080C1A] mb-2">
                        Akun Anda Telah Diaktivasi!
                    </h1>
                    <p class="text-[#6A7686] text-sm leading-relaxed">
                        Selamat! Alamat email Anda sudah terverifikasi. Silakan masuk untuk melanjutkan proses pendaftaran.
                    </p>
                </div>

                {{-- Divider --}}
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex-1 h-px bg-[#F3F4F3]"></div>
                    <span class="text-[11px] text-[#6A7686] font-medium">Langkah selanjutnya</span>
                    <div class="flex-1 h-px bg-[#F3F4F3]"></div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col gap-3">
                    <a href="{{ route('login') }}"
                        class="bg-[#FF1443] text-white font-bold rounded-full shadow-[0_4px_14px_rgba(255,20,67,0.30)] transition-all duration-250 ease-out hover:-translate-y-0.5 hover:bg-[#D90F38] hover:shadow-[0_8px_24px_rgba(255,20,67,0.40)] w-full py-3 px-6 text-sm flex items-center justify-center gap-2">
                        <i class="fa-solid fa-right-to-bracket text-xs"></i>
                        Masuk Sekarang
                    </a>

                    <a href="{{ route('home') }}"
                        class="text-[#6A7686] font-medium transition-all duration-200 rounded-full border border-[#F3F4F3] hover:text-[#FF1443] hover:border-[#FF1443]/30 hover:bg-[#FF1443]/[0.03] w-full py-2.5 px-6 text-sm flex items-center justify-center gap-2">
                        <i class="fa-solid fa-house text-xs"></i>
                        Kembali ke Beranda
                    </a>
                </div>

            </div>

            {{-- Tips --}}
            <div id="tips-box" class="mt-4 px-2 opacity-0 translate-y-5 transition-all duration-600 ease-out delay-100">
                <div class="flex items-start gap-3 px-4 py-3 rounded-xl bg-[#F59E0B]/[0.05] border border-[#F59E0B]/[0.15]">
                    <i class="fa-solid fa-lightbulb text-[#F59E0B] text-sm mt-0.5 shrink-0"></i>
                    <p class="text-[#6A7686] text-xs leading-relaxed">
                        <span class="font-semibold text-[#080C1A]">Akun sudah aktif.</span>
                        Anda dapat langsung masuk menggunakan email dan password yang telah didaftarkan.
                    </p>
                </div>
            </div>

            {{-- Footer note --}}
            <p id="footer-text" class="text-center text-[11px] text-[#6A7686] mt-6 opacity-0 translate-y-5 transition-all duration-600 ease-out delay-200">
                Gratis biaya pendaftaran · Proses 100% online
            </p>

        </div>
    </main>

    <style>
        @keyframes floatY {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                document.getElementById('card-main').classList.remove('opacity-0', 'translate-y-5');
            }, 80);
            setTimeout(() => {
                document.getElementById('tips-box').classList.remove('opacity-0', 'translate-y-5');
            }, 160);
            setTimeout(() => {
                document.getElementById('footer-text').classList.remove('opacity-0', 'translate-y-5');
            }, 240);
        });
    </script>

</body>

</html>
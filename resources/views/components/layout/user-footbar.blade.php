<footer class="relative overflow-hidden no-print" style="background: linear-gradient(180deg, #071526 0%, #020B18 100%);">
    <div class="absolute rounded-full pointer-events-none opacity-20"
        style="width:400px; height:400px; left:0; bottom:0; background:rgba(255,20,67,0.06); filter:blur(80px);">
    </div>

    <div class="max-w-[1400px] mx-auto px-6 py-14 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 mb-10">

            <div>
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-primary-hover flex items-center justify-center shadow-lg shadow-primary/30">
                        <i class="fa-solid fa-graduation-cap text-white"></i>
                    </div>
                    <div>
                        <p class="font-bold text-white text-sm">SMK Negeri 1</p>
                        <p class="text-xs text-primary/70">Rejang Lebong</p>
                    </div>
                </div>
                <p class="text-slate-400 text-sm leading-relaxed mb-6">
                    Mencetak generasi kompeten dan berkarakter untuk menghadapi tantangan industri masa depan.
                </p>
                <div class="flex gap-3">
                    @foreach(['instagram', 'facebook', 'youtube', 'tiktok'] as $social)
                    <a href="#" class="w-9 h-9 rounded-xl flex items-center justify-center border border-white/10 bg-white/5 hover:border-primary/40 hover:bg-primary/10 transition-all group">
                        <i class="fa-brands fa-{{ $social }} text-slate-400 group-hover:text-primary text-sm"></i>
                    </a>
                    @endforeach
                </div>
            </div>

            <div>
                <h4 class="font-bold text-white mb-6 flex items-center gap-2">
                    <span class="w-1.5 h-4 bg-primary rounded-full"></span> Program Keahlian
                </h4>
                <ul class="space-y-3">
                    @php
                    $jurusan = ['Rekayasa Perangkat Lunak', 'Teknik Komputer & Jaringan', 'Multimedia / DKV', 'Akuntansi & Keuangan', 'Teknik Kendaraan Ringan'];
                    @endphp
                    @foreach($jurusan as $j)
                    <li>
                        <a href="#" class="text-slate-400 text-sm hover:text-primary transition-colors flex items-center gap-2">
                            <i class="fa-solid fa-circle text-[4px] text-primary/40"></i>
                            {{ $j }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-white mb-6 flex items-center gap-2">
                    <span class="w-1.5 h-4 bg-primary rounded-full"></span> Hubungi Kami
                </h4>
                <ul class="space-y-4">
                    <li class="flex gap-3">
                        <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center shrink-0 mt-0.5 text-primary">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <p class="text-slate-400 text-sm leading-relaxed">
                            Jl. Kapten A. Rivai No. 47, Palembang, Sumatera Selatan 30129
                        </p>
                    </li>
                    <li class="flex gap-3 items-center">
                        <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center shrink-0 text-primary">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <a href="tel:+62711123456" class="text-slate-400 text-sm hover:text-primary transition-colors">(0711) 123-456</a>
                    </li>
                    <li class="flex gap-3 items-center">
                        <div class="w-8 h-8 rounded-lg bg-green-500/10 flex items-center justify-center shrink-0 text-green-400">
                            <i class="fa-brands fa-whatsapp"></i>
                        </div>
                        <a href="#" class="text-slate-400 text-sm hover:text-green-400 transition-colors">+62 812-3456-7890</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="pt-8 border-t border-white/5 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-slate-500 text-[11px]">
                © 2026 SMK Negeri 1 Kota Palembang. Dikembangkan untuk Portal SPMB.
            </p>
            <div class="flex gap-6">
                <a href="#" class="text-slate-500 text-[11px] hover:text-slate-300 transition-colors">Kebijakan Privasi</a>
                <a href="#" class="text-slate-500 text-[11px] hover:text-slate-300 transition-colors">Syarat & Ketentuan</a>
            </div>
        </div>
    </div>
</footer>
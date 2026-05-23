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
                    @include('pages.user.partials.dashboard._step-card', ['step' => $step])
                    @endforeach
                </div>

                <div class="pt-4 border-t border-dashed border-gray-100">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($bottomSteps as $step)
                        @include('pages.user.partials.dashboard._step-card', ['step' => $step])
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
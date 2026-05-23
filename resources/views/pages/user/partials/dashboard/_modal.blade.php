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
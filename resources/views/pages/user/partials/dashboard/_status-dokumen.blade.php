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

        {{-- Progress Bar Dinamis --}}
        <div class="flex items-center gap-3 bg-white border border-gray-100/80 px-4 py-2.5 rounded-xl shadow-3xs self-start sm:self-center transition-all duration-300 hover:border-green-100">
            <div class="text-right">
                <p class="text-[9px] uppercase font-black text-gray-400 tracking-widest">Progress Berkas</p>
                <p class="text-xs font-black text-green-600 mt-0.5">
                    {{ $verifiedCount }} / {{ $totalRequirements }} terpenuhi
                </p>
            </div>
            <div class="w-20 h-2 bg-gray-100 rounded-full overflow-hidden shadow-inner shrink-0">
                <div class="h-full bg-gradient-to-r from-emerald-400 to-green-500 rounded-full" style="width: {{ $progressPercent }}%"></div>
            </div>
        </div>

    </div>

    <div class="px-6 pt-4 flex gap-2" id="req-tabs">
        <button onclick="switchTab('dokumen')" id="tab-dokumen" class="text-xs font-semibold px-3.5 py-1.5 rounded-lg bg-gray-900 text-white transition-all">Dokumen</button>
        <button onclick="switchTab('akademik')" id="tab-akademik" class="text-xs font-semibold px-3.5 py-1.5 rounded-lg bg-gray-100 text-gray-500 transition-all">Akademik</button>
        <button onclick="switchTab('administratif')" id="tab-administratif" class="text-xs font-semibold px-3.5 py-1.5 rounded-lg bg-gray-100 text-gray-500 transition-all">Administratif</button>
    </div>

    {{-- PANEL DOKUMEN: Looping Master Requirements --}}
    <div id="panel-dokumen" class="px-4 py-4 space-y-2">
        @forelse($requirements as $req)
        @php
        // Cari status verifikasi fisik di tabel pivot berkas
        $docStatus = $registration ? $registration->documents->where('requirement_id', $req->id)->first() : null;
        $status = $docStatus ? $docStatus->verification_status : 'pending';

        // Pemetaan skema warna UI berdasarkan status verifikasi berkas dari panitia sekolah
        $theme = [
        'verified' => ['bg' => 'bg-green-50', 'border' => 'border-green-100', 'text' => 'text-green-600', 'badge' => 'border-green-200', 'icon' => 'fa-circle-check', 'label' => 'Terverifikasi'],
        'pending' => ['bg' => 'bg-amber-50', 'border' => 'border-amber-100', 'text' => 'text-amber-600', 'badge' => 'border-amber-200', 'icon' => 'fa-clock', 'label' => 'Lakukan Verifikasi'],
        'rejected' => ['bg' => 'bg-red-50', 'border' => 'border-red-100', 'text' => 'text-red-500', 'badge' => 'border-red-200', 'icon' => 'fa-circle-xmark', 'label' => 'Ditolak']
        ][$status];
        @endphp

        <div class="flex items-center gap-3 p-3 rounded-xl {{ $theme['bg'] }} border {{ $theme['border'] }}">
            <div class="w-9 h-9 rounded-lg bg-white/80 flex items-center justify-center shrink-0 shadow-3xs">
                {{-- Render icon dinamis dari database --}}
                <i class="{{ $req->icon ?? 'fa-solid fa-file-lines' }} {{ $theme['text'] }} text-[15px]"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-bold text-gray-800">{{ $req->name }}</p>
                <p class="text-xs text-gray-400 mt-0.5">
                    {{-- Jika ditolak, tampilkan alasan penolakan panitia, jika tidak tampilkan deskripsi bawaan berkas --}}
                    {{ $status === 'rejected' && $docStatus->verification_notes ? $docStatus->verification_notes : $req->description }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold {{ $theme['text'] }} bg-white border {{ $theme['badge'] }} px-2.5 py-1 rounded-lg">
                    {{ $theme['label'] }}
                </span>
                <i class="fa-solid {{ $theme['icon'] }} {{ $theme['text'] }} text-[16px]"></i>
            </div>
        </div>
        @empty
        <p class="text-xs text-gray-400 text-center py-4">Belum ada persyaratan dokumen wajib yang dikonfigurasi.</p>
        @endforelse
    </div>

    {{-- PANEL AKADEMIK: Menampilkan Nilai Rapor & TKA --}}
    <div id="panel-akademik" class="px-4 py-4 space-y-2 hidden">

        {{-- Rata-rata Rapor --}}
        <div class="flex items-center gap-3 p-3 rounded-xl {{ $registration && $registration->report_average ? 'bg-green-50 border border-green-100' : 'bg-amber-50 border border-amber-100' }}">
            <div class="w-9 h-9 rounded-lg bg-white/80 flex items-center justify-center shrink-0 shadow-3xs">
                <i class="fa-solid fa-chart-simple {{ $registration && $registration->report_average ? 'text-green-600' : 'text-amber-600' }} text-[15px]"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-bold text-gray-800">Nilai rata-rata Rapor semester 1 s.d. 5</p>
                <p class="text-xs text-gray-400 mt-0.5">
                    @if($registration && $registration->report_average)
                    Rata-rata nilai kamu: <span class="font-bold text-green-600">{{ number_format($registration->report_average, 2) }}</span>
                    @else
                    <span class="text-amber-600 font-medium">Nilai rapor belum di-input / diverifikasi oleh panitia</span>
                    @endif
                </p>
            </div>
            <i class="fa-solid {{ $registration && $registration->report_average ? 'fa-circle-check text-green-500' : 'fa-clock text-amber-500' }} text-[16px]"></i>
        </div>

        {{-- Rata-rata TKA --}}
        <div class="flex items-center gap-3 p-3 rounded-xl {{ $registration && $registration->tka_average ? 'bg-green-50 border border-green-100' : 'bg-amber-50 border border-amber-100' }}">
            <div class="w-9 h-9 rounded-lg bg-white/80 flex items-center justify-center shrink-0 shadow-3xs">
                <i class="fa-solid fa-pen-to-square {{ $registration && $registration->tka_average ? 'text-green-600' : 'text-amber-600' }} text-[15px]"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-bold text-gray-800">Nilai rata-rata Tes Kemampuan Akademik (TKA)</p>
                <p class="text-xs text-gray-400 mt-0.5">
                    @if($registration && $registration->tka_average)
                    Rata-rata nilai ujian: <span class="font-bold text-green-600">{{ number_format($registration->tka_average, 2) }}</span>
                    @else
                    <span class="text-amber-600 font-medium">Nilai TKA belum tersedia / belum mengikuti ujian</span>
                    @endif
                </p>
            </div>
            <i class="fa-solid {{ $registration && $registration->tka_average ? 'fa-circle-check text-green-500' : 'fa-clock text-amber-500' }} text-[16px]"></i>
        </div>

    </div>

    {{-- PANEL ADMINISTRATIF: Cek Validasi Kelengkapan Personal & Parent Data --}}
    <div id="panel-administratif" class="px-4 py-4 space-y-2 hidden">

        {{-- Biodata Diri Lengkap --}}
        <div class="flex items-center gap-3 p-3 rounded-xl {{ $isPersonalDataComplete ? 'bg-green-50 border border-green-100' : 'bg-red-50 border border-red-100' }}">
            <div class="w-9 h-9 rounded-lg bg-white/80 flex items-center justify-center shrink-0 shadow-3xs">
                <i class="fa-solid fa-user-check {{ $isPersonalDataComplete ? 'text-green-600' : 'text-red-500' }} text-[14px]"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-bold text-gray-800">Biodata diri lengkap</p>
                <p class="text-xs mt-0.5 {{ $isPersonalDataComplete ? 'text-gray-400' : 'text-red-400' }}">
                    {{ $isPersonalDataComplete ? 'Semua kolom wajib sudah terisi' : 'Status profil masih draft atau belum lengkap' }}
                </p>
            </div>
            @if($isPersonalDataComplete)
            <i class="fa-solid fa-circle-check text-green-500 text-[16px]"></i>
            @else
            <a href="{{ route('biodata') }}" class="text-xs font-bold text-white bg-red-500 hover:bg-red-600 px-3 py-1.5 rounded-lg transition-colors shrink-0">Lengkapi</a>
            @endif
        </div>

        {{-- Data Orang Tua / Wali --}}
        <div class="flex items-center gap-3 p-3 rounded-xl {{ $isParentDataComplete ? 'bg-green-50 border border-green-100' : 'bg-red-50 border border-red-100' }}">
            <div class="w-9 h-9 rounded-lg bg-white/80 flex items-center justify-center shrink-0 shadow-3xs">
                <i class="fa-solid fa-users {{ $isParentDataComplete ? 'text-green-600' : 'text-red-500' }} text-[14px]"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-bold text-gray-800">Data orang tua / wali</p>
                <p class="text-xs mt-0.5 {{ $isParentDataComplete ? 'text-gray-400' : 'text-red-400' }}">
                    @if($isParentDataComplete)
                    Data orang tua (Ayah & Ibu) berhasil direkam ({{ $parentDataCount }} terisi)
                    @else
                    Baru mengisi {{ $parentDataCount }} data orang tua. Mohon lengkapi data Ayah & Ibu
                    @endif
                </p>
            </div>
            @if($isParentDataComplete)
            <i class="fa-solid fa-circle-check text-green-500 text-[16px]"></i>
            @else
            <a href="{{ route('biodata') }}" class="text-xs font-bold text-white bg-red-500 hover:bg-red-600 px-3 py-1.5 rounded-lg transition-colors shrink-0">Lengkapi</a>
            @endif
        </div>

    </div>

    <div class="mx-4 mb-4 p-3 rounded-xl bg-gray-50 border border-gray-100 flex items-center gap-2">
        <i class="fa-solid fa-circle-info text-gray-400 text-[14px]"></i>
        <p class="text-xs text-gray-400">Silakan lakukan verifikasi berkas asli ke Panitia SPMB mulai tanggal <span class="font-semibold text-gray-600">{{ $verificationDateText }}</span></p>
    </div>
</div>
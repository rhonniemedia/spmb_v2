<div class="space-y-6">
    {{-- Tracker Alur Pendaftaran Pelamar --}}
    <div class="bg-white border border-gray-200 rounded-card shadow-sm overflow-hidden animate-fade-in">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-black text-[#080C1A]">Status Pendaftaran Anda</h3>
            <p class="text-xs text-[#6A7686] mt-0.5">Pantau terus perkembangan proses seleksi Anda</p>
        </div>

        <div class="p-5 max-h-[500px] overflow-y-auto space-y-5 relative">
            {{-- Garis vertikal di latar belakang alur tracker --}}
            <div class="absolute left-[29px] top-5 bottom-5 w-0.5 bg-gray-100 z-0"></div>

            @foreach($spmbSteps as $step)
            @php
            // 1. Inisialisasi default status tahapan user
            $isStepDone = false;
            $isStepActive = false;
            $userStatusText = 'Belum Dimulai';

            // Parse tanggal sistem
            $startDate = $step->start_date ? \Carbon\Carbon::parse($step->start_date) : null;
            $endDate = $step->end_date ? \Carbon\Carbon::parse($step->end_date) : null;
            $now = now();

            // 2. Tentukan status dinamis berdasarkan slug tahapan
            switch ($step->slug) {
            case 'pendaftaran-akun':
            $isStepDone = true;
            $userStatusText = 'Selesai';
            break;

            case 'pengisian-biodata':
            if ($personalData && $personalData->profile_status === 'final') {
            $isStepDone = true;
            $userStatusText = 'Selesai';
            } else {
            $isStepActive = true;
            $userStatusText = 'Belum Lengkap';
            }
            break;

            case 'pendaftaran-spmb':
            if ($registration) {
            $isStepDone = true;
            $userStatusText = 'Selesai';
            } elseif ($personalData && $personalData->profile_status === 'final') {
            $isStepActive = true;
            $userStatusText = 'Pilih Jurusan';
            }
            break;

            case 'verifikasi-dokumen':
            if ($registration && $registration->verification_status === 'verified') {
            $isStepDone = true;
            $userStatusText = 'Terverifikasi';
            } elseif ($registration && $registration->verification_status === 'pending') {
            $isStepActive = true;
            $userStatusText = 'Diproses';
            } elseif ($registration && $registration->verification_status === 'rejected') {
            $isStepActive = true;
            $userStatusText = 'Revisi';
            }
            break;

            case 'seleksi-akademik':
            // SELEKSI AKADEMIK/OBSERVASI: Selesai jika data observasi sudah diisi oleh panitia
            if ($registration && $registration->observation) {
            $obsStatus = $registration->observation->observation_status;

            if ($obsStatus === 'passed') {
            $isStepDone = true;
            $userStatusText = 'Lolos Uji'; // Hasil tes fisik/kesehatan memenuhi syarat
            } elseif ($obsStatus === 'failed') {
            $isStepDone = true;
            $userStatusText = 'Tidak Lolos'; // Hasil tes fisik/kesehatan gugur
            } else {
            // Jika data observasi sudah dibuat tetapi statusnya masih 'pending'
            $isStepActive = true;
            $userStatusText = 'Dinilai';
            }
            // Aktif jika berkas pendaftaran sudah dinyatakan valid/verified oleh panitia, tetapi observasi belum dibuat
            } elseif ($registration && $registration->verification_status === 'verified') {
            $isStepActive = true;
            $userStatusText = 'Observasi';
            }
            break;

            case 'pengumuman-hasil':
            // 1. Cek jika record data kelulusan di tabel selection_results sudah diterbitkan resmi
            if ($registration && $registration->selectionResult) {
            $isStepDone = true;
            if ($registration->selectionResult->status === 'accepted') {
            $userStatusText = 'Lulus';
            } elseif ($registration->selectionResult->status === 'rejected') {
            $userStatusText = 'Tidak Lulus';
            } else {
            $userStatusText = 'Proses';
            }
            } else {
            // 2. Tahap observasi fisik/kesehatan sebelumnya dianggap selesai jika status kelayakan telah terekam (passed/failed)
            $isObservationDone = $registration && $registration->observation && in_array($registration->observation->observation_status, ['passed', 'failed']);

            if ($isObservationDone) {
            // Hanya menyala MERAH (Aktif) jika hari ini SUDAH MASUK periode rentang tanggal rilis pengumuman hasil
            if ($startDate && $endDate && $now->between($startDate, $endDate)) {
            $isStepActive = true;
            $userStatusText = 'Rilis Hasil';
            } else {
            // JIKA BELUM mencapai tanggal pengumuman, biarkan berwarna abu-abu tenang (isStepActive = false)
            $isStepActive = false;
            $userStatusText = 'Menunggu';
            }
            }
            }
            break;

            default:
            // Otomatisasi berbasis waktu riil sistem untuk tahap "Daftar Ulang & Berkas" dan "MOS"
            if ($startDate && $endDate) {
            if ($now->between($startDate, $endDate)) {
            $isStepActive = true;
            $userStatusText = 'Aktif';
            } elseif ($now->greaterThan($endDate)) {
            $isStepDone = true;
            $userStatusText = 'Selesai';
            }
            }
            break;
            }

            // 3. Konfigurasi penataan warna indikator visual (Bulatan & Teks)
            $bulletBgColor = 'bg-gray-100 text-gray-400 border border-gray-200';
            $textTitleColor = 'text-gray-400';

            if ($isStepDone) {
            $bulletBgColor = 'bg-emerald-500 text-white ring-4 ring-emerald-500/10';
            $textTitleColor = 'text-[#080C1A] font-bold';
            } elseif ($isStepActive) {
            $bulletBgColor = 'bg-primary text-white ring-4 ring-primary/20 animate-pulse';
            $textTitleColor = 'text-primary font-black';
            }
            @endphp

            <div class="flex gap-4 relative z-10 items-start group">
                {{-- Bulatan Angka / Icon Check --}}
                <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs flex-shrink-0 transition-all duration-300 {{ $bulletBgColor }}">
                    @if($isStepDone)
                    <i class="fa-solid fa-check text-[11px]"></i>
                    @else
                    <span class="text-[11px] font-bold">{{ $step->step_order }}</span>
                    @endif
                </div>

                {{-- Konten Informasi Detail Tahapan --}}
                <div class="flex-1 min-w-0 pt-0.5">
                    <div class="flex items-center justify-between gap-2">
                        <h4 class="text-[13px] leading-tight transition-colors {{ $textTitleColor }}">
                            {{ $step->title }}
                        </h4>
                        {{-- Badge Status Badge --}}
                        <span class="text-[10px] px-1.5 py-0.5 rounded font-bold flex-shrink-0 tracking-wide
                                {{ $isStepDone ? 'bg-emerald-50 text-emerald-600' : '' }}
                                {{ $isStepActive ? 'bg-primary/10 text-primary' : '' }}
                                {{ !$isStepDone && !$isStepActive ? 'bg-gray-50 text-gray-400' : '' }}">
                            {{ $userStatusText }}
                        </span>
                    </div>
                    <p class="text-[11px] text-[#6A7686] mt-0.5 line-clamp-1 group-hover:line-clamp-none transition-all duration-200">
                        <i class="fa-solid fa-calendar-days text-[10px] mr-1 text-gray-400"></i>{{ $step->period_text }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Partial Biodata Sidebar bawaan Anda --}}
    @include('pages.user.partials.biodata._sidebar')
</div>
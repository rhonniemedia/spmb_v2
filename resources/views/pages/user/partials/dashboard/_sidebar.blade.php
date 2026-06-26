<div class="space-y-5">

    {{-- ══════════════════════════════
        Tracker Status Pendaftaran
    ══════════════════════════════ --}}
    <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-900">Status pendaftaran</h3>
            <p class="text-xs text-gray-400 mt-0.5">Riwayat kemajuan proses seleksimu</p>
        </div>

        <div class="p-5 space-y-4 relative">
            {{-- Garis vertikal tracker --}}
            <div class="absolute left-[28px] top-5 bottom-5 w-px bg-gray-100 z-0"></div>

            @foreach($spmbSteps as $step)
            @php
            $isStepDone = false;
            $isStepActive = false;
            $userStatusText = 'Belum dimulai';

            $startDate = $step->start_date ? \Carbon\Carbon::parse($step->start_date) : null;
            $endDate = $step->end_date ? \Carbon\Carbon::parse($step->end_date) : null;
            $now = now();

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
            $userStatusText = 'Belum lengkap';
            }
            break;

            case 'pendaftaran-spmb':
            if ($registration) {
            $isStepDone = true;
            $userStatusText = 'Selesai';
            } elseif ($personalData && $personalData->profile_status === 'final') {
            $isStepActive = true;
            $userStatusText = 'Pilih jurusan';
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
            $userStatusText = 'Perlu revisi';
            }
            break;

            case 'seleksi-akademik':
            if ($registration && $registration->observation) {
            $obsStatus = $registration->observation->observation_status;
            if ($obsStatus === 'passed') {
            $isStepDone = true;
            $userStatusText = 'Lolos';
            } elseif ($obsStatus === 'failed') {
            $isStepDone = true;
            $userStatusText = 'Tidak lolos';
            } else {
            $isStepActive = true;
            $userStatusText = 'Sedang dinilai';
            }
            } elseif ($registration && $registration->verification_status === 'verified') {
            $isStepActive = true;
            $userStatusText = 'Observasi';
            }
            break;

            case 'pengumuman-hasil':
            if ($registration && $registration->selectionResult) {
            $isStepDone = true;
            $statusMap = ['accepted' => 'Lulus', 'rejected' => 'Tidak lulus'];
            $userStatusText = $statusMap[$registration->selectionResult->status] ?? 'Proses';
            } else {
            $isObsDone = $registration && $registration->observation
            && in_array($registration->observation->observation_status, ['passed', 'failed']);
            if ($isObsDone) {
            if ($startDate && $endDate && $now->between($startDate, $endDate)) {
            $isStepActive = true;
            $userStatusText = 'Hasil dirilis';
            } else {
            $userStatusText = 'Menunggu';
            }
            }
            }
            break;

            default:
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

            // Konfigurasi warna
            if ($isStepDone) {
            $bulletCls = 'bg-emerald-500 text-white';
            $titleCls = 'text-gray-800 font-medium';
            $badgeCls = 'bg-emerald-50 text-emerald-600';
            } elseif ($isStepActive) {
            $bulletCls = 'bg-primary text-white ring-4 ring-primary/15';
            $titleCls = 'text-primary font-semibold';
            $badgeCls = 'bg-primary/10 text-primary';
            } else {
            $bulletCls = 'bg-gray-100 text-gray-400 border border-gray-200';
            $titleCls = 'text-gray-400';
            $badgeCls = 'bg-gray-50 text-gray-400';
            }
            @endphp

            <div class="flex gap-3 relative z-10 items-start">
                {{-- Bullet --}}
                <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] flex-shrink-0 mt-0.5 {{ $bulletCls }}">
                    @if($isStepDone)
                    <i class="fa-solid fa-check text-[9px]"></i>
                    @else
                    <span class="font-bold">{{ $step->step_order }}</span>
                    @endif
                </div>

                {{-- Konten --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2">
                        <h4 class="text-xs leading-tight {{ $titleCls }}">{{ $step->title }}</h4>
                        <span class="text-[10px] px-1.5 py-0.5 rounded font-medium flex-shrink-0 {{ $badgeCls }}">
                            {{ $userStatusText }}
                        </span>
                    </div>
                    <p class="text-[11px] text-gray-400 mt-0.5">
                        {{ $step->period_text }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Sidebar Biodata (partial bawaan) --}}
    @include('pages.user.partials.biodata._sidebar')

</div>
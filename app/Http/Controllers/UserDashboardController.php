<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Concentration;
use App\Models\Faq;
use App\Models\ParentData;
use App\Models\PersonalData;
use App\Models\RegistrationData;
use App\Models\Requirement;
use App\Models\SpmbStep;
use App\Notifications\DataReminderNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $now = now();

        // ─── 1. REQUIREMENTS (Berkas Fisik) ──────────────────────────────────
        $requirements = Requirement::where('category', 'dokumen')
            ->orderBy('is_mandatory', 'desc')
            ->get() ?? collect([]);
        $totalRequirements = $requirements->count();

        // ─── 2. PERSONAL DATA ────────────────────────────────────────────────
        $personalData = PersonalData::where('user_id', $user->id)->first();

        // Auto-kirim notifikasi sambutan jika belum ada personal data
        if (!$personalData) {
            $hasWelcomeNotification = $user->notifications()
                ->where('data->title', 'Selamat Datang di SPMB!')
                ->exists();

            if (!$hasWelcomeNotification) {
                $user->notify(new DataReminderNotification('welcome'));
                $user->load('unreadNotifications');
            }
        }

        // ─── 3. REGISTRATION DATA ────────────────────────────────────────────
        $registration = null;
        $verifiedCount = 0;
        $progressPercent = 0;

        if ($personalData) {
            $registration = RegistrationData::with([
                'documents',
                'selectionResult',
                'selectionResult.acceptedConcentration', // untuk profil singkat
                'admissionPath',                         // untuk jalur masuk
            ])
                ->where('personal_data_id', $personalData->id)
                ->first();

            if ($registration) {
                $verifiedCount = $registration->documents
                    ->where('verification_status', 'verified')
                    ->count();

                $progressPercent = $totalRequirements > 0
                    ? round(($verifiedCount / $totalRequirements) * 100)
                    : 0;
            }
        }

        // ─── 4. PARENT DATA ───────────────────────────────────────────────────
        $parentDataCount = $personalData
            ? ParentData::where('personal_data_id', $personalData->id)->count()
            : 0;

        // ─── 5. FLAG KELENGKAPAN DATA ─────────────────────────────────────────
        $isPersonalDataComplete = $personalData && $personalData->profile_status === 'final';
        $isParentDataComplete   = $parentDataCount >= 2;
        $isPhotoUploaded        = $personalData && !empty($personalData->photo);

        // ─── 6. PERSENTASE BIODATA ────────────────────────────────────────────
        $biodataPercentage = 0;
        $biodataStatusText = 'Belum Mengisi Data';

        if ($personalData) {
            $filledFields = 0;
            $totalFields  = 5;

            if (!empty($personalData->full_name))  $filledFields++;
            if (!empty($personalData->nisn_hash))  $filledFields++;
            if (!empty($personalData->nik_hash))   $filledFields++;
            if (!empty($personalData->photo))      $filledFields++;
            if ($parentDataCount >= 2)             $filledFields++;

            $biodataPercentage = round(($filledFields / $totalFields) * 100);

            if ($biodataPercentage == 100 && $personalData->profile_status === 'final') {
                $biodataStatusText = 'Data Sudah Final';
            } elseif ($biodataPercentage == 100) {
                $biodataStatusText = 'Siap di-Finalisasi';
            } else {
                $biodataStatusText = 'Lengkapi Foto & Ortu';
            }
        }

        // ─── 7. SPMB STEPS (Timeline) ────────────────────────────────────────
        $spmbSteps = SpmbStep::orderBy('step_order', 'asc')->get() ?? collect([]);
        $mappedSteps = [];
        $currentActiveStepText = 'Menunggu Pembukaan';
        $latestActiveStep = null;

        foreach ($spmbSteps as $step) {
            $startDate  = $step->start_date ? Carbon::parse($step->start_date) : null;
            $endDate    = $step->end_date   ? Carbon::parse($step->end_date)   : null;
            $isActive   = false;
            $isDone     = false;
            $statusText = 'Mendatang';

            if ($startDate && $endDate) {
                if ($now->between($startDate, $endDate)) {
                    $isActive              = true;
                    $statusText            = 'Sedang Berlangsung';
                    $currentActiveStepText = "Tahap {$step->step_order}: {$step->title}";
                    $latestActiveStep      = $step;
                } elseif ($now->greaterThan($endDate)) {
                    $isDone     = true;
                    $statusText = 'Selesai';
                }
            }

            $periodText = $step->period_text;
            if ($step->step_order == 8 || str_contains($step->slug, 'mpls') || str_contains($step->slug, 'orientasi')) {
                $periodText = '13, 14 Juli 2026';
            }

            $mappedSteps[] = [
                'no'     => str_pad($step->step_order, 2, '0', STR_PAD_LEFT),
                'title'  => $step->title,
                'desc'   => $step->description,
                'date'   => $periodText,
                'status' => $statusText,
                'active' => $isActive,
                'done'   => $isDone,
                'icon'   => $step->icon ?? 'fa-circle-dot',
            ];
        }

        $topSteps    = array_slice($mappedSteps, 0, 4);
        $bottomSteps = array_slice($mappedSteps, 4, 4);

        // ─── 8. STEP PENGUMUMAN ───────────────────────────────────────────────
        $announcementStep = $spmbSteps->first(
            fn($s) =>
            str_contains($s->slug, 'pengumuman') || str_contains($s->slug, 'kelulusan')
        );

        $daysToAnnouncement = 0;
        $announcementDateText = '-';

        if ($announcementStep && $announcementStep->start_date) {
            $targetDate           = Carbon::parse($announcementStep->start_date);
            $daysToAnnouncement   = max(0, ceil($now->diffInDays($targetDate, false)));
            $announcementDateText = $targetDate->translatedFormat('d F Y');
        }

        // ─── 9. KONSENTRASI ───────────────────────────────────────────────────
        $concentrations = Concentration::where('status', 'active')
            ->orderBy('name', 'asc')
            ->get() ?? collect([]);
        $totalQuota = $concentrations->sum('quota');

        // ─── 10. STEP VERIFIKASI BERKAS ───────────────────────────────────────
        $verificationStep = $spmbSteps->first(
            fn($s) =>
            str_contains($s->slug, 'verifikasi') || str_contains($s->slug, 'validasi')
        );

        $verificationDateText = '';
        if ($verificationStep && $verificationStep->start_date) {
            $verificationDateText = Carbon::parse($verificationStep->start_date)
                ->translatedFormat('d F Y');
        }

        // ─── 11. STEP DAFTAR ULANG ───────────────────────────────────────────
        $reRegistrationStep = $spmbSteps->firstWhere('slug', 'daftar-ulang-dan-penyerahan-berkas');

        $isReRegistrationActive = $reRegistrationStep && $now->between(
            Carbon::parse($reRegistrationStep->start_date),
            Carbon::parse($reRegistrationStep->end_date)
        );

        // ─── 12. DATA KHUSUS DASHBOARD DAFTAR ULANG ──────────────────────────
        // Hanya disiapkan saat view daftar-ulang yang akan ditampilkan,
        // agar tidak ada query sia-sia saat masih di dashboard SPMB biasa.

        $reRegistrationData = null;

        if ($isReRegistrationActive) {
            // 12a. Status daftar ulang siswa
            //      re_registered_at null  = belum daftar ulang
            //      re_registered_at filled = sudah daftar ulang
            $isReRegistered     = $registration && !is_null($registration->re_registered_at);
            $reRegisteredAt     = $registration?->re_registered_at
                ? Carbon::parse($registration->re_registered_at)->translatedFormat('d F Y, H:i')
                : null;

            // 12b. Deadline & sisa waktu daftar ulang
            $reRegDeadline      = $reRegistrationStep->end_date
                ? Carbon::parse($reRegistrationStep->end_date)->endOfDay()
                : null;
            $reRegDeadlineText  = $reRegDeadline
                ? $reRegDeadline->translatedFormat('d F Y')
                : '-';

            // 12c. Progress step registrasi ulang
            //      Dibuat dari SpmbStep yang sudah ada, filter hanya step relevan
            //      dengan tag atau slug tertentu, atau ambil semua & biarkan blade filter
            $reRegProgressSteps = $spmbSteps
                ->filter(fn($s) => in_array($s->slug, [
                    'pengumuman-hasil-seleksi',
                    'konfirmasi-kesediaan',
                    'daftar-ulang-dan-penyerahan-berkas',
                    'verifikasi-berkas',
                    'selesai',
                ]))
                ->sortBy('step_order')
                ->values()
                ->map(fn($s) => [
                    'title' => $s->title,
                    'desc'  => $s->description,
                    'done'  => $s->status === 'done' || $s->status === 'active',
                ]);

            // 12d. Status verifikasi berkas & registrasi
            //      Diambil dari dokumen yang sudah diverifikasi panitia
            $fileStatus         = $verifiedCount >= $totalRequirements && $totalRequirements > 0
                ? 'Lengkap'
                : 'Belum Lengkap';

            $verificationStatus = $registration?->documents
                ->contains('verification_status', 'processing')
                ? 'Diproses'
                : ($verifiedCount > 0 ? 'Terverifikasi' : 'Menunggu');

            $registrationStatus = $isReRegistered ? 'Diterima' : 'Menunggu';

            // 12e. Profil singkat — konsentrasi yang diterima
            $acceptedConcentration = $registration?->selectionResult?->acceptedConcentration ?? null;

            // 12f. Pengumuman aktif (is_active = true, urut by sort_order)
            $announcements = Announcement::where('is_active', true)
                ->orderBy('is_urgent', 'asc')
                ->get();

            // 12g. FAQ daftar ulang (filter by category slug jika ada, fallback semua)
            $faqs = Faq::whereHas('category', fn($q) => $q->where('slug', 'daftar-ulang'))
                ->orderBy('updated_at', 'asc')
                ->get();

            // 12h. Checklist Akses Cepat
            //      Hitung progress per item berdasarkan data yang ada
            $checklistItems = collect([
                [
                    'key'      => 'biodata',
                    'label'    => 'Biodata',
                    'desc'     => 'Lengkapi data pribadi murid baru.',
                    'icon'     => 'file',
                    'color'    => '#ff1443',
                    'gradient' => 'from-[#ff1443] to-[#f43f5e]',
                    'status'   => $isPersonalDataComplete ? 'Selesai' : "{$biodataPercentage}%",
                    'badge_bg' => $isPersonalDataComplete ? 'bg-[#dcfce7] text-[#166534]' : 'bg-[#fee2e2] text-[#ff1443]',
                    'url'      => route('biodata'),
                ],
                [
                    'key'      => 'konfirmasi',
                    'label'    => 'Konfirmasi',
                    'desc'     => 'Konfirmasi kesediaan daftar ulang.',
                    'icon'     => 'check-circle-2',
                    'color'    => '#30b22d',
                    'gradient' => 'from-[#30b22d] to-[#4ade80]',
                    'status'   => $isReRegistered ? 'Selesai' : 'Belum',
                    'badge_bg' => $isReRegistered ? 'bg-[#dcfce7] text-[#166534]' : 'bg-[#fef9c3] text-[#92400e]',
                    'url'      => route('konfirmasi'),
                ],
                [
                    'key'      => 'cetak_bukti',
                    'label'    => 'Cetak Bukti',
                    'desc'     => 'Cetak bukti lolos dan daftar ulang.',
                    'icon'     => 'printer',
                    'color'    => '#f59e0b',
                    'gradient' => 'from-[#f59e0b] to-[#fbbf24]',
                    'status'   => 'Siap Cetak',
                    'badge_bg' => 'bg-[#dcfce7] text-[#166534]',
                    'url'      => route('cetak-bukti'),
                ],
                [
                    'key'      => 'jadwal',
                    'label'    => 'Jadwal',
                    'desc'     => 'Detail jadwal kegiatan registrasi ulang.',
                    'icon'     => 'calendar-days',
                    'color'    => '#0ea5e9',
                    'gradient' => 'from-[#0ea5e9] to-[#38bdf8]',
                    'status'   => $reRegDeadline ? $reRegDeadline->format('d M') : '-',
                    'badge_bg' => 'bg-[#e0f2fe] text-[#0ea5e9]',
                    'url'      => route('jadwal'),
                ],
            ]);

            // Kumpulkan semua data khusus daftar ulang ke satu array
            $reRegistrationData = compact(
                'isReRegistered',
                'reRegisteredAt',
                'reRegDeadline',
                'reRegDeadlineText',
                'reRegProgressSteps',
                'fileStatus',
                'verificationStatus',
                'registrationStatus',
                'acceptedConcentration',
                'announcements',
                'faqs',
                'checklistItems'
            );
        }

        // ─── 13. PILIH VIEW ───────────────────────────────────────────────────
        $viewDashboard = $isReRegistrationActive
            ? 'pages.user.dashboard-daftar-ulang'
            : 'pages.user.dashboard-spmb';

        return view($viewDashboard, compact(
            // ── Data umum (dipakai di kedua dashboard) ──
            'requirements',
            'totalRequirements',
            'registration',
            'verifiedCount',
            'progressPercent',
            'isPersonalDataComplete',
            'isParentDataComplete',
            'isPhotoUploaded',
            'personalData',
            'parentDataCount',
            'biodataPercentage',
            'biodataStatusText',
            'spmbSteps',
            'topSteps',
            'bottomSteps',
            'currentActiveStepText',
            'latestActiveStep',
            'daysToAnnouncement',
            'announcementDateText',
            'verificationDateText',
            'concentrations',
            'totalQuota',
            // ── Data khusus daftar ulang (null saat dashboard SPMB) ──
            'reRegistrationStep',
            'isReRegistrationActive',
            'reRegistrationData'
        ));
    }
}

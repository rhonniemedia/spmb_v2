<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Concentration;
use App\Models\Faq;
use App\Models\ParentData;
use App\Models\PersonalData;
use App\Models\RegistrationData;
use App\Models\Requirement;
use App\Models\ReRegistrationData;
use App\Models\SpmbStep;
use App\Notifications\DataReminderNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
                'reRegistrationData',                    // untuk status daftar ulang
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
            // 12a. Ambil record re_registration_data milik siswa ini
            //      Record dibuat saat siswa pertama kali mengakses/konfirmasi daftar ulang
            $reReg          = $registration?->reRegistrationData;

            // Status per-step dari timestamps di re_registration_data
            $isAnnounced    = $reReg && !is_null($reReg->announced_at);
            $isConfirmed    = $reReg && !is_null($reReg->confirmed_at);
            $isReRegistered = $reReg && !is_null($reReg->re_registered_at);
            $isVerified     = $reReg && !is_null($reReg->verified_at);
            $isCompleted    = $reReg && !is_null($reReg->completed_at);

            $reRegisteredAt = $isReRegistered
                ? Carbon::parse($reReg->re_registered_at)->translatedFormat('d F Y, H:i')
                : null;

            // 12b. Deadline & sisa waktu daftar ulang
            $reRegDeadline      = $reRegistrationStep->end_date
                ? Carbon::parse($reRegistrationStep->end_date)->endOfDay()
                : null;
            $reRegDeadlineText  = $reRegDeadline
                ? $reRegDeadline->translatedFormat('d F Y')
                : '-';

            // 12c. Progress step — done ditentukan dari timestamp di re_registration_data
            //      bukan dari SpmbStep->status, agar akurat per siswa
            $reRegProgressSteps = collect([
                [
                    'title' => 'Pengumuman',
                    'desc'  => 'Hasil seleksi telah diumumkan.',
                    'done'  => $isAnnounced,
                ],
                [
                    'title' => 'Konfirmasi Kesediaan',
                    'desc'  => 'Kesediaan hadir telah dikonfirmasi.',
                    'done'  => $isConfirmed,
                ],
                [
                    'title' => 'Daftar Ulang',
                    'desc'  => 'Penyerahan berkas daftar ulang.',
                    'done'  => $isReRegistered,
                ],
                [
                    'title' => 'Verifikasi',
                    'desc'  => 'Panitia sedang memeriksa berkas.',
                    'done'  => $isVerified,
                ],
                [
                    'title' => 'Selesai',
                    'desc'  => 'Proses daftar ulang selesai.',
                    'done'  => $isCompleted,
                ],
            ]);

            // 12d. Status verifikasi berkas & registrasi — dari re_registration_data
            $fileStatus         = ($reReg?->data_status === 'complete') ? 'Lengkap' : 'Belum Lengkap';

            $verificationStatus = match ($reReg?->verification_status) {
                'processing' => 'Diproses',
                'verified'   => 'Terverifikasi',
                'rejected'   => 'Ditolak',
                default      => 'Menunggu',
            };

            $registrationStatus = $isReRegistered ? 'Diterima' : 'Menunggu';

            // 12e. Profil singkat — konsentrasi yang diterima
            $acceptedConcentration = $registration?->selectionResult?->acceptedConcentration ?? null;

            // 12f. Pengumuman aktif — urut by created_at (sort_order belum ada di migrasi)
            $announcements = Announcement::where('is_active', true)
                ->orderBy('created_at', 'asc')
                ->get();

            // 12g. FAQ daftar ulang — urut by created_at (sort_order belum ada di migrasi)
            $faqs = Faq::whereHas('category', fn($q) => $q->where('slug', 'daftar-ulang'))
                ->orderBy('created_at', 'asc')
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
                    'url'      => '#',
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
                    'url'      => '#',
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
                    'url'      => '#',
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
                    'url'      => '#',
                ],
            ]);

            // Kumpulkan semua data khusus daftar ulang ke satu array
            $reRegistrationData = compact(
                'reReg',
                'isReRegistered',
                'isConfirmed',
                'isVerified',
                'isCompleted',
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

    public function confirmReRegistration(Request $request)
    {
        $user = Auth::user();

        $personalData = PersonalData::where('user_id', $user->id)->first();

        // Proteksi 1: Pastikan biodata sudah final
        if (!$personalData || $personalData->profile_status !== 'final') {
            return back()->with('error', 'Silakan selesaikan kelengkapan biodata Anda terlebih dahulu.');
        }

        $registration = RegistrationData::where('personal_data_id', $personalData->id)->first();

        // Proteksi 2: Pastikan data registrasi ada
        if (!$registration) {
            return back()->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        // Cari record daftar ulang atau buat baru jika belum ada
        // (Asumsi foreign key di tabel re_registration_data adalah registration_id)
        $reReg = ReRegistrationData::firstOrCreate(
            ['registration_id' => $registration->id]
        );

        // Jika belum dikonfirmasi, maka update timestamp-nya
        if (is_null($reReg->confirmed_at)) {
            $reReg->update([
                'confirmed_at' => now(),
            ]);

            return back()->with('success', 'Konfirmasi kesediaan daftar ulang berhasil disimpan!');
        }

        return back()->with('info', 'Anda sudah melakukan konfirmasi kesediaan sebelumnya.');
    }
}

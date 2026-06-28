<?php

namespace App\Http\Controllers;

use App\Models\Concentration;
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

        // 1. Ambil data dokumen persyaratan dari master requirements
        $requirements = Requirement::where('category', 'dokumen')
            ->orderBy('is_mandatory', 'desc')
            ->get() ?? collect([]);
        $totalRequirements = $requirements->count();

        // Ambil data personal pendaftar
        $personalData = PersonalData::where('user_id', $user->id)->first();

        // ─── AUTO-CHECK & KIRIM NOTIFIKASI SAMBUTAN (UNTUK GOOGLE & MANUAL) ───
        if (!$personalData) {
            // Periksa apakah user sudah pernah menerima notifikasi selamat datang agar tidak duplikat saat di-refresh
            $hasWelcomeNotification = $user->notifications()
                ->where('data->title', 'Selamat Datang di SPMB!')
                ->exists();

            if (!$hasWelcomeNotification) {
                // Kirim notifikasi selamat datang ke database
                $user->notify(new DataReminderNotification('welcome'));

                // Muat ulang relasi unreadNotifications agar langsung tampil di navbar saat ini juga
                $user->load('unreadNotifications');
            }
        }

        // Ambil data registrasi utama pendaftar beserta dokumen fisik yang sudah divalidasi panitia
        $registration = null;
        $verifiedCount = 0;
        $progressPercent = 0;

        if ($personalData) {
            $registration = RegistrationData::with(['documents', 'selectionResult'])
                ->where('personal_data_id', $personalData->id)
                ->first();

            if ($registration) {
                // Hitung berapa berkas fisik bawaan yang sudah berstatus 'verified' oleh panitia
                $verifiedCount = $registration->documents->where('verification_status', 'verified')->count();
                $progressPercent = $totalRequirements > 0 ? ($verifiedCount / $totalRequirements) * 100 : 0;
            }
        }

        // Cek kelengkapan Parent Data (Memastikan ada data bapak DAN data ibu yang terisi)
        $parentDataCount = $personalData ? ParentData::where('personal_data_id', $personalData->id)->count() : 0;

        // Pengecekan spesifik untuk panel administratif (Minimal memiliki 2 records: Ayah & Ibu)
        $isPersonalDataComplete = $personalData && $personalData->profile_status === 'final';
        $isParentDataComplete = $parentDataCount >= 2;

        // Cek status foto dari personal_data
        $isPhotoUploaded = $personalData && !empty($personalData->photo);

        // 2. Hitung Kelengkapan Biodata (Logika bawaan Anda tetap dipertahankan)
        $biodataPercentage = 0;
        $biodataStatusText = 'Belum Mengisi Data';

        if ($personalData) {
            $filledFields = 0;
            $totalFields = 5;

            if (!empty($personalData->full_name)) $filledFields++;
            if (!empty($personalData->nisn_hash)) $filledFields++;
            if (!empty($personalData->nik_hash)) $filledFields++;
            if (!empty($personalData->photo)) $filledFields++;
            if ($parentDataCount >= 2) $filledFields++;

            $biodataPercentage = round(($filledFields / $totalFields) * 100);

            if ($biodataPercentage == 100 && $personalData->profile_status === 'final') {
                $biodataStatusText = 'Data Sudah Final';
            } elseif ($biodataPercentage == 100) {
                $biodataStatusText = 'Siap di-Finalisasi';
            } else {
                $biodataStatusText = 'Lengkapi Foto & Ortu';
            }
        }

        // --- TIMELINE LOGIC ---
        $spmbSteps = SpmbStep::orderBy('step_order', 'asc')->get() ?? collect([]);
        $mappedSteps = [];
        $currentActiveStepText = 'Menunggu Pembukaan';
        $latestActiveStep = null; // Menyimpan object step aktif dengan order paling akhir

        foreach ($spmbSteps as $step) {
            $startDate = $step->start_date ? Carbon::parse($step->start_date) : null;
            $endDate = $step->end_date ? Carbon::parse($step->end_date) : null;
            $isActive = false;
            $isDone = false;
            $statusText = 'Mendatang';

            if ($startDate && $endDate) {
                if ($now->between($startDate, $endDate)) {
                    $isActive = true;
                    $statusText = 'Sedang Berlangsung';
                    $currentActiveStepText = "Tahap {$step->step_order}: {$step->title}";
                    $latestActiveStep = $step; // Karena loop ini urut ASC, variabel ini otomatis terisi oleh tahapan aktif dengan order terbesar/terakhir
                } elseif ($now->greaterThan($endDate)) {
                    $isDone = true;
                    $statusText = 'Selesai';
                }
            }

            $periodText = $step->period_text;
            if ($step->step_order == 8 || str_contains($step->slug, 'mpls') || str_contains($step->slug, 'orientasi')) {
                $periodText = '13, 14 Juli 2026';
            }

            $mappedSteps[] = [
                'no' => str_pad($step->step_order, 2, '0', STR_PAD_LEFT),
                'title' => $step->title,
                'desc' => $step->description,
                'date' => $periodText,
                'status' => $statusText,
                'active' => $isActive,
                'done' => $isDone,
                'icon' => $step->icon ?? 'fa-circle-dot'
            ];
        }

        $topSteps = array_slice($mappedSteps, 0, 4);
        $bottomSteps = array_slice($mappedSteps, 4, 4);

        $announcementStep = $spmbSteps->first(function ($step) {
            return str_contains($step->slug, 'pengumuman') || str_contains($step->slug, 'kelulusan');
        });

        $daysToAnnouncement = 0;
        $announcementDateText = '-';

        if ($announcementStep && $announcementStep->start_date) {
            $targetDate = Carbon::parse($announcementStep->start_date);
            $daysToAnnouncement = max(0, ceil($now->diffInDays($targetDate, false)));
            $announcementDateText = $targetDate->translatedFormat('d F Y');
        }

        $concentrations = Concentration::where('status', 'active')->orderBy('name', 'asc')->get() ?? collect([]);
        $totalQuota = $concentrations->sum('quota');

        // ─── AMBIL TANGGAL VERIFIKASI BERKAS SECARA DINAMIS ───
        $verificationStep = $spmbSteps->first(function ($step) {
            return str_contains($step->slug, 'verifikasi') || str_contains($step->slug, 'validasi');
        });

        $verificationDateText = '';

        if ($verificationStep && $verificationStep->start_date) {
            $verificationDateText = Carbon::parse($verificationStep->start_date)->translatedFormat('d F Y');
        }

        // ─── LOGIKA PENGECEKAN DASHBOARD DINAMIS ───
        $reRegistrationStep = $spmbSteps->firstWhere('slug', 'daftar-ulang-dan-penyerahan-berkas');

        $isReRegistrationActive = $reRegistrationStep && $now->between(
            Carbon::parse($reRegistrationStep->start_date),
            Carbon::parse($reRegistrationStep->end_date)
        );

        $viewDashboard = $isReRegistrationActive ? 'pages.user.dashboard-daftar-ulang' : 'pages.user.dashboard-spmb';

        return view($viewDashboard, compact(
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
            'totalQuota'
        ));
    }
}

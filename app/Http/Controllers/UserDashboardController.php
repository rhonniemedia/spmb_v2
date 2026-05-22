<?php

namespace App\Http\Controllers;

use App\Models\Concentration;
use App\Models\ParentData;
use App\Models\PersonalData;
use App\Models\Requirement;
use App\Models\SpmbStep;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $now = now();

        // 1. Ambil data dokumen persyaratan (Tanpa Relasi User)
        $requirements = Requirement::where('category', 'dokumen')
            ->orderBy('is_mandatory', 'desc')
            ->get() ?? collect([]);
        $totalRequirements = $requirements->count();

        // Ambil data personal pendaftar
        $personalData = PersonalData::where('user_id', $user->id)->first();
        $parentDataCount = $personalData ? ParentData::where('personal_data_id', $personalData->id)->count() : 0;

        // Cek status foto dari personal_data
        $isPhotoUploaded = $personalData && !empty($personalData->photo);

        // 2. Hitung Kelengkapan Biodata
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

        // 3. Ambil Semua Data Langkah SPMB untuk Timeline & Pemetaan Status
        $spmbSteps = SpmbStep::orderBy('step_order', 'asc')->get() ?? collect([]);

        $mappedSteps = [];
        $currentActiveStepText = 'Menunggu Pembukaan';

        foreach ($spmbSteps as $step) {
            $startDate = $step->start_date ? Carbon::parse($step->start_date) : null;
            $endDate = $step->end_date ? Carbon::parse($step->end_date) : null;

            $isActive = false;
            $isDone = false;
            $statusText = 'Mendatang';

            // Menentukan status aktif/selesai berdasarkan tanggal real-time
            if ($startDate && $endDate) {
                if ($now->between($startDate, $endDate)) {
                    $isActive = true;
                    $statusText = 'Sedang Berlangsung';
                    $currentActiveStepText = "Tahap {$step->step_order}: {$step->title}";
                } elseif ($now->greaterThan($endDate)) {
                    $isDone = true;
                    $statusText = 'Selesai';
                }
            }

            // Hardcode teks tanggal khusus untuk langkah ke-8 (Masa Orientasi) sesuai request
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

        // Membagi urutan langkah untuk kebutuhan grid baris atas dan baris bawah pada UI view Anda
        $topSteps = array_slice($mappedSteps, 0, 4);
        $bottomSteps = array_slice($mappedSteps, 4, 4);

        // Hitung Hari Menuju Pengumuman berdasarkan slug 'pengumuman' atau 'kelulusan'
        $announcementStep = $spmbSteps->first(function ($step) {
            return str_contains($step->slug, 'pengumuman') || str_contains($step->slug, 'kelulusan');
        });

        $daysToAnnouncement = 0;
        $announcementDateText = '-';

        if ($announcementStep && $announcementStep->start_date) {
            $targetDate = Carbon::parse($announcementStep->start_date);

            // Membulatkan ke atas agar desimal panjang hilang dan menjadi angka bulat murni
            $daysToAnnouncement = max(0, ceil($now->diffInDays($targetDate, false)));

            $announcementDateText = $targetDate->translatedFormat('d F Y');
        }

        // 4. Ambil semua kompetensi keahlian/jurusan yang aktif
        $concentrations = Concentration::where('status', 'active')
            ->orderBy('name', 'asc')
            ->get() ?? collect([]);
        $totalQuota = $concentrations->sum('quota');

        return view('pages.user.dashboard', compact(
            'requirements',
            'totalRequirements',
            'isPhotoUploaded',
            'personalData',
            'parentDataCount',
            'biodataPercentage',
            'biodataStatusText',
            'spmbSteps',
            'topSteps',
            'bottomSteps',
            'currentActiveStepText',
            'daysToAnnouncement',
            'announcementDateText',
            'concentrations',
            'totalQuota'
        ));
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReRegistrationData;
use App\Http\Resources\PintarIntegrationResource;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{
    /**
     * Endpoint untuk mengekspor data pendaftar yang sudah verifikasi daftar ulang
     * ke aplikasi Pintar.
     */
    public function exportToPintar(Request $request)
    {
        // Gunakan relasi latestSelectionResult agar sama dengan ReRegistrationController
        $verifiedStudents = ReRegistrationData::with([
            'registrationData.personalData.parents',
            'registrationData.latestSelectionResult.acceptedConcentration'
        ])
            ->where('verification_status', 'verified')
            ->whereHas('registrationData.latestSelectionResult', function ($query) {
                // Cukup pastikan pada riwayat seleksi terakhirnya, peserta berstatus diterima
                $query->where('status', 'accepted');
            })
            ->get();

        // Validasi jika data kosong
        if ($verifiedStudents->isEmpty()) {
            return response()->json([
                'data' => [],
                'message' => 'Belum ada data pendaftar yang diverifikasi dan diterima.'
            ], 404);
        }

        // --- MENGHITUNG STATISTIK PER KONSENTRASI KEAHLIAN ---
        $statistics = $verifiedStudents->groupBy(function ($item) {
            // Mengelompokkan berdasarkan nama jurusan
            return $item->registrationData->latestSelectionResult->acceptedConcentration->name ?? 'Belum Ditentukan';
        })->map(function ($group) {
            // Menghitung total dan rincian per gender dalam grup tersebut
            return [
                'total' => $group->count(),
                'laki_laki' => $group->filter(function ($item) {
                    $gender = $item->registrationData->personalData->gender ?? '';
                    // Deteksi jika gender berupa Enum atau String
                    $genderValue = is_object($gender) ? ($gender->value ?? '') : $gender;
                    return strtoupper($genderValue) === 'L';
                })->count(),
                'perempuan' => $group->filter(function ($item) {
                    $gender = $item->registrationData->personalData->gender ?? '';
                    $genderValue = is_object($gender) ? ($gender->value ?? '') : $gender;
                    return strtoupper($genderValue) === 'P';
                })->count(),
            ];
        });

        // --- FORMAT PENGIRIMAN JSON ---
        return PintarIntegrationResource::collection($verifiedStudents)->additional([
            'meta' => [
                'status' => 'success',
                'total_data' => $verifiedStudents->count(),
                'statistik_jurusan' => $statistics, // <-- Data ditambahkan di sini
                'timestamp' => now()->toDateTimeString()
            ]
        ]);
    }
}

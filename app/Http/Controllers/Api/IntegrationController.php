<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReRegistrationData;
use App\Models\SelectionResult;
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
        // Ambil batch kelulusan terbaru
        $latestBatch = SelectionResult::max('batch') ?? 0;

        // Mencegah error jika data penjenjangan belum ada sama sekali
        if ($latestBatch == 0) {
            return response()->json([
                'data' => [],
                'message' => 'Belum ada data penjenjangan.'
            ], 404);
        }

        $verifiedStudents = ReRegistrationData::with([
            'registrationData.personalData.parents',
            'registrationData.selectionResult.acceptedConcentration'
        ])
            ->where('verification_status', 'verified')
            ->whereHas('registrationData.selectionResult', function ($query) use ($latestBatch) {
                // Pastikan pendaftar tersebut memang berstatus diterima di batch terakhir
                $query->where('status', 'accepted')
                    ->where('batch', $latestBatch);
            })
            ->get(); // Gunakan get() untuk menarik semua data sekaligus

        // Letakkan di sini, menggantikan return yang sebelumnya
        return PintarIntegrationResource::collection($verifiedStudents)->additional([
            'meta' => [
                'status' => 'success',
                'total_data' => $verifiedStudents->count(),
                'timestamp' => now()->toDateTimeString()
            ]
        ]);
    }
}

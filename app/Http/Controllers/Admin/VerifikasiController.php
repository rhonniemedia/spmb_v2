<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\Peserta;   // ← uncomment & sesuaikan dengan model kamu
// use App\Models\Dokumen;   // ← uncomment & sesuaikan dengan model kamu

class VerifikasiController extends Controller
{
    /**
     * GET /admin/verifikasi
     * Tampilkan tabel daftar semua peserta.
     */
    public function index(Request $request)
    {
        // Contoh query — sesuaikan dengan model & kolom database kamu:
        //
        // $peserta = Peserta::with('dokumen')
        //     ->when($request->filled('status'), fn($q) => $q->where('status_verifikasi', $request->status))
        //     ->when($request->filled('search'), fn($q) => $q->where(function ($q) use ($request) {
        //         $q->where('nama_lengkap', 'like', "%{$request->search}%")
        //           ->orWhere('no_pendaftaran', 'like', "%{$request->search}%");
        //     }))
        //     ->orderByRaw("FIELD(status_verifikasi, 'pending', 'rejected', 'verified')")
        //     ->paginate(15);

        return view('admin.verifikasi.index', [
            // 'peserta' => $peserta,
        ]);
    }

    /**
     * GET /admin/verifikasi/{noPendaftaran}
     * Tampilkan detail peserta + panel keputusan.
     */
    public function show(string $noPendaftaran)
    {
        // Contoh query:
        //
        // $peserta = Peserta::with('dokumen')
        //     ->where('no_pendaftaran', $noPendaftaran)
        //     ->firstOrFail();
        //
        // $prev = Peserta::where('status_verifikasi', 'pending')
        //     ->where('no_pendaftaran', '<', $noPendaftaran)
        //     ->orderByDesc('no_pendaftaran')->first();
        //
        // $next = Peserta::where('status_verifikasi', 'pending')
        //     ->where('no_pendaftaran', '>', $noPendaftaran)
        //     ->orderBy('no_pendaftaran')->first();

        return view('admin.verifikasi.show', [
            // 'peserta' => $peserta,
            // 'prev'    => $prev,
            // 'next'    => $next,
        ]);
    }

    /**
     * POST /admin/verifikasi/{noPendaftaran}/keputusan
     * Simpan keputusan final (approve / hold / reject).
     */
    public function keputusan(Request $request, string $noPendaftaran)
    {
        $request->validate([
            'keputusan'       => ['required', 'in:approve,hold,reject'],
            'catatan'         => ['nullable', 'string', 'max:2000'],
            'nilai_rapor'     => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai_un'        => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        // Contoh simpan:
        //
        // $peserta = Peserta::where('no_pendaftaran', $noPendaftaran)->firstOrFail();
        //
        // $statusMap = [
        //     'approve' => 'verified',
        //     'hold'    => 'pending',
        //     'reject'  => 'rejected',
        // ];
        //
        // $peserta->update([
        //     'status_verifikasi' => $statusMap[$request->keputusan],
        //     'catatan_admin'     => $request->catatan,
        //     'nilai_rapor'       => $request->nilai_rapor,
        //     'nilai_un'          => $request->nilai_un,
        //     'diverifikasi_oleh' => auth()->id(),
        //     'diverifikasi_at'   => now(),
        // ]);

        return redirect()
            ->route('admin.verifikasi.index')
            ->with('toast_success', 'Keputusan untuk ' . $noPendaftaran . ' berhasil disimpan.');
    }

    /**
     * POST /admin/verifikasi/{noPendaftaran}/dokumen/{dokId}
     * Update status satu dokumen via AJAX dari panel keputusan.
     */
    public function updateDokumen(Request $request, string $noPendaftaran, int $dokId)
    {
        $request->validate([
            'status' => ['required', 'in:approve,reject,pending'],
        ]);

        // Contoh simpan:
        //
        // $dok = Dokumen::where('peserta_no', $noPendaftaran)
        //     ->where('id', $dokId)
        //     ->firstOrFail();
        //
        // $dok->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status dokumen diperbarui.',
        ]);
    }
}

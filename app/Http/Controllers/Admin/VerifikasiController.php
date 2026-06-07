<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RegistrationData;
use App\Models\RegistrationDocument;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifikasiController extends Controller
{
    use LogsActivity;

    /**
     * GET /admin/verifikasi
     */
    public function index(Request $request)
    {
        $search      = $request->input('search');
        $filterStatus = $request->input('status');

        $peserta = RegistrationData::with([
            'personalData',
            'admissionPath',
            'choice1',
            'choice2',
            'choice3',
            'registrationDocuments.requirement',
        ])
            ->when($filterStatus, fn($q) => $q->where('verification_status', $filterStatus))
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('registration_number', 'like', "%{$search}%")
                    ->orWhereHas(
                        'personalData',
                        fn($q2) =>
                        $q2->where('full_name', 'like', "%{$search}%")
                    );
            }))
            ->orderByRaw("FIELD(verification_status, 'pending', 'rejected', 'verified')")
            ->paginate(15)
            ->withQueryString();

        return view('pages.admin.verifikasi.index', compact('peserta', 'search', 'filterStatus'));
    }

    /**
     * GET /admin/verifikasi/{id}
     */
    public function show(string $id)
    {
        $peserta = RegistrationData::with([
            'personalData',
            'admissionPath',
            'choice1',
            'choice2',
            'choice3',
            'registrationDocuments.requirement',
            'achievements',
        ])->findOrFail($id);

        $prev = RegistrationData::where('verification_status', 'pending')
            ->where('registration_number', '<', $peserta->registration_number)
            ->orderByDesc('registration_number')->first();

        $next = RegistrationData::where('verification_status', 'pending')
            ->where('registration_number', '>', $peserta->registration_number)
            ->orderBy('registration_number')->first();

        return view('pages.admin.verifikasi.show', compact('peserta', 'prev', 'next'));
    }

    /**
     * POST /admin/verifikasi/{id}/keputusan
     * Keputusan final: approve / reject
     */
    public function keputusan(Request $request, string $id)
    {
        $request->validate([
            'keputusan'    => ['required', 'in:approve,reject'],
            'catatan'      => ['nullable', 'string', 'max:2000'],
            'report_sem_1' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'report_sem_2' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'report_sem_3' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'report_sem_4' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'report_sem_5' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $registration = RegistrationData::with('personalData', 'choice1')->findOrFail($id);

        $statusMap = [
            'approve' => 'verified',
            'reject'  => 'rejected',
        ];

        $registration->update([
            'verification_status' => $statusMap[$request->keputusan],
            'verification_notes'  => $request->catatan,
            'verified_by'         => Auth::id(),
        ]);

        // ── Catat ke activity log ──────────────────────────────────────────
        $this->logActivity(
            action: $request->keputusan === 'approve' ? 'verified' : 'rejected',
            registration: $registration,
            description: $request->keputusan === 'approve'
                ? "Registrasi {$registration->personalData->full_name} diverifikasi"
                : "Registrasi {$registration->personalData->full_name} ditolak",
            context: $request->filled('catatan')
                ? $request->catatan
                : "Pilihan 1: {$registration->choice1?->alias}",
        );

        return redirect()
            ->route('admin.verifikasi.index')
            ->with('toast_success', 'Keputusan berhasil disimpan.');
    }

    /**
     * POST /admin/verifikasi/{id}/dokumen/{docId}
     * Update status satu dokumen (HTMX).
     */
    public function updateDokumen(Request $request, string $id, string $docId)
    {
        $request->validate([
            'status' => ['required', 'in:verified,rejected,pending'],
            'notes'  => ['nullable', 'string', 'max:500'],
        ]);

        $registration = RegistrationData::with('personalData')->findOrFail($id);

        $dokumen = RegistrationDocument::with('requirement')
            ->where('registration_data_id', $id)
            ->where('id', $docId)
            ->firstOrFail();

        $dokumen->update([
            'verification_status' => $request->status,
            'verification_notes'  => $request->notes,
        ]);

        // ── Catat ke activity log (hanya jika bukan pending) ──────────────
        if ($request->status !== 'pending') {
            $this->logActivity(
                action: $request->status === 'verified' ? 'document_verified' : 'document_rejected',
                registration: $registration,
                description: "Dokumen {$dokumen->requirement->name} " .
                    ($request->status === 'verified' ? 'diverifikasi' : 'ditolak'),
                context: $registration->personalData->full_name,
            );
        }

        return response()->json(['status' => 'success', 'message' => 'Status dokumen diperbarui.']);
    }
}

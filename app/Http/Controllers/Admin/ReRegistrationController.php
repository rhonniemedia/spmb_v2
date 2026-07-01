<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReRegistrationData;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ReRegistrationController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        $search        = $request->input('search');
        $filterStatus  = $request->input('filter_status');  // pending|processing|verified|rejected
        $filterBerkas  = $request->input('filter_berkas');  // incomplete|complete

        $stats = $this->getGlobalStats();

        $daftarUlang = ReRegistrationData::with([
            'registrationData.personalData',
            'registrationData.admissionPath',
            'verifiedBy',
        ])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('registrationData', function ($q) use ($search) {
                    $q->where('registration_number', 'like', "%{$search}%")
                        ->orWhereHas('personalData', function ($q2) use ($search) {
                            $q2->where('full_name', 'like', "%{$search}%")
                                ->orWhere('previous_school', 'like', "%{$search}%");
                        });
                });
            })
            ->when($filterStatus, fn($q) => $q->where('verification_status', $filterStatus))
            ->when($filterBerkas, fn($q) => $q->where('data_status', $filterBerkas))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.daftar-ulang.daftar-ulang', array_merge(
            compact('daftarUlang', 'search', 'filterStatus', 'filterBerkas'),
            $stats
        ));
    }

    private function getGlobalStats(): array
    {
        $totalStats    = ReRegistrationData::count();
        $verifiedStats = ReRegistrationData::where('verification_status', 'verified')->count();
        $rejectedStats = ReRegistrationData::where('verification_status', 'rejected')->count();
        $pendingStats  = $totalStats - ($verifiedStats + $rejectedStats);

        return compact('totalStats', 'verifiedStats', 'rejectedStats', 'pendingStats');
    }

    /**
     * Admin / Superadmin / Verifikator: memutuskan hasil verifikasi
     * berkas daftar ulang. verification_status: pending -> verified / rejected.
     * Hanya bisa dilakukan jika berkas peserta berstatus "complete".
     */
    public function decision(Request $request, string $id)
    {
        $reReg = ReRegistrationData::with('registrationData.personalData')->findOrFail($id);

        $validated = $request->validate([
            'verification_status' => ['required', Rule::in(['verified', 'rejected'])],
            'verification_notes'  => ['nullable', 'string', 'max:2000'],
        ], [
            'verification_status.required' => 'Keputusan verifikasi belum dipilih.',
            'verification_status.in'       => 'Keputusan verifikasi tidak valid.',
            'verification_notes.max'       => 'Catatan tidak boleh lebih dari 2000 karakter.',
        ]);

        if ($reReg->data_status !== 'complete') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Berkas daftar ulang peserta ini belum lengkap. Verifikasi tidak dapat dilakukan.',
            ], 422);
        }

        $reReg->verification_status = $validated['verification_status'];
        $reReg->verification_notes  = $validated['verification_notes'] ?? null;
        $reReg->verified_by         = Auth::id();
        $reReg->verified_at         = now();
        $reReg->completed_at        = $validated['verification_status'] === 'verified' ? now() : null;
        $reReg->save();
        $reReg->load('verifiedBy');

        $p = $reReg->registrationData;

        $this->logActivity(
            action: $validated['verification_status'] === 'verified'
                ? 're_registration_verified'
                : 're_registration_rejected',
            registration: $p,
            description: $validated['verification_status'] === 'verified'
                ? "Berkas daftar ulang {$p->personalData->full_name} diverifikasi"
                : "Berkas daftar ulang {$p->personalData->full_name} ditolak",
            context: $validated['verification_notes'] ?? '-',
        );

        return $this->renderPartials($reReg);
    }

    /**
     * Admin / Superadmin: reset progres daftar ulang peserta.
     * data_status -> incomplete, re_registered_at -> null, verified_at -> null,
     * verification_status -> pending. Dipakai ketika berkas perlu diulang.
     */
    public function reset(string $id)
    {
        $reReg = ReRegistrationData::with('registrationData.personalData')->findOrFail($id);

        $reReg->data_status         = 'incomplete';
        $reReg->re_registered_at    = null;
        $reReg->verified_at         = null;
        $reReg->verification_status = 'pending';
        $reReg->verification_notes  = null;
        $reReg->verified_by         = null;
        $reReg->completed_at        = null;
        $reReg->save();
        $reReg->load('verifiedBy');

        $p = $reReg->registrationData;

        $this->logActivity(
            action: 're_registration_reset',
            registration: $p,
            description: "Progres daftar ulang {$p->personalData->full_name} direset oleh admin",
            context: '-',
        );

        return $this->renderPartials($reReg);
    }

    private function renderPartials(ReRegistrationData $reReg): string
    {
        $stats = $this->getGlobalStats();
        $stats['isOob'] = true;

        $rowHtml   = view('pages.admin.daftar-ulang.partials._row-peserta', ['r' => $reReg])->render();
        $statsHtml = view('pages.admin.daftar-ulang.partials._stats-cards', $stats)->render();

        return $rowHtml . $statsHtml;
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ObservationData;
use App\Models\RegistrationData;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ObservationController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        $search = $request->input('search');
        $filterStatus = $request->input('filter_status');

        $stats = $this->getGlobalStats();

        $peserta = RegistrationData::with([
            'personalData',
            'admissionPath',
            'choice1',
            'choice2',
            'choice3',
            'observationData',
            'achievements',
        ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('registration_number', 'like', "%{$search}%")
                        ->orWhereHas('personalData', function ($q2) use ($search) {
                            $q2->where('full_name', 'like', "%{$search}%")
                                ->orWhere('previous_school', 'like', "%{$search}%");
                        });
                });
            })
            ->when($filterStatus, function ($query) use ($filterStatus) {
                if ($filterStatus === 'sudah') {
                    $query->has('observationData');
                } elseif ($filterStatus === 'belum') {
                    $query->doesntHave('observationData');
                }
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.observasi.observasi', array_merge(
            compact('peserta', 'search', 'filterStatus'),
            $stats
        ));
    }

    private function getGlobalStats(): array
    {
        $totalPesertaStats = RegistrationData::count();
        $passedStats = ObservationData::where('observation_status', 'passed')->count();
        $failedStats = ObservationData::where('observation_status', 'failed')->count();
        $pendingStats = $totalPesertaStats - ($passedStats + $failedStats);
        $passedPercentage = $totalPesertaStats > 0
            ? round(($passedStats / $totalPesertaStats) * 100, 1)
            : 0;

        return compact('totalPesertaStats', 'passedStats', 'failedStats', 'pendingStats', 'passedPercentage');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());

        ObservationData::create([
            ...$validated,
            'observer_id' => Auth::id(),
        ]);

        // ── Ambil relasi untuk log & render ───────────────────────────────
        $p = RegistrationData::with([
            'personalData',
            'admissionPath',
            'choice1',
            'choice2',
            'choice3',
            'observationData',
            'achievements',
        ])->findOrFail($validated['registration_id']);

        // ── Catat ke activity log ─────────────────────────────────────────
        $this->logActivity(
            action: $validated['observation_status'] === 'passed'
                ? 'observation_passed'
                : 'observation_failed',
            registration: $p,
            description: $validated['observation_status'] === 'passed'
                ? "{$p->personalData->full_name} lulus observasi"
                : "{$p->personalData->full_name} tidak lulus observasi",
            context: "Skor total: {$validated['total_score']} — {$p->choice1?->alias}",
        );

        $stats = $this->getGlobalStats();
        $stats['isOob'] = true;

        $rowHtml   = view('pages.admin.observasi.partials._row-peserta', compact('p'))->render();
        $statsHtml = view('pages.admin.observasi.partials._stats-cards', $stats)->render();

        return $rowHtml . $statsHtml;
    }

    public function update(Request $request, string $registrationId)
    {
        $validated = $request->validate($this->rules(isUpdate: true), $this->messages());

        $obs = ObservationData::where('registration_id', $registrationId)->firstOrFail();
        $obs->fill($validated);

        if ($obs->isClean()) {
            return response()->json([
                'status'  => 'info',
                'message' => 'Tidak ada data yang diubah.',
            ]);
        }

        $obs->updated_by = Auth::id();
        $obs->save();

        $p = RegistrationData::with([
            'personalData',
            'admissionPath',
            'choice1',
            'choice2',
            'choice3',
            'observationData',
            'achievements',
        ])->findOrFail($registrationId);

        // ── Catat ke activity log ─────────────────────────────────────────
        $this->logActivity(
            action: $validated['observation_status'] === 'passed'
                ? 'observation_passed'
                : 'observation_failed',
            registration: $p,
            description: "Data observasi {$p->personalData->full_name} diperbarui",
            context: "Skor total: {$validated['total_score']} — {$p->choice1?->alias}",
        );

        $stats = $this->getGlobalStats();
        $stats['isOob'] = true;

        $rowHtml   = view('pages.admin.observasi.partials._row-peserta', compact('p'))->render();
        $statsHtml = view('pages.admin.observasi.partials._stats-cards', $stats)->render();

        return $rowHtml . $statsHtml;
    }

    // ── Validation helpers ────────────────────────────────────────────────────

    private function rules(bool $isUpdate = false): array
    {
        $rules = [
            'hearing_check'       => 'required|in:yes,no',
            'vision_check'        => 'required|in:yes,no',
            'physical_activity'   => 'required|in:yes,no',
            'color_blind_check'   => 'required|in:yes,no',
            'tattoo'              => 'required|in:yes,no',
            'tattoo_scar'         => 'required|in:yes,no',
            'piercing'            => 'required|in:yes,no',
            'keloid'              => 'required|in:yes,no',
            'minor_disability'    => 'required|in:yes,no',
            'aid_tool'            => 'required|in:yes,no',
            'physical_score'      => 'nullable|numeric|min:0|max:100',
            'special_trait_score' => 'nullable|numeric|min:0|max:100',
            'achievement_score'   => 'nullable|numeric|min:0|max:100',
            'total_score'         => 'required|numeric|min:0',
            'observation_status'  => 'required|in:pending,passed,failed',
            'observation_notes'   => 'nullable|string|max:2000',
        ];

        if (! $isUpdate) {
            $rules['registration_id'] = 'required|uuid|exists:registration_data,id';
        }

        return $rules;
    }

    private function messages(): array
    {
        return [
            'registration_id.required'    => 'Sistem tidak dapat menemukan ID Pendaftaran peserta.',
            'registration_id.uuid'        => 'Format ID Pendaftaran tidak valid.',
            'registration_id.exists'      => 'Data peserta tidak ditemukan di dalam database.',
            'hearing_check.required'      => 'Pilihan Fungsi Pendengaran belum dipilih.',
            'vision_check.required'       => 'Pilihan Fungsi Penglihatan belum dipilih.',
            'physical_activity.required'  => 'Pilihan Kemampuan Aktivitas Fisik belum dipilih.',
            'color_blind_check.required'  => 'Pilihan Buta Warna belum dipilih.',
            'tattoo.required'             => 'Pilihan Tato belum dipilih.',
            'tattoo_scar.required'        => 'Pilihan Bekas Tato belum dipilih.',
            'piercing.required'           => 'Pilihan Tindik belum dipilih.',
            'keloid.required'             => 'Pilihan Keloid/Bekas Luka belum dipilih.',
            'minor_disability.required'   => 'Pilihan Cacat Fisik Ringan belum dipilih.',
            'aid_tool.required'           => 'Pilihan Alat Bantu Permanen belum dipilih.',
            'observation_status.required' => 'Keputusan Akhir Observasi belum dipilih.',
            'observation_status.in'       => 'Pilihan Keputusan Akhir tidak valid.',
            'observation_notes.max'       => 'Catatan tidak boleh lebih dari 2000 karakter.',
            'in'                          => 'Pilihan tidak valid, mohon segarkan halaman.',
        ];
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ObservationData;
use App\Models\RegistrationData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ObservationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filterStatus = $request->input('filter_status');

        // Panggil fungsi statistik
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

        // Pastikan variabel statistik ikut di-passing (compact) ke view
        return view('pages.admin.observasi.observasi', array_merge(
            compact('peserta', 'search', 'filterStatus'),
            $stats
        ));
    }

    private function getGlobalStats()
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

    /**
     * Simpan data observasi baru (POST /admin/observasi)
     */
    public function store(Request $request)
    {
        $rules = [
            'registration_id'    => 'required|uuid|exists:registration_data,id',

            // Data Kondisi (Yes/No)
            'hearing_check'      => 'required|in:yes,no',
            'vision_check'       => 'required|in:yes,no',
            'physical_activity'  => 'required|in:yes,no',
            'color_blind_check'  => 'required|in:yes,no',
            'tattoo'             => 'required|in:yes,no',
            'tattoo_scar'        => 'required|in:yes,no',
            'piercing'           => 'required|in:yes,no',
            'keloid'             => 'required|in:yes,no',
            'minor_disability'   => 'required|in:yes,no',
            'aid_tool'           => 'required|in:yes,no',

            'physical_score'      => 'nullable|numeric|min:0|max:100',
            'special_trait_score' => 'nullable|numeric|min:0|max:100',
            'achievement_score'   => 'nullable|numeric|min:0|max:100',
            'total_score'         => 'required|numeric|min:0',

            // Status & Catatan
            'observation_status' => 'required|in:pending,passed,failed',
            'observation_notes'  => 'nullable|string|max:2000',
        ];

        $messages = $this->getValidationMessages();
        $messages['registration_id.required'] = 'Sistem tidak dapat menemukan ID Pendaftaran peserta.';
        $messages['registration_id.uuid']     = 'Format ID Pendaftaran tidak valid.';
        $messages['registration_id.exists']   = 'Data peserta tidak ditemukan di dalam database.';

        $validated = $request->validate($rules, $messages);

        ObservationData::create([
            ...$validated,
            'observer_id' => Auth::user()->id,
            'updated_by'  => Auth::user()->id,
        ]);

        // 1. Ambil data peserta terbaru beserta relasinya
        $p = RegistrationData::with([
            'personalData',
            'admissionPath',
            'choice1',
            'choice2',
            'choice3',
            'observationData',
            'achievements'
        ])->findOrFail($request->registration_id);

        // 2. Hitung statistik terbaru dan set penanda OOB (Out-of-Band)
        $stats = $this->getGlobalStats();
        $stats['isOob'] = true; // Ini akan mengaktifkan hx-swap-oob="true" di _stats-cards.blade.php

        // 3. Render kedua komponen HTML
        $rowHtml = view('pages.admin.observasi.partials._row-peserta', compact('p'))->render();
        $statsHtml = view('pages.admin.observasi.partials._stats-cards', $stats)->render();

        // 4. Kembalikan gabungan HTML (HTMX akan memisahkan mereka secara otomatis di frontend!)
        return $rowHtml . $statsHtml;
    }

    /**
     * Update data observasi (PUT /admin/observasi/{registrationId})
     */
    public function update(Request $request, string $registrationId)
    {
        $rules = [
            'physical_score'      => 'nullable|numeric|min:0|max:100',
            'special_trait_score' => 'nullable|numeric|min:0|max:100',
            'achievement_score'   => 'nullable|numeric|min:0|max:100',
            'total_score'         => 'required|numeric|min:0',

            'hearing_check'      => 'required|in:yes,no',
            'vision_check'       => 'required|in:yes,no',
            'physical_activity'  => 'required|in:yes,no',
            'color_blind_check'  => 'required|in:yes,no',
            'tattoo'             => 'required|in:yes,no',
            'tattoo_scar'        => 'required|in:yes,no',
            'piercing'           => 'required|in:yes,no',
            'keloid'             => 'required|in:yes,no',
            'minor_disability'   => 'required|in:yes,no',
            'aid_tool'           => 'required|in:yes,no',

            'observation_status' => 'required|in:pending,passed,failed',
            'observation_notes'  => 'nullable|string|max:2000',
        ];

        $validated = $request->validate($rules, $this->getValidationMessages());

        // 1. Ambil data dari database
        $obs = ObservationData::where('registration_id', $registrationId)->firstOrFail();

        // 2. Suntikkan data baru ke model (TAPI BELUM DISIMPAN KE DB)
        $obs->fill($validated);

        // 3. Cek apakah ada data yang berubah
        if ($obs->isClean()) {
            // Jika isClean() bernilai true, artinya isi form == isi database
            return response()->json([
                'status'  => 'info',
                'message' => 'Tidak ada data yang diubah, sistem mengabaikan pembaruan.'
            ]);
        }

        // 4. Jika ada yang berubah (isDirty), barulah simpan ke database
        $obs->updated_by = Auth::user()->id;
        $obs->save();

        // 1. Ambil data peserta terbaru beserta relasinya
        $p = RegistrationData::with([
            'personalData',
            'admissionPath',
            'choice1',
            'choice2',
            'choice3',
            'observationData',
            'achievements'
        ])->findOrFail($registrationId);

        // 2. Hitung statistik terbaru dan set penanda OOB (Out-of-Band)
        $stats = $this->getGlobalStats();
        $stats['isOob'] = true; // Ini akan mengaktifkan hx-swap-oob="true" di _stats-cards.blade.php

        // 3. Render kedua komponen HTML
        $rowHtml = view('pages.admin.observasi.partials._row-peserta', compact('p'))->render();
        $statsHtml = view('pages.admin.observasi.partials._stats-cards', $stats)->render();

        // 4. Kembalikan gabungan HTML (HTMX akan memisahkan mereka secara otomatis di frontend!)
        return $rowHtml . $statsHtml;
    }

    private function getValidationMessages()
    {
        return [
            'hearing_check.required'    => 'Pilihan Fungsi Pendengaran belum dipilih.',
            'vision_check.required'     => 'Pilihan Fungsi Penglihatan belum dipilih.',
            'physical_activity.required' => 'Pilihan Kemampuan Aktivitas Fisik belum dipilih.',
            'color_blind_check.required' => 'Pilihan Buta Warna belum dipilih.',

            'tattoo.required'           => 'Pilihan Tato belum dipilih.',
            'tattoo_scar.required'      => 'Pilihan Bekas Tato belum dipilih.',
            'piercing.required'         => 'Pilihan Tindik belum dipilih.',
            'keloid.required'           => 'Pilihan Keloid/Bekas Luka belum dipilih.',
            'minor_disability.required' => 'Pilihan Cacat Fisik Ringan belum dipilih.',
            'aid_tool.required'         => 'Pilihan Alat Bantu Permanen belum dipilih.',

            'observation_status.required' => 'Keputusan Akhir Observasi belum dipilih.',
            'observation_status.in'       => 'Pilihan Keputusan Akhir tidak valid.',

            'observation_notes.max'       => 'Catatan observer tidak boleh lebih dari 2000 karakter.',
            'in'                          => 'Pilihan tidak valid, mohon segarkan halaman.',
        ];
    }
}

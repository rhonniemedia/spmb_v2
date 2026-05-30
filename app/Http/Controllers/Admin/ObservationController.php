<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ObservationData;
use App\Models\RegistrationData;
use Illuminate\Http\Request;

class ObservationController extends Controller
{
    public function index()
    {
        $peserta = RegistrationData::with([
            'personalData',
            'admissionPath',
            'choice1',
            'choice2',
            'choice3',
            'observationData',          // Cek apakah data observasi sudah ada
            'achievements',             // Relasi ke registration_achievements
        ])
            ->latest()
            ->paginate(10);

        return view('pages.admin.observasi.observasi', compact('peserta'));
    }

    /**
     * Simpan data observasi baru (POST /admin/observasi)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'registration_id'        => 'required|uuid|exists:registration_data,id',
            'hearing_check'          => 'required|in:yes,no',
            'hearing_score'          => 'required|integer|min:0|max:100',
            'vision_check'           => 'required|in:yes,no',
            'vision_score'           => 'required|integer|min:0|max:100',
            'color_blind_check'      => 'required|in:yes,no',
            'color_blind_score'      => 'required|integer|min:0|max:100',
            'physical_activity'      => 'required|in:yes,no',
            'physical_activity_score' => 'required|integer|min:0|max:100',
            'tattoo'                 => 'required|in:yes,no',
            'tattoo_score'           => 'required|integer|min:0|max:100',
            'tattoo_scar'            => 'required|in:yes,no',
            'tattoo_scar_score'      => 'required|integer|min:0|max:100',
            'piercing'               => 'required|in:yes,no',
            'piercing_score'         => 'required|integer|min:0|max:100',
            'keloid'                 => 'required|in:yes,no',
            'keloid_score'           => 'required|integer|min:0|max:100',
            'minor_disability'       => 'required|in:yes,no',
            'minor_disability_score' => 'required|integer|min:0|max:100',
            'aid_tool'               => 'required|in:yes,no',
            'aid_tool_score'         => 'required|integer|min:0|max:100',
            'total_score'            => 'required|integer|min:0',
            'observation_status'     => 'required|in:pending,passed,failed',
            'observation_notes'      => 'nullable|string|max:2000',
        ]);

        ObservationData::create([
            ...$validated,
            'observer_id' => auth()->id(),
            'updated_by'  => auth()->id(),
        ]);

        return response()->json(['message' => 'Data observasi berhasil disimpan.']);
    }

    /**
     * Update data observasi (PUT /admin/observasi/{registrationId})
     */
    public function update(Request $request, string $registrationId)
    {
        $validated = $request->validate([
            'hearing_check'          => 'required|in:yes,no',
            'hearing_score'          => 'required|integer|min:0|max:100',
            'vision_check'           => 'required|in:yes,no',
            'vision_score'           => 'required|integer|min:0|max:100',
            'color_blind_check'      => 'required|in:yes,no',
            'color_blind_score'      => 'required|integer|min:0|max:100',
            'physical_activity'      => 'required|in:yes,no',
            'physical_activity_score' => 'required|integer|min:0|max:100',
            'tattoo'                 => 'required|in:yes,no',
            'tattoo_score'           => 'required|integer|min:0|max:100',
            'tattoo_scar'            => 'required|in:yes,no',
            'tattoo_scar_score'      => 'required|integer|min:0|max:100',
            'piercing'               => 'required|in:yes,no',
            'piercing_score'         => 'required|integer|min:0|max:100',
            'keloid'                 => 'required|in:yes,no',
            'keloid_score'           => 'required|integer|min:0|max:100',
            'minor_disability'       => 'required|in:yes,no',
            'minor_disability_score' => 'required|integer|min:0|max:100',
            'aid_tool'               => 'required|in:yes,no',
            'aid_tool_score'         => 'required|integer|min:0|max:100',
            'total_score'            => 'required|integer|min:0',
            'observation_status'     => 'required|in:pending,passed,failed',
            'observation_notes'      => 'nullable|string|max:2000',
        ]);

        $obs = ObservationData::where('registration_id', $registrationId)->firstOrFail();
        $obs->update([
            ...$validated,
            'updated_by' => auth()->id(),
        ]);

        return response()->json(['message' => 'Data observasi berhasil diperbarui.']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\AdmissionPath;
use App\Models\Concentration;
use App\Models\PersonalData;
use App\Models\RegistrationData;
use App\Models\RegistrationZonasi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RegistrationDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $personalData = PersonalData::where('user_id', Auth::id())->first();

        // Jika personal data belum final, redirect balik ke biodata
        if (!$personalData || !$personalData->isFinal()) {
            return redirect()->route('biodata')
                ->with('error', 'Lengkapi dan submit data pribadi terlebih dahulu sebelum mendaftar.');
        }

        // Personal data sudah final, load semua data untuk registrasi
        $admissionPaths = AdmissionPath::where('is_active', true)->get();
        $concentrations = Concentration::where('status', 'active')->get();
        $grupTerlarang  = ['tkj', 'tkr', 'tsm'];
        $jurusanList    = [];

        foreach ($concentrations as $c) {
            $jurusanList[$c->id] = [
                'nama'          => $c->name,
                'singkat'       => $c->alias,
                'kode'          => $c->code,
                'kuota'         => $c->quota,
                'alias'         => strtolower($c->alias),
                'grupTerlarang' => in_array(strtolower($c->alias), $grupTerlarang),
            ];
        }

        return view('pages.user.pendaftaran', compact(
            'personalData',
            'admissionPaths',
            'concentrations',
            'jurusanList',
            'grupTerlarang'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    /**
     * Store a newly created resource in storage.
     * Menerima data nilai rapor & TKA (Step Nilai)
     * Return  : JSON {success, step} — Alpine akan step++
     */
    public function saveStep1(Request $request): JsonResponse
    {
        // 1. Aturan Validasi (Key disesuaikan dengan atribut name pada form input)
        $rules = [
            'rapor_sem1' => 'required|numeric|between:0,100',
            'rapor_sem2' => 'required|numeric|between:0,100',
            'rapor_sem3' => 'required|numeric|between:0,100',
            'rapor_sem4' => 'required|numeric|between:0,100',
            'rapor_sem5' => 'required|numeric|between:0,100',
            'tka_mtk'    => 'required|numeric|between:0,100',
            'tka_bind'   => 'required|numeric|between:0,100',
        ];

        // 2. Pesan Validasi Eksplisit (Mengikuti pola PersonalDataController)
        $messages = [
            // --- Nilai Rapor ---
            'rapor_sem1.required' => 'Kolom Rata-rata Rapor Semester 1 wajib diisi.',
            'rapor_sem1.numeric'  => 'Rata-rata Rapor Semester 1 harus berupa angka.',
            'rapor_sem1.between'  => 'Rata-rata Rapor Semester 1 harus berada di antara 0 dan 100.',

            'rapor_sem2.required' => 'Kolom Rata-rata Rapor Semester 2 wajib diisi.',
            'rapor_sem2.numeric'  => 'Rata-rata Rapor Semester 2 harus berupa angka.',
            'rapor_sem2.between'  => 'Rata-rata Rapor Semester 2 harus berada di antara 0 dan 100.',

            'rapor_sem3.required' => 'Kolom Rata-rata Rapor Semester 3 wajib diisi.',
            'rapor_sem3.numeric'  => 'Rata-rata Rapor Semester 3 harus berupa angka.',
            'rapor_sem3.between'  => 'Rata-rata Rapor Semester 3 harus berada di antara 0 dan 100.',

            'rapor_sem4.required' => 'Kolom Rata-rata Rapor Semester 4 wajib diisi.',
            'rapor_sem4.numeric'  => 'Rata-rata Rapor Semester 4 harus berupa angka.',
            'rapor_sem4.between'  => 'Rata-rata Rapor Semester 4 harus berada di antara 0 dan 100.',

            'rapor_sem5.required' => 'Kolom Rata-rata Rapor Semester 5 wajib diisi.',
            'rapor_sem5.numeric'  => 'Rata-rata Rapor Semester 5 harus berupa angka.',
            'rapor_sem5.between'  => 'Rata-rata Rapor Semester 5 harus berada di antara 0 dan 100.',

            // --- Nilai TKA ---
            'tka_mtk.required'    => 'Kolom Nilai TKA Matematika wajib diisi.',
            'tka_mtk.numeric'     => 'Nilai TKA Matematika harus berupa angka.',
            'tka_mtk.between'     => 'Nilai TKA Matematika harus berada di antara 0 dan 100.',

            'tka_bind.required'   => 'Kolom Nilai TKA Bahasa Indonesia wajib diisi.',
            'tka_bind.numeric'    => 'Nilai TKA Bahasa Indonesia harus berupa angka.',
            'tka_bind.between'    => 'Nilai TKA Bahasa Indonesia harus berada di antara 0 dan 100.',
        ];

        // Eksekusi validasi
        $validated = $request->validate($rules, $messages);

        // 3. Ambil personal_data_id dari user yang sedang login
        $personalData = PersonalData::where('user_id', Auth::id())->firstOrFail();

        // 4. Cari record lama atau inisialisasi object baru berdasarkan personal_data_id
        $registration = RegistrationData::firstOrNew(['personal_data_id' => $personalData->id]);
        $registration->personal_data_id = $personalData->id;

        // 5. Mapping & simpan ke kolom database yang sesuai (Sesuai file migrasi)
        $registration->report_sem_1     = $validated['rapor_sem1'];
        $registration->report_sem_2     = $validated['rapor_sem2'];
        $registration->report_sem_3     = $validated['rapor_sem3'];
        $registration->report_sem_4     = $validated['rapor_sem4'];
        $registration->report_sem_5     = $validated['rapor_sem5'];
        $registration->tka_math         = $validated['tka_mtk'];
        $registration->tka_indonesian   = $validated['tka_bind'];

        // 6. Kalkulasi nilai rata-rata untuk backend
        $raporFields = [
            $validated['rapor_sem1'],
            $validated['rapor_sem2'],
            $validated['rapor_sem3'],
            $validated['rapor_sem4'],
            $validated['rapor_sem5']
        ];
        $registration->report_average = round(array_sum($raporFields) / count($raporFields), 2);

        $tkaFields = [
            $validated['tka_mtk'],
            $validated['tka_bind']
        ];
        $registration->tka_average = round(array_sum($tkaFields) / count($tkaFields), 2);

        // 7. Simpan record
        $registration->save();

        // 8. Return response untuk handle step di Alpine.js berikutnya
        return response()->json(['success' => true, 'step' => 2]);
    }

    public function saveStep2(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'jalur_pendaftaran' => 'required|string',
        ], [
            'jalur_pendaftaran.required' => 'Jalur pendaftaran wajib dipilih.',
        ]);

        $slug = $validated['jalur_pendaftaran'];

        // Cari admission path berdasarkan slug dari name
        $path = AdmissionPath::where('is_active', true)->get()
            ->first(fn($p) => Str::slug(str_replace('Jalur ', '', $p->name)) === $slug);

        if (!$path) {
            return response()->json(['success' => false, 'message' => 'Jalur tidak ditemukan.'], 422);
        }

        $personalData = PersonalData::where('user_id', Auth::id())->firstOrFail();
        $registration = RegistrationData::firstOrNew(['personal_data_id' => $personalData->id]);
        $registration->admission_path_id = $path->id;
        $registration->save();

        $nextStep = match ($slug) {
            'zonasi'   => 'zonasi_jarak',
            'prestasi' => 'prestasi',
            'afirmasi' => 'afirmasi_dok',
            default    => 'jurusan',
        };

        return response()->json(['success' => true, 'nextStep' => $nextStep]);
    }

    public function hitungJarak(Request $request)
    {
        $validated = $request->validate([
            'rumah_lat' => 'required|numeric',
            'rumah_lng' => 'required|numeric',
        ]);

        $jarak = $this->kalkulasiHaversine($validated['rumah_lat'], $validated['rumah_lng']);

        return view('pages.user.partials.pendaftaran._hasil_jarak', [
            'jarak' => round($jarak / 1000, 2), // dalam KM
            'jarakMeter' => round($jarak),
        ]);
    }

    // Private helper Haversine
    private function kalkulasiHaversine(float $lat, float $lng): float
    {
        $latSekolah = config('sekolah.lat', -3.45678);
        $lngSekolah = config('sekolah.lng', 102.34567);

        $R    = 6371000;
        $dLat = deg2rad($lat - $latSekolah);
        $dLng = deg2rad($lng - $lngSekolah);
        $a    = sin($dLat / 2) ** 2 +
            cos(deg2rad($latSekolah)) * cos(deg2rad($lat)) * sin($dLng / 2) ** 2;

        return $R * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    public function saveStep3(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'rumah_lat'    => 'required|numeric',
            'rumah_lng'    => 'required|numeric',
            'alamat_jalan' => 'nullable|string',
            'kelurahan'    => 'nullable|string',
            'kecamatan'    => 'nullable|string',
            'kota'         => 'nullable|string',
        ]);

        // 1. Hitung jarak terlebih dahulu
        $jarak = $this->kalkulasiHaversine(
            $validated['rumah_lat'],
            $validated['rumah_lng']
        );

        // 2. Validasi batas maksimal 2 km (2000 meter)
        if ($jarak > 2000) {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, koordinat rumah Anda berada di luar batas aman zonasi (Maksimal 2 km).'
            ], 422);
        }

        $personalData = PersonalData::where('user_id', Auth::id())->firstOrFail();
        $registration = RegistrationData::firstOrNew(['personal_data_id' => $personalData->id]);
        $registration->save();

        RegistrationZonasi::updateOrCreate(
            ['registration_data_id' => $registration->id],
            [
                'house_latitude'             => $validated['rumah_lat'],
                'house_longitude'            => $validated['rumah_lng'],
                'calculated_distance_meters' => $jarak,
                'address_street'             => $validated['alamat_jalan'],
                'village'                    => $validated['kelurahan'],
                'subdistrict'                => $validated['kecamatan'],
                'city'                       => $validated['kota'],
            ]
        );

        return response()->json(['success' => true, 'nextStep' => 'jurusan']);
    }

    public function saveStep4(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'pilihan_jurusan_1' => 'required|uuid|exists:concentrations,id',
            'pilihan_jurusan_2' => 'required|uuid|exists:concentrations,id|different:pilihan_jurusan_1',
            'pilihan_jurusan_3' => 'required|uuid|exists:concentrations,id|different:pilihan_jurusan_1|different:pilihan_jurusan_2',
        ], [
            'pilihan_jurusan_1.required' => 'Pilihan jurusan pertama wajib dipilih.',
            'pilihan_jurusan_2.required' => 'Pilihan jurusan kedua wajib dipilih.',
            'pilihan_jurusan_2.different' => 'Pilihan jurusan kedua harus berbeda dengan pilihan pertama.',
            'pilihan_jurusan_3.required' => 'Pilihan jurusan ketiga wajib dipilih.',
            'pilihan_jurusan_3.different' => 'Pilihan jurusan ketiga harus berbeda dengan pilihan sebelumnya.',
        ]);

        $personalData = PersonalData::where('user_id', Auth::id())->firstOrFail();
        $registration = RegistrationData::firstOrNew(['personal_data_id' => $personalData->id]);

        $registration->choice_1 = $validated['pilihan_jurusan_1'];
        $registration->choice_2 = $validated['pilihan_jurusan_2'];
        $registration->choice_3 = $validated['pilihan_jurusan_3'];
        $registration->save();

        return response()->json(['success' => true, 'nextStep' => 'konfirmasi']);
    }

    /**
     * Display the specified resource.
     */
    public function show(RegistrationData $registrationData)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RegistrationData $registrationData)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RegistrationData $registrationData)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RegistrationData $registrationData)
    {
        //
    }
}

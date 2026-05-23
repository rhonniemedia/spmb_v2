<?php

namespace App\Http\Controllers;

use App\Models\AdmissionPath;
use App\Models\Concentration;
use App\Models\PersonalData;
use App\Models\RegistrationAchievement;
use App\Models\RegistrationAffirmation;
use App\Models\RegistrationData;
use App\Models\RegistrationZone;
use App\Notifications\RegistrationFinalizedNotification;
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

        $admissionPaths = AdmissionPath::where('is_active', true)->get();
        $concentrations = Concentration::where('status', 'active')->get();
        $hanyaPilihanSatu = ['tkj', 'tkr', 'tsm'];
        $jurusanList = [];

        foreach ($concentrations as $c) {
            $aliasLower = strtolower($c->alias);
            $jurusanList[$c->id] = [
                'nama'            => $c->name,
                'singkat'         => $c->alias,
                'kode'            => $c->code,
                'kuota'           => $c->quota,
                'alias'           => $aliasLower,
                'restrict_choice' => in_array($aliasLower, $hanyaPilihanSatu),
            ];
        }

        $registration            = RegistrationData::where('personal_data_id', $personalData->id)->first();
        $registrationData        = $registration;
        $registrationZone        = $registration?->zonasi;
        $registrationAchievement = $registration?->prestasi;
        $registrationAffirmasi   = $registration?->afirmasi;

        // ── Jika sudah pernah submit, arahkan ke halaman resume ──
        if ($registration && $registration->submitted_at !== null) {
            return view('pages.user.resume-pendaftaran', compact(
                'personalData',
                'registration',
                'registrationData',
                'registrationZone',
                'registrationAchievement',
                'registrationAffirmasi',
                'admissionPaths',
                'concentrations',
                'jurusanList',
                'hanyaPilihanSatu',
            ));
        }

        // ── Belum submit, hitung initial step dan tampilkan form ──
        $initialStep = 1;
        if ($registration) {
            if ($registration->report_sem_1) {
                $initialStep = 2;

                if ($registration->admission_path_id) {
                    $admissionPath = $admissionPaths->find($registration->admission_path_id);
                    $jalurSlug = $admissionPath
                        ? Str::slug(str_replace('Jalur ', '', $admissionPath->name))
                        : null;

                    if ($jalurSlug === 'zonasi' && $registrationZone) {
                        $initialStep = 4;
                    } elseif ($jalurSlug === 'prestasi' && $registrationAchievement) {
                        $initialStep = 4;
                    } elseif ($jalurSlug === 'afirmasi' && $registrationAffirmasi) {
                        $initialStep = 4;
                    } elseif (in_array($jalurSlug, ['zonasi', 'prestasi', 'afirmasi'])) {
                        $initialStep = 3;
                    } else {
                        $initialStep = 3;
                    }

                    if ($registration->choice_1) {
                        $initialStep = 99;
                    }
                }
            }
        }

        return view('pages.user.pendaftaran', compact(
            'personalData',
            'admissionPaths',
            'concentrations',
            'jurusanList',
            'hanyaPilihanSatu',
            'registration',
            'registrationData',
            'registrationZone',
            'registrationAchievement',
            'registrationAffirmasi',
            'initialStep'
        ));
    }

    public function saveStepNilai(Request $request): JsonResponse
    {
        // 1. Aturan Validasi (Key disesuaikan dengan atribut name pada form input)
        $rules = [
            'rapor_sem1' => 'required|numeric|between:0,100',
            'rapor_sem2' => 'required|numeric|between:0,100',
            'rapor_sem3' => 'required|numeric|between:0,100',
            'rapor_sem4' => 'required|numeric|between:0,100',
            'rapor_sem5' => 'required|numeric|between:0,100',
            'tka_mtk'    => 'nullable|numeric|between:0,100',
            'tka_bind'   => 'nullable|numeric|between:0,100',
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
            'tka_mtk.numeric'     => 'Nilai TKA Matematika harus berupa angka.',
            'tka_mtk.between'     => 'Nilai TKA Matematika harus berada di antara 0 dan 100.',

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

    public function saveStepJalur(Request $request): JsonResponse
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
            'zonasi'   => 'zonasi',
            'prestasi' => 'prestasi',
            'afirmasi' => 'afirmasi',
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

    public function saveStepZonasi(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'rumah_lat'    => 'required|numeric',
            'rumah_lng'    => 'required|numeric',
            'address' => 'nullable|string',
            'village'    => 'nullable|string',
            'district'    => 'nullable|string',
            'regency'         => 'nullable|string',
        ]);

        // 1. Hitung jarak terlebih dahulu
        $jarak = $this->kalkulasiHaversine(
            $validated['rumah_lat'],
            $validated['rumah_lng']
        );

        // 2. Validasi batas maksimal 1 km (1000 meter)
        if ($jarak > 1000) {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, koordinat rumah Anda berada di luar batas aman zonasi (Maksimal 1 km).'
            ], 422);
        }

        $personalData = PersonalData::where('user_id', Auth::id())->firstOrFail();
        $registration = RegistrationData::firstOrNew(['personal_data_id' => $personalData->id]);
        $registration->save();

        RegistrationZone::updateOrCreate(
            ['registration_data_id' => $registration->id],
            [
                'house_latitude'             => $validated['rumah_lat'],
                'house_longitude'            => $validated['rumah_lng'],
                'calculated_distance_meters' => $jarak,
                'address_street'             => $validated['address'],
                'village'                    => $validated['village'],
                'subdistrict'                => $validated['district'],
                'city'                       => $validated['regency'],
            ]
        );

        return response()->json(['success' => true, 'nextStep' => 'jurusan']);
    }

    public function saveStepJurusan(Request $request): JsonResponse
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

    public function summary()
    {
        $personalData = PersonalData::where('user_id', Auth::id())->firstOrFail();
        $registration = RegistrationData::where('personal_data_id', $personalData->id)->first();

        return view('pages.user.partials.pendaftaran._summary_content', compact(
            'personalData',
            'registration'
        ));
    }

    public function saveStepPrestasi(Request $request): JsonResponse
    {
        // ── 1. Validasi Jenis (selalu wajib) ─────────────────────────────────
        // Ditambahkan pilihan 'peringkat'
        $rules = [
            'prestasi_jenis' => 'required|in:kejuaraan,tahfiz,kepemimpinan,peringkat',
        ];

        $messages = [
            'prestasi_jenis.required' => 'Jenis prestasi wajib dipilih.',
            'prestasi_jenis.in'      => 'Jenis prestasi yang dipilih tidak valid.',
        ];

        // ── 2. Validasi Conditional Berdasarkan Jenis ─────────────────────────
        $jenis = $request->input('prestasi_jenis');

        if ($jenis === 'kejuaraan') {
            $rules['prestasi_tingkat'] = 'required|in:kabupaten,provinsi,nasional,internasional';
            $messages['prestasi_tingkat.required'] = 'Tingkat kejuaraan wajib dipilih.';
            $messages['prestasi_tingkat.in']       = 'Tingkat kejuaraan yang dipilih tidak valid.';

            // Validasi kurasi dihapus sesuai request UI baru
        }

        if ($jenis === 'tahfiz') {
            // Jalur tahfiz sekarang langsung valid tanpa kurasi (hanya sertifikat resmi)
        }

        if ($jenis === 'kepemimpinan') {
            $rules['prestasi_jabatan'] = 'required|in:ketua_osis,ketua_osim,ketua_mpk,ketua_pramuka,ketua_ambalan,ketua_bes';
            $messages['prestasi_jabatan.required'] = 'Jabatan organisasi wajib dipilih untuk jalur kepemimpinan.';
            $messages['prestasi_jabatan.in']       = 'Jabatan yang dipilih tidak valid.';
        }

        if ($jenis === 'peringkat') {
            // Validasi bahwa payload yang masuk harus berformat JSON
            $rules['prestasi_peringkat'] = [
                'required',
                'json',
                function ($attribute, $value, $fail) {
                    $data = json_decode($value, true);
                    // Pastikan minimal ada 1 semester yang diisi (nilainya tidak kosong '')
                    $filtered = array_filter($data, function ($val) {
                        return $val !== '';
                    });

                    if (empty($filtered)) {
                        $fail('Minimal pilih satu peringkat semester yang ingin dilaporkan.');
                    }
                }
            ];
            $messages['prestasi_peringkat.required'] = 'Data peringkat per semester wajib diisi.';
            $messages['prestasi_peringkat.json']     = 'Format data peringkat tidak valid.';
        }

        // ── 3. Eksekusi Validasi ──────────────────────────────────────────────
        $validated = $request->validate($rules, $messages);

        // ── 4. Ambil Registration terkait user yang sedang login ──────────────
        $personalData = PersonalData::where('user_id', Auth::id())->firstOrFail();
        $registration = RegistrationData::firstOrNew(['personal_data_id' => $personalData->id]);
        $registration->save(); // pastikan record ada sebelum relasi disimpan

        // ── 5. Upsert ke tabel registration_achievements ─────────────────────
        // Decode data peringkat dari JSON string ke Array PHP sebelum disimpan
        $ranksArray = isset($validated['prestasi_peringkat'])
            ? json_decode($validated['prestasi_peringkat'], true)
            : null;

        RegistrationAchievement::updateOrCreate(
            ['registration_data_id' => $registration->id],
            [
                'achievement_type'    => $validated['prestasi_jenis'],
                'level'               => $validated['prestasi_tingkat'] ?? null,
                'leadership_position' => $validated['prestasi_jabatan'] ?? null,
                'class_ranks'         => $ranksArray, // Masuk ke kolom json database
                // 'curation_type' dihapus karena kolomnya sudah tidak ada di migrasi baru
            ]
        );

        // ── 6. Return — Alpine navigasi ke step jurusan ───────────────────────
        return response()->json(['success' => true, 'nextStep' => 'jurusan']);
    }

    public function saveStepAfirmasi(Request $request): JsonResponse
    {
        // ── 1. Aturan Validasi (Sesuai dengan atribut name di form input) ──
        $rules = [
            'nomor_sktm'   => 'required|string|max:100',
            'pakai_kartu'  => 'required|boolean',
        ];

        $messages = [
            'nomor_sktm.required' => 'Nomor Surat Keterangan Tidak Mampu (SKTM) wajib diisi.',
            'nomor_sktm.string'   => 'Format nomor SKTM tidak valid.',
            'nomor_sktm.max'      => 'Nomor SKTM terlalu panjang (maksimal 100 karakter).',
        ];

        // ── 2. Validasi Bersyarat (Conditional Validation) ──
        // Jika toggle 'pakai_kartu' bernilai true (1), jenis dan nomor kartu wajib diisi
        if ($request->input('pakai_kartu') == 1) {
            $rules['jenis_kartu'] = 'required|in:pkh,kip,kps,dtks,lain';
            $rules['nomor_kartu'] = 'required|string|max:100';

            $messages['jenis_kartu.required'] = 'Jenis kartu bantuan sosial wajib dipilih.';
            $messages['jenis_kartu.in']       = 'Jenis kartu yang dipilih tidak valid.';
            $messages['nomor_kartu.required'] = 'Nomor kartu bantuan sosial wajib diisi.';
        }

        // Eksekusi validasi
        $validated = $request->validate($rules, $messages);

        // ── 3. Ambil Personal Data & Registration Data User login ──
        $personalData = PersonalData::where('user_id', Auth::id())->firstOrFail();
        $registration = RegistrationData::firstOrNew(['personal_data_id' => $personalData->id]);
        $registration->save(); // Memastikan ID registrasi terbentuk

        // ── 4. Upsert ke Tabel Relasi Dokumen Afirmasi ──
        RegistrationAffirmation::updateOrCreate(
            ['registration_data_id' => $registration->id],
            [
                'sktm_number'   => $validated['nomor_sktm'],
                'has_social_card'      => $validated['pakai_kartu'],
                'card_type'     => $validated['pakai_kartu'] ? $validated['jenis_kartu'] : null,
                'card_number'   => $validated['pakai_kartu'] ? $validated['nomor_kartu'] : null,
            ]
        );

        // ── 5. Return JSON Response — Mengarahkan Alpine.js ke step 'jurusan' ──
        return response()->json(['success' => true, 'nextStep' => 'jurusan']);
    }

    public function submit(): JsonResponse
    {
        $personalData = PersonalData::where('user_id', Auth::id())->firstOrFail();
        $registration = RegistrationData::where('personal_data_id', $personalData->id)
            ->with(['admissionPath', 'choice1', 'choice2', 'choice3'])
            ->firstOrFail();

        if ($registration->submitted_at !== null) {
            return response()->json([
                'success' => false,
                'message' => 'Formulir pendaftaran Anda sudah dikirim sebelumnya dan status data telah dikunci.'
            ], 422);
        }

        // ── GENERATE NOMOR URUT PENDAFTARAN OTOMATIS ──
        if (!$registration->registration_number) {
            $tahunSekarang = date('Y');

            // Hitung ada berapa banyak pendaftar yang sudah mendapatkan nomor di tahun ini
            $jumlahPendaftarTahunIni = RegistrationData::whereYear('submitted_at', $tahunSekarang)
                ->whereNotNull('registration_number')
                ->count();

            // Urutan pendaftar saat ini adalah jumlah data + 1
            $nomorUrutNext = $jumlahPendaftarTahunIni + 1;

            // Gabungkan format: SPMB - 2026 - 0001
            $registration->registration_number = 'SPMB-' . $tahunSekarang . '-' . str_pad($nomorUrutNext, 4, '0', STR_PAD_LEFT);
        }

        $registration->submitted_at        = now();
        $registration->verification_status = 'pending';
        $registration->save();

                // ─── AMBIL USER & KIRIM NOTIFIKASI SECARA INSTAN ───
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user) {
            //  Benar (Kirimkan objek $registration ke dalamnya)
            $user->notify(new RegistrationFinalizedNotification($registration));
        }

        return response()->json([
            'success'          => true,
            'nama'             => $personalData->full_name,
            'noPeserta'        => $registration->registration_number, // Membaca kolom terbaru dari database
            'jalur'            => $registration->admissionPath?->name ?? '—',
            'pilihan1'         => $registration->choice1?->name ?? '—',
            'pilihan2'         => $registration->choice2?->name ?? '—',
            'pilihan3'         => $registration->choice3?->name ?? '—',
            'submittedAt'      => $registration->submitted_at->format('d M Y'),
        ])->header('HX-Trigger', json_encode([
            'refresh-notifications' => true,
            'registration-submitted' => true,  // sesuaikan nama event-nya
        ]));
    }

    public function successScreen()
    {
        $personalData = PersonalData::where('user_id', Auth::id())->firstOrFail();
        $registration = RegistrationData::where('personal_data_id', $personalData->id)->firstOrFail();

        // Proteksi: Jika pendaftar nakal mencoba bypass URL sukses langsung sebelum submit
        if ($registration->submitted_at === null) {
            return redirect()->route('registration.index')
                ->with('error', 'Silakan selesaikan dan kirim formulir pendaftaran Anda terlebih dahulu.');
        }

        // Return view diletakkan secara bersih lewat request halaman (GET)
        return view('pages.user.partials.pendaftaran._success_screen', compact('personalData', 'registration'));
    }
}

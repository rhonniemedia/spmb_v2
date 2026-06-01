<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionPath;
use App\Models\Concentration;
use App\Models\PersonalData;
use App\Models\RegistrationAchievement;
use App\Models\RegistrationData;
use App\Models\RegistrationDocument;
use App\Models\Requirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RegistrationDataController extends Controller
{
    /**
     * Tampilkan halaman daftar peserta.
     */
    public function index(Request $request)
    {
        // 1. Tangkap kata kunci pencarian dari HTMX
        $search = $request->input('search');

        // 2. Hitung Statistik untuk Kartu (Berdasarkan Status Verifikasi)
        $totalPesertaStats = RegistrationData::count();
        $passedStats       = RegistrationData::where('verification_status', 'verified')->count();
        $failedStats       = RegistrationData::where('verification_status', 'rejected')->count();
        $pendingStats      = RegistrationData::where('verification_status', 'pending')->count();

        $passedPercentage = $totalPesertaStats > 0
            ? round(($passedStats / $totalPesertaStats) * 100, 1)
            : 0;

        // 3. Query Data Tabel
        $peserta = RegistrationData::with([
            'personalData',
            'choice1',
            'choice2',
            'choice3'
        ])
            ->when($search, function ($query) use ($search) {
                // Gunakan kurung tambahan (nested where) agar aman
                $query->where(function ($q) use ($search) {
                    $q->where('registration_number', 'like', "%{$search}%")
                        ->orWhereHas('personalData', function ($q2) use ($search) {
                            $q2->where('full_name', 'like', "%{$search}%")
                                ->orWhere('previous_school', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // 4. Kirim Data + Statistik ke View
        return view('pages.admin.peserta.data-peserta', compact(
            'peserta',
            'search',
            'totalPesertaStats',
            'passedStats',
            'failedStats',
            'pendingStats',
            'passedPercentage'
        ));
    }

    /**
     * Muat partial blade per step ke dalam #modal-body via HTMX.
     *
     * Route: GET /admin/pendaftar/create?step={1..6}
     * Nama : admin.pendaftar.create
     *
     * Semua data form dikirim via query-string (HTMX GET + hx-include).
     * Controller hanya memvalidasi step lalu merender partial yang sesuai.
     */
    public function create(Request $request)
    {
        $step = (int) $request->query('step', 1);

        // 1. Ambil Data Master dari Database
        $jurusanList = Concentration::where('status', 'active')->get();
        $jalurList   = AdmissionPath::where('is_active', true)->get();
        $berkasList  = Requirement::where('category', 'dokumen')->get();

        // 2. Siapkan array dinamis untuk Hidden Inputs agar tidak repot di Blade
        $hiddenFields = [
            'reg_number',
            'full_name',
            'nickname',
            'gender',
            'nisn',
            'phone',
            'school_origin',
            'r1',
            'r2',
            'r3',
            'r4',
            'r5',
            'rata_rapor',
            'tka_mtk',
            'tka_indo',
            'rata_tka',
            'jalur',
            'jalur_name',
            'jenis_prestasi',
            'tingkat_kejuaraan',
            'juz_hafalan',
            'jabatan_org',
            'peringkat_sem_1',
            'peringkat_sem_2',
            'peringkat_sem_3',
            'peringkat_sem_4',
            'peringkat_sem_5',
            'pil1',
            'pil2',
            'pil3'
        ];

        // Otomatis daftarkan slug berkas ke hidden input (contoh: berkas_akta_kelahiran)
        foreach ($berkasList as $berkas) {
            $hiddenFields[] = 'berkas_' . str_replace('-', '_', $berkas->slug);
        }

        $regNumber = $request->query('reg_number')
            ?? 'REG-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

        // Siapkan URL setiap step untuk navigasi Lanjut/Kembali di Blade
        $stepUrls = [];
        for ($i = 1; $i <= 6; $i++) {
            $stepUrls[$i] = route('admin.pendaftar.create', ['step' => $i]);
        }

        // Diperlukan oleh step-6-konfirmasi
        $isEdit  = false;
        $postUrl = route('admin.pendaftar.store');

        return view("pages.admin.peserta.partials.step-{$step}-" . match ($step) {
            1 => 'biodata',
            2 => 'akademik',
            3 => 'jalur',
            4 => 'jurusan',
            5 => 'berkas',
            6 => 'konfirmasi',
            default => abort(404),
        }, compact('jurusanList', 'jalurList', 'berkasList', 'hiddenFields', 'regNumber', 'stepUrls', 'isEdit', 'postUrl'));
    }
    /**
     * Generate nomor registrasi dengan counter global (semua prefix berbagi urutan yang sama).
     * Wajib dipanggil di dalam DB::beginTransaction() agar lockForUpdate() efektif.
     *
     * Contoh urutan tersimpan di DB:
     *   REG-2026-1234-0001  <- pendaftar ke-1
     *   REG-2027-1222-0002  <- pendaftar ke-2
     *   REG-2026-8000-0003  <- pendaftar ke-3
     *   dst.
     */
    private function generateRegistrationNumber(string $baseReg): string
    {
        $baseReg = rtrim($baseReg, '-');

        // Ambil nomor urut terbesar dari SEMUA data (counter global, bukan per-prefix)
        $lastRecord = RegistrationData::where('registration_number', 'REGEXP', '-[0-9]{4}$')
            ->lockForUpdate()
            ->orderByRaw('CAST(SUBSTRING_INDEX(registration_number, "-", -1) AS UNSIGNED) DESC')
            ->first();

        if ($lastRecord) {
            $parts      = explode('-', $lastRecord->registration_number);
            $lastNumber = (int) end($parts);
            $sequence   = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $sequence = '0001';
        }

        return $baseReg . '-' . $sequence;
    }

    /**
     * Simpan data pendaftar ke database.
     *
     * Route: POST /admin/pendaftar
     * Nama : admin.pendaftar.store
     */
    public function store(Request $request)
    {
        // 0. VALIDASI AWAL: pastikan reg_number diisi sebelum masuk transaksi
        $baseReg = $request->input('reg_number');

        if (empty(trim($baseReg ?? ''))) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor registrasi wajib diisi.',
                'errors'  => ['reg_number' => ['Nomor registrasi wajib diisi.']],
            ], 422);
        }

        // 1. Definisikan aturan validasi
        $rules = [
            'reg_number'    => ['required', 'string', 'max:25'],
            'full_name'     => ['required', 'string', 'max:100'],
            'nickname'      => ['required', 'string', 'max:50'],
            'gender'        => ['required', 'in:L,P'],
            'nisn'          => ['required', 'digits:10'],
            'phone'         => ['required', 'string', 'min:10', 'max:15', 'regex:/^[0-9\-\+\s]+$/'],
            'school_origin' => ['required', 'string', 'max:150'],
            'jalur'         => ['required', 'exists:admission_paths,id'],
            'pil1'          => ['required', 'exists:concentrations,id'],
            'pil2'          => ['nullable', 'exists:concentrations,id'],
            'pil3'          => ['nullable', 'exists:concentrations,id'],
            'r1'            => ['nullable', 'numeric'],
            'r2'            => ['nullable', 'numeric'],
            'r3'            => ['nullable', 'numeric'],
            'r4'            => ['nullable', 'numeric'],
            'r5'            => ['nullable', 'numeric'],
            'rata_rapor'    => ['nullable', 'numeric', 'between:0,100'],
            'tka_mtk'       => ['nullable', 'numeric'],
            'tka_indo'      => ['nullable', 'numeric'],
            'rata_tka'      => ['nullable', 'numeric', 'between:0,100'],
            'admin_verify1' => ['required', 'in:1'],
            'admin_verify2' => ['required', 'in:1'],
        ];

        // 2. Pesan Error Kustom
        $messages = [
            'nisn.digits'            => 'NISN harus terdiri dari tepat 10 digit angka.',
            'phone.regex'            => 'Format nomor WhatsApp tidak valid (hanya angka, +, -, spasi).',
            'gender.in'              => 'Jenis kelamin harus L (Laki-laki) atau P (Perempuan).',
            'admin_verify1.required' => 'Pernyataan verifikasi berkas 1 wajib dicentang.',
            'admin_verify2.required' => 'Pernyataan verifikasi berkas 2 wajib dicentang.',
            'between'                => 'Nilai :attribute harus antara 0 sampai 100.',
            'jalur.exists'           => 'Jalur pendaftaran tidak valid.',
            'pil1.exists'            => 'Pilihan jurusan utama tidak valid.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal. Silakan periksa kembali isian form Anda.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        // 3. Transaksi Database
        DB::beginTransaction();

        try {
            // [0] Generate nomor registrasi final di dalam transaksi
            // agar lockForUpdate() mencegah race condition (duplikat nomor)
            $finalRegNumber = $this->generateRegistrationNumber($validated['reg_number']);

            // [A] Simpan PersonalData
            $personalData = PersonalData::create([
                'full_name'       => $validated['full_name'],
                'nick_name'       => $validated['nickname'],
                'gender'          => $validated['gender'],
                'nisn'            => $validated['nisn'],
                'phone_number'    => $validated['phone'],
                'previous_school' => $validated['school_origin'],
                'profile_status'  => 'final',
            ]);

            // [B] Simpan RegistrationData
            $registrationData = RegistrationData::create([
                'personal_data_id'    => $personalData->id,
                'admission_path_id'   => $validated['jalur'],
                'registration_number' => $finalRegNumber,
                'choice_1'            => $validated['pil1'],
                'choice_2'            => $validated['pil2'] ?? null,
                'choice_3'            => $validated['pil3'] ?? null,
                'report_sem_1'        => $validated['r1'] ?? null,
                'report_sem_2'        => $validated['r2'] ?? null,
                'report_sem_3'        => $validated['r3'] ?? null,
                'report_sem_4'        => $validated['r4'] ?? null,
                'report_sem_5'        => $validated['r5'] ?? null,
                'report_average'      => $validated['rata_rapor'] ?? null,
                'tka_math'            => $validated['tka_mtk'] ?? null,
                'tka_indonesian'      => $validated['tka_indo'] ?? null,
                'tka_average'         => $validated['rata_tka'] ?? null,

                'verification_status' => 'verified',
                'verified_by'         => Auth::id(),
                'created_by'          => Auth::id(),
                'submitted_at'        => now(),
            ]);

            // [C] Simpan Prestasi
            $isPrestasi = str_contains(strtolower($request->jalur_name ?? ''), 'prestasi');
            if ($isPrestasi && $request->jenis_prestasi) {
                RegistrationAchievement::create([
                    'registration_data_id' => $registrationData->id,
                    'achievement_type'     => $request->jenis_prestasi,
                    'level'                => $request->tingkat_kejuaraan ?? null,
                    'leadership_position'  => $request->jabatan_org ?? null,
                    'class_ranks'          => $request->jenis_prestasi === 'peringkat' ? [
                        '1' => $request->peringkat_sem_1 ?? '',
                        '2' => $request->peringkat_sem_2 ?? '',
                        '3' => $request->peringkat_sem_3 ?? '',
                        '4' => $request->peringkat_sem_4 ?? '',
                        '5' => $request->peringkat_sem_5 ?? '',
                    ] : null,
                ]);
            }

            // [D] Simpan Dokumen
            $requirements = Requirement::where('category', 'dokumen')->get();
            foreach ($requirements as $req) {
                $field = 'berkas_' . str_replace('-', '_', $req->slug);
                if ($request->filled($field)) {
                    RegistrationDocument::create([
                        'registration_data_id' => $registrationData->id,
                        'requirement_id'       => $req->id,
                        'file_path'            => 'Diserahkan fisik di loket',
                        'verification_status'  => 'verified',
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Data pendaftar berhasil disimpan!',
                // Tambahkan array data ini
                'data'     => [
                    'id'         => $registrationData->id,
                    'full_name'  => $personalData->full_name,
                    'reg_number' => $registrationData->registration_number
                ],
                'redirect' => route('admin.pendaftar.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error Entri Pendaftar: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function edit(Request $request, $id)
    {
        $step = (int) $request->query('step', 1);

        $jurusanList = Concentration::where('status', 'active')->get();
        $jalurList   = AdmissionPath::where('is_active', true)->get();
        $berkasList  = Requirement::where('category', 'dokumen')->get();

        $registration = RegistrationData::with(['personalData', 'admissionPath', 'achievements', 'documents'])->findOrFail($id);

        // Jika ini adalah tarikan pertama (bukan hasil klik Lanjut/Kembali HTMX),
        // suntikkan data dari database ke dalam Request agar Blade Alpine dapat mendeteksinya.
        if (! $request->has('full_name')) {
            // Potong 5 karakter terakhir (tanda strip dan 4 digit angka)
            $prefixOnly = substr($registration->registration_number, 0, -5);

            $dbData = [
                'reg_number'    => $prefixOnly, // Tampilkan prefix saja
                'full_name'     => $registration->personalData->full_name ?? '',
                'nickname'      => $registration->personalData->nick_name ?? '',
                'gender'        => $registration->personalData->gender ?? '',
                'nisn'          => $registration->personalData->nisn ?? '',
                'phone'         => $registration->personalData->phone_number ?? '',
                'school_origin' => $registration->personalData->previous_school ?? '',

                'r1'            => $registration->report_sem_1,
                'r2'            => $registration->report_sem_2,
                'r3'            => $registration->report_sem_3,
                'r4'            => $registration->report_sem_4,
                'r5'            => $registration->report_sem_5,
                'rata_rapor'    => $registration->report_average,
                'tka_mtk'       => $registration->tka_math,
                'tka_indo'      => $registration->tka_indonesian,
                'rata_tka'      => $registration->tka_average,

                'jalur'         => $registration->admission_path_id,
                'jalur_name'    => $registration->admissionPath->name ?? '',
                'pil1'          => $registration->choice_1,
                'pil2'          => $registration->choice_2,
                'pil3'          => $registration->choice_3,
            ];

            // Suntik Data Prestasi
            $achievement = $registration->achievements->first();
            if ($achievement) {
                $dbData['jenis_prestasi']    = $achievement->achievement_type;
                $dbData['tingkat_kejuaraan'] = $achievement->level;
                $dbData['jabatan_org']       = $achievement->leadership_position;
                if ($achievement->class_ranks) {
                    $ranks = is_array($achievement->class_ranks)
                        ? $achievement->class_ranks
                        : json_decode($achievement->class_ranks, true);
                    $dbData['peringkat_sem_1'] = $ranks['1'] ?? '';
                    $dbData['peringkat_sem_2'] = $ranks['2'] ?? '';
                    $dbData['peringkat_sem_3'] = $ranks['3'] ?? '';
                    $dbData['peringkat_sem_4'] = $ranks['4'] ?? '';
                    $dbData['peringkat_sem_5'] = $ranks['5'] ?? '';
                }
            }

            // Suntik Data Berkas — cari slug Requirement langsung via requirement_id
            foreach ($registration->documents as $doc) {
                $req = Requirement::find($doc->requirement_id);
                if ($req) {
                    $slug = 'berkas_' . str_replace('-', '_', $req->slug);
                    $dbData[$slug] = 1;
                }
            }

            $request->merge($dbData);
        }

        $hiddenFields = [
            'reg_number',
            'full_name',
            'nickname',
            'gender',
            'nisn',
            'phone',
            'school_origin',
            'r1',
            'r2',
            'r3',
            'r4',
            'r5',
            'rata_rapor',
            'tka_mtk',
            'tka_indo',
            'rata_tka',
            'jalur',
            'jalur_name',
            'jenis_prestasi',
            'tingkat_kejuaraan',
            'juz_hafalan',
            'jabatan_org',
            'peringkat_sem_1',
            'peringkat_sem_2',
            'peringkat_sem_3',
            'peringkat_sem_4',
            'peringkat_sem_5',
            'pil1',
            'pil2',
            'pil3'
        ];

        foreach ($berkasList as $berkas) {
            $hiddenFields[] = 'berkas_' . str_replace('-', '_', $berkas->slug);
        }

        // -- VARIABEL BANTUAN UNTUK URL HTMX SAAT EDIT --
        $isEdit    = true;
        $postUrl   = route('admin.pendaftar.update', $id);
        $regNumber = $registration->registration_number;

        // stepUrls harus mengarah ke route EDIT (bukan create) agar $id tetap terbawa
        // sehingga $request->merge($dbData) tetap dijalankan saat navigasi Lanjut/Kembali
        $stepUrls = [];
        for ($i = 1; $i <= 6; $i++) {
            $stepUrls[$i] = route('admin.pendaftar.edit', ['id' => $id, 'step' => $i]);
        }

        return view("pages.admin.peserta.partials.step-{$step}-" . match ($step) {
            1 => 'biodata',
            2 => 'akademik',
            3 => 'jalur',
            4 => 'jurusan',
            5 => 'berkas',
            6 => 'konfirmasi',
            default => abort(404),
        }, compact('jurusanList', 'jalurList', 'berkasList', 'hiddenFields', 'regNumber', 'stepUrls', 'isEdit', 'postUrl'));
    }

    public function update(Request $request, $id)
    {
        $requirements = Requirement::where('category', 'dokumen')->get();

        $rules = [
            // Abaikan unique rule untuk ID milik pendaftar ini sendiri
            'reg_number'    => 'required|string|max:30|unique:registration_data,registration_number,' . $id,
            'full_name'     => 'required|string|max:100',
            'nickname'      => 'required|string|max:50',
            'gender'        => 'required|in:L,P',
            'nisn'          => 'required|string|max:10',
            'phone'         => 'required|string|max:20',
            'school_origin' => 'required|string|max:150',

            // Akademik
            'r1' => 'nullable|numeric',
            'r2' => 'nullable|numeric',
            'r3' => 'nullable|numeric',
            'r4' => 'nullable|numeric',
            'r5' => 'nullable|numeric',
            'rata_rapor' => 'nullable|numeric',
            'tka_mtk' => 'nullable|numeric',
            'tka_indo' => 'nullable|numeric',
            'rata_tka' => 'nullable|numeric',

            // Jalur & Jurusan
            'jalur' => 'required|exists:admission_paths,id',
            'pil1'  => 'required|exists:concentrations,id',
            'pil2'  => 'nullable|exists:concentrations,id',
            'pil3'  => 'nullable|exists:concentrations,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal. Periksa kembali isian form.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        DB::beginTransaction();

        try {
            $registrationData = RegistrationData::findOrFail($id);

            // --- LOGIKA MENGUBAH AWALAN TANPA MENGGANGU 4 DIGIT TERAKHIR ---
            $newInputPrefix = rtrim($validated['reg_number'], '-'); // Contoh: "NEW-2026"
            $oldRegNumber = $registrationData->registration_number; // Contoh: "REG-2026-0001"

            // Ambil 4 digit terakhir dari nomor lama
            $suffix = substr($oldRegNumber, -4);

            // Gabungkan awalan baru dengan akhiran lama
            $finalRegNumber = $newInputPrefix . '-' . $suffix;

            $personalData = $registrationData->personalData;

            // Update PersonalData
            $personalData->update([
                'full_name'       => $validated['full_name'],
                'nick_name'       => $validated['nickname'],
                'gender'          => $validated['gender'],
                'nisn'            => $validated['nisn'],
                'phone_number'    => $validated['phone'],
                'previous_school' => $validated['school_origin'],
            ]);

            // Update RegistrationData
            $registrationData->update([
                'admission_path_id'   => $validated['jalur'],
                'registration_number' => $finalRegNumber,
                'choice_1'            => $validated['pil1'],
                'choice_2'            => $validated['pil2'] ?? null,
                'choice_3'            => $validated['pil3'] ?? null,
                'report_sem_1'        => $validated['r1'] ?? null,
                'report_sem_2'        => $validated['r2'] ?? null,
                'report_sem_3'        => $validated['r3'] ?? null,
                'report_sem_4'        => $validated['r4'] ?? null,
                'report_sem_5'        => $validated['r5'] ?? null,
                'report_average'      => $validated['rata_rapor'] ?? null,
                'tka_math'            => $validated['tka_mtk'] ?? null,
                'tka_indonesian'      => $validated['tka_indo'] ?? null,
                'tka_average'         => $validated['rata_tka'] ?? null,

                'updated_by'          => Auth::id(),
            ]);

            // Update Prestasi: Hapus yang lama, insert yang baru (Lebih aman dari bentrok data)
            $registrationData->achievements()->delete();
            $isPrestasi = str_contains(strtolower($request->jalur_name ?? ''), 'prestasi');

            if ($isPrestasi && $request->jenis_prestasi) {
                RegistrationAchievement::create([
                    'registration_data_id' => $registrationData->id,
                    'achievement_type'     => $request->jenis_prestasi,
                    'level'                => $request->tingkat_kejuaraan ?? null,
                    'leadership_position'  => $request->jabatan_org ?? null,
                    'class_ranks'          => $request->jenis_prestasi === 'peringkat' ? [
                        '1' => $request->peringkat_sem_1 ?? '',
                        '2' => $request->peringkat_sem_2 ?? '',
                        '3' => $request->peringkat_sem_3 ?? '',
                        '4' => $request->peringkat_sem_4 ?? '',
                        '5' => $request->peringkat_sem_5 ?? '',
                    ] : null,
                ]);
            }

            // Update Dokumen: Hapus lama, insert yang baru dicentang
            $registrationData->documents()->delete();
            foreach ($requirements as $req) {
                $field = 'berkas_' . str_replace('-', '_', $req->slug);
                if ($request->filled($field)) {
                    RegistrationDocument::create([
                        'registration_data_id' => $registrationData->id,
                        'requirement_id'       => $req->id,
                        'file_path'            => 'Diserahkan fisik di loket',
                        'verification_status'  => 'verified',
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Data pendaftar berhasil diperbarui!',
                // Tambahkan array data ini
                'data'     => [
                    'id'         => $registrationData->id,
                    'full_name'  => $personalData->full_name,
                    'reg_number' => $registrationData->registration_number
                ],
                'redirect' => route('admin.pendaftar.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error Edit Pendaftar: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data: ' . $e->getMessage(),
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\PersonalData;
use App\Models\SpmbStep;
use App\Notifications\BiodataFinalizedNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PersonalDataController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ④ index() — Load halaman biodata, prefill jika data lama ada
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        // ── 1. Ambil Biodata pribadi dengan eager load relasi 'parents' ──
        $personalData = PersonalData::where('user_id', Auth::id())
            ->with(['parents'])
            ->first();

        // ── 2. Jika biodata sudah final, tampilkan halaman resume pendaftaran ──
        if ($personalData && $personalData->isFinal()) {

            // Ambil data orang tua dari relasi 'parents' (jika null, kembalikan koleksi kosong)
            $parentData = $personalData->parents ?? collect();

            // Ambil step "Pendaftaran SPMB" dari tabel SpmbStep berdasarkan slug alur tunggal yang baru
            $spmbStep = SpmbStep::where('slug', 'pendaftaran-spmb')->first();

            // Definisikan $isSchedule berdasarkan start_date & end_date
            $isSchedule = isset($spmbStep)
                && $spmbStep->start_date !== null
                && now()->between($spmbStep->start_date, $spmbStep->end_date);

            // dd([
            //     'now'        => now()->toDateTimeString(),
            //     'start_date' => $spmbStep->start_date,
            //     'end_date'   => $spmbStep->end_date,
            //     'isSchedule' => $isSchedule,
            // ]);

            return view('pages.user.resume-biodata', [
                'personalData' => $personalData,
                'parentData'   => $parentData,
                'spmbStep'     => $spmbStep,
                'isFinal'      => true,
                'isSchedule'   => $isSchedule,
            ]);
        }

        // ── 3. Jika biodata belum final, tampilkan halaman form biodata ──
        // Ambil jadwal pendaftaran — dibutuhkan oleh _success_screen setelah submit
        $registrationSchedule = SpmbStep::where('slug', 'pendaftaran-spmb')->first();

        return view('pages.user.biodata', [
            'personalData'         => $personalData,
            'isFinal'              => false,
            'registrationSchedule' => $registrationSchedule,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ⑤ saveStep1() — Data Pribadi (identitas + keluarga + kondisi khusus)
    |--------------------------------------------------------------------------
    | Menerima: POST /biodata/step/1
    | Return  : JSON {success, step} — Alpine akan step++
    */
    public function saveStep1(Request $request): JsonResponse
    {
        $rules = [
            'nik'                    => 'required|digits:16',
            'nisn'                   => 'required|digits:10',
            'full_name'              => 'required|string|max:255',
            'nick_name'              => 'nullable|string|max:100',
            'pob'                    => 'required|string|max:100',
            'dob'                    => 'required|date',
            'gender'                 => 'required|in:L,P',
            'religion'               => 'required|string',
            'blood_type'             => 'nullable|in:A,B,AB,O',
            'child_order'            => 'required|integer|min:1|max:20',
            'number_of_siblings'     => 'required|integer|min:0|max:20',
            'is_special_condition'   => 'required|in:yes,no',
            'special_condition_type' => 'nullable|string|max:100',
            'condition_description'  => 'nullable|string|max:500',
            // --- Field Baru ---
            'height'                 => 'required|integer|min:50|max:250',
            'weight'                 => 'required|integer|min:10|max:300',
            'medical_history'        => 'nullable|string|max:100',
            'interest_art'           => 'nullable|string|max:100',
            'interest_sport'         => 'nullable|string|max:100',
            'interest_organization'  => 'nullable|string|max:100',
            'extracurricular_choice' => 'required|string|max:100',
            'fl2sn_category'         => 'nullable|string|max:100',
            'o2sn_category'          => 'nullable|string|max:100',
        ];

        // Tulis pesan langsung menyebutkan nama aliasnya agar key JSON yang return tetap aman (nik, nisn, full_name)
        $messages = [
            'nik.required'                    => 'Kolom NIK wajib diisi.',
            'nik.digits'                      => 'Kolom NIK harus berupa angka sebanyak 16 digit.',
            'nisn.required'                   => 'Kolom NISN wajib diisi.',
            'nisn.digits'                     => 'Kolom NISN harus berupa angka sebanyak 10 digit.',
            'full_name.required'              => 'Kolom Nama Lengkap wajib diisi.',
            'pob.required'                    => 'Kolom Tempat Lahir wajib diisi.',
            'dob.required'                    => 'Format tanggal pada Tanggal Lahir tidak valid.',
            'gender.required'                 => 'Pilihan pada kolom Jenis Kelamin tidak valid.',
            'religion.required'               => 'Kolom Agama wajib diisi.',
            'child_order.required'            => 'Kolom Anak Ke- wajib diisi.',
            'child_order.min'                 => 'Kolom Anak Ke- minimal bernilai 1.',
            'number_of_siblings.required'     => 'Kolom Jumlah Saudara wajib diisi.',
            'is_special_condition.required'   => 'Pilihan pada kolom Kondisi Khusus tidak valid.',

            // --- Pesan Validasi Field Baru ---
            'height.required'                 => 'Kolom Tinggi Badan wajib diisi.',
            'height.integer'                  => 'Tinggi badan harus berupa angka.',
            'height.min'                      => 'Tinggi badan tidak valid (minimal 50 cm).',
            'height.max'                      => 'Tinggi badan tidak valid (maksimal 250 cm).',
            'weight.required'                 => 'Kolom Berat Badan wajib diisi.',
            'weight.integer'                  => 'Berat badan harus berupa angka.',
            'weight.min'                      => 'Berat badan tidak valid (minimal 10 kg).',
            'weight.max'                      => 'Berat badan tidak valid (maksimal 300 kg).',
            'extracurricular_choice.required' => 'Pilihan Ekstrakurikuler yang Ingin Diikuti wajib diisi.',

            // Pesan umum untuk string/max jika ada yang terlewat
            'string'                          => 'Kolom harus berupa teks.',
            'max'                             => 'Kolom tidak boleh lebih dari :max karakter.',
        ];

        // JANGAN masukkan parameter ketiga ($attributes) agar key JSON tidak berubah menjadi kapital/spasi
        $validated = $request->validate($rules, $messages);

        // Ambil atau buat record
        $personal = PersonalData::firstOrNew(['user_id' => Auth::id()]);

        // Plain columns
        $personal->full_name   = $validated['full_name'];
        $personal->nick_name   = $validated['nick_name'] ?? '';
        $personal->gender      = $validated['gender'];
        $personal->blood_type  = $validated['blood_type'] ?? null;
        $personal->child_order = $validated['child_order'];
        $personal->number_of_siblings   = $validated['number_of_siblings'];
        $personal->is_special_condition = $validated['is_special_condition'];
        $personal->special_condition_type = $validated['special_condition_type'] ?? null;
        $personal->condition_description  = $validated['condition_description'] ?? null;

        // --- Simpan Field Baru ---
        $personal->height                 = $validated['height'];
        $personal->weight                 = $validated['weight'];
        $personal->medical_history        = $validated['medical_history'] ?? null;
        $personal->interest_art           = $validated['interest_art'] ?? null;
        $personal->interest_sport         = $validated['interest_sport'] ?? null;
        $personal->interest_organization  = $validated['interest_organization'] ?? null;
        $personal->extracurricular_choice = $validated['extracurricular_choice'];
        $personal->fl2sn_category         = $validated['fl2sn_category'] ?? null;
        $personal->o2sn_category          = $validated['o2sn_category'] ?? null;

        // Encrypted via mutators
        $personal->nik      = $validated['nik'];
        $personal->nisn     = $validated['nisn'];
        $personal->pob      = $validated['pob'];
        $personal->dob      = $validated['dob'];
        $personal->religion = $validated['religion'];

        // Pastikan user_id ter-set (untuk record baru)
        $personal->user_id = Auth::id();

        $personal->save();

        return response()->json(['success' => true, 'step' => 2]);
    }

    /*
    |--------------------------------------------------------------------------
    | ⑥ saveStep2() — Alamat domisili + kontak + jarak & transportasi
    |--------------------------------------------------------------------------
    | Menerima: POST /biodata/step/2
    | Return  : JSON {success, step}
    */
    public function saveStep2(Request $request): JsonResponse
    {
        $rules = [
            'address'            => 'required|string|max:500',
            'rt'                 => 'nullable|string|max:5',
            'rw'                 => 'nullable|string|max:5',
            'village'            => 'nullable|string|max:100',
            'district'           => 'required|string|max:100',
            'regency'            => 'required|string|max:100',
            'province'           => 'required|string|max:100',
            'postal_code'        => 'nullable|string|max:10',
            'phone_number'       => 'nullable|string|max:20',
            'email'              => 'nullable|email|max:255',
            'residence_type'     => 'nullable|string|max:50',
            'transportation'     => 'nullable|string|max:50',
            'distance_to_school' => 'nullable|string|max:50',
        ];

        // Pesan langsung menyebutkan nama alias agar struktur key JSON aman (tetap snake_case)
        $messages = [
            // --- Address ---
            'address.required'            => 'Kolom Alamat wajib diisi.',
            'address.max'                 => 'Kolom Alamat tidak boleh lebih dari 500 karakter.',

            // --- Wilayah ---
            'district.required'           => 'Kolom Kecamatan wajib diisi.',
            'regency.required'            => 'Kolom Kabupaten/Kota wajib diisi.',
            'province.required'           => 'Kolom Provinsi wajib diisi.',

            // --- Kontak & Email ---
            'email.email'                 => 'Format email pada kolom Email tidak valid.',

            // --- Format Batasan Karakter Umum (Fallback) ---
            'string'                      => 'Kolom :attribute harus berupa teks.',
            'max'                         => 'Kolom :attribute tidak boleh lebih dari :max karakter.',
        ];

        // JANGAN masukkan parameter ketiga ($attributes) agar key JSON tidak berubah menjadi spasi/kapital
        $validated = $request->validate($rules, $messages);

        $personal = PersonalData::where('user_id', Auth::id())->firstOrFail();

        // Encrypted via mutators
        $personal->address     = $validated['address'];
        $personal->rt          = $validated['rt'] ?? null;
        $personal->rw          = $validated['rw'] ?? null;
        $personal->village     = $validated['village'] ?? null;
        $personal->district    = $validated['district'];
        $personal->regency     = $validated['regency'];
        $personal->province    = $validated['province'];
        $personal->postal_code = $validated['postal_code'] ?? null;
        $personal->phone_number = $validated['phone_number'] ?? null;
        $personal->email        = $validated['email'] ?? null;

        // Plain columns
        $personal->residence_type     = $validated['residence_type'] ?? null;
        $personal->transportation     = $validated['transportation'] ?? null;
        $personal->distance_to_school = $validated['distance_to_school'] ?? null;

        $personal->save();

        return response()->json(['success' => true, 'step' => 3]);
    }

    /*
    |--------------------------------------------------------------------------
    | ⑧ saveStep4() — Riwayat Pendidikan Sebelumnya
    |--------------------------------------------------------------------------
    | Menerima: POST /biodata/step/4
    | Return  : JSON {success, step}
    */
    public function saveStep4(Request $request): JsonResponse
    {
        $rules = [
            'previous_school'               => 'required|string|max:255',
            'previous_school_npsn'          => 'nullable|string|max:20',
            'previous_school_status'        => 'required|string|max:50',
            'previous_school_city'          => 'required|string|max:100',
            'previous_school_province'      => 'required|string|max:100',
            'graduation_certificate_number' => 'nullable|string|max:100',
            'graduation_year'               => 'required|digits:4',
        ];

        // Pesan langsung menyebutkan nama alias eksplisit agar ramah saat dibaca di Error Summary Box Alpine.js
        $messages = [
            // --- Nama Sekolah Asal ---
            'previous_school.required'               => 'Kolom Nama Sekolah Asal wajib diisi.',
            'previous_school.max'                    => 'Nama Sekolah Asal tidak boleh lebih dari 255 karakter.',
            'previous_school_npsn.max'               => 'NPSN Sekolah Asal tidak boleh lebih dari 20 karakter.',
            'previous_school_status.required'        => 'Kolom Status Sekolah (Negeri/Swasta) wajib diisi.',
            'previous_school_city.required'          => 'Kolom Kabupaten/Kota Sekolah Asal wajib diisi.',
            'previous_school_province.required'      => 'Kolom Provinsi Sekolah Asal wajib diisi.',
            'graduation_certificate_number.max'     => 'Nomor Ijazah tidak boleh lebih dari 100 karakter.',
            'graduation_year.required'               => 'Kolom Tahun Lulus wajib diisi.',
            'graduation_year.digits'                 => 'Kolom Tahun Lulus harus berupa angka sebanyak 4 digit.',

            // --- Fallback Global (Antisipasi jika ada yang terlewat) ---
            'string'                                 => 'Kolom ini harus berupa teks.',
            'max'                                    => 'Kolom ini tidak boleh lebih dari :max karakter.',
        ];

        // Eksekusi validasi tanpa parameter ketiga ($attributes) agar key JSON tetap asli (snake_case)
        $validated = $request->validate($rules, $messages);

        $personal = PersonalData::where('user_id', Auth::id())->firstOrFail();

        $personal->fill([
            'previous_school'               => $validated['previous_school'] ?? null,
            'previous_school_npsn'          => $validated['previous_school_npsn'] ?? null,
            'previous_school_status'        => $validated['previous_school_status'] ?? null,
            'previous_school_city'          => $validated['previous_school_city'] ?? null,
            'previous_school_province'      => $validated['previous_school_province'] ?? null,
            'graduation_certificate_number' => $validated['graduation_certificate_number'] ?? null,
            'graduation_year'               => $validated['graduation_year'] ?? null,
        ]);

        $personal->save();

        return response()->json(['success' => true, 'step' => 5]);
    }

    /*
    |--------------------------------------------------------------------------
    | ⑨ saveStep5() — Upload pas foto
    |--------------------------------------------------------------------------
    | Menerima: POST /biodata/step/5 (multipart/form-data)
    | Return  : JSON {success, step}
    */
    public function saveStep5(Request $request): JsonResponse
    {
        // 1. Ambil data personal atau gagalkan jika user tidak ditemukan
        $personal = PersonalData::where('user_id', Auth::id())->firstOrFail();

        // 2. Tentukan rule secara dinamis berdasarkan kondisi foto di database
        // Jika kolom photo di DB kosong/null, gunakan 'required'. Jika sudah ada, gunakan 'nullable'
        $photoRule = empty($personal->photo)
            ? 'required|image|mimes:jpg,jpeg,png|max:1024'
            : 'nullable|image|mimes:jpg,jpeg,png|max:1024';

        $rules = [
            'photo' => $photoRule,
        ];

        $messages = [
            'photo.required' => 'Pas foto wajib diunggah karena Anda belum memiliki foto.',
            'photo.image'    => 'Berkas yang diunggah harus berupa gambar.',
            'photo.mimes'    => 'Format gambar harus berupa: :values.',
            'photo.max'      => 'Ukuran foto maksimal adalah 1 MB (1024 KB).',
        ];

        // 3. Jalankan Validasi
        $request->validate($rules, $messages);

        // 4. Cek apakah pengguna mengunggah berkas baru
        if ($request->hasFile('photo')) {

            // Hapus foto lama dari storage jika sebelumnya sudah ada foto
            if ($personal->photo && Storage::disk('public')->exists($personal->photo)) {
                Storage::disk('public')->delete($personal->photo);
            }

            // Simpan foto yang baru diunggah
            $path = $request->file('photo')->store('photos/biodata', 'public');
            $personal->photo = $path;
            $personal->save();
        }
        // Jika di DB sudah ada foto DAN user tidak mengunggah foto baru,
        // blok IF di atas akan dilewati dengan aman tanpa mengubah data lama.

        return response()->json(['success' => true, 'step' => 6])
            ->header('HX-Trigger', 'refreshResume');
    }

    /*
    |--------------------------------------------------------------------------
    | ⑩ summary() — Render partial HTML ringkasan untuk Step 6 (HTMX GET)
    |--------------------------------------------------------------------------
    | Menerima: GET /biodata/summary
    | Return  : Partial Blade HTML (HTMX swap ke #summary-container)
    */
    public function summary()
    {
        $personal = PersonalData::where('user_id', Auth::id())
            ->with(['parents'])
            ->firstOrFail();

        return view('pages.user.partials.biodata._summary', ['personalData' => $personal]);
    }

    /*
    |--------------------------------------------------------------------------
    | ⑪ submit() — Ubah profile_status → final (lock biodata)
    |--------------------------------------------------------------------------
    | Menerima: POST /biodata/submit
    | Return  : JSON {success} → Alpine set isSubmitted = true
    */
    public function submit(Request $request): JsonResponse
    {
        $personal = PersonalData::where('user_id', Auth::id())->firstOrFail();

        if ($personal->isFinal()) {
            return response()->json(['success' => false, 'message' => 'Biodata sudah final.'], 422);
        }

        $personal->profile_status = 'final';
        $personal->save();

        // ─── AMBIL USER & KIRIM NOTIFIKASI SECARA INSTAN ───
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user) {
            // Kirim notifikasi 'Biodata Berhasil Dikunci!' ke database pendaftar
            $user->notify(new BiodataFinalizedNotification());
        }

        // Mengembalikan response JSON dengan tambahan header HX-Trigger untuk HTMX
        return response()->json([
            'success'        => true,
            'profile_status' => $personal->profile_status,
            'nisn'            => $personal->nisn,
            'full_name'       => $personal->full_name,
            'previous_school' => $personal->previous_school,
            'phone_number'    => $personal->phone_number,
        ])->header('HX-Trigger', json_encode([
            'refresh-notifications' => true,
            'biodata-submitted'     => true,
        ]));
    }

    /*
    |--------------------------------------------------------------------------
    | saveDraft() — Simpan semua field yang terisi dari step saat ini
    |--------------------------------------------------------------------------
    | Menerima: POST /biodata/draft
    | Return  : JSON {success} → HTMX swap toast ke #toast-container
    |
    | Endpoint ini menerima semua field step 1–4 sekaligus (partial save),
    | tidak validasi ketat — hanya encrypt & upsert apa yang dikirim.
    */
    public function saveDraft(Request $request): JsonResponse
    {
        $personal = PersonalData::firstOrNew(['user_id' => Auth::id()]);
        $personal->user_id = Auth::id();

        // -- Step 1 fields --
        if ($request->filled('full_name')) $personal->full_name   = $request->full_name;
        if ($request->filled('nick_name')) $personal->nick_name   = $request->nick_name;
        if ($request->filled('gender'))    $personal->gender      = $request->gender;
        if ($request->filled('blood_type')) $personal->blood_type  = $request->blood_type;
        if ($request->filled('nik'))       $personal->nik         = $request->nik;
        if ($request->filled('nisn'))      $personal->nisn        = $request->nisn;
        if ($request->filled('pob'))       $personal->pob         = $request->pob;
        if ($request->filled('dob'))       $personal->dob         = $request->dob;
        if ($request->filled('religion'))  $personal->religion    = $request->religion;
        if ($request->has('child_order'))         $personal->child_order        = $request->child_order;
        if ($request->has('number_of_siblings'))  $personal->number_of_siblings = $request->number_of_siblings;
        if ($request->has('is_special_condition')) $personal->is_special_condition = $request->is_special_condition;
        if ($request->has('special_condition_type')) $personal->special_condition_type = $request->special_condition_type;
        if ($request->has('condition_description')) $personal->condition_description  = $request->condition_description;

        // --- Draft Field Baru Step 1 ---
        if ($request->filled('height'))                 $personal->height = $request->height;
        if ($request->filled('weight'))                 $personal->weight = $request->weight;
        if ($request->has('medical_history'))           $personal->medical_history = $request->medical_history;
        if ($request->has('interest_art'))              $personal->interest_art = $request->interest_art;
        if ($request->has('interest_sport'))            $personal->interest_sport = $request->interest_sport;
        if ($request->has('interest_organization'))     $personal->interest_organization = $request->interest_organization;
        if ($request->filled('extracurricular_choice')) $personal->extracurricular_choice = $request->extracurricular_choice;
        if ($request->has('fl2sn_category'))            $personal->fl2sn_category = $request->fl2sn_category;
        if ($request->has('o2sn_category'))             $personal->o2sn_category = $request->o2sn_category;

        // -- Step 2 fields --
        if ($request->filled('address'))      $personal->address      = $request->address;
        if ($request->filled('rt'))           $personal->rt           = $request->rt;
        if ($request->filled('rw'))           $personal->rw           = $request->rw;
        if ($request->filled('village'))      $personal->village      = $request->village;
        if ($request->filled('district'))     $personal->district     = $request->district;
        if ($request->filled('regency'))      $personal->regency      = $request->regency;
        if ($request->filled('province'))     $personal->province     = $request->province;
        if ($request->filled('postal_code'))  $personal->postal_code  = $request->postal_code;
        if ($request->filled('phone_number')) $personal->phone_number = $request->phone_number;
        if ($request->filled('email'))        $personal->email        = $request->email;
        if ($request->filled('residence_type'))     $personal->residence_type     = $request->residence_type;
        if ($request->filled('transportation'))     $personal->transportation     = $request->transportation;
        if ($request->filled('distance_to_school')) $personal->distance_to_school = $request->distance_to_school;

        // -- Step 4 fields --
        if ($request->filled('previous_school'))               $personal->previous_school               = $request->previous_school;
        if ($request->filled('previous_school_npsn'))          $personal->previous_school_npsn          = $request->previous_school_npsn;
        if ($request->filled('previous_school_status'))        $personal->previous_school_status        = $request->previous_school_status;
        if ($request->filled('previous_school_city'))          $personal->previous_school_city          = $request->previous_school_city;
        if ($request->filled('previous_school_province'))      $personal->previous_school_province      = $request->previous_school_province;
        if ($request->filled('graduation_certificate_number')) $personal->graduation_certificate_number = $request->graduation_certificate_number;
        if ($request->filled('graduation_year'))               $personal->graduation_year               = $request->graduation_year;

        $personal->save();

        // Return toast partial untuk HTMX swap
        return response()->json(['success' => true]);
    }
}

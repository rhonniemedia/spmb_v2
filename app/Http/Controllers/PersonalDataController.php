<?php

namespace App\Http\Controllers;

use App\Models\PersonalData;
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
        $personalData = PersonalData::where('user_id', Auth::id())
            ->with(['parents'])
            ->first();

        // Jika sudah final, tampilkan view read-only (opsional: redirect dashboard)
        if ($personalData && $personalData->isFinal()) {
            return view('biodata', [
                'personalData' => $personalData,
                'isFinal'      => true,
            ]);
        }

        return view('biodata', [
            'personalData' => $personalData,
            'isFinal'      => false,
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
        $validated = $request->validate([
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
        ]);

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
        $validated = $request->validate([
            'address'           => 'required|string|max:500',
            'rt'                => 'nullable|string|max:5',
            'rw'                => 'nullable|string|max:5',
            'village'           => 'nullable|string|max:100',
            'district'          => 'required|string|max:100',
            'regency'           => 'required|string|max:100',
            'province'          => 'required|string|max:100',
            'postal_code'       => 'nullable|string|max:10',
            'phone_number'      => 'nullable|string|max:20',
            'email'             => 'nullable|email|max:255',
            'residence_type'    => 'nullable|string|max:50',
            'transportation'    => 'nullable|string|max:50',
            'distance_to_school' => 'nullable|string|max:50',
        ]);

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
        $validated = $request->validate([
            'previous_school'                 => 'nullable|string|max:255',
            'previous_school_npsn'            => 'nullable|string|max:20',
            'previous_school_status'          => 'nullable|string|max:50',
            'previous_school_city'            => 'nullable|string|max:100',
            'previous_school_province'        => 'nullable|string|max:100',
            'graduation_certificate_number'   => 'nullable|string|max:100',
            'graduation_year'                 => 'nullable|digits:4',
        ]);

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
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:1024',
        ]);

        $personal = PersonalData::where('user_id', Auth::id())->firstOrFail();

        // Hapus foto lama jika ada
        if ($personal->photo && Storage::disk('public')->exists($personal->photo)) {
            Storage::disk('public')->delete($personal->photo);
        }

        $path = $request->file('photo')->store('photos/biodata', 'public');
        $personal->photo = $path;
        $personal->save();

        return response()->json(['success' => true, 'step' => 6]);
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

        return view('partials.biodata-summary', compact('personal'));
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

        return response()->json(['success' => true]);
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

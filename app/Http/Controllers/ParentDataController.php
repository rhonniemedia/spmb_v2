<?php

namespace App\Http\Controllers;

use App\Models\ParentData;
use App\Models\PersonalData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentDataController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ⑦ saveStep3() — Upsert data ayah, ibu, dan wali (opsional)
    |--------------------------------------------------------------------------
    | Menerima: POST /biodata/step/3
    | Return  : JSON {success, step}
    |
    | Strategi upsert:
    |   - Cari record berdasarkan personal_data_id + relationship
    |   - Jika ada → update, jika tidak → create
    |   - Data wali hanya disimpan jika field wali dikirim (showWali = true)
    */
    public function saveStep3(Request $request): JsonResponse
    {
        // ── Aturan Validasi (Rules) ──────────────────────────────────────
        $rules = [
            // --- Validasi Ayah ---
            'ayah_name'       => 'required|string|max:255',
            'ayah_status'     => 'required|in:alive,deceased',
            'ayah_nik'        => 'required_if:ayah_status,alive|nullable|digits:16',
            'ayah_birth_year' => 'required_if:ayah_status,alive|nullable|digits:4',
            'ayah_education'  => 'required_if:ayah_status,alive|nullable|string|max:50',
            'ayah_job'        => 'required_if:ayah_status,alive|nullable|string|max:100',
            'ayah_income'     => 'required_if:ayah_status,alive|nullable|string|max:100',
            'ayah_phone'      => 'required_if:ayah_status,alive|nullable|string|max:20',
            'ayah_address'    => 'required_if:ayah_status,alive|nullable|string|max:500',

            // --- Validasi Ibu ---
            'ibu_name'        => 'required|string|max:255',
            'ibu_status'      => 'required|in:alive,deceased',
            'ibu_nik'         => 'required_if:ibu_status,alive|nullable|digits:16',
            'ibu_birth_year'  => 'required_if:ibu_status,alive|nullable|digits:4',
            'ibu_education'   => 'required_if:ibu_status,alive|nullable|string|max:50',
            'ibu_job'         => 'required_if:ibu_status,alive|nullable|string|max:100',
            'ibu_income'      => 'required_if:ibu_status,alive|nullable|string|max:100',
            'ibu_phone'       => 'required_if:ibu_status,alive|nullable|string|max:20',
            'ibu_address'     => 'required_if:ibu_status,alive|nullable|string|max:500',

            // --- Validasi Wali (Opsional & Wajib Hidup) ---
            'wali_name'       => 'nullable|string|max:255',
            'wali_nik'        => 'required_with:wali_name|nullable|digits:16',
            'wali_birth_year' => 'required_with:wali_name|nullable|digits:4',
            'wali_education'  => 'required_with:wali_name|nullable|string|max:50',
            'wali_job'        => 'required_with:wali_name|nullable|string|max:100',
            'wali_income'     => 'required_with:wali_name|nullable|string|max:100',
            'wali_phone'      => 'required_with:wali_name|nullable|string|max:20',
            'wali_address'    => 'required_with:wali_name|nullable|string|max:500',
        ];

        // Pesan langsung dipetakan ke field agar key JSON aman
        $messages = [
            // --- Ayah ---
            'ayah_name.required'       => 'Kolom Nama Ayah wajib diisi.',
            'ayah_status.required'     => 'Kolom Status Ayah wajib diisi.',
            'ayah_nik.required_if'     => 'Kolom NIK Ayah wajib diisi jika status masih hidup.',
            'ayah_nik.digits'          => 'Kolom NIK Ayah harus berupa angka sebanyak 16 digit.',
            'ayah_birth_year.required_if' => 'Kolom Tahun Lahir Ayah wajib diisi jika status masih hidup.',
            'ayah_birth_year.digits'   => 'Kolom Tahun Lahir Ayah harus berupa angka sebanyak 4 digit.',
            'ayah_education.required_if'  => 'Kolom Pendidikan Terakhir Ayah wajib diisi jika status masih hidup.',
            'ayah_job.required_if'     => 'Kolom Pekerjaan Ayah wajib diisi jika status masih hidup.',
            'ayah_income.required_if'  => 'Kolom Penghasilan Ayah wajib diisi jika status masih hidup.',
            'ayah_phone.required_if'   => 'Kolom Nomor Telepon Ayah wajib diisi jika status masih hidup.',
            'ayah_address.required_if' => 'Kolom Alamat Ayah wajib diisi jika status masih hidup.',

            // --- Ibu ---
            'ibu_name.required'        => 'Kolom Nama Ibu wajib diisi.',
            'ibu_status.required'      => 'Kolom Status Ibu wajib diisi.',
            'ibu_nik.required_if'      => 'Kolom NIK Ibu wajib diisi jika status masih hidup.',
            'ibu_nik.digits'           => 'Kolom NIK Ibu harus berupa angka sebanyak 16 digit.',
            'ibu_birth_year.required_if'  => 'Kolom Tahun Lahir Ibu wajib diisi jika status masih hidup.',
            'ibu_birth_year.digits'    => 'Kolom Tahun Lahir Ibu harus berupa angka sebanyak 4 digit.',
            'ibu_education.required_if'   => 'Kolom Pendidikan Terakhir Ibu wajib diisi jika status masih hidup.',
            'ibu_job.required_if'      => 'Kolom Pekerjaan Ibu wajib diisi jika status masih hidup.',
            'ibu_income.required_if'   => 'Kolom Penghasilan Ibu wajib diisi jika status masih hidup.',
            'ibu_phone.required_if'    => 'Kolom Nomor Telepon Ibu wajib diisi jika status masih hidup.',
            'ibu_address.required_if'  => 'Kolom Alamat Ibu wajib diisi jika status masih hidup.',

            // --- Wali ---
            'wali_nik.required_with'   => 'Kolom NIK Wali wajib diisi jika data Wali dicantumkan.',
            'wali_nik.digits'          => 'Kolom NIK Wali harus berupa angka sebanyak 16 digit.',
            'wali_birth_year.required_with' => 'Kolom Tahun Lahir Wali wajib diisi jika data Wali dicantumkan.',
            'wali_birth_year.digits'   => 'Kolom Tahun Lahir Wali harus berupa angka sebanyak 4 digit.',
            'wali_education.required_with'  => 'Kolom Pendidikan Terakhir Wali wajib diisi jika data Wali dicantumkan.',
            'wali_job.required_with'   => 'Kolom Pekerjaan Wali wajib diisi jika data Wali dicantumkan.',
            'wali_income.required_with' => 'Kolom Penghasilan Wali wajib diisi jika data Wali dicantumkan.',
            'wali_phone.required_with' => 'Kolom Nomor Telepon Wali wajib diisi jika data Wali dicantumkan.',
            'wali_address.required_with' => 'Kolom Alamat Wali wajib diisi jika data Wali dicantumkan.',

            // --- Fallback Aturan Umum ---
            'string'                   => 'Kolom harus berupa teks.',
            'max'                      => 'Kolom tidak boleh lebih dari :max karakter.',
        ];

        // Jalankan tanpa parameter ketiga ($attributes)
        $validated = $request->validate($rules, $messages);

        $personal = PersonalData::where('user_id', Auth::id())->firstOrFail();

        // ── Upsert Ayah ──────────────────────────────────────────────────
        $this->upsertParent($personal->id, ParentData::RELATIONSHIP_FATHER, [
            'name'         => $validated['ayah_name'],
            'living_status' => $validated['ayah_status'],
            'nik'          => $validated['ayah_nik'] ?? null,
            'birth_year'   => $validated['ayah_birth_year'] ?? null,
            'education'    => $validated['ayah_education'] ?? null,
            'occupation'   => $validated['ayah_job'] ?? null,
            'income_range' => $validated['ayah_income'] ?? null,
            'phone_number' => $validated['ayah_status'] === 'alive' ? ($validated['ayah_phone'] ?? null) : null,
            'address'      => $validated['ayah_address'] ?? null,
        ]);

        // ── Upsert Ibu ───────────────────────────────────────────────────
        $this->upsertParent($personal->id, ParentData::RELATIONSHIP_MOTHER, [
            'name'         => $validated['ibu_name'],
            'living_status' => $validated['ibu_status'],
            'nik'          => $validated['ibu_nik'] ?? null,
            'birth_year'   => $validated['ibu_birth_year'] ?? null,
            'education'    => $validated['ibu_education'] ?? null,
            'occupation'   => $validated['ibu_job'] ?? null,
            'income_range' => $validated['ibu_income'] ?? null,
            'phone_number' => $validated['ibu_status'] === 'alive' ? ($validated['ibu_phone'] ?? null) : null,
            'address'      => $validated['ibu_address'] ?? null,
        ]);

        // ── Upsert / Hapus Wali ──────────────────────────────────────────
        if ($request->filled('wali_name')) {
            // Wali diisi → upsert
            $this->upsertParent($personal->id, ParentData::RELATIONSHIP_GUARDIAN, [
                'name'         => $validated['wali_name'],
                'living_status' => $validated['wali_status'] ?? 'alive',
                'nik'          => $validated['wali_nik'] ?? null,
                'birth_year'   => $validated['wali_birth_year'] ?? null,
                'education'    => $validated['wali_education'] ?? null,
                'occupation'   => $validated['wali_job'] ?? null,
                'income_range' => $validated['wali_income'] ?? null,
                'phone_number' => ($validated['wali_status'] ?? 'alive') === 'alive'
                    ? ($validated['wali_phone'] ?? null)
                    : null,
                'address'      => $validated['wali_address'] ?? null,
            ]);
        } else {
            // Wali tidak diisi → hapus record lama jika ada
            ParentData::where('personal_data_id', $personal->id)
                ->where('relationship', ParentData::RELATIONSHIP_GUARDIAN)
                ->delete();
        }

        return response()->json(['success' => true, 'step' => 4]);
    }

    /*
    |--------------------------------------------------------------------------
    | PRIVATE HELPER — upsertParent()
    |--------------------------------------------------------------------------
    | Cari record berdasarkan personal_data_id + relationship,
    | update jika ada, create jika belum ada.
    | Encrypted field di-set via mutator model.
    */
    private function upsertParent(string $personalDataId, string $relationship, array $data): ParentData
    {
        $parent = ParentData::firstOrNew([
            'personal_data_id' => $personalDataId,
            'relationship'     => $relationship,
        ]);

        // Encrypted via mutator
        $parent->name         = $data['name'];
        $parent->nik          = $data['nik'];          // nullable mutator
        $parent->phone_number = $data['phone_number']; // nullable mutator
        $parent->address      = $data['address'];      // nullable mutator

        // Plain columns
        $parent->living_status = $data['living_status'];
        $parent->birth_year    = $data['birth_year'];
        $parent->education     = $data['education'];
        $parent->occupation    = $data['occupation'];
        $parent->income_range  = $data['income_range'];

        $parent->save();

        return $parent;
    }
}

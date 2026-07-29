<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\ParentData;
use Carbon\Carbon;

class PintarIntegrationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $reg = $this->registrationData;
        $personal = $reg->personalData ?? null;
        $selection = $reg->selectionResult ?? null;

        // Memisahkan data orang tua berdasarkan 'relationship'
        $parents = $personal ? $personal->parents : collect();
        $ayah = $parents->where('relationship', ParentData::RELATIONSHIP_FATHER)->first();
        $ibu = $parents->where('relationship', ParentData::RELATIONSHIP_MOTHER)->first();
        $wali = $parents->where('relationship', ParentData::RELATIONSHIP_GUARDIAN)->first();

        // Format tanggal daftar ulang
        $tanggalDaftarUlang = $this->verified_at
            ? Carbon::parse($this->verified_at)->format('Y-m-d')
            : null;

        // ── MENYUSUN ARRAY ORANG TUA YANG BERSIH ──
        // Array ini hanya akan diisi jika data orang tua benar-benar ada.
        $dataOrangTua = [];

        if ($ayah) {
            $dataOrangTua[] = [
                'status_hubungan' => 'Ayah',
                'nama'            => strtoupper($ayah->name),
                'status_hidup'    => $ayah->living_status,
                'nik'             => $ayah->nik,
                'tahun_lahir'     => $ayah->birth_year,
                'pekerjaan'       => $ayah->occupation,
                'pendidikan'      => $ayah->education,
                'penghasilan'     => $ayah->income_range,
                'no_hp'           => $ayah->phone_number,
                'alamat'          => $ayah->address,
            ];
        }

        if ($ibu) {
            $dataOrangTua[] = [
                'status_hubungan' => 'Ibu',
                'nama'            => strtoupper($ibu->name),
                'status_hidup'    => $ibu->living_status,
                'nik'             => $ibu->nik,
                'tahun_lahir'     => $ibu->birth_year,
                'pekerjaan'       => $ibu->occupation,
                'pendidikan'      => $ibu->education,
                'penghasilan'     => $ibu->income_range,
                'no_hp'           => $ibu->phone_number,
                'alamat'          => $ibu->address,
            ];
        }

        if ($wali) {
            $dataOrangTua[] = [
                'status_hubungan' => 'Wali',
                'nama'            => strtoupper($wali->name),
                'status_hidup'    => $wali->living_status,
                'nik'             => $wali->nik,
                'tahun_lahir'     => $wali->birth_year,
                'pekerjaan'       => $wali->occupation,
                'pendidikan'      => $wali->education,
                'penghasilan'     => $wali->income_range,
                'no_hp'           => $wali->phone_number,
                'alamat'          => $wali->address,
            ];
        }

        return [
            // ── DATA PENDAFTARAN & KELULUSAN ──
            'no_registrasi'            => $reg->registration_number ?? null,
            'konsentrasi_keahlian'     => $selection->acceptedConcentration->name ?? null,
            'tanggal_daftar_ulang'     => $tanggalDaftarUlang,
            'keterangan_status'        => 'Terverifikasi (Sudah Daftar Ulang)',

            // ── GROUP 1: CORE IDENTITIES ──
            'nama_lengkap'             => $personal && $personal->full_name ? strtoupper($personal->full_name) : null,
            'nama_panggilan'           => $personal && $personal->nick_name ? strtoupper($personal->nick_name) : null,
            'jk'                       => $personal->gender ?? null,
            'nisn'                     => $personal->nisn ?? null,
            'nik'                      => $personal->nik ?? null,
            'tempat_lahir'             => $personal && $personal->pob ? strtoupper($personal->pob) : null,
            'tanggal_lahir'            => $personal->dob ?? null,
            'agama'                    => $personal->religion ?? null,
            'anak_ke'                  => $personal->child_order ?? null,
            'jumlah_saudara'           => $personal->number_of_siblings ?? null,
            'golongan_darah'           => $personal->blood_type ?? null,

            // ── GROUP 2: CONTACT & ADDRESS ──
            'email'                    => $personal->email ?? null,
            'no_hp_siswa'              => $personal->phone_number ?? null,
            'alamat_siswa'             => $personal->address ?? null,
            'rt'                       => $personal->rt ?? null,
            'rw'                       => $personal->rw ?? null,
            'desa_kelurahan'           => $personal->village ?? null,
            'kecamatan'                => $personal->district ?? null,
            'kabupaten_kota'           => $personal->regency ?? null,
            'provinsi'                 => $personal->province ?? null,
            'kode_pos'                 => $personal->postal_code ?? null,
            'jenis_tinggal'            => $personal->residence_type ?? null,
            'alat_transportasi'        => $personal->transportation ?? null,
            'jarak_ke_sekolah'         => $personal->distance_to_school ?? null,

            // ── GROUP 3: PREVIOUS EDUCATION ──
            'asal_sekolah'             => $personal && $personal->previous_school ? strtoupper($personal->previous_school) : null,
            'npsn_asal_sekolah'        => $personal->previous_school_npsn ?? null,
            'status_asal_sekolah'      => $personal->previous_school_status ?? null,
            'kota_asal_sekolah'        => $personal->previous_school_city ?? null,
            'provinsi_asal_sekolah'    => $personal->previous_school_province ?? null,
            'no_seri_ijazah'           => $personal->graduation_certificate_number ?? null,
            'tahun_lulus'              => $personal->graduation_year ?? null,

            // ── GROUP 4: SPECIAL CONDITIONS ──
            'berkebutuhan_khusus'      => $personal->is_special_condition ?? 'no',
            'jenis_kebutuhan_khusus'   => $personal->special_condition_type ?? null,
            'deskripsi_kebutuhan'      => $personal->condition_description ?? null,

            // ── GROUP 5: HEALTH & INTEREST ──
            'tinggi_badan'             => $personal->height ?? null,
            'berat_badan'              => $personal->weight ?? null,
            'riwayat_penyakit'         => $personal->medical_history ?? null,
            'minat_seni'               => $personal->interest_art ?? null,
            'minat_olahraga'           => $personal->interest_sport ?? null,
            'minat_organisasi'         => $personal->interest_organization ?? null,
            'pilihan_ekskul'           => $personal->extracurricular_choice ?? null,
            'kategori_fl2sn'           => $personal->fl2sn_category ?? null,
            'kategori_o2sn'            => $personal->o2sn_category ?? null,
            'foto_profil'              => $personal->photo ?? null,

            // ── ARRAY ORANG TUA ──
            'orang_tua'                => $dataOrangTua,
        ];
    }
}

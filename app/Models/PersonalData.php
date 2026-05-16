<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;

class PersonalData extends Model
{

    use HasFactory, HasUuids;

    /*
    |--------------------------------------------------------------------------
    | TABLE & KEY CONFIG
    |--------------------------------------------------------------------------
    */
    protected $table = 'personal_data';

    public $incrementing = false;
    protected $keyType   = 'string';

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |
    | Kolom _hash dan _encrypted tidak perlu di-list manual di fillable karena
    | kita pakai $guarded. Kolom yang TIDAK boleh diisi mass-assign hanya 'id'.
    |--------------------------------------------------------------------------
    */
    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | HIDDEN FIELDS
    |
    | Semua kolom *_encrypted disembunyikan dari serialisasi (toArray / toJson)
    | agar ciphertext tidak bocor ke response API / blade {{ $model }}.
    | Gunakan accessor (get___Attribute) untuk membaca nilai aslinya.
    |--------------------------------------------------------------------------
    */
    protected $hidden = [
        'nisn_encrypted',
        'nik_encrypted',
        'pob_encrypted',
        'dob_encrypted',
        'religion_encrypted',
        'email_encrypted',
        'phone_number_encrypted',
        'address_encrypted',
        'rt_encrypted',
        'rw_encrypted',
        'village_encrypted',
        'district_encrypted',
        'regency_encrypted',
        'province_encrypted',
        'postal_code_encrypted',
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */
    protected $casts = [
        'child_order'       => 'integer',
        'number_of_siblings' => 'integer',
        'profile_status'    => 'string',
        'is_special_condition' => 'string',
    ];

    /*
    |==========================================================================
    | RELASI
    |==========================================================================
    */

    /**
     * Pemilik data (siswa yang login).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Data orang tua / wali.
     * 1 personal_data → banyak parent_data (father, mother, guardian).
     */
    public function parents(): HasMany
    {
        return $this->hasMany(ParentData::class, 'personal_data_id', 'id');
    }

    /**
     * Shortcut: hanya data ayah.
     */
    public function father(): HasMany
    {
        return $this->hasMany(ParentData::class, 'personal_data_id', 'id')
            ->where('relationship', 'father');
    }

    /**
     * Shortcut: hanya data ibu.
     */
    public function mother(): HasMany
    {
        return $this->hasMany(ParentData::class, 'personal_data_id', 'id')
            ->where('relationship', 'mother');
    }

    /**
     * Shortcut: hanya data wali (opsional).
     */
    public function guardian(): HasMany
    {
        return $this->hasMany(ParentData::class, 'personal_data_id', 'id')
            ->where('relationship', 'guardian');
    }

    public function registrationData(): HasMany
    {
        return $this->hasMany(RegistrationData::class, 'personal_data_id');
    }

    /*
    |==========================================================================
    | HELPER — STATUS
    |==========================================================================
    */

    /**
     * Apakah biodata sudah final (dikunci / tidak bisa diedit)?
     */
    public function isFinal(): bool
    {
        return $this->profile_status === 'final';
    }

    /**
     * Apakah biodata masih draft (masih bisa diedit)?
     */
    public function isDraft(): bool
    {
        return $this->profile_status === 'draft';
    }

    /*
    |==========================================================================
    | ENCRYPTED ACCESSORS
    |
    | Konvensi penamaan: get{FieldName}Attribute()
    | Dipanggil lewat: $model->nisn   (bukan $model->nisn_encrypted)
    |
    | Crypt::decryptString() akan throw DecryptException jika nilai null
    | atau rusak, maka kita guard dengan nullable check.
    |==========================================================================
    */

    // ── GROUP 1: CORE IDENTITIES ──────────────────────────────────────────

    public function getNisnAttribute(): ?string
    {
        return $this->nisn_encrypted
            ? Crypt::decryptString($this->nisn_encrypted)
            : null;
    }

    public function getNikAttribute(): ?string
    {
        return $this->nik_encrypted
            ? Crypt::decryptString($this->nik_encrypted)
            : null;
    }

    public function getPobAttribute(): ?string  // Place of Birth
    {
        return $this->pob_encrypted
            ? Crypt::decryptString($this->pob_encrypted)
            : null;
    }

    public function getDobAttribute(): ?string  // Date of Birth
    {
        return $this->dob_encrypted
            ? Crypt::decryptString($this->dob_encrypted)
            : null;
    }

    public function getReligionAttribute(): ?string
    {
        return $this->religion_encrypted
            ? Crypt::decryptString($this->religion_encrypted)
            : null;
    }

    // ── GROUP 2: CONTACT & ADDRESS ────────────────────────────────────────

    public function getEmailAttribute(): ?string
    {
        return $this->email_encrypted
            ? Crypt::decryptString($this->email_encrypted)
            : null;
    }

    public function getPhoneNumberAttribute(): ?string
    {
        return $this->phone_number_encrypted
            ? Crypt::decryptString($this->phone_number_encrypted)
            : null;
    }

    public function getAddressAttribute(): ?string
    {
        return $this->address_encrypted
            ? Crypt::decryptString($this->address_encrypted)
            : null;
    }

    public function getRtAttribute(): ?string
    {
        return $this->rt_encrypted
            ? Crypt::decryptString($this->rt_encrypted)
            : null;
    }

    public function getRwAttribute(): ?string
    {
        return $this->rw_encrypted
            ? Crypt::decryptString($this->rw_encrypted)
            : null;
    }

    public function getVillageAttribute(): ?string
    {
        return $this->village_encrypted
            ? Crypt::decryptString($this->village_encrypted)
            : null;
    }

    public function getDistrictAttribute(): ?string
    {
        return $this->district_encrypted
            ? Crypt::decryptString($this->district_encrypted)
            : null;
    }

    public function getRegencyAttribute(): ?string
    {
        return $this->regency_encrypted
            ? Crypt::decryptString($this->regency_encrypted)
            : null;
    }

    public function getProvinceAttribute(): ?string
    {
        return $this->province_encrypted
            ? Crypt::decryptString($this->province_encrypted)
            : null;
    }

    public function getPostalCodeAttribute(): ?string
    {
        return $this->postal_code_encrypted
            ? Crypt::decryptString($this->postal_code_encrypted)
            : null;
    }

    /*
    |==========================================================================
    | ENCRYPTED MUTATORS
    |
    | Konvensi: set{FieldName}Attribute($value)
    | Dipanggil lewat: $model->nisn = '0012345678'
    |
    | Mutator ini akan:
    |   1. Encrypt nilai → simpan ke kolom *_encrypted
    |   2. Hash nilai    → simpan ke kolom *_hash (untuk pencarian/unique check)
    |
    | Hash pakai hash('sha256', ...) bukan bcrypt karena perlu deterministik
    | (input sama → hash sama) agar bisa dipakai WHERE clause.
    |==========================================================================
    */

    // ── GROUP 1: CORE IDENTITIES ──────────────────────────────────────────

    public function setNisnAttribute(string $value): void
    {
        $this->attributes['nisn_encrypted'] = Crypt::encryptString($value);
        $this->attributes['nisn_hash']      = hash('sha256', $value);
    }

    public function setNikAttribute(string $value): void
    {
        $this->attributes['nik_encrypted'] = Crypt::encryptString($value);
        $this->attributes['nik_hash']      = hash('sha256', $value);
    }

    public function setPobAttribute(string $value): void  // Place of Birth
    {
        $this->attributes['pob_encrypted'] = Crypt::encryptString($value);
    }

    public function setDobAttribute(string $value): void  // Date of Birth
    {
        $this->attributes['dob_encrypted'] = Crypt::encryptString($value);
        $this->attributes['dob_hash']      = hash('sha256', $value);
    }

    public function setReligionAttribute(string $value): void
    {
        $this->attributes['religion_encrypted'] = Crypt::encryptString($value);
        $this->attributes['religion_hash']      = hash('sha256', $value);
    }

    // ── GROUP 2: CONTACT & ADDRESS ────────────────────────────────────────

    public function setEmailAttribute(?string $value): void
    {
        if ($value) {
            $this->attributes['email_encrypted'] = Crypt::encryptString($value);
            $this->attributes['email_hash']      = hash('sha256', strtolower($value));
        } else {
            $this->attributes['email_encrypted'] = null;
            $this->attributes['email_hash']      = null;
        }
    }

    public function setPhoneNumberAttribute(?string $value): void
    {
        $this->attributes['phone_number_encrypted'] = $value
            ? Crypt::encryptString($value)
            : null;
    }

    public function setAddressAttribute(?string $value): void
    {
        $this->attributes['address_encrypted'] = $value
            ? Crypt::encryptString($value)
            : null;
    }

    public function setRtAttribute(?string $value): void
    {
        $this->attributes['rt_encrypted'] = $value
            ? Crypt::encryptString($value)
            : null;
    }

    public function setRwAttribute(?string $value): void
    {
        $this->attributes['rw_encrypted'] = $value
            ? Crypt::encryptString($value)
            : null;
    }

    public function setVillageAttribute(?string $value): void
    {
        $this->attributes['village_encrypted'] = $value
            ? Crypt::encryptString($value)
            : null;
    }

    public function setDistrictAttribute(?string $value): void
    {
        if ($value) {
            $this->attributes['district_encrypted'] = Crypt::encryptString($value);
            $this->attributes['district_hash']      = hash('sha256', $value);
        } else {
            $this->attributes['district_encrypted'] = null;
            $this->attributes['district_hash']      = null;
        }
    }

    public function setRegencyAttribute(?string $value): void
    {
        $this->attributes['regency_encrypted'] = $value
            ? Crypt::encryptString($value)
            : null;
    }

    public function setProvinceAttribute(?string $value): void
    {
        $this->attributes['province_encrypted'] = $value
            ? Crypt::encryptString($value)
            : null;
    }

    public function setPostalCodeAttribute(?string $value): void
    {
        $this->attributes['postal_code_encrypted'] = $value
            ? Crypt::encryptString($value)
            : null;
    }
}

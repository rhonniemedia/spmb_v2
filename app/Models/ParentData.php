<?php

namespace App\Models;

use App\Models\PersonalData;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

class ParentData extends Model
{
    use HasFactory, HasUuids;

    /*
    |--------------------------------------------------------------------------
    | TABLE & KEY CONFIG
    |--------------------------------------------------------------------------
    */
    protected $table = 'parent_data';

    public $incrementing = false;
    protected $keyType   = 'string';

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */
    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | CONSTANTS — nilai enum yang valid
    |
    | Dipakai di Controller saat validasi, tidak perlu hardcode string di mana-mana.
    |--------------------------------------------------------------------------
    */
    const RELATIONSHIP_FATHER   = 'father';
    const RELATIONSHIP_MOTHER   = 'mother';
    const RELATIONSHIP_GUARDIAN = 'guardian';

    const STATUS_ALIVE    = 'alive';
    const STATUS_DECEASED = 'deceased';

    /*
    |--------------------------------------------------------------------------
    | HIDDEN FIELDS — ciphertext tidak bocor ke response
    |--------------------------------------------------------------------------
    */
    protected $hidden = [
        'name_encrypted',
        'nik_encrypted',
        'phone_number_encrypted',
        'address_encrypted',
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */
    protected $casts = [
        'living_status' => 'string',
        'relationship'  => 'string',
    ];

    /*
    |==========================================================================
    | RELASI
    |==========================================================================
    */

    /**
     * Data pribadi siswa yang memiliki record orang tua ini.
     */
    public function personalData(): BelongsTo
    {
        return $this->belongsTo(PersonalData::class, 'personal_data_id', 'id');
    }

    /*
    |==========================================================================
    | HELPER — STATUS & RELATIONSHIP
    |==========================================================================
    */

    public function isFather(): bool
    {
        return $this->relationship === self::RELATIONSHIP_FATHER;
    }

    public function isMother(): bool
    {
        return $this->relationship === self::RELATIONSHIP_MOTHER;
    }

    public function isGuardian(): bool
    {
        return $this->relationship === self::RELATIONSHIP_GUARDIAN;
    }

    public function isAlive(): bool
    {
        return $this->living_status === self::STATUS_ALIVE;
    }

    public function isDeceased(): bool
    {
        return $this->living_status === self::STATUS_DECEASED;
    }

    /**
     * Label relationship dalam Bahasa Indonesia — untuk ditampilkan di blade.
     */
    public function getRelationshipLabelAttribute(): string
    {
        return match ($this->relationship) {
            self::RELATIONSHIP_FATHER   => 'Ayah Kandung',
            self::RELATIONSHIP_MOTHER   => 'Ibu Kandung',
            self::RELATIONSHIP_GUARDIAN => 'Wali',
            default                     => 'Tidak Diketahui',
        };
    }

    /*
    |==========================================================================
    | ENCRYPTED ACCESSORS
    |
    | Kolom yang dienkripsi di parent_data:
    |   - name_encrypted         → nama orang tua / wali
    |   - nik_encrypted          → NIK (nullable, bisa null jika meninggal)
    |   - phone_number_encrypted → nomor HP (nullable)
    |   - address_encrypted      → alamat (nullable, jika beda dengan siswa)
    |==========================================================================
    */

    public function getNameAttribute(): ?string
    {
        return $this->name_encrypted
            ? Crypt::decryptString($this->name_encrypted)
            : null;
    }

    public function getNikAttribute(): ?string
    {
        return $this->nik_encrypted
            ? Crypt::decryptString($this->nik_encrypted)
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

    /*
    |==========================================================================
    | ENCRYPTED MUTATORS
    |
    | Catatan khusus NIK, HP, dan Alamat: nilainya nullable karena:
    |   - NIK tidak wajib jika status = deceased
    |   - HP dan alamat opsional
    |
    | Untuk nama: wajib ada, tidak nullable.
    |==========================================================================
    */

    public function setNameAttribute(string $value): void
    {
        $this->attributes['name_encrypted'] = Crypt::encryptString($value);
    }

    public function setNikAttribute(?string $value): void
    {
        // NIK tidak di-hash di parent_data karena tidak ada kolom nik_hash
        // (berbeda dengan personal_data yang punya nik_hash untuk unique check)
        $this->attributes['nik_encrypted'] = $value
            ? Crypt::encryptString($value)
            : null;
    }

    public function setPhoneNumberAttribute(?string $value): void
    {
        if ($value) {
            $this->attributes['phone_number_encrypted'] = Crypt::encryptString($value);
            $this->attributes['phone_number_hash']      = hash('sha256', $value);
        } else {
            $this->attributes['phone_number_encrypted'] = null;
            $this->attributes['phone_number_hash']      = null;
        }
    }

    public function setAddressAttribute(?string $value): void
    {
        $this->attributes['address_encrypted'] = $value
            ? Crypt::encryptString($value)
            : null;
    }
}

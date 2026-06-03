<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Concentration extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'concentrations';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = ['id'];

    protected $casts = [
        'tags' => 'array',
    ];

    // ── Relasi ke RegistrationData ───────────────────────────────────────────

    // Pendaftar yang memilih konsentrasi ini sebagai pilihan 1
    public function registrationsAsChoice1(): HasMany
    {
        return $this->hasMany(RegistrationData::class, 'choice_1');
    }

    // Pendaftar yang memilih konsentrasi ini sebagai pilihan 2
    public function registrationsAsChoice2(): HasMany
    {
        return $this->hasMany(RegistrationData::class, 'choice_2');
    }

    // Pendaftar yang memilih konsentrasi ini sebagai pilihan 3
    public function registrationsAsChoice3(): HasMany
    {
        return $this->hasMany(RegistrationData::class, 'choice_3');
    }

    // Pendaftar yang diterima di konsentrasi ini
    public function acceptedApplicants(): HasMany
    {
        return $this->hasMany(SelectionResult::class, 'accepted_concentration_id');
    }
 
    // ── Helper: Hitung rasio peminat / kuota ────────────────────────────────

    /**
     * Contoh: 347 / 64 = 5.4x
     * Dipakai di progress bar Kuota & Peminat.
     */
    public function getDemandRatioAttribute(): float
    {
        if ($this->quota === 0) return 0;
        return round(($this->applicant_count ?? 0) / $this->quota, 1);
    }

    /**
     * Persentase bar (max 100%) untuk progress bar.
     */
    public function getQuotaBarPercentAttribute(): int
    {
        if ($this->quota === 0) return 0;
        return min(100, (int)(($this->applicant_count ?? 0) / $this->quota * 100));
    }
}

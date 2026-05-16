<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationAfirmasi extends Model
{
    use HasUuids;

    protected $table = 'registration_afirmasis';

    protected $guarded = ['id'];

    protected $casts = [
        'has_social_card' => 'boolean', // Otomatis cast 1/0 dari view ke true/false
    ];

    /**
     * Balikan relasi ke Data Registrasi Utama
     */
    public function registrationData(): BelongsTo
    {
        return $this->belongsTo(RegistrationData::class, 'registration_data_id');
    }
}

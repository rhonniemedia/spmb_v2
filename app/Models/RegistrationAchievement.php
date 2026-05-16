<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationAchievement extends Model
{
    use HasUuids;

    protected $table = 'registration_achievements';

    protected $guarded = ['id'];

    /**
     * Balikan relasi ke Data Registrasi Utama
     */
    public function registrationData(): BelongsTo
    {
        return $this->belongsTo(RegistrationData::class, 'registration_data_id');
    }
}

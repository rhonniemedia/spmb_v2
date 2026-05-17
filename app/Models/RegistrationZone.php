<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationZone extends Model
{
    use HasUuids;

    protected $table = 'registration_zones';

    protected $guarded = ['id'];

    protected $casts = [
        'house_latitude' => 'float',
        'house_longitude' => 'float',
        'calculated_distance_meters' => 'double',
    ];

    /**
     * Balikan relasi ke Data Registrasi Utama
     */
    public function registrationData(): BelongsTo
    {
        return $this->belongsTo(RegistrationData::class, 'registration_data_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RegistrationData extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'registration_data';

    protected $guarded = ['id'];

    protected $casts = [
        'submitted_at' => 'datetime',
        'report_sem_1' => 'float',
        'report_sem_2' => 'float',
        'report_sem_3' => 'float',
        'report_sem_4' => 'float',
        'report_sem_5' => 'float',
        'report_average' => 'float',
        'tka_math' => 'float',
        'tka_indonesian' => 'float',
        'tka_average' => 'float',
    ];

    public function personalData(): BelongsTo
    {
        return $this->belongsTo(PersonalData::class, 'personal_data_id');
    }

    /**
     * Relasi ke Jalur Pendaftaran (Master Data)
     */
    public function admissionPath(): BelongsTo
    {
        return $this->belongsTo(AdmissionPath::class, 'admission_path_id');
    }

    /**
     * Relasi Ekstensi: Jalur Afirmasi (1:1 Bersyarat)
     */
    public function afirmasi(): HasOne
    {
        return $this->hasOne(RegistrationAfirmasi::class, 'registration_data_id');
    }

    /**
     * Relasi Ekstensi: Jalur Zonasi (1:1 Bersyarat)
     */
    public function zonasi(): HasOne
    {
        return $this->hasOne(RegistrationZonasi::class, 'registration_data_id');
    }

    /**
     * Relasi Ekstensi: Jalur Prestasi (1:1 Bersyarat)
     */
    public function prestasi(): HasOne
    {
        return $this->hasOne(RegistrationAchievement::class, 'registration_data_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdmissionPath extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'admission_paths';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = ['id'];

    /**
     * Cast attributes ke tipe data asli.
     * Sangat berguna untuk field JSON seperti tags/persyaratan tambahan.
     */
    protected $casts = [
        'tags' => 'array',
        'quota_percentage' => 'integer',
    ];

    /**
     * Relasi ke data Pendaftaran / Calon Siswa.
     * Satu jalur pendaftaran memiliki banyak pendaftar.
     * * (Asumsi nama model pendaftaran adalah 'Registration' atau 'Applicant')
     */
    public function registrations(): HasMany
    {
        // Ganti 'Registration::class' sesuai dengan nama Model pendaftaran di aplikasimu
        // foreign_key otomatis mendeteksi 'admission_path_id' karena nama model ini AdmissionPath
        return $this->hasMany(RegistrationData::class, 'admission_path_id');
    }

    /**
     * Contoh Scope untuk mempermudah query jalur yang aktif atau berdasarkan kuota tertentu jika diperlukan
     */
    public function scopePopular($query)
    {
        return $query->withCount('registrations')->orderBy('registrations_count', 'desc');
    }
}

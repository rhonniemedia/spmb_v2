<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
     * SUMBER KEBENARAN TUNGGAL untuk "hasil seleksi terbaru" (batch tertinggi)
     * milik pendaftaran ini.
     *
     * Dipakai oleh SEMUA tempat yang perlu menampilkan status/konsentrasi
     * kelulusan seorang siswa: halaman cek kelulusan, dashboard, dan cetak
     * PDF. Jangan query SelectionResult secara manual di controller lain —
     * selalu pakai relasi ini agar tidak ada lagi celah data yang berbeda
     * antar halaman.
     *
     * `latestOfMany('batch')` adalah pola resmi Laravel untuk relasi
     * "hasOne" yang otomatis mengambil baris dengan nilai 'batch' TERTINGGI
     * per registration_id (dihitung ulang tiap kali relasi di-load /
     * eager-load, bukan dihitung sekali secara global seperti bug
     * sebelumnya di scopeLatestBatch()).
     */
    public function latestSelectionResult(): HasOne
    {
        return $this->hasOne(SelectionResult::class, 'registration_id')
            ->latestOfMany('batch');
    }

    /**
     * @deprecated Ambigu (tidak terurut per-batch) — pakai latestSelectionResult().
     * Dibiarkan untuk kompatibilitas mundur bila masih direferensikan di
     * tempat lain, TAPI jangan dipakai untuk logika baru.
     */
    public function selectionResult(): HasOne
    {
        return $this->hasOne(SelectionResult::class, 'registration_id');
    }

    public function selectionResults(): HasMany
    {
        return $this->hasMany(SelectionResult::class, 'registration_id');
    }

    /**
     * Relasi ke data daftar ulang (re_registration_data).
     * Pasangan dari ReRegistrationData::registrationData() yang sudah ada,
     * tapi sisi ini belum pernah didefinisikan — padahal sudah dipakai
     * di UserDashboardController lewat with('reRegistrationData').
     */
    public function reRegistrationData(): HasOne
    {
        return $this->hasOne(ReRegistrationData::class, 'registration_data_id');
    }

    /**
     * Relasi Ekstensi: Jalur Afirmasi (1:1 Bersyarat)
     */
    public function afirmasi(): HasOne
    {
        return $this->hasOne(RegistrationAffirmation::class, 'registration_data_id');
    }

    /**
     * Relasi Ekstensi: Jalur Zonasi (1:1 Bersyarat)
     */
    public function zonasi(): HasOne
    {
        return $this->hasOne(RegistrationZone::class, 'registration_data_id');
    }

    /**
     * Relasi Ekstensi: Jalur Prestasi (1:1 Bersyarat)
     */
    public function prestasi(): HasOne
    {
        return $this->hasOne(RegistrationAchievement::class, 'registration_data_id');
    }

    public function choice1(): BelongsTo
    {
        return $this->belongsTo(Concentration::class, 'choice_1');
    }

    public function choice2(): BelongsTo
    {
        return $this->belongsTo(Concentration::class, 'choice_2');
    }

    public function choice3(): BelongsTo
    {
        return $this->belongsTo(Concentration::class, 'choice_3');
    }

    public function achievements(): HasMany
    {
        // Menggunakan hasMany agar konsisten dengan proses delete() di Controller
        return $this->hasMany(RegistrationAchievement::class, 'registration_data_id');
    }

    public function documents()
    {
        // Mengambil semua dokumen yang sudah diunggah oleh siswa ini beserta detail requirement-nya
        return $this->hasMany(RegistrationDocument::class, 'registration_data_id');
    }

    public function observationData()
    {
        return $this->hasOne(ObservationData::class, 'registration_id');
    }

    public function choice1Concentration()
    {
        // Assuming 'choice_1' is the foreign key column in your registration_data table
        return $this->belongsTo(Concentration::class, 'choice_1');
    }

    public function registrationDocuments()
    {
        // Assuming 'registration_data_id' is the foreign key on the registration_documents table
        return $this->hasMany(RegistrationDocument::class, 'registration_data_id');
    }

    public function choice2Concentration()
    {
        return $this->belongsTo(Concentration::class, 'choice_2');
    }

    public function choice3Concentration()
    {
        return $this->belongsTo(Concentration::class, 'choice_3');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

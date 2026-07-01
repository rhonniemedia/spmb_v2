<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SelectionResult extends Model
{
    use HasUuids;

    protected $fillable = [
        'registration_id',
        'batch',
        'status',
        'accepted_concentration_id',
        'accepted_in_choice',
        'score_rapor',
        'score_tka',
        'score_observasi',
        'score_prestasi',
        'final_score',
        'rank_in_path',
        'rank_in_concentration',
        'processed_by',
        'processed_at',
        'selection_notes',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
        'final_score'  => 'float',
        'score_rapor'  => 'float',
        'score_tka'    => 'float',
        'score_observasi' => 'float',
        'score_prestasi'  => 'float',
    ];

    // ── RELASI ──────────────────────────────────────────

    public function registration(): BelongsTo
    {
        return $this->belongsTo(RegistrationData::class, 'registration_id');
    }

    public function acceptedConcentration(): BelongsTo
    {
        return $this->belongsTo(Concentration::class, 'accepted_concentration_id');
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // ── SCOPE ───────────────────────────────────────────

    /**
     * Ambil hanya hasil dari batch terbaru — PER registration_id.
     *
     * PENTING: sebelumnya scope ini memakai `static::max('batch')` yang
     * mengambil batch tertinggi di SELURUH tabel `selection_results`
     * (lintas semua pendaftar), bukan batch tertinggi milik pendaftar yang
     * sedang di-query. Akibatnya jika satu siswa sudah sampai batch 3
     * (penjenjangan) sementara siswa lain baru sampai batch 1/2, siswa yang
     * belum sampai batch 3 tidak akan cocok sama sekali (hasil null) atau
     * bisa salah ambil baris — inilah yang menyebabkan konsentrasi yang
     * tampil di halaman kelulusan berbeda dari sumber data lain yang sudah
     * benar (ReportController, dashboard) yang memakai
     * `orderByDesc('batch')->first()` per pendaftaran.
     *
     * Diperbaiki memakai correlated subquery: ambil baris yang batch-nya
     * sama dengan MAX(batch) UNTUK registration_id yang sama pada baris itu
     * sendiri, sehingga tetap benar berapa pun filter registration_id yang
     * sudah diterapkan di $query sebelumnya.
     */
    public function scopeLatestBatch($query)
    {
        $table = $this->getTable();

        return $query->whereRaw(
            "`{$table}`.`batch` = (
                select max(`t2`.`batch`)
                from `{$table}` as `t2`
                where `t2`.`registration_id` = `{$table}`.`registration_id`
            )"
        );
    }

    /**
     * Ambil hanya peserta yang diterima.
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function registrationData()
    {
        // Pastikan memanggil class model yang benar (misal: RegistrationData::class atau Registration::class)
        return $this->belongsTo(RegistrationData::class, 'registration_id');
    }
}

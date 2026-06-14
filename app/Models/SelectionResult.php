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
     * Ambil hanya hasil dari batch terbaru.
     */
    public function scopeLatestBatch($query)
    {
        $latestBatch = static::max('batch') ?? 1;
        return $query->where('batch', $latestBatch);
    }

    /**
     * Ambil hanya peserta yang diterima.
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }
}

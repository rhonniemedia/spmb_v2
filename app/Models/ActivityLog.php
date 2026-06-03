<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'registration_data_id',
        'action',
        'description',
        'context',
    ];

    // ── Relasi ──────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function registrationData(): BelongsTo
    {
        return $this->belongsTo(RegistrationData::class);
    }

    // ── Helper: Catat log dari mana saja ────────────────

    /**
     * Shortcut statis untuk mencatat aktivitas.
     *
     * Contoh pemakaian di controller:
     *   ActivityLog::record('verified', $registration, 'Dokumen Ahmad Fauzi diverifikasi', 'Ijazah & rapor valid — RPL');
     */
    public static function record(
        string $action,
        ?RegistrationData $registration = null,
        string $description = '',
        ?string $context = null,
    ): self {
        return self::create([
            'user_id'              => Auth::id(),
            'registration_data_id' => $registration?->id,
            'action'               => $action,
            'description'          => $description,
            'context'              => $context,
        ]);
    }

    // ── Helper: Mapping action → icon & warna ───────────

    /**
     * Kembalikan config icon & warna berdasarkan action.
     * Dipakai di blade untuk render badge/icon feed.
     */
    public function getIconConfigAttribute(): array
    {
        return match ($this->action) {
            'verified', 'document_verified', 'accepted', 'observation_passed' => [
                'icon'       => 'circle-check',
                'bg'         => 'bg-success/10',
                'text'       => 'text-success',
            ],
            'rejected', 'document_rejected', 'observation_failed' => [
                'icon'       => 'circle-x',
                'bg'         => 'bg-error/10',
                'text'       => 'text-error',
            ],
            're_registered' => [
                'icon'       => 'user-check',
                'bg'         => 'bg-info/10',
                'text'       => 'text-info',
            ],
            'submitted' => [
                'icon'       => 'send',
                'bg'         => 'bg-primary/10',
                'text'       => 'text-primary',
            ],
            default => [ // system
                'icon'       => 'settings',
                'bg'         => 'bg-muted',
                'text'       => 'text-secondary',
            ],
        };
    }

    /**
     * Kembalikan label waktu relatif (10 menit lalu, dst).
     */
    public function getTimeAgoAttribute(): string
    {
        $diff = now()->diffInSeconds($this->created_at);

        if ($diff < 60)     return "{$diff} detik lalu";
        if ($diff < 3600)   return now()->diffInMinutes($this->created_at) . ' menit lalu';
        if ($diff < 86400)  return now()->diffInHours($this->created_at) . ' jam lalu';

        return $this->created_at->translatedFormat('d M Y');
    }
}

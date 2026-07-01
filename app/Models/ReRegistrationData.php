<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReRegistrationData extends Model
{
    use HasFactory, HasUuids;

    protected $table = 're_registration_data';

    protected $guarded = ['id'];

    protected $casts = [
        'announced_at'     => 'datetime',
        'confirmed_at'     => 'datetime',
        're_registered_at' => 'datetime',
        'verified_at'      => 'datetime',
        'completed_at'     => 'datetime',
    ];

    public function registrationData()
    {
        return $this->belongsTo(RegistrationData::class, 'registration_data_id');
    }

    // ── TAMBAHAN: siapa admin/verifikator yang memutuskan verifikasi ──
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}

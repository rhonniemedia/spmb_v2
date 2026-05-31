<?php

namespace App\Models;

use App\Notifications\VerifyEmailQueued;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasUuids, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'email_verified_at',
        'role'
    ];

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailQueued);
    }

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function personalData(): HasOne
    {
        return $this->hasOne(PersonalData::class, 'user_id', 'id');
    }

    public function getRealNameAttribute(): string
    {
        // Cek apakah user memiliki relasi personalData dan kolom full_name tidak kosong
        if ($this->personalData && !empty($this->personalData->full_name)) {
            return $this->personalData->full_name;
        }

        // Jika personal data belum diisi, kembalikan nama default (bawaan google login)
        return $this->name;
    }

    public function getFirstNameAttribute(): string
    {
        // Pecah nama menjadi array kata
        $words = explode(' ', $this->real_name);

        // Jika hanya ada 1 kata, langsung kembalikan kata tersebut
        if (count($words) <= 1) {
            return $words[0];
        }

        // Bersihkan kata pertama dari titik untuk mengecek panjangnya (misal "M." jadi "M")
        $firstWordClean = str_replace('.', '', $words[0]);

        // Jika kata pertama sangat pendek (<= 3 huruf, kemungkinan singkatan/gelar seperti M., H., Moh, Andi, Dr)
        // Dan jika ada kata kedua, maka gunakan kata kedua sebagai panggilan.
        if (strlen($firstWordClean) <= 3 && isset($words[1])) {
            return $words[1];
        }

        // Jika normal, gunakan kata pertama
        return $words[0];
    }
}

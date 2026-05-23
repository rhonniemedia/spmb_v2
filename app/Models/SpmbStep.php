<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpmbStep extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'spmb_steps';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = ['id'];

    protected $casts = [
        'tags'               => 'array',
        'show_statuses'      => 'boolean',
        'show_result_badges' => 'boolean',
        'is_highlight'       => 'boolean',
    ];

    protected function status(): Attribute
    {
        return Attribute::get(function () {
            $now = Carbon::now()->startOfDay();

            // Menggunakan startOfDay dan endOfDay agar perbandingan tanggal lebih akurat
            $start = $this->start_date ? Carbon::parse($this->start_date)->startOfDay() : null;
            $end = $this->end_date ? Carbon::parse($this->end_date)->endOfDay() : null;

            // 1. Jika start_date belum diisi, atau hari ini belum mencapai tanggal mulai
            if ($start && $now->lt($start)) {
                return 'pending';
            }

            // 2. Jika tanggal akhir sudah terlewati
            if ($end && $now->gt($end)) {
                return 'done';
            }

            // 3. Jika berada di antara start_date dan end_date (atau tanggal kosong/tidak dibatasi)
            return 'active';
        });
    }
}

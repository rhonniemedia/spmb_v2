<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        School::updateOrCreate(
            ['npsn' => '10700610'], // Unique identifier
            [
                'name' => 'SMK Negeri 1 Rejang Lebong',
                'npsn' => '10700610',
                'nis' => null,
                'nss' => null,
                'nds' => null,
                'address' => 'Jl. Ahmad Marzuki 105',
                'postal_code' => '39111',
                'village' => 'Air Rambai',
                'district' => 'Curup',
                'city' => 'Rejang Lebong',
                'province' => 'Bengkulu',
                'phone' => '073221258',
                'website' => 'https://smkn1rl.sch.id/',
                'email' => 'mail@smkn1rl.sch.id',
                'school_logo_path' => null,
                'government_logo_path' => null,

                // ── DATA SEEDER UNTUK KOLOM WHATSAPP (JSON ARRAY) ──
                'whatsapp_numbers' => [
                    '081234567890', // Nomor Utama / Admin PPDB
                    '089876543210'  // Nomor Cadangan / Humas
                ],

                // ── DATA SEEDER UNTUK SOSIAL MEDIA (JSON ARRAY OF OBJECTS) ──
                'social_media' => [
                    [
                        'platform' => 'instagram',
                        'url' => 'https://www.instagram.com/smkn1rejanglebong/'
                    ],
                    [
                        'platform' => 'facebook',
                        'url' => 'https://www.facebook.com/smkn1rejanglebong/'
                    ],
                    [
                        'platform' => 'youtube',
                        'url' => 'https://www.youtube.com/@smkn1rejanglebong'
                    ],
                    [
                        'platform' => 'tiktok',
                        'url' => 'https://www.tiktok.com/@smkn1rejanglebong'
                    ]
                ],
            ]
        );
    }
}

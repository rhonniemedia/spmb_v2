<?php

namespace Database\Seeders;

use App\Models\AdmissionPath;
use Illuminate\Database\Seeder;

class AdmissionPathSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pathways = [
            [
                'name' => 'Jalur Reguler',
                'subtitle' => 'Pendaftaran umum tanpa syarat khusus',
                'description' => 'Terbuka untuk seluruh lulusan SMP/MTs yang memenuhi persyaratan umum. Seleksi dilakukan berdasarkan nilai rapor dan hasil TKA yang telah diinputkan.',
                'tags' => ['Terbuka umum', 'Langsung ke pilih jurusan'],
                'quota_percentage' => 65,
                'color_theme' => 'red',
                'icon' => 'fa-user-graduate',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jalur Zonasi',
                'subtitle' => 'Berdasarkan jarak domisili ke sekolah',
                'description' => 'Diperuntukkan bagi calon peserta yang berdomisili di zona wilayah sekolah. Jarak dihitung dari titik koordinat rumah ke sekolah menggunakan formula geospatial.',
                'tags' => ['Domisili KK', 'Prioritas utama'],
                'quota_percentage' => 10,
                'color_theme' => 'green',
                'icon' => 'fa-map-location-dot',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jalur Prestasi',
                'subtitle' => 'Nilai akademik & penghargaan kompetisi',
                'description' => 'Diperuntukkan bagi peserta dengan nilai rapor unggul atau memiliki prestasi kompetisi akademik/non-akademik di tingkat kabupaten ke atas.',
                'tags' => ['Sertifikat prestasi', 'Min. rata-rata 80'],
                'quota_percentage' => 10,
                'color_theme' => 'amber',
                'icon' => 'fa-trophy',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jalur Afirmasi',
                'subtitle' => 'Keluarga tidak mampu & disabilitas',
                'description' => 'Diperuntukkan bagi peserta dari keluarga penerima bantuan sosial pemerintah (PKH/KIP) atau peserta dengan kebutuhan khusus yang tercatat secara resmi.',
                'tags' => ['Kartu KIP/PKH', 'Surat keterangan resmi'],
                'quota_percentage' => 15,
                'color_theme' => 'indigo',
                'icon' => 'fa-hand-holding-heart',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($pathways as $item) {
            AdmissionPath::updateOrCreate(
                ['name' => $item['name']], // Kunci pencarian: Nama jalur
                [
                    'subtitle' => $item['subtitle'],
                    'description' => $item['description'],
                    'tags' => $item['tags'],
                    'quota_percentage' => $item['quota_percentage'],
                    'color_theme' => $item['color_theme'],
                    'icon' => $item['icon'],
                    'is_active' => true,
                ]
            );
        }
    }
}

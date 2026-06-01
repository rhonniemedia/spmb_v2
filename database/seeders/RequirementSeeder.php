<?php

namespace Database\Seeders;

use App\Models\Requirement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RequirementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'category' => 'dokumen',
                'name' => 'Akta Kelahiran Asli',
                'description' => 'Akta kelahiran Asli calon murid baru',
                'is_mandatory' => true,
                'icon' => 'fa-solid fa-file-invoice',
                'color_theme' => 'emerald'
            ],
            [
                'category' => 'dokumen',
                'name' => 'Ijazah SMP / Sederajat',
                'description' => 'Ijazah SMP Asli dari satuan pendidikan sebelumnya',
                'is_mandatory' => true,
                'icon' => 'fa-solid fa-certificate',
                'color_theme' => 'emerald'
            ],
            [
                'category' => 'dokumen',
                'name' => 'Surat Keterangan Lulus (SKL)',
                'description' => 'Surat Keterangan Lulus (SKL) jika ijazah resmi belum terbit',
                'is_mandatory' => true,
                'icon' => 'fa-solid fa-file-shield',
                'color_theme' => 'emerald'
            ],
            [
                'category' => 'dokumen',
                'name' => 'Rapor Semester 1–5',
                'description' => 'Buku Rapor Asli pengisian nilai dari sekolah asal',
                'is_mandatory' => true,
                'icon' => 'fa-solid fa-school',
                'color_theme' => 'amber'
            ],
            [
                'category' => 'dokumen',
                'name' => 'Surat Keterangan Rata-rata Nilai Rapor',
                'description' => 'Surat Keterangan Rata-rata Nilai Rapor dari Semester 1 s.d. 5',
                'is_mandatory' => true,
                'icon' => 'fa-solid fa-image',
                'color_theme' => 'rose'
            ],
            [
                'category' => 'dokumen',
                'name' => 'Surat Keterangan Domisili',
                'description' => 'Surat domisili resmi dari pihak kelurahan/kecamatan setempat',
                'is_mandatory' => false,
                'icon' => 'fa-solid fa-map-location-dot',
                'color_theme' => 'slate'
            ]
        ];

        foreach ($data as $item) {
            // Otomatis membuat slug dari properti 'name'
            // Contoh: 'Akta Kelahiran' menjadi 'akta-kelahiran'
            $item['slug'] = Str::slug($item['name']);

            Requirement::create($item);
        }
    }
}

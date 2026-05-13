<?php

namespace Database\Seeders;

use App\Models\SpmbStep;
use Illuminate\Database\Seeder;

class SpmbStepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $steps = [
            [
                'title' => 'Pendaftaran Akun',
                'description' => 'Buat akun dengan NISN + verifikasi OTP. Pilih jurusan 1 & 2.',
                'period_text' => '1 – 31 Mei 2026',
                'status' => 'done',
                'step_order' => 1
            ],
            [
                'title' => 'Pengisian Biodata',
                'description' => 'Isi 6 langkah data diri, orang tua, riwayat pendidikan & unggah dokumen.',
                'period_text' => 'S.d. 31 Mei 2026',
                'status' => 'done',
                'step_order' => 2
            ],
            [
                'title' => 'Seleksi Akademik',
                'description' => 'Tes online berbasis nilai rapor dan soal akademik secara daring.',
                'period_text' => '5 – 7 Juni 2026',
                'status' => 'active',
                'step_order' => 3
            ],
            [
                'title' => 'Pengumuman Hasil',
                'description' => 'Cek status diterima/tidak langsung di dashboard peserta.',
                'period_text' => '10 Juni 2026',
                'status' => 'pending',
                'step_order' => 4
            ],
            [
                'title' => 'Daftar Ulang',
                'description' => 'Bayar biaya, pilih seragam, dan tentukan jadwal hadir ke sekolah.',
                'period_text' => '11 – 15 Juni 2026',
                'status' => 'pending',
                'step_order' => 5
            ],
        ];

        foreach ($steps as $step) {
            SpmbStep::create($step);
        }
    }
}

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
                'title'       => 'Pendaftaran dan Aktivasi Akun',
                'slug'        => 'pendaftaran-akun',
                'description' => 'Buat akun menggunakan NISN dan lakukan verifikasi aktivasi akun.',
                'period_text' => '1 Mei – 30 Juni 2026',
                'start_date'  => '2026-05-01 00:00:00',
                'end_date'    => '2026-06-30 23:59:59',
                'step_order'  => 1,
                'icon'        => 'fa-user-plus',
                'color'       => 'cyan',
            ],
            [
                'title'       => 'Pengisian Biodata',
                'slug'        => 'pengisian-biodata',
                'description' => 'Lengkapi data diri, alamat, riwayat pendidikan, serta data orang tua/wali.',
                'period_text' => '1 Mei – 30 Juni 2026',
                'start_date'  => '2026-05-01 00:00:00',
                'end_date'    => '2026-06-30 23:59:59',
                'step_order'  => 2,
                'icon'        => 'fa-file-signature',
                'color'       => 'blue',
            ],
            [
                'title'       => 'Pendaftaran SPMB',
                'slug'        => 'pendaftaran-spmb',
                'description' => 'Kunci biodata (finalisasi) dan pilih kompetensi keahlian/jurusan pendaftaran.',
                'period_text' => '1 Mei – 30 Juni 2026',
                'start_date'  => '2026-05-01 00:00:00',
                'end_date'    => '2026-06-30 23:59:59',
                'step_order'  => 3,
                'icon'        => 'fa-id-card',
                'color'       => 'purple',
            ],
            [
                'title'       => 'Verifikasi Berkas Online',
                'slug'        => 'verifikasi-berkas',
                'description' => 'Tim panitia melakukan pengecekan keabsahan dokumen pendaftaran secara digital.',
                'period_text' => '2 Mei – 1 Juli 2026',
                'start_date'  => '2026-05-02 00:00:00',
                'end_date'    => '2026-07-01 23:59:59',
                'step_order'  => 4,
                'icon'        => 'fa-user-check',
                'color'       => 'indigo',
            ],
            [
                'title'       => 'Seleksi Minat dan Bakat',
                'slug'        => 'seleksi-minat-bakat',
                'description' => 'Pelaksanaan tes seleksi minat dan bakat bagi calon peserta didik.',
                'period_text' => '2 – 24 Juni 2026 (s.d Pukul 15.00 WIB)',
                'start_date'  => '2026-06-02 00:00:00',
                'end_date'    => '2026-06-24 15:00:00',
                'step_order'  => 5,
                'icon'        => 'fa-laptop-code',
                'color'       => 'amber',
            ],
            [
                'title'       => 'Pengumuman Hasil',
                'slug'        => 'pengumuman-hasil',
                'description' => 'Melihat hasil seleksi kelulusan akhir langsung melalui dashboard.',
                'period_text' => '25 Juni 2026 (Pukul 12.00 WIB)',
                'start_date'  => '2026-06-25 12:00:00',
                'end_date'    => '2026-06-25 23:59:59',
                'step_order'  => 6,
                'icon'        => 'fa-bullhorn',
                'color'       => 'emerald',
            ],
            [
                'title'       => 'Daftar Ulang',
                'slug'        => 'daftar-ulang',
                'description' => 'Proses registrasi ulang, verifikasi berkas fisik, dan administrasi seragam.',
                'period_text' => '26 – 27 Juni 2026 (s.d Pukul 16.00 WIB)',
                'start_date'  => '2026-06-26 00:00:00',
                'end_date'    => '2026-06-27 16:00:00',
                'step_order'  => 7,
                'icon'        => 'fa-flag-checkered',
                'color'       => 'rose',
            ],
        ];

        foreach ($steps as $step) {
            SpmbStep::create($step);
        }
    }
}

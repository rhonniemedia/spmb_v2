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
                'title'       => 'Pendaftaran Akun',
                'slug'        => 'pendaftaran-akun',
                'description' => 'Membuat akun pendaftaran menggunakan NISN aktif dan melakukan aktivasi melalui email.',
                'period_text' => '01 Mei - 24 Juni 2026',
                'start_date'  => '2026-05-01 00:00:00',
                'end_date'    => '2026-06-24 15:00:00',
                'step_order'  => 1,
                'icon'        => 'fa-user-plus',
                'color'       => 'cyan',
            ],
            [
                'title'       => 'Pengisian Biodata',
                'slug'        => 'pengisian-biodata',
                'description' => 'Melengkapi data diri, asal sekolah, data orang tua/wali, serta mengunggah pasfoto resmi.',
                'period_text' => '01 Mei - 24 Juni 2026',
                'start_date'  => '2026-05-01 00:00:00',
                'end_date'    => '2026-06-24 15:00:00',
                'step_order'  => 2,
                'icon'        => 'fa-id-card',
                'color'       => 'blue',
            ],
            [
                'title'       => 'Pendaftaran SPMB',
                'slug'        => 'pendaftaran-spmb',
                'description' => 'Kunci biodata (finalisasi) dan pilih kompetensi keahlian/jurusan pendaftaran.',
                'period_text' => '02 Juni - 24 Juni 2026',
                'start_date'  => '2026-06-02 00:00:00',
                'end_date'    => '2026-06-24 15:00:00',
                'step_order'  => 3, // <── Berlanjut ke nomor 4
                'icon'        => 'fa-id-card',
                'color'       => 'purple',
            ],
            [
                'title'       => 'Verifikasi Dokumen',
                'slug'        => 'verifikasi-dokumen',
                'description' => 'Proses pengecekan dan validasi berkas pendaftaran oleh tim panitia seleksi SPMB.',
                'period_text' => '02 Juni - 24 Juni 2026',
                'start_date'  => '2026-06-02 00:00:00',
                'end_date'    => '2026-06-24 15:00:00',
                'step_order'  => 4,
                'icon'        => 'fa-file-shield',
                'color'       => 'purple',
            ],
            [
                'title'       => 'Seleksi Academic',
                'slug'        => 'seleksi-akademik',
                'description' => 'Pelaksanaan ujian seleksi untuk mengukur kemampuan akademik.',
                'period_text' => '23 - 24 Juni 2026',
                'start_date'  => '2026-06-02 00:00:00',
                'end_date'    => '2026-06-24 15:00:00',
                'step_order'  => 5,
                'icon'        => 'fa-laptop-code',
                'color'       => 'amber',
            ],
            [
                'title'       => 'Pengumuman Hasil',
                'slug'        => 'pengumuman-hasil',
                'description' => 'Melihat hasil seleksi kelulusan akhir langsung melalui halaman dashboard akun Anda.',
                'period_text' => '25 Juni 2026',
                'start_date'  => '2026-06-25 12:00:00',
                'end_date'    => '2026-06-25 23:59:59',
                'step_order'  => 6,
                'icon'        => 'fa-bullhorn',
                'color'       => 'emerald',
            ],
            [
                'title'       => 'Daftar Ulang & Penyerahan Berkas',
                'slug'        => 'daftar-ulang-dan-penyerahan-berkas',
                'description' => 'Proses registrasi ulang secara sistem dan penyerahan dokumen fisik persyaratan ke kampus.',
                'period_text' => '26 - 27 Juni 2026',
                'start_date'  => '2026-06-26 00:00:00',
                'end_date'    => '2026-06-27 16:00:00',
                'step_order'  => 7,
                'icon'        => 'fa-user-check',
                'color'       => 'indigo',
            ],
            [
                'title'       => 'Masa Orientasi Siswa',
                'slug'        => 'masa-orientasi-siswa',
                'description' => 'Kegiatan pengenalan lingkungan sekolah, program akademis, tata tertib, dan budaya kampus.',
                'period_text' => '13 - 14 Juli 2026',
                'start_date'  => '2026-07-13 00:00:00',
                'end_date'    => '2026-07-14 23:59:59',
                'step_order'  => 8,
                'icon'        => 'fa-users-gear',
                'color'       => 'teal',
            ],
        ];

        foreach ($steps as $step) {
            SpmbStep::create($step);
        }
    }
}

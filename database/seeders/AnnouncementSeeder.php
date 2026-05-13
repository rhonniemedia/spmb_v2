<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $data = [
            [
                'title' => 'Pembukaan Pendaftaran Murid Baru Gelombang 1',
                'slug' => Str::slug('Pembukaan Pendaftaran Murid Baru Gelombang 1'),
                'category' => 'Pendaftaran',
                'content' => 'Pendaftaran calon murid baru SMK Negeri 1 telah resmi dibuka. Silakan lengkapi biodata dan unggah dokumen pendukung melalui dashboard masing-masing.',
                'is_urgent' => true,
                'action_link' => null,
                'action_label' => null,
            ],
            [
                'title' => 'Hasil Seleksi Administrasi Tahap 1',
                'slug' => Str::slug('Hasil Seleksi Administrasi Tahap 1'),
                'category' => 'Hasil Seleksi',
                'content' => 'Selamat bagi para pendaftar yang lolos seleksi administrasi. Silakan unduh daftar lengkap nomor peserta yang berhak mengikuti ujian akademik melalui tombol di bawah.',
                'is_urgent' => false,
                'action_link' => 'https://drive.google.com/file/d/link-pdf-kelulusan',
                'action_label' => 'Download Daftar Lulus',
            ],
            [
                'title' => 'Perubahan Jadwal Tes Akademik Online',
                'slug' => Str::slug('Perubahan Jadwal Tes Akademik Online'),
                'category' => 'Ujian',
                'content' => 'Dikarenakan pemeliharaan server, jadwal tes akademik yang semula dijadwalkan tanggal 5 Juni diundur menjadi 7 Juni 2026. Mohon maaf atas ketidaknyamanannya.',
                'is_urgent' => true,
                'action_link' => null,
                'action_label' => null,
            ],
            [
                'title' => 'Panduan Penggunaan Aplikasi MySch bagi Siswa Baru',
                'slug' => Str::slug('Panduan Penggunaan Aplikasi MySch bagi Siswa Baru'),
                'category' => 'Informasi',
                'content' => 'Untuk memudahkan proses adaptasi, panitia telah merilis video panduan penggunaan aplikasi MySch untuk memantau nilai dan absensi.',
                'is_urgent' => false,
                'action_link' => 'https://youtube.com/watch?v=tutorial-mysch',
                'action_label' => 'Tonton Video Panduan',
            ],
            [
                'title' => 'Verifikasi Berkas Fisik di Sekolah',
                'slug' => Str::slug('Verifikasi Berkas Fisik di Sekolah'),
                'category' => 'Pendaftaran',
                'content' => 'Bagi calon siswa yang sudah melengkapi biodata online, harap hadir ke sekolah untuk verifikasi berkas fisik sesuai jadwal yang tertera di dashboard.',
                'is_urgent' => false,
                'action_link' => null,
                'action_label' => null,
            ],
        ];

        foreach ($data as $item) {
            Announcement::create([
                'title' => $item['title'],
                'slug' => $item['slug'],
                'category' => $item['category'],
                'content' => $item['content'],
                'is_urgent' => $item['is_urgent'],
                'action_link' => $item['action_link'],
                'action_label' => $item['action_label'],
                'author_id' => null,
                'is_active' => true,
            ]);
        }
    }
}

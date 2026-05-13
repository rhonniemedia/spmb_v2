<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\FaqCategories;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data FAQ lengkap berdasarkan bantuan.blade.php
        $faqData = [
            // --- KATEGORI: PENDAFTARAN ---
            'pendaftaran' => [
                [
                    'q' => 'Siapa saja yang bisa mendaftar SPMB?',
                    'a' => 'Calon peserta didik yang telah lulus atau sedang menempuh kelas 9 SMP/MTs/sederajat dengan nilai rapor memenuhi syarat minimum. Usia maksimal <strong>21 tahun</strong> per 1 Juli tahun ajaran baru.'
                ],
                [
                    'q' => 'Bagaimana cara membuat akun pendaftaran?',
                    'a' => 'Kunjungi halaman utama SPMB, klik <strong>Daftar Sekarang</strong>, masukkan nomor NISN dan tanggal lahir. Sistem akan mengirimkan kode OTP ke nomor HP yang terdaftar di Data Pokok Pendidikan (Dapodik).'
                ],
                [
                    'q' => 'Berapa jurusan yang bisa dipilih?',
                    'a' => 'Setiap calon peserta dapat memilih <strong>maksimal 2 jurusan</strong>. Pastikan pilihan pertama adalah jurusan yang paling diminati karena seleksi diprioritaskan berdasarkan pilihan pertama.'
                ],
                [
                    'q' => 'Apakah pendaftaran dikenakan biaya?',
                    'a' => 'Pendaftaran <strong>tidak dipungut biaya apapun (gratis)</strong>. Jika ada pihak yang meminta pembayaran di luar ketentuan resmi, segera laporkan ke panitia SPMB.'
                ],
                [
                    'q' => 'Kapan batas akhir pendaftaran?',
                    'a' => 'Pendaftaran dibuka <strong>1–31 Mei 2026</strong>. Setelah tanggal tersebut sistem otomatis menutup dan tidak ada perpanjangan. Pastikan semua data terisi sebelum batas waktu.'
                ],
            ],

            // --- KATEGORI: BIODATA ---
            'biodata' => [
                [
                    'q' => 'Dokumen apa saja yang harus diunggah?',
                    'a' => 'Lima dokumen wajib: <strong>Akta Kelahiran</strong>, <strong>Ijazah/SKL SMP</strong>, <strong>Rapor semester 1–5</strong>, <strong>Pas foto terbaru</strong> (3×4, latar merah), dan <strong>sertifikat prestasi</strong> (jika ada). Format: JPG/PNG/PDF, maks 2 MB per file.'
                ],
                [
                    'q' => 'Bolehkah data biodata diedit setelah disimpan?',
                    'a' => 'Data dapat diedit <strong>selama status masih "Draft"</strong>. Setelah kamu klik <em>Kirim Biodata</em>, data terkunci dan hanya bisa diubah dengan menghubungi panitia secara langsung.'
                ],
                [
                    'q' => 'Bagaimana jika upload dokumen gagal terus?',
                    'a' => 'Pastikan ukuran file <strong>di bawah 2 MB</strong> dan format JPG, PNG, atau PDF. Kompres gambar menggunakan tools seperti ilovepdf.com atau squoosh.app, lalu coba unggah kembali.'
                ],
                [
                    'q' => 'Apa fungsi tombol "Simpan Draft"?',
                    'a' => 'Tombol Simpan Draft menyimpan progres pengisian <strong>tanpa mengirimkan data</strong> ke panitia. Data akan tersimpan dan bisa dilanjutkan kapan saja sebelum batas waktu pendaftaran.'
                ],
            ],

            // --- KATEGORI: SELEKSI ---
            'seleksi' => [
                [
                    'q' => 'Apa saja kriteria seleksi penerimaan?',
                    'a' => 'Seleksi menggunakan 3 komponen: <strong>nilai rapor rata-rata (60%)</strong>, <strong>tes akademik online (30%)</strong>, dan <strong>prestasi/sertifikat (10%)</strong>. Peserta dengan nilai tertinggi diterima sesuai kuota jurusan.'
                ],
                [
                    'q' => 'Kapan jadwal tes seleksi dilaksanakan?',
                    'a' => 'Tes seleksi akademik dijadwalkan pada <strong>5–7 Juni 2026</strong> secara online melalui sistem SPMB. Peserta akan mendapatkan notifikasi jadwal dan token tes melalui email dan dashboard.'
                ],
                [
                    'q' => 'Kapan pengumuman hasil seleksi?',
                    'a' => 'Hasil seleksi diumumkan pada <strong>10 Juni 2026 pukul 08.00 WIB</strong> melalui dashboard peserta. Peserta diterima akan mendapat notifikasi untuk melanjutkan proses daftar ulang.'
                ],
                [
                    'q' => 'Apakah ada jalur khusus atau beasiswa?',
                    'a' => 'Ada <strong>Jalur Prestasi</strong> untuk calon peserta dengan piagam kejuaraan tingkat kabupaten/kota ke atas, dan <strong>Jalur Afirmasi</strong> untuk peserta dari keluarga kurang mampu (dilengkapi DTKS atau SKTM).'
                ],
            ],

            // --- KATEGORI: DAFTAR ULANG ---
            'daftarulang' => [
                [
                    'q' => 'Apa saja tahapan dalam proses daftar ulang?',
                    'a' => 'Ada 5 tahap: <strong>(1)</strong> Konfirmasi kehadiran, <strong>(2)</strong> Pemilihan seragam, <strong>(3)</strong> Pembayaran biaya daftar ulang, <strong>(4)</strong> Pilih jadwal kehadiran ke sekolah, <strong>(5)</strong> Tanda tangan pernyataan & kirim. Semua dilakukan secara online.'
                ],
                [
                    'q' => 'Batas waktu daftar ulang sampai kapan?',
                    'a' => 'Daftar ulang harus diselesaikan paling lambat <strong>15 Juni 2026</strong>. Peserta yang tidak menyelesaikan tepat waktu dianggap mengundurkan diri.'
                ],
                [
                    'q' => 'Apa yang terjadi jika tidak bisa hadir pada jadwal yang dipilih?',
                    'a' => 'Perubahan jadwal bisa dilakukan <strong>maksimal H-1 sebelum jadwal</strong> dengan menghubungi panitia via WhatsApp.'
                ],
                [
                    'q' => 'Dokumen apa yang dibawa saat hadir ke sekolah?',
                    'a' => 'Bawa dokumen asli: <strong>Ijazah/SKL</strong>, <strong>Akta Kelahiran</strong>, <strong>Kartu Keluarga</strong>, <strong>pas foto 3×4 (5 lembar)</strong>, dan <strong>bukti pembayaran daftar ulang</strong>.'
                ],
                [
                    'q' => 'Apakah orang tua wajib hadir saat daftar ulang?',
                    'a' => '<strong>Ya, wajib.</strong> Setidaknya salah satu orang tua atau wali yang terdaftar di biodata harus hadir untuk menandatangani surat pernyataan orang tua/wali.'
                ],
            ],

            // --- KATEGORI: PEMBAYARAN ---
            'pembayaran' => [
                [
                    'q' => 'Metode pembayaran apa yang tersedia?',
                    'a' => 'Tersedia 3 metode: <strong>Transfer Bank</strong> (BNI/BRI/Mandiri/BCA), <strong>QRIS</strong>, dan <strong>Virtual Account</strong> otomatis. Bukti transfer wajib diunggah.'
                ],
                [
                    'q' => 'Berapa total biaya yang harus dibayar?',
                    'a' => 'Rincian biaya: Daftar ulang Rp 250.000, Seragam Rp 650.000, Perlengkapan ±Rp 145.000, SPP pertama Rp 200.000. <strong>Total estimasi Rp 1.245.000</strong>.'
                ],
                [
                    'q' => 'Bagaimana jika bukti pembayaran hilang?',
                    'a' => 'Hubungi panitia via WhatsApp dengan menyertakan <strong>nomor peserta</strong> dan <strong>tanggal transaksi</strong> untuk verifikasi rekening koran sekolah.'
                ],
                [
                    'q' => 'Apakah ada cicilan atau keringanan biaya?',
                    'a' => 'Peserta dari keluarga tidak mampu (pemegang KIP/DTKS) dapat mengajukan <strong>keringanan biaya</strong> dengan menyerahkan surat permohonan ke TU sekolah.'
                ],
            ],
        ];

        foreach ($faqData as $slug => $questions) {
            // Mencari kategori berdasarkan slug untuk mendapatkan UUID
            $category = FaqCategories::where('slug', $slug)->first();

            if ($category) {
                foreach ($questions as $item) {
                    Faq::create([
                        'faq_category_id' => $category->id,
                        'question'        => $item['q'],
                        'answer'          => $item['a'],
                        'is_published'    => true,
                    ]);
                }
            }
        }
    }
}

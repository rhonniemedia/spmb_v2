<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Biodata Peserta Didik</title>
    <style>
        /* Margin kertas standar */
        @page { margin: 50px 50px; }
        
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px; 
            color: #333;
            line-height: 1.4;
        }

        /* Warna Utama & Tipografi */
        .text-primary { color: #2563eb; }
        .text-gray { color: #6b7280; }
        
        /* Judul Dokumen */
        .document-title {
            font-size: 22px;
            font-weight: 900;
            text-align: center;
            margin-bottom: 30px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }
        .document-title span { color: #2563eb; }

        /* KUNCI ANTI-TERPOTONG: Bungkus setiap section dengan class ini */
        .section-block {
            page-break-inside: avoid;
            margin-bottom: 25px;
        }

        /* Styling Judul Section */
        .section-title {
            color: #2563eb;
            font-weight: 900;
            font-size: 13px;
            text-transform: uppercase;
            border-bottom: 2px solid #bfdbfe;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        /* Styling Tabel Data 1 Kolom */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table tr {
            page-break-inside: avoid; 
        }
        .data-table td {
            vertical-align: top;
            padding: 6px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        
        /* Pengaturan Lebar Kolom (Total 100%) */
        .label { 
            width: 30%; 
            color: #4b5563; 
            font-weight: bold; 
            font-size: 11px; 
            text-transform: uppercase; 
        }
        .colon { 
            width: 3%; 
            font-weight: bold; 
            color: #9ca3af; 
        }
        .value { 
            width: 67%; 
            color: #111; 
            font-weight: bold; 
        }

        /* Khusus Header & Foto - Full Width tanpa card */
        .header-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 25px; 
            page-break-inside: avoid;
        }
        .header-table td { 
            border: none; 
            padding: 0; 
        }
        
        .header-info .name {
            font-size: 20px;
            font-weight: 900;
            color: #1e3a8a;
            margin-bottom: 2px;
        }
        
        .header-info .name-sub {
            font-size: 13px;
            color: #6b7280;
            font-weight: 400;
            margin-bottom: 12px;
            border-bottom: 2px solid #bfdbfe;
            padding-bottom: 8px;
        }
        
        .header-data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-data-table tr {
            page-break-inside: avoid; 
        }
        .header-data-table td {
            vertical-align: top;
            padding: 6px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .header-data-table .label { 
            width: 30%; 
            color: #4b5563; 
            font-weight: bold; 
            font-size: 11px; 
            text-transform: uppercase; 
        }
        .header-data-table .colon { 
            width: 3%; 
            font-weight: bold; 
            color: #9ca3af; 
        }
        .header-data-table .value { 
            width: 67%; 
            color: #111; 
            font-weight: bold; 
        }
        
        .photo-box {
            width: 3.5cm;
            height: 4.5cm;
            background-color: #f3f4f6;
            border: 2px dashed #d1d5db;
            text-align: center;
            color: #9ca3af;
            border-radius: 8px;
            margin-left: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            font-size: 11px;
        }

        /* Styling Tabel Rapor & TKA - Horizontal */
        .rapor-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            font-size: 12px;
        }
        .rapor-table th {
            background-color: #2563eb !important;
            color: #ffffff !important;
            font-weight: bold;
            text-align: center;
            padding: 6px 10px;
            border: 1px solid #1e4fa8;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .rapor-table td {
            text-align: center;
            padding: 6px 10px;
            border: 1px solid #d1d5db;
        }
        .rapor-table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .rapor-table .total-row {
            background-color: #eff6ff !important;
            font-weight: bold;
            color: #2563eb;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Judul Tabel Rapor & TKA */
        .table-title {
            font-weight: 700;
            font-size: 12px;
            color: #1e3a8a;
            margin-top: 10px;
            margin-bottom: 3px;
            padding-left: 2px;
        }
        .table-title:first-of-type {
            margin-top: 5px;
        }

        /* Tanda Tangan */
        .ttd-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #f3f4f6;
        }
        .ttd-left {
            text-align: left;
        }
        .ttd-right {
            text-align: right;
        }
        .ttd-box {
            width: 45%;
        }
        .ttd-box .ttd-label {
            font-weight: bold;
            font-size: 12px;
            color: #4b5563;
            margin-bottom: 40px;
        }
        .ttd-box .ttd-line {
            border-bottom: 1px solid #333;
            width: 100%;
            margin: 0 auto 5px auto;
        }
        .ttd-box .ttd-name {
            font-size: 11px;
            color: #6b7280;
        }
        .ttd-date {
            text-align: right;
            font-size: 12px;
            color: #4b5563;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="document-title">
        BIODATA <span>PESERTA DIDIK</span>
    </div>

    <!-- IDENTITAS & FOTO - Full Width tanpa card -->
    <table class="header-table">
        <tr>
            <td style="width: 75%; padding-right: 20px;">
                <div class="header-info">
                    <div class="name">Muhammad Farhan Saputra</div>
                    <div class="name-sub">Peserta Didik Baru Tahun Ajaran 2024/2025</div>
                    
                    <table class="header-data-table">
                        <tr><td class="label">Nama Panggilan</td><td class="colon">:</td><td class="value">Farhan</td></tr>
                        <tr><td class="label">NIK</td><td class="colon">:</td><td class="value">1702011508080001</td></tr>
                        <tr><td class="label">NISN</td><td class="colon">:</td><td class="value">0081234567</td></tr>
                        <tr><td class="label">Tempat, Tgl Lahir</td><td class="colon">:</td><td class="value">Bengkulu, 15 Agustus 2008</td></tr>
                        <tr><td class="label">Jenis Kelamin</td><td class="colon">:</td><td class="value">Laki-laki (L)</td></tr>
                    </table>
                </div>
            </td>
            <td style="width: 25%; text-align: right; vertical-align: top; padding-top: 5px;">
                <div class="photo-box">
                    <br><br><br>PAS FOTO<br>3x4
                </div>
            </td>
        </tr>
    </table>

    <!-- A. DETAIL PRIBADI & KONTAK -->
    <div class="section-block">
        <div class="section-title">A. DETAIL PRIBADI & KONTAK</div>
        <table class="data-table">
            <tr><td class="label">Agama</td><td class="colon">:</td><td class="value">Islam</td></tr>
            <tr><td class="label">Anak Ke</td><td class="colon">:</td><td class="value">1 dari 3 Saudara Kandung</td></tr>
            <tr><td class="label">No. Telepon / HP</td><td class="colon">:</td><td class="value">+62 812-3456-7890</td></tr>
            <tr><td class="label">Email</td><td class="colon">:</td><td class="value">farhan.saputra@siswa.smkn1rl.sch.id</td></tr>
        </table>
    </div>

    <!-- B. DATA ALAMAT DOMISILI -->
    <div class="section-block">
        <div class="section-title">B. DATA ALAMAT DOMISILI</div>
        <table class="data-table">
            <tr><td class="label">Alamat Jalan</td><td class="colon">:</td><td class="value">Jl. Pramuka No. 45</td></tr>
            <tr><td class="label">RT / RW</td><td class="colon">:</td><td class="value">003 / 001</td></tr>
            <tr><td class="label">Desa / Kelurahan</td><td class="colon">:</td><td class="value">Air Putih Lama</td></tr>
            <tr><td class="label">Kecamatan</td><td class="colon">:</td><td class="value">Curup</td></tr>
            <tr><td class="label">Kabupaten / Kota</td><td class="colon">:</td><td class="value">Rejang Lebong</td></tr>
            <tr><td class="label">Provinsi & Kode Pos</td><td class="colon">:</td><td class="value">Bengkulu (39112)</td></tr>
            <tr><td class="label">Jenis Tempat Tinggal</td><td class="colon">:</td><td class="value">Rumah Orang Tua</td></tr>
            <tr><td class="label">Transportasi & Jarak</td><td class="colon">:</td><td class="value">Sepeda Motor (1 - 5 km)</td></tr>
        </table>
    </div>

    <!-- C. PENDIDIKAN SEBELUMNYA -->
    <div class="section-block">
        <div class="section-title">C. PENDIDIKAN SEBELUMNYA</div>
        <table class="data-table">
            <tr><td class="label">Nama Sekolah Asal</td><td class="colon">:</td><td class="value text-primary">SMP Negeri 1 Bengkulu</td></tr>
            <tr><td class="label">Status Sekolah</td><td class="colon">:</td><td class="value">Negeri</td></tr>
            <tr><td class="label">NPSN</td><td class="colon">:</td><td class="value">10700123</td></tr>
            <tr><td class="label">Kota & Provinsi</td><td class="colon">:</td><td class="value">Bengkulu, Provinsi Bengkulu</td></tr>
            <tr><td class="label">Tahun Lulus</td><td class="colon">:</td><td class="value">2024</td></tr>
            <tr><td class="label">No. Ijazah / SKL</td><td class="colon">:</td><td class="value">DN-07 DI 0123456</td></tr>
        </table>
    </div>

    <!-- D. DATA AKADEMIK (NILAI RAPOR & TKA) -->
    <div class="section-block">
        <div class="section-title" style="color: #065f46; border-bottom-color: #a7f3d0;">D. DATA AKADEMIK (NILAI RAPOR &amp; TKA)</div>
        
        <div class="table-title">Nilai Rapor</div>
        <table class="rapor-table">
            <thead>
                <tr>
                    <th>Smt 1</th>
                    <th>Smt 2</th>
                    <th>Smt 3</th>
                    <th>Smt 4</th>
                    <th>Smt 5</th>
                    <th>Rata-rata</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>85.00</td>
                    <td>86.50</td>
                    <td>87.00</td>
                    <td>88.00</td>
                    <td>89.50</td>
                    <td class="total-row">87.20</td>
                </tr>
            </tbody>
        </table>

        <div class="table-title">Tes Kemampuan Akademik (TKA)</div>
        <table class="rapor-table" style="margin-top: 5px;">
            <thead>
                <tr>
                    <th>Matematika</th>
                    <th>Bahasa Indonesia</th>
                    <th>Rata-rata</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>82.00</td>
                    <td>90.00</td>
                    <td class="total-row">86.00</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- E. KESEHATAN, MINAT & BAKAT -->
    <div class="section-block">
        <div class="section-title">E. KESEHATAN, MINAT & BAKAT</div>
        <table class="data-table">
            <tr><td class="label">Tinggi / Berat Badan</td><td class="colon">:</td><td class="value">165 cm / 55 kg</td></tr>
            <tr><td class="label">Golongan Darah</td><td class="colon">:</td><td class="value">O</td></tr>
            <tr><td class="label">Riwayat Penyakit</td><td class="colon">:</td><td class="value">Tidak ada</td></tr>
            <tr><td class="label">Kondisi Khusus (Disabilitas)</td><td class="colon">:</td><td class="value">Tidak Ada</td></tr>
            <tr><td class="label">Ekstrakurikuler Pilihan</td><td class="colon">:</td><td class="value">Pramuka</td></tr>
            <tr><td class="label">Minat Organisasi</td><td class="colon">:</td><td class="value">OSIS (Organisasi Siswa Intra Sekolah)</td></tr>
            <tr><td class="label">Kategori Seni (FL2SN)</td><td class="colon">:</td><td class="value">Desain Poster</td></tr>
            <tr><td class="label">Kategori Olahraga (O2SN)</td><td class="colon">:</td><td class="value">Pencak Silat</td></tr>
        </table>
    </div>

    <!-- F. DATA AYAH KANDUNG -->
    <div class="section-block">
        <div class="section-title" style="color: #1e3a8a; border-bottom-color: #bfdbfe;">F. DATA AYAH KANDUNG</div>
        <table class="data-table">
            <tr><td class="label">Nama Lengkap</td><td class="colon">:</td><td class="value">Ahmad Suryono, S.Pd</td></tr>
            <tr><td class="label">Status</td><td class="colon">:</td><td class="value">Masih Hidup</td></tr>
            <tr><td class="label">NIK</td><td class="colon">:</td><td class="value">1702011234567890</td></tr>
            <tr><td class="label">Tahun Lahir</td><td class="colon">:</td><td class="value">1975</td></tr>
            <tr><td class="label">Pendidikan Terakhir</td><td class="colon">:</td><td class="value">S1/D4</td></tr>
            <tr><td class="label">Pekerjaan</td><td class="colon">:</td><td class="value">Guru/Dosen</td></tr>
            <tr><td class="label">Penghasilan per Bulan</td><td class="colon">:</td><td class="value">Rp 3.000.000 - Rp 5.000.000</td></tr>
            <tr><td class="label">No. HP</td><td class="colon">:</td><td class="value">+62 812-1111-2222</td></tr>
            <tr><td class="label">Alamat Lengkap</td><td class="colon">:</td><td class="value" style="font-weight: normal;">Sesuai Domisili Siswa</td></tr>
        </table>
    </div>

    <!-- G. DATA IBU KANDUNG -->
    <div class="section-block">
        <div class="section-title" style="color: #831843; border-bottom-color: #fbcfe8;">G. DATA IBU KANDUNG</div>
        <table class="data-table">
            <tr><td class="label">Nama Lengkap</td><td class="colon">:</td><td class="value">Siti Aminah, S.E</td></tr>
            <tr><td class="label">Status</td><td class="colon">:</td><td class="value">Masih Hidup</td></tr>
            <tr><td class="label">NIK</td><td class="colon">:</td><td class="value">1702019876543210</td></tr>
            <tr><td class="label">Tahun Lahir</td><td class="colon">:</td><td class="value">1978</td></tr>
            <tr><td class="label">Pendidikan Terakhir</td><td class="colon">:</td><td class="value">S1/D4</td></tr>
            <tr><td class="label">Pekerjaan</td><td class="colon">:</td><td class="value">Wiraswasta</td></tr>
            <tr><td class="label">Penghasilan per Bulan</td><td class="colon">:</td><td class="value">Rp 2.000.000 - Rp 3.000.000</td></tr>
            <tr><td class="label">No. HP</td><td class="colon">:</td><td class="value">+62 813-3333-4444</td></tr>
            <tr><td class="label">Alamat Lengkap</td><td class="colon">:</td><td class="value" style="font-weight: normal;">Sesuai Domisili Siswa</td></tr>
        </table>
    </div>

    <!-- H. DATA WALI (JIKA ADA) -->
    <div class="section-block">
        <div class="section-title" style="color: #4c1d95; border-bottom-color: #ddd6fe;">H. DATA WALI (JIKA ADA)</div>
        <table class="data-table">
            <tr><td class="label">Nama Lengkap</td><td class="colon">:</td><td class="value">-</td></tr>
            <tr><td class="label">NIK</td><td class="colon">:</td><td class="value">-</td></tr>
            <tr><td class="label">Tahun Lahir</td><td class="colon">:</td><td class="value">-</td></tr>
            <tr><td class="label">Pendidikan Terakhir</td><td class="colon">:</td><td class="value">-</td></tr>
            <tr><td class="label">Pekerjaan</td><td class="colon">:</td><td class="value">-</td></tr>
            <tr><td class="label">Penghasilan per Bulan</td><td class="colon">:</td><td class="value">-</td></tr>
            <tr><td class="label">No. HP</td><td class="colon">:</td><td class="value">-</td></tr>
            <tr><td class="label">Alamat Lengkap</td><td class="colon">:</td><td class="value">-</td></tr>
        </table>
    </div>

    <!-- TANDA TANGAN -->
    <div class="ttd-wrapper">
        <div class="ttd-box ttd-left">
            <div class="ttd-label">Wali Murid</div>
            <div class="ttd-line">&nbsp;</div>
            <div class="ttd-name">(Ahmad Suryono, S.Pd)</div>
        </div>
        <div class="ttd-box ttd-right">
            <div class="ttd-date">Rejang Lebong, 28 Juni 2026</div>
            <div class="ttd-label">Calon Peserta Didik</div>
            <div class="ttd-line">&nbsp;</div>
            <div class="ttd-name">(Muhammad Farhan Saputra)</div>
        </div>
    </div>

</body>
</html>
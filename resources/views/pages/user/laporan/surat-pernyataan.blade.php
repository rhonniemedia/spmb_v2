<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pernyataan Peserta Didik Baru</title>
    <style>
        /* Pengaturan halaman A4 untuk Dompdf dengan margin lebih rapat */
        @page {
            size: A4;
            margin: 1.25cm 1.25cm; 
        }
        
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt; /* Diperkecil agar muat 1 halaman */
            line-height: 1.3; /* Dirapatkan */
            color: #000;
        }

        /* Judul Dokumen */
        .document-title {
            font-size: 12pt;
            font-weight: bold;
            text-align: center;
            text-decoration: underline;
            margin-bottom: 12px;
        }

        /* Paragraf Standar */
        p {
            text-align: justify;
            margin: 4px 0;
        }

        /* Styling Judul Bagian */
        .section-title {
            font-weight: bold;
            margin-top: 8px;
            margin-bottom: 3px;
        }

        /* Tabel Data Form - Perbaikan Biodata dengan Garis Bawah */
        .form-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }
        .form-table td {
            vertical-align: top;
            padding: 2px 0;
        }
        .col-label { width: 26%; font-weight: bold; }
        .col-colon { width: 3%; text-align: center; }
        .col-value { width: 71%; }
        .col-value .garis-bawah {
            display: inline-block;
            width: 100%;
            border-bottom: 1px solid #aaa;
            height: 16px;
            vertical-align: bottom;
        }
        .col-value .garis-bawah-full {
            display: inline-block;
            width: 100%;
            border-bottom: 1px solid #aaa;
            height: 16px;
            vertical-align: bottom;
        }
        .col-value .garis-bawah-hubungan {
            display: inline-block;
            width: 100%;
            border-bottom: 1px solid #aaa;
            height: 16px;
            vertical-align: bottom;
        }

        /* Styling Numbering */
        ol.pernyataan-list, ol.sanksi-list {
            margin-top: 3px;
            margin-bottom: 5px;
            padding-left: 18px;
            text-align: justify;
        }
        ol.pernyataan-list li, ol.sanksi-list li {
            margin-bottom: 2px;
            padding-left: 3px;
        }

        /* Styling Tabel Tanda Tangan */
        .signature-table {
            width: 85%;
            margin-left: 15%;
            margin-top: 16px;
            page-break-inside: avoid;
            border: none;
            border-collapse: collapse;
        }
        .signature-table td {
            width: 50%;
            vertical-align: top;
            border: none;
            padding: 0;
        }
        .sig-ortu {
            padding-left: 0;
        }
        .sig-pembuat {
            padding-left: 30px;
        }
        .signature-left {
            text-align: left !important;
        }
        
        /* Spacer untuk tempat tanda tangan manual */
        .ttd-space {
            height: 50px;
        }
        
        /* Keterangan Materai */
        .materai-note {
            font-size: 8pt;
            font-style: italic;
            color: #555;
            margin-top: 2px;
            margin-bottom: 0;
        }
    </style>
</head>
<body>

    <div class="document-title">SURAT PERNYATAAN PESERTA DIDIK BARU</div>

    <p>Yang bertanda tangan di bawah ini:</p>

    <!-- A. DATA PESERTA DIDIK - Perbaikan dengan Garis Bawah -->
    <div class="section-title">A. Data Peserta Didik</div>
    <table class="form-table">
        <tr><td class="col-label">Nama Lengkap</td><td class="col-colon">:</td><td class="col-value"><span class="garis-bawah">Rizky Pratama Putra</span></td></tr>
        <tr><td class="col-label">Tempat, Tanggal Lahir</td><td class="col-colon">:</td><td class="col-value"><span class="garis-bawah">Curup, 14 Maret 2010</span></td></tr>
        <tr><td class="col-label">Jenis Kelamin</td><td class="col-colon">:</td><td class="col-value"><span class="garis-bawah">Laki-laki</span></td></tr>
        <tr><td class="col-label">Agama</td><td class="col-colon">:</td><td class="col-value"><span class="garis-bawah">Islam</span></td></tr>
        <tr><td class="col-label">Alamat Lengkap</td><td class="col-colon">:</td><td class="col-value"><span class="garis-bawah-full">Jl. Merdeka No. 12, Kel. Air Rambai, Kec. Curup, Rejang Lebong</span></td></tr>
        <tr><td class="col-label">No. HP/Telepon</td><td class="col-colon">:</td><td class="col-value"><span class="garis-bawah">0812-3456-7890</span></td></tr>
        <tr><td class="col-label">Asal Sekolah</td><td class="col-colon">:</td><td class="col-value"><span class="garis-bawah">SMP Negeri 1 Curup</span></td></tr>
    </table>

    <!-- B. DATA ORANG TUA / WALI - Perbaikan dengan Garis Bawah -->
    <div class="section-title">B. Data Orang Tua / Wali</div>
    <table class="form-table">
        <tr><td class="col-label">Nama Lengkap</td><td class="col-colon">:</td><td class="col-value"><span class="garis-bawah">Hendra Pratama</span></td></tr>
        <tr><td class="col-label">Pekerjaan</td><td class="col-colon">:</td><td class="col-value"><span class="garis-bawah">Wiraswasta</span></td></tr>
        <tr><td class="col-label">Alamat Lengkap</td><td class="col-colon">:</td><td class="col-value"><span class="garis-bawah-full">Jl. Merdeka No. 12, Kel. Air Rambai, Kec. Curup, Rejang Lebong</span></td></tr>
        <tr><td class="col-label">No. HP/Telepon</td><td class="col-colon">:</td><td class="col-value"><span class="garis-bawah">0821-9876-5432</span></td></tr>
        <tr><td class="col-label">Hubungan Keluarga</td><td class="col-colon">:</td><td class="col-value"><span class="garis-bawah-hubungan">Ayah</span></td></tr>
    </table>

    <p>Dengan sesungguhnya dan penuh kesadaran menyatakan bahwa selama menjadi peserta didik di <strong>SMK Negeri 1 Rejang Lebong</strong>, saya bersedia:</p>
    
    <ol class="pernyataan-list" type="1">
        <li><strong>Menaati</strong> pelaksanaan Wawasan Wiyata Mandala serta seluruh peraturan dan tata tertib sekolah yang berlaku.</li>
        <li><strong>Mengikuti</strong> pendidikan agama sesuai dengan agama dan keyakinan yang saya anut.</li>
        <li><strong>Mengikuti</strong> kegiatan ekstrakurikuler wajib maupun pilihan yang telah ditetapkan oleh sekolah dengan penuh tanggung jawab.</li>
        <li><strong>Menjaga</strong> nama baik diri sendiri, keluarga, dan SMK Negeri 1 Rejang Lebong, baik di dalam maupun di luar lingkungan sekolah.</li>
        <li><strong>Mematuhi</strong> kebijakan pembatasan penggunaan telepon seluler (HP) di lingkungan sekolah sesuai dengan prosedur yang ditetapkan.</li>
        <li><strong>Tidak membawa</strong> kendaraan bermotor ke lingkungan sekolah sebelum memiliki Surat Izin Mengemudi (SIM).</li>
        <li><strong>Tidak mengajukan</strong> permohonan pindah konsentrasi keahlian (jurusan) selama menempuh pendidikan di sekolah ini.</li>
        <li><strong>Membebaskan</strong> pihak sekolah dari segala tuntutan dan tanggung jawab atas kehilangan barang berharga milik pribadi di lingkungan sekolah.</li>
        <li><strong>Mengenakan</strong> seragam sekolah sesuai dengan jadwal, ketentuan yang berlaku, rapi, serta sopan.</li>
        <li><strong>Tidak terlibat</strong> dalam tindakan melanggar hukum, termasuk namun tidak terbatas pada tindakan kriminal, penyalahgunaan narkoba, merokok, minuman keras, perkelahian/tawuran, dan perundungan (<em>bullying</em>).</li>
    </ol>

    <p>Apabila di kemudian hari saya terbukti tidak menaati dan melanggar tata tertib yang telah ditetapkan dalam pernyataan di atas, maka saya siap menerima sanksi berupa:</p>
    
    <ol class="sanksi-list" type="a">
        <li><strong>Larangan</strong> mengikuti kegiatan belajar mengajar di sekolah (skorsing) selama maksimal 7 (tujuh) hari.</li>
        <li><strong>Pengembalian</strong> status peserta didik kepada orang tua/wali (dikeluarkan dari sekolah).</li>
    </ol>

    <p>Demikian surat pernyataan ini saya buat dengan sebenarnya, tanpa ada paksaan dari pihak mana pun, serta diketahui dan disetujui oleh orang tua/wali.</p>

    <!-- TANDA TANGAN -->
    <table class="signature-table">
        <tr>
            <td></td>
            <td class="sig-pembuat" style="padding-bottom: 6px;">
                Rejang Lebong, 07 Juli 2026
            </td>
        </tr>
        <tr>
            <td class="sig-ortu">
                Mengetahui/Menyetujui,<br>
                <strong>Orang Tua/Wali</strong>
            </td>
            <td class="sig-pembuat">
                <strong>Yang Membuat Pernyataan,</strong><br>
                <span class="materai-note">(*Materai Rp 10.000)</span>
            </td>
        </tr>
        <tr>
            <td class="ttd-space sig-ortu"></td>
            <td class="ttd-space sig-pembuat"></td>
        </tr>
        <tr>
            <td class="sig-ortu">( Hendra Pratama )</td>
            <td class="sig-pembuat">( Rizky Pratama Putra )</td>
        </tr>
    </table>

</body>
</html>
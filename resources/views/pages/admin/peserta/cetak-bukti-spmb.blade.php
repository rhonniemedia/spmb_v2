<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Bukti Verifikasi & Observasi SPMB</title>
    <style>
        @page {
            margin: 0px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            color: #000;
            background: #fff;
            line-height: 1.3;
            margin: 0;
            padding: 1cm;
            /* Ini yang akan membuat jarak aman dari tepi kertas */
        }

        /* ===== MAIN LAYOUT: Kanan-Kiri Split ===== */
        .main-layout {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .left-panel {
            display: table-cell;
            width: 60%;
            padding-right: 1cm;
            /* Margin pembagi ke konten = margin tepi 1cm */
            border-right: 1px dashed #000;
            vertical-align: top;
        }

        .right-panel {
            display: table-cell;
            width: 40%;
            padding-left: 1cm;
            /* Margin pembagi ke konten = margin tepi 1cm */
            vertical-align: top;
        }

        /* ===== HEADER TABLE ===== */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 3px solid #000;
            /* Garis tebal saja (solid) */
            margin-bottom: 10px;
        }

        .header-table td {
            padding-bottom: 6px;
            vertical-align: top;
            border: none !important;
        }

        .school-name {
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .left-panel .school-name {
            font-size: 14pt;
        }

        .right-panel .school-name {
            font-size: 10pt;
        }

        .school-address {
            color: #222;
        }

        .left-panel .school-address {
            font-size: 9pt;
            margin-top: 2px;
        }

        .right-panel .school-address {
            font-size: 7.5pt;
            margin-top: 1px;
        }

        /* ===== KOTAK NO. PENDAFTARAN ===== */
        .header-table td:nth-child(2) {
            text-align: right;
            /* Memaksa isi kolom kedua rata kanan */
            vertical-align: bottom;
            /* Membuat kotak duduk rapi persis di atas garis */
        }

        .no-daftar-box {
            border: 1.5px solid #000;
            text-align: center;
            display: inline-block;
            /* Diubah menjadi inline-block agar tidak keluar jalur */
            /* float: right; (Dihapus) */
        }

        /* Left Panel No. Pendaftaran (Lebih Kecil & Rapat) */
        .left-panel .no-daftar-box {
            padding: 4px 8px;
            min-width: 115px;
        }

        .left-panel .nd-val-large {
            font-size: 26pt;
            font-weight: bold;
            line-height: 0.9;
            letter-spacing: 5px;
            margin-bottom: 2px;
        }

        .left-panel .nd-val-small {
            font-size: 11pt;
            font-weight: bold;
            letter-spacing: 1px;
            line-height: 1;
            margin-bottom: 2px;
        }

        .left-panel .nd-label {
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1;
        }

        /* Right Panel No. Pendaftaran (Lebih Kecil & Rapat) */
        .right-panel .no-daftar-box {
            padding: 3px 6px;
            min-width: 90px;
        }

        .right-panel .nd-val-large {
            font-size: 19pt;
            font-weight: bold;
            line-height: 0.9;
            letter-spacing: 4px;
            margin-bottom: 2px;
        }

        .right-panel .nd-val-small {
            font-size: 9pt;
            font-weight: bold;
            letter-spacing: 1px;
            line-height: 1;
            margin-bottom: 2px;
        }

        .right-panel .nd-label {
            font-size: 6pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1;
        }

        /* ===== TITLE BLOCK ===== */
        .title-block {
            text-align: center;
            margin-bottom: 10px;
        }

        .title-block .doc-title {
            font-weight: bold;
            text-transform: uppercase;
        }

        .left-panel .doc-title {
            font-size: 12pt;
        }

        .right-panel .doc-title {
            font-size: 9.5pt;
        }

        .title-block .doc-subtitle {
            font-weight: bold;
        }

        .left-panel .doc-subtitle {
            font-size: 10pt;
        }

        .right-panel .doc-subtitle {
            font-size: 8pt;
        }

        /* ===== TABLES COMMON ===== */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .data-table td {
            padding: 4px 6px;
            vertical-align: top;
            /* Berubah menjadi top agar semua teks rapat ke atas jika barisnya multi-line */
            border: none !important;
        }

        /* Left panel specific table widths */
        .left-panel .identitas-table .lbl {
            width: 22%;
            font-weight: bold;
            font-size: 9.5pt;
        }

        .left-panel .identitas-table .sep {
            width: 2%;
            text-align: center;
            font-size: 9.5pt;
        }

        .left-panel .identitas-table .val {
            width: 26%;
            font-size: 9.5pt;
        }

        .left-panel .keahlian-table .lbl {
            width: 22%;
            font-weight: bold;
            font-size: 9.5pt;
        }

        .left-panel .keahlian-table .sep {
            width: 2%;
            text-align: center;
            font-size: 9.5pt;
        }

        .left-panel .keahlian-table .val {
            width: 76%;
            font-size: 9.5pt;
        }

        /* Right panel specific table layout */
        .right-panel .identitas-table .lbl {
            width: 34%;
            font-weight: bold;
            font-size: 8pt;
        }

        .right-panel .identitas-table .sep {
            width: 3%;
            text-align: center;
            font-size: 8pt;
        }

        .right-panel .identitas-table .val {
            width: 63%;
            font-size: 8pt;
        }

        .right-panel .keahlian-table .lbl {
            width: 34%;
            font-weight: bold;
            font-size: 8pt;
        }

        .right-panel .keahlian-table .sep {
            width: 3%;
            text-align: center;
            font-size: 8pt;
        }

        .right-panel .keahlian-table .val {
            width: 63%;
            font-size: 8pt;
        }

        /* ===== SECTION HEADING ===== */
        .section-heading {
            font-weight: bold;
            text-decoration: none;
            margin-bottom: 4px;
        }

        .left-panel .section-heading {
            font-size: 10pt;
        }

        .right-panel .section-heading {
            font-size: 8.5pt;
        }

        /* ===== BERKAS TABLE ===== */
        .berkas-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .berkas-table th {
            padding: 4px 6px;
            font-weight: bold;
            text-align: left;
            border-top: 1px dashed #000 !important;
            border-bottom: 1px dashed #000 !important;
            border-left: none !important;
            border-right: none !important;
        }

        .berkas-table th.center {
            text-align: center;
        }

        .left-panel .berkas-table th {
            font-size: 9.5pt;
        }

        .right-panel .berkas-table th {
            font-size: 8pt;
        }

        .berkas-table td {
            padding: 4px 6px;
            border-top: 1px dashed #000 !important;
            border-bottom: 1px dashed #000 !important;
            border-left: none !important;
            border-right: none !important;
        }

        .left-panel .berkas-table td {
            font-size: 9.5pt;
        }

        .right-panel .berkas-table td {
            font-size: 8pt;
        }

        .berkas-table td.center {
            text-align: center;
        }

        .ada {
            color: #1a7a2e;
            font-weight: bold;
        }

        .tidak {
            color: #c0392b;
            font-weight: bold;
        }

        /* ===== NOTE PARAGRAPH ===== */
        .note-paragraph {
            text-align: justify;
            border: 1px solid #000;
            padding: 6px 10px;
            margin-bottom: 10px;
        }

        .left-panel .note-paragraph {
            font-size: 9.5pt;
            line-height: 1.4;
        }

        .right-panel .note-paragraph {
            font-size: 8pt;
            line-height: 1.3;
        }

        /* ===== SIGNATURE SECTION ===== */
        .signature-section {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .signature-section td {
            width: 50%;
            text-align: left;
            /* Rata Kiri */
            vertical-align: top;
            border: none !important;
        }

        /* Menambahkan Tabulasi (Padding Left) yang lebih dalam */
        .left-panel .signature-section td {
            font-size: 9.5pt;
            padding-left: 50px;
        }

        /* Jauh lebih menjorok (tab 2-3x) */
        .right-panel .signature-section td {
            font-size: 8pt;
            padding-left: 10px;
        }

        .sig-role {
            font-weight: bold;
            margin-bottom: 2px;
        }

        .sig-location {
            margin-bottom: 40px;
        }

        .left-panel .sig-location {
            font-size: 9pt;
        }

        .right-panel .sig-location {
            font-size: 7.5pt;
        }

        .sig-name {
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 2px;
            display: inline-block;
            min-width: 140px;
        }
    </style>
</head>

<body>
    <div class="main-layout">

        <div class="left-panel">
            <table class="header-table">
                <tr>
                    <td>
                        <div class="school-name">{{ $sekolah['nama'] }}</div>
                        <div class="school-address">{{ $sekolah['alamat'] }}</div>
                        <div class="school-address">{{ $sekolah['email'] }}</div>
                    </td>
                    <td>
                        <div class="no-daftar-box">
                            <div class="nd-val-large">{{ $peserta['no_daftar_besar'] }}</div>
                            <div class="nd-val-small">{{ $peserta['no_daftar_kecil'] }}</div>
                            <div class="nd-label">No. Pendaftaran</div>
                        </div>
                    </td>
                </tr>
            </table>

            <div class="title-block">
                <div class="doc-title">Bukti Verifikasi &amp; Observasi</div>
                <div class="doc-subtitle">Sistem Penerimaan Murid Baru (SPMB)</div>
            </div>

            <table class="data-table identitas-table">
                <tbody>
                    <tr>
                        <td class="lbl">Nama Lengkap</td>
                        <td class="sep">:</td>
                        <td class="val" colspan="4">{{ strtoupper($peserta['nama_lengkap']) }}&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="lbl">Nomor Telepon</td>
                        <td class="sep">:</td>
                        <td class="val">{{ $peserta['no_telp'] }}</td>
                        <td class="lbl">Sekolah Asal</td>
                        <td class="sep">:</td>
                        <td class="val">{{ strtoupper($peserta['sekolah_asal']) }}</td>
                    </tr>
                </tbody>
            </table>

            <table class="data-table keahlian-table">
                <tbody>
                    @php $jmlJurusan = count($peserta['pilihan_jurusan']) ?: 1; @endphp
                    <tr>
                        <td class="lbl" rowspan="{{ $jmlJurusan }}">Komp. Keahlian Pilihan&nbsp;&nbsp;</td>
                        <td class="sep" rowspan="{{ $jmlJurusan }}">:</td>
                        <td class="val">1. {{ $peserta['pilihan_jurusan'][0] ?? '-' }}</td>
                    </tr>
                    @if(isset($peserta['pilihan_jurusan'][1]))
                    <tr>
                        <td class="val">2. {{ $peserta['pilihan_jurusan'][1] }}</td>
                    </tr>
                    @endif
                    @if(isset($peserta['pilihan_jurusan'][2]))
                    <tr>
                        <td class="val">3. {{ $peserta['pilihan_jurusan'][2] }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>

            <div class="section-heading">Kelengkapan Berkas:</div>
            <table class="berkas-table">
                <thead>
                    <tr>
                        <th class="center" style="width:10%">No</th>
                        <th style="width:65%">Nama Berkas</th>
                        <th class="center" style="width:25%">Ada / Tidak</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peserta['kelengkapan_berkas'] as $index => $berkas)
                    <tr>
                        <td class="center">{{ $index + 1 }}</td>
                        <td>{{ $berkas['nama'] }}</td>
                        @if($berkas['status'])
                        <td class="center ada">Ada</td>
                        @else
                        <td class="center tidak">Tidak</td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="note-paragraph">
                Peserta dengan data di atas telah menyelesaikan seluruh tahapan <strong>Verifikasi Berkas</strong> dan
                <strong>Observasi Fisik serta Kesehatan</strong>, sebagai bagian dari proses seleksi
                Sistem Penerimaan Murid Baru (SPMB) Tahun {{ $peserta['tahun_spmb'] }}.
            </div>

            <table class="signature-section">
                <tr>
                    <td>
                        <div style="margin-bottom: 2px;">&nbsp;</div>
                        <div style="margin-bottom: 40px; font-weight: bold;">Observator</div>
                        <span class="sig-name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    </td>
                    <td>
                        <div class="sig-role">Verifikator</div>
                        <div class="sig-location">{{ $peserta['lokasi_ttd'] }}, {{ $peserta['tanggal_ttd'] }}</div>
                        <span class="sig-name">{{ $peserta['nama_verifikator'] }}</span>
                    </td>
                </tr>
            </table>

        </div>

        <div class="right-panel">
            <table class="header-table">
                <tr>
                    <td>
                        <div class="school-name">{{ $sekolah['nama'] }}</div>
                        <div class="school-address">{{ $sekolah['alamat'] }}</div>
                        <div class="school-address">{{ $sekolah['email'] }}</div>
                    </td>
                    <td>
                        <div class="no-daftar-box">
                            <div class="nd-val-large">{{ $peserta['no_daftar_besar'] }}</div>
                            <div class="nd-val-small">{{ $peserta['no_daftar_kecil'] }}</div>
                            <div class="nd-label">No. Pendaftaran</div>
                        </div>
                    </td>
                </tr>
            </table>

            <div class="title-block">
                <div class="doc-title">Bukti Verifikasi Dokumen</div>
                <div class="doc-subtitle">Sistem Penerimaan Murid Baru (SPMB)</div>
            </div>

            <table class="data-table identitas-table">
                <tr>
                    <td class="lbl">Nama Lengkap</td>
                    <td class="sep">:</td>
                    <td class="val">{{ strtoupper($peserta['nama_lengkap']) }}</td>
                </tr>
                <tr>
                    <td class="lbl">Nomor Telepon</td>
                    <td class="sep">:</td>
                    <td class="val">{{ $peserta['no_telp'] }}</td>
                </tr>
                <tr>
                    <td class="lbl">Sekolah Asal</td>
                    <td class="sep">:</td>
                    <td class="val">{{ strtoupper($peserta['sekolah_asal']) }}</td>
                </tr>
            </table>

            <table class="data-table keahlian-table">
                <tbody>
                    <tr>
                        <td class="lbl" rowspan="{{ $jmlJurusan }}">Komp. Keahlian Pilihan&nbsp;&nbsp;</td>
                        <td class="sep" rowspan="{{ $jmlJurusan }}">:</td>
                        <td class="val">1. {{ $peserta['pilihan_jurusan'][0] ?? '-' }}</td>
                    </tr>
                    @if(isset($peserta['pilihan_jurusan'][1]))
                    <tr>
                        <td class="val">2. {{ $peserta['pilihan_jurusan'][1] }}</td>
                    </tr>
                    @endif
                    @if(isset($peserta['pilihan_jurusan'][2]))
                    <tr>
                        <td class="val">3. {{ $peserta['pilihan_jurusan'][2] }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>

            <div class="section-heading">Kelengkapan Berkas:</div>
            <table class="berkas-table">
                <thead>
                    <tr>
                        <th class="center" style="width:12%">No</th>
                        <th style="width:58%">Nama Berkas</th>
                        <th class="center" style="width:30%">Ada / Tidak</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peserta['kelengkapan_berkas'] as $index => $berkas)
                    <tr>
                        <td class="center">{{ $index + 1 }}</td>
                        <td>{{ $berkas['nama'] }}</td>
                        @if($berkas['status'])
                        <td class="center ada">Ada</td>
                        @else
                        <td class="center tidak">Tidak</td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="note-paragraph">
                Peserta dengan data di atas telah menyelesaikan seluruh tahapan <strong>Verifikasi Berkas</strong> dan
                <strong>Observasi Fisik serta Kesehatan</strong>, sebagai bagian dari proses seleksi
                Sistem Penerimaan Murid Baru (SPMB) Tahun {{ $peserta['tahun_spmb'] }}.
            </div>

            <table class="signature-section">
                <tr>
                    <td>
                    </td>
                    <td>
                        <div class="sig-role">Verifikator</div>
                        <div class="sig-location">{{ $peserta['lokasi_ttd'] }}, {{ $peserta['tanggal_ttd'] }}</div>
                        <span class="sig-name">{{ $peserta['nama_verifikator'] }}</span>
                    </td>
                </tr>
            </table>

        </div>
    </div>
</body>

</html>
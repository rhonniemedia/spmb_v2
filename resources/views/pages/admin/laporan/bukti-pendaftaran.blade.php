<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pendaftaran - SPMB 2025</title>
    <style>
        /* Mengatur margin untuk halaman */
        @page {
            margin: 1.5cm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            background-color: #fff;
        }

        .document {
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            position: relative;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 14px;
        }

        .header h1 {
            margin: 5px 0;
            font-size: 16px;
        }

        .dotted-line {
            border-top: 1px solid #000;
            margin: 5px 0;
        }

        .double-dotted-line {
            border-top: 2px dotted #000;
            border-bottom: 2px dotted #000;
            margin: 5px 0;
            padding: 5px 0;
        }

        .table-container {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .table-container td {
            padding: 3px;
            vertical-align: top;
            border: none;
            border-bottom: 1px dotted #ccc;
        }

        .table-container th {
            padding: 2px;
            vertical-align: top;
            font-weight: bold;
            border: none;
            border-bottom: 1px solid #000;
        }

        .checkbox {
            text-align: center;
            width: 50px;
        }

        .checkbox:before {
            content: "□";
            font-size: 18px;
        }

        .signature-area {
            margin-top: 20px;
            text-align: right;
        }

        .signature-line {
            display: inline-block;
            width: 150px;
            border-top: 1px dotted #000;
        }

        .left-align {
            text-align: left;
        }

        .right-align {
            text-align: right;
        }

        .center-align {
            text-align: center;
        }

        .spaced-text {
            line-height: 1.5;
        }
    </style>
</head>

<body>
    <div class="document">
        <!-- Bagian Bukti Verifikasi dan Observasi -->
        <div class="header">
            <h1>BUKTI VERIFIKASI DAN OBSERVASI</h1>
            <h2>SISTEM PENERIMAAN MURID BARU (SPMB) 2025</h2>
            <h2>SMK NEGERI 1 REJANG LEBONG</h2>
        </div>

        <table class="table-container">
            <tr>
                <td width="20%"><strong>Nomor Pendaftaran:</strong></td>
                <td width="35%">{{ $dataPendaftar->registration_number }}</td>
                <td width="17%"><strong>Sekolah Asal:</strong></td>
                <td width="28%">{{ $dataPendaftar->asal_sekolah }}</td>
            </tr>
            <tr>
                <td><strong>Nama Lengkap:</strong></td>
                <td>{{ Str::upper($dataPendaftar->student_name) }}</td>
                <td><strong>Nomor Telepon:</strong></td>
                <td>{{ $dataPendaftar->nomor_telepon }}</td>
            </tr>
            <tr>
                <td><strong>Komp. Keahlian:</strong></td>
                <td>{{ $dataPendaftar->keahlianSatu->nama_keahlian }} ({{ $dataPendaftar->keahlianSatu->alias }})</td>
                <td></td>
                <td></td>
            </tr>
        </table>

        <p class="spaced-text"><strong>Telah menyelesaikan seluruh tahapan Verifikasi Berkas dan Observasi Fisik serta Kesehatan, sebagai bagian dari proses seleksi Sistem Penerimaan Murid Baru (SPMB) Tahun 2025.</strong></p>

        <table class="table-container">
            <tr>
                <td width="14%"></td>
                <td width="43%">
                    <br>
                    Observator<br><br><br><br><br>
                    <div class="signature-line"></div>
                </td>
                <td width="43%">
                    Rejang Lebong, {{ $tanggalHariIni }}<br>
                    Verifikator<br><br><br><br>
                    {{ auth()->user()->name }}
                </td>
            </tr>
        </table>

        <!-- Bagian Tanda Terima Berkas Asli -->
        <div class="header">
            <h1>TANDA TERIMA BERKAS ASLI</h1>
            <h2>SISTEM PENERIMAAN MURID BARU (SPMB) 2025</h2>
            <h2>SMK NEGERI 1 REJANG LEBONG</h2>
        </div>

        <table class="table-container">
            <tr>
                <td width="20%"><strong>Nomor Pendaftaran:</strong></td>
                <td width="35%">{{ $dataPendaftar->registration_number }}</td>
                <td width="17%"><strong>Sekolah Asal:</strong></td>
                <td width="28%">{{ $dataPendaftar->asal_sekolah }}</td>
            </tr>
            <tr>
                <td><strong>Nama Lengkap:</strong></td>
                <td>{{ Str::upper($dataPendaftar->student_name) }}</td>
                <td><strong>Nomor Telepon:</strong></td>
                <td>{{ $dataPendaftar->nomor_telepon }}</td>
            </tr>
            <tr>
                <td><strong>Komp. Keahlian:</strong></td>
                <td>{{ $dataPendaftar->keahlianSatu->nama_keahlian }} ({{ $dataPendaftar->keahlianSatu->alias }})</td>
                <td></td>
                <td></td>
            </tr>
        </table>

        <div class="dotted-line"></div>

        <table class="table-container">
            <tr>
                <th class="left-align">Nama Berkas</th>
                <th class="center-align">Ada</th>
                <th class="center-align">Tidak</th>
            </tr>
            <tr>
                <td>Ijazah Asli</td>
                <td class="center-align">
                    @if ($dataPendaftar->berkas_ijazah === 'ada')
                    <img src="{{ public_path('assets/images/icons/check.png') }}" width="15">
                    @endif
                </td>
                <td class="center-align">
                    @if ($dataPendaftar->berkas_ijazah === 'tidak')
                    <img src="{{ public_path('assets/images/icons/check.png') }}" width="15">
                    @endif
                </td>
            </tr>
            <tr>
                <td>Surat Keterangan Lulus (SKL)</td>
                <td class="center-align">
                    @if ($dataPendaftar->berkas_skl === 'ada')
                    <img src="{{ public_path('assets/images/icons/check.png') }}" width="15">
                    @endif
                </td>
                <td class="center-align">
                    @if ($dataPendaftar->berkas_skl === 'tidak')
                    <img src="{{ public_path('assets/images/icons/check.png') }}" width="15">
                    @endif
                </td>
            </tr>
            <tr>
                <td>Rapor</td>
                <td class="center-align">
                    @if ($dataPendaftar->berkas_rapor === 'ada')
                    <img src="{{ public_path('assets/images/icons/check.png') }}" width="15">
                    @endif
                </td>
                <td class="center-align">
                    @if ($dataPendaftar->berkas_rapor === 'tidak')
                    <img src="{{ public_path('assets/images/icons/check.png') }}" width="15">
                    @endif
                </td>
            </tr>
        </table>

        <table class="table-container">
            <tr>
                <td width="57%"></td>
                <td width="43%">
                    Rejang Lebong, {{ $tanggalHariIni }}<br>
                    Verifikator<br><br><br><br>
                    {{ auth()->user()->name }}
                </td>
            </tr>
        </table>

        <!-- Pemisah Halaman -->
        <!-- <div style="page-break-before: always;"></div> -->

        <!-- Bagian Ceklis Kelengkapan Berkas Asli -->
        <div class="header">
            <h1>CEKLIS KELENGKAPAN BERKAS ASLI</h1>
            <h2>SISTEM PENERIMAAN MURID BARU (SPMB) 2025</h2>
            <h2>SMK NEGERI 1 REJANG LEBONG</h2>
        </div>

        <table class="table-container">
            <tr>
                <td width="20%"><strong>Nomor Pendaftaran:</strong></td>
                <td width="35%">{{ $dataPendaftar->registration_number }}</td>
                <td width="17%"><strong>Sekolah Asal:</strong></td>
                <td width="28%">{{ $dataPendaftar->asal_sekolah }}</td>
            </tr>
            <tr>
                <td><strong>Nama Lengkap:</strong></td>
                <td>{{ Str::upper($dataPendaftar->student_name) }}</td>
                <td><strong>Nomor Telepon:</strong></td>
                <td>{{ $dataPendaftar->nomor_telepon }}</td>
            </tr>
            <tr>
                <td><strong>Komp. Keahlian:</strong></td>
                <td>{{ $dataPendaftar->keahlianSatu->nama_keahlian }} ({{ $dataPendaftar->keahlianSatu->alias }})</td>
                <td></td>
                <td></td>
            </tr>
        </table>

        <div class="dotted-line"></div>

        <table class="table-container">
            <tr>
                <th class="left-align">Nama Berkas</th>
                <th class="center-align">Ada</th>
                <th class="center-align">Tidak</th>
            </tr>
            <tr>
                <td>Ijazah Asli</td>
                <td class="center-align">
                    @if ($dataPendaftar->berkas_ijazah === 'ada')
                    <img src="{{ public_path('assets/images/icons/check.png') }}" width="15">
                    @endif
                </td>
                <td class="center-align">
                    @if ($dataPendaftar->berkas_ijazah === 'tidak')
                    <img src="{{ public_path('assets/images/icons/check.png') }}" width="15">
                    @endif
                </td>
            </tr>
            <tr>
                <td>Surat Keterangan Lulus (SKL)</td>
                <td class="center-align">
                    @if ($dataPendaftar->berkas_skl === 'ada')
                    <img src="{{ public_path('assets/images/icons/check.png') }}" width="15">
                    @endif
                </td>
                <td class="center-align">
                    @if ($dataPendaftar->berkas_skl === 'tidak')
                    <img src="{{ public_path('assets/images/icons/check.png') }}" width="15">
                    @endif
                </td>
            </tr>
            <tr>
                <td>Rapor</td>
                <td class="center-align">
                    @if ($dataPendaftar->berkas_rapor === 'ada')
                    <img src="{{ public_path('assets/images/icons/check.png') }}" width="15">
                    @endif
                </td>
                <td class="center-align">
                    @if ($dataPendaftar->berkas_rapor === 'tidak')
                    <img src="{{ public_path('assets/images/icons/check.png') }}" width="15">
                    @endif
                </td>
            </tr>
        </table>

        <table class="table-container">
            <tr>
                <td width="57%"></td>
                <td width="43%">
                    Rejang Lebong, {{ $tanggalHariIni }}<br>
                    Verifikator<br><br><br><br>
                    {{ auth()->user()->name }}
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
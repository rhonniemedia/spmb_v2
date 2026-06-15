<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Peminat Pendaftaran</title>
    <style>
        /* Mengatur margin untuk halaman */
        @page {
            margin: 1.5cm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0px;
            font-size: 12px;
        }

        .text-center {
            text-align: center;
        }

        h2,
        h3 {
            margin: 4px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 4px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background-color: #f0f0f0;
        }

        .rekap-table {
            width: 25%;
            margin-top: 30px;
            border-collapse: collapse;
        }

        .rekap-table td {
            border: 1px solid black;
            padding: 4px;
        }

        .signature {
            margin-top: 10px;
            width: 100%;
        }

        .signature td {
            border: none;
            text-align: left;
            padding-top: 20px;
            vertical-align: top;
        }

        .spacing {
            margin-top: 40px;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="text-center">
        <h2>DAFTAR PEMINAT PENDAFTARAN</h2>
        <h3>SISTEM PENERIMAAN MURID BARU (SPMB)</h3>
        <h3>SMK NEGERI 1 REJANG LEBONG</h3>
        <h3>TANGGAL {{ Str::upper($tanggalHariIni) }}</h3>
    </div>

    <table>
        <thead>
            <tr>
                <th colspan="2">NOMOR</th>
                <th rowspan="2">NAMA PENDAFTAR</th>
                <th rowspan="2">JK</th>
                <th rowspan="2">JURUSAN</th>
                <th colspan="2">TANGGAL</th>
                <th rowspan="2">SEKOLAH ASAL</th>
            </tr>
            <tr>
                <th>URUT</th>
                <th>REGISTRASI</th>
                <th>VERIFIKASI</th>
                <th>OBSERVASI</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dataPendaftar as $pendaftar)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $pendaftar->registration_number }}</td>
                <td class="text-left">{{ Str::upper($pendaftar->student_name) }}</td>
                <td>{{ $pendaftar->gender }}</td>
                <td>{{ $pendaftar->keahlianSatu->alias }}</td>
                <td>{{ $pendaftar->tanggal_daftar }}</td>
                <td>{{ $pendaftar->tanggal_observasi ?? '' }}</td>
                <td class="text-left">{{ Str::upper($pendaftar->asal_sekolah) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="rekap-table">
        <tr>
            <th colspan="2" width="141">
                Rekapitulasi
            </th>
        </tr>
        <tr>
            <td class="text-left">Laki-laki</td>
            <td class="text-right">{{ $jumlahLakiLaki }}</td>
        </tr>
        <tr>
            <td class="text-left">Perempuan</td>
            <td class="text-right">{{ $jumlahPerempuan }}</td>
        </tr>
        <tr>
            <td class="text-left">Jumlah Total</td>
            <td class="text-right">{{ $jumlahTotal }}</td>
        </tr>
    </table>

    <table class="signature">
        <tr>
            <td width="70%"></td>
            <td width="30%">
                Rejang Lebong, {{ $tanggalHariIni }}<br>
                Ketua,<br><br><br><br><br>
                <strong>Tiar Hadi Saputra, M.Pd</strong><br>
                NIP 199107312022211006
            </td>
        </tr>
    </table>

</body>

</html>
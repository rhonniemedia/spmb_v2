<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Pendaftaran SPMB</title>
    <style>
        /* Mengatur margin untuk halaman */
        @page {
            margin: 1.5cm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0px;
            font-size: 13px;
        }

        .text-center {
            text-align: center;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 15px;
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
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

        .right {
            float: right;
        }

        .text-left {
            text-align: left;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>REKAPITULASI PEMINAT PENDAFTARAN</h1>
        <h2>SISTEM PENERIMAAN MURID BARU (SPMB)</h2>
        <h2>SMK NEGERI 1 REJANG LEBONG</h2>
        <h2>TANGGAL {{ Str::upper($tanggalHariIni) }}</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2">NO</th>
                <th rowspan="2">KONSENTRASI KEAHLIAN</th>
                <th rowspan="2">KUOTA</th>
                <th colspan="3">PEMINAT</th>
            </tr>
            <tr>
                <th>L</th>
                <th>P</th>
                <th>JML</th>
            </tr>
        </thead>
        <tbody>
            @php
            // Inisialisasi penjumlahan total
            $sumKuota = 0;
            $sumLaki = 0;
            $sumPerempuan = 0;
            $sumTotal = 0;
            @endphp

            @foreach ($dataKeahlian as $keahlian)
            @php
            $jumlahLaki = $rekap[$keahlian->id]['laki'] ?? 0;
            $jumlahPerempuan = $rekap[$keahlian->id]['perempuan'] ?? 0;
            $jumlahTotal = $rekap[$keahlian->id]['total'] ?? 0;

            // Tambahkan ke variabel total
            $sumKuota += $keahlian->quota;
            $sumLaki += $jumlahLaki;
            $sumPerempuan += $jumlahPerempuan;
            $sumTotal += $jumlahTotal;
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="text-left">{{ $keahlian->name }} ({{ $keahlian->alias }})</td>
                <td>{{ $keahlian->quota }}</td>
                <td>{{ $jumlahLaki }}</td>
                <td>{{ $jumlahPerempuan }}</td>
                <td>{{ $jumlahTotal }}</td>
            </tr>
            @endforeach

            <tr>
                <td colspan="2" class="text-right"><strong>Jumlah Total</strong></td>
                <td><strong>{{ $sumKuota }}</strong></td>
                <td><strong>{{ $sumLaki }}</strong></td>
                <td><strong>{{ $sumPerempuan }}</strong></td>
                <td><strong>{{ $sumTotal }}</strong></td>
            </tr>
        </tbody>
    </table>

    <table class="signature">
        <tr>
            <td width="60%"></td>
            <td width="40%">
                Rejang Lebong, {{ $tanggalHariIni }}<br>
                Ketua,<br><br><br><br><br>
                <strong>Tiar Hadi Saputra, M.Pd</strong><br>
                NIP 199107312022211006
            </td>
        </tr>
    </table>

</body>

</html>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Peserta Daftar Ulang</title>
    <style>
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

        .text-left {
            text-align: left !important;
        }

        .text-right {
            text-align: right;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 15px;
            margin: 2px 0;
        }

        .concentration-date {
            margin-bottom: 10px;
            font-weight: bold;
        }

        .concentration-date table {
            width: 100%;
            border: none;
            border-collapse: collapse;
        }

        .concentration-date td {
            border: none;
            padding: 4px 0;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid black;
            padding: 6px;
            text-align: center;
            vertical-align: middle;
        }

        table.data-table th {
            background-color: #f0f0f0;
        }

        thead {
            display: table-header-group;
        }

        .rekap-table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }

        .rekap-table td {
            border: 1px solid black;
            padding: 4px;
            text-align: left;
        }

        .signature {
            margin-top: 20px;
            width: 100%;
        }

        .signature td {
            border: none;
            text-align: left;
            padding-top: 20px;
            vertical-align: top;
        }

        .today-cell {
            background-color: #003366;
            color: white;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>DAFTAR PESERTA DAFTAR ULANG</h2>
        <h2>SISTEM PENERIMAAN MURID BARU</h2>
        <h2 class="school-name">SMK NEGERI 1 REJANG LEBONG</h2>
        <h2>TAHUN {{ now()->year }}</h2>
    </div>

    @foreach ($dataKeahlian as $keahlian)
    <div class="concentration-date">
        <table>
            <tr>
                <td class="text-left" style="width: 50%;">Konsentrasi Keahlian: {{ $keahlian->name }} ({{ $keahlian->alias }})</td>
                <td class="text-right" style="width: 50%;">Tanggal Cetak: {{ $tanggalHariIni }}</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th width="15%">NO REGISTRASI</th>
                <th width="30%">NAMA LENGKAP</th>
                <th width="5%">JK</th>
                <th width="25%">ASAL SEKOLAH</th>
                <th width="12%">TANGGAL DAFTAR ULANG</th>
                <th width="8%">KET</th>
            </tr>
        </thead>
        <tbody>
            @php
            $daftar = $pendaftarPerKeahlian[$keahlian->id] ?? collect();
            @endphp

            @forelse ($daftar as $pendaftar)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $pendaftar->registration_number }}</td>
                <td class="text-left">{{ strtoupper($pendaftar->student_name) }}</td>
                <td>{{ $pendaftar->gender }}</td>
                <td class="text-left">{{ strtoupper($pendaftar->asal_sekolah) }}</td>
                <td>{{ $pendaftar->tanggal_daftar_ulang }}</td>
                <td>{{ $pendaftar->keterangan }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Belum ada peserta yang dinyatakan lulus / diterima pada Konsentrasi Keahlian ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @php
    $daftar = collect($pendaftarPerKeahlian[$keahlian->id] ?? []);
    $jumlahLaki = $daftar->where('gender', 'L')->count();
    $jumlahPerempuan = $daftar->where('gender', 'P')->count();
    $jumlahTotal = $daftar->count();
    @endphp

    <table class="rekap-table">
        <tr>
            <th width="15%" style="background-color: #f0f0f0; border: 1px solid black; text-align: center;">Rekapitulasi</th>
            <td>Laki-laki: {{ $jumlahLaki }}</td>
            <td>Perempuan: {{ $jumlahPerempuan }}</td>
            <td>Jumlah Total: {{ $jumlahTotal }}</td>
        </tr>
    </table>

    @if (!$loop->last)
    <div style="page-break-after: always;"></div>
    @endif

    @endforeach

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
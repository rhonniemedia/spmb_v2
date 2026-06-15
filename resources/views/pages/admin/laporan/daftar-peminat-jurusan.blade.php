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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
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

    <div class="header">
        <h1>DAFTAR PEMINAT PENDAFTARAN</h1>
        <h2>SISTEM PENERIMAAN MURID BARU</h2>
        <h2 class="school-name">SMK NEGERI 1 REJANG LEBONG</h2>
        <h2>TAHUN 2026</h2>
    </div>

    @foreach ($dataKeahlian as $keahlian)
    <div class="concentration-date">
        <table>
            <tr>
                <td class="text-left" style="width: 50%;">Konsentrasi Keahlian: {{ $keahlian->name }} ({{ $keahlian->alias }})</td>
                <td class="text-right" style="width: 50%;">Tanggal: {{ $tanggalHariIni }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th colspan="2">NOMOR</th>
                <th rowspan="2">NAMA</th>
                <th rowspan="2">JK</th>
                <th colspan="2">TANGGAL</th>
                <th rowspan="2">ASAL SEKOLAH</th>
            </tr>
            <tr>
                <th>URUT</th>
                <th>REGISTRASI</th>
                <th>VERIFIKASI</th>
                <th>OBSERVASI</th>
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
                <td class="text-left">{{ Str::upper($pendaftar->student_name) }}</td>
                <td>{{ $pendaftar->gender }}</td>
                <td>{{ $pendaftar->tanggal_daftar }}</td>
                <td>{{ $pendaftar->tanggal_observasi ?? '-' }}</td>
                <td class="text-left">{{ Str::upper($pendaftar->asal_sekolah) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Belum ada pendaftar pada Konsentrasi Keahlian ini.</td>
            </tr>
            @endforelse

            <!-- Tambahkan baris dinamis lainnya -->
        </tbody>
    </table>

    @php
    $jumlahLaki = $daftar->where('gender', 'L')->count();
    $jumlahPerempuan = $daftar->where('gender', 'P')->count();
    $jumlahTotal = $jumlahLaki + $jumlahPerempuan;
    @endphp
    <table class="rekap-table">
        <tr>
            <th>Rekapitulasi</th>
            <td>Laki-laki: {{ $jumlahLaki }}</td>
            <td>Perempuan: {{ $jumlahPerempuan }}</td>
            <td>Jumlah Total: {{ $jumlahTotal }}</td>
        </tr>
    </table>

    {{-- Sisipkan pemisah halaman kecuali di iterasi terakhir --}}
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
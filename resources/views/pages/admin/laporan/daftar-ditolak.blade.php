<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Peserta Ditolak</title>
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
            text-align: left;
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

        thead {
            display: table-header-group;
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

        .sel-error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .today-cell {
            background-color: #003366;
            color: white;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>DAFTAR PESERTA TIDAK DIJENJANGKAN (DITOLAK)</h2>
        <h2>SISTEM PENERIMAAN MURID BARU</h2>
        <h2 class="school-name">SMK NEGERI 1 REJANG LEBONG</h2>
        <h2>TAHUN {{ now()->year }}</h2>
    </div>

    <div class="concentration-date">
        <table>
            <tr>
                <td class="text-left" style="width: 50%;">Batch Penjenjangan: #{{ $latestBatch }}</td>
                <td class="text-right" style="width: 50%;">Tanggal Cetak: {{ $tanggalHariIni }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="text-align: center;" colspan="2">NOMOR</th>
                <th style="text-align: center;" rowspan="2">NAMA</th>
                <th style="text-align: center;" rowspan="2">JK</th>
                <th style="text-align: center;" rowspan="2">NILAI AKHIR</th>
                <th style="text-align: center;" colspan="2">TANGGAL</th>
                <th style="text-align: center;" rowspan="2">ASAL SEKOLAH</th>
                <th style="text-align: center;" rowspan="2">JURUSAN</th>
                <th style="text-align: center;" rowspan="2">KET</th>
            </tr>
            <tr>
                <th style="text-align: center;">URUT</th>
                <th style="text-align: center;">REGISTRASI</th>
                <th style="text-align: center;">VERIFIKASI</th>
                <th style="text-align: center;">OBSERVASI</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tidakDijenjang as $index => $pendaftar)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $pendaftar->registration_number }}</td>
                <td class="text-left">{{ strtoupper($pendaftar->student_name) }}</td>
                <td>{{ $pendaftar->gender }}</td>
                <td @class([ 'sel-error'=> $pendaftar->input_rapor == 'tidak', ])>
                    {{ $pendaftar->nilai_akhir }}
                </td>
                <td>{{ $pendaftar->tanggal_daftar }}</td>
                <td>{{ $pendaftar->tanggal_observasi ?? '-' }}</td>
                <td class="text-left">{{ strtoupper($pendaftar->asal_sekolah) }}</td>
                {{-- Mengambil Pilihan 1 dari Relasi --}}
                <td>{{ $pendaftar->registration->choice1->alias ?? '-' }}</td>
                <td>{{ $pendaftar->keterangan }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">Tidak ada data.</td>
            </tr>
            @endforelse
        </tbody>
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
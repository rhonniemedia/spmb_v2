<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tanda Terima Pengembalian Berkas Asli</title>
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
    </style>
</head>

<body>

    <div class="header">
        <h1>TANDA TERIMA PENGEMBALIAN BERKAS ASLI</h1>
        <h2>SISTEM PENERIMAAN MURID BARU (SPMB)</h2>
        <h2>SMK NEGERI 1 REJANG LEBONG</h2>
        <h2>TAHUN {{ now()->year }}</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th colspan="2">NOMOR</th>
                <th rowspan="2">NAMA PENDAFTAR</th>
                <th rowspan="2">JK</th>
                <th rowspan="2">PRODI</th>
                <th rowspan="2">SEKOLAH ASAL</th>
                <th colspan="5">BERKAS</th>
                <th rowspan="2">TANGGAL KEMBALI</th>
                <th rowspan="2">TANDA TANGAN</th>
            </tr>
            <tr>
                <th>URUT</th>
                <th>REGISTRASI</th>
                <th style="width: 20px; font-size: 10px;">AKT</th>
                <th style="width: 20px; font-size: 10px;">IJZ</th>
                <th style="width: 20px; font-size: 10px;">SKL</th>
                <th style="width: 20px; font-size: 10px;">RAP</th>
                <th style="width: 20px; font-size: 10px;">SKR</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dataPendaftar as $pendaftar)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td style="white-space: nowrap; font-size: 11px;">{{ $pendaftar->registration_number }}</td>
                <td class="text-left">{{ Str::upper($pendaftar->student_name) }}</td>
                <td>{{ $pendaftar->gender }}</td>
                <td>{{ $pendaftar->keahlianSatu->alias ?? '-' }}</td>
                <td class="text-left">{{ Str::upper($pendaftar->asal_sekolah) }}</td>

                {{-- 1. AKT (Akta) --}}
                <td class="center-align">
                    @if ($pendaftar->berkas_akta === 'ada')
                    <span style="font-family: DejaVu Sans, sans-serif; font-size: 14px;">&#10004;</span> {{-- Simbol Centang (✔) --}}
                    @else
                    <span style="font-family: DejaVu Sans, sans-serif; font-size: 14px;">&#10008;</span> {{-- Simbol Silang (✘) --}}
                    @endif
                </td>

                {{-- 2. IJZ (Ijazah) --}}
                <td class="center-align">
                    @if ($pendaftar->berkas_ijazah === 'ada')
                    <span style="font-family: DejaVu Sans, sans-serif; font-size: 14px;">&#10004;</span> {{-- Simbol Centang (✔) --}}
                    @else
                    <span style="font-family: DejaVu Sans, sans-serif; font-size: 14px;">&#10008;</span> {{-- Simbol Silang (✘) --}}
                    @endif
                </td>

                {{-- 3. SKL (Surat Keterangan Lulus) --}}
                <td class="center-align">
                    @if ($pendaftar->berkas_skl === 'ada')
                    <span style="font-family: DejaVu Sans, sans-serif; font-size: 14px;">&#10004;</span> {{-- Simbol Centang (✔) --}}
                    @else
                    <span style="font-family: DejaVu Sans, sans-serif; font-size: 14px;">&#10008;</span> {{-- Simbol Silang (✘) --}}
                    @endif
                </td>

                {{-- 4. RAP (Rapor) --}}
                <td class="center-align">
                    @if ($pendaftar->berkas_rapor === 'ada')
                    <span style="font-family: DejaVu Sans, sans-serif; font-size: 14px;">&#10004;</span> {{-- Simbol Centang (✔) --}}
                    @else
                    <span style="font-family: DejaVu Sans, sans-serif; font-size: 14px;">&#10008;</span> {{-- Simbol Silang (✘) --}}
                    @endif
                </td>

                {{-- 5. SKR (Surat Keterangan) --}}
                <td class="center-align">
                    @if ($pendaftar->berkas_skr === 'ada')
                    <span style="font-family: DejaVu Sans, sans-serif; font-size: 14px;">&#10004;</span> {{-- Simbol Centang (✔) --}}
                    @else
                    <span style="font-family: DejaVu Sans, sans-serif; font-size: 14px;">&#10008;</span> {{-- Simbol Silang (✘) --}}
                    @endif
                </td>

                {{-- Kolom Kosong untuk Tanggal Kembali & Tanda Tangan --}}
                <td></td>
                <td></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="signature">
        <tr>
            <td width="70%"></td>
            <td width="30%">
                Rejang Lebong, ____________<br>
                Ketua,<br><br><br><br><br>
                <strong>Tiar Hadi Saputra, M.Pd</strong><br>
                NIP 199107312022211006
            </td>
        </tr>
    </table>

</body>

</html>
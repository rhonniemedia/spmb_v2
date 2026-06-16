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

        /* public/css/app.css */
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
        <h2>DAFTAR PENJENJANGAN SEMENTARA</h2>
        <h2>SISTEM PENERIMAAN MURID BARU</h2>
        <h2 class="school-name">SMK NEGERI 1 REJANG LEBONG</h2>
        <h2>TAHUN {{ now()->year }}</h2>
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
                <th style="text-align: center;" colspan="2">NOMOR</th>
                <th style="text-align: center;" rowspan="2">NAMA</th>
                <th style="text-align: center;" rowspan="2">JK</th>
                {{-- Ubah colspan="2" menjadi rowspan="2" untuk NILAI AKHIR --}}
                <th style="text-align: center;" rowspan="2">NILAI AKHIR</th>
                <th style="text-align: center;" colspan="2">TANGGAL</th>
                <th style="text-align: center;" rowspan="2">ASAL SEKOLAH</th>
                <th style="text-align: center;" rowspan="2">KET</th>
            </tr>
            <tr>
                <th style="text-align: center;">URUT</th>
                <th style="text-align: center;">REGISTRASI</th>
                {{-- Kolom AKHIR dan SELEKSI dihapus dari sini --}}
                <th style="text-align: center;">VERIFIKASI</th>
                <th style="text-align: center;">OBSERVASI</th>
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
                <td @class([ 'sel-error'=> $pendaftar->input_rapor == 'tidak', ])>
                    {{ $pendaftar->nilai_akhir }}
                </td>
                {{-- Kolom nilai observasi (seleksi) dihapus dari sini --}}
                @php
                $isToday = false;
                try {
                $isToday = \Carbon\Carbon::createFromFormat('d/m/Y', $pendaftar->tanggal_daftar)->isToday();
                } catch (\Exception $e) {
                // Tangani jika format salah
                }
                @endphp
                <td class="{{ $isToday ? 'today-cell' : '' }}"> {{ $pendaftar->tanggal_daftar }}
                </td>
                <td>{{ $pendaftar->tanggal_observasi ?? '-' }}</td>
                <td class="text-left">{{ strtoupper($pendaftar->asal_sekolah) }}</td>
                <td>{{ $pendaftar->pilihan_jalur ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                {{-- Ubah colspan menjadi 9 agar sesuai dengan jumlah kolom tabel yang baru --}}
                <td colspan="9" class="text-center">Belum ada pendaftar pada Konsentrasi Keahlian ini.</td>
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
            <td width="70%">
            </td>
            <td width="30%">
                Rejang Lebong, {{ $tanggalHariIni }}<br>
                Ketua,<br><br><br><br><br>
                <strong>Tiar Hadi Saputra, M.Pd</strong><br>
                NIP 199107312022211006
            </td>
        </tr>
    </table>

    @if ($belumTerjenjang->isNotEmpty())

    <div style="page-break-before: always;"></div>

    <div class="concentration-date">
        <table>
            <tr>
                <td class="text-left" style="width: 50%;">BELUM MASUK PENJENJANGAN</td>
                <td class="text-right" style="width: 50%;">Tanggal: {{ $tanggalHariIni }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="text-align: center;" colspan="2">NOMOR</th>
                <th style="text-align: center;" rowspan="2">NAMA</th>
                <th style="text-align: center;" rowspan="2">JK</th>
                <th style="text-align: center;" colspan="2">NILAI</th>
                <th style="text-align: center;" colspan="2">TANGGAL</th>
                <th style="text-align: center;" rowspan="2">ASAL SEKOLAH</th>
                <th style="text-align: center;" rowspan="2">KET</th>
            </tr>
            <tr>
                <th style="text-align: center;">URUT</th>
                <th style="text-align: center;">REGISTRASI</th>
                <th style="text-align: center;">AKHIR</th>
                <th style="text-align: center;">SELEKSI</th>
                <th style="text-align: center;">VERIFIKASI</th>
                <th style="text-align: center;">OBSERVASI</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($belumTerjenjang as $index => $pendaftar)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $pendaftar->registration_number }}</td>
                <td class="text-left">{{ strtoupper($pendaftar->student_name) }}</td>
                <td>{{ $pendaftar->gender }}</td>
                <td @class([ 'sel-error'=> $pendaftar->input_rapor == 'tidak', ])>
                    {{ $pendaftar->nilai_akhir }}
                </td>
                <td>{{ $pendaftar->hasilObservasi->total_nilai ?? '-' }}</td>
                <td>{{ $pendaftar->tanggal_daftar }}</td>
                <td>{{ $pendaftar->tanggal_observasi ?? '-' }}</td>
                <td class="text-left">{{ strtoupper($pendaftar->asal_sekolah) }}</td>
                <td>TIDAK TERJENJANG</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @endif

    <table class="signature">
        <tr>
            <td width="70%"></td>
            <td width="30%">
                Rejang Lebong, {{ $tanggalHariIni }}<br>
                Ketua,<br><br><br><br><br>
                <strong>Meilinda, M.Pd</strong><br>
                NIP 198405202009032006
            </td>
        </tr>
    </table>

    @if (isset($tidakDijenjang) && $tidakDijenjang->isNotEmpty())

    <div style="page-break-before: always;"></div>

    <div class="concentration-date">
        <table>
            <tr>
                <td class="text-left" style="width: 50%;">TIDAK DIJENJANGKAN</td>
                <td class="text-right" style="width: 50%;">Tanggal: {{ $tanggalHariIni }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="text-align: center;" colspan="2">NOMOR</th>
                <th style="text-align: center;" rowspan="2">NAMA</th>
                <th style="text-align: center;" rowspan="2">JK</th>
                <th style="text-align: center;" colspan="2">NILAI</th>
                <th style="text-align: center;" colspan="2">TANGGAL</th>
                <th style="text-align: center;" rowspan="2">ASAL SEKOLAH</th>
                <th style="text-align: center;" rowspan="2">KET</th>
            </tr>
            <tr>
                <th style="text-align: center;">URUT</th>
                <th style="text-align: center;">REGISTRASI</th>
                <th style="text-align: center;">AKHIR</th>
                <th style="text-align: center;">SELEKSI</th>
                <th style="text-align: center;">VERIFIKASI</th>
                <th style="text-align: center;">OBSERVASI</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tidakDijenjang as $index => $pendaftar)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $pendaftar->registration_number }}</td>
                <td class="text-left">{{ strtoupper($pendaftar->student_name) }}</td>
                <td>{{ $pendaftar->gender }}</td>
                <td @class([ 'sel-error'=> $pendaftar->input_rapor == 'tidak', ])>
                    {{ $pendaftar->nilai_akhir }}
                </td>
                </td>
                <td>{{ $pendaftar->hasilObservasi->total_nilai ?? '-' }}</td>
                <td>{{ $pendaftar->tanggal_daftar }}</td>
                <td>{{ $pendaftar->tanggal_observasi ?? '-' }}</td>
                <td class="text-left">{{ strtoupper($pendaftar->asal_sekolah) }}</td>
                <td>
                    @php
                    $keterangan = [];

                    if ($pendaftar->hasilObservasi?->buta_warna === 'ya') {
                    $keterangan[] = 'Buta Warna';
                    }

                    if (
                    $pendaftar->hasilObservasi?->tato === 'ya' ||
                    $pendaftar->hasilObservasi?->bekas_tato === 'ya'
                    ) {
                    $keterangan[] = 'Tato / Bekas Tato';
                    }

                    if ($pendaftar->hasilObservasi?->tindik === 'ya') {
                    $keterangan[] = 'Tindik';
                    }
                    @endphp

                    @if (count($keterangan))
                    @foreach ($keterangan as $ket)
                    {{ $ket }}
                    @endforeach
                    @else
                    -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="signature">
        <tr>
            <td width="70%"></td>
            <td width="30%">
                Rejang Lebong, {{ $tanggalHariIni }}<br>
                Ketua,<br><br><br><br><br>
                <strong>Meilinda, M.Pd</strong><br>
                NIP 198405202009032006
            </td>
        </tr>
    </table>

    @endif

</body>

</html>
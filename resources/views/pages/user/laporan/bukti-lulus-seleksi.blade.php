<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Bukti Kelulusan - SPMB {{ date('Y') }}</title>
    <style>
        @page {
            margin: 1.5cm 1.6cm;
        }

        @media print {
            .section-title {
                background-color: #555 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .status-accepted {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .score-table th {
                background-color: #d9d9d9 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .score-table .final-row td {
                background-color: #e6e6e6 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .choice-table th {
                background-color: #d9d9d9 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11.5px;
            color: #1a1a1a;
            margin: 0;
        }

        .document {
            width: 100%;
        }

        /* ===== KOP SURAT ===== */
        .letterhead {
            width: 100%;
            border-bottom: 3px solid #1d4ed8;
            padding-bottom: 8px;
            margin-bottom: 14px;
            text-align: center;
        }

        .letterhead .logo {
            display: block;
            margin: 0 auto 6px auto;
            width: 70px;
            height: 70px;
        }

        .letterhead .logo img {
            width: 70px;
            height: 70px;
        }

        .letterhead h3 {
            margin: 0;
            font-size: 11px;
            font-weight: normal;
            letter-spacing: 0.5px;
        }

        .letterhead h1 {
            margin: 2px 0;
            font-size: 17px;
            letter-spacing: 0.5px;
        }

        .letterhead p {
            margin: 0;
            font-size: 9.5px;
            color: #444;
        }

        /* ===== JUDUL DOKUMEN ===== */
        .doc-title {
            text-align: center;
            margin: 10px 0 16px 0;
        }

        .doc-title h2 {
            margin: 0;
            font-size: 15px;
            text-decoration: none;
            text-underline-offset: 3px;
        }

        .doc-title p {
            margin: 3px 0 0 0;
            font-size: 10.5px;
            color: #555;
        }

        /* ===== STATUS BADGE ===== */
        .status-badge {
            display: block;
            width: 220px;
            margin: 0 auto 16px auto;
            text-align: center;
            padding: 8px 0;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 1px;
            border: 2px solid;
        }

        .status-accepted {
            color: #0a7c2f;
            border-color: #0a7c2f;
            background-color: #eafaf0;
        }

        /* ===== BIODATA ===== */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .info-table td {
            padding: 4px 4px;
            vertical-align: top;
            font-size: 11.5px;
        }

        .info-table .label {
            width: 18%;
            color: #444;
        }

        .info-table .colon {
            width: 1%;
        }

        .info-table .value {
            font-weight: bold;
        }

        /* ===== SECTION TITLE ===== */
        .section-title {
            background-color: #555;
            color: #fff;
            padding: 5px 8px;
            font-size: 11.5px;
            font-weight: bold;
            margin: 16px 0 0 0;
            border-radius: 3px 3px 0 0;
        }

        /* ===== HASIL JURUSAN ===== */
        .result-box {
            border: 1px solid #000;
            border-top: none;
            padding: 10px 12px;
            margin-bottom: 4px;
            background-color: #fff;
        }

        .result-box table {
            width: 100%;
            border-collapse: collapse;
        }

        .result-box td {
            padding: 3px 4px;
            font-size: 11.5px;
            color: #000;
        }

        .result-main {
            font-size: 14px;
            font-weight: bold;
            color: #000;
        }

        /* ===== TABEL SKOR & PILIHAN ===== */
        .score-table,
        .choice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        .score-table th,
        .choice-table th {
            background-color: #d9d9d9;
            border: 1px solid #000;
            padding: 5px;
            font-size: 10.5px;
            text-align: center;
            color: #000;
        }

        .score-table td,
        .choice-table td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 11px;
            color: #000;
        }

        .score-table .label-col {
            text-align: left;
            font-weight: bold;
        }

        .score-table td,
        .score-table .final-row td {
            text-align: center;
        }

        .score-table .final-row td {
            background-color: #e6e6e6;
            font-weight: bold;
        }

        .choice-table .center {
            text-align: center;
        }

        .badge-tercapai {
            color: #0a7c2f;
            font-weight: bold;
            font-size: 10px;
        }

        /* ===== CATATAN & FOOTER ===== */
        .notes-box {
            border: 1px dashed #aaa;
            padding: 8px 8px;
            font-size: 10.5px;
            color: #444;
            margin-top: 10px;
            min-height: 20px;
        }

        .footer-note {
            margin-top: 18px;
            border-top: 1px dotted #999;
            padding-top: 6px;
            font-size: 9px;
            color: #777;
            width: 100%;
        }

        .qr-box {
            width: 70px;
            height: 70px;
            border: 1px solid #ccc;
            text-align: center;
            vertical-align: middle;
            font-size: 8px;
            color: #999;
        }
    </style>
</head>

<body>
    <div class="document">

        <div class="letterhead">
            <div class="logo">
                <img src="{{ public_path('imgs/smk.png') }}" alt="Logo Sekolah">
            </div>
            <h3>PEMERINTAH PROVINSI BENGKULU</h3>
            <h1>SMK NEGERI 1 REJANG LEBONG</h1>
            <p>Jln. Ahmad Marzuki 105, Air Rambai, Curup &middot; Telp. (0732) 21258</p>
            <p>Email: mail@smkn1rl.sch.id &middot; Website: www.smkn1rl.sch.id</p>
        </div>

        <div class="doc-title">
            <h2>BUKTI KELULUSAN SELEKSI</h2>
            <p>SISTEM PENERIMAAN MURID BARU (SPMB) TAHUN {{ date('Y') }}</p>
        </div>

        <div class="status-badge status-accepted">
            DINYATAKAN {{ strtoupper($selectionResult->status === 'accepted' ? 'lulus' : $selectionResult->status) }}
        </div>

        <table class="info-table">
            <tr>
                <td class="label">Nomor Pendaftaran</td>
                <td class="colon">:</td>
                <td class="value">{{ $registration->registration_number ?? '-' }}</td>
                <td class="label">Jalur Seleksi</td>
                <td class="colon">:</td>
                <td class="value">{{ $registration->admissionPath->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Nama Lengkap</td>
                <td class="colon">:</td>
                <td class="value">{{ strtoupper($personalData->full_name ?? '-') }}</td>
                <td class="label">Sekolah Asal</td>
                <td class="colon">:</td>
                <td class="value">{{ $personalData->previous_school ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">NISN</td>
                <td class="colon">:</td>
                <td class="value">{{ $personalData->nisn ?? '-' }}</td>
                <td class="label"></td>
                <td class="colon"></td>
                <td class="value"></td>
            </tr>
        </table>

        <div class="section-title">HASIL PENEMPATAN KOMPETENSI KEAHLIAN</div>
        <div class="result-box">
            <table>
                <tr>
                    <td width="60%">
                        Dinyatakan <strong>LULUS</strong> dan diterima pada Kompetensi Keahlian:<br>
                        <span class="result-main">{{ $selectionResult->acceptedConcentration->name ?? 'Menunggu Penetapan' }}</span>
                    </td>
                    <td width="40%" style="text-align:right;">
                        Diterima pada Pilihan ke-<strong>{{ $selectionResult->accepted_in_choice ?? '-' }}</strong><br>
                        Peringkat di Jurusan: <strong>{{ $selectionResult->rank_in_concentration ?? '-' }}</strong><br>
                    </td>
                </tr>
            </table>
        </div>

        <div class="section-title">RIWAYAT PILIHAN KOMPETENSI KEAHLIAN</div>
        <table class="choice-table">
            <tr>
                <th width="10%">Pilihan</th>
                <th width="60%">Kompetensi Keahlian</th>
                <th width="30%">Keterangan</th>
            </tr>
            <tr>
                <td class="center">1</td>
                <td>{{ $registration->choice1->name ?? '-' }}</td>
                <td class="center">
                    @if(($selectionResult->accepted_in_choice ?? null) == 1)
                    <span class="badge-tercapai">DITERIMA</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="center">2</td>
                <td>{{ $registration->choice2->name ?? '-' }}</td>
                <td class="center">
                    @if(($selectionResult->accepted_in_choice ?? null) == 2)
                    <span class="badge-tercapai">DITERIMA</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="center">3</td>
                <td>{{ $registration->choice3->name ?? '-' }}</td>
                <td class="center">
                    @if(($selectionResult->accepted_in_choice ?? null) == 3)
                    <span class="badge-tercapai">DITERIMA</span>
                    @endif
                </td>
            </tr>
        </table>

        <div class="section-title">RINCIAN PEROLEHAN NILAI</div>
        <table class="score-table">
            <tr>
                <th>Komponen Penilaian</th>
                <th width="15%">Bobot</th>
                <th width="20%">Nilai Murni</th>
                <th width="20%">Nilai Terbobot</th>
            </tr>
            <tr>
                <td class="label-col">Rata-rata Nilai Rapor</td>
                <td>40%</td>
                <td>{{ number_format($registration->report_average ?? 0, 2) }}</td>
                <td>{{ number_format($selectionResult->score_rapor ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td class="label-col">Tes Kemampuan Akademik (TKA)</td>
                <td>20%</td>
                <td>{{ number_format($registration->tka_average ?? 0, 2) }}</td>
                <td>{{ number_format($selectionResult->score_tka ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td class="label-col">Hasil Observasi Fisik &amp; Kesehatan</td>
                <td>30%</td>
                <td>{{ number_format($registration->observationData->total_score ?? 0, 2) }}</td>
                <td>{{ number_format($selectionResult->score_observasi ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td class="label-col">Nilai Prestasi Tambahan</td>
                <td>10%</td>
                <td>&mdash;</td>
                <td>{{ number_format($selectionResult->score_prestasi ?? 0, 2) }}</td>
            </tr>
            <tr class="final-row">
                <td class="label-col" colspan="3">NILAI AKHIR (FINAL SCORE)</td>
                <td>{{ number_format($selectionResult->final_score ?? 0, 2) }}</td>
            </tr>
        </table>

        <div class="section-title">CATATAN PANITIA SELEKSI</div>
        <div class="notes-box">
            {{ $selectionResult->selection_notes ?? 'Peserta dinyatakan lulus. Harap melakukan daftar ulang sebelum tanggal yang ditentukan.' }}
        </div>

        <p style="margin-top:10px; font-size:10.5px; color:#444;">
            Peserta yang dinyatakan <strong>LULUS</strong> wajib melakukan <strong>DAFTAR ULANG</strong>
            sesuai jadwal yang ditentukan panitia. Kelalaian melakukan daftar ulang dapat
            menyebabkan pengguguran status kelulusan.
        </p>

        <table class="footer-note">
            <tr>
                <td style="vertical-align:bottom; font-size:9px; color:#777; padding-left:0px;">
                    Dokumen ini diterbitkan secara elektronik oleh Sistem SPMB SMK Negeri 1 Rejang Lebong
                    dan sah tanpa memerlukan tanda tangan basah, kecuali ditentukan lain oleh panitia.
                    <br>Diterbitkan pada: {{ $tanggalCetak ?? \Carbon\Carbon::now()->translatedFormat('d F Y') }}.
                </td>
            </tr>
        </table>

    </div>
</body>

</html>
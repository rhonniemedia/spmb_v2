<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Bukti Daftar Ulang - SPMB {{ date('Y') }}</title>
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

            .status-badge {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .checklist-table th {
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
            border-bottom: 3px solid #555;
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
            width: 300px;
            margin: 0 auto 16px auto;
            text-align: center;
            padding: 8px 0;
            border-radius: 4px;
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 1px;
            border: 2px solid #0a7c2f;
            color: #0a7c2f;
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

        /* ===== SECTION TITLE - ABU-ABU GELAP ===== */
        .section-title {
            background-color: #555;
            color: #fff;
            padding: 5px 8px;
            font-size: 11.5px;
            font-weight: bold;
            margin: 16px 0 0 0;
            border-radius: 3px 3px 0 0;
        }

        /* ===== RESULT BOX ===== */
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

        /* ===== TABEL CEKLIS KELENGKAPAN DATA ===== */
        .checklist-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        .checklist-table th {
            background-color: #d9d9d9;
            border: 1px solid #000;
            padding: 5px;
            font-size: 10.5px;
            text-align: center;
            color: #000;
        }

        .checklist-table td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 11px;
            color: #000;
        }

        .checklist-table .no-col {
            width: 6%;
            text-align: center;
        }

        .checklist-table .center {
            width: 20%;
            text-align: center;
        }

        .checklist-table .status-verified {
            color: #0a7c2f;
            font-weight: bold;
        }

        .checklist-table .status-pending {
            color: #b91c1c;
            font-weight: bold;
        }

        /* ===== CATATAN ===== */
        .notes-box {
            border: 1px dashed #aaa;
            padding: 8px 10px;
            font-size: 10.5px;
            color: #444;
            margin-top: 10px;
            min-height: 32px;
        }

        /* ===== FOOTER ===== */
        .footer-note {
            margin-top: 18px;
            border-top: none;
        }

        .footer-note table {
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

        .instruction-box {
            background-color: #fef6e7;
            border: 1px solid #f0d080;
            padding: 10px 12px;
            margin-top: 10px;
            border-radius: 4px;
            font-size: 10.5px;
            color: #444;
        }

        .instruction-box strong {
            color: #92600a;
        }
    </style>
</head>

<body>
    @php
    // --- Kelengkapan data biodata (Group 1-3 di personal_data) ---
    $isDataPribadiLengkap = filled($personalData->full_name) && filled($personalData->nisn) && ($personalData->profile_status ?? null) === 'final';
    $isAlamatLengkap = filled($personalData->address ?? null);
    $isOrangTuaLengkap = ($personalData->parents->count() ?? 0) > 0;
    $isPendidikanLengkap = filled($personalData->previous_school ?? null);
    $isFotoLengkap = filled($personalData->photo ?? null);

    $isBerkasVerified = ($reRegistration->verification_status ?? null) === 'verified';
    @endphp

    <div class="document">

        <!-- KOP SURAT - Logo di atas rata tengah -->
        <div class="letterhead">
            <div class="logo">
                <img src="{{ public_path('imgs/smk.png') }}" alt="Logo Sekolah">
            </div>
            <h3>PEMERINTAH PROVINSI BENGKULU</h3>
            <h1>SMK NEGERI 1 REJANG LEBONG</h1>
            <p>Jln. Ahmad Marzuki 105, Air Rambai, Curup &middot; Telp. (0732) 21258</p>
            <p>Email: mail@smkn1rl.sch.id &middot; Website: www.smkn1rl.sch.id</p>
        </div>

        <!-- JUDUL -->
        <div class="doc-title">
            <h2>BUKTI DAFTAR ULANG</h2>
            <p>SISTEM PENERIMAAN MURID BARU (SPMB) TAHUN {{ date('Y') }}</p>
        </div>

        <!-- STATUS -->
        <div class="status-badge">PENDAFTARAN ULANG BERHASIL</div>

        <!-- BIODATA -->
        <table class="info-table">
            <tr>
                <td class="label">Nomor Pendaftaran</td>
                <td class="colon">:</td>
                <td class="value">{{ $registration->registration_number ?? '-' }}</td>
                <td class="label">Tanggal Daftar Ulang</td>
                <td class="colon">:</td>
                <td class="value">{{ $reRegistration->re_registered_at ? \Carbon\Carbon::parse($reRegistration->re_registered_at)->translatedFormat('d F Y') : '-' }}</td>
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
                <td class="label">Nomor Telepon</td>
                <td class="colon">:</td>
                <td class="value">{{ $personalData->phone_number ?? '-' }}</td>
            </tr>
        </table>

        <!-- KOMPETENSI KEAHLIAN DITERIMA -->
        <div class="section-title">KOMPETENSI KEAHLIAN DITERIMA</div>
        <div class="result-box">
            <table>
                <tr>
                    <td width="65%">
                        <span class="result-main">{{ $selectionResult->acceptedConcentration->name ?? 'Menunggu Penetapan' }}</span>
                        @if($selectionResult->acceptedConcentration->alias ?? null)
                        ({{ $selectionResult->acceptedConcentration->alias }})
                        @endif
                    </td>
                    <td width="35%" style="text-align:right;">
                        Jalur: <strong>{{ $registration->admissionPath->name ?? '-' }}</strong><br>
                        Tahun Ajaran: <strong>{{ $tahunAjaran ?? '-' }}</strong>
                    </td>
                </tr>
            </table>
        </div>

        <!-- KELENGKAPAN DATA -->
        <div class="section-title">KELENGKAPAN DATA</div>
        <table class="checklist-table">
            <tr>
                <th class="no-col">No</th>
                <th>Komponen Data</th>
                <th class="center">Status</th>
            </tr>
            <tr>
                <td class="no-col">1</td>
                <td>Data Pribadi</td>
                <td class="center">
                    @if($isDataPribadiLengkap)
                    <span class="status-verified">Lengkap</span>
                    @else
                    <span class="status-pending">Belum Lengkap</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="no-col">2</td>
                <td>Alamat</td>
                <td class="center">
                    @if($isAlamatLengkap)
                    <span class="status-verified">Lengkap</span>
                    @else
                    <span class="status-pending">Belum Lengkap</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="no-col">3</td>
                <td>Orang Tua</td>
                <td class="center">
                    @if($isOrangTuaLengkap)
                    <span class="status-verified">Lengkap</span>
                    @else
                    <span class="status-pending">Belum Lengkap</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="no-col">4</td>
                <td>Pendidikan</td>
                <td class="center">
                    @if($isPendidikanLengkap)
                    <span class="status-verified">Lengkap</span>
                    @else
                    <span class="status-pending">Belum Lengkap</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="no-col">5</td>
                <td>Pas Foto</td>
                <td class="center">
                    @if($isFotoLengkap)
                    <span class="status-verified">Terunggah</span>
                    @else
                    <span class="status-pending">Belum Diunggah</span>
                    @endif
                </td>
            </tr>
        </table>

        <!-- PENTING: INSTRUKSI VERIFIKASI -->
        <div class="section-title">INSTRUKSI VERIFIKASI FISIK</div>
        <div class="instruction-box">
            @php
            // Menentukan jadwal daftar ulang berdasarkan singkatan/alias jurusan
            $aliasJurusan = $selectionResult->acceptedConcentration->alias ?? '';
            $jadwalDaftarUlang = $jadwalVerifikasi ?? 'Sesuai pengumuman panitia'; // Fallback default

            $jadwalKamis = ['TEI', 'DPIB', 'TPTL', 'TKJ', 'TM', 'TL'];
            $jadwalJumat = ['TITL', 'TSM', 'TKR'];

            if (in_array(strtoupper($aliasJurusan), $jadwalKamis)) {
            $jadwalDaftarUlang = 'Hari KAMIS, 02 JULI 2026';
            } elseif (in_array(strtoupper($aliasJurusan), $jadwalJumat)) {
            $jadwalDaftarUlang = 'Hari JUMAT, 03 JULI 2026';
            }
            @endphp

            <strong>PERHATIAN:</strong> Bukti ini adalah <strong>konfirmasi pendaftaran ulang online</strong>.<br><br>
            <strong>Langkah selanjutnya:</strong>
            <ol style="margin: 6px 0 0 20px; padding-left: 0;">
                <li>Cetak bukti ini dan <strong>bawa ke sekolah</strong> pada saat penyerahan berkas fisik.</li>
                <li>Membawa <strong>berkas fotokopi</strong> sesuai dengan ketentuan persyaratan daftar ulang.</li>
                {{-- Bagian hari dan tanggal ditambahkan warna merah (#b91c1c) dan cetak tebal --}}
                <li>Verifikasi fisik dan penyerahan berkas dilaksanakan pada <strong style="color: #b91c1c; -webkit-print-color-adjust: exact; print-color-adjust: exact;">{{ $jadwalDaftarUlang }}</strong> di <strong>{{ $lokasiVerifikasi ?? 'Ruang Panitia SPMB' }}</strong>.</li>
                <li>Tanpa verifikasi fisik, pendaftaran ulang <strong>DIANGGAP BATAL</strong>.</li>
            </ol>
        </div>

        <!-- CATATAN -->
        <div class="section-title">CATATAN</div>
        <div class="notes-box">
            {{ $reRegistration->verification_notes ?? 'Peserta wajib membawa bukti ini dan fotokopi dokumen yang telah ditentukan sebagai syarat daftar ulang, dan pastikan seluruh data telah diisi dengan benar.' }}
        </div>

        <!-- QR & FOOTER -->
        <table class="footer-note">
            <tr>
                <!-- <td class="qr-box">QR Verifikasi</td> -->
                <td style="vertical-align:bottom; font-size:9px; color:#777; padding-left:0px;">
                    Dokumen ini adalah bukti pendaftaran ulang online yang diterbitkan secara elektronik oleh Sistem SPMB SMK Negeri 1 Rejang Lebong. <br>
                    Diterbitkan pada: {{ $tanggalCetak ?? \Carbon\Carbon::now()->translatedFormat('d F Y') }}.
                </td>
            </tr>
        </table>

    </div>
</body>

</html>
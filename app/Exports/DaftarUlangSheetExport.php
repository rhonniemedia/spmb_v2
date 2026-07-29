<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DaftarUlangSheetExport implements FromArray, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    protected $keahlian;
    protected $daftar;
    protected $tanggalHariIni;

    // Nomor baris (1-indexed) dilacak otomatis saat array() dibangun,
    // supaya styling di styles()/registerEvents() TIDAK pernah hardcode
    // dan selalu tepat sasaran, berapa pun jumlah baris kop suratnya.
    protected $rowTitle1 = 1;
    protected $rowTitle4 = 4;
    protected $rowInfo = 6;
    protected $rowHeader = 8;
    protected $rowDataStart = 9;
    protected $rowDataEnd = 9;
    protected $rowRekap = 11;
    protected $rowLegend = 13;

    protected $keteranganLabel = [
        'V' => 'Terverifikasi (Sudah Daftar Ulang)',
        'B' => 'Belum Daftar Ulang',
        'D' => 'Ditolak',
        'M' => 'Menunggu Verifikasi',
    ];

    public function __construct($keahlian, $daftar, $tanggalHariIni)
    {
        $this->keahlian = $keahlian;
        $this->daftar = $daftar;
        $this->tanggalHariIni = $tanggalHariIni;
    }

    /**
     * Nama tab sheet Excel, maksimal 31 karakter & tanpa karakter terlarang.
     */
    public function title(): string
    {
        $title = $this->keahlian->alias ?: $this->keahlian->name;
        $title = preg_replace('/[\[\]\*\/\\\?:]/', '-', $title);
        return substr($title, 0, 31) ?: 'Jurusan';
    }

    public function array(): array
    {
        $rows = [];

        // ── Kop laporan ──
        $rows[] = ['DAFTAR PESERTA DAFTAR ULANG'];
        $rows[] = ['SISTEM PENERIMAAN MURID BARU'];
        $rows[] = ['SMK NEGERI 1 REJANG LEBONG'];
        $rows[] = ['TAHUN ' . now()->year];
        $this->rowTitle1 = 1;
        $this->rowTitle4 = count($rows); // baris 4

        $rows[] = array_fill(0, 15, ''); // baris kosong pemisah (tetap diberi sel supaya tidak "hilang")

        $rows[] = [
            'Konsentrasi Keahlian: ' . $this->keahlian->name . ' (' . $this->keahlian->alias . ')',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '', // Tambahan elemen kosong agar terdorong ke kanan
            'Tanggal Cetak:',
            $this->tanggalHariIni,
            '' // Untuk mengisi kolom O (Keterangan Status)
        ];
        $this->rowInfo = count($rows);

        $rows[] = array_fill(0, 15, '');

        // ── Header kolom tabel ──
        $rows[] = [
            'NO',
            'NO REGISTRASI',
            'NISN',
            'NAMA LENGKAP',
            'JK',
            'ASAL SEKOLAH',
            'NAMA AYAH',
            'NO HP AYAH',
            'NAMA IBU',
            'NO HP IBU',
            'NAMA WALI',
            'NO HP WALI',
            'TANGGAL DAFTAR ULANG',
            'KET',
            'KETERANGAN STATUS',
        ];
        $this->rowHeader = count($rows);
        $this->rowDataStart = $this->rowHeader + 1;

        $no = 1;
        $laki = 0;
        $perempuan = 0;

        if ($this->daftar->isEmpty()) {
            $rows[] = ['Belum ada peserta yang dinyatakan lulus / diterima pada Konsentrasi Keahlian ini.'];
        } else {
            foreach ($this->daftar as $pendaftar) {
                $rows[] = [
                    $no,
                    $pendaftar->registration_number,
                    $pendaftar->nisn ?? '-',
                    strtoupper($pendaftar->student_name),
                    $pendaftar->gender,
                    strtoupper($pendaftar->asal_sekolah),

                    // Panggil data orang tua yang sudah di-mapping dari controller
                    strtoupper($pendaftar->nama_ayah),
                    $pendaftar->telepon_ayah,
                    strtoupper($pendaftar->nama_ibu),
                    $pendaftar->telepon_ibu,
                    strtoupper($pendaftar->nama_wali),
                    $pendaftar->telepon_wali,

                    $pendaftar->tanggal_daftar_ulang,
                    $pendaftar->keterangan,
                    $this->keteranganLabel[$pendaftar->keterangan] ?? $pendaftar->keterangan,
                ];

                if ($pendaftar->gender === 'L') {
                    $laki++;
                } elseif ($pendaftar->gender === 'P') {
                    $perempuan++;
                }

                $no++;
            }
        }

        $this->rowDataEnd = count($rows);

        $rows[] = array_fill(0, 15, '');

        $rows[] = [
            'Rekapitulasi',
            '',
            '',
            'Laki-laki: ' . $laki,
            '',
            'Perempuan: ' . $perempuan,
            'Jumlah Total: ' . ($laki + $perempuan),
        ];
        $this->rowRekap = count($rows);

        $rows[] = array_fill(0, 15, '');

        $rows[] = ['Keterangan: V = Terverifikasi, B = Belum Daftar Ulang, D = Ditolak, M = Menunggu Verifikasi'];
        $this->rowLegend = count($rows);

        return $rows;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,   // NO
            'B' => 20,  // NO REGISTRASI
            'C' => 16,  // NISN
            'D' => 32,  // NAMA LENGKAP
            'E' => 6,   // JK
            'F' => 34,  // ASAL SEKOLAH
            'G' => 25,  // NAMA AYAH
            'H' => 16,  // NO HP AYAH
            'I' => 25,  // NAMA IBU
            'J' => 16,  // NO HP IBU
            'K' => 25,  // NAMA WALI
            'L' => 16,  // NO HP WALI
            'M' => 20,  // TANGGAL DAFTAR ULANG
            'N' => 8,   // KET
            'O' => 28,  // KETERANGAN STATUS
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Ubah semua ujung merge dari I menjadi O (karena total ada 15 kolom, A sampai O)
        $sheet->mergeCells("A{$this->rowTitle1}:O{$this->rowTitle1}");
        $sheet->mergeCells('A2:O2');
        $sheet->mergeCells('A3:O3');
        $sheet->mergeCells("A{$this->rowTitle4}:O{$this->rowTitle4}");

        $sheet->getStyle("A{$this->rowTitle1}:A{$this->rowTitle4}")->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle("A{$this->rowTitle1}:O{$this->rowTitle4}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle("A{$this->rowInfo}")->getFont()->setBold(true);
        // Tanggal Cetak bergeser posisinya ke kolom O agar tetap di ujung kanan (opsional, sesuaikan dengan desain)
        // Atau biarkan di G jika ingin tetap di tengah
        $sheet->getStyle("M{$this->rowInfo}")->getFont()->setBold(true);

        // Header dari A sampai O
        $headerRange = "A{$this->rowHeader}:O{$this->rowHeader}";
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F0F0F0');
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Border untuk seluruh tabel (header sampai baris data terakhir, dari A sampai O)
                $sheet->getStyle("A{$this->rowHeader}:O{$this->rowDataEnd}")
                    ->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // Default rata tengah untuk semua sel di tabel
                $sheet->getStyle("A{$this->rowDataStart}:O{$this->rowDataEnd}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                // Rata kiri untuk teks panjang agar rapi
                $sheet->getStyle("D{$this->rowDataStart}:D{$this->rowDataEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT); // Nama Lengkap
                $sheet->getStyle("F{$this->rowDataStart}:F{$this->rowDataEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT); // Asal Sekolah
                $sheet->getStyle("G{$this->rowDataStart}:G{$this->rowDataEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT); // Nama Ayah
                $sheet->getStyle("I{$this->rowDataStart}:I{$this->rowDataEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT); // Nama Ibu
                $sheet->getStyle("K{$this->rowDataStart}:K{$this->rowDataEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT); // Nama Wali

                // PENTING: Keterangan status sekarang bergeser ke kolom O
                $sheet->getStyle("O{$this->rowDataStart}:O{$this->rowDataEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                // Baris rekap dibold (A sampai O)
                $sheet->getStyle("A{$this->rowRekap}:O{$this->rowRekap}")->getFont()->setBold(true);

                // Baris legenda dibuat miring & lebih kecil
                $sheet->getStyle("A{$this->rowLegend}")->getFont()->setItalic(true)->setSize(10);

                // Baris tinggi header sedikit lebih besar biar teks tidak mepet
                $sheet->getRowDimension($this->rowHeader)->setRowHeight(20);
            },
        ];
    }
}

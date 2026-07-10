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

        $rows[] = array_fill(0, 9, ''); // baris kosong pemisah (tetap diberi sel supaya tidak "hilang")

        $rows[] = [
            'Konsentrasi Keahlian: ' . $this->keahlian->name . ' (' . $this->keahlian->alias . ')',
            '',
            '',
            '',
            '',
            '',
            'Tanggal Cetak:',
            $this->tanggalHariIni,
        ];
        $this->rowInfo = count($rows);

        $rows[] = array_fill(0, 9, '');

        // ── Header kolom tabel ──
        $rows[] = [
            'NO',
            'NO REGISTRASI',
            'NISN',
            'NAMA LENGKAP',
            'JK',
            'ASAL SEKOLAH',
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

        $rows[] = array_fill(0, 9, '');

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

        $rows[] = array_fill(0, 9, '');

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
            'G' => 20,  // TANGGAL DAFTAR ULANG
            'H' => 8,   // KET
            'I' => 28,  // KETERANGAN STATUS
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells("A{$this->rowTitle1}:I{$this->rowTitle1}");
        $sheet->mergeCells('A2:I2');
        $sheet->mergeCells('A3:I3');
        $sheet->mergeCells("A{$this->rowTitle4}:I{$this->rowTitle4}");

        $sheet->getStyle("A{$this->rowTitle1}:A{$this->rowTitle4}")->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle("A{$this->rowTitle1}:I{$this->rowTitle4}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle("A{$this->rowInfo}")->getFont()->setBold(true);
        $sheet->getStyle("G{$this->rowInfo}")->getFont()->setBold(true);

        $headerRange = "A{$this->rowHeader}:I{$this->rowHeader}";
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

                // Border untuk seluruh tabel (header sampai baris data terakhir)
                $sheet->getStyle("A{$this->rowHeader}:I{$this->rowDataEnd}")
                    ->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                $sheet->getStyle("A{$this->rowDataStart}:I{$this->rowDataEnd}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                // Rata kiri untuk kolom Nama Lengkap, Asal Sekolah, Keterangan Status
                $sheet->getStyle("D{$this->rowDataStart}:D{$this->rowDataEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle("F{$this->rowDataStart}:F{$this->rowDataEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle("I{$this->rowDataStart}:I{$this->rowDataEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                // Baris rekap dibold
                $sheet->getStyle("A{$this->rowRekap}:I{$this->rowRekap}")->getFont()->setBold(true);

                // Baris legenda dibuat miring & lebih kecil
                $sheet->getStyle("A{$this->rowLegend}")->getFont()->setItalic(true)->setSize(10);

                // Baris tinggi header sedikit lebih besar biar teks tidak mepet
                $sheet->getRowDimension($this->rowHeader)->setRowHeight(20);
            },
        ];
    }
}

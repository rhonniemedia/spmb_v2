<?php

namespace App\Exports;

use App\Exports\DaftarUlangSheetExport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DaftarUlangExport implements WithMultipleSheets
{
    protected $dataKeahlian;
    protected $pendaftarPerKeahlian;
    protected $tanggalHariIni;

    public function __construct($dataKeahlian, $pendaftarPerKeahlian, $tanggalHariIni)
    {
        $this->dataKeahlian = $dataKeahlian;
        $this->pendaftarPerKeahlian = $pendaftarPerKeahlian;
        $this->tanggalHariIni = $tanggalHariIni;
    }

    /**
     * Satu sheet dibuat untuk setiap Konsentrasi Keahlian (jurusan) aktif,
     * mengikuti urutan yang sama seperti versi PDF.
     */
    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->dataKeahlian as $keahlian) {
            $daftar = $this->pendaftarPerKeahlian[$keahlian->id] ?? collect();

            $sheets[] = new DaftarUlangSheetExport($keahlian, $daftar, $this->tanggalHariIni);
        }

        return $sheets;
    }
}

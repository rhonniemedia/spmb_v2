<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Concentration;
use App\Models\RegistrationData;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Helper untuk mendapatkan tanggal cetak berbahasa Indonesia
     */
    private function getTanggalCetak()
    {
        Carbon::setLocale('id');
        return Carbon::now()->translatedFormat('d F Y');
    }

    /**
     * Helper untuk memetakan data relasi agar sesuai dengan format di Blade PDF Anda
     */
    private function mapPendaftarData($pendaftar)
    {
        return $pendaftar->map(function ($p) {
            // Mapping Data Pribadi
            $p->student_name = $p->personalData->full_name ?? '-';
            $p->gender = $p->personalData->gender ?? '-';
            $p->asal_sekolah = $p->personalData->previous_school ?? '-';

            // Mapping Pilihan Jurusan
            $p->keahlianSatu = $p->choice1;

            // Mapping Tanggal
            $p->tanggal_daftar = $p->created_at ? $p->created_at->format('d/m/Y') : '-';
            $p->tanggal_observasi = $p->observationData ? $p->observationData->created_at->format('d/m/Y') : null;

            // Mapping Status Berkas dari tabel registration_documents
            if ($p->relationLoaded('documents')) {
                // Pastikan slug ini sesuai dengan data di tabel requirements Anda
                $p->berkas_akta   = $p->documents->contains(fn($doc) => $doc->requirement->slug === 'akta-kelahiran-asli' && $doc->verification_status !== 'rejected') ? 'ada' : 'tidak';
                $p->berkas_ijazah = $p->documents->contains(fn($doc) => $doc->requirement->slug === 'ijazah-smp-sederajat' && $doc->verification_status !== 'rejected') ? 'ada' : 'tidak';
                $p->berkas_skl    = $p->documents->contains(fn($doc) => $doc->requirement->slug === 'surat-keterangan-lulus-skl' && $doc->verification_status !== 'rejected') ? 'ada' : 'tidak';
                $p->berkas_rapor  = $p->documents->contains(fn($doc) => $doc->requirement->slug === 'rapor-semester-1-5' && $doc->verification_status !== 'rejected') ? 'ada' : 'tidak';
                $p->berkas_skr    = $p->documents->contains(fn($doc) => $doc->requirement->slug === 'surat-keterangan-rata-rata-nilai-rapor' && $doc->verification_status !== 'rejected') ? 'ada' : 'tidak';
            } else {
                // Fallback jika bukan cetak tanda terima
                $p->berkas_akta   = 'ada';
                $p->berkas_ijazah = 'ada';
                $p->berkas_skl    = 'ada';
                $p->berkas_rapor  = 'ada';
                $p->berkas_skr    = 'ada';
            }

            return $p;
        });
    }

    /**
     * 1. Cetak Rekapitulasi (Mendukung filter Harian / Semua)
     */
    public function rekapitulasi(Request $request)
    {
        $opsi = $request->get('opsi', 'semua');
        $tanggal = $request->get('tanggal', date('Y-m-d'));

        // HANYA AMBIL JURUSAN YANG AKTIF
        $dataKeahlian = Concentration::where('status', 'active')->orderBy('name')->get();

        // Query pendaftar beserta relasi datanya
        $query = RegistrationData::with(['personalData', 'choice1']);

        if ($opsi === 'harian') {
            $query->whereDate('created_at', $tanggal);
            $tanggalHariIni = Carbon::parse($tanggal)->translatedFormat('d F Y');
        } else {
            $tanggalHariIni = $this->getTanggalCetak();
        }

        $pendaftar = $query->get();

        // Menyusun array rekapitulasi sesuai dengan ID Keahlian
        $rekap = [];
        foreach ($dataKeahlian as $keahlian) {
            // Filter pendaftar berdasarkan choice_1 (Pilihan Jurusan 1)
            $peminatKeahlian = $pendaftar->where('choice_1', $keahlian->id);

            $laki = $peminatKeahlian->filter(function ($p) {
                return ($p->personalData->gender ?? '') === 'L';
            })->count();

            $perempuan = $peminatKeahlian->filter(function ($p) {
                return ($p->personalData->gender ?? '') === 'P';
            })->count();

            $rekap[$keahlian->id] = [
                'laki' => $laki,
                'perempuan' => $perempuan,
                'total' => $laki + $perempuan,
            ];
        }

        $pdf = Pdf::loadView('pages.admin.laporan.rekapitulasi', compact(
            'dataKeahlian',
            'rekap',
            'tanggalHariIni'
        ))->setPaper('A4', 'portrait');

        return $pdf->stream('Rekapitulasi_Pendaftar_' . date('Ymd') . '.pdf');
    }

    /**
     * 2. Cetak Daftar Peminat (Global)
     */
    public function peminat(Request $request)
    {
        $rawPendaftar = RegistrationData::with(['personalData', 'choice1', 'observationData'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Hitung total sebelum di-map
        $jumlahLakiLaki = $rawPendaftar->filter(fn($p) => ($p->personalData->gender ?? '') === 'L')->count();
        $jumlahPerempuan = $rawPendaftar->filter(fn($p) => ($p->personalData->gender ?? '') === 'P')->count();
        $jumlahTotal = $rawPendaftar->count();

        // Map data agar sesuai dengan Blade PDF
        $dataPendaftar = $this->mapPendaftarData($rawPendaftar);
        $tanggalHariIni = $this->getTanggalCetak();

        $pdf = Pdf::loadView('pages.admin.laporan.daftar-peminat', compact(
            'dataPendaftar',
            'jumlahLakiLaki',
            'jumlahPerempuan',
            'jumlahTotal',
            'tanggalHariIni'
        ))->setPaper('A4', 'landscape');

        return $pdf->stream('Daftar_Peminat_' . date('Ymd') . '.pdf');
    }

    /**
     * 3. Cetak Daftar Peminat Per Jurusan
     */
    public function peminatJurusan(Request $request)
    {
        // HANYA AMBIL JURUSAN YANG AKTIF
        $dataKeahlian = Concentration::where('status', 'active')->orderBy('name')->get();

        $rawPendaftar = RegistrationData::with(['personalData', 'choice1', 'observationData'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Map data agar sesuai dengan Blade PDF
        $mappedPendaftar = $this->mapPendaftarData($rawPendaftar);

        // Mengelompokkan pendaftar berdasarkan choice_1 (Jurusan Pilihan 1)
        $pendaftarPerKeahlian = $mappedPendaftar->groupBy('choice_1');
        $tanggalHariIni = $this->getTanggalCetak();

        $pdf = Pdf::loadView('pages.admin.laporan.daftar-peminat-jurusan', compact(
            'dataKeahlian',
            'pendaftarPerKeahlian',
            'tanggalHariIni'
        ))->setPaper('A4', 'landscape');

        return $pdf->stream('Peminat_Per_Jurusan_' . date('Ymd') . '.pdf');
    }

    /**
     * 4. Cetak Tanda Terima Berkas
     */
    public function tandaTerima(Request $request)
    {
        $rawPendaftar = RegistrationData::with(['personalData', 'choice1', 'documents.requirement'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Map data agar sesuai dengan Blade PDF
        $dataPendaftar = $this->mapPendaftarData($rawPendaftar);
        $tanggalHariIni = $this->getTanggalCetak();

        $pdf = Pdf::loadView('pages.admin.laporan.tanda-terima', compact(
            'dataPendaftar',
            'tanggalHariIni'
        ))->setPaper('A4', 'landscape');

        return $pdf->stream('Tanda_Terima_Berkas_' . date('Ymd') . '.pdf');
    }
}

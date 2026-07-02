<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Concentration;
use App\Models\RegistrationData;
use App\Models\SelectionResult;
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

    /**
     * Helper khusus untuk mapping data Selection Result (Penjenjangan)
     */
    private function mapSelectionData($selectionResults)
    {
        return $selectionResults->map(function ($result) {
            $reg = $result->registration;
            $personal = $reg->personalData ?? null;
            $obs = $reg->observationData ?? null;

            // Mapping Data Pribadi
            $result->registration_number = $reg->registration_number ?? '-';
            $result->student_name = $personal->full_name ?? '-';
            $result->gender = $personal->gender ?? '-';
            $result->asal_sekolah = $personal->previous_school ?? '-';

            // Mapping Tanggal
            $result->tanggal_daftar = $reg->created_at ? $reg->created_at->format('d/m/Y') : '-';
            $result->tanggal_observasi = $obs ? $obs->created_at->format('d/m/Y') : '-';

            // Nilai Akhir & Observasi
            $result->nilai_akhir = number_format($result->final_score ?? 0, 2);
            $result->hasilObservasi = (object) [
                'total_nilai' => $obs->total_score ?? '-',
                'buta_warna' => $obs->color_blind_check ?? 'no',
                'tato' => $obs->tattoo ?? 'no',
                'bekas_tato' => $obs->tattoo_scar ?? 'no',
                'tindik' => $obs->piercing ?? 'no'
            ];

            // Bypass class error warna merah di blade
            $result->input_rapor = 'ya';

            // ── Keterangan Pilihan, Jalur & Urutan Sorting ──
            $choiceNumber = $result->accepted_in_choice ?? '-';

            // --- FIX TAMPILAN JALUR ---
            // Deteksi apakah anak diterima di jalur aslinya, atau dilimpahkan ke Reguler.
            $originalPathId = $reg->admissionPath->id ?? null;
            $acceptedPathId = $result->path_id ?? null;

            // Jika path_id berbeda (berarti dia dilimpahkan), atau diterima di Pilihan 2/3,
            // maka otomatis dia berstatus Reguler.
            if ($acceptedPathId !== $originalPathId || in_array($choiceNumber, [2, 3])) {
                $pathName = 'reguler';
            } else {
                $pathName = strtolower($reg->admissionPath->name ?? 'reguler');
            }

            $pathInitial = 'R';
            $pathOrder = 4;

            if (str_contains($pathName, 'prestasi')) {
                $pathInitial = 'P';
                $pathOrder = 1;
            } elseif (str_contains($pathName, 'afirmasi')) {
                $pathInitial = 'A';
                $pathOrder = 2;
            } elseif (str_contains($pathName, 'zonasi')) {
                $pathInitial = 'Z';
                $pathOrder = 3;
            }

            $result->pilihan_jalur = ($choiceNumber !== '-') ? $choiceNumber . $pathInitial : '-';
            $result->path_order = $pathOrder;

            // ── LOGIKA BARU: Keterangan Observasi / Penolakan ──
            $keterangan = [];
            if (($obs->color_blind_check ?? 'no') === 'yes') $keterangan[] = 'Buta Warna';
            if (($obs->tattoo ?? 'no') === 'yes' || ($obs->tattoo_scar ?? 'no') === 'yes') $keterangan[] = 'Tato';
            if (($obs->piercing ?? 'no') === 'yes') $keterangan[] = 'Tindik';

            $result->keterangan = count($keterangan) > 0 ? implode(', ', $keterangan) : 'Tidak Terjenjang';

            return $result;
        });
    }

    /**
     * 5. Cetak Hasil Penjenjangan (Diterima & Ditolak)
     * Query 'belumTerjenjang' dihapus dari sini karena sudah dipindah.
     */
    public function penjenjangan(Request $request)
    {
        $latestBatch = SelectionResult::max('batch') ?? 0;

        if ($latestBatch == 0) {
            return back()->with('error', 'Belum ada data penjenjangan untuk dicetak.');
        }

        $dataKeahlian = Concentration::where('status', 'active')->orderBy('name')->get();

        // --- A. PESERTA DITERIMA ---
        $acceptedResults = SelectionResult::with([
            'registration.personalData',
            'registration.observationData',
            'registration.choice1',
            'registration.admissionPath'
        ])
            ->where('batch', $latestBatch)
            ->where('status', 'accepted')
            ->orderBy('rank_in_concentration')
            ->get();

        $pendaftarPerKeahlian = $acceptedResults->groupBy('accepted_concentration_id')->map(function ($group) {
            $mappedData = $this->mapSelectionData($group);
            return $mappedData->sortBy([
                ['path_order', 'asc'],
                ['rank_in_concentration', 'asc']
            ])->values();
        });

        // --- B. PESERTA DITOLAK ---
        $rejectedResults = SelectionResult::with([
            'registration.personalData',
            'registration.observationData',
            'registration.choice1',
            'registration.admissionPath'
        ])
            ->where('batch', $latestBatch)
            ->where('status', 'rejected')
            ->orderByDesc('final_score')
            ->get();

        $tidakDijenjang = $this->mapSelectionData($rejectedResults);
        $tanggalHariIni = $this->getTanggalCetak();

        // Tidak perlu passing 'belumTerjenjang' lagi ke sini
        $pdf = Pdf::loadView('pages.admin.laporan.daftar-jenjang-jurusan', compact(
            'dataKeahlian',
            'pendaftarPerKeahlian',
            'tidakDijenjang',
            'tanggalHariIni'
        ))->setPaper('A4', 'landscape');

        return $pdf->stream('Hasil_Penjenjangan_Batch_' . $latestBatch . '.pdf');
    }

    /**
     * 7. Cetak Daftar Peserta Pending (Belum Terjenjang)
     * Method baru yang dipanggil oleh route 'penjenjangan-pending'
     */
    public function penjenjanganDipending(Request $request)
    {
        $latestBatch = SelectionResult::max('batch') ?? 0;

        // Ambil semua ID pendaftar yang sudah diproses di batch ini
        $processedIds = SelectionResult::where('batch', $latestBatch)->pluck('registration_id');

        // Filter pendaftar yang ID-nya tidak ada di dalam daftar $processedIds
        // Pastikan status berkasnya sudah verified agar masuk kriteria
        $unprocessed = RegistrationData::with(['personalData', 'choice1', 'observationData'])
            ->where('verification_status', 'verified')
            ->whereNotIn('id', $processedIds)
            ->orderByDesc('created_at')
            ->get();

        // Menggunakan helper mapPendaftarData yang sudah Anda buat
        $belumTerjenjang = $this->mapPendaftarData($unprocessed);
        $tanggalHariIni = $this->getTanggalCetak();

        // Pastikan path ke file blade sudah benar sesuai struktur folder Anda
        $pdf = Pdf::loadView('pages.admin.laporan.daftar-jenjang-pending', compact(
            'belumTerjenjang',
            'tanggalHariIni'
        ))->setPaper('A4', 'landscape');

        return $pdf->stream('Daftar_Belum_Terjenjang.pdf');
    }

    /**
     * 6. Cetak Daftar Peserta Ditolak (Tidak Dijenjangkan)
     */
    public function penjenjanganDitolak(Request $request)
    {
        $latestBatch = SelectionResult::max('batch') ?? 0;

        if ($latestBatch == 0) {
            return back()->with('error', 'Belum ada data penjenjangan untuk dicetak.');
        }

        $rejectedResults = SelectionResult::with([
            'registration.personalData',
            'registration.observationData',
            'registration.choice1',
            'registration.admissionPath' // Penting untuk mapping 1R, 2A, dst
        ])
            ->where('batch', $latestBatch)
            ->where('status', 'rejected')
            ->orderByDesc('final_score')
            ->get();

        $tidakDijenjang = $this->mapSelectionData($rejectedResults);
        $tanggalHariIni = $this->getTanggalCetak();

        $pdf = Pdf::loadView('pages.admin.laporan.daftar-ditolak', compact(
            'tidakDijenjang',
            'tanggalHariIni',
            'latestBatch'
        ))->setPaper('A4', 'landscape');

        return $pdf->stream('Daftar_Ditolak_Batch_' . $latestBatch . '.pdf');
    }

    /**
     * 8. Cetak Hasil Daftar Ulang Per Jurusan 
     * (Berdasarkan Data Penjenjangan yang Diterima)
     */
    public function daftarUlang(Request $request)
    {
        $latestBatch = SelectionResult::max('batch') ?? 0;

        if ($latestBatch == 0) {
            return back()->with('error', 'Belum ada data penjenjangan untuk dicetak.');
        }

        // Ambil jurusan yang aktif
        $dataKeahlian = Concentration::where('status', 'active')->orderBy('name')->get();

        // 1. Ambil SEMUA data penjenjangan yang DITERIMA
        $acceptedResults = SelectionResult::with(['registration.personalData'])
            ->where('batch', $latestBatch)
            ->where('status', 'accepted')
            ->get();

        // 2. Ambil data daftar ulang untuk peserta-peserta yang diterima tersebut
        $registrationIds = $acceptedResults->pluck('registration_id');

        // PERBAIKAN: Menggunakan 'registration_data_id' sesuai skema database Anda
        $reRegistrations = \App\Models\ReRegistrationData::whereIn('registration_data_id', $registrationIds)
            ->get()
            ->keyBy('registration_data_id');

        // 3. Map data untuk digabungkan
        $mappedData = $acceptedResults->map(function ($result) use ($reRegistrations) {
            $reg = $result->registration;
            $personal = $reg->personalData ?? null;

            // Cari data daftar ulang berdasarkan ID pendaftaran
            $reReg = $reRegistrations->get($reg->id);

            // Default state (Belum daftar ulang / belum verifikasi)
            $tanggalDaftarUlang = '-';
            $keterangan = 'B';

            if ($reReg) {
                if ($reReg->verification_status === 'verified') {
                    // Jika sudah diverifikasi, ambil tanggal verifikasinya
                    $tanggalDaftarUlang = $reReg->verified_at ? \Carbon\Carbon::parse($reReg->verified_at)->format('d/m/Y') : '-';
                    $keterangan = 'V';
                } elseif ($reReg->verification_status === 'rejected') {
                    $keterangan = 'D';
                } elseif (in_array($reReg->verification_status, ['pending', 'processing'])) {
                    $keterangan = 'M';
                }
            }

            return (object) [
                'concentration_id' => $result->accepted_concentration_id ?? null,
                'registration_number' => $reg->registration_number ?? '-',
                'student_name' => $personal->full_name ?? '-',
                'gender' => $personal->gender ?? '-',
                'asal_sekolah' => $personal->previous_school ?? '-',
                'tanggal_daftar_ulang' => $tanggalDaftarUlang,
                'keterangan' => $keterangan
            ];
        });

        // 4. Mengelompokkan berdasarkan ID Konsentrasi (Jurusan Diterima)
        $pendaftarPerKeahlian = $mappedData->groupBy('concentration_id')->map(function ($group) {
            // Urutkan berdasarkan nama siswa alfabetis
            return $group->sortBy('student_name')->values();
        });

        $tanggalHariIni = $this->getTanggalCetak();

        $pdf = Pdf::loadView('pages.admin.laporan.daftar-ulang-jurusan', compact(
            'dataKeahlian',
            'pendaftarPerKeahlian',
            'tanggalHariIni'
        ))->setPaper('A4', 'landscape');

        return $pdf->stream('Laporan_Daftar_Ulang_' . date('Ymd') . '.pdf');
    }
}

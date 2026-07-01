<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ParentData;
use App\Models\PersonalData;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    /**
     * [USER] Cetak biodata milik siswa yang sedang login sendiri.
     * Dipanggil dari dashboard siswa (kartu "Formulir Data Pribadi").
     */
    public function cetakBiodataPribadi()
    {
        $personalData = $this->getPersonalDataAuthUser();

        $father   = $personalData->parents->firstWhere('relationship', ParentData::RELATIONSHIP_FATHER);
        $mother   = $personalData->parents->firstWhere('relationship', ParentData::RELATIONSHIP_MOTHER);
        $guardian = $personalData->parents->firstWhere('relationship', ParentData::RELATIONSHIP_GUARDIAN);

        $registration = $personalData->registrationData->first();

        Carbon::setLocale('id');

        $pdf = Pdf::loadView('pages.user.laporan.biodata-murid-baru', [
            'personalData' => $personalData,
            'father'       => $father,
            'mother'       => $mother,
            'guardian'     => $guardian,
            'registration' => $registration,
            'ttl'          => $this->formatTtl($personalData),
            'tahunAjaran'  => now()->year . '/' . (now()->year + 1),
            'tanggalCetak' => $this->tanggalCetak(),
        ])->setPaper('a4', 'portrait');

        $fileName = 'Formulir-Data-Pribadi-' . Str::slug($personalData->full_name) . '.pdf';

        return $pdf->stream($fileName);
    }

    /**
     * [ADMIN] Cetak biodata siswa tertentu berdasarkan ID — dipakai panitia/verifikator.
     */
    public function biodataMuridBaru(string $id)
    {
        $personalData = PersonalData::with([
            'parents',
            'registrationData' => fn($q) => $q->latest('created_at'),
        ])->findOrFail($id);

        $father   = $personalData->parents->firstWhere('relationship', ParentData::RELATIONSHIP_FATHER);
        $mother   = $personalData->parents->firstWhere('relationship', ParentData::RELATIONSHIP_MOTHER);
        $guardian = $personalData->parents->firstWhere('relationship', ParentData::RELATIONSHIP_GUARDIAN);

        $registration = $personalData->registrationData->first();

        Carbon::setLocale('id');

        $pdf = Pdf::loadView('pages.user.laporan.biodata-murid-baru', [
            'personalData' => $personalData,
            'father'       => $father,
            'mother'       => $mother,
            'guardian'     => $guardian,
            'registration' => $registration,
            'ttl'          => $this->formatTtl($personalData),
            'tahunAjaran'  => now()->year . '/' . (now()->year + 1),
            'tanggalCetak' => $this->tanggalCetak(),
        ])->setPaper('a4', 'portrait');

        $fileName = 'Biodata-' . Str::slug($personalData->full_name) . '.pdf';

        return $pdf->stream($fileName); // ganti ->download($fileName) kalau mau langsung diunduh
    }

    /**
     * [USER] Cetak surat pernyataan peserta didik baru milik siswa yang login sendiri.
     */
    public function cetakSuratPernyataan()
    {
        $personalData = $this->getPersonalDataAuthUser();

        // Prioritas penanda tangan orang tua/wali: Ayah > Ibu > Wali (Syarat: masih hidup / alive)
        $waliPembuat = $personalData->parents->first(fn($p) => $p->isFather() && $p->isAlive())
            ?? $personalData->parents->first(fn($p) => $p->isMother() && $p->isAlive())
            ?? $personalData->parents->first(fn($p) => $p->isGuardian() && $p->isAlive());

        Carbon::setLocale('id');

        $alamatLengkapSiswa = collect([
            $personalData->address,
            $personalData->village,
            $personalData->district,
            $personalData->regency,
        ])->filter()->implode(', ');

        $pdf = Pdf::loadView('pages.user.laporan.surat-pernyataan', [
            'personalData'        => $personalData,
            'waliPembuat'         => $waliPembuat,
            'ttl'                 => $this->formatTtl($personalData),
            'alamatLengkapSiswa'  => $alamatLengkapSiswa ?: '-',
            'namaSekolah'         => config('app.school_name', 'SMK Negeri 1 Rejang Lebong'),
            'tanggalCetak'        => $this->tanggalCetak(),
        ])->setPaper('a4', 'portrait');

        $fileName = 'Surat-Pernyataan-' . Str::slug($personalData->full_name) . '.pdf';

        return $pdf->stream($fileName);
    }

    /**
     * Ambil PersonalData milik user yang sedang login, lengkap dengan relasi
     * yang biasa dipakai laporan (parents & registrationData terbaru).
     */
    private function getPersonalDataAuthUser(): PersonalData
    {
        return PersonalData::with([
            'parents',
            'registrationData' => fn($q) => $q->latest('created_at'),
        ])->where('user_id', Auth::id())->firstOrFail();
    }

    /**
     * Format "Tempat, Tanggal Lahir" dari pob/dob terenkripsi.
     */
    private function formatTtl(PersonalData $personalData): string
    {
        return ($personalData->pob ?: '-') . ', ' .
            ($personalData->dob ? Carbon::parse($personalData->dob)->translatedFormat('d F Y') : '-');
    }

    /**
     * Teks "Kota, tanggal cetak" untuk bagian tanda tangan dokumen.
     */
    private function tanggalCetak(): string
    {
        $kota = config('app.school_city', 'Rejang Lebong'); // sesuaikan nama kota

        return $kota . ', ' . Carbon::now()->translatedFormat('d F Y');
    }

    /**
     * [USER] Cetak bukti kelulusan seleksi milik siswa yang login sendiri.
     *
     * PENTING: status LULUS/TIDAK, jurusan diterima, peringkat, dan skor-skor
     * berbobot TIDAK disimpan di tabel `registration_data`. Semua itu ada di
     * tabel `selection_results` (relasi registration_id -> registration_data.id),
     * dengan histori per `batch`. Kita ambil batch terakhir.
     */
    public function cetakBuktiKelulusan()
    {
        $personalData = $this->getPersonalDataAuthUser();

        /** @var \App\Models\RegistrationData|null $registration */
        $registration = $personalData->registrationData->first();

        if (!$registration) {
            abort(403, 'Data pendaftaran tidak ditemukan.');
        }

        // Lengkapi relasi yang dibutuhkan template: jalur & 3 pilihan jurusan.
        $registration->loadMissing(['admissionPath', 'choice1', 'choice2', 'choice3']);

        // Ambil hasil seleksi TERBARU (batch tertinggi) untuk pendaftaran ini,
        // via relasi resmi latestSelectionResult() — SUMBER TUNGGAL yang sama
        // dipakai di halaman cek kelulusan & dashboard.
        $registration->loadMissing('latestSelectionResult.acceptedConcentration');
        $selectionResult = $registration->latestSelectionResult;

        // Hanya siswa yang statusnya "accepted" pada hasil seleksi terbaru
        // yang boleh mencetak bukti kelulusan.
        if (!$selectionResult || $selectionResult->status !== 'accepted') {
            abort(403, 'Anda tidak memiliki hak akses atau belum dinyatakan lulus.');
        }

        Carbon::setLocale('id');

        $pdf = Pdf::loadView('pages.user.laporan.bukti-lulus-seleksi', [
            'personalData'    => $personalData,
            'registration'    => $registration,
            'selectionResult' => $selectionResult,
            'tanggalCetak'    => $this->tanggalCetak(),
        ])->setPaper('a4', 'portrait');

        $fileName = 'Bukti-Kelulusan-' . Str::slug($personalData->full_name) . '.pdf';

        return $pdf->stream($fileName);
    }

    /**
     * [USER] Cetak bukti daftar ulang milik siswa yang login sendiri.
     *
     * Bukti ini baru boleh dicetak setelah siswa benar-benar menyelesaikan
     * proses daftar ulang, yaitu ketika `re_registration_data.re_registered_at`
     * sudah terisi. Selama masih null, akses ditolak (403) — dashboard cukup
     * mengunci link-nya berdasarkan kondisi yang sama.
     */
    public function cetakBuktiDaftarUlang()
    {
        $personalData = $this->getPersonalDataAuthUser();

        /** @var \App\Models\RegistrationData|null $registration */
        $registration = $personalData->registrationData->first();

        if (!$registration) {
            abort(403, 'Data pendaftaran tidak ditemukan.');
        }

        $registration->loadMissing('admissionPath');

        $reRegistration = $registration->reRegistrationData;

        if (!$reRegistration || !$reRegistration->re_registered_at) {
            abort(403, 'Anda belum menyelesaikan proses daftar ulang.');
        }

        // Ambil kompetensi keahlian yang diterima — via relasi resmi
        // latestSelectionResult() (SUMBER TUNGGAL, sama dengan halaman cek
        // kelulusan & dashboard). Daftar ulang hanya bisa diselesaikan oleh
        // siswa yang diterima, jadi batch terbaru di sini seharusnya selalu
        // berstatus 'accepted' — tapi tetap kita jaga (guard) untuk berjaga-jaga.
        $registration->loadMissing('latestSelectionResult.acceptedConcentration');
        $latest = $registration->latestSelectionResult;
        $selectionResult = ($latest && $latest->status === 'accepted') ? $latest : null;

        Carbon::setLocale('id');

        $pdf = Pdf::loadView('pages.user.laporan.bukti-daftar-ulang', [
            'personalData'      => $personalData,
            'registration'      => $registration,
            'reRegistration'    => $reRegistration,
            'selectionResult'   => $selectionResult,
            'tahunAjaran'       => now()->year . '/' . (now()->year + 1),
            'jadwalVerifikasi'  => config('app.re_registration_verification_schedule', 'sesuai pengumuman panitia'),
            'lokasiVerifikasi'  => config('app.re_registration_verification_location', 'Ruang Panitia SPMB'),
            'tanggalCetak'      => $this->tanggalCetak(),
        ])->setPaper('a4', 'portrait');

        $fileName = 'Bukti-Daftar-Ulang-' . Str::slug($personalData->full_name) . '.pdf';

        return $pdf->stream($fileName);
    }
}

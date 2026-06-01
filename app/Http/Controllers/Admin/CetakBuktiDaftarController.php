<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RegistrationData;
use App\Models\Requirement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class CetakBuktiDaftarController extends Controller
{
    public function cetakBukti($id)
    {
        // 1. Ambil data peserta beserta relasinya
        $registration = RegistrationData::with([
            'personalData',
            'admissionPath',
            'choice1',
            'choice2',
            'choice3',
            'documents'
        ])->findOrFail($id);

        // 2. Cek kelengkapan berkas secara dinamis dari master data
        $allRequirements = Requirement::where('category', 'dokumen')->orderBy('id')->get();
        // Ambil array ID requirement (dokumen) yang dimiliki/disubmit oleh peserta ini
        $userDocs = $registration->documents->pluck('requirement_id')->toArray();

        $kelengkapanBerkas = [];
        foreach ($allRequirements as $req) {
            $kelengkapanBerkas[] = [
                'nama'   => $req->name,
                // Berstatus true jika ID dokumen master ada di dalam tabel dokumen pendaftar
                'status' => in_array($req->id, $userDocs)
            ];
        }

        // 3. Pecah format Nomor Pendaftaran (Dinamis mengambil base prefix)
        $regNumber = $registration->registration_number;

        // noBesar = Ambil 4 digit dari paling belakang
        $noBesar = substr($regNumber, -4);

        // noKecil = Ambil seluruh teks dari awal, KECUALI 5 karakter terakhir (yaitu "-0000")
        $noKecil = substr($regNumber, 0, -5);

        // 4. Susun data akhir
        $data = [
            'sekolah' => [
                'nama'   => 'SMK Negeri 1 Rejang Lebong',
                'alamat' => 'Jln. Ahmad Marzuki 105, Rejang Lebong, Bengkulu',
                'email'  => 'mail@smkn1rl.sch.id'
            ],
            'peserta' => [
                'no_daftar_besar' => $noBesar,
                'no_daftar_kecil' => $noKecil,
                'nama_lengkap'    => $registration->personalData->full_name ?? '-',
                'no_telp'         => $registration->personalData->phone_number ?? '-',
                'sekolah_asal'    => $registration->personalData->previous_school ?? '-',
                'tahun_spmb'      => date('Y', strtotime($registration->created_at)),

                // Menghilangkan nilai null jika peserta tidak memilih pilihan 2 dan 3
                'pilihan_jurusan' => array_values(array_filter([
                    $registration->choice1->name ?? null,
                    $registration->choice2->name ?? null,
                    $registration->choice3->name ?? null,
                ])),

                'kelengkapan_berkas' => $kelengkapanBerkas,
                'nama_verifikator'   => Auth::user()->name ?? 'Panitia SPMB',
                'lokasi_ttd'         => 'Rejang Lebong',
                'tanggal_ttd'        => now()->translatedFormat('d F Y')
            ]
        ];

        // 5. Render PDF
        $pdf = Pdf::loadView('pages.admin.peserta.cetak-bukti-spmb', $data);
        $pdf->setPaper('A4', 'landscape');

        // Aktifkan JS untuk fitur Auto-Print
        $pdf->setOption(['isJavascriptEnabled' => true]);
        $pdf->render();

        // Menyuntikkan instruksi Print
        $pdf->getDomPDF()->getCanvas()->javascript("print(true);");

        return $pdf->stream(
            'Bukti_SPMB_' . $registration->registration_number . '.pdf',
            ['Attachment' => false]
        );
    }
}

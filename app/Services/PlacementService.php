<?php

namespace App\Services;

use App\Models\AdmissionPath;
use App\Models\Concentration;
use App\Models\RegistrationData;
use App\Models\SelectionResult;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlacementService
{
    // ── BOBOT SKOR ───────────────────────────────────────
    const WEIGHT_RAPOR     = 0.50;
    const WEIGHT_TKA       = 0.20;
    const WEIGHT_OBSERVASI = 0.20;
    const WEIGHT_PRESTASI  = 0.10;

    // ── TIPE JALUR (sesuaikan dengan kolom `name` di admission_paths) ──
    const JALUR_ZONASI   = 'zonasi';
    const JALUR_AFIRMASI = 'afirmasi';
    const JALUR_PRESTASI = 'prestasi';
    const JALUR_REGULER  = 'reguler';

    /**
     * Jalankan penjenjangan penuh.
     * Dipanggil dari Controller (manual) atau Scheduler (otomatis).
     *
     * @param  User|null  $processedBy  User yang menjalankan (null jika scheduler)
     * @return array  ['batch' => int, 'summary' => array]
     */
    public function run(?User $processedBy = null): array
    {
        return DB::transaction(function () use ($processedBy) {

            $batch       = (SelectionResult::max('batch') ?? 0) + 1;
            $processedAt = now();
            $processedById = $processedBy?->id;

            // 1. Ambil semua konsentrasi aktif beserta kuota per jalur
            $concentrations = Concentration::where('status', 'active')->get();

            // 2. Hitung kuota tiap jurusan per jalur secara FLEKSIBEL (menggunakan str_contains)
            $dbPaths = AdmissionPath::where('is_active', true)->get();
            $admissionPaths = collect();

            foreach ($dbPaths as $p) {
                $name = strtolower($p->name);
                if (str_contains($name, 'zonasi')) $admissionPaths->put(self::JALUR_ZONASI, $p);
                elseif (str_contains($name, 'afirmasi')) $admissionPaths->put(self::JALUR_AFIRMASI, $p);
                elseif (str_contains($name, 'prestasi')) $admissionPaths->put(self::JALUR_PRESTASI, $p);
                else $admissionPaths->put(self::JALUR_REGULER, $p); // Fallback ke reguler
            }

            // Jika tabel admission_paths di DB masih kosong, buat dummy "Reguler" 100% agar sistem tidak error
            if ($admissionPaths->isEmpty()) {
                $dummy = new AdmissionPath();
                $dummy->name = 'Reguler';
                $dummy->quota_percentage = 100;
                $admissionPaths->put(self::JALUR_REGULER, $dummy);
            }

            $quotaMap = $this->buildQuotaMap($concentrations, $admissionPaths);

            // 3. Inisialisasi slot yang sudah terisi dengan 4 key jalur wajib
            $filledSlots = [];
            foreach ($concentrations as $c) {
                $filledSlots[$c->id] = [
                    self::JALUR_ZONASI   => 0,
                    self::JALUR_AFIRMASI => 0,
                    self::JALUR_PRESTASI => 0,
                    self::JALUR_REGULER  => 0,
                ];
            }

            // 4. Ambil semua peserta verified
            $registrations = RegistrationData::with([
                'admissionPath',
                'observationData',
                'achievements',
            ])
                ->where('verification_status', 'verified')
                ->whereHas('observationData', function ($query) {
                    // Abaikan peserta yang belum punya data observasi atau masih 'pending'
                    $query->whereIn('observation_status', ['passed', 'failed']);
                })
                ->get();

            $results  = collect();
            $summary  = [];

            // ── PISAHKAN: gagal observasi langsung tolak ─────────────
            // Hanya peserta dengan observation_status = 'failed' yang diblokir.
            // Status null/pending tetap masuk penjenjangan.
            $observasiGagal = $registrations->filter(
                fn($r) => $r->observationData?->observation_status === 'failed'
            );
            $registrations = $registrations->reject(
                fn($r) => $r->observationData?->observation_status === 'failed'
            );

            foreach ($observasiGagal as $reg) {
                $results->push([
                    'id'                        => \Illuminate\Support\Str::uuid()->toString(),
                    'registration_id'           => $reg->id,
                    'path_id'                   => $reg->admissionPath?->id,
                    'batch'                     => $batch,
                    'status'                    => 'rejected',
                    'accepted_concentration_id' => null,
                    'accepted_in_choice'        => null,
                    'score_rapor'               => 0,
                    'score_tka'                 => 0,
                    'score_observasi'           => 0,
                    'score_prestasi'            => 0,
                    'final_score'               => 0,
                    'rank_in_path'              => null,
                    'rank_in_concentration'     => null,
                    'processed_by'              => $processedById,
                    'processed_at'              => $processedAt,
                    'selection_notes'           => 'Tidak lulus observasi',
                    'created_at'                => now(),
                    'updated_at'                => now(),
                ]);
            }

            Log::info("PlacementService: {$observasiGagal->count()} peserta ditolak (gagal observasi), {$registrations->count()} dilanjut penjenjangan.");

            // ── URUTAN PEMROSESAN JALUR ─────────────────────────────
            $jalurOrder = [
                self::JALUR_ZONASI,
                self::JALUR_AFIRMASI,
                self::JALUR_PRESTASI,
                self::JALUR_REGULER,
            ];

            // 5. Pisahkan peserta per jalur dengan pencocokan FLEKSIBEL
            $pesertaPerJalur = [];
            foreach ($jalurOrder as $jalurKey) {
                $pesertaPerJalur[$jalurKey] = $registrations->filter(function ($r) use ($jalurKey) {
                    // Jika data peserta tidak punya jalur di DB, anggap "Reguler"
                    $pathName = strtolower($r->admissionPath->name ?? 'reguler');

                    if ($jalurKey === self::JALUR_ZONASI) return str_contains($pathName, 'zonasi');
                    if ($jalurKey === self::JALUR_AFIRMASI) return str_contains($pathName, 'afirmasi');
                    if ($jalurKey === self::JALUR_PRESTASI) return str_contains($pathName, 'prestasi');
                    if ($jalurKey === self::JALUR_REGULER) return str_contains($pathName, 'reguler');

                    return false;
                });
            }

            // ── STEP 1: ZONASI (skor berbobot tanpa prestasi, ranking by final_score) ──
            $summary[self::JALUR_ZONASI] = $this->processJalurTanpaPrestasi(
                peserta: $pesertaPerJalur[self::JALUR_ZONASI],
                jalurKey: self::JALUR_ZONASI,
                admissionPath: $admissionPaths->get(self::JALUR_ZONASI),
                quotaMap: $quotaMap,
                filledSlots: $filledSlots,
                batch: $batch,
                processedById: $processedById,
                processedAt: $processedAt,
                results: $results,
            );

            // ── STEP 2: AFIRMASI (skor berbobot tanpa prestasi, ranking by final_score) ──
            $summary[self::JALUR_AFIRMASI] = $this->processJalurTanpaPrestasi(
                peserta: $pesertaPerJalur[self::JALUR_AFIRMASI],
                jalurKey: self::JALUR_AFIRMASI,
                admissionPath: $admissionPaths->get(self::JALUR_AFIRMASI),
                quotaMap: $quotaMap,
                filledSlots: $filledSlots,
                batch: $batch,
                processedById: $processedById,
                processedAt: $processedAt,
                results: $results,
            );

            // ── STEP 3: PRESTASI (skor berbobot penuh termasuk prestasi) ────────
            $summary[self::JALUR_PRESTASI] = $this->processJalurPrestasi(
                peserta: $pesertaPerJalur[self::JALUR_PRESTASI],
                admissionPath: $admissionPaths->get(self::JALUR_PRESTASI),
                quotaMap: $quotaMap,
                filledSlots: $filledSlots,
                batch: $batch,
                processedById: $processedById,
                processedAt: $processedAt,
                results: $results,
            );

            // ── PELIMPAHAN FISIK KE JALUR REGULER ──────────────────
            // Hanya rejected dari jalur non-reguler (bukan gagal observasi)
            // yang boleh dilimpahkan ke antrean reguler.
            $idGagalObservasi = $observasiGagal->pluck('id')->flip();
            $rejectedItems = $results->where('status', 'rejected')
                ->reject(fn($item) => $idGagalObservasi->has($item['registration_id']));

            foreach ($rejectedItems as $key => $item) {
                // Cari data pendaftaran aslinya
                $reg = $registrations->firstWhere('id', $item['registration_id']);

                // Masukkan ke antrean reguler jika belum ada (tanpa mengubah data DB)
                if ($reg && !$pesertaPerJalur[self::JALUR_REGULER]->contains('id', $reg->id)) {
                    $pesertaPerJalur[self::JALUR_REGULER]->push($reg);
                }

                // Hapus status 'rejected' lama dari memori agar digantikan hasil Reguler
                $results->forget($key);
            }

            // ── STEP 4: REGULER (ranking skor gabungan berbobot) ────
            // Sisa kuota dari jalur sebelumnya otomatis masuk ke reguler
            $this->redistributeSisaKuota($quotaMap, $filledSlots, $admissionPaths);

            $summary[self::JALUR_REGULER] = $this->processJalurReguler(
                peserta: $pesertaPerJalur[self::JALUR_REGULER],
                admissionPath: $admissionPaths->get(self::JALUR_REGULER),
                quotaMap: $quotaMap,
                filledSlots: $filledSlots,
                batch: $batch,
                processedById: $processedById,
                processedAt: $processedAt,
                results: $results,
            );

            // ── INSERT SEMUA HASIL ───────────────────────────────────
            if ($results->isNotEmpty()) {
                SelectionResult::insert($results->values()->toArray());
            }

            Log::info("PlacementService: Batch #{$batch} selesai.", ['summary' => $summary]);

            return ['batch' => $batch, 'summary' => $summary];
        });
    }

    // ────────────────────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ────────────────────────────────────────────────────────────────────────────

    /**
     * Bangun peta kuota:
     * $quotaMap[concentration_id][jalur_key] = jumlah_kursi
     */
    private function buildQuotaMap(Collection $concentrations, Collection $admissionPaths): array
    {
        $map = [];
        foreach ($concentrations as $c) {
            $map[$c->id] = [];
            $totalAssigned = 0;

            // Hitung kuota dasar
            foreach ($admissionPaths as $key => $path) {
                $kuota = (int) floor($c->quota * $path->quota_percentage / 100);
                $map[$c->id][$key] = $kuota;
                $totalAssigned += $kuota;
            }

            // Tangkap kursi yang hilang karena pembulatan desimal (floor),
            // lalu tambahkan langsung ke Jalur Reguler
            $sisaPembulatan = $c->quota - $totalAssigned;
            if ($sisaPembulatan > 0 && isset($map[$c->id][self::JALUR_REGULER])) {
                $map[$c->id][self::JALUR_REGULER] += $sisaPembulatan;
            }
        }
        return $map;
    }

    /**
     * Sisa kuota jalur non-reguler yang tidak terpakai
     * ditambahkan ke slot reguler.
     */
    private function redistributeSisaKuota(array &$quotaMap, array $filledSlots, Collection $admissionPaths): void
    {
        $nonReguler = [self::JALUR_ZONASI, self::JALUR_AFIRMASI, self::JALUR_PRESTASI];

        foreach ($quotaMap as $concId => $jalurQuotas) {
            $sisa = 0;
            foreach ($nonReguler as $jalur) {
                $kuota  = $jalurQuotas[$jalur] ?? 0;
                $terisi = $filledSlots[$concId][$jalur] ?? 0;
                $sisa  += max(0, $kuota - $terisi);
            }
            $quotaMap[$concId][self::JALUR_REGULER] = ($jalurQuotas[self::JALUR_REGULER] ?? 0) + $sisa;
        }
    }

    /**
     * Proses jalur TANPA PRESTASI (Zonasi & Afirmasi).
     * Skor berbobot: rapor (40%) + tka (20%) + observasi (30%).
     * Komponen prestasi dipaksa 0 karena tidak relevan di jalur ini.
     * Ranking berdasarkan final_score DESC.
     */
    private function processJalurTanpaPrestasi(
        Collection   $peserta,
        string       $jalurKey,
        ?AdmissionPath $admissionPath,
        array        $quotaMap,
        array        &$filledSlots,
        int          $batch,
        ?string      $processedById,
        \Carbon\Carbon $processedAt,
        Collection   &$results,
    ): array {
        if (!$admissionPath || $peserta->isEmpty()) {
            return ['total' => 0, 'accepted' => 0, 'rejected' => 0];
        }

        // Hitung skor berbobot tanpa komponen prestasi
        $scored = $peserta->map(function ($reg) {
            $rapor     = (float) ($reg->report_average ?? 0);
            $tka       = (float) ($reg->tka_average ?? 0);
            $observasi = (float) ($reg->observationData?->physical_score ?? 0)
                + (float) ($reg->observationData?->special_trait_score ?? 0);
            $observasi = min($observasi / 2, 100);

            // Prestasi dipaksa 0 — tidak relevan di jalur Zonasi & Afirmasi
            $prestasi  = 0;

            $scoreRapor     = $rapor     * self::WEIGHT_RAPOR;
            $scoreTka       = $tka       * self::WEIGHT_TKA;
            $scoreObservasi = $observasi * self::WEIGHT_OBSERVASI;
            $scorePrestasi  = $prestasi  * self::WEIGHT_PRESTASI; // selalu 0
            $finalScore     = $scoreRapor + $scoreTka + $scoreObservasi + $scorePrestasi;

            return [
                'reg'             => $reg,
                'score_rapor'     => round($scoreRapor, 2),
                'score_tka'       => round($scoreTka, 2),
                'score_observasi' => round($scoreObservasi, 2),
                'score_prestasi'  => round($scorePrestasi, 2),
                'final_score'     => round($finalScore, 2),
            ];
        })->sortByDesc('final_score')->values();

        $rank = 1;
        $scoredData = $scored->map(function ($item) use (&$rank) {
            $item['rank_in_path'] = $rank++;
            return $item;
        });

        return $this->executeAllocationRounds($scoredData, $jalurKey, $quotaMap, $filledSlots, $batch, $processedById, $processedAt, $results, $admissionPath->id);
    }

    /**
     * Proses jalur PRESTASI.
     * Skor berbobot PENUH: rapor (40%) + tka (20%) + observasi (30%) + prestasi (10%).
     * Komponen prestasi (achievement_score) ikut dihitung karena siswa memiliki bukti prestasi.
     * Jika peserta ini nantinya dilimpahkan ke Reguler, prestasi akan dipaksa 0 di sana.
     */
    private function processJalurPrestasi(
        Collection   $peserta,
        ?AdmissionPath $admissionPath,
        array        $quotaMap,
        array        &$filledSlots,
        int          $batch,
        ?string      $processedById,
        \Carbon\Carbon $processedAt,
        Collection   &$results,
    ): array {
        if (!$admissionPath || $peserta->isEmpty()) {
            return ['total' => 0, 'accepted' => 0, 'rejected' => 0];
        }

        // Hitung skor berbobot penuh termasuk komponen prestasi
        $scored = $peserta->map(function ($reg) {
            $rapor     = (float) ($reg->report_average ?? 0);
            $tka       = (float) ($reg->tka_average ?? 0);
            $observasi = (float) ($reg->observationData?->physical_score ?? 0)
                + (float) ($reg->observationData?->special_trait_score ?? 0);
            $observasi = min($observasi / 2, 100);
            $prestasi  = (float) ($reg->observationData?->achievement_score ?? 0);

            $scoreRapor     = $rapor     * self::WEIGHT_RAPOR;
            $scoreTka       = $tka       * self::WEIGHT_TKA;
            $scoreObservasi = $observasi * self::WEIGHT_OBSERVASI;
            $scorePrestasi  = $prestasi  * self::WEIGHT_PRESTASI;
            $finalScore     = $scoreRapor + $scoreTka + $scoreObservasi + $scorePrestasi;

            return [
                'reg'             => $reg,
                'score_rapor'     => round($scoreRapor, 2),
                'score_tka'       => round($scoreTka, 2),
                'score_observasi' => round($scoreObservasi, 2),
                'score_prestasi'  => round($scorePrestasi, 2),
                'final_score'     => round($finalScore, 2),
            ];
        })->sortByDesc('final_score')->values();

        $rank = 1;
        $scoredData = $scored->map(function ($item) use (&$rank) {
            $item['rank_in_path'] = $rank++;
            return $item;
        });

        return $this->executeAllocationRounds($scoredData, self::JALUR_PRESTASI, $quotaMap, $filledSlots, $batch, $processedById, $processedAt, $results, $admissionPath->id);
    }

    /**
     * Proses jalur REGULER.
     * Skor berbobot: rapor (40%) + tka (20%) + observasi (30%).
     * Komponen prestasi dipaksa 0 — jalur ini adalah jalur umum tanpa keistimewaan prestasi.
     * Peserta dari jalur lain yang gagal (Zonasi, Afirmasi, Prestasi) juga dilimpahkan ke sini
     * dan dinilai ulang dengan formula yang sama (prestasi tetap 0).
     */
    private function processJalurReguler(
        Collection   $peserta,
        ?AdmissionPath $admissionPath,
        array        $quotaMap,
        array        &$filledSlots,
        int          $batch,
        ?string      $processedById,
        \Carbon\Carbon $processedAt,
        Collection   &$results,
    ): array {
        if (!$admissionPath || $peserta->isEmpty()) {
            return ['total' => 0, 'accepted' => 0, 'rejected' => 0];
        }

        // Hitung skor berbobot tiap peserta
        $scored = $peserta->map(function ($reg) {
            $rapor     = (float) ($reg->report_average ?? 0);
            $tka       = (float) ($reg->tka_average ?? 0);
            $observasi = (float) ($reg->observationData?->physical_score ?? 0)
                + (float) ($reg->observationData?->special_trait_score ?? 0);
            $observasi = min($observasi / 2, 100);

            // Prestasi dipaksa 0 — tidak relevan di jalur Reguler
            $prestasi  = 0;

            $scoreRapor     = $rapor     * self::WEIGHT_RAPOR;
            $scoreTka       = $tka       * self::WEIGHT_TKA;
            $scoreObservasi = $observasi * self::WEIGHT_OBSERVASI;
            $scorePrestasi  = $prestasi  * self::WEIGHT_PRESTASI; // selalu 0
            $finalScore     = $scoreRapor + $scoreTka + $scoreObservasi + $scorePrestasi;

            return [
                'reg'            => $reg,
                'score_rapor'    => round($scoreRapor, 2),
                'score_tka'      => round($scoreTka, 2),
                'score_observasi' => round($scoreObservasi, 2),
                'score_prestasi' => round($scorePrestasi, 2),
                'final_score'    => round($finalScore, 2),
            ];
        })->sortByDesc('final_score')->values();

        $rank = 1;
        $scoredData = $scored->map(function ($item) use (&$rank) {
            $item['rank_in_path'] = $rank++;
            return $item;
        });

        // Lempar ke sistem putaran (Rounds) agar memprioritaskan Pilihan 1, lalu 2, dst.
        return $this->executeAllocationRounds($scoredData, self::JALUR_REGULER, $quotaMap, $filledSlots, $batch, $processedById, $processedAt, $results, $admissionPath->id);
    }

    /**
     * Mengeksekusi penempatan peserta dalam 3 putaran (Pilihan 1 dulu, lalu 2, lalu 3).
     */
    private function executeAllocationRounds(
        Collection $pesertaDatas,
        string $jalurKey,
        array $quotaMap,
        array &$filledSlots,
        int $batch,
        ?string $processedById,
        \Carbon\Carbon $processedAt,
        Collection &$results,
        ?string $pathId = null
    ): array {
        $rankMap = [];
        $accepted = 0;
        $rejected = 0;

        // Buat salinan antrean peserta
        $unplaced = collect($pesertaDatas);

        // Putaran 1, 2, dan 3
        for ($choiceLevel = 1; $choiceLevel <= 3; $choiceLevel++) {
            foreach ($unplaced as $key => $item) {
                $reg = $item['reg'];
                $concId = match ($choiceLevel) {
                    1 => $reg->choice_1,
                    2 => $reg->choice_2,
                    3 => $reg->choice_3,
                    default => null,
                };

                if (!$concId) continue;

                $kuota  = $quotaMap[$concId][$jalurKey] ?? 0;
                $terisi = $filledSlots[$concId][$jalurKey] ?? 0;

                if ($terisi < $kuota) {
                    // Diterima di putaran ini
                    $filledSlots[$concId][$jalurKey]++;
                    $rankMap[$concId] = ($rankMap[$concId] ?? 0) + 1;

                    $result = $this->formatResult(
                        $item,
                        $jalurKey,
                        $batch,
                        'accepted',
                        $concId,
                        $choiceLevel,
                        $rankMap[$concId],
                        $item['rank_in_path'] ?? null,
                        $processedById,
                        $processedAt,
                        $pathId
                    );
                    $results->push($result);

                    // Keluarkan dari antrean karena sudah dapat kursi
                    $unplaced->forget($key);
                    $accepted++;
                }
            }
        }

        // Sisa peserta yang tidak mendapat kuota di pilihan manapun
        foreach ($unplaced as $item) {
            $result = $this->formatResult(
                $item,
                $jalurKey,
                $batch,
                'rejected',
                null,
                null,
                null,
                $item['rank_in_path'] ?? null,
                $processedById,
                $processedAt,
                $pathId
            );
            $results->push($result);
            $rejected++;
        }

        return ['total' => count($pesertaDatas), 'accepted' => $accepted, 'rejected' => $rejected];
    }

    /**
     * Membentuk struktur array data untuk disimpan ke SelectionResult.
     */
    private function formatResult(
        array $item,
        string $jalurKey,
        int $batch,
        string $status,
        ?string $acceptedConcId,
        ?int $acceptedInChoice,
        ?int $rankInConc,
        ?int $rankInPath,
        ?string $processedById,
        \Carbon\Carbon $processedAt,
        ?string $pathId = null
    ): array {
        return [
            'id'                        => \Illuminate\Support\Str::uuid()->toString(),
            'registration_id'           => $item['reg']->id,
            'path_id'                   => $pathId,
            'batch'                     => $batch,
            'status'                    => $status,
            'accepted_concentration_id' => $acceptedConcId,
            'accepted_in_choice'        => $acceptedInChoice,
            'score_rapor'               => $item['score_rapor'],
            'score_tka'                 => $item['score_tka'],
            'score_observasi'           => $item['score_observasi'],
            'score_prestasi'            => $item['score_prestasi'],
            'final_score'               => $item['final_score'],
            'rank_in_path'              => $rankInPath,
            'rank_in_concentration'     => $rankInConc,
            'processed_by'              => $processedById,
            'processed_at'              => $processedAt,
            'selection_notes'           => null,
            'created_at'                => now(),
            'updated_at'                => now(),
        ];
    }
}

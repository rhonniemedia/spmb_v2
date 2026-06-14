<?php

namespace App\Console\Commands;

use App\Services\PlacementService;
use Illuminate\Console\Command;

class RunPlacementCommand extends Command
{
    protected $signature   = 'placement:run';
    protected $description = 'Jalankan algoritma penjenjangan peserta PPDB secara otomatis';

    public function __construct(protected PlacementService $service)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Memulai penjenjangan...');

        try {
            $output = $this->service->run(processedBy: null); // null = dijalankan oleh sistem

            $this->info("✓ Batch #{$output['batch']} selesai.");
            $this->table(
                ['Jalur', 'Total', 'Diterima', 'Ditolak'],
                collect($output['summary'])->map(fn($s, $jalur) => [
                    ucfirst($jalur),
                    $s['total'],
                    $s['accepted'],
                    $s['rejected'],
                ])->values()->toArray()
            );

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Penjenjangan gagal: ' . $e->getMessage());
            report($e);
            return self::FAILURE;
        }
    }
}

<?php

namespace App\Providers;

use App\Models\SpmbStep;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Daftarkan View Composer khusus untuk file komponen topbar user
        View::composer('layouts.partials.user-topbar', function ($view) {
            // Ambil data step dengan slug 'daftar-ulang' (atau sesuai data di seeder Anda)
            $daftarUlangStep = SpmbStep::where('slug', 'daftar-ulang')->first();

            $isDaftarUlangActive = false;

            if ($daftarUlangStep) {
                $now = Carbon::now();
                $start = $daftarUlangStep->start_date;
                $end = $daftarUlangStep->end_date;

                // Logika: Menu aktif jika waktu sekarang berada di antara start_date dan end_date
                // Jika tanggal kosong (null), kita asumsikan menu tidak aktif/belum diatur
                if ($start && $end) {
                    $isDaftarUlangActive = $now->between($start, $end);
                }
            }

            // Lempar variabel $isDaftarUlangActive ke dalam view
            $view->with('isDaftarUlangActive', $isDaftarUlangActive);
        });
    }
}

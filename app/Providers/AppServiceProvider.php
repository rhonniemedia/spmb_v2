<?php

namespace App\Providers;

use App\Http\View\Composers\MasterComposer;
use App\Notifications\DataReminderNotification;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
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
        // Opsi A: Jika ingin data ini tersedia di SEMUA halaman/view aplikasi
        View::composer('*', MasterComposer::class);

        // ─── TAMBAHKAN LISTENER AKTIVASI EMAIL DI SINI ───
        Event::listen(
            Verified::class,
            function ($event) {
                // $event->user berisi objek siswa yang baru saja sukses klik link verifikasi email
                $event->user->notify(new DataReminderNotification('welcome'));
            }
        );
    }
}

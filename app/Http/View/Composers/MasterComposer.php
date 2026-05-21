<?php

namespace App\Http\View\Composers;

use App\Models\Concentration;
use App\Models\School;
use App\Models\SpmbStep;
use Carbon\Carbon;
use Illuminate\View\View;

class MasterComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        // 1. Mengambil langkah SPMB diurutkan berdasarkan step_order
        $spmbSteps = SpmbStep::orderBy('step_order', 'asc')->get();

        // 2. Mengambil konsentrasi/jurusan yang aktif
        $activeConcentrations = Concentration::where('status', 'active')
            ->orderBy('name', 'asc')
            ->get();

        // 3. Mengambil data sekolah utama (asumsi data sekolah di aplikasi ini hanya 1 baris/settingan)
        $schoolInfo = School::first();

        $now = Carbon::now();
        $isDaftarUlangActive = SpmbStep::where('slug', 'daftar-ulang')
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->exists();

        // Lempar variabel ke view
        $view->with([
            'g_spmbSteps'         => $spmbSteps,
            'g_concentrations'    => $activeConcentrations,
            'g_schoolInfo'        => $schoolInfo,
            'g_isDaftarUlangActive' => $isDaftarUlangActive,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Concentration;
use App\Models\Faq;
use App\Models\SpmbStep;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    /**
     * Menampilkan halaman utama (landing page) beserta data jurusan.
     */
    public function index()
    {
        // 1. Mengambil semua data konsentrasi keahlian
        $concentrations = Concentration::where('status', 'active')
            ->orderBy('code', 'asc')
            ->get();

        // 2. Mengambil FAQ
        $faqs = Faq::where('is_published', true)
            ->whereHas('category', function ($query) {
                $query->where('slug', 'pendaftaran');
            })
            ->latest()
            ->get();

        // 3. Mengambil semua tahapan SPMB
        $spmbSteps = SpmbStep::orderBy('step_order', 'asc')->get();

        // 4. PENGECEKAN JADWAL: Cek apakah step 'pengumuman' atau 'daftar-ulang' sedang aktif
        $isPengumumanActive = SpmbStep::whereIn('slug', ['pengumuman-hasil', 'daftar-ulang-dan-penyerahan-berkas'])
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->exists();

        // 5. Kirimkan semua variabel ke view landing-page
        return view('pages.home.landing-page', compact('concentrations', 'faqs', 'spmbSteps', 'isPengumumanActive'));
    }
}

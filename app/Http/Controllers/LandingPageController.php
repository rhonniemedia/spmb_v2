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
        // 1. Mengambil semua data konsentrasi keahlian yang aktif, diurutkan berdasarkan kode
        $concentrations = Concentration::where('status', 'active')
            ->orderBy('code', 'asc')
            ->get();

        // 2. Mengambil FAQ khusus kategori Pendaftaran yang di-publish
        $faqs = Faq::where('is_published', true)
            ->whereHas('category', function ($query) {
                $query->where('slug', 'pendaftaran');
            })
            ->latest()
            ->get();

        // 3. Mengambil semua tahapan SPMB untuk jadwal kegiatan, diurutkan berdasarkan urutan langkah
        $spmbSteps = SpmbStep::orderBy('step_order', 'asc')->get();

        // 4. Kirimkan semua variabel ke view landing-page
        return view('pages.home.landing-page', compact('concentrations', 'faqs', 'spmbSteps'));
    }
}

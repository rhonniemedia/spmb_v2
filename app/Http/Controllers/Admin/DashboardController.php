<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Concentration;
use App\Models\PersonalData;
use App\Models\RegistrationData;
use App\Models\RegistrationDocument;
use Illuminate\Http\Request;
use App\Models\ObservationData;
use App\Models\SpmbStep;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // ── Halaman Utama ────────────────────────────────────────────────────────

    public function index()
    {
        // --- STAT CARDS ---
        $totalApplicants = RegistrationData::count();

        // Terverifikasi = verification_status verified di registration_data
        $verifiedCount = RegistrationData::where('verification_status', 'verified')->count();

        // Belum diproses = masih pending
        $pendingCount = RegistrationData::where('verification_status', 'pending')->count();

        // Sudah Observasi
        $reRegisteredCount = RegistrationData::whereHas('observationData')->count();

        // Dokumen bermasalah = ada minimal 1 dokumen rejected per pendaftar
        $problematicCount = RegistrationDocument::where('verification_status', 'rejected')
            ->distinct('registration_data_id')
            ->count('registration_data_id');

        // --- DISTRIBUSI JURUSAN (Donut Chart) ---
        // Hitung peminat berdasarkan pilihan 1 per konsentrasi
        $concentrations = Concentration::where('status', 'active')
            ->withCount([
                'registrationsAsChoice1 as applicant_count',
            ])
            ->get();

        // --- DATA CHART JURUSAN (Mencegah error parse di Blade JS) ---
        $concentrationChartData = $concentrations->map(function ($c) {
            return [
                'label' => $c->alias ?? $c->name,
                'count' => $c->applicant_count ?? 0,
                'color' => $c->color ?? '#6B7280',
            ];
        });

        // --- KUOTA & PEMINAT (Progress bars) ---
        // Data sudah ada di $concentrations di atas (quota + applicant_count)

        // --- AKTIVITAS TERBARU ---
        $activities = ActivityLog::with(['user', 'registrationData.personalData'])
            ->latest()
            ->take(8)
            ->get();

        // --- TABEL PENDAFTAR (Halaman pertama, 10 baris) ---
        $applicants = $this->getApplicantsQuery()->paginate(10);

        // --- CHART RANGE DARI SPMB STEP 'pendaftaran-spmb' ---
        $spmbStep = SpmbStep::where('slug', 'pendaftaran-spmb')->first();
        $spmbChartRange = ['days' => 30, 'label' => '30 hari terakhir', 'btn_label' => '30 Hari'];
        if ($spmbStep) {
            $start = \Carbon\Carbon::parse($spmbStep->start_date);
            $end   = \Carbon\Carbon::parse($spmbStep->end_date);
            $days  = max(1, (int) $start->diffInDays(now()->min($end)) + 1);
            $spmbChartRange = [
                'days'      => $days,
                'label'     => $spmbStep->period_text ?? "{$days} hari terakhir",
                'btn_label' => $spmbStep->period_text ?? "{$days} Hari",
                'start'     => $start->format('Y-m-d'),
                'end'       => $end->format('Y-m-d'),
            ];
        }

        return view('pages.admin.dashboard.dashboard', compact(
            'totalApplicants',
            'verifiedCount',
            'pendingCount',
            'reRegisteredCount',
            'problematicCount',
            'concentrations',
            'concentrationChartData', // <--- Tambahkan variabel di sini
            'activities',
            'applicants',
            'spmbChartRange',
        ));
    }

    // ── HTMX: Data Chart (JSON) ──────────────────────────────────────────────

    /**
     * GET /admin/dashboard/chart?range=7|30
     * Dipanggil oleh JS Chart.js via fetch saat toggle 7/30 hari.
     */
    public function chartData(Request $request)
    {
        $requestedRange = (int) $request->input('range', 30);
        if ($requestedRange === 7) {
            $days = 7;
        } else {
            // Gunakan rentang SpmbStep jika tersedia
            $step = SpmbStep::where('slug', 'pendaftaran-spmb')->first();
            $days = $step
                ? max(1, (int) \Carbon\Carbon::parse($step->start_date)->diffInDays(now()->min(\Carbon\Carbon::parse($step->end_date))) + 1)
                : 30;
        }

        $data = RegistrationData::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subDays($days - 1)->startOfDay())
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Isi hari yang kosong dengan 0 supaya chart tidak bolong
        $labels = [];
        $counts = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $label = now()->subDays($i)->translatedFormat('j M');
            $labels[] = $label;
            $counts[] = $data[$date]->count ?? 0;
        }

        return response()->json([
            'labels' => $labels,
            'data'   => $counts,
        ]);
    }

    // ── HTMX: Tabel Pendaftar (Partial HTML) ────────────────────────────────

    /**
     * GET /admin/dashboard/applicants?search=&status=&concentration=&page=
     * hx-get dipanggil saat search/filter/pagination berubah.
     * hx-target="#applicant-table-wrapper"
     */
    public function applicants(Request $request)
    {
        $applicants = $this->getApplicantsQuery($request)->paginate(10);

        // Hanya return partial view (tanpa layout)
        return view('admin.dashboard.partials.applicant-table', compact('applicants'));
    }

    // ── HTMX: Activity Feed (Partial HTML) ──────────────────────────────────

    /**
     * GET /admin/dashboard/activities
     * hx-trigger="load, every 30s" untuk live polling.
     * hx-target="#activity-feed"
     */
    public function activities()
    {
        $activities = ActivityLog::with(['user', 'registrationData.personalData'])
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard.partials.activity-feed', compact('activities'));
    }

    // ── Private: Query Builder Tabel Pendaftar ───────────────────────────────

    private function getApplicantsQuery(?Request $request = null)
    {
        $query = RegistrationData::with([
            'personalData:id,full_name,nick_name,photo,previous_school,previous_school_city,phone_number_encrypted',
            'choice1Concentration:id,name,alias,color',
            'registrationDocuments',
            'selectionResult',
        ])
            ->latest('submitted_at');

        if ($request) {
            // Search nama / nomor pendaftaran
            if ($search = $request->input('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('registration_number', 'like', "%{$search}%")
                        ->orWhereHas(
                            'personalData',
                            fn($q) =>
                            $q->where('full_name', 'like', "%{$search}%")
                        );
                });
            }

            // Filter status verifikasi
            if ($status = $request->input('status')) {
                $query->where('verification_status', $status);
            }

            // Filter konsentrasi (pilihan 1)
            if ($concentration = $request->input('concentration')) {
                $query->where('choice_1', $concentration);
            }
        }

        return $query;
    }
}

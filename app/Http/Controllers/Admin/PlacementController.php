<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionPath;
use App\Models\Concentration;
use App\Models\SelectionResult;
use App\Services\PlacementService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class PlacementController extends Controller
{
    public function __construct(protected PlacementService $service) {}

    /**
     * Halaman ringkasan hasil penjenjangan terbaru.
     * GET /admin/penjenjangan
     */
    public function index()
    {
        $latestBatch = SelectionResult::max('batch') ?? 0;

        $summary = [];
        if ($latestBatch > 0) {
            $summary = SelectionResult::with(['registration.admissionPath', 'acceptedConcentration'])
                ->where('batch', $latestBatch)
                ->get()
                ->groupBy('status');
        }

        $acceptedCounts = SelectionResult::select('accepted_concentration_id', DB::raw('count(*) as accepted_count'))
            ->where('batch', $latestBatch)
            ->where('status', 'accepted')
            ->groupBy('accepted_concentration_id')
            ->pluck('accepted_count', 'accepted_concentration_id');

        $concentrations = Concentration::where('status', 'active')
            ->orderBy('name')
            ->get()
            ->map(function ($c) use ($acceptedCounts) {
                $c->accepted_count = $acceptedCounts[$c->id] ?? 0;
                return $c;
            });

        return view('pages.admin.penjenjangan.index', compact('latestBatch', 'summary', 'concentrations'));
    }

    /**
     * Jalankan penjenjangan secara manual oleh admin.
     * POST /admin/penjenjangan/run
     */
    public function run(Request $request)
    {
        Gate::authorize('superadmin');

        try {
            $output = $this->service->run(processedBy: Auth::user());

            return redirect()
                ->route('admin.penjenjangan.index')
                ->with('success', "Penjenjangan Batch #{$output['batch']} berhasil dijalankan.")
                ->with('placement_summary', $output['summary']);
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Gagal menjalankan penjenjangan: ' . $e->getMessage());
        }
    }

    /**
     * Detail hasil penjenjangan per jurusan & jalur.
     * GET /admin/penjenjangan/detail/{concentration}
     */
    public function detail(Concentration $concentration, Request $request)
    {
        $latestBatch    = SelectionResult::max('batch') ?? 0;
        $admissionPaths = AdmissionPath::where('is_active', true)->get();
        $perPage        = 10;

        // Bangun $resultsByPath sebagai LengthAwarePaginator per jalur
        $resultsByPath = collect();

        // Parameter pencarian global (berlaku untuk semua tab)
        $search = trim($request->get('search', ''));

        foreach ($admissionPaths as $path) {
            // Key konsisten: lowercase + spasi → underscore (slug-safe)
            $key = str_replace(' ', '_', strtolower($path->name));

            // Parameter halaman per jalur: ?page_reguler=2, ?page_prestasi=1, dst.
            // Reset ke halaman 1 saat ada pencarian baru
            $pageParam = 'page_' . $key;
            $page      = (int) $request->get($pageParam, 1);

            // Query per jalur dengan pagination
            // Filter menggunakan path_id (jalur saat DITERIMA), bukan jalur asal pendaftaran.
            $query = SelectionResult::with([
                'registration.personalData',
                'registration.admissionPath',
            ])
                ->where('batch', $latestBatch)
                ->where('accepted_concentration_id', $concentration->id)
                ->where('status', 'accepted')
                ->where('path_id', $path->id)
                ->orderBy('rank_in_concentration');

            // Terapkan filter pencarian berdasarkan nama atau nomor pendaftaran
            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('registration.personalData', function ($q2) use ($search) {
                        $q2->where('full_name', 'like', '%' . $search . '%');
                    })->orWhereHas('registration', function ($q2) use ($search) {
                        $q2->where('registration_number', 'like', '%' . $search . '%');
                    });
                });
            }

            $total = $query->count();
            $items = $query->offset(($page - 1) * $perPage)->limit($perPage)->get();

            $paginator = new LengthAwarePaginator(
                items: $items,
                total: $total,
                perPage: $perPage,
                currentPage: $page,
                options: [
                    'path'     => $request->url(),
                    'query'    => $request->except($pageParam),
                    'pageName' => $pageParam,
                ]
            );

            $resultsByPath->put($key, $paginator);
        }

        $quotaPerJalur = collect();
        $sisaKuotaDilimpahkan = 0;
        $totalKuotaDasar = 0;

        foreach ($admissionPaths as $path) {
            $key = str_replace(' ', '_', strtolower($path->name));
            $kuotaAwal = (int) floor($concentration->quota * $path->quota_percentage / 100);
            $totalKuotaDasar += $kuotaAwal;

            if (!str_contains(strtolower($path->name), 'reguler')) {
                $terisi = $resultsByPath->get($key)?->total() ?? 0;
                $sisa = max(0, $kuotaAwal - $terisi);
                $sisaKuotaDilimpahkan += $sisa;

                $quotaPerJalur->put($key, $kuotaAwal);
            } else {
                $quotaPerJalur->put($key, $kuotaAwal);
            }
        }

        // Hitung kursi yang menguap karena pembulatan
        $sisaPembulatan = $concentration->quota - $totalKuotaDasar;

        $regulerKey = $admissionPaths->map(fn($p) => str_replace(' ', '_', strtolower($p->name)))
            ->first(fn($k) => str_contains($k, 'reguler'));

        // Tambahkan limpahan sisa kuota jalur lain DAN sisa pembulatan ke Reguler
        if ($regulerKey && $quotaPerJalur->has($regulerKey)) {
            $kuotaRegulerAsli = $quotaPerJalur->get($regulerKey);
            $quotaPerJalur->put($regulerKey, $kuotaRegulerAsli + $sisaKuotaDilimpahkan + $sisaPembulatan);
        }

        return view('pages.admin.penjenjangan.detail', compact(
            'concentration',
            'latestBatch',
            'resultsByPath',
            'quotaPerJalur',
            'admissionPaths',
            'search'
        ));
    }

    /**
     * Daftar peserta yang tidak diterima (rejected) di batch terbaru.
     * GET /admin/penjenjangan/rejected
     */
    public function rejected(Request $request)
    {
        $latestBatch = SelectionResult::max('batch') ?? 0;
        $search      = trim($request->get('search', ''));

        $query = SelectionResult::with([
            'registration.personalData',
            'registration.admissionPath',
            'registration.choice1',
            'registration.choice2',
            'registration.choice3',
        ])
            ->where('batch', $latestBatch)
            ->where('status', 'rejected');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereHas('registration.personalData', function ($q2) use ($search) {
                    $q2->where('full_name', 'like', '%' . $search . '%');
                })->orWhereHas('registration', function ($q2) use ($search) {
                    $q2->where('registration_number', 'like', '%' . $search . '%');
                });
            });
        }

        $rejected = $query->orderByDesc('final_score')->paginate(10)->withQueryString();

        return view('pages.admin.penjenjangan.rejected', compact('rejected', 'latestBatch', 'search'));
    }

    /**
     * Histori semua batch penjenjangan yang pernah dijalankan.
     * GET /admin/penjenjangan/history
     */
    public function history()
    {
        $batches = SelectionResult::select(
            'batch',
            'processed_by',
            'processed_at',
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(status = "accepted") as accepted'),
            DB::raw('SUM(status = "rejected") as rejected')
        )
            ->with('processor')
            ->groupBy('batch', 'processed_by', 'processed_at')
            ->orderByDesc('batch')
            ->get();

        return view('pages.admin.penjenjangan.history', compact('batches'));
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\SpmbStep;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDaftarUlangSchedule
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $step = SpmbStep::where('slug', 'daftar-ulang')->first();
        $now = Carbon::now();

        if (!$step || !$step->start_date || !$step->end_date || !$now->between($step->start_date, $step->end_date)) {
            return redirect()->route('dashboard')->with('error', 'Tahap daftar ulang belum dibuka atau sudah berakhir.');
        }

        return $next($request);
    }
}

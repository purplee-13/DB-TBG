<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Site;
use App\Models\Maintanance;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (!session()->has('user_id')) {
            return redirect()->route('login')->withErrors(['login' => 'Silakan login terlebih dahulu!']);
        }
        
        $totalSites = DB::table('sites')->count();
        $totalVisit = DB::table('maintenances')->where('progres', 'Visit')->count();
        $totalNotVisit = DB::table('maintenances')->where('progres', 'Belum Visit')->count();
        $visitPercentage = $totalSites > 0
            ? round(($totalVisit / $totalSites) * 100, 2)
            : 0;

        $products = ['INTERSITE FO', 'MMP'];
        $chartData = [];

        $daysInMonth = Carbon::now()->daysInMonth;
        $startOfMonth = Carbon::now()->startOfMonth();

        // === Tambahan: Data tabel berdasarkan Service Area & STO ===
        $summary = Site::select(
                'service_area',
                'sto',
                DB::raw("SUM(CASE WHEN maintenances.progres = 'Visit' AND sites.product = 'INTERSITE FO' THEN 1 ELSE 0 END) as visited_fo"),
                DB::raw("SUM(CASE WHEN maintenances.progres = 'Visit' AND sites.product = 'MMP' THEN 1 ELSE 0 END) as visited_mmp"),
                DB::raw("SUM(CASE WHEN maintenances.progres = 'Belum Visit' AND sites.product = 'INTERSITE FO' THEN 1 ELSE 0 END) as notvisit_fo"),
                DB::raw("SUM(CASE WHEN maintenances.progres = 'Belum Visit' AND sites.product = 'MMP' THEN 1 ELSE 0 END) as notvisit_mmp"),
                DB::raw("COUNT(sites.id) as total")
            )
            ->leftJoin('maintenances', 'maintenances.site_id', '=', 'sites.id')
            ->groupBy('service_area', 'sto')
            ->orderBy('service_area')
            ->get();
        
        foreach ($products as $product) {
            $visited = DB::table('maintenances')
                ->join('sites', 'maintenances.site_id', '=', 'sites.id')
                ->where('sites.product', $product)
                ->where('maintenances.progres', 'Visit')
                ->count();

            $notVisited = DB::table('maintenances')
                ->join('sites', 'maintenances.site_id', '=', 'sites.id')
                ->where('sites.product', $product)
                ->where('maintenances.progres', 'Belum Visit')
                ->count();

            $total = $visited + $notVisited;
            if ($total == 0) {
                $visited = 0;
                $notVisited = 0;
            }

            $chartData[$product] = [
                'visited' => $visited,
                'notVisited' => $notVisited,
            ];

            // Ambil data visit per tanggal dalam bulan ini
            $visitsPerDay = DB::table('maintenances')
                ->select(DB::raw('DATE(tngl_visit) as date'), DB::raw('COUNT(*) as count'))
                ->whereMonth('tngl_visit', Carbon::now()->month)
                ->where('progres', 'Visit')
                ->groupBy(DB::raw('DATE(tngl_visit)'))
                ->pluck('count', 'date')
                ->toArray();

            // Siapkan array untuk grafik (agar tetap 31 hari meski ada yang kosong)
            $dailyVisits = [];
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $date = $startOfMonth->copy()->addDays($i - 1)->toDateString();
                $dailyVisits[] = $visitsPerDay[$date] ?? 0;
            }

            // Target = total sites / jumlah hari
            $dailyTarget = $daysInMonth > 0 ? round($totalSites / $daysInMonth, 2) : 0;
            
            return view('dashboard', [
                'totalSites' => $totalSites,
                'visitedSites' => $totalVisit,
                'notVisitedSites' => $totalNotVisit,
                'visitPercentage' => $visitPercentage,
                'chartData' => $chartData,
                'dailyVisits' => $dailyVisits,
                'dailyTarget' => $dailyTarget,
                'daysInMonth' => $daysInMonth,
                'summary' => $summary,
            ]);
        }
    }
}
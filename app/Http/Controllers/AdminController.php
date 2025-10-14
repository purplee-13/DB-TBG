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

        $today = Carbon::now();

        // Jika hari ini tanggal 1, update progres jadi "Belum Visit"
        if ($today->day === 1) {
            Maintenance::where('progres', 'Visit')->update(['progres' => 'Belum Visit']);
        }
        
        $totalSites = DB::table('sites')->count();

        $totalVisit = DB::table('maintenances')->where('progres', 'Visit')->count();
        $totalNotVisit = DB::table('sites')
    ->join('maintenances', 'sites.id', '=', 'maintenances.site_id')
    ->where('maintenances.progres', 'Belum Visit')
    ->distinct('sites.id')
    ->count('sites.id');

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

        // Ambil total site dari bulan lalu (berdasarkan created_at)
        $lastMonth = Carbon::now()->subMonth();
        $lastMonthTotal = Site::whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();

        // Hitung selisih
        $growth = $totalSites - $lastMonthTotal;

        // Tentukan warna indikator
        if ($growth > 0) {
            $color = 'text-green-500';
            $icon = 'trending_up';
        } elseif ($growth < 0) {
            $color = 'text-red-500';
            $icon = 'trending_down';
        } else {
            $color = 'text-gray-700';
            $icon = 'trending_flat';
        }
        
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
                'growth' => $growth,
                'color' => $color,
                'icon' => $icon,
            ]);
        }
    }
}
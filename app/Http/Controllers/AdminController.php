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

        // === Reset progres setiap tanggal 1 ===
        if ($today->day === 1) {
            Site::where('progres', 'Sudah Visit')->update(['progres' => 'Belum Visit']);
        }

        // === Statistik umum ===
        $totalSites = Site::count();
        $totalVisit = Site::where('progres', 'Sudah Visit')->count();
        $totalNotVisit = Site::where('progres', 'Belum Visit')->count();

        $visitPercentage = $totalSites > 0
            ? round(($totalVisit / $totalSites) * 100, 2)
            : 0;

        // === Produk yang tersedia ===
        $products = ['INTERSITE FO', 'MMP'];
        $chartData = [];

        $daysInMonth = $today->daysInMonth;
        $startOfMonth = $today->copy()->startOfMonth();

        // === TABEL REKAP PER STO ===
        $summary = Site::select(
            'sites.service_area',
            'sites.sto',
            DB::raw("COALESCE(technicians.jumlah_teknisi, 0) as jumlah_teknisi"),
            // jumlah visit hari ini (kolom tgl_visit harus ada di tabel sites)
            DB::raw("SUM(CASE WHEN sites.progres = 'Sudah Visit' AND DATE(sites.tgl_visit) = CURDATE() THEN 1 ELSE 0 END) as visited_today"),
            // visited per product
            DB::raw("SUM(CASE WHEN sites.progres = 'Sudah Visit' AND sites.product = 'INTERSITE FO' THEN 1 ELSE 0 END) as visited_fo"),
            DB::raw("SUM(CASE WHEN sites.progres = 'Sudah Visit' AND sites.product = 'MMP' THEN 1 ELSE 0 END) as visited_mmp"),
            // not visited per product
            DB::raw("SUM(CASE WHEN sites.progres = 'Belum Visit' AND sites.product = 'INTERSITE FO' THEN 1 ELSE 0 END) as notvisit_fo"),
            DB::raw("SUM(CASE WHEN sites.progres = 'Belum Visit' AND sites.product = 'MMP' THEN 1 ELSE 0 END) as notvisit_mmp"),
            DB::raw("COUNT(sites.id) as total")
        )
        ->leftJoin('technicians', 'technicians.sto', '=', 'sites.sto')
        // group by harus menyertakan semua kolom non-aggregat yang kita pilih
        ->groupBy('sites.service_area', 'sites.sto', 'technicians.jumlah_teknisi')
        ->orderBy('sites.service_area')
        ->get();

        // === Perbandingan dengan bulan lalu ===
        $lastMonth = Carbon::now()->subMonth();
        $lastMonthTotal = Site::whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();

        $growth = $totalSites - $lastMonthTotal;

        // === Warna & ikon indikator ===
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

        // === Data grafik per produk ===
        foreach ($products as $product) {
            $visited = Site::where('product', $product)
                ->where('progres', 'Sudah Visit')
                ->count();

            $notVisited = Site::where('product', $product)
                ->where('progres', 'Belum Visit')
                ->count();

            $chartData[$product] = [
                'visited' => $visited,
                'notVisited' => $notVisited,
            ];
        }

        // === Visit per tanggal ===
        $visitsPerDay = Site::select(DB::raw('DATE(tgl_visit) as date'), DB::raw('COUNT(*) as count'))
            ->whereMonth('tgl_visit', Carbon::now()->month)
            ->where('progres', 'Sudah Visit')
            ->groupBy(DB::raw('DATE(tgl_visit)'))
            ->pluck('count', 'date')
            ->toArray();

        $dailyVisits = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $date = $startOfMonth->copy()->addDays($i - 1)->toDateString();
            $dailyVisits[] = $visitsPerDay[$date] ?? 0;
        }

        $dailyTarget = $daysInMonth > 0 ? round($totalSites / $daysInMonth, 2) : 0;

        // === Kirim data ke view ===
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

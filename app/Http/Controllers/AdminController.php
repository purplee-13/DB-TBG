<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Site;
use App\Models\Maintanance;

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
            return view('dashboard', [
                'totalSites' => $totalSites,
                'visitedSites' => $totalVisit,
                'notVisitedSites' => $totalNotVisit,
                'visitPercentage' => $visitPercentage,
                'chartData' => $chartData,
            ]);
        }
    }
}
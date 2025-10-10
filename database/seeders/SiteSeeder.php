<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Site;
use Illuminate\Support\Str;

class SiteSeeder extends Seeder
{
    public function run(): void
    {
        $serviceAreas = ['SA PAREPARE', 'SA PALOPO', 'SA MAJENE', 'SA PINRANG'];
        $stos = ['BAR', 'BLP', 'ENR', 'MAJ', 'MAK', 'MAM', 'MAS', 'MLL', 'MMS', 'PIN', 'PKA', 'PLP', 'PLW', 'PRE', 'RTP', 'SID', 'SIW', 'SKG', 'TMN', 'TPY', 'TTE', 'WTG'];
        $products = ['INTERSITE FO', 'MMP'];

        for ($i = 1; $i <= 100; $i++) {
            Site::create([
                'site_code' => 100000 + $i,
                'site_name' => 'Site Name ' . $i,
                'service_area' => $serviceAreas[array_rand($serviceAreas)],
                'sto' => $stos[array_rand($stos)],
                'product' => $products[array_rand($products)],
                'tikor' => '-'.rand(1,9).'.'.rand(10000,99999).', '.rand(100,999).'.'.rand(10000,99999),
            ]);
        }
    }
}

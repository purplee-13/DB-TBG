<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Site;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;

class SiteSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = database_path('seeders/data/datasites.xlsx');

        // Buka file Excel
        $spreadsheet = IOFactory::load($filePath);

        // Pilih sheet "Sheet1"
        $sheet = $spreadsheet->getSheetByName('Sheet1');
        if (!$sheet) {
            $this->command->error('Sheet1 tidak ditemukan di file Excel!');
            return;
        }

        $rows = $sheet->toArray(null, true, true, true);

        // Lewati baris pertama (header)
        foreach (array_slice($rows, 1) as $row) {
            $siteCode = $row['A'] ?? null;
            $siteName = $row['B'] ?? null;
            $serviceArea = $row['C'] ?? null;
            $sto = $row['D'] ?? null;
            $product = $row['E'] ?? null;
            $tikor = $row['F'] ?? null;

            if ($siteCode && $siteName && $product) {

                DB::table('sites')->updateOrInsert(
                    ['site_code' => $siteCode],
                    [
                        'site_name' => $siteName,
                        'service_area' => $serviceArea,
                        'sto' => $sto,
                        'product' => $product,
                        'tikor' => $tikor,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]
                );
            }
        }

        $this->command->info('✅ Data dari Sheet1 berhasil dimasukkan ke tabel sites!');
    }
}

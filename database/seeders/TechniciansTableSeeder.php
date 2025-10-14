<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TechniciansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('technicians')->insert([
            ['sto' => 'MAS', 'jumlah_teknisi' => 4],
            ['sto' => 'MLL', 'jumlah_teknisi' => 1],
            ['sto' => 'TMN', 'jumlah_teknisi' => 2],
            ['sto' => 'MAJ', 'jumlah_teknisi' => 1],
            ['sto' => 'MMS', 'jumlah_teknisi' => 1],
            ['sto' => 'PLW', 'jumlah_teknisi' => 2],
            ['sto' => 'MAM', 'jumlah_teknisi' => 2],
            ['sto' => 'PKA', 'jumlah_teknisi' => 2],
            ['sto' => 'TPY', 'jumlah_teknisi' => 1],
            ['sto' => 'BLP', 'jumlah_teknisi' => 1],
            ['sto' => 'PLP', 'jumlah_teknisi' => 7],
            ['sto' => 'BAR', 'jumlah_teknisi' => 1],
            ['sto' => 'PRE', 'jumlah_teknisi' => 2],
            ['sto' => 'PIN', 'jumlah_teknisi' => 3],
            ['sto' => 'SID', 'jumlah_teknisi' => 2],
            ['sto' => 'TTE', 'jumlah_teknisi' => 2],
            ['sto' => 'ENR', 'jumlah_teknisi' => 2],
            ['sto' => 'MAK', 'jumlah_teknisi' => 3],
            ['sto' => 'RTP', 'jumlah_teknisi' => 4],
            ['sto' => 'SIW', 'jumlah_teknisi' => 2],
            ['sto' => 'SKG', 'jumlah_teknisi' => 4],
            ['sto' => 'WTG', 'jumlah_teknisi' => 2],
        ]);
    }
}

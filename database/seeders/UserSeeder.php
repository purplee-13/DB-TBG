<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'username' => 'master01',
                'name' => 'Master',
                'email' => 'master@example.com',
                'password' => Hash::make('Password_123'),
                'role' => 'master',
                'remember_token' => Str::random(10),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'username' => 'admin01',
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => Hash::make('Password_123'),
                'role' => 'admin',
                'remember_token' => Str::random(10),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'username' => 'ingridd01',
                'name' => 'Ingrid Febrianti',
                'email' => 'ingridd@example.com',
                'password' => Hash::make('Password_123'),
                'role' => 'pegawai',
                'remember_token' => Str::random(10),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
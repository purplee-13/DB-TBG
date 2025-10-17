<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     */
    public function run(): void
    {
        // Data user yang akan disimpan
        $users = [
            [
                'username' => 'master01',
                'name' => 'Master',
                'email' => 'master@example.com',
                'password' => Hash::make('Password_123'),
                'role' => 'master',
            ],
            [
                'username' => 'admin01',
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => Hash::make('Password_123'),
                'role' => 'admin',
            ],
            [
                'username' => 'ingridd01',
                'name' => 'Ingrid Febrianti',
                'email' => 'ingridd@example.com',
                'password' => Hash::make('Password_123'),
                'role' => 'pegawai',
            ],
        ];

        // Looping untuk insert/update tanpa error duplicate
        foreach ($users as $user) {
            User::updateOrCreate(
                ['username' => $user['username']], // jika username sudah ada → update
                array_merge($user, [
                    'remember_token' => Str::random(10),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ])
            );
        }
    }
}

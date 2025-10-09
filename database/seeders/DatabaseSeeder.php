<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat akun admin default
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@towergroup.com',
            'username' => 'admin',
            'password' => Hash::make('admin'), // password = admin
        ]);

        // Tambahkan seeder lain jika perlu
        // $this->call(OtherSeeder::class);
    }
}

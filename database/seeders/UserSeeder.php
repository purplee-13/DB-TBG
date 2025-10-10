<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'namikaze',
            'name' => 'minato namikaze',
            'email' => 'superadmin@gmail.com',
            'role' => 'super admin',
            'password' => Hash::make('superadmin3221'),
        ]);

        User::create([
            'username' => 'nara',
            'name' => 'nara shikamaru',
            'email' => 'nara@gmail.com',
            'role' => 'admin',
            'password' => Hash::make('admin3221'),
        ]);

        User::create([
            'username' => 'sarutobi',
            'name' => 'konohamaru',
            'email' => 'pegawai@gmail.com',
            'role' => 'pegawai',
            'password' => Hash::make('pegawai3221'),
        ]);
    }
}

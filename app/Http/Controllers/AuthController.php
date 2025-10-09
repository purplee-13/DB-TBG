<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Tampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        // validasi sederhana
        $username = $request->input('username');
        $password = $request->input('password');

        // contoh hardcode user login
        if ($username === 'admin' && $password === '12345') {
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'login' => 'Username atau password salah!',
        ]);
    }

    // Halaman dashboard setelah login berhasil
    public function dashboard()
    {
        return view('dashboard');
    }
}

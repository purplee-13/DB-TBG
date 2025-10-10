<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // 🔹 Tampilkan halaman login
    public function showLoginForm()
    {
        return view('login');
    }

    // 🔹 Proses login
    public function processLogin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Ambil data user dari database
        $user = User::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Simpan session
            session([
                'user_id' => $user->id,
                'username' => $user->username,
                'role' => $user->role,
                'name' => $user->name,
            ]);

            return redirect()->route('dashboard');
        }

        return back()->withErrors(['login' => 'Username atau password salah!']);
    }

    // 🔹 Halaman dashboard
    public function dashboard()
    {
        if (!session('user_id')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu!');
        }

        return view('dashboard');
    }

    // 🔹 Logout
    public function processLogout()
    {
        session()->flush();
        return redirect()->route('login')->with('success', 'Berhasil logout!');
    }
}

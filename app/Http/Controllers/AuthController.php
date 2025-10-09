<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // 🔹 Menampilkan form login
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

        // Cari user berdasarkan username
        $user = User::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Simpan data user ke session
            session([
                'user_id' => $user->id,
                'username' => $user->username,
                'name' => $user->name,
                'role' => $user->role,
            ]);

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'login' => 'Username atau password salah!',
        ]);
    }

    // 🔹 Dashboard hanya bisa diakses setelah login
    public function dashboard()
    {
        if (!session()->has('user_id')) {
            return redirect()->route('login')->withErrors(['login' => 'Silakan login terlebih dahulu!']);
        }

        return view('dashboard');
    }

    // 🔹 Logout
    public function processLogout()
    {
        session()->flush();
        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}

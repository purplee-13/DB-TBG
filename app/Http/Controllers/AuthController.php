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
            'password' => [
                'required',
                'string',
                'min:8',
                'max:20',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&_]/',
            ],
        ], [
            'password.min' => 'Password minimal 8 karakter',
            'password.max' => 'Password maksimal 20 karakter',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter spesial.'
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

        return back()->withErrors(['login' => 'Username atau password salah!']);
    }


    // 🔹 Logout
    public function processLogout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}

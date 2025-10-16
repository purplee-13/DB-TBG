<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class AuthController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('login');
    }

    // Proses login
    public function processLogin(Request $request)
    {
        $credentials = $request->validate([
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

        // Debug logging (no passwords)
        Log::info('Login attempt', [
            'username' => $request->username,
            'user_found' => $user ? true : false,
            'user_id' => $user ? $user->id : null,
        ]);

        $passwordMatches = $user ? Hash::check($request->password, $user->password) : false;
        Log::info('Login password_check', [
            'username' => $request->username,
            'password_matches' => $passwordMatches,
        ]);

        if ($user && $passwordMatches) {
            // Use Laravel Auth to log the user in and regenerate session to prevent fixation
            Auth::login($user);
            $request->session()->regenerate();

            // Also keep session keys for legacy code that reads them
            session([
                'user_id' => $user->id,
                'username' => $user->username,
                'name' => $user->name,
                'role' => $user->role,
            ]);

            Log::info('User logged in', ['user_id' => $user->id, 'username' => $user->username]);

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
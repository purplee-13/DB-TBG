<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        // Allow only users with role 'Master' to access any method in this controller.
        $this->middleware(function ($request, $next) {
            $user = auth()->user();

            // Some parts of the app use custom session-based auth; also check session('role')
            $role = $user?->role ?? session('role');

            // Normalize role to lowercase for comparison (database uses lowercase values)
            $role = is_string($role) ? strtolower($role) : $role;

            if ($role !== 'master') {
                abort(403, 'Unauthorized - hanya Master yang dapat mengakses halaman ini.');
            }

            return $next($request);
        });
    }
    // Tampilkan semua user
    public function index()
    {
        // Exclude master accounts (roles stored lowercase in DB)
        $users = User::where('role', '!=', 'master')->get();
        return view('kelola-pengguna', compact('users'));
    }

    // Simpan user baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'username' => 'required|string|max:100|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,pegawai'
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => strtolower($request->role)
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    // Tampilkan form edit
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    // Update data user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->username = $request->username;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password); // ⬅️ penting!
        }

    $user->role = strtolower($request->role);
        $user->save();

        return redirect()->back()->with('success', 'User berhasil diperbarui.');
    }


    // Hapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}

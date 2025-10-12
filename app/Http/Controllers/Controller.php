<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Site;
use App\Models\Maintenance;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // Halaman login
    public function login()
    {
        return view('login');
    }

    // Proses login
    public function loginPost(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            // Login sukses
            return redirect()->route('dashboard');
        }
        // Login gagal
        return back()->withErrors(['email' => 'Login gagal!']);
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    // Dashboard
    public function dashboard()
    {
        // Sudah dijaga oleh middleware auth, tidak perlu cek lagi
        return view('dashboard');
    }

    // Data Site
    public function datasite()
    {
        $stoData = config('sto');
        $sites = Site::all();
        return view('datasite', compact('sites', 'stoData'));
    }

    // Update Maintenance
    public function updateMaintenance()
    {
        $maintenances = Maintenance::with('site')->get();
        $sites = Site::all();
        return view('update-maintenance', compact('maintenances', 'sites'));
    }

    // Simpan Data Site
    public function storeSite(Request $request)
    {
        $request->validate([
            'site_code' => 'required',
            'site_name' => 'required',
            'service_area' => 'required',
            'sto' => 'nullable',
            'product' => 'nullable',
            'tikor' => 'nullable',
            'status' => 'required',
        ]);
        Site::create($request->all());
        return redirect()->route('datasite')->with('success', 'Data site berhasil disimpan!');
    }

    // Simpan Data Maintenance
    public function storeMaintenance(Request $request)
    {
        $request->validate([
            'site_id' => 'required|exists:sites,id',
            'technician' => 'required',
            'visit_date' => 'required|date',
            'status' => 'required',
            'operator' => 'nullable',
            'description' => 'nullable',
            'notes' => 'nullable',
        ]);
        Maintenance::create($request->all());
        return redirect()->route('update.maintenance')->with('success', 'Data maintenance berhasil disimpan!');
    }
}
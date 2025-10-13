<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Site;
use App\Models\Maintenance;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // 🟢 Halaman Login
    public function login()
    {
        return view('login');
    }

    // 🟢 Proses Login (sementara langsung ke dashboard)
    public function loginPost(Request $request)
    {
        // Simulasi login tanpa database
        Session::put('user', 'admin');
        return redirect()->route('dashboard');
    }

    // 🟢 Logout
    public function logout()
    {
        Session::forget('user');
        return redirect()->route('login');
    }

    // 🟢 Dashboard
    public function dashboard()
    {
        if (!Session::has('user')) {
            return redirect()->route('login');
        }
        return view('dashboard');
    }

    
    // 🟢 Simpan Data Maintenance
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

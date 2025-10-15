<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Site::query();
        
        // Search by site_code or site_name
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('site_code', 'like', '%' . $searchTerm . '%')
                  ->orWhere('site_name', 'like', '%' . $searchTerm . '%');
            });
        }
        
        $sites = $query->orderByRaw("CASE WHEN progres = 'Belum Visit' THEN 0 ELSE 1 END")
                      ->orderBy('site_name', 'asc')
                      ->get();
        return view('update-maintenance', compact('sites'));
    }

    public function create()
    {
        $sites = Site::orderBy('site_name')->get();
        return view('maintenances.create', compact('sites'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'site_id' => 'required|exists:sites,id',
            'technician' => 'required|string',
            'description' => 'nullable|string',
            'visit_date' => 'required|date',
            'status' => 'required|string',
            'operator' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        Maintenance::create($request->only([
            'site_id','technician','description','visit_date','status','operator','notes'
        ]));

        return redirect()->route('maintenances.index')->with('success','Data maintenance berhasil disimpan.');
    }

    public function edit(Maintenance $maintenance)
    {
        $sites = Site::orderBy('site_name')->get();
        return view('maintenances.edit', compact('maintenance','sites'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'teknisi' => 'nullable|string',
            'tgl_visit' => 'nullable|date',
            'progres' => 'required|in:Sudah Visit,Belum Visit',
            'operator' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $site = Site::findOrFail($id);
        
        // Debug: Log the request data
        \Log::info('Update request data:', $request->all());
        \Log::info('Site ID:', ['id' => $id]);
        
        $site->update($request->only([
            'teknisi', 'tgl_visit', 'progres', 'operator', 'keterangan'
        ]));

        return redirect()->route('maintenance.index')->with('success','Data site berhasil diperbarui.');
    }

    public function destroy(Maintenance $maintenance)
    {
        $maintenance->delete();
        return redirect()->route('maintenances.index')->with('success','Maintenance berhasil dihapus.');
    }
}
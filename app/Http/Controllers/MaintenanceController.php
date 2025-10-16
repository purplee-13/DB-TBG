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
            'teknisi' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'tngl_visit' => 'nullable|date',
            // Accept both 'Visit' and legacy label 'Sudah Visit'
            'progres' => 'required|in:Visit,Belum Visit,Sudah Visit',
            'operator' => 'nullable|string',
        ]);

        // Upsert: if a maintenance record exists for this site on the same date, update it; otherwise create
        $siteId = $request->input('site_id');
    // Use provided date or default to today so user can update progres without filling date
    $date = $request->input('tngl_visit') ?: now()->toDateString();

        $maintenance = Maintenance::where('site_id', $siteId)
            ->whereDate('tngl_visit', $date)
            ->first();

        // Normalize progres value to canonical DB enum 'Visit' or 'Belum Visit'
        $progresInput = $request->input('progres');
        if ($progresInput === 'Sudah Visit') {
            $progresInput = 'Visit';
        }

        $data = [
            'site_id' => $siteId,
            'teknisi' => $request->input('teknisi'),
            'keterangan' => $request->input('keterangan'),
            'tngl_visit' => $date,
            'progres' => $progresInput,
            'operator' => $request->input('operator'),
        ];

        if ($maintenance) {
            $maintenance->update($data);
        } else {
            Maintenance::create($data);
        }

        return redirect()->route('update-maintenance')->with('success','Data maintenance berhasil disimpan.');
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

        return redirect()->route('update-maintenance')->with('success','Update berhasil disimpan.');
    }

    public function destroy(Maintenance $maintenance)
    {
        $maintenance->delete();
        return redirect()->route('maintenances.index')->with('success','Maintenance berhasil dihapus.');
    }
}
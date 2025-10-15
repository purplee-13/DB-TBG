<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MaintenanceController extends Controller
{
    public function index()
    {
        // Load all sites with their latest maintenance (if any)
        $sites = Site::with(['maintenances' => function ($q) {
            $q->orderBy('tngl_visit', 'desc');
        }])->orderBy('site_name')->get();

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

    public function update(Request $request, Maintenance $maintenance)
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

        $maintenance->update($request->only([
            'site_id','technician','description','visit_date','status','operator','notes'
        ]));

        return redirect()->route('maintenances.index')->with('success','Maintenance berhasil diperbarui.');
    }

    public function destroy(Maintenance $maintenance)
    {
        $maintenance->delete();
        return redirect()->route('maintenances.index')->with('success','Maintenance berhasil dihapus.');
    }
}
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
        // $maintenances = Maintenance::with('site')->orderBy('visit_date','desc')->get();
        return view('update-maintenance');
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
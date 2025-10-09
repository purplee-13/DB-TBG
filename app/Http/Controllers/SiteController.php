<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SiteController extends Controller
{
    public function index()
    {
        $sites = Site::orderBy('id','desc')->get();
        return view('sites.index', compact('sites'));
    }

    public function create()
    {
        return view('sites.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'site_code' => 'required|unique:sites,site_code',
            'site_name' => 'required|string',
            'service_area' => 'nullable|string',
            'sto' => 'nullable|string',
            'product' => 'nullable|string',
            'tikor' => 'nullable|string',
            'status' => 'required|string',
        ]);

        Site::create($request->only([
            'site_code','site_name','service_area','sto','product','tikor','status'
        ]));

        return redirect()->route('sites.index')->with('success','Site berhasil ditambahkan.');
    }

    public function edit(Site $site)
    {
        return view('sites.edit', compact('site'));
    }

    public function update(Request $request, Site $site)
    {
        $request->validate([
            'site_code' => ['required', Rule::unique('sites','site_code')->ignore($site->id)],
            'site_name' => 'required|string',
            'service_area' => 'nullable|string',
            'sto' => 'nullable|string',
            'product' => 'nullable|string',
            'tikor' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $site->update($request->only([
            'site_code','site_name','service_area','sto','product','tikor','status'
        ]));

        return redirect()->route('sites.index')->with('success','Site berhasil diperbarui.');
    }

    public function destroy(Site $site)
    {
        $site->delete();
        return redirect()->route('sites.index')->with('success','Site berhasil dihapus.');
    }
}

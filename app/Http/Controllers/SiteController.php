<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SiteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'site_code' => 'required|unique:sites,site_code',
            'site_name' => 'required|string',
            'service_area' => 'required|string',
            'sto' => 'required|string',
            'product' => 'required|string',
            'tikor' => 'nullable|string',
        ], [
            'site_code.unique' => 'Kode site sudah digunakan, silakan masukkan kode lain.',
            'site_code.required' => 'Kode site wajib diisi.',
            'site_name.required' => 'Nama site wajib diisi.',
            'service_area.required' => 'Service area wajib dipilih.',
            'sto.required' => 'STO wajib dipilih.',
            'product.required' => 'Product wajib dipilih.'
        ]);

        Site::create($request->only([
            'site_code','site_name','service_area','sto','product','tikor'
        ]));

        return redirect()->route('datasite')->with('success','Site berhasil ditambahkan.');
    }
    public function index()
    {
        $sites = Site::orderBy('id','desc')->get();
        return view('datasite', compact('sites'));
    }

    public function create()
    {
        return view('sites.create');
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
        ]);

        $site->update($request->only([
            'site_code','site_name','service_area','sto','product','tikor',
        ]));

        return redirect()->route('datasite')->with('success','Site berhasil diperbarui.');
    }

    public function destroy(Site $site)
    { 
    $site->delete();
    return redirect()->route('datasite')->with('success','Site berhasil dihapus.');
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'ID tidak valid']);
        }

        Site::whereIn('id', $ids)->delete();

        return response()->json(['success' => true]);
    }

}

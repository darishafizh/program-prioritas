<?php

namespace App\Http\Controllers\Knmp\Master;

use App\Http\Controllers\ProgramBaseController;
use App\Models\PenyediaJasaKonstruksi;
use Illuminate\Http\Request;

class VendorController extends ProgramBaseController
{

    public function index(Request $request, $program)
    {
        $this->checkAuth();
        \Illuminate\Support\Facades\Gate::authorize('manage-master');
        $activeProgram = $this->formatProgramName($program);
        
        $vendors = PenyediaJasaKonstruksi::orderBy('id', 'desc')->get();

        return view('programs.knmp.master.vendor.index', [
            'activeModule' => 'Master Data', 
            'activeProgram' => $activeProgram,
            'vendors' => $vendors
        ]);
    }

    public function store(Request $request, $program)
    {
        $this->checkAuth();
        \Illuminate\Support\Facades\Gate::authorize('manage-master');
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'npwp' => 'nullable|string|max:255',
            'direktur_utama' => 'nullable|string|max:255',
            'kontak' => 'nullable|string|max:255',
            'kualifikasi_sbu' => 'nullable|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        PenyediaJasaKonstruksi::create($validated);

        return redirect()->back()->with('success', 'Data vendor berhasil ditambahkan.');
    }

    public function update(Request $request, $id, $program = 'knmp')
    {
        $this->checkAuth();
        \Illuminate\Support\Facades\Gate::authorize('manage-master');
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'npwp' => 'nullable|string|max:255',
            'direktur_utama' => 'nullable|string|max:255',
            'kontak' => 'nullable|string|max:255',
            'kualifikasi_sbu' => 'nullable|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        $vendor = PenyediaJasaKonstruksi::findOrFail($id);
        $vendor->update($validated);

        return redirect()->back()->with('success', 'Data vendor berhasil diperbarui.');
    }

    public function destroy($id, $program = 'knmp')
    {
        $this->checkAuth();
        \Illuminate\Support\Facades\Gate::authorize('manage-master');

        try {
            $vendor = PenyediaJasaKonstruksi::findOrFail($id);
            $vendor->delete();
            return redirect()->back()->with('success', 'Data vendor berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data vendor gagal dihapus.');
        }
    }
}

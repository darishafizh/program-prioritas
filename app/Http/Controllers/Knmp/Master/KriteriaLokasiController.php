<?php

namespace App\Http\Controllers\Knmp\Master;

use App\Http\Controllers\ProgramBaseController;
use App\Models\KriteriaLokasi;
use Illuminate\Http\Request;

class KriteriaLokasiController extends ProgramBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $program)
    {
        $this->checkAuth();
        $activeProgram = $this->formatProgramName($program);

        $kriterias = KriteriaLokasi::orderBy('id', 'desc')->get();
        return view('programs.knmp.master.kriteria.index', [
            'activeModule' => 'Master Data',
            'activeProgram' => $activeProgram,
            'kriterias' => $kriterias
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $program)
    {
        $this->checkAuth();
        
        $validated = $request->validate([
            'nama_kriteria' => 'required|string|max:255',
            'bobot' => 'required|integer|min:0|max:100',
            'keterangan' => 'nullable|string',
        ]);

        KriteriaLokasi::create($validated);

        return redirect()->back()->with('success', 'Data kriteria lokasi berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $program, string $id)
    {
        $this->checkAuth();

        $validated = $request->validate([
            'nama_kriteria' => 'required|string|max:255',
            'bobot' => 'required|integer|min:0|max:100',
            'keterangan' => 'nullable|string',
        ]);

        $kriteria = KriteriaLokasi::findOrFail($id);
        $kriteria->update($validated);

        return redirect()->back()->with('success', 'Data kriteria lokasi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($program, string $id)
    {
        $this->checkAuth();

        try {
            $kriteria = KriteriaLokasi::findOrFail($id);
            $kriteria->delete();
            return redirect()->back()->with('success', 'Data kriteria lokasi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data kriteria lokasi gagal dihapus.');
        }
    }
}

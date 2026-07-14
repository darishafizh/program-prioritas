<?php

namespace App\Http\Controllers\Knmp\Master;

use App\Http\Controllers\ProgramBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class TahapController extends ProgramBaseController
{
    public function index(Request $request, $program)
    {
        $this->checkAuth();
        Gate::authorize('manage-master');
        $activeProgram = $this->formatProgramName($program);

        $tahaps = DB::connection('mysql_knmp')->table('batch')->orderBy('id', 'desc')->get();

        return view('programs.knmp.master.tahap.index', [
            'activeModule' => 'Master Data',
            'activeProgram' => $activeProgram,
            'tahaps' => $tahaps,
        ]);
    }

    public function create(Request $request, $program)
    {
        $this->checkAuth();
        Gate::authorize('manage-master');
        $activeProgram = $this->formatProgramName($program);

        $tahaps = DB::connection('mysql_knmp')->table('batch')->orderBy('id', 'desc')->get();

        return view('programs.knmp.master.tahap.index', [
            'activeModule' => 'Master Data',
            'activeProgram' => $activeProgram,
            'tahaps' => $tahaps,
            'openCreateModal' => true,
        ]);
    }

    public function store(Request $request, $program)
    {
        $this->checkAuth();
        Gate::authorize('manage-master');

        $request->validate([
            'nama_tahap' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2000|max:2099',
        ], [
            'nama_tahap.required' => 'Nama tahap wajib diisi.',
            'tahun.required' => 'Tahun wajib diisi.',
            'tahun.integer' => 'Tahun harus berupa angka.',
        ]);

        DB::connection('mysql_knmp')->table('batch')->insert([
            'nama_tahap' => $request->nama_tahap,
            'tahun' => $request->tahun,
        ]);

        return redirect()->route('program.master.tahap.index')->with('success', 'Data tahap berhasil ditambahkan.');
    }

    public function edit(Request $request, $id, $program = 'knmp')
    {
        $this->checkAuth();
        Gate::authorize('manage-master');
        $activeProgram = $this->formatProgramName($program);

        $tahaps = DB::connection('mysql_knmp')->table('batch')->orderBy('id', 'desc')->get();
        $tahap = DB::connection('mysql_knmp')->table('batch')->where('id', $id)->first();
        if (!$tahap) {
            abort(404, 'Data tahap tidak ditemukan.');
        }

        return view('programs.knmp.master.tahap.index', [
            'activeModule' => 'Master Data',
            'activeProgram' => $activeProgram,
            'tahaps' => $tahaps,
            'openEditModal' => true,
            'editTahap' => $tahap,
        ]);
    }

    public function update(Request $request, $id, $program = 'knmp')
    {
        $this->checkAuth();
        Gate::authorize('manage-master');

        $request->validate([
            'nama_tahap' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2000|max:2099',
        ], [
            'nama_tahap.required' => 'Nama tahap wajib diisi.',
            'tahun.required' => 'Tahun wajib diisi.',
            'tahun.integer' => 'Tahun harus berupa angka.',
        ]);

        $tahap = DB::connection('mysql_knmp')->table('batch')->where('id', $id)->first();
        if (!$tahap) {
            abort(404, 'Data tahap tidak ditemukan.');
        }

        DB::connection('mysql_knmp')->table('batch')->where('id', $id)->update([
            'nama_tahap' => $request->nama_tahap,
            'tahun' => $request->tahun,
        ]);

        return redirect()->route('program.master.tahap.index')->with('success', 'Data tahap berhasil diperbarui.');
    }

    public function destroy($id, $program = 'knmp')
    {
        $this->checkAuth();
        Gate::authorize('manage-master');

        try {
            DB::connection('mysql_knmp')->table('batch')->where('id', $id)->delete();
            return redirect()->back()->with('success', 'Data tahap berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data tahap gagal dihapus karena masih digunakan oleh data lain.');
        }
    }
}

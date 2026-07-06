<?php

namespace App\Http\Controllers\Knmp\Master;

use App\Http\Controllers\ProgramBaseController;
use Illuminate\Http\Request;

class BatchController extends ProgramBaseController
{

    public function index(Request $request, $program)
    {
        $this->checkAuth();
        \Illuminate\Support\Facades\Gate::authorize('manage-master');
        $activeProgram = $this->formatProgramName($program);
        
        $batches = \Illuminate\Support\Facades\DB::connection('mysql_knmp')->table('batch')->get();
        return view('programs.knmp.master.batch.index', [
            'activeModule' => 'Master Data', 
            'activeProgram' => $activeProgram,
            'batches' => $batches
        ]);
    }

    public function store(Request $request, $program)
    {
        $this->checkAuth();
        \Illuminate\Support\Facades\Gate::authorize('manage-master');
        $request->validate([
            'nama_tahap' => 'required|string|max:255',
            'tahun' => 'required|integer',
        ]);

        \Illuminate\Support\Facades\DB::connection('mysql_knmp')->table('batch')->insert([
            'nama_tahap' => $request->nama_tahap,
            'tahun' => $request->tahun,
        ]);

        return redirect()->back()->with('success', 'Data tahap berhasil ditambahkan.');
    }

    public function update(Request $request, $program, $id)
    {
        $this->checkAuth();
        \Illuminate\Support\Facades\Gate::authorize('manage-master');
        $request->validate([
            'nama_tahap' => 'required|string|max:255',
            'tahun' => 'required|integer',
        ]);

        \Illuminate\Support\Facades\DB::connection('mysql_knmp')->table('batch')->where('id', $id)->update([
            'nama_tahap' => $request->nama_tahap,
            'tahun' => $request->tahun,
        ]);

        return redirect()->back()->with('success', 'Data tahap berhasil diperbarui.');
    }

    public function destroy($program, $id)
    {
        $this->checkAuth();
        \Illuminate\Support\Facades\Gate::authorize('manage-master');
        try {
            \Illuminate\Support\Facades\DB::connection('mysql_knmp')->table('batch')->where('id', $id)->delete();
            return redirect()->back()->with('success', 'Data tahap berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data tahap gagal dihapus karena masih digunakan oleh data lain.');
        }
    }
}

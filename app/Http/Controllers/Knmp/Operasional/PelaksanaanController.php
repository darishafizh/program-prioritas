<?php

namespace App\Http\Controllers\Knmp\Operasional;

use App\Http\Controllers\ProgramBaseController;
use Illuminate\Http\Request;
use App\Models\Knmp;
use App\Models\KonstruksiKnmp;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsulanTemplateExport;
use App\Imports\UsulanImport;
use App\Exports\ProgresTemplateExport;
use App\Imports\ProgresImport;

class PelaksanaanController extends ProgramBaseController
{
    public function index(Request $request, $program)
    {
        $this->checkAuth();
        $activeProgram = $this->formatProgramName($program);
        
        $baseQuery = function($stage) {
            $query = Knmp::where('tahap_saat_ini', $stage);
            if (\Illuminate\Support\Facades\Auth::user()->isUserDaerah()) {
                $query->where('kabupaten', 'LIKE', '%' . \Illuminate\Support\Facades\Auth::user()->kabupaten . '%');
            }
            return $query;
        };
        
        $usulanData = $baseQuery('usulan')
            ->select('id', 'nama', 'provinsi', 'kabupaten', 'kecamatan', 'desa', 'status', 'latitude', 'longitude')
            ->get()->map(fn($k) => [
                'id' => $k->id,
                'lokasi' => $k->nama,
                'daerah' => $k->kabupaten . ', ' . $k->provinsi,
                'statusHub' => $k->status ?: 'Penyangga',
            ]);

        $surveiData = $baseQuery('survey')
            ->select('id', 'nama', 'provinsi', 'kabupaten', 'status', 'latitude', 'longitude')
            ->get()->map(fn($k) => [
                'id' => $k->id,
                'lokasi' => $k->nama,
                'statusHub' => $k->status ?: 'Penyangga',
                'koordinat' => $k->latitude && $k->longitude ? $k->latitude . ', ' . $k->longitude : '-',
            ]);

        $dedData = $baseQuery('ded')
            ->select('id', 'nama', 'status')
            ->get()->map(fn($k) => [
                'id' => $k->id,
                'lokasi' => $k->nama,
                'statusHub' => $k->status ?: 'Penyangga',
                'nilaiSkala' => '-',
            ]);

        $lelangData = $baseQuery('lelang')
            ->select('id', 'nama', 'status')
            ->get()->map(fn($k) => [
                'id' => $k->id,
                'lokasi' => $k->nama,
                'statusHub' => $k->status ?: 'Penyangga',
                'namaKonstruksi' => '-',
            ]);

        $konstruksiData = $baseQuery('konstruksi')
            ->select('id', 'nama', 'status')
            ->get()->map(function($k) {
                $kons = KonstruksiKnmp::where('knmp_id', $k->id)->first();
                $progres = 0;
                $konstruktor = '-';
                if ($kons) {
                    $konstruktor = $kons->penyediaJasa ? $kons->penyediaJasa->nama : '-';
                    $lastProgres = \Illuminate\Support\Facades\DB::connection('mysql_knmp')
                        ->table('progres_harian')
                        ->where('knmp_konstruksi_id', $kons->id)
                        ->orderBy('tanggal', 'desc')
                        ->first();
                    $progres = $lastProgres ? round($lastProgres->progres, 1) : 0;
                }
                return [
                    'id' => $k->id,
                    'lokasi' => $k->nama,
                    'statusHub' => $k->status ?: 'Penyangga',
                    'konstruktor' => $konstruktor,
                    'progres' => $progres,
                ];
            });

        $serahTerimaData = $baseQuery('serah_terima')
            ->select('id', 'nama', 'status')
            ->get()->map(fn($k) => [
                'id' => $k->id,
                'lokasi' => $k->nama,
                'statusHub' => $k->status ?: 'Penyangga',
            ]);

        return view('programs.knmp.operasional.index', [
            'activeModule' => 'Operasional',
            'activeProgram' => $activeProgram,
            'stage' => $request->query('stage', 'usulan'),
            'usulanData' => $usulanData->values(),
            'surveiData' => $surveiData->values(),
            'dedData' => $dedData->values(),
            'lelangData' => $lelangData->values(),
            'konstruksiData' => $konstruksiData->values(),
            'serahTerimaData' => $serahTerimaData->values(),
        ]);
    }

    public function uploadFoto(Request $request, $program)
    {
        $this->checkAuth();
        \Illuminate\Support\Facades\Gate::authorize('manage-data');

        $request->validate([
            'knmp_id' => 'required|exists:mysql_knmp.knmp,id',
            'foto_before' => 'nullable|array|max:2',
            'foto_before.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'foto_after' => 'nullable|array|max:2',
            'foto_after.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if (!$request->hasFile('foto_before') && !$request->hasFile('foto_after')) {
            return back()->with('error', 'Silakan pilih setidaknya satu foto untuk diunggah.');
        }

        $now = now();
        $inserts = [];

        if ($request->hasFile('foto_before')) {
            foreach ($request->file('foto_before') as $file) {
                $path = $file->store('bukti_uploads', 'public');
                $inserts[] = [
                    'knmp_id' => $request->knmp_id,
                    'kondisi' => 'before',
                    'nama_file' => $file->getClientOriginalName(),
                    'path_file' => $path,
                    'tipe_file' => $file->getMimeType(),
                    'ukuran_file' => $file->getSize(),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if ($request->hasFile('foto_after')) {
            foreach ($request->file('foto_after') as $file) {
                $path = $file->store('bukti_uploads', 'public');
                $inserts[] = [
                    'knmp_id' => $request->knmp_id,
                    'kondisi' => 'after',
                    'nama_file' => $file->getClientOriginalName(),
                    'path_file' => $path,
                    'tipe_file' => $file->getMimeType(),
                    'ukuran_file' => $file->getSize(),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if (count($inserts) > 0) {
            \Illuminate\Support\Facades\DB::connection('mysql_knmp')->table('bukti_uploads')->insert($inserts);
        }

        return back()->with('success', 'Foto berhasil diunggah dan disimpan.');
    }

    public function moveStage(Request $request, $program)
    {
        $this->checkAuth();
        \Illuminate\Support\Facades\Gate::authorize('manage-data');
        
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:mysql_knmp.knmp,id',
            'target_stage' => 'required|in:survey,ded,lelang,konstruksi,serah_terima'
        ]);

        // When moving to 'konstruksi', we need to create a KonstruksiKnmp entry if it doesn't exist
        if ($request->target_stage === 'konstruksi') {
            foreach ($request->ids as $id) {
                KonstruksiKnmp::firstOrCreate(['knmp_id' => $id], [
                    'penyedia_jasa_id' => null,
                    'nomor_kontrak' => '-',
                    'nilai_kontrak' => 0,
                    'tanggal_mulai' => now(),
                    'tanggal_selesai' => now()->addMonths(3),
                ]);
            }
        }

        Knmp::whereIn('id', $request->ids)->update(['tahap_saat_ini' => $request->target_stage]);

        return back()->with('success', count($request->ids) . ' lokasi berhasil dipindahkan ke tahap ' . ucfirst($request->target_stage) . '.');
    }

    public function downloadTemplateUsulan()
    {
        return Excel::download(new UsulanTemplateExport, 'Template_Usulan_KNMP.xlsx');
    }

    public function importUsulan(Request $request, $program)
    {
        $this->checkAuth();
        \Illuminate\Support\Facades\Gate::authorize('manage-data');
        
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls|max:5120'
        ]);

        Excel::import(new UsulanImport, $request->file('file_excel'));

        return back()->with('success', 'Data usulan berhasil diimport.');
    }

    public function downloadTemplateProgres()
    {
        return Excel::download(new ProgresTemplateExport, 'Template_Progres_KNMP.xlsx');
    }

    public function importProgres(Request $request, $program)
    {
        $this->checkAuth();
        \Illuminate\Support\Facades\Gate::authorize('manage-data');
        
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls|max:5120'
        ]);

        Excel::import(new ProgresImport, $request->file('file_excel'));

        return back()->with('success', 'Data progres harian berhasil diimport.');
    }
}

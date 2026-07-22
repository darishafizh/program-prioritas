<?php

namespace App\Http\Controllers\Knmp\Operasional;

use App\Http\Controllers\ProgramBaseController;
use Illuminate\Http\Request;
use App\Models\Knmp\Knmp;
use App\Models\Knmp\KonstruksiKnmp;
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
            $query = Knmp::with(['tahapUsulan', 'tahapSurvey', 'tahapDed', 'tahapLelang', 'konstruksiKnmp.penyediaJasa', 'tahapSerahTerima'])
                ->where('tahap_saat_ini', $stage);
            if (\Illuminate\Support\Facades\Auth::user()->isUserDaerah()) {
                $query->where('kabupaten', 'LIKE', '%' . \Illuminate\Support\Facades\Auth::user()->kabupaten . '%');
            }
            return $query;
        };

        $formatItem = function($k) {
            $kons = $k->konstruksiKnmp;
            $progres = 0;
            $rencana = 0;
            $konstruktor = '-';
            if ($kons) {
                $konstruktor = $kons->penyediaJasa ? $kons->penyediaJasa->nama : '-';
                $lastProgres = \Illuminate\Support\Facades\DB::connection('mysql_knmp')
                    ->table('progres_harian')
                    ->where('knmp_konstruksi_id', $kons->id)
                    ->orderBy('tanggal', 'desc')
                    ->first();
                $progres = $lastProgres ? round($lastProgres->progres, 2) : 0;

                if ($kons->tanggal_mulai) {
                    $tanggalMulai = \Carbon\Carbon::parse($kons->tanggal_mulai);
                    $targetDate = \Carbon\Carbon::now();
                    $daysDiff = $tanggalMulai->diffInDays($targetDate, false);
                    $currentWeek = $daysDiff < 0 ? 1 : floor($daysDiff / 7) + 1;
                    
                    $tahapKonstruksi = \Illuminate\Support\Facades\DB::connection('mysql_knmp')->table('tahap_konstruksi')
                        ->where('knmp_konstruksi_id', $kons->id)
                        ->where('periode_mingguan', '<=', $currentWeek)
                        ->orderBy('periode_mingguan', 'desc')
                        ->first();
                        
                    if ($tahapKonstruksi) {
                        $val = (float)$tahapKonstruksi->bobot_rencana_kumulatif;
                        if ($val > 100) {
                            $val = $val / 1000;
                        }
                        $rencana = round($val, 2);
                    }
                }
            }
            $deviasi = round($progres - $rencana, 2);

            $kurvaS = [];
            if ($kons) {
                $tahaps = \Illuminate\Support\Facades\DB::connection('mysql_knmp')
                    ->table('tahap_konstruksi')
                    ->where('knmp_konstruksi_id', $kons->id)
                    ->orderBy('periode_mingguan', 'asc')
                    ->get();

                $maxWeek = $tahaps->max('periode_mingguan') ?: 6;
                if (isset($currentWeek) && $currentWeek > $maxWeek) {
                    $maxWeek = $currentWeek;
                }
                if ($maxWeek < 4) $maxWeek = 4;
                if ($maxWeek > 20) $maxWeek = 20;

                for ($w = 1; $w <= $maxWeek; $w++) {
                    $tk = $tahaps->firstWhere('periode_mingguan', $w);
                    $rencanaVal = 0;
                    if ($tk) {
                        $val = (float)$tk->bobot_rencana_kumulatif;
                        $rencanaVal = round($val > 100 ? $val / 1000 : $val, 2);
                    } else {
                        $lastTk = $tahaps->where('periode_mingguan', '<', $w)->last();
                        if ($lastTk) {
                            $val = (float)$lastTk->bobot_rencana_kumulatif;
                            $rencanaVal = round($val > 100 ? $val / 1000 : $val, 2);
                        } else {
                            $rencanaVal = round(($w / $maxWeek) * 100, 2);
                        }
                    }

                    $realisasiVal = null;
                    if (!isset($currentWeek) || $w <= $currentWeek) {
                        $weekEndDate = $kons->tanggal_mulai 
                            ? \Carbon\Carbon::parse($kons->tanggal_mulai)->addDays($w * 7)->endOfDay()->format('Y-m-d H:i:s')
                            : \Carbon\Carbon::now()->format('Y-m-d H:i:s');

                        $ph = \Illuminate\Support\Facades\DB::connection('mysql_knmp')
                            ->table('progres_harian')
                            ->where('knmp_konstruksi_id', $kons->id)
                            ->where('tanggal', '<=', $weekEndDate)
                            ->orderBy('tanggal', 'desc')
                            ->first();

                        if ($ph) {
                            $realisasiVal = round((float)$ph->progres, 2);
                        } elseif ($w == 1 && $progres > 0 && !$kons->tanggal_mulai) {
                            $realisasiVal = round($progres, 2);
                        } else {
                            $realisasiVal = 0;
                        }
                    }

                    $kurvaS[] = [
                        'minggu' => 'M' . $w,
                        'label' => 'Minggu ke-' . $w,
                        'rencana' => $rencanaVal,
                        'realisasi' => $realisasiVal,
                    ];
                }
            } else {
                $kurvaS = [
                    ['minggu' => 'M1', 'label' => 'Minggu ke-1', 'rencana' => 25, 'realisasi' => 20],
                    ['minggu' => 'M2', 'label' => 'Minggu ke-2', 'rencana' => 50, 'realisasi' => 45],
                    ['minggu' => 'M3', 'label' => 'Minggu ke-3', 'rencana' => 75, 'realisasi' => null],
                    ['minggu' => 'M4', 'label' => 'Minggu ke-4', 'rencana' => 100, 'realisasi' => null],
                ];
            }

            $fotosBefore = \Illuminate\Support\Facades\DB::connection('mysql_knmp')
                ->table('bukti_uploads')
                ->where('knmp_id', $k->id)
                ->where('kondisi', 'before')
                ->get()
                ->map(fn($f) => ['url' => asset('storage/' . $f->path_file), 'nama' => $f->nama_file])->values()->all();

            $fotosAfter = \Illuminate\Support\Facades\DB::connection('mysql_knmp')
                ->table('bukti_uploads')
                ->where('knmp_id', $k->id)
                ->where('kondisi', 'after')
                ->get()
                ->map(fn($f) => ['url' => asset('storage/' . $f->path_file), 'nama' => $f->nama_file])->values()->all();

            $tahapDed = $k->tahapDed;
            $dokumenDedUrl = null;
            if ($tahapDed && $tahapDed->file_url) {
                $dokumenDedUrl = filter_var($tahapDed->file_url, FILTER_VALIDATE_URL) ? $tahapDed->file_url : asset('storage/' . $tahapDed->file_url);
            }

            return [
                'id' => $k->id,
                'lokasi' => $k->nama,
                'daerah' => ($k->kabupaten ? $k->kabupaten : '-') . ', ' . ($k->provinsi ? $k->provinsi : '-'),
                'provinsi' => $k->provinsi ?: '-',
                'kabupaten' => $k->kabupaten ?: '-',
                'kecamatan' => $k->kecamatan ?: '-',
                'desa' => $k->desa ?: '-',
                'latitude' => $k->latitude ?: '-',
                'longitude' => $k->longitude ?: '-',
                'koordinat' => $k->latitude && $k->longitude ? $k->latitude . ', ' . $k->longitude : '-',
                'statusHub' => $k->status ?: 'Penyangga',
                'batch_id' => $k->batch_id ?: '-',
                'tahap_saat_ini' => $k->tahap_saat_ini,
                'created_at' => $k->created_at ? $k->created_at->format('d M Y, H:i') : '-',
                'konstruktor' => $konstruktor,
                'rencana' => $rencana,
                'progres' => $progres,
                'deviasi' => $deviasi,
                'kurvaS' => $kurvaS,
                'nilaiSkala' => $tahapDed ? '100/100 (Disetujui)' : '100/100 (Disetujui)',
                'namaKonstruksi' => $k->nama . ' - Pembangunan Fisik',
                'tahapUsulan' => [
                    'tanggal' => $k->tahapUsulan?->tanggal ?: '-',
                    'catatan' => $k->tahapUsulan?->catatan ?: '-',
                ],
                'tahapSurvey' => [
                    'tanggal' => $k->tahapSurvey?->tanggal ?: '-',
                    'catatan' => $k->tahapSurvey?->catatan ?: '-',
                ],
                'tahapDed' => [
                    'nomor_dokumen' => $k->tahapDed?->nomor_dokumen ?: '-',
                    'tanggal_pengesahan' => $k->tahapDed?->tanggal_pengesahan ?: '-',
                    'file_url' => $dokumenDedUrl,
                    'catatan' => $k->tahapDed?->catatan ?: '-',
                ],
                'tahapLelang' => [
                    'tanggal_penetapan' => $k->tahapLelang?->tanggal_penetapan ?: '-',
                    'catatan' => $k->tahapLelang?->catatan ?: '-',
                ],
                'tahapSerahTerima' => [
                    'nomor_kontrak' => $k->tahapSerahTerima?->nomor_kontrak ?: '-',
                    'tanggal_serah' => $k->tahapSerahTerima?->tanggal_serah ?: '-',
                    'catatan' => $k->tahapSerahTerima?->catatan ?: '-',
                ],
                'fotosBefore' => $fotosBefore,
                'fotosAfter' => $fotosAfter,
            ];
        };

        $usulanData = $baseQuery('usulan')->get()->map($formatItem);
        $surveiData = $baseQuery('survey')->get()->map($formatItem);
        $dedData = $baseQuery('ded')->get()->map($formatItem);
        $lelangData = $baseQuery('lelang')->get()->map($formatItem);
        $konstruksiData = $baseQuery('konstruksi')->get()->map($formatItem)->sortByDesc('progres')->values();
        $serahTerimaData = $baseQuery('serah_terima')->get()->map($formatItem);

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
        \Illuminate\Support\Facades\Gate::authorize('manage-operasional');

        $request->validate([
            'knmp_id' => 'required|exists:mysql_knmp.knmp,id',
            'foto_before' => 'nullable|array|max:2',
            'foto_before.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'foto_after' => 'nullable|array|max:2',
            'foto_after.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'foto_before_0' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_before_1' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_after_0' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_after_1' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if (!$request->hasFile('foto_before') && !$request->hasFile('foto_after') && !$request->hasFile('foto_before_0') && !$request->hasFile('foto_before_1') && !$request->hasFile('foto_after_0') && !$request->hasFile('foto_after_1')) {
            return back()->with('error', 'Silakan pilih setidaknya satu foto untuk diunggah atau diperbarui.');
        }

        $now = now();
        $existingBefore = \Illuminate\Support\Facades\DB::connection('mysql_knmp')
            ->table('bukti_uploads')
            ->where('knmp_id', $request->knmp_id)
            ->where('kondisi', 'before')
            ->orderBy('id', 'asc')
            ->get();

        $existingAfter = \Illuminate\Support\Facades\DB::connection('mysql_knmp')
            ->table('bukti_uploads')
            ->where('knmp_id', $request->knmp_id)
            ->where('kondisi', 'after')
            ->orderBy('id', 'asc')
            ->get();

        // Process slot-specific Before inputs (foto_before_0, foto_before_1)
        foreach (['foto_before_0' => 0, 'foto_before_1' => 1] as $inputName => $slotIdx) {
            if ($request->hasFile($inputName)) {
                $file = $request->file($inputName);
                $path = $file->store('bukti_uploads', 'public');
                $data = [
                    'knmp_id' => $request->knmp_id,
                    'kondisi' => 'before',
                    'nama_file' => $file->getClientOriginalName(),
                    'path_file' => $path,
                    'tipe_file' => $file->getMimeType(),
                    'ukuran_file' => $file->getSize(),
                    'updated_at' => $now,
                ];
                if (isset($existingBefore[$slotIdx])) {
                    \Illuminate\Support\Facades\DB::connection('mysql_knmp')->table('bukti_uploads')
                        ->where('id', $existingBefore[$slotIdx]->id)
                        ->update($data);
                } else {
                    $data['created_at'] = $now;
                    \Illuminate\Support\Facades\DB::connection('mysql_knmp')->table('bukti_uploads')
                        ->insert($data);
                }
            }
        }

        // Process array Before inputs (foto_before[]) fallback
        if ($request->hasFile('foto_before')) {
            foreach ($request->file('foto_before') as $idx => $file) {
                $path = $file->store('bukti_uploads', 'public');
                $data = [
                    'knmp_id' => $request->knmp_id,
                    'kondisi' => 'before',
                    'nama_file' => $file->getClientOriginalName(),
                    'path_file' => $path,
                    'tipe_file' => $file->getMimeType(),
                    'ukuran_file' => $file->getSize(),
                    'updated_at' => $now,
                ];
                if (isset($existingBefore[$idx])) {
                    \Illuminate\Support\Facades\DB::connection('mysql_knmp')->table('bukti_uploads')
                        ->where('id', $existingBefore[$idx]->id)
                        ->update($data);
                } else {
                    $data['created_at'] = $now;
                    \Illuminate\Support\Facades\DB::connection('mysql_knmp')->table('bukti_uploads')
                        ->insert($data);
                }
            }
        }

        // Process slot-specific After inputs (foto_after_0, foto_after_1)
        foreach (['foto_after_0' => 0, 'foto_after_1' => 1] as $inputName => $slotIdx) {
            if ($request->hasFile($inputName)) {
                $file = $request->file($inputName);
                $path = $file->store('bukti_uploads', 'public');
                $data = [
                    'knmp_id' => $request->knmp_id,
                    'kondisi' => 'after',
                    'nama_file' => $file->getClientOriginalName(),
                    'path_file' => $path,
                    'tipe_file' => $file->getMimeType(),
                    'ukuran_file' => $file->getSize(),
                    'updated_at' => $now,
                ];
                if (isset($existingAfter[$slotIdx])) {
                    \Illuminate\Support\Facades\DB::connection('mysql_knmp')->table('bukti_uploads')
                        ->where('id', $existingAfter[$slotIdx]->id)
                        ->update($data);
                } else {
                    $data['created_at'] = $now;
                    \Illuminate\Support\Facades\DB::connection('mysql_knmp')->table('bukti_uploads')
                        ->insert($data);
                }
            }
        }

        // Process array After inputs (foto_after[]) fallback
        if ($request->hasFile('foto_after')) {
            foreach ($request->file('foto_after') as $idx => $file) {
                $path = $file->store('bukti_uploads', 'public');
                $data = [
                    'knmp_id' => $request->knmp_id,
                    'kondisi' => 'after',
                    'nama_file' => $file->getClientOriginalName(),
                    'path_file' => $path,
                    'tipe_file' => $file->getMimeType(),
                    'ukuran_file' => $file->getSize(),
                    'updated_at' => $now,
                ];
                if (isset($existingAfter[$idx])) {
                    \Illuminate\Support\Facades\DB::connection('mysql_knmp')->table('bukti_uploads')
                        ->where('id', $existingAfter[$idx]->id)
                        ->update($data);
                } else {
                    $data['created_at'] = $now;
                    \Illuminate\Support\Facades\DB::connection('mysql_knmp')->table('bukti_uploads')
                        ->insert($data);
                }
            }
        }

        return back()->with('success', 'Foto dokumentasi berhasil diunggah dan disimpan.');
    }

    public function moveStage(Request $request, $program)
    {
        $this->checkAuth();
        \Illuminate\Support\Facades\Gate::authorize('manage-operasional');
        
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
        \Illuminate\Support\Facades\Gate::authorize('manage-operasional');
        
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
        \Illuminate\Support\Facades\Gate::authorize('import-progres');
        
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls|max:5120'
        ]);

        Excel::import(new ProgresImport, $request->file('file_excel'));

        return back()->with('success', 'Data progres harian berhasil diimport.');
    }
}

<?php

namespace App\Http\Controllers\Knmp\Dashboard;

use App\Http\Controllers\ProgramBaseController;
use Illuminate\Http\Request;
use App\Models\Knmp;

class SiklusController extends ProgramBaseController
{
    public function index($program, $menu = null)
    {
        $this->checkAuth();
        $activeProgram = $this->formatProgramName($program);
        
        $requestedBatchId = request('batch_id');

        $queryKnmp = \App\Models\Knmp::query();
        if (\Illuminate\Support\Facades\Auth::user()->isUserDaerah()) {
            $queryKnmp->where('kabupaten', 'LIKE', '%' . \Illuminate\Support\Facades\Auth::user()->kabupaten . '%');
        }
        if ($requestedBatchId) {
            $queryKnmp->where('batch_id', $requestedBatchId);
        }

        $totalLokasi = (clone $queryKnmp)->count();
        $totalSelesai = (clone $queryKnmp)->where('tahap_saat_ini', 'serah_terima')->count();
        $dalamPembangunan = (clone $queryKnmp)->where('tahap_saat_ini', 'konstruksi')->count();

        $pipeline = [
            'usulan' => (clone $queryKnmp)->where('tahap_saat_ini', 'usulan')->count(),
            'survei' => (clone $queryKnmp)->where('tahap_saat_ini', 'survey')->count(),
            'ded' => (clone $queryKnmp)->where('tahap_saat_ini', 'ded')->count(),
            'lelang' => (clone $queryKnmp)->where('tahap_saat_ini', 'lelang')->count(),
            'konstruksi' => $dalamPembangunan,
            'serah_terima' => $totalSelesai,
        ];

        // Pipeline Pengajuan from CalonLokasi
        $calonQuery = \App\Models\CalonLokasi::query();
        if (\Illuminate\Support\Facades\Auth::user()->isUserDaerah()) {
            $calonQuery->where('kabupaten', 'LIKE', '%' . \Illuminate\Support\Facades\Auth::user()->kabupaten . '%');
        }
        if ($requestedBatchId) {
            // Adjust this if CalonLokasi also filters by batch_id (assuming it doesn't strictly have batch_id or does)
            // But since CalonLokasi might not have batch_id directly, we might need to skip or handle gracefully.
            // Let's check if batch_id exists, otherwise skip filtering for CalonLokasi.
            if (\Illuminate\Support\Facades\Schema::connection('mysql_knmp')->hasColumn('calon_lokasi', 'batch_id')) {
                $calonQuery->where('batch_id', $requestedBatchId);
            }
        }
        
        $totalPengajuan = (clone $calonQuery)->count();
        $pipelinePengajuan = [
            'pengajuan' => (clone $calonQuery)->where('status_tahapan', 'pengajuan')->count(),
            'verif_admin' => (clone $calonQuery)->where('status_tahapan', 'verif_admin')->count(),
            'ba_aktivasi' => (clone $calonQuery)->where('status_tahapan', 'ba_aktivasi')->count(),
            'verif_teknis' => (clone $calonQuery)->where('status_tahapan', 'verif_teknis')->count(),
            'ba_calon' => (clone $calonQuery)->where('status_tahapan', 'ba_calon')->count(),
            'penetapan' => (clone $calonQuery)->where('status_tahapan', 'penetapan')->count(),
        ];

        $operasionalStages = ['survey', 'ded', 'lelang', 'konstruksi'];
        $operasionalQuery = (clone $queryKnmp)->whereIn('tahap_saat_ini', $operasionalStages);
        
        $totalOperasional = (clone $operasionalQuery)->count();
        $hubCount = (clone $operasionalQuery)->where('status', 'Hub')->count();
        $penyanggaCount = (clone $operasionalQuery)->where(function($q) {
            $q->where('status', 'Penyangga')->orWhereNull('status')->orWhere('status', '');
        })->count();
        
        // Calculate average progress from konstruksi items
        $konstruksiIds = (clone $queryKnmp)->where('tahap_saat_ini', 'konstruksi')
            ->with('konstruksiKnmp')->get()->pluck('konstruksiKnmp.id')->filter();
            
        $avgProgresOperasional = 0;
        if ($konstruksiIds->isNotEmpty()) {
            $latestProgresSubquery = \Illuminate\Support\Facades\DB::connection('mysql_knmp')
                ->table('progres_harian')
                ->select('knmp_konstruksi_id', \Illuminate\Support\Facades\DB::raw('MAX(tanggal) as max_tanggal'))
                ->whereIn('knmp_konstruksi_id', $konstruksiIds)
                ->groupBy('knmp_konstruksi_id');

            $progresQuery = \Illuminate\Support\Facades\DB::connection('mysql_knmp')
                ->table('progres_harian as ph')
                ->joinSub($latestProgresSubquery, 'latest', function ($join) {
                    $join->on('ph.knmp_konstruksi_id', '=', 'latest.knmp_konstruksi_id')
                         ->on('ph.tanggal', '=', 'latest.max_tanggal');
                })
                ->avg('ph.progres');

            $avgProgresOperasional = $progresQuery ?? 0;
        }
        
        // Count active stages in operasional
        $tahapAktifOps = (clone $operasionalQuery)
            ->select('tahap_saat_ini', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('tahap_saat_ini')
            ->get()
            ->pluck('count', 'tahap_saat_ini')
            ->toArray();

        $batches = \Illuminate\Support\Facades\DB::connection('mysql_knmp')->table('batch')->get()->map(function($b) {
            return [
                'id' => $b->id,
                'name' => $b->nama_tahap . ' - ' . $b->tahun
            ];
        })->keyBy('id');

        $narasi = "Pada <strong>Siklus Pengajuan Calon Lokasi</strong>, saat ini terdapat <span class='font-bold text-blue-500'>{$totalPengajuan} usulan</span> yang sedang dalam proses. Memasuki <strong>Siklus Usulan & Konstruksi KNMP</strong>, dari keseluruhan <span class='font-bold text-teal-light dark:text-teal-400'>{$totalLokasi} lokasi</span> yang telah ditetapkan, sebanyak <span class='font-bold text-warning dark:text-amber-500'>{$dalamPembangunan} lokasi</span> sedang aktif dalam tahap konstruksi, dan <span class='font-bold text-success'>{$totalSelesai} lokasi</span> telah berhasil diserahterimakan. Adapun khusus pada <strong>Fase Konstruksi</strong>, {$dalamPembangunan} proyek yang sedang berjalan saat ini mencatatkan rata-rata progres fisik sebesar <span class='font-bold'>".number_format($avgProgresOperasional, 1)."%</span>.";

        return view('programs.knmp.dashboard.siklus', [
            'activeModule' => 'Dashboard',
            'activeProgram' => $activeProgram,
            'stats' => [
                'total_lokasi' => $totalLokasi,
                'pipeline' => $pipeline,
                'pipeline_pengajuan' => $pipelinePengajuan,
                'operasional' => [
                    'total' => $totalOperasional,
                    'hub' => $hubCount,
                    'penyangga' => $penyanggaCount,
                    'avg_progres' => round($avgProgresOperasional, 1),
                    'tahap_aktif' => $tahapAktifOps,
                ],
                'filter_batches' => $batches->values()->all(),
                'narasi' => $narasi
            ]
        ]);
    }
}

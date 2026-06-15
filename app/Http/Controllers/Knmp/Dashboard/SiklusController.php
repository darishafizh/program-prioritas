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

        // Operasional data from knmp_proyek
        $operasionalQuery = \Illuminate\Support\Facades\DB::connection('mysql_knmp')
            ->table('knmp_proyek');
            
        // Note: knmp_proyek does not have batch_id or knmp_id, so it cannot be filtered by batch
        
        $totalOperasional = (clone $operasionalQuery)->count();
        $hubCount = (clone $operasionalQuery)->where('knmp_proyek.status_wilayah', 'Hub')->count();
        $penyanggaCount = (clone $operasionalQuery)->where('knmp_proyek.status_wilayah', 'Penyangga')->count();
        
        $avgProgresOperasional = $totalOperasional > 0 ? (clone $operasionalQuery)->avg('knmp_proyek.progres_fisik') : 0;
        
        // Count active stages in operasional
        $tahapAktifOps = (clone $operasionalQuery)
            ->select('knmp_proyek.tahap_aktif', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('knmp_proyek.tahap_aktif')
            ->get()
            ->pluck('count', 'tahap_aktif')
            ->toArray();

        $batches = \Illuminate\Support\Facades\DB::connection('mysql_knmp')->table('batch')->get()->map(function($b) {
            return [
                'id' => $b->id,
                'name' => $b->nama_tahap . ' - ' . $b->tahun
            ];
        })->keyBy('id');

        $narasi = "Dashboard ini merangkum pergerakan siklus dari <span class='font-bold text-teal-light dark:text-teal-400'>{$totalLokasi} usulan lokasi</span> KNMP. Saat ini terdapat <span class='font-bold text-warning dark:text-amber-500'>{$dalamPembangunan} lokasi</span> yang telah memasuki tahap konstruksi dan <span class='font-bold text-success'>{$totalSelesai} lokasi</span> yang telah diserahterimakan. Pada fase operasional pasca-konstruksi, dari total {$totalOperasional} proyek aktif, {$hubCount} berstatus sebagai Hub dan {$penyanggaCount} sebagai Penyangga, dengan rata-rata progres operasional mencapai <span class='font-bold'>".number_format($avgProgresOperasional, 1)."%</span>.";

        return view('programs.knmp.dashboard.siklus', [
            'activeModule' => 'Dashboard',
            'activeProgram' => $activeProgram,
            'stats' => [
                'total_lokasi' => $totalLokasi,
                'pipeline' => $pipeline,
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

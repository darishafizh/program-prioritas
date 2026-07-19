<?php

namespace App\Http\Controllers\Knmp\Dashboard;

use App\Http\Controllers\ProgramBaseController;
use Illuminate\Http\Request;
use App\Models\Knmp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KonstruksiKnmpController extends ProgramBaseController
{
    public function index($program)
    {
        $this->checkAuth();
        $activeProgram = $this->formatProgramName($program);
        
        $requestedDate = request('date');
        $requestedBatchId = request('batch_id');
        $perPage = (int) request('per_page', 20);
        
        // ---------- Effective date ----------
        if (!$requestedDate) {
            $latestDateQuery = DB::connection('mysql_knmp')
                ->table('progres_harian')
                ->join('konstruksi_knmp', 'konstruksi_knmp.id', '=', 'progres_harian.knmp_konstruksi_id')
                ->join('knmp', 'knmp.id', '=', 'konstruksi_knmp.knmp_id');
                
            if ($requestedBatchId) {
                $latestDateQuery->where('knmp.batch_id', $requestedBatchId);
            }
            
            $latestDateRecord = $latestDateQuery->orderBy('progres_harian.tanggal', 'desc')->select('progres_harian.tanggal')->first();
            $effectiveDate = $latestDateRecord ? $latestDateRecord->tanggal : now()->format('Y-m-d');
        } else {
            $effectiveDate = $requestedDate;
        }

        // ---------- Base query: HANYA konstruksi ----------
        $baseQuery = Knmp::query()->where('tahap_saat_ini', 'konstruksi');
            
        if (Auth::user()->isUserDaerah()) {
            $baseQuery->where('kabupaten', 'LIKE', '%' . Auth::user()->kabupaten . '%');
        }
        if ($requestedBatchId) {
            $baseQuery->where('batch_id', $requestedBatchId);
        }

        $totalLokasi = (clone $baseQuery)->count();

        // ---------- Batches ----------
        $batches = DB::connection('mysql_knmp')->table('batch')->get()->map(function($b) {
            return [
                'id' => $b->id,
                'name' => $b->nama_tahap . ' - ' . $b->tahun,
            ];
        })->keyBy('id');

        // ---------- Ambil SEMUA data konstruksi (untuk stagnant global + scatter + avg) ----------
        $semuaKnmp = (clone $baseQuery)
            ->with('konstruksiKnmp.penyediaJasa', 'konstruksiKnmp.tahapKonstruksi')
            ->get();

        $konstruksiIds = $semuaKnmp->pluck('konstruksiKnmp.id')->filter()->toArray();
        $allProgresHarian = collect();
        $allTahapKonstruksi = collect();
        
        if (!empty($konstruksiIds)) {
            $allProgresHarian = DB::connection('mysql_knmp')
                ->table('progres_harian')
                ->whereIn('knmp_konstruksi_id', $konstruksiIds)
                ->whereDate('tanggal', '<=', $effectiveDate)
                ->orderBy('tanggal', 'desc')
                ->get()
                ->groupBy('knmp_konstruksi_id');
            
            $allTahapKonstruksi = DB::connection('mysql_knmp')
                ->table('tahap_konstruksi')
                ->whereIn('knmp_konstruksi_id', $konstruksiIds)
                ->orderBy('periode_mingguan', 'desc')
                ->get()
                ->groupBy('knmp_konstruksi_id');
        }

        // ---------- Hitung detail per KNMP ----------
        $allDetails = [];
        $totalProgres = 0;
        $countWithProgres = 0;

        foreach ($semuaKnmp as $k) {
            $kons = $k->konstruksiKnmp;
            $progres = 0;
            $rencana = 0;
            $deviasi = 0;
            $isStagnant = false;
            $daysStagnant = 0;

            if ($kons) {
                $progresHarianList = $allProgresHarian->get($kons->id, collect());
                $latestProgres = $progresHarianList->first();
                $progres = $latestProgres ? (float)$latestProgres->progres : 0;
                
                if ($kons->tanggal_mulai) {
                    $tanggalMulai = Carbon::parse($kons->tanggal_mulai);
                    $targetDate = Carbon::parse($effectiveDate);
                    $daysDiff = $tanggalMulai->diffInDays($targetDate, false);
                    $currentWeek = $daysDiff < 0 ? 1 : floor($daysDiff / 7) + 1;
                    
                    $tahapList = $allTahapKonstruksi->get($kons->id, collect());
                    $tahapKonstruksi = $tahapList->where('periode_mingguan', '<=', $currentWeek)->first();
                        
                    if ($tahapKonstruksi) {
                        $val = (float)$tahapKonstruksi->bobot_rencana_kumulatif;
                        if ($val > 100) { $val = $val / 1000; }
                        $rencana = round($val, 2);
                    }
                }
                
                $deviasi = round($progres - $rencana, 2);
                
                if ($latestProgres) {
                    $totalProgres += $progres;
                    $countWithProgres++;
                }
                
                if ($progres < 100 && $progresHarianList->count() > 0) {
                    $effectiveDateObj = Carbon::parse($effectiveDate);
                    $dateFirstAchieved = $progresHarianList->first()->tanggal;
                    
                    foreach ($progresHarianList as $p) {
                        if ((float)$p->progres == $progres) {
                            $dateFirstAchieved = $p->tanggal;
                        } else {
                            break;
                        }
                    }
                    
                    $daysStagnant = Carbon::parse($dateFirstAchieved)->diffInDays($effectiveDateObj);
                    if ($daysStagnant >= 5) {
                        $isStagnant = true;
                    }
                }
            }

            $batchName = $k->batch_id && $batches->has($k->batch_id) ? $batches[$k->batch_id]['name'] : '-';

            $allDetails[] = [
                'lokasi' => $k->nama,
                'daerah' => $k->status ?: 'Penyangga',
                'konstruktor' => $kons && $kons->penyediaJasa ? $kons->penyediaJasa->nama : '-',
                'progres' => round($progres, 1),
                'rencana' => round($rencana, 1),
                'deviasi' => round($deviasi, 1),
                'batch_name' => $batchName,
                'is_stagnant' => $isStagnant,
                'days_stagnant' => $daysStagnant,
            ];
        }

        $avgProgres = $countWithProgres > 0 ? round($totalProgres / $countWithProgres, 2) : 0;

        // ---------- Sortir & slicing ----------
        $sorted = collect($allDetails)->sortByDesc('progres')->values();
        $stagnantList = collect($allDetails)->where('is_stagnant', true)->sortByDesc('days_stagnant')->values()->all();

        // ---------- Pagination manual ----------
        $page = (int) request('page', 1);
        $total = $sorted->count();
        $paginatedItems = $sorted->forPage($page, $perPage)->values()->all();
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedItems, $total, $perPage, $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('programs.knmp.dashboard.partials.konstruksi', [
            'activeModule' => 'Dashboard',
            'activeProgram' => $activeProgram,
            'paginatedDetails' => $paginatedItems,
            'paginator' => $paginator,
            'perPage' => $perPage,
            'stagnantList' => $stagnantList,
            'allDetails' => $sorted->all(), // untuk scatter plot
            'stats' => [
                'total_lokasi' => $totalLokasi,
                'dalam_pembangunan' => $totalLokasi,
                'rata_progres' => $avgProgres,
                'filter_batches' => $batches->values()->all(),
            ],
        ]);
    }
}
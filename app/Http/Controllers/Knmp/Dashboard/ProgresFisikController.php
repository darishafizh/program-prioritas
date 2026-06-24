<?php

namespace App\Http\Controllers\Knmp\Dashboard;

use App\Http\Controllers\ProgramBaseController;
use Illuminate\Http\Request;
use App\Models\Knmp;

class ProgresFisikController extends ProgramBaseController
{
    public function index($program, $menu = null)
    {
        $this->checkAuth();
        $activeProgram = $this->formatProgramName($program);
        $requestedDate = request('date');
        $requestedBatchId = request('batch_id');
        
        if (!$requestedDate) {
            $latestDateQuery = \Illuminate\Support\Facades\DB::connection('mysql_knmp')
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

        $queryKnmp = \App\Models\Knmp::query();
        if (\Illuminate\Support\Facades\Auth::user()->isUserDaerah()) {
            $queryKnmp->where('kabupaten', \Illuminate\Support\Facades\Auth::user()->kabupaten);
        }
        if ($requestedBatchId) {
            $queryKnmp->where('batch_id', $requestedBatchId);
        }

        $totalLokasi = (clone $queryKnmp)->count();
        $totalSelesai = (clone $queryKnmp)->where('tahap_saat_ini', 'serah_terima')->count();
        $dalamPembangunan = (clone $queryKnmp)->where('tahap_saat_ini', 'konstruksi')->count();
        
        $avgProgres = 0;
        $konstruksiDetails = [];
        $kesehatan = ['sesuai' => 0, 'ringan' => 0, 'kritis' => 0];

        $batches = \Illuminate\Support\Facades\DB::connection('mysql_knmp')->table('batch')->get()->map(function($b) {
            return [
                'id' => $b->id,
                'name' => $b->nama_tahap . ' - ' . $b->tahun
            ];
        })->keyBy('id');

        if ($totalLokasi > 0) {
            $semuaKnmp = (clone $queryKnmp)->with('konstruksiKnmp.penyediaJasa', 'konstruksiKnmp.tahapKonstruksi')
                ->get();
            
            $totalProgres = 0;
            $countWithProgres = 0;

            foreach($semuaKnmp as $k) {
                $kons = $k->konstruksiKnmp;
                $progres = 0;
                $rencana = 0;
                $deviasi = 0;
                $latestProgres = null;
                $isStagnant = false;
                $daysStagnant = 0;

                if ($kons) {
                    $query = \Illuminate\Support\Facades\DB::connection('mysql_knmp')
                        ->table('progres_harian')
                        ->where('knmp_konstruksi_id', $kons->id);
                        
                    if ($effectiveDate) {
                        $query->whereDate('tanggal', '<=', $effectiveDate);
                    }
                    
                    $allProgres = $query->orderBy('tanggal', 'desc')->get();
                    $latestProgres = $allProgres->first();
                    $progres = $latestProgres ? (float)$latestProgres->progres : 0;
                    
                    if ($kons->tanggal_mulai) {
                        $tanggalMulai = \Carbon\Carbon::parse($kons->tanggal_mulai);
                        $targetDate = \Carbon\Carbon::parse($effectiveDate);
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
                    
                    $deviasi = round($progres - $rencana, 2);
                    
                    if ($deviasi >= 0) $kesehatan['sesuai']++;
                    elseif ($deviasi >= -5) $kesehatan['ringan']++;
                    else $kesehatan['kritis']++;

                    if ($latestProgres) {
                        $totalProgres += $progres;
                        $countWithProgres++;
                    }
                    
                    if ($k->tahap_saat_ini === 'konstruksi' && $progres < 100 && $latestProgres) {
                        $effectiveDateObj = \Carbon\Carbon::parse($effectiveDate);
                        $dateFirstAchieved = $latestProgres->tanggal;
                        
                        foreach($allProgres as $pRecord) {
                            if ((float)$pRecord->progres === $progres) {
                                $dateFirstAchieved = $pRecord->tanggal;
                            } else {
                                break;
                            }
                        }
                        
                        $daysStagnant = \Carbon\Carbon::parse($dateFirstAchieved)->diffInDays($effectiveDateObj);
                        if ($daysStagnant >= 5) {
                            $isStagnant = true;
                        }
                    }
                }

                $batchName = $k->batch_id && $batches->has($k->batch_id) ? $batches[$k->batch_id]['name'] : '-';

                $konstruksiDetails[] = [
                    'lokasi' => $k->nama,
                    'daerah' => $k->status ?: 'Penyangga',
                    'konstruktor' => $kons && $kons->penyediaJasa ? $kons->penyediaJasa->nama : '-',
                    'progres' => round($progres, 1),
                    'rencana' => round($rencana, 1),
                    'deviasi' => round($deviasi, 1),
                    'tahap' => $k->tahap_saat_ini,
                    'batch_name' => $batchName,
                    'is_stagnant' => $isStagnant,
                    'days_stagnant' => $daysStagnant,
                ];
            }

            if ($countWithProgres > 0) {
                $avgProgres = round($totalProgres / $countWithProgres, 1);
            }
        }

        $sortedByProgres = collect($konstruksiDetails)->sortByDesc('progres')->values();
        $top10 = $sortedByProgres->take(10);
        $bottom10 = collect($konstruksiDetails)->sortBy('progres')->values()->take(10);
        
        $stagnantList = collect($konstruksiDetails)->where('is_stagnant', true)->sortByDesc('days_stagnant')->values()->all();
        
        $allTableData = $sortedByProgres->all();

        $mapQuery = \App\Models\Knmp::select('nama', 'latitude', 'longitude', 'status')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');
            
        if (\Illuminate\Support\Facades\Auth::user()->isUserDaerah()) {
            $mapQuery->where('kabupaten', \Illuminate\Support\Facades\Auth::user()->kabupaten);
        }
            
        if ($requestedBatchId) {
            $mapQuery->where('batch_id', $requestedBatchId);
        }
        
        $mapLocations = $mapQuery->get();
            
        $regionBarat = 0;
        $regionTengah = 0;
        $regionTimur = 0;
        
        foreach ($mapLocations as $loc) {
            if ($loc->longitude < 116) {
                $regionBarat++;
            } elseif ($loc->longitude >= 116 && $loc->longitude < 124) {
                $regionTengah++;
            } else {
                $regionTimur++;
            }
        }

        $narasi = "Sejauh ini, progres program Kampung Nelayan Merah Putih (KNMP) mencatatkan perkembangan yang terukur. Dari total <span class='text-teal-light dark:text-teal-400 font-bold'>{$totalLokasi} lokasi</span> yang terdaftar, terdapat <span class='text-warning dark:text-amber-500 font-bold'>{$dalamPembangunan} lokasi</span> yang saat ini sedang dalam tahap konstruksi aktif dengan rata-rata progres fisik mencapai <span class='font-bold'>{$avgProgres}%</span>. Selain itu, <span class='text-success font-bold'>{$totalSelesai} lokasi</span> telah berhasil diselesaikan dan diserahterimakan. Sebaran pembangunan mencakup {$regionBarat} lokasi di Wilayah Barat, {$regionTengah} di Tengah, dan {$regionTimur} di Timur Indonesia, menunjukkan komitmen pemerataan infrastruktur pesisir.";

        return view("programs.knmp.dashboard.index", [
            'activeModule' => 'Dashboard',
            'activeProgram' => $activeProgram,
            'stats' => [
                'total_lokasi' => $totalLokasi,
                'rata_progres' => $avgProgres,
                'total_selesai' => $totalSelesai,
                'dalam_pembangunan' => $dalamPembangunan,
                'kesehatan' => $kesehatan,
                'top10' => $top10,
                'bottom10' => $bottom10,
                'stagnant_list' => $stagnantList,
                'map_locations' => $mapLocations,
                'all_konstruksi' => $allTableData,
                'filter_batches' => $batches->values()->all(),
                'regions' => [
                    'barat' => $regionBarat,
                    'tengah' => $regionTengah,
                    'timur' => $regionTimur,
                ],
                'narasi' => $narasi
            ]
        ]);
    }
}

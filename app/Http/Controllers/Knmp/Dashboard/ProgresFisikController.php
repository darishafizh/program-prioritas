<?php

namespace App\Http\Controllers\Knmp\Dashboard;

use App\Http\Controllers\ProgramBaseController;
use Illuminate\Http\Request;
use App\Models\Knmp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProgresFisikController extends ProgramBaseController
{
    public function index($program, $menu = null)
    {
        $this->checkAuth();
        $activeProgram = $this->formatProgramName($program);

        // No filters for Dashboard Utama
        $maxTanggalProgres = DB::connection('mysql_knmp')->table('progres_harian')->max('tanggal');
        $effectiveDate = $maxTanggalProgres ?: now()->format('Y-m-d');

        $queryKnmp = Knmp::query()
            ->whereIn('tahap_saat_ini', ['konstruksi', 'serah_terima']);
            
        if (Auth::user()->isUserDaerah()) {
            $queryKnmp->where('kabupaten', 'LIKE', '%' . Auth::user()->kabupaten . '%');
        }


        $totalLokasi = (clone $queryKnmp)->count();
        $totalSelesai = (clone $queryKnmp)->where('tahap_saat_ini', 'serah_terima')->count();
        $dalamPembangunan = (clone $queryKnmp)->where('tahap_saat_ini', 'konstruksi')->count();
        
        $avgProgres = 0;
        
        $kesehatan = ['sesuai' => 0, 'ringan' => 0, 'kritis' => 0];

        $batches = DB::connection('mysql_knmp')->table('batch')->get()->map(function($b) {
            return [
                'id' => $b->id,
                'name' => 'Tahap ' . $b->nama_tahap . ' - ' . $b->tahun,
                'nama_tahap' => $b->nama_tahap,
                'tahun' => $b->tahun
            ];
        })->keyBy('id');

        if ($totalLokasi > 0) {
            $semuaKnmp = (clone $queryKnmp)->whereIn('tahap_saat_ini', ['konstruksi', 'serah_terima'])
                ->with('konstruksiKnmp.penyediaJasa', 'konstruksiKnmp.tahapKonstruksi')
                ->get();
            
            $totalProgres = 0;
            $countWithProgres = 0;

            // BULK FETCH FOR TABLE LOGIC
            $konstruksiIds = $semuaKnmp->pluck('konstruksiKnmp.id')->filter()->toArray();
            $allProgresHarian = collect();
            $allTahapKonstruksi = collect();
            
            if (!empty($konstruksiIds)) {
                $queryPh = DB::connection('mysql_knmp')
                    ->table('progres_harian')
                    ->whereIn('knmp_konstruksi_id', $konstruksiIds);
                if ($effectiveDate) {
                    $queryPh->whereDate('tanggal', '<=', $effectiveDate);
                }
                $allProgresHarian = $queryPh->orderBy('tanggal', 'desc')->get()->groupBy('knmp_konstruksi_id');
                
                $allTahapKonstruksi = DB::connection('mysql_knmp')
                    ->table('tahap_konstruksi')
                    ->whereIn('knmp_konstruksi_id', $konstruksiIds)
                    ->orderBy('periode_mingguan', 'desc')
                    ->get()
                    ->groupBy('knmp_konstruksi_id');
            }

            foreach($semuaKnmp as $k) {
                $kons = $k->konstruksiKnmp;
                $progres = 0;
                $rencana = 0;
                $deviasi = 0;
                $latestProgres = null;
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

                    if ($latestProgres && $k->tahap_saat_ini === 'konstruksi') {
                        $totalProgres += $progres;
                        $countWithProgres++;
                    }
                    
                    if ($k->tahap_saat_ini === 'konstruksi' && $progres < 100 && $latestProgres) {
                        $effectiveDateObj = Carbon::parse($effectiveDate);
                        $dateFirstAchieved = $latestProgres->tanggal;
                        
                        foreach($progresHarianList as $p) {
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

                

                
            }

            if ($countWithProgres > 0) {
                $avgProgres = round($totalProgres / $countWithProgres, 2);
            }
        }
        
        $activeBatchIds = (clone $queryKnmp)->whereNotNull('batch_id')->distinct()->pluck('batch_id')->toArray();
        
        $batchGroups = [];
        foreach($batches->values() as $b) {
            if (in_array($b['id'], $activeBatchIds)) {
                $batchGroups[$b['tahun']][] = $b['nama_tahap'];
            }
        }
        $batchStrings = [];
        foreach($batchGroups as $tahun => $tahaps) {
            $batchStrings[] = "Tahap " . implode(' & ', $tahaps) . " Tahun " . $tahun;
        }
        $labelTotalLokasi = implode(', ', $batchStrings);

        

        $mapQuery = Knmp::select('id', 'nama', 'provinsi', 'kabupaten', 'kecamatan', 'desa', 'latitude', 'longitude', 'status', 'tahap_saat_ini', 'batch_id', 'created_at')
            ->whereIn('tahap_saat_ini', ['konstruksi', 'serah_terima'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with(['tahapUsulan', 'tahapSurvey', 'tahapDed', 'tahapLelang', 'tahapSerahTerima', 'konstruksiKnmp.penyediaJasa']);
            
        if (Auth::user()->isUserDaerah()) {
            $mapQuery->where('kabupaten', 'LIKE', '%' . Auth::user()->kabupaten . '%');
        }
            

        
        $mapLocationsRaw = $mapQuery->get();
        
        $regionBarat = 0;
        $regionTengah = 0;
        $regionTimur = 0;
        
        $islands = [
            'sumatera' => 0,
            'jawa' => 0,
            'kalimantan' => 0,
            'sulawesi' => 0,
            'bali_nusra' => 0,
            'maluku_papua' => 0,
        ];

        foreach ($mapLocationsRaw as $loc) {
            if ($loc->longitude < 116) {
                $regionBarat++;
            } elseif ($loc->longitude >= 116 && $loc->longitude < 124) {
                $regionTengah++;
            } else {
                $regionTimur++;
            }

            $prov = strtolower($loc->provinsi ?? '');
            if (preg_match('/(aceh|sumatera|riau|jambi|bengkulu|bangka|belitung|lampung)/i', $prov)) {
                $islands['sumatera']++;
            } elseif (preg_match('/(jawa|jakarta|dki|banten|yogyakarta|diy)/i', $prov)) {
                $islands['jawa']++;
            } elseif (preg_match('/(kalimantan)/i', $prov)) {
                $islands['kalimantan']++;
            } elseif (preg_match('/(sulawesi|gorontalo)/i', $prov)) {
                $islands['sulawesi']++;
            } elseif (preg_match('/(bali|nusa tenggara|ntb|ntt)/i', $prov)) {
                $islands['bali_nusra']++;
            } elseif (preg_match('/(maluku|papua)/i', $prov)) {
                $islands['maluku_papua']++;
            } else {
                $lat = $loc->latitude;
                $lng = $loc->longitude;
                if ($lng < 106) {
                    $islands['sumatera']++;
                } elseif ($lng >= 106 && $lng < 115 && $lat < -5) {
                    $islands['jawa']++;
                } elseif ($lng >= 108 && $lng < 119 && $lat >= -5) {
                    $islands['kalimantan']++;
                } elseif ($lng >= 118 && $lng < 126 && $lat >= -6) {
                    $islands['sulawesi']++;
                } elseif ($lng >= 114 && $lng < 125 && $lat < -6) {
                    $islands['bali_nusra']++;
                } else {
                    $islands['maluku_papua']++;
                }
            }
        }

        $mapLocations = $this->buildMapPointsData($mapLocationsRaw, $batches);

        $narasi = "Sejauh ini, progres program Kampung Nelayan Merah Putih (KNMP) mencatatkan perkembangan yang terukur. Dari total <span class='text-teal-light dark:text-teal-400 font-bold'>{$totalLokasi} lokasi</span> yang terdaftar, terdapat <span class='text-warning dark:text-amber-500 font-bold'>{$dalamPembangunan} lokasi</span> yang saat ini sedang dalam tahap konstruksi aktif dengan rata-rata progres fisik mencapai <span class='font-bold'>{$avgProgres}%</span>. Selain itu, <span class='text-success font-bold'>{$totalSelesai} lokasi</span> telah berhasil diselesaikan dan diserahterimakan. Sebaran pembangunan mencakup {$regionBarat} lokasi di Wilayah Barat, {$regionTengah} di Tengah, dan {$regionTimur} di Timur Indonesia, menunjukkan komitmen pemerataan infrastruktur pesisir.";

        $masterSarpras = \Illuminate\Support\Facades\DB::connection('mysql_knmp')->table('master_sarpras')->get();
        
        $currentYear = \Carbon\Carbon::now()->year;
        $lokasiTahunIni = (clone $queryKnmp)->whereYear('created_at', $currentYear)->count();

        // --- PIPELINE CALCULATION FOR MODAL SIKLUS ---
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
        if (Auth::user()->isUserDaerah()) {
            $calonQuery->where('kabupaten', 'LIKE', '%' . Auth::user()->kabupaten . '%');
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
        
        $narasiSiklus = "Pada <strong>Siklus Pengajuan Calon Lokasi</strong>, saat ini terdapat <span class='font-bold text-blue-500'>{$totalPengajuan} usulan</span> yang sedang dalam proses. Memasuki <strong>Siklus Usulan & Konstruksi KNMP</strong>, dari keseluruhan <span class='font-bold text-teal-light dark:text-teal-400'>{$totalLokasi} lokasi</span> yang telah ditetapkan, sebanyak <span class='font-bold text-warning dark:text-amber-500'>{$dalamPembangunan} lokasi</span> sedang aktif dalam tahap konstruksi, dan <span class='font-bold text-success'>{$totalSelesai} lokasi</span> telah berhasil diserahterimakan. Adapun khusus pada <strong>Fase Konstruksi</strong>, {$dalamPembangunan} proyek yang sedang berjalan saat ini mencatatkan rata-rata progres fisik sebesar <span class='font-bold'>".number_format($avgProgres, 1)."%</span>.";

        return view("programs.knmp.dashboard.index", [
            'activeModule' => 'Dashboard',
            'activeProgram' => $activeProgram,
            'masterSarpras' => $masterSarpras,
            'stats' => [
                'total_lokasi' => $totalLokasi,
                'label_total_lokasi' => $labelTotalLokasi,
                'lokasi_tahun_ini' => $lokasiTahunIni,
                'rata_progres' => $avgProgres,
                'total_selesai' => $totalSelesai,
                'dalam_pembangunan' => $dalamPembangunan,
                'kesehatan' => $kesehatan,
                'pipeline' => $pipeline,
                'pipeline_pengajuan' => $pipelinePengajuan,
                'narasi_siklus' => $narasiSiklus,
                
                
                
                'map_locations' => $mapLocations,
                
                'filter_batches' => $batches->values()->all(),
                'regions' => [
                    'barat' => $regionBarat,
                    'tengah' => $regionTengah,
                    'timur' => $regionTimur,
                ],
                'islands' => $islands,
                'narasi' => $narasi
            ]
        ]);
    }


    private function buildMapPointsData($locationsData, $batches)
    {
        if ($locationsData->isEmpty()) {
            return [];
        }

        // 1. Bulk Load Bukti Uploads
        $knmpIds = $locationsData->pluck('id')->filter()->unique()->toArray();
        $allBuktiUploads = DB::connection('mysql_knmp')
            ->table('bukti_uploads')
            ->whereIn('knmp_id', $knmpIds)
            ->whereIn('kondisi', ['before', 'after'])
            ->get()
            ->groupBy('knmp_id');
            
        // 1.5 Bulk Load Profil KNMP
        $allProfilKnmp = DB::connection('mysql_knmp')
            ->table('profil_knmp')
            ->whereIn('knmp_id', $knmpIds)
            ->get()
            ->keyBy('knmp_id');
            
        // 1.6 Bulk Load API Sarpras
        $apiData = \Illuminate\Support\Facades\Cache::get('knmp_api_data');
        if (!is_array($apiData)) {
            try {
                $response = \Illuminate\Support\Facades\Http::withoutVerifying()
                    ->timeout(15)
                    ->get('https://kdmp.pdspkp.id/knmp/get_data.php');
                
                if ($response->successful()) {
                    $apiData = $response->json();
                    \Illuminate\Support\Facades\Cache::put('knmp_api_data', $apiData, 3600);
                } else {
                    \Illuminate\Support\Facades\Log::warning('API Sarpras failed with status: ' . $response->status());
                    $apiData = [];
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('API Sarpras Exception: ' . $e->getMessage());
                $apiData = [];
            }
        }

        $apiKeys = [
            'SPBN' => 'SPBUN_status',
            'Docking' => 'Docking nelayan_status',
            'Bengkel' => 'Bengkel Nelayan_status',
            'Waserda' => 'Waserda_status',
            'Pabrik Es' => 'Pabrik Es_status',
            'Cold Storage' => 'Cold Storage_status',
            'KDRN Dingin' => 'Kenderaan Berpendingin_status',
            'Sentra Kuliner' => 'Sentra Kuliner_status',
            'Kios Pemasaran' => 'Kios Pemasaran_status',
            'Kapal' => 'Kapal_status',
            'Mesin' => 'Mesin_Status',
            'Alat Tangkap' => 'Alat_tangkap_Status',
            'Cool Box' => 'cool_box_status',
            'Roda 3' => 'roda3_status',
        ];
        
        $masterSarpras = \Illuminate\Support\Facades\DB::connection('mysql_knmp')->table('master_sarpras')->get();

        // 2. Bulk Load Progres Harian & Tahap Konstruksi
        $konstruksiIds = $locationsData->pluck('konstruksiKnmp.id')->filter()->unique()->toArray();
        $allProgresHarian = collect();
        $allTahapKonstruksi = collect();
        
        if (!empty($konstruksiIds)) {
            $allProgresHarian = DB::connection('mysql_knmp')
                ->table('progres_harian')
                ->whereIn('knmp_konstruksi_id', $konstruksiIds)
                ->orderBy('tanggal', 'desc')
                ->get()
                ->groupBy('knmp_konstruksi_id');
                
            $allTahapKonstruksi = DB::connection('mysql_knmp')
                ->table('tahap_konstruksi')
                ->whereIn('knmp_konstruksi_id', $konstruksiIds)
                ->orderBy('periode_mingguan', 'asc')
                ->get()
                ->groupBy('knmp_konstruksi_id');
        }

        $mapPoints = [];
        $now = now();

        foreach ($locationsData as $loc) {
            $tahapNorm = strtolower(trim($loc->tahap_saat_ini));
            $isSerahTerima = in_array($tahapNorm, ['serah_terima', 'serah terima', 'selesai']);

            $progres = 0;
            $rencana = 0;
            $deviasi = 0;
            $kontraktor = '-';
            $currentWeek = null;
            $kurvaS = [];

            $kons = $loc->konstruksiKnmp;
            
            if ($isSerahTerima) {
                $progres = 100;
                $rencana = 100;
                $deviasi = 0;
            } elseif ($kons) {
                $kontraktor = $kons->penyediaJasa ? $kons->penyediaJasa->nama : '-';
                $progresHarianList = $allProgresHarian->get($kons->id, collect());
                $latestProgres = $progresHarianList->first();
                $progres = $latestProgres ? (float)$latestProgres->progres : 0;

                if ($kons->tanggal_mulai) {
                    $tanggalMulai = Carbon::parse($kons->tanggal_mulai);
                    $daysDiff = $tanggalMulai->diffInDays($now, false);
                    $currentWeek = $daysDiff < 0 ? 1 : floor($daysDiff / 7) + 1;
                    
                    $tahapList = $allTahapKonstruksi->get($kons->id, collect());
                    $tahapKonstruksi = $tahapList->where('periode_mingguan', '<=', $currentWeek)->last();
                    
                    if ($tahapKonstruksi) {
                        $val = (float)$tahapKonstruksi->bobot_rencana_kumulatif;
                        if ($val > 100) {
                            $val = $val / 1000;
                        }
                        $rencana = round($val, 2);
                    }
                }
                $deviasi = round($progres - $rencana, 2);
            }

            // Kurva S Logic using Memory Collections
            if ($kons) {
                $tahaps = $allTahapKonstruksi->get($kons->id, collect());
                if ($tahaps->count() > 0) {
                    $maxWeek = $tahaps->max('periode_mingguan');
                    if ($maxWeek < 4) $maxWeek = 4;
                    // if ($maxWeek > 20) $maxWeek = 20; // Removed per user request to allow >20 weeks

                    $progresHarianList = $allProgresHarian->get($kons->id, collect());

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
                                ? Carbon::parse($kons->tanggal_mulai)->addDays($w * 7)->endOfDay()
                                : Carbon::now();

                            $ph = $progresHarianList->where('tanggal', '<=', $weekEndDate->format('Y-m-d H:i:s'))->first();

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
                            'realisasi' => $realisasiVal
                        ];
                    }
                } else {
                    $kurvaS = [
                        ['minggu' => 'M1', 'label' => 'Minggu ke-1', 'rencana' => 25, 'realisasi' => round($progres * 0.25, 2)],
                        ['minggu' => 'M2', 'label' => 'Minggu ke-2', 'rencana' => 50, 'realisasi' => round($progres * 0.5, 2)],
                        ['minggu' => 'M3', 'label' => 'Minggu ke-3', 'rencana' => 75, 'realisasi' => round($progres * 0.75, 2)],
                        ['minggu' => 'M4', 'label' => 'Minggu ke-4', 'rencana' => 100, 'realisasi' => round($progres, 2)],
                    ];
                }
            } else {
                $kurvaS = [
                    ['minggu' => 'M1', 'label' => 'Minggu ke-1', 'rencana' => 25, 'realisasi' => 25],
                    ['minggu' => 'M2', 'label' => 'Minggu ke-2', 'rencana' => 50, 'realisasi' => 50],
                    ['minggu' => 'M3', 'label' => 'Minggu ke-3', 'rencana' => 75, 'realisasi' => 75],
                    ['minggu' => 'M4', 'label' => 'Minggu ke-4', 'rencana' => 100, 'realisasi' => 100],
                ];
            }

            $buktiUploads = $allBuktiUploads->get($loc->id, collect());
            $fotosBefore = $buktiUploads->where('kondisi', 'before')->map(fn($f) => ['url' => asset('storage/' . $f->path_file), 'nama' => $f->nama_file])->values()->all();
            $fotosAfter = $buktiUploads->where('kondisi', $isSerahTerima ? 'after' : 'progres')->map(fn($f) => ['url' => asset('storage/' . $f->path_file), 'nama' => $f->nama_file])->values()->all();

            $tahapDed = $loc->tahapDed;
            $dokumenDedUrl = null;
            if ($tahapDed && $tahapDed->file_url) {
                $dokumenDedUrl = filter_var($tahapDed->file_url, FILTER_VALIDATE_URL) ? $tahapDed->file_url : asset('storage/' . $tahapDed->file_url);
            }

            $latVal = is_numeric($loc->latitude) && $loc->latitude != 0 ? (float)$loc->latitude : null;
            $lngVal = is_numeric($loc->longitude) && $loc->longitude != 0 ? (float)$loc->longitude : null;
            if ($latVal === null || $lngVal === null) {
                if (stripos($loc->kabupaten ?? '', 'Alor') !== false || stripos($loc->nama ?? '', 'Adang') !== false) {
                    $latVal = -8.2198;
                    $lngVal = 124.5161;
                } else {
                    $latVal = -1.5;
                    $lngVal = 118.0;
                }
            }

            $batchName = $loc->batch_id && $batches->has($loc->batch_id) ? $batches[$loc->batch_id]['name'] : '-';

            $profil = $allProfilKnmp->get($loc->id);

            $mapPoints[] = [
                'id' => $loc->id,
                'nama' => $loc->nama,
                'provinsi' => $loc->provinsi ?? '-',
                'kabupaten' => $loc->kabupaten ?? '-',
                'kecamatan' => $loc->kecamatan ?? '-',
                'desa' => $loc->desa ?? '-',
                'latitude' => $latVal,
                'longitude' => $lngVal,
                'koordinat' => $loc->latitude && $loc->longitude ? $loc->latitude . ', ' . $loc->longitude : $latVal . ', ' . $lngVal,
                'status' => $loc->status ?: 'Penyangga',
                'statusHub' => $loc->status ?: 'Penyangga',
                'daerah' => ($loc->kabupaten ?: '-') . ', ' . ($loc->provinsi ?: '-'),
                'tahap' => $isSerahTerima ? 'serah_terima' : 'konstruksi',
                'tahap_label' => $isSerahTerima ? 'Serah Terima (Selesai)' : 'Konstruksi',
                'tahap_saat_ini' => $isSerahTerima ? 'serah_terima' : 'konstruksi',
                'progres' => round($progres, 1),
                'rencana' => round($rencana, 1),
                'deviasi' => round($deviasi, 1),
                'kontraktor' => $kontraktor,
                'konstruktor' => $kontraktor,
                'batch_name' => $batchName,
                
                // --- PROFIL KNMP ---
                'jumlah_kk' => $profil && $profil->jml_kk ? number_format($profil->jml_kk, 0, ',', '.') . ' KK' : '-',
                'jumlah_nelayan' => $profil && $profil->jml_nelayan ? number_format($profil->jml_nelayan, 0, ',', '.') . ' Orang' : '-',
                'komoditas' => $profil && $profil->komoditas ? $profil->komoditas : '-',
                'penjualan_ikan' => $profil && $profil->penjualan_ikan ? $profil->penjualan_ikan : '-',
                'jumlah_hari_melaut' => $profil && $profil->jml_hari_melaut ? $profil->jml_hari_melaut . ' Hari/bln' : '-',
                'pendapatan_rata_saat_ini' => $profil && $profil->pend_avg_saat_ini ? 'Rp ' . rtrim(rtrim(number_format($profil->pend_avg_saat_ini, 2, ',', '.'), '0'), ',') . ' Jt' : '-',
                'pendapatan_pasca_intervensi' => $profil && $profil->pend_avg_intervensi ? 'Rp ' . rtrim(rtrim(number_format($profil->pend_avg_intervensi, 2, ',', '.'), '0'), ',') . ' Jt' : '-',
                'vol_produksi_daerah' => $profil && $profil->vol_produksi_daerah ? number_format($profil->vol_produksi_daerah, 0, ',', '.') . ' Ton/thn' : '-',
                'nilai_produksi_daerah' => $profil && $profil->nilai_produksi_daerah ? 'Rp ' . rtrim(rtrim(number_format($profil->nilai_produksi_daerah, 2, ',', '.'), '0'), ',') . ' M' : '-',
                'vol_produksi_pasca_intervensi' => $profil && $profil->vol_produksi_intervensi ? number_format($profil->vol_produksi_intervensi, 0, ',', '.') . ' Ton/thn' : '-',
                'nilai_produksi_pasca_intervensi' => $profil && $profil->nilai_produksi_intervensi ? 'Rp ' . rtrim(rtrim(number_format($profil->nilai_produksi_intervensi, 2, ',', '.'), '0'), ',') . ' M' : '-',
                'serapan_tenaga_kerja' => $profil && $profil->serapan_tenaga_kerja ? number_format($profil->serapan_tenaga_kerja, 0, ',', '.') . ' Orang' : '-',
                // -------------------
                
                'created_at' => $loc->created_at ? $loc->created_at->format('d M Y, H:i') : '-',
                'kurvaS' => $kurvaS,
                'sarpras' => (function() use ($apiData, $apiKeys, $masterSarpras, $loc) {
                    $pointSarpras = [];
                    $apiItem = null;
                    if (is_array($apiData)) {
                        $normalize = function($str) {
                            return strtolower(preg_replace('/[^a-zA-Z0-9]/', '', str_replace(['KNMP', 'Desa'], '', $str)));
                        };
                        $locDesaNorm = $loc->desa ? $normalize($loc->desa) : '';
                        $locNamaNorm = $loc->nama ? $normalize($loc->nama) : '';

                        foreach ($apiData as $item) {
                            $itemDesaNorm = isset($item['Desa']) ? $normalize($item['Desa']) : '';
                            $itemNamaNorm = isset($item['KNMP']) ? $normalize($item['KNMP']) : '';
                            
                            $matchDesa = false;
                            if ($locDesaNorm && $itemDesaNorm) {
                                similar_text($locDesaNorm, $itemDesaNorm, $pctDesa);
                                if ($pctDesa >= 85 || strpos($itemDesaNorm, $locDesaNorm) !== false || strpos($locDesaNorm, $itemDesaNorm) !== false) {
                                    $matchDesa = true;
                                }
                            }
                            
                            $matchNama = false;
                            if ($locNamaNorm && $itemNamaNorm) {
                                similar_text($locNamaNorm, $itemNamaNorm, $pctNama);
                                if ($pctNama >= 85 || strpos($itemNamaNorm, $locNamaNorm) !== false || strpos($locNamaNorm, $itemNamaNorm) !== false) {
                                    $matchNama = true;
                                }
                            }

                            if ($matchDesa || $matchNama) {
                                $apiItem = $item;
                                break;
                            }
                        }
                    }

                    foreach ($masterSarpras as $s) {
                        $icon = \App\Http\Controllers\Knmp\Dashboard\OperasionalKnmpController::SARPRAS_ICONS[$s->nama] ?? 'fa-solid fa-box';
                        $statusSarpras = 0; // 0=Tidak Ada, 1=Belum Operasional, 2=Sudah Operasional
                        
                        if ($apiItem) {
                            $apiKey = $apiKeys[$s->nama] ?? null;
                            if ($apiKey && isset($apiItem[$apiKey])) {
                                if (stripos($apiItem[$apiKey], '2. Sudah Operasional') !== false) {
                                    $statusSarpras = 2;
                                } elseif (stripos($apiItem[$apiKey], '1. Belum Operasional') !== false) {
                                    $statusSarpras = 1;
                                }
                            }
                        }

                        $displayNama = $s->nama;
                        if ($s->nama === 'KDRN Dingin') {
                            $displayNama = 'Kendaraan Dingin';
                        } elseif ($s->nama === 'Waserda') {
                            $displayNama = 'Kios Perbekalan';
                        } elseif ($s->nama === 'Kios Pemasaran') {
                            $displayNama = 'Lapak Ikan';
                        }

                        $pointSarpras[] = [
                            'nama' => $displayNama,
                            'icon' => $icon,
                            'status' => $statusSarpras
                        ];
                    }
                    return $pointSarpras;
                })(),
                'fotosBefore' => $fotosBefore,
                'fotosAfter' => $fotosAfter,
                'tahapUsulan' => [
                    'tanggal' => $loc->tahapUsulan?->tanggal ?: '-',
                    'catatan' => $loc->tahapUsulan?->catatan ?: '-',
                ],
                'tahapSurvey' => [
                    'tanggal' => $loc->tahapSurvey?->tanggal ?: '-',
                    'catatan' => $loc->tahapSurvey?->catatan ?: '-',
                ],
                'tahapDed' => [
                    'nomor_dokumen' => $loc->tahapDed?->nomor_dokumen ?: '-',
                    'tanggal_pengesahan' => $loc->tahapDed?->tanggal_pengesahan ?: '-',
                    'file_url' => $dokumenDedUrl,
                    'catatan' => $loc->tahapDed?->catatan ?: '-',
                ],
                'tahapLelang' => [
                    'tanggal_penetapan' => $loc->tahapLelang?->tanggal_penetapan ?: '-',
                    'catatan' => $loc->tahapLelang?->catatan ?: '-',
                ],
                'tahapSerahTerima' => [
                    'nomor_kontrak' => $loc->tahapSerahTerima?->nomor_kontrak ?: '-',
                    'tanggal_serah' => $loc->tahapSerahTerima?->tanggal_serah ?: '-',
                    'catatan' => $loc->tahapSerahTerima?->catatan ?: '-',
                ],
            ];
        }

        return $mapPoints;
    }
}
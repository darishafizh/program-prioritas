<?php

namespace App\Http\Controllers\Knmp\Evaluasi;

use App\Http\Controllers\ProgramBaseController;
use App\Models\Knmp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProgresFisikEvaluasiController extends ProgramBaseController
{
    public function index(Request $request, $program)
    {
        $this->checkAuth();
        $activeProgram = $this->formatProgramName($program);

        $requestedBatchId = $request->query('batch_id');
        $requestedDate = $request->query('date');

        $batches = DB::connection('mysql_knmp')->table('batch')->get()->map(function ($b) {
            return ['id' => $b->id, 'name' => $b->nama_tahap . ' - ' . $b->tahun];
        })->values()->all();

        // Determine effective date
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

        // Query KNMP yang pernah/sedang di tahap konstruksi atau serah_terima
        $query = Knmp::whereIn('tahap_saat_ini', ['konstruksi', 'serah_terima'])
            ->with('konstruksiKnmp.penyediaJasa', 'konstruksiKnmp.tahapKonstruksi');

        if ($requestedBatchId) {
            $query->where('batch_id', $requestedBatchId);
        }
        
        if (\Illuminate\Support\Facades\Auth::user()->isUserDaerah()) {
            $query->where('kabupaten', 'LIKE', '%' . \Illuminate\Support\Facades\Auth::user()->kabupaten . '%');
        }

        $allKnmp = $query->get();

        $progresFisikData = [];
        $totalProgres = 0;
        $totalDeviasi = 0;
        $countWithProgres = 0;
        $totalKonstruksiAktif = 0;
        $totalSelesai = 0;

        foreach ($allKnmp as $knmp) {
            $kons = $knmp->konstruksiKnmp;
            $progres = 0;
            $rencana = 0;
            $deviasi = 0;
            $tanggalProgres = '-';
            $konstruktor = '-';

            if ($knmp->tahap_saat_ini === 'konstruksi') $totalKonstruksiAktif++;
            if ($knmp->tahap_saat_ini === 'serah_terima') $totalSelesai++;

            if ($kons) {
                $konstruktor = $kons->penyediaJasa ? $kons->penyediaJasa->nama : '-';

                $progresQuery = DB::connection('mysql_knmp')
                    ->table('progres_harian')
                    ->where('knmp_konstruksi_id', $kons->id);

                if ($effectiveDate) {
                    $progresQuery->whereDate('tanggal', '<=', $effectiveDate);
                }

                $latestProgres = $progresQuery->orderBy('tanggal', 'desc')->first();
                $progres = $latestProgres ? round((float) $latestProgres->progres, 1) : 0;
                $tanggalProgres = $latestProgres ? Carbon::parse($latestProgres->tanggal)->format('d M Y') : '-';

                // Calculate rencana from tahap_konstruksi
                if ($kons->tanggal_mulai) {
                    $tanggalMulai = Carbon::parse($kons->tanggal_mulai);
                    $targetDate = Carbon::parse($effectiveDate);
                    $daysDiff = $tanggalMulai->diffInDays($targetDate, false);
                    $currentWeek = $daysDiff < 0 ? 1 : floor($daysDiff / 7) + 1;

                    $tahapKonstruksi = DB::connection('mysql_knmp')->table('tahap_konstruksi')
                        ->where('knmp_konstruksi_id', $kons->id)
                        ->where('periode_mingguan', '<=', $currentWeek)
                        ->orderBy('periode_mingguan', 'desc')
                        ->first();

                    if ($tahapKonstruksi) {
                        $val = (float) $tahapKonstruksi->bobot_rencana_kumulatif;
                        if ($val > 100) $val = $val / 1000;
                        $rencana = round($val, 2);
                    }
                }

                $deviasi = round($progres - $rencana, 2);

                if ($latestProgres) {
                    $totalProgres += $progres;
                    $totalDeviasi += $deviasi;
                    $countWithProgres++;
                }
            }

            // Determine health status
            $statusKesehatan = 'Sesuai Jadwal';
            $statusColor = 'success';
            if ($deviasi < -5) {
                $statusKesehatan = 'Kritis';
                $statusColor = 'danger';
            } elseif ($deviasi < 0) {
                $statusKesehatan = 'Terlambat';
                $statusColor = 'warning';
            }

            $progresFisikData[] = [
                'id' => $knmp->id,
                'nama' => $knmp->nama,
                'provinsi' => $knmp->provinsi ?: '-',
                'kabupaten' => $knmp->kabupaten ?: '-',
                'konstruktor' => $konstruktor,
                'progres' => $progres,
                'rencana' => $rencana,
                'deviasi' => $deviasi,
                'tahap' => $knmp->tahap_saat_ini,
                'status_kesehatan' => $statusKesehatan,
                'status_color' => $statusColor,
                'tanggal_progres' => $tanggalProgres,
            ];
        }

        $avgProgres = $countWithProgres > 0 ? round($totalProgres / $countWithProgres, 2) : 0;
        $avgDeviasi = $countWithProgres > 0 ? round($totalDeviasi / $countWithProgres, 1) : 0;

        // Sort by progres descending
        usort($progresFisikData, function ($a, $b) {
            return $b['progres'] <=> $a['progres'];
        });

        // === ANALYTICAL DATA ===

        // 1. Distribution by health status (for donut chart)
        $collection = collect($progresFisikData);
        $distribution = [
            'Sesuai Jadwal' => $collection->where('status_kesehatan', 'Sesuai Jadwal')->count(),
            'Terlambat' => $collection->where('status_kesehatan', 'Terlambat')->count(),
            'Kritis' => $collection->where('status_kesehatan', 'Kritis')->count(),
        ];

        // 2. Average progress by vendor/konstruktor (for bar chart)
        $avgByVendor = $collection->groupBy('konstruktor')->map(function ($items, $vendor) {
            return [
                'vendor' => $vendor,
                'avg_progres' => round($items->avg('progres'), 1),
                'avg_rencana' => round($items->avg('rencana'), 1),
                'count' => $items->count(),
            ];
        })->sortByDesc('avg_progres')->values()->take(10)->all();

        // 3. Insight text
        $totalData = count($progresFisikData);
        $kritisCount = $distribution['Kritis'];
        $terlambatCount = $distribution['Terlambat'];
        $sesuaiCount = $distribution['Sesuai Jadwal'];

        if ($totalData > 0) {
            $kritisPercent = round(($kritisCount / $totalData) * 100, 1);
            $sesuaiPercent = round(($sesuaiCount / $totalData) * 100, 1);

            $insightParts = [];
            if ($kritisCount > 0) {
                $insightParts[] = "<strong>{$kritisCount} dari {$totalData} lokasi</strong> ({$kritisPercent}%) berstatus <span class='text-danger font-semibold'>Kritis</span> dengan deviasi di bawah -5%";
            }
            if ($terlambatCount > 0) {
                $insightParts[] = "<strong>{$terlambatCount} lokasi</strong> berstatus <span class='text-warning font-semibold'>Terlambat</span>";
            }
            if ($sesuaiCount > 0) {
                $insightParts[] = "<strong>{$sesuaiCount} lokasi</strong> ({$sesuaiPercent}%) berjalan <span class='text-success font-semibold'>Sesuai Jadwal</span>";
            }

            $insightText = implode('. ', $insightParts) . ". Rata-rata deviasi keseluruhan: <strong>" . ($avgDeviasi >= 0 ? '+' : '') . "{$avgDeviasi}%</strong>.";
        } else {
            $insightText = 'Belum ada data konstruksi untuk dianalisis pada filter yang dipilih.';
        }

        return view('programs.knmp.evaluasi.progres-fisik', [
            'activeModule' => 'Evaluasi',
            'activeProgram' => $activeProgram,
            'progresFisikData' => $progresFisikData,
            'filter_batches' => $batches,
            'effectiveDate' => $effectiveDate,
            'stats' => [
                'konstruksi_aktif' => $totalKonstruksiAktif,
                'rata_progres' => $avgProgres,
                'total_selesai' => $totalSelesai,
                'rata_deviasi' => $avgDeviasi,
                'distribution' => $distribution,
                'avg_by_vendor' => $avgByVendor,
                'insight_text' => $insightText,
            ],
        ]);
    }

    public function pdf(Request $request, $program)
    {
        $this->checkAuth();
        \Illuminate\Support\Facades\Gate::authorize('generate-pdf');
        $activeProgram = $this->formatProgramName($program);

        $requestedBatchId = $request->query('batch_id');
        $requestedDate = $request->query('date');

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

        $query = Knmp::whereIn('tahap_saat_ini', ['konstruksi', 'serah_terima'])
            ->with('konstruksiKnmp.penyediaJasa');

        if ($requestedBatchId) {
            $query->where('batch_id', $requestedBatchId);
        }

        if (\Illuminate\Support\Facades\Auth::user()->isUserDaerah()) {
            $query->where('kabupaten', 'LIKE', '%' . \Illuminate\Support\Facades\Auth::user()->kabupaten . '%');
        }

        $allKnmp = $query->get();
        $progresFisikData = [];

        foreach ($allKnmp as $knmp) {
            $kons = $knmp->konstruksiKnmp;
            $progres = 0;
            $rencana = 0;
            $konstruktor = '-';

            if ($kons) {
                $konstruktor = $kons->penyediaJasa ? $kons->penyediaJasa->nama : '-';
                $latestProgres = DB::connection('mysql_knmp')
                    ->table('progres_harian')
                    ->where('knmp_konstruksi_id', $kons->id)
                    ->whereDate('tanggal', '<=', $effectiveDate)
                    ->orderBy('tanggal', 'desc')
                    ->first();
                $progres = $latestProgres ? round((float) $latestProgres->progres, 1) : 0;

                if ($kons->tanggal_mulai) {
                    $tanggalMulai = Carbon::parse($kons->tanggal_mulai);
                    $daysDiff = $tanggalMulai->diffInDays(Carbon::parse($effectiveDate), false);
                    $currentWeek = $daysDiff < 0 ? 1 : floor($daysDiff / 7) + 1;
                    $tahapKonstruksi = DB::connection('mysql_knmp')->table('tahap_konstruksi')
                        ->where('knmp_konstruksi_id', $kons->id)
                        ->where('periode_mingguan', '<=', $currentWeek)
                        ->orderBy('periode_mingguan', 'desc')
                        ->first();
                    if ($tahapKonstruksi) {
                        $val = (float) $tahapKonstruksi->bobot_rencana_kumulatif;
                        if ($val > 100) $val = $val / 1000;
                        $rencana = round($val, 2);
                    }
                }
            }

            $deviasi = round($progres - $rencana, 2);

            $progresFisikData[] = [
                'nama' => $knmp->nama,
                'konstruktor' => $konstruktor,
                'progres' => $progres,
                'rencana' => $rencana,
                'deviasi' => $deviasi,
                'tahap' => $knmp->tahap_saat_ini,
            ];
        }

        usort($progresFisikData, function ($a, $b) {
            return $b['progres'] <=> $a['progres'];
        });

        $batchName = 'Semua Tahap';
        if ($requestedBatchId) {
            $batch = DB::connection('mysql_knmp')->table('batch')->where('id', $requestedBatchId)->first();
            if ($batch) $batchName = $batch->nama_tahap . ' - ' . $batch->tahun;
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('programs.knmp.evaluasi.pdf.progres-fisik', [
            'data' => $progresFisikData,
            'batchName' => $batchName,
            'date' => $effectiveDate,
            'activeProgram' => $activeProgram,
        ])->setPaper('A4', 'landscape');

        $dateFormatted = Carbon::parse($effectiveDate)->format('d_M_Y');
        return $pdf->stream("Evaluasi_ProgresFisik_KNMP_{$dateFormatted}.pdf");
    }
}

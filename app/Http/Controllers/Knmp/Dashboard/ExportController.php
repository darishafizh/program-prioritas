<?php

namespace App\Http\Controllers\Knmp\Dashboard;

use App\Http\Controllers\ProgramBaseController;
use Illuminate\Http\Request;
use App\Models\Knmp\Knmp;

class ExportController extends ProgramBaseController
{
    public function pdf($program)
    {
        $this->checkAuth();
        \Illuminate\Support\Facades\Gate::authorize('generate-pdf');
        $activeProgram = $this->formatProgramName($program);

        $requestedDate = request('date');
        $requestedBatchId = request('batch_id');

        // Determine effective date: use request date, or find the latest progres_harian date
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

        // Build base query with optional batch filter
        $queryKnmp = \App\Models\Knmp\Knmp::query();
        if ($requestedBatchId) {
            $queryKnmp->where('batch_id', $requestedBatchId);
        }

        $totalLokasi = (clone $queryKnmp)->count();
        $avgProgres = 0;
        $konstruksiDetails = [];

        if ($totalLokasi > 0) {
            $konstruksis = (clone $queryKnmp)
                ->with('konstruksiKnmp.penyediaJasa', 'konstruksiKnmp.tahapKonstruksi')
                ->get();
            
            $totalProgres = 0;
            $countWithProgres = 0;

            foreach($konstruksis as $k) {
                $kons = $k->konstruksiKnmp;
                if (!$kons) continue;

                // Query progres_harian with effective date filter (matching dashboard logic)
                $query = \Illuminate\Support\Facades\DB::connection('mysql_knmp')
                    ->table('progres_harian')
                    ->where('knmp_konstruksi_id', $kons->id);

                if ($effectiveDate) {
                    $query->whereDate('tanggal', '<=', $effectiveDate);
                }

                $latestProgres = $query->orderBy('tanggal', 'desc')->first();
                $progres = $latestProgres ? (float)$latestProgres->progres : 0;
                
                // Calculate rencana using effective date instead of now()
                $rencana = 0;
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
                        if ($val > 100) $val = $val / 1000;
                        $rencana = round($val, 2);
                    }
                }
                
                $deviasi = round($progres - $rencana, 2);

                if ($latestProgres) {
                    $totalProgres += $progres;
                    $countWithProgres++;
                }

                $fotoAfter = \Illuminate\Support\Facades\DB::connection('mysql_knmp')->table('bukti_uploads')
                    ->where('knmp_id', $k->id)
                    ->where('kondisi', 'after')
                    ->where('tipe_file', 'like', 'image/%')
                    ->orderBy('created_at', 'desc')
                    ->first();

                if ($fotoAfter) {
                    $fotoPath = $fotoAfter->path_file;
                } else {
                    $fotoBefore = \Illuminate\Support\Facades\DB::connection('mysql_knmp')->table('bukti_uploads')
                        ->where('knmp_id', $k->id)
                        ->where('kondisi', 'before')
                        ->where('tipe_file', 'like', 'image/%')
                        ->orderBy('created_at', 'desc')
                        ->first();
                    $fotoPath = $fotoBefore ? $fotoBefore->path_file : null;
                }

                $konstruksiDetails[] = [
                    'lokasi' => $k->nama,
                    'daerah' => $k->status ?: 'Penyangga',
                    'konstruktor' => $kons->penyediaJasa ? $kons->penyediaJasa->nama : '-',
                    'progres' => round($progres, 2),
                    'rencana' => round($rencana, 2),
                    'deviasi' => round($deviasi, 2),
                    'foto' => $fotoPath
                ];
            }

            if ($countWithProgres > 0) {
                $avgProgres = round($totalProgres / $countWithProgres, 2);
            }
        }

        $sortedByProgres = collect($konstruksiDetails)->sortByDesc('progres')->values()->all();

        $effectiveDateFormatted = \Carbon\Carbon::parse($effectiveDate)->translatedFormat('d F Y');

        $tahapStr = 'Nasional';
        if ($requestedBatchId == 1) $tahapStr = 'Tahap_I';
        elseif ($requestedBatchId == 2) $tahapStr = 'Tahap_II';
        elseif ($requestedBatchId == 3) $tahapStr = 'Tahap_III';

        $dateFormatted = \Carbon\Carbon::parse($effectiveDate)->locale('id')->translatedFormat('d_F_Y');
        $filename = "Progres_KNMP_{$tahapStr}_{$dateFormatted}.pdf";

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('programs.knmp.dashboard.pdf', [
            'data' => $sortedByProgres,
            'avgProgres' => str_replace('.', ',', $avgProgres),
            'tanggal' => $effectiveDateFormatted
        ])->setPaper('A4', 'portrait');

        return $pdf->stream($filename);
    }
}

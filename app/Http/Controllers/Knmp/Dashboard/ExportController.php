<?php

namespace App\Http\Controllers\Knmp\Dashboard;

use App\Http\Controllers\ProgramBaseController;
use Illuminate\Http\Request;
use App\Models\Knmp;

class ExportController extends ProgramBaseController
{
    public function pdf($program)
    {
        $this->checkAuth();
        $activeProgram = $this->formatProgramName($program);

        $dalamPembangunan = \App\Models\Knmp::where('tahap_saat_ini', 'konstruksi')->count();
        $avgProgres = 0;
        $konstruksiDetails = [];

        if ($dalamPembangunan > 0) {
            $konstruksis = \App\Models\Knmp::where('tahap_saat_ini', 'konstruksi')
                ->with('konstruksiKnmp.penyediaJasa', 'konstruksiKnmp.tahapKonstruksi')
                ->get();
            
            $totalProgres = 0;
            $countWithProgres = 0;

            foreach($konstruksis as $k) {
                $kons = $k->konstruksiKnmp;
                if (!$kons) continue;

                $latestProgres = \Illuminate\Support\Facades\DB::connection('mysql_knmp')
                    ->table('progres_harian')
                    ->where('knmp_konstruksi_id', $kons->id)
                    ->orderBy('tanggal', 'desc')
                    ->first();
                $progres = $latestProgres ? (float)$latestProgres->progres : 0;
                
                $rencana = 0;
                if ($kons->tanggal_mulai) {
                    $tanggalMulai = \Carbon\Carbon::parse($kons->tanggal_mulai);
                    $daysDiff = $tanggalMulai->diffInDays(now(), false);
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

                $foto = \Illuminate\Support\Facades\DB::connection('mysql_knmp')->table('bukti_uploads')
                    ->where('knmp_id', $k->id)
                    ->orderBy('kondisi', 'asc')
                    ->first();
                $fotoPath = $foto ? $foto->path_file : null;

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

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('programs.knmp.dashboard.pdf', [
            'data' => $sortedByProgres,
            'avgProgres' => str_replace('.', ',', $avgProgres),
            'tanggal' => now()->translatedFormat('d F Y')
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('Progres_KNMP_' . now()->format('Y-m-d') . '.pdf');
    }
}

<?php

namespace App\Http\Controllers\Knmp\Evaluasi;

use App\Http\Controllers\ProgramBaseController;
use App\Models\Knmp\CalonLokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalonLokasiEvaluasiController extends ProgramBaseController
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

        $query = CalonLokasi::with(['pengajuan', 'verifAdmin', 'baAktivasi', 'verifTeknis', 'baCalon', 'penetapan', 'detail', 'user']);

        if ($requestedBatchId) {
            $query->where('batch_id', $requestedBatchId);
        }

        if ($requestedDate) {
            $query->whereDate('created_at', '<=', $requestedDate);
        }

        if (\Illuminate\Support\Facades\Auth::user()->isUserDaerah()) {
            $query->where('kabupaten', 'LIKE', '%' . \Illuminate\Support\Facades\Auth::user()->kabupaten . '%');
        }

        $allCalonLokasi = $query->orderBy('created_at', 'desc')->get();

        $stageOrder = ['pengajuan', 'verif_admin', 'ba_aktivasi', 'verif_teknis', 'ba_calon', 'penetapan'];

        $calonLokasiData = $allCalonLokasi->map(function ($cl) use ($stageOrder) {
            $currentStageIndex = array_search($cl->status_tahapan, $stageOrder);
            if ($currentStageIndex === false) $currentStageIndex = -1;

            $stages = [];
            foreach ($stageOrder as $idx => $stage) {
                $status = 'pending';
                $date = null;
                
                if ($cl->status_tahapan === 'ditolak') {
                    if ($idx <= $currentStageIndex) $status = 'rejected';
                } elseif ($idx < $currentStageIndex) {
                    $status = 'completed';
                } elseif ($idx === $currentStageIndex) {
                    $status = 'active';
                }

                // Get date from related table
                switch ($stage) {
                    case 'pengajuan':
                        $date = $cl->pengajuan ? ($cl->pengajuan->tanggal_pengajuan ?? $cl->pengajuan->created_at?->format('Y-m-d')) : null;
                        break;
                    case 'verif_admin':
                        $date = $cl->verifAdmin ? ($cl->verifAdmin->tanggal_verif ?? $cl->verifAdmin->created_at?->format('Y-m-d')) : null;
                        break;
                    case 'ba_aktivasi':
                        $date = $cl->baAktivasi ? ($cl->baAktivasi->tanggal_ba ?? $cl->baAktivasi->created_at?->format('Y-m-d')) : null;
                        break;
                    case 'verif_teknis':
                        $date = $cl->verifTeknis ? ($cl->verifTeknis->tanggal_verif ?? $cl->verifTeknis->created_at?->format('Y-m-d')) : null;
                        break;
                    case 'ba_calon':
                        $date = $cl->baCalon ? ($cl->baCalon->tanggal_ba ?? $cl->baCalon->created_at?->format('Y-m-d')) : null;
                        break;
                    case 'penetapan':
                        $date = $cl->penetapan ? ($cl->penetapan->tanggal_sk ?? $cl->penetapan->created_at?->format('Y-m-d')) : null;
                        break;
                }

                $stages[] = ['key' => $stage, 'status' => $status, 'date' => $date];
            }

            $idUser = $cl->user ? $cl->user->name : ('#USR-'.$cl->user_id);

            return [
                'id' => $cl->id,
                'idUser' => $idUser,
                'desa' => $cl->desa ?: '-',
                'kecamatan' => $cl->kecamatan ?: '-',
                'kabupaten' => $cl->kabupaten ?: '-',
                'provinsi' => $cl->provinsi ?: '-',
                'status_tahapan' => $cl->status_tahapan,
                'is_active' => $cl->is_active,
                'created_at' => $cl->created_at ? $cl->created_at->format('d M Y') : '-',
                'updated_at' => $cl->updated_at ? $cl->updated_at->format('d M Y') : '-',
                'stages' => $stages,
            ];
        })->values()->all();

        // Stats
        $totalCalonLokasi = count($calonLokasiData);
        $totalPengajuan = collect($calonLokasiData)->where('status_tahapan', 'pengajuan')->count();
        $totalVerifikasi = collect($calonLokasiData)->whereIn('status_tahapan', ['verif_admin', 'ba_aktivasi', 'verif_teknis', 'ba_calon'])->count();
        $totalDitetapkan = collect($calonLokasiData)->where('status_tahapan', 'penetapan')->count();
        $totalDitolak = collect($calonLokasiData)->where('status_tahapan', 'ditolak')->count();

        // === ANALYTICAL DATA ===

        $stageOrder = ['pengajuan', 'verif_admin', 'ba_aktivasi', 'verif_teknis', 'ba_calon', 'penetapan'];
        $stageLabels = [
            'pengajuan' => 'Pengajuan',
            'verif_admin' => 'Verif Admin',
            'ba_aktivasi' => 'BA Aktivasi',
            'verif_teknis' => 'Verif Teknis',
            'ba_calon' => 'BA Calon',
            'penetapan' => 'Penetapan',
        ];

        // 1. Funnel: cumulative count (locations that have reached or passed each stage)
        $funnel = [];
        foreach ($stageOrder as $idx => $stage) {
            $count = collect($calonLokasiData)->filter(function ($item) use ($stageOrder, $idx) {
                $itemIdx = array_search($item['status_tahapan'], $stageOrder);
                return $itemIdx !== false && $itemIdx >= $idx;
            })->count();
            $funnel[$stage] = $count;
        }
        // Adjust: funnel should show how many entered each stage (cumulative from start)
        $funnelCumulative = [];
        foreach ($stageOrder as $idx => $stage) {
            $reached = collect($calonLokasiData)->filter(function ($item) use ($stageOrder, $idx) {
                $itemIdx = array_search($item['status_tahapan'], $stageOrder);
                if ($itemIdx === false) return false;
                return $itemIdx >= $idx;
            })->count();
            $funnelCumulative[$stage] = $reached;
        }

        // 2. Conversion rate per stage
        $conversionRate = [];
        foreach ($stageOrder as $idx => $stage) {
            if ($idx === 0) {
                $conversionRate[$stage] = 100;
            } else {
                $prevCount = $funnelCumulative[$stageOrder[$idx - 1]] ?? 0;
                $currCount = $funnelCumulative[$stage] ?? 0;
                $conversionRate[$stage] = $prevCount > 0 ? round(($currCount / $prevCount) * 100, 1) : 0;
            }
        }

        // 3. Rejection rate
        $rejectionRate = $totalCalonLokasi > 0 ? round(($totalDitolak / $totalCalonLokasi) * 100, 1) : 0;

        // 4. Insight text
        if ($totalCalonLokasi > 0) {
            $overallConversion = $totalCalonLokasi > 0 ? round(($totalDitetapkan / $totalCalonLokasi) * 100, 1) : 0;
            
            $insightParts = [];
            $insightParts[] = "Dari <strong>{$totalCalonLokasi} calon lokasi</strong>, <strong>{$totalDitetapkan}</strong> ({$overallConversion}%) berhasil mencapai <span class='text-success font-semibold'>Penetapan</span>";

            // Find stage with biggest dropout
            $maxDropout = 0;
            $dropoutStage = '';
            foreach ($stageOrder as $idx => $stage) {
                if ($idx === 0) continue;
                $prev = $funnelCumulative[$stageOrder[$idx - 1]] ?? 0;
                $curr = $funnelCumulative[$stage] ?? 0;
                $dropout = $prev - $curr;
                if ($dropout > $maxDropout) {
                    $maxDropout = $dropout;
                    $dropoutStage = $stageOrder[$idx - 1];
                }
            }
            if ($dropoutStage && $maxDropout > 0) {
                $insightParts[] = "Tahap <span class='text-warning font-semibold'>" . ($stageLabels[$dropoutStage] ?? $dropoutStage) . "</span> memiliki <strong>dropout tertinggi</strong> ({$maxDropout} lokasi)";
            }

            if ($totalDitolak > 0) {
                $insightParts[] = "<strong>{$totalDitolak} lokasi</strong> ({$rejectionRate}%) berstatus <span class='text-danger font-semibold'>Ditolak</span>";
            }

            $insightText = implode('. ', $insightParts) . '.';
        } else {
            $insightText = 'Belum ada data calon lokasi untuk dianalisis pada filter yang dipilih.';
        }

        return view('programs.knmp.evaluasi.calon-lokasi', [
            'activeModule' => 'Evaluasi',
            'activeProgram' => $activeProgram,
            'calonLokasiData' => $calonLokasiData,
            'filter_batches' => $batches,
            'stats' => [
                'total' => $totalCalonLokasi,
                'pengajuan' => $totalPengajuan,
                'verifikasi' => $totalVerifikasi,
                'ditetapkan' => $totalDitetapkan,
                'ditolak' => $totalDitolak,
                'funnel' => $funnelCumulative,
                'conversion_rate' => $conversionRate,
                'rejection_rate' => $rejectionRate,
                'stage_labels' => $stageLabels,
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

        $query = CalonLokasi::with(['pengajuan', 'verifAdmin', 'baAktivasi', 'verifTeknis', 'baCalon', 'penetapan', 'user']);

        if ($requestedBatchId) {
            $query->where('batch_id', $requestedBatchId);
        }
        if ($requestedDate) {
            $query->whereDate('created_at', '<=', $requestedDate);
        }
        if (\Illuminate\Support\Facades\Auth::user()->isUserDaerah()) {
            $query->where('kabupaten', 'LIKE', '%' . \Illuminate\Support\Facades\Auth::user()->kabupaten . '%');
        }

        $allCalonLokasi = $query->orderBy('created_at', 'desc')->get();

        $stageOrder = ['pengajuan', 'verif_admin', 'ba_aktivasi', 'verif_teknis', 'ba_calon', 'penetapan'];

        $calonLokasiData = $allCalonLokasi->map(function ($cl) use ($stageOrder) {
            $currentStageIndex = array_search($cl->status_tahapan, $stageOrder);
            if ($currentStageIndex === false) $currentStageIndex = -1;

            return [
                'nama_lokasi' => $cl->nama_lokasi ?: '-',
                'provinsi' => $cl->provinsi ?: '-',
                'kabupaten' => $cl->kabupaten ?: '-',
                'pengusul' => $cl->user ? $cl->user->name : '-',
                'status_tahapan' => $cl->status_tahapan,
                'stage_index' => $currentStageIndex,
                'total_stages' => count($stageOrder),
                'created_at' => $cl->created_at ? $cl->created_at->format('d M Y') : '-',
            ];
        })->values()->all();

        $batchName = 'Semua Tahap';
        if ($requestedBatchId) {
            $batch = DB::connection('mysql_knmp')->table('batch')->where('id', $requestedBatchId)->first();
            if ($batch) $batchName = $batch->nama_tahap . ' - ' . $batch->tahun;
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('programs.knmp.evaluasi.pdf.calon-lokasi', [
            'data' => $calonLokasiData,
            'batchName' => $batchName,
            'date' => $requestedDate ?: now()->format('Y-m-d'),
            'activeProgram' => $activeProgram,
        ])->setPaper('A4', 'portrait');

        $dateFormatted = \Carbon\Carbon::parse($requestedDate ?: now())->format('d_M_Y');
        return $pdf->stream("Evaluasi_CalonLokasi_KNMP_{$dateFormatted}.pdf");
    }
}

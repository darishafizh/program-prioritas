<?php

namespace App\Http\Controllers\Knmp\Evaluasi;

use App\Http\Controllers\ProgramBaseController;
use App\Models\Knmp\Knmp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OperasionalEvaluasiController extends ProgramBaseController
{
    private $stageOrder = ['usulan', 'survey', 'ded', 'lelang', 'konstruksi', 'serah_terima'];

    public function index(Request $request, $program)
    {
        $this->checkAuth();
        $activeProgram = $this->formatProgramName($program);

        $requestedBatchId = $request->query('batch_id');
        $requestedDate = $request->query('date');

        $batches = DB::connection('mysql_knmp')->table('batch')->get()->map(function ($b) {
            return ['id' => $b->id, 'name' => $b->nama_tahap . ' - ' . $b->tahun];
        })->values()->all();

        $query = Knmp::with(['tahapUsulan', 'tahapSurvey', 'tahapDed', 'tahapLelang', 'konstruksiKnmp.penyediaJasa', 'tahapSerahTerima']);
        if (\Illuminate\Support\Facades\Auth::user()->isUserDaerah()) {
            $query->where('kabupaten', 'LIKE', '%' . \Illuminate\Support\Facades\Auth::user()->kabupaten . '%');
        }

        if ($requestedBatchId) {
            $query->where('batch_id', $requestedBatchId);
        }
        if ($requestedDate) {
            $query->whereDate('created_at', '<=', $requestedDate);
        }

        $allKnmp = $query->orderBy('created_at', 'desc')->get();

        $operasionalData = $allKnmp->map(function ($knmp) {
            $currentStageIndex = array_search($knmp->tahap_saat_ini, $this->stageOrder);
            if ($currentStageIndex === false) $currentStageIndex = 0;

            $stages = [];
            foreach ($this->stageOrder as $idx => $stage) {
                $status = 'pending';
                $date = null;

                if ($idx < $currentStageIndex) {
                    $status = 'completed';
                } elseif ($idx === $currentStageIndex) {
                    $status = 'active';
                }

                switch ($stage) {
                    case 'usulan':
                        $date = $knmp->tahapUsulan ? ($knmp->tahapUsulan->tanggal ?? $knmp->tahapUsulan->created_at?->format('Y-m-d')) : null;
                        break;
                    case 'survey':
                        $date = $knmp->tahapSurvey ? ($knmp->tahapSurvey->tanggal ?? $knmp->tahapSurvey->created_at?->format('Y-m-d')) : null;
                        break;
                    case 'ded':
                        $date = $knmp->tahapDed ? ($knmp->tahapDed->tanggal ?? $knmp->tahapDed->created_at?->format('Y-m-d')) : null;
                        break;
                    case 'lelang':
                        $date = $knmp->tahapLelang ? ($knmp->tahapLelang->tanggal ?? $knmp->tahapLelang->created_at?->format('Y-m-d')) : null;
                        break;
                    case 'konstruksi':
                        $date = $knmp->konstruksiKnmp ? ($knmp->konstruksiKnmp->tanggal_mulai ?? $knmp->konstruksiKnmp->created_at?->format('Y-m-d')) : null;
                        break;
                    case 'serah_terima':
                        $date = $knmp->tahapSerahTerima ? ($knmp->tahapSerahTerima->tanggal_serah ?? $knmp->tahapSerahTerima->created_at?->format('Y-m-d')) : null;
                        break;
                }

                $stages[] = ['key' => $stage, 'status' => $status, 'date' => $date];
            }

            return [
                'id' => $knmp->id,
                'nama' => $knmp->nama,
                'provinsi' => $knmp->provinsi ?: '-',
                'kabupaten' => $knmp->kabupaten ?: '-',
                'status' => $knmp->status ?: 'Penyangga',
                'tahap_saat_ini' => $knmp->tahap_saat_ini,
                'created_at' => $knmp->created_at ? $knmp->created_at->format('d M Y') : '-',
                'stages' => $stages,
            ];
        })->values()->all();

        // Stats per tahap
        $stageStats = [];
        foreach ($this->stageOrder as $stage) {
            $stageStats[$stage] = collect($operasionalData)->where('tahap_saat_ini', $stage)->count();
        }

        // === ANALYTICAL DATA ===

        // 1. Average duration per stage (days)
        $avgDurationDays = [];
        $stageLabels = [
            'usulan' => 'Usulan',
            'survey' => 'Survei',
            'ded' => 'DED',
            'lelang' => 'Lelang',
            'konstruksi' => 'Konstruksi',
            'serah_terima' => 'Serah Terima',
        ];

        foreach ($this->stageOrder as $idx => $stage) {
            if ($idx >= count($this->stageOrder) - 1) break; // skip last stage
            $nextStage = $this->stageOrder[$idx + 1];
            $durations = [];

            foreach ($operasionalData as $item) {
                $currentStageIdx = array_search($item['tahap_saat_ini'], $this->stageOrder);
                if ($currentStageIdx === false) continue;
                // Only count locations that have passed this stage
                if ($currentStageIdx <= $idx) continue;

                $stageData = $item['stages'][$idx] ?? null;
                $nextStageData = $item['stages'][$idx + 1] ?? null;

                if ($stageData && $nextStageData && $stageData['date'] && $nextStageData['date']) {
                    $start = \Carbon\Carbon::parse($stageData['date']);
                    $end = \Carbon\Carbon::parse($nextStageData['date']);
                    $days = $start->diffInDays($end);
                    if ($days >= 0 && $days < 3650) { // sanity check
                        $durations[] = $days;
                    }
                }
            }

            $avgDurationDays[$stage] = count($durations) > 0 ? round(array_sum($durations) / count($durations)) : 0;
        }

        // Find bottleneck (longest average duration)
        $bottleneckStage = '';
        $maxDuration = 0;
        foreach ($avgDurationDays as $stage => $days) {
            if ($days > $maxDuration) {
                $maxDuration = $days;
                $bottleneckStage = $stage;
            }
        }

        // 2. Insight text
        $totalOps = count($operasionalData);
        if ($totalOps > 0) {
            $insightParts = [];
            $insightParts[] = "Terdapat <strong>{$totalOps} lokasi KNMP</strong> yang terpantau dalam siklus operasional";

            if ($bottleneckStage && $maxDuration > 0) {
                $insightParts[] = "Tahap <span class='text-warning font-semibold'>" . ($stageLabels[$bottleneckStage] ?? $bottleneckStage) . "</span> menjadi <strong>bottleneck</strong> dengan rata-rata durasi <strong>{$maxDuration} hari</strong> per lokasi";
            }

            $serahTerimaCount = $stageStats['serah_terima'] ?? 0;
            if ($serahTerimaCount > 0) {
                $completionRate = round(($serahTerimaCount / $totalOps) * 100, 1);
                $insightParts[] = "<strong>{$serahTerimaCount} lokasi</strong> ({$completionRate}%) telah mencapai <span class='text-success font-semibold'>Serah Terima</span>";
            }

            $insightText = implode('. ', $insightParts) . '.';
        } else {
            $insightText = 'Belum ada data operasional untuk dianalisis pada filter yang dipilih.';
        }

        return view('programs.knmp.evaluasi.operasional', [
            'activeModule' => 'Evaluasi',
            'activeProgram' => $activeProgram,
            'operasionalData' => $operasionalData,
            'filter_batches' => $batches,
            'stats' => [
                'total' => count($operasionalData),
                'per_tahap' => $stageStats,
                'avg_duration_days' => $avgDurationDays,
                'bottleneck_stage' => $bottleneckStage,
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

        $query = Knmp::query();
        if (\Illuminate\Support\Facades\Auth::user()->isUserDaerah()) {
            $query->where('kabupaten', 'LIKE', '%' . \Illuminate\Support\Facades\Auth::user()->kabupaten . '%');
        }
        if ($requestedBatchId) {
            $query->where('batch_id', $requestedBatchId);
        }
        if ($requestedDate) {
            $query->whereDate('created_at', '<=', $requestedDate);
        }

        $allKnmp = $query->orderBy('created_at', 'desc')->get();

        $operasionalData = $allKnmp->map(function ($knmp) {
            $currentStageIndex = array_search($knmp->tahap_saat_ini, $this->stageOrder);
            return [
                'nama' => $knmp->nama,
                'provinsi' => $knmp->provinsi ?: '-',
                'kabupaten' => $knmp->kabupaten ?: '-',
                'status' => $knmp->status ?: 'Penyangga',
                'tahap_saat_ini' => $knmp->tahap_saat_ini,
                'stage_index' => $currentStageIndex !== false ? $currentStageIndex : 0,
                'total_stages' => count($this->stageOrder),
            ];
        })->values()->all();

        $batchName = 'Semua Tahap';
        if ($requestedBatchId) {
            $batch = DB::connection('mysql_knmp')->table('batch')->where('id', $requestedBatchId)->first();
            if ($batch) $batchName = $batch->nama_tahap . ' - ' . $batch->tahun;
        }

        // Stats
        $stageStats = [];
        foreach ($this->stageOrder as $stage) {
            $stageStats[$stage] = collect($operasionalData)->where('tahap_saat_ini', $stage)->count();
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('programs.knmp.evaluasi.pdf.operasional', [
            'data' => $operasionalData,
            'batchName' => $batchName,
            'date' => $requestedDate ?: now()->format('Y-m-d'),
            'activeProgram' => $activeProgram,
            'stats' => $stageStats,
        ])->setPaper('A4', 'landscape');

        $dateFormatted = \Carbon\Carbon::parse($requestedDate ?: now())->format('d_M_Y');
        return $pdf->stream("Evaluasi_Operasional_KNMP_{$dateFormatted}.pdf");
    }
}

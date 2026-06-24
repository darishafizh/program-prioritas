<?php

namespace App\Http\Controllers\Knmp\Evaluasi;

use App\Http\Controllers\ProgramBaseController;
use App\Models\CalonLokasi;
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
            ],
        ]);
    }

    public function pdf(Request $request, $program)
    {
        $this->checkAuth();
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

<?php

namespace App\Http\Controllers\Knmp\Dashboard;

use App\Http\Controllers\ProgramBaseController;
use App\Models\Knmp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OperasionalKnmpController extends ProgramBaseController
{
    /**
     * Daftar 14 Sarpras standar KNMP.
     * Kunci mengacu pada field/slug yang nantinya bisa dijadikan kolom di DB.
     */
    /**
     * Mapping Ikon untuk master_sarpras.
     */
    public const SARPRAS_ICONS = [
        'SPBN' => 'fa-solid fa-gas-pump',
        'Docking' => 'fa-solid fa-anchor',
        'Bengkel' => 'fa-solid fa-wrench',
        'Waserda' => 'fa-solid fa-store',
        'Pabrik Es' => 'fa-solid fa-cubes-stacked',
        'Cold Storage' => 'fa-solid fa-snowflake',
        'KDRN Dingin' => 'fa-solid fa-temperature-arrow-down',
        'Sentra Kuliner' => 'fa-solid fa-utensils',
        'Kios Pemasaran' => 'fa-solid fa-shop',
        'Kapal' => 'fa-solid fa-ship',
        'Mesin' => 'fa-solid fa-gears',
        'Alat Tangkap' => 'fa-solid fa-fish',
        'Cool Box' => 'fa-solid fa-box-archive',
        'Roda 3' => 'fa-solid fa-motorcycle',
    ];

    public function index($program)
    {
        $this->checkAuth();
        $activeProgram = $this->formatProgramName($program);

        $requestedBatchId = request('batch_id');

        // ---------- Base query: HANYA serah_terima ----------
        $baseQuery = Knmp::query()->where('tahap_saat_ini', 'serah_terima');

        if (Auth::user()->isUserDaerah()) {
            $baseQuery->where('kabupaten', 'LIKE', '%' . Auth::user()->kabupaten . '%');
        }
        if ($requestedBatchId) {
            $baseQuery->where('batch_id', $requestedBatchId);
        }

        $totalSelesai = (clone $baseQuery)->count();

        // ---------- Batches ----------
        $batches = DB::connection('mysql_knmp')->table('batch')->get()->map(function ($b) {
            return [
                'id'   => $b->id,
                'name' => $b->nama_tahap . ' - ' . $b->tahun,
            ];
        })->keyBy('id');

        // ---------- Ambil seluruh data KNMP serah_terima ----------
        $semuaKnmp = (clone $baseQuery)->get();

        // ---------- Hitung statistik sarpras ----------
        $apiData = \Illuminate\Support\Facades\Cache::remember('knmp_api_data', 3600, function () {
            try {
                $response = \Illuminate\Support\Facades\Http::withoutVerifying()->timeout(15)->get('https://kdmp.pdspkp.id/knmp/get_data.php');
                if ($response->successful()) {
                    return $response->json();
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('API Sarpras Exception: ' . $e->getMessage());
            }
            return [];
        });

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
        $sarprasStats = $masterSarpras->map(function ($s) use ($totalSelesai, $apiData, $apiKeys) {
            $icon = self::SARPRAS_ICONS[$s->nama] ?? 'fa-solid fa-box';
            
            $tersedia = 0;
            $target = 0;
            $apiKey = $apiKeys[$s->nama] ?? null;
            if ($apiKey && is_array($apiData)) {
                foreach ($apiData as $item) {
                    if (isset($item[$apiKey])) {
                        $val = $item[$apiKey];
                        if (stripos($val, '2. Sudah Operasional') !== false) {
                            $tersedia++;
                            $target++;
                        } elseif (stripos($val, '1. Belum Operasional') !== false) {
                            $target++;
                        }
                    }
                }
            }

            return [
                'key'       => \Illuminate\Support\Str::slug($s->nama, '_'),
                'nama'      => $s->nama,
                'icon'      => $icon,
                'total'     => $tersedia,
                'target'    => $target,
                'persen'    => ($target > 0) ? ($tersedia / $target) * 100 : 0,
            ];
        })->all();

        // ---------- Daftar lokasi selesai ----------
        $lokasiSelesai = $semuaKnmp->map(function ($k) use ($batches) {
            $batchName = $k->batch_id && $batches->has($k->batch_id) ? $batches[$k->batch_id]['name'] : '-';
            return [
                'nama'       => $k->nama,
                'provinsi'   => $k->provinsi,
                'kabupaten'  => $k->kabupaten,
                'status'     => $k->status ?: 'Penyangga',
                'batch_name' => $batchName,
            ];
        })->sortBy('nama')->values()->all();

        return view('programs.knmp.dashboard.partials.operasional', [
            'activeModule'  => 'Dashboard',
            'activeProgram' => $activeProgram,
            'totalSelesai'  => $totalSelesai,
            'sarprasStats'  => $sarprasStats,
            'lokasiSelesai' => $lokasiSelesai,
            'stats'         => [
                'filter_batches' => $batches->values()->all(),
            ],
        ]);
    }
}

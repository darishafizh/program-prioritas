<?php

namespace App\Http\Controllers\Knmp\Dashboard;

use App\Http\Controllers\ProgramBaseController;
use App\Models\Knmp\Knmp;
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
        1 => 'fa-solid fa-gas-pump', // SPBUN
        2 => 'fa-solid fa-anchor', // Docking
        3 => 'fa-solid fa-wrench', // Bengkel
        4 => 'fa-solid fa-store', // Waserda/Kios Perbekalan
        5 => 'fa-solid fa-cubes-stacked', // Pabrik Es
        6 => 'fa-solid fa-snowflake', // Cold Storage
        7 => 'fa-solid fa-temperature-arrow-down', // KDRN Dingin/Kendaraan Dingin
        8 => 'fa-solid fa-utensils', // Sentra Kuliner
        9 => 'fa-solid fa-shop', // Kios Pemasaran/Lapak Ikan
        10 => 'fa-solid fa-ship', // Kapal
        11 => 'fa-solid fa-gears', // Mesin
        12 => 'fa-solid fa-fish', // Alat Tangkap
        13 => 'fa-solid fa-box-archive', // Cool Box
        14 => 'fa-solid fa-motorcycle', // Roda 3
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
            1 => 'SPBUN_status',
            2 => 'Docking nelayan_status',
            3 => 'Bengkel Nelayan_status',
            4 => 'Waserda_status',
            5 => 'Pabrik Es_status',
            6 => 'Cold Storage_status',
            7 => 'Kenderaan Berpendingin_status',
            8 => 'Sentra Kuliner_status',
            9 => 'Kios Pemasaran_status',
            10 => 'Kapal_status',
            11 => 'Mesin_Status',
            12 => 'Alat_tangkap_Status',
            13 => 'cool_box_status',
            14 => 'roda3_status',
        ];

        $masterSarpras = \Illuminate\Support\Facades\DB::connection('mysql_knmp')->table('master_sarpras')->get();
        $sarprasStats = $masterSarpras->map(function ($s) use ($totalSelesai, $apiData, $apiKeys) {
            $icon = self::SARPRAS_ICONS[$s->id] ?? 'fa-solid fa-box';
            
            $tersedia = 0;
            $target = 0;
            $lokasiOperasional = [];
            $lokasiBelum = [];

            $apiKey = $apiKeys[$s->id] ?? null;
            if ($apiKey && is_array($apiData)) {
                foreach ($apiData as $item) {
                    if (isset($item[$apiKey])) {
                        $val = $item[$apiKey];
                        $namaLokasi = $item['KNMP'] ?? $item['Desa'] ?? 'Lokasi tidak diketahui';
                        
                        if (stripos($val, '2. Sudah Operasional') !== false) {
                            $tersedia++;
                            $target++;
                            $lokasiOperasional[] = $namaLokasi;
                        } elseif (stripos($val, '1. Belum Operasional') !== false) {
                            $target++;
                            $lokasiBelum[] = $namaLokasi;
                        }
                    }
                }
            }

            return [
                'key'                => \Illuminate\Support\Str::slug($s->nama, '_'),
                'nama'               => $s->nama,
                'icon'               => $icon,
                'total'              => $tersedia,
                'target'             => $target,
                'persen'             => ($target > 0) ? ($tersedia / $target) * 100 : 0,
                'lokasi_operasional' => $lokasiOperasional,
                'lokasi_belum'       => $lokasiBelum,
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

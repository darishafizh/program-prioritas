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
    private const SARPRAS_LIST = [
        ['key' => 'dermaga',        'nama' => 'Dermaga',              'icon' => 'fa-solid fa-anchor'],
        ['key' => 'breakwater',     'nama' => 'Breakwater',           'icon' => 'fa-solid fa-water'],
        ['key' => 'tpi',            'nama' => 'TPI',                  'icon' => 'fa-solid fa-fish'],
        ['key' => 'cold_storage',   'nama' => 'Cold Storage',         'icon' => 'fa-solid fa-snowflake'],
        ['key' => 'pabrik_es',      'nama' => 'Pabrik Es',            'icon' => 'fa-solid fa-cubes-stacked'],
        ['key' => 'spbn',           'nama' => 'SPBN',                 'icon' => 'fa-solid fa-gas-pump'],
        ['key' => 'jalan_akses',    'nama' => 'Jalan Akses',          'icon' => 'fa-solid fa-road'],
        ['key' => 'drainase',       'nama' => 'Drainase',             'icon' => 'fa-solid fa-droplet'],
        ['key' => 'air_bersih',     'nama' => 'Air Bersih',           'icon' => 'fa-solid fa-faucet-drip'],
        ['key' => 'listrik',        'nama' => 'Listrik',              'icon' => 'fa-solid fa-bolt'],
        ['key' => 'mushola',        'nama' => 'Mushola',              'icon' => 'fa-solid fa-mosque'],
        ['key' => 'mck',            'nama' => 'MCK',                  'icon' => 'fa-solid fa-restroom'],
        ['key' => 'rumah_nelayan',  'nama' => 'Rumah Nelayan',        'icon' => 'fa-solid fa-house'],
        ['key' => 'pasar_ikan',     'nama' => 'Pasar Ikan',           'icon' => 'fa-solid fa-store'],
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
        // Karena belum ada tabel sarpras per KNMP di database,
        // kita asumsikan setiap KNMP yang sudah serah_terima memiliki seluruh 14 sarpras (100%).
        // Nantinya ketika tabel sarpras sudah ada, tinggal diganti query-nya di sini.
        $sarprasStats = collect(self::SARPRAS_LIST)->map(function ($s) use ($totalSelesai) {
            return [
                'key'       => $s['key'],
                'nama'      => $s['nama'],
                'icon'      => $s['icon'],
                'total'     => $totalSelesai,     // jumlah KNMP yang memiliki sarpras ini
                'target'    => $totalSelesai,     // target total KNMP serah_terima
                'persen'    => $totalSelesai > 0 ? 100 : 0,
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

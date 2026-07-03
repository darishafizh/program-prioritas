<?php

namespace App\Http\Controllers\Bioflok;

use App\Http\Controllers\ProgramBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends ProgramBaseController
{
    public function progresFisik(Request $request)
    {
        $this->checkAuth();
        $activeProgram = 'Bioflok';

        $filter_batches = [
            ['id' => 1, 'name' => 'Tahap I - 2025/2026'],
            ['id' => 2, 'name' => 'Tahap II - Akselerasi 2026'],
        ];

        $stats = [
            'total_lokasi' => 45,
            'rata_progres' => 78.4,
            'total_selesai' => 28,
            'dalam_pembangunan' => 17,
            'filter_batches' => $filter_batches,
            'narasi' => 'Pengerjaan budidaya ikan sistem <strong class="text-textMain-light dark:text-white">Bioflok Terintegrasi</strong> menunjukkan progres fisik rata-rata <strong class="text-teal-light dark:text-teal-400">78.4%</strong> di 45 titik lokasi Sentra Kampung Perikanan. Sebanyak 28 kelompok/lokasi telah rampung konstruksi kolam dan instalasi aerator (Siap Tebar/Sudah Panen), sementara 17 titik masih dalam tahap akselerasi pengecoran fondasi dan pemasangan rangka besi.'
        ];

        $regionalData = [
            ['nama' => 'Jawa Barat (Karawang, Subang, Indramayu)', 'target' => 80, 'realisasi' => 88, 'status' => 'Over-performing', 'class' => 'text-success'],
            ['nama' => 'Jawa Tengah (Kendal, Brebes, Demak)', 'target' => 75, 'realisasi' => 76, 'status' => 'On-Track', 'class' => 'text-textMain-light'],
            ['nama' => 'Jawa Timur (Lamongan, Gresik, Banyuwangi)', 'target' => 78, 'realisasi' => 81, 'status' => 'Over-performing', 'class' => 'text-success'],
            ['nama' => 'Nusa Tenggara Barat & Timur', 'target' => 70, 'realisasi' => 61, 'status' => 'Perlu Perhatian', 'class' => 'text-warning'],
        ];

        $kendala = [
            ['judul' => 'Distribusi Suplai Benih & Pakan (40%)', 'deskripsi' => 'Keterlambatan pengiriman bibit nila/lele unggul di wilayah kepulauan dan NTB.', 'icon' => 'fa-truck-fast', 'color' => 'warning'],
            ['judul' => 'Pemasangan Instalasi Kelistrikan & Aerator (35%)', 'deskripsi' => 'Kendala tegangan listrik tiga fasa di beberapa desa terpencil untuk aerator 24 jam.', 'icon' => 'fa-bolt', 'color' => 'danger'],
            ['judul' => 'Pengerasan Terpal & Rangka Kolam (25%)', 'deskripsi' => 'Penyesuaian kemiringan dasar kolam pembuangan limbah flok agar optimal.', 'icon' => 'fa-water', 'color' => 'teal-light'],
        ];

        $actionPlans = [
            [
                'prioritas' => 'Penyediaan Genset Darurat & Stabilizer Listrik',
                'deskripsi' => 'Pengadaan genset cadangan untuk 12 kolam di kawasan rawan pemadaman guna menjaga saturasi oksigen flok.',
                'wilayah' => 'NTB & NTT',
                'pic_singkatan' => 'TEK',
                'pic' => 'Tim Teknis Aerasi',
                'tenggat' => '15 Jul 2026',
                'class' => 'text-danger'
            ],
            [
                'prioritas' => 'Akselerasi Pengiriman Benih Bersertifikat',
                'deskripsi' => 'Koordinasi dengan Balai Perikanan Budidaya Air Tawar (BPBAT) untuk pengiriman tepat waktu.',
                'wilayah' => 'Jawa Barat & Jateng',
                'pic_singkatan' => 'PRO',
                'pic' => 'PPK Budidaya',
                'tenggat' => '20 Jul 2026',
                'class' => 'text-warning'
            ],
            [
                'prioritas' => 'Verifikasi Kesiapan Bak & Kualitas Air Awal',
                'deskripsi' => 'Pengujian probiotik dan penumbuhan flok perdana sebelum penebaran benih massal.',
                'wilayah' => 'Seluruh Wilayah',
                'pic_singkatan' => 'VER',
                'pic' => 'Tim Verifikasi Kualitas',
                'tenggat' => '25 Jul 2026',
                'class' => 'text-success'
            ],
        ];

        return view('programs.bioflok.dashboard.progres-fisik', compact(
            'activeProgram',
            'stats',
            'filter_batches',
            'regionalData',
            'kendala',
            'actionPlans'
        ));
    }

    public function produksi(Request $request)
    {
        $this->checkAuth();
        $activeProgram = 'Bioflok';

        // Filter parameters
        $bulan = $request->get('bulan', '');

        // Query aggregated production data per KDMP
        $produksiQuery = DB::connection('mysql_bioflok')
            ->table('monitoring_produksi')
            ->select(
                'kdmp_id',
                DB::raw('SUM(volume_panen_kg) as total_volume_kg'),
                DB::raw('SUM(nilai_produksi) as total_nilai'),
                DB::raw('MAX(tanggal) as last_tanggal')
            );

        // Apply month filter if provided (bulan is numeric 1-12)
        if ($bulan) {
            $produksiQuery->whereMonth('tanggal', (int) $bulan)
                ->whereYear('tanggal', date('Y'));
        }

        $produksiQuery->groupBy('kdmp_id');
        $produksiPerKdmp = $produksiQuery->get()->keyBy('kdmp_id');

        // Get all KDMP data
        $allKdmp = DB::connection('mysql_bioflok')
            ->table('kdmp')
            ->select('id', 'nama_kdkmp', 'provinsi', 'kabupaten', 'desa', 'komoditas', 'long', 'lat')
            ->get();

        // === KPI Cards ===
        $totalKdmp = $allKdmp->count();
        $sudahPanen = $produksiPerKdmp->filter(fn($p) => $p->total_volume_kg > 0)->count();
        $belumPanen = $totalKdmp - $sudahPanen;
        $totalVolumeKg = $produksiPerKdmp->sum('total_volume_kg');
        $totalNilai = $produksiPerKdmp->sum('total_nilai');

        $kpi = [
            'total_kdmp'         => $totalKdmp,
            'sudah_panen'        => $sudahPanen,
            'persen_panen'       => $totalKdmp > 0 ? round(($sudahPanen / $totalKdmp) * 100, 1) : 0,
            'total_volume_panen' => round($totalVolumeKg / 1000, 1), // Ton
            'total_nilai_panen'  => round($totalNilai / 1000000000, 2), // Miliar Rp
            'belum_panen'        => $belumPanen,
            'persen_belum'       => $totalKdmp > 0 ? round(($belumPanen / $totalKdmp) * 100, 1) : 0,
        ];

        // === Scatter Plot Data ===
        $scatterData = [];
        foreach ($allKdmp as $kdmp) {
            $produksi = $produksiPerKdmp->get($kdmp->id);
            if ($produksi && $produksi->total_volume_kg > 0) {
                $volumeTon = round($produksi->total_volume_kg / 1000, 2);
                $nilaiJuta = round($produksi->total_nilai / 1000000, 1);
                $hargaPerKg = $produksi->total_volume_kg > 0
                    ? round($produksi->total_nilai / $produksi->total_volume_kg)
                    : 0;

                $scatterData[] = [
                    'name'     => $kdmp->nama_kdkmp,
                    'volume'   => $volumeTon,
                    'nilai'    => $nilaiJuta,
                    'harga'    => $hargaPerKg,
                    'provinsi' => $kdmp->provinsi,
                ];
            }
        }

        // === Table Data Produksi KDKMP ===
        $tableProduksi = [];
        $no = 1;

        // Sort KDMP by volume descending (yang sudah panen di atas, belum panen di bawah)
        $sortedKdmp = $allKdmp->sortByDesc(function ($kdmp) use ($produksiPerKdmp) {
            $produksi = $produksiPerKdmp->get($kdmp->id);
            return $produksi ? $produksi->total_volume_kg : 0;
        });

        foreach ($sortedKdmp as $kdmp) {
            $produksi = $produksiPerKdmp->get($kdmp->id);
            $volumeKg = $produksi ? (float) $produksi->total_volume_kg : 0;
            $nilai = $produksi ? (float) $produksi->total_nilai : 0;
            $hargaJual = $volumeKg > 0 ? round($nilai / $volumeKg) : 0;

            $tableProduksi[] = [
                'no'         => $no++,
                'kdkmp'      => $kdmp->nama_kdkmp,
                'lokasi'     => 'Kab. ' . $kdmp->kabupaten . ', ' . $kdmp->provinsi,
                'volume'     => round($volumeKg / 1000, 2), // Simpan dalam Ton untuk scatter consistency
                'volume_kg'  => $volumeKg,
                'nilai'      => $nilai,
                'harga_jual' => $hargaJual,
            ];
        }

        // === Horizontal Bar Chart Data (Bulanan, Mingguan, Tahunan) ===
        $rawMonitoring = DB::connection('mysql_bioflok')
            ->table('monitoring_produksi')
            ->select('tanggal', 'volume_panen_kg', 'nilai_produksi')
            ->whereNotNull('tanggal')
            ->orderBy('tanggal')
            ->get();

        // 1. Bulanan (Monthly - 12 Bulan Tahun Berjalan atau yang tersedia)
        $bulananMap = [];
        $indonesianMonths = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
            7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];
        $currentYear = date('Y');
        for ($m = 1; $m <= 12; $m++) {
            $key = sprintf("%04d-%02d", $currentYear, $m);
            $bulananMap[$key] = [
                'label' => $indonesianMonths[$m] . ' ' . $currentYear,
                'volume' => 0,
                'nilai' => 0
            ];
        }
        foreach ($rawMonitoring as $row) {
            $time = strtotime($row->tanggal);
            $key = date('Y-m', $time);
            if (!isset($bulananMap[$key])) {
                $m = (int) date('m', $time);
                $y = date('Y', $time);
                $bulananMap[$key] = [
                    'label' => ($indonesianMonths[$m] ?? date('M', $time)) . ' ' . $y,
                    'volume' => 0,
                    'nilai' => 0
                ];
            }
            $bulananMap[$key]['volume'] += (float) $row->volume_panen_kg / 1000; // Ton
            $bulananMap[$key]['nilai'] += (float) $row->nilai_produksi / 1000000; // Juta Rp
        }
        ksort($bulananMap);
        $bulananList = array_values($bulananMap);

        // 2. Mingguan (Weekly - 10 Minggu terakhir / tersedia)
        $mingguanMap = [];
        for ($w = 9; $w >= 0; $w--) {
            $t = strtotime("-$w weeks");
            $key = date('o-W', $t);
            $weekNum = (int) date('W', $t);
            $mingguanMap[$key] = [
                'label' => 'Mg ke-' . $weekNum . ' (' . date('y', $t) . ')',
                'volume' => 0,
                'nilai' => 0
            ];
        }
        foreach ($rawMonitoring as $row) {
            $time = strtotime($row->tanggal);
            $key = date('o-W', $time);
            $weekNum = (int) date('W', $time);
            if (!isset($mingguanMap[$key])) {
                $mingguanMap[$key] = [
                    'label' => 'Mg ke-' . $weekNum . ' (' . date('y', $time) . ')',
                    'volume' => 0,
                    'nilai' => 0
                ];
            }
            $mingguanMap[$key]['volume'] += (float) $row->volume_panen_kg / 1000;
            $mingguanMap[$key]['nilai'] += (float) $row->nilai_produksi / 1000000;
        }
        ksort($mingguanMap);
        $mingguanList = array_slice(array_values($mingguanMap), -10);

        // 3. Tahunan (Yearly - minimal 3 tahun terakhir)
        $tahunanMap = [];
        for ($y = $currentYear - 2; $y <= $currentYear; $y++) {
            $tahunanMap[(string)$y] = [
                'label' => (string) $y,
                'volume' => 0,
                'nilai' => 0
            ];
        }
        foreach ($rawMonitoring as $row) {
            $y = date('Y', strtotime($row->tanggal));
            if (!isset($tahunanMap[$y])) {
                $tahunanMap[$y] = [
                    'label' => (string) $y,
                    'volume' => 0,
                    'nilai' => 0
                ];
            }
            $tahunanMap[$y]['volume'] += (float) $row->volume_panen_kg / 1000;
            $tahunanMap[$y]['nilai'] += (float) $row->nilai_produksi / 1000000;
        }
        ksort($tahunanMap);
        $tahunanList = array_values($tahunanMap);

        $barChartPeriods = [
            'bulanan' => [
                'categories' => array_column($bulananList, 'label'),
                'volume' => array_map(fn($v) => round($v, 2), array_column($bulananList, 'volume')),
                'nilai' => array_map(fn($v) => round($v, 1), array_column($bulananList, 'nilai')),
            ],
            'mingguan' => [
                'categories' => array_column($mingguanList, 'label'),
                'volume' => array_map(fn($v) => round($v, 2), array_column($mingguanList, 'volume')),
                'nilai' => array_map(fn($v) => round($v, 1), array_column($mingguanList, 'nilai')),
            ],
            'tahunan' => [
                'categories' => array_column($tahunanList, 'label'),
                'volume' => array_map(fn($v) => round($v, 2), array_column($tahunanList, 'volume')),
                'nilai' => array_map(fn($v) => round($v, 1), array_column($tahunanList, 'nilai')),
            ],
        ];

        return view('programs.bioflok.dashboard.produksi', compact(
            'activeProgram',
            'kpi',
            'scatterData',
            'tableProduksi',
            'bulan',
            'barChartPeriods'
        ));
    }

    public function exportProduksiPdf(Request $request)
    {
        $this->checkAuth();
        \Illuminate\Support\Facades\Gate::authorize('generate-pdf');

        $bulan = $request->get('bulan', '');

        $produksiQuery = DB::connection('mysql_bioflok')
            ->table('monitoring_produksi')
            ->select(
                'kdmp_id',
                DB::raw('SUM(volume_panen_kg) as total_volume_kg'),
                DB::raw('SUM(nilai_produksi) as total_nilai'),
                DB::raw('MAX(tanggal) as last_tanggal')
            );

        if ($bulan) {
            $produksiQuery->whereMonth('tanggal', (int) $bulan)
                ->whereYear('tanggal', date('Y'));
        }

        $produksiQuery->groupBy('kdmp_id');
        $produksiPerKdmp = $produksiQuery->get()->keyBy('kdmp_id');

        $allKdmp = DB::connection('mysql_bioflok')
            ->table('kdmp')
            ->select('id', 'nama_kdkmp', 'provinsi', 'kabupaten', 'desa', 'komoditas', 'long', 'lat')
            ->get();

        $totalKdmp = $allKdmp->count();
        $sudahPanen = $produksiPerKdmp->filter(fn($p) => $p->total_volume_kg > 0)->count();
        $belumPanen = $totalKdmp - $sudahPanen;
        $totalVolumeKg = $produksiPerKdmp->sum('total_volume_kg');
        $totalNilai = $produksiPerKdmp->sum('total_nilai');

        $tableProduksi = [];
        $no = 1;

        $sortedKdmp = $allKdmp->sortByDesc(function ($kdmp) use ($produksiPerKdmp) {
            $produksi = $produksiPerKdmp->get($kdmp->id);
            return $produksi ? $produksi->total_volume_kg : 0;
        });

        foreach ($sortedKdmp as $kdmp) {
            $produksi = $produksiPerKdmp->get($kdmp->id);
            $volumeKg = $produksi ? (float) $produksi->total_volume_kg : 0;
            $nilai = $produksi ? (float) $produksi->total_nilai : 0;
            $hargaJual = $volumeKg > 0 ? round($nilai / $volumeKg) : 0;

            $tableProduksi[] = [
                'no'         => $no++,
                'kdkmp'      => $kdmp->nama_kdkmp,
                'lokasi'     => 'Kab. ' . $kdmp->kabupaten . ', ' . $kdmp->provinsi,
                'volume'     => round($volumeKg / 1000, 2),
                'volume_kg'  => $volumeKg,
                'nilai'      => $nilai,
                'harga_jual' => $hargaJual,
            ];
        }

        $indonesianMonths = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $bulanName = $bulan && isset($indonesianMonths[(int)$bulan]) ? $indonesianMonths[(int)$bulan] . ' ' . date('Y') : 'Keseluruhan / Semua Bulan';

        $filename = "Data_Produksi_Bioflok_" . ($bulan ? "Bulan_{$bulan}_" : "") . date('Ymd_His') . ".pdf";

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('programs.bioflok.dashboard.pdf-produksi', [
            'data' => $tableProduksi,
            'totalKdmp' => $totalKdmp,
            'sudahPanen' => $sudahPanen,
            'belumPanen' => $belumPanen,
            'totalVolumeKg' => $totalVolumeKg,
            'totalNilai' => $totalNilai,
            'bulanName' => $bulanName,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream($filename);
    }
}

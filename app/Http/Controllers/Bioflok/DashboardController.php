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
                'nilai'      => $nilai,
                'harga_jual' => $hargaJual,
            ];
        }

        return view('programs.bioflok.dashboard.produksi', compact(
            'activeProgram',
            'kpi',
            'scatterData',
            'tableProduksi',
            'bulan'
        ));
    }
}

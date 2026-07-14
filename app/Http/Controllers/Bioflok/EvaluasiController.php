<?php

namespace App\Http\Controllers\Bioflok;

use App\Http\Controllers\ProgramBaseController;
use Illuminate\Http\Request;

class EvaluasiController extends ProgramBaseController
{
    public function progresFisik(Request $request)
    {
        $this->checkAuth();
        $activeProgram = 'Budidaya Tematik';

        $filter_batches = [
            ['id' => 1, 'name' => 'Tahap I - 2025/2026'],
            ['id' => 2, 'name' => 'Tahap II - Akselerasi 2026'],
        ];

        $stats = [
            'konstruksi_aktif' => 17,
            'rata_progres' => 78.4,
            'total_selesai' => 28,
            'kritis_terlambat' => 4,
            'deviasi_negatif' => 6
        ];

        $listLokasi = [
            [
                'no' => 1,
                'kdkmp' => 'KDKMP Lombok Timur 2',
                'kabupaten' => 'Kab. Lombok Timur, NTB',
                'batch' => 'Tahap I - 2025/2026',
                'progres_rencana' => 85.0,
                'progres_aktual' => 62.0,
                'deviasi' => -23.0,
                'status_kurva' => 'Terlambat Kritis',
                'status_class' => 'bg-danger/10 text-danger border-danger/20',
                'catatan_audit' => 'Keterlambatan pengecoran bak kolam akibat curah hujan dan pengiriman material terhambat.'
            ],
            [
                'no' => 2,
                'kdkmp' => 'KDKMP Kupang Timur',
                'kabupaten' => 'Kab. Kupang, NTT',
                'batch' => 'Tahap I - 2025/2026',
                'progres_rencana' => 80.0,
                'progres_aktual' => 64.5,
                'deviasi' => -15.5,
                'status_kurva' => 'Terlambat Kritis',
                'status_class' => 'bg-danger/10 text-danger border-danger/20',
                'catatan_audit' => 'Instalasi aerator belum tiba di lokasi proyek.'
            ],
            [
                'no' => 3,
                'kdkmp' => 'KDKMP Sumbawa Besar',
                'kabupaten' => 'Kab. Sumbawa, NTB',
                'batch' => 'Tahap II - Akselerasi',
                'progres_rencana' => 70.0,
                'progres_aktual' => 58.0,
                'deviasi' => -12.0,
                'status_kurva' => 'Perlu Perhatian',
                'status_class' => 'bg-warning/10 text-warning border-warning/20',
                'catatan_audit' => 'Kendala pasokan listrik untuk mesin pompa aerasi.'
            ],
            [
                'no' => 4,
                'kdkmp' => 'KDKMP Cirebon Utara',
                'kabupaten' => 'Kab. Cirebon, Jawa Barat',
                'batch' => 'Tahap II - Akselerasi',
                'progres_rencana' => 75.0,
                'progres_aktual' => 73.0,
                'deviasi' => -2.0,
                'status_kurva' => 'Wajar / On-Track',
                'status_class' => 'bg-teal-light/10 text-teal-light border-teal-light/20',
                'catatan_audit' => 'Finishing lapisan terpal kolam pembesaran sedang berjalan.'
            ],
            [
                'no' => 5,
                'kdkmp' => 'KDKMP Pati Mandiri',
                'kabupaten' => 'Kab. Pati, Jawa Tengah',
                'batch' => 'Tahap II - Akselerasi',
                'progres_rencana' => 75.0,
                'progres_aktual' => 76.5,
                'deviasi' => 1.5,
                'status_kurva' => 'Lebih Cepat',
                'status_class' => 'bg-success/10 text-success border-success/20',
                'catatan_audit' => 'Seluruh bak pembesaran siap diisi air awal minggu depan.'
            ],
            [
                'no' => 6,
                'kdkmp' => 'KDKMP Banyuwangi Asri',
                'kabupaten' => 'Kab. Banyuwangi, Jawa Timur',
                'batch' => 'Tahap I - 2025/2026',
                'progres_rencana' => 100.0,
                'progres_aktual' => 100.0,
                'deviasi' => 0.0,
                'status_kurva' => 'Selesai 100%',
                'status_class' => 'bg-success/10 text-success border-success/20',
                'catatan_audit' => 'Proyek rampung dan telah operasional panen siklus perdana.'
            ]
        ];

        return view('programs.budidaya-tematik.evaluasi.progres-fisik', compact(
            'activeProgram',
            'stats',
            'filter_batches',
            'listLokasi'
        ));
    }

    public function produksi(Request $request)
    {
        $this->checkAuth();
        $activeProgram = 'Budidaya Tematik';

        $stats = [
            'total_target_panen' => 150.0, // Ton
            'total_realisasi_panen' => 142.8, // Ton
            'persentase_capaian' => 95.2,
            'survival_rate_rata' => 86.5, // %
            'fcr_rata' => 1.25, // Feed Conversion Ratio
            'lokasi_diatas_target' => 24,
            'lokasi_dibawah_target' => 4,
        ];

        $chartEvaluasi = [
            'categories' => ['Karawang', 'Subang', 'Indramayu', 'Brebes', 'Kendal', 'Demak', 'Lamongan', 'Gresik', 'Banyuwangi', 'Lombok Tim', 'Sumbawa', 'Kupang'],
            'target' => [8.0, 11.5, 10.0, 7.0, 9.0, 7.5, 14.0, 11.0, 14.5, 6.0, 6.5, 5.5],
            'realisasi' => [8.5, 12.0, 10.2, 6.8, 9.4, 7.5, 14.5, 11.8, 15.2, 5.4, 6.1, 4.8]
        ];

        $listEvaluasiProduksi = [
            [
                'no' => 1,
                'kdkmp' => 'KDKMP Banyuwangi Asri',
                'lokasi' => 'Kab. Banyuwangi, Jawa Timur',
                'target_ton' => 14.5,
                'realisasi_ton' => 15.2,
                'capaian_persen' => 104.8,
                'survival_rate' => '91.2%',
                'fcr' => '1.18',
                'status_evaluasi' => 'Melampaui Target',
                'badge_class' => 'bg-success/10 text-success border-success/20',
                'rekomendasi' => 'Dipertahankan sebagai percontohan nasional pengelolaan pakan probiotik.'
            ],
            [
                'no' => 2,
                'kdkmp' => 'KDKMP Lamongan Makmur',
                'lokasi' => 'Kab. Lamongan, Jawa Timur',
                'target_ton' => 14.0,
                'realisasi_ton' => 14.5,
                'capaian_persen' => 103.5,
                'survival_rate' => '89.5%',
                'fcr' => '1.20',
                'status_evaluasi' => 'Melampaui Target',
                'badge_class' => 'bg-success/10 text-success border-success/20',
                'rekomendasi' => 'Kinerja aerasi sangat stabil, penjadwalan panen bertahap efisien.'
            ],
            [
                'no' => 3,
                'kdkmp' => 'KDKMP Subang Maju',
                'lokasi' => 'Kab. Subang, Jawa Barat',
                'target_ton' => 11.5,
                'realisasi_ton' => 12.0,
                'capaian_persen' => 104.3,
                'survival_rate' => '88.4%',
                'fcr' => '1.22',
                'status_evaluasi' => 'Sesuai Target',
                'badge_class' => 'bg-teal-light/10 text-teal-light border-teal-light/20',
                'rekomendasi' => 'Manajemen kualitas air flok berjalan optimal selama siklus pemeliharaan.'
            ],
            [
                'no' => 4,
                'kdkmp' => 'KDKMP Gresik Mandiri',
                'lokasi' => 'Kab. Gresik, Jawa Timur',
                'target_ton' => 11.0,
                'realisasi_ton' => 11.8,
                'capaian_persen' => 107.2,
                'survival_rate' => '89.0%',
                'fcr' => '1.21',
                'status_evaluasi' => 'Melampaui Target',
                'badge_class' => 'bg-success/10 text-success border-success/20',
                'rekomendasi' => 'Penebaran padat tebar tepat, tingkat kanibalisme benih rendah.'
            ],
            [
                'no' => 5,
                'kdkmp' => 'KDKMP Karawang Timur',
                'lokasi' => 'Kab. Karawang, Jawa Barat',
                'target_ton' => 8.0,
                'realisasi_ton' => 8.5,
                'capaian_persen' => 106.2,
                'survival_rate' => '87.5%',
                'fcr' => '1.24',
                'status_evaluasi' => 'Sesuai Target',
                'badge_class' => 'bg-teal-light/10 text-teal-light border-teal-light/20',
                'rekomendasi' => 'Perlu penambahan paranet pelindung panas untuk menstabilkan suhu siang hari.'
            ],
            [
                'no' => 6,
                'kdkmp' => 'KDKMP Brebes Sejahtera',
                'lokasi' => 'Kab. Brebes, Jawa Tengah',
                'target_ton' => 7.0,
                'realisasi_ton' => 6.8,
                'capaian_persen' => 97.1,
                'survival_rate' => '84.0%',
                'fcr' => '1.30',
                'status_evaluasi' => 'Sesuai Target',
                'badge_class' => 'bg-teal-light/10 text-teal-light border-teal-light/20',
                'rekomendasi' => 'Suhu air berfluktuasi saat malam hari, disarankan penambahan probiotik penyeimbang.'
            ],
            [
                'no' => 7,
                'kdkmp' => 'KDKMP Lombok Timur 1',
                'lokasi' => 'Kab. Lombok Timur, NTB',
                'target_ton' => 6.0,
                'realisasi_ton' => 5.4,
                'capaian_persen' => 90.0,
                'survival_rate' => '81.5%',
                'fcr' => '1.38',
                'status_evaluasi' => 'Perlu Perhatian',
                'badge_class' => 'bg-warning/10 text-warning border-warning/20',
                'rekomendasi' => 'Terjadi pemadaman listrik berkala yang mengurangi aktivitas makan ikan.'
            ],
            [
                'no' => 8,
                'kdkmp' => 'KDKMP Kupang Permai',
                'lokasi' => 'Kota Kupang, NTT',
                'target_ton' => 5.5,
                'realisasi_ton' => 4.8,
                'capaian_persen' => 87.2,
                'survival_rate' => '78.0%',
                'fcr' => '1.45',
                'status_evaluasi' => 'Under-performing',
                'badge_class' => 'bg-danger/10 text-danger border-danger/20',
                'rekomendasi' => 'Bimbingan teknis intensif terkait pembuatan dan maintenance flok bakteri pada air kapur.'
            ]
        ];

        return view('programs.budidaya-tematik.evaluasi.produksi', compact(
            'activeProgram',
            'stats',
            'chartEvaluasi',
            'listEvaluasiProduksi'
        ));
    }
}

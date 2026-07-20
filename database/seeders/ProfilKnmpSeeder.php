<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfilKnmpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sql = "INSERT INTO profil_knmp (knmp_id, jml_kk, jml_nelayan, komoditas, penjualan_ikan, jml_hari_melaut, pend_avg_saat_ini, pend_avg_intervensi, vol_produksi_daerah, nilai_produksi_daerah, vol_produksi_intervensi, nilai_produksi_intervensi, serapan_tenaga_kerja, created_at, updated_at) VALUES
(1, 256, 300, 'Kerapu,Kapal,Kwe,Teri', NULL, NULL, 4.3, 5.3, 785.0, 11.77, 893.5, 15.19, 348, NOW(), NOW()),
(2, 460, 476, 'KakapMerah,Kerapu,Bawal', NULL, NULL, 1.7, 3.6, 520.0, 7.8, 624.0, 10.68, 524, NOW(), NOW()),
(3, 277, 300, 'Tongkol,TunaSiripKuning,Teri', NULL, NULL, 4.8, 6.0, 1111.0, 16.65, 1196.0, 20.33, 348, NOW(), NOW()),
(4, 250, 310, 'Rajungan,Ikan Kembung', NULL, NULL, 2.9, 3.6, 300.0, 4.5, 375.0, 6.38, 358, NOW(), NOW()),
(5, 467, 203, 'Tongkol,Layur,Marlin', NULL, NULL, 3.7, 4.6, 165.0, 2.48, 228.0, 3.88, 251, NOW(), NOW()),
(6, 300, 120, 'Kakap,Bawal,Tuna,Udang', NULL, NULL, 3.2, 4.0, 180.0, 2.7, 246.0, 4.18, 168, NOW(), NOW()),
(7, 1423, 827, 'Udang,Sembilang Betul,Bawal', NULL, NULL, 2.0, 4.0, 4789.0, 71.8, 5986.0, 89.75, 857, NOW(), NOW()),
(8, 937, 693, 'Cumi,Kembung,Udang,Bawal', NULL, NULL, 5.0, 7.1, 1200.0, 18.0, 1290.0, 21.9, 741, NOW(), NOW()),
(9, 1612, 799, 'Kembung,Tongkol,Kuniran,Udang', NULL, NULL, 1.8, 3.0, 700.0, 10.5, 800.0, 13.6, 847, NOW(), NOW()),
(10, 300, 200, 'Cumi,Kembung,Tongkol,Kakap', NULL, NULL, 3.2, 4.0, 180.0, 2.7, 246.0, 4.18, 248, NOW(), NOW()),
(11, 2156, 3594, 'Cumi,Kembung,Udang,Bawal', NULL, NULL, 2.36, 2.9, 4000.0, 60.0, 4320.0, 71.9, 3, NOW(), NOW()),
(12, 300, 266, 'Rajungan,Teri,Baronang,Kerapu', NULL, NULL, 4.7, 6.38, 700.0, 10.5, 800.0, 13.6, 314, NOW(), NOW()),
(13, 1000, 500, 'Kepiting,Sotong,Cumi,Kerapu', NULL, NULL, 6.6, 8.9, 450.0, 6.75, 547.0, 9.31, 548, NOW(), NOW()),
(14, 300, 200, 'Kerapu,Kepiting,Sagai,Cumi', NULL, NULL, 4.9, 6.1, 360.0, 5.4, 444.0, 7.55, 248, NOW(), NOW()),
(15, 4801, 298, 'SelarKuning,Cakalng,BawalHitam', NULL, NULL, 3.3, 4.2, 400.0, 6.0, 490.0, 8.33, 346, NOW(), NOW()),
(16, 300, 679, 'Teri,Kembung,Cakalang,Tongkol', NULL, NULL, 3.27, 4.0, 951.0, 14.6, 1075.0, 18.29, 727, NOW(), NOW()),
(17, 350, 300, 'KakapMerah,Layur,Belanak,Bawal', NULL, NULL, 2.2, 3.1, 240.0, 3.6, 318.0, 5.41, 348, NOW(), NOW()),
(18, 500, 400, 'Tongkol,Layur,Lobster,Cumi', NULL, NULL, 2.4, 3.0, 560.0, 8.4, 674.0, 11.46, 448, NOW(), NOW()),
(19, 1703, 600, 'Rajungan,Cumi,Udang', NULL, NULL, 2.5, 3.2, 1100.0, 16.5, 1185.0, 20.1, 648, NOW(), NOW()),
(20, 2305, 581, 'Layur,Tenggiri,Kembung,Tongkol', NULL, NULL, 3.3, 4.19, 1100.0, 15.0, 1080.0, 18.3, 629, NOW(), NOW()),
(21, 297, 310, 'Kembung,Tongkol,Layur', NULL, NULL, 3.9, 4.9, 675.0, 10.0, 772.0, 13.1, 358, NOW(), NOW()),
(22, 5000, 4349, 'Kembung,Layang,Layur', NULL, NULL, 2.3, 3.1, 7792.0, 116.0, 8211.0, 139.0, 4377, NOW(), NOW()),
(23, 4324, 230, 'Layur,Trejet,Tongkol', NULL, NULL, 2.0, 2.55, 276.0, 4.14, 361.0, 6.14, 278, NOW(), NOW()),
(24, 500, 400, 'Manyung,Belanak,Kerapu', NULL, NULL, 4.8, 7.88, 300.0, 4.5, 375.0, 6.38, 448, NOW(), NOW()),
(25, 385, 214, 'Bawal,Lobster,Layur,Tenggiri', NULL, NULL, 2.1, 2.64, 212.0, 3.1, 284.0, 4.83, 262, NOW(), NOW()),
(26, 519, 250, 'Bawal,Layur,Kepiting,Lobster', NULL, NULL, 2.3, 2.99, 300.0, 4.5, 375.0, 6.38, 298, NOW(), NOW()),
(27, 224, 120, 'Layur,Trejet,Tongkol Ekor Kucing', NULL, NULL, 4.3, 5.38, 200.0, 3.0, 270.0, 4.59, 168, NOW(), NOW()),
(28, 224, 120, 'Layur,Trejet,Tongkol Eko Kucing', NULL, NULL, 5.0, 5.85, 250.0, 3.75, 330.0, 5.61, 168, NOW(), NOW()),
(29, 1400, 1200, 'Pelagis Kecil,Kuniran,Kerapu', NULL, NULL, 1.7, 3.0, 1440.0, 21.4, 1542.0, 26.0, 1248, NOW(), NOW()),
(30, 2500, 865, 'Tongkol,Lemuru,Layur', NULL, NULL, 2.7, 3.39, 1200.0, 18.0, 1290.0, 21.9, 913, NOW(), NOW()),
(31, 1893, 895, 'Banyar,Lemuru,Slengseng,Tongkol', NULL, NULL, 3.4, 4.3, 1500.0, 22.5, 1605.0, 27.2, 943, NOW(), NOW()),
(32, 1400, 1000, 'PelagisKecil,Karang,Tongkol', NULL, NULL, 1.83, 2.8, 1300.0, 19.5, 1395.0, 23.72, 1048, NOW(), NOW()),
(33, 1132, 408, 'Tongkol,Cakalang,Kembung,Udang', NULL, NULL, 2.2, 2.76, 490.0, 7.34, 598.0, 10.08, 456, NOW(), NOW()),
(34, 1226, 854, 'Tongkol,Cakalang,Kembung,Udang', NULL, NULL, 2.2, 3.5, 490.0, 7.34, 598.0, 10.08, 902, NOW(), NOW()),
(35, 1093, 1020, 'Tongkol,Cakalang,Kembung,Udang', NULL, NULL, 3.2, 4.19, 1224.0, 18.3, 1315.0, 22.3, 1068, NOW(), NOW()),
(36, 750, 517, 'Tongkol', NULL, NULL, 4.8, 6.3, 620.0, 9.31, 743.0, 12.6, 565, NOW(), NOW()),
(37, 251, 263, 'Tongkol,Tembang,Layang', NULL, NULL, 2.2, 2.84, 280.0, 4.2, 352.0, 5.98, 311, NOW(), NOW()),
(38, 200, 220, 'Tuna,Cakalng,Tongkol,Layang', NULL, NULL, 2.39, 2.99, 300.0, 4.5, 375.0, 6.38, 268, NOW(), NOW()),
(39, 200, 220, 'Tuna,Cakalng,Tongkol,Layang', NULL, NULL, 2.48, 3.1, 180.0, 2.7, 246.0, 4.18, 268, NOW(), NOW()),
(40, 1093, 1020, 'Sunu,Kakap Merah,Kerapu', NULL, NULL, 4.9, 6.0, 1224.0, 18.3, 1315.0, 22.3, 1068, NOW(), NOW()),
(41, 358, 426, 'Rajungan,Pelagis Kecil,Ikan Dasar', NULL, NULL, 3.3, 4.1, 693.0, 10.4, 792.0, 13.4, 510, NOW(), NOW()),
(42, 300, 200, 'Kembung,Tenggiri,Cumi,Tongkol', NULL, NULL, 4.2, 5.2, 400.0, 6.0, 490.0, 8.33, 248, NOW(), NOW()),
(43, 231, 681, 'Toman,Biawan,Lais,Patik', NULL, NULL, 4.3, 5.89, 817.0, 12.26, 928.0, 15.79, 729, NOW(), NOW()),
(44, 1745, 530, 'Tuna Mata Besar,Layang,Kakap', NULL, NULL, 2.4, 3.1, 795.0, 11.93, 904.0, 15.3, 578, NOW(), NOW()),
(45, 723, 326, 'Cakalang,Tuna,Katamba,Kakap', NULL, NULL, 4.3, 5.4, 690.0, 10.0, 789.0, 13.4, 374, NOW(), NOW()),
(46, 250, 470, 'Cakalang,Layang,Kakap,Baronang', NULL, NULL, 3.4, 4.3, 705.0, 10.58, 904.0, 15.38, 518, NOW(), NOW()),
(47, 1332, 554, 'Tuna,Cakalang', NULL, NULL, 3.2, 4.38, 831.0, 12.4, 944.0, 16.0, 602, NOW(), NOW()),
(48, 1130, 530, 'Bandeng,Cakalang,Katamba,Layang', NULL, NULL, 4.4, 5.92, 795.0, 11.93, 904.0, 15.38, 578, NOW(), NOW()),
(49, 566, 300, 'Kembung,Layang,Sarden,Kepiting', NULL, NULL, 2.8, 3.55, 450.0, 6.75, 547.0, 9.31, 348, NOW(), NOW()),
(50, 220, 184, 'Kembung,Rajungan', NULL, NULL, 2.84, 3.98, 200.0, 3.0, 270.0, 4.59, 232, NOW(), NOW()),
(51, 939, 528, 'Rajungan,Ikan Demersal', NULL, NULL, 2.6, 3.6, 760.0, 11.4, 866.0, 14.7, 576, NOW(), NOW()),
(52, 387, 115, 'Tuna,Cakalang,Selar,Kembung', NULL, NULL, 3.8, 5.2, 173.0, 2.59, 237.0, 4.0, 163, NOW(), NOW()),
(53, 835, 612, 'Selar,Cakalang,Tuna', NULL, NULL, 3.2, 4.0, 1000.0, 15.0, 1080.0, 18.3, 660, NOW(), NOW()),
(54, 564, 816, 'Tenggiri,Katamba,Kerapu,Kuwe', NULL, NULL, 2.8, 3.6, 854.0, 12.8, 969.0, 16.48, 864, NOW(), NOW()),
(55, 556, 245, 'Cakalang,Tuna,Selar', NULL, NULL, 3.8, 4.7, 368.0, 5.5, 452.0, 7.69, 293, NOW(), NOW()),
(56, 916, 309, 'Tuna,Tongkol,Kakap,Tenggiri', NULL, NULL, 2.6, 3.36, 464.0, 6.95, 563.0, 9.57, 357, NOW(), NOW()),
(57, 251, 263, 'Kerapu,Senu,KakapMerah', NULL, NULL, 2.2, 3.1, 206.4, 3.0, 277.0, 4.72, 311, NOW(), NOW()),
(58, 206, 360, 'Tuna,Cakalang', NULL, NULL, 2.8, 3.5, 330.0, 4.9, 409.0, 6.69, 408, NOW(), NOW()),
(59, 420, 300, 'Pelagis,Demersal', NULL, NULL, 2.7, 3.45, 450.0, 6.75, 547.0, 9.31, 348, NOW(), NOW()),
(60, 357, 242, 'Pelagis,Demersal', NULL, NULL, 2.47, 3.4, 275.16, 4.1, 360.0, 6.12, 290, NOW(), NOW()),
(61, 294, 192, 'Tuna,Cakalang,Ikan Demersal', NULL, NULL, 3.2, 4.0, 288.0, 4.32, 361.0, 6.12, 240, NOW(), NOW()),
(62, 187, 125, 'Layang,Kuwe,Tongkol Komo', NULL, NULL, 3.5, 4.5, 157.6, 2.3, 219.0, 3737.0, 173, NOW(), NOW()),
(63, 340, 329, 'Tuna,Cakalang,Tongkol', NULL, NULL, 4.0, 5.0, 428.0, 6.42, 521.0, 8.87, 377, NOW(), NOW()),
(64, 300, 252, 'Tuna,Tengiri Ikan Demersal', NULL, NULL, 4.2, 5.33, 328.0, 4.91, 406.0, 6.9, 300, NOW(), NOW()),
(65, 433, 260, 'Gulamah,Bandeng,Kurau,Tenggiri', NULL, NULL, 4.0, 5.0, 520.0, 7.8, 628.0, 10.68, 308, NOW(), NOW());";
        \Illuminate\Support\Facades\DB::connection('mysql_knmp')->unprepared($sql);
    }
}

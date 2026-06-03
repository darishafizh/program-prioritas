export const modules = [
  { id: "knmp", label: "KNMP" },
  { id: "bioflok", label: "Bioflok" },
  { id: "mina-padi", label: "Mina Padi" },
  { id: "bins", label: "BINS" },
  { id: "revitalisasi-pantura", label: "Revitalisasi Pantura" },
  { id: "isf-waingapu", label: "ISF Waingapu" },
  { id: "modernisasi-kapal", label: "Modernisasi Kapal" },
  { id: "swasembada-garam", label: "Swasembada Garam" },
  { id: "modernisasi-sarpras", label: "Modernisasi SarPras Pendidikan KP" },
] as const;

export const menus = [
  { id: "dashboard", label: "Dashboard", icon: "LayoutDashboard" },
  { id: "proses-bisnis", label: "Proses Bisnis", icon: "GitBranch" },
  { id: "evaluasi", label: "Evaluasi", icon: "ClipboardCheck" },
] as const;

export type ModuleId = (typeof modules)[number]["id"];
export type MenuId = (typeof menus)[number]["id"];

// Dashboard dummy data
export function getDashboardStats() {
  return [
    { title: "Total Data", value: "1,248", change: "+12.5%", trend: "up" as const, icon: "Database" },
    { title: "Realisasi", value: "Rp 8.4 M", change: "+8.2%", trend: "up" as const, icon: "TrendingUp" },
    { title: "Target", value: "Rp 12.6 M", change: "0%", trend: "neutral" as const, icon: "Target" },
    { title: "Persentase", value: "66.7%", change: "+5.3%", trend: "up" as const, icon: "PieChart" },
  ];
}

export function getMonthlyTrendData() {
  return [
    { bulan: "Jan", realisasi: 400, target: 600 },
    { bulan: "Feb", realisasi: 520, target: 650 },
    { bulan: "Mar", realisasi: 650, target: 700 },
    { bulan: "Apr", realisasi: 780, target: 750 },
    { bulan: "Mei", realisasi: 820, target: 800 },
    { bulan: "Jun", realisasi: 900, target: 850 },
    { bulan: "Jul", realisasi: 960, target: 900 },
    { bulan: "Agt", realisasi: 1050, target: 950 },
    { bulan: "Sep", realisasi: 1100, target: 1000 },
    { bulan: "Okt", realisasi: 1180, target: 1050 },
    { bulan: "Nov", realisasi: 1200, target: 1100 },
    { bulan: "Des", realisasi: 1248, target: 1150 },
  ];
}

export function getComparisonData() {
  return [
    { kategori: "Perencanaan", target: 95, realisasi: 88 },
    { kategori: "Pelaksanaan", target: 90, realisasi: 82 },
    { kategori: "Pengawasan", target: 85, realisasi: 78 },
    { kategori: "Evaluasi", target: 88, realisasi: 85 },
    { kategori: "Pelaporan", target: 92, realisasi: 90 },
  ];
}

export function getRecentActivity() {
  return [
    { id: 1, kegiatan: "Update data realisasi Q3", pengguna: "Ahmad Fauzi", waktu: "2 jam lalu", status: "Selesai" },
    { id: 2, kegiatan: "Input laporan bulanan September", pengguna: "Siti Nurhaliza", waktu: "4 jam lalu", status: "Proses" },
    { id: 3, kegiatan: "Verifikasi dokumen pendukung", pengguna: "Budi Santoso", waktu: "6 jam lalu", status: "Selesai" },
    { id: 4, kegiatan: "Revisi target tahunan", pengguna: "Dewi Lestari", waktu: "1 hari lalu", status: "Menunggu" },
    { id: 5, kegiatan: "Upload foto dokumentasi", pengguna: "Rizky Pratama", waktu: "1 hari lalu", status: "Selesai" },
  ];
}

// Proses Bisnis dummy data
export function getProsesBisnisData() {
  return [
    { id: 1, nama: "Pembangunan Kolam Bioflok Tahap I", lokasi: "Kab. Garut, Jawa Barat", status: "Selesai", tanggal: "2024-01-15" },
    { id: 2, nama: "Rehabilitasi Tambak Udang", lokasi: "Kab. Sidoarjo, Jawa Timur", status: "Proses", tanggal: "2024-02-20" },
    { id: 3, nama: "Pengadaan Bibit Ikan Nila", lokasi: "Kab. Sleman, DIY", status: "Selesai", tanggal: "2024-03-10" },
    { id: 4, nama: "Pelatihan Budidaya Intensif", lokasi: "Kab. Karawang, Jawa Barat", status: "Proses", tanggal: "2024-04-05" },
    { id: 5, nama: "Modernisasi Alat Tangkap", lokasi: "Kab. Cilacap, Jawa Tengah", status: "Menunggu", tanggal: "2024-05-12" },
    { id: 6, nama: "Pembangunan Cold Storage", lokasi: "Kota Ambon, Maluku", status: "Selesai", tanggal: "2024-06-18" },
    { id: 7, nama: "Sertifikasi Hasil Tangkap", lokasi: "Kab. Banyuwangi, Jawa Timur", status: "Proses", tanggal: "2024-07-22" },
    { id: 8, nama: "Penyuluhan Nelayan Pesisir", lokasi: "Kab. Pangandaran, Jawa Barat", status: "Selesai", tanggal: "2024-08-30" },
    { id: 9, nama: "Revitalisasi Pelabuhan Perikanan", lokasi: "Kab. Tegal, Jawa Tengah", status: "Menunggu", tanggal: "2024-09-14" },
    { id: 10, nama: "Pengembangan Pakan Lokal", lokasi: "Kab. Tulungagung, Jawa Timur", status: "Proses", tanggal: "2024-10-08" },
  ];
}

// Evaluasi dummy data
export function getKPIData() {
  return [
    {
      id: 1,
      nama: "Indeks Kinerja Utama (IKU)",
      nilai: 78,
      target: 90,
      satuan: "%",
      status: "Perlu Peningkatan",
      deskripsi: "Capaian kinerja utama berdasarkan indikator yang telah ditetapkan dalam Renstra KKP.",
    },
    {
      id: 2,
      nama: "Tingkat Penyerapan Anggaran",
      nilai: 85,
      target: 95,
      satuan: "%",
      status: "Baik",
      deskripsi: "Persentase realisasi anggaran terhadap pagu yang telah ditetapkan dalam DIPA.",
    },
    {
      id: 3,
      nama: "Kepuasan Pemangku Kepentingan",
      nilai: 92,
      target: 88,
      satuan: "%",
      status: "Sangat Baik",
      deskripsi: "Hasil survei kepuasan masyarakat dan pemangku kepentingan terhadap layanan KKP.",
    },
  ];
}

export function getEvaluasiComparison() {
  return [
    { indikator: "Produksi Perikanan Tangkap", target: "8.5 Juta Ton", realisasi: "7.8 Juta Ton", capaian: 91.76 },
    { indikator: "Produksi Perikanan Budidaya", target: "19.5 Juta Ton", realisasi: "17.2 Juta Ton", capaian: 88.21 },
    { indikator: "Nilai Ekspor Hasil Perikanan", target: "USD 7.6 Miliar", realisasi: "USD 6.9 Miliar", capaian: 90.79 },
    { indikator: "Konsumsi Ikan per Kapita", target: "62.5 kg/kapita", realisasi: "59.8 kg/kapita", capaian: 95.68 },
    { indikator: "Luas Kawasan Konservasi Laut", target: "26.9 Juta Ha", realisasi: "24.1 Juta Ha", capaian: 89.59 },
  ];
}

@extends('layouts.app')

@section('title', 'KNMP - Laporan & Evaluasi')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h2 class="text-3xl font-extrabold tracking-tight">Evaluasi <span class="text-info dark:text-blue-400">KNMP</span></h2>
        <p class="text-textMuted-light dark:text-textMuted-dark text-sm mt-1">Sistem pelaporan cerdas dan cetak dokumen resmi untuk audit proyek.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Generator Panel (Left) -->
    <div class="lg:col-span-1 flex flex-col gap-6">
        <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-200 dark:border-gray-800 rounded-3xl p-6 shadow-sm">
            <h3 class="font-bold text-lg mb-4 flex items-center gap-2 text-textMain-light dark:text-textMain-dark">
                <i class="fa-solid fa-sliders text-info"></i> Parameter Laporan
            </h3>
            
            <div class="space-y-4">
                <!-- Periode -->
                <div>
                    <label class="block text-xs font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wide mb-2">Periode Evaluasi</label>
                    <div class="relative flex items-center bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm shadow-sm focus-within:ring-2 focus-within:ring-info transition-all">
                        <i class="fa-regular fa-calendar text-info mr-3"></i>
                        <input type="text" value="01 Jan 2026 - 31 Des 2026" class="bg-transparent border-none outline-none text-textMain-light dark:text-textMain-dark w-full font-semibold">
                    </div>
                </div>

                <!-- Tahap -->
                <div>
                    <label class="block text-xs font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wide mb-2">Fase Proyek</label>
                    <div class="relative">
                        <select class="w-full appearance-none bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 pr-10 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-info text-textMain-light dark:text-textMain-dark shadow-sm transition-all">
                            <option value="semua">Semua Fase (Keseluruhan)</option>
                            <option value="usulan">Fase Usulan</option>
                            <option value="konstruksi">Fase Konstruksi (Aktif)</option>
                            <option value="serah_terima">Fase Serah Terima (Selesai)</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                    </div>
                </div>

                <!-- Wilayah -->
                <div>
                    <label class="block text-xs font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wide mb-2">Wilayah</label>
                    <div class="relative">
                        <select class="w-full appearance-none bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 pr-10 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-info text-textMain-light dark:text-textMain-dark shadow-sm transition-all">
                            <option value="semua">Nasional (Semua Region)</option>
                            <option value="barat">Indonesia Barat</option>
                            <option value="tengah">Indonesia Tengah</option>
                            <option value="timur">Indonesia Timur</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                    </div>
                </div>
            </div>

            <!-- Generate Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 flex flex-col gap-3">
                <button class="w-full bg-gradient-to-r from-danger to-red-600 hover:from-red-600 hover:to-danger text-white rounded-xl px-4 py-3 text-sm font-bold shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 group">
                    <i class="fa-solid fa-file-pdf text-lg group-hover:scale-110 transition-transform"></i> Generate Laporan PDF
                </button>
                <button class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-success hover:bg-success/5 dark:hover:bg-success/10 rounded-xl px-4 py-3 text-sm font-bold shadow-sm transition-all flex items-center justify-center gap-2 group">
                    <i class="fa-solid fa-file-excel text-lg group-hover:scale-110 transition-transform"></i> Export Data Excel
                </button>
            </div>
        </div>
        
        <!-- Summary Mini Card -->
        <div class="bg-gradient-to-br from-info to-blue-600 text-white rounded-3xl p-6 shadow-md relative overflow-hidden">
            <i class="fa-solid fa-chart-pie absolute -bottom-4 -right-4 text-8xl text-white/10"></i>
            <div class="relative z-10">
                <h4 class="font-bold text-white/90 text-sm uppercase tracking-wider mb-1">Total Deviasi</h4>
                <div class="text-3xl font-extrabold mb-2">-2.4%</div>
                <p class="text-xs text-blue-100 leading-relaxed">
                    Secara nasional, progres fisik mengalami keterlambatan rata-rata sebesar 2.4% dari jadwal rencana induk. Laporan detail tersedia pada halaman 4 PDF.
                </p>
            </div>
        </div>
    </div>

    <!-- Preview Panel (Right) -->
    <div class="lg:col-span-2 bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-200 dark:border-gray-800 rounded-3xl shadow-sm flex flex-col overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/20">
            <div>
                <h3 class="font-bold text-lg flex items-center gap-2">
                    <i class="fa-solid fa-eye text-info"></i> Pratinjau Evaluasi
                </h3>
                <p class="text-xs text-textMuted-light mt-1">Cuplikan data yang akan dicetak pada laporan akhir.</p>
            </div>
            <div class="px-3 py-1 bg-warning/10 text-warning dark:text-amber-500 text-xs font-bold rounded-lg border border-warning/20">
                Draft Mode
            </div>
        </div>
        
        <div class="p-6 flex-1 overflow-y-auto">
            <!-- Mock Report Content -->
            <div class="max-w-3xl mx-auto bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 p-8 rounded-xl shadow-sm">
                
                <div class="text-center mb-8 border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h1 class="text-2xl font-serif font-bold text-gray-800 dark:text-gray-100 uppercase">Laporan Evaluasi Progres KNMP</h1>
                    <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm">Kementerian Kelautan dan Perikanan Republik Indonesia</p>
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-semibold mt-1">Periode: 01 Jan 2026 - 31 Des 2026</p>
                </div>

                <div class="mb-8">
                    <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 border-l-4 border-info pl-3 mb-4">1. Ringkasan Eksekutif</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed text-justify indent-8">
                        Laporan ini menyajikan hasil evaluasi progres pembangunan Kampung Nelayan Merah Putih (KNMP) secara nasional. Dari total 124 lokasi yang direncanakan, sebanyak 42 lokasi telah memasuki tahapan konstruksi fisik, sementara sisanya masih dalam tahap pelelangan dan proses pengadaan. Persentase progres fisik rata-rata nasional mencapai 45.8%.
                    </p>
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 border-l-4 border-info pl-3 mb-4">2. Tabel Rekapitulasi Progres</h2>
                    <table class="w-full text-sm border-collapse border border-gray-300 dark:border-gray-700">
                        <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-bold">
                            <tr>
                                <th class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-left">Tahapan</th>
                                <th class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center">Jumlah Lokasi</th>
                                <th class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center">Progres Rata-rata</th>
                                <th class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 dark:text-gray-400">
                            <tr>
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2">Usulan s.d. Lelang</td>
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center">97</td>
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center">-</td>
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center text-warning font-semibold">Persiapan</td>
                            </tr>
                            <tr class="bg-gray-50 dark:bg-gray-800/30">
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2">Konstruksi</td>
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center">18</td>
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center">62.5%</td>
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center text-info font-semibold">Berjalan</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2">Serah Terima</td>
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center">9</td>
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center">100%</td>
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center text-success font-semibold">Selesai</td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-gray-100 dark:bg-gray-800 font-bold text-gray-800 dark:text-gray-200">
                            <tr>
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2">TOTAL NASIONAL</td>
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center">124</td>
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center text-info">45.8%</td>
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center">-</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div class="mt-12 text-center text-xs text-gray-400">
                    <p>-- Akhir dari Pratinjau Dokumen --</p>
                    <p class="mt-1">Dokumen lengkap mencakup 14 halaman lampiran titik koordinat dan dokumentasi lapangan.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

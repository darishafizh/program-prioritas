@extends('layouts.app')

@section('title', 'BINS - Evaluasi Produksi')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h2 class="text-xl font-semibold tracking-tight">Evaluasi BINS</h2>
        <p class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1">Laporan analitik, cetak rekap produksi, dan evaluasi hasil budidaya Nila Salin.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Generator Panel -->
    <div class="lg:col-span-1 flex flex-col gap-6">
        <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 shadow-sm">
            <h3 class="font-medium text-base mb-4 flex items-center gap-2 text-textMain-light dark:text-textMain-dark">
                <i class="fa-solid fa-sliders text-warning"></i> Parameter Laporan
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wide mb-2">Periode Produksi</label>
                    <div class="relative flex items-center bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm shadow-sm focus-within:ring-2 focus-within:ring-warning transition-all">
                        <i class="fa-regular fa-calendar text-warning mr-3"></i>
                        <input type="text" value="01 Jan 2026 - 30 Jun 2026" class="bg-transparent border-none outline-none text-textMain-light dark:text-textMain-dark w-full font-medium">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wide mb-2">Pilih Petak / Wilayah</label>
                    <div class="relative">
                        <select class="w-full appearance-none bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-xl px-4 py-2.5 pr-10 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-warning text-textMain-light dark:text-textMain-dark shadow-sm transition-all">
                            <option value="semua">Semua Petak Nasional</option>
                            <option value="petak1">Petak Utama 1</option>
                            <option value="petak2">Petak Utama 2</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                    </div>
                </div>
            </div>

            <!-- Generate Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 flex flex-col gap-3">
                <button class="w-full bg-gradient-to-r from-danger to-red-600 hover:from-red-600 hover:to-danger text-white rounded-md px-4 py-3 text-xs font-medium shadow-md hover:shadow-lg transition-all flex items-center justify-between  gap-2 group"> Generate Laporan PDF <i class="fa-solid fa-file-pdf text-base group-hover:scale-110 transition-transform"></i> </button>
                <button class="w-full bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-success hover:bg-success/5 dark:hover:bg-success/10 rounded-md px-4 py-3 text-xs font-medium shadow-sm transition-all flex items-center justify-between  gap-2 group"> Export Data Excel <i class="fa-solid fa-file-excel text-base group-hover:scale-110 transition-transform"></i> </button>
            </div>
        </div>
    </div>

    <!-- Preview Panel -->
    <div class="lg:col-span-2 bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl shadow-sm flex flex-col overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/20">
            <div>
                <h3 class="font-medium text-base flex items-center gap-2">
                    <i class="fa-solid fa-eye text-warning"></i> Pratinjau Rekapitulasi Produksi
                </h3>
            </div>
            <div class="px-3 py-1 bg-warning/10 text-warning dark:text-amber-500 text-xs font-medium rounded-lg border border-warning/20">
                Draft Mode
            </div>
        </div>
        
        <div class="p-6 flex-1 overflow-y-auto">
            <div class="max-w-3xl mx-auto bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 p-8 rounded-xl shadow-sm">
                
                <div class="text-center mb-8 border-b border-gray-100 dark:border-gray-700 pb-6">
                    <h1 class="text-base font-serif font-medium text-gray-800 dark:text-gray-100 uppercase">Rekapitulasi Evaluasi Produksi BINS</h1>
                    <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm">Kementerian Kelautan dan Perikanan Republik Indonesia</p>
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-normal font-medium mt-1">Periode: 01 Jan 2026 - 30 Jun 2026</p>
                </div>

                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 border-l-4 border-warning pl-3 mb-4">1. Hasil Panen Nasional</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed text-justify indent-8">
                        Evaluasi produksi Budidaya Ikan Nila Salin (BINS) selama semester pertama tahun 2026 menunjukkan tren positif. Dari keseluruhan 45 petak dan 120 kolam yang dikelola secara aktif, tercatat total volume panen sebesar 8.450 Ton. Hasil panen ini telah melewati target evaluasi semester pertama.
                    </p>
                </div>

                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 border-l-4 border-warning pl-3 mb-4">2. Tabel Sebaran Produksi Berdasarkan Petak</h2>
                    <table class="w-full text-xs border-collapse border border-gray-300 dark:border-gray-700">
                        <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-medium">
                            <tr>
                                <th class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-left">Nama Petak</th>
                                <th class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center">Jumlah Kolam</th>
                                <th class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-right">Volume Panen (Ton)</th>
                                <th class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center">Pencapaian Target</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 dark:text-gray-400">
                            <tr>
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2">Petak Utama 1</td>
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center">35</td>
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-right">2,450.5</td>
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center text-success font-medium">105%</td>
                            </tr>
                            <tr class="bg-gray-50 dark:bg-gray-800/30">
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2">Petak Utama 2</td>
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center">40</td>
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-right">3,120.0</td>
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center text-success font-medium">112%</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2">Petak Percontohan</td>
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center">45</td>
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-right">2,879.5</td>
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center text-warning font-medium">98%</td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-gray-100 dark:bg-gray-800 font-medium text-gray-800 dark:text-gray-200">
                            <tr>
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2">TOTAL NASIONAL</td>
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center">120</td>
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-right text-warning">8,450.0</td>
                                <td class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center text-success">106%</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection










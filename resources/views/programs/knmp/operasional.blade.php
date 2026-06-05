@extends('layouts.app')

@section('title', 'KNMP - Operasional Progres')

@section('content')
<!-- Header Section -->
<div class="mb-8">
    <h2 class="text-3xl font-extrabold tracking-tight">Operasional <span class="text-info dark:text-blue-400">KNMP</span></h2>
    <p class="text-textMuted-light dark:text-textMuted-dark text-sm mt-1">Pembaruan data progres fisik dan status harian melalui skema sinkronisasi masal (Bulk Import).</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Upload Section -->
    <div class="lg:col-span-2 flex flex-col gap-6">
        <!-- Main Upload Card -->
        <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-200 dark:border-gray-800 rounded-3xl p-8 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-bold text-lg text-textMain-light dark:text-textMain-dark flex items-center gap-2">
                        <i class="fa-solid fa-cloud-arrow-up text-info"></i> Sinkronisasi Data Excel
                    </h3>
                    <p class="text-sm text-textMuted-light mt-1">Unggah file laporan harian/mingguan untuk memperbarui progres ratusan lokasi sekaligus.</p>
                </div>
                <button class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 text-textMain-light dark:text-textMain-dark rounded-xl px-4 py-2 text-sm font-bold shadow-sm transition-all flex items-center gap-2">
                    <i class="fa-solid fa-file-excel text-success"></i> Unduh Template
                </button>
            </div>

            <!-- Drag & Drop Zone -->
            <label class="group relative flex flex-col items-center justify-center w-full h-64 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-2xl bg-gray-50 dark:bg-gray-900/50 hover:bg-info/5 dark:hover:bg-blue-900/10 hover:border-info dark:hover:border-blue-500 transition-colors cursor-pointer overflow-hidden">
                <input type="file" class="hidden" accept=".xlsx, .xls, .csv" />
                
                <div class="absolute inset-0 bg-gradient-to-b from-transparent to-info/5 dark:to-blue-500/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                <div class="w-16 h-16 rounded-full bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 relative z-10">
                    <i class="fa-solid fa-file-excel text-3xl text-success/80 group-hover:text-success transition-colors"></i>
                </div>
                
                <div class="text-center relative z-10 px-4">
                    <p class="text-base font-bold text-textMain-light dark:text-textMain-dark mb-1">
                        Tarik & Lepaskan file Excel di sini
                    </p>
                    <p class="text-sm text-textMuted-light mb-4">
                        atau <span class="text-info dark:text-blue-400 font-bold group-hover:underline">Telusuri File</span>
                    </p>
                    <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 bg-gray-200/50 dark:bg-gray-800/50 px-3 py-1 rounded-full inline-block">
                        Mendukung .XLSX, .XLS, .CSV (Maks. 10MB)
                    </p>
                </div>
            </label>

            <!-- Status/Action Button (Disabled initially) -->
            <div class="mt-6 flex justify-end">
                <button class="bg-gray-200 dark:bg-gray-800 text-gray-400 dark:text-gray-500 rounded-xl px-6 py-2.5 text-sm font-bold flex items-center gap-2 cursor-not-allowed">
                    <i class="fa-solid fa-bolt"></i> Mulai Proses Import
                </button>
            </div>
        </div>
        
        <!-- Import Validation Rules -->
        <div class="bg-info/5 dark:bg-blue-900/10 border border-info/20 dark:border-blue-500/20 rounded-3xl p-6">
            <h4 class="font-bold text-info dark:text-blue-400 mb-3 flex items-center gap-2">
                <i class="fa-solid fa-circle-info"></i> Petunjuk Import Progres KNMP
            </h4>
            <ul class="space-y-2 text-sm text-textMain-light dark:text-textMain-dark/80">
                <li class="flex items-start gap-2">
                    <i class="fa-solid fa-check text-success mt-0.5"></i> 
                    <span>Pastikan <strong>Nama Lokasi</strong> dan <strong>ID Proyek</strong> sama persis dengan Master Data.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fa-solid fa-check text-success mt-0.5"></i> 
                    <span>Kolom <strong>Persentase Fisik</strong> hanya menerima angka (contoh: 45.5).</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fa-solid fa-check text-success mt-0.5"></i> 
                    <span>Jika terdapat deviasi (keterlambatan), sistem akan otomatis mengkalkulasinya berdasarkan <em>Timeline Curve</em>.</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Right Sidebar (History) -->
    <div class="flex flex-col gap-6">
        <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-200 dark:border-gray-800 rounded-3xl p-6 shadow-sm flex-1">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-bold text-sm uppercase tracking-wider text-textMuted-light dark:text-textMuted-dark flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Import
                </h3>
                <button class="text-info text-xs font-bold hover:underline">Lihat Semua</button>
            </div>
            
            <div class="space-y-4">
                <!-- History Item 1 -->
                <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700 hover:border-info dark:hover:border-blue-500/50 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2 text-success text-xs font-bold bg-success/10 px-2 py-0.5 rounded">
                            <i class="fa-solid fa-check-circle"></i> Selesai
                        </div>
                        <span class="text-xs text-textMuted-light">Hari ini, 09:41</span>
                    </div>
                    <div class="font-bold text-sm text-textMain-light dark:text-textMain-dark truncate">Progres_KNMP_Minggu_1.xlsx</div>
                    <div class="text-xs text-textMuted-light mt-1 flex items-center gap-2">
                        <img src="https://ui-avatars.com/api/?name=Admin&background=0891B2&color=fff&size=32" class="w-4 h-4 rounded-full" alt="User"> 
                        Oleh Administrator
                    </div>
                    <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700 flex justify-between text-xs">
                        <span class="font-semibold text-textMain-light dark:text-textMain-dark"><i class="fa-solid fa-arrows-rotate text-info"></i> 124 Data Diperbarui</span>
                    </div>
                </div>

                <!-- History Item 2 -->
                <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700 hover:border-info dark:hover:border-blue-500/50 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2 text-danger text-xs font-bold bg-danger/10 px-2 py-0.5 rounded">
                            <i class="fa-solid fa-circle-exclamation"></i> Gagal (Format)
                        </div>
                        <span class="text-xs text-textMuted-light">Kemarin, 15:20</span>
                    </div>
                    <div class="font-bold text-sm text-textMain-light dark:text-textMain-dark truncate">Update_Tender_Sulawesi.csv</div>
                    <div class="text-xs text-textMuted-light mt-1 flex items-center gap-2">
                        <img src="https://ui-avatars.com/api/?name=Admin&background=0891B2&color=fff&size=32" class="w-4 h-4 rounded-full" alt="User"> 
                        Oleh Administrator
                    </div>
                    <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700 flex justify-between text-xs">
                        <span class="text-danger font-semibold"><i class="fa-solid fa-xmark"></i> Kolom tidak sesuai template</span>
                    </div>
                </div>

                <!-- History Item 3 -->
                <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700 hover:border-info dark:hover:border-blue-500/50 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2 text-success text-xs font-bold bg-success/10 px-2 py-0.5 rounded">
                            <i class="fa-solid fa-check-circle"></i> Selesai
                        </div>
                        <span class="text-xs text-textMuted-light">3 Mei 2026</span>
                    </div>
                    <div class="font-bold text-sm text-textMain-light dark:text-textMain-dark truncate">Progres_April_Final.xlsx</div>
                    <div class="text-xs text-textMuted-light mt-1 flex items-center gap-2">
                        <img src="https://ui-avatars.com/api/?name=Admin&background=0891B2&color=fff&size=32" class="w-4 h-4 rounded-full" alt="User"> 
                        Oleh Administrator
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

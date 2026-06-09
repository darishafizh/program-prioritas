@extends('layouts.app')

@section('title', 'KNMP - Dashboard Analisis Eksekutif')

@section('content')
<!-- Header & Global Filters (Row 1) -->
<div class="mb-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
    <div>
        <h2 class="text-3xl font-extrabold tracking-tight">Dashboard <span class="text-info dark:text-blue-400">KNMP</span></h2>
        <p class="text-textMuted-light dark:text-textMuted-dark text-sm mt-1">Ringkasan Eksekutif & Pantauan Konstruksi Kampung Nelayan Merah Putih</p>
    </div>
    
    <!-- Filters -->
    <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
        <div class="relative">
            <select class="appearance-none bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2 pr-10 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-info focus:border-info text-textMain-light dark:text-textMain-dark shadow-sm">
                <option value="">Semua Tahap</option>
                <option value="usulan">Usulan</option>
                <option value="survey">Survey</option>
                <option value="ded">DED</option>
                <option value="lelang">Lelang</option>
                <option value="konstruksi">Konstruksi</option>
                <option value="serah_terima">Serah Terima</option>
            </select>
            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
        </div>
        
        <div class="relative flex items-center bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2 text-sm font-semibold shadow-sm focus-within:ring-2 focus-within:ring-info focus-within:border-info">
            <i class="fa-regular fa-calendar text-gray-400 mr-2"></i>
            <input type="text" placeholder="Jan 2026 - Des 2026" class="bg-transparent border-none outline-none text-textMain-light dark:text-textMain-dark w-36">
        </div>
        
        <button class="bg-gradient-to-r from-info to-blue-600 hover:from-blue-600 hover:to-info text-white rounded-xl px-4 py-2 text-sm font-bold shadow-md hover:shadow-lg transition-all flex items-center gap-2">
            <i class="fa-solid fa-filter"></i> Filter
        </button>
    </div>
</div>

<!-- Narrative Storytelling Block -->
<div class="mb-6 relative overflow-hidden rounded-3xl bg-gradient-to-br from-bgSurface-light to-blue-50 dark:from-bgSurface-dark dark:to-blue-900/10 border border-info/20 dark:border-info/10 shadow-sm p-6 sm:p-8">
    <div class="absolute top-0 right-0 p-8 opacity-5 dark:opacity-10 pointer-events-none">
        <i class="fa-solid fa-quote-right text-9xl text-info"></i>
    </div>
    <div class="relative z-10 max-w-4xl">
        <div class="flex items-center gap-2 text-info dark:text-blue-400 font-bold text-sm tracking-widest uppercase mb-3">
            <span class="w-2 h-2 rounded-full bg-info animate-pulse"></span> Narasi Kinerja Bulan Ini
        </div>
        <p class="text-sm sm:text-base text-textMain-light dark:text-textMain-dark leading-relaxed font-medium">
            Sejauh ini, progres pembangunan program <span class="text-info dark:text-blue-400 font-bold">KNMP mencatatkan pertumbuhan positif</span>. Sebagian besar lokasi (42 lokasi) masih berada pada tahap pengusulan, namun <span class="text-success font-bold">18 lokasi telah memasuki tahap konstruksi aktif</span>. Tantangan utama bulan ini terletak pada <span class="text-warning dark:text-amber-500 font-bold">fase Lelang yang mengalami stagnasi</span> di wilayah Indonesia Timur. Akselerasi proses tender sangat krusial untuk memenuhi target serah terima pada akhir kuartal.
        </p>
    </div>
</div>

<!-- KPI Cards (Row 2) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-200 dark:border-gray-800 rounded-3xl p-6 shadow-sm relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-info/10 dark:bg-info/20 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
        <div class="flex items-center gap-4 mb-4 relative z-10">
            <div class="w-12 h-12 rounded-xl bg-info/10 dark:bg-info/20 flex items-center justify-center text-info dark:text-blue-400 text-xl">
                <i class="fa-solid fa-house-chimney-window"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">Total Lokasi</h3>
                <div class="text-3xl font-extrabold">124 <span class="text-lg font-medium text-textMuted-light dark:text-textMuted-dark">Lokasi</span></div>
            </div>
        </div>
        <div class="flex items-center gap-2 text-xs font-medium text-success relative z-10">
            <i class="fa-solid fa-arrow-trend-up"></i> +12 Lokasi dari tahun lalu
        </div>
    </div>

    <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-200 dark:border-gray-800 rounded-3xl p-6 shadow-sm relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-teal-light/10 dark:bg-teal-400/20 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
        <div class="flex items-center gap-4 mb-4 relative z-10">
            <div class="w-12 h-12 rounded-xl bg-teal-light/10 dark:bg-teal-400/20 flex items-center justify-center text-teal-light dark:text-teal-400 text-xl">
                <i class="fa-solid fa-chart-pie"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">Rata-Rata Progres</h3>
                <div class="text-3xl font-extrabold text-teal-light dark:text-teal-400">45.8%</div>
            </div>
        </div>
        <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2 relative z-10 mt-2">
            <div class="bg-teal-light dark:bg-teal-400 h-2 rounded-full" style="width: 45.8%"></div>
        </div>
    </div>

    <!-- Total Selesai -->
    <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-200 dark:border-gray-800 rounded-3xl p-6 shadow-sm relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-success/10 dark:bg-success/20 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
        <div class="flex items-center gap-4 mb-4 relative z-10">
            <div class="w-12 h-12 rounded-xl bg-success/10 dark:bg-success/20 flex items-center justify-center text-success dark:text-emerald-400 text-xl">
                <i class="fa-solid fa-check-double"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">Total Selesai</h3>
                <div class="text-3xl font-extrabold text-success dark:text-emerald-400">9 <span class="text-lg font-medium text-textMuted-light dark:text-textMuted-dark">Lokasi</span></div>
            </div>
        </div>
        <div class="flex items-center gap-2 text-xs font-medium text-success relative z-10">
            <i class="fa-solid fa-arrow-trend-up"></i> Telah serah terima
        </div>
    </div>

    <!-- Dalam Pembangunan -->
    <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-200 dark:border-gray-800 rounded-3xl p-6 shadow-sm relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-warning/10 dark:bg-amber-400/20 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
        <div class="flex items-center gap-4 mb-4 relative z-10">
            <div class="w-12 h-12 rounded-xl bg-warning/10 dark:bg-amber-400/20 flex items-center justify-center text-warning dark:text-amber-500 text-xl">
                <i class="fa-solid fa-person-digging"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">Dalam Pembangunan</h3>
                <div class="text-3xl font-extrabold text-warning dark:text-amber-500">18 <span class="text-lg font-medium text-textMuted-light dark:text-textMuted-dark">Lokasi</span></div>
            </div>
        </div>
        <div class="flex items-center gap-2 text-xs font-medium text-textMuted-light relative z-10">
            Tahap konstruksi aktif
        </div>
    </div>
</div>

<!-- Pipeline Process UI -->
<div class="mb-6 bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-200 dark:border-gray-800 rounded-3xl p-6 shadow-sm flex flex-col">
        <h3 class="text-lg font-bold mb-6 flex items-center gap-2">
            <i class="fa-solid fa-timeline text-info dark:text-blue-400"></i> Pipeline Status KNMP
        </h3>
        
        <div class="flex-1 flex items-center justify-center relative px-4 py-8">
            <!-- Connecting Line -->
            <div class="absolute top-1/2 left-8 right-8 h-1.5 bg-gray-100 dark:bg-gray-800 -translate-y-1/2 rounded-full hidden md:block z-0"></div>
            
            <div class="grid grid-cols-2 md:grid-cols-6 w-full gap-4 relative z-10">
                
                <!-- Step 1: Usulan -->
                <div class="flex flex-col items-center group cursor-pointer">
                    <div class="w-12 h-12 rounded-full bg-white dark:bg-gray-900 border-4 border-gray-200 dark:border-gray-700 flex items-center justify-center mb-3 group-hover:border-info dark:group-hover:border-blue-500 transition-colors shadow-sm">
                        <i class="fa-solid fa-file-signature text-gray-400 group-hover:text-info dark:group-hover:text-blue-400"></i>
                    </div>
                    <div class="text-center">
                        <div class="font-bold text-sm">Usulan</div>
                        <div class="text-2xl font-extrabold text-info dark:text-blue-400 mt-1">42</div>
                    </div>
                </div>

                <!-- Step 2: Survey -->
                <div class="flex flex-col items-center group cursor-pointer">
                    <div class="w-12 h-12 rounded-full bg-white dark:bg-gray-900 border-4 border-gray-200 dark:border-gray-700 flex items-center justify-center mb-3 group-hover:border-purple-500 transition-colors shadow-sm">
                        <i class="fa-solid fa-map-location-dot text-gray-400 group-hover:text-purple-500"></i>
                    </div>
                    <div class="text-center">
                        <div class="font-bold text-sm">Survey</div>
                        <div class="text-2xl font-extrabold text-purple-500 mt-1">28</div>
                    </div>
                </div>

                <!-- Step 3: DED -->
                <div class="flex flex-col items-center group cursor-pointer">
                    <div class="w-12 h-12 rounded-full bg-white dark:bg-gray-900 border-4 border-gray-200 dark:border-gray-700 flex items-center justify-center mb-3 group-hover:border-indigo-500 transition-colors shadow-sm">
                        <i class="fa-solid fa-compass-drafting text-gray-400 group-hover:text-indigo-500"></i>
                    </div>
                    <div class="text-center">
                        <div class="font-bold text-sm">DED</div>
                        <div class="text-2xl font-extrabold text-indigo-500 mt-1">15</div>
                    </div>
                </div>

                <!-- Step 4: Lelang -->
                <div class="flex flex-col items-center group cursor-pointer">
                    <div class="w-12 h-12 rounded-full bg-white dark:bg-gray-900 border-4 border-gray-200 dark:border-gray-700 flex items-center justify-center mb-3 group-hover:border-warning dark:group-hover:border-amber-500 transition-colors shadow-sm">
                        <i class="fa-solid fa-gavel text-gray-400 group-hover:text-warning dark:group-hover:text-amber-500"></i>
                    </div>
                    <div class="text-center">
                        <div class="font-bold text-sm">Lelang</div>
                        <div class="text-2xl font-extrabold text-warning dark:text-amber-500 mt-1">12</div>
                    </div>
                </div>

                <!-- Step 5: Konstruksi -->
                <div class="flex flex-col items-center group cursor-pointer relative">
                    <!-- Active Pulsing Indicator for bottleneck -->
                    <span class="absolute top-0 right-0 flex h-3 w-3 -mt-1 -mr-1">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-danger opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-danger"></span>
                    </span>
                    <div class="w-14 h-14 rounded-full bg-info text-white border-4 border-info/30 flex items-center justify-center mb-2 shadow-lg transform scale-110">
                        <i class="fa-solid fa-helmet-safety"></i>
                    </div>
                    <div class="text-center">
                        <div class="font-bold text-sm">Konstruksi</div>
                        <div class="text-2xl font-extrabold text-info dark:text-blue-400 mt-1">18</div>
                    </div>
                </div>

                <!-- Step 6: Serah Terima -->
                <div class="flex flex-col items-center group cursor-pointer">
                    <div class="w-12 h-12 rounded-full bg-white dark:bg-gray-900 border-4 border-gray-200 dark:border-gray-700 flex items-center justify-center mb-3 group-hover:border-success dark:group-hover:border-emerald-500 transition-colors shadow-sm">
                        <i class="fa-solid fa-handshake text-gray-400 group-hover:text-success dark:group-hover:text-emerald-500"></i>
                    </div>
                    <div class="text-center">
                        <div class="font-bold text-sm">Selesai</div>
                        <div class="text-2xl font-extrabold text-success dark:text-emerald-500 mt-1">9</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 3 Columns: Distribution, Top 10, Bottom 10 (Row 3) -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Status Kesehatan & Deviasi -->
    <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-200 dark:border-gray-800 rounded-3xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-sm uppercase tracking-wider text-textMuted-light dark:text-textMuted-dark flex items-center gap-2">
                <i class="fa-solid fa-heart-pulse text-danger"></i> Status Kesehatan Proyek
            </h3>
        </div>
        <div class="space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-32 text-xs font-bold text-right flex items-center justify-end gap-1"><i class="fa-solid fa-circle-check text-success"></i> Sesuai / Cepat</div>
                <div class="flex-1 bg-gray-100 dark:bg-gray-800 rounded-full h-3">
                    <div class="bg-success dark:bg-emerald-500 h-3 rounded-full" style="width: 65%"></div>
                </div>
                <div class="w-8 text-xs font-bold text-textMuted-light">42</div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-32 text-xs font-bold text-right flex items-center justify-end gap-1"><i class="fa-solid fa-circle-exclamation text-warning"></i> Terlambat Ringan</div>
                <div class="flex-1 bg-gray-100 dark:bg-gray-800 rounded-full h-3">
                    <div class="bg-warning dark:bg-amber-500 h-3 rounded-full" style="width: 25%"></div>
                </div>
                <div class="w-8 text-xs font-bold text-textMuted-light">20</div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-32 text-xs font-bold text-right flex items-center justify-end gap-1"><i class="fa-solid fa-triangle-exclamation text-danger"></i> Kritis / Berat</div>
                <div class="flex-1 bg-gray-100 dark:bg-gray-800 rounded-full h-3">
                    <div class="bg-danger h-3 rounded-full" style="width: 15%"></div>
                </div>
                <div class="w-8 text-xs font-bold text-textMuted-light">8</div>
            </div>
            <div class="flex items-center gap-3 mt-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                <div class="flex-1 flex items-center justify-between bg-danger/10 text-danger px-4 py-3 rounded-xl border border-danger/20">
                    <div class="text-xs font-bold flex items-center gap-2"><i class="fa-solid fa-triangle-exclamation"></i> Isu & Kendala Terbuka</div>
                    <div class="font-extrabold text-lg">12 <span class="text-xs font-medium">Kasus</span></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top 10 Progress -->
    <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-200 dark:border-gray-800 rounded-3xl p-6 shadow-sm flex flex-col">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-sm uppercase tracking-wider text-textMuted-light dark:text-textMuted-dark flex items-center gap-2">
                <i class="fa-solid fa-arrow-up-right-dots text-success"></i> Top 5 KNMP Tertinggi
            </h3>
        </div>
        <div class="flex-1 overflow-y-auto pr-2 space-y-3">
            @for($i=1; $i<=5; $i++)
            <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors border border-transparent hover:border-gray-100 dark:hover:border-gray-700">
                <div class="w-8 h-8 rounded-lg bg-success/10 text-success flex items-center justify-center font-bold text-xs">{{ $i }}</div>
                <div class="flex-1">
                    <div class="font-bold text-sm truncate">KNMP Desa Bahari {{ $i }}</div>
                    <div class="text-xs text-textMuted-light">Kecamatan Pesisir</div>
                </div>
                <div class="font-extrabold text-success">{{ 100 - ($i * 2) }}%</div>
            </div>
            @endfor
        </div>
    </div>

    <!-- Bottom 10 Progress -->
    <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-200 dark:border-gray-800 rounded-3xl p-6 shadow-sm flex flex-col">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-sm uppercase tracking-wider text-textMuted-light dark:text-textMuted-dark flex items-center gap-2">
                <i class="fa-solid fa-arrow-down-right-dots text-danger"></i> Top 5 KNMP Terendah
            </h3>
        </div>
        <div class="flex-1 overflow-y-auto pr-2 space-y-3">
            @for($i=1; $i<=5; $i++)
            <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors border border-transparent hover:border-gray-100 dark:hover:border-gray-700">
                <div class="w-8 h-8 rounded-lg bg-danger/10 text-danger flex items-center justify-center font-bold text-xs">{{ $i }}</div>
                <div class="flex-1">
                    <div class="font-bold text-sm truncate">KNMP Desa Muara {{ $i }}</div>
                    <div class="text-xs text-textMuted-light">Kabupaten Kepulauan {{ $i }}</div>
                </div>
                <div class="font-extrabold text-danger">{{ 5 + ($i * 3) }}%</div>
            </div>
            @endfor
        </div>
    </div>
</div>

<!-- Map Distribution (Row 4) -->
<div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-200 dark:border-gray-800 rounded-3xl overflow-hidden shadow-sm mb-6 flex flex-col lg:flex-row">
    <div class="p-6 lg:w-1/3 flex flex-col border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20">
        <h3 class="text-lg font-bold mb-2 flex items-center gap-2">
            <i class="fa-solid fa-map text-info"></i> Sebaran Lokasi KNMP
        </h3>
        <p class="text-sm text-textMuted-light dark:text-textMuted-dark mb-6">Peta interaktif persebaran pembangunan Kampung Nelayan Merah Putih di seluruh wilayah Indonesia.</p>
        
        <div class="space-y-4">
            <div class="p-4 bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm flex items-center justify-between">
                <div>
                    <div class="text-xs text-textMuted-light font-semibold">Wilayah Barat</div>
                    <div class="font-bold text-lg">45 <span class="text-xs font-normal">Lokasi</span></div>
                </div>
                <div class="w-10 h-10 rounded-full bg-info/10 text-info flex items-center justify-center"><i class="fa-solid fa-location-dot"></i></div>
            </div>
            <div class="p-4 bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm flex items-center justify-between">
                <div>
                    <div class="text-xs text-textMuted-light font-semibold">Wilayah Tengah</div>
                    <div class="font-bold text-lg">52 <span class="text-xs font-normal">Lokasi</span></div>
                </div>
                <div class="w-10 h-10 rounded-full bg-warning/10 text-warning flex items-center justify-center"><i class="fa-solid fa-location-dot"></i></div>
            </div>
            <div class="p-4 bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm flex items-center justify-between">
                <div>
                    <div class="text-xs text-textMuted-light font-semibold">Wilayah Timur</div>
                    <div class="font-bold text-lg">27 <span class="text-xs font-normal">Lokasi</span></div>
                </div>
                <div class="w-10 h-10 rounded-full bg-success/10 text-success flex items-center justify-center"><i class="fa-solid fa-location-dot"></i></div>
            </div>
        </div>
    </div>
    <div class="lg:w-2/3 h-80 lg:h-auto relative bg-blue-50 dark:bg-blue-900/10 flex items-center justify-center overflow-hidden">
        <!-- Placeholder for actual map -->
        <div class="absolute inset-0 opacity-20 dark:opacity-10" style="background-image: radial-gradient(#3b82f6 1px, transparent 1px); background-size: 20px 20px;"></div>
        <div class="text-center relative z-10">
            <i class="fa-solid fa-map-location-dot text-6xl text-info/30 mb-4 animate-bounce"></i>
            <h4 class="font-bold text-gray-500">Peta Interaktif Dimuat Disini</h4>
            <p class="text-xs text-gray-400 mt-1">Menggunakan integrasi Leaflet.js / Google Maps</p>
        </div>
        
        <!-- Dummy map pins -->
        <div class="absolute top-1/3 left-1/4 w-3 h-3 bg-info rounded-full shadow-[0_0_10px_rgba(59,130,246,0.8)] animate-ping"></div>
        <div class="absolute top-1/2 left-1/2 w-4 h-4 bg-warning rounded-full shadow-[0_0_10px_rgba(245,158,11,0.8)] animate-pulse"></div>
        <div class="absolute bottom-1/3 right-1/4 w-3 h-3 bg-success rounded-full shadow-[0_0_10px_rgba(16,185,129,0.8)] animate-ping" style="animation-delay: 500ms"></div>
    </div>
</div>

<!-- All Data Table (Row 5) -->
<div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-200 dark:border-gray-800 rounded-3xl shadow-sm overflow-hidden flex flex-col">
    <div class="p-6 border-b border-gray-200 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4">
        <div>
            <h3 class="font-bold text-lg flex items-center gap-2">
                <i class="fa-solid fa-table-list text-info"></i> Daftar Progres Konstruksi KNMP
            </h3>
            <p class="text-xs text-textMuted-light mt-1">Detail menyeluruh status pembangunan lokasi KNMP.</p>
        </div>
        <div class="flex gap-2 w-full sm:w-auto">
            <div class="relative flex-1 sm:w-64">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" placeholder="Cari nama lokasi/desa..." class="w-full pl-8 pr-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:border-info focus:ring-1 focus:ring-info outline-none transition-all">
            </div>
            <button class="px-4 py-2 bg-danger/10 dark:bg-danger/20 border border-danger/20 text-danger rounded-xl text-sm font-bold shadow-sm hover:bg-danger/20 dark:hover:bg-danger/30 transition-colors flex items-center gap-2">
                <i class="fa-solid fa-file-pdf"></i> PDF
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-gray-50 dark:bg-gray-800/50 text-textMuted-light dark:text-textMuted-dark text-xs uppercase tracking-wider font-semibold border-b border-gray-200 dark:border-gray-800">
                <tr>
                    <th class="px-6 py-4">Lokasi / Desa</th>
                    <th class="px-6 py-4">Konstruktor (Vendor)</th>
                    <th class="px-6 py-4">Rencana</th>
                    <th class="px-6 py-4">Progres & Deviasi</th>
                    <th class="px-6 py-4">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @for($i=1; $i<=5; $i++)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-bold text-textMain-light dark:text-textMain-dark">KNMP Desa Bahari {{ $i }}</div>
                        <div class="text-xs text-textMuted-light">Kecamatan Pesisir {{ $i }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold">PT</div>
                            <span class="font-medium">PT Samudera Konstruksi {{ $i }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-textMain-light dark:text-textMain-dark">{{ 45 + ($i * 5) }}%</div>
                        <div class="text-[0.65rem] text-textMuted-light mt-0.5">Target bulan ini</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-1.5 w-48">
                            <div class="flex justify-between items-end">
                                <span class="font-bold text-sm">{{ 40 + ($i * 10) }}%</span>
                                @if($i % 2 == 0)
                                <span class="text-success font-bold text-[0.65rem] flex items-center gap-1 bg-success/10 px-1.5 py-0.5 rounded"><i class="fa-solid fa-arrow-up"></i> +2.5%</span>
                                @else
                                <span class="text-danger font-bold text-[0.65rem] flex items-center gap-1 bg-danger/10 px-1.5 py-0.5 rounded"><i class="fa-solid fa-arrow-down"></i> -1.2%</span>
                                @endif
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                <div class="bg-info h-1.5 rounded-full" style="width: {{ 40 + ($i * 10) }}%"></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($i % 2 == 0)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 dark:bg-gray-800 text-textMuted-light">Sesuai timeline</span>
                        @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-danger/10 text-danger"><i class="fa-solid fa-triangle-exclamation"></i> Kendala Cuaca</span>
                        @endif
                    </td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800 flex justify-between items-center text-sm bg-gray-50/50 dark:bg-gray-800/20">
        <div class="text-textMuted-light">Menampilkan 1 - 5 dari 124 data</div>
        <div class="flex gap-1">
            <button class="w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-400"><i class="fa-solid fa-chevron-left"></i></button>
            <button class="w-8 h-8 rounded-lg bg-info text-white font-bold flex items-center justify-center">1</button>
            <button class="w-8 h-8 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 font-bold flex items-center justify-center">2</button>
            <button class="w-8 h-8 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 font-bold flex items-center justify-center">3</button>
            <button class="w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
    </div>
</div>
@endsection

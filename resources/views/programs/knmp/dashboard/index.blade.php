@extends('layouts.app')

@section('title', 'KNMP - Dashboard Analisis Eksekutif')

@section('content')
<div x-data="dashboardTableManager()">
<!-- Header & Global Filters (Row 1) -->
<div class="mb-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
 <div>
 <h2 class="text-xl font-semibold tracking-tight">Dashboard KNMP</h2>
 <p class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1">Ringkasan Eksekutif & Pantauan Konstruksi Kampung Nelayan Merah Putih</p>
 </div>
 
 <!-- Filters -->
 <form id="dashboardFilterForm" action="{{ url()->current() }}" method="GET" class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
 <div class="relative">
 <select name="batch_id" onchange="this.form.submit()" class="appearance-none bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-xl px-4 py-2 pr-10 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-teal-light focus:border-teal-light text-textMain-light dark:text-textMain-dark ">
 <option value="">Semua Tahap</option>
 @foreach($stats['filter_batches'] ?? [] as $batch)
 <option value="{{ $batch['id'] }}" {{ request('batch_id') == $batch['id'] ? 'selected' : '' }}>{{ $batch['name'] }}</option>
 @endforeach
 </select>
 <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
 </div>
 
 <div class="relative flex items-center bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-xl px-4 py-2 text-xs font-medium focus-within:ring-2 focus-within:ring-teal-light focus-within:border-teal-light">
 <i class="fa-regular fa-calendar text-gray-400 mr-2"></i>
 <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()" class="bg-transparent border-none outline-none text-textMain-light dark:text-textMain-dark w-32">
 </div>
 </form>
</div>

<!-- Narrative Storytelling Block -->
<div class="mb-6 relative overflow-hidden rounded-3xl bg-gradient-to-br from-bgSurface-light to-blue-50 dark:from-bgSurface-dark dark:to-blue-900/10 border border-teal-light/20 dark:border-teal-light/10 p-6 sm:p-8">
 <div class="absolute top-0 right-0 p-8 opacity-5 dark:opacity-10 pointer-events-none">
 <i class="fa-solid fa-quote-right text-9xl text-teal-light"></i>
 </div>
 <div class="relative z-10 max-w-4xl">
 <div class="flex items-center gap-2 text-teal-light dark:text-teal-400 font-medium text-xs tracking-widest uppercase mb-3">
 <span class="w-2 h-2 rounded-full bg-teal-light animate-pulse"></span> Narasi Kinerja Bulan Ini
 </div>
 <p class="text-xs text-textMain-light dark:text-textMain-dark leading-relaxed font-medium">
 {!! $stats['narasi'] ?? '' !!}
 </p>
 </div>
</div>

<!-- KPI Cards (Row 2) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
 <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 relative overflow-hidden group">
 <div class="absolute top-0 right-0 w-32 h-32 bg-teal-light/10 dark:bg-teal-light/20 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
 <div class="flex items-center gap-4 mb-4 relative z-10">
 <div class="w-12 h-12 rounded-xl bg-teal-light/10 dark:bg-teal-light/20 flex items-center justify-center text-teal-light dark:text-teal-400 text-sm">
 <i class="fa-solid fa-house-chimney-window"></i>
 </div>
 <div>
 <h3 class="text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">Total Lokasi</h3>
 <div class="text-sm font-medium">{{ $stats['total_lokasi'] ?? 0 }} <span class="text-sm font-medium text-textMuted-light dark:text-textMuted-dark">Lokasi</span></div>
 </div>
 </div>
 <div class="flex items-center gap-2 text-xs font-medium text-success relative z-10">
 <i class="fa-solid fa-arrow-trend-up"></i> +12 Lokasi dari tahun lalu
 </div>
 </div>

 <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 relative overflow-hidden group">
 <div class="absolute top-0 right-0 w-32 h-32 bg-teal-light/10 dark:bg-teal-400/20 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
 <div class="flex items-center gap-4 mb-4 relative z-10">
 <div class="w-12 h-12 rounded-xl bg-teal-light/10 dark:bg-teal-400/20 flex items-center justify-center text-textMain-light dark:text-teal-400 text-sm">
 <i class="fa-solid fa-chart-pie"></i>
 </div>
 <div>
 <h3 class="text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">Rata-Rata Progres</h3>
 <div class="text-sm font-medium text-textMain-light dark:text-teal-400">{{ $stats['rata_progres'] ?? 0 }}%</div>
 </div>
 </div>
 <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2 relative z-10 mt-2">
 <div class="bg-teal-light dark:bg-teal-400 h-2 rounded-full" style="width: {{ $stats['rata_progres'] ?? 0 }}%"></div>
 </div>
 </div>

 <!-- Total Selesai -->
 <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 relative overflow-hidden group">
 <div class="absolute top-0 right-0 w-32 h-32 bg-success/10 dark:bg-success/20 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
 <div class="flex items-center gap-4 mb-4 relative z-10">
 <div class="w-12 h-12 rounded-xl bg-success/10 dark:bg-success/20 flex items-center justify-center text-success dark:text-emerald-400 text-sm">
 <i class="fa-solid fa-check-double"></i>
 </div>
 <div>
 <h3 class="text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">Total Selesai</h3>
 <div class="text-sm font-medium text-success dark:text-emerald-400">{{ $stats['total_selesai'] ?? 0 }} <span class="text-sm font-medium text-textMuted-light dark:text-textMuted-dark">Lokasi</span></div>
 </div>
 </div>
 <div class="flex items-center gap-2 text-xs font-medium text-success relative z-10">
 <i class="fa-solid fa-arrow-trend-up"></i> Telah serah terima
 </div>
 </div>

 <!-- Dalam Pembangunan -->
 <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 relative overflow-hidden group">
 <div class="absolute top-0 right-0 w-32 h-32 bg-warning/10 dark:bg-amber-400/20 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
 <div class="flex items-center gap-4 mb-4 relative z-10">
 <div class="w-12 h-12 rounded-xl bg-warning/10 dark:bg-amber-400/20 flex items-center justify-center text-warning dark:text-amber-500 text-sm">
 <i class="fa-solid fa-person-digging"></i>
 </div>
 <div>
 <h3 class="text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">Dalam Pembangunan</h3>
 <div class="text-sm font-medium text-warning dark:text-amber-500">{{ $stats['dalam_pembangunan'] ?? 0 }} <span class="text-sm font-medium text-textMuted-light dark:text-textMuted-dark">Lokasi</span></div>
 </div>
 </div>
 <div class="flex items-center gap-2 text-xs font-medium text-textMuted-light relative z-10">
 Tahap konstruksi aktif
 </div>
 </div>
</div>


<!-- 2 Columns: Top 10, Bottom 10 (Row 3) -->
<div class="grid grid-cols-2 gap-6 mb-6">
    <!-- Top 10 Progress -->
    <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 flex flex-col">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-medium text-xs uppercase tracking-wider text-textMuted-light dark:text-textMuted-dark flex items-center gap-2">
                <i class="fa-solid fa-arrow-up-right-dots text-success"></i> Top 10 KNMP Tertinggi
            </h3>
        </div>
        <div class="flex-1 w-full relative min-h-[300px]">
            <div id="chart-top10" class="w-full h-full"></div>
        </div>
    </div>

    <!-- Bottom 10 Progress -->
    <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 flex flex-col">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-medium text-xs uppercase tracking-wider text-textMuted-light dark:text-textMuted-dark flex items-center gap-2">
                <i class="fa-solid fa-arrow-down-right-dots text-danger"></i> Top 10 KNMP Terendah
            </h3>
        </div>
        <div class="flex-1 w-full relative min-h-[300px]">
            <div id="chart-bottom10" class="w-full h-full"></div>
        </div>
    </div>
</div>

<!-- Warning Stagnant Progress -->
@if(count($stats['stagnant_list'] ?? []) > 0)
<div class="mb-6 bg-warning/10 dark:bg-warning/5 border border-warning/30 dark:border-warning/20 rounded-3xl p-6">
    <div class="flex items-center gap-3 mb-5">
        <div class="w-10 h-10 rounded-full bg-warning/20 flex items-center justify-center text-warning shrink-0">
            <i class="fa-solid fa-triangle-exclamation text-lg"></i>
        </div>
        <div>
            <h3 class="font-bold text-sm text-warning dark:text-amber-500">Peringatan Risiko: Progres Stagnan</h3>
            <p class="text-xs text-textMuted-light dark:text-textMuted-dark mt-0.5">Lokasi konstruksi di bawah ini tidak mencatatkan penambahan progres fisik sedikitpun selama lebih dari 5 hari terakhir.</p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach($stats['stagnant_list'] as $item)
        <div class="bg-white/60 dark:bg-gray-900/40 border border-warning/20 rounded-2xl p-4 flex flex-col relative overflow-hidden hover:bg-white dark:hover:bg-gray-900/80 transition-colors shadow-sm">
            <div class="absolute right-0 top-0 bottom-0 w-1 bg-warning"></div>
            <div class="flex justify-between items-start mb-2">
                <div class="font-bold text-xs text-textMain-light dark:text-textMain-dark truncate pr-2" title="{{ $item['lokasi'] }}">{{ $item['lokasi'] }}</div>
                <div class="text-[10px] font-black text-warning bg-warning/10 px-2 py-1 rounded-full shrink-0 flex items-center gap-1">
                    <i class="fa-regular fa-clock"></i> {{ $item['days_stagnant'] }} Hari
                </div>
            </div>
            
            <div class="space-y-1.5 mt-1">
                <div class="flex justify-between items-center text-[10px]">
                    <span class="text-textMuted-light dark:text-textMuted-dark"><i class="fa-solid fa-chart-simple w-3"></i> Stuck di Angka</span>
                    <span class="font-bold text-textMain-light dark:text-textMain-dark">{{ $item['progres'] }}%</span>
                </div>
                <div class="flex justify-between items-center text-[10px]">
                    <span class="text-textMuted-light dark:text-textMuted-dark"><i class="fa-solid fa-hard-hat w-3"></i> Kontraktor</span>
                    <span class="font-medium text-textMain-light dark:text-textMain-dark truncate max-w-[130px] text-right" title="{{ $item['konstruktor'] }}">{{ $item['konstruktor'] }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Map Distribution (Row 4) -->
<div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl overflow-hidden mb-6 flex flex-col lg:flex-row">
    <div class="p-6 lg:w-1/3 flex flex-col border-b lg:border-b-0 lg:border-r border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20">
        <h3 class="text-sm font-medium mb-2 flex items-center gap-2">
            <i class="fa-solid fa-map text-teal-light"></i> Sebaran Lokasi KNMP
        </h3>
        <p class="text-xs text-textMuted-light dark:text-textMuted-dark mb-6">Peta interaktif persebaran pembangunan Kampung Nelayan Merah Putih di seluruh wilayah Indonesia.</p>
        
        <div class="space-y-4">
            <div class="p-4 bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <div>
                    <div class="text-xs text-textMuted-light font-medium">Wilayah Barat</div>
                    <div class="font-medium text-sm">{{ $stats['regions']['barat'] ?? 0 }} <span class="text-xs font-normal">Lokasi</span></div>
                </div>
                <div class="w-10 h-10 rounded-full bg-teal-light/10 text-teal-light flex items-center justify-center"><i class="fa-solid fa-location-dot"></i></div>
            </div>
            <div class="p-4 bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <div>
                    <div class="text-xs text-textMuted-light font-medium">Wilayah Tengah</div>
                    <div class="font-medium text-sm">{{ $stats['regions']['tengah'] ?? 0 }} <span class="text-xs font-normal">Lokasi</span></div>
                </div>
                <div class="w-10 h-10 rounded-full bg-warning/10 text-warning flex items-center justify-center"><i class="fa-solid fa-location-dot"></i></div>
            </div>
            <div class="p-4 bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <div>
                    <div class="text-xs text-textMuted-light font-medium">Wilayah Timur</div>
                    <div class="font-medium text-sm">{{ $stats['regions']['timur'] ?? 0 }} <span class="text-xs font-normal">Lokasi</span></div>
                </div>
                <div class="w-10 h-10 rounded-full bg-success/10 text-success flex items-center justify-center"><i class="fa-solid fa-location-dot"></i></div>
            </div>
        </div>
    </div>
    <div class="lg:w-2/3 min-h-[500px] relative z-0">
        <div id="knmpMap" class="absolute inset-0 w-full h-full z-0"></div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize map centered on Indonesia
        var map = L.map('knmpMap').setView([-0.7893, 113.9213], 5);
        
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
            subdomains: 'abcd',
            maxZoom: 19
        }).addTo(map);

        var locations = @json($stats['map_locations'] ?? []);
        
        locations.forEach(function(loc) {
            if(loc.latitude && loc.longitude) {
                var color = loc.status === 'Hub' ? '#0d9488' : '#f59e0b'; // teal for Hub, amber for Penyangga
                
                var markerHtmlStyles = `
                    background-color: ${color};
                    width: 1rem;
                    height: 1rem;
                    display: block;
                    left: -0.5rem;
                    top: -0.5rem;
                    position: relative;
                    border-radius: 3rem 3rem 0;
                    transform: rotate(45deg);
                    border: 1px solid #FFFFFF;
                    box-shadow: 0 1px 3px rgba(0,0,0,0.3)
                `;
                
                var icon = L.divIcon({
                    className: "my-custom-pin",
                    iconAnchor: [0, 12],
                    labelAnchor: [-6, 0],
                    popupAnchor: [0, -20],
                    html: `<span style="${markerHtmlStyles}"></span>`
                });

                L.marker([loc.latitude, loc.longitude], {icon: icon})
                    .addTo(map)
                    .bindPopup(`<b>${loc.nama}</b><br/>Status: ${loc.status || 'Penyangga'}`);
            }
        });
    });
</script>

<!-- All Data Table (Row 5) -->
<div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl overflow-hidden flex flex-col">
    <!-- Header -->
    <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h3 class="font-medium text-sm flex items-center gap-2">
                <i class="fa-solid fa-table-list text-teal-light"></i> Daftar Progres Konstruksi KNMP
            </h3>
            <p class="text-xs text-textMuted-light mt-1">Detail menyeluruh status pembangunan lokasi KNMP yang sedang berjalan.</p>
        </div>
        <div class="flex gap-2 w-full sm:w-auto self-end sm:self-auto">
            <a href="{{ route('program.dashboard.export-pdf', ['program' => strtolower($activeProgram)]) }}" target="_blank" class="px-4 py-2 bg-danger/10 dark:bg-danger/20 border border-danger/20 text-danger rounded-md text-xs font-medium hover:bg-danger/20 dark:hover:bg-danger/30 transition-colors flex items-center justify-between gap-2"> PDF <i class="fa-solid fa-file-pdf"></i> </a>
        </div>
    </div>

    <!-- Toolbar: Filter + Search -->
    <div class="px-6 py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gray-50/50 dark:bg-gray-800/20">
        <!-- Show entries -->
        <div class="flex items-center gap-2 text-xs text-textMuted-light dark:text-textMuted-dark">
            <span>Tampilkan</span>
            <select x-model="perPage" @change="currentPage = 1" class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-md px-2 py-1.5 text-xs focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark font-medium">
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="all">Semua</option>
            </select>
            <span>entri</span>
        </div>

        <!-- Search bar -->
        <div class="relative w-full sm:w-64">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Cari nama lokasi/desa..." class="w-full pl-8 pr-4 py-2 rounded-md border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-xs focus:border-teal-light outline-none transition-all">
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs whitespace-nowrap">
            <thead class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
                <tr>
                    <th class="px-6 py-4">Nama KNMP</th>
                    <th class="px-6 py-4">Konstruktor (Vendor)</th>
                    <th class="px-6 py-4">Rencana</th>
                    <th class="px-6 py-4">Progres & Deviasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
                <template x-for="(item, index) in paginatedData()" :key="index">
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="item.lokasi"></div>
                            <template x-if="item.is_stagnant">
                                <i class="fa-solid fa-triangle-exclamation text-warning text-sm" title="Progres Stagnan"></i>
                            </template>
                        </div>
                        <template x-if="item.is_stagnant">
                            <div class="text-[9px] text-warning font-medium mt-1 bg-warning/10 inline-block px-1.5 py-0.5 rounded">
                                <i class="fa-regular fa-clock mr-0.5"></i> Stagnan selama <span x-text="item.days_stagnant"></span> hari
                            </div>
                        </template>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-teal-100 text-teal-light flex items-center justify-center text-[10px] font-bold" x-text="item.konstruktor.substring(0, 2)"></div>
                            <span class="font-medium" x-text="item.konstruktor"></span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="item.rencana + '%'"></div>
                        <div class="text-[0.65rem] text-textMuted-light mt-0.5">Kumulatif Minggu Ini</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-1.5 w-48">
                            <div class="flex justify-between items-end">
                                <span class="font-medium text-xs" x-text="item.progres + '%'"></span>
                                <template x-if="item.deviasi >= 0">
                                    <span class="text-success font-medium text-[0.65rem] flex items-center gap-1 bg-success/10 px-1.5 py-0.5 rounded"><i class="fa-solid fa-arrow-up"></i> +<span x-text="item.deviasi"></span>%</span>
                                </template>
                                <template x-if="item.deviasi < 0">
                                    <span class="text-danger font-medium text-[0.65rem] flex items-center gap-1 bg-danger/10 px-1.5 py-0.5 rounded"><i class="fa-solid fa-arrow-down"></i> <span x-text="item.deviasi"></span>%</span>
                                </template>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                <div class="bg-teal-light h-1.5 rounded-full" :style="'width: ' + item.progres + '%'"></div>
                            </div>
                        </div>
                    </td>
                </tr>
                </template>
                <tr x-show="paginatedData().length === 0">
                    <td colspan="4" class="px-6 py-8 text-center text-textMuted-light">Belum ada proyek atau tidak ada hasil pencarian.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Footer: Info + Pagination -->
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50 dark:bg-gray-800/20">
        <!-- Info total data -->
        <div class="text-xs text-textMuted-light dark:text-textMuted-dark">
            Menampilkan <span class="font-medium text-textMain-light dark:text-textMain-dark" x-text="paginatedData().length"></span> dari <span class="font-medium text-textMain-light dark:text-textMain-dark" x-text="filteredData().length"></span> data
        </div>

        <!-- Pagination -->
        <div class="flex gap-1" x-show="totalPages() > 1">
            <button @click="currentPage = Math.max(1, currentPage - 1)" :disabled="currentPage === 1"
                class="w-8 h-8 rounded-md border border-gray-100 dark:border-gray-700 flex items-center justify-center text-gray-400 disabled:opacity-30 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <i class="fa-solid fa-chevron-left text-[10px]"></i>
            </button>
            
            <template x-for="page in visiblePages()" :key="page">
                <button @click="if(page !== '...') currentPage = page"
                    class="w-8 h-8 rounded-md font-medium text-xs flex items-center justify-center transition-colors"
                    :class="page === currentPage ? 'bg-teal-light text-white' : (page === '...' ? 'cursor-default text-gray-400' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-textMain-light dark:text-textMain-dark')"
                    x-text="page">
                </button>
            </template>

            <button @click="currentPage = Math.min(totalPages(), currentPage + 1)" :disabled="currentPage === totalPages()"
                class="w-8 h-8 rounded-md border border-gray-100 dark:border-gray-700 flex items-center justify-center text-gray-400 disabled:opacity-30 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // ApexCharts initialization for Top 10 and Bottom 10
    document.addEventListener('DOMContentLoaded', function() {
        const top10Data = @json($stats['top10'] ?? []);
        const bottom10Data = @json($stats['bottom10'] ?? []);
        const isDark = document.documentElement.classList.contains('dark');

        function renderBarChart(elementId, data, color) {
            if (!document.getElementById(elementId) || data.length === 0) return;
            
            const categories = data.map(item => item.lokasi);
            const progresData = data.map(item => item.progres);
            const rencanaData = data.map(item => item.rencana);
            
            const options = {
                series: [{
                    name: 'Progres Aktual',
                    data: progresData
                }],
                chart: {
                    type: 'bar',
                    height: Math.max(300, data.length * 35),
                    toolbar: { show: false },
                    background: 'transparent',
                    fontFamily: 'Inter, sans-serif'
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        dataLabels: {
                            position: 'top',
                        },
                        borderRadius: 2,
                        barHeight: '70%'
                    }
                },
                colors: [color],
                dataLabels: {
                    enabled: true,
                    offsetX: -6,
                    style: {
                        fontSize: '9px',
                        colors: ['#fff']
                    },
                    formatter: function (val) {
                        return val + "%";
                    }
                },
                stroke: {
                    show: true,
                    width: 1,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: categories,
                    max: 100,
                    labels: {
                        style: {
                            colors: isDark ? '#9CA3AF' : '#6B7280',
                            fontSize: '10px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: isDark ? '#E5E7EB' : '#374151',
                            fontSize: '11px',
                            fontWeight: 500
                        },
                        maxWidth: 180
                    }
                },
                grid: {
                    borderColor: isDark ? '#374151' : '#F3F4F6',
                    strokeDashArray: 4,
                },
                theme: {
                    mode: isDark ? 'dark' : 'light'
                },
                tooltip: {
                    y: {
                        formatter: function (val, opts) {
                            if(opts.seriesIndex === 0) {
                                let dev = data[opts.dataPointIndex].deviasi;
                                let sign = dev > 0 ? '+' : '';
                                return val + "% (Deviasi: " + sign + dev + "%)";
                            }
                            return val + "%";
                        }
                    }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'center',
                    fontSize: '11px'
                }
            };

            const chart = new ApexCharts(document.querySelector("#" + elementId), options);
            chart.render();
            
            // Re-render chart on theme change
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.attributeName === 'class') {
                        const newIsDark = document.documentElement.classList.contains('dark');
                        chart.updateOptions({
                            theme: { mode: newIsDark ? 'dark' : 'light' },
                            xaxis: { labels: { style: { colors: newIsDark ? '#9CA3AF' : '#6B7280' } } },
                            yaxis: { labels: { style: { colors: newIsDark ? '#E5E7EB' : '#374151' } } },
                            grid: { borderColor: newIsDark ? '#374151' : '#F3F4F6' }
                        });
                    }
                });
            });
            observer.observe(document.documentElement, { attributes: true });
        }

        renderBarChart('chart-top10', top10Data, '#10B981'); // Success green
        renderBarChart('chart-bottom10', bottom10Data, '#EF4444'); // Danger red
    });
</script>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('dashboardTableManager', () => ({
            searchQuery: '',
            perPage: '25',
            currentPage: 1,
            tableData: @json($stats['all_konstruksi'] ?? []),

            filteredData() {
                const q = this.searchQuery.toLowerCase().trim();
                let data = this.tableData;
                
                if (!q) return data;
                return data.filter(item => {
                    return Object.values(item).some(val => 
                        String(val).toLowerCase().includes(q)
                    );
                });
            },

            paginatedData() {
                const data = this.filteredData();
                if (this.perPage === 'all') return data;
                const pp = parseInt(this.perPage);
                const start = (this.currentPage - 1) * pp;
                return data.slice(start, start + pp);
            },

            totalPages() {
                if (this.perPage === 'all') return 1;
                const pp = parseInt(this.perPage);
                return Math.max(1, Math.ceil(this.filteredData().length / pp));
            },

            visiblePages() {
                const total = this.totalPages();
                if (total <= 7) return Array.from({length: total}, (_, i) => i + 1);
                
                const pages = [];
                const cur = this.currentPage;
                
                pages.push(1);
                if (cur > 3) pages.push('...');
                
                for (let i = Math.max(2, cur - 1); i <= Math.min(total - 1, cur + 1); i++) {
                    pages.push(i);
                }
                
                if (cur < total - 2) pages.push('...');
                pages.push(total);
                
                return pages;
            }
        }));
    });
</script>
</div>
@endsection


















@extends('layouts.app')

@section('title', 'BINS - Dashboard Eksekutif')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-3xl font-extrabold tracking-tight">Dashboard <span class="text-warning dark:text-amber-500">BINS</span></h2>
        <p class="text-textMuted-light dark:text-textMuted-dark text-sm mt-1">Ringkasan Eksekutif & Pantauan Produksi Budidaya Ikan Nila Salin</p>
    </div>
    
    <div class="relative flex items-center bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2 text-sm font-semibold shadow-sm focus-within:ring-2 focus-within:ring-warning focus-within:border-warning">
        <i class="fa-regular fa-calendar text-gray-400 mr-2"></i>
        <input type="text" placeholder="Tahun 2026" class="bg-transparent border-none outline-none text-textMain-light dark:text-textMain-dark w-24">
    </div>
</div>

<!-- Narrative Storytelling Block -->
<div class="mb-6 relative overflow-hidden rounded-3xl bg-gradient-to-br from-bgSurface-light to-orange-50 dark:from-bgSurface-dark dark:to-orange-900/10 border border-warning/20 shadow-sm p-6 sm:p-8">
    <div class="absolute top-0 right-0 p-8 opacity-5 dark:opacity-10 pointer-events-none">
        <i class="fa-solid fa-fish text-9xl text-warning"></i>
    </div>
    <div class="relative z-10 max-w-4xl">
        <div class="flex items-center gap-2 text-warning dark:text-amber-500 font-bold text-sm tracking-widest uppercase mb-3">
            <span class="w-2 h-2 rounded-full bg-warning animate-pulse"></span> Narasi Produksi BINS
        </div>
        <p class="text-sm sm:text-base text-textMain-light dark:text-textMain-dark leading-relaxed font-medium">
            Pengembangan Budidaya Ikan Nila Salin (BINS) berjalan dengan sangat baik di <span class="text-warning dark:text-amber-500 font-bold">120 Kolam</span> yang tersebar pada <span class="text-success font-bold">45 Petak</span> budidaya. Tercatat volume produksi pada kuartal ini mengalami <span class="text-success font-bold">peningkatan sebesar 15%</span> dibandingkan kuartal sebelumnya, mendukung ketahanan pangan serta kesejahteraan pembudidaya.
        </p>
    </div>
</div>

<!-- KPI Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-200 dark:border-gray-800 rounded-3xl p-6 shadow-sm relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-24 h-24 bg-success/10 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
        <div class="flex items-center gap-4 mb-4 relative z-10">
            <div class="w-12 h-12 rounded-xl bg-success/10 flex items-center justify-center text-success text-xl">
                <i class="fa-solid fa-draw-polygon"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">Total Petak</h3>
                <div class="text-3xl font-extrabold">45</div>
            </div>
        </div>
    </div>

    <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-200 dark:border-gray-800 rounded-3xl p-6 shadow-sm relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-24 h-24 bg-info/10 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
        <div class="flex items-center gap-4 mb-4 relative z-10">
            <div class="w-12 h-12 rounded-xl bg-info/10 flex items-center justify-center text-info text-xl">
                <i class="fa-solid fa-water"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">Total Kolam</h3>
                <div class="text-3xl font-extrabold">120</div>
            </div>
        </div>
    </div>

    <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-200 dark:border-gray-800 rounded-3xl p-6 shadow-sm relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-24 h-24 bg-warning/10 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
        <div class="flex items-center gap-4 mb-4 relative z-10">
            <div class="w-12 h-12 rounded-xl bg-warning/10 flex items-center justify-center text-warning text-xl">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">Total Produksi</h3>
                <div class="text-3xl font-extrabold">8,450 <span class="text-sm text-textMuted-light font-medium">Ton</span></div>
            </div>
        </div>
    </div>
</div>

<!-- Chart Section -->
<div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-200 dark:border-gray-800 rounded-3xl shadow-sm p-6 flex flex-col items-center justify-center min-h-[300px]">
    <i class="fa-solid fa-chart-line text-6xl text-gray-200 dark:text-gray-700 mb-4"></i>
    <h3 class="text-lg font-bold text-gray-400 dark:text-gray-500">Grafik Produksi Dimuat Disini</h3>
    <p class="text-sm text-gray-400 dark:text-gray-600 mt-2">Menampilkan tren produksi Ikan Nila Salin sepanjang tahun.</p>
</div>
@endsection

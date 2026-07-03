@extends('layouts.app')

@section('title', 'BINS - Dashboard Eksekutif')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
 <div>
 <h2 class="text-xl font-semibold tracking-tight">Dashboard BINS</h2>
 <p class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1">Ringkasan Eksekutif & Pantauan Produksi Budidaya Ikan Nila Salin</p>
 </div>
 
 <div class="relative flex items-center bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-xl px-4 py-2 text-xs font-medium focus-within:ring-2 focus-within:ring-warning focus-within:border-warning">
 <i class="fa-regular fa-calendar text-gray-400 mr-2"></i>
 <input type="text" placeholder="Tahun 2026" class="bg-transparent border-none outline-none text-textMain-light dark:text-textMain-dark w-24">
 </div>
</div>

<!-- Narrative Storytelling Block -->
<div class="mb-6 relative overflow-hidden rounded-3xl bg-gradient-to-br from-bgSurface-light to-orange-50 dark:from-bgSurface-dark dark:to-orange-900/10 border border-warning/20 p-6 sm:p-8">
 <div class="absolute top-0 right-0 p-8 opacity-5 dark:opacity-10 pointer-events-none">
 <i class="fa-solid fa-fish text-9xl text-warning"></i>
 </div>
 <div class="relative z-10 max-w-4xl">
 <div class="flex items-center gap-2 text-warning dark:text-amber-500 font-medium text-xs tracking-widest uppercase mb-3">
 <span class="w-2 h-2 rounded-full bg-warning animate-pulse"></span> Narasi Produksi BINS
 </div>
 <p class="text-xs text-textMain-light dark:text-textMain-dark leading-relaxed font-medium">
 Pengembangan Budidaya Ikan Nila Salin (BINS) berjalan dengan sangat baik di <span class="text-warning dark:text-amber-500 font-medium">120 Kolam</span> yang tersebar pada <span class="text-success font-medium">45 Petak</span> budidaya. Tercatat volume produksi pada kuartal ini mengalami <span class="text-success font-medium">peningkatan sebesar 15%</span> dibandingkan kuartal sebelumnya, mendukung ketahanan pangan serta kesejahteraan pembudidaya.
 </p>
 </div>
</div>

<!-- KPI Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <x-stat-card
        title="Total Petak"
        icon="fa-solid fa-draw-polygon"
        icon-color="text-success dark:text-emerald-400"
        icon-bg="bg-success/10 dark:bg-success/20"
        value="45"
        unit="Petak"
        description="Terdaftar aktif di sistem"
    />

    <x-stat-card
        title="Total Kolam"
        icon="fa-solid fa-water"
        icon-color="text-teal-light dark:text-teal-400"
        icon-bg="bg-teal-light/10 dark:bg-teal-light/20"
        value="120"
        unit="Kolam"
        description="Tersebar pada 45 Petak"
    />

    <x-stat-card
        title="Total Produksi"
        icon="fa-solid fa-boxes-stacked"
        icon-color="text-warning dark:text-amber-500"
        icon-bg="bg-warning/10 dark:bg-amber-400/20"
        value="8,450"
        unit="Ton"
        description="Akumulasi produksi kuartal ini"
    />
</div>

<!-- Chart Section -->
<div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 flex flex-col items-center justify-center min-h-[300px]">
 <i class="fa-solid fa-chart-line text-6xl text-gray-200 dark:text-gray-700 mb-4"></i>
 <h3 class="text-sm font-medium text-gray-400 dark:text-gray-500">Grafik Produksi Dimuat Disini</h3>
 <p class="text-xs text-gray-400 dark:text-gray-600 mt-2">Menampilkan tren produksi Ikan Nila Salin sepanjang tahun.</p>
</div>
@endsection












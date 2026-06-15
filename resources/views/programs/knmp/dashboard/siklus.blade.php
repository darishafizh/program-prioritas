@extends('layouts.app')

@section('title', 'KNMP - Siklus & Operasional')

@section('content')
<div x-data="{ filterTahap: '' }">
    <!-- Header & Global Filters -->
    <div class="mb-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div>
            <h2 class="text-xl font-semibold tracking-tight">Dashboard Siklus & Operasional</h2>
            <p class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1">Pantauan Siklus Calon Lokasi dan Status Operasional Proyek KNMP</p>
        </div>
        
        <!-- Filters -->
        <form id="dashboardFilterForm" action="{{ url()->current() }}" method="GET" class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
            <div class="relative">
                <select name="batch_id" onchange="this.form.submit()" class="appearance-none bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-xl px-4 py-2 pr-10 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-teal-light focus:border-teal-light text-textMain-light dark:text-textMain-dark ">
                    <option value="">Semua Tahap (Batch)</option>
                    @foreach($stats['filter_batches'] ?? [] as $batch)
                        <option value="{{ $batch['id'] }}" {{ request('batch_id') == $batch['id'] ? 'selected' : '' }}>{{ $batch['name'] }}</option>
                    @endforeach
                </select>
                <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
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
                <span class="w-2 h-2 rounded-full bg-teal-light animate-pulse"></span> Narasi Analisis Eksekutif
            </div>
            <p class="text-sm sm:text-base text-textMain-light dark:text-textMain-dark leading-relaxed font-medium">
                {!! $stats['narasi'] ?? '' !!}
            </p>
        </div>
    </div>

    <!-- Pipeline Process UI -->
    <div class="mb-6 bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 flex flex-col">
        <h3 class="text-sm font-medium mb-6 flex items-center gap-2">
            <i class="fa-solid fa-timeline text-teal-light dark:text-teal-400"></i> Pipeline Status KNMP
        </h3>
        
        <div class="flex-1 flex items-center justify-center relative px-4 py-8">
            <!-- Connecting Line -->
            <div class="absolute top-1/2 left-8 right-8 h-1.5 bg-gray-100 dark:bg-gray-800 -translate-y-1/2 rounded-full hidden md:block z-0"></div>
            
            <div class="grid grid-cols-2 md:grid-cols-6 w-full gap-4 relative z-10">
                
                <!-- Step 1: Usulan -->
                <div class="flex flex-col items-center group cursor-pointer">
                    <div class="w-12 h-12 rounded-full bg-white dark:bg-gray-900 border-4 border-gray-100 dark:border-gray-700 flex items-center justify-center mb-3 group-hover:border-teal-light dark:group-hover:border-teal-500 transition-colors ">
                        <i class="fa-solid fa-file-signature text-gray-400 group-hover:text-teal-light dark:group-hover:text-teal-400"></i>
                    </div>
                    <div class="text-center">
                        <div class="font-medium text-xs">Usulan</div>
                        <div class="text-sm font-semibold text-teal-light dark:text-teal-400 mt-1">{{ $stats['pipeline']['usulan'] ?? 0 }}</div>
                    </div>
                </div>

                <!-- Step 2: Survey -->
                <div class="flex flex-col items-center group cursor-pointer">
                    <div class="w-12 h-12 rounded-full bg-white dark:bg-gray-900 border-4 border-gray-100 dark:border-gray-700 flex items-center justify-center mb-3 group-hover:border-teal-500 transition-colors ">
                        <i class="fa-solid fa-map-location-dot text-gray-400 group-hover:text-teal-500"></i>
                    </div>
                    <div class="text-center">
                        <div class="font-medium text-xs">Survey</div>
                        <div class="text-sm font-semibold text-teal-500 mt-1">{{ $stats['pipeline']['survei'] ?? 0 }}</div>
                    </div>
                </div>

                <!-- Step 3: DED -->
                <div class="flex flex-col items-center group cursor-pointer">
                    <div class="w-12 h-12 rounded-full bg-white dark:bg-gray-900 border-4 border-gray-100 dark:border-gray-700 flex items-center justify-center mb-3 group-hover:border-teal-500 transition-colors ">
                        <i class="fa-solid fa-compass-drafting text-gray-400 group-hover:text-teal-500"></i>
                    </div>
                    <div class="text-center">
                        <div class="font-medium text-xs">DED</div>
                        <div class="text-sm font-semibold text-teal-500 mt-1">{{ $stats['pipeline']['ded'] ?? 0 }}</div>
                    </div>
                </div>

                <!-- Step 4: Lelang -->
                <div class="flex flex-col items-center group cursor-pointer">
                    <div class="w-12 h-12 rounded-full bg-white dark:bg-gray-900 border-4 border-gray-100 dark:border-gray-700 flex items-center justify-center mb-3 group-hover:border-warning dark:group-hover:border-amber-500 transition-colors ">
                        <i class="fa-solid fa-gavel text-gray-400 group-hover:text-warning dark:group-hover:text-amber-500"></i>
                    </div>
                    <div class="text-center">
                        <div class="font-medium text-xs">Lelang</div>
                        <div class="text-sm font-semibold text-warning dark:text-amber-500 mt-1">{{ $stats['pipeline']['lelang'] ?? 0 }}</div>
                    </div>
                </div>

                <!-- Step 5: Konstruksi -->
                <div class="flex flex-col items-center group cursor-pointer relative">
                    <div class="w-14 h-14 rounded-full bg-teal-light text-white border-4 border-teal-light/30 flex items-center justify-center mb-2 transform scale-110">
                        <i class="fa-solid fa-helmet-safety"></i>
                    </div>
                    <div class="text-center">
                        <div class="font-medium text-xs">Konstruksi</div>
                        <div class="text-sm font-semibold text-teal-light dark:text-teal-400 mt-1">{{ $stats['pipeline']['konstruksi'] ?? 0 }}</div>
                    </div>
                </div>

                <!-- Step 6: Serah Terima -->
                <div class="flex flex-col items-center group cursor-pointer">
                    <div class="w-12 h-12 rounded-full bg-white dark:bg-gray-900 border-4 border-gray-100 dark:border-gray-700 flex items-center justify-center mb-3 group-hover:border-success dark:group-hover:border-emerald-500 transition-colors ">
                        <i class="fa-solid fa-handshake text-gray-400 group-hover:text-success dark:group-hover:text-emerald-500"></i>
                    </div>
                    <div class="text-center">
                        <div class="font-medium text-xs">Selesai</div>
                        <div class="text-sm font-semibold text-success dark:text-emerald-500 mt-1">{{ $stats['pipeline']['serah_terima'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Operasional Status -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 flex flex-col">
            <h3 class="font-medium text-xs uppercase tracking-wider text-textMuted-light dark:text-textMuted-dark flex items-center gap-2 mb-4">
                <i class="fa-solid fa-earth-asia text-blue-500"></i> Status Wilayah Operasional
            </h3>
            <div class="flex-1 flex flex-col justify-center">
                <div class="flex items-end gap-2 mb-2">
                    <span class="text-3xl font-bold text-textMain-light dark:text-textMain-dark leading-none">{{ $stats['operasional']['total'] ?? 0 }}</span>
                    <span class="text-sm font-medium text-textMuted-light dark:text-textMuted-dark mb-1">Total Proyek</span>
                </div>
                <div class="flex items-center gap-4 mt-4">
                    <div class="flex-1">
                        <div class="flex justify-between text-xs mb-1">
                            <span class="font-medium text-textMuted-light dark:text-textMuted-dark">Hub</span>
                            <span class="font-semibold text-blue-500">{{ $stats['operasional']['hub'] ?? 0 }}</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-1.5">
                            <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ $stats['operasional']['total'] > 0 ? (($stats['operasional']['hub'] / $stats['operasional']['total']) * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between text-xs mb-1">
                            <span class="font-medium text-textMuted-light dark:text-textMuted-dark">Penyangga</span>
                            <span class="font-semibold text-teal-light">{{ $stats['operasional']['penyangga'] ?? 0 }}</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-1.5">
                            <div class="bg-teal-light h-1.5 rounded-full" style="width: {{ $stats['operasional']['total'] > 0 ? (($stats['operasional']['penyangga'] / $stats['operasional']['total']) * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 flex flex-col">
            <h3 class="font-medium text-xs uppercase tracking-wider text-textMuted-light dark:text-textMuted-dark flex items-center gap-2 mb-4">
                <i class="fa-solid fa-list-check text-warning"></i> Tahap Aktif Operasional
            </h3>
            <div class="flex-1 overflow-y-auto pr-2 space-y-2">
                @forelse($stats['operasional']['tahap_aktif'] ?? [] as $tahap => $count)
                <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-800 last:border-0">
                    <span class="text-xs font-medium text-textMain-light dark:text-textMain-dark capitalize">{{ str_replace('_', ' ', $tahap) }}</span>
                    <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark">{{ $count }}</span>
                </div>
                @empty
                <div class="text-center text-xs text-textMuted-light py-4">Belum ada data tahapan operasional</div>
                @endforelse
            </div>
        </div>

        <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 flex flex-col relative overflow-hidden">
            <div class="absolute -right-6 -bottom-6 opacity-10">
                <i class="fa-solid fa-chart-pie text-9xl text-teal-light"></i>
            </div>
            <h3 class="font-medium text-xs uppercase tracking-wider text-textMuted-light dark:text-textMuted-dark flex items-center gap-2 mb-4 relative z-10">
                <i class="fa-solid fa-chart-line text-success"></i> Rata-rata Progres
            </h3>
            <div class="flex-1 flex flex-col items-center justify-center relative z-10">
                <div class="text-4xl sm:text-5xl font-bold text-teal-light dark:text-teal-400 tracking-tighter mb-2">
                    {{ $stats['operasional']['avg_progres'] ?? 0 }}<span class="text-2xl text-textMuted-light">%</span>
                </div>
                <div class="text-xs text-textMuted-light text-center">Progres fisik rata-rata dari seluruh proyek operasional.</div>
            </div>
        </div>
    </div>
</div>
@endsection

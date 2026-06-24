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
                <span class="w-2 h-2 rounded-full bg-teal-light animate-pulse"></span> Analisis Siklus & Operasional
            </div>
            <p class="text-xs text-textMain-light dark:text-textMain-dark leading-relaxed font-medium">
                {!! $stats['narasi'] ?? '' !!}
            </p>
        </div>
    </div>

    <div class="flex flex-col gap-6 mb-6">
        <!-- Pipeline Pengajuan Calon Lokasi -->
        <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 flex flex-col">
            <h3 class="text-xs font-medium mb-8 flex items-center gap-2 uppercase tracking-wider text-textMuted-light dark:text-textMuted-dark">
                <i class="fa-solid fa-file-signature text-blue-500"></i> Siklus Pengajuan Calon Lokasi
            </h3>
            
            <div class="relative w-full px-2 sm:px-8 mb-4">
                <!-- Connecting Line -->
                <div class="absolute top-6 left-[10%] right-[10%] h-1.5 bg-gray-100 dark:bg-gray-800 -translate-y-1/2 rounded-full hidden sm:block z-0"></div>
                
                <div class="grid grid-cols-2 sm:grid-cols-6 w-full gap-4 relative z-10">
                    
                    <!-- Step 1: Pengajuan -->
                    <div class="flex flex-col items-center group cursor-pointer">
                        <div class="w-12 h-12 rounded-full bg-white dark:bg-gray-900 border-[3px] border-gray-100 dark:border-gray-700 flex items-center justify-center mb-3 group-hover:border-blue-500 transition-colors shadow-sm">
                            <i class="fa-solid fa-file-import text-gray-400 text-sm group-hover:text-blue-500"></i>
                        </div>
                        <div class="text-center">
                            <div class="font-medium text-[10px] uppercase text-textMuted-light tracking-wider">Pengajuan</div>
                            <div class="text-sm font-semibold text-blue-500 mt-1">{{ $stats['pipeline_pengajuan']['pengajuan'] ?? 0 }}</div>
                        </div>
                    </div>

                    <!-- Step 2: Verif Admin -->
                    <div class="flex flex-col items-center group cursor-pointer">
                        <div class="w-12 h-12 rounded-full bg-white dark:bg-gray-900 border-[3px] border-gray-100 dark:border-gray-700 flex items-center justify-center mb-3 group-hover:border-blue-500 transition-colors shadow-sm">
                            <i class="fa-solid fa-clipboard-check text-gray-400 text-sm group-hover:text-blue-500"></i>
                        </div>
                        <div class="text-center">
                            <div class="font-medium text-[10px] uppercase text-textMuted-light tracking-wider">Verif Admin</div>
                            <div class="text-sm font-semibold text-blue-500 mt-1">{{ $stats['pipeline_pengajuan']['verif_admin'] ?? 0 }}</div>
                        </div>
                    </div>

                    <!-- Step 3: Verif Teknis -->
                    <div class="flex flex-col items-center group cursor-pointer">
                        <div class="w-12 h-12 rounded-full bg-white dark:bg-gray-900 border-[3px] border-gray-100 dark:border-gray-700 flex items-center justify-center mb-3 group-hover:border-blue-500 transition-colors shadow-sm">
                            <i class="fa-solid fa-microscope text-gray-400 text-sm group-hover:text-blue-500"></i>
                        </div>
                        <div class="text-center">
                            <div class="font-medium text-[10px] uppercase text-textMuted-light tracking-wider">Verif Teknis</div>
                            <div class="text-sm font-semibold text-blue-500 mt-1">{{ $stats['pipeline_pengajuan']['verif_teknis'] ?? 0 }}</div>
                        </div>
                    </div>

                    <!-- Step 4: BA Calon -->
                    <div class="flex flex-col items-center group cursor-pointer">
                        <div class="w-12 h-12 rounded-full bg-white dark:bg-gray-900 border-[3px] border-gray-100 dark:border-gray-700 flex items-center justify-center mb-3 group-hover:border-blue-500 transition-colors shadow-sm">
                            <i class="fa-solid fa-file-contract text-gray-400 text-sm group-hover:text-blue-500"></i>
                        </div>
                        <div class="text-center">
                            <div class="font-medium text-[10px] uppercase text-textMuted-light tracking-wider">BA Calon</div>
                            <div class="text-sm font-semibold text-blue-500 mt-1">{{ $stats['pipeline_pengajuan']['ba_calon'] ?? 0 }}</div>
                        </div>
                    </div>
                    
                    <!-- Step 5: BA Aktivasi -->
                    <div class="flex flex-col items-center group cursor-pointer">
                        <div class="w-12 h-12 rounded-full bg-white dark:bg-gray-900 border-[3px] border-gray-100 dark:border-gray-700 flex items-center justify-center mb-3 group-hover:border-blue-500 transition-colors shadow-sm">
                            <i class="fa-solid fa-file-shield text-gray-400 text-sm group-hover:text-blue-500"></i>
                        </div>
                        <div class="text-center">
                            <div class="font-medium text-[10px] uppercase text-textMuted-light tracking-wider">BA Aktivasi</div>
                            <div class="text-sm font-semibold text-blue-500 mt-1">{{ $stats['pipeline_pengajuan']['ba_aktivasi'] ?? 0 }}</div>
                        </div>
                    </div>

                    <!-- Step 6: Penetapan -->
                    <div class="flex flex-col items-center group cursor-pointer relative">
                        <div class="w-14 h-14 rounded-full bg-blue-500 text-white border-4 border-blue-500/30 flex items-center justify-center mb-2 transform scale-110 shadow-md">
                            <i class="fa-solid fa-check-double text-sm"></i>
                        </div>
                        <div class="text-center">
                            <div class="font-medium text-[10px] uppercase text-blue-500 tracking-wider font-bold">Penetapan</div>
                            <div class="text-sm font-semibold text-blue-500 mt-1">{{ $stats['pipeline_pengajuan']['penetapan'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pipeline Siklus Usulan Konstruksi -->
        <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 flex flex-col">
            <h3 class="text-xs font-medium mb-8 flex items-center gap-2 uppercase tracking-wider text-textMuted-light dark:text-textMuted-dark">
                <i class="fa-solid fa-timeline text-teal-light dark:text-teal-400"></i> Siklus Usulan & Konstruksi KNMP
            </h3>
            
            <div class="relative w-full px-2 sm:px-8 mb-4">
                <!-- Connecting Line -->
                <div class="absolute top-6 left-[10%] right-[10%] h-1.5 bg-gray-100 dark:bg-gray-800 -translate-y-1/2 rounded-full hidden sm:block z-0"></div>
                
                <div class="grid grid-cols-2 sm:grid-cols-6 w-full gap-4 relative z-10">
                    
                    <!-- Step 1: Usulan -->
                    <div class="flex flex-col items-center group cursor-pointer">
                        <div class="w-12 h-12 rounded-full bg-white dark:bg-gray-900 border-[3px] border-gray-100 dark:border-gray-700 flex items-center justify-center mb-3 group-hover:border-teal-light dark:group-hover:border-teal-400 transition-colors shadow-sm">
                            <i class="fa-solid fa-file-signature text-gray-400 text-sm group-hover:text-teal-light dark:group-hover:text-teal-400"></i>
                        </div>
                        <div class="text-center">
                            <div class="font-medium text-[10px] uppercase text-textMuted-light tracking-wider">Usulan</div>
                            <div class="text-sm font-semibold text-teal-light dark:text-teal-400 mt-1">{{ $stats['pipeline']['usulan'] ?? 0 }}</div>
                        </div>
                    </div>

                    <!-- Step 2: Survey -->
                    <div class="flex flex-col items-center group cursor-pointer">
                        <div class="w-12 h-12 rounded-full bg-white dark:bg-gray-900 border-[3px] border-gray-100 dark:border-gray-700 flex items-center justify-center mb-3 group-hover:border-teal-500 transition-colors shadow-sm">
                            <i class="fa-solid fa-map-location-dot text-gray-400 text-sm group-hover:text-teal-500"></i>
                        </div>
                        <div class="text-center">
                            <div class="font-medium text-[10px] uppercase text-textMuted-light tracking-wider">Survey</div>
                            <div class="text-sm font-semibold text-teal-500 mt-1">{{ $stats['pipeline']['survei'] ?? 0 }}</div>
                        </div>
                    </div>

                    <!-- Step 3: DED -->
                    <div class="flex flex-col items-center group cursor-pointer">
                        <div class="w-12 h-12 rounded-full bg-white dark:bg-gray-900 border-[3px] border-gray-100 dark:border-gray-700 flex items-center justify-center mb-3 group-hover:border-teal-500 transition-colors shadow-sm">
                            <i class="fa-solid fa-compass-drafting text-gray-400 text-sm group-hover:text-teal-500"></i>
                        </div>
                        <div class="text-center">
                            <div class="font-medium text-[10px] uppercase text-textMuted-light tracking-wider">DED</div>
                            <div class="text-sm font-semibold text-teal-500 mt-1">{{ $stats['pipeline']['ded'] ?? 0 }}</div>
                        </div>
                    </div>

                    <!-- Step 4: Lelang -->
                    <div class="flex flex-col items-center group cursor-pointer">
                        <div class="w-12 h-12 rounded-full bg-white dark:bg-gray-900 border-[3px] border-gray-100 dark:border-gray-700 flex items-center justify-center mb-3 group-hover:border-warning dark:group-hover:border-amber-500 transition-colors shadow-sm">
                            <i class="fa-solid fa-gavel text-gray-400 text-sm group-hover:text-warning dark:group-hover:text-amber-500"></i>
                        </div>
                        <div class="text-center">
                            <div class="font-medium text-[10px] uppercase text-textMuted-light tracking-wider">Lelang</div>
                            <div class="text-sm font-semibold text-warning dark:text-amber-500 mt-1">{{ $stats['pipeline']['lelang'] ?? 0 }}</div>
                        </div>
                    </div>

                    <!-- Step 5: Konstruksi -->
                    <div class="flex flex-col items-center group cursor-pointer relative">
                        <div class="w-14 h-14 rounded-full bg-teal-light text-white border-4 border-teal-light/30 flex items-center justify-center mb-2 transform scale-110 shadow-md">
                            <i class="fa-solid fa-helmet-safety text-[10px]"></i>
                        </div>
                        <div class="text-center">
                            <div class="font-medium text-[10px] uppercase text-teal-light tracking-wider font-bold">Konstruksi</div>
                            <div class="text-sm font-semibold text-teal-light dark:text-teal-400 mt-1">{{ $stats['pipeline']['konstruksi'] ?? 0 }}</div>
                        </div>
                    </div>

                    <!-- Step 6: Serah Terima -->
                    <div class="flex flex-col items-center group cursor-pointer">
                        <div class="w-12 h-12 rounded-full bg-white dark:bg-gray-900 border-[3px] border-gray-100 dark:border-gray-700 flex items-center justify-center mb-3 group-hover:border-success dark:group-hover:border-emerald-500 transition-colors shadow-sm">
                            <i class="fa-solid fa-handshake text-gray-400 text-sm group-hover:text-success dark:group-hover:text-emerald-500"></i>
                        </div>
                        <div class="text-center">
                            <div class="font-medium text-[10px] uppercase text-textMuted-light tracking-wider">Selesai</div>
                            <div class="text-sm font-semibold text-success dark:text-emerald-500 mt-1">{{ $stats['pipeline']['serah_terima'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Operasional Status (KPI Cards format) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        
        <!-- Total Proyek Operasional -->
        <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 dark:bg-blue-500/20 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="flex items-center gap-4 mb-4 relative z-10">
                <div class="w-12 h-12 rounded-xl bg-blue-500/10 dark:bg-blue-500/20 flex items-center justify-center text-blue-500 dark:text-blue-400 text-sm">
                    <i class="fa-solid fa-earth-asia"></i>
                </div>
                <div>
                    <h3 class="text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">Total Proyek</h3>
                    <div class="text-sm font-medium">{{ $stats['operasional']['total'] ?? 0 }} <span class="text-sm font-medium text-textMuted-light dark:text-textMuted-dark">Lokasi</span></div>
                </div>
            </div>
            <div class="flex items-center gap-4 text-[10px] font-medium text-textMuted-light relative z-10 mt-2">
                <div class="flex items-center gap-1.5"><div class="w-2 h-2 rounded-full bg-blue-500"></div> Hub: {{ $stats['operasional']['hub'] ?? 0 }}</div>
                <div class="flex items-center gap-1.5"><div class="w-2 h-2 rounded-full bg-teal-light"></div> Penyangga: {{ $stats['operasional']['penyangga'] ?? 0 }}</div>
            </div>
        </div>

        <!-- Rata-rata Progres -->
        <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-success/10 dark:bg-success/20 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="flex items-center gap-4 mb-4 relative z-10">
                <div class="w-12 h-12 rounded-xl bg-success/10 dark:bg-success/20 flex items-center justify-center text-success dark:text-emerald-400 text-sm">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <div>
                    <h3 class="text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">Rata-Rata Progres</h3>
                    <div class="text-sm font-medium text-success dark:text-emerald-400">{{ $stats['operasional']['avg_progres'] ?? 0 }}%</div>
                </div>
            </div>
            <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-1.5 relative z-10 mt-2">
                <div class="bg-success h-1.5 rounded-full" style="width: {{ $stats['operasional']['avg_progres'] ?? 0 }}%"></div>
            </div>
        </div>

        <!-- Tahap Aktif Operasional -->
        <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 relative overflow-hidden group col-span-1 sm:col-span-2 lg:col-span-2 flex flex-col">
            <div class="absolute top-0 right-0 w-32 h-32 bg-warning/10 dark:bg-warning/20 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <h3 class="font-medium text-xs uppercase tracking-wider text-textMuted-light dark:text-textMuted-dark flex items-center gap-2 mb-4 relative z-10">
                <i class="fa-solid fa-list-check text-warning"></i> Sebaran Tahap Aktif Operasional
            </h3>
            <div class="flex-1 grid grid-cols-2 md:grid-cols-4 gap-3 relative z-10">
                @forelse($stats['operasional']['tahap_aktif'] ?? [] as $tahap => $count)
                <div class="bg-white/50 dark:bg-gray-900/50 rounded-xl p-3 border border-gray-100 dark:border-gray-800 flex flex-col justify-center items-center text-center">
                    <div class="text-xs font-medium text-textMuted-light dark:text-textMuted-dark capitalize mb-1">{{ str_replace('_', ' ', $tahap) }}</div>
                    <div class="text-sm font-bold text-textMain-light dark:text-textMain-dark">{{ $count }}</div>
                </div>
                @empty
                <div class="col-span-4 text-center text-xs text-textMuted-light py-4 flex flex-col items-center justify-center">
                    <i class="fa-solid fa-box-open text-2xl text-gray-300 dark:text-gray-700 mb-2"></i>
                    Belum ada data tahapan operasional
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection

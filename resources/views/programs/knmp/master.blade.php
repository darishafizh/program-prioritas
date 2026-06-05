@extends('layouts.app')

@section('title', 'KNMP - Master Data')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-3xl font-extrabold tracking-tight">Tahap <span class="text-info dark:text-blue-400 capitalize">{{ str_replace('-', ' ', $stage) }}</span></h2>
        <p class="text-textMuted-light dark:text-textMuted-dark text-sm mt-1">Kelola data master KNMP untuk tahap {{ str_replace('-', ' ', $stage) }}</p>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-wrap items-center gap-3">
        @if($stage === 'usulan')
            <button class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 text-textMain-light dark:text-textMain-dark rounded-xl px-4 py-2 text-sm font-bold shadow-sm transition-all flex items-center gap-2">
                <i class="fa-solid fa-file-excel text-success"></i> Download Template
            </button>
            <button class="bg-gradient-to-r from-success to-emerald-600 hover:from-emerald-600 hover:to-success text-white rounded-xl px-4 py-2 text-sm font-bold shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                <i class="fa-solid fa-upload"></i> Import Excel
            </button>
        @endif
        <button class="bg-gradient-to-r from-info to-blue-600 hover:from-blue-600 hover:to-info text-white rounded-xl px-4 py-2 text-sm font-bold shadow-md hover:shadow-lg transition-all flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Tambah Data
        </button>
    </div>
</div>

<div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-200 dark:border-gray-800 rounded-3xl shadow-sm overflow-hidden flex flex-col">
    <!-- Toolbar -->
    <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50 dark:bg-gray-800/20">
        <!-- Left: Entries and Action -->
        <div class="flex flex-wrap items-center gap-4 w-full sm:w-auto">
            <div class="flex items-center gap-2 text-sm text-textMuted-light dark:text-textMuted-dark">
                <span>Tampilkan</span>
                <select class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:border-info text-textMain-light dark:text-textMain-dark font-medium shadow-sm">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                    <option>100</option>
                </select>
                <span>entri</span>
            </div>
            
            @if($stage !== 'serah-terima')
            <div class="w-px h-6 bg-gray-300 dark:bg-gray-700 hidden sm:block"></div>
            <button class="bg-teal-light hover:bg-teal-600 text-white rounded-xl px-4 py-1.5 text-sm font-bold shadow-sm hover:shadow transition-all flex items-center gap-2">
                Pindah Tahap <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
            @endif
        </div>

        <!-- Right: Search -->
        <div class="relative w-full sm:w-64">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" placeholder="Cari data..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:border-info focus:ring-1 focus:ring-info outline-none transition-all shadow-sm">
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-xs uppercase tracking-wider font-semibold border-b border-gray-200 dark:border-gray-800">
                <tr>
                    <th class="px-6 py-4 w-10">
                        <div class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 rounded border-gray-300 bg-gray-50 dark:bg-gray-800 text-info focus:ring-info cursor-pointer transition-colors">
                        </div>
                    </th>
                    <th class="px-6 py-4">Lokasi KNMP</th>
                    
                    @if($stage === 'usulan')
                        <!-- Usulan specific columns -->
                        
                    @elseif($stage === 'survey' || $stage === 'ded')
                        <!-- Survey & DED specific columns -->
                        <th class="px-6 py-4">Koordinat</th>
                        
                    @elseif(in_array($stage, ['lelang', 'konstruksi', 'serah-terima']))
                        <!-- Lelang, Konstruksi, Serah Terima specific columns -->
                        <th class="px-6 py-4">Penyedia Jasa Konstruksi</th>
                        
                    @endif
                    
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
                @for($i=1; $i<=5; $i++)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors group cursor-pointer">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 rounded border-gray-300 bg-gray-50 dark:bg-gray-800 text-info focus:ring-info cursor-pointer transition-colors">
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-textMain-light dark:text-textMain-dark">KNMP Desa Bahari {{ $i }}</div>
                        <div class="text-xs text-textMuted-light dark:text-textMuted-dark mt-0.5">Region: Wilayah {{ $i % 2 == 0 ? 'Timur' : 'Barat' }}</div>
                    </td>
                    
                    @if($stage === 'usulan')
                        <!-- Usulan has no extra middle columns specified besides Lokasi, Status, Aksi -->
                    @elseif($stage === 'survey' || $stage === 'ded')
                        <td class="px-6 py-4">
                            <div class="font-mono text-xs bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded text-textMain-light dark:text-textMain-dark inline-block">
                                -6.1{{ $i }}5, 106.8{{ $i }}2
                            </div>
                        </td>
                    @elseif(in_array($stage, ['lelang', 'konstruksi', 'serah-terima']))
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold">PT</div>
                                <span class="font-semibold text-textMain-light dark:text-textMain-dark">PT Samudera Konstruksi {{ $i }}</span>
                            </div>
                        </td>
                    @endif
                    
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-md text-[0.7rem] font-bold bg-warning/10 text-warning dark:text-amber-400">
                            Dalam Proses
                        </span>
                    </td>
                    
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button class="w-8 h-8 rounded-lg bg-info/10 text-info hover:bg-info hover:text-white transition-colors flex items-center justify-center" title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="w-8 h-8 rounded-lg bg-danger/10 text-danger hover:bg-danger hover:text-white transition-colors flex items-center justify-center" title="Hapus">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800 flex justify-between items-center text-sm bg-gray-50/50 dark:bg-gray-800/20">
        <div class="text-textMuted-light font-medium">Menampilkan 1 - 5 dari 24 data</div>
        <div class="flex gap-1 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
            <button class="w-9 h-9 bg-white dark:bg-gray-900 flex items-center justify-center text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"><i class="fa-solid fa-chevron-left"></i></button>
            <button class="w-9 h-9 bg-info text-white font-bold flex items-center justify-center">1</button>
            <button class="w-9 h-9 bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 font-bold flex items-center justify-center transition-colors">2</button>
            <button class="w-9 h-9 bg-white dark:bg-gray-900 flex items-center justify-center text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
    </div>
</div>
@endsection

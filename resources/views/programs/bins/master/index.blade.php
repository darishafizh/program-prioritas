@extends('layouts.app')

@section('title', 'BINS - Master Data')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-3xl font-extrabold tracking-tight">Master Data <span class="text-warning dark:text-amber-500 capitalize">{{ $type }}</span></h2>
        <p class="text-textMuted-light dark:text-textMuted-dark text-sm mt-1">Kelola data referensi {{ $type }} untuk program BINS</p>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <button class="bg-gradient-to-r from-warning to-amber-600 hover:from-amber-600 hover:to-warning text-white rounded-xl px-4 py-2 text-sm font-bold shadow-md hover:shadow-lg transition-all flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Tambah {{ ucfirst($type) }}
        </button>
    </div>
</div>

<div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-200 dark:border-gray-800 rounded-3xl shadow-sm overflow-hidden flex flex-col">
    <!-- Toolbar -->
    <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50 dark:bg-gray-800/20">
        <div class="flex items-center gap-2 text-sm text-textMuted-light dark:text-textMuted-dark">
            <span>Tampilkan</span>
            <select class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:border-warning text-textMain-light dark:text-textMain-dark font-medium shadow-sm">
                <option>10</option>
                <option>25</option>
                <option>50</option>
            </select>
            <span>entri</span>
        </div>

        <div class="relative w-full sm:w-64">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" placeholder="Cari {{ $type }}..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:border-warning focus:ring-1 focus:ring-warning outline-none transition-all shadow-sm">
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-xs uppercase tracking-wider font-semibold border-b border-gray-200 dark:border-gray-800">
                <tr>
                    <th class="px-6 py-4 w-10">
                        <input type="checkbox" class="w-4 h-4 rounded border-gray-300 bg-gray-50 dark:bg-gray-800 text-warning focus:ring-warning cursor-pointer transition-colors">
                    </th>
                    <th class="px-6 py-4">Kode {{ ucfirst($type) }}</th>
                    <th class="px-6 py-4">Nama {{ ucfirst($type) }}</th>
                    @if($type === 'kolam')
                        <th class="px-6 py-4">Petak Induk</th>
                        <th class="px-6 py-4">Kapasitas</th>
                    @else
                        <th class="px-6 py-4">Luas Lahan</th>
                        <th class="px-6 py-4">Lokasi Geografis</th>
                    @endif
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
                @for($i=1; $i<=5; $i++)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors group cursor-pointer">
                    <td class="px-6 py-4">
                        <input type="checkbox" class="w-4 h-4 rounded border-gray-300 bg-gray-50 dark:bg-gray-800 text-warning focus:ring-warning cursor-pointer transition-colors">
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-mono text-xs bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded text-textMain-light dark:text-textMain-dark inline-block">
                            {{ strtoupper(substr($type, 0, 3)) }}-2026-00{{ $i }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-textMain-light dark:text-textMain-dark">{{ ucfirst($type) }} Sejahtera {{ $i }}</div>
                        <div class="text-xs text-textMuted-light dark:text-textMuted-dark mt-0.5">Dikelola oleh Kelompok BINS {{ $i }}</div>
                    </td>
                    
                    @if($type === 'kolam')
                        <td class="px-6 py-4 font-medium text-textMain-light dark:text-textMain-dark">
                            Petak Utama {{ ceil($i/2) }}
                        </td>
                        <td class="px-6 py-4">
                            {{ 1000 * $i }} Ekor
                        </td>
                    @else
                        <td class="px-6 py-4 font-medium text-textMain-light dark:text-textMain-dark">
                            {{ 1.5 + ($i * 0.2) }} Hektar
                        </td>
                        <td class="px-6 py-4 text-textMuted-light dark:text-textMuted-dark">
                            Desa Percontohan, Kec. BINS
                        </td>
                    @endif
                    
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
    
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800 flex justify-between items-center text-sm bg-gray-50/50 dark:bg-gray-800/20">
        <div class="text-textMuted-light font-medium">Menampilkan 1 - 5 dari 15 data</div>
        <div class="flex gap-1 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
            <button class="w-9 h-9 bg-white dark:bg-gray-900 flex items-center justify-center text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"><i class="fa-solid fa-chevron-left"></i></button>
            <button class="w-9 h-9 bg-warning text-white font-bold flex items-center justify-center">1</button>
            <button class="w-9 h-9 bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 font-bold flex items-center justify-center transition-colors">2</button>
            <button class="w-9 h-9 bg-white dark:bg-gray-900 flex items-center justify-center text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
    </div>
</div>
@endsection

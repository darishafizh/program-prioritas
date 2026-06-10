@extends('layouts.app')

@section('title', 'BINS - Operasional Produksi')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h2 class="text-xl font-semibold tracking-tight">Operasional BINS</h2>
        <p class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1">Input dan kelola data produksi harian Ikan Nila Salin dari setiap kolam.</p>
    </div>
    <button class="bg-gradient-to-r from-warning to-amber-600 hover:from-amber-600 hover:to-warning text-white rounded-md px-4 py-2.5 text-xs font-medium shadow-md hover:shadow-lg transition-all flex items-center justify-between gap-2"> Input Produksi Baru <i class="fa-solid fa-plus"></i> </button>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <!-- Form Panel (Left) -->
    <div class="lg:col-span-1 flex flex-col gap-6">
        <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 shadow-sm">
            <h3 class="font-medium text-base mb-4 flex items-center gap-2 text-textMain-light dark:text-textMain-dark">
                <i class="fa-solid fa-boxes-stacked text-warning"></i> Form Produksi
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wide mb-2">Tanggal Panen</label>
                    <div class="relative flex items-center bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm shadow-sm focus-within:ring-2 focus-within:ring-warning transition-all">
                        <i class="fa-regular fa-calendar text-gray-400 mr-3"></i>
                        <input type="date" class="bg-transparent border-none outline-none text-textMain-light dark:text-textMain-dark w-full font-medium">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wide mb-2">Pilih Kolam</label>
                    <select class="w-full bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-warning text-textMain-light dark:text-textMain-dark shadow-sm transition-all">
                        <option value="">-- Pilih Kolam BINS --</option>
                        <option>Kolam Sejahtera 1 (Petak 1)</option>
                        <option>Kolam Sejahtera 2 (Petak 1)</option>
                        <option>Kolam Harapan 1 (Petak 2)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wide mb-2">Volume Panen (Kg)</label>
                    <div class="relative flex items-center bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm shadow-sm focus-within:ring-2 focus-within:ring-warning transition-all">
                        <i class="fa-solid fa-scale-balanced text-gray-400 mr-3"></i>
                        <input type="number" placeholder="Contoh: 1500" class="bg-transparent border-none outline-none text-textMain-light dark:text-textMain-dark w-full font-medium">
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-800">
                <button class="w-full bg-warning hover:bg-amber-500 text-white rounded-md px-4 py-3 text-xs font-medium shadow-md hover:shadow-lg transition-all flex items-center justify-between  gap-2"> Simpan Data <i class="fa-solid fa-save"></i> </button>
            </div>
        </div>
    </div>

    <!-- Data Table Panel (Right) -->
    <div class="lg:col-span-3 bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl shadow-sm flex flex-col overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/20">
            <div>
                <h3 class="font-medium text-base flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-warning"></i> Log Produksi Terakhir
                </h3>
            </div>
            <div class="relative w-64">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" placeholder="Cari log..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:border-warning focus:ring-1 focus:ring-warning outline-none transition-all shadow-sm">
            </div>
        </div>
        
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
                    <tr>
                        <th class="px-6 py-4">Tanggal Panen</th>
                        <th class="px-6 py-4">Kolam & Petak</th>
                        <th class="px-6 py-4 text-right">Volume (Kg)</th>
                        <th class="px-6 py-4">Status Verifikasi</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @for($i=1; $i<=6; $i++)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-medium text-textMain-light dark:text-textMain-dark">{{ 10 + $i }} Juni 2026</div>
                            <div class="text-xs text-textMuted-light">08:{{ 10 + $i * 5 }} WIB</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-textMain-light dark:text-textMain-dark">Kolam Sejahtera {{ $i }}</div>
                            <div class="text-xs text-textMuted-light mt-0.5">Petak Utama {{ ceil($i/2) }}</div>
                        </td>
                        <td class="px-6 py-4 text-right font-mono font-medium text-success">
                            {{ number_format(1500 + ($i * 125)) }}
                        </td>
                        <td class="px-6 py-4">
                            @if($i % 3 == 0)
                            <span class="px-2.5 py-1 rounded-md text-[0.7rem] font-medium bg-warning/10 text-warning dark:text-amber-500">
                                <i class="fa-solid fa-clock mr-1"></i> Menunggu
                            </span>
                            @else
                            <span class="px-2.5 py-1 rounded-md text-[0.7rem] font-medium bg-success/10 text-success">
                                <i class="fa-solid fa-check mr-1"></i> Terverifikasi
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-textMuted-light hover:text-warning hover:bg-warning/10 transition-colors"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection










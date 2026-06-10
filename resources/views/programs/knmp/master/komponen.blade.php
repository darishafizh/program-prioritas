@extends('layouts.app')

@section('title', 'KNMP - Komponen Pekerjaan')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-xl font-semibold tracking-tight">Komponen Pekerjaan</h2>
        <p class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1">Standar komponen infrastruktur dan Harga Satuan Pokok (HSP) untuk proyek KNMP.</p>
    </div>

    <div class="flex items-center gap-3">
        <button class="bg-gradient-to-r from-info to-blue-600 hover:from-blue-600 hover:to-info text-white rounded-md px-4 py-2 text-xs font-medium shadow-md transition-all flex items-center justify-between gap-2"> Tambah Komponen <i class="fa-solid fa-plus"></i> </button>
    </div>
</div>

<div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl shadow-sm overflow-hidden">
    <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/20">
        <div class="relative w-full sm:w-64">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" placeholder="Cari komponen..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm outline-none focus:border-teal-light">
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs whitespace-nowrap">
            <thead class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
                <tr>
                    <th class="px-6 py-4">Kode</th>
                    <th class="px-6 py-4">Kategori Infrastruktur</th>
                    <th class="px-6 py-4">Nama Komponen</th>
                    <th class="px-6 py-4">Satuan</th>
                    <th class="px-6 py-4">Estimasi Harga Pokok</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30">
                    <td class="px-6 py-4 font-mono text-xs">INF-001</td>
                    <td class="px-6 py-4"><span class="px-2 py-1 bg-gray-100 dark:bg-gray-800 rounded text-xs font-medium text-gray-600 dark:text-gray-400">Fasilitas Umum</span></td>
                    <td class="px-6 py-4 font-medium text-textMain-light dark:text-textMain-dark">Pembangunan Balai Nelayan</td>
                    <td class="px-6 py-4 text-textMuted-light">Unit</td>
                    <td class="px-6 py-4 font-mono text-xs">Rp 150.000.000</td>
                    <td class="px-6 py-4 text-center">
                        <button class="text-teal-light hover:text-blue-600"><i class="fa-solid fa-pen-to-square"></i></button>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30">
                    <td class="px-6 py-4 font-mono text-xs">INF-002</td>
                    <td class="px-6 py-4"><span class="px-2 py-1 bg-gray-100 dark:bg-gray-800 rounded text-xs font-medium text-gray-600 dark:text-gray-400">Sanitasi</span></td>
                    <td class="px-6 py-4 font-medium text-textMain-light dark:text-textMain-dark">Instalasi Air Bersih (RO)</td>
                    <td class="px-6 py-4 text-textMuted-light">Paket</td>
                    <td class="px-6 py-4 font-mono text-xs">Rp 250.000.000</td>
                    <td class="px-6 py-4 text-center">
                        <button class="text-teal-light hover:text-blue-600"><i class="fa-solid fa-pen-to-square"></i></button>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30">
                    <td class="px-6 py-4 font-mono text-xs">INF-003</td>
                    <td class="px-6 py-4"><span class="px-2 py-1 bg-gray-100 dark:bg-gray-800 rounded text-xs font-medium text-gray-600 dark:text-gray-400">Produksi</span></td>
                    <td class="px-6 py-4 font-medium text-textMain-light dark:text-textMain-dark">Cold Storage 10 Ton</td>
                    <td class="px-6 py-4 text-textMuted-light">Unit</td>
                    <td class="px-6 py-4 font-mono text-xs">Rp 800.000.000</td>
                    <td class="px-6 py-4 text-center">
                        <button class="text-teal-light hover:text-blue-600"><i class="fa-solid fa-pen-to-square"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection












@extends('layouts.app')

@section('title', 'Master Data - ' . $activeProgram)

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h2 class="text-xl font-semibold mb-1">Master Data {{ $activeProgram }}</h2>
        <p class="text-textMuted-light dark:text-textMuted-dark text-sm">Pengelolaan Data Referensi Utama</p>
    </div>
    <button class="px-4 py-2 bg-gradient-to-r from-navy-light to-teal-light text-white rounded-md text-xs font-medium hover:shadow-lg transition-shadow shadow-md"> Tambah Data Baru <i class="fa-solid fa-plus mr-2"></i> </button>
</div>

<!-- Table Card (Simple CRUD) -->
<div class="bg-bgSurface-light dark:bg-bgSurface-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden mb-12">
    <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/30">
        <h3 class="font-medium text-base">Daftar Kategori Indikator</h3>
        <div class="relative">
            <i class="fa-solid fa-search absolute left-3 top-2.5 text-textMuted-light text-xs"></i>
            <input type="text" placeholder="Cari data..." class="pl-8 pr-4 py-1.5 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:outline-none focus:border-teal-light w-64">
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-gray-100/50 dark:bg-gray-800/50 text-textMuted-light dark:text-textMuted-dark text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4 font-medium w-16">ID</th>
                    <th class="px-6 py-4 font-medium">Nama Kategori</th>
                    <th class="px-6 py-4 font-medium">Deskripsi</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @for($i=1; $i<=5; $i++)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                    <td class="px-6 py-4 text-textMuted-light">#{{ str_pad($i, 3, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-6 py-4 font-medium text-textMain-light dark:text-textMain-dark">Kategori Contoh {{ $i }}</td>
                    <td class="px-6 py-4 text-textMuted-light">Deskripsi singkat untuk kategori indikator {{ $i }} dari program {{ $activeProgram }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 bg-success/10 text-success text-[0.65rem] font-medium rounded-lg uppercase">Aktif</span>
                    </td>
                    <td class="px-6 py-4 text-right flex justify-end gap-2">
                        <button class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors"><i class="fa-solid fa-pen text-xs"></i></button>
                        <button class="w-8 h-8 rounded-md bg-danger/10 text-danger hover:bg-danger hover:text-white transition-colors"><i class="fa-solid fa-trash text-xs"></i></button>
                    </td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 flex justify-between items-center text-sm">
        <span class="text-textMuted-light">Menampilkan 1 sampai 5 dari 24 data</span>
        <div class="flex gap-1">
            <button class="px-3 py-1 rounded-md bg-gray-100 dark:bg-gray-800 text-textMuted-light disabled opacity-50">Prev</button>
            <button class="px-3 py-1 rounded-md bg-teal-light text-white font-medium">1</button>
            <button class="px-3 py-1 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">2</button>
            <button class="px-3 py-1 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">3</button>
            <button class="px-3 py-1 rounded-md bg-gray-100 dark:bg-gray-800 hover:bg-gray-200">Next</button>
        </div>
    </div>
</div>
@endsection












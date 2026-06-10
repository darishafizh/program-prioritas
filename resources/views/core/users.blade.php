@extends('layouts.app')

@section('title', 'Manajemen Pengguna - Program Prioritas Portal')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-base font-medium tracking-tight">Manajemen Pengguna</h2>
        <p class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1">Kelola akses, peran, dan informasi pengguna sistem Program Prioritas.</p>
    </div>
    
    <button class="px-4 py-2 bg-teal-light text-white rounded-md text-xs font-medium shadow-sm hover:shadow-md hover:bg-teal-light/90 transition-all flex items-center justify-between gap-2"> Tambah Pengguna <i class="fa-solid fa-user-plus"></i> </button>
</div>

<div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-2xl shadow-sm overflow-hidden">
    <!-- Toolbar -->
    <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50 dark:bg-gray-800/30">
        <div class="relative w-full sm:w-64">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" placeholder="Cari nama atau username..." class="w-full pl-9 pr-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:border-teal-light focus:ring-1 focus:ring-teal-light outline-none transition-all">
        </div>
        
        <div class="flex gap-2">
            <select class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:border-teal-light focus:ring-1 outline-none font-medium">
                <option value="">Semua Role</option>
                <option value="admin">Administrator</option>
                <option value="user">Pengguna Biasa</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs whitespace-nowrap">
            <thead class="bg-gray-50/50 dark:bg-gray-800/50 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
                <tr>
                    <th class="px-6 py-4">Pengguna</th>
                    <th class="px-6 py-4">Username</th>
                    <th class="px-6 py-4">Role</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Aktivitas Terakhir</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                <!-- Row 1 -->
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-teal-light/10 text-textMain-light flex items-center justify-center font-medium">
                                A
                            </div>
                            <div>
                                <div class="font-medium text-textMain-light dark:text-textMain-dark">Administrator Utama</div>
                                <div class="text-xs text-textMuted-light">admin@roren.kkp.go.id</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-medium">admin</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-md text-[0.65rem] font-medium bg-navy-light/10 text-textMain-light dark:bg-blue-900/30 dark:text-blue-400">SUPER ADMIN</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="flex items-center gap-1.5 text-success font-medium text-xs">
                            <span class="w-2 h-2 rounded-full bg-success"></span> Aktif
                        </span>
                    </td>
                    <td class="px-6 py-4 text-xs text-textMuted-light">Baru saja</td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <button class="w-8 h-8 rounded-md text-textMuted-light hover:text-textMain-light hover:bg-teal-light/10 transition-colors"><i class="fa-solid fa-pen"></i></button>
                    </td>
                </tr>

                <!-- Row 2 -->
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 flex items-center justify-center font-medium">
                                B
                            </div>
                            <div>
                                <div class="font-medium text-textMain-light dark:text-textMain-dark">Budi Santoso</div>
                                <div class="text-xs text-textMuted-light">budi.s@roren.kkp.go.id</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-medium">budisantoso</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-md text-[0.65rem] font-medium bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300">USER DAERAH</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="flex items-center gap-1.5 text-success font-medium text-xs">
                            <span class="w-2 h-2 rounded-full bg-success"></span> Aktif
                        </span>
                    </td>
                    <td class="px-6 py-4 text-xs text-textMuted-light">2 jam yang lalu</td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <button class="w-8 h-8 rounded-md text-textMuted-light hover:text-textMain-light hover:bg-teal-light/10 transition-colors"><i class="fa-solid fa-pen"></i></button>
                        <button class="w-8 h-8 rounded-md text-textMuted-light hover:text-danger hover:bg-danger/10 transition-colors"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 flex justify-between items-center text-sm">
        <div class="text-textMuted-light dark:text-textMuted-dark">
            Menampilkan <span class="font-medium text-textMain-light dark:text-textMain-dark">1</span> - <span class="font-medium text-textMain-light dark:text-textMain-dark">2</span> dari <span class="font-medium text-textMain-light dark:text-textMain-dark">2</span> pengguna
        </div>
        <div class="flex gap-1">
            <button class="w-8 h-8 rounded-md flex items-center justify-center border border-gray-200 dark:border-gray-700 text-gray-400 opacity-50 cursor-not-allowed"><i class="fa-solid fa-chevron-left"></i></button>
            <button class="w-8 h-8 rounded-md flex items-center justify-center border border-teal-light bg-teal-light text-white font-medium">1</button>
            <button class="w-8 h-8 rounded-md flex items-center justify-center border border-gray-200 dark:border-gray-700 text-gray-400 opacity-50 cursor-not-allowed"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
    </div>
</div>
@endsection









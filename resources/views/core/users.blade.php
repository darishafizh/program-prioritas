@extends('layouts.app')

@section('title', 'Manajemen Pengguna - Program Prioritas Portal')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-3xl font-extrabold tracking-tight">Manajemen <span class="text-teal-light">Pengguna</span></h2>
        <p class="text-textMuted-light dark:text-textMuted-dark text-sm mt-1">Kelola akses, peran, dan informasi pengguna sistem Program Prioritas.</p>
    </div>
    
    <button class="px-4 py-2 bg-gradient-to-r from-navy-light to-teal-light dark:from-blue-600 dark:to-teal-dark text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all flex items-center gap-2">
        <i class="fa-solid fa-user-plus"></i> Tambah Pengguna
    </button>
</div>

<div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm overflow-hidden">
    <!-- Toolbar -->
    <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50 dark:bg-gray-800/30">
        <div class="relative w-full sm:w-64">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" placeholder="Cari nama atau username..." class="w-full pl-9 pr-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:border-teal-light focus:ring-1 focus:ring-teal-light outline-none transition-all">
        </div>
        
        <div class="flex gap-2">
            <select class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:border-teal-light focus:ring-1 outline-none">
                <option value="">Semua Role</option>
                <option value="admin">Administrator</option>
                <option value="user">Pengguna Biasa</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-gray-50 dark:bg-gray-800/50 text-textMuted-light dark:text-textMuted-dark text-xs uppercase tracking-wider font-semibold border-b border-gray-200 dark:border-gray-800">
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
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-info to-teal-light text-white flex items-center justify-center font-bold shadow-sm">
                                A
                            </div>
                            <div>
                                <div class="font-bold text-textMain-light dark:text-textMain-dark">Administrator Utama</div>
                                <div class="text-xs text-textMuted-light">admin@roren.kkp.go.id</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-medium">admin</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-md text-[0.65rem] font-bold bg-navy-light/10 text-navy-light dark:bg-blue-900/30 dark:text-blue-400">SUPER ADMIN</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="flex items-center gap-1.5 text-success font-medium text-xs">
                            <span class="w-2 h-2 rounded-full bg-success"></span> Aktif
                        </span>
                    </td>
                    <td class="px-6 py-4 text-xs text-textMuted-light">Baru saja</td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <button class="w-8 h-8 rounded-lg text-textMuted-light hover:text-teal-light hover:bg-teal-light/10 transition-colors"><i class="fa-solid fa-pen"></i></button>
                    </td>
                </tr>

                <!-- Row 2 -->
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 flex items-center justify-center font-bold">
                                B
                            </div>
                            <div>
                                <div class="font-bold text-textMain-light dark:text-textMain-dark">Budi Santoso</div>
                                <div class="text-xs text-textMuted-light">budi.s@roren.kkp.go.id</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-medium">budisantoso</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-md text-[0.65rem] font-bold bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300">USER DAERAH</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="flex items-center gap-1.5 text-success font-medium text-xs">
                            <span class="w-2 h-2 rounded-full bg-success"></span> Aktif
                        </span>
                    </td>
                    <td class="px-6 py-4 text-xs text-textMuted-light">2 jam yang lalu</td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <button class="w-8 h-8 rounded-lg text-textMuted-light hover:text-teal-light hover:bg-teal-light/10 transition-colors"><i class="fa-solid fa-pen"></i></button>
                        <button class="w-8 h-8 rounded-lg text-textMuted-light hover:text-danger hover:bg-danger/10 transition-colors"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800 flex justify-between items-center text-sm">
        <div class="text-textMuted-light dark:text-textMuted-dark">
            Menampilkan <span class="font-bold text-textMain-light dark:text-textMain-dark">1</span> - <span class="font-bold text-textMain-light dark:text-textMain-dark">2</span> dari <span class="font-bold text-textMain-light dark:text-textMain-dark">2</span> pengguna
        </div>
        <div class="flex gap-1">
            <button class="w-8 h-8 rounded-lg flex items-center justify-center border border-gray-200 dark:border-gray-700 text-gray-400 opacity-50 cursor-not-allowed"><i class="fa-solid fa-chevron-left"></i></button>
            <button class="w-8 h-8 rounded-lg flex items-center justify-center border border-teal-light bg-teal-light text-white font-bold">1</button>
            <button class="w-8 h-8 rounded-lg flex items-center justify-center border border-gray-200 dark:border-gray-700 text-gray-400 opacity-50 cursor-not-allowed"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
    </div>
</div>
@endsection

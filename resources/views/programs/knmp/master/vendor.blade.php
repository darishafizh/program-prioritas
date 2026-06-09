@extends('layouts.app')

@section('title', 'KNMP - Data Vendor')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-3xl font-extrabold tracking-tight">Data <span class="text-info dark:text-blue-400">Vendor / Penyedia</span></h2>
        <p class="text-textMuted-light dark:text-textMuted-dark text-sm mt-1">Direktori perusahaan kontraktor pelaksana proyek KNMP berserta status performansinya.</p>
    </div>

    <div class="flex items-center gap-3">
        <button class="bg-gradient-to-r from-info to-blue-600 hover:from-blue-600 hover:to-info text-white rounded-xl px-4 py-2 text-sm font-bold shadow-md transition-all flex items-center gap-2">
            <i class="fa-solid fa-user-plus"></i> Register Vendor Baru
        </button>
    </div>
</div>

<div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-200 dark:border-gray-800 rounded-3xl shadow-sm overflow-hidden">
    <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/20">
        <div class="relative w-full sm:w-64">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" placeholder="Cari nama vendor / NPWP..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm outline-none focus:border-info">
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-xs uppercase tracking-wider font-semibold border-b border-gray-200 dark:border-gray-800">
                <tr>
                    <th class="px-6 py-4">Nama Perusahaan</th>
                    <th class="px-6 py-4">NPWP</th>
                    <th class="px-6 py-4">Direktur Utama</th>
                    <th class="px-6 py-4">Kontak / Email</th>
                    <th class="px-6 py-4">Kualifikasi SBU</th>
                    <th class="px-6 py-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30">
                    <td class="px-6 py-4">
                        <div class="font-bold text-textMain-light dark:text-textMain-dark">PT Samudera Konstruksi</div>
                        <div class="text-xs text-textMuted-light mt-0.5">Berdiri sejak 2010</div>
                    </td>
                    <td class="px-6 py-4 font-mono text-xs">01.234.567.8-901.000</td>
                    <td class="px-6 py-4 text-textMuted-light">Budi Santoso</td>
                    <td class="px-6 py-4">
                        <div class="text-xs">0812-3456-7890</div>
                        <div class="text-xs text-textMuted-light">admin@samudera.co.id</div>
                    </td>
                    <td class="px-6 py-4"><span class="px-2 py-1 bg-gray-100 dark:bg-gray-800 rounded text-xs font-bold text-gray-600 dark:text-gray-400">Menengah (M1)</span></td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-success/10 text-success text-xs font-bold rounded-md">Aktif / Eligible</span>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30">
                    <td class="px-6 py-4">
                        <div class="font-bold text-textMain-light dark:text-textMain-dark">CV Bina Desain Arsitektur</div>
                        <div class="text-xs text-textMuted-light mt-0.5">Berdiri sejak 2015</div>
                    </td>
                    <td class="px-6 py-4 font-mono text-xs">02.987.654.3-210.000</td>
                    <td class="px-6 py-4 text-textMuted-light">Ahmad Yani</td>
                    <td class="px-6 py-4">
                        <div class="text-xs">0819-8765-4321</div>
                        <div class="text-xs text-textMuted-light">info@binadesain.com</div>
                    </td>
                    <td class="px-6 py-4"><span class="px-2 py-1 bg-gray-100 dark:bg-gray-800 rounded text-xs font-bold text-gray-600 dark:text-gray-400">Kecil (K)</span></td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-danger/10 text-danger text-xs font-bold rounded-md"><i class="fa-solid fa-ban"></i> Blacklist (2025)</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

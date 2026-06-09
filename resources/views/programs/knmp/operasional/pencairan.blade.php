@extends('layouts.app')

@section('title', 'KNMP - Manajemen Pencairan Termin')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-3xl font-extrabold tracking-tight">Manajemen <span class="text-success dark:text-green-400">Pencairan Dana</span></h2>
        <p class="text-textMuted-light dark:text-textMuted-dark text-sm mt-1">Pemantauan progres keuangan dan pembayaran termin/MC (Monthly Certificate) kepada kontraktor.</p>
    </div>

    <div class="flex items-center gap-3">
        <button class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-textMain-light dark:text-textMain-dark rounded-xl px-4 py-2 text-sm font-bold shadow-sm transition-all flex items-center gap-2 hover:bg-gray-50 dark:hover:bg-gray-700">
            <i class="fa-solid fa-file-invoice"></i> Buat Surat Tagihan
        </button>
    </div>
</div>

<div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-200 dark:border-gray-800 rounded-3xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-gray-50 dark:bg-gray-800/50 text-textMuted-light dark:text-textMuted-dark text-xs uppercase tracking-wider font-semibold border-b border-gray-200 dark:border-gray-800">
                <tr>
                    <th class="px-6 py-4">Lokasi Proyek</th>
                    <th class="px-6 py-4">Kontraktor</th>
                    <th class="px-6 py-4">Uang Muka (20%)</th>
                    <th class="px-6 py-4">Termin 1 (50%)</th>
                    <th class="px-6 py-4">Termin 2 (100%)</th>
                    <th class="px-6 py-4">Retensi (5%)</th>
                    <th class="px-6 py-4 text-center">Status Keseluruhan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30">
                    <td class="px-6 py-4">
                        <div class="font-bold text-textMain-light dark:text-textMain-dark">KNMP Desa Bahari 1</div>
                        <div class="text-xs text-textMuted-light mt-0.5">Pagu: Rp 2.500.000.000</div>
                    </td>
                    <td class="px-6 py-4 text-textMuted-light">PT Samudera Konstruksi</td>
                    <td class="px-6 py-4">
                        <span class="text-success text-xs font-bold"><i class="fa-solid fa-check"></i> Cair</span><br>
                        <span class="text-[0.65rem] text-textMuted-light">SP2D: 10 Mar 2026</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-success text-xs font-bold"><i class="fa-solid fa-check"></i> Cair</span><br>
                        <span class="text-[0.65rem] text-textMuted-light">SP2D: 15 Mei 2026</span>
                    </td>
                    <td class="px-6 py-4">
                        <button class="px-2 py-1 bg-info/10 text-info text-xs font-bold rounded hover:bg-info hover:text-white transition-colors">Ajukan Pencairan</button><br>
                        <span class="text-[0.65rem] text-warning">Syarat Progres: >95%</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-gray-400 text-xs"><i class="fa-solid fa-lock"></i> Terkunci</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 mt-2">
                            <div class="bg-info h-1.5 rounded-full" style="width: 70%"></div>
                        </div>
                        <span class="text-[0.65rem] font-bold text-textMuted-light">Realisasi: 70%</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

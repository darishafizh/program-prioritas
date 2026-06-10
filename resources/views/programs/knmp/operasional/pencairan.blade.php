@extends('layouts.app')

@section('title', 'KNMP - Manajemen Pencairan Termin')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-xl font-semibold tracking-tight">Manajemen Pencairan Dana</h2>
        <p class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1">Pemantauan progres keuangan dan pembayaran termin/MC (Monthly Certificate) kepada kontraktor.</p>
    </div>

    <div class="flex items-center gap-3">
        <button class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-textMain-light dark:text-textMain-dark rounded-md px-4 py-2 text-xs font-medium shadow-sm transition-all flex items-center justify-between gap-2 hover:bg-gray-50 dark:hover:bg-gray-700"> Buat Surat Tagihan <i class="fa-solid fa-file-invoice"></i> </button>
    </div>
</div>

<div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs whitespace-nowrap">
            <thead class="bg-gray-50 dark:bg-gray-800/50 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
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
                        <div class="font-medium text-textMain-light dark:text-textMain-dark">KNMP Desa Bahari 1</div>
                        <div class="text-xs text-textMuted-light mt-0.5">Pagu: Rp 2.500.000.000</div>
                    </td>
                    <td class="px-6 py-4 text-textMuted-light">PT Samudera Konstruksi</td>
                    <td class="px-6 py-4">
                        <span class="text-success text-xs font-medium"><i class="fa-solid fa-check"></i> Cair</span><br>
                        <span class="text-[0.65rem] text-textMuted-light">SP2D: 10 Mar 2026</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-success text-xs font-medium"><i class="fa-solid fa-check"></i> Cair</span><br>
                        <span class="text-[0.65rem] text-textMuted-light">SP2D: 15 Mei 2026</span>
                    </td>
                    <td class="px-6 py-4">
                        <button class="px-2 py-1 bg-teal-light/10 text-teal-light text-xs font-medium rounded hover:bg-teal-light hover:text-white transition-colors">Ajukan Pencairan</button><br>
                        <span class="text-[0.65rem] text-warning">Syarat Progres: >95%</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-gray-400 text-xs"><i class="fa-solid fa-lock"></i> Terkunci</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 mt-2">
                            <div class="bg-teal-light h-1.5 rounded-full" style="width: 70%"></div>
                        </div>
                        <span class="text-[0.65rem] font-medium text-textMuted-light">Realisasi: 70%</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection












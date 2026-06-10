@extends('layouts.app')

@section('title', 'Evaluasi - ' . $activeProgram)

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h2 class="text-xl font-semibold mb-1">Evaluasi & Pelaporan {{ $activeProgram }}</h2>
        <p class="text-textMuted-light dark:text-textMuted-dark text-sm">Pemantauan Kinerja dan Ekspor Dokumen Resmi</p>
    </div>
</div>

<!-- Filter Box -->
<div class="bg-bgSurface-light dark:bg-bgSurface-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <div>
            <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase mb-2">Tahun Anggaran</label>
            <select class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm outline-none">
                <option>2026</option>
                <option>2025</option>
                <option>2024</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase mb-2">Provinsi</label>
            <select class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm outline-none">
                <option>Semua Provinsi</option>
                <option>Jawa Tengah</option>
                <option>Jawa Timur</option>
                <option>Nusa Tenggara Barat</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase mb-2">Status Pelaporan</label>
            <select class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm outline-none">
                <option>Semua Status</option>
                <option>Selesai</option>
                <option>Menunggu Validasi</option>
                <option>Revisi</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button class="flex-1 py-2 rounded-md bg-navy-light dark:bg-blue-600 text-white text-xs font-medium hover:bg-navy-dark dark:hover:bg-blue-700 transition-colors">
                Terapkan Filter
            </button>
            <button class="w-10 flex items-center justify-center rounded-md border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                <i class="fa-solid fa-rotate-right text-textMuted-light text-sm"></i>
            </button>
        </div>
    </div>
</div>

<!-- Laporan Kinerja Table -->
<div class="bg-bgSurface-light dark:bg-bgSurface-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden mb-12">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/30">
        <h3 class="font-medium text-base flex items-center gap-2">
            <i class="fa-solid fa-table-list text-textMain-light"></i>
            Matriks Evaluasi Kinerja
        </h3>
        
        <div class="flex gap-2">
            <button class="px-3 py-1.5 bg-green-100 text-green-700 border border-green-200 rounded-md text-xs font-medium hover:bg-green-200 transition-colors"> Excel <i class="fa-solid fa-file-excel mr-1"></i> </button>
            <button class="px-3 py-1.5 bg-red-100 text-red-700 border border-red-200 rounded-md text-xs font-medium hover:bg-red-200 transition-colors"> Cetak PDF <i class="fa-solid fa-file-pdf mr-1"></i> </button>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-gray-100/50 dark:bg-gray-800/50 text-textMuted-light dark:text-textMuted-dark text-[0.65rem] uppercase tracking-wider font-medium">
                <tr>
                    <th class="px-5 py-3 border-b border-gray-100 dark:border-gray-800">No</th>
                    <th class="px-5 py-3 border-b border-gray-100 dark:border-gray-800">Wilayah / Titik Lokasi</th>
                    <th class="px-5 py-3 border-b border-gray-100 dark:border-gray-800 text-center">Pagu Anggaran</th>
                    <th class="px-5 py-3 border-b border-gray-100 dark:border-gray-800 text-center">Realisasi (%)</th>
                    <th class="px-5 py-3 border-b border-gray-100 dark:border-gray-800 text-center">Fisik (%)</th>
                    <th class="px-5 py-3 border-b border-gray-100 dark:border-gray-800 text-center">Skor Evaluasi</th>
                    <th class="px-5 py-3 border-b border-gray-100 dark:border-gray-800 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @for($i=1; $i<=6; $i++)
                @php
                    $fisik = rand(40, 100);
                    $anggaran = rand(30, $fisik); // Anggaran biasanya tidak melebihi fisik
                    $skor = ($fisik + $anggaran) / 2;
                    
                    if($skor >= 85) { 
                        $status = 'Sangat Baik'; 
                        $badgeClass = 'bg-success/10 text-success'; 
                    } elseif($skor >= 70) { 
                        $status = 'Baik'; 
                        $badgeClass = 'bg-teal-light/10 text-teal-light'; 
                    } elseif($skor >= 50) { 
                        $status = 'Kurang'; 
                        $badgeClass = 'bg-warning/10 text-warning'; 
                    } else { 
                        $status = 'Buruk'; 
                        $badgeClass = 'bg-danger/10 text-danger'; 
                    }
                @endphp
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                    <td class="px-5 py-3 font-medium">{{ $i }}</td>
                    <td class="px-5 py-3">
                        <div class="font-medium">Kelompok Tani Harapan {{ $i }}</div>
                        <div class="text-xs text-textMuted-light">Kab. Demak, Jawa Tengah</div>
                    </td>
                    <td class="px-5 py-3 text-center text-xs font-medium">Rp {{ number_format(rand(100, 500), 0, ',', '.') }}.000.000</td>
                    <td class="px-5 py-3 text-center">
                        <span class="text-sm font-medium">{{ $anggaran }}%</span>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <span class="text-sm font-medium">{{ $fisik }}%</span>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <div class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $badgeClass }} font-medium text-xs">
                            {{ round($skor) }}
                        </div>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <span class="px-2 py-1 {{ $badgeClass }} text-[0.6rem] font-medium rounded uppercase">{{ $status }}</span>
                    </td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
@endsection











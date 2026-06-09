@extends('layouts.app')

@section('title', 'KNMP - Operasional Proyek')

@section('content')
@php
    $currentStage = request()->query('stage', 'usulan');
    
    $stages = [
        'usulan' => ['label' => 'Usulan (DIPA)', 'icon' => 'fa-file-invoice'],
        'survei' => ['label' => 'Survei Topografi/Batimetri', 'icon' => 'fa-compass-drafting'],
        'ded' => ['label' => 'Penyusunan DED', 'icon' => 'fa-pen-ruler'],
        'lelang' => ['label' => 'Siap Lelang', 'icon' => 'fa-gavel'],
        'konstruksi' => ['label' => 'Pelaksanaan Konstruksi', 'icon' => 'fa-helmet-safety'],
        'serah-terima' => ['label' => 'Serah Terima (BAST)', 'icon' => 'fa-handshake'],
    ];
@endphp

<div class="mb-6 flex flex-col gap-4">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-3xl font-extrabold tracking-tight">Operasional <span class="text-info dark:text-blue-400">Proyek KNMP</span></h2>
            <p class="text-textMuted-light dark:text-textMuted-dark text-sm mt-1">Siklus 2: Pelaksanaan teknis dari lokasi definitif hingga serah terima pembangunan.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            @if($currentStage === 'konstruksi')
            <button class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 text-textMain-light dark:text-textMain-dark rounded-xl px-4 py-2 text-sm font-bold shadow-sm transition-all flex items-center gap-2">
                <i class="fa-solid fa-cloud-arrow-up text-info"></i> Import Progres Massal
            </button>
            @endif
        </div>
    </div>

    <!-- Stepper / Tabs -->
    <div class="bg-white dark:bg-bgSurface-dark rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 p-2 overflow-x-auto hide-scrollbar">
        <div class="flex min-w-max">
            @foreach($stages as $key => $data)
            <a href="?stage={{ $key }}" class="flex items-center group relative px-4 py-3">
                <div class="flex items-center gap-3 relative z-10">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs transition-colors
                        {{ $currentStage === $key ? 'bg-success text-white shadow-md' : 'bg-gray-100 dark:bg-gray-800 text-gray-400 group-hover:bg-success/20 group-hover:text-success' }}">
                        <i class="fa-solid {{ $data['icon'] }}"></i>
                    </div>
                    <span class="font-bold text-sm transition-colors {{ $currentStage === $key ? 'text-success' : 'text-textMuted-light dark:text-textMuted-dark group-hover:text-success' }}">
                        {{ $data['label'] }}
                    </span>
                </div>
                @if(!$loop->last)
                <div class="w-8 h-px bg-gray-300 dark:bg-gray-700 mx-3"></div>
                @endif
                
                @if($currentStage === $key)
                <div class="absolute inset-0 bg-success/5 dark:bg-emerald-500/10 rounded-xl"></div>
                @endif
            </a>
            @endforeach
        </div>
    </div>
</div>

<div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-200 dark:border-gray-800 rounded-3xl shadow-sm overflow-hidden flex flex-col">
    <!-- Toolbar -->
    <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50 dark:bg-gray-800/20">
        <div class="flex flex-wrap items-center gap-4 w-full sm:w-auto">
            <div class="flex items-center gap-2 text-sm text-textMuted-light dark:text-textMuted-dark">
                <span>Tampilkan</span>
                <select class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:border-info text-textMain-light dark:text-textMain-dark font-medium shadow-sm">
                    <option>10</option>
                    <option>25</option>
                </select>
                <span>entri</span>
            </div>
            
            @if($currentStage !== 'serah-terima')
            <div class="w-px h-6 bg-gray-300 dark:bg-gray-700 hidden sm:block"></div>
            <button class="bg-success hover:bg-emerald-600 text-white rounded-xl px-4 py-1.5 text-sm font-bold shadow-sm hover:shadow transition-all flex items-center gap-2">
                Pindah Tahap Berikutnya <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
            @endif
        </div>

        <div class="relative w-full sm:w-64">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" placeholder="Cari proyek..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:border-success outline-none transition-all shadow-sm">
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-xs uppercase tracking-wider font-semibold border-b border-gray-200 dark:border-gray-800">
                <tr>
                    <th class="px-6 py-4 w-10">
                        <input type="checkbox" class="w-4 h-4 rounded border-gray-300 bg-gray-50 text-success focus:ring-success cursor-pointer transition-colors">
                    </th>
                    <th class="px-6 py-4">Nama Proyek (Lokasi Definitif)</th>
                    
                    @if($currentStage === 'usulan' || $currentStage === 'survei' || $currentStage === 'ded')
                        <th class="px-6 py-4">Konsultan Perencana</th>
                    @elseif($currentStage === 'lelang')
                        <th class="px-6 py-4">Kode Tender</th>
                    @elseif($currentStage === 'konstruksi' || $currentStage === 'serah-terima')
                        <th class="px-6 py-4">Kontraktor Pelaksana</th>
                        <th class="px-6 py-4">Progres Fisik</th>
                    @endif
                    
                    <th class="px-6 py-4">Status Update</th>
                    <th class="px-6 py-4 text-center">Tindakan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
                @for($i=1; $i<=5; $i++)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors group cursor-pointer">
                    <td class="px-6 py-4">
                        <input type="checkbox" class="w-4 h-4 rounded border-gray-300 bg-gray-50 text-success focus:ring-success cursor-pointer transition-colors">
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-textMain-light dark:text-textMain-dark">KNMP Desa Bahari {{ $i }}</div>
                        <div class="text-xs text-textMuted-light dark:text-textMuted-dark mt-0.5">Anggaran: Rp {{ 2 + $i }},5 Miliar</div>
                    </td>
                    
                    @if($currentStage === 'usulan' || $currentStage === 'survei' || $currentStage === 'ded')
                        <td class="px-6 py-4 text-textMuted-light dark:text-textMuted-dark">
                            CV Bina Desain {{ $i }}
                        </td>
                    @elseif($currentStage === 'lelang')
                        <td class="px-6 py-4 font-mono text-xs">
                            LPSE-{{ 10000 + $i }}
                        </td>
                    @elseif($currentStage === 'konstruksi' || $currentStage === 'serah-terima')
                        <td class="px-6 py-4 text-textMuted-light dark:text-textMuted-dark">
                            PT Samudera Konstruksi {{ $i }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-24 h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                    @php $prog = $currentStage === 'serah-terima' ? 100 : rand(20, 80); @endphp
                                    <div class="h-full bg-success" style="width: {{ $prog }}%"></div>
                                </div>
                                <span class="text-xs font-bold">{{ $prog }}%</span>
                            </div>
                        </td>
                    @endif
                    
                    <td class="px-6 py-4">
                        @if($currentStage === 'serah-terima')
                        <span class="px-2.5 py-1 rounded-md text-[0.7rem] font-bold bg-success/10 text-success">
                            Selesai (BAST)
                        </span>
                        @else
                        <span class="px-2.5 py-1 rounded-md text-[0.7rem] font-bold bg-info/10 text-info">
                            Sedang Berjalan
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button class="w-8 h-8 rounded-lg bg-info/10 text-info hover:bg-info hover:text-white transition-colors flex items-center justify-center" title="Update Progres">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="w-8 h-8 rounded-lg bg-success/10 text-success hover:bg-success hover:text-white transition-colors flex items-center justify-center" title="Lampirkan Dokumen">
                                <i class="fa-solid fa-paperclip"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800 flex justify-between items-center text-sm bg-gray-50/50 dark:bg-gray-800/20">
        <div class="text-textMuted-light font-medium">Menampilkan 1 - 5 data</div>
        <div class="flex gap-1 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
            <button class="w-9 h-9 bg-success text-white font-bold flex items-center justify-center">1</button>
        </div>
    </div>
</div>
@endsection

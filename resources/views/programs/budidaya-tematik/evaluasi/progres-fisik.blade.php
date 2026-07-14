@extends('layouts.app')

@section('title', 'Budidaya Tematik - Evaluasi Progres Fisik')

@section('content')
    <div x-data="{ batch: '' }">
        {{-- Header & Filters --}}
        <div class="mb-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h2 class="text-xl font-semibold tracking-tight">Evaluasi Progres Fisik Budidaya Tematik</h2>
                <p class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1">Evaluasi historis konstruksi bak kolam & aerator per KDKMP — perbandingan rencana vs aktual.</p>
            </div>

            {{-- Filter Bar --}}
            <form action="{{ url()->current() }}" method="GET" class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                <div class="relative">
                    <select name="batch_id" onchange="this.form.submit()"
                        class="appearance-none bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-xl px-4 py-2 pr-10 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-teal-light text-textMain-light dark:text-textMain-dark">
                        <option value="">Semua Tahap</option>
                        @foreach ($filter_batches ?? [] as $batchItem)
                            <option value="{{ $batchItem['id'] }}" {{ request('batch_id') == $batchItem['id'] ? 'selected' : '' }}>
                                {{ $batchItem['name'] }}
                            </option>
                        @endforeach
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                </div>

                <div class="relative flex items-center bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-xl px-4 py-2 text-xs font-medium focus-within:ring-2 focus-within:ring-teal-light">
                    <i class="fa-regular fa-calendar text-gray-400 mr-2 shrink-0"></i>
                    <input type="date" name="date" value="{{ request('date') ?: now()->format('Y-m-d') }}" onchange="this.form.submit()"
                        class="bg-transparent border-none outline-none text-textMain-light dark:text-textMain-dark w-32">
                </div>

                <button type="button" onclick="window.print()" class="px-4 py-2 bg-danger/10 border border-danger/20 text-danger rounded-xl text-xs font-medium hover:bg-danger/20 transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-file-pdf"></i> PDF
                </button>
            </form>
        </div>

        {{-- KPI Cards (Responsive Grid 1 -> 2 -> 4) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            {{-- Konstruksi Aktif --}}
            <x-stat-card
                title="Konstruksi Aktif"
                icon="fa-solid fa-person-digging"
                icon-color="text-warning dark:text-amber-500"
                icon-bg="bg-warning/10 dark:bg-amber-400/20"
                value="{{ $stats['konstruksi_aktif'] ?? 17 }}"
                unit="Lokasi"
            />

            {{-- Rata-Rata Progres --}}
            <x-stat-card
                title="Rata-Rata Progres"
                icon="fa-solid fa-chart-pie"
                icon-color="text-teal-light dark:text-teal-400"
                icon-bg="bg-teal-light/10 dark:bg-teal-400/20"
                value="{{ $stats['rata_progres'] ?? 78.4 }}"
                unit="%"
            >
                <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2 mt-2">
                    <div class="bg-teal-light dark:bg-teal-400 h-2 rounded-full transition-all" style="width: {{ $stats['rata_progres'] ?? 78.4 }}%"></div>
                </div>
            </x-stat-card>

            {{-- Total Selesai --}}
            <x-stat-card
                title="Total Selesai"
                icon="fa-solid fa-check-double"
                icon-color="text-success dark:text-emerald-400"
                icon-bg="bg-success/10 dark:bg-success/20"
                value="{{ $stats['total_selesai'] ?? 28 }}"
                unit="Lokasi"
            />

            {{-- Kritis / Terlambat --}}
            <x-stat-card
                title="Deviasi Kritis"
                icon="fa-solid fa-triangle-exclamation"
                icon-color="text-danger dark:text-red-400"
                icon-bg="bg-danger/10 dark:bg-danger/20"
                value="{{ $stats['kritis_terlambat'] ?? 4 }}"
                unit="Lokasi"
            />
        </div>

        {{-- Daftar Audit & Deviasi Lokasi --}}
        <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl overflow-hidden pb-2 flex flex-col">
            <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-sm font-medium text-textMain-light dark:text-textMain-dark">Analisis Deviasi Kurva S per Lokasi KDKMP</h3>
                    <p class="text-xs text-textMuted-light dark:text-textMuted-dark mt-1">Perbandingan target rencana konstruksi terhadap realisasi lapangan.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs whitespace-nowrap">
                    <thead class="bg-white dark:bg-gray-900 text-[11px] font-normal uppercase text-textMuted-light dark:text-textMuted-dark border-b border-gray-100 dark:border-gray-800">
                        <tr>
                            <th class="py-4 px-6 w-12 text-center">No</th>
                            <th class="py-4 px-6">KDKMP & Wilayah</th>
                            <th class="py-4 px-6">Progres & Deviasi</th>
                            <th class="py-4 px-6 text-center">Status Kurva</th>
                            <th class="py-4 px-6">Catatan Evaluasi / Audit</th>
                            <th class="py-4 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-xs font-medium">
                        @forelse($listLokasi as $lok)
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-800/40 transition-colors">
                            <td class="py-4 px-6 text-center text-textMuted-light">{{ $lok['no'] }}</td>
                            <td class="py-4 px-6">
                                <div class="font-semibold text-textMain-light dark:text-white">{{ $lok['kdkmp'] }}</div>
                                <div class="text-[11px] text-textMuted-light mt-0.5 truncate"><i class="fa-solid fa-location-dot mr-1"></i>{{ $lok['kabupaten'] }}</div>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center justify-between text-xs w-32">
                                        <span class="text-textMuted-light">Aktual: <span class="font-bold text-textMain-light dark:text-white">{{ $lok['progres_aktual'] }}%</span></span>
                                        @if($lok['deviasi'] < 0)
                                            <span class="font-bold text-[10px] text-danger">{{ $lok['deviasi'] }}%</span>
                                        @elseif($lok['deviasi'] > 0)
                                            <span class="font-bold text-[10px] text-success">+{{ $lok['deviasi'] }}%</span>
                                        @else
                                            <span class="font-bold text-[10px] text-textMuted-light">0.0%</span>
                                        @endif
                                    </div>
                                    <div class="w-32 bg-gray-100 dark:bg-gray-800 rounded-full h-1.5 overflow-hidden flex relative">
                                        <div class="bg-gray-300 dark:bg-gray-600 h-1.5 absolute left-0 z-0" style="width: {{ $lok['progres_rencana'] }}%"></div>
                                        <div class="bg-teal-light h-1.5 absolute left-0 z-10 opacity-80" style="width: {{ $lok['progres_aktual'] }}%"></div>
                                    </div>
                                    <div class="text-[10px] text-textMuted-light mt-0.5">Target: {{ $lok['progres_rencana'] }}%</div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full text-[11px] font-semibold border {{ $lok['status_class'] }}">
                                    {{ $lok['status_kurva'] }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-textMuted-light leading-relaxed max-w-xs">{{ $lok['catatan_audit'] }}</td>
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                <button class="px-4 py-2 rounded-xl bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-all text-xs font-medium">
                                    Detail Audit
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-textMuted-light">Belum ada data evaluasi progres fisik.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

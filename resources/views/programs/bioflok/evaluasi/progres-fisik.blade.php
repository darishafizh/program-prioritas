@extends('layouts.app')

@section('title', 'Bioflok - Evaluasi Progres Fisik')

@section('content')
    <div x-data="{ batch: '' }">
        {{-- Header & Filters --}}
        <div class="mb-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h2 class="text-xl font-semibold tracking-tight">Evaluasi Progres Fisik Bioflok</h2>
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
            <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-warning/10 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                <div class="flex items-center gap-4 mb-3 relative z-10">
                    <div class="w-12 h-12 rounded-xl bg-warning/10 flex items-center justify-center text-warning text-sm shrink-0"><i class="fa-solid fa-person-digging"></i></div>
                    <div>
                        <h3 class="text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">Konstruksi Aktif</h3>
                        <div class="text-sm font-medium text-warning mt-0.5">{{ $stats['konstruksi_aktif'] ?? 17 }} <span class="text-sm font-medium text-textMuted-light">Lokasi</span></div>
                    </div>
                </div>
            </div>

            {{-- Rata-Rata Progres --}}
            <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-teal-light/10 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                <div class="flex items-center gap-4 mb-2 relative z-10">
                    <div class="w-12 h-12 rounded-xl bg-teal-light/10 flex items-center justify-center text-teal-light text-sm shrink-0"><i class="fa-solid fa-chart-pie"></i></div>
                    <div>
                        <h3 class="text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">Rata-Rata Progres</h3>
                        <div class="text-sm font-medium text-teal-light mt-0.5">{{ $stats['rata_progres'] ?? 78.4 }}%</div>
                    </div>
                </div>
                <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2 relative z-10 mt-2">
                    <div class="bg-teal-light h-2 rounded-full transition-all" style="width: {{ $stats['rata_progres'] ?? 78.4 }}%"></div>
                </div>
            </div>

            {{-- Total Selesai --}}
            <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-success/10 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                <div class="flex items-center gap-4 mb-3 relative z-10">
                    <div class="w-12 h-12 rounded-xl bg-success/10 flex items-center justify-center text-success text-sm shrink-0"><i class="fa-solid fa-check-double"></i></div>
                    <div>
                        <h3 class="text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">Total Selesai</h3>
                        <div class="text-sm font-medium text-success mt-0.5">{{ $stats['total_selesai'] ?? 28 }} <span class="text-sm font-medium text-textMuted-light">Lokasi</span></div>
                    </div>
                </div>
            </div>

            {{-- Kritis / Terlambat --}}
            <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-danger/10 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                <div class="flex items-center gap-4 mb-3 relative z-10">
                    <div class="w-12 h-12 rounded-xl bg-danger/10 flex items-center justify-center text-danger text-sm shrink-0"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    <div>
                        <h3 class="text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">Deviasi Kritis</h3>
                        <div class="text-sm font-medium text-danger mt-0.5">{{ $stats['kritis_terlambat'] ?? 4 }} <span class="text-sm font-medium text-textMuted-light">Lokasi</span></div>
                    </div>
                </div>
            </div>
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
                            <th class="py-4 px-6 text-center">Rencana</th>
                            <th class="py-4 px-6 text-center">Aktual</th>
                            <th class="py-4 px-6 text-center">Deviasi</th>
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
                            <td class="py-4 px-6 text-center font-semibold text-textMuted-light whitespace-nowrap">{{ $lok['progres_rencana'] }}%</td>
                            <td class="py-4 px-6 text-center font-bold text-textMain-light dark:text-white whitespace-nowrap">{{ $lok['progres_aktual'] }}%</td>
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                @if($lok['deviasi'] < 0)
                                    <span class="font-bold text-danger">{{ $lok['deviasi'] }}%</span>
                                @elseif($lok['deviasi'] > 0)
                                    <span class="font-bold text-success">+{{ $lok['deviasi'] }}%</span>
                                @else
                                    <span class="font-bold text-textMuted-light">0.0%</span>
                                @endif
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

@extends('layouts.app')

@section('title', 'Budidaya Tematik - Dashboard Progres Fisik')

@section('content')
    <div x-data="{ batch: '', tanggal: '' }">
        <!-- Header & Global Filters (Responsive Row) -->
        <div class="mb-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h2 class="text-xl font-semibold tracking-tight">Dashboard Progres Fisik Budidaya Tematik</h2>
                <p class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1">Ringkasan Eksekutif & Pantauan Konstruksi Kolam Sentra Kampung Perikanan Budidaya Tematik</p>
            </div>

            <!-- Filters -->
            <form action="{{ url()->current() }}" method="GET" class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                <div class="relative">
                    <select name="batch_id" onchange="this.form.submit()"
                        class="appearance-none bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-xl px-4 py-2 pr-10 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-teal-light focus:border-teal-light text-textMain-light dark:text-textMain-dark">
                        <option value="">Semua Tahap</option>
                        @foreach ($filter_batches ?? [] as $batchItem)
                            <option value="{{ $batchItem['id'] }}" {{ request('batch_id') == $batchItem['id'] ? 'selected' : '' }}>
                                {{ $batchItem['name'] }}
                            </option>
                        @endforeach
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                </div>

                <div class="relative flex items-center bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-xl px-4 py-2 text-xs font-medium focus-within:ring-2 focus-within:ring-teal-light focus-within:border-teal-light">
                    <i class="fa-regular fa-calendar text-gray-400 mr-2 shrink-0"></i>
                    <input type="date" name="date" value="{{ request('date') ?: now()->format('Y-m-d') }}" onchange="this.form.submit()"
                        class="bg-transparent border-none outline-none text-textMain-light dark:text-textMain-dark w-32">
                </div>

                <button type="button" onclick="window.print()" class="px-4 py-2 bg-danger/10 border border-danger/20 text-danger rounded-xl text-xs font-medium hover:bg-danger/20 transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-file-pdf"></i> PDF
                </button>
            </form>
        </div>

        <!-- Narrative Storytelling Block -->
        <div class="mb-6 relative overflow-hidden rounded-3xl bg-gradient-to-br from-bgSurface-light to-blue-50 dark:from-bgSurface-dark dark:to-blue-900/10 border border-teal-light/20 dark:border-teal-light/10 p-6 sm:p-8">
            <div class="absolute top-0 right-0 p-8 opacity-5 dark:opacity-10 pointer-events-none hidden sm:block">
                <i class="fa-solid fa-quote-right text-8xl sm:text-9xl text-teal-light"></i>
            </div>
            <div class="relative z-10">
                <div class="flex items-center gap-2 text-teal-light dark:text-teal-400 font-medium text-xs tracking-widest uppercase mb-3">
                    <span class="w-2 h-2 rounded-full bg-teal-light animate-pulse shrink-0"></span>Analisis Kinerja Konstruksi Bulan Ini
                </div>
                <p class="text-xs text-textMain-light dark:text-textMain-dark leading-relaxed font-medium">
                    {!! $stats['narasi'] ?? '' !!}
                </p>
            </div>
        </div>

        <!-- KPI Cards (Responsive Grid 1 -> 2 -> 4) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <x-stat-card
                title="Total Sentra / KDMP"
                icon="fa-solid fa-water"
                icon-color="text-teal-light dark:text-teal-400"
                icon-bg="bg-teal-light/10 dark:bg-teal-light/20"
                value="{{ $stats['total_lokasi'] ?? 0 }}"
                unit="Lokasi"
                description="<span class='text-success font-medium inline-flex items-center gap-1.5'><i class='fa-solid fa-arrow-trend-up shrink-0'></i> Tersebar di 12 Provinsi</span>"
            />

            <x-stat-card
                title="Rata-Rata Progres"
                icon="fa-solid fa-chart-pie"
                icon-color="text-teal-light dark:text-teal-400"
                icon-bg="bg-teal-light/10 dark:bg-teal-400/20"
                value="{{ $stats['rata_progres'] ?? 0 }}"
                unit="%"
            >
                <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2 mt-2">
                    <div class="bg-teal-light dark:bg-teal-400 h-2 rounded-full" style="width: {{ $stats['rata_progres'] ?? 0 }}%"></div>
                </div>
            </x-stat-card>

            <x-stat-card
                title="Siap Tebar / Panen"
                icon="fa-solid fa-check-double"
                icon-color="text-success dark:text-emerald-400"
                icon-bg="bg-success/10 dark:bg-success/20"
                value="{{ $stats['total_selesai'] ?? 0 }}"
                unit="Lokasi"
                description="<span class='text-success font-medium inline-flex items-center gap-1.5'><i class='fa-solid fa-arrow-trend-up shrink-0'></i> Konstruksi rampung 100%</span>"
            />

            <x-stat-card
                title="Dalam Konstruksi"
                icon="fa-solid fa-person-digging"
                icon-color="text-warning dark:text-amber-500"
                icon-bg="bg-warning/10 dark:bg-amber-400/20"
                value="{{ $stats['dalam_pembangunan'] ?? 0 }}"
                unit="Lokasi"
                description="<span class='text-warning font-medium inline-flex items-center gap-1.5'><i class='fa-solid fa-clock shrink-0'></i> Akselerasi fondasi & bak</span>"
            />
        </div>

        <!-- Analisis Kesenjangan & Faktor Penghambat -->
        <div class="mb-6">
            <h3 class="font-medium text-sm flex items-center gap-2 mb-4">
                <span class="w-8 h-8 rounded-lg bg-teal-light/20 text-teal-light flex items-center justify-center text-xs font-bold shrink-0">1</span>
                Analisis Kesenjangan & Faktor Penghambat Konstruksi
            </h3>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Regional Realization Comparison -->
                <div class="bg-bgSurface-light dark:bg-bgSurface-dark rounded-3xl border border-gray-100 dark:border-gray-800 lg:col-span-2 p-6 flex flex-col">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h4 class="font-medium text-sm">Komparasi: Target vs Realisasi Pembangunan Kolam per Wilayah</h4>
                            <p class="text-xs text-textMuted-light mt-1">Diukur berdasarkan kemajuan fisik bak beton, terpal bioflok, dan instalasi aerasi.</p>
                        </div>
                    </div>

                    <div class="flex-1 flex flex-col justify-center space-y-4 sm:space-y-5 py-2 sm:py-4">
                        @foreach($regionalData as $reg)
                        <div>
                            <div class="flex flex-col sm:flex-row sm:justify-between text-xs font-medium mb-1.5 gap-0.5 sm:gap-2">
                                <span class="truncate">{{ $reg['nama'] }} <span class="text-textMuted-light font-normal">({{ $reg['status'] }})</span></span>
                                <span class="{{ $reg['class'] }} font-semibold text-left sm:text-right">{{ $reg['realisasi'] }}% <span class="text-[11px] font-normal text-textMuted-light">(Target: {{ $reg['target'] }}%)</span></span>
                            </div>
                            <div class="relative w-full h-2.5 sm:h-3 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                                <div class="absolute left-0 top-0 h-full bg-gray-300 dark:bg-gray-600 rounded-full" style="width: {{ $reg['target'] }}%"></div>
                                <div class="absolute left-0 top-0 h-full bg-teal-light opacity-85 rounded-full" style="width: {{ $reg['realisasi'] }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-4 text-[11px] flex flex-wrap items-center justify-center gap-4 text-textMuted-light font-medium pt-2 border-t border-gray-100 dark:border-gray-800/60">
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-gray-300 dark:bg-gray-600 shrink-0"></span> Target Kurva S</span>
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-teal-light shrink-0"></span> Realisasi Fisik Lapangan</span>
                    </div>
                </div>

                <!-- Root Cause Analysis List -->
                <div class="bg-bgSurface-light dark:bg-bgSurface-dark rounded-3xl border border-gray-100 dark:border-gray-800 p-6 flex flex-col">
                    <h4 class="font-medium text-sm mb-1">Kendala Lapangan Terbanyak</h4>
                    <p class="text-xs text-textMuted-light mb-5">Distribusi hambatan dari log harian proyek Budidaya Tematik.</p>

                    <div class="space-y-4 flex-1">
                        @foreach($kendala as $ken)
                        <div class="flex gap-3 items-start">
                            <div class="w-8 h-8 rounded-full bg-{{ $ken['color'] }}/10 text-{{ $ken['color'] }} flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fa-solid {{ $ken['icon'] }} text-xs"></i>
                            </div>
                            <div>
                                <div class="text-xs font-semibold">{{ $ken['judul'] }}</div>
                                <div class="text-xs text-textMuted-light mt-0.5 leading-relaxed">{{ $ken['deskripsi'] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Plan Table (Responsive Scrollable) -->
        <div class="mb-6">
            <h3 class="font-medium text-sm flex items-center gap-2 mb-4">
                <span class="w-8 h-8 rounded-lg bg-teal-light text-white flex items-center justify-center text-xs font-bold shrink-0">2</span>
                Rencana Tindak Lanjut Pembangunan & Akselerasi
            </h3>

            <div class="bg-bgSurface-light dark:bg-bgSurface-dark rounded-3xl border border-gray-100 dark:border-gray-800 overflow-hidden">
                <div class="overflow-x-auto -mx-1">
                    <table class="w-full text-left text-xs min-w-[700px]">
                        <thead class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
                            <tr>
                                <th class="px-6 py-4">Prioritas Aksi</th>
                                <th class="px-6 py-4">Target Wilayah</th>
                                <th class="px-6 py-4">PIC / Tanggung Jawab</th>
                                <th class="px-6 py-4">Tenggat Waktu</th>
                                <th class="px-6 py-4 text-right">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach($actionPlans as $plan)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-semibold {{ $plan['class'] }} flex items-center gap-1.5">
                                        <i class="fa-solid fa-circle-exclamation text-[11px] shrink-0"></i> {{ $plan['prioritas'] }}
                                    </div>
                                    <div class="text-xs text-textMuted-light mt-1 max-w-sm leading-relaxed">{{ $plan['deskripsi'] }}</div>
                                </td>
                                <td class="px-6 py-4 font-medium text-textMain-light dark:text-white">{{ $plan['wilayah'] }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-teal-light text-white flex items-center justify-center text-[0.6rem] font-bold shrink-0">{{ $plan['pic_singkatan'] }}</div>
                                        <span class="font-medium text-textMain-light dark:text-white">{{ $plan['pic'] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-semibold {{ $plan['class'] }} whitespace-nowrap">{{ $plan['tenggat'] }}</td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <button class="px-4 py-2 rounded-xl bg-gray-100 dark:bg-gray-800 font-medium text-xs hover:bg-teal-light hover:text-white transition-colors">Tindak Lanjut</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

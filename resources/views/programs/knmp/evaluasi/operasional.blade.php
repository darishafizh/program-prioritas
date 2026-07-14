@extends('layouts.app')

@section('title', 'KNMP - Evaluasi Operasional Proyek')

@section('content')
    <div x-data="evaluasiOperasionalManager()" class="min-w-0 overflow-hidden">

        {{-- Header --}}
        <div class="mb-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h2 class="text-base font-medium tracking-tight text-textMain-light dark:text-textMain-dark">Evaluasi Operasional Proyek</h2>
                <p class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1">Evaluasi historis
                    perjalanan proyek KNMP — dari usulan hingga serah terima.</p>
            </div>

            {{-- Filter Bar --}}
            <form id="evaluasiFilterForm" action="{{ url()->current() }}" method="GET"
                class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                <div class="relative">
                    <select name="batch_id" onchange="this.form.submit()"
                        class="appearance-none bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-xl px-4 py-2 pr-10 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-teal-light text-textMain-light dark:text-textMain-dark">
                        <option value="">Semua Tahap</option>
                        @foreach ($filter_batches as $batch)
                            <option value="{{ $batch['id'] }}" {{ request('batch_id') == $batch['id'] ? 'selected' : '' }}>
                                {{ $batch['name'] }}</option>
                        @endforeach
                    </select>
                    <i
                        class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                </div>

                <div
                    class="relative flex items-center bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-xl px-4 py-2 text-xs font-medium focus-within:ring-2 focus-within:ring-teal-light">
                    <i class="fa-regular fa-calendar text-gray-400 mr-2"></i>
                    <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()"
                        class="bg-transparent border-none outline-none text-textMain-light dark:text-textMain-dark w-32">
                </div>

                @can('generate-pdf')
                <button type="button" @click="isPdfModalOpen = true"
                    class="px-4 py-2 bg-danger/10 border border-danger/20 text-danger rounded-md text-xs font-medium hover:bg-danger/20 transition-colors flex items-center gap-2 cursor-pointer shadow-sm">
                    <i class="fa-solid fa-file-pdf"></i> Generate PDF
                </button>
                @endcan
            </form>
        </div>

        {{-- KPI Cards - 6 tahap --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
            @php
                $tahapConfig = [
                    'usulan' => ['label' => 'Usulan', 'icon' => 'fa-file-circle-plus', 'color' => 'info'],
                    'survey' => ['label' => 'Survei', 'icon' => 'fa-binoculars', 'color' => 'teal-light'],
                    'ded' => ['label' => 'DED', 'icon' => 'fa-drafting-compass', 'color' => 'violet-500'],
                    'lelang' => ['label' => 'Lelang', 'icon' => 'fa-gavel', 'color' => 'warning'],
                    'konstruksi' => ['label' => 'Konstruksi', 'icon' => 'fa-helmet-safety', 'color' => 'orange-500'],
                    'serah_terima' => ['label' => 'Serah Terima', 'icon' => 'fa-handshake', 'color' => 'success'],
                ];
            @endphp
            @foreach ($tahapConfig as $key => $config)
                <x-stat-card
                    title="{{ $config['label'] }}"
                    icon="fa-solid {{ $config['icon'] }}"
                    icon-color="text-{{ $config['color'] }}"
                    icon-bg="bg-{{ $config['color'] }}/10"
                    value="{{ $stats['per_tahap'][$key] ?? 0 }}"
                />
            @endforeach
        </div>

        {{-- Insight Banner --}}
        <div class="mb-6 relative overflow-hidden rounded-2xl bg-gradient-to-br from-bgSurface-light to-blue-50 dark:from-bgSurface-dark dark:to-blue-900/10 border border-teal-light/20 dark:border-teal-light/10 p-5 sm:p-6">
            <div class="absolute top-0 right-0 p-6 opacity-5 dark:opacity-10 pointer-events-none">
                <i class="fa-solid fa-magnifying-glass-chart text-7xl text-teal-light"></i>
            </div>
            <div class="relative z-10">
                <div class="flex items-center gap-2 text-teal-light dark:text-teal-400 font-medium text-[11px] tracking-widest uppercase mb-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-teal-light animate-pulse"></span> Insight Evaluasi
                </div>
                <p class="text-xs text-textMain-light dark:text-textMain-dark leading-relaxed font-medium">
                    {!! $stats['insight_text'] !!}
                </p>
            </div>
        </div>

        {{-- Chart Row: Distribusi Tahap + Durasi per Tahap --}}
        <div class="grid grid-cols-2 gap-6 mb-6">
            {{-- Bar Chart: Distribusi Lokasi per Tahap --}}
            <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-2xl p-6 flex flex-col min-w-0">
                <h3 class="font-medium text-xs uppercase tracking-wider text-textMuted-light dark:text-textMuted-dark flex items-center gap-2 mb-4 whitespace-nowrap overflow-hidden text-ellipsis">
                    <i class="fa-solid fa-chart-bar text-teal-light flex-shrink-0"></i> <span class="truncate">Distribusi Lokasi per Tahap</span>
                </h3>
                <div class="flex-1 min-h-[260px] min-w-0">
                    <div id="chart-distribusi-tahap" class="w-full h-full min-w-0"></div>
                </div>
            </div>

            {{-- Horizontal Bar Chart: Rata-rata Durasi per Tahap --}}
            <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-2xl p-6 flex flex-col min-w-0">
                <h3 class="font-medium text-xs uppercase tracking-wider text-textMuted-light dark:text-textMuted-dark flex items-center gap-2 mb-4 whitespace-nowrap overflow-hidden text-ellipsis">
                    <i class="fa-solid fa-clock text-orange-500 flex-shrink-0"></i> <span class="truncate">Rata-rata Durasi per Tahap (Hari)</span>
                </h3>
                <div class="flex-1 min-h-[260px] min-w-0">
                    <div id="chart-durasi-tahap" class="w-full h-full min-w-0"></div>
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div
            class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl overflow-hidden flex flex-col">
            <div
                class="p-6 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="font-medium text-sm flex items-center gap-2">
                        <i class="fa-solid fa-table-list text-teal-light"></i> Riwayat Tahapan Operasional KNMP
                    </h3>
                    <p class="text-xs text-textMuted-light mt-1">Tracking perjalanan setiap lokasi KNMP melalui siklus
                        operasional proyek.</p>
                </div>
            </div>

            {{-- Toolbar --}}
            <div
                class="px-6 py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gray-50/50 dark:bg-gray-800/20">
                <div class="flex items-center gap-2 text-xs text-textMuted-light dark:text-textMuted-dark">
                    <span>Tampilkan</span>
                    <select x-model="perPage" @change="currentPage = 1"
                        class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-md px-2 py-1.5 text-xs focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark font-medium">
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="all">Semua</option>
                    </select>
                    <span>entri</span>
                </div>
                <div class="relative w-full sm:w-64">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Cari nama lokasi..."
                        class="w-full pl-8 pr-4 py-2 rounded-md border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-xs focus:border-teal-light outline-none transition-all">
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs whitespace-nowrap">
                    <x-table.thead>
                        <x-table.th>KNMP & Tipe</x-table.th>
                        <x-table.th>Wilayah & Tahap</x-table.th>
                        <x-table.th class="min-w-[400px]">Tracking Operasional</x-table.th>
                    </x-table.thead>
                    <x-table.tbody>
                        <template x-for="(item, index) in paginatedData()" :key="index">
                            <x-table.tr>
                                <x-table.td>
                                    <div class="font-medium text-textMain-light dark:text-textMain-dark whitespace-normal min-w-[120px]" x-text="item.nama"></div>
                                    <span class="inline-block mt-1 px-2 py-0.5 rounded-md text-[0.65rem] font-medium"
                                        :class="item.status === 'Hub' ? 'bg-teal-light/10 text-teal-light' : 'bg-warning/10 text-warning'"
                                        x-text="item.status"></span>
                                </x-table.td>
                                <x-table.td>
                                    <div class="text-textMuted-light whitespace-normal min-w-[100px]" x-text="item.kabupaten"></div>
                                    <div class="text-[10px] text-gray-400 mb-1" x-text="item.provinsi"></div>
                                    <span class="inline-block px-2.5 py-1 rounded-md text-[0.7rem] font-medium"
                                        :class="tahapColors[item.tahap_saat_ini] || 'bg-gray-100 text-gray-500'"
                                        x-text="tahapLabels[item.tahap_saat_ini] || item.tahap_saat_ini"></span>
                                </x-table.td>
                                <x-table.td>
                                    {{-- Visual Stepper --}}
                                    <div class="flex items-center gap-0">
                                        <template x-for="(stage, sIdx) in item.stages" :key="stage.key">
                                            <div class="flex items-center">
                                                <div class="flex flex-col items-center">
                                                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-[9px] font-bold transition-all border-2"
                                                        :class="{
                                                            'bg-success text-white border-success': stage
                                                                .status === 'completed',
                                                            'bg-teal-light text-white border-teal-light animate-pulse': stage
                                                                .status === 'active',
                                                            'bg-gray-100 dark:bg-gray-800 text-gray-400 border-gray-200 dark:border-gray-700': stage
                                                                .status === 'pending',
                                                        }">
                                                        <i class="fa-solid"
                                                            :class="{
                                                                'fa-check': stage.status === 'completed',
                                                                'fa-circle text-[6px]': stage.status === 'active',
                                                                'fa-circle text-[4px]': stage.status === 'pending',
                                                            }"></i>
                                                    </div>
                                                    <span class="text-[8px] mt-1 max-w-[50px] text-center leading-tight"
                                                        :class="stage.status === 'active' ? 'text-teal-light font-bold' :
                                                            'text-gray-400'"
                                                        x-text="stepperLabels[stage.key]"></span>
                                                </div>
                                                <template x-if="sIdx < item.stages.length - 1">
                                                    <div class="w-6 h-0.5 mb-3 mx-0.5"
                                                        :class="stage.status === 'completed' ? 'bg-success' :
                                                            'bg-gray-200 dark:bg-gray-700'">
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </x-table.td>
                            </x-table.tr>
                        </template>
                        <x-table.tr x-show="paginatedData().length === 0">
                            <x-table.td colspan="3" class="px-6 py-8 text-center text-textMuted-light">Belum ada data atau tidak ada hasil pencarian.</x-table.td>
                        </x-table.tr>
                    </x-table.tbody>
                </table>
            </div>

            {{-- Footer Pagination --}}
            <div
                class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50 dark:bg-gray-800/20">
                <div class="text-xs text-textMuted-light dark:text-textMuted-dark">
                    Menampilkan <span class="font-medium text-textMain-light dark:text-textMain-dark"
                        x-text="paginatedData().length"></span> dari <span
                        class="font-medium text-textMain-light dark:text-textMain-dark"
                        x-text="filteredData().length"></span> data
                </div>
                <div class="flex gap-1" x-show="totalPages() > 1">
                    <button @click="currentPage = Math.max(1, currentPage - 1)" :disabled="currentPage === 1"
                        class="w-8 h-8 rounded-md border border-gray-100 dark:border-gray-700 flex items-center justify-center text-gray-400 disabled:opacity-30 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"><i
                            class="fa-solid fa-chevron-left text-[10px]"></i></button>
                    <template x-for="page in visiblePages()" :key="page">
                        <button @click="if(page !== '...') currentPage = page"
                            class="w-8 h-8 rounded-md font-medium text-xs flex items-center justify-center transition-colors"
                            :class="page === currentPage ? 'bg-teal-light text-white' : (page === '...' ?
                                'cursor-default text-gray-400' :
                                'hover:bg-gray-100 dark:hover:bg-gray-800 text-textMain-light dark:text-textMain-dark'
                                )"
                            x-text="page"></button>
                    </template>
                    <button @click="currentPage = Math.min(totalPages(), currentPage + 1)"
                        :disabled="currentPage === totalPages()"
                        class="w-8 h-8 rounded-md border border-gray-100 dark:border-gray-700 flex items-center justify-center text-gray-400 disabled:opacity-30 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"><i
                            class="fa-solid fa-chevron-right text-[10px]"></i></button>
                </div>
            </div>
        </div>

        {{-- PDF Modal --}}
        <div x-show="isPdfModalOpen" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div @click.away="isPdfModalOpen = false" x-transition.opacity.duration.200ms
                class="bg-white dark:bg-gray-900 rounded-2xl w-full max-w-md p-6 shadow-xl border border-gray-100 dark:border-gray-800 relative">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-lg font-bold text-textMain-light dark:text-textMain-dark">Export Laporan PDF</h3>
                    <button @click="isPdfModalOpen = false" class="text-gray-400 hover:text-danger transition-colors"><i
                            class="fa-solid fa-xmark text-lg"></i></button>
                </div>
                <form action="{{ route('program.evaluasi.operasional.pdf', ['program' => 'knmp']) }}" method="GET"
                    target="_blank" @submit="setTimeout(() => isPdfModalOpen = false, 500)">
                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Pilih
                                Tahap (Batch)</label>
                            <select name="batch_id"
                                class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark">
                                <option value="">Semua Tahap</option>
                                @foreach ($filter_batches as $batch)
                                    <option value="{{ $batch['id'] }}">{{ $batch['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Pilih
                                Tanggal</label>
                            <input type="date" name="date" value="{{ date('Y-m-d') }}"
                                class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark">
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end gap-3">
                        <button type="button" @click="isPdfModalOpen = false"
                            class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors cursor-pointer flex items-center gap-2"><i class="fa-solid fa-xmark"></i> <span>Batal</span></button>
                        <button type="submit"
                            class="px-4 py-2 bg-danger text-white rounded-lg text-sm font-medium hover:bg-red-600 transition-colors flex items-center gap-2 cursor-pointer shadow-sm"><i
                                class="fa-solid fa-download"></i> <span>Generate PDF</span></button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('evaluasiOperasionalManager', () => ({
                searchQuery: '',
                perPage: '25',
                currentPage: 1,
                isPdfModalOpen: false,
                tableData: @json($operasionalData),

                tahapLabels: {
                    'usulan': 'Usulan',
                    'survey': 'Survei',
                    'ded': 'DED',
                    'lelang': 'Lelang',
                    'konstruksi': 'Konstruksi',
                    'serah_terima': 'Serah Terima',
                },

                tahapColors: {
                    'usulan': 'bg-info/10 text-info',
                    'survey': 'bg-teal-light/10 text-teal-light',
                    'ded': 'bg-violet-500/10 text-violet-500',
                    'lelang': 'bg-warning/10 text-warning',
                    'konstruksi': 'bg-orange-500/10 text-orange-500',
                    'serah_terima': 'bg-success/10 text-success',
                },

                stepperLabels: {
                    'usulan': 'Usulan',
                    'survey': 'Survei',
                    'ded': 'DED',
                    'lelang': 'Lelang',
                    'konstruksi': 'Konstruksi',
                    'serah_terima': 'Serah Terima',
                },

                filteredData() {
                    const q = this.searchQuery.toLowerCase().trim();
                    if (!q) return this.tableData;
                    return this.tableData.filter(item =>
                        (item.nama || '').toLowerCase().includes(q) ||
                        (item.kabupaten || '').toLowerCase().includes(q) ||
                        (item.provinsi || '').toLowerCase().includes(q) ||
                        (item.status || '').toLowerCase().includes(q) ||
                        (item.tahap_saat_ini || '').toLowerCase().includes(q)
                    );
                },

                paginatedData() {
                    const data = this.filteredData();
                    if (this.perPage === 'all') return data;
                    const pp = parseInt(this.perPage);
                    const start = (this.currentPage - 1) * pp;
                    return data.slice(start, start + pp);
                },

                totalPages() {
                    if (this.perPage === 'all') return 1;
                    return Math.max(1, Math.ceil(this.filteredData().length / parseInt(this.perPage)));
                },

                visiblePages() {
                    const total = this.totalPages();
                    if (total <= 7) return Array.from({
                        length: total
                    }, (_, i) => i + 1);
                    const pages = [];
                    const cur = this.currentPage;
                    pages.push(1);
                    if (cur > 3) pages.push('...');
                    for (let i = Math.max(2, cur - 1); i <= Math.min(total - 1, cur + 1); i++) {
                        pages.push(i);
                    }
                    if (cur < total - 2) pages.push('...');
                    pages.push(total);
                    return pages;
                }
            }));
        });
    </script>

    {{-- ApexCharts for Evaluasi Operasional --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#94a3b8' : '#64748b';

            const stageLabels = @json($stats['stage_labels']);
            const perTahap = @json($stats['per_tahap']);
            const avgDuration = @json($stats['avg_duration_days']);
            const bottleneck = @json($stats['bottleneck_stage']);

            const stageKeys = Object.keys(stageLabels);
            const stageNames = stageKeys.map(k => stageLabels[k]);
            const stageColors = ['#3b82f6', '#14b8a6', '#8b5cf6', '#f59e0b', '#f97316', '#10b981'];

            // 1. Bar Chart — Distribusi Lokasi per Tahap
            const distEl = document.querySelector('#chart-distribusi-tahap');
            if (distEl) {
                new ApexCharts(distEl, {
                    chart: {
                        type: 'bar',
                        height: 260,
                        background: 'transparent',
                        fontFamily: 'Inter, sans-serif',
                        toolbar: { show: false },
                    },
                    series: [{ name: 'Jumlah Lokasi', data: stageKeys.map(k => perTahap[k] || 0) }],
                    xaxis: {
                        categories: stageNames,
                        labels: { style: { fontSize: '10px', colors: textColor } },
                        axisBorder: { show: false },
                        axisTicks: { show: false },
                    },
                    yaxis: {
                        labels: {
                            style: { fontSize: '10px', colors: textColor },
                            formatter: v => Math.round(v),
                        }
                    },
                    colors: stageColors,
                    plotOptions: {
                        bar: {
                            columnWidth: '50%',
                            borderRadius: 6,
                            borderRadiusApplication: 'end',
                            distributed: true,
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        style: { fontSize: '11px', fontWeight: 700 },
                        offsetY: -18,
                        formatter: v => v > 0 ? v : '',
                    },
                    grid: {
                        borderColor: isDark ? '#1e293b' : '#f1f5f9',
                        strokeDashArray: 4,
                    },
                    legend: { show: false },
                    tooltip: { theme: isDark ? 'dark' : 'light' },
                }).render();
            }

            // 2. Horizontal Bar Chart — Rata-rata Durasi per Tahap
            const durEl = document.querySelector('#chart-durasi-tahap');
            const durKeys = Object.keys(avgDuration);
            if (durEl && durKeys.length > 0) {
                const durColors = durKeys.map(k => k === bottleneck ? '#ef4444' : '#14b8a6');
                new ApexCharts(durEl, {
                    chart: {
                        type: 'bar',
                        height: 260,
                        background: 'transparent',
                        fontFamily: 'Inter, sans-serif',
                        toolbar: { show: false },
                    },
                    series: [{ name: 'Rata-rata (Hari)', data: durKeys.map(k => avgDuration[k]) }],
                    xaxis: {
                        categories: durKeys.map(k => stageLabels[k] || k),
                        labels: { style: { fontSize: '10px', colors: textColor } },
                        axisBorder: { show: false },
                        axisTicks: { show: false },
                    },
                    yaxis: {
                        labels: {
                            style: { fontSize: '10px', colors: textColor },
                            formatter: v => v + ' hari',
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            barHeight: '55%',
                            borderRadius: 4,
                            borderRadiusApplication: 'end',
                            distributed: true,
                        }
                    },
                    colors: durColors,
                    dataLabels: {
                        enabled: true,
                        style: { fontSize: '10px', fontWeight: 600 },
                        formatter: v => v > 0 ? v + ' hari' : '—',
                    },
                    grid: {
                        borderColor: isDark ? '#1e293b' : '#f1f5f9',
                        strokeDashArray: 4,
                    },
                    legend: { show: false },
                    tooltip: {
                        theme: isDark ? 'dark' : 'light',
                        y: { formatter: v => v + ' hari' },
                    },
                }).render();
            } else if (durEl) {
                durEl.innerHTML = '<div class="flex items-center justify-center h-full text-textMuted-light dark:text-textMuted-dark text-xs">Belum ada data durasi</div>';
            }
        });
    </script>
@endsection


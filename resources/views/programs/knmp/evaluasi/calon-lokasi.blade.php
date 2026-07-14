@extends('layouts.app')

@section('title', 'KNMP - Evaluasi Calon Lokasi')

@section('content')
    <div x-data="evaluasiCalonLokasiManager()" class="min-w-0 overflow-hidden">

        {{-- Header --}}
        <div class="mb-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h2 class="text-base font-medium tracking-tight text-textMain-light dark:text-textMain-dark">Evaluasi Calon Lokasi</h2>
                <p class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1">Evaluasi historis
                    pipeline calon lokasi KNMP — dari pengajuan hingga penetapan.</p>
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

        {{-- KPI Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            {{-- Total Calon Lokasi --}}
            <x-stat-card
                title="Total Calon Lokasi"
                icon="fa-solid fa-map-location-dot"
                icon-color="text-teal-light dark:text-teal-400"
                icon-bg="bg-teal-light/10 dark:bg-teal-light/20"
                value="{{ $stats['total'] }}"
                unit="Lokasi"
            />

            {{-- Tahap Pengajuan --}}
            <x-stat-card
                title="Tahap Pengajuan"
                icon="fa-solid fa-file-circle-plus"
                icon-color="text-blue-500 dark:text-blue-400"
                icon-bg="bg-blue-500/10 dark:bg-blue-500/20"
                value="{{ $stats['pengajuan'] }}"
                unit="Lokasi"
            />

            {{-- Dalam Verifikasi --}}
            <x-stat-card
                title="Dalam Verifikasi"
                icon="fa-solid fa-magnifying-glass-chart"
                icon-color="text-warning dark:text-amber-500"
                icon-bg="bg-warning/10 dark:bg-amber-400/20"
                value="{{ $stats['verifikasi'] }}"
                unit="Lokasi"
            />

            {{-- Sudah Ditetapkan --}}
            <x-stat-card
                title="Sudah Ditetapkan"
                icon="fa-solid fa-circle-check"
                icon-color="text-success dark:text-emerald-400"
                icon-bg="bg-success/10 dark:bg-success/20"
                value="{{ $stats['ditetapkan'] }}"
                unit="Lokasi"
            />
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

        {{-- Chart Row: Funnel Pipeline + Distribusi Status --}}
        <div class="grid grid-cols-2 gap-6 mb-6">
            {{-- Bar Chart: Funnel Pipeline --}}
            <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-2xl p-6 flex flex-col min-w-0">
                <h3 class="font-medium text-xs uppercase tracking-wider text-textMuted-light dark:text-textMuted-dark flex items-center gap-2 mb-4 whitespace-nowrap overflow-hidden text-ellipsis">
                    <i class="fa-solid fa-filter text-blue-500 flex-shrink-0"></i> <span class="truncate">Funnel Pipeline Pengajuan</span>
                </h3>
                <div class="flex-1 min-h-[260px] min-w-0">
                    <div id="chart-funnel-pipeline" class="w-full h-full min-w-0"></div>
                </div>
            </div>

            {{-- Pie Chart: Distribusi Status Akhir --}}
            <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-2xl p-6 flex flex-col min-w-0">
                <h3 class="font-medium text-xs uppercase tracking-wider text-textMuted-light dark:text-textMuted-dark flex items-center gap-2 mb-4 whitespace-nowrap overflow-hidden text-ellipsis">
                    <i class="fa-solid fa-chart-pie text-teal-light flex-shrink-0"></i> <span class="truncate">Distribusi Status Akhir</span>
                </h3>
                <div class="flex-1 min-h-[260px] min-w-0">
                    <div id="chart-distribusi-status" class="w-full h-full min-w-0"></div>
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div
            class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-2xl overflow-hidden flex flex-col">
            {{-- Table Header --}}
            <div
                class="p-6 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-base font-medium tracking-tight text-textMain-light dark:text-textMain-dark">Riwayat Pipeline Calon Lokasi</h2>
                    <p class="text-xs text-textMuted-light mt-1">Tracking status setiap calon lokasi melalui tahapan
                        evaluasi.</p>
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
                        <x-table.th>Lokasi & ID</x-table.th>
                        <x-table.th>Status & Tanggal</x-table.th>
                        <x-table.th class="min-w-[400px]">Tracking Pipeline</x-table.th>
                    </x-table.thead>
                    <x-table.tbody>
                        <template x-for="(item, index) in paginatedData()" :key="index">
                            <x-table.tr>
                                <x-table.td>
                                    <div class="font-medium text-textMain-light dark:text-textMain-dark whitespace-normal min-w-[150px]" x-text="item.desa"></div>
                                    <div class="text-[10px] text-gray-400 mt-0.5 whitespace-normal min-w-[150px]" x-text="`${item.kecamatan}, ${item.kabupaten}, ${item.provinsi}`"></div>
                                    <div class="mt-1">
                                        <span class="font-medium text-[10px] text-teal-light bg-teal-light/10 px-1.5 py-0.5 rounded cursor-pointer hover:underline" x-text="'ID: ' + item.idUser"></span>
                                    </div>
                                </x-table.td>
                                <x-table.td>
                                    <span class="inline-block px-2.5 py-1 rounded-md text-[0.7rem] font-medium mb-1"
                                        :class="{
                                            'bg-teal-light/10 text-teal-light': ['verif_admin', 'ba_aktivasi', 'verif_teknis', 'ba_calon'].includes(item.status_tahapan),
                                            'bg-success/10 text-success': item.status_tahapan === 'penetapan',
                                            'bg-danger/10 text-danger': item.status_tahapan === 'ditolak',
                                        }"
                                        :style="item.status_tahapan === 'pengajuan' ? 'background-color: rgba(59, 130, 246, 0.1); color: #3b82f6;' : ''"
                                        x-text="stageLabels[item.status_tahapan] || item.status_tahapan"></span>
                                    <div class="text-[10px] text-textMuted-light mt-1"><i class="fa-regular fa-clock mr-1"></i><span x-text="item.updated_at"></span></div>
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
                                                            'bg-danger/20 text-danger border-danger/40': stage
                                                                .status === 'rejected',
                                                        }">
                                                        <i class="fa-solid"
                                                            :class="{
                                                                'fa-check': stage.status === 'completed',
                                                                'fa-circle text-[6px]': stage.status === 'active',
                                                                'fa-circle text-[4px]': stage.status === 'pending',
                                                                'fa-xmark': stage.status === 'rejected',
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
                        <tr x-show="paginatedData().length === 0">
                            <x-table.td colspan="3" align="center" class="py-8 text-textMuted-light">Belum ada data calon
                                lokasi atau tidak ada hasil pencarian.</x-table.td>
                        </tr>
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
                <form action="{{ route('program.evaluasi.calon-lokasi.pdf', ['program' => 'knmp']) }}" method="GET"
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
                            class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors cursor-pointer shadow-sm">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-danger text-white rounded-lg text-sm font-medium hover:bg-red-600 transition-colors flex items-center gap-2 cursor-pointer shadow-sm"><i
                                class="fa-solid fa-download"></i> Generate PDF</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('evaluasiCalonLokasiManager', () => ({
                searchQuery: '',
                perPage: '25',
                currentPage: 1,
                isPdfModalOpen: false,
                tableData: @json($calonLokasiData),

                stageLabels: {
                    'pengajuan': 'Pengajuan',
                    'verif_admin': 'Verif Admin',
                    'ba_aktivasi': 'BA Aktivasi',
                    'verif_teknis': 'Verif Teknis',
                    'ba_calon': 'BA Calon',
                    'penetapan': 'Penetapan',
                    'ditolak': 'Ditolak',
                },

                stepperLabels: {
                    'pengajuan': 'Pengajuan',
                    'verif_admin': 'Verif Admin',
                    'ba_aktivasi': 'BA Aktivasi',
                    'verif_teknis': 'Verif Teknis',
                    'ba_calon': 'BA Calon',
                    'penetapan': 'Penetapan',
                },

                filteredData() {
                    const q = this.searchQuery.toLowerCase().trim();
                    if (!q) return this.tableData;
                    return this.tableData.filter(item =>
                        (item.desa || '').toLowerCase().includes(q) ||
                        (item.kecamatan || '').toLowerCase().includes(q) ||
                        (item.kabupaten || '').toLowerCase().includes(q) ||
                        (item.provinsi || '').toLowerCase().includes(q) ||
                        (item.idUser || '').toLowerCase().includes(q) ||
                        (item.status_tahapan || '').toLowerCase().includes(q)
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

    {{-- ApexCharts for Evaluasi Calon Lokasi --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#94a3b8' : '#64748b';
            const bgColor = isDark ? '#0f172a' : '#ffffff';

            const funnelData = @json($stats['funnel']);
            const stageLabels = @json($stats['stage_labels']);
            const conversionRate = @json($stats['conversion_rate']);

            const stageKeys = Object.keys(stageLabels);
            const stageNames = stageKeys.map(k => stageLabels[k]);
            const funnelValues = stageKeys.map(k => funnelData[k] || 0);

            // Gradient colors for funnel (darker as it progresses)
            const funnelColors = ['#3b82f6', '#6366f1', '#8b5cf6', '#a855f7', '#14b8a6', '#10b981'];

            // 1. Bar Chart — Funnel Pipeline
            const funnelEl = document.querySelector('#chart-funnel-pipeline');
            if (funnelEl) {
                new ApexCharts(funnelEl, {
                    chart: {
                        type: 'bar',
                        height: 260,
                        background: 'transparent',
                        fontFamily: 'Inter, sans-serif',
                        toolbar: { show: false },
                    },
                    series: [{ name: 'Lokasi', data: funnelValues }],
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
                    colors: funnelColors,
                    plotOptions: {
                        bar: {
                            columnWidth: '55%',
                            borderRadius: 6,
                            borderRadiusApplication: 'end',
                            distributed: true,
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        style: { fontSize: '10px', fontWeight: 700 },
                        offsetY: -18,
                        formatter: function (val, opts) {
                            const key = stageKeys[opts.dataPointIndex];
                            const rate = conversionRate[key];
                            return val + (opts.dataPointIndex > 0 ? ' (' + rate + '%)' : '');
                        },
                    },
                    grid: {
                        borderColor: isDark ? '#1e293b' : '#f1f5f9',
                        strokeDashArray: 4,
                    },
                    legend: { show: false },
                    tooltip: {
                        theme: isDark ? 'dark' : 'light',
                        custom: function ({ series, seriesIndex, dataPointIndex }) {
                            const key = stageKeys[dataPointIndex];
                            const rate = conversionRate[key];
                            return '<div class="px-3 py-2 text-xs">' +
                                '<strong>' + stageNames[dataPointIndex] + '</strong><br>' +
                                'Lokasi: ' + series[seriesIndex][dataPointIndex] + '<br>' +
                                'Conversion: ' + rate + '%' +
                                '</div>';
                        }
                    },
                }).render();
            }

            // 2. Pie Chart — Distribusi Status Akhir
            const statsData = @json($stats);
            const pieEl = document.querySelector('#chart-distribusi-status');
            if (pieEl) {
                const pieValues = [
                    statsData.pengajuan || 0,
                    statsData.verifikasi || 0,
                    statsData.ditetapkan || 0,
                    statsData.ditolak || 0,
                ];
                const pieLabels = ['Tahap Pengajuan', 'Dalam Verifikasi', 'Ditetapkan', 'Ditolak'];
                const pieColors = ['#3b82f6', '#f59e0b', '#10b981', '#ef4444'];

                new ApexCharts(pieEl, {
                    chart: {
                        type: 'donut',
                        height: 260,
                        background: 'transparent',
                        fontFamily: 'Inter, sans-serif',
                    },
                    series: pieValues,
                    labels: pieLabels,
                    colors: pieColors,
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '70%',
                                labels: {
                                    show: true,
                                    name: { fontSize: '12px', color: textColor },
                                    value: { fontSize: '20px', fontWeight: 700, color: isDark ? '#e2e8f0' : '#1e293b' },
                                    total: {
                                        show: true,
                                        label: 'Total',
                                        fontSize: '11px',
                                        color: textColor,
                                        formatter: function (w) {
                                            return w.globals.seriesTotals.reduce((a, b) => a + b, 0) + ' Lokasi';
                                        }
                                    }
                                }
                            }
                        }
                    },
                    dataLabels: { enabled: false },
                    legend: {
                        position: 'bottom',
                        fontSize: '11px',
                        labels: { colors: textColor },
                        markers: { size: 6, offsetX: -3 },
                    },
                    stroke: { width: 2, colors: [bgColor] },
                    tooltip: { theme: isDark ? 'dark' : 'light' },
                }).render();
            }
        });
    </script>
@endsection


@extends('layouts.app')

@section('title', 'Budidaya Tematik - Dashboard Produksi')

@section('content')
    <div x-data="{ bulan: '{{ $bulan }}' }">
        <!-- Header & Global Filters Sejajar -->
        <div class="mb-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h2 class="text-xl font-semibold tracking-tight">Dashboard Produksi Budidaya Tematik</h2>
                <p class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1">Monitoring capaian
                    produksi sistem budidaya tematik per Kelompok Budidaya (KDKMP).</p>
            </div>

            <!-- Filter Sejajar -->
            <form action="{{ url()->current() }}" method="GET" class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                <div class="relative">
                    <select name="bulan" onchange="this.form.submit()"
                        class="appearance-none bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-xl px-4 py-2 pr-10 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-teal-light focus:border-teal-light text-textMain-light dark:text-textMain-dark">
                        <option value="">Semua Bulan</option>
                        <option value="1" {{ request('bulan') == '1' ? 'selected' : '' }}>Januari</option>
                        <option value="2" {{ request('bulan') == '2' ? 'selected' : '' }}>Februari</option>
                        <option value="3" {{ request('bulan') == '3' ? 'selected' : '' }}>Maret</option>
                        <option value="4" {{ request('bulan') == '4' ? 'selected' : '' }}>April</option>
                        <option value="5" {{ request('bulan') == '5' ? 'selected' : '' }}>Mei</option>
                        <option value="6" {{ request('bulan') == '6' ? 'selected' : '' }}>Juni</option>
                        <option value="7" {{ request('bulan') == '7' ? 'selected' : '' }}>Juli</option>
                        <option value="8" {{ request('bulan') == '8' ? 'selected' : '' }}>Agustus</option>
                        <option value="9" {{ request('bulan') == '9' ? 'selected' : '' }}>September</option>
                        <option value="10" {{ request('bulan') == '10' ? 'selected' : '' }}>Oktober</option>
                        <option value="11" {{ request('bulan') == '11' ? 'selected' : '' }}>November</option>
                        <option value="12" {{ request('bulan') == '12' ? 'selected' : '' }}>Desember</option>
                    </select>
                    <i
                        class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                </div>

                <a href="{{ route('program.budidaya-tematik.dashboard.produksi.export-pdf', ['bulan' => request('bulan')]) }}" target="_blank"
                    class="px-4 py-2 bg-danger/10 border border-danger/20 text-danger rounded-xl text-xs font-medium hover:bg-danger/20 transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-file-pdf"></i> PDF
                </a>
            </form>
        </div>

        <!-- 3 KPI Cards: Total KDMP, Sudah Panen, Belum Panen -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <!-- Card 1: Total KDMP -->
            <x-stat-card title="Total KDKMP" icon="fa-solid fa-water" icon-color="text-teal-light dark:text-teal-400"
                icon-bg="bg-teal-light/10 dark:bg-teal-light/20" value="{{ $kpi['total_kdmp'] ?? 45 }}" unit="Lokasi"
                description="<span class='text-teal-light font-medium inline-flex items-center gap-1.5'><i class='fa-solid fa-check-circle shrink-0'></i> Terdaftar aktif budidaya tematik</span>" />

            <!-- Card 2: Sudah Panen -->
            <x-stat-card title="Sudah Panen" icon="fa-solid fa-fish-fins" icon-color="text-success dark:text-emerald-400"
                icon-bg="bg-success/10 dark:bg-success/20" value="{{ $kpi['sudah_panen'] ?? 28 }}" unit="Sentra">
                <div class="flex items-center justify-between text-xs font-medium text-textMuted-light mt-1">
                    <span>Vol: <strong
                            class="text-textMain-light dark:text-white">{{ $kpi['total_volume_panen'] ?? 142.8 }}
                            Ton</strong></span>
                    <span>Nilai: <strong class="text-success">Rp {{ $kpi['total_nilai_panen'] ?? 3.57 }} M</strong></span>
                </div>
            </x-stat-card>

            <!-- Card 3: Belum Panen -->
            <x-stat-card title="Belum Panen" icon="fa-solid fa-spinner fa-spin"
                icon-color="text-warning dark:text-amber-500" icon-bg="bg-warning/10 dark:bg-amber-400/20"
                value="{{ $kpi['belum_panen'] ?? 17 }}" unit="Sentra"
                description="<span class='text-warning font-medium inline-flex items-center gap-1.5'><i class='fa-regular fa-calendar-check shrink-0'></i> {{ $kpi['persen_belum'] ?? 37.8 }}% menunggu panen</span>"
                class="sm:col-span-2 lg:col-span-1" />
        </div>

        <!-- Grafik Dual-Axis Area: Tren Nilai & Volume Produksi per Periode -->
        <div x-data="periodeBarChart()"
            class="mb-6 bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 flex flex-col">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h3 class="font-medium text-xs uppercase tracking-wider text-textMuted-light dark:text-textMuted-dark flex items-center gap-2">
                        <i class="fa-solid fa-chart-area text-teal-light"></i> Tren Kenaikan & Penurunan Produksi per Periode
                    </h3>
                    <p class="text-xs text-textMuted-light mt-1">
                        Menampilkan tren fluktuasi volume panen (Ton - Sumbu Kiri) dan nilai ekonomis (Juta Rp - Sumbu Kanan).
                    </p>
                </div>
                
                <!-- Filter Periode: Bulanan, Mingguan, Tahunan -->
                <div class="flex items-center bg-gray-100 dark:bg-gray-800/80 p-1 rounded-xl shrink-0">
                    <button @click="setPeriode('bulanan')"
                        :class="activePeriode === 'bulanan' ? 'bg-teal-light text-white font-semibold shadow-sm' : 'text-textMuted-light dark:text-textMuted-dark hover:text-textMain-light dark:hover:text-white'"
                        class="px-3 py-1.5 rounded-lg text-xs transition-all duration-200">
                        Bulanan
                    </button>
                    <button @click="setPeriode('mingguan')"
                        :class="activePeriode === 'mingguan' ? 'bg-teal-light text-white font-semibold shadow-sm' : 'text-textMuted-light dark:text-textMuted-dark hover:text-textMain-light dark:hover:text-white'"
                        class="px-3 py-1.5 rounded-lg text-xs transition-all duration-200">
                        Mingguan
                    </button>
                    <button @click="setPeriode('tahunan')"
                        :class="activePeriode === 'tahunan' ? 'bg-teal-light text-white font-semibold shadow-sm' : 'text-textMuted-light dark:text-textMuted-dark hover:text-textMain-light dark:hover:text-white'"
                        class="px-3 py-1.5 rounded-lg text-xs transition-all duration-200">
                        Tahunan
                    </button>
                </div>
            </div>

            <!-- Chart Container -->
            <div class="relative w-full min-h-[340px] min-w-0">
                <div id="periodeBarChartContainer" class="w-full h-full min-w-0 overflow-hidden"></div>
            </div>
        </div>

        <!-- Grafik Sebaran Scatter Plot per Lokasi -->
        <div
            class="mb-6 bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 flex flex-col min-w-0 overflow-hidden">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-4">
                <div>
                    <h3
                        class="font-medium text-xs uppercase tracking-wider text-textMuted-light dark:text-textMuted-dark flex items-center gap-2">
                        <i class="fa-solid fa-chart-scatter text-teal-light"></i> Grafik Sebaran Produksi & Harga Jual per
                        Lokasi KDKMP
                    </h3>
                </div>
                <div class="flex items-center gap-3 text-xs font-medium shrink-0">
                    <span class="flex items-center gap-1.5"><span
                            class="w-3 h-3 rounded-full bg-teal-light shrink-0"></span> Lokasi Sudah Panen</span>
                </div>
            </div>

            <div class="relative w-full h-[320px] sm:h-[380px] min-w-0">
                <div id="scatterPlotBioflok" class="w-full h-full min-w-0 overflow-hidden"></div>
            </div>
        </div>

        <!-- Table Data Produksi KDKMP (DataTable) -->
        <div x-data="produksiDataTable()"
            class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl overflow-hidden flex flex-col">
            <!-- Header -->
            <div
                class="p-6 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="font-medium text-sm flex items-center gap-2">
                        <i class="fa-solid fa-table-list text-teal-light"></i> Tabel Data Produksi KDKMP Budidaya Tematik
                    </h3>
                    <p class="text-xs text-textMuted-light mt-1">Daftar lengkap capaian panen, volume produksi, dan status
                        siklus kelompok budidaya.</p>
                </div>
                <div class="flex gap-2 w-full sm:w-auto self-end sm:self-auto">
                    <a href="{{ route('program.budidaya-tematik.dashboard.produksi.export-pdf', ['bulan' => request('bulan')]) }}" target="_blank"
                        class="px-4 py-2 bg-danger/10 dark:bg-danger/20 border border-danger/20 text-danger rounded-xl text-xs font-medium hover:bg-danger/20 dark:hover:bg-danger/30 transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-file-pdf"></i> Export PDF
                    </a>
                </div>
            </div>

            <!-- Toolbar: Filter + Search -->
            <div
                class="px-6 py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gray-50/50 dark:bg-gray-800/20">
                <!-- Show entries -->
                <div class="flex items-center gap-2 text-xs text-textMuted-light dark:text-textMuted-dark">
                    <span>Tampilkan</span>
                    <select x-model="perPage" @change="currentPage = 1"
                        class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-md px-2 py-1.5 text-xs focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark font-medium">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="all">Semua</option>
                    </select>
                    <span>entri</span>
                </div>

                <!-- Search bar -->
                <div class="relative w-full sm:w-64">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" x-model="searchQuery" @input="currentPage = 1"
                        placeholder="Cari KDKMP atau lokasi..."
                        class="w-full pl-8 pr-4 py-2 rounded-md border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-xs focus:border-teal-light outline-none transition-all">
                </div>
            </div>

            <!-- Table Container -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs whitespace-nowrap">
                    <thead
                        class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
                        <tr>
                            <th rowspan="2"
                                class="py-4 px-6 w-12 text-center align-middle border-r border-gray-100 dark:border-gray-800">
                                No</th>
                            <th rowspan="2" class="py-4 px-6 align-middle border-r border-gray-100 dark:border-gray-800">
                                KDKMP</th>
                            <th colspan="2"
                                class="py-3 px-6 text-center border-b border-r border-gray-100 dark:border-gray-800 font-semibold text-textMain-light dark:text-white">
                                Hasil Panen</th>
                            <th rowspan="2"
                                class="py-4 px-6 text-right align-middle border-r border-gray-100 dark:border-gray-800">
                                Harga Jual</th>
                            <th rowspan="2" class="py-4 px-6 text-center align-middle">Aksi</th>
                        </tr>
                        <tr class="bg-gray-50/50 dark:bg-gray-800/30">
                            <th class="py-3 px-6 text-right border-r border-gray-100 dark:border-gray-800">Volume (Kg)</th>
                            <th class="py-3 px-6 text-right border-r border-gray-100 dark:border-gray-800">Nilai (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
                        <template x-for="(row, index) in paginatedData()" :key="index">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="px-6 py-4 text-center text-textMuted-light border-r border-gray-100 dark:border-gray-800"
                                    x-text="row.no"></td>
                                <td class="px-6 py-4 border-r border-gray-100 dark:border-gray-800">
                                    <div class="font-medium text-textMain-light dark:text-textMain-dark"
                                        x-text="row.kdkmp">
                                    </div>
                                    <div class="text-[11px] text-textMuted-light mt-0.5 truncate"><i
                                            class="fa-solid fa-location-dot mr-1"></i><span x-text="row.lokasi"></span>
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 text-right font-medium text-textMain-light dark:text-textMain-dark whitespace-nowrap border-r border-gray-100 dark:border-gray-800">
                                    <template x-if="row.volume > 0">
                                        <span x-text="new Intl.NumberFormat('id-ID').format(row.volume * 1000)"></span>
                                    </template>
                                    <template x-if="row.volume <= 0">
                                        <span class="text-textMuted-light font-normal italic">-</span>
                                    </template>
                                </td>
                                <td
                                    class="px-6 py-4 text-right font-medium text-success whitespace-nowrap border-r border-gray-100 dark:border-gray-800">
                                    <template x-if="row.nilai > 0">
                                        <span x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(row.nilai)"></span>
                                    </template>
                                    <template x-if="row.nilai <= 0">
                                        <span class="text-textMuted-light font-normal italic">-</span>
                                    </template>
                                </td>
                                <td
                                    class="px-6 py-4 text-right font-medium text-textMain-light dark:text-textMain-dark whitespace-nowrap border-r border-gray-100 dark:border-gray-800">
                                    <template x-if="row.volume > 0">
                                        <span
                                            x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(row.harga_jual)"></span>
                                    </template>
                                    <template x-if="row.volume <= 0">
                                        <span class="text-textMuted-light font-normal italic">-</span>
                                    </template>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        <button
                                            class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-all flex items-center justify-center text-xs"
                                            title="Lihat Detail Log Panen">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button
                                            class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all flex items-center justify-center text-xs"
                                            title="Unduh Sertifikat Panen">
                                            <i class="fa-solid fa-file-arrow-down"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <!-- Empty State -->
                        <tr x-show="filteredData().length === 0">
                            <td colspan="6" class="px-6 py-8 text-center text-textMuted-light">Belum ada data produksi
                                atau tidak ada hasil pencarian.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer: Info + Pagination -->
            <div
                class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50 dark:bg-gray-800/20">
                <!-- Info total data -->
                <div class="text-xs text-textMuted-light dark:text-textMuted-dark">
                    Menampilkan <span class="font-medium text-textMain-light dark:text-textMain-dark"
                        x-text="paginatedData().length"></span> dari <span
                        class="font-medium text-textMain-light dark:text-textMain-dark"
                        x-text="filteredData().length"></span> data
                </div>

                <!-- Pagination -->
                <div class="flex gap-1">
                    <button @click="currentPage = Math.max(1, currentPage - 1)" :disabled="currentPage === 1"
                        class="w-8 h-8 rounded-md border border-gray-100 dark:border-gray-700 flex items-center justify-center text-gray-400 disabled:opacity-30 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    </button>

                    <template x-for="page in visiblePages()" :key="page">
                        <button @click="if(page !== '...') currentPage = page"
                            class="w-8 h-8 rounded-md font-medium text-xs flex items-center justify-center transition-colors"
                            :class="page === currentPage ? 'bg-teal-light text-white' : (page === '...' ?
                                'cursor-default text-gray-400' :
                                'hover:bg-gray-100 dark:hover:bg-gray-800 text-textMain-light dark:text-textMain-dark'
                            )"
                            x-text="page">
                        </button>
                    </template>

                    <button @click="currentPage = Math.min(totalPages(), currentPage + 1)"
                        :disabled="currentPage === totalPages()"
                        class="w-8 h-8 rounded-md border border-gray-100 dark:border-gray-700 flex items-center justify-center text-gray-400 disabled:opacity-30 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ApexCharts & Alpine Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('produksiDataTable', () => ({
                searchQuery: '',
                perPage: '10',
                currentPage: 1,
                rawList: @json($tableProduksi ?? []),

                filteredData() {
                    const q = this.searchQuery.toLowerCase().trim();
                    if (!q) return this.rawList;
                    return this.rawList.filter(item => {
                        return item.kdkmp.toLowerCase().includes(q) ||
                            item.lokasi.toLowerCase().includes(q) ||
                            String(item.no).includes(q);
                    });
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
                    const pp = parseInt(this.perPage);
                    return Math.max(1, Math.ceil(this.filteredData().length / pp));
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

        document.addEventListener('DOMContentLoaded', function() {
            const rawScatterData = @json($scatterData ?? []);
            const isDark = document.documentElement.classList.contains('dark');

            const formattedSeries = rawScatterData.map(item => ({
                x: item.volume,
                y: item.nilai,
                name: item.name,
                harga: item.harga,
                provinsi: item.provinsi
            }));

            const options = {
                series: [{
                    name: 'Produksi KDKMP',
                    data: formattedSeries
                }],
                chart: {
                    height: window.innerWidth < 640 ? 280 : 360,
                    type: 'scatter',
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: true,
                        type: 'xy'
                    },
                    fontFamily: 'inherit'
                },
                theme: {
                    mode: isDark ? 'dark' : 'light'
                },
                colors: ['#0891B2'],
                xaxis: {
                    title: {
                        text: 'Volume Hasil Panen (Ton)',
                        style: {
                            fontWeight: 600,
                            fontSize: window.innerWidth < 640 ? '11px' : '12px',
                            color: '#64748b'
                        }
                    },
                    tickAmount: window.innerWidth < 640 ? 5 : 8,
                    labels: {
                        formatter: val => parseFloat(val).toFixed(1) + ' Ton',
                        style: {
                            colors: '#94a3b8',
                            fontSize: '11px',
                            fontWeight: 500
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: 'Total Nilai Panen (Juta Rp)',
                        style: {
                            fontWeight: 600,
                            fontSize: window.innerWidth < 640 ? '11px' : '12px',
                            color: '#64748b'
                        }
                    },
                    labels: {
                        formatter: val => 'Rp ' + parseFloat(val).toFixed(0) + ' Jt',
                        style: {
                            colors: '#94a3b8',
                            fontSize: '11px',
                            fontWeight: 500
                        }
                    }
                },
                markers: {
                    size: window.innerWidth < 640 ? 7 : 9,
                    hover: {
                        size: 12
                    }
                },
                tooltip: {
                    custom: function({
                        series,
                        seriesIndex,
                        dataPointIndex,
                        w
                    }) {
                        const data = w.config.series[seriesIndex].data[dataPointIndex];
                        const volumeKg = Math.round(data.x * 1000);
                        const formattedKg = new Intl.NumberFormat('id-ID').format(volumeKg);
                        const formattedTon = new Intl.NumberFormat('id-ID').format(data.x);
                        const formattedNilai = new Intl.NumberFormat('id-ID').format(data.y);
                        const formattedHarga = new Intl.NumberFormat('id-ID').format(data.harga);

                        return `<div class="custom-chart-tooltip p-4 bg-white/95 dark:bg-gray-900/95 backdrop-blur-md border border-gray-100 dark:border-gray-800 rounded-2xl shadow-2xl text-xs min-w-[240px] transition-all">
                            <div class="flex items-center justify-between gap-3 mb-1">
                                <span class="font-bold text-teal-light dark:text-teal-400 text-sm tracking-tight">${data.name}</span>
                                <span class="px-2 py-0.5 rounded-md bg-teal-light/10 text-teal-light dark:text-teal-400 font-semibold text-[10px]">Panen</span>
                            </div>
                            <div class="text-textMuted-light text-[11px] mb-3 flex items-center gap-1.5">
                                <i class="fa-solid fa-location-dot text-teal-light"></i>
                                <span>${data.provinsi}</span>
                            </div>
                            <div class="space-y-2 pt-2.5 border-t border-gray-100 dark:border-gray-800 pl-0.5">
                                <div class="flex justify-between items-center gap-4">
                                    <span class="text-textMuted-light font-medium flex items-center gap-2"><i class="fa-solid fa-scale-balanced text-teal-light w-3 text-center"></i> Volume Panen</span>
                                    <span class="font-bold text-textMain-light dark:text-white text-right">${formattedTon} Ton <span class="block text-[10px] font-normal text-textMuted-light">(${formattedKg} kg)</span></span>
                                </div>
                                <div class="flex justify-between items-center gap-4">
                                    <span class="text-textMuted-light font-medium flex items-center gap-2"><i class="fa-solid fa-money-bill-trend-up text-success w-3 text-center"></i> Nilai Total</span>
                                    <span class="font-bold text-success text-right">Rp ${formattedNilai} Juta</span>
                                </div>
                                <div class="flex justify-between items-center gap-4">
                                    <span class="text-textMuted-light font-medium flex items-center gap-2"><i class="fa-solid fa-tag text-amber-500 w-3 text-center"></i> Harga Jual</span>
                                    <span class="font-bold text-textMain-light dark:text-white text-right">Rp ${formattedHarga} / kg</span>
                                </div>
                            </div>
                        </div>`;
                    }
                },
                grid: {
                    borderColor: isDark ? '#374151' : '#f1f5f9',
                    strokeDashArray: 4
                }
            };

            const chart = new ApexCharts(document.querySelector("#scatterPlotBioflok"), options);
            chart.render();

            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.attributeName === 'class') {
                        const newIsDark = document.documentElement.classList.contains('dark');
                        chart.updateOptions({
                            theme: {
                                mode: newIsDark ? 'dark' : 'light'
                            },
                            grid: {
                                borderColor: newIsDark ? '#374151' : '#f1f5f9'
                            }
                        });
                    }
                });
            });
            observer.observe(document.documentElement, {
                attributes: true
            });
        });

        function periodeBarChart() {
            return {
                activePeriode: 'bulanan',
                chart: null,
                chartData: @json($barChartPeriods ?? []),
                init() {
                    const isDark = document.documentElement.classList.contains('dark');
                    const data = this.chartData[this.activePeriode] || { categories: [], volume: [], nilai: [] };

                    const options = {
                        series: [
                            {
                                name: 'Volume Produksi (Ton)',
                                data: data.volume
                            },
                            {
                                name: 'Nilai Produksi (Juta Rp)',
                                data: data.nilai
                            }
                        ],
                        chart: {
                            type: 'area',
                            height: 360,
                            toolbar: { show: false },
                            fontFamily: 'inherit',
                            animations: {
                                enabled: true,
                                speed: 400
                            }
                        },
                        colors: ['#0891B2', '#10B981'],
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 3
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: 0.45,
                                opacityTo: 0.05,
                                stops: [20, 100]
                            }
                        },
                        markers: {
                            size: 4,
                            colors: ['#0891B2', '#10B981'],
                            strokeColors: '#ffffff',
                            strokeWidth: 2,
                            hover: { size: 6 }
                        },
                        xaxis: {
                            categories: data.categories,
                            labels: {
                                style: {
                                    colors: '#94a3b8',
                                    fontSize: '11px'
                                }
                            }
                        },
                        yaxis: [
                            {
                                seriesName: 'Volume Produksi (Ton)',
                                title: {
                                    text: 'Volume (Ton)',
                                    style: { color: '#0891B2', fontSize: '11px', fontWeight: 600 }
                                },
                                labels: {
                                    style: {
                                        colors: isDark ? '#cbd5e1' : '#475569',
                                        fontSize: '11px',
                                        fontWeight: 500
                                    },
                                    formatter: function (val) {
                                        return val + ' Ton';
                                    }
                                }
                            },
                            {
                                opposite: true,
                                seriesName: 'Nilai Produksi (Juta Rp)',
                                title: {
                                    text: 'Nilai (Juta Rp)',
                                    style: { color: '#10b981', fontSize: '11px', fontWeight: 600 }
                                },
                                labels: {
                                    style: {
                                        colors: isDark ? '#cbd5e1' : '#475569',
                                        fontSize: '11px',
                                        fontWeight: 500
                                    },
                                    formatter: function (val) {
                                        return 'Rp ' + val + ' Jt';
                                    }
                                }
                            }
                        ],
                        legend: {
                            position: 'top',
                            horizontalAlign: 'right',
                            labels: {
                                colors: isDark ? '#cbd5e1' : '#475569'
                            }
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            custom: function({ series, seriesIndex, dataPointIndex, w }) {
                                const cat = w.globals.labels[dataPointIndex];
                                const vol = series[0][dataPointIndex];
                                const nil = series[1][dataPointIndex];
                                const volKg = Math.round(vol * 1000);
                                return `<div class="custom-chart-tooltip p-3.5 bg-white/95 dark:bg-gray-900/95 backdrop-blur-md border border-gray-100 dark:border-gray-800 rounded-xl shadow-xl text-xs min-w-[210px]">
                                    <div class="font-bold text-teal-light dark:text-teal-400 text-sm mb-2 pb-1.5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                                        <span>${cat}</span>
                                        <i class="fa-regular fa-calendar text-[11px] text-textMuted-light"></i>
                                    </div>
                                    <div class="space-y-1.5">
                                        <div class="flex justify-between items-center gap-3">
                                            <span class="text-textMuted-light flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-teal-light shrink-0"></span> Volume:</span>
                                            <span class="font-bold text-textMain-light dark:text-white">${new Intl.NumberFormat('id-ID').format(vol)} Ton <span class="text-[10px] text-textMuted-light">(${new Intl.NumberFormat('id-ID').format(volKg)} kg)</span></span>
                                        </div>
                                        <div class="flex justify-between items-center gap-3">
                                            <span class="text-textMuted-light flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-success shrink-0"></span> Nilai Total:</span>
                                            <span class="font-bold text-success">Rp ${new Intl.NumberFormat('id-ID').format(nil)} Juta</span>
                                        </div>
                                    </div>
                                </div>`;
                            }
                        },
                        grid: {
                            borderColor: isDark ? '#374151' : '#f1f5f9',
                            strokeDashArray: 4
                        }
                    };

                    this.chart = new ApexCharts(document.querySelector("#periodeBarChartContainer"), options);
                    this.chart.render();

                    const observer = new MutationObserver((mutations) => {
                        mutations.forEach((mutation) => {
                            if (mutation.attributeName === 'class') {
                                const newIsDark = document.documentElement.classList.contains('dark');
                                if (this.chart) {
                                    this.chart.updateOptions({
                                        yaxis: [
                                            { labels: { style: { colors: newIsDark ? '#cbd5e1' : '#475569' } } },
                                            { labels: { style: { colors: newIsDark ? '#cbd5e1' : '#475569' } } }
                                        ],
                                        legend: {
                                            labels: { colors: newIsDark ? '#cbd5e1' : '#475569' }
                                        },
                                        grid: {
                                            borderColor: newIsDark ? '#374151' : '#f1f5f9'
                                        }
                                    });
                                }
                            }
                        });
                    });
                    observer.observe(document.documentElement, { attributes: true });
                },
                setPeriode(periode) {
                    this.activePeriode = periode;
                    const data = this.chartData[periode] || { categories: [], volume: [], nilai: [] };
                    if (this.chart) {
                        this.chart.updateOptions({
                            xaxis: {
                                categories: data.categories
                            },
                            series: [
                                {
                                    name: 'Volume Produksi (Ton)',
                                    data: data.volume
                                },
                                {
                                    name: 'Nilai Produksi (Juta Rp)',
                                    data: data.nilai
                                }
                            ]
                        }, true, true);
                    }
                }
            };
        }
    </script>
@endsection

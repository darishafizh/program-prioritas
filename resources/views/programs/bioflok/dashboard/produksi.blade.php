@extends('layouts.app')

@section('title', 'Bioflok - Dashboard Produksi')

@section('content')
    <div x-data="{ bulan: '{{ $bulan }}' }">
        <!-- Header & Global Filters Sejajar -->
        <div class="mb-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h2 class="text-xl font-semibold tracking-tight">Dashboard Produksi Bioflok</h2>
                <p class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1">Monitoring capaian
                    produksi sistem bioflok per Kelompok Budidaya (KDKMP).</p>
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

                <button type="button" onclick="window.print()"
                    class="px-4 py-2 bg-danger/10 border border-danger/20 text-danger rounded-xl text-xs font-medium hover:bg-danger/20 transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-file-pdf"></i> PDF
                </button>
            </form>
        </div>

        <!-- 3 KPI Cards: Total KDMP, Sudah Panen, Belum Panen -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <!-- Card 1: Total KDMP -->
            <div
                class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 w-32 h-32 bg-teal-light/10 dark:bg-teal-light/20 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110">
                </div>
                <div class="flex items-center gap-4 mb-4 relative z-10">
                    <div
                        class="w-12 h-12 rounded-xl bg-teal-light/10 dark:bg-teal-light/20 flex items-center justify-center text-teal-light dark:text-teal-400 text-sm shrink-0">
                        <i class="fa-solid fa-water"></i>
                    </div>
                    <div>
                        <h3
                            class="text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">
                            Total KDKMP</h3>
                        <div class="text-sm font-medium text-textMain-light dark:text-textMain-dark">
                            {{ $kpi['total_kdmp'] ?? 45 }} </div>
                    </div>
                </div>
                <div class="flex items-center gap-1.5 text-xs font-medium text-teal-light relative z-10">
                    <i class="fa-solid fa-check-circle shrink-0"></i> Terdaftar aktif budidaya bioflok
                </div>
            </div>

            <!-- Card 2: Sudah Panen -->
            <div
                class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 w-32 h-32 bg-success/10 dark:bg-success/20 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110">
                </div>
                <div class="flex items-center gap-4 mb-4 relative z-10">
                    <div
                        class="w-12 h-12 rounded-xl bg-success/10 dark:bg-success/20 flex items-center justify-center text-success dark:text-emerald-400 text-sm shrink-0">
                        <i class="fa-solid fa-fish-fins"></i>
                    </div>
                    <div>
                        <h3
                            class="text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">
                            Sudah Panen</h3>
                        <div class="text-sm font-medium text-success dark:text-emerald-400">{{ $kpi['sudah_panen'] ?? 28 }}
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between text-xs font-medium text-textMuted-light relative z-10">
                    <span>Vol: <strong
                            class="text-textMain-light dark:text-white">{{ $kpi['total_volume_panen'] ?? 142.8 }}
                            Ton</strong></span>
                    <span>Nilai: <strong class="text-success">Rp {{ $kpi['total_nilai_panen'] ?? 3.57 }} M</strong></span>
                </div>
            </div>

            <!-- Card 3: Belum Panen -->
            <div
                class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 relative overflow-hidden group sm:col-span-2 lg:col-span-1">
                <div
                    class="absolute top-0 right-0 w-32 h-32 bg-warning/10 dark:bg-amber-400/20 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110">
                </div>
                <div class="flex items-center gap-4 mb-4 relative z-10">
                    <div
                        class="w-12 h-12 rounded-xl bg-warning/10 dark:bg-amber-400/20 flex items-center justify-center text-warning dark:text-amber-500 text-sm shrink-0">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                    </div>
                    <div>
                        <h3
                            class="text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">
                            Belum Panen</h3>
                        <div class="text-sm font-medium text-warning dark:text-amber-500">{{ $kpi['belum_panen'] ?? 17 }}
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1.5 text-xs font-medium text-warning relative z-10">
                    <i class="fa-regular fa-calendar-check shrink-0"></i> {{ $kpi['persen_belum'] ?? 37.8 }}% dalam tahap
                    pemeliharaan
                </div>
            </div>
        </div>

        <!-- Grafik Sebaran Scatter Plot per Lokasi -->
        <div
            class="mb-6 bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 flex flex-col">
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

            <div class="relative w-full h-[320px] sm:h-[380px]">
                <div id="scatterPlotBioflok" class="w-full h-full"></div>
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
                        <i class="fa-solid fa-table-list text-teal-light"></i> Tabel Data Produksi KDKMP Bioflok
                    </h3>
                    <p class="text-xs text-textMuted-light mt-1">Daftar lengkap capaian panen, volume produksi, dan status
                        siklus kelompok budidaya.</p>
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
                                    <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="row.kdkmp">
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
                                        <span
                                            x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(row.nilai)"></span>
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
                colors: ['#0d9488'],
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
                        return `<div class="p-3.5 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl shadow-xl text-xs">
                            <div class="font-bold text-teal-light text-sm mb-0.5">${data.name}</div>
                            <div class="text-textMuted-light text-[11px] mb-2.5"><i class="fa-solid fa-location-dot mr-1"></i>${data.provinsi}</div>
                            <div class="space-y-1.5 pt-2 border-t border-gray-100 dark:border-gray-800">
                                <div class="flex justify-between gap-4"><span class="text-textMuted-light">Volume Panen:</span> <span class="font-bold text-textMain-light dark:text-white">${data.x} Ton (${new Intl.NumberFormat('id-ID').format(data.x * 1000)} kg)</span></div>
                                <div class="flex justify-between gap-4"><span class="text-textMuted-light">Nilai Total:</span> <span class="font-bold text-success">Rp ${data.y} Juta</span></div>
                                <div class="flex justify-between gap-4"><span class="text-textMuted-light">Harga Jual:</span> <span class="font-bold text-textMain-light dark:text-white">Rp ${new Intl.NumberFormat('id-ID').format(data.harga)} / kg</span></div>
                            </div>
                        </div>`;
                    }
                },
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4
                }
            };

            const chart = new ApexCharts(document.querySelector("#scatterPlotBioflok"), options);
            chart.render();
        });
    </script>
@endsection

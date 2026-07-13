@extends('layouts.app')

@section('title', 'KNMP - Evaluasi Progres Fisik')

@section('content')
    <div x-data="evaluasiProgresFisikManager()">

        {{-- Header --}}
        <div class="mb-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h2 class="text-xl font-semibold tracking-tight">Evaluasi Progres Fisik</h2>
                <p class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1">Evaluasi historis
                    progres konstruksi KNMP — perbandingan rencana vs aktual.</p>
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
                    <input type="date" name="date" value="{{ request('date') ?: $effectiveDate }}"
                        onchange="this.form.submit()"
                        class="bg-transparent border-none outline-none text-textMain-light dark:text-textMain-dark w-32">
                </div>

                @can('generate-pdf')
                <button type="button" @click="isPdfModalOpen = true"
                    class="px-4 py-2 bg-danger/10 border border-danger/20 text-danger rounded-xl text-xs font-medium hover:bg-danger/20 transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-file-pdf"></i> Generate PDF
                </button>
                @endcan
            </form>
        </div>

        {{-- KPI Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            {{-- Konstruksi Aktif --}}
            <x-stat-card
                title="Konstruksi Aktif"
                icon="fa-solid fa-person-digging"
                icon-color="text-warning dark:text-amber-500"
                icon-bg="bg-warning/10 dark:bg-amber-400/20"
                value="{{ $stats['konstruksi_aktif'] }}"
                unit="Lokasi"
            />

            {{-- Rata-Rata Progres --}}
            <x-stat-card
                title="Rata-rata Rencana vs Aktual"
                icon="fa-solid fa-chart-line"
                icon-color="text-teal-light dark:text-teal-400"
                icon-bg="bg-teal-light/10 dark:bg-teal-400/20"
                value="{{ number_format($stats['rata_progres'] ?? 0, 2, ',', '.') }}"
                unit="%"
            >
                <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2 mt-2">
                    <div class="bg-teal-light dark:bg-teal-400 h-2 rounded-full transition-all" style="width: {{ $stats['rata_progres'] }}%"></div>
                </div>
            </x-stat-card>

            {{-- Total Selesai --}}
            <x-stat-card
                title="Serah Terima"
                icon="fa-solid fa-check-double"
                icon-color="text-success dark:text-emerald-400"
                icon-bg="bg-success/10 dark:bg-success/20"
                value="{{ $stats['total_selesai'] }}"
                unit="Lokasi"
            />

            {{-- Rata-Rata Deviasi --}}
            <x-stat-card
                title="Rata-Rata Deviasi"
                icon="{{ $stats['rata_deviasi'] >= 0 ? 'fa-solid fa-arrow-trend-up' : 'fa-solid fa-arrow-trend-down' }}"
                icon-color="{{ $stats['rata_deviasi'] >= 0 ? 'text-success dark:text-emerald-400' : 'text-danger dark:text-red-400' }}"
                icon-bg="{{ $stats['rata_deviasi'] >= 0 ? 'bg-success/10 dark:bg-success/20' : 'bg-danger/10 dark:bg-danger/20' }}"
                value="{{ ($stats['rata_deviasi'] >= 0 ? '+' : '') . $stats['rata_deviasi'] }}"
                unit="%"
            />
        </div>

        {{-- Table Card --}}
        <div
            class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl overflow-hidden flex flex-col">
            <div
                class="p-6 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="font-medium text-sm flex items-center gap-2">
                        <i class="fa-solid fa-table-list text-teal-light"></i> Daftar Progres Konstruksi KNMP
                    </h3>
                    <p class="text-xs text-textMuted-light mt-1">Data progres per tanggal
                        <strong>{{ \Carbon\Carbon::parse($effectiveDate)->format('d M Y') }}</strong>.</p>
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
                    <input type="text" x-model="searchQuery" @input="currentPage = 1"
                        placeholder="Cari nama lokasi/vendor..."
                        class="w-full pl-8 pr-4 py-2 rounded-md border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-xs focus:border-teal-light outline-none transition-all">
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs whitespace-nowrap">
                    <thead
                        class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
                        <tr>
                            <th class="px-6 py-4">Nama KNMP</th>
                            <th class="px-6 py-4">Konstruktor</th>
                            <th class="px-6 py-4">Rencana</th>
                            <th class="px-6 py-4">Progres Aktual</th>
                            <th class="px-6 py-4">Deviasi</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Tanggal Data</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
                        <template x-for="(item, index) in paginatedData()" :key="index">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="item.nama">
                                    </div>
                                    <template x-if="item.tahap === 'serah_terima'">
                                        <span
                                            class="text-[9px] text-success font-medium bg-success/10 px-1.5 py-0.5 rounded mt-0.5 inline-block"><i
                                                class="fa-solid fa-check mr-0.5"></i> Selesai</span>
                                    </template>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-teal-100 dark:bg-teal-900/30 text-teal-light flex items-center justify-center text-[10px] font-bold"
                                            x-text="item.konstruktor.substring(0, 2).toUpperCase()"></div>
                                        <span class="font-medium text-textMuted-light" x-text="item.konstruktor"></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-medium text-textMain-light dark:text-textMain-dark"
                                        x-text="item.rencana + '%'"></span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1.5 w-40">
                                        <div class="flex justify-between items-center">
                                            <span class="font-medium text-xs" x-text="item.progres + '%'"></span>
                                        </div>
                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                            <div class="h-1.5 rounded-full transition-all"
                                                :class="item.progres >= 100 ? 'bg-success' : 'bg-teal-light'"
                                                :style="'width: ' + Math.min(item.progres, 100) + '%'"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <template x-if="item.deviasi >= 0">
                                        <span
                                            class="text-success font-medium text-[0.7rem] flex items-center gap-1 bg-success/10 px-2 py-0.5 rounded w-fit">
                                            <i class="fa-solid fa-arrow-up text-[8px]"></i> +<span
                                                x-text="item.deviasi"></span>%
                                        </span>
                                    </template>
                                    <template x-if="item.deviasi < 0">
                                        <span
                                            class="text-danger font-medium text-[0.7rem] flex items-center gap-1 bg-danger/10 px-2 py-0.5 rounded w-fit">
                                            <i class="fa-solid fa-arrow-down text-[8px]"></i> <span
                                                x-text="item.deviasi"></span>%
                                        </span>
                                    </template>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-md text-[0.7rem] font-medium"
                                        :class="{
                                            'bg-success/10 text-success': item.status_color === 'success',
                                            'bg-warning/10 text-warning': item.status_color === 'warning',
                                            'bg-danger/10 text-danger': item.status_color === 'danger',
                                        }"
                                        x-text="item.status_kesehatan"></span>
                                </td>
                                <td class="px-6 py-4 text-textMuted-light" x-text="item.tanggal_progres"></td>
                            </tr>
                        </template>
                        <tr x-show="paginatedData().length === 0">
                            <td colspan="7" class="px-6 py-8 text-center text-textMuted-light">Belum ada data progres
                                atau tidak ada hasil pencarian.</td>
                        </tr>
                    </tbody>
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
                <form action="{{ route('program.evaluasi.progres-fisik.pdf', ['program' => 'knmp']) }}" method="GET"
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
                            <input type="date" name="date" value="{{ $effectiveDate }}"
                                class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark">
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end gap-3">
                        <button type="button" @click="isPdfModalOpen = false"
                            class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-danger text-white rounded-lg text-sm font-medium hover:bg-red-600 transition-colors flex items-center gap-2"><i
                                class="fa-solid fa-download"></i> Generate PDF</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('evaluasiProgresFisikManager', () => ({
                searchQuery: '',
                perPage: '25',
                currentPage: 1,
                isPdfModalOpen: false,
                tableData: @json($progresFisikData),

                filteredData() {
                    const q = this.searchQuery.toLowerCase().trim();
                    if (!q) return this.tableData;
                    return this.tableData.filter(item =>
                        (item.nama || '').toLowerCase().includes(q) ||
                        (item.konstruktor || '').toLowerCase().includes(q) ||
                        (item.status_kesehatan || '').toLowerCase().includes(q)
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
@endsection

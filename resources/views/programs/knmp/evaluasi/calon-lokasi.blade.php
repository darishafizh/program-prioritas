@extends('layouts.app')

@section('title', 'KNMP - Evaluasi Calon Lokasi')

@section('content')
<div x-data="evaluasiCalonLokasiManager()">

{{-- Header --}}
<div class="mb-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
    <div>
        <h2 class="text-xl font-semibold tracking-tight">Evaluasi Calon Lokasi</h2>
        <p class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1">Evaluasi historis pipeline calon lokasi KNMP — dari pengajuan hingga penetapan.</p>
    </div>

    {{-- Filter Bar --}}
    <form id="evaluasiFilterForm" action="{{ url()->current() }}" method="GET" class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
        <div class="relative">
            <select name="batch_id" onchange="this.form.submit()" class="appearance-none bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-xl px-4 py-2 pr-10 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-teal-light text-textMain-light dark:text-textMain-dark">
                <option value="">Semua Tahap</option>
                @foreach($filter_batches as $batch)
                <option value="{{ $batch['id'] }}" {{ request('batch_id') == $batch['id'] ? 'selected' : '' }}>{{ $batch['name'] }}</option>
                @endforeach
            </select>
            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
        </div>

        <div class="relative flex items-center bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-xl px-4 py-2 text-xs font-medium focus-within:ring-2 focus-within:ring-teal-light">
            <i class="fa-regular fa-calendar text-gray-400 mr-2"></i>
            <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()" class="bg-transparent border-none outline-none text-textMain-light dark:text-textMain-dark w-32">
        </div>

        <button type="button" @click="isPdfModalOpen = true" class="px-4 py-2 bg-danger/10 border border-danger/20 text-danger rounded-xl text-xs font-medium hover:bg-danger/20 transition-colors flex items-center gap-2">
            <i class="fa-solid fa-file-pdf"></i> Generate PDF
        </button>
    </form>
</div>

{{-- KPI Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    {{-- Total Calon Lokasi --}}
    <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-teal-light/10 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
        <div class="flex items-center gap-4 mb-3 relative z-10">
            <div class="w-12 h-12 rounded-xl bg-teal-light/10 flex items-center justify-center text-teal-light text-sm"><i class="fa-solid fa-map-location-dot"></i></div>
            <div>
                <h3 class="text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">Total Calon Lokasi</h3>
                <div class="text-sm font-medium">{{ $stats['total'] }} <span class="text-sm font-medium text-textMuted-light">Lokasi</span></div>
            </div>
        </div>
    </div>

    {{-- Tahap Pengajuan --}}
    <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 relative overflow-hidden group">
        <div style="background-color: rgba(59, 130, 246, 0.1);" class="absolute top-0 right-0 w-32 h-32 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
        <div class="flex items-center gap-4 mb-3 relative z-10">
            <div style="background-color: rgba(59, 130, 246, 0.1); color: #3b82f6;" class="w-12 h-12 rounded-xl flex items-center justify-center text-sm"><i class="fa-solid fa-file-circle-plus"></i></div>
            <div>
                <h3 class="text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">Tahap Pengajuan</h3>
                <div style="color: #3b82f6;" class="text-sm font-medium">{{ $stats['pengajuan'] }} <span class="text-sm font-medium text-textMuted-light">Lokasi</span></div>
            </div>
        </div>
    </div>

    {{-- Dalam Verifikasi --}}
    <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-warning/10 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
        <div class="flex items-center gap-4 mb-3 relative z-10">
            <div class="w-12 h-12 rounded-xl bg-warning/10 flex items-center justify-center text-warning text-sm"><i class="fa-solid fa-magnifying-glass-chart"></i></div>
            <div>
                <h3 class="text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">Dalam Verifikasi</h3>
                <div class="text-sm font-medium text-warning">{{ $stats['verifikasi'] }} <span class="text-sm font-medium text-textMuted-light">Lokasi</span></div>
            </div>
        </div>
    </div>

    {{-- Sudah Ditetapkan --}}
    <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-success/10 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
        <div class="flex items-center gap-4 mb-3 relative z-10">
            <div class="w-12 h-12 rounded-xl bg-success/10 flex items-center justify-center text-success text-sm"><i class="fa-solid fa-circle-check"></i></div>
            <div>
                <h3 class="text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">Sudah Ditetapkan</h3>
                <div class="text-sm font-medium text-success">{{ $stats['ditetapkan'] }} <span class="text-sm font-medium text-textMuted-light">Lokasi</span></div>
            </div>
        </div>
    </div>
</div>

{{-- Table Card --}}
<div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl overflow-hidden flex flex-col">
    {{-- Table Header --}}
    <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h3 class="font-medium text-sm flex items-center gap-2">
                <i class="fa-solid fa-table-list text-teal-light"></i> Riwayat Pipeline Calon Lokasi
            </h3>
            <p class="text-xs text-textMuted-light mt-1">Tracking status setiap calon lokasi melalui tahapan evaluasi.</p>
        </div>
    </div>

    {{-- Toolbar --}}
    <div class="px-6 py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gray-50/50 dark:bg-gray-800/20">
        <div class="flex items-center gap-2 text-xs text-textMuted-light dark:text-textMuted-dark">
            <span>Tampilkan</span>
            <select x-model="perPage" @change="currentPage = 1" class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-md px-2 py-1.5 text-xs focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark font-medium">
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="all">Semua</option>
            </select>
            <span>entri</span>
        </div>
        <div class="relative w-full sm:w-64">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Cari nama lokasi..." class="w-full pl-8 pr-4 py-2 rounded-md border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-xs focus:border-teal-light outline-none transition-all">
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs whitespace-nowrap">
            <thead class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
                <tr>
                    <th class="px-6 py-4">ID User</th>
                    <th class="px-6 py-4">Nama Lokasi</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 min-w-[420px]">Tracking Pipeline</th>
                    <th class="px-6 py-4">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
                <template x-for="(item, index) in paginatedData()" :key="index">
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-medium text-teal-light hover:underline cursor-pointer" x-text="item.idUser"></span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="item.desa"></div>
                            <div class="text-[10px] text-gray-400 mt-0.5" x-text="`${item.kecamatan}, ${item.kabupaten}, ${item.provinsi}`"></div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-md text-[0.7rem] font-medium"
                                :class="{
                                    'bg-teal-light/10 text-teal-light': ['verif_admin','ba_aktivasi','verif_teknis','ba_calon'].includes(item.status_tahapan),
                                    'bg-success/10 text-success': item.status_tahapan === 'penetapan',
                                    'bg-danger/10 text-danger': item.status_tahapan === 'ditolak',
                                }"
                                :style="item.status_tahapan === 'pengajuan' ? 'background-color: rgba(59, 130, 246, 0.1); color: #3b82f6;' : ''"
                                x-text="stageLabels[item.status_tahapan] || item.status_tahapan"></span>
                        </td>
                        <td class="px-6 py-4">
                            {{-- Visual Stepper --}}
                            <div class="flex items-center gap-0">
                                <template x-for="(stage, sIdx) in item.stages" :key="stage.key">
                                    <div class="flex items-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-[9px] font-bold transition-all border-2"
                                                :class="{
                                                    'bg-success text-white border-success': stage.status === 'completed',
                                                    'bg-teal-light text-white border-teal-light animate-pulse': stage.status === 'active',
                                                    'bg-gray-100 dark:bg-gray-800 text-gray-400 border-gray-200 dark:border-gray-700': stage.status === 'pending',
                                                    'bg-danger/20 text-danger border-danger/40': stage.status === 'rejected',
                                                }">
                                                <i class="fa-solid" :class="{
                                                    'fa-check': stage.status === 'completed',
                                                    'fa-circle text-[6px]': stage.status === 'active',
                                                    'fa-circle text-[4px]': stage.status === 'pending',
                                                    'fa-xmark': stage.status === 'rejected',
                                                }"></i>
                                            </div>
                                            <span class="text-[8px] mt-1 max-w-[50px] text-center leading-tight"
                                                :class="stage.status === 'active' ? 'text-teal-light font-bold' : 'text-gray-400'"
                                                x-text="stepperLabels[stage.key]"></span>
                                        </div>
                                        <template x-if="sIdx < item.stages.length - 1">
                                            <div class="w-6 h-0.5 mb-3 mx-0.5"
                                                :class="stage.status === 'completed' ? 'bg-success' : 'bg-gray-200 dark:bg-gray-700'"></div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-textMuted-light" x-text="item.updated_at"></td>
                    </tr>
                </template>
                <tr x-show="paginatedData().length === 0">
                    <td colspan="6" class="px-6 py-8 text-center text-textMuted-light">Belum ada data calon lokasi atau tidak ada hasil pencarian.</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Footer Pagination --}}
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50 dark:bg-gray-800/20">
        <div class="text-xs text-textMuted-light dark:text-textMuted-dark">
            Menampilkan <span class="font-medium text-textMain-light dark:text-textMain-dark" x-text="paginatedData().length"></span> dari <span class="font-medium text-textMain-light dark:text-textMain-dark" x-text="filteredData().length"></span> data
        </div>
        <div class="flex gap-1" x-show="totalPages() > 1">
            <button @click="currentPage = Math.max(1, currentPage - 1)" :disabled="currentPage === 1" class="w-8 h-8 rounded-md border border-gray-100 dark:border-gray-700 flex items-center justify-center text-gray-400 disabled:opacity-30 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"><i class="fa-solid fa-chevron-left text-[10px]"></i></button>
            <template x-for="page in visiblePages()" :key="page">
                <button @click="if(page !== '...') currentPage = page" class="w-8 h-8 rounded-md font-medium text-xs flex items-center justify-center transition-colors" :class="page === currentPage ? 'bg-teal-light text-white' : (page === '...' ? 'cursor-default text-gray-400' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-textMain-light dark:text-textMain-dark')" x-text="page"></button>
            </template>
            <button @click="currentPage = Math.min(totalPages(), currentPage + 1)" :disabled="currentPage === totalPages()" class="w-8 h-8 rounded-md border border-gray-100 dark:border-gray-700 flex items-center justify-center text-gray-400 disabled:opacity-30 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"><i class="fa-solid fa-chevron-right text-[10px]"></i></button>
        </div>
    </div>
</div>

{{-- PDF Modal --}}
<div x-show="isPdfModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div @click.away="isPdfModalOpen = false" x-transition.opacity.duration.200ms class="bg-white dark:bg-gray-900 rounded-2xl w-full max-w-md p-6 shadow-xl border border-gray-100 dark:border-gray-800 relative">
        <div class="flex justify-between items-center mb-5">
            <h3 class="text-lg font-bold text-textMain-light dark:text-textMain-dark">Export Laporan PDF</h3>
            <button @click="isPdfModalOpen = false" class="text-gray-400 hover:text-danger transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form action="{{ route('program.evaluasi.calon-lokasi.pdf', ['program' => 'knmp']) }}" method="GET" target="_blank" @submit="setTimeout(() => isPdfModalOpen = false, 500)">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Pilih Tahap (Batch)</label>
                    <select name="batch_id" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark">
                        <option value="">Semua Tahap</option>
                        @foreach($filter_batches as $batch)
                        <option value="{{ $batch['id'] }}">{{ $batch['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Pilih Tanggal</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark">
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" @click="isPdfModalOpen = false" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-danger text-white rounded-lg text-sm font-medium hover:bg-red-600 transition-colors flex items-center gap-2"><i class="fa-solid fa-download"></i> Generate PDF</button>
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
            if (total <= 7) return Array.from({length: total}, (_, i) => i + 1);
            const pages = [];
            const cur = this.currentPage;
            pages.push(1);
            if (cur > 3) pages.push('...');
            for (let i = Math.max(2, cur - 1); i <= Math.min(total - 1, cur + 1); i++) { pages.push(i); }
            if (cur < total - 2) pages.push('...');
            pages.push(total);
            return pages;
        }
    }));
});
</script>
@endsection

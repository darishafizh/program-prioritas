@extends('layouts.app')

@section('title', 'KNMP - Monitoring Konstruksi')

@section('content')
<div x-data="{ isPdfModalOpen: false }">

    {{-- Header --}}
    <div class="mb-6 animate-fade-in-up">
        <a href="{{ route('program.dashboard', ['program' => strtolower($activeProgram)]) }}"
            class="inline-flex items-center gap-2 text-xs font-medium text-textMuted-light dark:text-textMuted-dark hover:text-teal-light dark:hover:text-teal-400 transition-colors mb-5 bg-white dark:bg-gray-800 px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard Utama
        </a>

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3 sm:gap-4">
                <div>
                    <h2 class="text-base font-medium tracking-tight text-textMain-light dark:text-textMain-dark">Monitoring Konstruksi KNMP</h2>
                    <div class="flex items-center gap-2 mt-1">
                        <p class="text-[11px] sm:text-xs text-textMuted-light dark:text-textMuted-dark">
                            Menampilkan <strong>{{ count($paginatedDetails) }}</strong> dari <strong>{{ $paginator->total() }}</strong> lokasi konstruksi
                        </p>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-teal-light/10 dark:bg-teal-400/10 text-teal-light dark:text-teal-400 text-[11px] font-semibold border border-teal-light/20 dark:border-teal-400/20">
                            <i class="fa-solid fa-chart-line"></i>
                            Rata-rata Progres: {{ number_format($stats['rata_progres'] ?? 0, 2, ',', '.') }}%
                        </span>
                    </div>
                </div>
            </div>

            <form action="{{ url()->current() }}" method="GET" class="flex items-center gap-2 flex-wrap">
                @if(request('date')) <input type="hidden" name="date" value="{{ request('date') }}"> @endif
                @if(request('batch_id')) <input type="hidden" name="batch_id" value="{{ request('batch_id') }}"> @endif
                
                <label class="text-xs font-medium text-textMuted-light dark:text-textMuted-dark">Per Halaman:</label>
                <select name="per_page" onchange="this.form.submit()" class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark">
                    @foreach([20, 40, 60, 80, 100] as $opt)
                        <option value="{{ $opt }}" {{ ($perPage ?? 20) == $opt ? 'selected' : '' }}>{{ $opt }} Data</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    {{-- Bar Chart --}}
    <div class="border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden mb-6">
        @if(count($paginatedDetails) > 0)
            <div class="w-full relative min-w-0 p-4" style="min-height: {{ max(400, count($paginatedDetails) * 32) }}px;">
                <div id="chart-progres-paginated" class="w-full h-full min-w-0 overflow-hidden"></div>
            </div>
        @else
            <div class="flex flex-col items-center justify-center p-12 text-center min-h-[300px]">
                <div class="w-14 h-14 rounded-3xl bg-gray-200/50 dark:bg-gray-700/50 flex items-center justify-center text-textMuted-light dark:text-textMuted-dark mb-4">
                    <i class="fa-solid fa-chart-bar text-2xl"></i>
                </div>
                <h4 class="font-bold text-sm text-textMain-light dark:text-textMain-dark">Belum Ada Data Konstruksi</h4>
                <p class="text-xs text-textMuted-light dark:text-textMuted-dark max-w-md mt-1.5 leading-relaxed">Tidak ada data lokasi konstruksi pada filter yang dipilih.</p>
            </div>
        @endif
    </div>

    {{-- Pagination --}}
    @if(isset($paginator) && $paginator->hasPages())
    <div class="mb-6 flex flex-col sm:flex-row items-center justify-between gap-4 py-4 px-1">
        <div class="text-xs text-textMuted-light dark:text-textMuted-dark">
            Menampilkan <span class="font-medium text-textMain-light dark:text-textMain-dark">{{ $paginator->firstItem() }}</span> 
            sampai <span class="font-medium text-textMain-light dark:text-textMain-dark">{{ $paginator->lastItem() }}</span> 
            dari <span class="font-medium text-textMain-light dark:text-textMain-dark">{{ $paginator->total() }}</span> hasil
        </div>
        <div class="flex items-center gap-1">
            @if ($paginator->onFirstPage())
                <button type="button" disabled class="w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-400 opacity-30 cursor-not-allowed">
                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                </button>
            @else
                <a href="{{ $paginator->appends(request()->query())->previousPageUrl() }}" class="w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                </a>
            @endif

            @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                @if ($page == $paginator->currentPage())
                    <button type="button" class="w-8 h-8 rounded-lg font-medium text-xs flex items-center justify-center bg-teal-light text-white shadow-xs">
                        {{ $page }}
                    </button>
                @else
                    <a href="{{ $paginator->appends(request()->query())->url($page) }}" class="w-8 h-8 rounded-lg font-medium text-xs flex items-center justify-center text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->appends(request()->query())->nextPageUrl() }}" class="w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </a>
            @else
                <button type="button" disabled class="w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-400 opacity-30 cursor-not-allowed">
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </button>
            @endif
        </div>
    </div>
    @endif

    {{-- Warning Stagnant Progress --}}
    @if (count($stagnantList ?? []) > 0)
        <div class="mb-6 bg-warning/10 dark:bg-warning/5 border border-warning/20 rounded-3xl p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-full bg-warning/20 flex items-center justify-center text-warning shrink-0">
                    <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-warning dark:text-amber-500">Peringatan Risiko: Progres Stagnan</h3>
                    <p class="text-xs text-textMuted-light dark:text-textMuted-dark mt-0.5">Lokasi konstruksi di bawah ini tidak mencatatkan penambahan progres fisik sedikitpun selama lebih dari 5 hari terakhir.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($stagnantList as $item)
                    <div class="bg-white/60 dark:bg-gray-900/40 border border-warning/20 rounded-2xl p-4 flex flex-col relative overflow-hidden hover:bg-white dark:hover:bg-gray-900/80 transition-colors shadow-sm">
                        <div class="absolute right-0 top-0 bottom-0 w-1 bg-warning"></div>
                        <div class="flex justify-between items-start mb-2">
                            <div class="font-bold text-xs text-textMain-light dark:text-textMain-dark truncate pr-2" title="{{ $item['lokasi'] }}">{{ $item['lokasi'] }}</div>
                            <div class="text-[10px] font-black text-warning bg-warning/10 px-2 py-1 rounded-full shrink-0 flex items-center gap-1">
                                <i class="fa-regular fa-clock"></i> {{ $item['days_stagnant'] }} Hari
                            </div>
                        </div>

                        <div class="space-y-1.5 mt-1">
                            <div class="flex justify-between items-center text-[10px]">
                                <span class="text-textMuted-light dark:text-textMuted-dark"><i class="fa-solid fa-chart-simple w-3"></i> Stuck di Angka</span>
                                <span class="font-bold text-textMain-light dark:text-textMain-dark">{{ $item['progres'] }}%</span>
                            </div>
                            <div class="flex justify-between items-center text-[10px]">
                                <span class="text-textMuted-light dark:text-textMuted-dark"><i class="fa-solid fa-hard-hat w-3"></i> Kontraktor</span>
                                <span class="font-medium text-textMain-light dark:text-textMain-dark truncate max-w-[130px] text-right" title="{{ $item['konstruktor'] }}">{{ $item['konstruktor'] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>

{{-- ========== SCRIPTS ========== --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDark = document.documentElement.classList.contains('dark');

    // ===== BAR CHART (paginated) =====
    const paginatedData = @json($paginatedDetails ?? []);
    
    if (document.getElementById('chart-progres-paginated') && paginatedData.length > 0) {
        const categories = paginatedData.map(item => {
            let name = item.lokasi || '';
            return name.replace(/^KNMP\s+Desa\s+/i, 'Desa ').replace(/^KNMP\s+/i, '');
        });

        const barOptions = {
            series: [{
                name: 'Progres Aktual',
                data: paginatedData.map(item => item.progres)
            }],
            chart: {
                type: 'bar',
                height: Math.max(400, paginatedData.length * 32),
                toolbar: { show: false },
                background: 'transparent',
                fontFamily: 'Inter, sans-serif',
                redrawOnParentResize: true,
                redrawOnWindowResize: true
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    borderRadiusApplication: 'end',
                    horizontal: true,
                    barHeight: '70%',
                    dataLabels: { position: 'right' }
                }
            },
            dataLabels: {
                enabled: true,
                textAnchor: 'end',
                offsetX: -5,
                style: { fontSize: '10px', fontWeight: 'bold', colors: ['#FFFFFF'] },
                formatter: val => val + '%'
            },
            xaxis: {
                categories: categories,
                max: 100,
                labels: {
                    formatter: val => Math.round(val) + '%',
                    style: { colors: isDark ? '#9CA3AF' : '#6B7280', fontSize: '10px' }
                }
            },
            yaxis: {
                labels: {
                    style: { colors: isDark ? '#E5E7EB' : '#374151', fontSize: '11px', fontWeight: 500 },
                    maxWidth: 250
                }
            },
            colors: [function({ value }) {
                if (value >= 76) return '#10B981';  // Hijau: 76% - 100%
                if (value >= 36) return '#F59E0B';  // Kuning: 36% - 75%
                return '#EF4444';                   // Merah: 0% - 35%
            }],
            grid: {
                borderColor: isDark ? '#374151' : '#F3F4F6',
                strokeDashArray: 4,
                xaxis: { lines: { show: true } },
                yaxis: { lines: { show: false } }
            },
            theme: { mode: isDark ? 'dark' : 'light' },
            tooltip: {
                theme: isDark ? 'dark' : 'light',
                y: {
                    formatter: function(val, { dataPointIndex }) {
                        const item = paginatedData[dataPointIndex];
                        if (!item) return val + '%';
                        return val + '% (Rencana: ' + item.rencana + '%, Deviasi: ' + item.deviasi + '%)';
                    }
                }
            },
            legend: { show: false }
        };

        const barChart = new ApexCharts(document.querySelector('#chart-progres-paginated'), barOptions);
        barChart.render();

        // Theme observer for bar chart
        const barObserver = new MutationObserver(mutations => {
            mutations.forEach(mutation => {
                if (mutation.attributeName === 'class') {
                    const d = document.documentElement.classList.contains('dark');
                    barChart.updateOptions({
                        theme: { mode: d ? 'dark' : 'light' },
                        xaxis: { labels: { style: { colors: d ? '#9CA3AF' : '#6B7280' } } },
                        yaxis: { labels: { style: { colors: d ? '#E5E7EB' : '#374151' } } },
                        grid: { borderColor: d ? '#374151' : '#F3F4F6' }
                    });
                }
            });
        });
        barObserver.observe(document.documentElement, { attributes: true });
    }



    // Force resize after layout
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            window.dispatchEvent(new Event('resize'));
        });
    });
});
</script>
@endsection
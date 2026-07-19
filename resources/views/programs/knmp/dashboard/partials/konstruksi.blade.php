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
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-teal-light/10 text-teal-light dark:text-teal-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-chart-column text-lg sm:text-xl"></i>
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-textMain-light dark:text-textMain-dark tracking-tight">Monitoring Konstruksi KNMP</h2>
                    <p class="text-[11px] sm:text-xs text-textMuted-light dark:text-textMuted-dark mt-0.5">
                        Menampilkan <strong>{{ count($paginatedDetails) }}</strong> dari <strong>{{ $paginator->total() }}</strong> lokasi konstruksi aktif &bull; Rata-rata progres: <strong>{{ number_format($stats['rata_progres'] ?? 0, 2, ',', '.') }}%</strong>
                    </p>
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
    <div class="mb-6">
        {{ $paginator->links() }}
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

    {{-- Scatter Plot: Rencana vs Realisasi --}}
    <style>
        .apexcharts-tooltip-z,
        .apexcharts-tooltip-z-group,
        .apexcharts-tooltip-text-z-label,
        .apexcharts-tooltip-text-z-value {
            display: none !important;
        }
    </style>
    <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl overflow-hidden flex flex-col mb-6">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="font-medium text-sm flex items-center gap-2">
                    <i class="fa-solid fa-chart-line text-teal-light"></i> Scatter Plot: Rencana vs Realisasi
                </h3>
                <p class="text-xs text-textMuted-light mt-1">Titik di bawah garis diagonal menunjukkan lokasi dengan deviasi progres negatif (realisasi di bawah rencana).</p>
            </div>
            <div class="flex items-center gap-4 text-[11px]">
                <div class="flex items-center gap-1.5">
                    <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background-color: #10B981;"></span>
                    <span class="text-textMuted-light dark:text-textMuted-dark">Deviasi Positif</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background-color: #EF4444;"></span>
                    <span class="text-textMuted-light dark:text-textMuted-dark">Deviasi Negatif</span>
                </div>
            </div>
        </div>
        <div class="p-6">
            @if(count($allDetails ?? []) > 0)
                <div id="scatter-rencana-realisasi" style="min-height: 420px;"></div>
            @else
                <div class="flex flex-col items-center justify-center p-12 text-center min-h-[350px] bg-gray-50/50 dark:bg-gray-800/20 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
                    <div class="w-14 h-14 rounded-3xl bg-gray-200/50 dark:bg-gray-700/50 flex items-center justify-center text-textMuted-light dark:text-textMuted-dark mb-4">
                        <i class="fa-solid fa-chart-line text-2xl"></i>
                    </div>
                    <h4 class="font-bold text-sm text-textMain-light dark:text-textMain-dark">Plot Deviasi Tidak Tersedia</h4>
                    <p class="text-xs text-textMuted-light dark:text-textMuted-dark max-w-md mt-1.5 leading-relaxed">Tidak ada data konstruksi pada filter yang dipilih.</p>
                </div>
            @endif
        </div>
    </div>

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
                    horizontal: true,
                    barHeight: '70%',
                    dataLabels: { position: 'right' }
                }
            },
            dataLabels: {
                enabled: true,
                offsetX: 5,
                style: { fontSize: '10px', colors: [isDark ? '#D1D5DB' : '#4B5563'] },
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
                        dataLabels: { style: { colors: [d ? '#D1D5DB' : '#4B5563'] } },
                        grid: { borderColor: d ? '#374151' : '#F3F4F6' }
                    });
                }
            });
        });
        barObserver.observe(document.documentElement, { attributes: true });
    }

    // ===== SCATTER PLOT =====
    const allData = @json($allDetails ?? []);

    if (document.getElementById('scatter-rencana-realisasi') && allData.length > 0) {
        const positif = [];
        const negatif = [];

        allData.forEach(item => {
            const point = [item.rencana, item.progres, { lokasi: item.lokasi, konstruktor: item.konstruktor, deviasi: item.deviasi }];
            if (item.deviasi >= 0) { positif.push(point); } else { negatif.push(point); }
        });

        const scatterOptions = {
            series: [
                { name: 'Deviasi Positif', data: positif },
                { name: 'Deviasi Negatif', data: negatif }
            ],
            chart: {
                type: 'scatter', height: 420,
                toolbar: { show: true, tools: { download: false, selection: true, zoom: true, zoomin: true, zoomout: true, pan: true, reset: true } },
                background: 'transparent', fontFamily: 'Inter, sans-serif',
                zoom: { enabled: true, type: 'xy' }
            },
            colors: ['#10B981', '#EF4444'],
            markers: { size: 7, strokeWidth: 1, strokeColors: isDark ? '#1F2937' : '#FFFFFF', hover: { sizeOffset: 3 } },
            xaxis: {
                type: 'numeric',
                title: { text: 'Rencana (%)', style: { fontSize: '12px', fontWeight: 600, color: isDark ? '#D1D5DB' : '#4B5563' } },
                min: 0, max: 100, tickAmount: 10,
                labels: { formatter: val => Math.round(val) + '%', style: { colors: isDark ? '#9CA3AF' : '#6B7280', fontSize: '10px' } }
            },
            yaxis: {
                title: { text: 'Realisasi (%)', style: { fontSize: '12px', fontWeight: 600, color: isDark ? '#D1D5DB' : '#4B5563' } },
                min: 0, max: 100, tickAmount: 10,
                labels: { formatter: val => Math.round(val) + '%', style: { colors: isDark ? '#9CA3AF' : '#6B7280', fontSize: '10px' } }
            },
            grid: { borderColor: isDark ? '#374151' : '#F3F4F6', strokeDashArray: 4 },
            theme: { mode: isDark ? 'dark' : 'light' },
            legend: { show: false },
            tooltip: {
                enabled: true, intersect: false, shared: false,
                theme: isDark ? 'dark' : 'light',
                z: { formatter: () => '', title: '' },
                x: {
                    formatter: function(val, { seriesIndex, dataPointIndex, w }) {
                        const pointArr = w.config.series[seriesIndex].data[dataPointIndex];
                        return (pointArr && pointArr[2]) ? pointArr[2].lokasi : val;
                    }
                },
                y: {
                    title: { formatter: () => 'Progres:' },
                    formatter: val => val + '%'
                }
            }
        };

        const scatterChart = new ApexCharts(document.querySelector('#scatter-rencana-realisasi'), scatterOptions);
        scatterChart.render();

        // Diagonal line
        setTimeout(() => {
            const plotArea = document.querySelector('#scatter-rencana-realisasi .apexcharts-plot-area');
            if (plotArea) {
                const rect = plotArea.getBBox();
                const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
                line.classList.add('diagonal-ref');
                line.setAttribute('x1', rect.x);
                line.setAttribute('y1', rect.y + rect.height);
                line.setAttribute('x2', rect.x + rect.width);
                line.setAttribute('y2', rect.y);
                line.setAttribute('stroke', isDark ? '#4B5563' : '#D1D5DB');
                line.setAttribute('stroke-width', '1.5');
                line.setAttribute('stroke-dasharray', '6,4');
                line.setAttribute('opacity', '0.8');
                line.setAttribute('pointer-events', 'none');
                plotArea.appendChild(line);
            }
        }, 500);

        // Theme observer for scatter
        const scatterObserver = new MutationObserver(mutations => {
            mutations.forEach(mutation => {
                if (mutation.attributeName === 'class') {
                    const d = document.documentElement.classList.contains('dark');
                    scatterChart.updateOptions({
                        theme: { mode: d ? 'dark' : 'light' },
                        markers: { strokeColors: d ? '#1F2937' : '#FFFFFF' },
                        xaxis: { title: { style: { color: d ? '#D1D5DB' : '#4B5563' } }, labels: { style: { colors: d ? '#9CA3AF' : '#6B7280' } } },
                        yaxis: { title: { style: { color: d ? '#D1D5DB' : '#4B5563' } }, labels: { style: { colors: d ? '#9CA3AF' : '#6B7280' } } },
                        grid: { borderColor: d ? '#374151' : '#F3F4F6' }
                    });
                    setTimeout(() => {
                        const plotArea = document.querySelector('#scatter-rencana-realisasi .apexcharts-plot-area');
                        if (plotArea) {
                            const oldLine = plotArea.querySelector('line.diagonal-ref');
                            if (oldLine) oldLine.remove();
                            const rect = plotArea.getBBox();
                            const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
                            line.classList.add('diagonal-ref');
                            line.setAttribute('x1', rect.x);
                            line.setAttribute('y1', rect.y + rect.height);
                            line.setAttribute('x2', rect.x + rect.width);
                            line.setAttribute('y2', rect.y);
                            line.setAttribute('stroke', d ? '#4B5563' : '#D1D5DB');
                            line.setAttribute('stroke-width', '1.5');
                            line.setAttribute('stroke-dasharray', '6,4');
                            line.setAttribute('opacity', '0.8');
                            line.setAttribute('pointer-events', 'none');
                            plotArea.appendChild(line);
                        }
                    }, 300);
                }
            });
        });
        scatterObserver.observe(document.documentElement, { attributes: true });
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
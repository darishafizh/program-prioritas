@extends('layouts.app')

@section('title', 'Bioflok - Evaluasi Kinerja Produksi')

@section('content')
    <div x-data>
        {{-- Header & Filter Sejajar --}}
        <div class="mb-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h2 class="text-xl font-semibold tracking-tight">Evaluasi Kinerja Produksi Bioflok</h2>
                <p class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1">Analisis mendalam efisiensi pakan (FCR), tingkat kelangsungan hidup ikan (SR), dan deviasi target panen.</p>
            </div>

            {{-- Filter Bar --}}
            <form action="{{ url()->current() }}" method="GET" class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                <div class="relative">
                    <select name="bulan" onchange="this.form.submit()"
                        class="appearance-none bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-xl px-4 py-2 pr-10 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-teal-light text-textMain-light dark:text-textMain-dark">
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
                    <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                </div>

                <button type="button" onclick="window.print()" class="px-4 py-2 bg-danger/10 border border-danger/20 text-danger rounded-xl text-xs font-medium hover:bg-danger/20 transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-file-pdf"></i> PDF
                </button>
            </form>
        </div>

        {{-- 4 KPI Cards (Responsive Grid 1 -> 2 -> 4) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            {{-- Capaian Volume Panen --}}
            <x-stat-card
                title="Capaian Panen"
                icon="fa-solid fa-boxes-stacked"
                icon-color="text-teal-light dark:text-teal-400"
                icon-bg="bg-teal-light/10 dark:bg-teal-light/20"
                value="{{ $stats['total_realisasi_panen'] ?? 142.8 }}"
                unit="/ {{ $stats['total_target_panen'] ?? 150 }} Ton"
            >
                <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2 mt-2">
                    <div class="bg-teal-light dark:bg-teal-400 h-2 rounded-full" style="width: {{ $stats['persentase_capaian'] ?? 95.2 }}%"></div>
                </div>
            </x-stat-card>

            {{-- Survival Rate (SR) --}}
            <x-stat-card
                title="Survival Rate (SR)"
                icon="fa-solid fa-heart-pulse"
                icon-color="text-success dark:text-emerald-400"
                icon-bg="bg-success/10 dark:bg-success/20"
                value="{{ $stats['survival_rate_rata'] ?? 86.5 }}"
                unit="%"
                description="<span class='text-textMuted-light inline-flex items-center gap-1'><i class='fa-solid fa-check text-success'></i> Di atas standar baku (&gt;80%)</span>"
            />

            {{-- Feed Conversion Ratio (FCR) --}}
            <x-stat-card
                title="Rata-Rata FCR"
                icon="fa-solid fa-scale-balanced"
                icon-color="text-warning dark:text-amber-500"
                icon-bg="bg-warning/10 dark:bg-amber-400/20"
                value="{{ $stats['fcr_rata'] ?? 1.25 }}"
                description="Efisiensi pakan optimal (Standar: 1.1 - 1.4)"
            />

            {{-- Status Capaian Lokasi --}}
            <x-stat-card
                title="Performa Lokasi"
                icon="fa-solid fa-award"
                icon-color="text-teal-light dark:text-teal-400"
                icon-bg="bg-teal-light/10 dark:bg-teal-light/20"
                value="{{ $stats['lokasi_diatas_target'] ?? 24 }}"
                unit="Sesuai / Melampaui"
                description="<span class='text-danger inline-flex items-center gap-1'><i class='fa-solid fa-circle-exclamation'></i> {{ $stats['lokasi_dibawah_target'] ?? 4 }} Lokasi under-performing</span>"
            />
        </div>

        {{-- Grafik Komparasi Target vs Realisasi Produksi --}}
        <div class="mb-6 bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6">
            <h3 class="text-sm font-medium text-textMain-light dark:text-textMain-dark mb-1">Perbandingan Target vs Realisasi Produksi Bioflok per KDKMP (Ton)</h3>
            <p class="text-xs text-textMuted-light mb-6">Grafik evaluasi volume hasil panen terhadap target rencana pada siklus berjalan.</p>
            <div id="evaluasiProduksiChart" class="w-full h-64 sm:h-72"></div>
        </div>

        {{-- Table Evaluasi Kinerja Produksi KDKMP --}}
        <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl overflow-hidden pb-2 flex flex-col">
            <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-medium text-textMain-light dark:text-textMain-dark">Matriks Evaluasi Produksi & Teknis KDKMP</h3>
                    <p class="text-xs text-textMuted-light dark:text-textMuted-dark mt-1">Rincian parameter evaluasi budidaya dan rekomendasi perbaikan.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs whitespace-nowrap">
                    <thead class="bg-white dark:bg-gray-900 text-[11px] font-normal uppercase text-textMuted-light dark:text-textMuted-dark border-b border-gray-100 dark:border-gray-800">
                        <tr>
                            <th class="py-4 px-6 w-12 text-center">No</th>
                            <th class="py-4 px-6">KDKMP & Wilayah</th>
                            <th class="py-4 px-6 text-center">Target</th>
                            <th class="py-4 px-6 text-center">Realisasi</th>
                            <th class="py-4 px-6 text-center">Capaian</th>
                            <th class="py-4 px-6 text-center">SR / FCR</th>
                            <th class="py-4 px-6 text-center">Status Evaluasi</th>
                            <th class="py-4 px-6">Rekomendasi / Tindak Lanjut</th>
                            <th class="py-4 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-xs font-medium">
                        @forelse($listEvaluasiProduksi as $row)
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-800/40 transition-colors">
                            <td class="py-4 px-6 text-center text-textMuted-light">{{ $row['no'] }}</td>
                            <td class="py-4 px-6">
                                <div class="font-semibold text-textMain-light dark:text-white">{{ $row['kdkmp'] }}</div>
                                <div class="text-[11px] text-textMuted-light mt-0.5 truncate"><i class="fa-solid fa-location-dot mr-1"></i>{{ $row['lokasi'] }}</div>
                            </td>
                            <td class="py-4 px-6 text-center font-semibold text-textMuted-light whitespace-nowrap">{{ $row['target_ton'] }} Ton</td>
                            <td class="py-4 px-6 text-center font-bold text-textMain-light dark:text-white whitespace-nowrap">{{ $row['realisasi_ton'] }} Ton</td>
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                @if($row['capaian_persen'] >= 100)
                                    <span class="text-success font-bold">{{ $row['capaian_persen'] }}%</span>
                                @elseif($row['capaian_persen'] >= 90)
                                    <span class="text-textMain-light font-bold">{{ $row['capaian_persen'] }}%</span>
                                @else
                                    <span class="text-danger font-bold">{{ $row['capaian_persen'] }}%</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                <div class="font-bold text-textMain-light dark:text-white">SR: {{ $row['survival_rate'] }}</div>
                                <div class="text-[11px] text-textMuted-light">FCR: {{ $row['fcr'] }}</div>
                            </td>
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full text-[11px] font-semibold border {{ $row['badge_class'] }}">
                                    {{ $row['status_evaluasi'] }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-textMuted-light leading-relaxed max-w-xs">{{ $row['rekomendasi'] }}</td>
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                <button class="px-4 py-2 rounded-xl bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-all text-xs font-medium">
                                    Audit Teknis
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-textMuted-light">Belum ada data evaluasi produksi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartData = @json($chartEvaluasi ?? []);
            
            const options = {
                series: [
                    { name: 'Target Panen (Ton)', data: chartData.target || [] },
                    { name: 'Realisasi Panen (Ton)', data: chartData.realisasi || [] }
                ],
                chart: {
                    type: 'bar',
                    height: window.innerWidth < 640 ? 240 : 280,
                    toolbar: { show: false }
                },
                colors: ['#94a3b8', '#0d9488'],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: window.innerWidth < 640 ? '60%' : '45%',
                        borderRadius: 3
                    },
                },
                dataLabels: { enabled: false },
                stroke: { show: true, width: 2, colors: ['transparent'] },
                xaxis: {
                    categories: chartData.categories || [],
                    labels: { style: { fontSize: window.innerWidth < 640 ? '10px' : '11px', fontWeight: 500 } }
                },
                yaxis: {
                    title: { text: 'Ton', style: { fontSize: '11px' } }
                },
                fill: { opacity: 1 },
                tooltip: {
                    y: { formatter: val => val + ' Ton' }
                },
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4
                }
            };

            const chart = new ApexCharts(document.querySelector("#evaluasiProduksiChart"), options);
            chart.render();
        });
    </script>
@endsection

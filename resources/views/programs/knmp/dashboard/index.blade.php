@extends('layouts.app')

@section('title', 'KNMP - Dashboard Analisis Eksekutif')

@section('content')
    <div x-data="dashboardTableManager()">
        <!-- Header & Global Filters (Row 1) -->
        <div class="mb-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <div class="flex items-center gap-3 flex-wrap">
                    <h2 class="text-xl font-semibold tracking-tight">Dashboard KNMP</h2>
                    @if(isset($stats['last_updated']))
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-light/10 dark:bg-teal-400/10 border border-teal-light/20 dark:border-teal-400/20 text-teal-light dark:text-teal-300 text-xs font-medium shadow-xs">
                        <span class="w-2 h-2 rounded-full bg-teal-light dark:bg-teal-400 animate-pulse shrink-0"></span>
                        <i class="fa-regular fa-clock text-[11px]"></i>
                        <span>Update Data Terakhir: <strong class="font-semibold text-textMain-light dark:text-white">{{ $stats['last_updated'] }}</strong></span>
                    </div>
                    @endif
                </div>
                <p class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1.5">Ringkasan Eksekutif &
                    Pantauan Konstruksi Kampung Nelayan Merah Putih</p>
            </div>

            <!-- Filters -->
            <form id="dashboardFilterForm" action="{{ url()->current() }}" method="GET"
                class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                <div class="relative">
                    <select name="batch_id" onchange="this.form.submit()"
                        class="appearance-none bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-xl px-4 py-2 pr-10 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-teal-light focus:border-teal-light text-textMain-light dark:text-textMain-dark ">
                        <option value="">Semua Tahap</option>
                        @foreach ($stats['filter_batches'] ?? [] as $batch)
                            <option value="{{ $batch['id'] }}" {{ request('batch_id') == $batch['id'] ? 'selected' : '' }}>
                                {{ $batch['name'] }}</option>
                        @endforeach
                    </select>
                    <i
                        class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                </div>

                <div
                    class="relative flex items-center bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-xl px-4 py-2 text-xs font-medium focus-within:ring-2 focus-within:ring-teal-light focus-within:border-teal-light">
                    <i class="fa-regular fa-calendar text-gray-400 mr-2"></i>
                    <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()"
                        class="bg-transparent border-none outline-none text-textMain-light dark:text-textMain-dark w-32">
                </div>

                <button type="button" @click="isPdfModalOpen = true"
                    class="px-4 py-2 bg-danger/10 dark:bg-danger/20 border border-danger/20 text-danger rounded-xl text-xs font-medium hover:bg-danger/20 dark:hover:bg-danger/30 transition-colors flex items-center gap-2 shrink-0">
                    <i class="fa-solid fa-file-pdf"></i> PDF
                </button>
            </form>
        </div>

        <!-- Narrative Storytelling Block -->
        <div
            class="mb-6 relative overflow-hidden rounded-3xl bg-gradient-to-br from-bgSurface-light to-blue-50 dark:from-bgSurface-dark dark:to-blue-900/10 border border-teal-light/20 dark:border-teal-light/10 p-6 sm:p-8">
            <div class="absolute top-0 right-0 p-8 opacity-5 dark:opacity-10 pointer-events-none">
                <i class="fa-solid fa-quote-right text-9xl text-teal-light"></i>
            </div>
            <div class="relative z-10">
                <div
                    class="flex items-center gap-2 text-teal-light dark:text-teal-400 font-medium text-xs tracking-widest uppercase mb-3">
                    <span class="w-2 h-2 rounded-full bg-teal-light animate-pulse"></span>Analisis Kinerja Bulan Ini
                </div>
                <p class="text-xs text-textMain-light dark:text-textMain-dark leading-relaxed font-medium">
                    {!! $stats['narasi'] ?? '' !!}
                </p>
            </div>
        </div>

        <!-- KPI Cards (Row 2) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <x-stat-card title="Total Lokasi" icon="fa-solid fa-house-chimney-window"
                icon-color="text-teal-light dark:text-teal-400" icon-bg="bg-teal-light/10 dark:bg-teal-light/20"
                value="{{ $stats['total_lokasi'] ?? 0 }}" unit="Lokasi"
                description="<span class='text-success font-medium inline-flex items-center gap-1'><i class='fa-solid fa-arrow-trend-up'></i> +12 Lokasi dari tahun lalu</span>" />

            <x-stat-card title="Rata-Rata Progres" icon="fa-solid fa-chart-pie"
                icon-color="text-teal-light dark:text-teal-400" icon-bg="bg-teal-light/10 dark:bg-teal-400/20"
                value="{{ number_format($stats['rata_progres'] ?? 0, 2, ',', '.') }}" unit="%">
                <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2 mt-2">
                    <div class="bg-teal-light dark:bg-teal-400 h-2 rounded-full"
                        style="width: {{ $stats['rata_progres'] ?? 0 }}%"></div>
                </div>
            </x-stat-card>

            <x-stat-card title="Total Selesai" icon="fa-solid fa-check-double"
                icon-color="text-success dark:text-emerald-400" icon-bg="bg-success/10 dark:bg-success/20"
                value="{{ $stats['total_selesai'] ?? 0 }}" unit="Lokasi"
                description="<span class='text-success font-medium inline-flex items-center gap-1'><i class='fa-solid fa-arrow-trend-up'></i> Telah serah terima</span>" />

            <x-stat-card title="Dalam Pembangunan" icon="fa-solid fa-person-digging"
                icon-color="text-warning dark:text-amber-500" icon-bg="bg-warning/10 dark:bg-amber-400/20"
                value="{{ $stats['dalam_pembangunan'] ?? 0 }}" unit="Lokasi" description="Tahap konstruksi aktif" />
        </div>

        @if(($stats['dalam_pembangunan'] ?? 0) == 0)
            <div class="mb-6 bg-blue-50/80 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 rounded-3xl p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-xs">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-500/10 dark:bg-blue-400/10 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 text-xl font-bold">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-textMain-light dark:text-textMain-dark">
                            @if(($stats['total_selesai'] ?? 0) > 0 && ($stats['total_selesai'] ?? 0) == ($stats['total_lokasi'] ?? 0))
                                Seluruh Proyek pada Tahap Ini Telah Rampung (`Serah Terima`)
                            @elseif(($stats['total_lokasi'] ?? 0) > 0)
                                Lokasi Belum Memasuki Tahap Konstruksi Fisik
                            @else
                                Belum Terdapat Data pada Filter Tahap yang Dipilih
                            @endif
                        </h3>
                        <p class="text-xs text-textMuted-light dark:text-textMuted-dark mt-1 leading-relaxed">
                            @if(($stats['total_selesai'] ?? 0) > 0 && ($stats['total_selesai'] ?? 0) == ($stats['total_lokasi'] ?? 0))
                                Sebanyak <strong>{{ $stats['total_selesai'] }} lokasi KNMP</strong> pada filter/tahap ini telah berhasil diselesaikan 100% dan diserahterimakan. Oleh karena itu, grafik pemantauan dan analisis progres harian konstruksi aktif di bawah ini ditutup/tidak menampilkan data konstruksi berjalan.
                            @elseif(($stats['total_lokasi'] ?? 0) > 0)
                                Sebanyak <strong>{{ $stats['total_lokasi'] }} lokasi KNMP</strong> pada filter/tahap ini saat ini masih dalam tahapan pra-konstruksi (<em>Usulan, Survei, DED, atau Lelang</em>). Seluruh grafik pemantauan konstruksi baru akan aktif setelah lokasi resmi beralih ke tahap <strong>Konstruksi</strong>.
                            @else
                                Silakan pilih tahap/batch lain atau atur ulang filter pencarian Anda untuk melihat data pemantauan konstruksi Kampung Nelayan Merah Putih.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- 2 Columns: Top 10, Bottom 10 (Row 3) -->
        <div class="grid grid-cols-2 gap-6 mb-6">
            <!-- Top 10 Progress -->
            <div
                class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 flex flex-col min-w-0 overflow-hidden">
                <div class="flex items-center justify-between mb-4">
                    <h3
                        class="font-medium text-xs uppercase tracking-wider text-textMuted-light dark:text-textMuted-dark flex items-center gap-2">
                        <i class="fa-solid fa-arrow-up-right-dots text-success"></i> Top 10 KNMP Tertinggi
                    </h3>
                </div>
                @if(($stats['dalam_pembangunan'] ?? 0) > 0)
                    <div class="flex-1 w-full relative min-h-[300px] min-w-0">
                        <div id="chart-top10" class="w-full h-full min-w-0 overflow-hidden"></div>
                    </div>
                @else
                    <div class="flex-1 w-full flex flex-col items-center justify-center p-8 text-center min-h-[260px] bg-gray-50/50 dark:bg-gray-800/20 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
                        <div class="w-12 h-12 rounded-2xl bg-gray-200/50 dark:bg-gray-700/50 flex items-center justify-center text-textMuted-light dark:text-textMuted-dark mb-3">
                            <i class="fa-solid fa-chart-bar text-xl"></i>
                        </div>
                        <h4 class="font-semibold text-xs text-textMain-light dark:text-textMain-dark">Grafik Top 10 Tidak Ditampilkan</h4>
                        <p class="text-[11px] text-textMuted-light dark:text-textMuted-dark max-w-xs mt-1.5 leading-relaxed">
                            @if(($stats['total_selesai'] ?? 0) > 0 && ($stats['total_selesai'] ?? 0) == ($stats['total_lokasi'] ?? 0))
                                Seluruh lokasi pada tahap ini telah selesai 100% (`Serah Terima`).
                            @elseif(($stats['total_lokasi'] ?? 0) > 0)
                                Lokasi pada tahap ini belum memasuki masa konstruksi aktif.
                            @else
                                Belum terdapat data lokasi pada tahap/filter yang dipilih.
                            @endif
                        </p>
                    </div>
                @endif
            </div>

            <!-- Bottom 10 Progress -->
            <div
                class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 flex flex-col min-w-0 overflow-hidden">
                <div class="flex items-center justify-between mb-4">
                    <h3
                        class="font-medium text-xs uppercase tracking-wider text-textMuted-light dark:text-textMuted-dark flex items-center gap-2">
                        <i class="fa-solid fa-arrow-down-right-dots text-danger"></i> Top 10 KNMP Terendah
                    </h3>
                </div>
                @if(($stats['dalam_pembangunan'] ?? 0) > 0)
                    <div class="flex-1 w-full relative min-h-[300px] min-w-0">
                        <div id="chart-bottom10" class="w-full h-full min-w-0 overflow-hidden"></div>
                    </div>
                @else
                    <div class="flex-1 w-full flex flex-col items-center justify-center p-8 text-center min-h-[260px] bg-gray-50/50 dark:bg-gray-800/20 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
                        <div class="w-12 h-12 rounded-2xl bg-gray-200/50 dark:bg-gray-700/50 flex items-center justify-center text-textMuted-light dark:text-textMuted-dark mb-3">
                            <i class="fa-solid fa-chart-bar text-xl"></i>
                        </div>
                        <h4 class="font-semibold text-xs text-textMain-light dark:text-textMain-dark">Grafik Bottom 10 Tidak Ditampilkan</h4>
                        <p class="text-[11px] text-textMuted-light dark:text-textMuted-dark max-w-xs mt-1.5 leading-relaxed">
                            @if(($stats['total_selesai'] ?? 0) > 0 && ($stats['total_selesai'] ?? 0) == ($stats['total_lokasi'] ?? 0))
                                Seluruh lokasi pada tahap ini telah selesai 100% (`Serah Terima`).
                            @elseif(($stats['total_lokasi'] ?? 0) > 0)
                                Lokasi pada tahap ini belum memasuki masa konstruksi aktif.
                            @else
                                Belum terdapat data lokasi pada tahap/filter yang dipilih.
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Warning Stagnant Progress -->
        @if (count($stats['stagnant_list'] ?? []) > 0)
            <div class="mb-6 bg-warning/10 dark:bg-warning/5 border border-warning/20 rounded-3xl p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div
                        class="w-10 h-10 rounded-full bg-warning/20 flex items-center justify-center text-warning shrink-0">
                        <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-warning dark:text-amber-500">Peringatan Risiko: Progres Stagnan
                        </h3>
                        <p class="text-xs text-textMuted-light dark:text-textMuted-dark mt-0.5">Lokasi konstruksi di bawah
                            ini tidak mencatatkan penambahan progres fisik sedikitpun selama lebih dari 5 hari terakhir.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach ($stats['stagnant_list'] as $item)
                        <div
                            class="bg-white/60 dark:bg-gray-900/40 border border-warning/20 rounded-2xl p-4 flex flex-col relative overflow-hidden hover:bg-white dark:hover:bg-gray-900/80 transition-colors shadow-sm">
                            <div class="absolute right-0 top-0 bottom-0 w-1 bg-warning"></div>
                            <div class="flex justify-between items-start mb-2">
                                <div class="font-bold text-xs text-textMain-light dark:text-textMain-dark truncate pr-2"
                                    title="{{ $item['lokasi'] }}">{{ $item['lokasi'] }}</div>
                                <div
                                    class="text-[10px] font-black text-warning bg-warning/10 px-2 py-1 rounded-full shrink-0 flex items-center gap-1">
                                    <i class="fa-regular fa-clock"></i> {{ $item['days_stagnant'] }} Hari
                                </div>
                            </div>

                            <div class="space-y-1.5 mt-1">
                                <div class="flex justify-between items-center text-[10px]">
                                    <span class="text-textMuted-light dark:text-textMuted-dark"><i
                                            class="fa-solid fa-chart-simple w-3"></i> Stuck di Angka</span>
                                    <span
                                        class="font-bold text-textMain-light dark:text-textMain-dark">{{ $item['progres'] }}%</span>
                                </div>
                                <div class="flex justify-between items-center text-[10px]">
                                    <span class="text-textMuted-light dark:text-textMuted-dark"><i
                                            class="fa-solid fa-hard-hat w-3"></i> Kontraktor</span>
                                    <span
                                        class="font-medium text-textMain-light dark:text-textMain-dark truncate max-w-[130px] text-right"
                                        title="{{ $item['konstruktor'] }}">{{ $item['konstruktor'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Map Distribution (Row 4) -->
        <div
            class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl overflow-hidden mb-6 flex flex-col">
            <div
                class="p-6 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-sm font-bold flex items-center gap-2">
                        <i class="fa-solid fa-map text-teal-light dark:text-teal-400"></i> Sebaran Lokasi KNMP
                    </h3>
                    <p class="text-xs text-textMuted-light dark:text-textMuted-dark mt-1">Peta interaktif persebaran
                        pembangunan Kampung Nelayan Merah Putih di seluruh wilayah Indonesia.</p>
                </div>
            </div>

            <div id="knmpMapContainer">
                @if(($stats['dalam_pembangunan'] ?? 0) > 0)
                    <div id="knmpMap" class="w-full h-[500px] z-0 bg-gray-100 dark:bg-gray-900"
                        style="height: 500px; width: 100%; min-height: 500px;"></div>
                @else
                    <div class="w-full h-[380px] flex flex-col items-center justify-center p-8 text-center bg-gray-50/60 dark:bg-gray-900/40 border-b border-gray-100 dark:border-gray-800">
                        <div class="w-14 h-14 rounded-3xl bg-teal-light/10 dark:bg-teal-400/10 flex items-center justify-center text-teal-light dark:text-teal-400 mb-4 shadow-sm">
                            <i class="fa-solid fa-map-location-dot text-2xl"></i>
                        </div>
                        <h4 class="font-bold text-sm text-textMain-light dark:text-textMain-dark">Sebaran Peta Konstruksi Tidak Ditampilkan</h4>
                        <p class="text-xs text-textMuted-light dark:text-textMuted-dark max-w-md mt-1.5 leading-relaxed">
                            @if(($stats['total_selesai'] ?? 0) > 0 && ($stats['total_selesai'] ?? 0) == ($stats['total_lokasi'] ?? 0))
                                Semua proyek KNMP pada filter yang dipilih telah selesai dibangun (`Serah Terima`). Saat ini tidak ada lokasi dengan status konstruksi aktif di lapangan.
                            @elseif(($stats['total_lokasi'] ?? 0) > 0)
                                Sebanyak <strong>{{ $stats['total_lokasi'] }} lokasi</strong> pada filter ini masih berstatus pra-konstruksi (<em>Usulan / Survei / DED / Lelang</em>), sehingga koordinat progres konstruksi belum dipetakan.
                            @else
                                Belum terdapat titik lokasi KNMP pada filter yang dipilih.
                            @endif
                        </p>
                    </div>
                @endif
            </div>

            <div class="grid gap-3 sm:gap-4 p-6 bg-gray-50/50 dark:bg-gray-800/20 border-t border-gray-100 dark:border-gray-800 overflow-x-auto"
                style="grid-template-columns: repeat(6, minmax(130px, 1fr));">
                <div
                    class="p-3 sm:p-4 bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 flex items-center justify-between hover:border-teal-light/50 transition-all shadow-sm">
                    <div>
                        <div class="text-xs text-textMuted-light dark:text-textMuted-dark font-medium">Sumatera</div>
                        <div class="font-bold text-sm mt-0.5">{{ $stats['islands']['sumatera'] ?? 0 }} <span
                                class="text-[10px] font-normal text-textMuted-light">Lokasi</span></div>
                    </div>
                    <div
                        class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-teal-light/10 dark:bg-teal-400/10 text-teal-light dark:text-teal-400 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-location-dot text-xs"></i>
                    </div>
                </div>

                <div
                    class="p-3 sm:p-4 bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 flex items-center justify-between hover:border-teal-light/50 transition-all shadow-sm">
                    <div>
                        <div class="text-xs text-textMuted-light dark:text-textMuted-dark font-medium">Jawa</div>
                        <div class="font-bold text-sm mt-0.5">{{ $stats['islands']['jawa'] ?? 0 }} <span
                                class="text-[10px] font-normal text-textMuted-light">Lokasi</span></div>
                    </div>
                    <div
                        class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-teal-light/10 dark:bg-teal-400/10 text-teal-light dark:text-teal-400 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-location-dot text-xs"></i>
                    </div>
                </div>

                <div
                    class="p-3 sm:p-4 bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 flex items-center justify-between hover:border-teal-light/50 transition-all shadow-sm">
                    <div>
                        <div class="text-xs text-textMuted-light dark:text-textMuted-dark font-medium">Kalimantan</div>
                        <div class="font-bold text-sm mt-0.5">{{ $stats['islands']['kalimantan'] ?? 0 }} <span
                                class="text-[10px] font-normal text-textMuted-light">Lokasi</span></div>
                    </div>
                    <div
                        class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-teal-light/10 dark:bg-teal-400/10 text-teal-light dark:text-teal-400 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-location-dot text-xs"></i>
                    </div>
                </div>

                <div
                    class="p-3 sm:p-4 bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 flex items-center justify-between hover:border-teal-light/50 transition-all shadow-sm">
                    <div>
                        <div class="text-xs text-textMuted-light dark:text-textMuted-dark font-medium">Sulawesi</div>
                        <div class="font-bold text-sm mt-0.5">{{ $stats['islands']['sulawesi'] ?? 0 }} <span
                                class="text-[10px] font-normal text-textMuted-light">Lokasi</span></div>
                    </div>
                    <div
                        class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-teal-light/10 dark:bg-teal-400/10 text-teal-light dark:text-teal-400 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-location-dot text-xs"></i>
                    </div>
                </div>

                <div
                    class="p-3 sm:p-4 bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 flex items-center justify-between hover:border-teal-light/50 transition-all shadow-sm">
                    <div>
                        <div class="text-xs text-textMuted-light dark:text-textMuted-dark font-medium truncate"
                            title="Bali dan Nusa Tenggara">Bali & Nusa Tenggara</div>
                        <div class="font-bold text-sm mt-0.5">{{ $stats['islands']['bali_nusra'] ?? 0 }} <span
                                class="text-[10px] font-normal text-textMuted-light">Lokasi</span></div>
                    </div>
                    <div
                        class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-teal-light/10 dark:bg-teal-400/10 text-teal-light dark:text-teal-400 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-location-dot text-xs"></i>
                    </div>
                </div>

                <div
                    class="p-3 sm:p-4 bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 flex items-center justify-between hover:border-teal-light/50 transition-all shadow-sm">
                    <div>
                        <div class="text-xs text-textMuted-light dark:text-textMuted-dark font-medium truncate"
                            title="Maluku dan Papua">Maluku & Papua</div>
                        <div class="font-bold text-sm mt-0.5">{{ $stats['islands']['maluku_papua'] ?? 0 }} <span
                                class="text-[10px] font-normal text-textMuted-light">Lokasi</span></div>
                    </div>
                    <div
                        class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-teal-light/10 dark:bg-teal-400/10 text-teal-light dark:text-teal-400 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-location-dot text-xs"></i>
                    </div>
                </div>
            </div>
        </div>

        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize map centered on Indonesia
                var map = L.map('knmpMap').setView([-0.7893, 113.9213], 5);

                var lightTileUrl = 'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png';
                var darkTileUrl = 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png';

                var isDark = document.documentElement.classList.contains('dark');
                var tileLayer = L.tileLayer(isDark ? darkTileUrl : lightTileUrl, {
                    attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
                    subdomains: 'abcd',
                    maxZoom: 19
                }).addTo(map);

                var observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.attributeName === 'class') {
                            var newIsDark = document.documentElement.classList.contains('dark');
                            tileLayer.setUrl(newIsDark ? darkTileUrl : lightTileUrl);
                        }
                    });
                });
                observer.observe(document.documentElement, {
                    attributes: true
                });

                var locations = @json($stats['map_locations'] ?? []);

                locations.forEach(function(loc) {
                    if (loc.latitude && loc.longitude) {
                        var color = loc.status === 'Hub' ? '#0d9488' :
                            '#f59e0b'; // teal for Hub, amber for Penyangga

                        var markerHtmlStyles = `
                    background-color: ${color};
                    width: 1rem;
                    height: 1rem;
                    display: block;
                    left: -0.5rem;
                    top: -0.5rem;
                    position: relative;
                    border-radius: 3rem 3rem 0;
                    transform: rotate(45deg);
                    border: 1px solid #FFFFFF;
                    box-shadow: 0 1px 3px rgba(0,0,0,0.3)
                `;

                        var icon = L.divIcon({
                            className: "my-custom-pin",
                            iconAnchor: [0, 12],
                            labelAnchor: [-6, 0],
                            popupAnchor: [0, -20],
                            html: `<span style="${markerHtmlStyles}"></span>`
                        });

                        L.marker([loc.latitude, loc.longitude], {
                                icon: icon
                            })
                            .addTo(map)
                            .bindPopup(`<b>${loc.nama}</b><br/>Status: ${loc.status || 'Penyangga'}`);
                    }
                });

                setTimeout(function() {
                    map.invalidateSize();
                }, 300);
            });
        </script>

        <!-- Scatter Plot: Rencana vs Realisasi (Row 5) -->
        <style>
            .apexcharts-tooltip-z,
            .apexcharts-tooltip-z-group,
            .apexcharts-tooltip-text-z-label,
            .apexcharts-tooltip-text-z-value {
                display: none !important;
            }
        </style>
        <div
            class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl overflow-hidden flex flex-col">
            <!-- Header -->
            <div
                class="p-6 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="font-medium text-sm flex items-center gap-2">
                        <i class="fa-solid fa-chart-line text-teal-light"></i> Scatter Plot: Rencana vs Realisasi
                    </h3>
                    <p class="text-xs text-textMuted-light mt-1">Titik di bawah garis diagonal menunjukkan lokasi dengan
                        deviasi progres negatif (realisasi di bawah rencana).</p>
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
                    <div class="w-px h-4 bg-gray-200 dark:bg-gray-700 hidden sm:block"></div>
                    <a href="{{ route('program.operasional', ['stage' => 'konstruksi']) }}" 
                       class="flex items-center gap-1.5 px-3 py-1.5 bg-teal-light/10 hover:bg-teal-light/20 text-teal-light rounded-lg transition-colors font-medium">
                        Detail Progres
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>
            <!-- Chart -->
            <div class="p-6">
                @if(($stats['dalam_pembangunan'] ?? 0) > 0)
                    <div id="scatter-rencana-realisasi" style="min-height: 420px;"></div>
                @else
                    <div class="w-full flex flex-col items-center justify-center p-12 text-center min-h-[350px] bg-gray-50/50 dark:bg-gray-800/20 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
                        <div class="w-14 h-14 rounded-3xl bg-gray-200/50 dark:bg-gray-700/50 flex items-center justify-center text-textMuted-light dark:text-textMuted-dark mb-4">
                            <i class="fa-solid fa-chart-line text-2xl"></i>
                        </div>
                        <h4 class="font-bold text-sm text-textMain-light dark:text-textMain-dark">Plot Deviasi Rencana vs Realisasi Tidak Tersedia</h4>
                        <p class="text-xs text-textMuted-light dark:text-textMuted-dark max-w-md mt-1.5 leading-relaxed">
                            @if(($stats['total_selesai'] ?? 0) > 0 && ($stats['total_selesai'] ?? 0) == ($stats['total_lokasi'] ?? 0))
                                Karena seluruh proyek pada filter/tahap ini telah rampung 100% dan diserahterimakan, analisis perbandingan kurva rencana terhadap realisasi harian konstruksi sudah ditutup.
                            @elseif(($stats['total_lokasi'] ?? 0) > 0)
                                Proyek pada filter/tahap ini belum memasuki masa pelaksanaan konstruksi fisik di lapangan, sehingga bobot rencana dan realisasi mingguan belum mulai dicatat.
                            @else
                                Tidak ada data konstruksi pada filter/tahap yang dipilih.
                            @endif
                        </p>
                    </div>
                @endif
            </div>

        </div>

        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            // ApexCharts initialization for Top 10 and Bottom 10
            document.addEventListener('DOMContentLoaded', function() {
                const top10Data = @json($stats['top10'] ?? []);
                const bottom10Data = @json($stats['bottom10'] ?? []);
                const isDark = document.documentElement.classList.contains('dark');

                function renderBarChart(elementId, data, color) {
                    if (!document.getElementById(elementId) || data.length === 0) return;

                    // Bersihkan prefix "KNMP Desa " atau "KNMP " agar label di Y-Axis lebih ringkas dan pas di dalam card
                    const categories = data.map(item => {
                        let name = item.lokasi || '';
                        return name.replace(/^KNMP\s+Desa\s+/i, 'Desa ').replace(/^KNMP\s+/i, '');
                    });
                    const progresData = data.map(item => item.progres);
                    const rencanaData = data.map(item => item.rencana);

                    const options = {
                        series: [{
                            name: 'Progres Aktual',
                            data: progresData
                        }],
                        chart: {
                            type: 'bar',
                            width: '100%',
                            height: Math.max(300, data.length * 36),
                            toolbar: {
                                show: false
                            },
                            background: 'transparent',
                            fontFamily: 'Inter, sans-serif',
                            redrawOnParentResize: true,
                            redrawOnWindowResize: true
                        },
                        plotOptions: {
                            bar: {
                                horizontal: true,
                                dataLabels: {
                                    position: 'top',
                                },
                                borderRadius: 3,
                                barHeight: '65%'
                            }
                        },
                        colors: [color],
                        dataLabels: {
                            enabled: true,
                            offsetX: -4,
                            style: {
                                fontSize: '10px',
                                colors: ['#fff']
                            },
                            formatter: function(val) {
                                return val + "%";
                            }
                        },
                        stroke: {
                            show: true,
                            width: 1,
                            colors: ['transparent']
                        },
                        xaxis: {
                            categories: categories,
                            max: 108, // Memberi ruang ekstra agar label "100%" di ujung bar tidak melebihi batas card
                            labels: {
                                style: {
                                    colors: isDark ? '#9CA3AF' : '#6B7280',
                                    fontSize: '10px'
                                }
                            }
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    colors: isDark ? '#E5E7EB' : '#374151',
                                    fontSize: '11px',
                                    fontWeight: 500
                                },
                                maxWidth: 130,
                                formatter: function(val) {
                                    if (val && val.length > 18) {
                                        return val.substring(0, 18) + '...';
                                    }
                                    return val;
                                }
                            }
                        },
                        grid: {
                            borderColor: isDark ? '#374151' : '#F3F4F6',
                            strokeDashArray: 4,
                            padding: {
                                left: 0,
                                right: 15
                            }
                        },
                        theme: {
                            mode: isDark ? 'dark' : 'light'
                        },
                        tooltip: {
                            y: {
                                formatter: function(val, opts) {
                                    if (opts.seriesIndex === 0) {
                                        let dev = data[opts.dataPointIndex].deviasi;
                                        let sign = dev > 0 ? '+' : '';
                                        let fullLoc = data[opts.dataPointIndex].lokasi;
                                        return val + "% (Deviasi: " + sign + dev + "%) - " + fullLoc;
                                    }
                                    return val + "%";
                                }
                            }
                        },
                        legend: {
                            position: 'top',
                            horizontalAlign: 'center',
                            fontSize: '11px'
                        }
                    };

                    const chart = new ApexCharts(document.querySelector("#" + elementId), options);
                    chart.render();

                    // Re-render chart on theme change
                    const observer = new MutationObserver((mutations) => {
                        mutations.forEach((mutation) => {
                            if (mutation.attributeName === 'class') {
                                const newIsDark = document.documentElement.classList.contains('dark');
                                chart.updateOptions({
                                    theme: {
                                        mode: newIsDark ? 'dark' : 'light'
                                    },
                                    xaxis: {
                                        labels: {
                                            style: {
                                                colors: newIsDark ? '#9CA3AF' : '#6B7280'
                                            }
                                        }
                                    },
                                    yaxis: {
                                        labels: {
                                            style: {
                                                colors: newIsDark ? '#E5E7EB' : '#374151'
                                            }
                                        }
                                    },
                                    grid: {
                                        borderColor: newIsDark ? '#374151' : '#F3F4F6'
                                    }
                                });
                            }
                        });
                    });
                    observer.observe(document.documentElement, {
                        attributes: true
                    });
                }

                renderBarChart('chart-top10', top10Data, '#10B981'); // Success green
                renderBarChart('chart-bottom10', bottom10Data, '#EF4444'); // Danger red
            });
        </script>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('dashboardTableManager', () => ({
                    isPdfModalOpen: false,
                    pdfBatchId: '{{ request('batch_id') }}',
                    pdfDate: '{{ request('date') ?: date('Y-m-d') }}',
                }));
            });
        </script>

        <script>
            // Scatter Plot: Rencana vs Realisasi
            document.addEventListener('DOMContentLoaded', function() {
                const allData = @json($stats['all_konstruksi'] ?? []);
                const isDark = document.documentElement.classList.contains('dark');

                if (!document.getElementById('scatter-rencana-realisasi') || allData.length === 0) return;

                // Group data by deviation status: positif (hijau) vs negatif (merah)
                const positif = []; // deviasi >= 0
                const negatif = []; // deviasi < 0

                let totalRencana = 0;
                let totalRealisasi = 0;

                allData.forEach(item => {
                    const point = [
                        item.rencana,
                        item.progres,
                        {
                            lokasi: item.lokasi,
                            konstruktor: item.konstruktor,
                            deviasi: item.deviasi
                        }
                    ];
                    totalRencana += item.rencana;
                    totalRealisasi += item.progres;

                    if (item.deviasi >= 0) {
                        positif.push(point);
                    } else {
                        negatif.push(point);
                    }
                });



                const options = {
                    series: [{
                            name: 'Deviasi Positif',
                            data: positif
                        },
                        {
                            name: 'Deviasi Negatif',
                            data: negatif
                        },
                    ],
                    chart: {
                        type: 'scatter',
                        height: 420,
                        toolbar: {
                            show: true,
                            tools: {
                                download: false,
                                selection: true,
                                zoom: true,
                                zoomin: true,
                                zoomout: true,
                                pan: true,
                                reset: true
                            }
                        },
                        background: 'transparent',
                        fontFamily: 'Inter, sans-serif',
                        zoom: {
                            enabled: true,
                            type: 'xy'
                        },
                    },
                    colors: ['#10B981', '#EF4444'],
                    markers: {
                        size: 7,
                        strokeWidth: 1,
                        strokeColors: isDark ? '#1F2937' : '#FFFFFF',
                        hover: {
                            sizeOffset: 3
                        },
                    },
                    xaxis: {
                        type: 'numeric',
                        title: {
                            text: 'Rencana (%)',
                            style: {
                                fontSize: '12px',
                                fontWeight: 600,
                                color: isDark ? '#D1D5DB' : '#4B5563'
                            }
                        },
                        min: 0,
                        max: 100,
                        tickAmount: 10,
                        labels: {
                            formatter: val => Math.round(val) + '%',
                            style: {
                                colors: isDark ? '#9CA3AF' : '#6B7280',
                                fontSize: '10px'
                            },
                        },
                    },
                    yaxis: {
                        title: {
                            text: 'Realisasi (%)',
                            style: {
                                fontSize: '12px',
                                fontWeight: 600,
                                color: isDark ? '#D1D5DB' : '#4B5563'
                            }
                        },
                        min: 0,
                        max: 100,
                        tickAmount: 10,
                        labels: {
                            formatter: val => Math.round(val) + '%',
                            style: {
                                colors: isDark ? '#9CA3AF' : '#6B7280',
                                fontSize: '10px'
                            },
                        },
                    },
                    grid: {
                        borderColor: isDark ? '#374151' : '#F3F4F6',
                        strokeDashArray: 4,
                    },
                    theme: {
                        mode: isDark ? 'dark' : 'light'
                    },
                    legend: {
                        show: false,
                    },
                    tooltip: {
                        enabled: true,
                        intersect: false,
                        shared: false,
                        theme: isDark ? 'dark' : 'light',
                        z: {
                            formatter: function() {
                                return '';
                            },
                            title: ''
                        },
                        x: {
                            formatter: function(val, {
                                series,
                                seriesIndex,
                                dataPointIndex,
                                w
                            }) {
                                const pointArr = w.config.series[seriesIndex].data[dataPointIndex];
                                return (pointArr && pointArr[2]) ? pointArr[2].lokasi : val;
                            }
                        },
                        y: {
                            title: {
                                formatter: function(seriesName) {
                                    return 'Progres:';
                                }
                            },
                            formatter: function(val) {
                                return val + '%';
                            }
                        }
                    },
                };

                const chart = new ApexCharts(document.querySelector('#scatter-rencana-realisasi'), options);
                chart.render();

                // Draw diagonal reference line after render
                setTimeout(() => {
                    const chartEl = document.querySelector('#scatter-rencana-realisasi .apexcharts-plot-area');
                    if (chartEl) {
                        const svg = chartEl.closest('svg');
                        const plotArea = chartEl;
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

                // Theme change observer
                const observer = new MutationObserver((mutations) => {
                    mutations.forEach((mutation) => {
                        if (mutation.attributeName === 'class') {
                            const newIsDark = document.documentElement.classList.contains('dark');
                            chart.updateOptions({
                                theme: {
                                    mode: newIsDark ? 'dark' : 'light'
                                },
                                markers: {
                                    strokeColors: newIsDark ? '#1F2937' : '#FFFFFF'
                                },
                                xaxis: {
                                    title: {
                                        style: {
                                            color: newIsDark ? '#D1D5DB' : '#4B5563'
                                        }
                                    },
                                    labels: {
                                        style: {
                                            colors: newIsDark ? '#9CA3AF' : '#6B7280'
                                        }
                                    },
                                },
                                yaxis: {
                                    title: {
                                        style: {
                                            color: newIsDark ? '#D1D5DB' : '#4B5563'
                                        }
                                    },
                                    labels: {
                                        style: {
                                            colors: newIsDark ? '#9CA3AF' : '#6B7280'
                                        }
                                    },
                                },
                                grid: {
                                    borderColor: newIsDark ? '#374151' : '#F3F4F6'
                                },
                            });
                            // Redraw diagonal line
                            setTimeout(() => {
                                const plotArea = document.querySelector(
                                    '#scatter-rencana-realisasi .apexcharts-plot-area');
                                if (plotArea) {
                                    const oldLine = plotArea.querySelector('line.diagonal-ref');
                                    if (oldLine) oldLine.remove();
                                    const rect = plotArea.getBBox();
                                    const line = document.createElementNS(
                                        'http://www.w3.org/2000/svg', 'line');
                                    line.classList.add('diagonal-ref');
                                    line.setAttribute('x1', rect.x);
                                    line.setAttribute('y1', rect.y + rect.height);
                                    line.setAttribute('x2', rect.x + rect.width);
                                    line.setAttribute('y2', rect.y);
                                    line.setAttribute('stroke', newIsDark ? '#4B5563' :
                                        '#D1D5DB');
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
                observer.observe(document.documentElement, {
                    attributes: true
                });
            });
        </script>
        <!-- Modal PDF -->
        <div x-show="isPdfModalOpen" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div @click.away="isPdfModalOpen = false" x-transition.opacity.duration.200ms
                class="bg-white dark:bg-gray-900 rounded-2xl w-full max-w-md p-6 shadow-xl border border-gray-100 dark:border-gray-800 relative">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-lg font-bold text-textMain-light dark:text-textMain-dark">Export Laporan PDF</h3>
                    <button @click="isPdfModalOpen = false" class="text-gray-400 hover:text-danger transition-colors">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form action="{{ route('program.dashboard.export-pdf', ['program' => strtolower($activeProgram)]) }}"
                    method="GET" target="_blank" @submit="setTimeout(() => isPdfModalOpen = false, 500)">
                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Pilih
                                Tahap (Batch)</label>
                            <select name="batch_id" x-model="pdfBatchId"
                                class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark">
                                <option value="">Semua Tahap</option>
                                @foreach ($stats['filter_batches'] ?? [] as $batch)
                                    <option value="{{ $batch['id'] }}">{{ $batch['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Pilih
                                Tanggal</label>
                            <input type="date" name="date" x-model="pdfDate"
                                class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark">
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3">
                        <button type="button" @click="isPdfModalOpen = false"
                            class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-danger text-white rounded-lg text-sm font-medium hover:bg-red-600 transition-colors flex items-center gap-2">
                            <i class="fa-solid fa-download"></i> Generate PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

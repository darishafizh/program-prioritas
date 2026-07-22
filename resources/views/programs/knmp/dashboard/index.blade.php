@extends('layouts.app')

@section('title', 'KNMP - Dashboard Analisis Eksekutif')

@section('content')
    <style>
        .pin-konstruksi {
            background-color: var(--color-warning, #F59E0B);
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 2.5px solid #ffffff;
            box-shadow: 0 0 14px rgba(245, 158, 11, 0.8), 0 2px 5px rgba(0, 0, 0, 0.4);
            position: relative;
        }

        .pin-konstruksi::after {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            border-radius: 50%;
            border: 2px solid var(--color-warning, #F59E0B);
            animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;
        }

        .pin-serah-terima {
            background-color: var(--color-success, #10B981);
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 2.5px solid #ffffff;
            box-shadow: 0 0 14px rgba(16, 185, 129, 0.8), 0 2px 5px rgba(0, 0, 0, 0.4);
            position: relative;
        }

        @keyframes ping {

            75%,
            100% {
                transform: scale(2);
                opacity: 0;
            }
        }
    </style>
    <div x-data="dashboardTableManager()">
        <!-- Header & Global Filters (Row 1) -->
        <div class="mb-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <div class="flex items-center gap-3 flex-wrap">
                    <h2 class="text-xl font-semibold tracking-tight">Dashboard KNMP</h2>

                </div>
                <p class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1.5">Ringkasan Eksekutif &
                    Pantauan Konstruksi Kampung Nelayan Merah Putih</p>
            </div>


        </div>


        <!-- KPI Cards (Row 2) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <x-stat-card title="Total Lokasi" icon="fa-solid fa-house-chimney-window"
                icon-color="text-teal-light dark:text-teal-400" icon-bg="bg-teal-light/10 dark:bg-teal-light/20"
                value="{{ $stats['total_lokasi'] ?? 0 }}" unit="Lokasi"
                description="<span class='text-teal-light dark:text-teal-400 font-medium'>{{ $stats['label_total_lokasi'] ?? '' }}</span>"
                class="cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800"
                onclick="window.location.href='{{ route('program.dashboard.siklus', ['program' => strtolower($activeProgram)]) }}'" />

            <x-stat-card title="Total Operasional" icon="fa-solid fa-check-double"
                icon-color="text-success dark:text-emerald-400" icon-bg="bg-success/10 dark:bg-success/20"
                value="{{ $stats['total_selesai'] ?? 0 }}" unit="Lokasi"
                description="<span class='text-success font-medium inline-flex items-center gap-1'><i class='fa-solid fa-arrow-trend-up'></i> Telah beroperasi</span>"
                class="cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800" onclick="window.location.href='{{ route('program.dashboard.operasional', ['program' => strtolower($activeProgram)]) }}'" />

            <x-stat-card title="Total Konstruksi" icon="fa-solid fa-person-digging"
                icon-color="text-warning dark:text-amber-500" icon-bg="bg-warning/10 dark:bg-amber-400/20"
                value="{{ $stats['dalam_pembangunan'] ?? 0 }}" unit="Lokasi" description="<span class='text-warning dark:text-amber-500 font-medium'>Rata-rata Progres: {{ number_format($stats['rata_progres'] ?? 0, 2, ',', '.') }}%</span>"
                class="cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800" onclick="window.location.href='{{ route('program.dashboard.konstruksi', ['program' => strtolower($activeProgram)]) }}'" />
        </div>


        
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
                
                @if (count($stats['map_locations'] ?? []) > 0)
                <div class="flex items-center gap-4 bg-gray-50/50 dark:bg-gray-800/30 rounded-lg px-3 py-2 border border-gray-100 dark:border-gray-800">
                    <label class="flex items-center gap-2 cursor-pointer text-[11px] font-medium text-textMain-light dark:text-textMain-dark">
                        <input type="checkbox" id="filter-konstruksi" checked class="rounded text-warning border-gray-300 focus:ring-warning">
                        <div class="w-3 h-3 rounded-full bg-warning border border-white dark:border-gray-800 shadow-sm"></div>
                        <span>Konstruksi</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-[11px] font-medium text-textMain-light dark:text-textMain-dark">
                        <input type="checkbox" id="filter-operasional" checked class="rounded text-success border-gray-300 focus:ring-success">
                        <div class="w-3 h-3 rounded-full bg-success border border-white dark:border-gray-800 shadow-sm"></div>
                        <span>Operasional</span>
                    </label>
                </div>
                @endif
            </div>

            <div id="knmpMapContainer" class="relative">
                @if (count($stats['map_locations'] ?? []) > 0)
                    <div id="knmpMap" class="w-full h-[500px] z-0 bg-gray-100 dark:bg-gray-900"
                        style="height: 500px; width: 100%; min-height: 500px;"></div>
                @else
                    <div
                        class="w-full h-[380px] flex flex-col items-center justify-center p-8 text-center bg-gray-50/60 dark:bg-gray-900/40 border-b border-gray-100 dark:border-gray-800">
                        <div
                            class="w-14 h-14 rounded-3xl bg-teal-light/10 dark:bg-teal-400/10 flex items-center justify-center text-teal-light dark:text-teal-400 mb-4 shadow-sm">
                            <i class="fa-solid fa-map-location-dot text-2xl"></i>
                        </div>
                        <h4 class="font-bold text-sm text-textMain-light dark:text-textMain-dark">Sebaran Peta Konstruksi
                            Tidak Ditampilkan</h4>
                        <p class="text-xs text-textMuted-light dark:text-textMuted-dark max-w-md mt-1.5 leading-relaxed">
                            @if (($stats['total_selesai'] ?? 0) > 0 && ($stats['total_selesai'] ?? 0) == ($stats['total_lokasi'] ?? 0))
                                Semua proyek KNMP pada filter yang dipilih telah selesai dibangun (`Serah Terima`). Saat ini
                                tidak ada lokasi dengan status konstruksi aktif di lapangan.
                            @elseif(($stats['total_lokasi'] ?? 0) > 0)
                                Sebanyak <strong>{{ $stats['total_lokasi'] }} lokasi</strong> pada filter ini masih
                                berstatus pra-konstruksi (<em>Usulan / Survei / DED / Lelang</em>), sehingga koordinat
                                progres konstruksi belum dipetakan.
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
                var mapEl = document.getElementById('knmpMap');
                if (!mapEl) return;
                
                // Initialize map centered on Indonesia (shifted east to push map left)
                var map = L.map('knmpMap').setView([-0.7893, 118.9213], 5);

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
                
                var markers = {
                    konstruksi: L.featureGroup().addTo(map),
                    operasional: L.featureGroup().addTo(map)
                };

                locations.forEach(function(loc) {
                    if (loc.latitude && loc.longitude) {
                        var isSerahTerima = loc.tahap === 'serah_terima' || loc.tahap_saat_ini ===
                            'serah_terima';
                        var className = isSerahTerima ? 'pin-serah-terima' : 'pin-konstruksi';

                        var icon = L.divIcon({
                            className: 'custom-menteri-pin',
                            iconAnchor: [9, 9],
                            popupAnchor: [0, -12],
                            html: `<div class="${className}"></div>`
                        });

                        var marker = L.marker([loc.latitude, loc.longitude], {
                            icon: icon
                        });

                        marker.on('click', () => {
                            window.dispatchEvent(new CustomEvent('open-map-detail', {
                                detail: loc
                            }));

                            // Map pan logic
                            const targetZoom = 14;
                            const pointPx = map.project([loc.latitude, loc.longitude], targetZoom);
                            // Kalkulasi offset agar titik tepat berada di tengah area map yang tidak tertutup offcanvas (40% kiri layar)
                            const offsetX = window.innerWidth * 0.30;
                            const targetLatLng = map.unproject(L.point(pointPx.x + offsetX, pointPx.y),
                                targetZoom);
                            map.flyTo(targetLatLng, targetZoom, {
                                duration: 1.2
                            });
                        });

                        marker.bindTooltip(
                            `<b>${loc.nama}</b><br/><span style="font-size:11px; opacity:0.85; font-weight:500;">${loc.tahap === 'serah_terima' ? 'Operasional' : 'Konstruksi'}</span>`, {
                                direction: 'top',
                                offset: [0, -10]
                            });

                        if (isSerahTerima) {
                            marker.addTo(markers.operasional);
                        } else {
                            marker.addTo(markers.konstruksi);
                        }
                    }
                });

                var chkKonstruksi = document.getElementById('filter-konstruksi');
                var chkOperasional = document.getElementById('filter-operasional');
                
                if (chkKonstruksi) {
                    chkKonstruksi.addEventListener('change', function(e) {
                        if (e.target.checked) map.addLayer(markers.konstruksi);
                        else map.removeLayer(markers.konstruksi);
                    });
                }
                
                if (chkOperasional) {
                    chkOperasional.addEventListener('change', function(e) {
                        if (e.target.checked) map.addLayer(markers.operasional);
                        else map.removeLayer(markers.operasional);
                    });
                }

                window.addEventListener('map-fly-home', () => {
                    map.flyTo([-0.7893, 118.9213], 5, {
                        duration: 1.0
                    });
                });

                setTimeout(function() {
                    map.invalidateSize();
                }, 300);
            });
        </script>


        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('dashboardTableManager', () => ({
                    selectedPoint: null,
                    isOffcanvasOpen: false,

                    init() {
                        window.addEventListener('open-map-detail', (e) => {
                            this.selectedPoint = e.detail;
                            this.isOffcanvasOpen = true;
                        });
                        
                        this.$watch('activeTab', (value) => {
                            setTimeout(() => {
                                window.dispatchEvent(new Event('resize'));
                            }, 50);
                        });
                    },

                    stageLevel(stage) {
                        const map = {
                            'usulan': 1,
                            'survey': 2,
                            'survei': 2,
                            'ded': 3,
                            'lelang': 4,
                            'konstruksi': 5,
                            'serah_terima': 6,
                            'serah-terima': 6,
                            'selesai': 6
                        };
                        return map[stage] || 1;
                    },

                    formatDec(val) {
                        if (val === null || val === undefined || val === '') return '0,00';
                        return Number(val || 0).toLocaleString('id-ID', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    },

                    closeDetailPanel() {
                        this.isOffcanvasOpen = false;
                        setTimeout(() => this.selectedPoint = null, 300);
                        window.dispatchEvent(new CustomEvent('map-fly-home'));
                    }
                }));
            });
        </script>

        

<!-- Offcanvas Detail -->
        @include('programs.knmp.dashboard.partials.offcanvas_map')
        


    </div>
@endsection

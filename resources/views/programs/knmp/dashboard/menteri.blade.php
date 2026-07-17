<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal Eksekutif Menteri — Program Prioritas KNMP</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo-kkp.png') }}">

    <!-- Fonts (Inter - Konsisten dengan Sistem Utama) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <script>
        // Theme initialization konsisten dengan app.blade.php
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
        }

        /* Custom Glowing Marker Styles */
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

        /* Leaflet popup & tooltip customization consistent with design tokens */
        .leaflet-popup-content-wrapper {
            background: var(--color-bgSurface-light, #FFFFFF) !important;
            border: 1px solid rgba(100, 116, 139, 0.2);
            border-radius: 1.25rem !important;
            color: var(--color-textMain-light, #1e293b) !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2) !important;
        }

        .dark .leaflet-popup-content-wrapper {
            background: var(--color-bgSurface-dark, #131C2E) !important;
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: var(--color-textMain-dark, #F9FAFB) !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.6) !important;
        }

        .leaflet-popup-tip {
            background: var(--color-bgSurface-light, #FFFFFF) !important;
        }

        .dark .leaflet-popup-tip {
            background: var(--color-bgSurface-dark, #131C2E) !important;
        }

        .leaflet-tooltip {
            background-color: var(--color-bgSurface-light, #FFFFFF) !important;
            border: 1px solid rgba(100, 116, 139, 0.2) !important;
            color: var(--color-textMain-light, #1e293b) !important;
            border-radius: 0.75rem !important;
            padding: 0.5rem 0.75rem !important;
            font-family: 'Inter', system-ui, sans-serif !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
        }

        .dark .leaflet-tooltip {
            background-color: var(--color-bgSurface-dark, #131C2E) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            color: var(--color-textMain-dark, #F9FAFB) !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5) !important;
        }
    </style>
</head>

<body
    class="h-screen w-screen overflow-hidden bg-bgBody-light dark:bg-bgBody-dark text-textMain-light dark:text-textMain-dark select-none relative font-sans transition-colors duration-300"
    x-data="menteriMapApp()">

    <!-- Full Screen Map Container (Menutup 100% layar) -->
    <div id="menteriMap" class="absolute inset-0 w-full h-full z-0"></div>

    <!-- 3 Bagian Atas: Kiri (Tombol Kembali), Tengah (Card KPI), Kanan (Theme & Logout) -->
    <div
        class="absolute top-4 sm:top-6 left-4 right-4 sm:left-6 sm:right-6 z-10 pointer-events-none flex items-center justify-between gap-3">

        <!-- Bagian Kiri: Tombol Kembali Berbentuk Bulat -->
        <div class="pointer-events-auto shrink-0">
            <a href="{{ route('greetings') }}" title="Kembali ke Portal"
                class="w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-bgSurface-light/90 dark:bg-bgSurface-dark/90 backdrop-blur-xl border border-gray-100 dark:border-gray-800 text-textMain-light dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800 transition-all shadow-xl flex items-center justify-center">
                <i class="fa-solid fa-arrow-left text-base sm:text-lg"></i>
            </a>
        </div>

        <!-- Bagian Tengah: Card Total Lokasi, Serah Terima, Konstruksi, Avg. Progres -->
        <div
            class="pointer-events-auto bg-bgSurface-light/90 dark:bg-bgSurface-dark/90 backdrop-blur-xl border border-gray-100 dark:border-gray-800 rounded-2xl sm:rounded-3xl p-1.5 sm:p-2 shadow-xl flex items-center gap-1.5 sm:gap-2.5 max-w-full overflow-x-auto transition-colors duration-300">
            <!-- Total Lokasi -->
            <div
                class="px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl sm:rounded-2xl bg-gray-50 dark:bg-gray-800/80 border border-gray-100 dark:border-gray-700 flex items-center gap-2 sm:gap-3 shadow-2xs shrink-0">
                <div
                    class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl bg-teal-light/10 dark:bg-teal-400/10 text-teal-light dark:text-teal-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-location-dot text-xs sm:text-sm"></i>
                </div>
                <div>
                    <div
                        class="text-[9px] sm:text-[10px] font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider leading-none">
                        Total Lokasi</div>
                    <div class="font-bold text-xs sm:text-base text-textMain-light dark:text-white mt-1 leading-none">
                        {{ $stats['total_lokasi'] }} <span
                            class="text-[10px] font-normal text-textMuted-light dark:text-textMuted-dark">Titik</span>
                    </div>
                </div>
            </div>

            <!-- Serah Terima (Progres 100%) -->
            <div
                class="px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl sm:rounded-2xl bg-success/10 dark:bg-success/20 border border-success/20 dark:border-success/30 flex items-center gap-2 sm:gap-3 shadow-2xs shrink-0">
                <div
                    class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl bg-success/20 dark:bg-success/30 text-success dark:text-emerald-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-circle-check text-xs sm:text-sm"></i>
                </div>
                <div>
                    <div
                        class="text-[9px] sm:text-[10px] font-semibold text-success dark:text-emerald-300 uppercase tracking-wider leading-none">
                        Serah Terima</div>
                    <div class="font-bold text-xs sm:text-base text-success dark:text-emerald-400 mt-1 leading-none">
                        {{ $stats['total_serah_terima'] }} <span
                            class="text-[10px] font-normal opacity-80">Lokasi</span></div>
                </div>
            </div>

            <!-- Konstruksi (Masih Progres) -->
            <div
                class="px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl sm:rounded-2xl bg-warning/10 dark:bg-amber-400/10 border border-warning/20 dark:border-amber-400/20 flex items-center gap-2 sm:gap-3 shadow-2xs shrink-0">
                <div
                    class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl bg-warning/20 dark:bg-amber-400/20 text-warning dark:text-amber-400 flex items-center justify-center shrink-0 relative">
                    <span
                        class="w-1.5 h-1.5 rounded-full bg-warning dark:bg-amber-400 animate-ping absolute top-1 right-1"></span>
                    <i class="fa-solid fa-person-digging text-xs sm:text-sm"></i>
                </div>
                <div>
                    <div
                        class="text-[9px] sm:text-[10px] font-semibold text-warning dark:text-amber-300 uppercase tracking-wider leading-none">
                        Konstruksi</div>
                    <div class="font-bold text-xs sm:text-base text-warning dark:text-amber-400 mt-1 leading-none">
                        {{ $stats['total_konstruksi'] }} <span class="text-[10px] font-normal opacity-80">Lokasi</span>
                    </div>
                </div>
            </div>

            <!-- Avg. Progres -->
            <div
                class="px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl sm:rounded-2xl bg-teal-light/10 dark:bg-teal-400/10 border border-teal-light/20 dark:border-teal-400/20 flex items-center gap-2 sm:gap-3 shadow-2xs shrink-0">
                <div
                    class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl bg-teal-light/20 dark:bg-teal-400/20 text-teal-light dark:text-teal-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-chart-line text-xs sm:text-sm"></i>
                </div>
                <div>
                    <div
                        class="text-[9px] sm:text-[10px] font-semibold text-teal-light dark:text-teal-300 uppercase tracking-wider leading-none">
                        Avg. Progres Konstruksi</div>
                    <div class="font-bold text-xs sm:text-base text-teal-light dark:text-teal-400 mt-1 leading-none">
                        {{ $stats['rata_progres'] }}%</div>
                </div>
            </div>
        </div>

        <!-- Bagian Kanan: Tombol Switch Theme & Logout (Ukuran Bulat Sama Seperti Tombol Kembali) -->
        <div class="pointer-events-auto flex items-center gap-2 sm:gap-3 shrink-0">
            <!-- Theme Toggle Button -->
            <button @click="toggleTheme()" title="Ganti Tema"
                class="w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-bgSurface-light/90 dark:bg-bgSurface-dark/90 backdrop-blur-xl border border-gray-100 dark:border-gray-800 text-textMuted-light dark:text-textMuted-dark hover:text-textMain-light dark:hover:text-amber-400 transition-all shadow-xl flex items-center justify-center shrink-0">
                <i class="fa-solid text-base sm:text-lg" :class="darkMode ? 'fa-sun text-amber-400' : 'fa-moon'"></i>
            </button>

            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}" class="m-0 flex">
                @csrf
                <button type="submit" title="Keluar dari Sistem"
                    class="w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-danger/10 hover:bg-danger/20 text-danger border border-danger/20 backdrop-blur-xl shadow-xl flex items-center justify-center shrink-0 transition-all">
                    <i class="fa-solid fa-arrow-right-from-bracket text-base sm:text-lg"></i>
                </button>
            </form>
        </div>

    </div>

    <!-- Bottom Floating Legend Card (Melayang di bawah kiri peta, latar blur konsisten tema) -->
    <div class="absolute bottom-6 left-4 right-4 sm:right-auto sm:left-6 z-10 pointer-events-auto">
        <div
            class="bg-bgSurface-light/90 dark:bg-bgSurface-dark/90 backdrop-blur-xl border border-gray-100 dark:border-gray-800 rounded-2xl px-5 py-3.5 shadow-2xl flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6 text-xs text-textMain-light dark:text-textMain-dark transition-colors duration-300">

            <div
                class="font-semibold text-textMuted-light dark:text-textMuted-dark flex items-center gap-2 border-b sm:border-b-0 sm:border-r border-gray-200/60 dark:border-gray-700/60 pb-2 sm:pb-0 sm:pr-4 w-full sm:w-auto">
                <i class="fa-solid fa-layer-group text-teal-light dark:text-teal-400"></i>
                <span>Legenda Peta:</span>
            </div>

            <div class="flex flex-wrap items-center gap-5 font-medium">
                <!-- Indikator Masih Progres -->
                <div class="flex items-center gap-2.5">
                    <span
                        class="w-3.5 h-3.5 rounded-full bg-warning dark:bg-amber-400 border-2 border-white dark:border-gray-800 shadow-sm shrink-0 relative">
                        <span
                            class="w-2 h-2 rounded-full bg-warning dark:bg-amber-400 animate-ping absolute -top-0.5 -left-0.5"></span>
                    </span>
                    <span class="text-textMain-light dark:text-gray-200">Konstruksi</span>
                </div>

                <!-- Indikator Progres 100% -->
                <div class="flex items-center gap-2.5">
                    <span
                        class="w-3.5 h-3.5 rounded-full bg-success dark:bg-emerald-400 border-2 border-white dark:border-gray-800 shadow-sm shrink-0"></span>
                    <span class="text-textMain-light dark:text-gray-200">Serah Terima</span>
                </div>
            </div>

            <div
                class="hidden lg:flex items-center gap-1.5 text-[11px] text-textMuted-light dark:text-textMuted-dark pl-4 border-l border-gray-200/60 dark:border-gray-700/60 font-medium">
                <i class="fa-regular fa-clock"></i>
                <span>{{ $stats['last_updated'] }}</span>
            </div>

        </div>
    </div>

    <!-- OFFCANVAS DETAIL 75% LEBAR LAYAR (Muncul dari kanan saat titik lokasi diklik) -->
    <div x-show="selectedPoint" style="display: none; z-index: 99999;"
        class="fixed inset-0 overflow-hidden font-sans pointer-events-none">
        <div class="fixed inset-0 w-full flex justify-end pointer-events-none">
            <!-- Offcanvas Panel kanan 75% layar -->
            <div x-show="selectedPoint" @click.away="closeDetailPanel()"
                style="width: 75vw !important; max-width: 75vw !important;"
                x-transition:enter="transform transition ease-in-out duration-300 sm:duration-500"
                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in-out duration-300 sm:duration-500"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                class="w-3/4 h-full bg-white dark:bg-gray-900 shadow-2xl flex flex-col text-left pointer-events-auto border-l border-gray-200/80 dark:border-gray-800">

                <!-- Fixed Header -->
                <div
                    class="px-6 sm:px-8 py-5 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center flex-shrink-0 bg-white dark:bg-gray-900 shadow-sm gap-4">
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider"
                            :class="selectedPoint?.tahap === 'serah_terima' ?
                                'bg-success/10 dark:bg-success/20 text-success dark:text-emerald-400 border border-success/20' :
                                'bg-warning/10 dark:bg-amber-400/10 text-warning dark:text-amber-400 border border-warning/20'"
                            x-text="selectedPoint?.tahap_label"></span>
                        <h3 class="text-base sm:text-lg font-bold tracking-tight text-textMain-light dark:text-white truncate max-w-xl"
                            x-text="selectedPoint?.nama">
                        </h3>
                    </div>

                    <div class="flex items-center shrink-0 gap-3">
                        <a :href="`{{ url('operasional/knmp') }}?stage=${selectedPoint?.tahap === 'serah_terima' ? 'serah-terima' : 'konstruksi'}&detail_id=${selectedPoint?.id}`"
                            class="py-2 px-3.5 rounded-xl bg-teal-light text-white font-semibold text-xs flex items-center gap-2 shadow-sm hover:bg-teal-light/90 transition-all">
                            <span>Lihat di Modul Operasional</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[11px]"></i>
                        </a>
                        <button type="button" @click="closeDetailPanel()"
                            class="cursor-pointer text-gray-500 dark:text-gray-400 hover:text-danger dark:hover:text-danger p-2 transition-colors focus:outline-none"
                            title="Tutup Panel">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Scrollable Body with internal scroll -->
                <div class="flex flex-col flex-1 overflow-hidden bg-gray-50/60 dark:bg-gray-950/50">
                    <div class="overflow-y-auto overscroll-contain p-6 sm:p-8 flex-1">
                        <div class="w-full space-y-8">
                            <template x-if="selectedPoint">
                                <div class="space-y-8">

                                    <!-- STATUS ALERT BANNER -->
                                    <div
                                        class="p-4 sm:p-5 rounded-2xl border-l-4 flex items-start gap-4 shadow-sm transition-all bg-teal-light/10 border-teal-light text-teal-light dark:bg-teal-light/20 dark:border-teal-dark dark:text-teal-dark">
                                        <div
                                            class="w-10 h-10 rounded-xl flex items-center justify-center text-lg shrink-0 shadow-2xs bg-teal-light/20 text-teal-light dark:bg-teal-light/30 dark:text-teal-dark">
                                            <i class="fa-solid"
                                                :class="stageInfo(selectedPoint.tahap_saat_ini).icon"></i>
                                        </div>
                                        <div class="flex-1 text-xs sm:text-sm leading-relaxed">
                                            <div class="font-semibold text-sm mb-0.5"
                                                x-text="stageInfo(selectedPoint.tahap_saat_ini).label"></div>
                                            <div class="font-normal opacity-90 text-teal-light dark:text-teal-dark"
                                                x-text="stageInfo(selectedPoint.tahap_saat_ini).desc"></div>
                                        </div>
                                    </div>

                                    <!-- BARIS 1: Informasi Wilayah & Geografis | Status dan Pengesahan Dokumen -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 py-1 mt-6">
                                        <!-- Kolom 1: Informasi Wilayah & Geografis -->
                                        <div>
                                            <h4
                                                class="text-sm font-semibold tracking-tight text-textMain-light dark:text-textMain-dark mb-3 text-left">
                                                Informasi Wilayah & Geografis
                                            </h4>

                                            <dl class="space-y-2 text-xs sm:text-sm text-left">
                                                <div class="flex items-start gap-4 py-1 text-left">
                                                    <dt
                                                        class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                        Provinsi</dt>
                                                    <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left"
                                                        x-text="selectedPoint?.provinsi || '-'"></dd>
                                                </div>
                                                <div class="flex items-start gap-4 py-1 text-left">
                                                    <dt
                                                        class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                        Kabupaten / Kota</dt>
                                                    <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left"
                                                        x-text="selectedPoint?.kabupaten || '-'"></dd>
                                                </div>
                                                <div class="flex items-start gap-4 py-1 text-left">
                                                    <dt
                                                        class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                        Kecamatan</dt>
                                                    <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left"
                                                        x-text="selectedPoint?.kecamatan || '-'"></dd>
                                                </div>
                                                <div class="flex items-start gap-4 py-1 text-left">
                                                    <dt
                                                        class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                        Desa / Kelurahan</dt>
                                                    <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left"
                                                        x-text="selectedPoint?.desa || '-'"></dd>
                                                </div>
                                                <div class="flex items-start gap-4 py-1 text-left">
                                                    <dt
                                                        class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                        Koordinat GPS</dt>
                                                    <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left truncate"
                                                        x-text="selectedPoint?.koordinat || '-'"></dd>
                                                </div>
                                                <div class="flex items-start gap-4 py-1 text-left">
                                                    <dt
                                                        class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                        Wilayah Tugas</dt>
                                                    <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left"
                                                        x-text="selectedPoint?.daerah || '-'"></dd>
                                                </div>
                                            </dl>
                                        </div>

                                        <!-- Kolom 2: Status dan Pengesahan Dokumen -->
                                        <div>
                                            <h4
                                                class="text-sm font-semibold tracking-tight text-textMain-light dark:text-textMain-dark mb-3 text-left">
                                                Status dan Pengesahan Dokumen
                                            </h4>

                                            <dl class="space-y-2 text-xs sm:text-sm text-left">
                                                <div class="flex items-start gap-4 py-1 text-left">
                                                    <dt
                                                        class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                        Nomor DED</dt>
                                                    <dd
                                                        class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left truncate">
                                                        <template
                                                            x-if="stageLevel(selectedPoint?.tahap_saat_ini) >= 3">
                                                            <span
                                                                x-text="selectedPoint?.tahapDed?.nomor_dokumen || 'DED-KNMP/2026/01'"></span>
                                                        </template>
                                                        <template x-if="stageLevel(selectedPoint?.tahap_saat_ini) < 3">
                                                            <span class="text-textMuted-light italic">(Belum Tahap
                                                                DED)</span>
                                                        </template>
                                                    </dd>
                                                </div>
                                                <div class="flex items-start gap-4 py-1 text-left">
                                                    <dt
                                                        class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                        Pengesahan DED</dt>
                                                    <dd
                                                        class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left flex items-center gap-1.5">
                                                        <template
                                                            x-if="stageLevel(selectedPoint?.tahap_saat_ini) >= 3">
                                                            <span
                                                                class="flex items-center gap-1.5 text-teal-light font-semibold">
                                                                <i class="fa-solid fa-circle-check text-xs"></i>
                                                                <span
                                                                    x-text="selectedPoint?.tahapDed?.tanggal_pengesahan !== '-' ? selectedPoint?.tahapDed?.tanggal_pengesahan : 'Disahkan Tim Teknis'"></span>
                                                            </span>
                                                        </template>
                                                        <template x-if="stageLevel(selectedPoint?.tahap_saat_ini) < 3">
                                                            <span
                                                                class="flex items-center gap-1.5 text-textMuted-light italic">
                                                                <i class="fa-solid fa-clock text-xs"></i>
                                                                <span>Menunggu Proses</span>
                                                            </span>
                                                        </template>
                                                    </dd>
                                                </div>
                                                <div class="flex items-start gap-4 py-1 text-left">
                                                    <dt
                                                        class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                        Status Lelang</dt>
                                                    <dd
                                                        class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left">
                                                        <template
                                                            x-if="stageLevel(selectedPoint?.tahap_saat_ini) >= 4">
                                                            <span class="text-teal-light font-semibold"
                                                                x-text="selectedPoint?.tahapLelang?.tanggal_penetapan !== '-' ? 'Tersedia (' + selectedPoint?.tahapLelang?.tanggal_penetapan + ')' : 'Penetapan Selesai'"></span>
                                                        </template>
                                                        <template x-if="stageLevel(selectedPoint?.tahap_saat_ini) < 4">
                                                            <span class="text-textMuted-light italic">(Belum Masuk
                                                                Tahap Lelang)</span>
                                                        </template>
                                                    </dd>
                                                </div>
                                                <div class="flex items-start gap-4 py-1 text-left">
                                                    <dt
                                                        class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                        Kontrak BAST</dt>
                                                    <dd
                                                        class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left truncate">
                                                        <template
                                                            x-if="stageLevel(selectedPoint?.tahap_saat_ini) >= 6">
                                                            <span class="text-teal-light font-semibold"
                                                                x-text="selectedPoint?.tahapSerahTerima?.nomor_kontrak || 'Kontrak BAST Selesai'"></span>
                                                        </template>
                                                        <template x-if="stageLevel(selectedPoint?.tahap_saat_ini) < 6">
                                                            <span class="text-textMuted-light italic">(Belum Tahap
                                                                Serah Terima)</span>
                                                        </template>
                                                    </dd>
                                                </div>
                                                <div class="flex items-start gap-4 py-1 text-left">
                                                    <dt
                                                        class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                        Kategori Hub</dt>
                                                    <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left"
                                                        x-text="selectedPoint?.statusHub || 'Penyangga'"></dd>
                                                </div>
                                                <div class="flex items-start gap-4 py-1 text-left">
                                                    <dt
                                                        class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                        Tanggal Daftar</dt>
                                                    <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left"
                                                        x-text="selectedPoint?.created_at || '-'"></dd>
                                                </div>
                                            </dl>
                                        </div>
                                    </div>

                                    <!-- BARIS 2: Kinerja dan Fisik Konstruksi (Kurva S) | Lampiran Dokumen Operasional -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 py-1 mt-6">
                                        <!-- Kolom 1: Kinerja dan Fisik Konstruksi (Kurva S) -->
                                        <div>
                                            <h4
                                                class="text-sm font-semibold tracking-tight text-textMain-light dark:text-textMain-dark mb-3 text-left">
                                                Kinerja dan Fisik Konstruksi (Kurva S)
                                            </h4>

                                            <template
                                                x-if="stageLevel(selectedPoint?.tahap_saat_ini) >= 5 || (selectedPoint?.progres || 0) > 0">
                                                <div>
                                                    <!-- Grafik Kurva S -->
                                                    <div x-data="{
                                                        activePoint: null,
                                                        get kurva() { return selectedPoint?.kurvaS || []; },
                                                        getX(idx) {
                                                            const n = Math.max(1, this.kurva.length - 1);
                                                            return 28 + (idx / n) * 362;
                                                        },
                                                        getY(val) {
                                                            if (val === null || val === undefined) return null;
                                                            return 95 - (val / 100) * 80;
                                                        },
                                                        getRencanaPath() {
                                                            if (!this.kurva.length) return '';
                                                            return this.kurva.map((k, i) => `${i === 0 ? 'M' : 'L'} ${this.getX(i)} ${this.getY(k.rencana)}`).join(' ');
                                                        },
                                                        getRealisasiPath() {
                                                            const valid = this.kurva.map((k, i) => ({ x: this.getX(i), y: this.getY(k.realisasi) })).filter(p => p.y !== null);
                                                            if (!valid.length) return '';
                                                            return valid.map((p, i) => `${i === 0 ? 'M' : 'L'} ${p.x} ${p.y}`).join(' ');
                                                        },
                                                        getFillPath() {
                                                            const valid = this.kurva.map((k, i) => ({ x: this.getX(i), y: this.getY(k.realisasi) })).filter(p => p.y !== null);
                                                            if (!valid.length) return '';
                                                            let path = `M ${valid[0].x} 95`;
                                                            valid.forEach(p => path += ` L ${p.x} ${p.y}`);
                                                            path += ` L ${valid[valid.length - 1].x} 95 Z`;
                                                            return path;
                                                        }
                                                    }"
                                                        class="p-4 rounded-xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 shadow-2xs relative">
                                                        <div
                                                            class="flex items-center justify-between text-xs font-semibold text-textMain-light dark:text-textMain-dark mb-3 pb-2 border-b border-gray-100 dark:border-gray-800">
                                                            <span class="truncate pr-2 text-teal-light font-bold"
                                                                x-text="selectedPoint?.konstruktor && selectedPoint.konstruktor !== '-' ? selectedPoint.konstruktor : 'Penyedia Jasa Konstruksi'"></span>
                                                            <div
                                                                class="flex items-center gap-3 font-normal text-[11px] shrink-0">
                                                                <span class="flex items-center gap-1"><span
                                                                        class="w-2 h-2 rounded-full bg-gray-400 inline-block"></span>
                                                                    Rencana (<span
                                                                        x-text="formatDec(selectedPoint?.rencana || 0) + '%'"></span>)</span>
                                                                <span class="flex items-center gap-1"><span
                                                                        class="w-2 h-2 rounded-full bg-teal-light inline-block"></span>
                                                                    Realisasi (<span
                                                                        x-text="formatDec(selectedPoint?.progres || 0) + '%'"></span>)</span>
                                                            </div>
                                                        </div>

                                                        <!-- Tooltip Box saat Hover -->
                                                        <div x-show="activePoint !== null" x-cloak
                                                            x-transition:enter="transition ease-out duration-150"
                                                            x-transition:enter-start="opacity-0 translate-y-1"
                                                            x-transition:enter-end="opacity-100 translate-y-0"
                                                            :style="activePoint ?
                                                                `left: ${Math.min(80, Math.max(20, (activePoint.x / 400) * 100))}%; top: 38px;` :
                                                                ''"
                                                            class="absolute z-30 pointer-events-none bg-gray-900/95 dark:bg-gray-800/95 text-white px-3 py-2 rounded-xl shadow-xl border border-gray-700 text-xs w-48 -translate-x-1/2 backdrop-blur-xs">
                                                            <div
                                                                class="font-bold border-b border-gray-700 pb-1 mb-1.5 flex justify-between items-center text-[11px]">
                                                                <span class="text-teal-300"
                                                                    x-text="activePoint?.label || activePoint?.minggu"></span>
                                                                <span
                                                                    class="text-[9px] text-gray-400 font-normal">Target
                                                                    vs Aktual</span>
                                                            </div>
                                                            <div class="space-y-1 text-[11px]">
                                                                <div class="flex justify-between items-center">
                                                                    <span
                                                                        class="flex items-center gap-1.5 text-gray-300">
                                                                        <span
                                                                            class="w-2 h-2 rounded-full bg-gray-400 inline-block"></span>
                                                                        Rencana:
                                                                    </span>
                                                                    <span class="font-mono font-semibold text-gray-100"
                                                                        x-text="formatDec(activePoint?.rencana || 0) + '%'"></span>
                                                                </div>
                                                                <div class="flex justify-between items-center">
                                                                    <span
                                                                        class="flex items-center gap-1.5 text-teal-300">
                                                                        <span
                                                                            class="w-2 h-2 rounded-full bg-teal-400 inline-block"></span>
                                                                        Realisasi:
                                                                    </span>
                                                                    <span class="font-mono font-bold text-teal-300"
                                                                        x-text="activePoint?.realisasi !== null && activePoint?.realisasi !== undefined ? formatDec(activePoint.realisasi) + '%' : 'Belum Ada'"></span>
                                                                </div>
                                                                <template
                                                                    x-if="activePoint?.realisasi !== null && activePoint?.realisasi !== undefined">
                                                                    <div
                                                                        class="flex justify-between items-center pt-1 border-t border-gray-800 text-[10px]">
                                                                        <span class="text-gray-400">Deviasi:</span>
                                                                        <span class="font-mono font-bold"
                                                                            :class="(activePoint.realisasi - activePoint
                                                                                .rencana) >= 0 ? 'text-teal-400' :
                                                                                'text-danger'"
                                                                            x-text="((activePoint.realisasi - activePoint.rencana) >= 0 ? '+' : '') + formatDec(activePoint.realisasi - activePoint.rencana) + '%'"></span>
                                                                    </div>
                                                                </template>
                                                            </div>
                                                        </div>

                                                        <div
                                                            class="w-full h-36 relative flex items-center justify-center py-1 select-none">
                                                            <svg class="w-full h-full overflow-visible"
                                                                viewBox="0 0 400 110" preserveAspectRatio="none">
                                                                <text x="23" y="18" text-anchor="end"
                                                                    fill="currentColor"
                                                                    class="text-[9px] font-mono font-medium text-gray-400 dark:text-gray-500">100%</text>
                                                                <text x="23" y="58" text-anchor="end"
                                                                    fill="currentColor"
                                                                    class="text-[9px] font-mono font-medium text-gray-400 dark:text-gray-500">50%</text>
                                                                <text x="23" y="98" text-anchor="end"
                                                                    fill="currentColor"
                                                                    class="text-[9px] font-mono font-medium text-gray-400 dark:text-gray-500">0%</text>

                                                                <line x1="28" y1="15" x2="395"
                                                                    y2="15" stroke="currentColor"
                                                                    class="text-gray-100 dark:text-gray-800"
                                                                    stroke-width="1" stroke-dasharray="2,2" />
                                                                <line x1="28" y1="55" x2="395"
                                                                    y2="55" stroke="currentColor"
                                                                    class="text-gray-100 dark:text-gray-800"
                                                                    stroke-width="1" stroke-dasharray="2,2" />
                                                                <line x1="28" y1="95" x2="395"
                                                                    y2="95" stroke="currentColor"
                                                                    class="text-gray-200 dark:text-gray-700"
                                                                    stroke-width="1" />

                                                                <template x-if="activePoint !== null">
                                                                    <line :x1="activePoint.x" y1="15"
                                                                        :x2="activePoint.x" y2="95"
                                                                        stroke="#0d9488" stroke-width="1"
                                                                        stroke-dasharray="3,3" opacity="0.6" />
                                                                </template>

                                                                <path :d="getRencanaPath()" fill="none"
                                                                    stroke="#94a3b8" stroke-width="1.8"
                                                                    stroke-dasharray="4,4" />
                                                                <path :d="getFillPath()"
                                                                    fill="url(#tealGradMenteri)" opacity="0.2" />
                                                                <path :d="getRealisasiPath()" fill="none"
                                                                    stroke="#0d9488" stroke-width="2.2"
                                                                    stroke-linecap="round" />

                                                                <template x-for="(k, idx) in kurva"
                                                                    :key="'dot-ren-' + idx">
                                                                    <circle :cx="getX(idx)"
                                                                        :cy="getY(k.rencana)" r="2.5" fill="#94a3b8"
                                                                        class="transition-all"
                                                                        :class="activePoint?.idx === idx ?
                                                                            'r-4 fill-gray-700 stroke-white stroke-2' :
                                                                            ''" />
                                                                </template>
                                                                <template x-for="(k, idx) in kurva"
                                                                    :key="'dot-real-' + idx">
                                                                    <template
                                                                        x-if="k.realisasi !== null && k.realisasi !== undefined">
                                                                        <circle :cx="getX(idx)"
                                                                            :cy="getY(k.realisasi)" r="3.5"
                                                                            fill="#0d9488" stroke="#ffffff"
                                                                            stroke-width="1.5" class="transition-all"
                                                                            :class="activePoint?.idx === idx ? 'r-5 stroke-2' :
                                                                                ''" />
                                                                    </template>
                                                                </template>

                                                                <template x-for="(k, idx) in kurva"
                                                                    :key="'hitbox-' + idx">
                                                                    <rect :x="getX(idx) - (180 / kurva.length)" y="10"
                                                                        :width="360 / kurva.length" height="90"
                                                                        fill="transparent" class="cursor-pointer"
                                                                        @mouseenter="activePoint = { idx, ...k, x: getX(idx), yRen: getY(k.rencana), yReal: getY(k.realisasi) }"
                                                                        @mouseleave="activePoint = null" />
                                                                </template>

                                                                <template x-if="activePoint !== null">
                                                                    <g class="pointer-events-none">
                                                                        <rect :x="activePoint.x - 17"
                                                                            :y="activePoint.yRen - 16" width="34"
                                                                            height="13" rx="3"
                                                                            fill="#334155" opacity="0.95" />
                                                                        <text :x="activePoint.x"
                                                                            :y="activePoint.yRen - 7"
                                                                            text-anchor="middle" fill="#ffffff"
                                                                            class="text-[7.5px] font-mono font-bold"
                                                                            x-text="formatDec(activePoint.rencana) + '%'"></text>

                                                                        <template
                                                                            x-if="activePoint.realisasi !== null && activePoint.realisasi !== undefined">
                                                                            <g>
                                                                                <rect :x="activePoint.x - 17"
                                                                                    :y="activePoint.yReal + 5"
                                                                                    width="34" height="13"
                                                                                    rx="3" fill="#0d9488"
                                                                                    opacity="0.95" />
                                                                                <text :x="activePoint.x"
                                                                                    :y="activePoint.yReal + 14"
                                                                                    text-anchor="middle"
                                                                                    fill="#ffffff"
                                                                                    class="text-[7.5px] font-mono font-bold"
                                                                                    x-text="formatDec(activePoint.realisasi) + '%'"></text>
                                                                            </g>
                                                                        </template>
                                                                    </g>
                                                                </template>

                                                                <defs>
                                                                    <linearGradient id="tealGradMenteri"
                                                                        x1="0%" y1="0%" x2="0%"
                                                                        y2="100%">
                                                                        <stop offset="0%" stop-color="#0d9488" />
                                                                        <stop offset="100%" stop-color="#0d9488"
                                                                            stop-opacity="0" />
                                                                    </linearGradient>
                                                                </defs>
                                                            </svg>
                                                        </div>

                                                        <div
                                                            class="flex justify-between text-[10px] text-textMuted-light mt-1 pt-2 border-t border-gray-100 dark:border-gray-800 pl-8 pr-1">
                                                            <template x-for="(k, idx) in kurva"
                                                                :key="'lbl-' + idx">
                                                                <span
                                                                    class="truncate text-center transition-colors cursor-pointer"
                                                                    :class="activePoint?.idx === idx ?
                                                                        'font-bold text-teal-light scale-105' : (k
                                                                            .realisasi !== null ?
                                                                            'font-semibold text-textMain-light dark:text-textMain-dark' :
                                                                            '')"
                                                                    @mouseenter="activePoint = { idx, ...k, x: getX(idx), yRen: getY(k.rencana), yReal: getY(k.realisasi) }"
                                                                    @mouseleave="activePoint = null"
                                                                    x-text="k.minggu"></span>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>

                                            <template
                                                x-if="stageLevel(selectedPoint?.tahap_saat_ini) < 5 && (selectedPoint?.progres || 0) <= 0">
                                                <div
                                                    class="p-6 rounded-2xl bg-teal-light/10 dark:bg-teal-light/20 border border-teal-light/30 text-center flex flex-col items-center justify-center gap-2.5 py-10">
                                                    <div
                                                        class="w-12 h-12 rounded-xl bg-teal-light/20 dark:bg-teal-light/30 flex items-center justify-center text-teal-light text-xl">
                                                        <i class="fa-solid fa-chart-line"></i>
                                                    </div>
                                                    <div
                                                        class="text-xs font-semibold text-textMain-light dark:text-textMain-dark">
                                                        Tahap Konstruksi Fisik Belum Dimulai</div>
                                                    <p
                                                        class="text-[11px] text-teal-light dark:text-teal-dark max-w-sm leading-relaxed">
                                                        Proyek ini masih dalam tahap <span class="font-semibold"
                                                            x-text="stageInfo(selectedPoint.tahap_saat_ini).label"></span>.
                                                        Pemantauan deviasi fisik, realisasi progres harian, dan grafik
                                                        Kurva S akan aktif secara otomatis setelah tahapan konstruksi
                                                        lapangan berjalan.
                                                    </p>
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Kolom 2: Lampiran Dokumen Operasional -->
                                        <div>
                                            <h4
                                                class="text-sm font-semibold tracking-tight text-textMain-light dark:text-textMain-dark mb-3 text-left">
                                                Lampiran Dokumen Operasional
                                            </h4>

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <!-- DED Dokumen Card -->
                                                <template x-if="stageLevel(selectedPoint?.tahap_saat_ini) >= 3">
                                                    <div
                                                        class="flex items-center justify-between p-4 rounded-xl bg-teal-light dark:bg-teal-dark text-white dark:text-white shadow-xs font-sans">
                                                        <div class="flex items-center min-w-0 flex-1">
                                                            <div
                                                                class="shrink-0 flex items-center justify-center w-10 h-10 rounded-xl bg-white/15 dark:bg-white/20 text-white mr-4">
                                                                <i class="fa-solid fa-file-pdf text-sm"></i>
                                                            </div>
                                                            <div class="flex-1 min-w-0 text-left ml-3">
                                                                <div class="text-xs font-semibold text-white truncate">
                                                                    Detail Engineering Design (DED)</div>
                                                                <div class="text-[11px] font-normal text-white/90 truncate mt-0.5"
                                                                    x-text="selectedPoint?.tahapDed?.nomor_dokumen || 'DED-KNMP/2026/01'">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>

                                                <!-- Dokumen Lelang & Tender Card -->
                                                <template x-if="stageLevel(selectedPoint?.tahap_saat_ini) >= 4">
                                                    <div
                                                        class="flex items-center justify-between p-4 rounded-xl bg-teal-light dark:bg-teal-dark text-white dark:text-white shadow-xs font-sans">
                                                        <div class="flex items-center min-w-0 flex-1">
                                                            <div
                                                                class="shrink-0 flex items-center justify-center w-10 h-10 rounded-xl bg-white/15 dark:bg-white/20 text-white mr-4">
                                                                <i class="fa-solid fa-file-contract text-sm"></i>
                                                            </div>
                                                            <div class="flex-1 min-w-0 text-left ml-3">
                                                                <div class="text-xs font-semibold text-white truncate">
                                                                    Dokumen Tender & Lelang</div>
                                                                <div class="text-[11px] font-normal text-white/90 truncate mt-0.5"
                                                                    x-text="selectedPoint?.tahapLelang?.tanggal_penetapan !== '-' ? 'Penetapan: ' + selectedPoint?.tahapLelang?.tanggal_penetapan : 'Paket Dokumen Pengadaan'">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>

                                                <!-- Dokumen BAST Card -->
                                                <template x-if="stageLevel(selectedPoint?.tahap_saat_ini) >= 6">
                                                    <div
                                                        class="flex items-center justify-between p-4 rounded-xl bg-teal-light dark:bg-teal-dark text-white dark:text-white shadow-xs font-sans sm:col-span-2">
                                                        <div class="flex items-center min-w-0 flex-1">
                                                            <div
                                                                class="shrink-0 flex items-center justify-center w-10 h-10 rounded-xl bg-white/15 dark:bg-white/20 text-white mr-4">
                                                                <i class="fa-solid fa-file-circle-check text-sm"></i>
                                                            </div>
                                                            <div class="flex-1 min-w-0 text-left ml-3">
                                                                <div class="text-xs font-semibold text-white truncate">
                                                                    Berita Acara Serah Terima (BAST)</div>
                                                                <div class="text-[11px] font-normal text-white/90 truncate mt-0.5"
                                                                    x-text="selectedPoint?.tahapSerahTerima?.nomor_kontrak || 'BAST / Penyerahan Aset'">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>

                                                <template x-if="stageLevel(selectedPoint?.tahap_saat_ini) < 3">
                                                    <div
                                                        class="p-6 rounded-2xl bg-teal-light/10 dark:bg-teal-light/20 border border-teal-light/30 text-center flex flex-col items-center justify-center gap-2.5 py-10 sm:col-span-2">
                                                        <div
                                                            class="w-12 h-12 rounded-xl bg-teal-light/20 dark:bg-teal-light/30 flex items-center justify-center text-teal-light text-xl">
                                                            <i class="fa-solid fa-folder-open"></i>
                                                        </div>
                                                        <div
                                                            class="text-xs font-semibold text-textMain-light dark:text-textMain-dark">
                                                            Belum Ada Dokumen Operasional</div>
                                                        <p
                                                            class="text-[11px] text-teal-light dark:text-teal-dark max-w-sm leading-relaxed">
                                                            Dokumen resmi DED, Tender Lelang, dan BAST akan muncul
                                                            secara bertahap saat proyek mencapai tahapan terkait.
                                                        </p>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- BARIS 3: Foto Bukti Pendukung (Before & After) | Riwayat Kronologis -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 py-1 mt-6">
                                        <!-- Kolom 1: Foto Bukti Pendukung (Before & After) -->
                                        <div>
                                            <h4
                                                class="text-sm font-semibold tracking-tight text-textMain-light dark:text-textMain-dark mb-3 text-left">
                                                Foto Bukti Pendukung (Before & After)
                                            </h4>

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                                                <!-- Kondisi Awal Before -->
                                                <div class="space-y-2.5">
                                                    <div class="text-xs font-medium text-textMuted-light">Kondisi Awal
                                                        (Before)</div>
                                                    <template
                                                        x-if="selectedPoint?.fotosBefore && selectedPoint?.fotosBefore.length > 0">
                                                        <div class="space-y-3">
                                                            <template x-for="(foto, idx) in selectedPoint.fotosBefore"
                                                                :key="'bef-' + idx">
                                                                <a :href="foto.url" target="_blank"
                                                                    style="height: 180px; min-height: 180px;"
                                                                    class="block w-full rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-800 relative group shadow-xs border-0 shrink-0">
                                                                    <img :src="foto.url" :alt="foto.nama"
                                                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                                                    <span
                                                                        class="absolute bottom-1.5 left-1.5 px-2 py-0.5 rounded text-[10px] font-semibold bg-gray-900/80 text-white backdrop-blur-xs">Before</span>
                                                                </a>
                                                            </template>
                                                        </div>
                                                    </template>
                                                    <template
                                                        x-if="!selectedPoint?.fotosBefore || selectedPoint?.fotosBefore.length === 0">
                                                        <div style="height: 180px; min-height: 180px;"
                                                            class="w-full rounded-xl bg-gray-100 dark:bg-gray-800/50 flex flex-col items-center justify-center p-3 text-center border-0 shadow-xs shrink-0">
                                                            <i
                                                                class="fa-regular fa-image text-gray-300 dark:text-gray-600 text-xl mb-1"></i>
                                                            <span class="text-[11px] text-textMuted-light">Foto Awal
                                                                belum diunggah</span>
                                                        </div>
                                                    </template>
                                                </div>

                                                <!-- Progres After -->
                                                <div class="space-y-2.5">
                                                    <div
                                                        class="text-xs font-medium text-textMain-light dark:text-textMain-dark">
                                                        Progres Lapangan (After)</div>
                                                    <template
                                                        x-if="selectedPoint?.fotosAfter && selectedPoint?.fotosAfter.length > 0">
                                                        <div class="space-y-3">
                                                            <template x-for="(foto, idx) in selectedPoint.fotosAfter"
                                                                :key="'aft-' + idx">
                                                                <a :href="foto.url" target="_blank"
                                                                    style="height: 180px; min-height: 180px;"
                                                                    class="block w-full rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-800 relative group shadow-xs border-0 shrink-0">
                                                                    <img :src="foto.url" :alt="foto.nama"
                                                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                                                    <span
                                                                        class="absolute bottom-1.5 left-1.5 px-2 py-0.5 rounded text-[10px] font-semibold bg-teal-light text-white backdrop-blur-xs">Progress</span>
                                                                </a>
                                                            </template>
                                                        </div>
                                                    </template>
                                                    <template
                                                        x-if="(!selectedPoint?.fotosAfter || selectedPoint?.fotosAfter.length === 0) && stageLevel(selectedPoint?.tahap_saat_ini) >= 5">
                                                        <div style="height: 180px; min-height: 180px;"
                                                            class="w-full rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-800 relative shadow-xs border-0 shrink-0">
                                                            <img src="{{ asset('assets/images/placeholder-project.jpg') }}"
                                                                onerror="this.src='https://images.unsplash.com/photo-1541888946425-d09bb180c6f3?auto=format&fit=crop&w=600&q=80'"
                                                                alt="Ilustrasi Proyek"
                                                                class="w-full h-full object-cover">
                                                            <span
                                                                class="absolute bottom-1.5 left-1.5 px-2 py-0.5 rounded text-[10px] font-semibold bg-teal-light text-white backdrop-blur-xs"
                                                                x-text="formatDec(selectedPoint?.progres) + '% Progress'"></span>
                                                        </div>
                                                    </template>
                                                    <template
                                                        x-if="(!selectedPoint?.fotosAfter || selectedPoint?.fotosAfter.length === 0) && stageLevel(selectedPoint?.tahap_saat_ini) < 5">
                                                        <div style="height: 180px; min-height: 180px;"
                                                            class="w-full rounded-xl bg-gray-100 dark:bg-gray-800/50 flex flex-col items-center justify-center p-3 text-center border-0 shadow-xs shrink-0">
                                                            <i
                                                                class="fa-solid fa-camera-rotate text-gray-300 dark:text-gray-600 text-xl mb-1"></i>
                                                            <span class="text-[11px] text-textMuted-light">Foto progres
                                                                akan diunggah bertahap</span>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Kolom 2: Riwayat Kronologis -->
                                        <div>
                                            <h4
                                                class="text-sm font-semibold tracking-tight text-textMain-light dark:text-textMain-dark mb-3 text-left">
                                                Riwayat Kronologis & Tahapan Siklus
                                            </h4>

                                            <dl class="space-y-2.5 text-xs">
                                                <!-- Tahap 1: Usulan -->
                                                <template x-if="stageLevel(selectedPoint?.tahap_saat_ini) >= 1">
                                                    <div class="flex items-start gap-4 py-1 text-left">
                                                        <dt
                                                            class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                            Tahap 1: Usulan Lokasi</dt>
                                                        <dd
                                                            class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left">
                                                            <div class="flex items-center gap-2 flex-wrap">
                                                                <span
                                                                    x-text="selectedPoint?.tahapUsulan?.tanggal || selectedPoint?.created_at || '-'"></span>
                                                                <span
                                                                    class="px-2 py-0.5 rounded-md bg-teal-100 text-teal-800 dark:bg-teal-900/50 dark:text-teal-300 text-[10px] font-semibold">Selesai</span>
                                                            </div>
                                                            <div class="text-[11px] font-normal text-textMuted-light dark:text-textMuted-dark mt-1"
                                                                x-text="`Catatan: ${selectedPoint?.tahapUsulan?.catatan || 'Verifikasi usulan lokasi selesai.'}`">
                                                            </div>
                                                        </dd>
                                                    </div>
                                                </template>

                                                <!-- Tahap 2: Survei -->
                                                <template x-if="stageLevel(selectedPoint?.tahap_saat_ini) >= 2">
                                                    <div class="flex items-start gap-4 py-1 text-left">
                                                        <dt
                                                            class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                            Tahap 2: Survei Lapangan</dt>
                                                        <dd
                                                            class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left">
                                                            <div class="flex items-center gap-2 flex-wrap">
                                                                <span
                                                                    x-text="selectedPoint?.tahapSurvey?.tanggal !== '-' ? selectedPoint?.tahapSurvey?.tanggal : (selectedPoint?.created_at || '-')"></span>
                                                                <span
                                                                    class="px-2 py-0.5 rounded-md bg-teal-100 text-teal-800 dark:bg-teal-900/50 dark:text-teal-300 text-[10px] font-semibold">Selesai</span>
                                                            </div>
                                                            <div class="text-[11px] font-normal text-textMuted-light dark:text-textMuted-dark mt-1"
                                                                x-text="`Catatan: ${selectedPoint?.tahapSurvey?.catatan || 'Survei geoteknik & hidrologi.'}`">
                                                            </div>
                                                        </dd>
                                                    </div>
                                                </template>

                                                <!-- Tahap 3: DED -->
                                                <template x-if="stageLevel(selectedPoint?.tahap_saat_ini) >= 3">
                                                    <div class="flex items-start gap-4 py-1 text-left">
                                                        <dt
                                                            class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                            Tahap 3: Dokumen DED</dt>
                                                        <dd
                                                            class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left">
                                                            <div class="flex items-center gap-2 flex-wrap">
                                                                <span
                                                                    x-text="selectedPoint?.tahapDed?.tanggal_pengesahan !== '-' ? selectedPoint?.tahapDed?.tanggal_pengesahan : (selectedPoint?.created_at || '-')"></span>
                                                                <span
                                                                    class="px-2 py-0.5 rounded-md bg-teal-100 text-teal-800 dark:bg-teal-900/50 dark:text-teal-300 text-[10px] font-semibold">Disahkan</span>
                                                            </div>
                                                            <div class="text-[11px] font-normal text-textMuted-light dark:text-textMuted-dark mt-1"
                                                                x-text="`Nomor DED: ${selectedPoint?.tahapDed?.nomor_dokumen || '-'}`">
                                                            </div>
                                                        </dd>
                                                    </div>
                                                </template>

                                                <!-- Tahap 4: Lelang -->
                                                <template x-if="stageLevel(selectedPoint?.tahap_saat_ini) >= 4">
                                                    <div class="flex items-start gap-4 py-1 text-left">
                                                        <dt
                                                            class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                            Tahap 4: Lelang & Tender</dt>
                                                        <dd
                                                            class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left">
                                                            <div class="flex items-center gap-2 flex-wrap">
                                                                <span
                                                                    x-text="selectedPoint?.tahapLelang?.tanggal_penetapan !== '-' ? selectedPoint?.tahapLelang?.tanggal_penetapan : (selectedPoint?.created_at || '-')"></span>
                                                                <span
                                                                    class="px-2 py-0.5 rounded-md bg-teal-100 text-teal-800 dark:bg-teal-900/50 dark:text-teal-300 text-[10px] font-semibold">Selesai</span>
                                                            </div>
                                                            <div class="text-[11px] font-normal text-textMuted-light dark:text-textMuted-dark mt-1"
                                                                x-text="`Catatan: ${selectedPoint?.tahapLelang?.catatan || 'Pengadaan penyedia konstruksi.'}`">
                                                            </div>
                                                        </dd>
                                                    </div>
                                                </template>

                                                <!-- Tahap 5: Konstruksi -->
                                                <template x-if="stageLevel(selectedPoint?.tahap_saat_ini) >= 5">
                                                    <div class="flex items-start gap-4 py-1 text-left">
                                                        <dt
                                                            class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                            Tahap 5: Konstruksi Fisik</dt>
                                                        <dd
                                                            class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left">
                                                            <div class="flex items-center gap-2 flex-wrap">
                                                                <span x-text="selectedPoint?.created_at || '-'"></span>
                                                                <span
                                                                    class="px-2 py-0.5 rounded-md bg-teal-100 text-teal-800 dark:bg-teal-900/50 dark:text-teal-300 text-[10px] font-semibold"
                                                                    x-text="stageLevel(selectedPoint?.tahap_saat_ini) > 5 ? 'Selesai (100%)' : (formatDec(selectedPoint?.progres) + '% Progress')"></span>
                                                            </div>
                                                            <div class="text-[11px] font-normal text-textMuted-light dark:text-textMuted-dark mt-1 truncate"
                                                                x-text="`Pelaksana: ${selectedPoint?.konstruktor || '-'}`">
                                                            </div>
                                                        </dd>
                                                    </div>
                                                </template>

                                                <!-- Tahap 6: Serah Terima -->
                                                <template x-if="stageLevel(selectedPoint?.tahap_saat_ini) >= 6">
                                                    <div class="flex items-start gap-4 py-1 text-left">
                                                        <dt
                                                            class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                            Tahap 6: BAST / Serah Terima</dt>
                                                        <dd
                                                            class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left">
                                                            <div class="flex items-center gap-2 flex-wrap">
                                                                <span
                                                                    x-text="selectedPoint?.tahapSerahTerima?.tanggal !== '-' ? selectedPoint?.tahapSerahTerima?.tanggal : (selectedPoint?.created_at || '-')"></span>
                                                                <span
                                                                    class="px-2 py-0.5 rounded-md bg-teal-100 text-teal-800 dark:bg-teal-900/50 dark:text-teal-300 text-[10px] font-semibold">Selesai</span>
                                                            </div>
                                                            <div class="text-[11px] font-normal text-textMuted-light dark:text-textMuted-dark mt-1 font-mono truncate"
                                                                x-text="`Kontrak: ${selectedPoint?.tahapSerahTerima?.nomor_kontrak || '-'}`">
                                                            </div>
                                                        </dd>
                                                    </div>
                                                </template>
                                            </dl>
                                        </div>
                                    </div>

                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function menteriMapApp() {
            return {
                darkMode: document.documentElement.classList.contains('dark'),
                allPoints: @json($stats['map_points'] ?? []),
                selectedPoint: null,
                map: null,
                markers: [],
                markerGroup: null,
                tileLayer: null,
                lightTileUrl: 'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png',
                darkTileUrl: 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png',

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

                stageInfo(stage) {
                    const level = this.stageLevel(stage);
                    const map = {
                        1: {
                            label: 'Tahap 1: Usulan Lokasi',
                            badgeClass: 'bg-teal-light/10 text-teal-light border-teal-light dark:bg-teal-light/20 dark:border-teal-dark dark:text-teal-dark',
                            iconClass: 'bg-teal-light/20 text-teal-light dark:bg-teal-light/30 dark:text-teal-dark',
                            icon: 'fa-file-invoice',
                            desc: 'Usulan calon lokasi proyek ini telah terdaftar dalam sistem dan sedang dalam tahap peninjauan awal serta verifikasi lapangan.'
                        },
                        2: {
                            label: 'Tahap 2: Survei Lapangan',
                            badgeClass: 'bg-teal-light/10 text-teal-light border-teal-light dark:bg-teal-light/20 dark:border-teal-dark dark:text-teal-dark',
                            iconClass: 'bg-teal-light/20 text-teal-light dark:bg-teal-light/30 dark:text-teal-dark',
                            icon: 'fa-compass-drafting',
                            desc: 'Tim teknis saat ini sedang melaksanakan survei lapangan (studi geoteknik, batimetri, & hidrologi) sebagai landasan perancangan DED.'
                        },
                        3: {
                            label: 'Tahap 3: Dokumen DED',
                            badgeClass: 'bg-teal-light/10 text-teal-light border-teal-light dark:bg-teal-light/20 dark:border-teal-dark dark:text-teal-dark',
                            iconClass: 'bg-teal-light/20 text-teal-light dark:bg-teal-light/30 dark:text-teal-dark',
                            icon: 'fa-pen-ruler',
                            desc: 'Penyusunan dan pemeriksaan teknis Detail Engineering Design (DED) serta Rencana Anggaran Biaya (RAB) sedang berlangsung.'
                        },
                        4: {
                            label: 'Tahap 4: Lelang & Tender',
                            badgeClass: 'bg-teal-light/10 text-teal-light border-teal-light dark:bg-teal-light/20 dark:border-teal-dark dark:text-teal-dark',
                            iconClass: 'bg-teal-light/20 text-teal-light dark:bg-teal-light/30 dark:text-teal-dark',
                            icon: 'fa-gavel',
                            desc: 'Dokumen DED telah disahkan resmi. Proyek saat ini sedang dalam proses pengadaan penyedia jasa konstruksi (tender/lelang).'
                        },
                        5: {
                            label: 'Tahap 5: Konstruksi Fisik',
                            badgeClass: 'bg-teal-light/10 text-teal-light border-teal-light dark:bg-teal-light/20 dark:border-teal-dark dark:text-teal-dark',
                            iconClass: 'bg-teal-light/20 text-teal-light dark:bg-teal-light/30 dark:text-teal-dark',
                            icon: 'fa-helmet-safety',
                            desc: 'Pekerjaan konstruksi fisik sedang berlangsung di lapangan dan dipantau kinerjanya melalui Kurva S secara berkala.'
                        },
                        6: {
                            label: 'Tahap 6: BAST / Serah Terima',
                            badgeClass: 'bg-teal-light/10 text-teal-light border-teal-light dark:bg-teal-light/20 dark:border-teal-dark dark:text-teal-dark',
                            iconClass: 'bg-teal-light/20 text-teal-light dark:bg-teal-light/30 dark:text-teal-dark',
                            icon: 'fa-circle-check',
                            desc: 'Seluruh tahapan pekerjaan konstruksi fisik telah rampung 100% dan telah diserahterimakan melalui Berita Acara Serah Terima (BAST).'
                        }
                    };
                    return map[level] || map[1];
                },

                formatDec(val) {
                    if (val === null || val === undefined || val === '') return '0,00';
                    return Number(val || 0).toLocaleString('id-ID', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                },

                closeDetailPanel() {
                    this.selectedPoint = null;
                    this.map.flyTo([-1.5, 118.0], 5, {
                        duration: 1.0
                    });
                },

                init() {
                    // Initialize Leaflet Map full screen
                    this.map = L.map('menteriMap', {
                        zoomControl: false,
                        attributionControl: false
                    }).setView([-1.5, 118.0], 5);

                    // Add zoom control to bottom right above floating detail modal
                    L.control.zoom({
                        position: 'bottomright'
                    }).addTo(this.map);

                    // Dark/Light tile layer initial setting
                    this.tileLayer = L.tileLayer(this.darkMode ? this.darkTileUrl : this.lightTileUrl, {
                        maxZoom: 19,
                        subdomains: 'abcd'
                    }).addTo(this.map);

                    this.markerGroup = L.layerGroup().addTo(this.map);
                    this.renderMarkers();
                },

                toggleTheme() {
                    this.darkMode = !this.darkMode;
                    if (this.darkMode) {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                        if (this.tileLayer) this.tileLayer.setUrl(this.darkTileUrl);
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                        if (this.tileLayer) this.tileLayer.setUrl(this.lightTileUrl);
                    }
                },

                renderMarkers() {
                    if (!this.markerGroup) return;
                    this.markerGroup.clearLayers();
                    this.markers = [];

                    this.allPoints.forEach(p => {
                        if (p.latitude && p.longitude) {
                            const isSerahTerima = p.tahap === 'serah_terima';
                            const className = isSerahTerima ? 'pin-serah-terima' : 'pin-konstruksi';

                            const icon = L.divIcon({
                                className: 'custom-menteri-pin',
                                iconAnchor: [9, 9],
                                popupAnchor: [0, -12],
                                html: `<div class="${className}"></div>`
                            });

                            const marker = L.marker([p.latitude, p.longitude], {
                                icon: icon
                            });
                            marker.on('click', () => {
                                this.selectedPoint = p;
                                const targetZoom = 14;
                                const pointPx = this.map.project([p.latitude, p.longitude], targetZoom);
                                // Geser titik pandang peta ke kiri supaya posisi pin tepat berada di tengah area 25% sebelah kiri yang tidak tertutup offcanvas 75%
                                const offsetX = this.map.getSize().x * 0.375;
                                const targetLatLng = this.map.unproject(L.point(pointPx.x + offsetX, pointPx
                                    .y), targetZoom);
                                this.map.flyTo(targetLatLng, targetZoom, {
                                    duration: 1.2
                                });
                            });

                            marker.bindTooltip(
                                `<b>${p.nama}</b><br/><span style="font-size:11px; opacity:0.85; font-weight:500;">${p.tahap_label} (${p.progres}%)</span>`, {
                                    direction: 'top',
                                    offset: [0, -10]
                                });

                            this.markerGroup.addLayer(marker);
                            this.markers.push({
                                point: p,
                                marker: marker
                            });
                        }
                    });
                }
            };
        }
    </script>
</body>

</html>

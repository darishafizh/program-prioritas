@extends('layouts.app')

@section('title', 'KNMP - Siklus & Operasional')

@section('content')
    <div x-data="{ filterTahap: '' }">
        <style>
            .custom-bg-blue {
                background-color: #eff6ff;
            }

            .dark .custom-bg-blue {
                background-color: rgba(30, 58, 138, 0.4);
            }

            .custom-text-blue {
                color: #3b82f6;
            }

            .dark .custom-text-blue {
                color: #60a5fa;
            }

            .custom-bg-teal {
                background-color: #f0fdfa;
            }

            .dark .custom-bg-teal {
                background-color: rgba(19, 78, 74, 0.4);
            }

            .custom-text-teal {
                color: #0d9488;
            }

            .dark .custom-text-teal {
                color: #2dd4bf;
            }
        </style>
        <!-- Header & Global Filters -->
        <div class="mb-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h2 class="text-xl font-semibold tracking-tight">Dashboard Siklus & Operasional</h2>
                <p class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1">Pantauan Siklus Calon
                    Lokasi dan Status Operasional Proyek KNMP</p>
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
            </form>
        </div>

        <!-- Narrative Storytelling Block -->
        <div
            class="mb-6 relative overflow-hidden rounded-3xl bg-gradient-to-br from-bgSurface-light to-blue-50 dark:from-bgSurface-dark dark:to-blue-900/10 border border-teal-light/20 dark:border-teal-light/10 p-6 sm:p-8">
            <div class="absolute top-0 right-0 p-8 opacity-5 dark:opacity-10 pointer-events-none">
                <i class="fa-solid fa-quote-right text-9xl text-teal-light"></i>
            </div>
            <div class="relative z-10 max-w-4xl">
                <div
                    class="flex items-center gap-2 text-teal-light dark:text-teal-400 font-medium text-xs tracking-widest uppercase mb-3">
                    <span class="w-2 h-2 rounded-full bg-teal-light animate-pulse"></span> Analisis Siklus & Operasional
                </div>
                <p class="text-xs text-textMain-light dark:text-textMain-dark leading-relaxed font-medium">
                    {!! $stats['narasi'] ?? '' !!}
                </p>
            </div>
        </div>

        <div class="flex flex-col gap-6 mb-6">
            <!-- Pipeline Pengajuan Calon Lokasi -->
            <div
                class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 flex flex-col">
                <h3
                    class="text-xs font-medium mb-8 flex items-center gap-2 uppercase tracking-wider text-textMuted-light dark:text-textMuted-dark">
                    <i class="fa-solid fa-file-signature text-blue-500"></i> Siklus Pengajuan Calon Lokasi
                </h3>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 relative">

                    <!-- Step 1: Pengajuan -->
                    <div class="relative group">
                        <div
                            class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-4 flex flex-col relative z-10 h-full group-hover:border-blue-500 transition-colors shadow-sm">
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="w-8 h-8 rounded-xl custom-bg-blue custom-text-blue flex items-center justify-center">
                                    <i class="fa-solid fa-file-import text-xs"></i>
                                </div>
                                <span
                                    class="text-[10px] font-bold text-gray-300 dark:text-gray-600 bg-gray-50 dark:bg-gray-800 px-2 py-0.5 rounded-md">01</span>
                            </div>
                            <div
                                class="font-medium text-[10px] uppercase text-textMuted-light dark:text-textMuted-dark tracking-wider mb-1">
                                Pengajuan</div>
                            <div class="text-xl font-bold text-textMain-light dark:text-textMain-dark mt-auto">
                                {{ $stats['pipeline_pengajuan']['pengajuan'] ?? 0 }} <span
                                    class="text-[10px] font-normal text-textMuted-light">Data</span></div>
                        </div>
                        <div
                            class="hidden lg:block absolute -right-2.5 top-1/2 -translate-y-1/2 z-20 text-gray-300 dark:text-gray-600">
                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </div>
                    </div>

                    <!-- Step 2: Verif Admin -->
                    <div class="relative group">
                        <div
                            class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-4 flex flex-col relative z-10 h-full group-hover:border-blue-500 transition-colors shadow-sm">
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="w-8 h-8 rounded-xl custom-bg-blue custom-text-blue flex items-center justify-center">
                                    <i class="fa-solid fa-clipboard-check text-xs"></i>
                                </div>
                                <span
                                    class="text-[10px] font-bold text-gray-300 dark:text-gray-600 bg-gray-50 dark:bg-gray-800 px-2 py-0.5 rounded-md">02</span>
                            </div>
                            <div
                                class="font-medium text-[10px] uppercase text-textMuted-light dark:text-textMuted-dark tracking-wider mb-1">
                                Verif Admin</div>
                            <div class="text-xl font-bold text-textMain-light dark:text-textMain-dark mt-auto">
                                {{ $stats['pipeline_pengajuan']['verif_admin'] ?? 0 }} <span
                                    class="text-[10px] font-normal text-textMuted-light">Data</span></div>
                        </div>
                        <div
                            class="hidden lg:block absolute -right-2.5 top-1/2 -translate-y-1/2 z-20 text-gray-300 dark:text-gray-600">
                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </div>
                    </div>

                    <!-- Step 3: Verif Teknis -->
                    <div class="relative group">
                        <div
                            class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-4 flex flex-col relative z-10 h-full group-hover:border-blue-500 transition-colors shadow-sm">
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="w-8 h-8 rounded-xl custom-bg-blue custom-text-blue flex items-center justify-center">
                                    <i class="fa-solid fa-microscope text-xs"></i>
                                </div>
                                <span
                                    class="text-[10px] font-bold text-gray-300 dark:text-gray-600 bg-gray-50 dark:bg-gray-800 px-2 py-0.5 rounded-md">03</span>
                            </div>
                            <div
                                class="font-medium text-[10px] uppercase text-textMuted-light dark:text-textMuted-dark tracking-wider mb-1">
                                Verif Teknis</div>
                            <div class="text-xl font-bold text-textMain-light dark:text-textMain-dark mt-auto">
                                {{ $stats['pipeline_pengajuan']['verif_teknis'] ?? 0 }} <span
                                    class="text-[10px] font-normal text-textMuted-light">Data</span></div>
                        </div>
                        <div
                            class="hidden lg:block absolute -right-2.5 top-1/2 -translate-y-1/2 z-20 text-gray-300 dark:text-gray-600">
                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </div>
                    </div>

                    <!-- Step 4: BA Calon -->
                    <div class="relative group">
                        <div
                            class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-4 flex flex-col relative z-10 h-full group-hover:border-blue-500 transition-colors shadow-sm">
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="w-8 h-8 rounded-xl custom-bg-blue custom-text-blue flex items-center justify-center">
                                    <i class="fa-solid fa-file-contract text-xs"></i>
                                </div>
                                <span
                                    class="text-[10px] font-bold text-gray-300 dark:text-gray-600 bg-gray-50 dark:bg-gray-800 px-2 py-0.5 rounded-md">04</span>
                            </div>
                            <div
                                class="font-medium text-[10px] uppercase text-textMuted-light dark:text-textMuted-dark tracking-wider mb-1">
                                BA Calon</div>
                            <div class="text-xl font-bold text-textMain-light dark:text-textMain-dark mt-auto">
                                {{ $stats['pipeline_pengajuan']['ba_calon'] ?? 0 }} <span
                                    class="text-[10px] font-normal text-textMuted-light">Data</span></div>
                        </div>
                        <div
                            class="hidden lg:block absolute -right-2.5 top-1/2 -translate-y-1/2 z-20 text-gray-300 dark:text-gray-600">
                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </div>
                    </div>

                    <!-- Step 5: BA Aktivasi -->
                    <div class="relative group">
                        <div
                            class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-4 flex flex-col relative z-10 h-full group-hover:border-blue-500 transition-colors shadow-sm">
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="w-8 h-8 rounded-xl custom-bg-blue custom-text-blue flex items-center justify-center">
                                    <i class="fa-solid fa-file-shield text-xs"></i>
                                </div>
                                <span
                                    class="text-[10px] font-bold text-gray-300 dark:text-gray-600 bg-gray-50 dark:bg-gray-800 px-2 py-0.5 rounded-md">05</span>
                            </div>
                            <div
                                class="font-medium text-[10px] uppercase text-textMuted-light dark:text-textMuted-dark tracking-wider mb-1">
                                BA Aktivasi</div>
                            <div class="text-xl font-bold text-textMain-light dark:text-textMain-dark mt-auto">
                                {{ $stats['pipeline_pengajuan']['ba_aktivasi'] ?? 0 }} <span
                                    class="text-[10px] font-normal text-textMuted-light">Data</span></div>
                        </div>
                        <div
                            class="hidden lg:block absolute -right-2.5 top-1/2 -translate-y-1/2 z-20 text-gray-300 dark:text-gray-600">
                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </div>
                    </div>

                    <!-- Step 6: Penetapan -->
                    <div class="relative group">
                        <div
                            class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-4 flex flex-col relative z-10 h-full group-hover:border-blue-500 transition-colors shadow-sm">
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="w-8 h-8 rounded-xl custom-bg-blue custom-text-blue flex items-center justify-center">
                                    <i class="fa-solid fa-check-double text-xs"></i>
                                </div>
                                <span
                                    class="text-[10px] font-bold text-gray-300 dark:text-gray-600 bg-gray-50 dark:bg-gray-800 px-2 py-0.5 rounded-md">06</span>
                            </div>
                            <div
                                class="font-medium text-[10px] uppercase text-textMuted-light dark:text-textMuted-dark tracking-wider mb-1">
                                Penetapan</div>
                            <div class="text-xl font-bold text-textMain-light dark:text-textMain-dark mt-auto">
                                {{ $stats['pipeline_pengajuan']['penetapan'] ?? 0 }} <span
                                    class="text-[10px] font-normal text-textMuted-light">Lokasi</span></div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Pipeline Siklus Usulan Konstruksi -->
            <div
                class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 flex flex-col">
                <h3
                    class="text-xs font-medium mb-8 flex items-center gap-2 uppercase tracking-wider text-textMuted-light dark:text-textMuted-dark">
                    <i class="fa-solid fa-timeline text-teal-light dark:text-teal-400"></i> Siklus Usulan & Konstruksi KNMP
                </h3>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 relative">

                    <!-- Step 1: Usulan -->
                    <div class="relative group">
                        <div
                            class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-4 flex flex-col relative z-10 h-full group-hover:border-teal-light transition-colors shadow-sm">
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="w-8 h-8 rounded-xl custom-bg-teal custom-text-teal flex items-center justify-center">
                                    <i class="fa-solid fa-file-signature text-xs"></i>
                                </div>
                                <span
                                    class="text-[10px] font-bold text-gray-300 dark:text-gray-600 bg-gray-50 dark:bg-gray-800 px-2 py-0.5 rounded-md">01</span>
                            </div>
                            <div
                                class="font-medium text-[10px] uppercase text-textMuted-light dark:text-textMuted-dark tracking-wider mb-1">
                                Usulan</div>
                            <div class="text-xl font-bold text-textMain-light dark:text-textMain-dark mt-auto">
                                {{ $stats['pipeline']['usulan'] ?? 0 }} <span
                                    class="text-[10px] font-normal text-textMuted-light">Proyek</span></div>
                        </div>
                        <div
                            class="hidden lg:block absolute -right-2.5 top-1/2 -translate-y-1/2 z-20 text-gray-300 dark:text-gray-600">
                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </div>
                    </div>

                    <!-- Step 2: Survey -->
                    <div class="relative group">
                        <div
                            class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-4 flex flex-col relative z-10 h-full group-hover:border-teal-light transition-colors shadow-sm">
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="w-8 h-8 rounded-xl custom-bg-teal custom-text-teal flex items-center justify-center">
                                    <i class="fa-solid fa-map-location-dot text-xs"></i>
                                </div>
                                <span
                                    class="text-[10px] font-bold text-gray-300 dark:text-gray-600 bg-gray-50 dark:bg-gray-800 px-2 py-0.5 rounded-md">02</span>
                            </div>
                            <div
                                class="font-medium text-[10px] uppercase text-textMuted-light dark:text-textMuted-dark tracking-wider mb-1">
                                Survey</div>
                            <div class="text-xl font-bold text-textMain-light dark:text-textMain-dark mt-auto">
                                {{ $stats['pipeline']['survei'] ?? 0 }} <span
                                    class="text-[10px] font-normal text-textMuted-light">Proyek</span></div>
                        </div>
                        <div
                            class="hidden lg:block absolute -right-2.5 top-1/2 -translate-y-1/2 z-20 text-gray-300 dark:text-gray-600">
                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </div>
                    </div>

                    <!-- Step 3: DED -->
                    <div class="relative group">
                        <div
                            class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-4 flex flex-col relative z-10 h-full group-hover:border-teal-light transition-colors shadow-sm">
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="w-8 h-8 rounded-xl custom-bg-teal custom-text-teal flex items-center justify-center">
                                    <i class="fa-solid fa-compass-drafting text-xs"></i>
                                </div>
                                <span
                                    class="text-[10px] font-bold text-gray-300 dark:text-gray-600 bg-gray-50 dark:bg-gray-800 px-2 py-0.5 rounded-md">03</span>
                            </div>
                            <div
                                class="font-medium text-[10px] uppercase text-textMuted-light dark:text-textMuted-dark tracking-wider mb-1">
                                DED</div>
                            <div class="text-xl font-bold text-textMain-light dark:text-textMain-dark mt-auto">
                                {{ $stats['pipeline']['ded'] ?? 0 }} <span
                                    class="text-[10px] font-normal text-textMuted-light">Proyek</span></div>
                        </div>
                        <div
                            class="hidden lg:block absolute -right-2.5 top-1/2 -translate-y-1/2 z-20 text-gray-300 dark:text-gray-600">
                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </div>
                    </div>

                    <!-- Step 4: Lelang -->
                    <div class="relative group">
                        <div
                            class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-4 flex flex-col relative z-10 h-full group-hover:border-teal-light transition-colors shadow-sm">
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="w-8 h-8 rounded-xl custom-bg-teal custom-text-teal flex items-center justify-center">
                                    <i class="fa-solid fa-gavel text-xs"></i>
                                </div>
                                <span
                                    class="text-[10px] font-bold text-gray-300 dark:text-gray-600 bg-gray-50 dark:bg-gray-800 px-2 py-0.5 rounded-md">04</span>
                            </div>
                            <div
                                class="font-medium text-[10px] uppercase text-textMuted-light dark:text-textMuted-dark tracking-wider mb-1">
                                Lelang</div>
                            <div class="text-xl font-bold text-textMain-light dark:text-textMain-dark mt-auto">
                                {{ $stats['pipeline']['lelang'] ?? 0 }} <span
                                    class="text-[10px] font-normal text-textMuted-light">Proyek</span></div>
                        </div>
                        <div
                            class="hidden lg:block absolute -right-2.5 top-1/2 -translate-y-1/2 z-20 text-gray-300 dark:text-gray-600">
                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </div>
                    </div>

                    <!-- Step 5: Konstruksi -->
                    <div class="relative group">
                        <div
                            class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-4 flex flex-col relative z-10 h-full group-hover:border-teal-light transition-colors shadow-sm">
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="w-8 h-8 rounded-xl custom-bg-teal custom-text-teal flex items-center justify-center">
                                    <i class="fa-solid fa-helmet-safety text-xs"></i>
                                </div>
                                <span
                                    class="text-[10px] font-bold text-gray-300 dark:text-gray-600 bg-gray-50 dark:bg-gray-800 px-2 py-0.5 rounded-md">05</span>
                            </div>
                            <div
                                class="font-medium text-[10px] uppercase text-textMuted-light dark:text-textMuted-dark tracking-wider mb-1">
                                Konstruksi</div>
                            <div class="text-xl font-bold text-textMain-light dark:text-textMain-dark mt-auto">
                                {{ $stats['pipeline']['konstruksi'] ?? 0 }} <span
                                    class="text-[10px] font-normal text-textMuted-light">Proyek</span></div>
                        </div>
                        <div
                            class="hidden lg:block absolute -right-2.5 top-1/2 -translate-y-1/2 z-20 text-gray-300 dark:text-gray-600">
                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </div>
                    </div>

                    <!-- Step 6: Serah Terima -->
                    <div class="relative group">
                        <div
                            class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-4 flex flex-col relative z-10 h-full group-hover:border-teal-light transition-colors shadow-sm">
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="w-8 h-8 rounded-xl custom-bg-teal custom-text-teal flex items-center justify-center">
                                    <i class="fa-solid fa-handshake text-xs"></i>
                                </div>
                                <span
                                    class="text-[10px] font-bold text-gray-300 dark:text-gray-600 bg-gray-50 dark:bg-gray-800 px-2 py-0.5 rounded-md">06</span>
                            </div>
                            <div
                                class="font-medium text-[10px] uppercase text-textMuted-light dark:text-textMuted-dark tracking-wider mb-1">
                                Selesai</div>
                            <div class="text-xl font-bold text-textMain-light dark:text-textMain-dark mt-auto">
                                {{ $stats['pipeline']['serah_terima'] ?? 0 }} <span
                                    class="text-[10px] font-normal text-textMuted-light">Proyek</span></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

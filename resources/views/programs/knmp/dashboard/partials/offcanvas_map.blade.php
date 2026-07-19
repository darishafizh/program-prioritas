<!-- OFFCANVAS DETAIL 75% LEBAR LAYAR (Muncul dari kanan saat titik lokasi diklik) -->
<div x-show="selectedPoint" @open-map-detail.window="selectedPoint = $event.detail; isPdfModalOpen = false"
    style="display: none; z-index: 99999;" class="fixed inset-0 overflow-hidden font-sans pointer-events-none">
    <div class="fixed inset-0 w-full flex justify-end pointer-events-none">
        <!-- Offcanvas Panel kanan 75% layar - Fixed Layar Tanpa Scroll -->
        <div x-show="selectedPoint" @open-map-detail.window="selectedPoint = $event.detail; isPdfModalOpen = false"
            @click.away="closeDetailPanel()" style="width: 60vw !important; max-width: 60vw !important;"
            x-transition:enter="transform transition ease-in-out duration-300 sm:duration-500"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-300 sm:duration-500"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
            class="w-3/5 h-full bg-white dark:bg-gray-900 shadow-2xl flex flex-col text-left pointer-events-auto border-l border-gray-200/80 dark:border-gray-800 overflow-hidden">

            <!-- Header: Nama KNMP, Wilayah, Tombol Close di Kanan -->
            <div
                class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-start flex-shrink-0 bg-white dark:bg-gray-900 shadow-sm gap-4">
                <div class="flex flex-col items-start min-w-0">
                    <h3 class="text-sm font-bold tracking-tight text-textMain-light dark:text-white leading-snug truncate w-full"
                        x-text="selectedPoint?.nama">
                    </h3>
                    <div
                        class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1.5 flex items-center gap-1.5 truncate w-full">
                        <i class="fa-solid fa-location-dot"></i>
                        <span
                            x-text="[selectedPoint?.desa, selectedPoint?.kecamatan, selectedPoint?.kabupaten, selectedPoint?.provinsi].filter(Boolean).join(', ') || '-'"></span>
                    </div>
                </div>

                <div class="flex items-center shrink-0">
                    <button type="button" @click="closeDetailPanel()"
                        class="cursor-pointer text-gray-500 dark:text-gray-400 hover:text-danger dark:hover:text-danger p-2 transition-colors focus:outline-none rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800"
                        title="Tutup Panel">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Body (Bisa Scroll) -->
            <div class="flex-1 min-h-0 overflow-y-auto custom-scrollbar bg-gray-50/60 dark:bg-gray-950/50 p-4 sm:p-6">
                <template x-if="selectedPoint">
                    <div class="flex flex-col gap-6 w-full min-h-min">

                        <!-- BODY BARIS 0: Status Badge -->
                        <div class="flex items-center">
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium tracking-wide shrink-0"
                                :class="selectedPoint?.tahap === 'serah_terima' ?
                                    'bg-success/10 dark:bg-success/20 text-success dark:text-emerald-400 border border-success/20' :
                                    'bg-warning/10 dark:bg-amber-400/10 text-warning dark:text-amber-500 border border-warning/20'"
                                x-text="'Status: ' + selectedPoint?.tahap_label"></span>
                        </div>

                        <!-- BODY BARIS 1: Kolom Kiri Profil KNMP | Kolom Kanan Lampiran Dokumen -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 shrink-0 mt-[-0.5rem]">
                            <!-- Kolom Kiri: Profil KNMP -->
                            <div class="flex flex-col justify-start">
                                <h4 class="text-sm font-bold tracking-tight text-textMain-light dark:text-white mb-6">
                                    Profil KNMP
                                </h4>
                                <div class="grid grid-cols-2 gap-x-6" style="row-gap: 24px;">
                                    <div class="flex flex-col" style="gap: 2px;">
                                        <span class="text-[10px] font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider m-0 p-0 leading-none">
                                            Jumlah KK</span>
                                        <span class="text-[14px] font-semibold text-textMain-light dark:text-textMain-dark truncate m-0 p-0 leading-snug"
                                            x-text="selectedPoint?.jumlah_kk || '120 KK'"></span>
                                    </div>
                                    <div class="flex flex-col" style="gap: 2px;">
                                        <span class="text-[10px] font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider m-0 p-0 leading-none">
                                            Komoditas Utama</span>
                                        <span class="text-[14px] font-semibold text-textMain-light dark:text-textMain-dark truncate m-0 p-0 leading-snug"
                                            x-text="selectedPoint?.komoditas || 'Ikan Tongkol'"></span>
                                    </div>
                                    <div class="flex flex-col" style="gap: 2px;">
                                        <span class="text-[10px] font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider m-0 p-0 leading-none">
                                            Pendapatan</span>
                                        <span class="text-[14px] font-semibold text-textMain-light dark:text-textMain-dark truncate m-0 p-0 leading-snug"
                                            x-text="selectedPoint?.pendapatan || 'Rp 5.000.000 / bln'"></span>
                                    </div>
                                    <div class="flex flex-col" style="gap: 2px;">
                                        <span class="text-[10px] font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider m-0 p-0 leading-none">
                                            Penjualan Ikan</span>
                                        <span class="text-[14px] font-semibold text-textMain-light dark:text-textMain-dark truncate m-0 p-0 leading-snug"
                                            x-text="selectedPoint?.penjualan_ikan || 'Pasar Lokal'"></span>
                                    </div>
                                    <div class="flex flex-col" style="gap: 2px;">
                                        <span class="text-[10px] font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider m-0 p-0 leading-none">
                                            Jum. Hari Melaut</span>
                                        <span class="text-[14px] font-semibold text-textMain-light dark:text-textMain-dark truncate m-0 p-0 leading-snug"
                                            x-text="selectedPoint?.jumlah_hari_melaut || '15 Hari / bln'"></span>
                                    </div>
                                    <div class="flex flex-col" style="gap: 2px;">
                                        <span class="text-[10px] font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider m-0 p-0 leading-none">
                                            Pendpt. Rata2 Saat Ini</span>
                                        <span class="text-[14px] font-semibold text-textMain-light dark:text-textMain-dark truncate m-0 p-0 leading-snug"
                                            x-text="selectedPoint?.pendapatan_rata_saat_ini || 'Rp 3.500.000'"></span>
                                    </div>
                                    <div class="flex flex-col" style="gap: 2px;">
                                        <span class="text-[10px] font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider m-0 p-0 leading-none">
                                            Pendpt. Pasca Intervensi</span>
                                        <span class="text-[14px] font-semibold text-textMain-light dark:text-textMain-dark truncate m-0 p-0 leading-snug"
                                            x-text="selectedPoint?.pendapatan_pasca_intervensi || 'Rp 7.000.000'"></span>
                                    </div>
                                    <div class="flex flex-col" style="gap: 2px;">
                                        <span class="text-[10px] font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider m-0 p-0 leading-none">
                                            Serapan Tenaga Kerja</span>
                                        <span class="text-[14px] font-semibold text-textMain-light dark:text-textMain-dark truncate m-0 p-0 leading-snug"
                                            x-text="selectedPoint?.serapan_tenaga_kerja || '50 Orang'"></span>
                                    </div>
                                    <div class="flex flex-col" style="gap: 2px;">
                                        <span class="text-[10px] font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider m-0 p-0 leading-none">
                                            Vol. Produksi Daerah</span>
                                        <span class="text-[14px] font-semibold text-textMain-light dark:text-textMain-dark truncate m-0 p-0 leading-snug"
                                            x-text="selectedPoint?.vol_produksi_daerah || '120 Ton / thn'"></span>
                                    </div>
                                    <div class="flex flex-col" style="gap: 2px;">
                                        <span class="text-[10px] font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider m-0 p-0 leading-none">
                                            Nilai Produksi Daerah</span>
                                        <span class="text-[14px] font-semibold text-textMain-light dark:text-textMain-dark truncate m-0 p-0 leading-snug"
                                            x-text="selectedPoint?.nilai_produksi_daerah || 'Rp 2 Milyar'"></span>
                                    </div>
                                    <div class="flex flex-col" style="gap: 2px;">
                                        <span class="text-[10px] font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider m-0 p-0 leading-none">
                                            Vol. Produksi Pasca Inter.</span>
                                        <span class="text-[14px] font-semibold text-textMain-light dark:text-textMain-dark truncate m-0 p-0 leading-snug"
                                            x-text="selectedPoint?.vol_produksi_pasca_intervensi || '250 Ton / thn'">
                                        </span>
                                    </div>
                                    <div class="flex flex-col" style="gap: 2px;">
                                        <span class="text-[10px] font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider m-0 p-0 leading-none">
                                            Nilai Produksi Pasca Inter.</span>
                                        <span class="text-[14px] font-semibold text-textMain-light dark:text-textMain-dark truncate m-0 p-0 leading-snug"
                                            x-text="selectedPoint?.nilai_produksi_pasca_intervensi || 'Rp 5 Milyar'">
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Kolom Kanan: Dokumentasi (Before & After) -->
                            <div class="flex flex-col justify-start">
                                <h4 class="text-sm font-bold tracking-tight text-textMain-light dark:text-white mb-4">
                                    Dokumentasi (Before & After)
                                </h4>
                                <div class="grid grid-cols-2 gap-4 flex-1 min-h-0">
                                    <!-- Before -->
                                    <div class="flex flex-col gap-2.5 h-full">
                                        <div
                                            class="text-[10px] font-semibold text-textMuted-light text-center uppercase tracking-wider shrink-0">
                                            Before</div>
                                        <div class="grid grid-rows-3 gap-2.5 flex-1 min-h-0">
                                            <template x-for="i in 3" :key="'before-' + i">
                                                <div
                                                    class="rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-800 relative shadow-xs flex items-center justify-center">
                                                    <template
                                                        x-if="selectedPoint?.fotosBefore && selectedPoint.fotosBefore[i-1]">
                                                        <a :href="selectedPoint.fotosBefore[i - 1].url" target="_blank"
                                                            class="block w-full h-full">
                                                            <img :src="selectedPoint.fotosBefore[i - 1].url"
                                                                class="w-full h-full object-cover">
                                                        </a>
                                                    </template>
                                                    <template
                                                        x-if="!selectedPoint?.fotosBefore || !selectedPoint.fotosBefore[i-1]">
                                                        <div class="text-center p-1">
                                                            <i
                                                                class="fa-regular fa-image text-gray-400/50 text-base mb-0.5"></i>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- After -->
                                    <div class="flex flex-col gap-2.5 h-full">
                                        <div
                                            class="text-[10px] font-semibold text-teal-600 dark:text-teal-400 text-center uppercase tracking-wider shrink-0">
                                            After</div>
                                        <div class="grid grid-rows-3 gap-2.5 flex-1 min-h-0">
                                            <template x-for="i in 3" :key="'after-' + i">
                                                <div
                                                    class="rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-800 relative shadow-xs flex items-center justify-center">
                                                    <template
                                                        x-if="selectedPoint?.fotosAfter && selectedPoint.fotosAfter[i-1]">
                                                        <a :href="selectedPoint.fotosAfter[i - 1].url" target="_blank"
                                                            class="block w-full h-full">
                                                            <img :src="selectedPoint.fotosAfter[i - 1].url"
                                                                class="w-full h-full object-cover">
                                                        </a>
                                                    </template>
                                                    <template
                                                        x-if="!selectedPoint?.fotosAfter || !selectedPoint.fotosAfter[i-1]">
                                                        <div class="text-center p-1">
                                                            <i
                                                                class="fa-solid fa-camera text-gray-400/50 text-base mb-0.5"></i>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- BODY BARIS 2: Dinamis (Kurva S atau Sarpras) -->
                        <template x-if="stageLevel(selectedPoint?.tahap_saat_ini) !== 6">
                            <!-- Kurva S Full Width -->
                        <div x-data="{
                            activePoint: null,
                            get kurva() {
                                const arr = selectedPoint?.kurvaS || [];
                                const n = Math.max(1, arr.length - 1);
                                return arr.map((k, idx) => ({
                                    ...k,
                                    idx,
                                    x: 28 + (idx / n) * 362,
                                    yRencana: k.rencana !== null && k.rencana !== undefined ? 95 - (k.rencana / 100) * 80 : null,
                                    yRealisasi: k.realisasi !== null && k.realisasi !== undefined ? 95 - (k.realisasi / 100) * 80 : null
                                }));
                            },
                            getRencanaPath() {
                                if (!this.kurva.length) return '';
                                return this.kurva.map((k, i) => `${i === 0 ? 'M' : 'L'} ${k.x} ${k.yRencana}`).join(' ');
                            },
                            getRealisasiPath() {
                                const valid = this.kurva.filter(k => k.yRealisasi !== null);
                                if (!valid.length) return '';
                                return valid.map((k, i) => `${i === 0 ? 'M' : 'L'} ${k.x} ${k.yRealisasi}`).join(' ');
                            },
                            getFillPath() {
                                const valid = this.kurva.filter(k => k.yRealisasi !== null);
                                if (!valid.length) return '';
                                let path = `M ${valid[0].x} 95`;
                                valid.forEach(p => path += ` L ${p.x} ${p.yRealisasi}`);
                                path += ` L ${valid[valid.length - 1].x} 95 Z`;
                                return path;
                            }
                        }" x-init="$watch('selectedPoint', () => { activePoint = null; })"
                            class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-4 sm:p-5 shadow-sm shrink-0 w-full flex flex-col overflow-hidden h-64 sm:h-72">
                            <div class="flex justify-between items-start mb-4 shrink-0">
                                <h4 class="text-sm font-bold tracking-tight text-textMain-light dark:text-white">
                                    Kurva S (Kinerja dan Fisik Konstruksi)
                                </h4>
                                <!-- Legend -->
                                <div class="flex items-center gap-4 text-[10px] font-medium text-gray-500">
                                    <div class="flex items-center gap-1.5">
                                        <svg width="16" height="4" viewBox="0 0 16 4"
                                            class="overflow-visible">
                                            <line x1="0" y1="2" x2="16" y2="2"
                                                stroke="#9ca3af" stroke-width="1.5" stroke-dasharray="4,4" />
                                        </svg>
                                        <span>Rencana</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <svg width="16" height="4" viewBox="0 0 16 4"
                                            class="overflow-visible">
                                            <line x1="0" y1="2" x2="16" y2="2"
                                                stroke="#0d9488" stroke-width="2" />
                                        </svg>
                                        <span class="text-teal-700 dark:text-teal-300">Realisasi</span>
                                    </div>
                                </div>
                            </div>

                            <template
                                x-if="stageLevel(selectedPoint?.tahap_saat_ini) >= 5 || (selectedPoint?.progres || 0) > 0">
                                <div class="flex-1 min-h-0 w-full relative select-none py-1 overflow-hidden"
                                    @mouseleave="activePoint = null">

                                    <!-- BASE SVG CHART (Static Grids & Paths) -->
                                    <svg class="absolute inset-0 w-full h-full overflow-visible" viewBox="0 0 400 130"
                                        preserveAspectRatio="none">
                                        <!-- Y-Axis Grid Lines -->
                                        <line x1="28" y1="15" x2="395" y2="15"
                                            stroke="currentColor" class="text-gray-100 dark:text-gray-800"
                                            stroke-width="1" />
                                        <line x1="28" y1="23" x2="395" y2="23"
                                            stroke="currentColor" class="text-gray-100 dark:text-gray-800"
                                            stroke-width="1" />
                                        <line x1="28" y1="31" x2="395" y2="31"
                                            stroke="currentColor" class="text-gray-100 dark:text-gray-800"
                                            stroke-width="1" />
                                        <line x1="28" y1="39" x2="395" y2="39"
                                            stroke="currentColor" class="text-gray-100 dark:text-gray-800"
                                            stroke-width="1" />
                                        <line x1="28" y1="47" x2="395" y2="47"
                                            stroke="currentColor" class="text-gray-100 dark:text-gray-800"
                                            stroke-width="1" />
                                        <line x1="28" y1="55" x2="395" y2="55"
                                            stroke="currentColor" class="text-gray-100 dark:text-gray-800"
                                            stroke-width="1" />
                                        <line x1="28" y1="63" x2="395" y2="63"
                                            stroke="currentColor" class="text-gray-100 dark:text-gray-800"
                                            stroke-width="1" />
                                        <line x1="28" y1="71" x2="395" y2="71"
                                            stroke="currentColor" class="text-gray-100 dark:text-gray-800"
                                            stroke-width="1" />
                                        <line x1="28" y1="79" x2="395" y2="79"
                                            stroke="currentColor" class="text-gray-100 dark:text-gray-800"
                                            stroke-width="1" />
                                        <line x1="28" y1="87" x2="395" y2="87"
                                            stroke="currentColor" class="text-gray-100 dark:text-gray-800"
                                            stroke-width="1" />
                                        <line x1="28" y1="95" x2="395" y2="95"
                                            stroke="currentColor" class="text-gray-300 dark:text-gray-700"
                                            stroke-width="1" />

                                        <!-- Rencana Path -->
                                        <path :d="getRencanaPath()" fill="none" stroke="#9ca3af"
                                            stroke-width="1.5" stroke-dasharray="4,4" />
                                        <!-- Realisasi Path & Fill -->
                                        <path :d="getFillPath()" fill="rgba(13, 148, 136, 0.1)" />
                                        <path :d="getRealisasiPath()" fill="none" stroke="#0d9488"
                                            stroke-width="2.5" />
                                    </svg>

                                    <!-- HTML OVERLAYS (Y-Axis Labels, X-Labels, Interactive Points) -->
                                    <template x-for="val in [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100]"
                                        :key="'y-' + val">
                                        <span
                                            class="absolute text-[9px] font-sans font-light text-gray-400 dark:text-gray-500 text-right w-6"
                                            :style="'right: 94.25%; top: ' + ((95 - val * 0.8) / 130 * 100) +
                                            '%; transform: translateY(-50%);'"
                                            x-text="val"></span>
                                    </template>

                                    <template x-for="(pt, idx) in kurva" :key="'x-' + idx">
                                        <div class="absolute top-0 bottom-0 cursor-pointer z-10"
                                            :style="'left: ' + (pt.x / 400 * 100) + '%; width: ' + (362 / Math.max(1, kurva
                                                .length - 1) / 400 * 100) + '%; transform: translateX(-50%);'"
                                            @mouseenter="activePoint = pt" @click="activePoint = pt">

                                            <!-- Vertical Hover Guide -->
                                            <template x-if="activePoint === pt">
                                                <div class="absolute w-px bg-teal-500/30 border-l border-dashed border-teal-500/40"
                                                    style="top: 11.53%; bottom: 26.92%; left: 50%;"></div>
                                            </template>

                                            <!-- Rencana Dot -->
                                            <div class="absolute w-2 h-2 bg-gray-400 rounded-full transform -translate-x-1/2 -translate-y-1/2 transition-transform"
                                                :style="'top: ' + (pt.yRencana / 130 * 100) + '%; left: 50%;'"
                                                :class="{ 'scale-150 bg-gray-500': activePoint === pt }"></div>

                                            <!-- Realisasi Dot -->
                                            <template x-if="pt.realisasi !== null && pt.realisasi !== undefined">
                                                <div class="absolute w-2.5 h-2.5 bg-teal-500 border border-white dark:border-gray-900 rounded-full transform -translate-x-1/2 -translate-y-1/2 transition-transform z-10"
                                                    :style="'top: ' + (pt.yRealisasi / 130 * 100) + '%; left: 50%;'"
                                                    :class="{ 'scale-[1.7] bg-teal-400': activePoint === pt }"></div>
                                            </template>

                                            <!-- X Label (Minggu) -->
                                            <span
                                                class="absolute text-[9px] font-sans font-light text-gray-400 dark:text-gray-500 whitespace-nowrap transform -translate-x-1/2"
                                                style="bottom: 5%; left: 50%;" x-text="pt.minggu"></span>
                                        </div>
                                    </template>

                                    <!-- HTML Tooltip Overlay -->
                                    <template x-if="activePoint !== null && activePoint.realisasi !== null">
                                        <div class="absolute z-20 pointer-events-none bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-xl rounded-lg p-3 text-xs flex flex-col gap-1.5 min-w-[120px]"
                                            :style="'left: ' + (activePoint.x / 400 * 100) + '%; top: ' + (Math.min(activePoint
                                                .yRencana, activePoint.yRealisasi) / 130 * 100) +
                                            '%; transform: translate(-50%, -115%);'">
                                            <div class="font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-700 pb-1 mb-0.5 text-center"
                                                x-text="activePoint.label"></div>
                                            <div class="flex justify-between items-center gap-4">
                                                <span
                                                    class="text-gray-500 font-light text-[10px] uppercase tracking-wide">Rencana</span>
                                                <span class="font-semibold text-gray-700 dark:text-gray-300"
                                                    x-text="formatDec(activePoint.rencana) + '%'"></span>
                                            </div>
                                            <div class="flex justify-between items-center gap-4">
                                                <span
                                                    class="text-teal-600 font-light text-[10px] uppercase tracking-wide">Realisasi</span>
                                                <span class="font-bold text-teal-700 dark:text-teal-400"
                                                    x-text="formatDec(activePoint.realisasi) + '%'"></span>
                                            </div>
                                            <div
                                                class="flex justify-between items-center gap-4 mt-1 pt-1 border-t border-gray-50 dark:border-gray-700">
                                                <span
                                                    class="text-gray-500 font-light text-[10px] uppercase tracking-wide">Deviasi</span>
                                                <span class="font-bold"
                                                    :class="(activePoint.realisasi - activePoint.rencana) >= 0 ?
                                                        'text-success' : 'text-danger'"
                                                    x-text="((activePoint.realisasi - activePoint.rencana) > 0 ? '+' : '') + formatDec(activePoint.realisasi - activePoint.rencana) + '%'"></span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <template
                                x-if="stageLevel(selectedPoint?.tahap_saat_ini) < 5 && (selectedPoint?.progres || 0) <= 0">
                                <div class="flex-1 min-h-0 flex flex-col items-center justify-center text-center p-4">
                                    <i
                                        class="fa-solid fa-chart-line text-gray-300 dark:text-gray-600 text-2xl mb-1.5"></i>
                                    <span
                                        class="text-xs font-semibold text-textMain-light dark:text-textMain-dark">Kurva
                                        S belum aktif</span>
                                    <p class="text-[11px] text-textMuted-light max-w-sm mt-0.5">Pemantauan deviasi &
                                        grafik Kurva S akan berjalan otomatis saat tahap konstruksi dimulai.</p>
                                </div>
                            </template>
                        </div>
                        </template>

                        <!-- Daftar Progres Sarpras (Serah Terima) -->
                        <template x-if="stageLevel(selectedPoint?.tahap_saat_ini) === 6">
                            <div class="shrink-0 flex flex-col w-full h-auto">
                                <div class="flex justify-between items-center mb-4 shrink-0">
                                    <h4 class="text-sm font-bold tracking-tight text-textMain-light dark:text-white">
                                        Daftar Progres Sarpras (Tahap Serah Terima)
                                    </h4>
                                    <span class="text-[10px] font-medium px-2 py-1 bg-teal-50 dark:bg-teal-900/20 text-teal-600 dark:text-teal-400 rounded">14 Sarpras Selesai</span>
                                </div>
                                <div class="flex-1 min-h-0 grid gap-3" style="grid-template-columns: repeat(7, minmax(0, 1fr)); grid-template-rows: repeat(2, minmax(0, 1fr));" x-data="{
                                    sarpras: Array.from({length: 14}).map((_, i) => ({
                                        nama: 'Fasilitas ' + (i + 1),
                                        progres: 100
                                    }))
                                }">
                                    <template x-for="(s, idx) in sarpras" :key="idx">
                                        <div class="flex flex-col items-center justify-center p-2 rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700/50 hover:border-teal-500/50 hover:bg-teal-50/50 transition-all cursor-default group">
                                            <div class="w-8 h-8 rounded-full bg-teal-100 dark:bg-teal-900/40 text-teal-600 dark:text-teal-400 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                                <i class="fa-solid fa-check text-xs"></i>
                                            </div>
                                            <span class="text-[10px] font-semibold text-center text-gray-700 dark:text-gray-300 leading-tight truncate w-full" x-text="s.nama"></span>
                                            <span class="text-[9px] text-teal-600 dark:text-teal-400 font-bold mt-0.5" x-text="s.progres + '%'"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <!-- BODY BARIS 3: Perhatian/Kendala -->
                        <div class="flex flex-col shrink-0">
                            <div
                                class="bg-danger/10 dark:bg-red-500/10 border border-danger/20 dark:border-red-500/20 rounded-xl p-4 sm:p-5 flex gap-3 sm:gap-4 items-start">
                                <div
                                    class="w-8 h-8 rounded-full bg-danger/20 dark:bg-red-500/20 flex items-center justify-center shrink-0 mt-0.5">
                                    <i class="fa-solid fa-triangle-exclamation text-danger dark:text-red-400"></i>
                                </div>
                                <div class="flex flex-col gap-1.5 text-sm text-danger dark:text-red-400">
                                    <h4 class="font-bold tracking-tight">Perhatian / Kendala Proyek</h4>
                                    <p class="leading-relaxed text-xs sm:text-sm">Terdapat kendala teknis pada proses
                                        pengadaan material konstruksi di lapangan yang menyebabkan sedikit keterlambatan
                                        dari jadwal rencana awal. Tim sedang berkoordinasi dengan pihak terkait untuk
                                        percepatan distribusi material agar kembali sesuai dengan kurva S.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

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
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium tracking-wide shrink-0"
                                :class="selectedPoint?.tahap === 'serah_terima' ?
                                    'bg-success/10 dark:bg-success/20 text-success dark:text-emerald-400 border border-success/20' :
                                    'bg-warning/10 dark:bg-amber-400/10 text-warning dark:text-amber-500 border border-warning/20'"
                                x-text="'Status: ' + selectedPoint?.tahap_label"></span>

                            <template x-if="selectedPoint?.tahap === 'serah_terima'">
                                <span
                                    class="px-2 py-0.5 rounded text-[10px] font-medium tracking-wide shrink-0 bg-blue-50 text-blue-600 border border-blue-200 dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-800">
                                    Pra Kondisi
                                </span>
                            </template>
                        </div>

                        <!-- BODY BARIS 1: Kolom Kiri Profil KNMP | Kolom Kanan Lampiran Dokumen -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 shrink-0 mt-[-0.5rem]">
                            <!-- Kolom Kiri: Profil KNMP -->
                            <div class="flex flex-col justify-start">
                                <h4 class="text-sm font-bold tracking-tight text-textMain-light dark:text-white mb-6">
                                    Profil KNMP (Pra Kondisi)
                                </h4>
                                <div class="grid grid-cols-2 gap-x-6" style="row-gap: 24px;">
                                    <div class="flex flex-col" style="gap: 2px;">
                                        <span
                                            class="text-[10px] font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider m-0 p-0 leading-none">Jumlah
                                            KK</span>
                                        <span
                                            class="text-[14px] font-semibold text-textMain-light dark:text-textMain-dark truncate m-0 p-0 leading-snug"
                                            x-text="selectedPoint?.jumlah_kk || '-'"></span>
                                    </div>
                                    <div class="flex flex-col" style="gap: 2px;">
                                        <span
                                            class="text-[10px] font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider m-0 p-0 leading-none">Jumlah
                                            Nelayan</span>
                                        <span
                                            class="text-[14px] font-semibold text-textMain-light dark:text-textMain-dark truncate m-0 p-0 leading-snug"
                                            x-text="selectedPoint?.jumlah_nelayan || '-'"></span>
                                    </div>
                                    <div class="flex flex-col" style="gap: 2px;">
                                        <span
                                            class="text-[10px] font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider m-0 p-0 leading-none">Jumlah
                                            Kapal</span>
                                        <span
                                            class="text-[14px] font-semibold text-textMain-light dark:text-textMain-dark truncate m-0 p-0 leading-snug"
                                            x-text="selectedPoint?.jumlah_kapal || '-'"></span>
                                    </div>
                                    <div class="flex flex-col" style="gap: 2px;">
                                        <span
                                            class="text-[10px] font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider m-0 p-0 leading-none">Prod.
                                            Total Desa</span>
                                        <span
                                            class="text-[14px] font-semibold text-textMain-light dark:text-textMain-dark truncate m-0 p-0 leading-snug"
                                            x-text="selectedPoint?.prod_total_desa || '-'"></span>
                                    </div>

                                    <hr class="col-span-2 border-gray-200 dark:border-gray-700" style="margin: -8px 0;" />

                                    <div class="flex flex-col" style="gap: 2px;">
                                        <span
                                            class="text-[10px] font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider m-0 p-0 leading-none">Ukuran
                                            Perahu Dominan</span>
                                        <span
                                            class="text-[14px] font-semibold text-textMain-light dark:text-textMain-dark truncate m-0 p-0 leading-snug"
                                            x-text="selectedPoint?.ukuran_perahu_dominan || '-'"></span>
                                    </div>
                                    <div class="flex flex-col" style="gap: 2px;">
                                        <span
                                            class="text-[10px] font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider m-0 p-0 leading-none">Alat
                                            Tangkap Dominan</span>
                                        <span
                                            class="text-[14px] font-semibold text-textMain-light dark:text-textMain-dark truncate m-0 p-0 leading-snug"
                                            x-text="selectedPoint?.alat_tangkap_dominan || '-'"></span>
                                    </div>
                                    <div class="flex flex-col" style="gap: 2px;" :class="selectedPoint?.komoditas_utama?.length > 25 ? 'col-span-2' : ''">
                                        <span
                                            class="text-[10px] font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider m-0 p-0 leading-none">Komoditas
                                            Utama</span>
                                        <span
                                            class="text-[14px] font-semibold text-textMain-light dark:text-textMain-dark m-0 p-0 leading-snug"
                                            :class="selectedPoint?.komoditas_utama?.length > 25 ? 'whitespace-normal break-words' : 'truncate'"
                                            x-text="selectedPoint?.komoditas_utama || '-'"></span>
                                    </div>
                                    <div class="flex flex-col" style="gap: 2px;">
                                        <span
                                            class="text-[10px] font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider m-0 p-0 leading-none">Pendpt.
                                            Nelayan</span>
                                        <span
                                            class="text-[14px] font-semibold text-textMain-light dark:text-textMain-dark truncate m-0 p-0 leading-snug"
                                            x-text="selectedPoint?.pend_nelayan || '-'"></span>
                                    </div>
                                    <div class="flex flex-col" style="gap: 2px;">
                                        <span
                                            class="text-[10px] font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider m-0 p-0 leading-none">Prod.
                                            Per Trip/Kapal</span>
                                        <span
                                            class="text-[14px] font-semibold text-textMain-light dark:text-textMain-dark truncate m-0 p-0 leading-snug"
                                            x-text="selectedPoint?.prod_per_trip_per_kapal || '-'"></span>
                                    </div>
                                    <div class="flex flex-col" style="gap: 2px;">
                                        <span
                                            class="text-[10px] font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider m-0 p-0 leading-none">Jum.
                                            Trip / Bulan</span>
                                        <span
                                            class="text-[14px] font-semibold text-textMain-light dark:text-textMain-dark truncate m-0 p-0 leading-snug"
                                            x-text="selectedPoint?.jml_trip_per_bulan || '-'"></span>
                                    </div>
                                    <div class="flex flex-col" style="gap: 2px;">
                                        <span
                                            class="text-[10px] font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider m-0 p-0 leading-none">Prod.
                                            Kapal</span>
                                        <span
                                            class="text-[14px] font-semibold text-textMain-light dark:text-textMain-dark truncate m-0 p-0 leading-snug"
                                            x-text="selectedPoint?.prod_kapal || '-'"></span>
                                    </div>
                                    <div class="flex flex-col" style="gap: 2px;">
                                        <span
                                            class="text-[10px] font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider m-0 p-0 leading-none">Prod.
                                            Total Kapal</span>
                                        <span
                                            class="text-[14px] font-semibold text-textMain-light dark:text-textMain-dark truncate m-0 p-0 leading-snug"
                                            x-text="selectedPoint?.prod_total_kapal || '-'"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Kolom Kanan: Dokumentasi (Before & After/Progres) -->
                            <div class="flex flex-col justify-start">
                                <h4 class="text-sm font-bold tracking-tight text-textMain-light dark:text-white mb-4"
                                    x-text="'Dokumentasi (Before & ' + (stageLevel(selectedPoint?.tahap_saat_ini) === 6 ? 'After' : 'Progres') + ')'">
                                </h4>
                                <div class="grid grid-cols-2 gap-4 flex-1 min-h-0">
                                    <!-- Before -->
                                    <div class="flex flex-col gap-2.5 h-full">
                                        <div
                                            class="text-[10px] font-semibold text-textMuted-light text-center uppercase tracking-wider shrink-0">
                                            Pra Kondisi (Before)</div>
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

                                    <!-- After/Progres -->
                                    <div class="flex flex-col gap-2.5 h-full">
                                        <div class="text-[10px] font-semibold text-teal-600 dark:text-teal-400 text-center uppercase tracking-wider shrink-0"
                                            x-text="stageLevel(selectedPoint?.tahap_saat_ini) === 6 ? 'After' : 'Progres'">
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
                                    let lastRealIdx = -1;
                                    arr.forEach((k, i) => { if (k.realisasi !== null && k.realisasi !== undefined) lastRealIdx = i; });
                                    return arr.map((k, idx) => ({
                                        ...k,
                                        idx,
                                        isLastRealisasi: idx === lastRealIdx,
                                        x: 35 + (idx / n) * 350,
                                        yRencana: k.rencana !== null && k.rencana !== undefined ? 10 + (1 - k.rencana / 100) * 75 : null,
                                        yRealisasi: k.realisasi !== null && k.realisasi !== undefined ? 10 + (1 - k.realisasi / 100) * 75 : null
                                    }));
                                },
                                getRencanaPath() {
                                    const valid = this.kurva.filter(k => k.yRencana !== null);
                                    if (valid.length === 0) return '';
                                    if (valid.length === 1) return `M ${valid[0].x} ${valid[0].yRencana}`;
                                    if (valid.length === 2) return `M ${valid[0].x} ${valid[0].yRencana} L ${valid[1].x} ${valid[1].yRencana}`;
                                    let path = `M ${valid[0].x} ${valid[0].yRencana}`;
                                    valid.forEach((curr, i, arr) => {
                                        if (i === 0 || i === arr.length - 1) return;
                                        const prev = arr[i - 1];
                                        const midX = (prev.x + curr.x) / 2;
                                        const midY = (prev.yRencana + curr.yRencana) / 2;
                                        if (i === 1) path += ` L ${midX} ${midY}`;
                                        const next = arr[i + 1];
                                        const nextMidX = (curr.x + next.x) / 2;
                                        const nextMidY = (curr.yRencana + next.yRencana) / 2;
                                        path += ` Q ${curr.x} ${curr.yRencana}, ${nextMidX} ${nextMidY}`;
                                    });
                                    path += ` L ${valid[valid.length - 1].x} ${valid[valid.length - 1].yRencana}`;
                                    return path;
                                },
                                getRealisasiPath() {
                                    const valid = this.kurva.filter(k => k.yRealisasi !== null);
                                    if (valid.length === 0) return '';
                                    if (valid.length === 1) return `M ${valid[0].x} ${valid[0].yRealisasi}`;
                                    if (valid.length === 2) return `M ${valid[0].x} ${valid[0].yRealisasi} L ${valid[1].x} ${valid[1].yRealisasi}`;
                                    let path = `M ${valid[0].x} ${valid[0].yRealisasi}`;
                                    valid.forEach((curr, i, arr) => {
                                        if (i === 0 || i === arr.length - 1) return;
                                        const prev = arr[i - 1];
                                        const midX = (prev.x + curr.x) / 2;
                                        const midY = (prev.yRealisasi + curr.yRealisasi) / 2;
                                        if (i === 1) path += ` L ${midX} ${midY}`;
                                        const next = arr[i + 1];
                                        const nextMidX = (curr.x + next.x) / 2;
                                        const nextMidY = (curr.yRealisasi + next.yRealisasi) / 2;
                                        path += ` Q ${curr.x} ${curr.yRealisasi}, ${nextMidX} ${nextMidY}`;
                                    });
                                    path += ` L ${valid[valid.length - 1].x} ${valid[valid.length - 1].yRealisasi}`;
                                    return path;
                                },
                                getFillPath() {
                                    const valid = this.kurva.filter(k => k.yRealisasi !== null);
                                    if (valid.length === 0) return '';
                                    const baseline = 85;
                                    let path = `M ${valid[0].x} ${baseline} L ${valid[0].x} ${valid[0].yRealisasi}`;
                                    if (valid.length === 2) {
                                        path += ` L ${valid[1].x} ${valid[1].yRealisasi}`;
                                    } else if (valid.length > 2) {
                                        valid.forEach((curr, i, arr) => {
                                            if (i === 0 || i === arr.length - 1) return;
                                            const prev = arr[i - 1];
                                            const midX = (prev.x + curr.x) / 2;
                                            const midY = (prev.yRealisasi + curr.yRealisasi) / 2;
                                            if (i === 1) path += ` L ${midX} ${midY}`;
                                            const next = arr[i + 1];
                                            const nextMidX = (curr.x + next.x) / 2;
                                            const nextMidY = (curr.yRealisasi + next.yRealisasi) / 2;
                                            path += ` Q ${curr.x} ${curr.yRealisasi}, ${nextMidX} ${nextMidY}`;
                                        });
                                        path += ` L ${valid[valid.length - 1].x} ${valid[valid.length - 1].yRealisasi}`;
                                    }
                                    path += ` L ${valid[valid.length - 1].x} ${baseline} Z`;
                                    return path;
                                },
                                getTooltipStyle() {
                                    if (!this.activePoint) return '';
                                    const pt = this.activePoint;
                                    const xPct = pt.x / 400 * 100;
                                    const yPct = Math.min(pt.yRencana ?? 100, pt.yRealisasi ?? 100);
                            
                                    let translateX = '-50%';
                                    let translateY = 'calc(-100% - 12px)'; // 12px above the point
                            
                                    if (xPct < 20) translateX = '0%';
                                    else if (xPct > 80) translateX = '-100%';
                            
                                    if (yPct < 30) translateY = '12px'; // 12px below the point
                            
                                    return `left: ${xPct}%; top: ${yPct}%; transform: translate(${translateX}, ${translateY});`;
                                }
                            }" x-init="$watch('selectedPoint', () => { activePoint = null; })"
                                class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl shadow-sm shrink-0 w-full flex flex-col overflow-hidden"
                                style="min-height: 420px;">

                                <div class="flex justify-between items-center px-4 pt-3 pb-2 shrink-0">
                                    <h4
                                        class="text-[13px] font-semibold tracking-tight text-textMain-light dark:text-white">
                                        Kurva S
                                    </h4>
                                    <div
                                        class="flex items-center gap-3 text-[10px] font-medium text-gray-400 dark:text-gray-500">
                                        <div class="flex items-center gap-1">
                                            <span class="inline-block w-3 border-t border-dashed"
                                                style="border-color: #9ca3af;"></span>
                                            <span>Rencana</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <span class="inline-block w-3 border-t-2 rounded-full"
                                                style="border-color: #10B981;"></span>
                                            <span style="color: #10B981;">Realisasi</span>
                                        </div>
                                    </div>
                                </div>

                                <template
                                    x-if="stageLevel(selectedPoint?.tahap_saat_ini) >= 5 || (selectedPoint?.progres || 0) > 0">
                                    <div class="flex-1 min-h-0 w-full relative select-none overflow-hidden px-1 pb-2"
                                        @mouseleave="activePoint = null">

                                        <svg class="absolute inset-0 w-full h-full" viewBox="0 0 400 100"
                                            preserveAspectRatio="none">
                                            <defs>
                                                <linearGradient id="areaGradKurvaS" x1="0" y1="0"
                                                    x2="0" y2="1">
                                                    <stop offset="0%" stop-color="rgba(16, 185, 129, 0.25)" />
                                                    <stop offset="100%" stop-color="rgba(16, 185, 129, 0.02)" />
                                                </linearGradient>
                                            </defs>

                                            @foreach ([0, 25, 50, 75, 100] as $val)
                                                <line x1="35" y1="{{ 10 + (1 - $val / 100) * 75 }}"
                                                    x2="385" y2="{{ 10 + (1 - $val / 100) * 75 }}"
                                                    stroke="currentColor"
                                                    class="{{ $val === 0 ? 'text-gray-200 dark:text-gray-700' : 'text-gray-100 dark:text-gray-800' }}"
                                                    stroke-width="1" vector-effect="non-scaling-stroke" />
                                            @endforeach

                                            <path :d="getRencanaPath()" fill="none" stroke="#b0b0b0"
                                                stroke-width="1.5" stroke-dasharray="6,4" stroke-linecap="round"
                                                vector-effect="non-scaling-stroke" />

                                            <path :d="getFillPath()" fill="url(#areaGradKurvaS)" />

                                            <path :d="getRealisasiPath()" fill="none" stroke="#10B981"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                vector-effect="non-scaling-stroke" />
                                        </svg>

                                        @foreach ([0, 25, 50, 75, 100] as $val)
                                            <span
                                                class="absolute text-[9px] font-light text-gray-400 dark:text-gray-500 tabular-nums text-right w-6"
                                                style="left: 6px; top: {{ 10 + (1 - $val / 100) * 75 }}%; transform: translateY(-50%);">{{ $val }}</span>
                                        @endforeach

                                        <template x-for="(pt, idx) in kurva" :key="'x-' + idx">
                                            <div class="absolute top-0 bottom-0 cursor-pointer z-10"
                                                :style="'left: ' + (pt.x / 400 * 100) + '%; width: ' + (350 / Math.max(1, kurva
                                                    .length - 1) / 400 * 100) + '%; transform: translateX(-50%);'"
                                                @mouseenter="activePoint = pt" @click="activePoint = pt">

                                                <template x-if="activePoint === pt">
                                                    <div class="absolute w-px"
                                                        style="background-color: rgba(16, 185, 129, 0.2); top: 10%; bottom: 15%; left: 50%;">
                                                    </div>
                                                </template>

                                                <template
                                                    x-if="pt.realisasi !== null && pt.realisasi !== undefined && (pt.isLastRealisasi || activePoint === pt)">
                                                    <div class="absolute rounded-full transform -translate-x-1/2 -translate-y-1/2 z-10 transition-all duration-150"
                                                        :style="'background-color: #10B981; box-shadow: 0 0 0 3px rgba(16,185,129,0.2); top: ' +
                                                        pt.yRealisasi + '%; left: 50%; width: ' + (activePoint === pt ?
                                                            '10px' : '7px') + '; height: ' + (activePoint === pt ?
                                                            '10px' : '7px') + ';'">
                                                    </div>
                                                </template>

                                                <span
                                                    class="absolute text-[8px] font-light text-gray-400 dark:text-gray-500 whitespace-nowrap transform -translate-x-1/2 tabular-nums"
                                                    style="bottom: 8px; left: 50%;" x-text="pt.minggu"></span>
                                            </div>
                                        </template>

                                        <template x-if="activePoint !== null && activePoint.realisasi !== null">
                                            <div class="absolute z-30 pointer-events-none rounded-xl text-xs min-w-[130px] transition-all duration-150"
                                                style="backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); background: rgba(255,255,255,0.92); border: 1px solid rgba(0,0,0,0.06); box-shadow: 0 8px 32px rgba(0,0,0,0.10), 0 2px 8px rgba(0,0,0,0.06);"
                                                :style="getTooltipStyle()">
                                                <div class="px-3 pt-2.5 pb-1 border-b text-center"
                                                    style="border-color: rgba(0,0,0,0.06);">
                                                    <span class="font-semibold text-[11px] text-gray-800"
                                                        x-text="activePoint.label"></span>
                                                </div>
                                                <div class="px-3 py-2 flex flex-col gap-1.5">
                                                    <div class="flex justify-between items-center gap-3">
                                                        <span class="text-gray-400 text-[10px]">Rencana</span>
                                                        <span class="font-semibold text-gray-700 tabular-nums"
                                                            x-text="formatDec(activePoint.rencana) + '%'"></span>
                                                    </div>
                                                    <div class="flex justify-between items-center gap-3">
                                                        <span class="text-[10px]"
                                                            style="color: #10B981;">Realisasi</span>
                                                        <span class="font-bold tabular-nums" style="color: #10B981;"
                                                            x-text="formatDec(activePoint.realisasi) + '%'"></span>
                                                    </div>
                                                    <div class="flex justify-between items-center gap-3 pt-1.5 mt-0.5"
                                                        style="border-top: 1px solid rgba(0,0,0,0.05);">
                                                        <span class="text-gray-400 text-[10px]">Deviasi</span>
                                                        <span class="font-bold tabular-nums"
                                                            :class="(activePoint.realisasi - activePoint.rencana) >= 0 ?
                                                                'text-success' : 'text-danger'"
                                                            x-text="((activePoint.realisasi - activePoint.rencana) > 0 ? '+' : '') + formatDec(activePoint.realisasi - activePoint.rencana) + '%'"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                <template
                                    x-if="stageLevel(selectedPoint?.tahap_saat_ini) < 5 && (selectedPoint?.progres || 0) <= 0">
                                    <div
                                        class="flex-1 min-h-0 flex flex-col items-center justify-center text-center p-4">
                                        <i
                                            class="fa-solid fa-chart-line text-gray-300 dark:text-gray-600 text-2xl mb-1.5"></i>
                                        <span
                                            class="text-xs font-semibold text-textMain-light dark:text-textMain-dark">Kurva
                                            S belum aktif</span>
                                        <p class="text-[11px] text-textMuted-light max-w-sm mt-0.5">Pemantauan deviasi
                                            &
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
                                    <span
                                        class="text-[10px] font-medium px-2 py-1 bg-teal-50 dark:bg-teal-900/20 text-teal-600 dark:text-teal-400 rounded"
                                        x-text="(selectedPoint?.sarpras?.filter(s => s.status === 2)?.length || 0) + ' Sarpras Operasional'"></span>
                                </div>
                                <div class="flex-1 min-h-0 grid gap-3"
                                    style="grid-template-columns: repeat(7, minmax(0, 1fr)); grid-template-rows: repeat(2, minmax(0, 1fr));">
                                    <template x-for="(s, idx) in (selectedPoint?.sarpras || [])"
                                        :key="idx">
                                        <div class="flex flex-col items-center justify-center p-2 rounded-xl border transition-all cursor-default group"
                                            :class="s.status === 2 ?
                                                'bg-teal-50/50 dark:bg-teal-900/20 border-teal-200 dark:border-teal-800' :
                                                (s.status === 1 ? 'bg-warning/10 border-warning/20' :
                                                    'bg-gray-50 dark:bg-gray-800/50 border-gray-100 dark:border-gray-700/50'
                                                )">

                                            <div class="w-8 h-8 rounded-full flex items-center justify-center mb-2 transition-transform"
                                                :class="s.status === 2 ?
                                                    'bg-teal-100 dark:bg-teal-900/40 text-teal-600 dark:text-teal-400' :
                                                    (s.status === 1 ? 'bg-warning/20 text-warning' :
                                                        'bg-gray-200 dark:bg-gray-700 text-gray-400')">
                                                <i class="text-xs" :class="s.icon"
                                                    :style="s.status === 2 ? 'color: #0d9488;' : (s.status === 1 ?
                                                        'color: #d97706;' : 'color: #9ca3af;')"></i>
                                            </div>
                                            <span
                                                class="text-[10px] font-semibold text-center leading-tight truncate w-full"
                                                :class="s.status === 2 ? 'text-teal-700 dark:text-teal-300' : (s.status === 1 ?
                                                    'text-warning-dark' : 'text-gray-400 dark:text-gray-500')"
                                                x-text="s.nama"></span>
                                            <span class="text-[9px] font-bold mt-0.5"
                                                :class="s.status === 2 ? 'text-teal-600 dark:text-teal-400' : (s.status === 1 ?
                                                    'text-warning' : 'text-gray-400 dark:text-gray-500')"
                                                x-text="s.status === 2 ? 'Operasional' : (s.status === 1 ? 'Belum Ops.' : 'Tidak Ada')"></span>
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
                                    <h4 class="font-bold tracking-tight">Tantangan</h4>
                                    <p class="leading-relaxed text-xs sm:text-sm">Terdapat kendala teknis pada proses
                                        pengadaan material konstruksi di lapangan yang menyebabkan sedikit keterlambatan
                                        dari jadwal rencana awal. </p>
                                </div>
                            </div>

                            <div
                                class="mt-4 bg-primary/10 dark:bg-primary-500/10 border border-primary/20 dark:border-primary-500/20 rounded-xl p-4 sm:p-5 flex gap-3 sm:gap-4 items-start">
                                <div
                                    class="w-8 h-8 rounded-full bg-primary/20 dark:bg-primary-500/20 flex items-center justify-center shrink-0 mt-0.5">
                                    <i class="fa-solid fa-lightbulb text-primary dark:text-primary-400"></i>
                                </div>
                                <div class="flex flex-col gap-1.5 text-sm text-info dark:text-info-400">
                                    <h4 class="font-bold tracking-tight">Rencana Tindak Lanjut</h4>
                                    <p class="leading-relaxed text-xs sm:text-sm">Tim sedang berkoordinasi dengan pihak
                                        terkait untuk
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

<!-- MODAL DETAIL OPERASIONAL PELAKSANAAN (Offcanvas Full-Screen / Bertahap & Dinamis seperti Pengajuan) -->
<div x-show="showDetailDataModal" style="display: none; z-index: 99999;" class="fixed inset-0 overflow-hidden font-sans">
    <!-- Latar Belakang Gelap (Backdrop) -->
    <div x-show="showDetailDataModal" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="showDetailDataModal = false; activeDetail = null"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity">
    </div>

    <!-- Panel Offcanvas (Full Screen bergeser dari Kanan) -->
    <div class="fixed inset-0 w-full flex">
        <div x-show="showDetailDataModal" @click.away="showDetailDataModal = false; activeDetail = null"
            x-transition:enter="transform transition ease-in-out duration-300 sm:duration-500"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-300 sm:duration-500"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
            class="w-full bg-white dark:bg-gray-900 shadow-2xl flex flex-col text-left">

            <!-- Fixed Header (Persis seperti di menu pengajuan) -->
            <div class="px-6 sm:px-8 py-5 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center flex-shrink-0 bg-white dark:bg-gray-900 shadow-sm gap-4">
                <div class="flex items-center gap-3">
                    <h3 class="text-base font-semibold tracking-tight text-textMain-light dark:text-textMain-dark">
                        Detail Proyek Operasional KNMP
                    </h3>
                </div>

                <div class="flex items-center shrink-0">
                    <!-- Close Button (Icon Only, No Background, Red on Hover) -->
                    <button type="button" @click="showDetailDataModal = false; activeDetail = null"
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
                        <template x-if="activeDetail">
                            <div class="space-y-8">

                                <!-- STATUS ALERT BANNER (1 Warna Primary Teal di Semua Tahapan) -->
                                <div class="p-4 sm:p-5 rounded-2xl border-l-4 flex items-start gap-4 shadow-sm transition-all bg-teal-light/10 border-teal-light text-teal-light dark:bg-teal-light/20 dark:border-teal-dark dark:text-teal-dark">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg shrink-0 shadow-2xs bg-teal-light/20 text-teal-light dark:bg-teal-light/30 dark:text-teal-dark">
                                        <i class="fa-solid" :class="stageInfo(activeDetail.tahap_saat_ini).icon"></i>
                                    </div>
                                    <div class="flex-1 text-xs sm:text-sm leading-relaxed">
                                        <div class="font-semibold text-sm mb-0.5" x-text="stageInfo(activeDetail.tahap_saat_ini).label"></div>
                                        <div class="font-normal opacity-90 text-teal-light dark:text-teal-dark" x-text="stageInfo(activeDetail.tahap_saat_ini).desc"></div>
                                    </div>
                                </div>

                                <!-- BARIS 1: Informasi Wilayah & Geografis | Status dan Pengesahan Dokumen -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 py-1 mt-6">
                                    <!-- Kolom 1: Informasi Wilayah & Geografis -->
                                    <div>
                                        <h4 class="text-sm font-semibold tracking-tight text-textMain-light dark:text-textMain-dark mb-3 text-left">
                                            Informasi Wilayah & Geografis
                                        </h4>

                                        <dl class="space-y-2 text-xs sm:text-sm text-left">
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">Provinsi</dt>
                                                <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left" x-text="activeDetail?.provinsi || '-'"></dd>
                                            </div>
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">Kabupaten / Kota</dt>
                                                <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left" x-text="activeDetail?.kabupaten || '-'"></dd>
                                            </div>
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">Kecamatan</dt>
                                                <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left" x-text="activeDetail?.kecamatan || '-'"></dd>
                                            </div>
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">Desa / Kelurahan</dt>
                                                <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left" x-text="activeDetail?.desa || '-'"></dd>
                                            </div>
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">Koordinat GPS</dt>
                                                <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left truncate" x-text="activeDetail?.koordinat || '-'"></dd>
                                            </div>
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">Wilayah Tugas</dt>
                                                <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left" x-text="activeDetail?.daerah || '-'"></dd>
                                            </div>
                                        </dl>
                                    </div>

                                    <!-- Kolom 2: Status dan Pengesahan Dokumen (Bertahap dinamis) -->
                                    <div>
                                        <h4 class="text-sm font-semibold tracking-tight text-textMain-light dark:text-textMain-dark mb-3 text-left">
                                            Status dan Pengesahan Dokumen
                                        </h4>

                                        <dl class="space-y-2 text-xs sm:text-sm text-left">
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">Nomor DED</dt>
                                                <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left truncate">
                                                    <template x-if="stageLevel(activeDetail?.tahap_saat_ini) >= 3">
                                                        <span x-text="activeDetail?.tahapDed?.nomor_dokumen || 'DED-KNMP/2026/01'"></span>
                                                    </template>
                                                    <template x-if="stageLevel(activeDetail?.tahap_saat_ini) < 3">
                                                        <span class="text-textMuted-light italic">(Belum Tahap DED)</span>
                                                    </template>
                                                </dd>
                                            </div>
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">Pengesahan DED</dt>
                                                <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left flex items-center gap-1.5">
                                                    <template x-if="stageLevel(activeDetail?.tahap_saat_ini) >= 3">
                                                        <span class="flex items-center gap-1.5 text-teal-light font-semibold">
                                                            <i class="fa-solid fa-circle-check text-xs"></i>
                                                            <span x-text="activeDetail?.tahapDed?.tanggal_pengesahan !== '-' ? activeDetail?.tahapDed?.tanggal_pengesahan : 'Disahkan Tim Teknis'"></span>
                                                        </span>
                                                    </template>
                                                    <template x-if="stageLevel(activeDetail?.tahap_saat_ini) < 3">
                                                        <span class="flex items-center gap-1.5 text-textMuted-light italic">
                                                            <i class="fa-solid fa-clock text-xs"></i>
                                                            <span>Menunggu Proses</span>
                                                        </span>
                                                    </template>
                                                </dd>
                                            </div>
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">Status Lelang</dt>
                                                <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left">
                                                    <template x-if="stageLevel(activeDetail?.tahap_saat_ini) >= 4">
                                                        <span class="text-teal-light font-semibold" x-text="activeDetail?.tahapLelang?.tanggal_penetapan !== '-' ? 'Tersedia (' + activeDetail?.tahapLelang?.tanggal_penetapan + ')' : 'Penetapan Selesai'"></span>
                                                    </template>
                                                    <template x-if="stageLevel(activeDetail?.tahap_saat_ini) < 4">
                                                        <span class="text-textMuted-light italic">(Belum Masuk Tahap Lelang)</span>
                                                    </template>
                                                </dd>
                                            </div>
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">Kontrak BAST</dt>
                                                <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left truncate">
                                                    <template x-if="stageLevel(activeDetail?.tahap_saat_ini) >= 6">
                                                        <span class="text-teal-light font-semibold" x-text="activeDetail?.tahapSerahTerima?.nomor_kontrak || 'Kontrak BAST Selesai'"></span>
                                                    </template>
                                                    <template x-if="stageLevel(activeDetail?.tahap_saat_ini) < 6">
                                                        <span class="text-textMuted-light italic">(Belum Tahap Serah Terima)</span>
                                                    </template>
                                                </dd>
                                            </div>
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">Kategori Hub</dt>
                                                <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left" x-text="activeDetail?.statusHub || 'Penyangga'"></dd>
                                            </div>
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">Tanggal Daftar</dt>
                                                <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left" x-text="activeDetail?.created_at || '-'"></dd>
                                            </div>
                                        </dl>
                                    </div>
                                </div>

                                <!-- BARIS 2: Kinerja dan Fisik Konstruksi (Kurva S) | Lampiran Dokumen Operasional -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 py-1 mt-6">
                                    <!-- Kolom 1: Kinerja dan Fisik Konstruksi (Kurva S - Bertahap) -->
                                    <div>
                                        <h4 class="text-sm font-semibold tracking-tight text-textMain-light dark:text-textMain-dark mb-3 text-left">
                                            Kinerja dan Fisik Konstruksi (Kurva S)
                                        </h4>

                                        <!-- JIKA SUDAH MASUK TAHAP KONSTRUKSI ATAU SERAH TERIMA -->
                                        <template x-if="stageLevel(activeDetail?.tahap_saat_ini) >= 5 || (activeDetail?.progres || 0) > 0">
                                            <div>
                                                <!-- Grafik Kurva S Bersih (S-Curve - Border Clean seperti Template) -->
                                                <div x-data="{
                                                    activePoint: null,
                                                    get kurva() { return activeDetail?.kurvaS || []; },
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
                                                }" class="p-4 rounded-xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 shadow-2xs relative">
                                                    <div class="flex items-center justify-between text-xs font-semibold text-textMain-light dark:text-textMain-dark mb-3 pb-2 border-b border-gray-100 dark:border-gray-800">
                                                        <span class="truncate pr-2 text-teal-light font-bold" x-text="activeDetail?.konstruktor && activeDetail.konstruktor !== '-' ? activeDetail.konstruktor : 'Penyedia Jasa Konstruksi'"></span>
                                                        <div class="flex items-center gap-3 font-normal text-[11px] shrink-0">
                                                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-gray-400 inline-block"></span> Rencana (<span x-text="formatDec(activeDetail?.rencana || 0) + '%'"></span>)</span>
                                                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-teal-light inline-block"></span> Realisasi (<span x-text="formatDec(activeDetail?.progres || 0) + '%'"></span>)</span>
                                                        </div>
                                                    </div>

                                                    <!-- Tooltip Box saat Hover -->
                                                    <div x-show="activePoint !== null" x-cloak
                                                        x-transition:enter="transition ease-out duration-150"
                                                        x-transition:enter-start="opacity-0 translate-y-1"
                                                        x-transition:enter-end="opacity-100 translate-y-0"
                                                        :style="activePoint ? `left: ${Math.min(80, Math.max(20, (activePoint.x / 400) * 100))}%; top: 38px;` : ''"
                                                        class="absolute z-30 pointer-events-none bg-gray-900/95 dark:bg-gray-800/95 text-white px-3 py-2 rounded-xl shadow-xl border border-gray-700 text-xs w-48 -translate-x-1/2 backdrop-blur-xs">
                                                        <div class="font-bold border-b border-gray-700 pb-1 mb-1.5 flex justify-between items-center text-[11px]">
                                                            <span class="text-teal-300" x-text="activePoint?.label || activePoint?.minggu"></span>
                                                            <span class="text-[9px] text-gray-400 font-normal">Target vs Aktual</span>
                                                        </div>
                                                        <div class="space-y-1 text-[11px]">
                                                            <div class="flex justify-between items-center">
                                                                <span class="flex items-center gap-1.5 text-gray-300">
                                                                    <span class="w-2 h-2 rounded-full bg-gray-400 inline-block"></span> Rencana:
                                                                </span>
                                                                <span class="font-mono font-semibold text-gray-100" x-text="formatDec(activePoint?.rencana || 0) + '%'"></span>
                                                            </div>
                                                            <div class="flex justify-between items-center">
                                                                <span class="flex items-center gap-1.5 text-teal-300">
                                                                    <span class="w-2 h-2 rounded-full bg-teal-400 inline-block"></span> Realisasi:
                                                                </span>
                                                                <span class="font-mono font-bold text-teal-300" x-text="activePoint?.realisasi !== null && activePoint?.realisasi !== undefined ? formatDec(activePoint.realisasi) + '%' : 'Belum Ada'"></span>
                                                            </div>
                                                            <template x-if="activePoint?.realisasi !== null && activePoint?.realisasi !== undefined">
                                                                <div class="flex justify-between items-center pt-1 border-t border-gray-800 text-[10px]">
                                                                    <span class="text-gray-400">Deviasi:</span>
                                                                    <span class="font-mono font-bold" :class="(activePoint.realisasi - activePoint.rencana) >= 0 ? 'text-teal-400' : 'text-danger'" x-text="((activePoint.realisasi - activePoint.rencana) >= 0 ? '+' : '') + formatDec(activePoint.realisasi - activePoint.rencana) + '%'"></span>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>

                                                    <div class="w-full h-36 relative flex items-center justify-center py-1 select-none">
                                                        <svg class="w-full h-full overflow-visible" viewBox="0 0 400 110" preserveAspectRatio="none">
                                                            <!-- Y-Value Labels (Direct in SVG untuk Presisi 100% terhadap Grid) -->
                                                            <text x="23" y="18" text-anchor="end" fill="currentColor" class="text-[9px] font-mono font-medium text-gray-400 dark:text-gray-500">100%</text>
                                                            <text x="23" y="58" text-anchor="end" fill="currentColor" class="text-[9px] font-mono font-medium text-gray-400 dark:text-gray-500">50%</text>
                                                            <text x="23" y="98" text-anchor="end" fill="currentColor" class="text-[9px] font-mono font-medium text-gray-400 dark:text-gray-500">0%</text>

                                                            <!-- Grid Lines -->
                                                            <line x1="28" y1="15" x2="395" y2="15" stroke="currentColor" class="text-gray-100 dark:text-gray-800" stroke-width="1" stroke-dasharray="2,2" />
                                                            <line x1="28" y1="55" x2="395" y2="55" stroke="currentColor" class="text-gray-100 dark:text-gray-800" stroke-width="1" stroke-dasharray="2,2" />
                                                            <line x1="28" y1="95" x2="395" y2="95" stroke="currentColor" class="text-gray-200 dark:text-gray-700" stroke-width="1" />

                                                            <!-- Garis panduan saat Hover -->
                                                            <template x-if="activePoint !== null">
                                                                <line :x1="activePoint.x" y1="15" :x2="activePoint.x" y2="95" stroke="#0d9488" stroke-width="1" stroke-dasharray="3,3" opacity="0.6" />
                                                            </template>

                                                            <!-- Kurva Rencana -->
                                                            <path :d="getRencanaPath()" fill="none" stroke="#94a3b8" stroke-width="1.8" stroke-dasharray="4,4" />

                                                            <!-- Kurva Realisasi (Value Fill & Line) -->
                                                            <path :d="getFillPath()" fill="url(#tealGradOperasional)" opacity="0.2" />
                                                            <path :d="getRealisasiPath()" fill="none" stroke="#0d9488" stroke-width="2.2" stroke-linecap="round" />

                                                            <!-- Dots & Interactive Hover Areas -->
                                                            <template x-for="(k, idx) in kurva" :key="'dot-ren-' + idx">
                                                                <circle :cx="getX(idx)" :cy="getY(k.rencana)" r="2.5" fill="#94a3b8" class="transition-all" :class="activePoint?.idx === idx ? 'r-4 fill-gray-700 stroke-white stroke-2' : ''" />
                                                            </template>
                                                            <template x-for="(k, idx) in kurva" :key="'dot-real-' + idx">
                                                                <template x-if="k.realisasi !== null && k.realisasi !== undefined">
                                                                    <circle :cx="getX(idx)" :cy="getY(k.realisasi)" r="3.5" fill="#0d9488" stroke="#ffffff" stroke-width="1.5" class="transition-all" :class="activePoint?.idx === idx ? 'r-5 stroke-2' : ''" />
                                                                </template>
                                                            </template>

                                                            <!-- Hover Hitboxes / Strips -->
                                                            <template x-for="(k, idx) in kurva" :key="'hitbox-' + idx">
                                                                <rect :x="getX(idx) - (180 / kurva.length)" y="10" :width="360 / kurva.length" height="90" fill="transparent" class="cursor-pointer"
                                                                    @mouseenter="activePoint = { idx, ...k, x: getX(idx), yRen: getY(k.rencana), yReal: getY(k.realisasi) }"
                                                                    @mouseleave="activePoint = null" />
                                                            </template>

                                                            <!-- Angka Langsung di Dots saat Hover -->
                                                            <template x-if="activePoint !== null">
                                                                <g class="pointer-events-none">
                                                                    <!-- Pill Rencana -->
                                                                    <rect :x="activePoint.x - 17" :y="activePoint.yRen - 16" width="34" height="13" rx="3" fill="#334155" opacity="0.95" />
                                                                    <text :x="activePoint.x" :y="activePoint.yRen - 7" text-anchor="middle" fill="#ffffff" class="text-[7.5px] font-mono font-bold" x-text="formatDec(activePoint.rencana) + '%'"></text>

                                                                    <!-- Pill Realisasi (jika ada) -->
                                                                    <template x-if="activePoint.realisasi !== null && activePoint.realisasi !== undefined">
                                                                        <g>
                                                                            <rect :x="activePoint.x - 17" :y="activePoint.yReal + 5" width="34" height="13" rx="3" fill="#0d9488" opacity="0.95" />
                                                                            <text :x="activePoint.x" :y="activePoint.yReal + 14" text-anchor="middle" fill="#ffffff" class="text-[7.5px] font-mono font-bold" x-text="formatDec(activePoint.realisasi) + '%'"></text>
                                                                        </g>
                                                                    </template>
                                                                </g>
                                                            </template>

                                                            <defs>
                                                                <linearGradient id="tealGradOperasional" x1="0%" y1="0%" x2="0%" y2="100%">
                                                                    <stop offset="0%" stop-color="#0d9488" />
                                                                    <stop offset="100%" stop-color="#0d9488" stop-opacity="0" />
                                                                </linearGradient>
                                                            </defs>
                                                        </svg>
                                                    </div>

                                                    <!-- X-Value Labels (Minggu) -->
                                                    <div class="flex justify-between text-[10px] text-textMuted-light mt-1 pt-2 border-t border-gray-100 dark:border-gray-800 pl-8 pr-1">
                                                        <template x-for="(k, idx) in kurva" :key="'lbl-' + idx">
                                                            <span class="truncate text-center transition-colors cursor-pointer" 
                                                                :class="activePoint?.idx === idx ? 'font-bold text-teal-light scale-105' : (k.realisasi !== null ? 'font-semibold text-textMain-light dark:text-textMain-dark' : '')" 
                                                                @mouseenter="activePoint = { idx, ...k, x: getX(idx), yRen: getY(k.rencana), yReal: getY(k.realisasi) }"
                                                                @mouseleave="activePoint = null"
                                                                x-text="k.minggu"></span>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- JIKA BELUM MASUK TAHAP KONSTRUKSI (1 Warna Primary Teal) -->
                                        <template x-if="stageLevel(activeDetail?.tahap_saat_ini) < 5 && (activeDetail?.progres || 0) <= 0">
                                            <div class="p-6 rounded-2xl bg-teal-light/10 dark:bg-teal-light/20 border border-teal-light/30 text-center flex flex-col items-center justify-center gap-2.5 py-10">
                                                <div class="w-12 h-12 rounded-xl bg-teal-light/20 dark:bg-teal-light/30 flex items-center justify-center text-teal-light text-xl">
                                                    <i class="fa-solid fa-chart-line"></i>
                                                </div>
                                                <div class="text-xs font-semibold text-textMain-light dark:text-textMain-dark">Tahap Konstruksi Fisik Belum Dimulai</div>
                                                <p class="text-[11px] text-teal-light dark:text-teal-dark max-w-sm leading-relaxed">
                                                    Proyek ini masih dalam tahap <span class="font-semibold" x-text="stageInfo(activeDetail.tahap_saat_ini).label"></span>. Pemantauan deviasi fisik, realisasi progres harian, dan grafik Kurva S akan aktif secara otomatis setelah tahapan konstruksi lapangan berjalan.
                                                </p>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Kolom 2: Lampiran Dokumen Operasional (Ukuran & struktur persis view detail pengajuan) -->
                                    <div>
                                        <h4 class="text-sm font-semibold tracking-tight text-textMain-light dark:text-textMain-dark mb-3 text-left">
                                            Lampiran Dokumen Operasional
                                        </h4>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <!-- DED Dokumen Card -->
                                            <template x-if="stageLevel(activeDetail?.tahap_saat_ini) >= 3">
                                                <a @click="openPreviewDedModal(activeDetail)" target="_blank"
                                                    class="flex items-center justify-between p-4 rounded-xl bg-teal-light dark:bg-teal-dark hover:bg-teal-light/90 dark:hover:bg-teal-dark/90 text-white dark:text-white shadow-xs hover:shadow-md transition-all duration-200 cursor-pointer group font-sans"
                                                    style="font-family: 'Inter', sans-serif;">
                                                    <div class="flex items-center min-w-0 flex-1">
                                                        <div class="shrink-0 flex items-center justify-center w-10 h-10 rounded-xl bg-white/15 dark:bg-white/20 text-white dark:text-white group-hover:scale-105 transition-transform mr-4">
                                                            <i class="fa-solid fa-file-pdf text-sm"></i>
                                                        </div>
                                                        <div class="flex-1 min-w-0 text-left ml-3">
                                                            <div class="text-xs font-semibold text-white dark:text-white truncate">Detail Engineering Design (DED)</div>
                                                            <div class="text-[11px] font-normal text-white/90 dark:text-white/90 truncate mt-0.5" x-text="activeDetail?.tahapDed?.nomor_dokumen || 'DED-KNMP/2026/01'"></div>
                                                        </div>
                                                    </div>
                                                    <div class="shrink-0 ml-3 text-white/80 dark:text-white/80 group-hover:text-white dark:group-hover:text-white transition-colors">
                                                        <i class="fa-solid fa-arrow-up-right-from-square text-xs group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"></i>
                                                    </div>
                                                </a>
                                            </template>

                                            <!-- Dokumen Lelang & Tender Card -->
                                            <template x-if="stageLevel(activeDetail?.tahap_saat_ini) >= 4">
                                                <a @click="openPreviewDedModal(activeDetail)" target="_blank"
                                                    class="flex items-center justify-between p-4 rounded-xl bg-teal-light dark:bg-teal-dark hover:bg-teal-light/90 dark:hover:bg-teal-dark/90 text-white dark:text-white shadow-xs hover:shadow-md transition-all duration-200 cursor-pointer group font-sans"
                                                    style="font-family: 'Inter', sans-serif;">
                                                    <div class="flex items-center min-w-0 flex-1">
                                                        <div class="shrink-0 flex items-center justify-center w-10 h-10 rounded-xl bg-white/15 dark:bg-white/20 text-white dark:text-white group-hover:scale-105 transition-transform mr-4">
                                                            <i class="fa-solid fa-file-contract text-sm"></i>
                                                        </div>
                                                        <div class="flex-1 min-w-0 text-left ml-3">
                                                            <div class="text-xs font-semibold text-white dark:text-white truncate">Dokumen Tender & Lelang</div>
                                                            <div class="text-[11px] font-normal text-white/90 dark:text-white/90 truncate mt-0.5" x-text="activeDetail?.tahapLelang?.tanggal_penetapan !== '-' ? 'Penetapan: ' + activeDetail?.tahapLelang?.tanggal_penetapan : 'Paket Dokumen Pengadaan'"></div>
                                                        </div>
                                                    </div>
                                                    <div class="shrink-0 ml-3 text-white/80 dark:text-white/80 group-hover:text-white dark:group-hover:text-white transition-colors">
                                                        <i class="fa-solid fa-arrow-up-right-from-square text-xs group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"></i>
                                                    </div>
                                                </a>
                                            </template>

                                            <!-- Dokumen BAST Card -->
                                            <template x-if="stageLevel(activeDetail?.tahap_saat_ini) >= 6">
                                                <a @click="openPreviewDedModal(activeDetail)" target="_blank"
                                                    class="flex items-center justify-between p-4 rounded-xl bg-teal-light dark:bg-teal-dark hover:bg-teal-light/90 dark:hover:bg-teal-dark/90 text-white dark:text-white shadow-xs hover:shadow-md transition-all duration-200 cursor-pointer group font-sans sm:col-span-2"
                                                    style="font-family: 'Inter', sans-serif;">
                                                    <div class="flex items-center min-w-0 flex-1">
                                                        <div class="shrink-0 flex items-center justify-center w-10 h-10 rounded-xl bg-white/15 dark:bg-white/20 text-white dark:text-white group-hover:scale-105 transition-transform mr-4">
                                                            <i class="fa-solid fa-file-circle-check text-sm"></i>
                                                        </div>
                                                        <div class="flex-1 min-w-0 text-left ml-3">
                                                            <div class="text-xs font-semibold text-white dark:text-white truncate">Berita Acara Serah Terima (BAST)</div>
                                                            <div class="text-[11px] font-normal text-white/90 dark:text-white/90 truncate mt-0.5" x-text="activeDetail?.tahapSerahTerima?.nomor_kontrak || 'BAST / Penyerahan Aset'"></div>
                                                        </div>
                                                    </div>
                                                    <div class="shrink-0 ml-3 text-white/80 dark:text-white/80 group-hover:text-white dark:group-hover:text-white transition-colors">
                                                        <i class="fa-solid fa-arrow-up-right-from-square text-xs group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"></i>
                                                    </div>
                                                </a>
                                            </template>

                                            <!-- Jika belum mencapai tahap DED (Belum ada dokumen yang aktif) -->
                                            <template x-if="stageLevel(activeDetail?.tahap_saat_ini) < 3">
                                                <div class="p-6 rounded-2xl bg-teal-light/10 dark:bg-teal-light/20 border border-teal-light/30 text-center flex flex-col items-center justify-center gap-2.5 py-10 sm:col-span-2">
                                                    <div class="w-12 h-12 rounded-xl bg-teal-light/20 dark:bg-teal-light/30 flex items-center justify-center text-teal-light text-xl">
                                                        <i class="fa-solid fa-folder-open"></i>
                                                    </div>
                                                    <div class="text-xs font-semibold text-textMain-light dark:text-textMain-dark">Belum Ada Dokumen Operasional</div>
                                                    <p class="text-[11px] text-teal-light dark:text-teal-dark max-w-sm leading-relaxed">
                                                        Dokumen resmi DED, Tender Lelang, dan BAST akan muncul secara bertahap saat proyek mencapai tahapan terkait.
                                                    </p>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <!-- BARIS 3: Foto Bukti Pendukung (Before & After) | Riwayat Kronologis -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 py-1 mt-6">
                                    <!-- Kolom 1: Foto Bukti Pendukung (Before & After - Gap luas tanpa border) -->
                                    <div>
                                        <h4 class="text-sm font-semibold tracking-tight text-textMain-light dark:text-textMain-dark mb-3 text-left">
                                            Foto Bukti Pendukung (Before & After)
                                        </h4>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                                            <!-- Kondisi Awal Before -->
                                            <div class="space-y-2.5">
                                                <div class="text-xs font-medium text-textMuted-light">Kondisi Awal (Before)</div>
                                                <template x-if="activeDetail?.fotosBefore && activeDetail?.fotosBefore.length > 0">
                                                    <div class="space-y-3">
                                                        <template x-for="(foto, idx) in activeDetail.fotosBefore" :key="'bef-' + idx">
                                                            <a :href="foto.url" target="_blank" style="height: 180px; min-height: 180px;" class="block w-full rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-800 relative group shadow-xs border-0 shrink-0">
                                                                <img :src="foto.url" :alt="foto.nama" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                                                <span class="absolute bottom-1.5 left-1.5 px-2 py-0.5 rounded text-[10px] font-semibold bg-gray-900/80 text-white backdrop-blur-xs">Before</span>
                                                            </a>
                                                        </template>
                                                    </div>
                                                </template>
                                                <template x-if="!activeDetail?.fotosBefore || activeDetail?.fotosBefore.length === 0">
                                                    <div style="height: 180px; min-height: 180px;" class="w-full rounded-xl bg-gray-100 dark:bg-gray-800/50 flex flex-col items-center justify-center p-3 text-center border-0 shadow-xs shrink-0">
                                                        <i class="fa-regular fa-image text-gray-300 dark:text-gray-600 text-xl mb-1"></i>
                                                        <span class="text-[11px] text-textMuted-light">Foto Awal belum diunggah</span>
                                                    </div>
                                                </template>
                                            </div>

                                            <!-- Progres After -->
                                            <div class="space-y-2.5">
                                                <div class="text-xs font-medium text-textMain-light dark:text-textMain-dark">Progres Lapangan (After)</div>
                                                <template x-if="activeDetail?.fotosAfter && activeDetail?.fotosAfter.length > 0">
                                                    <div class="space-y-3">
                                                        <template x-for="(foto, idx) in activeDetail.fotosAfter" :key="'aft-' + idx">
                                                            <a :href="foto.url" target="_blank" style="height: 180px; min-height: 180px;" class="block w-full rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-800 relative group shadow-xs border-0 shrink-0">
                                                                <img :src="foto.url" :alt="foto.nama" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                                                <span class="absolute bottom-1.5 left-1.5 px-2 py-0.5 rounded text-[10px] font-semibold bg-teal-light text-white backdrop-blur-xs">Progress</span>
                                                            </a>
                                                        </template>
                                                    </div>
                                                </template>
                                                <template x-if="(!activeDetail?.fotosAfter || activeDetail?.fotosAfter.length === 0) && stageLevel(activeDetail?.tahap_saat_ini) >= 5">
                                                    <div style="height: 180px; min-height: 180px;" class="w-full rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-800 relative shadow-xs border-0 shrink-0">
                                                        <img src="{{ asset('assets/images/placeholder-project.jpg') }}" onerror="this.src='https://images.unsplash.com/photo-1541888946425-d09bb180c6f3?auto=format&fit=crop&w=600&q=80'" alt="Ilustrasi Proyek" class="w-full h-full object-cover">
                                                        <span class="absolute bottom-1.5 left-1.5 px-2 py-0.5 rounded text-[10px] font-semibold bg-teal-light text-white backdrop-blur-xs" x-text="formatDec(activeDetail?.progres) + '% Progress'"></span>
                                                    </div>
                                                </template>
                                                <template x-if="(!activeDetail?.fotosAfter || activeDetail?.fotosAfter.length === 0) && stageLevel(activeDetail?.tahap_saat_ini) < 5">
                                                    <div style="height: 180px; min-height: 180px;" class="w-full rounded-xl bg-gray-100 dark:bg-gray-800/50 flex flex-col items-center justify-center p-3 text-center border-0 shadow-xs shrink-0">
                                                        <i class="fa-solid fa-camera-rotate text-gray-300 dark:text-gray-600 text-xl mb-1"></i>
                                                        <span class="text-[11px] text-textMuted-light">Foto progres akan diunggah bertahap</span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Kolom 2: Riwayat Kronologis (Tampilkan HANYA sampai tahap saat ini dengan 1 Warna Primary Teal) -->
                                    <div>
                                        <h4 class="text-sm font-semibold tracking-tight text-textMain-light dark:text-textMain-dark mb-3 text-left">
                                            Riwayat Kronologis & Tahapan Siklus
                                        </h4>

                                        <dl class="space-y-2.5 text-xs">
                                            <!-- Tahap 1: Usulan (Selalu tampil minimal di tahap 1) -->
                                            <template x-if="stageLevel(activeDetail?.tahap_saat_ini) >= 1">
                                                <div class="flex items-start gap-4 py-1 text-left">
                                                    <dt class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">Tahap 1: Usulan Lokasi</dt>
                                                    <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left">
                                                        <div class="flex items-center gap-2 flex-wrap">
                                                            <span x-text="activeDetail?.tahapUsulan?.tanggal || activeDetail?.created_at || '-'"></span>
                                                            <span class="px-2 py-0.5 rounded-md bg-teal-100 text-teal-800 dark:bg-teal-900/50 dark:text-teal-300 text-[10px] font-semibold">Selesai</span>
                                                        </div>
                                                        <div class="text-[11px] font-normal text-textMuted-light dark:text-textMuted-dark mt-1" x-text="`Catatan: ${activeDetail?.tahapUsulan?.catatan || 'Verifikasi usulan lokasi selesai.'}`"></div>
                                                    </dd>
                                                </div>
                                            </template>

                                            <!-- Tahap 2: Survei (Hanya tampil jika tahap saat ini >= 2) -->
                                            <template x-if="stageLevel(activeDetail?.tahap_saat_ini) >= 2">
                                                <div class="flex items-start gap-4 py-1 text-left">
                                                    <dt class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">Tahap 2: Survei Lapangan</dt>
                                                    <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left">
                                                        <div class="flex items-center gap-2 flex-wrap">
                                                            <span x-text="activeDetail?.tahapSurvey?.tanggal !== '-' ? activeDetail?.tahapSurvey?.tanggal : (activeDetail?.created_at || '-')"></span>
                                                            <span class="px-2 py-0.5 rounded-md bg-teal-100 text-teal-800 dark:bg-teal-900/50 dark:text-teal-300 text-[10px] font-semibold">Selesai</span>
                                                        </div>
                                                        <div class="text-[11px] font-normal text-textMuted-light dark:text-textMuted-dark mt-1" x-text="`Catatan: ${activeDetail?.tahapSurvey?.catatan || 'Survei geoteknik & hidrologi.'}`"></div>
                                                    </dd>
                                                </div>
                                            </template>

                                            <!-- Tahap 3: DED (Hanya tampil jika tahap saat ini >= 3) -->
                                            <template x-if="stageLevel(activeDetail?.tahap_saat_ini) >= 3">
                                                <div class="flex items-start gap-4 py-1 text-left">
                                                    <dt class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">Tahap 3: Dokumen DED</dt>
                                                    <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left">
                                                        <div class="flex items-center gap-2 flex-wrap">
                                                            <span x-text="activeDetail?.tahapDed?.tanggal_pengesahan !== '-' ? activeDetail?.tahapDed?.tanggal_pengesahan : (activeDetail?.created_at || '-')"></span>
                                                            <span class="px-2 py-0.5 rounded-md bg-teal-100 text-teal-800 dark:bg-teal-900/50 dark:text-teal-300 text-[10px] font-semibold">Disahkan</span>
                                                        </div>
                                                        <div class="text-[11px] font-normal text-textMuted-light dark:text-textMuted-dark mt-1" x-text="`Nomor DED: ${activeDetail?.tahapDed?.nomor_dokumen || '-'}`"></div>
                                                    </dd>
                                                </div>
                                            </template>

                                            <!-- Tahap 4: Lelang (Hanya tampil jika tahap saat ini >= 4) -->
                                            <template x-if="stageLevel(activeDetail?.tahap_saat_ini) >= 4">
                                                <div class="flex items-start gap-4 py-1 text-left">
                                                    <dt class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">Tahap 4: Lelang & Tender</dt>
                                                    <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left">
                                                        <div class="flex items-center gap-2 flex-wrap">
                                                            <span x-text="activeDetail?.tahapLelang?.tanggal_penetapan !== '-' ? activeDetail?.tahapLelang?.tanggal_penetapan : (activeDetail?.created_at || '-')"></span>
                                                            <span class="px-2 py-0.5 rounded-md bg-teal-100 text-teal-800 dark:bg-teal-900/50 dark:text-teal-300 text-[10px] font-semibold">Selesai</span>
                                                        </div>
                                                        <div class="text-[11px] font-normal text-textMuted-light dark:text-textMuted-dark mt-1" x-text="`Catatan: ${activeDetail?.tahapLelang?.catatan || 'Pengadaan penyedia konstruksi.'}`"></div>
                                                    </dd>
                                                </div>
                                            </template>

                                            <!-- Tahap 5: Konstruksi (Hanya tampil jika tahap saat ini >= 5) -->
                                            <template x-if="stageLevel(activeDetail?.tahap_saat_ini) >= 5">
                                                <div class="flex items-start gap-4 py-1 text-left">
                                                    <dt class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">Tahap 5: Konstruksi Fisik</dt>
                                                    <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left">
                                                        <div class="flex items-center gap-2 flex-wrap">
                                                            <span x-text="activeDetail?.created_at || '-'"></span>
                                                            <span class="px-2 py-0.5 rounded-md bg-teal-100 text-teal-800 dark:bg-teal-900/50 dark:text-teal-300 text-[10px] font-semibold" x-text="stageLevel(activeDetail?.tahap_saat_ini) > 5 ? 'Selesai (100%)' : (formatDec(activeDetail?.progres) + '% Progress')"></span>
                                                        </div>
                                                        <div class="text-[11px] font-normal text-textMuted-light dark:text-textMuted-dark mt-1 truncate" x-text="`Pelaksana: ${activeDetail?.konstruktor || '-'}`"></div>
                                                    </dd>
                                                </div>
                                            </template>

                                            <!-- Tahap 6: Serah Terima (Hanya tampil jika tahap saat ini >= 6) -->
                                            <template x-if="stageLevel(activeDetail?.tahap_saat_ini) >= 6">
                                                <div class="flex items-start gap-4 py-1 text-left">
                                                    <dt class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">Tahap 6: BAST / Serah Terima</dt>
                                                    <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left">
                                                        <div class="flex items-center gap-2 flex-wrap">
                                                            <span x-text="activeDetail?.tahapSerahTerima?.tanggal !== '-' ? activeDetail?.tahapSerahTerima?.tanggal : (activeDetail?.created_at || '-')"></span>
                                                            <span class="px-2 py-0.5 rounded-md bg-teal-100 text-teal-800 dark:bg-teal-900/50 dark:text-teal-300 text-[10px] font-semibold">Selesai</span>
                                                        </div>
                                                        <div class="text-[11px] font-normal text-textMuted-light dark:text-textMuted-dark mt-1 font-mono truncate" x-text="`Kontrak: ${activeDetail?.tahapSerahTerima?.nomor_kontrak || '-'}`"></div>
                                                    </dd>
                                                </div>
                                            </template>
                                        </dl>
                                    </div>
                                </div>

                            </div>
                        </template>
                        <template x-if="!activeDetail">
                            <div class="flex flex-col items-center justify-center py-20 text-textMuted-light dark:text-textMuted-dark gap-3">
                                <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-2xl text-gray-400 dark:text-gray-500">
                                    <i class="fa-solid fa-circle-info"></i>
                                </div>
                                <p class="text-base font-semibold text-textMain-light dark:text-textMain-dark">Data detail proyek tidak tersedia.</p>
                                <p class="text-xs text-textMuted-light dark:text-textMuted-dark">Silakan pilih data proyek dari tabel utama.</p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

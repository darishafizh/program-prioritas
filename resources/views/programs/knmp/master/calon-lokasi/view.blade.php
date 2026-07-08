<!-- MODAL 4: Tampilkan Semua Data Detail (Offcanvas Full-Screen) -->
<div x-show="showDetailDataModal" style="display: none; z-index: 99999;" class="fixed inset-0 overflow-hidden">
    <!-- Latar Belakang Gelap (Backdrop) -->
    <div x-show="showDetailDataModal" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="showDetailDataModal = false"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity">
    </div>

    <!-- Panel Offcanvas (Full Screen bergeser dari Kanan) -->
    <div class="fixed inset-0 w-full flex">
        <div x-show="showDetailDataModal" @click.away="showDetailDataModal = false"
            x-transition:enter="transform transition ease-in-out duration-300 sm:duration-500"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-300 sm:duration-500"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
            class="w-full bg-white dark:bg-gray-900 shadow-2xl flex flex-col text-left">

            <!-- Fixed Header -->
            <div
                class="px-6 sm:px-8 py-5 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center flex-shrink-0 bg-white dark:bg-gray-900 shadow-sm gap-4">
                <h3 class="text-lg font-bold tracking-tight text-textMain-light dark:text-textMain-dark">
                    Detail Usulan Calon Lokasi
                </h3>

                <div class="flex items-center shrink-0">
                    <!-- Close Button (Icon Only, No Background, Red on Hover) -->
                    <button type="button" @click="showDetailDataModal = false"
                        class="cursor-pointer text-gray-500 dark:text-gray-400 hover:text-danger dark:hover:text-danger p-2 transition-colors focus:outline-none"
                        title="Tutup Panel">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Scrollable Body with internal scroll -->
            <div class="flex flex-col flex-1 overflow-hidden bg-gray-50/60 dark:bg-gray-950/50">
                <div class="overflow-y-auto overscroll-contain p-6 sm:p-8 flex-1">
                    <div class="max-w-5xl mx-auto space-y-8">
                        <template x-if="activeDetail && activeDetail.detail">
                            <div class="space-y-8">

                                <!-- STATUS ALERT BANNER -->
                                <div class="p-4 sm:p-5 rounded-2xl border-l-4 flex items-start gap-4 shadow-sm transition-all"
                                    :class="{
                                        'bg-teal-light/10 border-teal-light text-teal-light dark:bg-teal-light/20 dark:border-teal-dark dark:text-teal-dark': activeDetail
                                            ?.status !== 'Diverifikasi' && activeDetail?.status !== 'Selesai' &&
                                            activeDetail?.status !== 'Ditolak',
                                        'bg-emerald-50/70 border-emerald-500 text-emerald-900 dark:bg-emerald-950/30 dark:border-emerald-500 dark:text-emerald-200': activeDetail
                                            ?.status === 'Diverifikasi' || activeDetail?.status === 'Selesai',
                                        'bg-rose-50/70 border-rose-500 text-rose-900 dark:bg-rose-950/30 dark:border-rose-500 dark:text-rose-200': activeDetail
                                            ?.status === 'Ditolak'
                                    }">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg shrink-0 shadow-2xs"
                                        :class="{
                                            'bg-teal-light/20 text-teal-light dark:bg-teal-light/30 dark:text-teal-dark': activeDetail
                                                ?.status !== 'Diverifikasi' && activeDetail?.status !== 'Selesai' &&
                                                activeDetail?.status !== 'Ditolak',
                                            'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/60 dark:text-emerald-400': activeDetail
                                                ?.status === 'Diverifikasi' || activeDetail?.status === 'Selesai',
                                            'bg-rose-100 text-rose-600 dark:bg-rose-900/60 dark:text-rose-400': activeDetail
                                                ?.status === 'Ditolak'
                                        }">
                                        <i class="fa-solid"
                                            :class="{
                                                'fa-clock-rotate-left': activeDetail?.status !== 'Diverifikasi' &&
                                                    activeDetail?.status !== 'Selesai' &&
                                                    activeDetail?.status !== 'Ditolak',
                                                'fa-circle-check': activeDetail?.status === 'Diverifikasi' ||
                                                    activeDetail?.status === 'Selesai',
                                                'fa-circle-xmark': activeDetail?.status === 'Ditolak'
                                            }"></i>
                                    </div>
                                    <div class="flex-1 text-xs sm:text-sm leading-relaxed">
                                        <div class="font-bold text-sm mb-0.5"
                                            x-text="activeDetail?.status === 'Diverifikasi' || activeDetail?.status === 'Selesai' ? 'Status Disetujui' : (activeDetail?.status === 'Ditolak' ? 'Pemberitahuan Penolakan' : 'Informasi Verifikasi')">
                                        </div>
                                        <div class="font-normal"
                                            :class="{
                                                'text-teal-light dark:text-teal-dark': activeDetail
                                                    ?.status !== 'Diverifikasi' && activeDetail
                                                    ?.status !== 'Selesai' && activeDetail?.status !== 'Ditolak',
                                                'text-emerald-800 dark:text-emerald-200': activeDetail
                                                    ?.status === 'Diverifikasi' || activeDetail?.status === 'Selesai',
                                                'text-rose-800 dark:text-rose-200': activeDetail?.status === 'Ditolak'
                                            }"
                                            x-text="activeDetail?.status === 'Diverifikasi' || activeDetail?.status === 'Selesai' ? 'Usulan calon lokasi ini telah lolos tahapan verifikasi administrasi & teknis lapangan dan resmi ditetapkan.' : (activeDetail?.status === 'Ditolak' ? 'Usulan calon lokasi ini tidak memenuhi kriteria kelayakan dan telah ditolak pada proses evaluasi.' : 'Usulan calon lokasi ini sedang dalam tahap review dan menunggu proses pemeriksaan dokumen administrasi serta survei teknis lapangan.')">
                                        </div>
                                    </div>
                                </div>

                                <!-- SECTION 1 & SECTION 2: 2-COLUMN LAYOUT -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 py-1 mt-6">
                                    <!-- SECTION 1: INFORMASI WILAYAH & GEOGRAFIS -->
                                    <div>
                                        <h4
                                            class="text-sm font-bold tracking-tight text-textMain-light dark:text-textMain-dark mb-3 text-left">
                                            Informasi Wilayah & Geografis
                                        </h4>

                                        <dl class="space-y-2 text-xs sm:text-sm text-left">
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt
                                                    class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                    Provinsi</dt>
                                                <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left"
                                                    x-text="activeDetail?.provinsi || '-'"></dd>
                                            </div>
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt
                                                    class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                    Kabupaten / Kota</dt>
                                                <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left"
                                                    x-text="activeDetail?.kabupaten || '-'"></dd>
                                            </div>
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt
                                                    class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                    Kecamatan</dt>
                                                <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left"
                                                    x-text="activeDetail?.kecamatan || '-'"></dd>
                                            </div>
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt
                                                    class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                    Desa /
                                                    Kelurahan</dt>
                                                <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left"
                                                    x-text="activeDetail?.desa || '-'"></dd>
                                            </div>
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt
                                                    class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                    Luas
                                                    Lahan</dt>
                                                <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left"
                                                    x-text="`${activeDetail?.pengajuan?.luas_lahan || 0} m²`"></dd>
                                            </div>
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt
                                                    class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                    Dimensi (P × L)</dt>
                                                <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left"
                                                    x-text="`${activeDetail?.pengajuan?.panjang_lahan || 0}m × ${activeDetail?.pengajuan?.lebar_lahan || 0}m`">
                                                </dd>
                                            </div>
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt
                                                    class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                    Koordinat GPS</dt>
                                                <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left truncate"
                                                    x-text="activeDetail?.lat && activeDetail?.lng ? `${activeDetail.lat}, ${activeDetail.lng}` : '-'">
                                                </dd>
                                            </div>
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt
                                                    class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                    Tanggal Diajukan</dt>
                                                <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left"
                                                    x-text="activeDetail?.created_at || '-'"></dd>
                                            </div>
                                        </dl>
                                    </div>

                                    <!-- SECTION 2: SPESIFIKASI FISIK & KARAKTERISTIK LAHAN -->
                                    <div>
                                        <h4
                                            class="text-sm font-bold tracking-tight text-textMain-light dark:text-textMain-dark mb-3 text-left">
                                            Spesifikasi Fisik & Karakteristik Lahan
                                        </h4>

                                        <dl class="space-y-2 text-xs sm:text-sm text-left">
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt
                                                    class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                    Status Kepemilikan</dt>
                                                <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left"
                                                    x-text="activeDetail?.detail?.status_kepemilikan || '-'"></dd>
                                            </div>
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt
                                                    class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                    Kesesuaian RTRW</dt>
                                                <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left"
                                                    x-text="activeDetail?.detail?.kesesuaian_rtrw || '-'"></dd>
                                            </div>
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt
                                                    class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                    Kemiringan Lahan</dt>
                                                <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left"
                                                    x-text="`${activeDetail?.pengajuan?.kemiringan_lahan || 0}°`"></dd>
                                            </div>
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt
                                                    class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                    Tekstur Tanah</dt>
                                                <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left"
                                                    x-text="activeDetail?.pengajuan?.tekstur_tanah || '-'"></dd>
                                            </div>
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt
                                                    class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                    Salinitas Air</dt>
                                                <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left"
                                                    x-text="activeDetail?.pengajuan?.salinitas_air || '-'"></dd>
                                            </div>
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt
                                                    class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                    Jarak ke Pantai</dt>
                                                <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left"
                                                    x-text="`${activeDetail?.pengajuan?.jarak_pantai || '-'} meter`">
                                                </dd>
                                            </div>
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt
                                                    class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                    Jarak ke Sungai</dt>
                                                <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left"
                                                    x-text="`${activeDetail?.pengajuan?.jarak_sungai || '-'} meter`">
                                                </dd>
                                            </div>
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt
                                                    class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                    Pasang Surut</dt>
                                                <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left"
                                                    x-text="activeDetail?.detail?.is_pasang_surut || '-'"></dd>
                                            </div>
                                        </dl>
                                    </div>
                                </div>

                                <!-- SECTION 3 & SECTION 4: 2-COLUMN LAYOUT -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 py-1 mt-6">
                                    <!-- SECTION 3: KRITERIA KHUSUS KAWASAN -->
                                    <div>
                                        <h4
                                            class="text-sm font-bold tracking-tight text-textMain-light dark:text-textMain-dark mb-3 text-left">
                                            Kriteria Khusus Kawasan
                                        </h4>

                                        <div class="flex flex-wrap gap-2 py-1 text-left">
                                            <template
                                                x-if="activeDetail?.detail?.is_mangrove && activeDetail?.detail?.is_mangrove !== 'Tidak' && activeDetail?.detail?.is_mangrove !== '-'">
                                                <span
                                                    class="px-3 py-1.5 rounded-md text-xs font-regular bg-teal-light/10 text-teal-light dark:bg-teal-light/20 inline-block">Area
                                                    Mangrove</span>
                                            </template>
                                            <template
                                                x-if="activeDetail?.detail?.is_konservasi && activeDetail?.detail?.is_konservasi !== 'Tidak' && activeDetail?.detail?.is_konservasi !== '-'">
                                                <span
                                                    class="px-3 py-1.5 rounded-md text-xs font-regular bg-teal-light/10 text-teal-light dark:bg-teal-light/20 inline-block">Zona
                                                    Konservasi</span>
                                            </template>
                                            <template
                                                x-if="activeDetail?.detail?.is_hutan_lindung && activeDetail?.detail?.is_hutan_lindung !== 'Tidak' && activeDetail?.detail?.is_hutan_lindung !== '-'">
                                                <span
                                                    class="px-3 py-1.5 rounded-md text-xs font-regular bg-teal-light/10 text-teal-light dark:bg-teal-light/20 inline-block">Hutan
                                                    Lindung</span>
                                            </template>
                                            <template
                                                x-if="activeDetail?.detail?.is_kawasan_budidaya && activeDetail?.detail?.is_kawasan_budidaya !== 'Tidak' && activeDetail?.detail?.is_kawasan_budidaya !== '-'">
                                                <span
                                                    class="px-3 py-1.5 rounded-md text-xs font-regular bg-teal-light/10 text-teal-light dark:bg-teal-light/20 inline-block">Kawasan
                                                    Budidaya</span>
                                            </template>
                                            <template
                                                x-if="activeDetail?.detail?.is_das && activeDetail?.detail?.is_das !== 'Tidak' && activeDetail?.detail?.is_das !== '-'">
                                                <span
                                                    class="px-3 py-1.5 rounded-md text-xs font-regular bg-teal-light/10 text-teal-light dark:bg-teal-light/20 inline-block">Aliran
                                                    Sungai (DAS)</span>
                                            </template>
                                            <template
                                                x-if="(!activeDetail?.detail?.is_mangrove || activeDetail?.detail?.is_mangrove === 'Tidak' || activeDetail?.detail?.is_mangrove === '-') && (!activeDetail?.detail?.is_konservasi || activeDetail?.detail?.is_konservasi === 'Tidak' || activeDetail?.detail?.is_konservasi === '-') && (!activeDetail?.detail?.is_hutan_lindung || activeDetail?.detail?.is_hutan_lindung === 'Tidak' || activeDetail?.detail?.is_hutan_lindung === '-') && (!activeDetail?.detail?.is_kawasan_budidaya || activeDetail?.detail?.is_kawasan_budidaya === 'Tidak' || activeDetail?.detail?.is_kawasan_budidaya === '-') && (!activeDetail?.detail?.is_das || activeDetail?.detail?.is_das === 'Tidak' || activeDetail?.detail?.is_das === '-')">
                                                <span
                                                    class="text-xs text-textMuted-light dark:text-textMuted-dark py-1">-
                                                    Tidak ada kriteria khusus -</span>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- SECTION 4: PENANGGUNG JAWAB USULAN -->
                                    <div>
                                        <h4
                                            class="text-sm font-bold tracking-tight text-textMain-light dark:text-textMain-dark mb-3 text-left">
                                            Penanggung Jawab Usulan
                                        </h4>

                                        <dl class="space-y-2 text-xs sm:text-sm text-left">
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt
                                                    class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                    Nama Pengisi</dt>
                                                <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left"
                                                    x-text="activeDetail?.detail?.nama_pengisi || '-'"></dd>
                                            </div>
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt
                                                    class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                    Jabatan / Posisi</dt>
                                                <dd class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left"
                                                    x-text="activeDetail?.detail?.jabatan_pengisi || '-'"></dd>
                                            </div>
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt
                                                    class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                    No. WhatsApp / HP</dt>
                                                <dd class="flex-1 font-medium font-sans text-textMain-light dark:text-textMain-dark text-left"
                                                    style="font-family: 'Inter', sans-serif;"
                                                    x-text="activeDetail?.detail?.no_hp_pengisi || '-'"></dd>
                                            </div>
                                        </dl>
                                    </div>
                                </div>

                                <!-- SECTION 5 & SECTION 6: 2-COLUMN LAYOUT -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 py-1 mt-6">
                                    <!-- SECTION 5: LAMPIRAN DOKUMEN RESMI -->
                                    <div>
                                        <h4
                                            class="text-sm font-bold tracking-tight text-textMain-light dark:text-textMain-dark mb-3 text-left">
                                            Lampiran Dokumen Resmi
                                        </h4>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <!-- Proposal -->
                                            <template x-if="activeDetail?.dokumen">
                                                <a :href="activeDetail?.dokumen || '#'" target="_blank"
                                                    class="flex items-center justify-between p-4 rounded-xl bg-teal-light dark:bg-teal-dark hover:bg-teal-light/90 dark:hover:bg-teal-dark/90 text-white dark:text-white shadow-xs hover:shadow-md transition-all duration-200 cursor-pointer group font-sans"
                                                    style="font-family: 'Inter', sans-serif;">
                                                    <div class="flex items-center min-w-0 flex-1">
                                                        <div
                                                            class="shrink-0 flex items-center justify-center w-10 h-10 rounded-xl bg-white/15 dark:bg-white/20 text-white dark:text-white group-hover:scale-105 transition-transform mr-4">
                                                            <i class="fa-solid fa-file-pdf text-sm"></i>
                                                        </div>
                                                        <div class="flex-1 min-w-0 text-left ml-3">
                                                            <div
                                                                class="text-xs font-bold text-white dark:text-white truncate">
                                                                Proposal Usulan</div>
                                                            <div class="text-[11px] font-normal text-white/90 dark:text-white/90 truncate mt-0.5"
                                                                x-text="activeDetail?.created_at || 'PDF'"></div>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="shrink-0 ml-3 text-white/80 dark:text-white/80 group-hover:text-white dark:group-hover:text-white transition-colors">
                                                        <i
                                                            class="fa-solid fa-arrow-up-right-from-square text-xs group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"></i>
                                                    </div>
                                                </a>
                                            </template>

                                            <!-- BA Aktivasi -->
                                            <template x-if="activeDetail?.baAktivasi?.dokumen_ba">
                                                <a :href="activeDetail?.baAktivasi?.dokumen_ba ? (activeDetail.baAktivasi.dokumen_ba.startsWith('http') ? activeDetail.baAktivasi.dokumen_ba : '/storage/' + activeDetail.baAktivasi.dokumen_ba) : '#'"
                                                    target="_blank"
                                                    class="flex items-center justify-between p-4 rounded-xl bg-teal-light dark:bg-teal-dark hover:bg-teal-light/90 dark:hover:bg-teal-dark/90 text-white dark:text-white shadow-xs hover:shadow-md transition-all duration-200 cursor-pointer group font-sans"
                                                    style="font-family: 'Inter', sans-serif;">
                                                    <div class="flex items-center min-w-0 flex-1">
                                                        <div
                                                            class="shrink-0 flex items-center justify-center w-10 h-10 rounded-xl bg-white/15 dark:bg-white/20 text-white dark:text-white group-hover:scale-105 transition-transform mr-4">
                                                            <i class="fa-solid fa-file-lines text-sm"></i>
                                                        </div>
                                                        <div class="flex-1 min-w-0 text-left ml-3">
                                                            <div
                                                                class="text-xs font-bold text-white dark:text-white truncate">
                                                                BA Aktivasi</div>
                                                            <div class="text-[11px] font-normal text-white/90 dark:text-white/90 truncate mt-0.5"
                                                                x-text="activeDetail?.baAktivasi?.status || 'Verifikasi'">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="shrink-0 ml-3 text-white/80 dark:text-white/80 group-hover:text-white dark:group-hover:text-white transition-colors">
                                                        <i
                                                            class="fa-solid fa-arrow-up-right-from-square text-xs group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"></i>
                                                    </div>
                                                </a>
                                            </template>

                                            <!-- BA Calon -->
                                            <template x-if="activeDetail?.baCalon?.dokumen_ba">
                                                <a :href="activeDetail?.baCalon?.dokumen_ba ? (activeDetail.baCalon.dokumen_ba.startsWith('http') ? activeDetail.baCalon.dokumen_ba : '/storage/' + activeDetail.baCalon.dokumen_ba) : '#'"
                                                    target="_blank"
                                                    class="flex items-center justify-between p-4 rounded-xl bg-teal-light dark:bg-teal-dark hover:bg-teal-light/90 dark:hover:bg-teal-dark/90 text-white dark:text-white shadow-xs hover:shadow-md transition-all duration-200 cursor-pointer group font-sans"
                                                    style="font-family: 'Inter', sans-serif;">
                                                    <div class="flex items-center min-w-0 flex-1">
                                                        <div
                                                            class="shrink-0 flex items-center justify-center w-10 h-10 rounded-xl bg-white/15 dark:bg-white/20 text-white dark:text-white group-hover:scale-105 transition-transform mr-4">
                                                            <i class="fa-solid fa-file-signature text-sm"></i>
                                                        </div>
                                                        <div class="flex-1 min-w-0 text-left ml-3">
                                                            <div
                                                                class="text-xs font-bold text-white dark:text-white truncate">
                                                                BA Calon Lokasi</div>
                                                            <div class="text-[11px] font-normal text-white/90 dark:text-white/90 truncate mt-0.5"
                                                                x-text="activeDetail?.baCalon?.status || 'Survei'">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="shrink-0 ml-3 text-white/80 dark:text-white/80 group-hover:text-white dark:group-hover:text-white transition-colors">
                                                        <i
                                                            class="fa-solid fa-arrow-up-right-from-square text-xs group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"></i>
                                                    </div>
                                                </a>
                                            </template>

                                            <!-- SK Penetapan -->
                                            <template x-if="activeDetail?.penetapan?.dokumen_sk">
                                                <a :href="activeDetail?.penetapan?.dokumen_sk ? (activeDetail.penetapan.dokumen_sk.startsWith('http') ? activeDetail.penetapan.dokumen_sk : '/storage/' + activeDetail.penetapan.dokumen_sk) : '#'"
                                                    target="_blank"
                                                    class="flex items-center justify-between p-4 rounded-xl bg-teal-light dark:bg-teal-dark hover:bg-teal-light/90 dark:hover:bg-teal-dark/90 text-white dark:text-white shadow-xs hover:shadow-md transition-all duration-200 cursor-pointer group font-sans"
                                                    style="font-family: 'Inter', sans-serif;">
                                                    <div class="flex items-center min-w-0 flex-1">
                                                        <div
                                                            class="shrink-0 flex items-center justify-center w-10 h-10 rounded-xl bg-white/15 dark:bg-white/20 text-white dark:text-white group-hover:scale-105 transition-transform mr-4">
                                                            <i class="fa-solid fa-award text-sm"></i>
                                                        </div>
                                                        <div class="flex-1 min-w-0 text-left ml-3">
                                                            <div
                                                                class="text-xs font-bold text-white dark:text-white truncate">
                                                                SK Penetapan</div>
                                                            <div class="text-[11px] font-normal text-white/90 dark:text-white/90 truncate mt-0.5"
                                                                x-text="activeDetail?.penetapan?.no_sk || 'Penetapan'">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="shrink-0 ml-3 text-white/80 dark:text-white/80 group-hover:text-white dark:group-hover:text-white transition-colors">
                                                        <i
                                                            class="fa-solid fa-arrow-up-right-from-square text-xs group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"></i>
                                                    </div>
                                                </a>
                                            </template>
                                        </div>
                                        <template
                                            x-if="!activeDetail?.dokumen && !activeDetail?.baAktivasi?.dokumen_ba && !activeDetail?.baCalon?.dokumen_ba && !activeDetail?.penetapan?.dokumen_sk">
                                            <div
                                                class="text-xs text-textMuted-light dark:text-textMuted-dark italic py-8 bg-gray-50/60 dark:bg-gray-800/20 rounded-xl border border-dashed border-gray-200 dark:border-gray-700 text-center flex flex-col items-center justify-center gap-2">
                                                <i
                                                    class="fa-regular fa-folder-closed text-2xl text-gray-300 dark:text-gray-600"></i>
                                                <span>Belum ada berkas lampiran yang diunggah.</span>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- SECTION 6: HISTORY / RIWAYAT VERIFIKASI -->
                                    <div>
                                        <h4
                                            class="text-sm font-bold tracking-tight text-textMain-light dark:text-textMain-dark mb-3 text-left">
                                            History / Riwayat Verifikasi
                                        </h4>

                                        <dl class="space-y-2.5 text-xs">
                                            <!-- Item 1: Pengajuan Usulan -->
                                            <div class="flex items-start gap-4 py-1 text-left">
                                                <dt
                                                    class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                    Pengajuan Usulan</dt>
                                                <dd
                                                    class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left">
                                                    <div class="flex items-center gap-2 flex-wrap">
                                                        <span x-text="activeDetail?.created_at || '-'"></span>
                                                        <span
                                                            class="px-2 py-0.5 rounded-md bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300 text-[10px] font-semibold">Menunggu
                                                            Review</span>
                                                    </div>
                                                    <div
                                                        class="text-[11px] font-normal text-textMuted-light dark:text-textMuted-dark mt-1">
                                                        Oleh: <span
                                                            class="font-medium text-textMain-light dark:text-textMain-dark"
                                                            x-text="activeDetail?.detail?.nama_pengisi || 'Pengisi Usulan'"></span>
                                                    </div>
                                                </dd>
                                            </div>

                                            <!-- Item 2: Verifikasi Administrasi -->
                                            <template x-if="activeDetail?.verifAdmin">
                                                <div class="flex items-start gap-4 py-1 text-left">
                                                    <dt
                                                        class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                        Verifikasi Administrasi</dt>
                                                    <dd
                                                        class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left">
                                                        <div class="flex items-center gap-2 flex-wrap">
                                                            <span
                                                                x-text="activeDetail?.verifAdmin?.updated_at || activeDetail?.created_at || '-'"></span>
                                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold"
                                                                :class="activeDetail?.verifAdmin?.status === 'Ditolak' ?
                                                                    'bg-rose-100 text-rose-800 dark:bg-rose-900/50 dark:text-rose-300' :
                                                                    'bg-teal-100 text-teal-800 dark:bg-teal-900/50 dark:text-teal-300'"
                                                                x-text="activeDetail?.verifAdmin?.status"></span>
                                                        </div>
                                                        <template x-if="activeDetail?.verifAdmin?.catatan">
                                                            <div class="text-[11px] font-normal text-textMuted-light dark:text-textMuted-dark mt-1"
                                                                x-text="`Catatan: ${activeDetail.verifAdmin.catatan}`">
                                                            </div>
                                                        </template>
                                                    </dd>
                                                </div>
                                            </template>

                                            <!-- Item 3: Verifikasi Teknis -->
                                            <template x-if="activeDetail?.verifTeknis">
                                                <div class="flex items-start gap-4 py-1 text-left">
                                                    <dt
                                                        class="w-40 shrink-0 font-normal text-textMuted-light dark:text-textMuted-dark text-left">
                                                        Verifikasi Teknis</dt>
                                                    <dd
                                                        class="flex-1 font-medium text-textMain-light dark:text-textMain-dark text-left">
                                                        <div class="flex items-center gap-2 flex-wrap">
                                                            <span
                                                                x-text="activeDetail?.verifTeknis?.updated_at || activeDetail?.created_at || '-'"></span>
                                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold"
                                                                :class="activeDetail?.verifTeknis?.status === 'Ditolak' ?
                                                                    'bg-rose-100 text-rose-800 dark:bg-rose-900/50 dark:text-rose-300' :
                                                                    'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300'"
                                                                x-text="activeDetail?.verifTeknis?.status"></span>
                                                            <template x-if="activeDetail?.verifTeknis?.skor">
                                                                <span
                                                                    class="px-2 py-0.5 rounded-md bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300 text-[10px] font-semibold"
                                                                    x-text="`Skor: ${activeDetail.verifTeknis.skor}`"></span>
                                                            </template>
                                                        </div>
                                                        <template x-if="activeDetail?.verifTeknis?.catatan">
                                                            <div class="text-[11px] font-normal text-textMuted-light dark:text-textMuted-dark mt-1"
                                                                x-text="`Catatan: ${activeDetail.verifTeknis.catatan}`">
                                                            </div>
                                                        </template>
                                                    </dd>
                                                </div>
                                            </template>
                                        </dl>
                                    </div>
                                </div>

                            </div>
                        </template>
                        <template x-if="!activeDetail || !activeDetail.detail">
                            <div
                                class="flex flex-col items-center justify-center py-20 text-textMuted-light dark:text-textMuted-dark gap-3">
                                <div
                                    class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-2xl text-gray-400 dark:text-gray-500">
                                    <i class="fa-solid fa-circle-info"></i>
                                </div>
                                <p class="text-base font-semibold text-textMain-light dark:text-textMain-dark">Data
                                    detail usulan tidak tersedia.</p>
                                <p class="text-xs text-textMuted-light dark:text-textMuted-dark">Silakan pilih data
                                    lain atau muat ulang halaman.</p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

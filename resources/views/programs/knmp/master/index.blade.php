@extends('layouts.app')

@section('title', 'KNMP - Manajemen Calon Lokasi (Master)')

@section('content')
@php
    $currentStage = request()->query('stage', 'pengajuan');
    
    $stages = [
        'pengajuan' => ['label' => 'Pengajuan Proposal', 'icon' => 'fa-file-lines'],
        'verif-admin' => ['label' => 'Verif Administrasi', 'icon' => 'fa-clipboard-check'],
        'ba-aktivasi' => ['label' => 'BA Aktivasi', 'icon' => 'fa-signature'],
        'verif-teknis' => ['label' => 'Verif Teknis Lapangan', 'icon' => 'fa-map-location-dot'],
        'ba-calon' => ['label' => 'BA Calon', 'icon' => 'fa-file-contract'],
        'penetapan' => ['label' => 'Penetapan Calon (SK)', 'icon' => 'fa-award'],
    ];
@endphp

<div x-data="calonLokasiManager('{{ $currentStage }}')" x-init="initData()" class="flex flex-col gap-4">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-3xl font-extrabold tracking-tight">Manajemen <span class="text-info dark:text-blue-400">Calon Lokasi</span></h2>
            <p class="text-textMuted-light dark:text-textMuted-dark text-sm mt-1">Siklus 1: Dari pengajuan usulan baru hingga ditetapkan menjadi Lokasi Definitif.</p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap items-center gap-3">
            @if($currentStage === 'pengajuan')
            <button @click="openUploadModal()" class="bg-gradient-to-r from-success to-emerald-600 hover:from-emerald-600 hover:to-success text-white rounded-xl px-4 py-2 text-sm font-bold shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Input Pengajuan Baru
            </button>
            @endif
        </div>
    </div>

    <!-- Stepper / Tabs -->
    <div class="bg-white dark:bg-bgSurface-dark rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 p-2 overflow-x-auto hide-scrollbar">
        <div class="flex min-w-max">
            @foreach($stages as $key => $data)
            <a href="?stage={{ $key }}" class="flex items-center group relative px-4 py-3">
                <div class="flex items-center gap-3 relative z-10">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs transition-colors
                        {{ $currentStage === $key ? 'bg-info text-white shadow-md' : 'bg-gray-100 dark:bg-gray-800 text-gray-400 group-hover:bg-info/20 group-hover:text-info' }}">
                        <i class="fa-solid {{ $data['icon'] }}"></i>
                    </div>
                    <span class="font-bold text-sm transition-colors {{ $currentStage === $key ? 'text-info' : 'text-textMuted-light dark:text-textMuted-dark group-hover:text-info' }}">
                        {{ $data['label'] }}
                    </span>
                </div>
                @if(!$loop->last)
                <div class="w-8 h-px bg-gray-300 dark:bg-gray-700 mx-3"></div>
                @endif
                
                @if($currentStage === $key)
                <div class="absolute inset-0 bg-info/5 dark:bg-blue-500/10 rounded-xl"></div>
                @endif
            </a>
            @endforeach
        </div>
    </div>

    <!-- Main Data Table -->
    <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-200 dark:border-gray-800 rounded-3xl shadow-sm overflow-hidden flex flex-col mt-2">
        <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50 dark:bg-gray-800/20">
            <div class="flex items-center gap-2 text-sm text-textMuted-light dark:text-textMuted-dark">
                <span>Tampilkan</span>
                <select class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg px-2 py-1.5 focus:outline-none focus:border-info text-textMain-light dark:text-textMain-dark font-medium shadow-sm">
                    <option>10</option>
                </select>
                <span>entri</span>
            </div>

            <div class="relative w-full sm:w-64">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" placeholder="Cari data lokasi..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:border-info outline-none transition-all shadow-sm">
            </div>
        </div>

        <div class="overflow-x-auto min-h-[300px]">
            
            <!-- TABLE 1: PENGAJUAN PROPOSAL -->
            <table x-show="currentStage === 'pengajuan'" class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-xs uppercase tracking-wider font-semibold border-b border-gray-200 dark:border-gray-800">
                    <tr>
                        <th class="px-6 py-4 w-10"><input type="checkbox" class="rounded border-gray-300 text-info focus:ring-info"></th>
                        <th class="px-6 py-4">Usulan Lokasi</th>
                        <th class="px-6 py-4">Pengusul (Dinas/Pemda)</th>
                        <th class="px-6 py-4">Status Pengajuan</th>
                        <th class="px-6 py-4 text-center">Aksi & Riwayat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
                    <template x-for="item in proposals" :key="item.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors group cursor-pointer" @click="openPreviewModal(item)">
                            <td class="px-6 py-4"><input type="checkbox" class="rounded border-gray-300 text-info" @click.stop></td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-textMain-light dark:text-textMain-dark" x-text="item.desa"></div>
                                <div class="text-xs text-textMuted-light dark:text-textMuted-dark mt-0.5" x-text="`${item.kecamatan}, ${item.kabupaten}`"></div>
                            </td>
                            <td class="px-6 py-4 text-textMuted-light dark:text-textMuted-dark" x-text="item.pengusul"></td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-md text-[0.7rem] font-bold"
                                      :class="{
                                          'bg-warning/10 text-warning dark:text-amber-400': item.status === 'Menunggu Review',
                                          'bg-success/10 text-success': item.status === 'Diverifikasi',
                                          'bg-danger/10 text-danger': item.status === 'Ditolak'
                                      }" x-text="item.status">
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button class="w-8 h-8 rounded-lg bg-info/10 text-info hover:bg-info hover:text-white transition-colors flex items-center justify-center mx-auto" title="Preview Dokumen" @click.stop="openPreviewModal(item)">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="proposals.length === 0">
                        <td colspan="5" class="px-6 py-8 text-center text-textMuted-light dark:text-textMuted-dark italic">
                            Belum ada data pengajuan.
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- TABLE 2: VERIFIKASI ADMINISTRASI -->
            <table x-show="currentStage === 'verif-admin'" style="display: none;" class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-xs uppercase tracking-wider font-semibold border-b border-gray-200 dark:border-gray-800">
                    <tr>
                        <th class="px-6 py-4">Lokasi Usulan (Lolos Tahap 1)</th>
                        <th class="px-6 py-4 text-center">Kelengkapan Dokumen</th>
                        <th class="px-6 py-4">Status Administrasi</th>
                        <th class="px-6 py-4 text-center">Ceklis & Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
                    <template x-for="item in verifList" :key="item.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-textMain-light dark:text-textMain-dark" x-text="item.desa"></div>
                                <div class="text-xs text-textMuted-light dark:text-textMuted-dark mt-0.5" x-text="item.kabupaten"></div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-1 max-w-[150px] mx-auto">
                                    <div class="bg-info h-2 rounded-full transition-all duration-300" :style="`width: ${(item.checkedDocs / item.totalDocs) * 100}%`"></div>
                                </div>
                                <span class="text-[0.65rem] font-bold text-textMuted-light" x-text="`${item.checkedDocs} / ${item.totalDocs} Dokumen Sesuai`"></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-md text-[0.7rem] font-bold"
                                      :class="{
                                          'bg-warning/10 text-warning dark:text-amber-400': item.status === 'Pemeriksaan',
                                          'bg-success/10 text-success': item.status === 'Selesai (Lanjut BA)'
                                      }" x-text="item.status">
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button class="px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white border border-indigo-200 hover:border-indigo-600 transition-colors text-xs font-bold flex items-center gap-2 mx-auto disabled:opacity-50 disabled:cursor-not-allowed" 
                                        :disabled="item.status === 'Selesai (Lanjut BA)'"
                                        @click="openChecklistModal(item)">
                                    <i class="fa-solid fa-list-check"></i> <span x-text="item.status === 'Selesai (Lanjut BA)' ? 'Terkunci' : 'Form Ceklis'"></span>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>

            <!-- TABLE 3: BERITA ACARA AKTIVASI -->
            <table x-show="currentStage === 'ba-aktivasi'" style="display: none;" class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-xs uppercase tracking-wider font-semibold border-b border-gray-200 dark:border-gray-800">
                    <tr>
                        <th class="px-6 py-4">Lokasi Usulan</th>
                        <th class="px-6 py-4">Nomor Berita Acara</th>
                        <th class="px-6 py-4">Status BA</th>
                        <th class="px-6 py-4 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
                    <template x-for="item in baList" :key="item.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-textMain-light dark:text-textMain-dark" x-text="item.desa"></div>
                                <div class="text-xs text-textMuted-light dark:text-textMuted-dark mt-0.5" x-text="item.kabupaten"></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-mono text-xs text-textMain-light dark:text-textMain-dark" x-text="item.noBa || 'Belum di-generate'"></div>
                                <div class="text-[0.65rem] text-textMuted-light mt-0.5" x-text="item.tglBa ? `Tgl: ${item.tglBa}` : '-'"></div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-md text-[0.7rem] font-bold"
                                      :class="{
                                          'bg-gray-100 dark:bg-gray-800 text-gray-500': item.status === 'Menunggu Draft',
                                          'bg-warning/10 text-warning dark:text-amber-400': item.status === 'Menunggu TTD Pemda',
                                          'bg-success/10 text-success': item.status === 'BA Terbit (Selesai)'
                                      }">
                                    <i class="fa-solid" :class="{
                                        'fa-hourglass-start': item.status === 'Menunggu Draft',
                                        'fa-pen-nib': item.status === 'Menunggu TTD Pemda',
                                        'fa-check-double': item.status === 'BA Terbit (Selesai)'
                                    }"></i>
                                    <span x-text="item.status" class="ml-1"></span>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <!-- Tombol Generate Draft -->
                                <button x-show="item.status === 'Menunggu Draft'" 
                                        @click="generateDraftBA(item)" 
                                        class="px-3 py-1.5 rounded-lg bg-info/10 text-info hover:bg-info hover:text-white transition-colors text-xs font-bold flex items-center gap-2 mx-auto">
                                    <i class="fa-solid fa-file-export"></i> Buat Draft BA
                                </button>
                                
                                <!-- Tombol Upload BA Tertanda Tangan -->
                                <button x-show="item.status === 'Menunggu TTD Pemda'" 
                                        @click="openUploadBAModal(item)" 
                                        class="px-3 py-1.5 rounded-lg bg-warning/10 text-warning hover:bg-warning hover:text-white transition-colors text-xs font-bold flex items-center gap-2 mx-auto">
                                    <i class="fa-solid fa-cloud-arrow-up"></i> Upload BA (TTD)
                                </button>
                                
                                <!-- Tombol Lihat BA (Selesai) -->
                                <button x-show="item.status === 'BA Terbit (Selesai)'" 
                                        class="px-3 py-1.5 rounded-lg bg-success/10 text-success hover:bg-success hover:text-white transition-colors text-xs font-bold flex items-center gap-2 mx-auto">
                                    <i class="fa-solid fa-file-pdf"></i> Lihat Dokumen
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>

        </div>
    </div>

    <!-- SEMUA MODALS DI BAWAH SINI -->

    <!-- MODAL: Input Pengajuan Baru (Upload) -->
    <div x-show="showUploadModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showUploadModal" x-transition.opacity class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" @click="showUploadModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showUploadModal" x-transition.scale.origin.bottom class="relative z-10 inline-block align-bottom bg-bgSurface-light dark:bg-bgSurface-dark rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full border border-gray-200 dark:border-gray-800">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-textMain-light dark:text-textMain-dark" id="modal-title">Upload Pengajuan Baru</h3>
                    <button @click="showUploadModal = false" class="text-gray-400 hover:text-danger transition-colors">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
                <div class="px-6 py-5">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-textMain-light dark:text-textMain-dark mb-1">Nama Desa Calon Lokasi</label>
                            <input type="text" x-model="newProposal.desa" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-2 focus:ring-info outline-none" placeholder="Contoh: Desa Bahari Maju">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-textMain-light dark:text-textMain-dark mb-1">Kecamatan</label>
                                <input type="text" x-model="newProposal.kecamatan" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-2 focus:ring-info outline-none" placeholder="Nama Kecamatan">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-textMain-light dark:text-textMain-dark mb-1">Kabupaten</label>
                                <input type="text" x-model="newProposal.kabupaten" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-2 focus:ring-info outline-none" placeholder="Nama Kabupaten">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-textMain-light dark:text-textMain-dark mb-1">Dokumen Surat / Proposal (PDF)</label>
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800/50 hover:bg-info/5 hover:border-info transition-colors cursor-pointer">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2"></i>
                                    <p class="mb-1 text-sm text-textMuted-light dark:text-textMuted-dark"><span class="font-bold text-info">Klik untuk upload</span> atau drag and drop</p>
                                    <p class="text-xs text-gray-500">PDF (Maks. 5MB)</p>
                                </div>
                                <input type="file" class="hidden" accept=".pdf" @change="newProposal.fileName = $event.target.files[0].name" />
                            </label>
                            <p x-show="newProposal.fileName" class="text-sm text-success mt-2 font-semibold" x-text="`File terpilih: ${newProposal.fileName}`"></p>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-800 flex justify-end gap-3">
                    <button @click="showUploadModal = false" class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-textMain-light dark:text-textMain-dark rounded-xl text-sm font-bold shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Batal</button>
                    <button @click="submitProposal()" class="px-4 py-2 bg-info hover:bg-blue-600 text-white rounded-xl text-sm font-bold shadow-md transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Pengajuan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL 1: Preview PDF Pengajuan -->
    <div x-show="showPreviewModal" style="display: none;" class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-0 sm:p-4">
            <div x-show="showPreviewModal" x-transition.opacity class="fixed inset-0 bg-gray-900/90 backdrop-blur-sm transition-opacity" @click="showPreviewModal = false"></div>
            
            <div x-show="showPreviewModal" x-transition.scale.origin.center class="relative z-10 bg-bgSurface-light dark:bg-bgSurface-dark sm:rounded-3xl shadow-2xl flex flex-col md:flex-row w-full h-full sm:h-[90vh] sm:max-w-6xl border border-gray-200 dark:border-gray-800 overflow-hidden">
                <!-- PDF Viewer -->
                <div class="w-full md:w-3/5 bg-gray-200 dark:bg-gray-900 flex flex-col border-r border-gray-200 dark:border-gray-800">
                    <div class="px-4 py-3 bg-gray-800 text-white flex justify-between items-center shrink-0">
                        <div class="font-bold text-sm flex items-center gap-2"><i class="fa-solid fa-file-pdf text-danger"></i> Dokumen Proposal</div>
                    </div>
                    <div class="flex-1 overflow-auto p-4 md:p-8 flex items-start justify-center">
                        <div class="bg-white w-full max-w-2xl h-[800px] shadow-lg flex flex-col p-8 border border-gray-300">
                            <div class="text-center border-b-2 border-black pb-4 mb-6">
                                <h1 class="text-xl font-bold uppercase">PROPOSAL PEMBANGUNAN KNMP</h1>
                                <h2 class="text-lg font-bold uppercase" x-text="activeProposal?.desa"></h2>
                            </div>
                            <div class="space-y-4 text-justify font-serif">
                                <p>Yang bertanda tangan di bawah ini, selaku pimpinan daerah mengajukan usulan penetapan lokasi...</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Detail Kanan -->
                <div class="w-full md:w-2/5 flex flex-col bg-white dark:bg-bgSurface-dark">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800 flex justify-between items-center shrink-0">
                        <h3 class="text-lg font-bold text-textMain-light dark:text-textMain-dark">Detail Verifikasi</h3>
                        <button @click="showPreviewModal = false" class="text-gray-400 hover:text-danger transition-colors bg-gray-100 dark:bg-gray-800 w-8 h-8 rounded-full flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
                    </div>

                    <div class="flex-1 overflow-y-auto p-6">
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-5 mb-6 border border-gray-100 dark:border-gray-700">
                            <h4 class="font-bold text-lg mb-1" x-text="activeProposal?.desa"></h4>
                            <p class="text-sm text-textMuted-light mb-4" x-text="`${activeProposal?.kecamatan}, ${activeProposal?.kabupaten}`"></p>
                            <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-warning/10 text-warning" x-text="activeProposal?.status"></span>
                        </div>

                        <h4 class="font-bold text-sm uppercase text-textMuted-light dark:text-textMuted-dark mb-4 tracking-wider flex items-center gap-2"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Tindakan</h4>
                        <div class="relative border-l-2 border-gray-200 dark:border-gray-700 ml-3 space-y-6">
                            <template x-for="(hist, index) in activeProposal?.history" :key="index">
                                <div class="relative pl-6">
                                    <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-white dark:bg-gray-900 border-2" :class="hist.type === 'upload' ? 'border-info' : 'border-success'"></div>
                                    <div class="text-xs text-textMuted-light mb-1" x-text="hist.time"></div>
                                    <div class="font-bold text-sm text-textMain-light dark:text-textMain-dark mb-1" x-text="hist.user"></div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400" x-text="hist.action"></div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="p-6 border-t border-gray-200 dark:border-gray-800 shrink-0 bg-white dark:bg-bgSurface-dark">
                        <template x-if="activeProposal?.status === 'Menunggu Review'">
                            <div class="flex gap-3">
                                <button @click="verifyProposal('Ditolak', '')" class="flex-1 px-4 py-2.5 border border-danger text-danger hover:bg-danger hover:text-white rounded-xl text-sm font-bold shadow-sm transition-colors flex items-center justify-center gap-2"><i class="fa-solid fa-xmark"></i> Tolak</button>
                                <button @click="verifyProposal('Diverifikasi', '')" class="flex-1 px-4 py-2.5 bg-success hover:bg-green-600 text-white rounded-xl text-sm font-bold shadow-md transition-colors flex items-center justify-center gap-2"><i class="fa-solid fa-check-double"></i> Verifikasi</button>
                            </div>
                        </template>
                        <template x-if="activeProposal?.status !== 'Menunggu Review'">
                            <div class="bg-gray-50 dark:bg-gray-800/50 p-4 rounded-xl text-center border border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-bold text-textMuted-light dark:text-textMuted-dark"><i class="fa-solid fa-lock text-gray-400"></i> Pengajuan sudah diproses.</p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL 2: Form Ceklis Verifikasi Administrasi -->
    <div x-show="showChecklistModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showChecklistModal" x-transition.opacity class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" @click="showChecklistModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div x-show="showChecklistModal" x-transition.scale.origin.bottom class="relative z-10 inline-block align-bottom bg-bgSurface-light dark:bg-bgSurface-dark rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl w-full border border-gray-200 dark:border-gray-800">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/20">
                    <div>
                        <h3 class="text-xl font-bold text-textMain-light dark:text-textMain-dark">Checklist Verifikasi Administrasi</h3>
                        <p class="text-sm text-info font-bold mt-1" x-text="activeVerif?.desa"></p>
                    </div>
                    <button @click="showChecklistModal = false" class="text-gray-400 hover:text-danger transition-colors bg-white dark:bg-gray-800 w-8 h-8 rounded-full flex items-center justify-center border border-gray-200 dark:border-gray-700 shadow-sm"><i class="fa-solid fa-xmark"></i></button>
                </div>
                
                <div class="px-6 py-5 bg-white dark:bg-bgSurface-dark">
                    <p class="text-sm text-textMuted-light dark:text-textMuted-dark mb-4">Pastikan dokumen pendukung yang diunggah oleh daerah sesuai dengan pedoman teknis KNMP.</p>
                    
                    <div class="border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden divide-y divide-gray-100 dark:divide-gray-800">
                        <template x-for="(doc, idx) in activeVerif?.documents" :key="idx">
                            <div class="p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                                <div class="flex items-start gap-3 flex-1">
                                    <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-info flex items-center justify-center shrink-0"><i class="fa-regular fa-file-lines text-lg"></i></div>
                                    <div>
                                        <h4 class="font-bold text-sm text-textMain-light dark:text-textMain-dark" x-text="doc.name"></h4>
                                        <p class="text-xs text-textMuted-light mt-0.5" x-text="doc.desc"></p>
                                        <button class="text-[0.7rem] font-bold text-info hover:underline mt-1 flex items-center gap-1"><i class="fa-solid fa-paperclip"></i> Lihat File</button>
                                    </div>
                                </div>
                                <div class="shrink-0 flex items-center">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer" x-model="doc.isValid" @change="updateChecklistProgress()">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-success"></div>
                                        <span class="ml-3 text-sm font-bold" :class="doc.isValid ? 'text-success' : 'text-gray-400'" x-text="doc.isValid ? 'Sesuai' : 'Belum Sesuai'"></span>
                                    </label>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full border-4 flex items-center justify-center font-bold text-xs"
                             :class="activeVerif?.checkedDocs === activeVerif?.totalDocs ? 'border-success text-success bg-success/10' : 'border-warning text-warning bg-warning/10'"
                             x-text="`${activeVerif?.checkedDocs}/${activeVerif?.totalDocs}`">
                        </div>
                        <div class="text-sm">
                            <div class="font-bold text-textMain-light dark:text-textMain-dark">Progres Verifikasi</div>
                            <div class="text-xs text-success font-bold" x-show="activeVerif?.checkedDocs === activeVerif?.totalDocs">Dokumen lengkap, siap diterbitkan BA!</div>
                        </div>
                    </div>

                    <button @click="terbitkanBA()" class="px-5 py-2.5 rounded-xl text-sm font-bold shadow-md transition-all flex items-center gap-2"
                            :class="activeVerif?.checkedDocs === activeVerif?.totalDocs ? 'bg-indigo-600 hover:bg-indigo-700 text-white' : 'bg-gray-300 dark:bg-gray-700 text-gray-500 cursor-not-allowed'"
                            :disabled="activeVerif?.checkedDocs !== activeVerif?.totalDocs">
                        <i class="fa-solid fa-file-signature"></i> Terbitkan BA Aktivasi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL 3: Upload BA Tertanda Tangan -->
    <div x-show="showUploadBAModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showUploadBAModal" x-transition.opacity class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" @click="showUploadBAModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showUploadBAModal" x-transition.scale.origin.bottom class="relative z-10 inline-block align-bottom bg-bgSurface-light dark:bg-bgSurface-dark rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full border border-gray-200 dark:border-gray-800">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-textMain-light dark:text-textMain-dark">Upload Dokumen BA</h3>
                    <button @click="showUploadBAModal = false" class="text-gray-400 hover:text-danger transition-colors"><i class="fa-solid fa-xmark text-xl"></i></button>
                </div>
                <div class="px-6 py-5">
                    <div class="space-y-4">
                        <p class="text-sm text-textMuted-light">Silakan unggah dokumen Berita Acara Aktivasi (<span class="font-bold text-info" x-text="activeBa?.noBa"></span>) yang telah ditandatangani oleh Pemda setempat.</p>
                        
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800/50 hover:bg-info/5 hover:border-info transition-colors cursor-pointer">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2"></i>
                                <p class="mb-1 text-sm text-textMuted-light dark:text-textMuted-dark"><span class="font-bold text-info">Pilih file PDF BA</span></p>
                            </div>
                            <input type="file" class="hidden" accept=".pdf" @change="uploadBaFile = $event.target.files[0].name" />
                        </label>
                        <p x-show="uploadBaFile" class="text-sm text-success mt-2 font-semibold text-center" x-text="`File: ${uploadBaFile}`"></p>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-800 flex justify-end gap-3">
                    <button @click="showUploadBAModal = false" class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-textMain-light rounded-xl text-sm font-bold transition-colors">Batal</button>
                    <button @click="submitUploadBA()" class="px-4 py-2 bg-success hover:bg-green-600 text-white rounded-xl text-sm font-bold shadow-md transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-check"></i> Selesaikan BA
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('calonLokasiManager', (initialStage) => ({
            currentStage: initialStage,
            
            // Pengajuan
            proposals: [], showUploadModal: false, showPreviewModal: false, activeProposal: null, newProposal: { desa: '', kecamatan: '', kabupaten: '', fileName: '' },

            // Verif Admin
            verifList: [], showChecklistModal: false, activeVerif: null,

            // BA Aktivasi
            baList: [], showUploadBAModal: false, activeBa: null, uploadBaFile: '',

            initData() {
                // Table 1 Mock Data (Pengajuan)
                this.proposals = [
                    { id: 1, desa: 'Desa Pesisir Indah', kecamatan: 'Kecamatan Muara', kabupaten: 'Kabupaten Bahari', pengusul: 'Dinas Kelautan Prov. X', status: 'Menunggu Review', history: [] }
                ];

                // Table 2 Mock Data (Verif Administrasi)
                this.verifList = [
                    {
                        id: 101, desa: 'Kampung Nelayan Sejahtera', kabupaten: 'Kabupaten Samudera', status: 'Pemeriksaan', totalDocs: 4, checkedDocs: 2,
                        documents: [
                            { name: 'Surat Keputusan (SK) Kepala Daerah', desc: 'SK penetapan lokasi.', isValid: true },
                            { name: 'Bukti Status Lahan', desc: 'Surat keterangan lahan tidak sengketa.', isValid: true },
                            { name: 'Rencana Anggaran Biaya', desc: 'Estimasi awal biaya.', isValid: false },
                            { name: 'Surat Kesanggupan', desc: 'Komitmen Pemda.', isValid: false }
                        ]
                    }
                ];

                // Table 3 Mock Data (BA Aktivasi)
                // Ini merepresentasikan lokasi yang sudah lolos 'Verif Administrasi'
                this.baList = [
                    { id: 201, desa: 'Desa Bahari Makmur', kabupaten: 'Kabupaten Pesisir Barat', noBa: '', tglBa: '', status: 'Menunggu Draft' },
                    { id: 202, desa: 'Kampung Maju Bersama', kabupaten: 'Kabupaten Maritim', noBa: 'BA.045/KNMP/2026', tglBa: '04/06/2026', status: 'Menunggu TTD Pemda' },
                    { id: 203, desa: 'Desa Nelayan 1', kabupaten: 'Kabupaten Kepulauan', noBa: 'BA.021/KNMP/2026', tglBa: '01/06/2026', status: 'BA Terbit (Selesai)' },
                ];
            },

            // --- Fungsi Tabel Pengajuan ---
            openUploadModal() { this.newProposal = { desa: '', kecamatan: '', kabupaten: '', fileName: '' }; this.showUploadModal = true; },
            submitProposal() {
                if(!this.newProposal.desa || !this.newProposal.fileName) return alert('Lengkapi data!');
                this.proposals.unshift({ id: Date.now(), desa: this.newProposal.desa, kecamatan: this.newProposal.kecamatan, kabupaten: this.newProposal.kabupaten, pengusul: 'Pengguna Daerah', status: 'Menunggu Review', history: [] });
                this.showUploadModal = false;
            },
            openPreviewModal(item) { this.activeProposal = item; this.showPreviewModal = true; },
            verifyProposal(newStatus, noteStr) {
                window.dispatchEvent(new CustomEvent('trigger-confirm', {
                    detail: {
                        title: 'Konfirmasi Verifikasi', message: `Yakin berikan status: ${newStatus}?`, type: newStatus === 'Ditolak' ? 'danger' : 'success', confirmText: `Ya`,
                        onConfirm: () => {
                            this.activeProposal.status = newStatus; this.activeProposal = { ...this.activeProposal };
                            const idx = this.proposals.findIndex(p => p.id === this.activeProposal.id);
                            if(idx !== -1) this.proposals[idx] = this.activeProposal;
                        }
                    }
                }));
            },

            // --- Fungsi Tabel Verifikasi Administrasi ---
            openChecklistModal(item) { this.activeVerif = JSON.parse(JSON.stringify(item)); this.showChecklistModal = true; },
            updateChecklistProgress() { this.activeVerif.checkedDocs = this.activeVerif.documents.filter(d => d.isValid).length; },
            terbitkanBA() {
                if (this.activeVerif.checkedDocs !== this.activeVerif.totalDocs) return;
                window.dispatchEvent(new CustomEvent('trigger-confirm', {
                    detail: {
                        title: 'Terbitkan BA Aktivasi', message: 'Lanjutkan ke tahap BA Aktivasi?', type: 'info', confirmText: 'Terbitkan BA',
                        onConfirm: () => {
                            this.activeVerif.status = 'Selesai (Lanjut BA)';
                            const idx = this.verifList.findIndex(p => p.id === this.activeVerif.id);
                            if(idx !== -1) this.verifList[idx] = this.activeVerif;
                            
                            // Tambahkan otomatis ke tabel BA Aktivasi
                            this.baList.unshift({
                                id: Date.now(), desa: this.activeVerif.desa, kabupaten: this.activeVerif.kabupaten, noBa: '', tglBa: '', status: 'Menunggu Draft'
                            });
                            this.showChecklistModal = false;
                        }
                    }
                }));
            },

            // --- Fungsi Tabel BA Aktivasi ---
            generateDraftBA(item) {
                window.dispatchEvent(new CustomEvent('trigger-confirm', {
                    detail: {
                        title: 'Generate Draft BA', message: 'Sistem akan membuat PDF Draft BA Aktivasi dengan Nomor Surat otomatis. Lanjutkan?', type: 'info', confirmText: 'Ya, Generate',
                        onConfirm: () => {
                            item.status = 'Menunggu TTD Pemda';
                            item.noBa = 'BA.' + Math.floor(Math.random() * 900 + 100) + '/KNMP/2026';
                            item.tglBa = this.formatDate(new Date()).split(' ')[0]; // just date
                        }
                    }
                }));
            },
            openUploadBAModal(item) {
                this.activeBa = item;
                this.uploadBaFile = '';
                this.showUploadBAModal = true;
            },
            submitUploadBA() {
                if(!this.uploadBaFile) return alert('Silakan pilih file PDF Berita Acara!');
                
                window.dispatchEvent(new CustomEvent('trigger-confirm', {
                    detail: {
                        title: 'Selesaikan BA Aktivasi', message: 'Pastikan dokumen yang diunggah telah memiliki tanda tangan dan stempel resmi.', type: 'success', confirmText: 'Selesai & Simpan',
                        onConfirm: () => {
                            this.activeBa.status = 'BA Terbit (Selesai)';
                            this.showUploadBAModal = false;
                        }
                    }
                }));
            },

            formatDate(date) {
                const pad = (n) => n.toString().padStart(2, '0');
                return `${pad(date.getDate())}/${pad(date.getMonth() + 1)}/${date.getFullYear()} ${pad(date.getHours())}:${pad(date.getMinutes())}`;
            }
        }));
    });
</script>
@endsection

@extends('layouts.app')

@section('title', 'KNMP - Manajemen Calon Lokasi (Master)')

@section('content')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

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
                <h2 class="text-xl font-semibold tracking-tight">Manajemen Calon Lokasi</h2>
                <p class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1">Siklus 1: Dari
                    pengajuan usulan baru hingga ditetapkan menjadi Lokasi Definitif.</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center gap-3">
                @if ($currentStage === 'pengajuan')
                    @if (\Illuminate\Support\Facades\Auth::user()->isUserDaerah())
                        <a href="{{ route('program.master.calon-lokasi.create') }}"
                            class="bg-teal-light hover:bg-teal-600 text-white rounded-xl px-4 py-2.5 text-xs font-semibold transition-all flex items-center justify-between gap-2 shadow-sm whitespace-nowrap">
                            Tambah Pengajuan <i class="fa-solid fa-plus bg-white/20 p-1.5 rounded-lg"></i>
                        </a>
                    @endif
                @endif
            </div>
        </div>

        <!-- Stepper / Tabs -->
        <div
            class="bg-white dark:bg-bgSurface-dark rounded-2xl border border-gray-100 dark:border-gray-800 p-2 overflow-hidden">
            <div class="flex items-center w-full">
                @foreach ($stages as $key => $data)
                    <a href="?stage={{ $key }}"
                        class="flex items-center group relative py-2 px-2 {{ $loop->last ? '' : 'flex-1' }}">
                        <div class="flex items-center gap-2 relative z-10 shrink-0">
                            <div
                                class="w-7 h-7 rounded-full flex items-center justify-center font-medium text-xs transition-colors
 {{ $currentStage === $key ? 'bg-teal-light text-white ' : 'bg-gray-100 dark:bg-gray-800 text-gray-400 group-hover:bg-teal-light/20 group-hover:text-teal-light' }}">
                                <i class="fa-solid {{ $data['icon'] }} text-[10px]"></i>
                            </div>
                            <span
                                class="font-medium text-xs transition-colors whitespace-normal max-w-[90px] leading-[1.1] {{ $currentStage === $key ? 'text-teal-light' : 'text-textMuted-light dark:text-textMuted-dark group-hover:text-teal-light' }}">
                                {{ $data['label'] }}
                            </span>
                        </div>
                        @if (!$loop->last)
                            <div class="flex-1 h-px bg-gray-300 dark:bg-gray-700 mx-2 min-w-[10px]"></div>
                        @endif

                        @if ($currentStage === $key)
                            <div class="absolute inset-0 bg-teal-light/5 dark:bg-teal-600/10 rounded-xl"></div>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Main Data Table -->
        <div
            class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl overflow-hidden flex flex-col mt-2">
            <div
                class="p-4 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50 dark:bg-gray-800/20">
                <div class="flex items-center gap-2 text-sm text-textMuted-light dark:text-textMuted-dark">
                    <span>Tampilkan</span>
                    <select
                        class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-lg px-2 py-1.5 focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark font-medium ">
                        <option>10</option>
                    </select>
                    <span>entri</span>
                </div>

                <div class="relative w-full sm:w-64">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input x-model="searchQuery" type="text" placeholder="Cari data lokasi..."
                        class="w-full pl-9 pr-4 py-2 rounded-xl border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:border-teal-light outline-none transition-all ">
                </div>
            </div>

            <div class="overflow-x-auto min-h-[300px]">

                <!-- TABLE 1: PENGAJUAN PROPOSAL -->
                <table x-show="currentStage === 'pengajuan'" class="w-full text-left text-xs whitespace-nowrap">
                    <thead
                        class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
                        <tr>
                            <th class="px-6 py-4">Pengusul</th>
                            <th class="px-6 py-4">Usulan Lokasi</th>
                            <th class="px-6 py-4">Tanggal Pengajuan</th>
                            <th class="px-6 py-4 text-center">Dokumen</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
                        <template x-for="item in filterData(proposals)" :key="item.id">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="px-6 py-4"><a href="#" class="font-medium text-teal-light hover:underline"
                                        x-text="item.idUser"></a></td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="item.desa">
                                    </div>
                                    <div class="text-[11px] text-textMuted-light dark:text-textMuted-dark mt-0.5"
                                        x-text="`${item.kecamatan}, ${item.kabupaten}, ${item.provinsi}`"></div>
                                </td>
                                <td class="px-6 py-4 text-textMuted-light" x-text="item.tanggal"></td>
                                <td class="px-6 py-4 text-center">
                                    <template x-if="item.dokumen">
                                        <a :href="item.dokumen" target="_blank"
                                            class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors inline-flex items-center justify-center mx-auto"
                                            title="Lihat Dokumen Proposal"><i class="fa-solid fa-file-lines"></i></a>
                                    </template>
                                    <template x-if="!item.dokumen">
                                        <span class="text-gray-400 dark:text-gray-600">-</span>
                                    </template>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" @click="openDetailModal(item)"
                                            class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-teal-light hover:bg-teal-light/10 transition-colors flex items-center justify-center"
                                            title="Detail"><i class="fa-solid fa-eye pointer-events-none"></i></button>
                                        <template
                                            x-if="item.status === 'Menunggu Review' || item.status === undefined || item.status === null">
                                            @can('verify-calon-lokasi')
                                                <button type="button" @click="verifyProposalDirect(item)"
                                                    class="w-8 h-8 rounded-md bg-success/10 text-success hover:bg-success hover:text-white transition-colors flex items-center justify-center"
                                                    title="Terima Proposal"><i
                                                        class="fa-solid fa-check pointer-events-none"></i></button>
                                            @endcan
                                        </template>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>

                <!-- TABLE 2: VERIFIKASI ADMINISTRASI -->
                <table x-show="currentStage === 'verif-admin'" style="display: none;"
                    class="w-full text-left text-xs whitespace-nowrap">
                    <thead
                        class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
                        <tr>
                            <th class="px-6 py-4">ID User</th>
                            <th class="px-6 py-4">Usulan Lokasi</th>
                            <th class="px-6 py-4 text-center">Dokumen</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
                        <template x-for="item in filterData(verifList)" :key="item.id">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="px-6 py-4"><span
                                        class="font-medium text-teal-light cursor-pointer hover:underline"
                                        x-text="item.idUser"></span></td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="item.desa">
                                    </div>
                                    <div class="text-[11px] text-textMuted-light dark:text-textMuted-dark mt-0.5"
                                        x-text="item.kabupaten"></div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <template x-if="item.dokumen">
                                        <a :href="item.dokumen" target="_blank"
                                            class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors inline-flex items-center justify-center mx-auto"
                                            title="Lihat Dokumen Proposal"><i class="fa-solid fa-file-lines"></i></a>
                                    </template>
                                    <template x-if="!item.dokumen">
                                        <span class="text-gray-400 dark:text-gray-600">-</span>
                                    </template>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2.5 py-1 rounded-md text-[0.7rem] font-medium bg-teal-light/10 text-teal-light"
                                        x-text="item.status"></span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @can('verify-calon-lokasi')
                                            <button type="button" @click="openVerifAdminModal(item)"
                                                class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors flex items-center justify-center"
                                                title="Penilaian Verifikasi"><i
                                                    class="fa-solid fa-clipboard-check pointer-events-none"></i></button>
                                        @endcan
                                        <button type="button" @click="openDetailModal(item)"
                                            class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-teal-light hover:bg-teal-light/10 transition-colors flex items-center justify-center cursor-pointer relative z-50"
                                            title="Detail"><i class="fa-solid fa-eye pointer-events-none"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>

                <!-- TABLE 3: BA AKTIVASI -->
                <table x-show="currentStage === 'ba-aktivasi'" style="display: none;"
                    class="w-full text-left text-xs whitespace-nowrap">
                    <thead
                        class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
                        <tr>
                            <th class="px-6 py-4">ID User</th>
                            <th class="px-6 py-4">Usulan Lokasi</th>
                            <th class="px-6 py-4 text-center">Berita Acara</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
                        <template x-for="item in filterData(baAktivasiList)" :key="item.id">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="px-6 py-4"><span
                                        class="font-medium text-teal-light cursor-pointer hover:underline"
                                        x-text="item.idUser"></span></td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-textMain-light dark:text-textMain-dark"
                                        x-text="item.desa"></div>
                                    <div class="text-[11px] text-textMuted-light mt-0.5" x-text="item.kabupaten"></div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <template x-if="item.dokumen">
                                        <a :href="item.dokumen" target="_blank"
                                            class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors inline-flex items-center justify-center mx-auto"
                                            title="Lihat BA Aktivasi"><i class="fa-solid fa-file-pdf"></i></a>
                                    </template>
                                    <template x-if="!item.dokumen">
                                        <span class="text-gray-400 dark:text-gray-600">-</span>
                                    </template>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2.5 py-1 rounded-md text-[0.7rem] font-medium bg-warning/10 text-warning"
                                        x-text="item.status"></span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @can('manage-calon-lokasi')
                                            <button type="button" @click="openUploadBaAktivasiModal(item)"
                                                class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors flex items-center justify-center cursor-pointer relative z-50"
                                                title="Unggah Berita Acara Aktivasi"><i
                                                    class="fa-solid fa-file-arrow-up pointer-events-none"></i></button>
                                        @endcan
                                        <button type="button" @click="openDetailModal(item)"
                                            class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-teal-light hover:bg-teal-light/10 transition-colors flex items-center justify-center cursor-pointer relative z-50"
                                            title="Detail"><i class="fa-solid fa-eye pointer-events-none"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>

                <!-- TABLE 4: VERIFIKASI TEKNIS LAPANGAN -->
                <table x-show="currentStage === 'verif-teknis'" style="display: none;"
                    class="w-full text-left text-xs whitespace-nowrap">
                    <thead
                        class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
                        <tr>
                            <th class="px-6 py-4">ID User</th>
                            <th class="px-6 py-4">Usulan Lokasi</th>
                            <th class="px-6 py-4 text-center">Dokumen</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
                        <template x-for="item in filterData(verifTeknisList)" :key="item.id">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="px-6 py-4"><span
                                        class="font-medium text-teal-light cursor-pointer hover:underline"
                                        x-text="item.idUser"></span></td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-textMain-light dark:text-textMain-dark"
                                        x-text="item.desa"></div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <template x-if="item.dokumen">
                                        <a :href="item.dokumen" target="_blank"
                                            class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors inline-flex items-center justify-center mx-auto"
                                            title="Lihat BA Aktivasi"><i class="fa-solid fa-file-pdf"></i></a>
                                    </template>
                                    <template x-if="!item.dokumen">
                                        <span class="text-gray-400 dark:text-gray-600">-</span>
                                    </template>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @can('verify-calon-lokasi')
                                            <button type="button" @click="openVerifTeknisModal(item)"
                                                class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors flex items-center justify-center cursor-pointer relative z-50"
                                                title="Penilaian Lapangan"><i
                                                    class="fa-solid fa-map-location-dot pointer-events-none"></i></button>
                                        @endcan
                                        <button type="button" @click="openDetailModal(item)"
                                            class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-teal-light hover:bg-teal-light/10 transition-colors flex items-center justify-center cursor-pointer relative z-50"
                                            title="Detail"><i class="fa-solid fa-eye pointer-events-none"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>

                <!-- TABLE 5: BA CALON -->
                <table x-show="currentStage === 'ba-calon'" style="display: none;"
                    class="w-full text-left text-xs whitespace-nowrap">
                    <thead
                        class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
                        <tr>
                            <th class="px-6 py-4">ID User</th>
                            <th class="px-6 py-4">Usulan Lokasi</th>
                            <th class="px-6 py-4 text-center">Berita Acara</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
                        <template x-for="item in filterData(baCalonList)" :key="item.id">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="px-6 py-4"><span
                                        class="font-medium text-teal-light cursor-pointer hover:underline"
                                        x-text="item.idUser"></span></td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-textMain-light dark:text-textMain-dark"
                                        x-text="item.desa"></div>
                                    <div class="text-[11px] text-textMuted-light mt-0.5" x-text="item.kabupaten"></div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <template x-if="item.dokumen">
                                        <a :href="item.dokumen" target="_blank"
                                            class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors inline-flex items-center justify-center mx-auto"
                                            title="Lihat BA Calon"><i class="fa-solid fa-file-pdf"></i></a>
                                    </template>
                                    <template x-if="!item.dokumen">
                                        <span class="text-gray-400 dark:text-gray-600">-</span>
                                    </template>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2.5 py-1 rounded-md text-[0.7rem] font-medium bg-warning/10 text-warning"
                                        x-text="item.status"></span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @can('manage-calon-lokasi')
                                            <button type="button" @click="openBaCalonModal(item)"
                                                class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors flex items-center justify-center cursor-pointer relative z-50"
                                                title="Unggah Berita Acara Calon"><i
                                                    class="fa-solid fa-file-arrow-up pointer-events-none"></i></button>
                                        @endcan
                                        <button type="button" @click="openDetailModal(item)"
                                            class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-teal-light hover:bg-teal-light/10 transition-colors flex items-center justify-center cursor-pointer relative z-50"
                                            title="Detail"><i class="fa-solid fa-eye pointer-events-none"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>

                <!-- TABLE 6: PENETAPAN CALON -->
                <table x-show="currentStage === 'penetapan'" style="display: none;"
                    class="w-full text-left text-xs whitespace-nowrap">
                    <thead
                        class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
                        <tr>
                            <th class="px-6 py-4">ID User</th>
                            <th class="px-6 py-4">Usulan Lokasi</th>
                            <th class="px-6 py-4 text-center">Dokumen</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
                        <template x-for="item in filterData(penetapanList)" :key="item.id">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="px-6 py-4"><span
                                        class="font-medium text-teal-light cursor-pointer hover:underline"
                                        x-text="item.idUser"></span></td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-textMain-light dark:text-textMain-dark"
                                        x-text="item.desa"></div>
                                    <div class="text-[11px] text-textMuted-light mt-0.5" x-text="item.kabupaten"></div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a :href="item.dokumen || '#'" target="_blank"
                                        class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors inline-flex items-center justify-center mx-auto"
                                        title="Lihat BA Calon"><i class="fa-solid fa-file-pdf"></i></a>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2.5 py-1 rounded-md text-[0.7rem] font-medium bg-success/10 text-success border border-success/20 w-max"
                                        x-text="item.status"></span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @can('manage-calon-lokasi')
                                            <button type="button" @click="openPenetapanModal(item)"
                                                class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors flex items-center justify-center cursor-pointer relative z-50"
                                                title="Unggah SK Penetapan"><i
                                                    class="fa-solid fa-file-circle-check pointer-events-none"></i></button>
                                        @endcan
                                        <button type="button" @click="openDetailModal(item)"
                                            class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-teal-light hover:bg-teal-light/10 transition-colors flex items-center justify-center cursor-pointer relative z-50"
                                            title="Detail"><i class="fa-solid fa-eye pointer-events-none"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div><!-- close overflow-x-auto -->
        </div><!-- close bg-bgSurface -->

        <!-- SEMUA MODALS DI BAWAH SINI -->
        <div>

            <!-- MODAL: Preview PDF Pengajuan -->
            <div x-show="showPreviewModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">

                <div @click.away="showPreviewModal = false"
                     x-show="showPreviewModal"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                     class="bg-white dark:bg-gray-900 rounded-2xl w-full max-w-6xl shadow-2xl border border-gray-100 dark:border-gray-800 relative mx-4 h-[90vh] flex flex-col md:flex-row overflow-hidden">

                    <!-- PDF Viewer -->
                    <div class="w-full md:w-3/5 bg-gray-100 dark:bg-gray-800 flex flex-col border-r border-gray-200 dark:border-gray-700">
                        <div class="px-4 py-3 bg-gray-200 dark:bg-gray-900 flex justify-between items-center shrink-0">
                            <div class="font-medium text-sm flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                <i class="fa-solid fa-file-pdf text-danger"></i> Dokumen Proposal
                            </div>
                        </div>
                        <div class="flex-1 overflow-auto p-4 md:p-8 flex items-start justify-center">
                            <div class="bg-white w-full max-w-2xl h-[800px] flex flex-col p-8 shadow-sm">
                                <div class="text-center border-b-2 border-black pb-4 mb-6">
                                    <h1 class="text-base font-medium uppercase text-black">PROPOSAL PEMBANGUNAN KNMP</h1>
                                    <h2 class="text-xl font-semibold uppercase text-black" x-text="activeProposal?.desa"></h2>
                                </div>
                                <div class="space-y-4 text-justify font-serif text-black">
                                    <p>Yang bertanda tangan di bawah ini, selaku pimpinan daerah mengajukan usulan penetapan lokasi...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Kanan -->
                    <div class="w-full md:w-2/5 flex flex-col bg-white dark:bg-bgSurface-dark">
                        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center shrink-0">
                            <h3 class="text-base font-medium text-textMain-light dark:text-textMain-dark">Detail Verifikasi</h3>
                            <button type="button" @click="showPreviewModal = false" class="text-gray-400 hover:text-danger transition-colors">
                                <i class="fa-solid fa-xmark text-lg"></i>
                            </button>
                        </div>

                        <div class="flex-1 overflow-y-auto p-6">
                            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-5 mb-6 border border-gray-100 dark:border-gray-700">
                                <h4 class="font-medium text-base mb-1 text-textMain-light dark:text-textMain-dark" x-text="activeProposal?.desa"></h4>
                                <p class="text-sm text-textMuted-light mb-4" x-text="`${activeProposal?.kecamatan}, ${activeProposal?.kabupaten}`"></p>
                                <span class="px-2.5 py-1 rounded-md text-xs font-medium bg-warning/10 text-warning" x-text="activeProposal?.status"></span>
                            </div>

                            <h4 class="font-medium text-sm uppercase text-textMuted-light dark:text-textMuted-dark mb-4 tracking-wider flex items-center gap-2">
                                <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Tindakan
                            </h4>
                            <div class="relative border-l-2 border-gray-100 dark:border-gray-700 ml-3 space-y-6">
                                <template x-for="(hist, index) in activeProposal?.history" :key="index">
                                    <div class="relative pl-6">
                                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-white dark:bg-gray-900 border-2" :class="hist.type === 'upload' ? 'border-teal-light' : 'border-success'"></div>
                                        <div class="text-xs text-textMuted-light mb-1" x-text="hist.time"></div>
                                        <div class="font-medium text-sm text-textMain-light dark:text-textMain-dark mb-1" x-text="hist.user"></div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400" x-text="hist.action"></div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="p-6 border-t border-gray-100 dark:border-gray-800 shrink-0">
                            @can('verify-calon-lokasi')
                                <template x-if="activeProposal?.status === 'Menunggu Review'">
                                    <div class="flex gap-3">
                                        <button @click="verifyProposal('Ditolak', '')" class="flex-1 px-4 py-2 border border-danger text-danger hover:bg-danger hover:text-white rounded-lg text-sm font-medium transition-colors flex items-center justify-center gap-2">
                                            <i class="fa-solid fa-xmark"></i> Tolak
                                        </button>
                                        <button @click="verifyProposal('Diverifikasi', '')" class="flex-1 px-4 py-2 bg-teal-light text-white rounded-lg text-sm font-medium hover:bg-teal-600 transition-colors flex items-center justify-center gap-2">
                                            <i class="fa-solid fa-check-double"></i> Verifikasi
                                        </button>
                                    </div>
                                </template>
                            @endcan
                            <template x-if="activeProposal?.status !== 'Menunggu Review'">
                                <div class="bg-gray-50 dark:bg-gray-800/50 p-4 rounded-xl text-center border border-gray-100 dark:border-gray-700">
                                    <p class="text-sm font-medium text-textMuted-light dark:text-textMuted-dark"><i class="fa-solid fa-lock text-gray-400 mr-2"></i> Pengajuan sudah diproses.</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODAL 2: Form Ceklis Verifikasi Administrasi -->
            <div x-show="showChecklistModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">

                <div @click.away="showChecklistModal = false"
                     x-show="showChecklistModal"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                     class="bg-white dark:bg-gray-900 rounded-2xl w-full max-w-3xl p-6 shadow-2xl border border-gray-100 dark:border-gray-800 relative mx-4 max-h-[90vh] flex flex-col">
                    
                    <div class="flex justify-between items-center mb-6 shrink-0">
                        <div>
                            <h3 class="text-lg font-semibold text-textMain-light dark:text-textMain-dark">Checklist Verifikasi Administrasi</h3>
                            <p class="text-xs font-normal text-teal-light font-medium mt-1" x-text="activeVerif?.desa"></p>
                        </div>
                        <button type="button" @click="showChecklistModal = false" class="text-gray-400 hover:text-danger transition-colors">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <div class="space-y-4 overflow-y-auto">
                        <p class="text-sm text-textMuted-light dark:text-textMuted-dark mb-4">Pastikan dokumen pendukung yang diunggah oleh daerah sesuai dengan pedoman teknis KNMP.</p>

                        <div class="border border-gray-100 dark:border-gray-700 rounded-2xl overflow-hidden divide-y divide-gray-100 dark:divide-gray-800">
                            <template x-for="(doc, idx) in activeVerif?.documents" :key="idx">
                                <div class="p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                                    <div class="flex items-start gap-3 flex-1">
                                        <div class="w-10 h-10 rounded-xl bg-teal-50 dark:bg-teal-900/30 text-teal-light flex items-center justify-center shrink-0">
                                            <i class="fa-regular fa-file-lines text-base"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-textMain-light dark:text-textMain-dark" x-text="doc.name"></h4>
                                            <p class="text-xs text-textMuted-light mt-0.5" x-text="doc.desc"></p>
                                            <button class="text-[0.7rem] font-medium text-teal-light hover:underline mt-1 flex items-center justify-between gap-1">
                                                Lihat File <i class="fa-solid fa-paperclip"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="shrink-0 flex items-center">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" class="sr-only peer" x-model="doc.isValid" @change="updateChecklistProgress()">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-success">
                                            </div>
                                            <span class="ml-3 text-sm font-medium" :class="doc.isValid ? 'text-success' : 'text-gray-400'" x-text="doc.isValid ? 'Sesuai' : 'Belum Sesuai'"></span>
                                        </label>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col sm:flex-row justify-between items-center gap-4 shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full border-4 flex items-center justify-center font-medium text-xs"
                                :class="activeVerif?.checkedDocs === activeVerif?.totalDocs ? 'border-success text-success bg-success/10' : 'border-warning text-warning bg-warning/10'"
                                x-text="`${activeVerif?.checkedDocs}/${activeVerif?.totalDocs}`">
                            </div>
                            <div class="text-sm">
                                <div class="font-medium text-textMain-light dark:text-textMain-dark">Progres Verifikasi</div>
                                <div class="text-xs text-success font-medium" x-show="activeVerif?.checkedDocs === activeVerif?.totalDocs">Dokumen lengkap, siap diterbitkan BA!</div>
                            </div>
                        </div>

                        <button @click="terbitkanBA()"
                            class="px-5 py-2.5 rounded-lg text-sm font-medium transition-all flex items-center justify-between gap-2"
                            :class="activeVerif?.checkedDocs === activeVerif?.totalDocs ? 'bg-teal-light hover:bg-teal-600 text-white' : 'bg-gray-300 dark:bg-gray-700 text-gray-500 cursor-not-allowed'"
                            :disabled="activeVerif?.checkedDocs !== activeVerif?.totalDocs">
                            Terbitkan BA <i class="fa-solid fa-file-signature"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- MODAL 2.5: Form Penilaian Verifikasi Administrasi -->
            <div x-show="showVerifAdminModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">

                <div @click.away="showVerifAdminModal = false"
                     x-show="showVerifAdminModal"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                     class="bg-white dark:bg-gray-900 rounded-2xl w-full max-w-xl p-6 shadow-2xl border border-gray-100 dark:border-gray-800 relative mx-4 max-h-[90vh] overflow-y-auto">

                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-textMain-light dark:text-textMain-dark">Penilaian Verifikasi</h3>
                            <p class="text-xs text-textMuted-light dark:text-textMuted-dark mt-0.5">Tahap Administrasi</p>
                        </div>
                        <button type="button" @click="showVerifAdminModal = false" class="text-gray-400 hover:text-danger transition-colors">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="space-y-4">
                        <!-- Context Info -->
                        <div class="mb-5 p-4 rounded-xl bg-teal-light/5 border border-teal-light/10 flex items-start gap-3">
                            <i class="fa-solid fa-circle-info text-teal-light mt-0.5"></i>
                            <div>
                                <p class="text-[11px] text-teal-light/80 font-medium uppercase tracking-wider mb-0.5">
                                    Target Usulan Lokasi</p>
                                <p class="text-sm font-semibold text-teal-900 dark:text-teal-100" x-text="activeVerif?.desa || '-'"></p>
                                <p class="text-xs text-teal-700/70 dark:text-teal-300/70" x-text="activeVerif?.kabupaten || '-'"></p>
                            </div>
                        </div>

                        <form @submit.prevent="submitVerifAdmin" id="formVerifAdmin" class="space-y-5">
                            <div class="grid grid-cols-1 gap-5">
                                <!-- Status -->
                                <div>
                                    <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Keputusan <span class="text-danger">*</span></label>
                                    <select x-model="formVerif.status" required class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark">
                                        <option value="Lolos">Lolos</option>
                                        <option value="Revisi">Revisi</option>
                                        <option value="Ditolak">Ditolak</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Catatan -->
                            <div>
                                <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Catatan Verifikasi</label>
                                <textarea x-model="formVerif.catatan" rows="3" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark placeholder-gray-400" placeholder="Berikan catatan detail terkait hasil verifikasi..."></textarea>
                            </div>

                            <div class="mt-8 flex justify-end gap-3">
                                <button type="button" @click="showVerifAdminModal = false" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-teal-light text-white rounded-lg text-sm font-medium hover:bg-teal-600 transition-colors flex items-center gap-2">
                                    <i class="fa-solid fa-paper-plane"></i> Simpan Hasil
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <!-- MODAL 5: Form Penilaian Verifikasi Teknis Lapangan -->
            <div x-show="showVerifTeknisModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">

                <div @click.away="showVerifTeknisModal = false"
                     x-show="showVerifTeknisModal"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                     class="bg-white dark:bg-gray-900 rounded-2xl w-full max-w-xl p-6 shadow-2xl border border-gray-100 dark:border-gray-800 relative mx-4 max-h-[90vh] overflow-y-auto">

                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-textMain-light dark:text-textMain-dark">Penilaian Lapangan</h3>
                            <p class="text-xs text-textMuted-light dark:text-textMuted-dark mt-0.5">Tahap Verifikasi Teknis</p>
                        </div>
                        <button type="button" @click="showVerifTeknisModal = false" class="text-gray-400 hover:text-danger transition-colors">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="space-y-4">
                        <div class="mb-5 p-4 rounded-xl bg-teal-light/5 border border-teal-light/10 flex items-start gap-3">
                            <i class="fa-solid fa-circle-info text-teal-light mt-0.5"></i>
                            <div>
                                <p class="text-[11px] text-teal-light/80 font-medium uppercase tracking-wider mb-0.5">
                                    Target Usulan Lokasi</p>
                                <p class="text-sm font-semibold text-teal-900 dark:text-teal-100" x-text="activeVerifTeknis?.desa || '-'"></p>
                                <p class="text-xs text-teal-700/70 dark:text-teal-300/70" x-text="activeVerifTeknis?.kabupaten || '-'"></p>
                            </div>
                        </div>

                        <form @submit.prevent="submitVerifTeknis" id="formVerifTeknis" class="space-y-5">
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Keputusan <span class="text-danger">*</span></label>
                                    <select x-model="formVerifTeknis.status" required class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark">
                                        <option value="Lolos">Lolos (Lanjut BA Calon)</option>
                                        <option value="Revisi">Revisi</option>
                                        <option value="Ditolak">Ditolak</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Catatan Verifikasi Lapangan</label>
                                    <textarea x-model="formVerifTeknis.catatan" rows="3" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark placeholder-gray-400" placeholder="Berikan catatan detail terkait hasil tinjauan lapangan..."></textarea>
                                </div>

                                <div class="mt-8 flex justify-end gap-3">
                                    <button type="button" @click="showVerifTeknisModal = false" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">Batal</button>
                                    <button type="submit" class="px-4 py-2 bg-teal-light text-white rounded-lg text-sm font-medium hover:bg-teal-600 transition-colors flex items-center gap-2">
                                        <i class="fa-solid fa-paper-plane"></i> Simpan Penilaian
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- MODAL 6: Upload BA Calon -->
            <div x-show="showUploadBaCalonModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">

                <div @click.away="showUploadBaCalonModal = false"
                     x-show="showUploadBaCalonModal"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                     class="bg-white dark:bg-gray-900 rounded-2xl w-full max-w-md p-6 shadow-2xl border border-gray-100 dark:border-gray-800 relative mx-4 max-h-[90vh] overflow-y-auto">

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-textMain-light dark:text-textMain-dark">Unggah BA Calon Lokasi</h3>
                        <button type="button" @click="showUploadBaCalonModal = false" class="text-gray-400 hover:text-danger transition-colors">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <p class="text-xs text-textMuted-light dark:text-textMuted-dark leading-relaxed">Silakan unggah dokumen Berita Acara yang telah disetujui bersama.</p>

                        <div class="mt-4 p-5 rounded-xl border-2 border-dashed flex flex-col items-center justify-center text-center relative transition-colors group"
                             :class="fileNameBaCalon ? 'bg-teal-50 border-teal-light dark:bg-teal-900/20' : 'border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 hover:border-teal-light/50'">
                            <template x-if="!fileNameBaCalon">
                                <div>
                                    <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2 group-hover:text-teal-light transition-colors"></i>
                                    <p class="text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Pilih File PDF</p>
                                    <p class="text-[10px] text-gray-400">Maks. 2MB</p>
                                </div>
                            </template>
                            <template x-if="fileNameBaCalon">
                                <div>
                                    <i class="fa-solid fa-file-pdf text-3xl text-teal-light mb-2"></i>
                                    <p class="text-xs font-medium text-teal-700 dark:text-teal-300 mb-1 px-4 truncate max-w-[250px]" x-text="fileNameBaCalon"></p>
                                    <p class="text-[10px] text-teal-600/70 dark:text-teal-400/70">Siap diunggah</p>
                                </div>
                            </template>
                            <input type="file" id="dokumen_ba_calon" accept=".pdf"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                   @change="const file = $event.target.files[0]; if (file) { if (file.size > 2 * 1024 * 1024) { showToastMsg('Ukuran file melebihi 2MB!', 'danger'); $event.target.value = ''; fileNameBaCalon = ''; } else { fileNameBaCalon = file.name; } } else { fileNameBaCalon = ''; }">
                        </div>

                        <div class="mt-8 flex justify-end gap-3">
                            <button @click="showUploadBaCalonModal = false" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">Batal</button>
                            <button @click="submitUploadBaCalon()" class="px-4 py-2 bg-teal-light text-white rounded-lg text-sm font-medium hover:bg-teal-600 transition-colors flex items-center gap-2">
                                Unggah <i class="fa-solid fa-check"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODAL 7: Upload SK Penetapan -->
            <div x-show="showUploadSkPenetapanModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">

                <div @click.away="showUploadSkPenetapanModal = false"
                     x-show="showUploadSkPenetapanModal"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                     class="bg-white dark:bg-gray-900 rounded-2xl w-full max-w-md p-6 shadow-2xl border border-gray-100 dark:border-gray-800 relative mx-4 max-h-[90vh] overflow-y-auto">

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-textMain-light dark:text-textMain-dark">Unggah SK Penetapan</h3>
                        <button type="button" @click="showUploadSkPenetapanModal = false" class="text-gray-400 hover:text-danger transition-colors">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <p class="text-xs text-textMuted-light dark:text-textMuted-dark leading-relaxed">Silakan unggah dokumen Surat Keputusan (SK) Penetapan final.</p>

                        <div class="mt-4 p-5 rounded-xl border-2 border-dashed flex flex-col items-center justify-center text-center relative transition-colors group"
                             :class="fileNameSkPenetapan ? 'bg-teal-50 border-teal-light dark:bg-teal-900/20' : 'border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 hover:border-teal-light/50'">
                            <template x-if="!fileNameSkPenetapan">
                                <div>
                                    <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2 group-hover:text-teal-light transition-colors"></i>
                                    <p class="text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Pilih File PDF</p>
                                    <p class="text-[10px] text-gray-400">Maks. 2MB</p>
                                </div>
                            </template>
                            <template x-if="fileNameSkPenetapan">
                                <div>
                                    <i class="fa-solid fa-file-pdf text-3xl text-teal-light mb-2"></i>
                                    <p class="text-xs font-medium text-teal-700 dark:text-teal-300 mb-1 px-4 truncate max-w-[250px]" x-text="fileNameSkPenetapan"></p>
                                    <p class="text-[10px] text-teal-600/70 dark:text-teal-400/70">Siap diunggah</p>
                                </div>
                            </template>
                            <input type="file" id="dokumen_sk_penetapan" accept=".pdf"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                   @change="const file = $event.target.files[0]; if (file) { if (file.size > 2 * 1024 * 1024) { showToastMsg('Ukuran file melebihi 2MB!', 'danger'); $event.target.value = ''; fileNameSkPenetapan = ''; } else { fileNameSkPenetapan = file.name; } } else { fileNameSkPenetapan = ''; }">
                        </div>

                        <div class="mt-8 flex justify-end gap-3">
                            <button @click="showUploadSkPenetapanModal = false" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">Batal</button>
                            <button @click="submitUploadSkPenetapan()" class="px-4 py-2 bg-teal-light text-white rounded-lg text-sm font-medium hover:bg-teal-600 transition-colors flex items-center gap-2">
                                Unggah <i class="fa-solid fa-check"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODAL 8: Upload BA Aktivasi -->
            <div x-show="showUploadBaAktivasiModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">

                <div @click.away="showUploadBaAktivasiModal = false"
                     x-show="showUploadBaAktivasiModal"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                     class="bg-white dark:bg-gray-900 rounded-2xl w-full max-w-md p-6 shadow-2xl border border-gray-100 dark:border-gray-800 relative mx-4 max-h-[90vh] overflow-y-auto">

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-textMain-light dark:text-textMain-dark">Unggah BA Aktivasi</h3>
                        <button type="button" @click="showUploadBaAktivasiModal = false" class="text-gray-400 hover:text-danger transition-colors">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <p class="text-xs text-textMuted-light dark:text-textMuted-dark leading-relaxed">Silakan unggah salinan Berita Acara Aktivasi yang telah ditandatangani.</p>

                        <div class="mt-4 p-5 rounded-xl border-2 border-dashed flex flex-col items-center justify-center text-center relative transition-colors group"
                             :class="fileNameBaAktivasi ? 'bg-teal-50 border-teal-light dark:bg-teal-900/20' : 'border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 hover:border-teal-light/50'">
                            <template x-if="!fileNameBaAktivasi">
                                <div>
                                    <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2 group-hover:text-teal-light transition-colors"></i>
                                    <p class="text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Pilih File PDF</p>
                                    <p class="text-[10px] text-gray-400">Maks. 2MB</p>
                                </div>
                            </template>
                            <template x-if="fileNameBaAktivasi">
                                <div>
                                    <i class="fa-solid fa-file-pdf text-3xl text-teal-light mb-2"></i>
                                    <p class="text-xs font-medium text-teal-700 dark:text-teal-300 mb-1 px-4 truncate max-w-[250px]" x-text="fileNameBaAktivasi"></p>
                                    <p class="text-[10px] text-teal-600/70 dark:text-teal-400/70">Siap diunggah</p>
                                </div>
                            </template>
                            <input type="file" id="dokumen_ba_aktivasi" accept=".pdf"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                   @change="const file = $event.target.files[0]; if (file) { if (file.size > 2 * 1024 * 1024) { showToastMsg('Ukuran file melebihi 2MB!', 'danger'); $event.target.value = ''; fileNameBaAktivasi = ''; } else { fileNameBaAktivasi = file.name; } } else { fileNameBaAktivasi = ''; }">
                        </div>

                        <div class="mt-8 flex justify-end gap-3">
                            <button @click="showUploadBaAktivasiModal = false" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">Batal</button>
                            <button @click="submitUploadBaAktivasi()" class="px-4 py-2 bg-teal-light text-white rounded-lg text-sm font-medium hover:bg-teal-600 transition-colors flex items-center gap-2">
                                Unggah <i class="fa-solid fa-check"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODAL 4: Tampilkan Semua Data Detail -->
            <div x-show="showDetailDataModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-3 sm:p-6 bg-slate-900/75 dark:bg-black/80 backdrop-blur-md overflow-y-auto"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">

                <div @click.away="showDetailDataModal = false"
                     x-show="showDetailDataModal"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                     class="bg-bgSurface-light dark:bg-bgSurface-dark rounded-3xl w-full max-w-5xl shadow-2xl border border-gray-100 dark:border-gray-800 relative my-auto max-h-[90vh] flex flex-col overflow-hidden text-left">

                    <!-- Header Modal (Sticky Top) -->
                    <div class="px-6 py-5 sm:px-8 sm:py-6 border-b border-gray-100 dark:border-gray-800 bg-white/95 dark:bg-bgSurface-dark/95 backdrop-blur-md z-20 flex items-center justify-between gap-4 shrink-0">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <div class="w-11 h-11 rounded-2xl bg-teal-light/10 text-teal-light flex items-center justify-center font-bold text-lg shrink-0 shadow-2xs">
                                <i class="fa-solid fa-map-location-dot"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2.5 flex-wrap">
                                    <h3 class="text-lg sm:text-xl font-bold tracking-tight text-textMain-light dark:text-textMain-dark">Usulan Calon Lokasi</h3>
                                    <span class="px-2.5 py-0.5 rounded-lg bg-teal-light/10 text-teal-light font-mono font-bold text-xs" x-text="activeDetail ? `#${activeDetail.id}` : ''"></span>
                                </div>
                                <p class="text-xs text-textMuted-light dark:text-textMuted-dark mt-1 truncate flex items-center gap-1.5">
                                    <i class="fa-solid fa-location-dot text-teal-light/80 text-[11px]"></i>
                                    <span x-text="activeDetail ? `Desa ${activeDetail.desa || '-'}, Kec. ${activeDetail.kecamatan || '-'}, Kab. ${activeDetail.kabupaten || '-'}, Prov. ${activeDetail.provinsi || '-'}` : ''"></span>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 shrink-0">
                            <!-- Status Badge -->
                            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-semibold border shadow-2xs transition-all"
                                  :class="{
                                      'bg-amber-50 text-amber-700 border-amber-200/80 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800/80': activeDetail?.status === 'Menunggu Review' || !activeDetail?.status,
                                      'bg-emerald-50 text-emerald-700 border-emerald-200/80 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/80': activeDetail?.status === 'Diverifikasi' || activeDetail?.status === 'Selesai',
                                      'bg-rose-50 text-rose-700 border-rose-200/80 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800/80': activeDetail?.status === 'Ditolak'
                                  }">
                                <span class="w-2 h-2 rounded-full animate-pulse"
                                      :class="{
                                          'bg-amber-500': activeDetail?.status === 'Menunggu Review' || !activeDetail?.status,
                                          'bg-emerald-500': activeDetail?.status === 'Diverifikasi' || activeDetail?.status === 'Selesai',
                                          'bg-rose-500': activeDetail?.status === 'Ditolak'
                                      }"></span>
                                <span x-text="activeDetail?.status || 'Menunggu Review'"></span>
                            </span>

                            <!-- Close Button -->
                            <button type="button" @click="showDetailDataModal = false"
                                    class="w-9 h-9 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 flex items-center justify-center transition-all focus:outline-none"
                                    title="Tutup Modal">
                                <i class="fa-solid fa-xmark text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Body Content (Scrollable) -->
                    <div class="p-6 sm:p-8 overflow-y-auto space-y-8 divide-y divide-gray-100 dark:divide-gray-800/80">
                        <template x-if="activeDetail && activeDetail.detail">
                            <div class="space-y-8">

                                <!-- STATUS ALERT BANNER -->
                                <div class="p-4 sm:p-5 rounded-2xl border-l-4 flex items-start gap-4 shadow-sm transition-all"
                                     :class="{
                                         'bg-amber-50/70 border-amber-500 text-amber-900 dark:bg-amber-950/30 dark:border-amber-500 dark:text-amber-200': activeDetail?.status === 'Menunggu Review' || !activeDetail?.status,
                                         'bg-emerald-50/70 border-emerald-500 text-emerald-900 dark:bg-emerald-950/30 dark:border-emerald-500 dark:text-emerald-200': activeDetail?.status === 'Diverifikasi' || activeDetail?.status === 'Selesai',
                                         'bg-rose-50/70 border-rose-500 text-rose-900 dark:bg-rose-950/30 dark:border-rose-500 dark:text-rose-200': activeDetail?.status === 'Ditolak'
                                     }">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg shrink-0 shadow-2xs"
                                         :class="{
                                             'bg-amber-100 text-amber-600 dark:bg-amber-900/60 dark:text-amber-400': activeDetail?.status === 'Menunggu Review' || !activeDetail?.status,
                                             'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/60 dark:text-emerald-400': activeDetail?.status === 'Diverifikasi' || activeDetail?.status === 'Selesai',
                                             'bg-rose-100 text-rose-600 dark:bg-rose-900/60 dark:text-rose-400': activeDetail?.status === 'Ditolak'
                                         }">
                                        <i class="fa-solid" :class="{
                                            'fa-clock-rotate-left': activeDetail?.status === 'Menunggu Review' || !activeDetail?.status,
                                            'fa-circle-check': activeDetail?.status === 'Diverifikasi' || activeDetail?.status === 'Selesai',
                                            'fa-circle-xmark': activeDetail?.status === 'Ditolak'
                                        }"></i>
                                    </div>
                                    <div class="flex-1 text-xs sm:text-sm leading-relaxed">
                                        <div class="font-bold text-sm mb-0.5" x-text="activeDetail?.status === 'Diverifikasi' || activeDetail?.status === 'Selesai' ? 'Status Disetujui' : (activeDetail?.status === 'Ditolak' ? 'Pemberitahuan Penolakan' : 'Informasi Verifikasi')"></div>
                                        <div class="text-textMuted-light dark:text-textMuted-dark font-normal" x-text="activeDetail?.status === 'Diverifikasi' || activeDetail?.status === 'Selesai' ? 'Usulan calon lokasi ini telah lolos tahapan verifikasi administrasi & teknis lapangan dan resmi ditetapkan.' : (activeDetail?.status === 'Ditolak' ? 'Usulan calon lokasi ini tidak memenuhi kriteria kelayakan dan telah ditolak pada proses evaluasi.' : 'Usulan calon lokasi ini sedang dalam tahap review dan menunggu proses pemeriksaan dokumen administrasi serta survei teknis lapangan.')"></div>
                                    </div>
                                </div>

                                <!-- SECTION 1: INFORMASI WILAYAH & GEOGRAFIS -->
                                <div class="space-y-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-teal-light/10 text-teal-light flex items-center justify-center font-bold text-xs">
                                            <i class="fa-solid fa-earth-asia"></i>
                                        </div>
                                        <h4 class="text-base font-bold tracking-tight text-textMain-light dark:text-textMain-dark">Informasi Wilayah & Geografis</h4>
                                    </div>
                                    
                                    <!-- Unified Card Panel -->
                                    <div class="bg-gray-50/50 dark:bg-gray-800/30 rounded-2xl p-6 border border-gray-200/60 dark:border-gray-800 shadow-2xs">
                                        <dl class="grid grid-cols-2 sm:grid-cols-4 gap-y-6 gap-x-6">
                                            <div>
                                                <dt class="text-[11px] font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider mb-1">Provinsi</dt>
                                                <dd class="text-sm font-bold text-textMain-light dark:text-textMain-dark" x-text="activeDetail?.provinsi || '-'"></dd>
                                            </div>
                                            <div>
                                                <dt class="text-[11px] font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider mb-1">Kabupaten / Kota</dt>
                                                <dd class="text-sm font-bold text-textMain-light dark:text-textMain-dark" x-text="activeDetail?.kabupaten || '-'"></dd>
                                            </div>
                                            <div>
                                                <dt class="text-[11px] font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider mb-1">Kecamatan</dt>
                                                <dd class="text-sm font-bold text-textMain-light dark:text-textMain-dark" x-text="activeDetail?.kecamatan || '-'"></dd>
                                            </div>
                                            <div>
                                                <dt class="text-[11px] font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider mb-1">Desa / Kelurahan</dt>
                                                <dd class="text-sm font-bold text-textMain-light dark:text-textMain-dark" x-text="activeDetail?.desa || '-'"></dd>
                                            </div>
                                            <div>
                                                <dt class="text-[11px] font-semibold text-teal-light uppercase tracking-wider mb-1">Luas Lahan</dt>
                                                <dd class="text-sm font-extrabold text-teal-light dark:text-teal-400 font-mono" x-text="`${activeDetail?.pengajuan?.luas_lahan || 0} m²`"></dd>
                                            </div>
                                            <div>
                                                <dt class="text-[11px] font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider mb-1">Dimensi (P × L)</dt>
                                                <dd class="text-sm font-semibold text-textMain-light dark:text-textMain-dark" x-text="`${activeDetail?.pengajuan?.panjang_lahan || 0}m × ${activeDetail?.pengajuan?.lebar_lahan || 0}m`"></dd>
                                            </div>
                                            <div>
                                                <dt class="text-[11px] font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider mb-1">Koordinat GPS</dt>
                                                <dd class="text-xs font-mono font-semibold text-textMain-light dark:text-textMain-dark truncate" x-text="activeDetail?.lat && activeDetail?.lng ? `${activeDetail.lat}, ${activeDetail.lng}` : '-'"></dd>
                                            </div>
                                            <div>
                                                <dt class="text-[11px] font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider mb-1">Tanggal Diajukan</dt>
                                                <dd class="text-xs font-mono font-semibold text-textMain-light dark:text-textMain-dark" x-text="activeDetail?.created_at || '-'"></dd>
                                            </div>
                                        </dl>
                                    </div>
                                </div>

                                <!-- SECTION 2: SPESIFIKASI FISIK & KARAKTERISTIK LAHAN -->
                                <div class="space-y-4 pt-6 border-t border-gray-100 dark:border-gray-800">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center font-bold text-xs">
                                            <i class="fa-solid fa-layer-group"></i>
                                        </div>
                                        <h4 class="text-base font-bold tracking-tight text-textMain-light dark:text-textMain-dark">Spesifikasi Fisik & Karakteristik Lahan</h4>
                                    </div>
                                    
                                    <!-- Unified Card Panel -->
                                    <div class="bg-gray-50/50 dark:bg-gray-800/30 rounded-2xl p-6 border border-gray-200/60 dark:border-gray-800 shadow-2xs">
                                        <dl class="grid grid-cols-2 sm:grid-cols-4 gap-y-6 gap-x-6">
                                            <div>
                                                <dt class="text-[11px] font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider mb-1">Status Kepemilikan</dt>
                                                <dd class="text-sm font-bold text-textMain-light dark:text-textMain-dark" x-text="activeDetail?.detail?.status_kepemilikan || '-'"></dd>
                                            </div>
                                            <div>
                                                <dt class="text-[11px] font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider mb-1">Kesesuaian RTRW</dt>
                                                <dd class="text-sm font-bold text-textMain-light dark:text-textMain-dark" x-text="activeDetail?.detail?.kesesuaian_rtrw || '-'"></dd>
                                            </div>
                                            <div>
                                                <dt class="text-[11px] font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider mb-1">Kemiringan Lahan</dt>
                                                <dd class="text-sm font-semibold text-textMain-light dark:text-textMain-dark" x-text="`${activeDetail?.pengajuan?.kemiringan_lahan || 0}°`"></dd>
                                            </div>
                                            <div>
                                                <dt class="text-[11px] font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider mb-1">Tekstur Tanah</dt>
                                                <dd class="text-sm font-semibold text-textMain-light dark:text-textMain-dark" x-text="activeDetail?.pengajuan?.tekstur_tanah || '-'"></dd>
                                            </div>
                                            <div>
                                                <dt class="text-[11px] font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider mb-1">Salinitas Air</dt>
                                                <dd class="text-sm font-semibold text-textMain-light dark:text-textMain-dark" x-text="activeDetail?.pengajuan?.salinitas_air || '-'"></dd>
                                            </div>
                                            <div>
                                                <dt class="text-[11px] font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider mb-1">Jarak ke Pantai</dt>
                                                <dd class="text-sm font-semibold text-textMain-light dark:text-textMain-dark" x-text="`${activeDetail?.pengajuan?.jarak_pantai || '-'} meter`"></dd>
                                            </div>
                                            <div>
                                                <dt class="text-[11px] font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider mb-1">Jarak ke Sungai</dt>
                                                <dd class="text-sm font-semibold text-textMain-light dark:text-textMain-dark" x-text="`${activeDetail?.pengajuan?.jarak_sungai || '-'} meter`"></dd>
                                            </div>
                                            <div>
                                                <dt class="text-[11px] font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider mb-1">Pasang Surut</dt>
                                                <dd class="text-sm font-bold text-textMain-light dark:text-textMain-dark" x-text="activeDetail?.detail?.is_pasang_surut || '-'"></dd>
                                            </div>
                                        </dl>
                                    </div>
                                </div>

                                <!-- SECTION 3: KRITERIA KHUSUS KAWASAN -->
                                <div class="space-y-4 pt-6 border-t border-gray-100 dark:border-gray-800">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center font-bold text-xs">
                                            <i class="fa-solid fa-seedling"></i>
                                        </div>
                                        <h4 class="text-base font-bold tracking-tight text-textMain-light dark:text-textMain-dark">Kriteria Khusus Kawasan</h4>
                                    </div>
                                    
                                    <!-- Unified Card Panel -->
                                    <div class="bg-gray-50/50 dark:bg-gray-800/30 rounded-2xl p-6 border border-gray-200/60 dark:border-gray-800 shadow-2xs">
                                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6">
                                            <div class="space-y-1.5">
                                                <div class="text-[11px] font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">Area Mangrove</div>
                                                <div class="flex items-center gap-2 text-sm font-bold"
                                                     :class="activeDetail?.detail?.is_mangrove === 'Ya' ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-500 dark:text-gray-400'">
                                                    <i class="fa-solid text-base" :class="activeDetail?.detail?.is_mangrove === 'Ya' ? 'fa-circle-check text-emerald-500' : 'fa-circle-xmark text-gray-400'"></i>
                                                    <span x-text="activeDetail?.detail?.is_mangrove || 'Tidak'"></span>
                                                </div>
                                            </div>
                                            <div class="space-y-1.5">
                                                <div class="text-[11px] font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">Zona Konservasi</div>
                                                <div class="flex items-center gap-2 text-sm font-bold"
                                                     :class="activeDetail?.detail?.is_konservasi === 'Ya' ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-500 dark:text-gray-400'">
                                                    <i class="fa-solid text-base" :class="activeDetail?.detail?.is_konservasi === 'Ya' ? 'fa-circle-check text-emerald-500' : 'fa-circle-xmark text-gray-400'"></i>
                                                    <span x-text="activeDetail?.detail?.is_konservasi || 'Tidak'"></span>
                                                </div>
                                            </div>
                                            <div class="space-y-1.5">
                                                <div class="text-[11px] font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">Hutan Lindung</div>
                                                <div class="flex items-center gap-2 text-sm font-bold"
                                                     :class="activeDetail?.detail?.is_hutan_lindung === 'Ya' ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-500 dark:text-gray-400'">
                                                    <i class="fa-solid text-base" :class="activeDetail?.detail?.is_hutan_lindung === 'Ya' ? 'fa-circle-check text-emerald-500' : 'fa-circle-xmark text-gray-400'"></i>
                                                    <span x-text="activeDetail?.detail?.is_hutan_lindung || 'Tidak'"></span>
                                                </div>
                                            </div>
                                            <div class="space-y-1.5">
                                                <div class="text-[11px] font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">Kawasan Budidaya</div>
                                                <div class="flex items-center gap-2 text-sm font-bold"
                                                     :class="activeDetail?.detail?.is_kawasan_budidaya === 'Ya' ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-500 dark:text-gray-400'">
                                                    <i class="fa-solid text-base" :class="activeDetail?.detail?.is_kawasan_budidaya === 'Ya' ? 'fa-circle-check text-emerald-500' : 'fa-circle-xmark text-gray-400'"></i>
                                                    <span x-text="activeDetail?.detail?.is_kawasan_budidaya || 'Tidak'"></span>
                                                </div>
                                            </div>
                                            <div class="space-y-1.5">
                                                <div class="text-[11px] font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">Aliran Sungai (DAS)</div>
                                                <div class="flex items-center gap-2 text-sm font-bold"
                                                     :class="activeDetail?.detail?.is_das === 'Ya' ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-500 dark:text-gray-400'">
                                                    <i class="fa-solid text-base" :class="activeDetail?.detail?.is_das === 'Ya' ? 'fa-circle-check text-emerald-500' : 'fa-circle-xmark text-gray-400'"></i>
                                                    <span x-text="activeDetail?.detail?.is_das || 'Tidak'"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- SECTION 4: PENANGGUNG JAWAB USULAN -->
                                <div class="space-y-4 pt-6 border-t border-gray-100 dark:border-gray-800">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-purple-500/10 text-purple-500 flex items-center justify-center font-bold text-xs">
                                            <i class="fa-solid fa-user-check"></i>
                                        </div>
                                        <h4 class="text-base font-bold tracking-tight text-textMain-light dark:text-textMain-dark">Penanggung Jawab Usulan</h4>
                                    </div>
                                    
                                    <!-- Sleek Contact Card -->
                                    <div class="bg-gray-50/50 dark:bg-gray-800/30 rounded-2xl p-5 border border-gray-200/60 dark:border-gray-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-2xs">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-2xl bg-teal-light/10 text-teal-light flex items-center justify-center text-lg font-bold shrink-0 shadow-2xs">
                                                <i class="fa-solid fa-user-tie"></i>
                                            </div>
                                            <div>
                                                <div class="text-[11px] font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">Reporter / Nama Pengisi</div>
                                                <div class="text-base font-bold text-textMain-light dark:text-textMain-dark mt-0.5" x-text="activeDetail?.detail?.nama_pengisi || '-'"></div>
                                                <div class="text-xs text-textMuted-light dark:text-textMuted-dark mt-0.5 flex items-center gap-1.5">
                                                    <i class="fa-solid fa-briefcase text-[11px] text-teal-light"></i>
                                                    <span x-text="activeDetail?.detail?.jabatan_pengisi || '-'"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-4 pt-3 sm:pt-0 border-t sm:border-t-0 border-gray-200/60 dark:border-gray-700/60 justify-between sm:justify-end">
                                            <div class="text-left sm:text-right">
                                                <div class="text-[11px] font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">Kontak / WhatsApp</div>
                                                <div class="text-xs font-mono font-bold text-textMain-light dark:text-textMain-dark mt-0.5" x-text="activeDetail?.detail?.no_hp_pengisi || '-'"></div>
                                            </div>
                                            <a :href="activeDetail?.detail?.no_hp_pengisi ? `https://wa.me/${activeDetail.detail.no_hp_pengisi.replace(/[^0-9]/g, '')}` : '#'" target="_blank"
                                               class="px-3.5 py-2 rounded-xl bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-xs font-semibold flex items-center gap-2 transition-colors shrink-0">
                                                <i class="fa-brands fa-whatsapp text-base"></i>
                                                <span>Hubungi</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- SECTION 5: LAMPIRAN DOKUMEN RESMI -->
                                <div class="space-y-4 pt-6 border-t border-gray-100 dark:border-gray-800">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center font-bold text-xs">
                                            <i class="fa-solid fa-folder-open"></i>
                                        </div>
                                        <h4 class="text-base font-bold tracking-tight text-textMain-light dark:text-textMain-dark">Lampiran Dokumen Resmi</h4>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 pt-1">
                                        <!-- Proposal -->
                                        <template x-if="activeDetail?.dokumen">
                                            <a :href="activeDetail?.dokumen || '#'" target="_blank"
                                               class="flex items-center justify-between p-4 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200/80 dark:border-gray-800 hover:border-rose-400 dark:hover:border-rose-500/60 transition-all hover:shadow-md group">
                                                <div class="flex items-center gap-3 min-w-0">
                                                    <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                                        <i class="fa-solid fa-file-pdf text-lg"></i>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <div class="text-xs font-bold text-textMain-light dark:text-textMain-dark truncate group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">Proposal Usulan</div>
                                                        <div class="text-[10px] text-textMuted-light dark:text-textMuted-dark font-mono mt-0.5 truncate" x-text="activeDetail?.created_at || 'PDF'"></div>
                                                    </div>
                                                </div>
                                                <i class="fa-solid fa-arrow-up-right-from-square text-xs text-gray-400 group-hover:text-rose-600 transition-colors"></i>
                                            </a>
                                        </template>

                                        <!-- BA Aktivasi -->
                                        <template x-if="activeDetail?.baAktivasi?.dokumen_ba">
                                            <a :href="activeDetail?.baAktivasi?.dokumen_ba ? (activeDetail.baAktivasi.dokumen_ba.startsWith('http') ? activeDetail.baAktivasi.dokumen_ba : '/storage/' + activeDetail.baAktivasi.dokumen_ba) : '#'" target="_blank"
                                               class="flex items-center justify-between p-4 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200/80 dark:border-gray-800 hover:border-blue-400 dark:hover:border-blue-500/60 transition-all hover:shadow-md group">
                                                <div class="flex items-center gap-3 min-w-0">
                                                    <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                                        <i class="fa-solid fa-file-lines text-lg"></i>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <div class="text-xs font-bold text-textMain-light dark:text-textMain-dark truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">BA Aktivasi</div>
                                                        <div class="text-[10px] text-textMuted-light dark:text-textMuted-dark font-mono mt-0.5 truncate" x-text="activeDetail?.baAktivasi?.status || 'Verifikasi'"></div>
                                                    </div>
                                                </div>
                                                <i class="fa-solid fa-arrow-up-right-from-square text-xs text-gray-400 group-hover:text-blue-600 transition-colors"></i>
                                            </a>
                                        </template>

                                        <!-- BA Calon -->
                                        <template x-if="activeDetail?.baCalon?.dokumen_ba">
                                            <a :href="activeDetail?.baCalon?.dokumen_ba ? (activeDetail.baCalon.dokumen_ba.startsWith('http') ? activeDetail.baCalon.dokumen_ba : '/storage/' + activeDetail.baCalon.dokumen_ba) : '#'" target="_blank"
                                               class="flex items-center justify-between p-4 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200/80 dark:border-gray-800 hover:border-purple-400 dark:hover:border-purple-500/60 transition-all hover:shadow-md group">
                                                <div class="flex items-center gap-3 min-w-0">
                                                    <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                                        <i class="fa-solid fa-file-signature text-lg"></i>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <div class="text-xs font-bold text-textMain-light dark:text-textMain-dark truncate group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">BA Calon Lokasi</div>
                                                        <div class="text-[10px] text-textMuted-light dark:text-textMuted-dark font-mono mt-0.5 truncate" x-text="activeDetail?.baCalon?.status || 'Survei'"></div>
                                                    </div>
                                                </div>
                                                <i class="fa-solid fa-arrow-up-right-from-square text-xs text-gray-400 group-hover:text-purple-600 transition-colors"></i>
                                            </a>
                                        </template>

                                        <!-- SK Penetapan -->
                                        <template x-if="activeDetail?.penetapan?.dokumen_sk">
                                            <a :href="activeDetail?.penetapan?.dokumen_sk ? (activeDetail.penetapan.dokumen_sk.startsWith('http') ? activeDetail.penetapan.dokumen_sk : '/storage/' + activeDetail.penetapan.dokumen_sk) : '#'" target="_blank"
                                               class="flex items-center justify-between p-4 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200/80 dark:border-gray-800 hover:border-amber-400 dark:hover:border-amber-500/60 transition-all hover:shadow-md group">
                                                <div class="flex items-center gap-3 min-w-0">
                                                    <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                                        <i class="fa-solid fa-award text-lg"></i>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <div class="text-xs font-bold text-textMain-light dark:text-textMain-dark truncate group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">SK Penetapan</div>
                                                        <div class="text-[10px] text-textMuted-light dark:text-textMuted-dark truncate mt-0.5" x-text="activeDetail?.penetapan?.no_sk || 'Penetapan'"></div>
                                                    </div>
                                                </div>
                                                <i class="fa-solid fa-arrow-up-right-from-square text-xs text-gray-400 group-hover:text-amber-600 transition-colors"></i>
                                            </a>
                                        </template>
                                    </div>
                                    <template x-if="!activeDetail?.dokumen && !activeDetail?.baAktivasi?.dokumen_ba && !activeDetail?.baCalon?.dokumen_ba && !activeDetail?.penetapan?.dokumen_sk">
                                        <div class="text-xs text-textMuted-light dark:text-textMuted-dark italic py-6 bg-gray-50/60 dark:bg-gray-800/20 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700 text-center flex flex-col items-center justify-center gap-2">
                                            <i class="fa-regular fa-folder-closed text-2xl text-gray-300 dark:text-gray-600"></i>
                                            <span>Belum ada berkas lampiran yang diunggah.</span>
                                        </div>
                                    </template>
                                </div>

                                <!-- SECTION 6: RIWAYAT & LOG VERIFIKASI (TIMELINE) -->
                                <div class="space-y-6 pt-6 border-t border-gray-100 dark:border-gray-800">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center font-bold text-xs">
                                            <i class="fa-solid fa-clock-rotate-left"></i>
                                        </div>
                                        <h4 class="text-base font-bold tracking-tight text-textMain-light dark:text-textMain-dark">History / Riwayat Verifikasi</h4>
                                    </div>
                                    
                                    <div class="relative pl-3 sm:pl-5 pt-2 space-y-8 before:absolute before:left-[15px] sm:before:left-[23px] before:top-4 before:bottom-4 before:w-0.5 before:bg-gradient-to-b before:from-amber-400 before:via-teal-400 before:to-blue-500 dark:before:from-amber-600 dark:before:via-teal-600 dark:before:to-blue-600">
                                        <!-- Item 1: Diajukan -->
                                        <div class="relative flex items-start gap-4 sm:gap-5">
                                            <div class="w-3.5 sm:w-4 h-3.5 sm:h-4 rounded-full bg-amber-500 ring-4 ring-white dark:ring-bgSurface-dark mt-1 shrink-0 z-10 shadow-xs"></div>
                                            <div class="flex-1 p-4 rounded-2xl bg-gray-50/80 dark:bg-gray-800/30 border border-gray-100 dark:border-gray-800/80 flex flex-col sm:flex-row sm:items-baseline justify-between gap-2 transition-all hover:border-amber-300 dark:hover:border-amber-700/50">
                                                <div>
                                                    <div class="flex items-center gap-2 flex-wrap mb-1">
                                                        <span class="text-sm font-bold text-textMain-light dark:text-textMain-dark">Status awal dibuat</span>
                                                        <span class="px-2.5 py-0.5 rounded-md bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300 text-[11px] font-semibold">Menunggu Review</span>
                                                    </div>
                                                    <div class="text-xs text-textMuted-light dark:text-textMuted-dark leading-relaxed">Usulan calon lokasi berhasil dikirim oleh <span class="font-semibold text-textMain-light dark:text-textMain-dark" x-text="activeDetail?.detail?.nama_pengisi || 'Pengisi Usulan'"></span>.</div>
                                                </div>
                                                <div class="text-xs font-mono font-medium text-textMuted-light dark:text-textMuted-dark shrink-0" x-text="activeDetail?.created_at || '-'"></div>
                                            </div>
                                        </div>

                                        <!-- Item 2: Verifikasi Adm -->
                                        <template x-if="activeDetail?.verifAdmin">
                                            <div class="relative flex items-start gap-4 sm:gap-5">
                                                <div class="w-3.5 sm:w-4 h-3.5 sm:h-4 rounded-full ring-4 ring-white dark:ring-bgSurface-dark mt-1 shrink-0 z-10 shadow-xs"
                                                     :class="activeDetail?.verifAdmin?.status === 'Ditolak' ? 'bg-rose-500' : 'bg-teal-500'"></div>
                                                <div class="flex-1 p-4 rounded-2xl bg-gray-50/80 dark:bg-gray-800/30 border border-gray-100 dark:border-gray-800/80 flex flex-col sm:flex-row sm:items-baseline justify-between gap-2 transition-all"
                                                     :class="activeDetail?.verifAdmin?.status === 'Ditolak' ? 'hover:border-rose-300 dark:hover:border-rose-700/50' : 'hover:border-teal-300 dark:hover:border-teal-700/50'">
                                                    <div class="space-y-1.5 flex-1">
                                                        <div class="flex items-center gap-2 flex-wrap">
                                                            <span class="text-sm font-bold text-textMain-light dark:text-textMain-dark">Verifikasi Administrasi</span>
                                                            <span class="px-2.5 py-0.5 rounded-md text-[11px] font-semibold"
                                                                  :class="activeDetail?.verifAdmin?.status === 'Ditolak' ? 'bg-rose-100 text-rose-800 dark:bg-rose-900/50 dark:text-rose-300' : 'bg-teal-100 text-teal-800 dark:bg-teal-900/50 dark:text-teal-300'"
                                                                  x-text="activeDetail?.verifAdmin?.status"></span>
                                                        </div>
                                                        <template x-if="activeDetail?.verifAdmin?.catatan">
                                                            <div class="text-xs text-textMain-light dark:text-textMain-dark bg-white dark:bg-gray-900/60 p-3 rounded-xl border border-gray-200/60 dark:border-gray-800 leading-relaxed font-normal" x-text="`Catatan: ${activeDetail.verifAdmin.catatan}`"></div>
                                                        </template>
                                                    </div>
                                                    <div class="text-xs font-mono font-medium text-textMuted-light dark:text-textMuted-dark shrink-0 sm:ml-4" x-text="activeDetail?.verifAdmin?.updated_at || activeDetail?.created_at || '-'"></div>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Item 3: Verifikasi Teknis -->
                                        <template x-if="activeDetail?.verifTeknis">
                                            <div class="relative flex items-start gap-4 sm:gap-5">
                                                <div class="w-3.5 sm:w-4 h-3.5 sm:h-4 rounded-full ring-4 ring-white dark:ring-bgSurface-dark mt-1 shrink-0 z-10 shadow-xs"
                                                     :class="activeDetail?.verifTeknis?.status === 'Ditolak' ? 'bg-rose-500' : 'bg-blue-500'"></div>
                                                <div class="flex-1 p-4 rounded-2xl bg-gray-50/80 dark:bg-gray-800/30 border border-gray-100 dark:border-gray-800/80 flex flex-col sm:flex-row sm:items-baseline justify-between gap-2 transition-all"
                                                     :class="activeDetail?.verifTeknis?.status === 'Ditolak' ? 'hover:border-rose-300 dark:hover:border-rose-700/50' : 'hover:border-blue-300 dark:hover:border-blue-700/50'">
                                                    <div class="space-y-1.5 flex-1">
                                                        <div class="flex items-center gap-2 flex-wrap">
                                                            <span class="text-sm font-bold text-textMain-light dark:text-textMain-dark">Verifikasi Teknis Lapangan</span>
                                                            <span class="px-2.5 py-0.5 rounded-md text-[11px] font-semibold"
                                                                  :class="activeDetail?.verifTeknis?.status === 'Ditolak' ? 'bg-rose-100 text-rose-800 dark:bg-rose-900/50 dark:text-rose-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300'"
                                                                  x-text="activeDetail?.verifTeknis?.status"></span>
                                                            <template x-if="activeDetail?.verifTeknis?.skor">
                                                                <span class="px-2 py-0.5 rounded-md bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300 text-[11px] font-semibold" x-text="`Skor: ${activeDetail.verifTeknis.skor}`"></span>
                                                            </template>
                                                        </div>
                                                        <template x-if="activeDetail?.verifTeknis?.catatan">
                                                            <div class="text-xs text-textMain-light dark:text-textMain-dark bg-white dark:bg-gray-900/60 p-3 rounded-xl border border-gray-200/60 dark:border-gray-800 leading-relaxed font-normal" x-text="`Catatan Inspeksi: ${activeDetail.verifTeknis.catatan}`"></div>
                                                        </template>
                                                    </div>
                                                    <div class="text-xs font-mono font-medium text-textMuted-light dark:text-textMuted-dark shrink-0 sm:ml-4" x-text="activeDetail?.verifTeknis?.updated_at || activeDetail?.created_at || '-'"></div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                            </div>
                        </template>
                        <template x-if="!activeDetail || !activeDetail.detail">
                            <div class="flex flex-col items-center justify-center py-20 text-textMuted-light dark:text-textMuted-dark gap-3">
                                <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-2xl text-gray-400 dark:text-gray-500">
                                    <i class="fa-solid fa-circle-info"></i>
                                </div>
                                <p class="text-base font-semibold text-textMain-light dark:text-textMain-dark">Data detail usulan tidak tersedia.</p>
                                <p class="text-xs text-textMuted-light dark:text-textMuted-dark">Silakan pilih data lain atau muat ulang halaman.</p>
                            </div>
                        </template>
                    </div>

                    <!-- Footer Modal (Sticky Bottom) -->
                    <div class="px-6 py-4 sm:px-8 border-t border-gray-100 dark:border-gray-800 bg-gray-50/80 dark:bg-gray-800/40 backdrop-blur-md flex justify-end gap-3 shrink-0">
                        <button type="button" @click="showDetailDataModal = false"
                            class="px-6 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-textMain-light dark:text-textMain-dark hover:bg-gray-50 dark:hover:bg-gray-700/80 rounded-xl text-xs font-bold shadow-xs transition-all flex items-center gap-2 focus:outline-none">
                            <i class="fa-solid fa-xmark text-[11px]"></i> Tutup
                        </button>
                    </div>

                </div>
            </div>

        </div>

    </div><!-- close x-data -->

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('calonLokasiManager', (initialStage) => ({
                currentStage: initialStage,
                // State filter pencarian
                searchQuery: '',

                // Toast Notification
                // (State handled globally via x-toast-notification component)

                // Pengajuan
                proposals: [],
                showPreviewModal: false,
                activeProposal: null,

                // Verif Admin
                verifList: [],
                showChecklistModal: false,
                activeVerif: null,
                showVerifAdminModal: false,
                formVerif: {
                    skor: '',
                    status: 'Lolos',
                    catatan: ''
                },

                // BA Aktivasi
                baAktivasiList: [],
                showUploadBaAktivasiModal: false,
                activeBaAktivasi: null,
                fileNameBaAktivasi: '',

                // Detail Data
                showDetailDataModal: false,
                activeDetail: null,

                // Verifikasi Teknis Lapangan
                verifTeknisList: [],
                showVerifTeknisModal: false,
                activeVerifTeknis: null,
                formVerifTeknis: {
                    skor: '',
                    status: 'Lolos',
                    catatan: ''
                },
                fileNameLaporanTeknis: '',

                // BA Calon
                baCalonList: [],
                showUploadBaCalonModal: false,
                activeBaCalon: null,
                fileNameBaCalon: '',

                // Penetapan Calon
                penetapanList: [],
                showUploadSkPenetapanModal: false,
                activePenetapan: null,
                fileNameSkPenetapan: '',

                // Data Master Wilayah
                provinces: [],
                regencies: [],
                districts: [],
                villages: [],

                initData() {
                    // Data diambil secara real-time dari database via Controller
                    this.proposals = @json($proposals);
                    this.verifList = @json($verifList);
                    this.baAktivasiList = @json($baAktivasiList);
                    this.verifTeknisList = @json($verifTeknisList);
                    this.baCalonList = @json($baCalonList);
                    this.penetapanList = @json($penetapanList);
                },

                filterData(list) {
                    if (!this.searchQuery) return list;
                    const q = this.searchQuery.toLowerCase();
                    return list.filter(item => Object.values(item).some(val => String(val).toLowerCase()
                        .includes(q)));
                },

                // --- Fungsi Toast ---
                showToastMsg(msg, type = 'success') {
                    Alpine.store('toast').showToast({
                        message: msg,
                        type: type
                    });
                },

                // --- Fungsi Tabel Pengajuan ---
                openPreviewModal(item) {
                    this.activeProposal = item;
                    this.showPreviewModal = true;
                },
                openDetailModal(item) {
                    this.activeDetail = item;
                    this.showDetailDataModal = true;
                },
                verifyProposal(newStatus, noteStr) {
                    window.dispatchEvent(new CustomEvent('trigger-confirm', {
                        detail: {
                            title: 'Konfirmasi Verifikasi',
                            message: `Yakin berikan status: ${newStatus}?`,
                            type: newStatus === 'Ditolak' ? 'danger' : 'success',
                            confirmText: `Ya`,
                            onConfirm: () => {
                                this.activeProposal.status = newStatus;
                                this.activeProposal = {
                                    ...this.activeProposal
                                };
                                const idx = this.proposals.findIndex(p => p.id === this
                                    .activeProposal.id);
                                if (idx !== -1) this.proposals[idx] = this
                                    .activeProposal;
                            }
                        }
                    }));
                },
                verifyProposalDirect(item) {
                    window.dispatchEvent(new CustomEvent('trigger-confirm', {
                        detail: {
                            title: 'Terima Proposal',
                            message: `Apakah Anda yakin ingin menerima proposal untuk usulan lokasi ${item.desa}? Data akan diverifikasi secara administratif.`,
                            type: 'success',
                            confirmText: 'Terima Proposal',
                            onConfirm: () => {
                                fetch(`/master/knmp/calon-lokasi/${item.id}/update-status`, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify({
                                            status_tahapan: 'verif_admin'
                                        })
                                    })
                                    .then(res => res.json())
                                    .then(data => {
                                        if (data.success) {
                                            this.showToastMsg(
                                                'Proposal berhasil diverifikasi dan dipindahkan ke tahap Verifikasi Administrasi!'
                                            );
                                            setTimeout(() => {
                                                window.location.reload();
                                            }, 2000);
                                        } else {
                                            this.showToastMsg('Gagal: ' + data
                                                .message, 'danger');
                                        }
                                    })
                                    .catch(err => {
                                        console.error(err);
                                        this.showToastMsg(
                                            'Gagal: Terjadi kesalahan sistem saat menghubungi server',
                                            'danger');
                                    });
                            }
                        }
                    }));
                },

                // --- Fungsi Tabel Verifikasi Administrasi ---
                openChecklistModal(item) {
                    this.activeVerif = JSON.parse(JSON.stringify(item));
                    this.showChecklistModal = true;
                },
                openVerifAdminModal(item) {
                    this.activeVerif = item;
                    this.formVerif = {
                        skor: '',
                        status: 'Lolos',
                        catatan: ''
                    };
                    this.showVerifAdminModal = true;
                },
                submitVerifAdmin() {
                    let formData = new FormData();
                    formData.append('status_verif', this.formVerif.status);
                    formData.append('catatan', this.formVerif.catatan || '');

                    fetch(`/master/knmp/calon-lokasi/${this.activeVerif.id}/verif-admin`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                this.showToastMsg(data.message, 'success');
                                this.showVerifAdminModal = false;
                                setTimeout(() => {
                                    window.location.reload();
                                }, 2500);
                            } else {
                                this.showToastMsg('Gagal: ' + (data.message || 'Terjadi kesalahan'),
                                    'danger');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            this.showToastMsg('Gagal terhubung ke server', 'danger');
                        });
                },

                // --- Fungsi Tabel Verifikasi Teknis Lapangan ---
                openVerifTeknisModal(item) {
                    this.activeVerifTeknis = item;
                    this.formVerifTeknis = {
                        status: 'Lolos',
                        catatan: ''
                    };
                    this.showVerifTeknisModal = true;
                },
                submitVerifTeknis() {
                    let formData = new FormData();
                    formData.append('status_verif', this.formVerifTeknis.status);
                    formData.append('catatan', this.formVerifTeknis.catatan);
                    fetch(`/master/knmp/calon-lokasi/${this.activeVerifTeknis.id}/verif-teknis`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    }).then(res => res.json()).then(data => {
                        if (data.success) {
                            this.showToastMsg(data.message, 'success');
                            this.showVerifTeknisModal = false;
                            setTimeout(() => {
                                window.location.reload();
                            }, 2500);
                        } else {
                            this.showToastMsg('Gagal: ' + data.message, 'danger');
                        }
                    }).catch(err => {
                        console.error(err);
                        this.showToastMsg('Gagal terhubung ke server', 'danger');
                    });
                },

                // --- Fungsi Tabel BA Calon Lokasi ---
                openBaCalonModal(item) {
                    this.activeBaCalon = item;
                    let el = document.getElementById('dokumen_ba_calon');
                    if (el) el.value = '';
                    this.fileNameBaCalon = '';
                    this.showUploadBaCalonModal = true;
                },
                submitUploadBaCalon() {
                    let fileInput = document.getElementById('dokumen_ba_calon');
                    if (fileInput.files.length === 0) {
                        this.showToastMsg('Pilih dokumen BA terlebih dahulu', 'warning');
                        return;
                    }
                    let formData = new FormData();
                    formData.append('dokumen_ba', fileInput.files[0]);
                    fetch(`/master/knmp/calon-lokasi/${this.activeBaCalon.id}/ba-calon`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    }).then(res => res.json()).then(data => {
                        if (data.success) {
                            this.showToastMsg(data.message, 'success');
                            this.showUploadBaCalonModal = false;
                            setTimeout(() => window.location.reload(), 2500);
                        } else {
                            this.showToastMsg('Gagal: ' + data.message, 'danger');
                        }
                    }).catch(err => {
                        console.error(err);
                        this.showToastMsg('Gagal terhubung ke server', 'danger');
                    });
                },

                // --- Fungsi Tabel Penetapan SK ---
                openPenetapanModal(item) {
                    this.activePenetapan = item;
                    let el = document.getElementById('dokumen_sk_penetapan');
                    if (el) el.value = '';
                    this.fileNameSkPenetapan = '';
                    this.showUploadSkPenetapanModal = true;
                },
                submitUploadSkPenetapan() {
                    let fileInput = document.getElementById('dokumen_sk_penetapan');
                    if (fileInput.files.length === 0) {
                        this.showToastMsg('Pilih dokumen SK terlebih dahulu', 'warning');
                        return;
                    }
                    let formData = new FormData();
                    formData.append('dokumen_sk', fileInput.files[0]);
                    fetch(`/master/knmp/calon-lokasi/${this.activePenetapan.id}/penetapan`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    }).then(res => res.json()).then(data => {
                        if (data.success) {
                            this.showToastMsg(data.message, 'success');
                            this.showUploadSkPenetapanModal = false;
                            setTimeout(() => window.location.reload(), 2500);
                        } else {
                            this.showToastMsg('Gagal: ' + data.message, 'danger');
                        }
                    }).catch(err => {
                        console.error(err);
                        this.showToastMsg('Gagal terhubung ke server', 'danger');
                    });
                },

                updateChecklistProgress() {
                    this.activeVerif.checkedDocs = this.activeVerif.documents.filter(d => d.isValid)
                        .length;
                },
                terbitkanBA() {
                    if (this.activeVerif.checkedDocs !== this.activeVerif.totalDocs) return;
                    window.dispatchEvent(new CustomEvent('trigger-confirm', {
                        detail: {
                            title: 'Terbitkan BA',
                            message: 'Lanjutkan ke tahap BA Aktivasi?',
                            type: 'info',
                            confirmText: 'Terbitkan BA',
                            onConfirm: () => {
                                this.activeVerif.status = 'Selesai (Lanjut BA)';
                                const idx = this.verifList.findIndex(p => p.id === this
                                    .activeVerif.id);
                                if (idx !== -1) this.verifList[idx] = this.activeVerif;

                                // Tambahkan otomatis ke tabel BA Aktivasi
                                this.baAktivasiList.unshift({
                                    id: Date.now(),
                                    desa: this.activeVerif.desa,
                                    kabupaten: this.activeVerif.kabupaten,
                                    noBa: '',
                                    tglBa: '',
                                    status: 'Menunggu Draft'
                                });
                                this.showChecklistModal = false;
                            }
                        }
                    }));
                },

                // --- Fungsi Tabel BA Aktivasi ---
                openUploadBaAktivasiModal(item) {
                    this.activeBaAktivasi = item;
                    let el = document.getElementById('dokumen_ba_aktivasi');
                    if (el) el.value = '';
                    this.fileNameBaAktivasi = '';
                    this.showUploadBaAktivasiModal = true;
                },
                submitUploadBaAktivasi() {
                    let fileInput = document.getElementById('dokumen_ba_aktivasi');
                    if (fileInput.files.length === 0) {
                        this.showToastMsg('Pilih dokumen BA Aktivasi terlebih dahulu', 'warning');
                        return;
                    }
                    let formData = new FormData();
                    formData.append('dokumen_ba', fileInput.files[0]);
                    fetch(`/master/knmp/calon-lokasi/${this.activeBaAktivasi.id}/ba-aktivasi`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    }).then(res => res.json()).then(data => {
                        if (data.success) {
                            this.showToastMsg(data.message, 'success');
                            this.showUploadBaAktivasiModal = false;
                            setTimeout(() => window.location.reload(), 2500);
                        } else {
                            this.showToastMsg('Gagal: ' + data.message, 'danger');
                        }
                    }).catch(err => {
                        console.error(err);
                        this.showToastMsg('Gagal terhubung ke server', 'danger');
                    });
                },

                formatDate(date) {
                    const pad = (n) => n.toString().padStart(2, '0');
                    return `${pad(date.getDate())}/${pad(date.getMonth() + 1)}/${date.getFullYear()} ${pad(date.getHours())}:${pad(date.getMinutes())}`;
                }
            }));
        });
    </script>
@endsection

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
        <!-- Stepper / Tabs -->
        <div
            class="bg-white dark:bg-bgSurface-dark rounded-2xl border border-gray-100 dark:border-gray-800 p-2 overflow-hidden">
            <div class="flex items-center w-full">
                <template x-for="(data, key, index) in stages" :key="key">
                    <a href="#" @click.prevent="switchStage(key)"
                        class="flex items-center group relative py-2 px-2 cursor-pointer"
                        :class="{ 'flex-1': key !== 'penetapan' }">
                        <div class="flex items-center gap-2 relative z-10 shrink-0">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center font-medium text-xs transition-colors"
                                :class="currentStage === key ? 'bg-teal-light text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-400 group-hover:bg-teal-light/20 group-hover:text-teal-light'">
                                <i class="fa-solid text-[10px]" :class="data.icon"></i>
                            </div>
                            <span class="font-medium text-xs transition-colors whitespace-normal max-w-[90px] leading-[1.1]"
                                :class="currentStage === key ? 'text-teal-light' : 'text-textMuted-light dark:text-textMuted-dark group-hover:text-teal-light'"
                                x-text="data.label">
                            </span>
                        </div>
                        <template x-if="key !== 'penetapan'">
                            <div class="flex-1 h-px bg-gray-300 dark:bg-gray-700 mx-2 min-w-[10px]"></div>
                        </template>

                        <template x-if="currentStage === key">
                            <div class="absolute inset-0 bg-teal-light/5 dark:bg-teal-600/10 rounded-xl"></div>
                        </template>
                    </a>
                </template>
            </div>
        </div>

        <x-table.card 
            title="Manajemen Calon Lokasi" 
            description="Siklus 1: Dari pengajuan usulan baru hingga ditetapkan menjadi Lokasi Definitif."
            :show-per-page="true"
            :custom-table="true">
            <x-slot name="actions">
                @if (\Illuminate\Support\Facades\Auth::user()->isUserDaerah() || \Illuminate\Support\Facades\Auth::user()->isSuperAdmin())
                    <div x-show="currentStage === 'pengajuan'">
                        <x-button-add href="{{ route('program.master.calon-lokasi.create') }}" label="Tambah Pengajuan" icon="fa-plus" />
                    </div>
                @endif
            </x-slot>
            <x-slot name="search">
                <div class="relative w-full sm:w-64">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input x-model="searchQuery" type="text" placeholder="Cari data lokasi..."
                        class="w-full pl-9 pr-4 py-2 rounded-xl border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm outline-none focus:border-teal-light transition-colors text-textMain-light dark:text-textMain-dark">
                </div>
            </x-slot>

                <!-- TABLE 1: PENGAJUAN PROPOSAL -->
                <table x-show="currentStage === 'pengajuan'" class="w-full text-left text-xs whitespace-nowrap">
                    <x-table.thead>
                        <x-table.th>Pengusul</x-table.th>
                        <x-table.th>Usulan Lokasi</x-table.th>
                        <x-table.th>Tanggal Pengajuan</x-table.th>
                        <x-table.th align="center">Dokumen</x-table.th>
                        <x-table.th align="center">Aksi</x-table.th>
                    </x-table.thead>
                    <x-table.tbody>
                        <template x-for="item in filterData(proposals)" :key="item.id">
                            <x-table.tr>
                                <x-table.td><a href="#" class="font-medium text-teal-light hover:underline"
                                        x-text="item.idUser"></a></x-table.td>
                                <x-table.td>
                                    <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="item.desa">
                                    </div>
                                    <div class="text-[11px] text-textMuted-light dark:text-textMuted-dark mt-0.5"
                                        x-text="`${item.kecamatan}, ${item.kabupaten}, ${item.provinsi}`"></div>
                                </x-table.td>
                                <x-table.td class="text-textMuted-light" x-text="item.tanggal"></x-table.td>
                                <x-table.td align="center">
                                    <template x-if="item.dokumen">
                                        <a :href="item.dokumen" target="_blank"
                                            class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors inline-flex items-center justify-center mx-auto cursor-pointer"
                                            title="Lihat Dokumen Proposal"><i class="fa-solid fa-file-lines"></i></a>
                                    </template>
                                    <template x-if="!item.dokumen">
                                        <span class="text-gray-400 dark:text-gray-600">-</span>
                                    </template>
                                </x-table.td>
                                <x-table.td align="center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" @click="openDetailModal(item)"
                                            class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-teal-light hover:bg-teal-light/10 transition-colors flex items-center justify-center cursor-pointer relative z-50"
                                            title="Detail"><i class="fa-solid fa-eye pointer-events-none"></i></button>
                                        <template
                                            x-if="item.status === 'Menunggu Review' || item.status === undefined || item.status === null">
                                            @can('verify-calon-lokasi')
                                                <button type="button" @click="verifyProposalDirect(item)"
                                                    class="w-8 h-8 rounded-md bg-success/10 text-success hover:bg-success hover:text-white transition-colors flex items-center justify-center cursor-pointer relative z-50"
                                                    title="Terima Proposal"><i
                                                        class="fa-solid fa-check pointer-events-none"></i></button>
                                            @endcan
                                        </template>
                                    </div>
                                </x-table.td>
                            </x-table.tr>
                        </template>
                        <tr x-show="filterData(proposals).length === 0">
                            <x-table.td colspan="5" align="center" class="py-8 text-textMuted-light">
                                Belum ada data pengajuan proposal atau tidak ada hasil pencarian.
                            </x-table.td>
                        </tr>
                    </x-table.tbody>
                </table>

                <!-- TABLE 2: VERIFIKASI ADMINISTRASI -->
                <table x-show="currentStage === 'verif-admin'" style="display: none;"
                    class="w-full text-left text-xs whitespace-nowrap">
                    <x-table.thead>
                        <x-table.th>ID User</x-table.th>
                        <x-table.th>Usulan Lokasi</x-table.th>
                        <x-table.th align="center">Dokumen</x-table.th>
                        <x-table.th>Status</x-table.th>
                        <x-table.th align="center">Aksi</x-table.th>
                    </x-table.thead>
                    <x-table.tbody>
                        <template x-for="item in filterData(verifList)" :key="item.id">
                            <x-table.tr>
                                <x-table.td><span
                                        class="font-medium text-teal-light cursor-pointer hover:underline"
                                        x-text="item.idUser"></span></x-table.td>
                                <x-table.td>
                                    <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="item.desa">
                                    </div>
                                    <div class="text-[11px] text-textMuted-light dark:text-textMuted-dark mt-0.5"
                                        x-text="item.kabupaten"></div>
                                </x-table.td>
                                <x-table.td align="center">
                                    <template x-if="item.dokumen">
                                        <a :href="item.dokumen" target="_blank"
                                            class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors inline-flex items-center justify-center mx-auto cursor-pointer relative z-50"
                                            title="Lihat Dokumen Proposal"><i class="fa-solid fa-file-lines"></i></a>
                                    </template>
                                    <template x-if="!item.dokumen">
                                        <span class="text-gray-400 dark:text-gray-600">-</span>
                                    </template>
                                </x-table.td>
                                <x-table.td>
                                    <span
                                        class="px-2.5 py-1 rounded-md text-[0.7rem] font-medium bg-teal-light/10 text-teal-light"
                                        x-text="item.status"></span>
                                </x-table.td>
                                <x-table.td align="center">
                                    <div class="flex items-center justify-center gap-2">
                                        @can('verify-calon-lokasi')
                                            <button type="button" @click="openVerifAdminModal(item)"
                                                class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors flex items-center justify-center cursor-pointer relative z-50"
                                                title="Penilaian Verifikasi"><i
                                                    class="fa-solid fa-clipboard-check pointer-events-none"></i></button>
                                        @endcan
                                        <button type="button" @click="openDetailModal(item)"
                                            class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-teal-light hover:bg-teal-light/10 transition-colors flex items-center justify-center cursor-pointer relative z-50"
                                            title="Detail"><i class="fa-solid fa-eye pointer-events-none"></i></button>
                                    </div>
                                </x-table.td>
                            </x-table.tr>
                        </template>
                        <tr x-show="filterData(verifList).length === 0">
                            <x-table.td colspan="5" align="center" class="py-8 text-textMuted-light">
                                Belum ada data verifikasi administrasi atau tidak ada hasil pencarian.
                            </x-table.td>
                        </tr>
                    </x-table.tbody>
                </table>

                <!-- TABLE 3: BA AKTIVASI -->
                <table x-show="currentStage === 'ba-aktivasi'" style="display: none;"
                    class="w-full text-left text-xs whitespace-nowrap">
                    <x-table.thead>
                        <x-table.th>ID User</x-table.th>
                        <x-table.th>Usulan Lokasi</x-table.th>
                        <x-table.th align="center">Berita Acara</x-table.th>
                        <x-table.th>Status</x-table.th>
                        <x-table.th align="center">Aksi</x-table.th>
                    </x-table.thead>
                    <x-table.tbody>
                        <template x-for="item in filterData(baAktivasiList)" :key="item.id">
                            <x-table.tr>
                                <x-table.td><span
                                        class="font-medium text-teal-light cursor-pointer hover:underline"
                                        x-text="item.idUser"></span></x-table.td>
                                <x-table.td>
                                    <div class="font-medium text-textMain-light dark:text-textMain-dark"
                                        x-text="item.desa"></div>
                                    <div class="text-[11px] text-textMuted-light mt-0.5" x-text="item.kabupaten"></div>
                                </x-table.td>
                                <x-table.td align="center">
                                    <template x-if="item.dokumen">
                                        <a :href="item.dokumen" target="_blank"
                                            class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors inline-flex items-center justify-center mx-auto cursor-pointer relative z-50"
                                            title="Lihat BA Aktivasi"><i class="fa-solid fa-file-pdf"></i></a>
                                    </template>
                                    <template x-if="!item.dokumen">
                                        <span class="text-gray-400 dark:text-gray-600">-</span>
                                    </template>
                                </x-table.td>
                                <x-table.td>
                                    <span
                                        class="px-2.5 py-1 rounded-md text-[0.7rem] font-medium bg-warning/10 text-warning"
                                        x-text="item.status"></span>
                                </x-table.td>
                                <x-table.td align="center">
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
                                </x-table.td>
                            </x-table.tr>
                        </template>
                        <tr x-show="filterData(baAktivasiList).length === 0">
                            <x-table.td colspan="5" align="center" class="py-8 text-textMuted-light">
                                Belum ada data Berita Acara Aktivasi atau tidak ada hasil pencarian.
                            </x-table.td>
                        </tr>
                    </x-table.tbody>
                </table>

                <!-- TABLE 4: VERIFIKASI TEKNIS LAPANGAN -->
                <table x-show="currentStage === 'verif-teknis'" style="display: none;"
                    class="w-full text-left text-xs whitespace-nowrap">
                    <x-table.thead>
                        <x-table.th>ID User</x-table.th>
                        <x-table.th>Usulan Lokasi</x-table.th>
                        <x-table.th align="center">Dokumen</x-table.th>
                        <x-table.th align="center">Aksi</x-table.th>
                    </x-table.thead>
                    <x-table.tbody>
                        <template x-for="item in filterData(verifTeknisList)" :key="item.id">
                            <x-table.tr>
                                <x-table.td><span
                                        class="font-medium text-teal-light cursor-pointer hover:underline"
                                        x-text="item.idUser"></span></x-table.td>
                                <x-table.td>
                                    <div class="font-medium text-textMain-light dark:text-textMain-dark"
                                        x-text="item.desa"></div>
                                </x-table.td>
                                <x-table.td align="center">
                                    <template x-if="item.dokumen">
                                        <a :href="item.dokumen" target="_blank"
                                            class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors inline-flex items-center justify-center mx-auto cursor-pointer relative z-50"
                                            title="Lihat BA Aktivasi"><i class="fa-solid fa-file-pdf"></i></a>
                                    </template>
                                    <template x-if="!item.dokumen">
                                        <span class="text-gray-400 dark:text-gray-600">-</span>
                                    </template>
                                </x-table.td>
                                <x-table.td align="center">
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
                                </x-table.td>
                            </x-table.tr>
                        </template>
                        <tr x-show="filterData(verifTeknisList).length === 0">
                            <x-table.td colspan="4" align="center" class="py-8 text-textMuted-light">
                                Belum ada data verifikasi teknis lapangan atau tidak ada hasil pencarian.
                            </x-table.td>
                        </tr>
                    </x-table.tbody>
                </table>

                <!-- TABLE 5: BA CALON -->
                <table x-show="currentStage === 'ba-calon'" style="display: none;"
                    class="w-full text-left text-xs whitespace-nowrap">
                    <x-table.thead>
                        <x-table.th>ID User</x-table.th>
                        <x-table.th>Usulan Lokasi</x-table.th>
                        <x-table.th align="center">Berita Acara</x-table.th>
                        <x-table.th>Status</x-table.th>
                        <x-table.th align="center">Aksi</x-table.th>
                    </x-table.thead>
                    <x-table.tbody>
                        <template x-for="item in filterData(baCalonList)" :key="item.id">
                            <x-table.tr>
                                <x-table.td><span
                                        class="font-medium text-teal-light cursor-pointer hover:underline"
                                        x-text="item.idUser"></span></x-table.td>
                                <x-table.td>
                                    <div class="font-medium text-textMain-light dark:text-textMain-dark"
                                        x-text="item.desa"></div>
                                    <div class="text-[11px] text-textMuted-light mt-0.5" x-text="item.kabupaten"></div>
                                </x-table.td>
                                <x-table.td align="center">
                                    <template x-if="item.dokumen">
                                        <a :href="item.dokumen" target="_blank"
                                            class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors inline-flex items-center justify-center mx-auto cursor-pointer relative z-50"
                                            title="Lihat BA Calon"><i class="fa-solid fa-file-pdf"></i></a>
                                    </template>
                                    <template x-if="!item.dokumen">
                                        <span class="text-gray-400 dark:text-gray-600">-</span>
                                    </template>
                                </x-table.td>
                                <x-table.td>
                                    <span
                                        class="px-2.5 py-1 rounded-md text-[0.7rem] font-medium bg-warning/10 text-warning"
                                        x-text="item.status"></span>
                                </x-table.td>
                                <x-table.td align="center">
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
                                </x-table.td>
                            </x-table.tr>
                        </template>
                        <tr x-show="filterData(baCalonList).length === 0">
                            <x-table.td colspan="5" align="center" class="py-8 text-textMuted-light">
                                Belum ada data Berita Acara Calon atau tidak ada hasil pencarian.
                            </x-table.td>
                        </tr>
                    </x-table.tbody>
                </table>

                <!-- TABLE 6: PENETAPAN CALON -->
                <table x-show="currentStage === 'penetapan'" style="display: none;"
                    class="w-full text-left text-xs whitespace-nowrap">
                    <x-table.thead>
                        <x-table.th>ID User</x-table.th>
                        <x-table.th>Usulan Lokasi</x-table.th>
                        <x-table.th align="center">Dokumen</x-table.th>
                        <x-table.th>Status</x-table.th>
                        <x-table.th align="center">Aksi</x-table.th>
                    </x-table.thead>
                    <x-table.tbody>
                        <template x-for="item in filterData(penetapanList)" :key="item.id">
                            <x-table.tr>
                                <x-table.td><span
                                        class="font-medium text-teal-light cursor-pointer hover:underline"
                                        x-text="item.idUser"></span></x-table.td>
                                <x-table.td>
                                    <div class="font-medium text-textMain-light dark:text-textMain-dark"
                                        x-text="item.desa"></div>
                                    <div class="text-[11px] text-textMuted-light mt-0.5" x-text="item.kabupaten"></div>
                                </x-table.td>
                                <x-table.td align="center">
                                    <template x-if="item.dokumen">
                                        <a :href="item.dokumen" target="_blank"
                                            class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors inline-flex items-center justify-center mx-auto cursor-pointer relative z-50"
                                            title="Lihat SK Penetapan"><i class="fa-solid fa-file-pdf"></i></a>
                                    </template>
                                    <template x-if="!item.dokumen">
                                        <span class="text-gray-400 dark:text-gray-600">-</span>
                                    </template>
                                </x-table.td>
                                <x-table.td>
                                    <span
                                        class="px-2.5 py-1 rounded-md text-[0.7rem] font-medium bg-success/10 text-success border border-success/20 w-max"
                                        x-text="item.status"></span>
                                </x-table.td>
                                <x-table.td align="center">
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
                                </x-table.td>
                            </x-table.tr>
                        </template>
                        <tr x-show="filterData(penetapanList).length === 0">
                            <x-table.td colspan="5" align="center" class="py-8 text-textMuted-light">
                                Belum ada data penetapan calon atau tidak ada hasil pencarian.
                            </x-table.td>
                        </tr>
                    </x-table.tbody>
                </table>
        </x-table.card>

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
                            <button type="button" @click="showPreviewModal = false" class="text-gray-400 hover:text-danger transition-colors cursor-pointer">
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
                                        <button @click="verifyProposal('Ditolak', '')" class="flex-1 px-4 py-2 border border-danger text-danger hover:bg-danger hover:text-white rounded-lg text-sm font-medium transition-colors flex items-center justify-center gap-2 cursor-pointer">
                                            <i class="fa-solid fa-xmark"></i> Tolak
                                        </button>
                                        <button @click="verifyProposal('Diverifikasi', '')" class="flex-1 px-4 py-2 bg-teal-light text-white rounded-lg text-sm font-medium hover:bg-teal-600 transition-colors flex items-center justify-center gap-2 cursor-pointer">
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
                        <button type="button" @click="showChecklistModal = false" class="text-gray-400 hover:text-danger transition-colors cursor-pointer">
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
                                            <button class="text-[0.7rem] font-medium text-teal-light hover:underline mt-1 flex items-center justify-between gap-1 cursor-pointer">
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
                            :class="activeVerif?.checkedDocs === activeVerif?.totalDocs ? 'bg-teal-light hover:bg-teal-600 text-white cursor-pointer' : 'bg-gray-300 dark:bg-gray-700 text-gray-500 cursor-not-allowed'"
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
                        <button type="button" @click="showVerifAdminModal = false" class="text-gray-400 hover:text-danger transition-colors cursor-pointer">
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
                                    <select x-model="formVerif.status" required class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark cursor-pointer">
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
                                <button type="button" @click="showVerifAdminModal = false" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors cursor-pointer">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-teal-light text-white rounded-lg text-sm font-medium hover:bg-teal-600 transition-colors flex items-center gap-2 cursor-pointer">
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
                        <button type="button" @click="showVerifTeknisModal = false" class="text-gray-400 hover:text-danger transition-colors cursor-pointer">
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
                                    <select x-model="formVerifTeknis.status" required class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark cursor-pointer">
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
                                    <button type="button" @click="showVerifTeknisModal = false" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors cursor-pointer">Batal</button>
                                    <button type="submit" class="px-4 py-2 bg-teal-light text-white rounded-lg text-sm font-medium hover:bg-teal-600 transition-colors flex items-center gap-2 cursor-pointer">
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
                        <button type="button" @click="showUploadBaCalonModal = false" class="text-gray-400 hover:text-danger transition-colors cursor-pointer">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <p class="text-xs text-textMuted-light dark:text-textMuted-dark leading-relaxed">Silakan unggah dokumen Berita Acara yang telah disetujui bersama.</p>

                        <div class="mt-4 p-5 rounded-xl border-2 border-dashed flex flex-col items-center justify-center text-center relative transition-colors group cursor-pointer"
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
                            <button @click="showUploadBaCalonModal = false" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors cursor-pointer">Batal</button>
                            <button @click="submitUploadBaCalon()" class="px-4 py-2 bg-teal-light text-white rounded-lg text-sm font-medium hover:bg-teal-600 transition-colors flex items-center gap-2 cursor-pointer">
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
                        <button type="button" @click="showUploadSkPenetapanModal = false" class="text-gray-400 hover:text-danger transition-colors cursor-pointer">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <p class="text-xs text-textMuted-light dark:text-textMuted-dark leading-relaxed">Silakan unggah dokumen Surat Keputusan (SK) Penetapan final.</p>

                        <div class="mt-4 p-5 rounded-xl border-2 border-dashed flex flex-col items-center justify-center text-center relative transition-colors group cursor-pointer"
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
                            <button @click="showUploadSkPenetapanModal = false" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors cursor-pointer">Batal</button>
                            <button @click="submitUploadSkPenetapan()" class="px-4 py-2 bg-teal-light text-white rounded-lg text-sm font-medium hover:bg-teal-600 transition-colors flex items-center gap-2 cursor-pointer">
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
                        <button type="button" @click="showUploadBaAktivasiModal = false" class="text-gray-400 hover:text-danger transition-colors cursor-pointer">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <p class="text-xs text-textMuted-light dark:text-textMuted-dark leading-relaxed">Silakan unggah salinan Berita Acara Aktivasi yang telah ditandatangani.</p>

                        <div class="mt-4 p-5 rounded-xl border-2 border-dashed flex flex-col items-center justify-center text-center relative transition-colors group cursor-pointer"
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
                            <button @click="showUploadBaAktivasiModal = false" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors cursor-pointer">Batal</button>
                            <button @click="submitUploadBaAktivasi()" class="px-4 py-2 bg-teal-light text-white rounded-lg text-sm font-medium hover:bg-teal-600 transition-colors flex items-center gap-2 cursor-pointer">
                                Unggah <i class="fa-solid fa-check"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODAL 4: Tampilkan Semua Data Detail (Offcanvas Full-Screen) -->
            @include('programs.knmp.master.calon-lokasi.view')

        </div>

    </div><!-- close x-data -->

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('calonLokasiManager', (initialStage) => ({
                currentStage: initialStage,
                stages: {
                    'pengajuan': { label: 'Pengajuan Proposal', icon: 'fa-file-lines' },
                    'verif-admin': { label: 'Verif Administrasi', icon: 'fa-clipboard-check' },
                    'ba-aktivasi': { label: 'BA Aktivasi', icon: 'fa-signature' },
                    'verif-teknis': { label: 'Verif Teknis Lapangan', icon: 'fa-map-location-dot' },
                    'ba-calon': { label: 'BA Calon', icon: 'fa-file-contract' },
                    'penetapan': { label: 'Penetapan Calon (SK)', icon: 'fa-award' }
                },
                switchStage(key) {
                    this.currentStage = key;
                    this.currentPage = 1;
                    this.searchQuery = '';
                    
                    const url = new URL(window.location.href);
                    url.searchParams.set('stage', key);
                    window.history.replaceState({}, '', url);
                },
                // State filter pencarian
                searchQuery: '',
                perPage: '10',
                currentPage: 1,

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
                    
                    this.$watch('perPage', () => { this.currentPage = 1; });
                    this.$watch('searchQuery', () => { this.currentPage = 1; });
                },

                filterData(list) {
                    let filtered = list;
                    if (this.searchQuery) {
                        const q = this.searchQuery.toLowerCase();
                        filtered = list.filter(item => Object.values(item).some(val => String(val).toLowerCase().includes(q)));
                    }
                    const limit = (this.perPage === 'all' || !Number(this.perPage)) ? filtered.length : Number(this.perPage);
                    const start = (this.currentPage - 1) * limit;
                    return filtered.slice(start, start + limit);
                },

                get totalPages() {
                    let list = this.currentStage === 'pengajuan' ? this.proposals :
                               (this.currentStage === 'verif-admin' ? this.verifList :
                               (this.currentStage === 'ba-aktivasi' ? this.baAktivasiList :
                               (this.currentStage === 'verif-teknis' ? this.verifTeknisList :
                               (this.currentStage === 'ba-calon' ? this.baCalonList : this.penetapanList))));
                    if (this.searchQuery) {
                        const q = this.searchQuery.toLowerCase();
                        list = list.filter(item => Object.values(item).some(val => String(val).toLowerCase().includes(q)));
                    }
                    if (this.perPage === 'all' || !Number(this.perPage)) return 1;
                    return Math.max(1, Math.ceil(list.length / Number(this.perPage)));
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

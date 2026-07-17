@extends('layouts.app')

@section('title', 'KNMP - Operasional Proyek')

@section('content')
    <div x-data="operasionalManager()">
        <div class="mb-6 flex flex-col gap-4">
            <!-- Stepper / Tabs -->
            <div class="bg-white dark:bg-bgSurface-dark rounded-2xl border border-gray-100 dark:border-gray-800 p-2">
                <div class="flex min-w-max md:w-full">
                    <template x-for="(data, key, index) in stages" :key="key">
                        <a href="#" @click.prevent="switchStage(key)" class="flex items-center group relative py-2 px-2"
                            :class="{ 'flex-1': key !== 'serah-terima' }">
                            <div class="flex items-center gap-2 relative z-10 shrink-0">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center font-medium text-xs transition-colors"
                                    :class="currentStage === key ? 'bg-teal-light text-white' :
                                        'bg-gray-100 dark:bg-gray-800 text-gray-400 group-hover:bg-teal-light/20 group-hover:text-teal-light'">
                                    <i class="fa-solid text-[10px]" :class="data.icon"></i>
                                </div>
                                <span
                                    class="font-medium text-xs transition-colors whitespace-normal max-w-[90px] leading-[1.1]"
                                    :class="currentStage === key ? 'text-teal-light' :
                                        'text-textMuted-light dark:text-textMuted-dark group-hover:text-teal-light'"
                                    x-text="data.label">
                                </span>
                            </div>
                            <template x-if="key !== 'serah-terima'">
                                <div class="flex-1 h-px bg-gray-300 dark:bg-gray-700 mx-2 min-w-[10px]"></div>
                            </template>
                            <template x-if="currentStage === key">
                                <div class="absolute inset-0 bg-teal-light/5 dark:bg-teal-600/10 rounded-xl"></div>
                            </template>
                        </a>
                    </template>
                </div>
            </div>
        </div>

        <x-table.card title="Operasional Proyek KNMP"
            description="Siklus 2: Pelaksanaan teknis dari lokasi definitif hingga serah terima pembangunan."
            :show-per-page="true" :custom-table="true">
            <x-slot name="actions">
                <div class="flex flex-wrap items-center gap-2">
                    <template x-if="currentStage === 'usulan'">
                        @can('manage-operasional')
                            <button type="button" @click="$dispatch('open-import-usulan-modal')"
                                class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 hover:bg-gray-50 text-textMain-light dark:text-textMain-dark rounded-md px-4 py-2 text-xs font-medium transition-all flex items-center justify-between gap-2 cursor-pointer shadow-sm">
                                Import Data <i class="fa-solid fa-cloud-arrow-up text-teal-light"></i> </button>
                        @endcan
                    </template>
                    <template x-if="currentStage === 'konstruksi'">
                        @can('import-progres')
                            <button type="button" @click="$dispatch('open-import-modal')"
                                class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 hover:bg-gray-50 text-textMain-light dark:text-textMain-dark rounded-md px-4 py-2 text-xs font-medium transition-all flex items-center justify-between gap-2 cursor-pointer shadow-sm">
                                Import Progres <i class="fa-solid fa-cloud-arrow-up text-teal-light"></i> </button>
                        @endcan
                    </template>
                    <template x-if="currentStage !== 'serah-terima'">
                        @can('manage-operasional')
                            <form
                                action="{{ route('program.operasional.pindah-tahap', ['program' => strtolower($activeProgram)]) }}"
                                method="POST" class="inline-block">
                                @csrf
                                <input type="hidden" name="target_stage"
                                    :value="currentStage === 'usulan' ? 'survey' :
                                        (currentStage === 'survei' ? 'ded' :
                                            (currentStage === 'ded' ? 'lelang' :
                                                (currentStage === 'lelang' ? 'konstruksi' : 'serah_terima')))">
                                <template x-for="id in selectedIds" :key="id">
                                    <input type="hidden" name="ids[]" :value="id">
                                </template>
                                <button type="submit" :disabled="selectedIds.length === 0"
                                    class="bg-teal-light hover:bg-teal-light/90 text-white rounded-md px-4 py-2 text-xs font-medium transition-all flex items-center justify-between gap-2 w-max disabled:opacity-50 disabled:cursor-not-allowed shadow-sm cursor-pointer">
                                    <span x-show="selectedIds.length > 0"
                                        class="bg-white text-teal-light rounded-full w-4 h-4 flex items-center justify-center text-[10px] font-bold mr-1"
                                        x-text="selectedIds.length"></span>
                                    Pindah Tahap <i class="fa-solid fa-arrow-right"></i>
                                </button>
                            </form>
                        @endcan
                    </template>
                </div>
            </x-slot>
            <x-slot name="search">
                <div class="relative w-full sm:w-64">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Cari proyek..."
                        class="w-full pl-8 pr-4 py-2 rounded-xl border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-xs focus:border-teal-light outline-none transition-all text-textMain-light dark:text-textMain-dark">
                </div>
            </x-slot>

            <!-- TABLE 1: USULAN -->
            <table x-show="currentStage === 'usulan'" class="w-full text-left text-xs whitespace-nowrap">
                <x-table.thead>
                    <x-table.th class="w-10 text-center">
                        <input type="checkbox" :checked="allSelected" @change="toggleAll()"
                            class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-teal-light focus:ring-teal-light dark:focus:ring-teal-light/50 cursor-pointer transition-colors">
                    </x-table.th>
                    <x-table.th>Lokasi KNMP</x-table.th>
                    <x-table.th>Status</x-table.th>
                    <x-table.th align="center">Aksi</x-table.th>
                </x-table.thead>
                <x-table.tbody>
                    <template x-for="item in paginatedData()" :key="item.id">
                        <x-table.tr x-bind:class="{ 'bg-teal-50/30 dark:bg-teal-900/10': selectedIds.includes(item.id) }">
                            <x-table.td align="center">
                                <input type="checkbox" :value="item.id" x-model="selectedIds"
                                    class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-teal-light focus:ring-teal-light dark:focus:ring-teal-light/50 cursor-pointer transition-colors">
                            </x-table.td>
                            <x-table.td>
                                <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="item.lokasi">
                                </div>
                                <div class="text-[11px] text-textMuted-light mt-0.5" x-text="item.daerah"></div>
                            </x-table.td>
                            <x-table.td>
                                <span
                                    class="px-2.5 py-1 rounded-md text-[0.7rem] font-medium bg-teal-light/10 text-teal-light"
                                    x-text="item.statusHub"></span>
                            </x-table.td>
                            <x-table.td align="center">
                                <button type="button" @click="openDetailModal(item)"
                                    class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-teal-light hover:bg-teal-light/10 transition-colors flex items-center justify-center mx-auto cursor-pointer relative z-10"
                                    title="Detail"><i class="fa-solid fa-eye pointer-events-none"></i></button>
                            </x-table.td>
                        </x-table.tr>
                    </template>
                    <x-table.tr x-show="paginatedData().length === 0">
                        <x-table.td colspan="4" class="text-center py-8 text-textMuted-light dark:text-textMuted-dark">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fa-solid fa-folder-open text-3xl text-gray-400"></i>
                                <span>Belum ada data proyek pada tahap ini...</span>
                            </div>
                        </x-table.td>
                    </x-table.tr>
                </x-table.tbody>
            </table>

            <!-- TABLE 2: SURVEI -->
            <table x-show="currentStage === 'survei'" style="display: none;"
                class="w-full text-left text-xs whitespace-nowrap">
                <x-table.thead>
                    <x-table.th class="w-10 text-center">
                        <input type="checkbox" :checked="allSelected" @change="toggleAll()"
                            class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-teal-light focus:ring-teal-light dark:focus:ring-teal-light/50 cursor-pointer transition-colors">
                    </x-table.th>
                    <x-table.th>Lokasi KNMP</x-table.th>
                    <x-table.th>Status</x-table.th>
                    <x-table.th>Koordinat</x-table.th>
                    <x-table.th align="center">Aksi</x-table.th>
                </x-table.thead>
                <x-table.tbody>
                    <template x-for="item in paginatedData()" :key="item.id">
                        <x-table.tr x-bind:class="{ 'bg-teal-50/30 dark:bg-teal-900/10': selectedIds.includes(item.id) }">
                            <x-table.td align="center">
                                <input type="checkbox" :value="item.id" x-model="selectedIds"
                                    class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-teal-light focus:ring-teal-light dark:focus:ring-teal-light/50 cursor-pointer transition-colors">
                            </x-table.td>
                            <x-table.td>
                                <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="item.lokasi">
                                </div>
                            </x-table.td>
                            <x-table.td>
                                <span
                                    class="px-2.5 py-1 rounded-md text-[0.7rem] font-medium bg-teal-light/10 text-teal-light"
                                    x-text="item.statusHub"></span>
                            </x-table.td>
                            <x-table.td class="text-textMuted-light" x-text="item.koordinat"></x-table.td>
                            <x-table.td align="center">
                                <button type="button" @click="openDetailModal(item)"
                                    class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-teal-light hover:bg-teal-light/10 transition-colors flex items-center justify-center mx-auto cursor-pointer relative z-10"
                                    title="Detail"><i class="fa-solid fa-eye pointer-events-none"></i></button>
                            </x-table.td>
                        </x-table.tr>
                    </template>
                    <x-table.tr x-show="paginatedData().length === 0">
                        <x-table.td colspan="5" class="text-center py-8 text-textMuted-light dark:text-textMuted-dark">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fa-solid fa-folder-open text-3xl text-gray-400"></i>
                                <span>Belum ada data proyek pada tahap ini...</span>
                            </div>
                        </x-table.td>
                    </x-table.tr>
                </x-table.tbody>
            </table>

            <!-- TABLE 3: DED -->
            <table x-show="currentStage === 'ded'" style="display: none;"
                class="w-full text-left text-xs whitespace-nowrap">
                <x-table.thead>
                    <x-table.th class="w-10 text-center">
                        <input type="checkbox" :checked="allSelected" @change="toggleAll()"
                            class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-teal-light focus:ring-teal-light dark:focus:ring-teal-light/50 cursor-pointer transition-colors">
                    </x-table.th>
                    <x-table.th>Lokasi KNMP</x-table.th>
                    <x-table.th>Status</x-table.th>
                    <x-table.th align="center">Dokumen DED</x-table.th>
                    <x-table.th align="center">Aksi</x-table.th>
                </x-table.thead>
                <x-table.tbody>
                    <template x-for="item in paginatedData()" :key="item.id">
                        <x-table.tr x-bind:class="{ 'bg-teal-50/30 dark:bg-teal-900/10': selectedIds.includes(item.id) }">
                            <x-table.td align="center">
                                <input type="checkbox" :value="item.id" x-model="selectedIds"
                                    class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-teal-light focus:ring-teal-light dark:focus:ring-teal-light/50 cursor-pointer transition-colors">
                            </x-table.td>
                            <x-table.td>
                                <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="item.lokasi">
                                </div>
                            </x-table.td>
                            <x-table.td>
                                <span
                                    class="px-2.5 py-1 rounded-md text-[0.7rem] font-medium bg-teal-light/10 text-teal-light"
                                    x-text="item.statusHub"></span>
                            </x-table.td>
                            <x-table.td align="center">
                                <button type="button" @click="openPreviewDedModal(item)"
                                    class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors flex items-center justify-center mx-auto cursor-pointer relative z-10"
                                    title="Lihat DED"><i class="fa-solid fa-file-pdf pointer-events-none"></i></button>
                            </x-table.td>
                            <x-table.td align="center">
                                <button type="button" @click="openDetailModal(item)"
                                    class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-teal-light hover:bg-teal-light/10 transition-colors flex items-center justify-center mx-auto cursor-pointer relative z-10"
                                    title="Detail"><i class="fa-solid fa-eye pointer-events-none"></i></button>
                            </x-table.td>
                        </x-table.tr>
                    </template>
                    <x-table.tr x-show="paginatedData().length === 0">
                        <x-table.td colspan="5" class="text-center py-8 text-textMuted-light dark:text-textMuted-dark">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fa-solid fa-folder-open text-3xl text-gray-400"></i>
                                <span>Belum ada data proyek pada tahap ini...</span>
                            </div>
                        </x-table.td>
                    </x-table.tr>
                </x-table.tbody>
            </table>

            <!-- TABLE 4: SIAP LELANG -->
            <table x-show="currentStage === 'lelang'" style="display: none;"
                class="w-full text-left text-xs whitespace-nowrap">
                <x-table.thead>
                    <x-table.th class="w-10 text-center">
                        <input type="checkbox" :checked="allSelected" @change="toggleAll()"
                            class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-teal-light focus:ring-teal-light dark:focus:ring-teal-light/50 cursor-pointer transition-colors">
                    </x-table.th>
                    <x-table.th>Lokasi KNMP</x-table.th>
                    <x-table.th>Status</x-table.th>
                    <x-table.th>Nama Konstruksi</x-table.th>
                    <x-table.th align="center">Dokumen</x-table.th>
                    <x-table.th align="center">Aksi</x-table.th>
                </x-table.thead>
                <x-table.tbody>
                    <template x-for="item in paginatedData()" :key="item.id">
                        <x-table.tr x-bind:class="{ 'bg-teal-50/30 dark:bg-teal-900/10': selectedIds.includes(item.id) }">
                            <x-table.td align="center">
                                <input type="checkbox" :value="item.id" x-model="selectedIds"
                                    class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-teal-light focus:ring-teal-light dark:focus:ring-teal-light/50 cursor-pointer transition-colors">
                            </x-table.td>
                            <x-table.td>
                                <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="item.lokasi">
                                </div>
                            </x-table.td>
                            <x-table.td>
                                <span
                                    class="px-2.5 py-1 rounded-md text-[0.7rem] font-medium bg-teal-light/10 text-teal-light"
                                    x-text="item.statusHub"></span>
                            </x-table.td>
                            <x-table.td class="text-textMuted-light" x-text="item.namaKonstruksi"></x-table.td>
                            <x-table.td align="center">
                                <button type="button" @click="openPreviewDedModal(item)"
                                    class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors flex items-center justify-center mx-auto cursor-pointer relative z-10"
                                    title="Dokumen Lelang"><i
                                        class="fa-solid fa-file-pdf pointer-events-none"></i></button>
                            </x-table.td>
                            <x-table.td align="center">
                                <button type="button" @click="openDetailModal(item)"
                                    class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-teal-light hover:bg-teal-light/10 transition-colors flex items-center justify-center mx-auto cursor-pointer relative z-10"
                                    title="Detail"><i class="fa-solid fa-eye pointer-events-none"></i></button>
                            </x-table.td>
                        </x-table.tr>
                    </template>
                    <x-table.tr x-show="paginatedData().length === 0">
                        <x-table.td colspan="6" class="text-center py-8 text-textMuted-light dark:text-textMuted-dark">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fa-solid fa-folder-open text-3xl text-gray-400"></i>
                                <span>Belum ada data proyek pada tahap ini...</span>
                            </div>
                        </x-table.td>
                    </x-table.tr>
                </x-table.tbody>
            </table>

            <!-- TABLE 5: KONSTRUKSI -->
            <table x-show="currentStage === 'konstruksi'" style="display: none;"
                class="w-full text-left text-xs whitespace-nowrap">
                <x-table.thead>
                    <x-table.th class="w-10 text-center">
                        <input type="checkbox" :checked="allSelected" @change="toggleAll()"
                            class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-teal-light focus:ring-teal-light dark:focus:ring-teal-light/50 cursor-pointer transition-colors">
                    </x-table.th>
                    <x-table.th>Lokasi KNMP</x-table.th>
                    <x-table.th>Status</x-table.th>
                    <x-table.th>Konstruktor (Vendor)</x-table.th>
                    <x-table.th>Rencana</x-table.th>
                    <x-table.th>Progres & Deviasi</x-table.th>
                    <x-table.th align="center">Aksi</x-table.th>
                </x-table.thead>
                <x-table.tbody>
                    <template x-for="item in paginatedData()" :key="item.id">
                        <x-table.tr x-bind:class="{ 'bg-teal-50/30 dark:bg-teal-900/10': selectedIds.includes(item.id) }">
                            <x-table.td align="center">
                                <input type="checkbox" :value="item.id" x-model="selectedIds"
                                    class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-teal-light focus:ring-teal-light dark:focus:ring-teal-light/50 cursor-pointer transition-colors">
                            </x-table.td>
                            <x-table.td>
                                <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="item.lokasi">
                                </div>
                            </x-table.td>
                            <x-table.td>
                                <span
                                    class="px-2.5 py-1 rounded-md text-[0.7rem] font-medium bg-teal-light/10 text-teal-light"
                                    x-text="item.statusHub"></span>
                            </x-table.td>
                            <x-table.td>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-teal-100 text-teal-light flex items-center justify-center text-[10px] font-bold"
                                        x-text="(item.konstruktor || '-').substring(0, 2)"></div>
                                    <span class="font-medium" x-text="item.konstruktor"></span>
                                </div>
                            </x-table.td>
                            <x-table.td>
                                <div class="font-medium text-textMain-light dark:text-textMain-dark"
                                    x-text="formatDec(item.rencana) + '%'"></div>
                                <div class="text-[0.65rem] text-textMuted-light mt-0.5">Kumulatif Minggu Ini</div>
                            </x-table.td>
                            <x-table.td>
                                <div class="flex flex-col gap-1.5 w-48">
                                    <div class="flex justify-between items-end">
                                        <span class="font-medium text-xs" x-text="formatDec(item.progres) + '%'"></span>
                                        <template x-if="item.deviasi >= 0">
                                            <span
                                                class="text-success font-medium text-[0.65rem] flex items-center gap-1 bg-success/10 px-1.5 py-0.5 rounded"><i
                                                    class="fa-solid fa-arrow-up"></i> +<span
                                                    x-text="formatDec(item.deviasi)"></span>%</span>
                                        </template>
                                        <template x-if="item.deviasi < 0">
                                            <span
                                                class="text-danger font-medium text-[0.65rem] flex items-center gap-1 bg-danger/10 px-1.5 py-0.5 rounded"><i
                                                    class="fa-solid fa-arrow-down"></i> <span
                                                    x-text="formatDec(item.deviasi)"></span>%</span>
                                        </template>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                        <div class="bg-teal-light h-1.5 rounded-full"
                                            :style="'width: ' + item.progres + '%'"></div>
                                    </div>
                                </div>
                            </x-table.td>
                            <x-table.td align="center">
                                <div class="flex items-center justify-center gap-2">
                                    @can('manage-operasional')
                                        <button type="button" @click="$dispatch('open-upload-modal', { item: item })"
                                            class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors flex items-center justify-center relative z-10 cursor-pointer"
                                            title="Upload Foto Progres"><i
                                                class="fa-solid fa-camera pointer-events-none"></i></button>
                                    @endcan
                                    <button type="button" @click="openDetailModal(item)"
                                        class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-teal-light hover:bg-teal-light/10 transition-colors flex items-center justify-center cursor-pointer relative z-10"
                                        title="Detail"><i class="fa-solid fa-eye pointer-events-none"></i></button>
                                </div>
                            </x-table.td>
                        </x-table.tr>
                    </template>
                    <x-table.tr x-show="paginatedData().length === 0">
                        <x-table.td colspan="7" class="text-center py-8 text-textMuted-light dark:text-textMuted-dark">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fa-solid fa-folder-open text-3xl text-gray-400"></i>
                                <span>Belum ada data proyek pada tahap ini...</span>
                            </div>
                        </x-table.td>
                    </x-table.tr>
                </x-table.tbody>
            </table>

            <!-- TABLE 6: SERAH TERIMA -->
            <table x-show="currentStage === 'serah-terima'" style="display: none;"
                class="w-full text-left text-xs whitespace-nowrap">
                <x-table.thead>
                    <x-table.th class="w-10 text-center">
                        <input type="checkbox" :checked="allSelected" @change="toggleAll()"
                            class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-teal-light focus:ring-teal-light dark:focus:ring-teal-light/50 cursor-pointer transition-colors">
                    </x-table.th>
                    <x-table.th>Lokasi KNMP</x-table.th>
                    <x-table.th>Status</x-table.th>
                    <x-table.th align="center">Dokumen</x-table.th>
                    <x-table.th align="center">Aksi</x-table.th>
                </x-table.thead>
                <x-table.tbody>
                    <template x-for="item in paginatedData()" :key="item.id">
                        <x-table.tr x-bind:class="{ 'bg-teal-50/30 dark:bg-teal-900/10': selectedIds.includes(item.id) }">
                            <x-table.td align="center">
                                <input type="checkbox" :value="item.id" x-model="selectedIds"
                                    class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-teal-light focus:ring-teal-light dark:focus:ring-teal-light/50 cursor-pointer transition-colors">
                            </x-table.td>
                            <x-table.td>
                                <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="item.lokasi">
                                </div>
                            </x-table.td>
                            <x-table.td>
                                <span
                                    class="px-2.5 py-1 rounded-md text-[0.7rem] font-medium bg-teal-light/10 text-teal-light"
                                    x-text="item.statusHub"></span>
                            </x-table.td>
                            <x-table.td align="center">
                                <button type="button" @click="openPreviewDedModal(item)"
                                    class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors flex items-center justify-center mx-auto cursor-pointer relative z-10"
                                    title="Dokumen BAST"><i
                                        class="fa-solid fa-file-contract pointer-events-none"></i></button>
                            </x-table.td>
                            <x-table.td align="center">
                                <button type="button" @click="openDetailModal(item)"
                                    class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-teal-light hover:bg-teal-light/10 transition-colors flex items-center justify-center mx-auto cursor-pointer relative z-10"
                                    title="Detail"><i class="fa-solid fa-eye pointer-events-none"></i></button>
                            </x-table.td>
                        </x-table.tr>
                    </template>
                    <x-table.tr x-show="paginatedData().length === 0">
                        <x-table.td colspan="5" class="text-center py-8 text-textMuted-light dark:text-textMuted-dark">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fa-solid fa-folder-open text-3xl text-gray-400"></i>
                                <span>Belum ada data proyek pada tahap ini...</span>
                            </div>
                        </x-table.td>
                    </x-table.tr>
                </x-table.tbody>
            </table>

            <x-slot name="paginationSlot">
                <div class="flex gap-1" x-show="totalPages() >= 1">
                    <button @click="currentPage = Math.max(1, currentPage - 1)" :disabled="currentPage === 1"
                        class="w-8 h-8 rounded-md border border-gray-100 dark:border-gray-700 flex items-center justify-center text-gray-400 disabled:opacity-30 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    </button>

                    <template x-for="page in visiblePages()" :key="page">
                        <button @click="if(page !== '...') currentPage = page"
                            class="w-8 h-8 rounded-md font-medium text-xs flex items-center justify-center transition-colors"
                            :class="page === currentPage ? 'bg-teal-light text-white' : (page === '...' ?
                                'cursor-default text-gray-400' :
                                'hover:bg-gray-100 dark:hover:bg-gray-800 text-textMain-light dark:text-textMain-dark'
                                )"
                            x-text="page">
                        </button>
                    </template>

                    <button @click="currentPage = Math.min(totalPages(), currentPage + 1)"
                        :disabled="currentPage === totalPages()"
                        class="w-8 h-8 rounded-md border border-gray-100 dark:border-gray-700 flex items-center justify-center text-gray-400 disabled:opacity-30 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </button>
                </div>
            </x-slot>
        </x-table.card>

        <!-- Modal Upload Foto -->
        <template x-teleport="body">
            <div x-data="{ isUploadModalOpen: false, uploadItem: null }"
                @open-upload-modal.window="uploadItem = $event.detail.item; isUploadModalOpen = true"
                x-show="isUploadModalOpen" class="fixed inset-0 overflow-y-auto" style="display: none; z-index: 99999;"
                aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                    <!-- Background overlay -->
                    <div x-show="isUploadModalOpen" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" aria-hidden="true"></div>

                    <!-- Modal panel -->
                    <div x-show="isUploadModalOpen" @click.away="isUploadModalOpen = false"
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="relative inline-block w-full max-w-2xl overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-900 shadow-2xl rounded-3xl border border-gray-100 dark:border-gray-800 font-sans"
                        style="font-family: 'Inter', sans-serif;">

                        <!-- Fixed Clean Header -->
                        <div
                            class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-white dark:bg-gray-900">
                            <div class="flex items-center gap-2.5">
                                <div
                                    class="w-8 h-8 rounded-lg bg-teal-light/10 text-teal-light flex items-center justify-center text-sm font-semibold">
                                    <i class="fa-solid fa-camera"></i>
                                </div>
                                <h3 class="text-base font-semibold tracking-tight text-textMain-light dark:text-textMain-dark"
                                    id="modal-title">
                                    Upload Dokumentasi Proyek
                                </h3>
                            </div>
                            <button type="button" @click="isUploadModalOpen = false"
                                class="text-gray-400 hover:text-danger p-1.5 rounded-lg transition-colors focus:outline-none cursor-pointer"
                                title="Tutup">
                                <i class="fa-solid fa-xmark text-lg"></i>
                            </button>
                        </div>

                        <div class="p-6 overflow-y-auto max-h-[82vh]">
                            <!-- Top Project Info Banner -->
                            <div
                                class="p-3.5 rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800 flex items-center gap-3 mb-6">
                                <div
                                    class="w-9 h-9 rounded-lg bg-teal-light/10 text-teal-light flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-location-dot text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0 text-left">
                                    <div class="text-xs font-semibold text-textMain-light dark:text-textMain-dark truncate"
                                        x-text="uploadItem?.lokasi || 'Proyek KNMP'"></div>
                                    <div class="text-[11px] text-textMuted-light truncate mt-0.5"
                                        x-text="uploadItem?.daerah || 'Lokasi Kegiatan'"></div>
                                </div>
                                <div
                                    class="shrink-0 text-[11px] font-medium text-teal-light bg-teal-light/10 px-2.5 py-1 rounded-md">
                                    Bukti Progres Fisik
                                </div>
                            </div>

                            <form
                                action="{{ route('program.operasional.upload-foto', ['program' => strtolower($activeProgram)]) }}"
                                method="POST" enctype="multipart/form-data" class="w-full">
                                @csrf
                                <input type="hidden" name="knmp_id" :value="uploadItem?.id">

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                                    <!-- Kolom Upload Before (0% - Sebelum) -->
                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between pb-2.5 border-b border-gray-100 dark:border-gray-800">
                                            <div class="flex items-center gap-2">
                                                <div class="w-2 h-2 rounded-full bg-red-500"></div>
                                                <span class="text-xs font-semibold text-textMain-light dark:text-textMain-dark tracking-tight">Kondisi 0% (Before)</span>
                                            </div>
                                            <span class="text-[10px] text-textMuted-light">Maks. 2 Foto</span>
                                        </div>
                                        
                                        <!-- Slot Before 1 -->
                                        <div x-data="{
                                            imgPreview: null,
                                            get savedUrl() { return uploadItem?.fotosBefore?.[0]?.url || null; },
                                            get displayUrl() { return this.imgPreview || this.savedUrl; },
                                            fileChange(e) {
                                                const file = e.target.files[0];
                                                if (file) {
                                                    const reader = new FileReader();
                                                    reader.onload = (event) => { this.imgPreview = event.target.result; };
                                                    reader.readAsDataURL(file);
                                                } else {
                                                    this.imgPreview = null;
                                                }
                                            }
                                        }" class="space-y-1.5">
                                            <div class="flex items-center justify-between">
                                                <label class="text-[11px] font-medium text-textMain-light dark:text-textMain-dark">Foto Before 1</label>
                                                <template x-if="displayUrl">
                                                    <span class="text-[10px] font-medium" :class="imgPreview ? 'text-amber-500' : 'text-teal-light'" x-text="imgPreview ? 'Siap Upload' : 'Tersimpan'"></span>
                                                </template>
                                            </div>

                                            <div style="height: 120px; min-height: 120px;" class="relative w-full rounded-xl overflow-hidden group transition-all flex items-center justify-center bg-gray-50/60 dark:bg-gray-800/30"
                                                :class="displayUrl ? 'border border-gray-200/80 dark:border-gray-800 shadow-2xs' : 'border border-dashed border-gray-300 dark:border-gray-700 hover:border-teal-light dark:hover:border-teal-light'">
                                                
                                                <template x-if="displayUrl">
                                                    <div class="absolute inset-0 w-full h-full z-0">
                                                        <img :src="displayUrl" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                                        <div class="absolute inset-0 bg-gray-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white p-2 text-center backdrop-blur-[2px]">
                                                            <i class="fa-solid fa-camera-rotate text-base mb-1"></i>
                                                            <span class="text-[11px] font-medium" x-text="imgPreview ? 'Ganti Pilihan Foto' : 'Klik Upload Ulang / Ganti'"></span>
                                                            <span class="text-[9px] text-gray-300 mt-0.5">JPG/PNG (Maks. 2MB)</span>
                                                        </div>
                                                    </div>
                                                </template>

                                                <template x-if="!displayUrl">
                                                    <div class="w-full h-full flex flex-col items-center justify-center p-3 text-center pointer-events-none">
                                                        <div class="w-8 h-8 rounded-lg bg-white dark:bg-gray-800 shadow-2xs border border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-400 group-hover:text-teal-light transition-colors mb-1.5">
                                                            <i class="fa-solid fa-cloud-arrow-up text-sm"></i>
                                                        </div>
                                                        <span class="text-[11px] font-medium text-textMain-light dark:text-textMain-dark group-hover:text-teal-light transition-colors">Upload Foto Before 1</span>
                                                        <span class="text-[9px] text-textMuted-light mt-0.5">JPG / PNG (Maks. 2MB)</span>
                                                    </div>
                                                </template>

                                                <input type="file" name="foto_before_0" id="foto_before_0" accept="image/jpeg, image/png, image/jpg" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" @change="fileChange($event)">
                                            </div>
                                        </div>

                                        <!-- Slot Before 2 -->
                                        <div x-data="{
                                            imgPreview: null,
                                            get savedUrl() { return uploadItem?.fotosBefore?.[1]?.url || null; },
                                            get displayUrl() { return this.imgPreview || this.savedUrl; },
                                            fileChange(e) {
                                                const file = e.target.files[0];
                                                if (file) {
                                                    const reader = new FileReader();
                                                    reader.onload = (event) => { this.imgPreview = event.target.result; };
                                                    reader.readAsDataURL(file);
                                                } else {
                                                    this.imgPreview = null;
                                                }
                                            }
                                        }" class="space-y-1.5 pt-1">
                                            <div class="flex items-center justify-between">
                                                <label class="text-[11px] font-medium text-textMain-light dark:text-textMain-dark">Foto Before 2</label>
                                                <template x-if="displayUrl">
                                                    <span class="text-[10px] font-medium" :class="imgPreview ? 'text-amber-500' : 'text-teal-light'" x-text="imgPreview ? 'Siap Upload' : 'Tersimpan'"></span>
                                                </template>
                                            </div>

                                            <div style="height: 120px; min-height: 120px;" class="relative w-full rounded-xl overflow-hidden group transition-all flex items-center justify-center bg-gray-50/60 dark:bg-gray-800/30"
                                                :class="displayUrl ? 'border border-gray-200/80 dark:border-gray-800 shadow-2xs' : 'border border-dashed border-gray-300 dark:border-gray-700 hover:border-teal-light dark:hover:border-teal-light'">
                                                
                                                <template x-if="displayUrl">
                                                    <div class="absolute inset-0 w-full h-full z-0">
                                                        <img :src="displayUrl" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                                        <div class="absolute inset-0 bg-gray-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white p-2 text-center backdrop-blur-[2px]">
                                                            <i class="fa-solid fa-camera-rotate text-base mb-1"></i>
                                                            <span class="text-[11px] font-medium" x-text="imgPreview ? 'Ganti Pilihan Foto' : 'Klik Upload Ulang / Ganti'"></span>
                                                            <span class="text-[9px] text-gray-300 mt-0.5">JPG/PNG (Maks. 2MB)</span>
                                                        </div>
                                                    </div>
                                                </template>

                                                <template x-if="!displayUrl">
                                                    <div class="w-full h-full flex flex-col items-center justify-center p-3 text-center pointer-events-none">
                                                        <div class="w-8 h-8 rounded-lg bg-white dark:bg-gray-800 shadow-2xs border border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-400 group-hover:text-teal-light transition-colors mb-1.5">
                                                            <i class="fa-solid fa-cloud-arrow-up text-sm"></i>
                                                        </div>
                                                        <span class="text-[11px] font-medium text-textMain-light dark:text-textMain-dark group-hover:text-teal-light transition-colors">Upload Foto Before 2</span>
                                                        <span class="text-[9px] text-textMuted-light mt-0.5">JPG / PNG (Maks. 2MB)</span>
                                                    </div>
                                                </template>

                                                <input type="file" name="foto_before_1" id="foto_before_1" accept="image/jpeg, image/png, image/jpg" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" @change="fileChange($event)">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Kolom Upload After (Progres Saat Ini / After) -->
                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between pb-2.5 border-b border-gray-100 dark:border-gray-800">
                                            <div class="flex items-center gap-2">
                                                <div class="w-2 h-2 rounded-full bg-teal-light"></div>
                                                <span class="text-xs font-semibold text-textMain-light dark:text-textMain-dark tracking-tight">Progres / Kondisi After</span>
                                            </div>
                                            <span class="text-[10px] text-teal-light font-medium">Maks. 2 Foto</span>
                                        </div>
                                        
                                        <!-- Slot After 1 -->
                                        <div x-data="{
                                            imgPreview: null,
                                            get savedUrl() { return uploadItem?.fotosAfter?.[0]?.url || null; },
                                            get displayUrl() { return this.imgPreview || this.savedUrl; },
                                            fileChange(e) {
                                                const file = e.target.files[0];
                                                if (file) {
                                                    const reader = new FileReader();
                                                    reader.onload = (event) => { this.imgPreview = event.target.result; };
                                                    reader.readAsDataURL(file);
                                                } else {
                                                    this.imgPreview = null;
                                                }
                                            }
                                        }" class="space-y-1.5">
                                            <div class="flex items-center justify-between">
                                                <label class="text-[11px] font-medium text-textMain-light dark:text-textMain-dark">Foto After 1</label>
                                                <template x-if="displayUrl">
                                                    <span class="text-[10px] font-medium" :class="imgPreview ? 'text-amber-500' : 'text-teal-light'" x-text="imgPreview ? 'Siap Upload' : 'Tersimpan'"></span>
                                                </template>
                                            </div>

                                            <div style="height: 120px; min-height: 120px;" class="relative w-full rounded-xl overflow-hidden group transition-all flex items-center justify-center bg-teal-50/20 dark:bg-teal-900/10"
                                                :class="displayUrl ? 'border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'border border-dashed border-teal-300/80 dark:border-teal-800/80 hover:border-teal-light dark:hover:border-teal-light'">
                                                
                                                <template x-if="displayUrl">
                                                    <div class="absolute inset-0 w-full h-full z-0">
                                                        <img :src="displayUrl" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                                        <div class="absolute inset-0 bg-gray-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white p-2 text-center backdrop-blur-[2px]">
                                                            <i class="fa-solid fa-camera-rotate text-base mb-1"></i>
                                                            <span class="text-[11px] font-medium" x-text="imgPreview ? 'Ganti Pilihan Foto' : 'Klik Upload Ulang / Ganti'"></span>
                                                            <span class="text-[9px] text-gray-300 mt-0.5">JPG/PNG (Maks. 2MB)</span>
                                                        </div>
                                                    </div>
                                                </template>

                                                <template x-if="!displayUrl">
                                                    <div class="w-full h-full flex flex-col items-center justify-center p-3 text-center pointer-events-none">
                                                        <div class="w-8 h-8 rounded-lg bg-white dark:bg-gray-800 shadow-2xs border border-teal-200 dark:border-teal-800 flex items-center justify-center text-gray-400 group-hover:text-teal-light transition-colors mb-1.5">
                                                            <i class="fa-solid fa-cloud-arrow-up text-sm"></i>
                                                        </div>
                                                        <span class="text-[11px] font-medium text-textMain-light dark:text-textMain-dark group-hover:text-teal-light transition-colors">Upload Foto After 1</span>
                                                        <span class="text-[9px] text-textMuted-light mt-0.5">JPG / PNG (Maks. 2MB)</span>
                                                    </div>
                                                </template>

                                                <input type="file" name="foto_after_0" id="foto_after_0" accept="image/jpeg, image/png, image/jpg" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" @change="fileChange($event)">
                                            </div>
                                        </div>

                                        <!-- Slot After 2 -->
                                        <div x-data="{
                                            imgPreview: null,
                                            get savedUrl() { return uploadItem?.fotosAfter?.[1]?.url || null; },
                                            get displayUrl() { return this.imgPreview || this.savedUrl; },
                                            fileChange(e) {
                                                const file = e.target.files[0];
                                                if (file) {
                                                    const reader = new FileReader();
                                                    reader.onload = (event) => { this.imgPreview = event.target.result; };
                                                    reader.readAsDataURL(file);
                                                } else {
                                                    this.imgPreview = null;
                                                }
                                            }
                                        }" class="space-y-1.5 pt-1">
                                            <div class="flex items-center justify-between">
                                                <label class="text-[11px] font-medium text-textMain-light dark:text-textMain-dark">Foto After 2</label>
                                                <template x-if="displayUrl">
                                                    <span class="text-[10px] font-medium" :class="imgPreview ? 'text-amber-500' : 'text-teal-light'" x-text="imgPreview ? 'Siap Upload' : 'Tersimpan'"></span>
                                                </template>
                                            </div>

                                            <div style="height: 120px; min-height: 120px;" class="relative w-full rounded-xl overflow-hidden group transition-all flex items-center justify-center bg-teal-50/20 dark:bg-teal-900/10"
                                                :class="displayUrl ? 'border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'border border-dashed border-teal-300/80 dark:border-teal-800/80 hover:border-teal-light dark:hover:border-teal-light'">
                                                
                                                <template x-if="displayUrl">
                                                    <div class="absolute inset-0 w-full h-full z-0">
                                                        <img :src="displayUrl" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                                        <div class="absolute inset-0 bg-gray-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white p-2 text-center backdrop-blur-[2px]">
                                                            <i class="fa-solid fa-camera-rotate text-base mb-1"></i>
                                                            <span class="text-[11px] font-medium" x-text="imgPreview ? 'Ganti Pilihan Foto' : 'Klik Upload Ulang / Ganti'"></span>
                                                            <span class="text-[9px] text-gray-300 mt-0.5">JPG/PNG (Maks. 2MB)</span>
                                                        </div>
                                                    </div>
                                                </template>

                                                <template x-if="!displayUrl">
                                                    <div class="w-full h-full flex flex-col items-center justify-center p-3 text-center pointer-events-none">
                                                        <div class="w-8 h-8 rounded-lg bg-white dark:bg-gray-800 shadow-2xs border border-teal-200 dark:border-teal-800 flex items-center justify-center text-gray-400 group-hover:text-teal-light transition-colors mb-1.5">
                                                            <i class="fa-solid fa-cloud-arrow-up text-sm"></i>
                                                        </div>
                                                        <span class="text-[11px] font-medium text-textMain-light dark:text-textMain-dark group-hover:text-teal-light transition-colors">Upload Foto After 2</span>
                                                        <span class="text-[9px] text-textMuted-light mt-0.5">JPG / PNG (Maks. 2MB)</span>
                                                    </div>
                                                </template>

                                                <input type="file" name="foto_after_1" id="foto_after_1" accept="image/jpeg, image/png, image/jpg" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" @change="fileChange($event)">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex justify-end gap-3 pt-5 border-t border-gray-100 dark:border-gray-800">
                                    <button type="button" @click="isUploadModalOpen = false"
                                        class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark rounded-lg text-xs font-semibold hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors cursor-pointer flex items-center gap-1.5">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-teal-light text-white rounded-lg text-xs font-semibold hover:bg-teal-light/90 transition-colors flex items-center gap-1.5 cursor-pointer shadow-xs">
                                        <i class="fa-solid fa-cloud-arrow-up text-sm"></i> Simpan Dokumentasi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <!-- Modal Import -->
        <template x-teleport="body">
            <div x-data="{ isImportModalOpen: false }" @open-import-modal.window="isImportModalOpen = true" x-show="isImportModalOpen"
                style="display: none; z-index: 99999;"
                class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

                <div @click.away="isImportModalOpen = false" x-show="isImportModalOpen"
                    x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-200 transform"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                    class="bg-white dark:bg-gray-900 rounded-2xl w-full max-w-md p-6 shadow-2xl border border-gray-100 dark:border-gray-800 relative mx-4 max-h-[90vh] overflow-y-auto">

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-textMain-light dark:text-textMain-dark">Import Progres Fisik
                        </h3>
                        <button type="button" @click="isImportModalOpen = false"
                            class="text-gray-400 hover:text-danger transition-colors">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <p class="text-xs text-textMuted-light dark:text-textMuted-dark leading-relaxed">Unggah file Excel
                            (Template) untuk memperbarui data progres harian lokasi tahap konstruksi sekaligus.</p>

                        <a href="{{ route('program.operasional.template-progres', ['program' => strtolower($activeProgram)]) }}"
                            class="w-full justify-center rounded-lg border border-gray-200 dark:border-gray-700 px-4 py-2.5 bg-gray-50 dark:bg-gray-800/50 text-xs font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition-all duration-200 relative z-10 flex items-center gap-2">
                            <i class="fa-solid fa-download text-teal-light text-sm"></i> Download Template Excel
                        </a>

                        <form
                            action="{{ route('program.operasional.import-progres', ['program' => strtolower($activeProgram)]) }}"
                            method="POST" enctype="multipart/form-data" class="w-full">
                            @csrf
                            <div x-data="{ fileName: '' }" class="w-full mb-6">
                                <label
                                    class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Upload
                                    File Excel <span class="text-danger">*</span></label>
                                <label
                                    class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800/50 hover:bg-teal-light/5 hover:border-teal-light transition-colors cursor-pointer relative z-10">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2"></i>
                                        <p class="mb-1 text-sm text-textMuted-light dark:text-textMuted-dark"><span
                                                class="font-medium text-teal-light">Klik untuk upload</span> atau drag and
                                            drop</p>
                                        <p class="text-xs text-gray-500">Excel (Maks. 5MB)</p>
                                    </div>
                                    <input type="file" name="file_excel" class="hidden" required accept=".xlsx, .xls"
                                        @change="fileName = $event.target.files.length ? $event.target.files[0].name : ''" />
                                </label>
                                <p x-show="fileName" class="text-sm text-success mt-2 font-medium text-center"
                                    x-text="`File terpilih: ${fileName}`"></p>
                            </div>

                            <div class="mt-8 flex justify-end gap-3">
                                <button type="button" @click="isImportModalOpen = false"
                                    class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors cursor-pointer flex items-center gap-2">
                                    <i class="fa-solid fa-xmark"></i> <span>Batal</span>
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 bg-teal-light text-white rounded-lg text-sm font-medium hover:bg-teal-light/90 transition-colors flex items-center gap-2 cursor-pointer shadow-sm">
                                    <i class="fa-solid fa-cloud-arrow-up"></i> <span>Import Data</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>

        <!-- Modal Import Usulan -->
        <template x-teleport="body">
            <div x-data="{ isImportUsulanModalOpen: false }" @open-import-usulan-modal.window="isImportUsulanModalOpen = true"
                x-show="isImportUsulanModalOpen" style="display: none; z-index: 99999;"
                class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

                <div @click.away="isImportUsulanModalOpen = false" x-show="isImportUsulanModalOpen"
                    x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-200 transform"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                    class="bg-white dark:bg-gray-900 rounded-2xl w-full max-w-md p-6 shadow-2xl border border-gray-100 dark:border-gray-800 relative mx-4 max-h-[90vh] overflow-y-auto">

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-textMain-light dark:text-textMain-dark">Import Data Usulan
                        </h3>
                        <button type="button" @click="isImportUsulanModalOpen = false"
                            class="text-gray-400 hover:text-danger transition-colors">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <p class="text-xs text-textMuted-light dark:text-textMuted-dark leading-relaxed">Unggah file Excel
                            (Template) untuk menambahkan data lokasi usulan baru ke dalam sistem.</p>

                        <a href="{{ route('program.operasional.template-usulan', ['program' => strtolower($activeProgram)]) }}"
                            class="w-full justify-center rounded-lg border border-gray-200 dark:border-gray-700 px-4 py-2.5 bg-gray-50 dark:bg-gray-800/50 text-xs font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition-all duration-200 relative z-10 flex items-center gap-2">
                            <i class="fa-solid fa-download text-teal-light text-sm"></i> Download Template Excel
                        </a>

                        <form
                            action="{{ route('program.operasional.import-usulan', ['program' => strtolower($activeProgram)]) }}"
                            method="POST" enctype="multipart/form-data" class="w-full">
                            @csrf
                            <div x-data="{ fileName: '' }" class="w-full mb-6">
                                <label
                                    class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Upload
                                    File Excel <span class="text-danger">*</span></label>
                                <label
                                    class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800/50 hover:bg-teal-light/5 hover:border-teal-light transition-colors cursor-pointer relative z-10">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2"></i>
                                        <p class="mb-1 text-sm text-textMuted-light dark:text-textMuted-dark"><span
                                                class="font-medium text-teal-light">Klik untuk upload</span> atau drag and
                                            drop</p>
                                        <p class="text-xs text-gray-500">Excel (Maks. 5MB)</p>
                                    </div>
                                    <input type="file" name="file_excel" class="hidden" required accept=".xlsx, .xls"
                                        @change="fileName = $event.target.files.length ? $event.target.files[0].name : ''" />
                                </label>
                                <p x-show="fileName" class="text-sm text-success mt-2 font-medium text-center"
                                    x-text="`File terpilih: ${fileName}`"></p>
                            </div>

                            <div class="mt-8 flex justify-end gap-3">
                                <button type="button" @click="isImportUsulanModalOpen = false"
                                    class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors cursor-pointer flex items-center gap-2">
                                    <i class="fa-solid fa-xmark"></i> <span>Batal</span>
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 bg-teal-light text-white rounded-lg text-sm font-medium hover:bg-teal-light/90 transition-colors flex items-center gap-2 cursor-pointer shadow-sm">
                                    <i class="fa-solid fa-cloud-arrow-up"></i> <span>Import Data</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>

        <!-- Modal Detail & Preview DED -->
        @include('programs.knmp.operasional.view')

        <!-- MODAL: Preview Dokumen DED -->
        <template x-teleport="body">
            <div x-show="showPreviewDedModal" class="fixed inset-0 overflow-y-auto"
                style="display: none; z-index: 999999;" aria-labelledby="modal-ded-title" role="dialog"
                aria-modal="true">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                    <!-- Background overlay -->
                    <div x-show="showPreviewDedModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="fixed inset-0 transition-opacity bg-gray-900/70 backdrop-blur-sm" aria-hidden="true"
                        @click="showPreviewDedModal = false; activeDed = null"></div>

                    <!-- Modal panel -->
                    <div x-show="showPreviewDedModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="relative inline-block w-full max-w-5xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-bgSurface-dark shadow-2xl rounded-3xl border border-gray-100 dark:border-gray-800 flex flex-col md:flex-row min-h-[580px]">

                        <!-- Left Side: PDF Viewer Area -->
                        <div
                            class="w-full md:w-3/5 bg-gray-100 dark:bg-gray-900/80 p-6 flex flex-col justify-between border-b md:border-b-0 md:border-r border-gray-200 dark:border-gray-800 relative min-h-[450px]">
                            <!-- PDF Header -->
                            <div
                                class="flex items-center justify-between pb-3 mb-4 border-b border-gray-300 dark:border-gray-700">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-file-pdf text-danger text-lg"></i>
                                    <span
                                        class="font-semibold text-xs text-textMain-light dark:text-textMain-dark truncate max-w-[240px]"
                                        x-text="activeDed?.tahapDed?.nomor_dokumen || 'DED_KAWASAN_KNMP_2026.pdf'"></span>
                                </div>
                                <span
                                    class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-teal-light/10 text-teal-light">DED
                                    Resmi</span>
                            </div>

                            <!-- Real or Mock PDF Preview -->
                            <div
                                class="flex-1 rounded-xl bg-white dark:bg-gray-800 shadow-inner border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col items-center justify-center p-6 text-center relative">
                                <template x-if="activeDed?.tahapDed?.file_url && activeDed?.tahapDed?.file_url !== '-'">
                                    <iframe :src="activeDed.tahapDed.file_url"
                                        class="w-full h-full min-h-[420px] border-0 rounded-lg"></iframe>
                                </template>
                                <template x-if="!activeDed?.tahapDed?.file_url || activeDed?.tahapDed?.file_url === '-'">
                                    <div class="w-full max-w-md space-y-6 py-4">
                                        <div
                                            class="w-16 h-16 mx-auto rounded-2xl bg-red-50 dark:bg-red-900/20 text-red-500 flex items-center justify-center text-3xl shadow-sm">
                                            <i class="fa-solid fa-file-pdf"></i>
                                        </div>
                                        <div class="space-y-1">
                                            <p
                                                class="text-[10px] font-bold tracking-widest text-textMuted-light uppercase">
                                                KEMENTERIAN KELAUTAN DAN PERIKANAN</p>
                                            <h4
                                                class="text-sm font-black text-textMain-light dark:text-textMain-dark leading-snug">
                                                DOKUMEN DETAIL ENGINEERING DESIGN (DED)<br>PEMBANGUNAN KAWASAN NELAYAN
                                                MODERN & PRODUKTIF</h4>
                                        </div>
                                        <div
                                            class="p-3.5 rounded-xl bg-gray-50 dark:bg-gray-900/60 border border-gray-200/60 dark:border-gray-700/60 text-left space-y-2 text-xs">
                                            <div class="flex justify-between"><span class="text-textMuted-light">Nama
                                                    Lokasi:</span><span
                                                    class="font-bold text-textMain-light dark:text-textMain-dark"
                                                    x-text="activeDed?.lokasi || '-'"></span></div>
                                            <div class="flex justify-between"><span
                                                    class="text-textMuted-light">Wilayah:</span><span
                                                    class="font-medium text-textMain-light dark:text-textMain-dark"
                                                    x-text="activeDed?.daerah || '-'"></span></div>
                                            <div class="flex justify-between"><span class="text-textMuted-light">Nomor
                                                    DED:</span><span class="font-mono font-bold text-teal-light"
                                                    x-text="activeDed?.tahapDed?.nomor_dokumen || 'DED-KNMP/2026/01'"></span>
                                            </div>
                                        </div>
                                        <div
                                            class="pt-2 border-t border-dashed border-gray-200 dark:border-gray-700 flex items-center justify-center gap-2 text-[10px] text-textMuted-light">
                                            <i class="fa-solid fa-shield-halved text-teal-light"></i> Dokumen Terverifikasi
                                            & Disahkan Tim Teknis
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- PDF Toolbar Footer -->
                            <div
                                class="flex items-center justify-between pt-3 mt-4 border-t border-gray-300 dark:border-gray-700 text-[11px] text-textMuted-light">
                                <span><i class="fa-solid fa-lock mr-1"></i> Dokumen Digital Terenkripsi</span>
                                <div class="flex gap-2">
                                    <span class="px-2 py-0.5 rounded bg-gray-200 dark:bg-gray-800 font-medium">Halaman 1 /
                                        1</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side: Details & Actions -->
                        <div class="w-full md:w-2/5 flex flex-col bg-white dark:bg-bgSurface-dark p-6 justify-between">
                            <div>
                                <!-- Modal Header -->
                                <div
                                    class="flex items-start justify-between pb-4 border-b border-gray-100 dark:border-gray-800">
                                    <div>
                                        <span
                                            class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-teal-light/10 text-teal-light"
                                            x-text="activeDed?.statusHub || 'Penyangga'"></span>
                                        <h3 class="text-lg font-bold text-textMain-light dark:text-textMain-dark mt-1">
                                            Evaluasi & Preview DED</h3>
                                        <p class="text-xs text-textMuted-light dark:text-textMuted-dark mt-0.5"
                                            x-text="activeDed?.lokasi || '-'"></p>
                                    </div>
                                    <button type="button" @click="showPreviewDedModal = false; activeDed = null"
                                        class="text-gray-400 hover:text-gray-500 transition-colors">
                                        <i class="fa-solid fa-xmark text-lg"></i>
                                    </button>
                                </div>

                                <!-- DED Details List -->
                                <div class="mt-6 space-y-4 text-xs">
                                    <div
                                        class="bg-teal-50/50 dark:bg-teal-900/10 p-3.5 rounded-2xl border border-teal-100 dark:border-teal-800/40 space-y-1.5">
                                        <div class="flex items-center gap-2 text-teal-light font-bold">
                                            <i class="fa-solid fa-check-circle"></i>
                                            <span>Status Engineering Design</span>
                                        </div>
                                        <p class="text-textMain-light dark:text-textMain-dark text-[11px] leading-relaxed">
                                            Seluruh kriteria perencanaan teknis (RAB, spesifikasi material, dan gambar kerja
                                            konstruksi) telah disetujui dan dinyatakan siap lelang/konstruksi.
                                        </p>
                                    </div>

                                    <div class="space-y-3 pt-2">
                                        <div
                                            class="flex justify-between items-center py-1.5 border-b border-gray-100 dark:border-gray-800">
                                            <span class="text-textMuted-light dark:text-textMuted-dark font-medium">Nomor
                                                Dokumen</span>
                                            <span class="font-mono font-bold text-textMain-light dark:text-textMain-dark"
                                                x-text="activeDed?.tahapDed?.nomor_dokumen || 'DED-KNMP/2026/01'"></span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center py-1.5 border-b border-gray-100 dark:border-gray-800">
                                            <span class="text-textMuted-light dark:text-textMuted-dark font-medium">Tanggal
                                                Pengesahan</span>
                                            <span class="font-semibold text-textMain-light dark:text-textMain-dark"
                                                x-text="activeDed?.tahapDed?.tanggal_pengesahan || activeDed?.created_at || '-'"></span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center py-1.5 border-b border-gray-100 dark:border-gray-800">
                                            <span
                                                class="text-textMuted-light dark:text-textMuted-dark font-medium">Kriteria
                                                DED</span>
                                            <span
                                                class="px-2 py-0.5 rounded text-[10px] font-bold bg-success/10 text-success">100/100
                                                (Disetujui)</span>
                                        </div>
                                        <div class="py-1.5">
                                            <span
                                                class="text-textMuted-light dark:text-textMuted-dark font-medium block mb-1">Catatan
                                                Verifikasi Teknis</span>
                                            <p class="text-textMain-light dark:text-textMain-dark bg-gray-50 dark:bg-gray-800/50 p-3 rounded-xl border border-gray-100 dark:border-gray-800 italic"
                                                x-text="activeDed?.tahapDed?.catatan !== '-' ? activeDed?.tahapDed?.catatan : 'Desain konstruksi pemecah gelombang (breakwater) dan dermaga pendaratan ikan telah memenuhi standar keamanan maritim.'">
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="pt-6 mt-6 border-t border-gray-100 dark:border-gray-800 flex flex-col gap-2.5">
                                <template x-if="activeDed?.tahapDed?.file_url && activeDed?.tahapDed?.file_url !== '-'">
                                    <a :href="activeDed.tahapDed.file_url" target="_blank"
                                        class="w-full py-2.5 rounded-xl bg-teal-light text-white font-semibold text-xs text-center hover:bg-teal-light/90 transition-colors flex items-center justify-center gap-2 shadow-sm">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka PDF di Tab Baru
                                    </a>
                                </template>
                                <button type="button" @click="showPreviewDedModal = false; activeDed = null"
                                    class="w-full py-2.5 rounded-xl bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark font-semibold text-xs hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                                    Tutup Preview
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('operasionalManager', () => ({
                showDetailDataModal: false,
                activeDetail: null,
                openDetailModal(item) {
                    console.log('Detail clicked for item:', item);
                    this.activeDetail = item;
                    this.showDetailDataModal = true;
                },

                showPreviewDedModal: false,
                activeDed: null,
                openPreviewDedModal(item) {
                    console.log('Preview DED clicked for item:', item);
                    this.activeDed = item;
                    this.showPreviewDedModal = true;
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
                        'serah-terima': 6
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

                currentStage: '{{ $stage }}',
                searchQuery: '',
                perPage: '10',
                currentPage: 1,

                init() {
                    const urlParams = new URLSearchParams(window.location.search);
                    const detailId = urlParams.get('detail_id');
                    if (detailId) {
                        setTimeout(() => {
                            const allItems = [
                                ...(this.usulanList || []),
                                ...(this.surveiList || []),
                                ...(this.dedList || []),
                                ...(this.lelangList || []),
                                ...(this.konstruksiList || []),
                                ...(this.serahTerimaList || [])
                            ];
                            const found = allItems.find(item => item.id == detailId);
                            if (found) {
                                this.openDetailModal(found);
                            }
                        }, 100);
                    }
                },


                formatDec(val) {
                    if (val === null || val === undefined || val === '') return '0,00';
                    return Number(val || 0).toLocaleString('id-ID', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                },

                isUploadModalOpen: false,
                isImportModalOpen: false,
                isImportUsulanModalOpen: false,
                uploadItem: null,
                selectedIds: [],

                get allSelected() {
                    const currentIds = this.paginatedData().map(item => item.id);
                    if (currentIds.length === 0) return false;
                    return currentIds.every(id => this.selectedIds.includes(id));
                },

                toggleAll() {
                    const currentIds = this.paginatedData().map(item => item.id);
                    if (this.allSelected) {
                        this.selectedIds = this.selectedIds.filter(id => !currentIds.includes(id));
                    } else {
                        const newIds = currentIds.filter(id => !this.selectedIds.includes(id));
                        this.selectedIds.push(...newIds);
                    }
                },

                openUploadModal(item) {
                    console.log('Upload clicked for item:', item);
                    this.uploadItem = item;
                    this.isUploadModalOpen = true;
                },
                closeUploadModal() {
                    this.isUploadModalOpen = false;
                    this.uploadItem = null;
                },

                stages: {
                    'usulan': {
                        label: 'Usulan',
                        icon: 'fa-file-invoice'
                    },
                    'survei': {
                        label: 'Survei',
                        icon: 'fa-compass-drafting'
                    },
                    'ded': {
                        label: 'DED',
                        icon: 'fa-pen-ruler'
                    },
                    'lelang': {
                        label: 'Siap Lelang',
                        icon: 'fa-gavel'
                    },
                    'konstruksi': {
                        label: 'Konstruksi',
                        icon: 'fa-helmet-safety'
                    },
                    'serah-terima': {
                        label: 'Serah Terima',
                        icon: 'fa-handshake'
                    }
                },

                // Real Data from Database
                usulanList: @json($usulanData ?? []),
                surveiList: @json($surveiData ?? []),
                dedList: @json($dedData ?? []),
                lelangList: @json($lelangData ?? []),
                konstruksiList: @json($konstruksiData ?? []),
                serahTerimaList: @json($serahTerimaData ?? []),

                // Switch stage and reset pagination
                switchStage(key) {
                    this.currentStage = key;
                    this.currentPage = 1;
                    this.searchQuery = '';
                    this.selectedIds = [];

                    const url = new URL(window.location.href);
                    url.searchParams.set('stage', key);
                    window.history.replaceState({}, '', url);
                },

                // Get current stage's raw data
                currentData() {
                    const map = {
                        'usulan': this.usulanList,
                        'survei': this.surveiList,
                        'ded': this.dedList,
                        'lelang': this.lelangList,
                        'konstruksi': this.konstruksiList,
                        'serah-terima': this.serahTerimaList,
                    };
                    return map[this.currentStage] || [];
                },

                // Filter data by search query
                filteredData() {
                    const q = this.searchQuery.toLowerCase().trim();
                    if (!q) return this.currentData();
                    return this.currentData().filter(item => {
                        return Object.values(item).some(val =>
                            String(val).toLowerCase().includes(q)
                        );
                    });
                },

                // Paginated slice
                paginatedData() {
                    const data = this.filteredData();
                    if (this.perPage === 'all') return data;
                    const pp = parseInt(this.perPage);
                    const start = (this.currentPage - 1) * pp;
                    return data.slice(start, start + pp);
                },

                // Total pages
                totalPages() {
                    if (this.perPage === 'all') return 1;
                    const pp = parseInt(this.perPage);
                    return Math.max(1, Math.ceil(this.filteredData().length / pp));
                },

                // Generate visible page numbers with ellipsis
                visiblePages() {
                    const total = this.totalPages();
                    if (total <= 7) return Array.from({
                        length: total
                    }, (_, i) => i + 1);

                    const pages = [];
                    const cur = this.currentPage;

                    pages.push(1);
                    if (cur > 3) pages.push('...');

                    for (let i = Math.max(2, cur - 1); i <= Math.min(total - 1, cur + 1); i++) {
                        pages.push(i);
                    }

                    if (cur < total - 2) pages.push('...');
                    pages.push(total);

                    return pages;
                },
            }));
        });
    </script>
@endsection

@extends('layouts.app')

@section('title', 'KNMP - Operasional Proyek')

@section('content')
<div x-data="operasionalManager()">
    <div class="mb-6 flex flex-col gap-4">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-xl font-semibold tracking-tight">Operasional Proyek KNMP</h2>
                <p class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1">Siklus 2: Pelaksanaan teknis dari lokasi definitif hingga serah terima pembangunan.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <template x-if="currentStage === 'usulan'">
                    @can('manage-operasional')
                    <button type="button" @click="$dispatch('open-import-usulan-modal')" class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 hover:bg-gray-50 text-textMain-light dark:text-textMain-dark rounded-md px-4 py-2 text-xs font-medium transition-all flex items-center justify-between gap-2 cursor-pointer"> Import Data <i class="fa-solid fa-cloud-arrow-up text-teal-light"></i> </button>
                    @endcan
                </template>
                <template x-if="currentStage === 'konstruksi'">
                    @can('import-progres')
                    <button type="button" @click="$dispatch('open-import-modal')" class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 hover:bg-gray-50 text-textMain-light dark:text-textMain-dark rounded-md px-4 py-2 text-xs font-medium transition-all flex items-center justify-between gap-2 cursor-pointer"> Import Progres <i class="fa-solid fa-cloud-arrow-up text-teal-light"></i> </button>
                    @endcan
                </template>
            </div>
        </div>

        <!-- Stepper / Tabs -->
        <div class="bg-white dark:bg-bgSurface-dark rounded-2xl border border-gray-100 dark:border-gray-800 p-2">
            <div class="flex min-w-max md:w-full">
                <template x-for="(data, key, index) in stages" :key="key">
                    <a href="#" @click.prevent="switchStage(key)" class="flex items-center group relative py-2 px-2" :class="{ 'flex-1': key !== 'serah-terima' }">
                        <div class="flex items-center gap-2 relative z-10 shrink-0">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center font-medium text-xs transition-colors"
                                :class="currentStage === key ? 'bg-teal-light text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-400 group-hover:bg-teal-light/20 group-hover:text-teal-light'">
                                <i class="fa-solid text-[10px]" :class="data.icon"></i>
                            </div>
                            <span class="font-medium text-xs transition-colors whitespace-normal max-w-[90px] leading-[1.1]" 
                                :class="currentStage === key ? 'text-teal-light' : 'text-textMuted-light dark:text-textMuted-dark group-hover:text-teal-light'" x-text="data.label">
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

    <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl overflow-hidden flex flex-col relative min-h-[400px]">
        <!-- Header: Title + Pindah Tahap -->
        <div class="px-6 pt-5 pb-3 flex justify-between items-center">
            <h3 class="text-base font-medium text-textMuted-light dark:text-textMuted-dark" x-text="stages[currentStage].label"></h3>
            <template x-if="currentStage !== 'serah-terima'">
                @can('manage-operasional')
                <form action="{{ route('program.operasional.pindah-tahap', ['program' => strtolower($activeProgram)]) }}" method="POST" class="inline-block">
                    @csrf
                    <input type="hidden" name="target_stage" :value="
                        currentStage === 'usulan' ? 'survey' :
                        (currentStage === 'survei' ? 'ded' :
                        (currentStage === 'ded' ? 'lelang' :
                        (currentStage === 'lelang' ? 'konstruksi' : 'serah_terima')))
                    ">
                    <template x-for="id in selectedIds" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                    <button type="submit" :disabled="selectedIds.length === 0" 
                            class="bg-teal-light hover:bg-teal-600 text-white rounded-md px-4 py-2 text-xs font-medium transition-all flex items-center justify-between gap-2 w-max disabled:opacity-50 disabled:cursor-not-allowed"> 
                        <span x-show="selectedIds.length > 0" class="bg-white text-teal-light rounded-full w-4 h-4 flex items-center justify-center text-[10px] font-bold mr-1" x-text="selectedIds.length"></span>
                        Pindah Tahap <i class="fa-solid fa-arrow-right"></i> 
                    </button>
                </form>
                @endcan
            </template>
        </div>

        <!-- Toolbar: Filter + Search -->
        <div class="px-6 pb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <!-- Left side: Show entries -->
            <div class="flex items-center gap-2 text-xs text-textMuted-light dark:text-textMuted-dark">
                <span>Tampilkan</span>
                <select x-model="perPage" @change="currentPage = 1" class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-md px-2 py-1.5 text-xs focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark font-medium">
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="250">250</option>
                    <option value="500">500</option>
                    <option value="all">Semua</option>
                </select>
                <span>entri</span>
            </div>

            <!-- Right side: Search bar -->
            <div class="relative w-full sm:w-64">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Cari proyek..." class="w-full pl-8 pr-4 py-2 rounded-md border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-xs focus:border-teal-light outline-none transition-all">
            </div>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto">
            
            <!-- TABLE 1: USULAN -->
            <table x-show="currentStage === 'usulan'" class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
                    <tr>
                        <th class="px-6 py-4 w-10 text-center">
                            <input type="checkbox" :checked="allSelected" @change="toggleAll()" class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-teal-light focus:ring-teal-light dark:focus:ring-teal-light/50 cursor-pointer transition-colors">
                        </th>
                        <th class="px-6 py-4">Lokasi KNMP</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
                    <template x-for="item in paginatedData()" :key="item.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors" :class="{'bg-teal-50/30 dark:bg-teal-900/10': selectedIds.includes(item.id)}">
                            <td class="px-6 py-4 text-center">
                                <input type="checkbox" :value="item.id" x-model="selectedIds" class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-teal-light focus:ring-teal-light dark:focus:ring-teal-light/50 cursor-pointer transition-colors">
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="item.lokasi"></div>
                                <div class="text-[11px] text-textMuted-light mt-0.5" x-text="item.daerah"></div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-md text-[0.7rem] font-medium bg-teal-light/10 text-teal-light" x-text="item.statusHub"></span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-teal-light hover:bg-teal-light/10 transition-colors flex items-center justify-center mx-auto" title="Detail"><i class="fa-solid fa-eye"></i></button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>

            <!-- TABLE 2: SURVEI -->
            <table x-show="currentStage === 'survei'" style="display: none;" class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
                    <tr>
                        <th class="px-6 py-4 w-10 text-center">
                            <input type="checkbox" :checked="allSelected" @change="toggleAll()" class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-teal-light focus:ring-teal-light dark:focus:ring-teal-light/50 cursor-pointer transition-colors">
                        </th>
                        <th class="px-6 py-4">Lokasi KNMP</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Koordinat</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
                    <template x-for="item in paginatedData()" :key="item.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors" :class="{'bg-teal-50/30 dark:bg-teal-900/10': selectedIds.includes(item.id)}">
                            <td class="px-6 py-4 text-center">
                                <input type="checkbox" :value="item.id" x-model="selectedIds" class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-teal-light focus:ring-teal-light dark:focus:ring-teal-light/50 cursor-pointer transition-colors">
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="item.lokasi"></div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-md text-[0.7rem] font-medium bg-teal-light/10 text-teal-light" x-text="item.statusHub"></span>
                            </td>
                            <td class="px-6 py-4 text-textMuted-light" x-text="item.koordinat"></td>
                            <td class="px-6 py-4 text-center">
                                <button class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-teal-light hover:bg-teal-light/10 transition-colors flex items-center justify-center mx-auto" title="Detail"><i class="fa-solid fa-eye"></i></button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>

            <!-- TABLE 3: DED -->
            <table x-show="currentStage === 'ded'" style="display: none;" class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
                    <tr>
                        <th class="px-6 py-4 w-10 text-center">
                            <input type="checkbox" :checked="allSelected" @change="toggleAll()" class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-teal-light focus:ring-teal-light dark:focus:ring-teal-light/50 cursor-pointer transition-colors">
                        </th>
                        <th class="px-6 py-4">Lokasi KNMP</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Dokumen DED</th>
                        <th class="px-6 py-4">Nilai Skala Kriteria</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
                    <template x-for="item in paginatedData()" :key="item.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors" :class="{'bg-teal-50/30 dark:bg-teal-900/10': selectedIds.includes(item.id)}">
                            <td class="px-6 py-4 text-center">
                                <input type="checkbox" :value="item.id" x-model="selectedIds" class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-teal-light focus:ring-teal-light dark:focus:ring-teal-light/50 cursor-pointer transition-colors">
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="item.lokasi"></div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-md text-[0.7rem] font-medium bg-teal-light/10 text-teal-light" x-text="item.statusHub"></span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors flex items-center justify-center mx-auto" title="Lihat DED"><i class="fa-solid fa-file-pdf"></i></button>
                            </td>
                            <td class="px-6 py-4 font-medium text-teal-light" x-text="item.nilaiSkala"></td>
                            <td class="px-6 py-4 text-center">
                                <button class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-teal-light hover:bg-teal-light/10 transition-colors flex items-center justify-center mx-auto" title="Detail"><i class="fa-solid fa-eye"></i></button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>

            <!-- TABLE 4: SIAP LELANG -->
            <table x-show="currentStage === 'lelang'" style="display: none;" class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
                    <tr>
                        <th class="px-6 py-4 w-10 text-center">
                            <input type="checkbox" :checked="allSelected" @change="toggleAll()" class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-teal-light focus:ring-teal-light dark:focus:ring-teal-light/50 cursor-pointer transition-colors">
                        </th>
                        <th class="px-6 py-4">Lokasi KNMP</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Nama Konstruksi</th>
                        <th class="px-6 py-4 text-center">Dokumen</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
                    <template x-for="item in paginatedData()" :key="item.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors" :class="{'bg-teal-50/30 dark:bg-teal-900/10': selectedIds.includes(item.id)}">
                            <td class="px-6 py-4 text-center">
                                <input type="checkbox" :value="item.id" x-model="selectedIds" class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-teal-light focus:ring-teal-light dark:focus:ring-teal-light/50 cursor-pointer transition-colors">
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="item.lokasi"></div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-md text-[0.7rem] font-medium bg-teal-light/10 text-teal-light" x-text="item.statusHub"></span>
                            </td>
                            <td class="px-6 py-4 text-textMuted-light" x-text="item.namaKonstruksi"></td>
                            <td class="px-6 py-4 text-center">
                                <button class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors flex items-center justify-center mx-auto" title="Dokumen Lelang"><i class="fa-solid fa-file-pdf"></i></button>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-teal-light hover:bg-teal-light/10 transition-colors flex items-center justify-center mx-auto" title="Detail"><i class="fa-solid fa-eye"></i></button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>

            <!-- TABLE 5: KONSTRUKSI -->
            <table x-show="currentStage === 'konstruksi'" style="display: none;" class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
                    <tr>
                        <th class="px-6 py-4 w-10 text-center">
                            <input type="checkbox" :checked="allSelected" @change="toggleAll()" class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-teal-light focus:ring-teal-light dark:focus:ring-teal-light/50 cursor-pointer transition-colors">
                        </th>
                        <th class="px-6 py-4">Lokasi KNMP</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Konstruktor</th>
                        <th class="px-6 py-4">Progres</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
                    <template x-for="item in paginatedData()" :key="item.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors" :class="{'bg-teal-50/30 dark:bg-teal-900/10': selectedIds.includes(item.id)}">
                            <td class="px-6 py-4 text-center">
                                <input type="checkbox" :value="item.id" x-model="selectedIds" class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-teal-light focus:ring-teal-light dark:focus:ring-teal-light/50 cursor-pointer transition-colors">
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="item.lokasi"></div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-md text-[0.7rem] font-medium bg-teal-light/10 text-teal-light" x-text="item.statusHub"></span>
                            </td>
                            <td class="px-6 py-4 text-textMuted-light" x-text="item.konstruktor"></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-24 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                        <div class="h-full bg-success" :style="'width: ' + item.progres + '%'"></div>
                                    </div>
                                    <span class="text-[11px] font-medium" x-text="item.progres + '%'"></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    @can('manage-operasional')
                                    <button type="button" @click="$dispatch('open-upload-modal', { item: item })" class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors flex items-center justify-center relative z-10 cursor-pointer" title="Upload Foto Progres"><i class="fa-solid fa-camera pointer-events-none"></i></button>
                                    @endcan
                                    <button class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-teal-light hover:bg-teal-light/10 transition-colors flex items-center justify-center" title="Detail"><i class="fa-solid fa-eye"></i></button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>

            <!-- TABLE 6: SERAH TERIMA -->
            <table x-show="currentStage === 'serah-terima'" style="display: none;" class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
                    <tr>
                        <th class="px-6 py-4 w-10 text-center">
                            <input type="checkbox" :checked="allSelected" @change="toggleAll()" class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-teal-light focus:ring-teal-light dark:focus:ring-teal-light/50 cursor-pointer transition-colors">
                        </th>
                        <th class="px-6 py-4">Lokasi KNMP</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Dokumen</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
                    <template x-for="item in paginatedData()" :key="item.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors" :class="{'bg-teal-50/30 dark:bg-teal-900/10': selectedIds.includes(item.id)}">
                            <td class="px-6 py-4 text-center">
                                <input type="checkbox" :value="item.id" x-model="selectedIds" class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-teal-light focus:ring-teal-light dark:focus:ring-teal-light/50 cursor-pointer transition-colors">
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="item.lokasi"></div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-md text-[0.7rem] font-medium bg-teal-light/10 text-teal-light" x-text="item.statusHub"></span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors flex items-center justify-center mx-auto" title="Dokumen BAST"><i class="fa-solid fa-file-contract"></i></button>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-teal-light hover:bg-teal-light/10 transition-colors flex items-center justify-center mx-auto" title="Detail"><i class="fa-solid fa-eye"></i></button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
            
        </div>

        <!-- Footer: Info + Pagination -->
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50 dark:bg-gray-800/20">
            <!-- Info total data (kiri bawah) -->
            <div class="text-xs text-textMuted-light dark:text-textMuted-dark">
                Menampilkan <span class="font-medium text-textMain-light dark:text-textMain-dark" x-text="paginatedData().length"></span> dari <span class="font-medium text-textMain-light dark:text-textMain-dark" x-text="filteredData().length"></span> data
            </div>

            <!-- Pagination (kanan bawah) -->
            <div class="flex gap-1" x-show="totalPages() > 1">
                <button @click="currentPage = Math.max(1, currentPage - 1)" :disabled="currentPage === 1"
                    class="w-8 h-8 rounded-md border border-gray-100 dark:border-gray-700 flex items-center justify-center text-gray-400 disabled:opacity-30 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                </button>
                
                <template x-for="page in visiblePages()" :key="page">
                    <button @click="if(page !== '...') currentPage = page"
                        class="w-8 h-8 rounded-md font-medium text-xs flex items-center justify-center transition-colors"
                        :class="page === currentPage ? 'bg-teal-light text-white' : (page === '...' ? 'cursor-default text-gray-400' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-textMain-light dark:text-textMain-dark')"
                        x-text="page">
                    </button>
                </template>

                <button @click="currentPage = Math.min(totalPages(), currentPage + 1)" :disabled="currentPage === totalPages()"
                    class="w-8 h-8 rounded-md border border-gray-100 dark:border-gray-700 flex items-center justify-center text-gray-400 disabled:opacity-30 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </button>
            </div>
        </div>
        </div>
    </div>

    <!-- Modal Upload Foto -->
    <template x-teleport="body">
        <div x-data="{ isUploadModalOpen: false, uploadItem: null }" @open-upload-modal.window="uploadItem = $event.detail.item; isUploadModalOpen = true" x-show="isUploadModalOpen" class="fixed inset-0 overflow-y-auto" style="display: none; z-index: 99999;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <!-- Background overlay -->
                <div x-show="isUploadModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" aria-hidden="true"></div>

                <!-- Modal panel -->
                <div x-show="isUploadModalOpen" @click.away="isUploadModalOpen = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-bgSurface-dark shadow-2xl rounded-3xl border border-gray-100 dark:border-gray-800">
                    
                    <!-- Header Icon & Close -->
                    <div class="flex items-start justify-between">
                        <div class="flex items-center justify-center w-12 h-12 bg-teal-light/10 text-teal-light rounded-full mb-4">
                            <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
                        </div>
                        <button type="button" @click="isUploadModalOpen = false" class="text-gray-400 hover:text-gray-500 focus:outline-none transition-colors relative z-10">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <div>
                        <div class="mt-2 text-left">
                            <h3 class="text-xl font-bold leading-6 text-textMain-light dark:text-textMain-dark" id="modal-title">
                                Upload Dokumentasi
                            </h3>
                            <p class="text-sm font-medium text-teal-light mt-1" x-text="uploadItem?.lokasi"></p>
                            <p class="text-xs text-textMuted-light dark:text-textMuted-dark mt-2 leading-relaxed">Pilih gambar visual konstruksi untuk melampirkan bukti progres fisik. Pastikan foto memiliki pencahayaan yang baik (Maks. 2MB).</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <form action="{{ route('program.operasional.upload-foto', ['program' => strtolower($activeProgram)]) }}" method="POST" enctype="multipart/form-data" class="w-full">
                            @csrf
                            <input type="hidden" name="knmp_id" :value="uploadItem?.id">
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                                <!-- Area Upload Before -->
                                <div class="bg-gray-50 dark:bg-gray-800/50 p-4 rounded-2xl border border-gray-100 dark:border-gray-700">
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="w-6 h-6 rounded-full bg-red-100 text-red-500 flex items-center justify-center text-xs"><i class="fa-solid fa-hourglass-start"></i></div>
                                        <label class="block text-xs font-bold text-textMain-light dark:text-textMain-dark uppercase tracking-wider">0% - Sebelum</label>
                                    </div>
                                    <p class="text-[10px] text-textMuted-light dark:text-textMuted-dark mb-4">Pilih hingga 2 foto kondisi awal (Before).</p>
                                    
                                    <div class="space-y-3">
                                        <div x-data="{ imgName: '' }">
                                            <label class="block text-xs font-semibold text-textMuted-light dark:text-textMuted-dark mb-2">Foto Before 1</label>
                                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800/50 hover:bg-red-50 hover:border-red-400 transition-colors cursor-pointer relative z-10">
                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                    <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2"></i>
                                                    <p class="mb-1 text-sm text-textMuted-light dark:text-textMuted-dark"><span class="font-medium text-red-500">Klik untuk upload</span> atau drag and drop</p>
                                                    <p class="text-xs text-gray-500">JPG/PNG (Maks. 2MB)</p>
                                                </div>
                                                <input id="foto_before_1" type="file" name="foto_before[]" accept="image/jpeg, image/png, image/jpg" class="hidden" @change="imgName = $event.target.files.length ? $event.target.files[0].name : ''">
                                            </label>
                                            <p x-show="imgName" class="text-xs text-success mt-2 font-medium text-center truncate" x-text="`File: ${imgName}`"></p>
                                        </div>
                                        <div x-data="{ imgName: '' }">
                                            <label class="block text-xs font-semibold text-textMuted-light dark:text-textMuted-dark mb-2">Foto Before 2</label>
                                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800/50 hover:bg-red-50 hover:border-red-400 transition-colors cursor-pointer relative z-10">
                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                    <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2"></i>
                                                    <p class="mb-1 text-sm text-textMuted-light dark:text-textMuted-dark"><span class="font-medium text-red-500">Klik untuk upload</span> atau drag and drop</p>
                                                    <p class="text-xs text-gray-500">JPG/PNG (Maks. 2MB)</p>
                                                </div>
                                                <input id="foto_before_2" type="file" name="foto_before[]" accept="image/jpeg, image/png, image/jpg" class="hidden" @change="imgName = $event.target.files.length ? $event.target.files[0].name : ''">
                                            </label>
                                            <p x-show="imgName" class="text-xs text-success mt-2 font-medium text-center truncate" x-text="`File: ${imgName}`"></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Area Upload After -->
                                <div class="bg-teal-50/50 dark:bg-teal-900/10 p-4 rounded-2xl border border-teal-100 dark:border-teal-800/30">
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="w-6 h-6 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center text-xs"><i class="fa-solid fa-check-double"></i></div>
                                        <label class="block text-xs font-bold text-textMain-light dark:text-textMain-dark uppercase tracking-wider">Progres Saat Ini</label>
                                    </div>
                                    <p class="text-[10px] text-textMuted-light dark:text-textMuted-dark mb-4">Pilih hingga 2 foto kondisi progres (After).</p>
                                    
                                    <div class="space-y-3">
                                        <div x-data="{ imgName: '' }">
                                            <label class="block text-xs font-semibold text-textMuted-light dark:text-textMuted-dark mb-2">Foto After 1</label>
                                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-teal-300 dark:border-teal-700 rounded-xl bg-gray-50 dark:bg-gray-800/50 hover:bg-teal-50 hover:border-teal-400 transition-colors cursor-pointer relative z-10">
                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                    <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2"></i>
                                                    <p class="mb-1 text-sm text-textMuted-light dark:text-textMuted-dark"><span class="font-medium text-teal-600">Klik untuk upload</span> atau drag and drop</p>
                                                    <p class="text-xs text-gray-500">JPG/PNG (Maks. 2MB)</p>
                                                </div>
                                                <input id="foto_after_1" type="file" name="foto_after[]" accept="image/jpeg, image/png, image/jpg" class="hidden" @change="imgName = $event.target.files.length ? $event.target.files[0].name : ''">
                                            </label>
                                            <p x-show="imgName" class="text-xs text-success mt-2 font-medium text-center truncate" x-text="`File: ${imgName}`"></p>
                                        </div>
                                        <div x-data="{ imgName: '' }">
                                            <label class="block text-xs font-semibold text-textMuted-light dark:text-textMuted-dark mb-2">Foto After 2</label>
                                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-teal-300 dark:border-teal-700 rounded-xl bg-gray-50 dark:bg-gray-800/50 hover:bg-teal-50 hover:border-teal-400 transition-colors cursor-pointer relative z-10">
                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                    <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2"></i>
                                                    <p class="mb-1 text-sm text-textMuted-light dark:text-textMuted-dark"><span class="font-medium text-teal-600">Klik untuk upload</span> atau drag and drop</p>
                                                    <p class="text-xs text-gray-500">JPG/PNG (Maks. 2MB)</p>
                                                </div>
                                                <input id="foto_after_2" type="file" name="foto_after[]" accept="image/jpeg, image/png, image/jpg" class="hidden" @change="imgName = $event.target.files.length ? $event.target.files[0].name : ''">
                                            </label>
                                            <p x-show="imgName" class="text-xs text-success mt-2 font-medium text-center truncate" x-text="`File: ${imgName}`"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex gap-3 mt-6 pt-2 border-t border-gray-100 dark:border-gray-800">
                                <button type="button" @click="isUploadModalOpen = false" class="flex-1 justify-center rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-2.5 bg-white dark:bg-gray-800 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none transition-all duration-200 relative z-10">Batal</button>
                                <button type="submit" class="flex-1 justify-center rounded-xl border border-transparent shadow-lg shadow-teal-light/30 px-4 py-2.5 bg-gradient-to-r from-teal-light to-emerald-500 text-sm font-semibold text-white hover:from-teal-light/90 hover:to-emerald-500/90 focus:outline-none hover:-translate-y-0.5 transition-all duration-200 relative z-10">
                                    <i class="fa-solid fa-arrow-up-from-bracket mr-2"></i> Simpan Data
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
        <div x-data="{ isImportModalOpen: false }" @open-import-modal.window="isImportModalOpen = true" x-show="isImportModalOpen" style="display: none; z-index: 99999;" class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            <div @click.away="isImportModalOpen = false"
                 x-show="isImportModalOpen"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                 class="bg-white dark:bg-gray-900 rounded-2xl w-full max-w-md p-6 shadow-2xl border border-gray-100 dark:border-gray-800 relative mx-4 max-h-[90vh] overflow-y-auto">
                
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-textMain-light dark:text-textMain-dark">Import Progres Fisik</h3>
                    <button type="button" @click="isImportModalOpen = false" class="text-gray-400 hover:text-danger transition-colors">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <p class="text-xs text-textMuted-light dark:text-textMuted-dark leading-relaxed">Unggah file Excel (Template) untuk memperbarui data progres harian lokasi tahap konstruksi sekaligus.</p>

                    <a href="{{ route('program.operasional.template-progres', ['program' => strtolower($activeProgram)]) }}" class="w-full justify-center rounded-lg border border-gray-200 dark:border-gray-700 px-4 py-2.5 bg-gray-50 dark:bg-gray-800/50 text-xs font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition-all duration-200 relative z-10 flex items-center gap-2">
                        <i class="fa-solid fa-download text-teal-light text-sm"></i> Download Template Excel
                    </a>
                    
                    <form action="{{ route('program.operasional.import-progres', ['program' => strtolower($activeProgram)]) }}" method="POST" enctype="multipart/form-data" class="w-full">
                        @csrf
                        <div x-data="{ fileName: '' }" class="w-full mb-6">
                            <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Upload File Excel <span class="text-danger">*</span></label>
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800/50 hover:bg-teal-light/5 hover:border-teal-light transition-colors cursor-pointer relative z-10">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2"></i>
                                    <p class="mb-1 text-sm text-textMuted-light dark:text-textMuted-dark"><span class="font-medium text-teal-light">Klik untuk upload</span> atau drag and drop</p>
                                    <p class="text-xs text-gray-500">Excel (Maks. 5MB)</p>
                                </div>
                                <input type="file" name="file_excel" class="hidden" required accept=".xlsx, .xls" @change="fileName = $event.target.files.length ? $event.target.files[0].name : ''" />
                            </label>
                            <p x-show="fileName" class="text-sm text-success mt-2 font-medium text-center" x-text="`File terpilih: ${fileName}`"></p>
                        </div>
                        
                        <div class="mt-8 flex justify-end gap-3">
                            <button type="button" @click="isImportModalOpen = false" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">Batal</button>
                            <button type="submit" class="px-4 py-2 bg-teal-light text-white rounded-lg text-sm font-medium hover:bg-teal-600 transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-cloud-arrow-up"></i> Import Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>

    <!-- Modal Import Usulan -->
    <template x-teleport="body">
        <div x-data="{ isImportUsulanModalOpen: false }" @open-import-usulan-modal.window="isImportUsulanModalOpen = true" x-show="isImportUsulanModalOpen" style="display: none; z-index: 99999;" class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            <div @click.away="isImportUsulanModalOpen = false"
                 x-show="isImportUsulanModalOpen"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                 class="bg-white dark:bg-gray-900 rounded-2xl w-full max-w-md p-6 shadow-2xl border border-gray-100 dark:border-gray-800 relative mx-4 max-h-[90vh] overflow-y-auto">
                
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-textMain-light dark:text-textMain-dark">Import Data Usulan</h3>
                    <button type="button" @click="isImportUsulanModalOpen = false" class="text-gray-400 hover:text-danger transition-colors">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <p class="text-xs text-textMuted-light dark:text-textMuted-dark leading-relaxed">Unggah file Excel (Template) untuk menambahkan data lokasi usulan baru ke dalam sistem.</p>

                    <a href="{{ route('program.operasional.template-usulan', ['program' => strtolower($activeProgram)]) }}" class="w-full justify-center rounded-lg border border-gray-200 dark:border-gray-700 px-4 py-2.5 bg-gray-50 dark:bg-gray-800/50 text-xs font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition-all duration-200 relative z-10 flex items-center gap-2">
                        <i class="fa-solid fa-download text-teal-light text-sm"></i> Download Template Excel
                    </a>
                    
                    <form action="{{ route('program.operasional.import-usulan', ['program' => strtolower($activeProgram)]) }}" method="POST" enctype="multipart/form-data" class="w-full">
                        @csrf
                        <div x-data="{ fileName: '' }" class="w-full mb-6">
                            <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Upload File Excel <span class="text-danger">*</span></label>
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800/50 hover:bg-teal-light/5 hover:border-teal-light transition-colors cursor-pointer relative z-10">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2"></i>
                                    <p class="mb-1 text-sm text-textMuted-light dark:text-textMuted-dark"><span class="font-medium text-teal-light">Klik untuk upload</span> atau drag and drop</p>
                                    <p class="text-xs text-gray-500">Excel (Maks. 5MB)</p>
                                </div>
                                <input type="file" name="file_excel" class="hidden" required accept=".xlsx, .xls" @change="fileName = $event.target.files.length ? $event.target.files[0].name : ''" />
                            </label>
                            <p x-show="fileName" class="text-sm text-success mt-2 font-medium text-center" x-text="`File terpilih: ${fileName}`"></p>
                        </div>
                        
                        <div class="mt-8 flex justify-end gap-3">
                            <button type="button" @click="isImportUsulanModalOpen = false" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">Batal</button>
                            <button type="submit" class="px-4 py-2 bg-teal-light text-white rounded-lg text-sm font-medium hover:bg-teal-600 transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-cloud-arrow-up"></i> Import Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('operasionalManager', () => ({
            currentStage: '{{ $stage }}',
            searchQuery: '',
            perPage: '10',
            currentPage: 1,

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
                'usulan': { label: 'Usulan', icon: 'fa-file-invoice' },
                'survei': { label: 'Survei', icon: 'fa-compass-drafting' },
                'ded': { label: 'DED', icon: 'fa-pen-ruler' },
                'lelang': { label: 'Siap Lelang', icon: 'fa-gavel' },
                'konstruksi': { label: 'Konstruksi', icon: 'fa-helmet-safety' },
                'serah-terima': { label: 'Serah Terima', icon: 'fa-handshake' }
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
                if (total <= 7) return Array.from({length: total}, (_, i) => i + 1);
                
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

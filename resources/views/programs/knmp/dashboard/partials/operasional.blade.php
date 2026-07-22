@extends('layouts.app')

@section('title', 'KNMP - Operasional Serah Terima')

@section('content')
    <div x-data="sarprasDetailManager()">

        {{-- Header --}}
        <div class="mb-6 animate-fade-in-up">
        <a href="{{ route('program.dashboard', ['program' => strtolower($activeProgram)]) }}"
            class="inline-flex items-center gap-2 text-xs font-medium text-textMuted-light dark:text-textMuted-dark hover:text-teal-light dark:hover:text-teal-400 transition-colors mb-5 bg-white dark:bg-gray-800 px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard Utama
        </a>

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div>
                        <h2 class="text-base font-medium tracking-tight text-textMain-light dark:text-textMain-dark">
                            Operasional KNMP</h2>
                        <p class="text-[11px] sm:text-xs text-textMuted-light dark:text-textMuted-dark mt-0.5">
                            Total <strong>{{ $totalSelesai }}</strong> lokasi KNMP telah operasional
                        </p>
                    </div>
                </div>

                <form action="{{ url()->current() }}" method="GET" class="flex items-center gap-2 flex-wrap">
                    <div class="relative">
                        <select name="batch_id" onchange="this.form.submit()"
                            class="appearance-none bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2 pr-10 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-teal-light focus:border-teal-light text-textMain-light dark:text-textMain-dark">
                            <option value="">Semua Tahap</option>
                            @foreach ($stats['filter_batches'] ?? [] as $batch)
                                <option value="{{ $batch['id'] }}"
                                    {{ request('batch_id') == $batch['id'] ? 'selected' : '' }}>{{ $batch['name'] }}
                                </option>
                            @endforeach
                        </select>
                        <i
                            class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                    </div>
                </form>
            </div>
        </div>

        {{-- Sarpras Grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-4 mb-8">
            @foreach ($sarprasStats as $sarpras)
                <div @click="openModal('{{ $sarpras['nama'] }}', '{{ $sarpras['icon'] }}', JSON.parse($el.dataset.operasional), JSON.parse($el.dataset.belum))"
                     data-operasional="{{ json_encode($sarpras['lokasi_operasional']) }}"
                     data-belum="{{ json_encode($sarpras['lokasi_belum']) }}"
                    class="cursor-pointer border border-gray-200 dark:border-gray-700 rounded-2xl p-4 flex flex-col items-center text-center hover:border-teal-light/50 hover:shadow-md transition-all group bg-white dark:bg-gray-800">
                    {{-- Icon --}}
                    <div
                        class="w-12 h-12 rounded-xl flex items-center justify-center mb-3 transition-transform group-hover:scale-110
                    {{ $sarpras['persen'] >= 100 ? 'bg-teal-light/10 text-teal-light dark:text-teal-400' : ($sarpras['persen'] >= 50 ? 'bg-warning/10 text-warning' : 'bg-danger/10 text-danger') }}">
                        <i class="{{ $sarpras['icon'] }} text-xl"></i>
                    </div>

                    {{-- Label --}}
                    <span
                        class="text-[11px] font-semibold text-center leading-tight truncate w-full text-textMain-light dark:text-textMain-dark mb-2" title="{{ $sarpras['nama'] }}">{{ $sarpras['nama'] }}</span>

                    {{-- Stats --}}
                    <div class="w-full mt-auto">
                        <div class="flex justify-between items-center text-[10px] mb-1.5">
                            <span class="text-textMuted-light dark:text-textMuted-dark">Operasional</span>
                            <span
                                class="font-bold {{ $sarpras['persen'] >= 100 ? 'text-teal-light dark:text-teal-400' : ($sarpras['persen'] >= 50 ? 'text-warning' : 'text-danger') }}">{{ $sarpras['total'] }}/{{ $sarpras['target'] }}</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5">
                            <div class="h-1.5 rounded-full transition-all duration-500 {{ $sarpras['persen'] >= 100 ? 'bg-teal-light' : ($sarpras['persen'] >= 50 ? 'bg-warning' : 'bg-danger') }}"
                                style="width: {{ min($sarpras['persen'], 100) }}%"></div>
                        </div>
                        <div
                            class="text-[9px] font-bold mt-1 {{ $sarpras['persen'] >= 100 ? 'text-teal-light dark:text-teal-400' : ($sarpras['persen'] >= 50 ? 'text-warning' : 'text-danger') }}">
                            {{ number_format($sarpras['persen'], 0) }}%
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Detail Sarpras Modal --}}
        <div x-show="isModalOpen" 
             style="display: none;"
             class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden p-4 md:p-6"
             aria-labelledby="modal-title" role="dialog" aria-modal="true">
             
            <!-- Backdrop -->
            <div x-show="isModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" 
                 @click="isModalOpen = false"></div>

            <!-- Modal Panel -->
            <div x-show="isModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative w-full max-w-4xl transform rounded-3xl bg-white dark:bg-gray-800 shadow-2xl transition-all border border-gray-100 dark:border-gray-700 flex flex-col max-h-[90vh]">
                
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-teal-light/10 text-teal-light flex items-center justify-center">
                            <i :class="modalIcon + ' text-lg'"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-white" id="modal-title">
                                Detail Operasional <span x-text="modalTitle" class="text-teal-light"></span>
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Daftar lokasi berdasarkan status operasional</p>
                        </div>
                    </div>
                    <button @click="isModalOpen = false" class="text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full p-2 transition-colors">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <!-- Body (Scrollable) -->
                <div class="flex-1 overflow-y-auto p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Sudah Operasional -->
                        <div class="flex flex-col bg-gray-50/50 dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <div class="bg-teal-light/10 dark:bg-teal-900/20 px-4 py-3 border-b border-teal-light/20 flex justify-between items-center sticky top-0 backdrop-blur-md">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-circle-check text-teal-light"></i>
                                    <span class="font-bold text-sm text-teal-light dark:text-teal-400">Sudah Operasional</span>
                                </div>
                                <span class="bg-white dark:bg-gray-800 text-teal-light text-xs font-bold px-2.5 py-0.5 rounded-full" x-text="operasionalCount"></span>
                            </div>
                            <div class="p-4 overflow-y-auto max-h-[50vh] custom-scrollbar">
                                <ul class="space-y-2">
                                    <template x-for="(lokasi, index) in modalOperasional" :key="index">
                                        <li class="flex items-start gap-2.5 text-sm text-gray-700 dark:text-gray-300 py-2 border-b border-gray-100 dark:border-gray-800/50 last:border-0 last:pb-0">
                                            <span class="text-teal-light text-[10px] mt-1"><i class="fa-solid fa-location-dot"></i></span>
                                            <span x-text="lokasi" class="font-medium leading-tight"></span>
                                        </li>
                                    </template>
                                    <template x-if="operasionalCount === 0">
                                        <div class="flex flex-col items-center justify-center py-8 text-gray-400">
                                            <i class="fa-solid fa-folder-open text-2xl mb-2 opacity-50"></i>
                                            <span class="text-xs">Tidak ada data</span>
                                        </div>
                                    </template>
                                </ul>
                            </div>
                        </div>

                        <!-- Belum Operasional -->
                        <div class="flex flex-col bg-gray-50/50 dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <div class="bg-danger/10 dark:bg-danger-900/20 px-4 py-3 border-b border-danger/20 flex justify-between items-center sticky top-0 backdrop-blur-md">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-clock text-danger"></i>
                                    <span class="font-bold text-sm text-danger dark:text-danger-400">Belum Operasional</span>
                                </div>
                                <span class="bg-white dark:bg-gray-800 text-danger text-xs font-bold px-2.5 py-0.5 rounded-full" x-text="belumCount"></span>
                            </div>
                            <div class="p-4 overflow-y-auto max-h-[50vh] custom-scrollbar">
                                <ul class="space-y-2">
                                    <template x-for="(lokasi, index) in modalBelum" :key="index">
                                        <li class="flex items-start gap-2.5 text-sm text-gray-700 dark:text-gray-300 py-2 border-b border-gray-100 dark:border-gray-800/50 last:border-0 last:pb-0">
                                            <span class="text-danger text-[10px] mt-1"><i class="fa-solid fa-location-dot"></i></span>
                                            <span x-text="lokasi" class="font-medium leading-tight"></span>
                                        </li>
                                    </template>
                                    <template x-if="belumCount === 0">
                                        <div class="flex flex-col items-center justify-center py-8 text-gray-400">
                                            <i class="fa-solid fa-folder-open text-2xl mb-2 opacity-50"></i>
                                            <span class="text-xs">Tidak ada data</span>
                                        </div>
                                    </template>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- Daftar Lokasi Selesai (Disembunyikan sementara) --}}
        <div style="display: none;">
            <x-table.card title="Daftar Lokasi KNMP Operasional" 
                          description="{{ count($lokasiSelesai) }} lokasi telah menyelesaikan seluruh tahapan pembangunan KNMP."
                          searchPlaceholder="Cari lokasi..."
                      :customTable="false">
            
            <x-table.thead>
                <x-table.th width="50px">No</x-table.th>
                <x-table.th>Nama Lokasi</x-table.th>
                <x-table.th>Kabupaten</x-table.th>
                <x-table.th>Provinsi</x-table.th>
                <x-table.th>Daerah</x-table.th>
                <x-table.th>Batch</x-table.th>
                <x-table.th align="center">Status</x-table.th>
            </x-table.thead>
    
            <x-table.tbody>
                @forelse ($lokasiSelesai as $idx => $lok)
                    <x-table.tr>
                        <x-table.td class="text-textMuted-light dark:text-textMuted-dark">{{ $idx + 1 }}</x-table.td>
                        <x-table.td class="font-semibold text-textMain-light dark:text-textMain-dark">{{ $lok['nama'] }}</x-table.td>
                        <x-table.td class="text-textMain-light dark:text-textMain-dark">{{ $lok['kabupaten'] }}</x-table.td>
                        <x-table.td class="text-textMain-light dark:text-textMain-dark">{{ $lok['provinsi'] }}</x-table.td>
                        <x-table.td>
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium {{ $lok['status'] === 'IKN' ? 'bg-teal-light/10 text-teal-light border border-teal-light/20' : 'bg-blue-100 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-800' }}">
                                {{ $lok['status'] }}
                            </span>
                        </x-table.td>
                        <x-table.td class="text-textMuted-light dark:text-textMuted-dark">{{ $lok['batch_name'] }}</x-table.td>
                        <x-table.td align="center">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-teal-light/10 text-teal-light dark:bg-teal-400/10 dark:text-teal-400 text-[10px] font-bold">
                                <i class="fa-solid fa-circle-check text-[8px]"></i> Selesai
                            </span>
                        </x-table.td>
                    </x-table.tr>
                @empty
                    <tr data-empty-row>
                        <x-table.td colspan="7" align="center" class="py-12 text-textMuted-light dark:text-textMuted-dark">
                            <i class="fa-solid fa-inbox text-3xl text-gray-300 dark:text-gray-600 mb-3 block"></i>
                            Belum ada lokasi yang menyelesaikan tahap operasional.
                        </x-table.td>
                    </tr>
                @endforelse
            </x-table.tbody>
        </x-table.card>
        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('sarprasDetailManager', () => ({
                isModalOpen: false,
                modalTitle: '',
                modalIcon: '',
                modalOperasional: [],
                modalBelum: [],
                
                get operasionalCount() {
                    return this.modalOperasional.length;
                },
                
                get belumCount() {
                    return this.modalBelum.length;
                },

                openModal(title, icon, operasional, belum) {
                    this.modalTitle = title;
                    this.modalIcon = icon;
                    this.modalOperasional = operasional;
                    this.modalBelum = belum;
                    this.isModalOpen = true;
                }
            }));
        });
    </script>
@endsection

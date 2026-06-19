@extends('layouts.app')

@section('title', 'KNMP - Data Tahap (Batch)')

@section('content')
<div x-data="{ 
    showModal: false, 
    isEdit: false, 
    formData: { id: '', nama_tahap: '', tahun: '' },
    modalTitle: 'Tambah Tahap (Batch)',
    
    openCreate() {
        this.isEdit = false;
        this.formData = { id: '', nama_tahap: '', tahun: new Date().getFullYear() };
        this.modalTitle = 'Tambah Tahap (Batch)';
        this.showModal = true;
    },
    
    openEdit(batch) {
        this.isEdit = true;
        this.formData = { id: batch.id, nama_tahap: batch.nama_tahap, tahun: batch.tahun };
        this.modalTitle = 'Edit Tahap (Batch)';
        this.showModal = true;
    },
    
    confirmDelete(id) {
        window.dispatchEvent(new CustomEvent('trigger-confirm', {
            detail: {
                title: 'Konfirmasi Hapus', 
                message: 'Apakah Anda yakin ingin menghapus data batch ini?', 
                type: 'danger', 
                confirmText: 'Ya, Hapus',
                onConfirm: () => {
                    document.getElementById('delete-form-' + id).submit();
                }
            }
        }));
    }
}">

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="mb-4 p-4 rounded-xl bg-success/10 border border-success/20 text-success text-sm flex items-center gap-3">
        <i class="fa-solid fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-4 p-4 rounded-xl bg-danger/10 border border-danger/20 text-danger text-sm flex items-center gap-3">
        <i class="fa-solid fa-triangle-exclamation"></i>
        {{ session('error') }}
    </div>
    @endif

    @if($errors->any())
    <div class="mb-4 p-4 rounded-xl bg-danger/10 border border-danger/20 text-danger text-sm flex flex-col gap-1">
        <div class="flex items-center gap-3 font-medium">
            <i class="fa-solid fa-triangle-exclamation"></i> Terdapat kesalahan:
        </div>
        <ul class="list-disc list-inside ml-5">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-semibold tracking-tight">Data Tahap (Batch)</h2>
            <p class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1">Kelola data tahapan atau batch pelaksanaan program KNMP.</p>
        </div>

        <div class="flex items-center gap-3">
            <button @click="openCreate()" class="bg-teal-light hover:bg-teal-600 text-white rounded-md px-4 py-2 text-xs font-medium transition-all flex items-center justify-between gap-2 shadow-sm"> 
                Input Batch <i class="fa-solid fa-plus"></i> 
            </button>
        </div>
    </div>

    <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl overflow-hidden shadow-sm">
        <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/20">
            <div class="relative w-full sm:w-64">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" placeholder="Cari tahap / tahun..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm outline-none focus:border-teal-light transition-colors">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
                    <tr>
                        <th class="px-6 py-4 w-16 text-center">ID</th>
                        <th class="px-6 py-4">Nama Tahap</th>
                        <th class="px-6 py-4">Tahun</th>
                        <th class="px-6 py-4 text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
                    @forelse($batches as $batch)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                        <td class="px-6 py-4 text-center font-medium text-textMuted-light">{{ $batch->id }}</td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-textMain-light dark:text-textMain-dark">{{ $batch->nama_tahap }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-teal-light/10 text-teal-light dark:bg-teal-500/10 dark:text-teal-400 rounded-md text-xs font-semibold">{{ $batch->tahun }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button @click="openEdit({{ json_encode($batch) }})" class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-teal-light hover:bg-teal-light/10 transition-colors flex items-center justify-center" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form :id="'delete-form-' + {{ $batch->id }}" action="{{ route('program.master.batch.destroy', $batch->id) }}" method="POST" class="inline-flex">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" @click="confirmDelete({{ $batch->id }})" class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-danger hover:text-white hover:bg-danger transition-colors flex items-center justify-center" title="Hapus">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-textMuted-light">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fa-solid fa-inbox text-3xl opacity-50 mb-2"></i>
                                <p>Belum ada data batch.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form -->
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showModal = false">
                <div class="absolute inset-0 bg-gray-900/75 backdrop-blur-sm"></div>
            </div>
            
            <!-- Modal panel -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative z-10 inline-block align-bottom bg-bgSurface-light dark:bg-bgSurface-dark rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full border border-gray-100 dark:border-gray-800">
                <form :action="isEdit ? '{{ url('master/knmp/batch') }}/' + formData.id : '{{ route('program.master.batch.store') }}'" method="POST">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    
                    <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/20">
                        <h3 class="text-base font-semibold text-textMain-light dark:text-textMain-dark flex items-center gap-2">
                            <i class="fa-solid" :class="isEdit ? 'fa-pen-to-square text-blue-500' : 'fa-plus text-teal-light'"></i>
                            <span x-text="modalTitle"></span>
                        </h3>
                        <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>
                    
                    <div class="p-6 space-y-5 bg-white dark:bg-bgSurface-dark">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-textMuted-light dark:text-textMuted-dark">Nama Tahap <span class="text-danger">*</span></label>
                            <div class="relative">
                                <i class="fa-solid fa-layer-group absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                <input type="text" name="nama_tahap" x-model="formData.nama_tahap" required placeholder="Contoh: I, II, III, Susulan" class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm outline-none focus:border-teal-light focus:ring-1 focus:ring-teal-light transition-all text-textMain-light dark:text-textMain-dark">
                            </div>
                            <p class="text-[10px] text-textMuted-light mt-1.5">Masukkan nama atau penomoran tahap (misal: "I" atau "Susulan").</p>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-textMuted-light dark:text-textMuted-dark">Tahun <span class="text-danger">*</span></label>
                            <div class="relative">
                                <i class="fa-regular fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                <input type="number" name="tahun" x-model="formData.tahun" required min="2000" max="2099" placeholder="Contoh: 2025" class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm outline-none focus:border-teal-light focus:ring-1 focus:ring-teal-light transition-all text-textMain-light dark:text-textMain-dark">
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-3 rounded-b-2xl border-t border-gray-100 dark:border-gray-800">
                        <button type="button" @click="showModal = false" class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-textMain-light dark:text-textMain-dark rounded-md text-xs font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-teal-light hover:bg-teal-600 text-white rounded-md text-xs font-medium transition-colors flex items-center justify-between gap-2 shadow-sm">
                            <i class="fa-solid fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

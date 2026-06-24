@extends('layouts.app')

@section('title', 'KNMP - Data Kriteria Lokasi')

@section('content')
<div x-data="{ 
    showModal: false, 
    isEdit: false, 
    formData: { id: '', nama_kriteria: '', bobot: 0, keterangan: '' },
    modalTitle: 'Tambah Kriteria Lokasi',
    
    openCreate() {
        this.isEdit = false;
        this.formData = { id: '', nama_kriteria: '', bobot: 0, keterangan: '' };
        this.modalTitle = 'Tambah Kriteria Lokasi';
        this.showModal = true;
    },
    
    openEdit(kriteria) {
        this.isEdit = true;
        this.formData = { id: kriteria.id, nama_kriteria: kriteria.nama_kriteria, bobot: kriteria.bobot, keterangan: kriteria.keterangan || '' };
        this.modalTitle = 'Edit Kriteria Lokasi';
        this.showModal = true;
    },
    
    confirmDelete(id) {
        window.dispatchEvent(new CustomEvent('trigger-confirm', {
            detail: {
                title: 'Konfirmasi Hapus', 
                message: 'Apakah Anda yakin ingin menghapus data kriteria lokasi ini?', 
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
            <h2 class="text-xl font-semibold tracking-tight">Data Kriteria Lokasi</h2>
            <p class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1">Kelola data kriteria untuk evaluasi calon lokasi KNMP.</p>
        </div>

        <div class="flex items-center gap-3">
            <button @click="openCreate()" class="bg-teal-light hover:bg-teal-600 text-white rounded-md px-4 py-2 text-xs font-medium transition-all flex items-center justify-between gap-2 shadow-sm"> 
                Input Kriteria <i class="fa-solid fa-plus"></i> 
            </button>
        </div>
    </div>

    <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl overflow-hidden shadow-sm">
        <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/20">
            <div class="relative w-full sm:w-64">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" placeholder="Cari kriteria..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm outline-none focus:border-teal-light transition-colors">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
                    <tr>
                        <th class="px-6 py-4 w-16 text-center">ID</th>
                        <th class="px-6 py-4">Nama Kriteria</th>
                        <th class="px-6 py-4">Bobot</th>
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4 text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
                    @forelse($kriterias as $kriteria)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                        <td class="px-6 py-4 text-center font-medium text-textMuted-light">{{ $kriteria->id }}</td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-textMain-light dark:text-textMain-dark">{{ $kriteria->nama_kriteria }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-teal-light/10 text-teal-light dark:bg-teal-500/10 dark:text-teal-400 rounded-md text-xs font-semibold">{{ $kriteria->bobot }}%</span>
                        </td>
                        <td class="px-6 py-4 text-gray-500 truncate max-w-xs">
                            {{ $kriteria->keterangan ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button @click="openEdit({{ json_encode($kriteria) }})" class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-teal-light hover:bg-teal-light/10 transition-colors flex items-center justify-center" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form :id="'delete-form-' + {{ $kriteria->id }}" action="{{ route('program.master.kriteria-lokasi.destroy', $kriteria->id) }}" method="POST" class="inline-flex">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" @click="confirmDelete({{ $kriteria->id }})" class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-danger hover:text-white hover:bg-danger transition-colors flex items-center justify-center" title="Hapus">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-textMuted-light">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fa-solid fa-inbox text-3xl opacity-50 mb-2"></i>
                                <p>Belum ada data kriteria lokasi.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form -->
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            <div @click.away="showModal = false"
                 x-show="showModal"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                 class="bg-white dark:bg-gray-900 rounded-2xl w-full max-w-md p-6 shadow-2xl border border-gray-100 dark:border-gray-800 relative mx-4">
                <form :action="isEdit ? '{{ url('master/knmp/kriteria-lokasi') }}/' + formData.id : '{{ route('program.master.kriteria-lokasi.store') }}'" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="PUT" x-bind:disabled="!isEdit">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-textMain-light dark:text-textMain-dark" x-text="modalTitle"></h3>
                        <button type="button" @click="showModal = false" class="text-gray-400 hover:text-danger transition-colors">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Nama Kriteria <span class="text-danger">*</span></label>
                            <div class="relative">
                                <i class="fa-solid fa-tag absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                <input type="text" name="nama_kriteria" x-model="formData.nama_kriteria" required placeholder="Contoh: Sertifikat Tanah" class="w-full pl-11 pr-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:border-teal-light focus:ring-1 focus:ring-teal-light transition-all text-textMain-light dark:text-textMain-dark">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Bobot (%) <span class="text-danger">*</span></label>
                            <div class="relative">
                                <i class="fa-solid fa-weight-scale absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                <input type="number" name="bobot" x-model="formData.bobot" required min="0" max="100" placeholder="Contoh: 10" class="w-full pl-11 pr-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:border-teal-light focus:ring-1 focus:ring-teal-light transition-all text-textMain-light dark:text-textMain-dark">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Keterangan</label>
                            <textarea name="keterangan" x-model="formData.keterangan" rows="3" placeholder="Opsional: Penjelasan detail terkait kriteria ini..." class="w-full px-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:border-teal-light focus:ring-1 focus:ring-teal-light transition-all text-textMain-light dark:text-textMain-dark"></textarea>
                        </div>
                    </div>
                    
                    <div class="mt-8 flex justify-end gap-3">
                        <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-teal-light text-white rounded-lg text-sm font-medium hover:bg-teal-600 transition-colors flex items-center gap-2">
                            <i class="fa-solid fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

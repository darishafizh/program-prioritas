@extends('layouts.app')

@section('title', 'KNMP - Data Vendor / Penyedia')

@section('content')
<div x-data="{ 
    showModal: false, 
    isEdit: false, 
    formData: { id: '', nama: '', npwp: '', direktur_utama: '', kontak: '', kualifikasi_sbu: '', status: 'Aktif' },
    modalTitle: 'Tambah Data Vendor',
    
    openCreate() {
        this.isEdit = false;
        this.formData = { id: '', nama: '', npwp: '', direktur_utama: '', kontak: '', kualifikasi_sbu: '', status: 'Aktif' };
        this.modalTitle = 'Tambah Data Vendor';
        this.showModal = true;
    },
    
    openEdit(vendor) {
        this.isEdit = true;
        this.formData = { 
            id: vendor.id, 
            nama: vendor.nama, 
            npwp: vendor.npwp || '', 
            direktur_utama: vendor.direktur_utama || '', 
            kontak: vendor.kontak || '', 
            kualifikasi_sbu: vendor.kualifikasi_sbu || '', 
            status: vendor.status || 'Aktif'
        };
        this.modalTitle = 'Edit Data Vendor';
        this.showModal = true;
    },
    
    confirmDelete(id) {
        window.dispatchEvent(new CustomEvent('trigger-confirm', {
            detail: {
                title: 'Konfirmasi Hapus', 
                message: 'Apakah Anda yakin ingin menghapus data vendor ini?', 
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
            <h2 class="text-xl font-semibold tracking-tight">Data Vendor / Penyedia</h2>
            <p class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1">Direktori perusahaan kontraktor pelaksana proyek KNMP berserta status performansinya.</p>
        </div>

        <div class="flex items-center gap-3">
            <button @click="openCreate()" class="bg-teal-light hover:bg-teal-600 text-white rounded-md px-4 py-2 text-xs font-medium transition-all flex items-center justify-between gap-2 shadow-sm"> 
                Input Vendor <i class="fa-solid fa-plus"></i> 
            </button>
        </div>
    </div>

    <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl overflow-hidden shadow-sm">
        <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/20">
            <div class="relative w-full sm:w-64">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" placeholder="Cari nama vendor / NPWP..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm outline-none focus:border-teal-light transition-colors">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
                    <tr>
                        <th class="px-6 py-4">Nama Perusahaan</th>
                        <th class="px-6 py-4">NPWP</th>
                        <th class="px-6 py-4">Direktur Utama</th>
                        <th class="px-6 py-4">Kontak / Email</th>
                        <th class="px-6 py-4">Kualifikasi SBU</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
                    @forelse($vendors as $vendor)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-medium text-textMain-light dark:text-textMain-dark">{{ $vendor->nama }}</div>
                            <div class="text-[10px] text-textMuted-light mt-0.5">ID: {{ $vendor->id }}</div>
                        </td>
                        <td class="px-6 py-4 font-mono text-xs text-textMuted-light">{{ $vendor->npwp ?: '-' }}</td>
                        <td class="px-6 py-4 text-textMuted-light">{{ $vendor->direktur_utama ?: '-' }}</td>
                        <td class="px-6 py-4 text-textMuted-light">{{ $vendor->kontak ?: '-' }}</td>
                        <td class="px-6 py-4">
                            @if($vendor->kualifikasi_sbu)
                                <span class="px-2 py-1 bg-gray-100 dark:bg-gray-800 rounded text-xs font-medium text-gray-600 dark:text-gray-400">{{ $vendor->kualifikasi_sbu }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if(strtolower($vendor->status) == 'aktif')
                                <span class="px-2 py-1 bg-success/10 text-success text-xs font-medium rounded-md">Aktif</span>
                            @elseif(strtolower($vendor->status) == 'blacklist')
                                <span class="px-2 py-1 bg-danger/10 text-danger text-xs font-medium rounded-md"><i class="fa-solid fa-ban"></i> Blacklist</span>
                            @else
                                <span class="px-2 py-1 bg-warning/10 text-warning text-xs font-medium rounded-md">{{ $vendor->status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button @click="openEdit({{ json_encode($vendor) }})" class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-teal-light hover:bg-teal-light/10 transition-colors flex items-center justify-center" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form :id="'delete-form-' + {{ $vendor->id }}" action="{{ route('program.master.vendor.destroy', $vendor->id) }}" method="POST" class="inline-flex">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" @click="confirmDelete({{ $vendor->id }})" class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-danger hover:text-white hover:bg-danger transition-colors flex items-center justify-center" title="Hapus">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-textMuted-light">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fa-solid fa-building-user text-3xl opacity-50 mb-2"></i>
                                <p>Belum ada data vendor/penyedia.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form -->
    <!-- Modal Form -->
    <div x-show="showModal" style="display: none; z-index: 99999;" class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
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
             class="bg-white dark:bg-gray-900 rounded-2xl w-full max-w-md p-6 shadow-2xl border border-gray-100 dark:border-gray-800 relative mx-4 max-h-[90vh] overflow-y-auto">
            <form :action="isEdit ? '{{ url('master/knmp/vendor') }}/' + formData.id : '{{ route('program.master.vendor.store') }}'" method="POST">
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
                        <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Nama Perusahaan <span class="text-danger">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-building absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                            <input type="text" name="nama" x-model="formData.nama" required placeholder="Contoh: PT Samudera Konstruksi" class="w-full pl-11 pr-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:border-teal-light focus:ring-1 focus:ring-teal-light transition-all text-textMain-light dark:text-textMain-dark">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">NPWP</label>
                        <div class="relative">
                            <i class="fa-solid fa-id-card absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                            <input type="text" name="npwp" x-model="formData.npwp" placeholder="01.234.567.8-901.000" class="w-full pl-11 pr-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:border-teal-light focus:ring-1 focus:ring-teal-light transition-all text-textMain-light dark:text-textMain-dark">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Direktur Utama</label>
                        <div class="relative">
                            <i class="fa-solid fa-user-tie absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                            <input type="text" name="direktur_utama" x-model="formData.direktur_utama" placeholder="Nama Pimpinan" class="w-full pl-11 pr-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:border-teal-light focus:ring-1 focus:ring-teal-light transition-all text-textMain-light dark:text-textMain-dark">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Kontak / Email</label>
                        <div class="relative">
                            <i class="fa-solid fa-address-book absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                            <input type="text" name="kontak" x-model="formData.kontak" placeholder="0812... / email@..." class="w-full pl-11 pr-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:border-teal-light focus:ring-1 focus:ring-teal-light transition-all text-textMain-light dark:text-textMain-dark">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Kualifikasi SBU</label>
                        <div class="relative">
                            <i class="fa-solid fa-certificate absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                            <input type="text" name="kualifikasi_sbu" x-model="formData.kualifikasi_sbu" placeholder="Menengah (M1)" class="w-full pl-11 pr-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:border-teal-light focus:ring-1 focus:ring-teal-light transition-all text-textMain-light dark:text-textMain-dark">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Status <span class="text-danger">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-heart-pulse absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                            <select name="status" x-model="formData.status" required class="w-full pl-11 pr-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:border-teal-light focus:ring-1 focus:ring-teal-light transition-all text-textMain-light dark:text-textMain-dark appearance-none">
                                <option value="Aktif">Aktif</option>
                                <option value="Blacklist">Blacklist</option>
                                <option value="Suspend">Suspend</option>
                                <option value="Tidak Aktif">Tidak Aktif</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                        </div>
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

@extends('layouts.app')

@section('title', 'KNMP - Data Vendor / Penyedia')

@section('content')
<div x-data="vendorManager()" class="space-y-6">
    
    <!-- Flash & Error Messages -->
    @if(session('success'))
    <div class="p-4 rounded-xl bg-success/10 border border-success/20 text-success text-sm flex items-center gap-3 font-medium">
        <i class="fa-solid fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 rounded-xl bg-danger/10 border border-danger/20 text-danger text-sm flex items-center gap-3 font-medium">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="p-4 rounded-xl bg-danger/10 border border-danger/20 text-danger text-sm flex flex-col gap-1">
        <div class="flex items-center gap-3 font-medium">
            <i class="fa-solid fa-triangle-exclamation"></i> Terdapat kesalahan input:
        </div>
        <ul class="list-disc list-inside ml-5 text-xs">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <x-table.card title="Data Vendor / Penyedia" 
                  description="Direktori perusahaan kontraktor pelaksana proyek KNMP beserta status performansinya."
                  :customTable="false">
        
        <x-slot:search>
            <div class="relative w-full sm:w-64">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" x-model="search" placeholder="Cari nama vendor / NPWP..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm outline-none focus:border-teal-light transition-colors text-textMain-light dark:text-textMain-dark">
            </div>
        </x-slot:search>

        <x-slot:actions>
            <div class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto">
                <select x-model="filterStatus" class="w-full sm:w-auto px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-xs focus:border-teal-light focus:ring-1 outline-none font-medium text-textMain-light dark:text-textMain-dark">
                    <option value="">Semua Status</option>
                    <option value="Aktif">Aktif</option>
                    <option value="Blacklist">Blacklist</option>
                    <option value="Suspend">Suspend</option>
                    <option value="Tidak Aktif">Tidak Aktif</option>
                </select>
                <x-button-add @click="openModal('add')" label="Tambah Vendor" icon="fa-plus" />
            </div>
        </x-slot:actions>

        <x-table.thead>
            <x-table.th>Nama Perusahaan</x-table.th>
            <x-table.th>NPWP</x-table.th>
            <x-table.th>Direktur Utama</x-table.th>
            <x-table.th>Kontak / Email</x-table.th>
            <x-table.th>Kualifikasi SBU</x-table.th>
            <x-table.th>Status</x-table.th>
            <x-table.th align="right">Aksi</x-table.th>
        </x-table.thead>

        <x-table.tbody>
            <template x-for="vendor in filteredVendors" :key="vendor.id">
                <x-table.tr>
                    <x-table.td>
                        <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="vendor.nama"></div>
                        <div class="text-[10px] text-textMuted-light mt-0.5" x-text="'ID: ' + vendor.id"></div>
                    </x-table.td>
                    <x-table.td class="font-mono text-xs text-textMuted-light dark:text-textMuted-dark" x-text="vendor.npwp || '-'"></x-table.td>
                    <x-table.td class="text-textMuted-light dark:text-textMuted-dark" x-text="vendor.direktur_utama || '-'"></x-table.td>
                    <x-table.td class="text-textMuted-light dark:text-textMuted-dark" x-text="vendor.kontak || '-'"></x-table.td>
                    <x-table.td>
                        <template x-if="vendor.kualifikasi_sbu">
                            <span class="px-2 py-1 bg-gray-100 dark:bg-gray-800 rounded-lg text-xs font-medium text-gray-600 dark:text-gray-400" x-text="vendor.kualifikasi_sbu"></span>
                        </template>
                        <template x-if="!vendor.kualifikasi_sbu">
                            <span>-</span>
                        </template>
                    </x-table.td>
                    <x-table.td>
                        <template x-if="vendor.status.toLowerCase() === 'aktif'">
                            <span class="px-2.5 py-1 bg-success/10 text-success text-xs font-medium rounded-lg inline-flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-success"></span> Aktif
                            </span>
                        </template>
                        <template x-if="vendor.status.toLowerCase() === 'blacklist'">
                            <span class="px-2.5 py-1 bg-danger/10 text-danger text-xs font-medium rounded-lg inline-flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-danger"></span> Blacklist
                            </span>
                        </template>
                        <template x-if="vendor.status.toLowerCase() !== 'aktif' && vendor.status.toLowerCase() !== 'blacklist'">
                            <span class="px-2.5 py-1 bg-warning/10 text-warning text-xs font-medium rounded-lg inline-flex items-center gap-1.5" x-text="vendor.status"></span>
                        </template>
                    </x-table.td>
                    <x-table.td align="right">
                        <x-table.action-buttons on-edit="openModal('edit', vendor)"
                                                on-delete="openDeleteConfirm(vendor)" />
                    </x-table.td>
                </x-table.tr>
            </template>
            <tr x-show="filteredVendors.length === 0" data-empty-row>
                <x-table.td colspan="7" align="center" class="py-8 text-textMuted-light">Tidak ada data vendor/penyedia yang ditemukan.</x-table.td>
            </tr>
        </x-table.tbody>
    </x-table.card>

    @include('programs.knmp.master.vendor.create')
    @include('programs.knmp.master.vendor.edit')

    <form id="deleteForm" action="" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('vendorManager', () => ({
        vendors: @json($vendors),
        search: '',
        filterStatus: '',
        
        isCreateOpen: false,
        isEditOpen: false,
        
        formData: {
            id: '',
            nama: '',
            npwp: '',
            direktur_utama: '',
            kontak: '',
            kualifikasi_sbu: '',
            status: 'Aktif'
        },

        init() {
            @if(isset($openCreateModal) && $openCreateModal)
                this.openModal('add');
            @endif
            @if(isset($openEditModal) && $openEditModal && isset($editVendor))
                this.openModal('edit', @json($editVendor));
            @endif
        },

        get filteredVendors() {
            let res = this.vendors;
            
            if (this.filterStatus) {
                res = res.filter(v => v.status && v.status.toLowerCase() === this.filterStatus.toLowerCase());
            }
            
            if (this.search) {
                const q = this.search.toLowerCase();
                res = res.filter(v => 
                    (v.nama && v.nama.toLowerCase().includes(q)) ||
                    (v.npwp && v.npwp.toLowerCase().includes(q))
                );
            }
            
            return res;
        },

        openModal(mode, vendor = null) {
            if (mode === 'add') {
                this.formData = { id: '', nama: '', npwp: '', direktur_utama: '', kontak: '', kualifikasi_sbu: '', status: 'Aktif' };
                this.isCreateOpen = true;
                this.isEditOpen = false;
            } else if (mode === 'edit' && vendor) {
                this.formData = { 
                    id: vendor.id, 
                    nama: vendor.nama || '', 
                    npwp: vendor.npwp || '', 
                    direktur_utama: vendor.direktur_utama || '', 
                    kontak: vendor.kontak || '', 
                    kualifikasi_sbu: vendor.kualifikasi_sbu || '', 
                    status: vendor.status || 'Aktif'
                };
                this.isEditOpen = true;
                this.isCreateOpen = false;
            }
        },

        openDeleteConfirm(item) {
            window.dispatchEvent(new CustomEvent('trigger-confirm', {
                detail: {
                    title: 'Konfirmasi Penghapusan',
                    message: `Apakah Anda yakin ingin menghapus vendor "${item.nama}"? Data yang dihapus tidak dapat dipulihkan.`,
                    confirmText: 'Ya, Hapus Vendor',
                    cancelText: 'Batal',
                    type: 'danger',
                    onConfirm: () => {
                        const form = document.getElementById('deleteForm');
                        form.action = `{{ url('master/knmp/vendor') }}/${item.id}`;
                        form.submit();
                    }
                }
            }));
        }
    }));
});
</script>
@endsection

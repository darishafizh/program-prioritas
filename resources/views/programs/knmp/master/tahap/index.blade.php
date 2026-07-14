@extends('layouts.app')

@section('title', 'KNMP - Master Data Tahap')

@section('content')
<div x-data="tahapManager()" class="space-y-6">
    
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

    <x-table.card title="Data Tahap Pelaksanaan" 
                  description="Kelola data tahapan pelaksanaan atau penomoran batch program KNMP."
                  :customTable="false">
        
        <x-slot:search>
            <div class="relative w-full sm:w-64">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" x-model="search" placeholder="Cari nama tahap / tahun..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm outline-none focus:border-teal-light transition-colors text-textMain-light dark:text-textMain-dark">
            </div>
        </x-slot:search>

        <x-slot:actions>
            <div class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto">
                <select x-model="filterTahun" class="w-full sm:w-auto px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-xs focus:border-teal-light focus:ring-1 outline-none font-medium text-textMain-light dark:text-textMain-dark">
                    <option value="">Semua Tahun</option>
                    @foreach($tahaps->pluck('tahun')->unique()->sortDesc() as $yr)
                        <option value="{{ $yr }}">{{ $yr }}</option>
                    @endforeach
                </select>
                <x-button-add @click="openModal('add')" label="Tambah Tahap" icon="fa-plus" />
            </div>
        </x-slot:actions>

        <x-table.thead>
            <x-table.th align="center" width="80px">ID</x-table.th>
            <x-table.th>Nama Tahap</x-table.th>
            <x-table.th>Tahun</x-table.th>
            <x-table.th align="right">Aksi</x-table.th>
        </x-table.thead>

        <x-table.tbody>
            <template x-for="tahap in filteredTahaps" :key="tahap.id">
                <x-table.tr>
                    <x-table.td align="center" class="font-medium text-textMuted-light dark:text-textMuted-dark" x-text="tahap.id"></x-table.td>
                    <x-table.td>
                        <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="tahap.nama_tahap"></div>
                    </x-table.td>
                    <x-table.td>
                        <span class="px-2.5 py-1 bg-teal-light/10 text-teal-light dark:bg-teal-500/10 dark:text-teal-400 rounded-lg text-xs font-semibold" x-text="tahap.tahun"></span>
                    </x-table.td>
                    <x-table.td align="right">
                        <x-table.action-buttons on-edit="openModal('edit', tahap)"
                                                on-delete="openDeleteConfirm(tahap)" />
                    </x-table.td>
                </x-table.tr>
            </template>
            <tr x-show="filteredTahaps.length === 0" data-empty-row>
                <x-table.td colspan="4" align="center" class="py-8 text-textMuted-light">Tidak ada data tahap terdaftar.</x-table.td>
            </tr>
        </x-table.tbody>
    </x-table.card>

    @include('programs.knmp.master.tahap.create')
    @include('programs.knmp.master.tahap.edit')

    <form id="deleteForm" action="" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('tahapManager', () => ({
        tahaps: @json($tahaps),
        search: '',
        filterTahun: '',
        
        isCreateOpen: false,
        isEditOpen: false,
        
        formData: {
            id: '',
            nama_tahap: '',
            tahun: '{{ date("Y") }}'
        },

        init() {
            @if(isset($openCreateModal) && $openCreateModal)
                this.openModal('add');
            @endif
            @if(isset($openEditModal) && $openEditModal && isset($editTahap))
                this.openModal('edit', @json($editTahap));
            @endif
        },

        get filteredTahaps() {
            let res = this.tahaps;
            
            if (this.filterTahun) {
                res = res.filter(t => t.tahun == this.filterTahun);
            }
            
            if (this.search) {
                const q = this.search.toLowerCase();
                res = res.filter(t => 
                    (t.nama_tahap && t.nama_tahap.toLowerCase().includes(q)) ||
                    (t.tahun && String(t.tahun).includes(q))
                );
            }
            
            return res;
        },

        openModal(mode, tahap = null) {
            if (mode === 'add') {
                this.formData = { id: '', nama_tahap: '', tahun: '{{ date("Y") }}' };
                this.isCreateOpen = true;
                this.isEditOpen = false;
            } else if (mode === 'edit' && tahap) {
                this.formData = { 
                    id: tahap.id, 
                    nama_tahap: tahap.nama_tahap || '', 
                    tahun: tahap.tahun || '{{ date("Y") }}'
                };
                this.isEditOpen = true;
                this.isCreateOpen = false;
            }
        },

        openDeleteConfirm(item) {
            window.dispatchEvent(new CustomEvent('trigger-confirm', {
                detail: {
                    title: 'Konfirmasi Penghapusan',
                    message: `Apakah Anda yakin ingin menghapus tahap "${item.nama_tahap}" (Tahun: ${item.tahun})? Data yang dihapus tidak dapat dipulihkan.`,
                    confirmText: 'Ya, Hapus Tahap',
                    cancelText: 'Batal',
                    type: 'danger',
                    onConfirm: () => {
                        const form = document.getElementById('deleteForm');
                        form.action = `{{ url('master/knmp/tahap') }}/${item.id}`;
                        form.submit();
                    }
                }
            }));
        }
    }));
});
</script>
@endsection

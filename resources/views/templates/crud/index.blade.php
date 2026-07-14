{{-- 
    ===================================================================
    TEMPLATE DASAR CRUD (INDEX VIEW)
    ===================================================================
    File ini adalah template referensi standar untuk pembuatan modul CRUD
    di dalam sistem Portal Program Prioritas. Mengadopsi komponen-komponen
    dari folder `resources/views/components/` agar kode singkat & rapi.
--}}

@extends('layouts.app')

@section('title', 'KNMP - Template Dasar CRUD')

@section('content')
<div x-data="crudTemplateManager()" class="space-y-6">
    
    <!-- Flash Messages (Dapat dijadikan komponen atau disertakan di layout) -->
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

    <!-- 
        Komponen Tabel Utama
        Menyediakan kontainer card, judul, search bar interaktif, serta dropdown perPage.
    -->
    <x-table.card title="Template Dasar Manajemen Referensi (CRUD)" 
                  description="Gunakan template ini sebagai referensi untuk membangun halaman master data baru yang cepat, bersih, dan konsisten."
                  searchPlaceholder="Cari kode atau nama referensi..."
                  :customTable="false">
        
        <!-- Action Slot (Tombol Tambah) -->
        <x-slot:actions>
            <x-button-add @click="openModal('add')" label="Tambah Referensi" icon="fa-plus" />
        </x-slot:actions>

        <!-- Thead Menggunakan Komponen table.thead & table.th -->
        <x-table.thead>
            <x-table.th align="center" width="80px">ID</x-table.th>
            <x-table.th>Kode</x-table.th>
            <x-table.th>Nama Referensi</x-table.th>
            <x-table.th>Kategori</x-table.th>
            <x-table.th>Status</x-table.th>
            <x-table.th align="right">Aksi</x-table.th>
        </x-table.thead>

        <!-- Tbody Menggunakan Komponen table.tbody, table.tr, table.td -->
        <x-table.tbody>
            @forelse($items as $item)
                <x-table.tr>
                    <x-table.td align="center" class="font-medium text-textMuted-light dark:text-textMuted-dark">{{ $item['id'] }}</x-table.td>
                    <x-table.td class="font-mono font-semibold text-teal-light">{{ $item['kode'] }}</x-table.td>
                    <x-table.td>
                        <div class="font-medium text-textMain-light dark:text-textMain-dark">{{ $item['nama'] }}</div>
                        @if(!empty($item['keterangan']))
                            <div class="text-[11px] text-textMuted-light dark:text-textMuted-dark mt-0.5">{{ $item['keterangan'] }}</div>
                        @endif
                    </x-table.td>
                    <x-table.td>
                        <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-800 rounded-lg text-xs font-medium text-gray-600 dark:text-gray-400">{{ $item['kategori'] }}</span>
                    </x-table.td>
                    <x-table.td>
                        @if($item['status'] === 'Aktif')
                            <span class="px-2.5 py-1 bg-success/10 text-success text-xs font-medium rounded-lg inline-flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-success"></span> Aktif
                            </span>
                        @else
                            <span class="px-2.5 py-1 bg-gray-200/50 dark:bg-gray-800 text-gray-500 text-xs font-medium rounded-lg inline-flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Tidak Aktif
                            </span>
                        @endif
                    </x-table.td>
                    <x-table.td align="right">
                        <!-- Komponen Tombol Aksi Standar (table.action-buttons) -->
                        <x-table.action-buttons on-edit="openModal('edit', {{ json_encode($item) }})"
                                                on-delete="openDeleteConfirm({{ json_encode($item) }})" />
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr data-empty-row>
                    <x-table.td colspan="6" align="center" class="py-8 text-textMuted-light">Belum ada data referensi yang tersedia.</x-table.td>
                </x-table.tr>
            @endforelse
        </x-table.tbody>
    </x-table.card>

    <!-- Included Partial Modals (Memanfaatkan praktik pemisahan file) -->
    @include('templates.crud.create')
    @include('templates.crud.edit')

    <!-- Hidden Form untuk Eksekusi Hapus yang dipicu oleh global confirm-modal -->
    <form id="deleteForm" action="" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('crudTemplateManager', () => ({
        isCreateOpen: false,
        isEditOpen: false,
        
        formData: {
            id: '',
            kode: '',
            nama: '',
            kategori: '',
            status: 'Aktif',
            keterangan: ''
        },

        init() {
            // Memeriksa jika dibuka langsung via URL create/edit
            @if(isset($openCreateModal) && $openCreateModal)
                this.openModal('add');
            @endif
            @if(isset($openEditModal) && $openEditModal && isset($editItem))
                this.openModal('edit', @json($editItem));
            @endif
        },

        openModal(mode, item = null) {
            if (mode === 'add') {
                this.formData = { id: '', kode: '', nama: '', kategori: '', status: 'Aktif', keterangan: '' };
                this.isCreateOpen = true;
                this.isEditOpen = false;
            } else if (mode === 'edit' && item) {
                this.formData = { 
                    id: item.id, 
                    kode: item.kode || '', 
                    nama: item.nama || '', 
                    kategori: item.kategori || '', 
                    status: item.status || 'Aktif',
                    keterangan: item.keterangan || ''
                };
                this.isEditOpen = true;
                this.isCreateOpen = false;
            }
        },

        openDeleteConfirm(item) {
            // Memanggil global event untuk membuka komponen confirm-modal
            window.dispatchEvent(new CustomEvent('trigger-confirm', {
                detail: {
                    title: 'Konfirmasi Penghapusan',
                    message: `Apakah Anda yakin ingin menghapus data "${item.nama}" (Kode: ${item.kode})? Data yang dihapus tidak dapat dipulihkan.`,
                    confirmText: 'Ya, Hapus Data',
                    cancelText: 'Batal',
                    type: 'danger',
                    onConfirm: () => {
                        const form = document.getElementById('deleteForm');
                        form.action = `{{ url('master/knmp/template-crud') }}/${item.id}`;
                        form.submit();
                    }
                }
            }));
        }
    }));
});
</script>
@endsection

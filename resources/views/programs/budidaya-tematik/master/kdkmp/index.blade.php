@extends('layouts.app')

@section('title', 'Budidaya Tematik - Master Data KDKMP')

@section('content')
<div x-data="kdkmpManager()" class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-semibold tracking-tight">Master Data KDKMP</h2>
            <p class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1">Kelola data referensi kelompok (Kelompok Pembudidaya dan Masyarakat Pengawas), lokasi, komoditas, ketua, dan penyuluh.</p>
        </div>
    </div>

    <!-- Feedback Message -->
    <template x-if="notification.show">
        <div x-transition class="p-4 rounded-xl flex items-center gap-3 text-sm font-medium"
             :class="notification.type === 'success' ? 'bg-success/10 text-success border border-success/20' : 'bg-danger/10 text-danger border border-danger/20'">
            <i class="fa-solid" :class="notification.type === 'success' ? 'fa-check-circle' : 'fa-circle-exclamation'"></i>
            <span x-text="notification.message"></span>
            <button @click="notification.show = false" class="ml-auto opacity-70 hover:opacity-100"><i class="fa-solid fa-xmark"></i></button>
        </div>
    </template>

    <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl overflow-hidden flex flex-col">
        <!-- Header -->
        <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="font-medium text-sm flex items-center gap-2">
                    <i class="fa-solid fa-table-list text-teal-light"></i> Tabel Data Master KDKMP
                </h3>
                <p class="text-xs text-textMuted-light mt-1">Daftar referensi lengkap Kelompok Pembudidaya Ikan (KDKMP) program Budidaya Tematik.</p>
            </div>
            <div class="flex gap-2 w-full sm:w-auto self-end sm:self-auto">
                <button @click="openModal('add')" class="px-4 py-2 bg-teal-light text-white rounded-xl text-xs font-medium hover:bg-teal-light/90 transition-all flex items-center gap-2"> 
                    <i class="fa-solid fa-plus"></i> Tambah KDKMP
                </button>
            </div>
        </div>

        <!-- Toolbar: Filter + Search -->
        <div class="px-6 py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gray-50/50 dark:bg-gray-800/20">
            <!-- Show entries -->
            <div class="flex items-center gap-2 text-xs text-textMuted-light dark:text-textMuted-dark">
                <span>Tampilkan</span>
                <select x-model="pageSize" @change="currentPage = 1"
                    class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-md px-2 py-1.5 text-xs focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark font-medium">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="all">Semua</option>
                </select>
                <span>entri</span>
            </div>

            <!-- Search bar & filters -->
            <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto justify-end">
                <select x-model="filterProvinsi" @change="currentPage = 1" class="px-3 py-2 rounded-md border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-xs focus:border-teal-light outline-none font-medium text-textMain-light dark:text-textMain-dark transition-all">
                    <option value="">Semua Provinsi</option>
                    <template x-for="prov in provinsiList" :key="prov">
                        <option :value="prov" x-text="prov"></option>
                    </template>
                </select>
                
                <select x-model="filterKabupaten" @change="currentPage = 1" class="px-3 py-2 rounded-md border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-xs focus:border-teal-light outline-none font-medium text-textMain-light dark:text-textMain-dark transition-all">
                    <option value="">Semua Kabupaten</option>
                    <template x-for="kab in availableKabupatenList" :key="kab">
                        <option :value="kab" x-text="kab"></option>
                    </template>
                </select>

                <select x-model="filterKomoditas" @change="currentPage = 1" class="px-3 py-2 rounded-md border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-xs focus:border-teal-light outline-none font-medium text-textMain-light dark:text-textMain-dark transition-all">
                    <option value="">Semua Komoditas</option>
                    <template x-for="kom in komoditasList" :key="kom">
                        <option :value="kom" x-text="kom"></option>
                    </template>
                </select>

                <div class="relative w-full sm:w-64">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" x-model="search" @input="currentPage = 1" placeholder="Cari KDKMP, ketua, penyuluh..." class="w-full pl-8 pr-4 py-2 rounded-md border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-xs focus:border-teal-light outline-none transition-all text-textMain-light dark:text-textMain-dark">
                </div>
            </div>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
                    <tr>
                        <th class="py-4 px-6 align-middle border-r border-gray-100 dark:border-gray-800">Nama KDKMP & Lokasi</th>
                        <th class="py-4 px-6 align-middle border-r border-gray-100 dark:border-gray-800">Komoditas</th>
                        <th class="py-4 px-6 align-middle border-r border-gray-100 dark:border-gray-800">Ketua & No. Telepon</th>
                        <th class="py-4 px-6 align-middle border-r border-gray-100 dark:border-gray-800">Penyuluh & No. Telepon</th>
                        <th class="py-4 px-6 text-center align-middle">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
                    <template x-for="item in paginatedItems" :key="item.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                            <td class="px-6 py-4 border-r border-gray-100 dark:border-gray-800">
                                <div class="font-medium text-textMain-light dark:text-textMain-dark text-sm" x-text="item.nama_kdkmp || '-'"></div>
                                <div class="text-[11px] text-textMuted-light mt-1 flex items-center gap-1.5">
                                    <i class="fa-solid fa-location-dot text-teal-light"></i>
                                    <span x-text="formatLokasi(item)"></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 border-r border-gray-100 dark:border-gray-800">
                                <span class="px-2.5 py-1 rounded-md text-[0.65rem] font-medium bg-navy-light/10 text-textMain-light dark:bg-teal-900/30 dark:text-teal-400 uppercase tracking-wide" x-text="item.komoditas || '-'"></span>
                            </td>
                            <td class="px-6 py-4 border-r border-gray-100 dark:border-gray-800">
                                <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="item.ketua_anggota || '-'"></div>
                                <div x-show="item.no_hp" class="text-[10px] text-textMuted-light mt-1 flex items-center gap-1">
                                    <i class="fa-solid fa-phone"></i> <span x-text="item.no_hp"></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 border-r border-gray-100 dark:border-gray-800">
                                <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="item.nama_penyuluh || '-'"></div>
                                <div x-show="item.no_hp_penyuluh" class="text-[10px] text-textMuted-light mt-1 flex items-center gap-1">
                                    <i class="fa-solid fa-phone"></i> <span x-text="item.no_hp_penyuluh"></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <button @click="openModal('edit', item)" class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-all flex items-center justify-center text-xs" title="Edit Data">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <button @click="openModal('delete', item)" class="w-8 h-8 rounded-md bg-danger/10 text-danger hover:bg-danger hover:text-white transition-all flex items-center justify-center text-xs" title="Hapus Data">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>

                    <!-- Empty State -->
                    <tr x-show="filteredItems.length === 0">
                        <td colspan="5" class="px-6 py-8 text-center text-textMuted-light">Belum ada data KDKMP atau tidak ada hasil pencarian.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer: Info + Pagination -->
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50 dark:bg-gray-800/20">
            <!-- Info total data -->
            <div class="text-xs text-textMuted-light dark:text-textMuted-dark">
                Menampilkan <span class="font-medium text-textMain-light dark:text-textMain-dark" x-text="paginatedItems.length"></span> dari <span class="font-medium text-textMain-light dark:text-textMain-dark" x-text="filteredItems.length"></span> data
            </div>

            <!-- Pagination -->
            <div class="flex gap-1" x-show="totalPages > 1">
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

                <button @click="currentPage = Math.min(totalPages, currentPage + 1)"
                    :disabled="currentPage === totalPages"
                    class="w-8 h-8 rounded-md border border-gray-100 dark:border-gray-700 flex items-center justify-center text-gray-400 disabled:opacity-30 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Include Modal Partials -->
    @include('programs.budidaya-tematik.master.kdkmp.create')
    @include('programs.budidaya-tematik.master.kdkmp.edit')

    <!-- Modal Delete -->
    <div x-show="isDeleteOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div @click.away="isDeleteOpen = false" 
             x-show="isDeleteOpen"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 scale-95"
             class="bg-white dark:bg-gray-900 rounded-2xl w-full max-w-md p-8 shadow-2xl border border-gray-100 dark:border-gray-800 text-center relative mx-4">
            <div class="w-20 h-20 rounded-full bg-danger/10 text-danger flex items-center justify-center text-3xl mx-auto mb-5 relative">
                <div class="absolute inset-0 rounded-full border-4 border-danger/20 animate-pulse"></div>
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h3 class="text-xl font-bold text-textMain-light dark:text-textMain-dark mb-3">Konfirmasi Penghapusan</h3>
            <p class="text-sm text-textMuted-light dark:text-textMuted-dark mb-8 leading-relaxed">Apakah Anda yakin ingin menghapus data <span class="font-bold text-danger" x-text="selectedItem?.nama_kdkmp"></span>? Data yang telah dihapus tidak dapat dipulihkan kembali.</p>
            
            <div class="flex justify-center gap-3">
                <button type="button" @click="isDeleteOpen = false" class="px-6 py-2.5 bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark rounded-xl text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-all focus:ring-2 focus:ring-gray-200 outline-none">Batal</button>
                <button type="button" @click="confirmDelete" :disabled="loading" class="px-6 py-2.5 bg-danger text-white rounded-xl text-sm font-medium hover:bg-red-600 transition-all disabled:opacity-50 flex items-center gap-2 focus:ring-2 focus:ring-red-200 outline-none shadow-lg shadow-danger/30 hover:shadow-danger/50 hover:-translate-y-0.5">
                    <i x-show="loading" class="fa-solid fa-circle-notch fa-spin"></i>
                    <i x-show="!loading" class="fa-solid fa-trash-can"></i> Ya, Hapus Data
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('kdkmpManager', () => ({
        items: @json($kdkmpList),
        provinsiList: @json($provinsiList),
        allKabupatenList: @json($kabupatenList),
        komoditasList: @json($komoditasList),
        
        search: '',
        filterProvinsi: '',
        filterKabupaten: '',
        filterKomoditas: '',
        
        currentPage: 1,
        pageSize: '10',
        
        isCreateOpen: false,
        isEditOpen: false,
        isDeleteOpen: false,
        selectedItem: null,
        loading: false,
        
        notification: {
            show: false,
            message: '',
            type: 'success'
        },
        
        formData: {
            nama_kdkmp: '',
            provinsi: '',
            kabupaten: '',
            desa: '',
            komoditas: '',
            ketua_anggota: '',
            no_hp: '',
            nama_penyuluh: '',
            no_hp_penyuluh: '',
            long: '',
            lat: ''
        },

        init() {
            this.$watch('search', () => this.currentPage = 1);
            this.$watch('filterProvinsi', () => {
                this.filterKabupaten = '';
                this.currentPage = 1;
            });
            this.$watch('filterKabupaten', () => this.currentPage = 1);
            this.$watch('filterKomoditas', () => this.currentPage = 1);
            this.$watch('pageSize', () => this.currentPage = 1);
            
            this.$watch('isCreateOpen', value => {
                const main = document.querySelector('main');
                if (main) {
                    if (value) main.classList.add('overflow-hidden');
                    else main.classList.remove('overflow-hidden');
                }
            });
            this.$watch('isEditOpen', value => {
                const main = document.querySelector('main');
                if (main) {
                    if (value) main.classList.add('overflow-hidden');
                    else main.classList.remove('overflow-hidden');
                }
            });
            this.$watch('isDeleteOpen', value => {
                const main = document.querySelector('main');
                if (main) {
                    if (value) main.classList.add('overflow-hidden');
                    else main.classList.remove('overflow-hidden');
                }
            });
        },

        get availableKabupatenList() {
            if (!this.filterProvinsi) return this.allKabupatenList;
            const kabs = new Set();
            this.items.forEach(i => {
                if (i.provinsi === this.filterProvinsi && i.kabupaten) {
                    kabs.add(i.kabupaten);
                }
            });
            return Array.from(kabs).sort();
        },

        get filteredItems() {
            let res = this.items;
            
            if (this.filterProvinsi) {
                res = res.filter(i => i.provinsi === this.filterProvinsi);
            }
            
            if (this.filterKabupaten) {
                res = res.filter(i => i.kabupaten === this.filterKabupaten);
            }

            if (this.filterKomoditas) {
                res = res.filter(i => i.komoditas === this.filterKomoditas);
            }
            
            if (this.search) {
                const q = this.search.toLowerCase();
                res = res.filter(i => 
                    (i.nama_kdkmp && i.nama_kdkmp.toLowerCase().includes(q)) ||
                    (i.ketua_anggota && i.ketua_anggota.toLowerCase().includes(q)) ||
                    (i.nama_penyuluh && i.nama_penyuluh.toLowerCase().includes(q)) ||
                    (i.provinsi && i.provinsi.toLowerCase().includes(q)) ||
                    (i.kabupaten && i.kabupaten.toLowerCase().includes(q)) ||
                    (i.desa && i.desa.toLowerCase().includes(q))
                );
            }
            
            return res;
        },

        get paginatedItems() {
            if (this.pageSize === 'all') return this.filteredItems;
            const pp = parseInt(this.pageSize);
            const start = (this.currentPage - 1) * pp;
            return this.filteredItems.slice(start, start + pp);
        },

        get totalPages() {
            if (this.pageSize === 'all') return 1;
            const pp = parseInt(this.pageSize);
            return Math.max(1, Math.ceil(this.filteredItems.length / pp));
        },

        visiblePages() {
            const total = this.totalPages;
            if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1);
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

        formatLokasi(item) {
            const parts = [];
            if (item.desa) parts.push('Desa ' + item.desa);
            if (item.kabupaten) parts.push('Kab. ' + item.kabupaten);
            if (item.provinsi) parts.push(item.provinsi);
            return parts.length > 0 ? parts.join(', ') : '-';
        },

        openModal(mode, item = null) {
            this.selectedItem = item;
            
            if (mode === 'add') {
                this.formData = { 
                    nama_kdkmp: '', 
                    provinsi: '', 
                    kabupaten: '', 
                    desa: '', 
                    komoditas: '', 
                    ketua_anggota: '', 
                    no_hp: '', 
                    nama_penyuluh: '', 
                    no_hp_penyuluh: '', 
                    long: '', 
                    lat: '' 
                };
                this.isCreateOpen = true;
            } else if (mode === 'edit') {
                this.formData = { 
                    nama_kdkmp: item.nama_kdkmp || '', 
                    provinsi: item.provinsi || '', 
                    kabupaten: item.kabupaten || '', 
                    desa: item.desa || '', 
                    komoditas: item.komoditas || '', 
                    ketua_anggota: item.ketua_anggota || '', 
                    no_hp: item.no_hp || '', 
                    nama_penyuluh: item.nama_penyuluh || '', 
                    no_hp_penyuluh: item.no_hp_penyuluh || '', 
                    long: item.long || '', 
                    lat: item.lat || '' 
                };
                this.isEditOpen = true;
            } else if (mode === 'delete') {
                this.isDeleteOpen = true;
            }
        },

        showNotification(message, type = 'success') {
            this.notification = { show: true, message, type };
            setTimeout(() => { this.notification.show = false; }, 4000);
        },

        async submitCreate() {
            this.loading = true;
            try {
                const response = await fetch('/master/budidaya-tematik/kdkmp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.formData)
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    this.showNotification(result.message);
                    this.isCreateOpen = false;
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    this.showNotification(result.message || 'Terjadi kesalahan saat menyimpan data.', 'error');
                }
            } catch (error) {
                this.showNotification('Gagal menghubungi server.', 'error');
            } finally {
                this.loading = false;
            }
        },

        async submitEdit() {
            if (!this.selectedItem) return;
            this.loading = true;
            try {
                const response = await fetch(`/master/budidaya-tematik/kdkmp/${this.selectedItem.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.formData)
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    this.showNotification(result.message);
                    this.isEditOpen = false;
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    this.showNotification(result.message || 'Terjadi kesalahan saat memperbarui data.', 'error');
                }
            } catch (error) {
                this.showNotification('Gagal menghubungi server.', 'error');
            } finally {
                this.loading = false;
            }
        },

        async confirmDelete() {
            if (!this.selectedItem) return;
            
            this.loading = true;
            try {
                const response = await fetch(`/master/budidaya-tematik/kdkmp/${this.selectedItem.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    this.showNotification(result.message);
                    this.isDeleteOpen = false;
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    this.showNotification(result.message || 'Terjadi kesalahan saat menghapus data.', 'error');
                    this.isDeleteOpen = false;
                }
            } catch (error) {
                this.showNotification('Gagal menghubungi server.', 'error');
                this.isDeleteOpen = false;
            } finally {
                this.loading = false;
            }
        }
    }));
});
</script>
@endsection

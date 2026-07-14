@extends('layouts.app')

@section('title', 'Manajemen Pengguna - Program Prioritas Portal')

@section('content')
    <div x-data="userManager()" class="space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-base font-medium tracking-tight">Manajemen Pengguna</h2>
                <p class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1">Kelola akses, peran,
                    dan informasi pengguna sistem Program Prioritas.</p>
            </div>

            <button @click="openModal('add')"
                class="px-4 py-2 bg-teal-light text-white rounded-md text-xs font-medium hover:bg-teal-light/90 transition-all flex items-center justify-between gap-2">
                Tambah Pengguna <i class="fa-solid fa-user-plus"></i>
            </button>
        </div>

        <!-- Feedback Message -->
        <template x-if="notification.show">
            <div x-transition class="p-4 rounded-xl flex items-center gap-3 text-sm font-medium"
                :class="notification.type === 'success' ? 'bg-success/10 text-success border border-success/20' :
                    'bg-danger/10 text-danger border border-danger/20'">
                <i class="fa-solid"
                    :class="notification.type === 'success' ? 'fa-check-circle' : 'fa-circle-exclamation'"></i>
                <span x-text="notification.message"></span>
                <button @click="notification.show = false" class="ml-auto opacity-70 hover:opacity-100"><i
                        class="fa-solid fa-xmark"></i></button>
            </div>
        </template>

        <div
            class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-2xl overflow-hidden">
            <!-- Toolbar -->
            <div
                class="p-4 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50 dark:bg-gray-800/30">
                <div class="relative w-full sm:w-64">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" x-model="search" placeholder="Cari username..."
                        class="w-full pl-9 pr-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:border-teal-light focus:ring-1 focus:ring-teal-light outline-none transition-all">
                </div>

                <div class="flex gap-2">
                    <select x-model="filterRole"
                        class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:border-teal-light focus:ring-1 outline-none font-medium">
                        <option value="">Semua Role</option>
                        <option value="Super Admin">Super Admin</option>
                        <option value="Admin">Admin</option>
                        <option value="Verifikator">Verifikator</option>
                        <option value="User Daerah">User Daerah</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs whitespace-nowrap">
                    <thead
                        class="bg-gray-50/50 dark:bg-gray-800/50 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
                        <tr>
                            <th class="px-6 py-4">Pengguna</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Tanggal Dibuat</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        <template x-for="user in filteredUsers" :key="user.id">
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-teal-light/10 text-teal-light flex items-center justify-center font-bold text-sm uppercase"
                                            x-text="user.name.charAt(0)"></div>
                                        <div>
                                            <div class="font-medium text-textMain-light dark:text-textMain-dark"
                                                x-text="user.name"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2.5 py-1 rounded-md text-[0.65rem] font-medium bg-navy-light/10 text-textMain-light dark:bg-teal-900/30 dark:text-teal-400 uppercase tracking-wide"
                                        x-text="user.role"></span>
                                    <div x-show="user.role === 'User Daerah' && user.kabupaten"
                                        class="text-[10px] text-textMuted-light mt-1 flex items-center gap-1">
                                        <i class="fa-solid fa-location-dot"></i> <span x-text="user.kabupaten"></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="flex items-center gap-1.5 text-success font-medium text-xs">
                                        <span class="w-2 h-2 rounded-full bg-success"></span> Aktif
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <x-table.action-buttons on-edit="openModal('edit', user)"
                                        on-delete="openModal('delete', user)" />
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filteredUsers.length === 0">
                            <td colspan="5" class="px-6 py-8 text-center text-textMuted-light">Tidak ada pengguna yang
                                ditemukan.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 flex justify-between items-center text-sm">
                <div class="text-textMuted-light dark:text-textMuted-dark">
                    Menampilkan <span class="font-medium text-textMain-light dark:text-textMain-dark"
                        x-text="filteredUsers.length"></span> dari <span
                        class="font-medium text-textMain-light dark:text-textMain-dark" x-text="users.length"></span>
                    pengguna
                </div>
            </div>
        </div>

        <!-- Modal Form (Add/Edit) -->
        <div x-show="isFormOpen" style="display: none;"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div @click.away="isFormOpen = false" x-transition.opacity.duration.200ms
                class="bg-white dark:bg-gray-900 rounded-2xl w-full max-w-[400px] p-6 shadow-xl border border-gray-100 dark:border-gray-800 relative"
                style="max-width: 400px;">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-textMain-light dark:text-textMain-dark"
                        x-text="formMode === 'add' ? 'Tambah Pengguna' : 'Edit Pengguna'"></h3>
                    <button @click="isFormOpen = false" class="text-gray-400 hover:text-danger transition-colors"><i
                            class="fa-solid fa-xmark text-lg"></i></button>
                </div>

                <form @submit.prevent="submitForm">
                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Nama/Username</label>
                            <input type="text" x-model="formData.name" required
                                class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark">
                        </div>
                        <div>
                            <label
                                class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Role</label>
                            <select x-model="formData.role" required
                                class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark">
                                <option value="">Pilih Role...</option>
                                <option value="Super Admin">Super Admin</option>
                                <option value="Admin">Admin</option>
                                <option value="Verifikator">Verifikator</option>
                                <option value="User Daerah">User Daerah</option>
                            </select>
                        </div>
                        <div x-show="formData.role === 'User Daerah'" x-transition>
                            <label
                                class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Kabupaten
                                <span class="text-danger">*</span></label>
                            <select x-model="formData.kabupaten" :required="formData.role === 'User Daerah'"
                                class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark">
                                <option value="">Pilih Kabupaten...</option>
                                @foreach ($kabupatenList as $kab)
                                    <option value="{{ $kab }}">{{ $kab }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div x-data="{ showPassword: false }">
                            <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">
                                Password
                                <span x-show="formMode === 'edit'"
                                    class="text-gray-400 text-[10px] font-normal">(Kosongkan jika tidak ingin
                                    diubah)</span>
                            </label>
                            <div
                                class="flex items-center bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg pr-1 focus-within:border-teal-light focus-within:ring-1 focus-within:ring-teal-light transition-all overflow-hidden w-full">
                                <input :type="showPassword ? 'text' : 'password'" x-model="formData.password"
                                    :required="formMode === 'add'"
                                    class="flex-1 bg-transparent pl-3 py-2.5 text-sm border-none focus:ring-0 focus:outline-none text-textMain-light dark:text-textMain-dark w-full m-0 outline-none">
                                <button type="button" @click="showPassword = !showPassword"
                                    class="text-gray-400 hover:text-teal-light focus:outline-none transition-colors ml-2 mr-2 w-6 h-6 flex-shrink-0 bg-transparent flex items-center justify-center">
                                    <i class="fa-solid text-sm" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3">
                        <button type="button" @click="isFormOpen = false"
                            class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">Batal</button>
                        <button type="submit" :disabled="loading"
                            class="px-4 py-2 bg-teal-light text-white rounded-lg text-sm font-medium hover:bg-teal-light/90 transition-colors disabled:opacity-50 flex items-center gap-2">
                            <i x-show="loading" class="fa-solid fa-circle-notch fa-spin"></i>
                            <span x-text="formMode === 'add' ? 'Simpan' : 'Perbarui'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Delete -->
        <div x-show="isDeleteOpen" style="display: none;"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div @click.away="isDeleteOpen = false" x-show="isDeleteOpen"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                class="bg-white dark:bg-gray-900 rounded-2xl w-full max-w-md p-8 shadow-2xl border border-gray-100 dark:border-gray-800 text-center relative mx-4">
                <div
                    class="w-16 h-16 rounded-full bg-danger/10 text-danger flex items-center justify-center text-2xl mx-auto mb-4 relative">
                    <div class="absolute inset-0 rounded-full border-2 border-danger/20 animate-pulse"></div>
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h3 class="text-base font-semibold text-textMain-light dark:text-textMain-dark mb-2">Konfirmasi Penghapusan
                </h3>
                <p class="text-xs text-textMuted-light dark:text-textMuted-dark mb-6 leading-relaxed">Apakah Anda yakin
                    ingin menghapus pengguna <span class="font-bold text-danger" x-text="selectedUser?.name"></span>? Data
                    yang telah dihapus tidak dapat dipulihkan kembali.</p>

                <div class="flex justify-center gap-3">
                    <button type="button" @click="isDeleteOpen = false"
                        class="px-6 py-2.5 bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark rounded-xl text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-all focus:ring-2 focus:ring-gray-200 outline-none">Batal</button>
                    <button type="button" @click="confirmDelete" :disabled="loading"
                        class="px-6 py-2.5 bg-danger text-white rounded-xl text-sm font-medium hover:bg-red-600 transition-all disabled:opacity-50 flex items-center gap-2 focus:ring-2 focus:ring-red-200 outline-none shadow-lg shadow-danger/30 hover:shadow-danger/50 hover:-translate-y-0.5">
                        <i x-show="loading" class="fa-solid fa-circle-notch fa-spin"></i>
                        <i x-show="!loading" class="fa-solid fa-trash-can"></i> Ya, Hapus Pengguna
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('userManager', () => ({
                users: @json($users),
                search: '',
                filterRole: '',

                isFormOpen: false,
                isDeleteOpen: false,
                formMode: 'add', // 'add' or 'edit'
                selectedUser: null,
                loading: false,

                notification: {
                    show: false,
                    message: '',
                    type: 'success'
                },

                formData: {
                    name: '',
                    password: '',
                    role: '',
                    kabupaten: ''
                },

                get filteredUsers() {
                    let res = this.users;

                    if (this.filterRole) {
                        res = res.filter(u => u.role === this.filterRole);
                    }

                    if (this.search) {
                        const q = this.search.toLowerCase();
                        res = res.filter(u =>
                            u.name.toLowerCase().includes(q)
                        );
                    }

                    return res;
                },

                openModal(mode, user = null) {
                    this.formMode = mode;
                    this.selectedUser = user;

                    if (mode === 'add') {
                        this.formData = {
                            name: '',
                            password: '',
                            role: '',
                            kabupaten: ''
                        };
                        this.isFormOpen = true;
                    } else if (mode === 'edit') {
                        this.formData = {
                            name: user.name,
                            password: '',
                            role: user.role,
                            kabupaten: user.kabupaten || ''
                        };
                        this.isFormOpen = true;
                    } else if (mode === 'delete') {
                        this.isDeleteOpen = true;
                    }
                },

                showNotification(message, type = 'success') {
                    this.notification = {
                        show: true,
                        message,
                        type
                    };
                    setTimeout(() => {
                        this.notification.show = false;
                    }, 3000);
                },

                async submitForm() {
                    this.loading = true;

                    const isAdd = this.formMode === 'add';
                    const url = isAdd ? '/users' : `/users/${this.selectedUser.id}`;
                    const method = isAdd ? 'POST' : 'PUT';

                    try {
                        const response = await fetch(url, {
                            method: method,
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
                            this.isFormOpen = false;
                            setTimeout(() => window.location.reload(),
                            1000); // Simple reload for now
                        } else {
                            this.showNotification(result.message || 'Terjadi kesalahan.', 'error');
                        }
                    } catch (error) {
                        this.showNotification('Gagal menghubungi server.', 'error');
                    } finally {
                        this.loading = false;
                    }
                },

                async confirmDelete() {
                    if (!this.selectedUser) return;

                    this.loading = true;
                    try {
                        const response = await fetch(`/users/${this.selectedUser.id}`, {
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
                            this.showNotification(result.message || 'Terjadi kesalahan.', 'error');
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

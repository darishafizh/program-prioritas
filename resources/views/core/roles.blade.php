@extends('layouts.app')

@section('title', 'Manajemen Akses - Program Prioritas Portal')

@section('content')
    <div x-data="accessManager()" class="space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-base font-medium tracking-tight">Manajemen Akses</h2>
                <p class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1">Kelola daftar pengguna, peran, dan hak akses sistem.</p>
            </div>

            <div class="flex gap-2">
                <button @click="openUserModal('add')"
                    class="px-4 py-2 bg-teal-light text-white rounded-md text-xs font-medium hover:bg-teal-light/90 transition-all flex items-center justify-between gap-2">
                    Tambah Pengguna <i class="fa-solid fa-user-plus"></i>
                </button>
            </div>
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

        <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-2xl overflow-hidden">
            <!-- Toolbar -->
            <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50 dark:bg-gray-800/30">
                <div class="relative w-full sm:w-64">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" x-model="searchUser" placeholder="Cari username..."
                        class="w-full pl-9 pr-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:border-teal-light focus:ring-1 focus:ring-teal-light outline-none transition-all">
                </div>
                <div class="flex gap-2">
                    <select x-model="filterRole"
                        class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:border-teal-light focus:ring-1 outline-none font-medium text-textMain-light dark:text-textMain-dark">
                        <option value="">Semua Role</option>
                        <template x-for="r in roles" :key="r.id">
                            <option :value="r.name" x-text="r.name.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())"></option>
                        </template>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs whitespace-nowrap">
                    <thead class="bg-gray-50/50 dark:bg-gray-800/50 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
                        <tr>
                            <th class="px-6 py-4">Pengguna</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4">Hak Akses</th>
                            <th class="px-6 py-4">Status</th>
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
                                            <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="user.name"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-md text-[0.65rem] font-medium bg-navy-light/10 text-textMain-light dark:bg-teal-900/30 dark:text-teal-400 uppercase tracking-wide"
                                        x-text="user.roles && user.roles.length > 0 ? user.roles[0].name.replace(/_/g, ' ') : 'Tanpa Role'"></span>
                                    <div x-show="(user.roles && user.roles.length > 0 ? user.roles[0].name : '') === 'user_daerah' && user.kabupaten" class="text-[10px] text-textMuted-light mt-1 flex items-center gap-1">
                                        <i class="fa-solid fa-location-dot"></i> <span x-text="user.kabupaten"></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 max-w-[200px] whitespace-normal">
                                    <div class="flex flex-wrap gap-1">
                                        <template x-if="user.all_permissions && user.all_permissions.length > 0">
                                            <template x-for="perm in user.all_permissions">
                                                <span class="px-1.5 py-0.5 rounded bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark text-[9px] border border-gray-200 dark:border-gray-700" 
                                                      x-text="perm.name.split('_').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ')"></span>
                                            </template>
                                        </template>
                                        <template x-if="!user.all_permissions || user.all_permissions.length === 0">
                                            <span class="text-[10px] text-textMuted-light italic">Belum ada hak akses</span>
                                        </template>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="flex items-center gap-1.5 text-success font-medium text-xs">
                                        <span class="w-2 h-2 rounded-full bg-success"></span> Aktif
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <template x-if="(user.roles && user.roles.length > 0 ? user.roles[0].name : '') !== 'super_admin'">
                                        <x-table.action-buttons on-edit="openUserModal('edit', user)"
                                            on-delete="openUserModal('delete', user)" />
                                    </template>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filteredUsers.length === 0">
                            <td colspan="5" class="px-6 py-8 text-center text-textMuted-light">Tidak ada pengguna yang ditemukan.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 flex justify-between items-center text-sm">
                <div class="text-textMuted-light dark:text-textMuted-dark">
                    Menampilkan <span class="font-medium text-textMain-light dark:text-textMain-dark" x-text="filteredUsers.length"></span> dari <span
                        class="font-medium text-textMain-light dark:text-textMain-dark" x-text="users.length"></span> pengguna
                </div>
            </div>
        </div>

        <!-- ===================== MODALS ===================== -->

        <!-- Modal Form User & Role -->
        <div x-show="isUserFormOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div @click.away="isUserFormOpen = false" x-transition.opacity.duration.200ms
                class="bg-white dark:bg-gray-900 rounded-2xl w-full max-w-[500px] p-6 shadow-xl border border-gray-100 dark:border-gray-800 relative max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-textMain-light dark:text-textMain-dark"
                        x-text="userFormMode === 'add' ? 'Tambah Pengguna' : 'Edit Pengguna'"></h3>
                    <button @click="isUserFormOpen = false" class="text-gray-400 hover:text-danger transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
                </div>
                <form @submit.prevent="submitUserForm">
                    <div class="space-y-4">
                        <!-- User Name -->
                        <div>
                            <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Nama/Username <span class="text-danger">*</span></label>
                            <input type="text" x-model="userFormData.name" required
                                class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark">
                        </div>

                        <!-- Role Selection -->
                        <div>
                            <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Role (Peran) <span class="text-danger">*</span></label>
                            <select x-model="userFormData.role" @change="handleRoleChange()" required
                                class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark">
                                <option value="">Pilih Role...</option>
                                <template x-for="r in roles" :key="r.id">
                                    <option :value="r.name" x-text="r.name.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Kabupaten Selection (Conditional) -->
                        <div x-show="userFormData.role === 'user_daerah'" x-transition>
                            <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Kabupaten <span class="text-danger">*</span></label>
                            <select x-model="userFormData.kabupaten" :required="userFormData.role === 'user_daerah'"
                                class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark">
                                <option value="">Pilih Kabupaten...</option>
                                @foreach ($kabupatenList as $kab)
                                    <option value="{{ $kab }}">{{ $kab }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Password -->
                        <div x-data="{ showPassword: false }">
                            <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">
                                Password
                                <span x-show="userFormMode === 'edit'" class="text-gray-400 text-[10px] font-normal">(Kosongkan jika tidak ingin diubah)</span>
                                <span x-show="userFormMode === 'add'" class="text-danger">*</span>
                            </label>
                            <div class="flex items-center bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg pr-1 focus-within:border-teal-light focus-within:ring-1 focus-within:ring-teal-light transition-all overflow-hidden w-full">
                                <input :type="showPassword ? 'text' : 'password'" x-model="userFormData.password"
                                    :required="userFormMode === 'add'"
                                    class="flex-1 bg-transparent pl-3 py-2.5 text-sm border-none focus:ring-0 focus:outline-none text-textMain-light dark:text-textMain-dark w-full m-0 outline-none">
                                <button type="button" @click="showPassword = !showPassword"
                                    class="text-gray-400 hover:text-teal-light focus:outline-none transition-colors ml-2 mr-2 w-6 h-6 flex-shrink-0 bg-transparent flex items-center justify-center">
                                    <i class="fa-solid text-sm" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Permissions Checkboxes -->
                        <div x-show="userFormData.role !== ''">
                            <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">
                                Hak Akses Spesifik User
                                <span class="text-[10px] text-teal-light ml-1">(Anda bisa kustomisasi akses untuk user ini)</span>
                            </label>
                            <div class="grid grid-cols-2 gap-2 mt-1 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700">
                                <template x-for="perm in allPermissions" :key="perm.id">
                                    <label class="flex items-center gap-2 cursor-pointer text-sm text-textMain-light dark:text-textMain-dark hover:bg-gray-100 dark:hover:bg-gray-700 p-1.5 rounded transition-colors"
                                           :class="{'opacity-50 cursor-not-allowed': userFormData.role === 'super_admin'}">
                                        <input type="checkbox" :value="perm.name" x-model="userFormData.permissions"
                                            :disabled="userFormData.role === 'super_admin'"
                                            class="w-4 h-4 text-teal-light rounded border-gray-300 focus:ring-teal-light disabled:opacity-50">
                                        <span x-text="perm.name.split('_').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ')"></span>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3">
                        <button type="button" @click="isUserFormOpen = false"
                            class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">Batal</button>
                        <button type="submit" :disabled="loading"
                            class="px-4 py-2 bg-teal-light text-white rounded-lg text-sm font-medium hover:bg-teal-light/90 transition-colors disabled:opacity-50 flex items-center gap-2">
                            <i x-show="loading" class="fa-solid fa-circle-notch fa-spin"></i>
                            <span x-text="userFormMode === 'add' ? 'Simpan' : 'Perbarui'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Delete User -->
        <div x-show="isUserDeleteOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div @click.away="isUserDeleteOpen = false"
                class="bg-white dark:bg-gray-900 rounded-2xl w-full max-w-md p-8 shadow-2xl border border-gray-100 dark:border-gray-800 text-center relative">
                <div class="w-16 h-16 rounded-full bg-danger/10 text-danger flex items-center justify-center text-2xl mx-auto mb-4">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h3 class="text-base font-semibold text-textMain-light dark:text-textMain-dark mb-2">Konfirmasi Penghapusan</h3>
                <p class="text-xs text-textMuted-light dark:text-textMuted-dark mb-6 leading-relaxed">Apakah Anda yakin ingin menghapus pengguna <span class="font-bold text-danger" x-text="selectedUser?.name"></span>?</p>
                <div class="flex justify-center gap-3">
                    <button type="button" @click="isUserDeleteOpen = false"
                        class="px-6 py-2.5 bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark rounded-xl text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-all">Batal</button>
                    <button type="button" @click="confirmDeleteUser" :disabled="loading"
                        class="px-6 py-2.5 bg-danger text-white rounded-xl text-sm font-medium hover:bg-red-600 transition-all disabled:opacity-50 flex items-center gap-2">
                        <i x-show="loading" class="fa-solid fa-circle-notch fa-spin"></i>
                        <i x-show="!loading" class="fa-solid fa-trash-can"></i> Hapus
                    </button>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('accessManager', () => ({
                users: @json($users),
                roles: @json($roles),
                allPermissions: @json($permissions),
                
                searchUser: '',
                filterRole: '',
                
                isUserFormOpen: false,
                isUserDeleteOpen: false,
                userFormMode: 'add',
                selectedUser: null,
                
                userFormData: { 
                    name: '', 
                    password: '', 
                    role: '', 
                    kabupaten: '',
                    permissions: []
                },
                
                loading: false,
                notification: { show: false, message: '', type: 'success' },

                get filteredUsers() {
                    let res = this.users;
                    if (this.filterRole) res = res.filter(u => (u.roles && u.roles.length > 0 ? u.roles[0].name : '') === this.filterRole);
                    if (this.searchUser) {
                        const q = this.searchUser.toLowerCase();
                        res = res.filter(u => u.name.toLowerCase().includes(q));
                    }
                    return res;
                },

                handleRoleChange() {
                    if (this.userFormData.role) {
                        // Apply default permissions based on Role
                        if (this.userFormData.role === 'super_admin') {
                            this.userFormData.permissions = this.allPermissions.map(p => p.name);
                        } else if (this.userFormData.role === 'admin') {
                            this.userFormData.permissions = ['lihat_dashboard'];
                        } else if (this.userFormData.role === 'user_daerah') {
                            this.userFormData.permissions = ['lihat_dashboard', 'kelola_operasional'];
                        } else {
                            this.userFormData.permissions = [];
                        }

                        if (this.userFormData.role !== 'user_daerah') {
                            this.userFormData.kabupaten = '';
                        }
                    } else {
                        this.userFormData.permissions = [];
                        this.userFormData.kabupaten = '';
                    }
                },

                openUserModal(mode, user = null) {
                    this.userFormMode = mode;
                    this.selectedUser = user;
                    
                    if (mode === 'add') {
                        this.userFormData = { 
                            name: '', 
                            password: '', 
                            role: '', 
                            kabupaten: '',
                            permissions: []
                        };
                        this.isUserFormOpen = true;
                    } else if (mode === 'edit') {
                        let userRole = (user.roles && user.roles.length > 0) ? user.roles[0].name : '';
                        let perms = (user.all_permissions && user.all_permissions.length > 0) ? user.all_permissions.map(p => p.name) : [];

                        this.userFormData = { 
                            name: user.name, 
                            password: '', 
                            role: userRole, 
                            kabupaten: user.kabupaten || '',
                            permissions: perms
                        };
                        this.isUserFormOpen = true;
                    } else if (mode === 'delete') {
                        this.isUserDeleteOpen = true;
                    }
                },

                async submitUserForm() {
                    this.loading = true;
                    const isAdd = this.userFormMode === 'add';
                    const url = isAdd ? "{{ url('users/create') }}" : `{{ url('users') }}/${this.selectedUser.id}/update`;
                    try {
                        const response = await fetch(url, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                            body: JSON.stringify(this.userFormData)
                        });
                        const result = await response.json();
                        if (response.ok) {
                            this.showNotification(result.message);
                            this.isUserFormOpen = false;
                            setTimeout(() => window.location.reload(), 1000);
                        } else this.showNotification(result.message || 'Terjadi kesalahan.', 'error');
                    } catch (error) { this.showNotification('Gagal menghubungi server.', 'error'); } 
                    finally { this.loading = false; }
                },

                async confirmDeleteUser() {
                    if (!this.selectedUser) return;
                    this.loading = true;
                    try {
                        const response = await fetch(`{{ url('users') }}/${this.selectedUser.id}/delete`, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                        });
                        const result = await response.json();
                        if (response.ok) {
                            this.showNotification(result.message);
                            this.isUserDeleteOpen = false;
                            setTimeout(() => window.location.reload(), 1000);
                        } else {
                            this.showNotification(result.message || 'Terjadi kesalahan.', 'error');
                            this.isUserDeleteOpen = false;
                        }
                    } catch (error) { this.showNotification('Gagal menghubungi server.', 'error'); this.isUserDeleteOpen = false; } 
                    finally { this.loading = false; }
                },

                showNotification(message, type = 'success') {
                    this.notification = { show: true, message, type };
                    setTimeout(() => { this.notification.show = false; }, 3000);
                }
            }));
        });
    </script>
@endsection

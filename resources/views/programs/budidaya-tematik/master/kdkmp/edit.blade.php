<!-- Offcanvas Full-Screen Form Edit -->
<div x-show="isEditOpen" style="display: none; z-index: 99999;" class="fixed inset-0 overflow-hidden">
    <!-- Latar Belakang Gelap (Backdrop) -->
    <div x-show="isEditOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="isEditOpen = false"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity">
    </div>

    <!-- Panel Offcanvas (Full Screen bergeser dari Kanan) -->
    <div class="fixed inset-0 w-full flex">
        <div x-show="isEditOpen" @click.away="isEditOpen = false"
            x-transition:enter="transform transition ease-in-out duration-300 sm:duration-500"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-300 sm:duration-500"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
            class="w-full bg-white dark:bg-gray-900 shadow-2xl flex flex-col">

            <!-- Fixed Header -->
            <div
                class="px-6 sm:px-8 py-5 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center flex-shrink-0 bg-white dark:bg-gray-900 shadow-sm">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-teal-light/10 text-teal-light flex items-center justify-center text-lg shadow-inner">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-textMain-light dark:text-textMain-dark">Edit Data Master KDKMP
                        </h3>
                        <p class="text-xs text-textMuted-light mt-0.5">Perbarui informasi KDKMP <span
                                class="font-bold text-teal-light" x-text="selectedItem?.nama_kdkmp"></span>.</p>
                    </div>
                </div>
                <button @click="isEditOpen = false"
                    class="px-4 py-2 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-danger hover:bg-danger/10 transition-all font-medium text-xs flex items-center gap-2">
                    <i class="fa-solid fa-xmark text-sm"></i> Tutup Panel
                </button>
            </div>

            <!-- Form with internal scroll -->
            <form @submit.prevent="submitEdit"
                class="flex flex-col flex-1 overflow-hidden bg-gray-50/60 dark:bg-gray-950/50">
                <!-- Scrollable Body -->
                <div class="overflow-y-auto overscroll-contain p-6 sm:p-8 flex-1">
                    <div class="max-w-5xl mx-auto space-y-8">

                        <!-- Section 1: Informasi Kelompok (3 Kolom per Baris) -->
                        <div
                            class="bg-white dark:bg-gray-900 p-6 sm:p-8 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm space-y-6">
                            <div
                                class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-4">
                                <h4
                                    class="text-sm font-bold uppercase tracking-wider text-teal-light flex items-center gap-2.5">
                                    <span
                                        class="w-7 h-7 rounded-lg bg-teal-light/10 flex items-center justify-center text-teal-light"><i
                                            class="fa-solid fa-users text-xs"></i></span>
                                    1. Informasi Kelompok & Lokasi Budidaya
                                </h4>
                                <span
                                    class="text-[11px] font-medium px-2.5 py-1 rounded-md bg-gray-100 dark:bg-gray-800 text-textMuted-light">Layout
                                    3 Kolom</span>
                            </div>

                            <!-- Baris 1: Nama KDKMP, Provinsi, Kab/Kota -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label
                                        class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Nama
                                        KDKMP <span class="text-danger">*</span></label>
                                    <div class="relative">
                                        <i
                                            class="fa-solid fa-users absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                        <input type="text" x-model="formData.nama_kdkmp" required
                                            placeholder="Contoh: KDKMP Gadingsari"
                                            class="w-full pl-11 pr-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:border-teal-light focus:ring-1 focus:ring-teal-light transition-all text-textMain-light dark:text-textMain-dark">
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Provinsi</label>
                                    <div class="relative">
                                        <i
                                            class="fa-solid fa-map absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                        <input type="text" x-model="formData.provinsi" list="edit-provinsi-list"
                                            placeholder="Contoh: DI. Yogyakarta"
                                            class="w-full pl-11 pr-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:border-teal-light focus:ring-1 focus:ring-teal-light transition-all text-textMain-light dark:text-textMain-dark">
                                        <datalist id="edit-provinsi-list">
                                            <template x-for="prov in provinsiList" :key="prov">
                                                <option :value="prov"></option>
                                            </template>
                                        </datalist>
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Kabupaten
                                        / Kota</label>
                                    <div class="relative">
                                        <i
                                            class="fa-solid fa-city absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                        <input type="text" x-model="formData.kabupaten" list="edit-kabupaten-list"
                                            placeholder="Contoh: Bantul"
                                            class="w-full pl-11 pr-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:border-teal-light focus:ring-1 focus:ring-teal-light transition-all text-textMain-light dark:text-textMain-dark">
                                        <datalist id="edit-kabupaten-list">
                                            <template x-for="kab in availableKabupatenList" :key="kab">
                                                <option :value="kab"></option>
                                            </template>
                                        </datalist>
                                    </div>
                                </div>
                            </div>

                            <!-- Baris 2: Desa/Kelurahan, Komoditas, Koordinat -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label
                                        class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Desa
                                        / Kelurahan</label>
                                    <div class="relative">
                                        <i
                                            class="fa-solid fa-house-flag absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                        <input type="text" x-model="formData.desa" placeholder="Contoh: Gadingsari"
                                            class="w-full pl-11 pr-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:border-teal-light focus:ring-1 focus:ring-teal-light transition-all text-textMain-light dark:text-textMain-dark">
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Komoditas
                                        Budidaya</label>
                                    <div class="relative">
                                        <i
                                            class="fa-solid fa-fish absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                        <input type="text" x-model="formData.komoditas" list="edit-komoditas-list"
                                            placeholder="Contoh: Nila / Lele / Udang"
                                            class="w-full pl-11 pr-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:border-teal-light focus:ring-1 focus:ring-teal-light transition-all text-textMain-light dark:text-textMain-dark">
                                        <datalist id="edit-komoditas-list">
                                            <template x-for="kom in komoditasList" :key="kom">
                                                <option :value="kom"></option>
                                            </template>
                                        </datalist>
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Koordinat
                                        (Long / Lat)</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div class="relative">
                                            <i
                                                class="fa-solid fa-location-arrow absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                            <input type="text" x-model="formData.long" placeholder="Long: 110.285"
                                                class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-xs outline-none focus:border-teal-light focus:ring-1 focus:ring-teal-light transition-all text-textMain-light dark:text-textMain-dark">
                                        </div>
                                        <div class="relative">
                                            <i
                                                class="fa-solid fa-compass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                            <input type="text" x-model="formData.lat" placeholder="Lat: -7.981"
                                                class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-xs outline-none focus:border-teal-light focus:ring-1 focus:ring-teal-light transition-all text-textMain-light dark:text-textMain-dark">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Ketua dan Penyuluh (2 Kolom per Baris) -->
                        <div
                            class="bg-white dark:bg-gray-900 p-6 sm:p-8 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm space-y-6">
                            <div
                                class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-4">
                                <h4
                                    class="text-sm font-bold uppercase tracking-wider text-teal-light flex items-center gap-2.5">
                                    <span
                                        class="w-7 h-7 rounded-lg bg-teal-light/10 flex items-center justify-center text-teal-light"><i
                                            class="fa-solid fa-address-book text-xs"></i></span>
                                    2. Ketua & Penyuluh Lapangan
                                </h4>
                                <span
                                    class="text-[11px] font-medium px-2.5 py-1 rounded-md bg-gray-100 dark:bg-gray-800 text-textMuted-light">Layout
                                    2 Kolom</span>
                            </div>

                            <!-- Baris 1: Nama Ketua, No HP Ketua -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label
                                        class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Nama
                                        Ketua / Anggota</label>
                                    <div class="relative">
                                        <i
                                            class="fa-solid fa-user-tie absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                        <input type="text" x-model="formData.ketua_anggota"
                                            placeholder="Contoh: Sugiyarto"
                                            class="w-full pl-11 pr-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:border-teal-light focus:ring-1 focus:ring-teal-light transition-all text-textMain-light dark:text-textMain-dark">
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">No.
                                        Telepon / HP Ketua</label>
                                    <div class="relative">
                                        <i
                                            class="fa-solid fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                        <input type="text" x-model="formData.no_hp"
                                            placeholder="Contoh: 081234567890"
                                            class="w-full pl-11 pr-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:border-teal-light focus:ring-1 focus:ring-teal-light transition-all text-textMain-light dark:text-textMain-dark">
                                    </div>
                                </div>
                            </div>

                            <!-- Baris 2: Nama Penyuluh, No HP Penyuluh -->
                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2 border-t border-gray-100 dark:border-gray-800/60">
                                <div>
                                    <label
                                        class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Nama
                                        Penyuluh</label>
                                    <div class="relative">
                                        <i
                                            class="fa-solid fa-user-check absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                        <input type="text" x-model="formData.nama_penyuluh"
                                            placeholder="Contoh: Firman Ardy"
                                            class="w-full pl-11 pr-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:border-teal-light focus:ring-1 focus:ring-teal-light transition-all text-textMain-light dark:text-textMain-dark">
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">No.
                                        Telepon / HP Penyuluh</label>
                                    <div class="relative">
                                        <i
                                            class="fa-solid fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                        <input type="text" x-model="formData.no_hp_penyuluh"
                                            placeholder="Contoh: 087712345678"
                                            class="w-full pl-11 pr-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:border-teal-light focus:ring-1 focus:ring-teal-light transition-all text-textMain-light dark:text-textMain-dark">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Fixed Footer -->
                <div
                    class="px-6 sm:px-8 py-5 border-t border-gray-100 dark:border-gray-800 flex justify-end items-center gap-3 flex-shrink-0 bg-white dark:bg-gray-900 shadow-lg">
                    <button type="button" @click="isEditOpen = false"
                        class="px-6 py-2.5 bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark rounded-xl text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">Batal</button>
                    <button type="submit" :disabled="loading"
                        class="px-6 py-2.5 bg-teal-light text-white rounded-xl text-sm font-medium hover:bg-teal-600 transition-colors disabled:opacity-50 flex items-center gap-2 shadow-lg shadow-teal-light/25 hover:shadow-teal-light/40">
                        <i x-show="loading" class="fa-solid fa-circle-notch fa-spin"></i>
                        <i x-show="!loading" class="fa-solid fa-save"></i>
                        <span>Perbarui Data KDKMP</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

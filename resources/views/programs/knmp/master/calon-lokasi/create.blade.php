@extends('layouts.app')

@section('title', 'KNMP - Tambah Pengajuan Calon Lokasi')

@section('content')
<style>
    /* Paksa arrow dropdown agar tidak mepet kanan dengan menggunakan custom SVG Arrow */
    select.pr-10 {
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        appearance: none !important;
        padding-left: 1rem !important;
        padding-right: 2.5rem !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e") !important;
        background-position: right 1rem center !important;
        background-repeat: no-repeat !important;
        background-size: 1.5em 1.5em !important;
    }
    
    /* Paksa warna primary (teal-light) pada radio button yang dipilih */
    input[type="radio"].peer:checked + div {
        background-color: #0891B2 !important;
        border-color: #0891B2 !important;
        color: #ffffff !important;
    }
    input[type="radio"].peer:checked ~ div.text-white {
        display: block !important;
        color: #ffffff !important;
    }
</style>
<div x-data="createPengajuan()" x-init="initForm()" class="flex flex-col gap-6 pb-12">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-semibold tracking-tight">Formulir Pengajuan KNMP</h2>
            <p class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1">Lengkapi data wilayah, spesifikasi lahan, dan unggah dokumen pendukung.</p>
        </div>
        <a href="{{ route('program.master.calon-lokasi.index', ['program' => strtolower($activeProgram)]) }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors cursor-pointer flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> <span>Kembali</span>
        </a>
    </div>

    <!-- Main Form -->
    <form action="{{ route('program.master.calon-lokasi.store', ['program' => strtolower($activeProgram)]) }}" method="POST" enctype="multipart/form-data" @submit.prevent="submitForm">
        @csrf
        
        <div class="space-y-6">
            <!-- A. Informasi Wilayah -->
            <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-teal-light/10 text-teal-light flex items-center justify-center font-bold text-sm">A</div>
                    <h3 class="text-base font-semibold text-textMain-light dark:text-textMain-dark">Informasi Wilayah & Pengisi</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="space-y-4">
                        <div >
                            <label class="block text-xs font-semibold text-textMain-light dark:text-textMain-dark mb-1">Provinsi <span class="text-danger">*</span></label>
                            <select name="provinsi" x-model="formData.provinsi" @change="fetchRegencies()" required class="w-full pl-4 pr-10 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:border-teal-light focus:ring-1 focus:ring-teal-light outline-none transition-all">
                                <option value="">Pilih Provinsi...</option>
                                <template x-for="prov in provinces" :key="prov.id">
                                    <option :value="prov.id" x-text="prov.name"></option>
                                </template>
                            </select>
                        </div>
                        
                        <div class="mt-2">
                            <label class="block text-xs font-semibold text-textMain-light dark:text-textMain-dark mb-1">Kabupaten / Kota <span class="text-danger">*</span></label>
                            <select name="kabupaten" x-model="formData.kabupaten" @change="fetchDistricts()" :disabled="!formData.provinsi" required class="w-full pl-4 pr-10 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:border-teal-light focus:ring-1 focus:ring-teal-light outline-none transition-all disabled:opacity-50 disabled:bg-gray-50">
                                <option value="">Pilih Kabupaten...</option>
                                <template x-for="reg in regencies" :key="reg.id">
                                    <option :value="reg.id" x-text="reg.name"></option>
                                </template>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-textMain-light dark:text-textMain-dark mb-1">Kecamatan <span class="text-danger">*</span></label>
                            <select name="kecamatan" x-model="formData.kecamatan" @change="fetchVillages()" :disabled="!formData.kabupaten" required class="w-full pl-4 pr-10 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:border-teal-light focus:ring-1 focus:ring-teal-light outline-none transition-all disabled:opacity-50 disabled:bg-gray-50">
                                <option value="">Pilih Kecamatan...</option>
                                <template x-for="dist in districts" :key="dist.id">
                                    <option :value="dist.id" x-text="dist.name"></option>
                                </template>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-textMain-light dark:text-textMain-dark mb-1">Desa / Kelurahan <span class="text-danger">*</span></label>
                            <select name="desa" x-model="formData.desa" :disabled="!formData.kecamatan" required class="w-full pl-4 pr-10 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:border-teal-light focus:ring-1 focus:ring-teal-light outline-none transition-all disabled:opacity-50 disabled:bg-gray-50">
                                <option value="">Pilih Desa...</option>
                                <template x-for="vil in villages" :key="vil.id">
                                    <option :value="vil.id" x-text="vil.name"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-textMain-light dark:text-textMain-dark mb-1">Nama Lengkap Pengisi <span class="text-danger">*</span></label>
                            <input type="text" name="q14_5Nama" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:border-teal-light focus:ring-1 focus:ring-teal-light outline-none transition-all" placeholder="Masukkan nama lengkap">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-semibold text-textMain-light dark:text-textMain-dark mb-1">Jabatan Pengisi <span class="text-danger">*</span></label>
                            <input type="text" name="q22_6Jabatan" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:border-teal-light focus:ring-1 focus:ring-teal-light outline-none transition-all" placeholder="Misal: Kepala Desa / Ketua Koperasi">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-textMain-light dark:text-textMain-dark mb-1">No HP / WhatsApp <span class="text-danger">*</span></label>
                            <input type="number" name="q15_7No" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:border-teal-light focus:ring-1 focus:ring-teal-light outline-none transition-all" placeholder="Misal: 08123456789">
                        </div>
                    </div>

                </div>
            </div>

            <!-- B. Informasi Lahan -->
            <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-teal-light/10 text-teal-light flex items-center justify-center font-bold text-sm">B</div>
                    <h3 class="text-base font-semibold text-textMain-light dark:text-textMain-dark">Spesifikasi Lahan yang Diusulkan</h3>
                </div>
                
                <div class="p-6 space-y-8">
                    <!-- Data Fisik Lahan -->
                    <div class="space-y-5 mb-6">
                        <h4 class="text-sm font-bold text-teal-700 dark:text-teal-400 border-b border-gray-200 dark:border-gray-700 pb-2">Data Fisik Lahan</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-xs font-semibold text-textMain-light dark:text-textMain-dark mb-1">Luas Lahan (m²) <span class="text-danger">*</span></label>
                                <input type="number" name="q36_7Luas" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:border-teal-light focus:ring-1 focus:ring-teal-light outline-none transition-all" placeholder="0">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-textMain-light dark:text-textMain-dark mb-1">Koordinat X (Lintang) <span class="text-danger">*</span></label>
                                <input type="text" name="q66_lampirkanTitik" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:border-teal-light focus:ring-1 focus:ring-teal-light outline-none transition-all" placeholder="Contoh: -6.12345">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-textMain-light dark:text-textMain-dark mb-1">Koordinat Y (Bujur) <span class="text-danger">*</span></label>
                                <input type="text" name="q67_masukkanTitik" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:border-teal-light focus:ring-1 focus:ring-teal-light outline-none transition-all" placeholder="Contoh: 106.12345">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-semibold text-textMain-light dark:text-textMain-dark mb-1">Jarak dari Pantai (m) <span class="text-danger">*</span></label>
                                <input type="number" name="q38_7Luas38" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:border-teal-light focus:ring-1 focus:ring-teal-light outline-none transition-all" placeholder="0">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-textMain-light dark:text-textMain-dark mb-1">Kemiringan Lahan (°) <span class="text-danger">*</span></label>
                                <input type="number" name="q43_10bJika43" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:border-teal-light focus:ring-1 focus:ring-teal-light outline-none transition-all" placeholder="0">
                            </div>
                        </div>
                    </div>

                    <!-- Kuesioner Kelayakan -->
                    <div>
                        <h4 class="text-sm font-bold text-teal-700 dark:text-teal-400 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">Kuesioner Kelayakan Lahan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- 1. Kepemilikan (Full Width) -->
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-xs font-semibold text-textMain-light dark:text-textMain-dark mb-1">1. Status Kepemilikan Lahan <span class="text-danger">*</span></label>
                                <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
                                    <template x-for="option in ['Lahan milik Pemkab', 'Lahan milik pribadi', 'Lahan milik adat', 'Lahan milik Pemprov', 'Lahan milik Pemdes']">
                                        <label class="cursor-pointer relative">
                                            <input type="radio" name="q24_typeA" :value="option" x-model="formLahan.kepemilikan" class="peer sr-only" required>
                                            <div class="rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-3 text-sm text-center font-medium text-gray-500 hover:bg-gray-50 peer-checked:border-teal-light peer-checked:bg-teal-light peer-checked:text-white transition-all">
                                                <span x-text="option"></span>
                                            </div>
                                            <div class="absolute top-2 right-2 hidden peer-checked:block text-white"><i class="fa-solid fa-circle-check text-sm"></i></div>
                                        </label>
                                    </template>
                                </div>
                            </div>

                            <!-- 2. RTRW -->
                            <div>
                                <label class="block text-xs font-semibold text-textMain-light dark:text-textMain-dark mb-1">2. Sesuai dengan dokumen RTRW / RZWP3K / PKKPRL? <span class="text-danger">*</span></label>
                                <div class="flex gap-3">
                                    <template x-for="option in ['Sesuai', 'Tidak Sesuai', 'Sedang Proses']">
                                        <label class="cursor-pointer relative flex-1">
                                            <input type="radio" name="q30_typeA30" :value="option" class="peer sr-only" required>
                                            <div class="rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-2.5 text-sm text-center font-medium text-gray-500 hover:bg-gray-50 peer-checked:border-teal-light peer-checked:bg-teal-light peer-checked:text-white transition-all" x-text="option"></div>
                                        </label>
                                    </template>
                                </div>
                            </div>

                            <!-- 3. Mangrove -->
                            <div>
                                <label class="block text-xs font-semibold text-textMain-light dark:text-textMain-dark mb-1">3. Apakah lahan merupakan kawasan mangrove? <span class="text-danger">*</span></label>
                                <div class="flex gap-3">
                                    <template x-for="option in ['Ya', 'Tidak']">
                                        <label class="cursor-pointer relative w-24">
                                            <input type="radio" name="q32_typeA32" :value="option" class="peer sr-only" required>
                                            <div class="rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-2 text-sm text-center font-medium text-gray-500 hover:bg-gray-50 peer-checked:border-teal-light peer-checked:bg-teal-light peer-checked:text-white transition-all" x-text="option"></div>
                                        </label>
                                    </template>
                                </div>
                            </div>

                            <!-- 4. Konservasi -->
                            <div>
                                <label class="block text-xs font-semibold text-textMain-light dark:text-textMain-dark mb-1">4. Dekat dengan area konservasi laut? <span class="text-danger">*</span></label>
                                <div class="flex gap-3">
                                    <template x-for="option in ['Ya', 'Tidak']">
                                        <label class="cursor-pointer relative w-24">
                                            <input type="radio" name="q33_3Apakah" :value="option" class="peer sr-only" required>
                                            <div class="rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-2 text-sm text-center font-medium text-gray-500 hover:bg-gray-50 peer-checked:border-teal-light peer-checked:bg-teal-light peer-checked:text-white transition-all" x-text="option"></div>
                                        </label>
                                    </template>
                                </div>
                            </div>

                            <!-- 5. Hutan Lindung -->
                            <div>
                                <label class="block text-xs font-semibold text-textMain-light dark:text-textMain-dark mb-1">5. Apakah lahan kawasan hutan lindung? <span class="text-danger">*</span></label>
                                <div class="flex gap-3">
                                    <template x-for="option in ['Ya', 'Tidak']">
                                        <label class="cursor-pointer relative w-24">
                                            <input type="radio" name="q34_4Apakah" :value="option" class="peer sr-only" required>
                                            <div class="rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-2 text-sm text-center font-medium text-gray-500 hover:bg-gray-50 peer-checked:border-teal-light peer-checked:bg-teal-light peer-checked:text-white transition-all" x-text="option"></div>
                                        </label>
                                    </template>
                                </div>
                            </div>

                            <!-- 6. RIPPN -->
                            <div>
                                <label class="block text-xs font-semibold text-textMain-light dark:text-textMain-dark mb-1">6. Termasuk wilayah RIPPN? <span class="text-danger">*</span></label>
                                <div class="flex gap-3">
                                    <template x-for="option in ['Ya', 'Tidak']">
                                        <label class="cursor-pointer relative w-24">
                                            <input type="radio" name="q35_5Apakah" :value="option" class="peer sr-only" required>
                                            <div class="rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-2 text-sm text-center font-medium text-gray-500 hover:bg-gray-50 peer-checked:border-teal-light peer-checked:bg-teal-light peer-checked:text-white transition-all" x-text="option"></div>
                                        </label>
                                    </template>
                                </div>
                            </div>

                            <!-- 7. DAS -->
                            <div>
                                <label class="block text-xs font-semibold text-textMain-light dark:text-textMain-dark mb-1">7. Berada pada Daerah Aliran Sungai (DAS)? <span class="text-danger">*</span></label>
                                <div class="flex gap-3 mb-3">
                                    <template x-for="option in ['Ya', 'Tidak']">
                                        <label class="cursor-pointer relative w-24">
                                            <input type="radio" name="q40_9Apakah" :value="option" x-model="formLahan.das" class="peer sr-only" required>
                                            <div class="rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-2 text-sm text-center font-medium text-gray-500 hover:bg-gray-50 peer-checked:border-teal-light peer-checked:bg-teal-light peer-checked:text-white transition-all" x-text="option"></div>
                                        </label>
                                    </template>
                                </div>
                                <div x-show="formLahan.das === 'Ya'" x-transition class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-textMain-light dark:text-textMain-dark mb-1">Jarak dari Sungai (m)</label>
                                        <input type="number" name="q41_jikaYa" class="w-full px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 text-sm outline-none focus:border-teal-light" placeholder="0">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-textMain-light dark:text-textMain-dark mb-1">Lebar Sungai (m)</label>
                                        <input type="number" name="q42_jikaYa42" class="w-full px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 text-sm outline-none focus:border-teal-light" placeholder="0">
                                    </div>
                                </div>
                            </div>

                            <!-- 8. Jenis Substrat -->
                            <div>
                                <label class="block text-xs font-semibold text-textMain-light dark:text-textMain-dark mb-1">8. Jenis substrat tanah <span class="text-danger">*</span></label>
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="option in ['Berpasir', 'Berlumpur', 'Berbatu', 'Lainnya']">
                                        <label class="cursor-pointer relative">
                                            <input type="radio" name="q44_typeA44" :value="option" class="peer sr-only" required>
                                            <div class="rounded-xl border border-gray-200 dark:border-gray-700 px-3 py-2 text-xs font-medium text-gray-500 hover:bg-gray-50 peer-checked:border-teal-light peer-checked:bg-teal-light peer-checked:text-white transition-all" x-text="option"></div>
                                        </label>
                                    </template>
                                </div>
                            </div>

                            <!-- 9. Jenis Air -->
                            <div>
                                <label class="block text-xs font-semibold text-textMain-light dark:text-textMain-dark mb-1">9. Jenis Air di Lokasi <span class="text-danger">*</span></label>
                                <div class="flex gap-3">
                                    <template x-for="option in ['Tawar', 'Payau', 'Asin']">
                                        <label class="cursor-pointer relative flex-1">
                                            <input type="radio" name="q50_typeA50" :value="option" class="peer sr-only" required>
                                            <div class="rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-2 text-sm text-center font-medium text-gray-500 hover:bg-gray-50 peer-checked:border-teal-light peer-checked:bg-teal-light peer-checked:text-white transition-all" x-text="option"></div>
                                        </label>
                                    </template>
                                </div>
                            </div>

                            <!-- 10. Mudah dijangkau -->
                            <div>
                                <label class="block text-xs font-semibold text-textMain-light dark:text-textMain-dark mb-1">10. Mudah dijangkau untuk mobilitas material? <span class="text-danger">*</span></label>
                                <div class="flex gap-3">
                                    <template x-for="option in ['Ya', 'Tidak']">
                                        <label class="cursor-pointer relative w-24">
                                            <input type="radio" name="q52_15Apakah" :value="option" class="peer sr-only" required>
                                            <div class="rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-2 text-sm text-center font-medium text-gray-500 hover:bg-gray-50 peer-checked:border-teal-light peer-checked:bg-teal-light peer-checked:text-white transition-all" x-text="option"></div>
                                        </label>
                                    </template>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- C. Dokumen Pendukung -->
            <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-teal-light/10 text-teal-light flex items-center justify-center font-bold text-sm">C</div>
                    <h3 class="text-base font-semibold text-textMain-light dark:text-textMain-dark">Lampiran Dokumen Pendukung</h3>
                </div>
                
                <div class="p-6 space-y-6">
                    <!-- Warning Banner -->
                    <style>
                        .warn-card { background-color: #fefce8; border-color: #fef08a; }
                        .warn-icon { color: #ca8a04; }
                        .warn-text { color: #a16207; }
                        .warn-strong { color: #713f12; }

                        .dark .warn-card { background-color: rgba(113, 63, 18, 0.2); border-color: rgba(133, 77, 14, 0.5); }
                        .dark .warn-icon { color: #eab308; }
                        .dark .warn-text { color: #fef08a; }
                        .dark .warn-strong { color: #fefce8; }
                    </style>
                    <div class="rounded-2xl p-4 flex gap-4 items-start border warn-card">
                        <div class="mt-0.5 warn-icon">
                            <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                        </div>
                        <div>
                            <h4 class="inline-block py-1 mb-1 text-sm font-bold rounded-lg warn-strong">Akses Link Harus Publik</h4>
                            <p class="text-xs leading-relaxed warn-text">
                                Pastikan link Google Drive atau penyimpanan cloud yang Anda berikan memiliki akses <strong class="warn-strong">Publik (Siapa saja yang memiliki link dapat melihat)</strong>. Jika link bersifat privat/terkunci, tim verifikator tidak dapat melihat dokumen dan pengajuan Anda tidak dapat diproses.
                            </p>
                        </div>
                    </div>

                    <!-- Input Link -->
                    <div>
                        <label class="block text-xs font-semibold text-textMain-light dark:text-textMain-dark mb-1">Tautan (Link) Folder Dokumen <span class="text-danger">*</span></label>
                        <input type="url" name="link_dokumen" required class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:border-teal-light focus:ring-1 focus:ring-teal-light outline-none transition-all placeholder-gray-400" placeholder="https://drive.google.com/drive/folders/...">
                        
                        <div class="mt-4 p-5 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-700">
                            <p class="text-xs font-bold text-gray-700 dark:text-gray-300 mb-3">Rincian dokumen yang perlu diupload di dalam folder link tersebut:</p>
                            <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-2 list-disc pl-4">
                                <li><strong>Proposal Pengajuan KNMP</strong> (Wajib melampirkan proposal utama pengajuan program beserta usulan dari koperasi desa dan dinas perikanan)</li>
                                <li><strong>Sertifikat / Pernyataan Lahan</strong></li>
                                <li><strong>Denah Lahan</strong> (Diarsir)</li>
                                <li><strong>Pernyataan Clean & Clear</strong></li>
                                <li><strong>Foto-foto Lahan</strong></li>
                                <li><strong>Dokumen Pendukung Tata Ruang</strong> (Opsional)</li>
                                <li><strong>Surat Pelepasan Adat / Hibah</strong> <span x-show="formLahan.kepemilikan === 'Lahan milik adat' || formLahan.kepemilikan === 'Lahan milik pribadi'" class="text-danger font-medium">(Wajib dilampirkan jika lahan milik adat/pribadi)</span><span x-show="formLahan.kepemilikan !== 'Lahan milik adat' && formLahan.kepemilikan !== 'Lahan milik pribadi'">(Jika lahan adat/pribadi)</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-3 mt-8 border-t border-gray-100 dark:border-gray-800 pt-4">
                <a href="{{ route('program.master.calon-lokasi.index', ['program' => strtolower($activeProgram)]) }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors cursor-pointer flex items-center gap-2">
                    <i class="fa-solid fa-xmark"></i> <span>Batal</span>
                </a>
                <button type="submit" :disabled="isSubmitting" class="px-4 py-2 bg-teal-light text-white rounded-lg text-sm font-medium hover:bg-teal-light/90 transition-colors flex items-center gap-2 cursor-pointer shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!isSubmitting">Kirim Pengajuan</span>
                    <span x-show="isSubmitting">Memproses...</span>
                    <i x-show="!isSubmitting" class="fa-solid fa-paper-plane"></i>
                    <i x-show="isSubmitting" class="fa-solid fa-spinner fa-spin"></i>
                </button>
            </div>
        </div>
    </form>
</div>

<!-- API & Interactivity Script -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('createPengajuan', () => ({
            isSubmitting: false,
            provinces: [], regencies: [], districts: [], villages: [],
            formData: {
                provinsi: '',
                kabupaten: '',
                kecamatan: '',
                desa: ''
            },
            formLahan: {
                kepemilikan: '',
                das: 'Tidak'
            },
            
            async initForm() {
                await this.fetchProvinces();
                const userProvId = "{{ $userProvinsiId ?? '' }}";
                const userKabId = "{{ $userKabupatenId ?? '' }}";

                if (userProvId) {
                    this.formData.provinsi = userProvId;
                    await this.fetchRegencies();
                    if (userKabId) {
                        this.formData.kabupaten = userKabId;
                        await this.fetchDistricts();
                    }
                }
            },

            async fetchProvinces() {
                try {
                    const res = await fetch('/api/internal/regions/provinces');
                    const json = await res.json();
                    this.provinces = Array.isArray(json) ? json : (json.data || []);
                } catch(e) { console.error('Gagal fetch provinsi', e); }
            },

            async fetchRegencies() {
                this.formData.kabupaten = '';
                this.formData.kecamatan = '';
                this.formData.desa = '';
                this.regencies = []; this.districts = []; this.villages = [];
                if(!this.formData.provinsi) return;
                try {
                    const res = await fetch(`/api/internal/regions/regencies/${this.formData.provinsi}`);
                    const json = await res.json();
                    this.regencies = Array.isArray(json) ? json : (json.data || []);
                } catch(e) { console.error('Gagal fetch kabupaten', e); }
            },

            async fetchDistricts() {
                this.formData.kecamatan = '';
                this.formData.desa = '';
                this.districts = []; this.villages = [];
                if(!this.formData.kabupaten) return;
                try {
                    const res = await fetch(`/api/internal/regions/districts/${this.formData.kabupaten}`);
                    const json = await res.json();
                    this.districts = Array.isArray(json) ? json : (json.data || []);
                } catch(e) { console.error('Gagal fetch kecamatan', e); }
            },

            async fetchVillages() {
                this.formData.desa = '';
                this.villages = [];
                if(!this.formData.kecamatan) return;
                try {
                    const res = await fetch(`/api/internal/regions/villages/${this.formData.kecamatan}`);
                    const json = await res.json();
                    this.villages = Array.isArray(json) ? json : (json.data || []);
                } catch(e) { console.error('Gagal fetch desa', e); }
            },

            submitForm(e) {
                this.isSubmitting = true;
                const form = e.target;
                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        Alpine.store('toast').showToast({ message: data.message, type: 'success' });
                        setTimeout(() => {
                            window.location.href = "{{ route('program.master.calon-lokasi.index', ['program' => strtolower($activeProgram)]) }}";
                        }, 1500);
                    } else {
                        this.isSubmitting = false;
                        Alpine.store('toast').showToast({ message: data.message || 'Terjadi kesalahan.', type: 'danger' });
                    }
                })
                .catch(err => {
                    this.isSubmitting = false;
                    Alpine.store('toast').showToast({ message: 'Gagal mengirim form. Periksa koneksi internet Anda.', type: 'danger' });
                    console.error(err);
                });
            }
        }));
    });
</script>
@endsection

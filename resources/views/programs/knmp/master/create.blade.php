@extends('layouts.app')

@section('title', 'KNMP - Form Pengajuan Calon Lokasi')

@section('content')
<style>
    /* Custom Select Arrow Styling */
    .custom-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1rem;
        padding-right: 2.5rem !important;
    }
</style>

<div x-data="createFormManager()" x-init="initData()" class="max-w-5xl mx-auto pb-12">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-textMain-light dark:text-textMain-dark">Form Pendaftaran KNMP</h2>
            <p class="text-textMuted-light dark:text-textMuted-dark text-sm mt-1">Program Prioritas Kampung Nelayan Merah Putih</p>
        </div>
        <a href="{{ route('program.master.calon-lokasi.index') }}" class="px-4 py-2 bg-white dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 text-textMain-light dark:text-textMain-dark rounded-lg text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Alert Success/Error -->
    <div x-show="toast.show" x-transition.opacity style="display: none;" class="fixed top-24 right-8 z-[100] flex items-center p-4 mb-4 w-full max-w-xs text-textMuted-light bg-white rounded-xl shadow-xl dark:text-textMuted-dark dark:bg-bgSurface-dark border-l-4" :class="toast.type === 'success' ? 'border-teal-light' : 'border-danger'" role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg" :class="toast.type === 'success' ? 'text-teal-light bg-teal-50 dark:bg-teal-900/30' : 'text-danger bg-red-50 dark:bg-red-900/30'">
            <i class="fa-solid" :class="toast.type === 'success' ? 'fa-check' : 'fa-xmark'"></i>
        </div>
        <div class="ml-3 text-sm font-semibold text-textMain-light dark:text-textMain-dark" x-text="toast.message"></div>
    </div>

    <form @submit.prevent="submitForm($event)">
        
        <!-- SECTION A -->
        <div class="bg-white dark:bg-bgSurface-dark rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 mb-6 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                <h3 class="text-lg font-bold text-textMain-light dark:text-textMain-dark flex items-center gap-2">
                    <i class="fa-solid fa-map-location-dot text-teal-light"></i> A. INFORMASI WILAYAH & PENGISI
                </h3>
            </div>
            <div class="p-6">
                <!-- Row 1: Wilayah -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-textMain-light dark:text-textMain-dark mb-2">1. Provinsi <span class="text-danger">*</span></label>
                        <select name="provinsi" x-model="formData.provinsi" @change="fetchKabupaten()" required class="custom-select w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm outline-none focus:border-teal-light transition-all text-textMain-light dark:text-textMain-dark">
                            <option value="" disabled selected>Pilih Provinsi</option>
                            <template x-for="p in listProvinsi" :key="p.id"><option :value="p.id" x-text="p.name"></option></template>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-textMain-light dark:text-textMain-dark mb-2">2. Kab/Kota <span class="text-danger">*</span></label>
                        <select name="kabupaten" x-model="formData.kabupaten" @change="fetchKecamatan()" required :disabled="!formData.provinsi" class="custom-select w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm outline-none focus:border-teal-light transition-all text-textMain-light dark:text-textMain-dark disabled:opacity-50">
                            <option value="" disabled selected>Pilih Kab/Kota</option>
                            <template x-for="k in listKabupaten" :key="k.id"><option :value="k.id" x-text="k.name"></option></template>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-textMain-light dark:text-textMain-dark mb-2">3. Kecamatan <span class="text-danger">*</span></label>
                        <select name="kecamatan" x-model="formData.kecamatan" @change="fetchDesa()" required :disabled="!formData.kabupaten" class="custom-select w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm outline-none focus:border-teal-light transition-all text-textMain-light dark:text-textMain-dark disabled:opacity-50">
                            <option value="" disabled selected>Pilih Kecamatan</option>
                            <template x-for="k in listKecamatan" :key="k.id"><option :value="k.id" x-text="k.name"></option></template>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-textMain-light dark:text-textMain-dark mb-2">4. Desa/Kelurahan <span class="text-danger">*</span></label>
                        <select name="desa" x-model="formData.desa" required :disabled="!formData.kecamatan" class="custom-select w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm outline-none focus:border-teal-light transition-all text-textMain-light dark:text-textMain-dark disabled:opacity-50">
                            <option value="" disabled selected>Pilih Desa/Kelurahan</option>
                            <template x-for="d in listDesa" :key="d.id"><option :value="d.id" x-text="d.name"></option></template>
                        </select>
                    </div>
                </div>

                <!-- Row 2: Pengisi -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-textMain-light dark:text-textMain-dark mb-2">5. Nama Pengisi <span class="text-danger">*</span></label>
                        <input type="text" name="q5_nama_pengisi" required placeholder="Masukkan Nama Lengkap" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm outline-none focus:border-teal-light transition-all text-textMain-light dark:text-textMain-dark">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-textMain-light dark:text-textMain-dark mb-2">6. Jabatan Pengisi <span class="text-danger">*</span></label>
                        <input type="text" name="q6_jabatan_pengisi" required placeholder="Masukkan Jabatan" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm outline-none focus:border-teal-light transition-all text-textMain-light dark:text-textMain-dark">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-textMain-light dark:text-textMain-dark mb-2">7. No HP Pengisi <span class="text-danger">*</span></label>
                        <input type="text" name="q7_nohp_pengisi" required placeholder="Contoh: 081234567890" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm outline-none focus:border-teal-light transition-all text-textMain-light dark:text-textMain-dark">
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION B1: LEGALITAS & STATUS -->
        <div class="bg-white dark:bg-bgSurface-dark rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 mb-6 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                <h3 class="text-lg font-bold text-textMain-light dark:text-textMain-dark flex items-center gap-2">
                    <i class="fa-solid fa-file-contract text-teal-light"></i> B1. LEGALITAS & STATUS LAHAN
                </h3>
            </div>
            <div class="p-6">
                <!-- Q1 -->
                <div class="mb-4">
                    <label class="block text-sm font-bold text-textMain-light dark:text-textMain-dark mb-2">1. Status kepemilikan lahan <span class="text-danger">*</span></label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach(['Lahan milik Pemkab/ Pemkot', 'Lahan milik pribadi', 'Lahan milik adat', 'Lahan milik Pemprov', 'Lahan milik Pemdes'] as $val)
                        <label class="flex items-center gap-2 p-3 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <input type="radio" name="q1_status_kepemilikan" value="{{ $val }}" required class="w-4 h-4 text-teal-light border-gray-300"> 
                            <span class="text-xs font-medium text-textMain-light dark:text-textMain-dark">{{ $val }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Dokumen Q1 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-4" x-data="{ f1: '', f2: '' }">
                    <div>
                        <label class="block text-xs font-semibold text-textMuted-light dark:text-textMuted-dark mb-2">Sertifikat kepemilikan / Kades</label>
                        <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fa-solid fa-upload text-gray-400 mb-1"></i>
                                <template x-if="!f1"><span class="text-xs text-teal-light font-semibold">Pilih File</span></template>
                                <template x-if="f1"><span class="text-xs text-teal-light font-semibold truncate max-w-[200px]" x-text="f1"></span></template>
                            </div>
                            <input type="file" name="q1_sertifikat" accept=".pdf,.png,.jpg" class="hidden" @change="f1 = $event.target.files[0]?.name">
                        </label>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-textMuted-light dark:text-textMuted-dark mb-2">Surat pernyataan hibah bermaterai</label>
                        <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fa-solid fa-upload text-gray-400 mb-1"></i>
                                <template x-if="!f2"><span class="text-xs text-teal-light font-semibold">Pilih File</span></template>
                                <template x-if="f2"><span class="text-xs text-teal-light font-semibold truncate max-w-[200px]" x-text="f2"></span></template>
                            </div>
                            <input type="file" name="q1_surat_hibah" accept=".pdf,.png,.jpg" class="hidden" @change="f2 = $event.target.files[0]?.name">
                        </label>
                    </div>
                </div>

                <!-- Koordinat -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-textMain-light dark:text-textMain-dark mb-2">Titik koordinat X (Lintang) <span class="text-danger">*</span></label>
                        <input type="text" name="q1_koordinat_x" required placeholder="-6.123456" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm outline-none focus:border-teal-light transition-all text-textMain-light dark:text-textMain-dark">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-textMain-light dark:text-textMain-dark mb-2">Titik koordinat Y (Bujur) <span class="text-danger">*</span></label>
                        <input type="text" name="q1_koordinat_y" required placeholder="106.123456" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm outline-none focus:border-teal-light transition-all text-textMain-light dark:text-textMain-dark">
                    </div>
                </div>

                <!-- Lahan Pics -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6" x-data="{ f3: '', f4: '' }">
                    <div>
                        <label class="block text-xs font-semibold text-textMuted-light dark:text-textMuted-dark mb-2">Denah lahan diarsir</label>
                        <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fa-solid fa-upload text-gray-400 mb-1"></i>
                                <template x-if="!f3"><span class="text-xs text-teal-light font-semibold">Pilih File</span></template>
                                <template x-if="f3"><span class="text-xs text-teal-light font-semibold truncate max-w-[200px]" x-text="f3"></span></template>
                            </div>
                            <input type="file" name="q1_denah" accept=".pdf,.png,.jpg" class="hidden" @change="f3 = $event.target.files[0]?.name">
                        </label>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-textMuted-light dark:text-textMuted-dark mb-2">Foto-Foto Lahan (Bisa pilih banyak)</label>
                        <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fa-solid fa-upload text-gray-400 mb-1"></i>
                                <template x-if="!f4"><span class="text-xs text-teal-light font-semibold">Pilih File</span></template>
                                <template x-if="f4"><span class="text-xs text-teal-light font-semibold truncate max-w-[200px]" x-text="f4"></span></template>
                            </div>
                            <input type="file" name="q1_foto_lahan[]" multiple accept=".png,.jpg,.jpeg" class="hidden" @change="f4 = $event.target.files.length > 1 ? $event.target.files.length + ' file terpilih' : $event.target.files[0]?.name">
                        </label>
                    </div>
                </div>

                <!-- Q2 -->
                <div class="mb-4">
                    <label class="block text-sm font-bold text-textMain-light dark:text-textMain-dark mb-2">2. Bagaimana status fungsi lahan apakah sesuai dengan dokumen RTRW/RZWP3K/ PKKPRL?</label>
                    <div class="flex gap-6 mb-3">
                        <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="q2_status_fungsi" value="Sesuai" class="w-4 h-4 text-teal-light border-gray-300"> <span class="text-sm font-medium text-textMain-light dark:text-textMain-dark">Sesuai</span></label>
                        <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="q2_status_fungsi" value="Tidak Sesuai" class="w-4 h-4 text-teal-light border-gray-300"> <span class="text-sm font-medium text-textMain-light dark:text-textMain-dark">Tidak Sesuai</span></label>
                    </div>
                    <div class="w-full md:w-1/2" x-data="{ f: '' }">
                        <label class="block text-xs font-semibold text-textMuted-light dark:text-textMuted-dark mb-2">Dokumen Pendukung (Opsional)</label>
                        <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fa-solid fa-upload text-gray-400 mb-1"></i>
                                <template x-if="!f"><span class="text-xs text-teal-light font-semibold">Pilih File PDF</span></template>
                                <template x-if="f"><span class="text-xs text-teal-light font-semibold truncate max-w-[200px]" x-text="f"></span></template>
                            </div>
                            <input type="file" name="q2_dokumen_pendukung" accept=".pdf" class="hidden" @change="f = $event.target.files[0]?.name">
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION B2: KONDISI LAHAN -->
        <div class="bg-white dark:bg-bgSurface-dark rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 mb-6 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                <h3 class="text-lg font-bold text-textMain-light dark:text-textMain-dark flex items-center gap-2">
                    <i class="fa-solid fa-earth-asia text-teal-light"></i> B2. KONDISI & KARAKTERISTIK LAHAN
                </h3>
            </div>
            <div class="p-6">
                <!-- Group of Yes/No Questions -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6 text-textMain-light dark:text-textMain-dark">
                    <div>
                        <label class="block text-sm font-bold mb-2">3. Apakah lahan merupakan kawasan mangrove?</label>
                        <div class="flex gap-6"><label class="flex items-center gap-2"><input type="radio" name="q3_mangrove" value="Ya" class="w-4 h-4 text-teal-light"> <span class="text-sm">Ya</span></label><label class="flex items-center gap-2"><input type="radio" name="q3_mangrove" value="Tidak" class="w-4 h-4 text-teal-light"> <span class="text-sm">Tidak</span></label></div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2">4. Dekat area konservasi laut?</label>
                        <div class="flex gap-6"><label class="flex items-center gap-2"><input type="radio" name="q4_konservasi" value="Ya" class="w-4 h-4 text-teal-light"> <span class="text-sm">Ya</span></label><label class="flex items-center gap-2"><input type="radio" name="q4_konservasi" value="Tidak" class="w-4 h-4 text-teal-light"> <span class="text-sm">Tidak</span></label></div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2">5. Kawasan hutan lindung?</label>
                        <div class="flex gap-6"><label class="flex items-center gap-2"><input type="radio" name="q5_hutan_lindung" value="Ya" class="w-4 h-4 text-teal-light"> <span class="text-sm">Ya</span></label><label class="flex items-center gap-2"><input type="radio" name="q5_hutan_lindung" value="Tidak" class="w-4 h-4 text-teal-light"> <span class="text-sm">Tidak</span></label></div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2">6. Termasuk wilayah RIPPN?</label>
                        <div class="flex gap-6"><label class="flex items-center gap-2"><input type="radio" name="q6_rippn" value="Ya" class="w-4 h-4 text-teal-light"> <span class="text-sm">Ya</span></label><label class="flex items-center gap-2"><input type="radio" name="q6_rippn" value="Tidak" class="w-4 h-4 text-teal-light"> <span class="text-sm">Tidak</span></label></div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2">9. Lahan berada pada area sempadan?</label>
                        <div class="flex gap-6"><label class="flex items-center gap-2"><input type="radio" name="q9_sempadan" value="Ya" class="w-4 h-4 text-teal-light"> <span class="text-sm">Ya</span></label><label class="flex items-center gap-2"><input type="radio" name="q9_sempadan" value="Tidak" class="w-4 h-4 text-teal-light"> <span class="text-sm">Tidak</span></label></div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2">13. Dipengaruhi pasang surut?</label>
                        <div class="flex gap-6"><label class="flex items-center gap-2"><input type="radio" name="q13_pasang_surut" value="Ya" class="w-4 h-4 text-teal-light"> <span class="text-sm">Ya</span></label><label class="flex items-center gap-2"><input type="radio" name="q13_pasang_surut" value="Tidak" class="w-4 h-4 text-teal-light"> <span class="text-sm">Tidak</span></label></div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2">15. Daerah sedimentasi?</label>
                        <div class="flex gap-6"><label class="flex items-center gap-2"><input type="radio" name="q15_sedimentasi" value="Ya" class="w-4 h-4 text-teal-light"> <span class="text-sm">Ya</span></label><label class="flex items-center gap-2"><input type="radio" name="q15_sedimentasi" value="Tidak" class="w-4 h-4 text-teal-light"> <span class="text-sm">Tidak</span></label></div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2">16. Jenis air di lokasi</label>
                        <div class="flex gap-6"><label class="flex items-center gap-2"><input type="radio" name="q16_jenis_air" value="Asin" class="w-4 h-4 text-teal-light"> <span class="text-sm">Asin</span></label><label class="flex items-center gap-2"><input type="radio" name="q16_jenis_air" value="Tawar" class="w-4 h-4 text-teal-light"> <span class="text-sm">Tawar</span></label><label class="flex items-center gap-2"><input type="radio" name="q16_jenis_air" value="Payau" class="w-4 h-4 text-teal-light"> <span class="text-sm">Payau</span></label></div>
                    </div>
                </div>

                <!-- Numeric Measurements -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-4 text-textMain-light dark:text-textMain-dark">
                    <div>
                        <label class="block text-sm font-semibold mb-2">7. Luas lahan (meter persegi)</label>
                        <input type="number" name="q7_luas_lahan" step="any" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm outline-none focus:border-teal-light">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">8. Jarak lahan dari pantai (meter)</label>
                        <input type="number" name="q8_jarak_pantai" step="any" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm outline-none focus:border-teal-light">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">11. Kemiringan lahan (derajat)</label>
                        <input type="number" name="q11_kemiringan" step="any" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm outline-none focus:border-teal-light">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">12. Jenis substrat tanah</label>
                        <select name="q12_substrat" class="custom-select w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm outline-none focus:border-teal-light">
                            <option value="" disabled selected>Pilih Substrat</option>
                            <option value="Berlumpur">Berlumpur</option><option value="Berbatu">Berbatu</option><option value="Berpasir">Berpasir</option><option value="Berlumpur dan Berpasir">Berlumpur dan Berpasir</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">14. Kedalaman perairan depan lahan (m)</label>
                        <input type="number" name="q14_kedalaman_perairan" step="any" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm outline-none focus:border-teal-light">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">17. Kedalaman sumber air tanah (m)</label>
                        <input type="number" name="q17_kedalaman_air_tanah" step="any" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm outline-none focus:border-teal-light">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">18. Jarak dengan kabupaten (km)</label>
                        <input type="number" name="q18_jarak_kabupaten" step="any" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm outline-none focus:border-teal-light">
                    </div>
                </div>

                <!-- Q10 DAS (Dynamic) -->
                <div class="mb-4 text-textMain-light dark:text-textMain-dark" x-data="{ isDas: '' }">
                    <label class="block text-sm font-bold mb-2">10. Lahan berada pada Daerah Aliran Sungai (DAS)?</label>
                    <div class="flex gap-6 mb-2">
                        <label class="flex items-center gap-2"><input type="radio" name="q10_das" value="Ya" x-model="isDas" class="w-4 h-4 text-teal-light"> <span class="text-sm">Ya</span></label>
                        <label class="flex items-center gap-2"><input type="radio" name="q10_das" value="Tidak" x-model="isDas" class="w-4 h-4 text-teal-light"> <span class="text-sm">Tidak</span></label>
                    </div>
                    <div x-show="isDas === 'Ya'" class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 p-4 bg-gray-50/50 dark:bg-gray-800/30 rounded-xl border border-gray-100 dark:border-gray-800 mt-2">
                        <div>
                            <label class="block text-sm font-semibold mb-2">Jarak dari titik pantai? (meter)</label>
                            <input type="number" name="q10_jarak_pantai" step="any" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm outline-none focus:border-teal-light">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">Berapa lebar sungai? (meter)</label>
                            <input type="number" name="q10_lebar_sungai" step="any" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm outline-none focus:border-teal-light">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION B3: AKSES & DOKUMEN -->
        <div class="bg-white dark:bg-bgSurface-dark rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 mb-6 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                <h3 class="text-lg font-bold text-textMain-light dark:text-textMain-dark flex items-center gap-2">
                    <i class="fa-solid fa-folder-open text-teal-light"></i> B3. AKSES & DOKUMEN PENGAJUAN
                </h3>
            </div>
            <div class="p-6 text-textMain-light dark:text-textMain-dark">
                <!-- Q19 -->
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">19. Mobilitas material pembangunan (akses, jarak, keamanan)</label>
                    <div class="flex gap-6 mb-4">
                        <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="q19_mobilitas" value="Mudah dijangkau" class="w-4 h-4 text-teal-light"> <span class="text-sm">Mudah dijangkau</span></label>
                        <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="q19_mobilitas" value="Sulit Dijangkau" class="w-4 h-4 text-teal-light"> <span class="text-sm">Sulit Dijangkau</span></label>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4" x-data="{ f1: '', f2: '', f3: '' }">
                        <div>
                            <label class="block text-xs font-semibold mb-2 text-center text-textMuted-light dark:text-textMuted-dark">Foto Udara Lahan</label>
                            <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-plane-up text-gray-400 mb-1"></i>
                                    <template x-if="!f1"><span class="text-xs text-teal-light font-semibold">Pilih File</span></template>
                                    <template x-if="f1"><span class="text-xs text-teal-light font-semibold truncate max-w-[150px]" x-text="f1"></span></template>
                                </div>
                                <input type="file" name="q19_foto_udara" accept=".jpg,.png" class="hidden" @change="f1 = $event.target.files[0]?.name">
                            </label>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold mb-2 text-center text-textMuted-light dark:text-textMuted-dark">Surat Pelepasan Adat</label>
                            <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-handshake text-gray-400 mb-1"></i>
                                    <template x-if="!f2"><span class="text-xs text-teal-light font-semibold">Pilih File</span></template>
                                    <template x-if="f2"><span class="text-xs text-teal-light font-semibold truncate max-w-[150px]" x-text="f2"></span></template>
                                </div>
                                <input type="file" name="q19_pelepasan_adat" accept=".pdf" class="hidden" @change="f2 = $event.target.files[0]?.name">
                            </label>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold mb-2 text-center text-textMuted-light dark:text-textMuted-dark">Surat Lahan Clean & Clear</label>
                            <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-file-shield text-gray-400 mb-1"></i>
                                    <template x-if="!f3"><span class="text-xs text-teal-light font-semibold">Pilih File</span></template>
                                    <template x-if="f3"><span class="text-xs text-teal-light font-semibold truncate max-w-[150px]" x-text="f3"></span></template>
                                </div>
                                <input type="file" name="q19_clean_clear" accept=".pdf" class="hidden" @change="f3 = $event.target.files[0]?.name">
                            </label>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100 dark:border-gray-800 my-6">

                <!-- Q20 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-4" x-data="{ f: '' }">
                    <div>
                        <label class="block text-sm font-bold mb-1">20. Proposal Pengajuan KNMP <span class="text-danger">*</span></label>
                        <p class="text-xs text-textMuted-light dark:text-textMuted-dark mb-2">Upload Proposal (Wajib format PDF)</p>
                        <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fa-solid fa-file-pdf text-gray-400 mb-1"></i>
                                <template x-if="!f"><span class="text-xs text-teal-light font-semibold">Pilih File PDF</span></template>
                                <template x-if="f"><span class="text-xs text-teal-light font-semibold truncate max-w-[200px]" x-text="f"></span></template>
                            </div>
                            <input type="file" required name="q20_proposal_knmp" accept=".pdf" class="hidden" @change="f = $event.target.files[0]?.name">
                        </label>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-transparent select-none mb-1">.</label>
                        <p class="text-xs text-textMuted-light dark:text-textMuted-dark mb-2">Link Video/Klip (Opsional)</p>
                        <input type="url" name="q20_link_video" placeholder="https://youtube.com/..." class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm outline-none focus:border-teal-light transition-all">
                    </div>
                </div>

                <!-- Q21 -->
                <div class="mb-2" x-data="{ f: '' }">
                    <label class="block text-sm font-bold mb-2">21. Proposal usulan Ketua Koperasi Desa / Dinas Perikanan</label>
                    <label class="flex flex-col items-center justify-center w-full md:w-1/2 h-24 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fa-solid fa-file-pdf text-gray-400 mb-1"></i>
                            <template x-if="!f"><span class="text-xs text-teal-light font-semibold">Pilih File PDF</span></template>
                            <template x-if="f"><span class="text-xs text-teal-light font-semibold truncate max-w-[250px]" x-text="f"></span></template>
                        </div>
                        <input type="file" name="q21_proposal_koperasi" accept=".pdf" class="hidden" @change="f = $event.target.files[0]?.name">
                    </label>
                </div>
            </div>
        </div>

        <!-- SUBMIT BAR -->
        <div class="bg-white dark:bg-bgSurface-dark p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 flex justify-end gap-4 items-center">
            <p class="text-sm text-textMuted-light dark:text-textMuted-dark mr-auto hidden sm:block">Pastikan isian bertanda <span class="text-danger">*</span> terisi.</p>
            <a href="{{ route('program.master.calon-lokasi.index') }}" class="px-5 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-textMain-light dark:text-textMain-dark rounded-lg text-sm font-bold transition-colors border border-gray-200 dark:border-gray-700">Batal</a>
            <button type="submit" :disabled="isSubmitting" class="px-6 py-2 bg-teal-light hover:bg-teal-600 text-white rounded-lg text-sm font-bold transition-all flex items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">
                <i class="fa-solid fa-spinner fa-spin" x-show="isSubmitting" style="display: none;"></i>
                <i class="fa-solid fa-paper-plane" x-show="!isSubmitting"></i>
                <span x-text="isSubmitting ? 'Menyimpan...' : 'Kirim Pengajuan'"></span>
            </button>
        </div>

    </form>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('createFormManager', () => ({
            listProvinsi: [], listKabupaten: [], listKecamatan: [], listDesa: [],
            isSubmitting: false,
            toast: { show: false, message: '', type: 'success' },
            formData: { provinsi: '', kabupaten: '', kecamatan: '', desa: '' },

            async initData() {
                try {
                    const res = await fetch('/api/internal/regions/provinces');
                    this.listProvinsi = await res.json();
                } catch (err) {}
            },
            async fetchKabupaten() {
                this.formData.kabupaten = ''; this.formData.kecamatan = ''; this.formData.desa = '';
                if(!this.formData.provinsi) return;
                try {
                    const res = await fetch(`/api/internal/regions/regencies/${this.formData.provinsi}`);
                    this.listKabupaten = await res.json();
                } catch (err) {}
            },
            async fetchKecamatan() {
                this.formData.kecamatan = ''; this.formData.desa = '';
                if(!this.formData.kabupaten) return;
                try {
                    const res = await fetch(`/api/internal/regions/districts/${this.formData.kabupaten}`);
                    this.listKecamatan = await res.json();
                } catch (err) {}
            },
            async fetchDesa() {
                this.formData.desa = '';
                if(!this.formData.kecamatan) return;
                try {
                    const res = await fetch(`/api/internal/regions/villages/${this.formData.kecamatan}`);
                    this.listDesa = await res.json();
                } catch (err) {}
            },

            showToastMsg(msg, type='success') {
                this.toast.message = msg;
                this.toast.type = type;
                this.toast.show = true;
                setTimeout(() => this.toast.show = false, 4000);
            },

            async submitForm(event) {
                this.isSubmitting = true;
                let fd = new FormData(event.target);
                fd.append('_token', '{{ csrf_token() }}');

                try {
                    const res = await fetch('{{ route('program.master.calon-lokasi.store') }}', {
                        method: 'POST', body: fd, headers: { 'Accept': 'application/json' }
                    });
                    const data = await res.json();
                    if (res.ok && data.success) {
                        this.showToastMsg(data.message || 'Pengajuan disimpan!', 'success');
                        setTimeout(() => window.location.href = '{{ route('program.master.calon-lokasi.index') }}', 1500);
                    } else {
                        this.showToastMsg(data.message || 'Gagal menyimpan!', 'error');
                        this.isSubmitting = false;
                    }
                } catch (err) {
                    this.showToastMsg('Koneksi gagal!', 'error');
                    this.isSubmitting = false;
                }
            }
        }));
    });
</script>
@endsection

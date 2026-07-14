<x-modal-form show="isCreateOpen" 
              title="Tambah Vendor / Penyedia" 
              action="`{{ route('program.master.vendor.store') }}`" 
              method="POST" 
              submitText="Simpan Data">
    
    <div>
        <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Nama Perusahaan <span class="text-danger">*</span></label>
        <input type="text" name="nama" x-model="formData.nama" required placeholder="Contoh: PT Samudera Konstruksi Nusantara"
               class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark">
    </div>

    <div>
        <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">NPWP</label>
        <input type="text" name="npwp" x-model="formData.npwp" placeholder="01.234.567.8-901.000"
               class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark">
    </div>

    <div>
        <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Direktur Utama</label>
        <input type="text" name="direktur_utama" x-model="formData.direktur_utama" placeholder="Nama Direktur / Pimpinan"
               class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark">
    </div>

    <div>
        <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Kontak / Email</label>
        <input type="text" name="kontak" x-model="formData.kontak" placeholder="0812... / email@..."
               class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark">
    </div>

    <div>
        <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Kualifikasi SBU</label>
        <input type="text" name="kualifikasi_sbu" x-model="formData.kualifikasi_sbu" placeholder="Menengah (M1)"
               class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark">
    </div>

    <div>
        <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Status <span class="text-danger">*</span></label>
        <select name="status" x-model="formData.status" required
                class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark">
            <option value="Aktif">Aktif</option>
            <option value="Blacklist">Blacklist</option>
            <option value="Suspend">Suspend</option>
            <option value="Tidak Aktif">Tidak Aktif</option>
        </select>
    </div>

</x-modal-form>

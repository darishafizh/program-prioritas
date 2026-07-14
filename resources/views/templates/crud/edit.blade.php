{{-- 
    TEMPLATE DASAR CRUD - PARTIAL MODAL EDIT
    Menggunakan komponen <x-modal-form> dengan dynamic binding action.
--}}

<x-modal-form show="isEditOpen" 
              title="Edit Data Referensi" 
              maxWidth="max-w-[340px]"
              action="`{{ url('master/knmp/template-crud') }}/${formData.id}`" 
              method="PUT" 
              submitText="Perbarui Data">
    
    <div>
        <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Kode Data <span class="text-danger">*</span></label>
        <input type="text" name="kode" x-model="formData.kode" required placeholder="Contoh: REF-001"
               class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark">
    </div>

    <div>
        <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Nama Referensi <span class="text-danger">*</span></label>
        <input type="text" name="nama" x-model="formData.nama" required placeholder="Contoh: Indikator Utama Produksi"
               class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark">
    </div>

    <div>
        <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Kategori <span class="text-danger">*</span></label>
        <select name="kategori" x-model="formData.kategori" required
                class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark">
            <option value="">Pilih Kategori...</option>
            <option value="Operasional">Operasional</option>
            <option value="Infrastruktur">Infrastruktur</option>
            <option value="Logistik">Logistik</option>
            <option value="Umum">Umum</option>
        </select>
    </div>

    <div>
        <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Status <span class="text-danger">*</span></label>
        <select name="status" x-model="formData.status" required
                class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark">
            <option value="Aktif">Aktif</option>
            <option value="Tidak Aktif">Tidak Aktif</option>
        </select>
    </div>

    <div>
        <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Keterangan / Catatan</label>
        <textarea name="keterangan" x-model="formData.keterangan" rows="3" placeholder="Masukkan keterangan tambahan jika diperlukan..."
                  class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark"></textarea>
    </div>

</x-modal-form>

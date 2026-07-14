<x-modal-form show="isCreateOpen" 
              title="Tambah Data Tahap" 
              action="`{{ route('program.master.tahap.store') }}`" 
              method="POST" 
              submitText="Simpan Data">
    
    <div>
        <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Nama Tahap <span class="text-danger">*</span></label>
        <input type="text" name="nama_tahap" x-model="formData.nama_tahap" required placeholder="Contoh: I, II, III, atau Susulan"
               class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark">
        <p class="text-[11px] text-textMuted-light dark:text-textMuted-dark mt-1">Masukkan nama atau penomoran tahap pelaksanaan.</p>
    </div>

    <div>
        <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Tahun <span class="text-danger">*</span></label>
        <input type="number" name="tahun" x-model="formData.tahun" required min="2000" max="2099" placeholder="2026"
               class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark">
    </div>

</x-modal-form>

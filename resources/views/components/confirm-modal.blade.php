<div x-data="confirmModalData()" @trigger-confirm.window="openModal($event.detail)" style="display: none;" x-show="isOpen" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
 <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
 <!-- Backdrop -->
 <div x-show="isOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" @click="cancel()"></div>

 <!-- Center modal -->
 <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

 <!-- Modal Panel -->
 <div x-show="isOpen" 
 x-transition.scale.origin.center 
 class="relative z-10 inline-block align-bottom bg-bgSurface-light dark:bg-bgSurface-dark rounded-2xl text-left overflow-hidden transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full border border-gray-100 dark:border-gray-800">
 
  <div class="px-6 py-6 flex flex-col items-center text-center">
      <!-- Icon Container (Manajemen User style for danger/delete, or default for info/warning) -->
      <template x-if="isDanger">
          <div class="w-16 h-16 rounded-full bg-danger/10 text-danger flex items-center justify-center text-2xl mx-auto mb-4 relative">
              <div class="absolute inset-0 rounded-full border-2 border-danger/20 animate-pulse"></div>
              <i class="fa-solid" :class="icon"></i>
          </div>
      </template>
      <template x-if="!isDanger">
          <div class="w-14 h-14 rounded-full bg-gray-50 dark:bg-gray-800/50 flex items-center justify-center mb-4 border border-gray-100 dark:border-gray-700">
              <i class="fa-solid text-base" :class="icon"></i>
          </div>
      </template>
  
      <!-- Kata-kata / Title & Message -->
      <h3 id="modal-title" x-text="title" :class="isDanger ? 'text-base font-semibold text-textMain-light dark:text-textMain-dark mb-2' : 'text-base font-medium text-textMain-light dark:text-textMain-dark mb-2'"></h3>
      <p x-text="message" class="text-xs text-textMuted-light dark:text-textMuted-dark mb-6 leading-relaxed"></p>
  
      <!-- Tombol (Pertahankan tampilan UI saat ini: flex-1 px-4 py-2.5 rounded-md text-xs font-medium) -->
      <div class="flex flex-col sm:flex-row gap-3 w-full">
          <button @click="cancel()" class="flex-1 px-4 py-2.5 bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 text-textMain-light dark:text-textMain-dark rounded-md text-xs font-medium transition-colors cursor-pointer" x-text="cancelText"></button>
          <button @click="confirm()" class="flex-1 px-4 py-2.5 text-white rounded-md text-xs font-medium transition-colors cursor-pointer flex items-center justify-center gap-2" :class="confirmColor">
              <i x-show="isDanger" class="fa-solid fa-trash-can"></i>
              <span x-text="confirmText"></span>
          </button>
      </div>
  </div>
  </div>
  </div>
</div>

<script>
  document.addEventListener('alpine:init', () => {
  Alpine.data('confirmModalData', () => ({
  isOpen: false,
  isDanger: false,
  title: 'Konfirmasi',
  message: 'Apakah Anda yakin ingin melanjutkan tindakan ini?',
  confirmText: 'Ya, Lanjutkan',
  cancelText: 'Batal',
  confirmColor: 'bg-teal-light hover:bg-teal-600',
  icon: 'fa-triangle-exclamation text-warning',
  onConfirmCallback: null,

  openModal(detail) {
  this.title = detail.title || 'Konfirmasi';
  this.message = detail.message || 'Apakah Anda yakin?';
  this.confirmText = detail.confirmText || 'Ya';
  this.cancelText = detail.cancelText || 'Batal';
  
  if (detail.type === 'danger' || (this.title && this.title.toLowerCase().includes('hapus'))) {
  this.isDanger = true;
  this.confirmColor = 'bg-danger hover:bg-red-600';
  this.icon = 'fa-triangle-exclamation text-danger';
  } else if (detail.type === 'success') {
  this.isDanger = false;
  this.confirmColor = 'bg-success hover:bg-green-600';
  this.icon = 'fa-circle-check text-success';
  } else if (detail.type === 'warning') {
  this.isDanger = false;
  this.confirmColor = 'bg-warning hover:bg-amber-600';
  this.icon = 'fa-triangle-exclamation text-warning';
  } else {
  this.isDanger = false;
  this.confirmColor = 'bg-teal-light hover:bg-teal-600';
  this.icon = 'fa-circle-info text-teal-light';
  }

  this.onConfirmCallback = detail.onConfirm;
  this.isOpen = true;
  },

 confirm() {
 if (typeof this.onConfirmCallback === 'function') {
 this.onConfirmCallback();
 }
 this.isOpen = false;
 },

 cancel() {
 this.isOpen = false;
 }
 }));
 });
</script>







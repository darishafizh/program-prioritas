<div x-data="confirmModalData()" @trigger-confirm.window="openModal($event.detail)" style="display: none;" x-show="isOpen" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop -->
        <div x-show="isOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" @click="cancel()"></div>

        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal Panel -->
        <div x-show="isOpen" 
             x-transition.scale.origin.center 
             class="relative z-10 inline-block align-bottom bg-bgSurface-light dark:bg-bgSurface-dark rounded-2xl text-left overflow-hidden shadow-lg transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full border border-gray-100 dark:border-gray-800">
            
            <div class="px-6 py-6 flex flex-col items-center text-center">
                <div class="w-14 h-14 rounded-full bg-gray-50 dark:bg-gray-800/50 flex items-center justify-center mb-4 border border-gray-100 dark:border-gray-700">
                    <i class="fa-solid text-base" :class="icon"></i>
                </div>
                
                <h3 class="text-base font-medium text-textMain-light dark:text-textMain-dark mb-2" id="modal-title" x-text="title"></h3>
                <p class="text-sm text-textMuted-light dark:text-textMuted-dark mb-6" x-text="message"></p>
                
                <div class="flex flex-col sm:flex-row gap-3 w-full">
                    <button @click="cancel()" class="flex-1 px-4 py-2.5 bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 text-textMain-light dark:text-textMain-dark rounded-xl text-sm font-medium transition-colors" x-text="cancelText"></button>
                    <button @click="confirm()" class="flex-1 px-4 py-2.5 text-white rounded-xl text-sm font-medium shadow-sm transition-colors" :class="confirmColor" x-text="confirmText"></button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('confirmModalData', () => ({
            isOpen: false,
            title: 'Konfirmasi',
            message: 'Apakah Anda yakin ingin melanjutkan tindakan ini?',
            confirmText: 'Ya, Lanjutkan',
            cancelText: 'Batal',
            confirmColor: 'bg-info hover:bg-blue-600',
            icon: 'fa-triangle-exclamation text-warning',
            onConfirmCallback: null,

            openModal(detail) {
                this.title = detail.title || 'Konfirmasi';
                this.message = detail.message || 'Apakah Anda yakin?';
                this.confirmText = detail.confirmText || 'Ya';
                this.cancelText = detail.cancelText || 'Batal';
                
                if (detail.type === 'danger') {
                    this.confirmColor = 'bg-danger hover:bg-red-600';
                    this.icon = 'fa-circle-xmark text-danger';
                } else if (detail.type === 'success') {
                    this.confirmColor = 'bg-success hover:bg-green-600';
                    this.icon = 'fa-circle-check text-success';
                } else if (detail.type === 'warning') {
                    this.confirmColor = 'bg-warning hover:bg-amber-600';
                    this.icon = 'fa-triangle-exclamation text-warning';
                } else {
                    this.confirmColor = 'bg-info hover:bg-blue-600';
                    this.icon = 'fa-circle-info text-info';
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





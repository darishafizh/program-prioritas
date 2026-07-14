@props([
    'show' => 'false',
    'title' => 'Form Data',
    'action' => "'#'",
    'method' => 'POST',
    'submitText' => 'Simpan',
    'submitIcon' => 'fa-save',
    'maxWidth' => 'max-w-[400px]',
    'onClose' => null
])

<div x-show="{{ $show }}" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    <div @click.away="{{ $onClose ?: ($show . ' = false') }}" 
         x-show="{{ $show }}"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-y-8 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         class="bg-white dark:bg-gray-900 rounded-2xl w-full {{ $maxWidth }} p-6 shadow-xl border border-gray-100 dark:border-gray-800 relative mx-4 max-h-[90vh] overflow-y-auto"
         @if(preg_match('/max-w-\[([0-9]+px)\]/', $maxWidth, $m)) style="max-width: {{ $m[1] }};" @elseif($maxWidth === 'max-w-sm') style="max-width: 384px;" @elseif($maxWidth === 'max-w-xs') style="max-width: 320px;" @endif>
        
        <div class="flex justify-between items-center mb-5 border-b border-gray-100 dark:border-gray-800 pb-3.5">
            <h3 class="text-base font-semibold text-textMain-light dark:text-textMain-dark">{{ $title }}</h3>
            <button type="button" @click="{{ $onClose ?: ($show . ' = false') }}" class="text-gray-400 hover:text-danger transition-colors cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        
        <form :action="{{ $action }}" method="POST">
            @csrf
            @if(strtoupper($method) !== 'POST')
                @method($method)
            @endif
            @if(isset($methodSlot))
                {{ $methodSlot }}
            @endif
            
            <div class="space-y-4 text-left">
                {{ $slot }}
            </div>
            
            <div class="mt-8 flex justify-end gap-3 border-t border-gray-100 dark:border-gray-800 pt-4">
                <button type="button" @click="{{ $onClose ?: ($show . ' = false') }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-textMain-light dark:text-textMain-dark rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors cursor-pointer">Batal</button>
                <button type="submit" class="px-4 py-2 bg-teal-light text-white rounded-lg text-sm font-medium hover:bg-teal-light/90 transition-colors flex items-center gap-2 cursor-pointer shadow-sm">
                    <i class="fa-solid {{ $submitIcon }}"></i> <span>{{ $submitText }}</span>
                </button>
            </div>
        </form>
    </div>
</div>

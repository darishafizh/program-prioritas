@props(['activeProgram' => 'Bioflok', 'activeModule' => 'Dashboard'])

@php
    $progKey = strtolower(str_replace(' ', '-', $activeProgram));
    $allSidebarConfig = config('sidebar');
    
    // Fallback to default if program not defined in config
    $programSidebar = $allSidebarConfig[$progKey] ?? $allSidebarConfig['default'] ?? [];
    
    // Fallback to empty array if module not defined
    $moduleMenu = $programSidebar[$activeModule] ?? [];
@endphp

<aside :class="sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full lg:translate-x-0 w-64 lg:w-0'" 
       class="fixed lg:relative z-40 h-full bg-bgSurface-light dark:bg-bgSurface-dark border-r border-gray-200 dark:border-gray-800 transition-all duration-300 shrink-0 overflow-hidden">
    
    <div class="w-64 h-full flex flex-col">
        <!-- Context Header -->
        <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-800 shrink-0 bg-gray-50/50 dark:bg-gray-800/20">
            <div class="text-[0.65rem] font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest mb-1">
                Modul {{ $activeModule }}
            </div>
            <div class="font-semibold text-teal-light dark:text-teal-dark flex items-center gap-2">
                <i class="fa-solid fa-layer-group"></i>
                {{ $activeProgram }}
            </div>
        </div>

        <!-- Navigation Items -->
        <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            @if(!empty($moduleMenu))
                @if(isset($moduleMenu['heading']))
                    <div class="text-[0.65rem] font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest px-3 mb-2">{{ $moduleMenu['heading'] }}</div>
                @endif
                
                @foreach($moduleMenu['items'] as $item)
                    @php
                        // Cek apakah URL sama dengan halaman saat ini (menghiraukan query parameter unless it's explicitly matched later)
                        $pathOnly = strtok($item['url'], '?');
                        $isActive = request()->is(ltrim($pathOnly, '/')) || ($item['url'] !== '#' && request()->url() === url($pathOnly));
                    @endphp
                    <a href="{{ $item['url'] }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ $isActive ? 'bg-gradient-to-r from-navy-light to-teal-light dark:from-navy-dark dark:to-teal-dark text-white shadow-md' : 'text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800/50 hover:text-navy-light dark:hover:text-blue-400' }}">
                        <i class="fa-solid {{ $item['icon'] }} w-5 text-center {{ $isActive ? 'text-white' : '' }}"></i>
                        <span class="font-medium text-sm">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            @else
                <div class="px-3 py-4 text-sm text-center text-textMuted-light dark:text-textMuted-dark italic">
                    Belum ada menu untuk modul ini.
                </div>
            @endif
        </div>
    </div>
</aside>

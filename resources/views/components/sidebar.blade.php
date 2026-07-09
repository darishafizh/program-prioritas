@props(['activeProgram' => 'Bioflok', 'activeModule' => 'Dashboard'])

@php
 $progKey = strtolower(str_replace(' ', '-', $activeProgram));
 $allSidebarConfig = config('sidebar');
 
 // Fallback to default if program not defined in config
 $programSidebar = $allSidebarConfig[$progKey] ?? $allSidebarConfig['default'] ?? [];
 
 // Fallback to empty array if module not defined
 $moduleMenu = $programSidebar[$activeModule] ?? [];
@endphp

<aside :class="sidebarOpen ? 'translate-x-0 w-[var(--sidebar-width)]' : '-translate-x-full lg:translate-x-0 w-[var(--sidebar-width)] lg:w-0'" 
 class="fixed lg:relative z-40 h-full bg-bgSurface-light dark:bg-bgSurface-dark border-r border-gray-100 dark:border-gray-800 transition-all duration-300 shrink-0 overflow-hidden">
 
 <div class="w-[var(--sidebar-width)] h-full flex flex-col">
 <!-- Context Header -->
 <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800 shrink-0">
 <div class="text-xs text-textMuted-light dark:text-textMuted-dark mb-1 font-medium">
 Modul {{ $activeModule }}
 </div>
 <div class="font-medium text-textMain-light dark:text-teal-dark flex items-center gap-2">
 <i class="fa-solid fa-layer-group text-xs"></i>
 {{ $activeProgram }}
 </div>
 </div>

 <!-- Navigation Items -->
 <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
 @if(!empty($moduleMenu))
 @if(isset($moduleMenu['heading']))
 <div class="text-xs text-textMuted-light dark:text-textMuted-dark px-3 mb-2 font-medium">{{ $moduleMenu['heading'] }}</div>
 @endif
 
 @foreach($moduleMenu['items'] as $item)
 @php
 // Role filtering
 if (!\Illuminate\Support\Facades\Auth::user()->isSuperAdmin() && in_array($item['label'], ['Tahap (Batch)', 'Data Vendor/Penyedia'])) {
     continue;
 }

 // Cek apakah URL sama dengan halaman saat ini
 $pathOnly = strtok($item['url'], '?');
 $isActive = request()->is(ltrim($pathOnly, '/')) || ($item['url'] !== '#' && request()->url() === url($pathOnly));
 
 // Cek pattern tambahan jika ada
 if (isset($item['active']) && is_array($item['active'])) {
     foreach ($item['active'] as $pattern) {
         if (request()->is($pattern)) {
             $isActive = true;
             break;
         }
     }
 }
 @endphp
 <a href="{{ $item['url'] !== '#' ? url($item['url']) : '#' }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ $isActive ? 'bg-gray-100 dark:bg-gray-800/80 text-textMain-light dark:text-white' : 'text-textMuted-light dark:text-textMuted-dark hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-textMain-light dark:hover:text-white' }}">
 <i class="fa-solid {{ $item['icon'] }} w-5 text-center text-xs {{ $isActive ? 'text-teal-light dark:text-teal-400' : '' }}"></i>
 <span class="text-xs {{ $isActive ? 'font-semibold' : 'font-normal' }}">{{ $item['label'] }}</span>
 </a>
 @endforeach
 @else
 <div class="px-3 py-4 text-xs text-center text-textMuted-light dark:text-textMuted-dark">
 Belum ada menu untuk modul ini.
 </div>
 @endif
 </div>
 </div>
</aside>








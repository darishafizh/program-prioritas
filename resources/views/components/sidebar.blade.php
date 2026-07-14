@props(['activeProgram' => 'Bioflok', 'activeModule' => 'Dashboard'])

@php
 $progKey = strtolower(str_replace(' ', '-', $activeProgram));
 $allSidebarConfig = config('sidebar');
 
 // If program is Manajemen Sistem / Pengguna
 if ($activeModule === 'Pengguna' && !isset($allSidebarConfig[$progKey])) {
     $programSidebar = [
         'Pengguna & Hak Akses' => [
             'heading' => 'Manajemen Sistem',
             'items' => [
                 ['label' => 'Daftar Pengguna', 'url' => '/users', 'active' => ['users*']]
             ]
         ]
     ];
 } else {
     // Fallback to default if program not defined in config
     $programSidebar = $allSidebarConfig[$progKey] ?? $allSidebarConfig['default'] ?? [];
 }

 $moduleIcons = [
     'Dashboard' => 'fa-chart-pie',
     'Master' => 'fa-database',
     'Master Data' => 'fa-database',
     'Operasional' => 'fa-truck-fast',
     'Evaluasi' => 'fa-clipboard-check',
     'Pengguna & Hak Akses' => 'fa-users-gear',
 ];
@endphp

<aside :class="sidebarOpen ? 'translate-x-0 w-[var(--sidebar-width)]' : '-translate-x-full lg:translate-x-0 w-[var(--sidebar-width)] lg:w-[var(--sidebar-width)]'" 
 style="background-color: #E8ECF1;" :style="darkMode ? 'background-color: #0A0E17;' : 'background-color: #E8ECF1;'" class="fixed lg:relative z-40 h-full font-sans font-medium transition-all duration-300 shrink-0 overflow-hidden flex flex-col select-none border-0">
 
 <!-- Top Header: Brand & Ganti Program (font-inter font-medium) -->
 <div class="p-5 shrink-0 flex flex-col gap-3 border-0">
     <div class="flex items-center justify-between">
         <div class="flex items-center gap-3">
             <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-teal-light to-teal-700 text-white flex items-center justify-center shadow-md shadow-teal-light/20 shrink-0 border border-teal-500/30">
                <img src="{{ asset('assets/images/logo-kkp.png') }}" alt="Logo KKP" class="w-6 h-6 object-contain bg-white/95 rounded-md p-0.5 transition-all">
            </div>
             <div class="min-w-0">
                 <h1 class="font-sans font-medium text-sm text-textMain-light dark:text-textMain-dark leading-tight truncate">
                     Program Prioritas
                 </h1>
                 <p class="font-sans font-medium text-[11px] text-teal-light dark:text-teal-400 uppercase tracking-wider mt-0.5 truncate">
                     {{ $activeProgram }}
                 </p>
             </div>
         </div>
         <button @click="sidebarOpen = false" class="lg:hidden text-textMuted-light hover:text-textMain-light dark:hover:text-textMain-dark p-1">
             <i class="fa-solid fa-xmark text-lg"></i>
         </button>
     </div>

     @if($activeModule !== 'Pengguna')
     <div class="pt-1">
         @if(\Illuminate\Support\Facades\Auth::user()->isUserDaerah())
             <a href="{{ url('greetings') }}" class="w-full flex items-center justify-center gap-2 py-2 px-3 rounded-xl text-xs font-sans font-medium text-textMuted-light dark:text-textMuted-dark hover:text-textMain-light dark:hover:text-textMain-dark transition-all">
                 <i class="fa-solid fa-arrow-left text-xs text-teal-light"></i> Kembali ke Portal
             </a>
         @else
             <a href="{{ url('greetings') }}" class="w-full flex items-center justify-between py-2 px-3 rounded-xl text-xs font-sans font-medium text-textMuted-light dark:text-textMuted-dark hover:text-textMain-light dark:hover:text-textMain-dark transition-all group">
                 <span class="flex items-center gap-2.5 font-sans font-medium">
                     <i class="fa-regular fa-star text-amber-500 text-xs group-hover:scale-110 transition-transform"></i> Portal Utama (Ganti Program)
                 </span>
                 <i class="fa-solid fa-chevron-right text-[10px] text-textMuted-light group-hover:translate-x-0.5 transition-transform"></i>
             </a>
         @endif
     </div>
     @else
     <div class="pt-1">
         <a href="{{ url('greetings') }}" class="w-full flex items-center justify-center gap-2 py-2 px-3.5 rounded-xl bg-slate-200/70 dark:bg-slate-800/70 text-xs font-sans font-medium text-textMain-light dark:text-textMain-dark hover:bg-slate-300/70 dark:hover:bg-slate-700/70 transition-all">
             <i class="fa-solid fa-arrow-left text-xs text-teal-light"></i> Kembali ke Portal
         </a>
     </div>
     @endif
 </div>

 <!-- Middle Section: Accordion Branching Tree (only one module open at a time, smooth animation) -->
 <div x-data="{ activeAccordion: '{{ collect($programSidebar)->keys()->first(function($k) use ($programSidebar, $activeModule) { $m = $k; $d = $programSidebar[$k]; $active = ($m === $activeModule); if (isset($d['items'])) { foreach ($d['items'] as $ci) { $p = strtok($ci['url'], '?'); if (request()->is(ltrim($p, '/')) || ($ci['url'] !== '#' && request()->url() === url($p))) { $active = true; break; } if (isset($ci['active']) && is_array($ci['active'])) { foreach ($ci['active'] as $pat) { if (request()->is($pat)) { $active = true; break 2; } } } } } return $active; }) ?? collect($programSidebar)->keys()->first() }}' }" class="flex-1 overflow-y-auto py-2 px-4 space-y-1 border-0">
     @foreach($programSidebar as $moduleName => $moduleData)
         @php
             $isModuleActive = ($moduleName === $activeModule);
             if (isset($moduleData['items'])) {
                 foreach ($moduleData['items'] as $chkItem) {
                     $pathOnly = strtok($chkItem['url'], '?');
                     if (request()->is(ltrim($pathOnly, '/')) || ($chkItem['url'] !== '#' && request()->url() === url($pathOnly))) {
                         $isModuleActive = true;
                         break;
                     }
                     if (isset($chkItem['active']) && is_array($chkItem['active'])) {
                         foreach ($chkItem['active'] as $pat) {
                             if (request()->is($pat)) {
                                 $isModuleActive = true;
                                 break 2;
                             }
                         }
                     }
                 }
             }
             $iconClass = $moduleIcons[$moduleName] ?? 'fa-folder-tree';
         @endphp

         <div class="space-y-1">
             <!-- Level 1: Module Header (WITH ICON, font-sans font-medium, open state uses text color + primary icon) -->
             <button @click="activeAccordion = (activeAccordion === '{{ $moduleName }}') ? '' : '{{ $moduleName }}'" 
                 class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-xs font-sans font-medium transition-all border-0 select-none"
                 :class="activeAccordion === '{{ $moduleName }}' ? 'text-textMain-light dark:text-textMain-dark font-semibold' : 'text-textMuted-light dark:text-textMuted-dark hover:text-textMain-light dark:hover:text-textMain-dark'">
                 <div class="flex items-center gap-3 truncate">
                     <div class="w-6 h-6 flex items-center justify-center shrink-0 transition-colors"
                         :class="activeAccordion === '{{ $moduleName }}' ? 'text-teal-light dark:text-teal-400' : 'text-textMuted-light dark:text-textMuted-dark'">
                         <i class="fa-solid {{ $iconClass }} text-sm"></i>
                     </div>
                     <span class="truncate tracking-tight font-sans font-medium">{{ $moduleName }}</span>
                 </div>
                 <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-300 ease-in-out shrink-0"
                     :class="activeAccordion === '{{ $moduleName }}' ? 'rotate-180 text-textMain-light dark:text-textMain-dark' : 'text-textMuted-light dark:text-textMuted-dark'"></i>
             </button>

             <!-- Level 2: Sub-Menu Items with smooth accordion collapse animation -->
             <div x-show="activeAccordion === '{{ $moduleName }}'" x-collapse.duration.400ms style="position: relative; padding-top: 4px; padding-bottom: 4px;">

                 @if(isset($moduleData['items']) && count($moduleData['items']) > 0)
                     @foreach($moduleData['items'] as $item)
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
                         <div style="position: relative; display: flex; align-items: center; padding: 2px 0;" class="group">
                             <!-- Vertical Line: full height for non-last, half for last -->
                             @if(!$loop->last)
                                 <div :style="darkMode ? 'position: absolute; left: 24px; top: 0; bottom: 0; width: 2px; background-color: #475569; border-radius: 1px;' : 'position: absolute; left: 24px; top: 0; bottom: 0; width: 2px; background-color: #cbd5e1; border-radius: 1px;'"></div>
                             @else
                                 <div :style="darkMode ? 'position: absolute; left: 24px; top: 0; height: calc(50% - 5px); width: 2px; background-color: #475569; border-radius: 1px;' : 'position: absolute; left: 24px; top: 0; height: calc(50% - 5px); width: 2px; background-color: #cbd5e1; border-radius: 1px;'"></div>
                             @endif

                             <!-- Curved L-connector: rounded corner from vertical to horizontal -->
                             <div :style="darkMode ? 'position: absolute; left: 24px; top: calc(50% - 6px); width: 12px; height: 12px; border-left: 2px solid #475569; border-bottom: 2px solid #475569; border-radius: 0 0 0 8px; border-top: none; border-right: none;' : 'position: absolute; left: 24px; top: calc(50% - 6px); width: 12px; height: 12px; border-left: 2px solid #cbd5e1; border-bottom: 2px solid #cbd5e1; border-radius: 0 0 0 8px; border-top: none; border-right: none;'"></div>

                             <!-- Short horizontal extension after the curve -->
                             <div :style="darkMode ? 'position: absolute; left: 36px; top: calc(50% + 4px); width: 6px; height: 2px; background-color: #475569; border-radius: 1px;' : 'position: absolute; left: 36px; top: calc(50% + 4px); width: 6px; height: 2px; background-color: #cbd5e1; border-radius: 1px;'"></div>

                             <!-- Sub-menu link: active = text color only, no bg/shadow/border -->
                             <a href="{{ $item['url'] !== '#' ? url($item['url']) : '#' }}" 
                                 style="margin-left: 44px;" 
                                 class="relative w-full mr-1.5 px-3 py-2 rounded-xl text-xs font-sans font-medium transition-all duration-200 truncate {{ $isActive ? 'text-textMain-light dark:text-textMain-dark' : 'text-textMuted-light dark:text-textMuted-dark hover:text-textMain-light dark:hover:text-textMain-dark' }}">
                                 {{ $item['label'] }}
                             </a>
                         </div>
                     @endforeach
                 @else
                     <div style="margin-left: 44px;" class="px-3 py-2 text-[11px] font-sans font-medium text-textMuted-light dark:text-textMuted-dark italic">
                         Belum ada sub-menu
                     </div>
                 @endif
             </div>
         </div>
     @endforeach
 </div>

 <!-- Bottom Footer: User Profile, Theme Toggle & Logout (font-sans font-medium) -->
 <div class="p-4 bg-transparent shrink-0 mt-auto border-0">
     <div class="flex items-center justify-between gap-2 p-2 rounded-2xl bg-slate-200/50 dark:bg-slate-800/40">
         <div class="flex items-center gap-2.5 min-w-0 flex-1 px-1">
             <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-teal-light to-teal-700 text-white flex items-center justify-center font-sans font-semibold text-xs shrink-0 shadow-md shadow-teal-light/20 border border-teal-500/30">
                 {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
             </div>
             <div class="min-w-0 flex-1 truncate">
                 <div class="font-sans font-medium text-xs text-textMain-light dark:text-textMain-dark truncate">{{ Auth::user()->name ?? 'Pengguna' }}</div>
                 <div class="font-sans font-medium text-[10px] text-textMuted-light dark:text-textMuted-dark truncate">Administrator</div>
             </div>
         </div>

         <!-- Dark Mode Toggle -->
         <button @click="darkMode = !darkMode; if(darkMode) { document.documentElement.classList.add('dark'); localStorage.setItem('theme', 'dark'); } else { document.documentElement.classList.remove('dark'); localStorage.setItem('theme', 'light'); }" 
             title="Ganti Tema"
             class="w-8 h-8 rounded-xl flex items-center justify-center bg-white dark:bg-slate-800 text-textMuted-light dark:text-textMuted-dark hover:text-textMain-light dark:hover:text-textMain-dark transition-all shrink-0 shadow-xs">
             <i class="fa-solid" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
         </button>

         <!-- Logout -->
         <a href="{{ route('logout') }}" title="Keluar"
             class="w-8 h-8 rounded-xl flex items-center justify-center bg-white dark:bg-slate-800 text-danger hover:bg-red-50 dark:hover:bg-red-900/30 transition-all shrink-0 shadow-xs">
             <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
         </a>
     </div>
 </div>
</aside>

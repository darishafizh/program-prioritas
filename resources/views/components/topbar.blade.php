@props(['activeProgram' => 'Bioflok', 'activeModule' => 'Dashboard'])

@php
$programs = [
 'KNMP',
 'Budidaya Tematik',
 'BINS',
 'Swasembada Garam',
 'Revitalisasi Pantura',
 'Modernisasi Kapal',
 'ISF Waingapu'
];
@endphp

<header class="h-16 fixed top-0 w-full bg-bgSurface-light/95 dark:bg-bgSurface-dark/95 backdrop-blur-md border-b border-gray-100 dark:border-gray-800 flex items-center justify-between px-4 lg:px-6 z-50">
 
 <!-- Left Side: Brand -->
 <div class="flex items-center gap-4">
 <!-- Mobile & Desktop menu button -->
 <button @click="sidebarOpen = !sidebarOpen" class="text-textMuted-light dark:text-textMuted-dark hover:text-textMain-light dark:hover:text-textMain-light transition-colors p-2 rounded-md hover:bg-gray-50 dark:hover:bg-gray-800">
 <i class="fa-solid fa-bars text-base"></i>
 </button>

 <!-- Brand -->
 <div class="flex items-center gap-3">
 <img src="{{ asset('assets/images/logo-kkp.png') }}" alt="Logo KKP" class="w-8 h-8 object-contain dark:bg-white dark:rounded-full dark:p-0.5 transition-all">
 <h1 class="font-medium text-base hidden sm:block text-textMain-light dark:text-white">
 Program Prioritas
 </h1>
 </div>
 </div>

 <!-- Right Side: Navigation & Profile -->
 <div class="flex items-center gap-2 lg:gap-3 ml-auto">
 
 <!-- Desktop Module Navigation (Right Aligned) -->
 @if($activeModule !== 'Pengguna')
    @if(Auth::user()->isUserDaerah())
      {{-- User Daerah: hanya tampilkan tombol kembali --}}
      <div class="hidden lg:flex items-center">
        <a href="{{ url('greetings') }}" class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 text-textMain-light dark:text-textMain-dark text-xs font-medium transition-colors">
          <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
      </div>
    @else
      <nav class="hidden lg:flex items-center gap-1">
        @php
          $slug = strtolower(str_replace(' ', '-', $activeProgram));
        @endphp
        
        <a href="{{ url('dashboard/' . $slug) }}" class="flex items-center px-3 py-2 rounded-lg text-xs transition-colors {{ $activeModule === 'Dashboard' ? 'bg-gray-100 dark:bg-gray-800/80 text-textMain-light dark:text-white font-semibold' : 'text-textMuted-light dark:text-textMuted-dark hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-textMain-light dark:hover:text-white font-normal' }}">
          <i class="fa-solid fa-chart-pie mr-1 {{ $activeModule === 'Dashboard' ? 'text-teal-light dark:text-teal-400' : '' }}"></i> Dashboard
        </a>
        
        <a href="{{ url('master/' . $slug) }}" class="flex items-center px-3 py-2 rounded-lg text-xs transition-colors {{ $activeModule === 'Master Data' ? 'bg-gray-100 dark:bg-gray-800/80 text-textMain-light dark:text-white font-semibold' : 'text-textMuted-light dark:text-textMuted-dark hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-textMain-light dark:hover:text-white font-normal' }}">
          <i class="fa-solid fa-database mr-1 {{ $activeModule === 'Master Data' ? 'text-teal-light dark:text-teal-400' : '' }}"></i> Master Data
        </a>
        
        <a href="{{ url('operasional/' . $slug) }}" class="flex items-center px-3 py-2 rounded-lg text-xs transition-colors {{ $activeModule === 'Operasional' ? 'bg-gray-100 dark:bg-gray-800/80 text-textMain-light dark:text-white font-semibold' : 'text-textMuted-light dark:text-textMuted-dark hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-textMain-light dark:hover:text-white font-normal' }}">
          <i class="fa-solid fa-truck-fast mr-1 {{ $activeModule === 'Operasional' ? 'text-teal-light dark:text-teal-400' : '' }}"></i> Operasional
        </a>
        
        <a href="{{ url('evaluasi/' . $slug) }}" class="flex items-center px-3 py-2 rounded-lg text-xs transition-colors {{ $activeModule === 'Evaluasi' ? 'bg-gray-100 dark:bg-gray-800/80 text-textMain-light dark:text-white font-semibold' : 'text-textMuted-light dark:text-textMuted-dark hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-textMain-light dark:hover:text-white font-normal' }}">
          <i class="fa-solid fa-clipboard-check mr-1 {{ $activeModule === 'Evaluasi' ? 'text-teal-light dark:text-teal-400' : '' }}"></i> Evaluasi
        </a>
      </nav>
    @endif
 @else
 <div class="hidden lg:flex items-center">
 <a href="{{ url('greetings') }}" class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 text-textMain-light dark:text-textMain-dark text-xs font-medium transition-colors">
 <i class="fa-solid fa-arrow-left"></i> Kembali
 </a>
 </div>
 @endif

 <!-- Theme Toggle & Profile Info -->
 <div class="flex items-center gap-3 pl-3 lg:pl-4 border-l border-gray-100 dark:border-gray-700">
 <!-- Ganti Program -->
 @if($activeModule !== 'Pengguna')
 <a href="{{ url('greetings') }}" class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 text-xs font-medium transition-colors">
 <i class="fa-solid fa-grid-2"></i> Ganti Program
 </a>
 @endif
 

 
 <!-- Theme Toggle -->
 <button @click="darkMode = !darkMode; if(darkMode) { document.documentElement.classList.add('dark'); localStorage.setItem('theme', 'dark'); } else { document.documentElement.classList.remove('dark'); localStorage.setItem('theme', 'light'); }" 
 class="w-9 h-9 rounded-md flex items-center justify-center bg-gray-50 dark:bg-gray-800 text-textMuted-light dark:text-textMuted-dark hover:text-textMain-light dark:hover:text-teal-dark transition-all">
 <i class="fa-solid" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
 </button>

 <!-- User Profile -->
 <div x-data="{ open: false }" class="relative" @click.away="open = false">
 <button @click="open = !open" class="flex items-center gap-3 focus:outline-none text-left rounded-md p-1 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
 <div class="text-right hidden sm:block">
 <div class="font-medium text-sm text-textMain-light dark:text-textMain-dark">{{ Auth::user()->name }}</div>
 <div class="text-xs text-textMuted-light dark:text-textMuted-dark">Program Prioritas</div>
 </div>
 <div class="flex items-center gap-2">
 <div class="w-8 h-8 rounded-full bg-teal-light text-white flex items-center justify-center font-medium text-sm">
 {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
 </div>
 <i class="fa-solid fa-chevron-down text-[0.6rem] text-textMuted-light dark:text-textMuted-dark"></i>
 </div>
 </button>
 
 <div x-show="open" x-transition class="absolute top-full right-0 mt-2 w-48 bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-xl py-2">
 <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-800 mb-1">
 <p class="text-xs text-textMuted-light dark:text-textMuted-dark">Login sebagai:</p>
 <p class="text-sm font-medium truncate">{{ Auth::user()->name }}</p>
 </div>
 <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm text-danger hover:bg-red-50 dark:hover:bg-red-900/10 font-medium transition-colors">
 <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> Keluar
 </a>
 </div>
 </div>
 </div>

 </div>
</header>









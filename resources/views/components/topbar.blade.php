@props(['activeProgram' => 'Bioflok', 'activeModule' => 'Dashboard'])

@php
$programs = [
    'KNMP',
    'Bioflok',
    'Minapadi',
    'BINS',
    'Swasembada Garam',
    'Revitalisasi Pantura',
    'Modernisasi Kapal',
    'ISF Waingapu',
    'Sarpras Pendidikan KP'
];
@endphp

<header class="h-16 fixed top-0 w-full bg-bgSurface-light/95 dark:bg-bgSurface-dark/95 backdrop-blur-md border-b border-gray-200 dark:border-gray-800 flex items-center justify-between px-4 lg:px-6 z-50">
    
    <!-- Left Side: Brand -->
    <div class="flex items-center gap-4">
        <!-- Mobile & Desktop menu button -->
        <button @click="sidebarOpen = !sidebarOpen" class="text-textMuted-light dark:text-textMuted-dark hover:text-navy-light dark:hover:text-teal-light transition-colors p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>

        <!-- Brand -->
        <div class="flex items-center gap-3">
            <img src="{{ asset('assets/images/logo-kkp.png') }}" alt="Logo KKP" class="w-8 h-8 object-contain dark:bg-white dark:rounded-full dark:p-0.5 transition-all">
            <h1 class="font-bold text-lg hidden sm:block bg-clip-text text-transparent bg-gradient-to-r from-navy-light to-teal-light dark:from-teal-dark dark:to-teal-light">
                Program Prioritas
            </h1>
        </div>
    </div>

    <!-- Right Side: Navigation & Profile -->
    <div class="flex items-center gap-2 lg:gap-4 ml-auto">
        
        <!-- Desktop Module Navigation (Right Aligned) -->
        @if($activeModule !== 'Pengguna')
        <nav class="hidden lg:flex items-center gap-1">
            @php
                $slug = strtolower(str_replace(' ', '-', $activeProgram));
            @endphp
            
            <a href="/dashboard/{{ $slug }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ $activeModule === 'Dashboard' ? 'bg-white dark:bg-gray-700 shadow-sm text-teal-light dark:text-teal-400' : 'text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-teal-light dark:hover:text-teal-400' }}">
                <i class="fa-solid fa-chart-pie mr-1"></i> Dashboard
            </a>
            
            <a href="/master/{{ $slug }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ $activeModule === 'Master Data' ? 'bg-white dark:bg-gray-700 shadow-sm text-navy-light dark:text-blue-400' : 'text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-navy-light dark:hover:text-blue-400' }}">
                <i class="fa-solid fa-database mr-1"></i> Master Data
            </a>
            
            <a href="/operasional/{{ $slug }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ $activeModule === 'Operasional' ? 'bg-white dark:bg-gray-700 shadow-sm text-info dark:text-blue-400' : 'text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-info dark:hover:text-blue-400' }}">
                <i class="fa-solid fa-truck-fast mr-1"></i> Operasional
            </a>
            
            <a href="/evaluasi/{{ $slug }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ $activeModule === 'Evaluasi' ? 'bg-white dark:bg-gray-700 shadow-sm text-success dark:text-green-400' : 'text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-success dark:hover:text-green-400' }}">
                <i class="fa-solid fa-clipboard-check mr-1"></i> Evaluasi
            </a>
        </nav>
        @else
        <div class="hidden lg:flex items-center">
            <a href="/greetings" class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-textMain-light dark:text-textMain-dark text-sm font-bold transition-colors">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>
        @endif

        <!-- Theme Toggle & Profile Info -->
        <div class="flex items-center gap-3 pl-3 lg:pl-4 border-l border-gray-200 dark:border-gray-700">
            <!-- Ganti Program -->
            @if($activeModule !== 'Pengguna')
            <a href="/greetings" class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 text-xs font-bold transition-colors">
                <i class="fa-solid fa-grid-2"></i> Ganti Program
            </a>
            @endif
            

            
            <!-- Theme Toggle -->
            <button @click="darkMode = !darkMode; if(darkMode) { document.documentElement.classList.add('dark'); localStorage.setItem('theme', 'dark'); } else { document.documentElement.classList.remove('dark'); localStorage.setItem('theme', 'light'); }" 
                    class="w-9 h-9 rounded-full flex items-center justify-center bg-gray-100 dark:bg-gray-800 text-textMuted-light dark:text-textMuted-dark hover:text-teal-light dark:hover:text-teal-dark transition-all">
                <i class="fa-solid" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
            </button>

            <!-- User Profile -->
            <div x-data="{ open: false }" class="relative" @click.away="open = false">
                <button @click="open = !open" class="flex items-center gap-3 focus:outline-none text-left rounded-lg p-1 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <div class="text-right hidden sm:block">
                        <div class="font-semibold text-xs text-textMain-light dark:text-textMain-dark">{{ session('username') === 'admin' ? 'Administrator' : 'Pengguna' }}</div>
                        <div class="text-[0.65rem] text-textMuted-light dark:text-textMuted-dark">Program Prioritas</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-info to-teal-light text-white flex items-center justify-center font-bold shadow-sm text-sm">
                            {{ session('username') === 'admin' ? 'A' : 'P' }}
                        </div>
                        <i class="fa-solid fa-chevron-down text-[0.6rem] text-textMuted-light dark:text-textMuted-dark"></i>
                    </div>
                </button>
                
                <div x-show="open" x-transition class="absolute top-full right-0 mt-2 w-48 bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-200 dark:border-gray-800 rounded-xl shadow-lg py-2">
                    <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-800 mb-1">
                        <p class="text-xs text-textMuted-light dark:text-textMuted-dark">Login sebagai:</p>
                        <p class="text-sm font-bold truncate">{{ session('username') ?? 'User' }}</p>
                    </div>
                    <a href="/logout" class="block px-4 py-2 text-sm text-danger hover:bg-red-50 dark:hover:bg-red-900/10 font-bold transition-colors">
                        <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> Keluar
                    </a>
                </div>
            </div>
        </div>

    </div>
</header>

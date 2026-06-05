<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pilih Program - Program Prioritas</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo-kkp.png') }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Theme Initialization -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body x-data="{ darkMode: document.documentElement.classList.contains('dark') }" class="antialiased bg-bgBody-light dark:bg-bgBody-dark text-textMain-light dark:text-textMain-dark min-h-screen flex flex-col">

    <!-- Topbar (Minimal) -->
    <header class="h-16 w-full bg-bgSurface-light dark:bg-bgSurface-dark border-b border-gray-200 dark:border-gray-800 flex items-center justify-between px-6 z-50">
        <div class="flex items-center gap-3">
            <img src="{{ asset('assets/images/logo-kkp.png') }}" alt="Logo KKP" class="w-8 h-8 object-contain dark:bg-white dark:rounded-full dark:p-0.5 transition-all">
            <h1 class="font-bold text-lg hidden sm:block bg-clip-text text-transparent bg-gradient-to-r from-navy-light to-teal-light dark:from-teal-dark dark:to-teal-light">
                Program Prioritas
            </h1>
        </div>

        <div class="flex items-center gap-4">
            @if(session('username') === 'admin')
            <a href="/users" class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-lg border border-teal-light/20 bg-teal-light/5 hover:bg-teal-light/10 dark:border-teal-dark/30 dark:bg-teal-dark/10 dark:hover:bg-teal-dark/20 text-teal-light dark:text-teal-400 text-xs font-bold transition-colors">
                <i class="fa-solid fa-users-gear"></i> Pengguna
            </a>
            @endif
            
            <button @click="darkMode = !darkMode; if(darkMode) { document.documentElement.classList.add('dark'); localStorage.setItem('theme', 'dark'); } else { document.documentElement.classList.remove('dark'); localStorage.setItem('theme', 'light'); }" 
                    class="w-9 h-9 rounded-full flex items-center justify-center bg-gray-100 dark:bg-gray-800 text-textMuted-light dark:text-textMuted-dark hover:text-teal-light dark:hover:text-teal-dark transition-all">
                <i class="fa-solid" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
            </button>
            <div x-data="{ open: false }" class="relative" @click.away="open = false">
                <button @click="open = !open" class="flex items-center gap-3 focus:outline-none text-left rounded-lg p-1 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <div class="text-right hidden sm:block">
                        <div class="font-semibold text-xs text-textMain-light dark:text-textMain-dark">{{ session('username') === 'admin' ? 'Administrator' : 'Pengguna' }}</div>
                        <div class="text-[0.65rem] text-textMuted-light dark:text-textMuted-dark">Sistem Terpadu</div>
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
    </header>

    <!-- Animated Gradient Background & Decorative Shapes -->
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <!-- Gradients -->
        <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-teal-400/20 dark:bg-teal-900/30 blur-[120px] animate-[pulse_8s_ease-in-out_infinite]"></div>
        <div class="absolute top-[20%] -right-[10%] w-[40%] h-[60%] rounded-full bg-info/20 dark:bg-blue-900/20 blur-[120px] animate-[pulse_10s_ease-in-out_infinite_reverse]"></div>
        <div class="absolute -bottom-[20%] left-[20%] w-[60%] h-[50%] rounded-full bg-success/15 dark:bg-emerald-900/20 blur-[120px] animate-[pulse_12s_ease-in-out_infinite]"></div>

        <!-- Floating Shapes -->
        <div class="absolute top-[15%] left-[10%] w-4 h-4 rounded-full bg-teal-400/40 dark:bg-teal-400/10 animate-[bounce_4s_infinite]"></div>
        <div class="absolute bottom-[25%] right-[12%] w-6 h-6 rounded-full bg-info/30 dark:bg-info/10 animate-[bounce_5s_infinite]" style="animation-delay: 1s;"></div>
        <div class="absolute top-[25%] right-[20%] w-3 h-3 rounded-full bg-success/40 dark:bg-success/20 animate-pulse"></div>
        
        <div class="absolute bottom-[15%] left-[18%] w-10 h-10 rounded-full border-2 border-teal-300/30 dark:border-teal-700/30 animate-[spin_10s_linear_infinite]" style="border-top-color: transparent;"></div>
        <div class="absolute top-[35%] right-[5%] w-12 h-12 rounded-full border-2 border-dashed border-info/20 dark:border-info/10 animate-[spin_20s_linear_infinite_reverse]"></div>
        
        <div class="absolute top-[40%] left-[5%] text-info/30 dark:text-info/10 animate-[spin_15s_linear_infinite]">
            <i class="fa-solid fa-plus text-2xl"></i>
        </div>
        <div class="absolute bottom-[35%] right-[25%] text-teal-400/30 dark:text-teal-400/10 animate-[spin_12s_linear_infinite_reverse]">
            <i class="fa-solid fa-plus text-xl"></i>
        </div>
        
        <div class="absolute top-[12%] right-[30%] text-amber-400/40 dark:text-amber-500/10 animate-[pulse_3s_ease-in-out_infinite]">
            <i class="fa-solid fa-sparkle text-lg"></i>
        </div>
        <div class="absolute bottom-[10%] left-[30%] text-teal-400/40 dark:text-teal-500/10 animate-[pulse_4s_ease-in-out_infinite]" style="animation-delay: 2s;">
            <i class="fa-solid fa-sparkles text-2xl"></i>
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-1 w-full max-w-6xl mx-auto px-4 sm:px-6 py-6 sm:py-8 relative z-10 flex flex-col justify-center">
        
        <div class="text-center mb-6 sm:mb-8">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-teal-100/80 text-teal-700 dark:bg-teal-900/50 dark:text-teal-300 text-[0.65rem] font-bold mb-3 shadow-sm uppercase tracking-wider">
                <i class="fa-solid fa-anchor"></i> Portal Utama Program Prioritas
            </div>
            <h2 class="text-3xl sm:text-4xl font-extrabold mb-3 tracking-tight">Selamat Datang, <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-info">Administrator</span></h2>
            <p class="text-textMuted-light dark:text-textMuted-dark max-w-2xl mx-auto text-sm sm:text-base">Silakan pilih Program Prioritas yang ingin Anda kelola. Setiap program memiliki ruang lingkup pelaporan, pengawasan, dan analisis datanya sendiri.</p>
        </div>

        <div x-data="{ 
                activeSlide: 0, 
                slides: {{ count($programs) }}, 
                autoSlide() { 
                    setInterval(() => { this.activeSlide = this.activeSlide === this.slides - 1 ? 0 : this.activeSlide + 1 }, 6000); 
                } 
            }" 
            x-init="autoSlide()" 
            class="relative w-full max-w-5xl mx-auto">
            
            <div class="overflow-hidden rounded-[2rem]">
                <div class="flex transition-transform duration-700 ease-in-out" :style="'transform: translateX(-' + (activeSlide * 100) + '%)'">
                    @foreach($programs as $index => $prog)
                    <div class="w-full flex-shrink-0 p-2 sm:p-3">
                        <a href="/dashboard/{{ strtolower(str_replace(' ', '-', $prog['name'])) }}" 
                           class="group block bg-bgSurface-light dark:bg-bgSurface-dark rounded-3xl p-6 sm:p-8 border border-gray-200 dark:border-gray-800 shadow-lg hover:shadow-2xl hover:border-teal-light/50 dark:hover:border-teal-dark/50 transition-all duration-500 transform hover:-translate-y-1 relative overflow-hidden h-full">
                            
                            @php
                                $themeMap = [
                                    'bg-info' => ['bg' => 'bg-info/10 dark:bg-blue-400/20', 'text' => 'text-info dark:text-blue-400', 'glow' => 'bg-info'],
                                    'bg-teal-light' => ['bg' => 'bg-teal-light/10 dark:bg-teal-400/20', 'text' => 'text-teal-light dark:text-teal-400', 'glow' => 'bg-teal-400'],
                                    'bg-success' => ['bg' => 'bg-success/10 dark:bg-emerald-400/20', 'text' => 'text-success dark:text-emerald-400', 'glow' => 'bg-success'],
                                    'bg-warning' => ['bg' => 'bg-warning/10 dark:bg-amber-400/20', 'text' => 'text-warning dark:text-amber-400', 'glow' => 'bg-warning'],
                                    'bg-navy-light' => ['bg' => 'bg-blue-600/10 dark:bg-blue-500/20', 'text' => 'text-blue-600 dark:text-blue-400', 'glow' => 'bg-blue-600'],
                                    'bg-blue-500' => ['bg' => 'bg-blue-500/10 dark:bg-blue-400/20', 'text' => 'text-blue-500 dark:text-blue-400', 'glow' => 'bg-blue-500'],
                                    'bg-indigo-500' => ['bg' => 'bg-indigo-500/10 dark:bg-indigo-400/20', 'text' => 'text-indigo-500 dark:text-indigo-400', 'glow' => 'bg-indigo-500'],
                                    'bg-purple-500' => ['bg' => 'bg-purple-500/10 dark:bg-purple-400/20', 'text' => 'text-purple-500 dark:text-purple-400', 'glow' => 'bg-purple-500'],
                                    'bg-orange-500' => ['bg' => 'bg-orange-500/10 dark:bg-orange-400/20', 'text' => 'text-orange-500 dark:text-orange-400', 'glow' => 'bg-orange-500'],
                                ];
                                $colors = $themeMap[$prog['color']] ?? ['bg' => 'bg-gray-500/10 dark:bg-gray-400/20', 'text' => 'text-gray-500 dark:text-gray-400', 'glow' => 'bg-gray-500'];
                            @endphp
                            
                            <!-- Decorative Glow -->
                            <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full {{ $colors['glow'] }} blur-[80px] opacity-10 group-hover:opacity-20 transition-opacity duration-700 pointer-events-none"></div>
                            
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 sm:gap-8 relative z-10">
                                <div class="flex-1">
                                    <div class="w-16 h-16 rounded-2xl {{ $colors['bg'] }} flex items-center justify-center transition-colors mb-4 shadow-inner">
                                        <i class="fa-solid {{ $prog['icon'] }} text-3xl {{ $colors['text'] }} transition-colors"></i>
                                    </div>
                                    <h3 class="text-2xl sm:text-4xl font-extrabold text-textMain-light dark:text-textMain-dark transition-colors mb-3 tracking-tight relative z-10 group-hover:text-teal-600 dark:group-hover:text-teal-400">
                                        {{ in_array(strtolower($prog['name']), ['knmp', 'bins']) ? strtoupper($prog['name']) : $prog['name'] }}
                                    </h3>
                                    <p class="text-sm sm:text-base text-textMuted-light dark:text-textMuted-dark leading-relaxed mb-5 max-w-xl">
                                        {{ $prog['narrative'] }}
                                    </p>
                                    
                                    <div class="inline-flex items-center gap-2 {{ $colors['text'] }} font-bold text-xs bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 px-4 py-2 rounded-xl shadow-sm group-hover:shadow-md transition-all">
                                        Buka Modul Program <i class="fa-solid fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                                    </div>
                                </div>
                                
                                <div class="w-full md:w-1/3 min-w-[240px] flex flex-col gap-3">
                                    @foreach($prog['stats'] as $label => $value)
                                    <div class="bg-gray-50 dark:bg-gray-800/80 rounded-xl p-4 border border-gray-100 dark:border-gray-800 backdrop-blur-md shadow-sm group-hover:shadow transition-shadow">
                                        <div class="text-[0.65rem] font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest mb-1.5 flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $colors['glow'] }}"></span> {{ $label }}
                                        </div>
                                        <div class="text-2xl sm:text-3xl font-extrabold text-textMain-light dark:text-textMain-dark">{{ $value }}</div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Navigation Controls -->
            <div class="flex items-center justify-center gap-3 mt-6">
                <button @click="activeSlide = activeSlide === 0 ? slides - 1 : activeSlide - 1" class="w-8 h-8 rounded-full flex items-center justify-center bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-500 hover:text-teal-light transition-colors shadow-sm">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </button>
                
                <div class="flex gap-1.5">
                    <template x-for="i in slides" :key="i">
                        <button @click="activeSlide = i - 1" 
                                :class="activeSlide === i - 1 ? 'w-8 bg-teal-500 dark:bg-teal-400' : 'w-2 bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600'"
                                class="h-2 rounded-full transition-all duration-500 ease-out"></button>
                    </template>
                </div>

                <button @click="activeSlide = activeSlide === slides - 1 ? 0 : activeSlide + 1" class="w-8 h-8 rounded-full flex items-center justify-center bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-500 hover:text-teal-light transition-colors shadow-sm">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </button>
            </div>
        </div>

    </main>

    <footer class="absolute bottom-2 w-full text-center text-[0.65rem] text-textMuted-light dark:text-textMuted-dark pointer-events-none opacity-70">
        &copy; 2026 Program Prioritas - Kementerian Kelautan dan Perikanan
    </footer>

</body>
</html>

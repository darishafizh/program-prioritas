<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <title>Pilih Program - Program Prioritas</title>
 <link rel="icon" type="image/png" href="{{ asset('assets/images/logo-kkp.png') }}">

 <!-- Fonts -->
 <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
 <header class="h-16 w-full bg-bgSurface-light dark:bg-bgSurface-dark border-b border-gray-100 dark:border-gray-800 flex items-center justify-between px-6 z-50">
 <div class="flex items-center gap-3">
 <img src="{{ asset('assets/images/logo-kkp.png') }}" alt="Logo KKP" class="w-8 h-8 object-contain dark:bg-white dark:rounded-full dark:p-0.5 transition-all">
 <h1 class="font-medium text-base hidden sm:block text-textMain-light dark:text-white">
 Program Prioritas
 </h1>
 </div>

 <div class="flex items-center gap-3">
 @if(session('username') === 'admin')
 <a href="/users" class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 text-textMain-light dark:text-teal-400 text-xs font-medium transition-colors">
 <i class="fa-solid fa-users-gear"></i> Pengguna
 </a>
 @endif
 
 <button @click="darkMode = !darkMode; if(darkMode) { document.documentElement.classList.add('dark'); localStorage.setItem('theme', 'dark'); } else { document.documentElement.classList.remove('dark'); localStorage.setItem('theme', 'light'); }" 
 class="w-9 h-9 rounded-md flex items-center justify-center bg-gray-50 dark:bg-gray-800 text-textMuted-light dark:text-textMuted-dark hover:text-textMain-light dark:hover:text-teal-dark transition-all">
 <i class="fa-solid" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
 </button>
 <div x-data="{ open: false }" class="relative" @click.away="open = false">
 <button @click="open = !open" class="flex items-center gap-3 focus:outline-none text-left rounded-md p-1 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
 <div class="text-right hidden sm:block">
 <div class="font-medium text-sm text-textMain-light dark:text-textMain-dark">{{ session('username') === 'admin' ? 'Administrator' : 'Pengguna' }}</div>
 <div class="text-xs text-textMuted-light dark:text-textMuted-dark">Sistem Terpadu</div>
 </div>
 <div class="flex items-center gap-2">
 <div class="w-8 h-8 rounded-full bg-teal-light text-white flex items-center justify-center font-medium text-sm">
 {{ session('username') === 'admin' ? 'A' : 'P' }}
 </div>
 <i class="fa-solid fa-chevron-down text-[0.6rem] text-textMuted-light dark:text-textMuted-dark"></i>
 </div>
 </button>
 <div x-show="open" x-transition class="absolute top-full right-0 mt-2 w-48 bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-xl py-2">
 <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-800 mb-1">
 <p class="text-xs text-textMuted-light dark:text-textMuted-dark">Login sebagai:</p>
 <p class="text-sm font-medium truncate">{{ session('username') ?? 'User' }}</p>
 </div>
 <a href="/logout" class="block px-4 py-2 text-sm text-danger hover:bg-red-50 dark:hover:bg-red-900/10 font-medium transition-colors">
 <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> Keluar
 </a>
 </div>
 </div>
 </div>
 </header>

 <!-- Subtle Background Decoration -->
 <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
 <div class="absolute -top-[20%] -left-[10%] w-[40%] h-[40%] rounded-full bg-teal-400/8 dark:bg-teal-900/15 blur-[100px]"></div>
 <div class="absolute top-[20%] -right-[10%] w-[35%] h-[50%] rounded-full bg-teal-light/8 dark:bg-teal-900/10 blur-[100px]"></div>
 <div class="absolute -bottom-[20%] left-[20%] w-[50%] h-[40%] rounded-full bg-success/5 dark:bg-emerald-900/10 blur-[100px]"></div>
 </div>

 <!-- Main Content -->
 <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-12 relative z-10 flex flex-col">
 
 <div class="text-center mb-10 sm:mb-12">
 <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-teal-light/10 text-textMain-light dark:bg-teal-900/30 dark:text-teal-300 text-xs font-semibold tracking-wide mb-4">
 <i class="fa-solid fa-anchor"></i> Portal Utama Program Prioritas
 </div>
 <h2 class="text-3xl sm:text-4xl font-semibold mb-3 tracking-tight">Selamat Datang, <span class="text-textMain-light">Administrator</span></h2>
 <p class="text-textMuted-light dark:text-textMuted-dark max-w-2xl mx-auto text-sm sm:text-base leading-relaxed">Silakan pilih Program Prioritas yang ingin Anda kelola. Setiap program memiliki ruang lingkup pelaporan, pengawasan, dan analisis datanya sendiri.</p>
 </div>

 <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 mt-6 gap-6 lg:gap-8 max-w-6xl mx-auto pb-12">
 @foreach($programs as $index => $prog)
 <a href="/dashboard/{{ strtolower(str_replace(' ', '-', $prog['name'])) }}" 
 class="group bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 sm:p-8 relative overflow-hidden flex flex-col h-full hover:border-teal-light/30 dark:hover:border-teal-dark/30 transition-all duration-300">
 
 @php
 $themeMap = [
 'bg-teal-light' => ['bg' => 'bg-teal-light/10 dark:bg-blue-400/15', 'text' => 'text-teal-light dark:text-teal-400', 'glow' => 'bg-teal-light'],
 'bg-teal-light' => ['bg' => 'bg-teal-light/10 dark:bg-teal-400/15', 'text' => 'text-textMain-light dark:text-teal-400', 'glow' => 'bg-teal-400'],
 'bg-success' => ['bg' => 'bg-success/10 dark:bg-emerald-400/15', 'text' => 'text-success dark:text-emerald-400', 'glow' => 'bg-success'],
 'bg-warning' => ['bg' => 'bg-warning/10 dark:bg-amber-400/15', 'text' => 'text-warning dark:text-amber-400', 'glow' => 'bg-warning'],
 'bg-navy-light' => ['bg' => 'bg-teal-600/10 dark:bg-teal-500/15', 'text' => 'text-teal-light dark:text-teal-400', 'glow' => 'bg-teal-600'],
 'bg-teal-500' => ['bg' => 'bg-teal-500/10 dark:bg-blue-400/15', 'text' => 'text-blue-500 dark:text-teal-400', 'glow' => 'bg-teal-500'],
 'bg-teal-500' => ['bg' => 'bg-teal-500/10 dark:bg-teal-400/15', 'text' => 'text-teal-500 dark:text-teal-400', 'glow' => 'bg-teal-500'],
 'bg-teal-500' => ['bg' => 'bg-teal-500/10 dark:bg-teal-400/15', 'text' => 'text-teal-500 dark:text-teal-400', 'glow' => 'bg-teal-500'],
 'bg-orange-500' => ['bg' => 'bg-orange-500/10 dark:bg-orange-400/15', 'text' => 'text-orange-500 dark:text-orange-400', 'glow' => 'bg-orange-500'],
 ];
 $colors = $themeMap[$prog['color']] ?? ['bg' => 'bg-gray-500/10 dark:bg-gray-400/15', 'text' => 'text-gray-500 dark:text-gray-400', 'glow' => 'bg-gray-500'];
 @endphp
 
 <!-- Decoration Blob like in KNMP -->
 <div class="absolute top-0 right-0 w-32 h-32 {{ $colors['bg'] }} rounded-bl-full -mr-8 -mt-8 transition-transform duration-500 group-hover:scale-110"></div>

 <div class="flex-1 flex flex-col relative z-10">
 <div class="flex items-center gap-4 mb-6">
 <div class="w-14 h-14 rounded-2xl {{ $colors['bg'] }} flex items-center justify-center">
 <i class="fa-solid {{ $prog['icon'] }} text-xl {{ $colors['text'] }}"></i>
 </div>
 <div>
 <h3 class="text-base font-semibold text-textMain-light dark:text-textMain-dark tracking-tight">
 {{ in_array(strtolower($prog['name']), ['knmp', 'bins']) ? strtoupper($prog['name']) : $prog['name'] }}
 </h3>
 <div class="text-[11px] font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider mt-0.5">Program Prioritas</div>
 </div>
 </div>
 
 <p class="text-[13px] text-textMuted-light dark:text-textMuted-dark leading-relaxed mb-6 flex-1">
 {{ $prog['narrative'] }}
 </p>
 
 <div class="mt-auto flex items-center gap-2 text-xs font-medium {{ $colors['text'] }} transition-transform group-hover:translate-x-1">
 Buka Modul Program <i class="fa-solid fa-arrow-right"></i>
 </div>
 </div>
 </a>
 @endforeach
 </div>

 </main>

 <footer class="py-5 text-center text-xs text-textMuted-light dark:text-textMuted-dark border-t border-gray-100 dark:border-gray-800 bg-bgSurface-light dark:bg-bgSurface-dark mt-auto relative z-10">
 &copy; 2026 Program Prioritas - Kementerian Kelautan dan Perikanan
 </footer>

</body>
</html>










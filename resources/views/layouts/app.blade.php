<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Program Prioritas Portal')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo-kkp.png') }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        // Theme initialization
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body x-data="{ sidebarOpen: window.innerWidth >= 1024, darkMode: document.documentElement.classList.contains('dark') }" @resize.window="sidebarOpen = window.innerWidth >= 1024" class="antialiased bg-bgBody-light dark:bg-bgBody-dark text-textMain-light dark:text-textMain-dark transition-colors duration-300">

    <!-- Top Navigation (Module Nav) -->
    <x-topbar :activeProgram="$activeProgram ?? 'Bioflok'" :activeModule="$activeModule ?? 'Dashboard'" />

    <div class="flex h-screen overflow-hidden pt-16">
        
        @if(($activeModule ?? '') !== 'Pengguna')
        <!-- Sidebar Overlay (Mobile) -->
        <div x-show="sidebarOpen" 
             x-transition.opacity 
             @click="sidebarOpen = false" 
             class="fixed inset-0 bg-black/50 z-30 lg:hidden">
        </div>

        <!-- Sidebar Navigation (Program Menu) -->
        <x-sidebar :activeProgram="$activeProgram ?? 'Bioflok'" :activeModule="$activeModule ?? 'Dashboard'" />
        @endif

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden relative">
            <main class="flex-1 overflow-y-auto p-6 lg:p-8 scroll-smooth">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Dynamic Confirm Modal -->
    <x-confirm-modal />

</body>
</html>

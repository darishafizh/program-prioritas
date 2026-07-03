<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <title>@yield('title', 'Program Prioritas Portal')</title>
 <link rel="icon" type="image/png" href="{{ asset('assets/images/logo-kkp.png') }}">

 <!-- Fonts -->
 <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

 <!-- Styles / Scripts -->
 @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js Plugins & Core -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        // Theme initialization
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        // Sidebar pre-initialization check to prevent flicker when collapsed
        if (localStorage.getItem('_x_sidebarOpen') === 'false' && window.innerWidth >= 1024) {
            document.documentElement.classList.add('sidebar-init-closed');
        }
    </script>
</head>
<body x-data="{ sidebarOpen: $persist(window.innerWidth >= 1024), darkMode: document.documentElement.classList.contains('dark') }"
    x-init="document.documentElement.classList.remove('sidebar-init-closed')"
    @resize.window="if (window.innerWidth < 1024) sidebarOpen = false"
    class="antialiased bg-bgBody-light dark:bg-bgBody-dark text-textMain-light dark:text-textMain-dark transition-colors duration-300">

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
 
 <!-- Global Toast Notification -->
 <div x-data 
      x-show="$store.toast.isOpen" 
      x-transition.opacity 
      style="display: none; z-index: 99999; min-width: 300px; max-width: 400px;" 
      class="fixed top-6 right-6 flex items-center p-4 mb-4 text-gray-500 bg-white rounded-xl shadow-xl dark:text-gray-400 dark:bg-gray-800 border-l-4" 
      :class="$store.toast.type === 'danger' ? 'border-danger' : 'border-teal-light'" 
      role="alert">
      
     <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg" 
          :class="$store.toast.type === 'danger' ? 'text-danger bg-danger/10 dark:bg-danger/20' : 'text-teal-light bg-teal-light/10 dark:bg-teal-light/20'">
         <i class="fa-solid" :class="$store.toast.type === 'danger' ? 'fa-xmark' : 'fa-check'"></i>
     </div>
     
     <div class="ml-3 text-sm font-medium text-gray-800 dark:text-white" x-text="$store.toast.message"></div>
     
     <button type="button" @click="$store.toast.isOpen = false" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700">
         <span class="sr-only">Close</span>
         <i class="fa-solid fa-xmark"></i>
     </button>
 </div>

 <script>
     document.addEventListener('alpine:init', () => {
         Alpine.store('toast', {
             isOpen: false,
             message: '',
             type: 'success',
             toastTimeout: null,

             showToast(detail) {
                 this.message = detail.message || 'Berhasil';
                 this.type = detail.type || 'success';
                 this.isOpen = true;

                 if (this.toastTimeout) {
                     clearTimeout(this.toastTimeout);
                 }

                 this.toastTimeout = setTimeout(() => {
                     this.isOpen = false;
                 }, 3000);
             }
         });
     });
 </script>
</body>
</html>

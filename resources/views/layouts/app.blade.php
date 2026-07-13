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
    class="antialiased text-textMain-light dark:text-textMain-dark transition-colors duration-300"
    style="background-color: #E8ECF1;"
    :style="darkMode ? 'background-color: #0A0E17;' : 'background-color: #E8ECF1;'">

  <div class="flex h-screen overflow-hidden">
  
  <!-- Sidebar Overlay (Mobile) -->
  <div x-show="sidebarOpen" 
  x-transition.opacity 
  @click="sidebarOpen = false" 
  class="fixed inset-0 bg-black/50 z-50 lg:hidden">
  </div>

  <!-- Sidebar Navigation (Program Menu) -->
  <x-sidebar :activeProgram="$activeProgram ?? 'Bioflok'" :activeModule="$activeModule ?? 'Dashboard'" />

  <!-- Main Content Area -->
  <div class="flex-1 flex flex-col overflow-hidden relative">
      <!-- Mobile Hamburger Header (Visible < 1024px) -->
      <div class="lg:hidden flex items-center justify-between px-4 py-3.5 bg-bgBody-light dark:bg-bgBody-dark shrink-0">
          <button @click="sidebarOpen = true" class="p-2 -ml-2 rounded-xl text-textMain-light dark:text-textMain-dark hover:bg-gray-200/50 dark:hover:bg-gray-800 transition-colors">
              <i class="fa-solid fa-bars text-lg"></i>
          </button>
          <div class="flex items-center gap-2.5">
              <img src="{{ asset('assets/images/logo-kkp.png') }}" alt="Logo KKP" class="w-6 h-6 object-contain dark:bg-white dark:rounded-full dark:p-0.5">
              <span class="font-bold text-sm tracking-tight text-slate-900 dark:text-white">{{ $activeProgram ?? 'Program Prioritas' }}</span>
          </div>
          <a href="{{ url('greetings') }}" class="p-2 -mr-2 text-xs font-medium text-textMuted-light hover:text-teal-light transition-colors">
              <i class="fa-solid fa-grid-2"></i>
          </a>
      </div>

      <!-- Content Sheet: subtle shadow, 1rem margin top & right, rounded top corners only, hidden scrollbar (no entrance animation on refresh) -->
      <main style="margin-top: 1rem; margin-right: 1rem; margin-bottom: 0; border-radius: 1rem 1rem 0 0; scrollbar-width: none; -ms-overflow-style: none; background-color: #FFFFFF;" :style="darkMode ? 'margin-top: 1rem; margin-right: 1rem; margin-bottom: 0; border-radius: 1rem 1rem 0 0; scrollbar-width: none; -ms-overflow-style: none; background-color: #131C2E;' : 'margin-top: 1rem; margin-right: 1rem; margin-bottom: 0; border-radius: 1rem 1rem 0 0; scrollbar-width: none; -ms-overflow-style: none; background-color: #FFFFFF;'" class="flex-1 overflow-y-auto shadow-sm shadow-slate-200/80 dark:shadow-black/30 p-6 lg:p-8 scroll-smooth border-0 [&::-webkit-scrollbar]:hidden">
          <div class="w-full">
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

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <title>Login - Program Prioritas Portal</title>
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
<body x-data="{ darkMode: document.documentElement.classList.contains('dark') }" class="antialiased text-textMain-light dark:text-textMain-dark overflow-hidden">

 <!-- Full Background with Overlay -->
 <div class="min-h-screen w-full relative flex items-center justify-center bg-cover bg-center" style="background-image: url('{{ asset('assets/images/login-bg.png') }}');">
 
 <!-- Overlay -->
 <div class="absolute inset-0 bg-navy-light/40 dark:bg-navy-dark/60"></div>

 <!-- Theme Toggle Absolute -->
 <button @click="darkMode = !darkMode; if(darkMode) { document.documentElement.classList.add('dark'); localStorage.setItem('theme', 'dark'); } else { document.documentElement.classList.remove('dark'); localStorage.setItem('theme', 'light'); }" 
 class="absolute top-6 right-6 w-10 h-10 rounded-md flex items-center justify-center bg-white/15 backdrop-blur-md text-white hover:bg-white/25 transition-all z-20 border border-white/10">
 <i class="fa-solid" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
 </button>

 <!-- Login Card Container -->
 <div class="relative z-10 w-full max-w-md px-6">
 
 <div class="bg-bgSurface-light dark:bg-bgSurface-dark rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-800 transition-colors duration-300">
 
 <!-- Card Header -->
 <div class="pb-5 px-6 text-center border-b border-gray-100 dark:border-gray-800" style="padding-top: 3.5rem;">
 <div class="w-14 h-14 mx-auto mb-3">
 <img src="{{ asset('assets/images/logo-kkp.png') }}" alt="Logo KKP" class="w-full h-full object-contain dark:bg-white dark:rounded-full dark:p-1.5 transition-all">
 </div>
    <h1 class="text-xl font-semibold tracking-tight text-textMain-light dark:text-white">
        Sistem Terintegrasi
    </h1>
    <p class="text-[11px] font-normal text-textMuted-light dark:text-textMuted-dark mt-1">Program Prioritas Kementerian Kelautan dan Perikanan</p>
 </div>

 <!-- Card Body -->
 <div class="p-6">
 @if(session('error'))
 <div class="mb-4 p-3 rounded-xl bg-danger/10 border border-danger/20 text-danger text-sm font-medium flex items-center gap-2">
 <i class="fa-solid fa-circle-exclamation"></i>
 {{ session('error') }}
 </div>
 @endif
 
 <form action="{{ url('login') }}" method="POST" class="space-y-4">
 @csrf
 
 <!-- Username Field -->
 <div>
 <label class="block text-sm font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Username</label>
 <div class="relative">
 <i class="fa-regular fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
 <input type="text" name="username" required placeholder="Masukkan username Anda" 
 class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900 text-sm focus:border-teal-light focus:ring-1 focus:ring-teal-light outline-none transition-all placeholder:text-gray-400">
 </div>
 </div>

 <!-- Password Field -->
 <div x-data="{ showPassword: false }">
 <label class="block text-sm font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Password</label>
 <div class="relative">
 <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
 <input :type="showPassword ? 'text' : 'password'" name="password" required placeholder="••••••••" 
 class="w-full pl-11 pr-12 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900 text-sm focus:border-teal-light focus:ring-1 focus:ring-teal-light outline-none transition-all placeholder:text-gray-400">
 <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-textMain-light transition-colors">
 <i class="fa-regular" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
 </button>
 </div>
 </div>

 <!-- Captcha Field -->
 <div>
 <label class="block text-sm font-medium text-textMuted-light dark:text-textMuted-dark mb-1.5">Berapa hasil dari {{ $num1 }} + {{ $num2 }}?</label>
 <div class="relative">
 <i class="fa-solid fa-calculator absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
 <input type="number" name="captcha" required placeholder="Masukkan jawaban" 
 class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900 text-sm focus:border-teal-light focus:ring-1 focus:ring-teal-light outline-none transition-all placeholder:text-gray-400">
 </div>
 </div>

 <!-- Submit Button -->
 <button type="submit" class="w-full py-2.5 mt-2 rounded-lg bg-teal-light hover:bg-teal-light/90 text-white font-medium text-xs transition-all flex items-center justify-center gap-2">
 Masuk ke Sistem <i class="fa-solid fa-arrow-right-to-bracket"></i>
 </button>
 </form>
 </div>
 
 <!-- Footer -->
 <div class="text-center" style="padding-bottom: 2.5rem;">
 <p class="text-xs text-textMuted-light/60 dark:text-textMuted-dark/50">
 &copy; 2026 Kementerian Kelautan dan Perikanan
 </p>
 </div>
 </div>
 </div>
 </div>

</body>
</html>







<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Program Prioritas Portal</title>
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
<body x-data="{ darkMode: document.documentElement.classList.contains('dark') }" class="antialiased text-textMain-light dark:text-textMain-dark overflow-hidden">

    <!-- Full Background with Overlay -->
    <div class="min-h-screen w-full relative flex items-center justify-center bg-cover bg-center" style="background-image: url('{{ asset('assets/images/login-bg.png') }}');">
        
        <!-- Overlay -->
        <div class="absolute inset-0 bg-navy-light/30 dark:bg-navy-dark/50"></div>

        <!-- Theme Toggle Absolute -->
        <button @click="darkMode = !darkMode; if(darkMode) { document.documentElement.classList.add('dark'); localStorage.setItem('theme', 'dark'); } else { document.documentElement.classList.remove('dark'); localStorage.setItem('theme', 'light'); }" 
                class="absolute top-6 right-6 w-10 h-10 rounded-full flex items-center justify-center bg-white/20 dark:bg-black/20 backdrop-blur-md text-white hover:bg-white/30 transition-all z-20 shadow-lg border border-white/10">
            <i class="fa-solid" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
        </button>

        <!-- Login Card Container -->
        <div class="relative z-10 w-full max-w-md px-6">
            
            <div class="bg-bgSurface-light dark:bg-bgSurface-dark rounded-[2rem] shadow-2xl overflow-hidden border border-white/20 dark:border-gray-800 transition-colors duration-300">
                
                <!-- Card Header -->
                <div class="pt-10 pb-6 px-8 text-center bg-gradient-to-b from-gray-50/50 to-transparent dark:from-gray-800/50 dark:to-transparent border-b border-gray-100 dark:border-gray-800">
                    <div class="w-20 h-20 mx-auto mb-4 relative">
                        <img src="{{ asset('assets/images/logo-kkp.png') }}" alt="Logo KKP" class="w-full h-full object-contain relative z-10 dark:bg-white dark:rounded-full dark:p-1.5 transition-all">
                    </div>
                    <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-navy-light to-teal-light dark:from-teal-dark dark:to-teal-light tracking-tight mb-1">
                        Program Prioritas
                    </h1>
                    <p class="text-sm font-medium text-textMuted-light dark:text-textMuted-dark">Portal Sistem Terpadu KKP</p>
                </div>

                <!-- Card Body -->
                <div class="p-8">
                    @if(session('error'))
                    <div class="mb-4 p-3 rounded-xl bg-danger/10 border border-danger/20 text-danger text-xs font-bold flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ session('error') }}
                    </div>
                    @endif
                    
                    <form action="/login" method="POST" class="space-y-5">
                        @csrf
                        
                        <!-- Username Field -->
                        <div>
                            <label class="block text-xs font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest mb-2">Username</label>
                            <div class="relative">
                                <i class="fa-regular fa-user absolute left-4 top-1/2 -translate-y-1/2 text-textMuted-light dark:text-textMuted-dark"></i>
                                <input type="text" name="username" required placeholder="Masukkan username Anda" 
                                       class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:border-teal-light focus:ring-1 focus:ring-teal-light outline-none transition-all placeholder:text-gray-400">
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div x-data="{ showPassword: false }">
                            <label class="block text-xs font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest mb-2">Password</label>
                            <div class="relative">
                                <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-textMuted-light dark:text-textMuted-dark"></i>
                                <input :type="showPassword ? 'text' : 'password'" name="password" required placeholder="••••••••" 
                                       class="w-full pl-11 pr-12 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:border-teal-light focus:ring-1 focus:ring-teal-light outline-none transition-all placeholder:text-gray-400">
                                <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-textMuted-light hover:text-teal-light transition-colors">
                                    <i class="fa-regular" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Captcha Field -->
                        <div>
                            <label class="block text-xs font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest mb-2">Berapa hasil dari {{ $num1 }} + {{ $num2 }}?</label>
                            <div class="relative">
                                <i class="fa-solid fa-calculator absolute left-4 top-1/2 -translate-y-1/2 text-textMuted-light dark:text-textMuted-dark"></i>
                                <input type="number" name="captcha" required placeholder="Masukkan jawaban" 
                                       class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:border-teal-light focus:ring-1 focus:ring-teal-light outline-none transition-all placeholder:text-gray-400">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-navy-light to-teal-light dark:from-navy-dark dark:to-teal-dark text-white font-bold text-sm shadow-md hover:shadow-xl transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            Masuk ke Sistem <i class="fa-solid fa-arrow-right-to-bracket"></i>
                        </button>
                    </form>
                </div>
                
                <!-- Footer -->
                <div class="pb-6 text-center">
                    <p class="text-[0.65rem] font-medium text-textMuted-light/70 dark:text-textMuted-dark/50">
                        &copy; 2026 Kementerian Kelautan dan Perikanan
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

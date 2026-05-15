<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>FJS AUTO SERVICE - Internal System</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-100 dark:bg-[#0a0a0a] text-slate-900 dark:text-[#EDEDEC] font-sans antialiased">
        
        <div class="min-h-screen flex flex-col items-center justify-center p-6">
            <!-- Logo & Brand -->
            <div class="mb-12 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-600 rounded-3xl shadow-xl shadow-blue-500/20 mb-6">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-black tracking-tight">FJS <span class="text-blue-600">AUTO SERVICE</span></h1>
                <p class="text-slate-500 dark:text-slate-400 mt-2 font-medium uppercase tracking-widest text-xs">Internal Management System</p>
            </div>

            <!-- Login Card Entry -->
            <div class="w-full max-w-md bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl shadow-slate-200 dark:shadow-none border border-slate-200 dark:border-slate-800 p-10 overflow-hidden relative">
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
                
                <div class="text-center mb-8">
                    <h2 class="text-xl font-bold">Selamat Datang</h2>
                    <p class="text-sm text-slate-500 mt-1">Silakan masuk untuk mengelola kasir dan laporan keuangan.</p>
                </div>

                <div class="space-y-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" 
                            class="flex items-center justify-center w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-200 dark:shadow-none transition-all transform hover:-translate-y-1">
                            Lanjutkan ke Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                            class="flex items-center justify-center w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-200 dark:shadow-none transition-all transform hover:-translate-y-1">
                            Masuk ke Sistem
                        </a>
                        
                        @if (Route::has('register'))
                            <p class="text-center text-xs text-slate-400 pt-2">
                                Belum punya akses? Hubungi Admin atau <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">Daftar Staf Baru</a>
                            </p>
                        @endif
                    @endauth
                </div>

                <!-- System Features Indicator -->
                <div class="grid grid-cols-3 gap-4 mt-10 pt-8 border-t border-slate-100 dark:border-slate-800">
                    <div class="text-center">
                        <div class="text-blue-600 mb-1 flex justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Kasir</span>
                    </div>
                    <div class="text-center">
                        <div class="text-blue-600 mb-1 flex justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Laporan</span>
                    </div>
                    <div class="text-center">
                        <div class="text-blue-600 mb-1 flex justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Staf</span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="mt-12 text-center text-slate-400 text-xs">
                <p>&copy; {{ date('Y') }} FJS AUTO SERVICE. Secured Internal Environment.</p>
                <div class="flex items-center justify-center gap-2 mt-2">
                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                    <span>System Online</span>
                </div>
            </footer>
        </div>

    </body>
</html>
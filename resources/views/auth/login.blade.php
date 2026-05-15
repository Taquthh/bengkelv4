<x-guest-layout>
    <div>
        <!-- Card Utama dengan Efek Glassmorphism Ringan -->
        <div class="max-w-2xl w-full space-y-8 bg-white dark:bg-gray-800 shadow-2xl rounded-3xl p-6 sm:p-10 border border-gray-100 dark:border-gray-700 transition-all duration-500">
            
            <!-- Header -->
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-600 rounded-3xl shadow-xl shadow-blue-500/20 mb-6 transform -rotate-3 hover:rotate-0 transition-transform duration-300">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">FJS <span class="text-blue-600">AUTO SERVICE</span></h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 font-medium uppercase tracking-widest italic">Sistem Internal Bengkel</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4 text-center p-3 bg-green-50 dark:bg-green-900/20 rounded-xl text-green-600 text-xs font-bold" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
                @csrf

                <!-- Email Address -->
                <div class="space-y-1">
                    <x-input-label for="email" :value="__('Email')" class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 ml-1" />
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors group-focus-within:text-blue-600">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <x-text-input id="email" 
                            class="block w-full pl-10 py-3.5 bg-gray-50 dark:bg-gray-700/50 border-gray-200 dark:border-gray-600 rounded-2xl focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm" 
                            type="email" name="email" :value="old('email')" required autofocus placeholder="Masukkan Email" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-[10px]" />
                </div>

                <!-- Password -->
                <div class="space-y-1">
                    <div class="flex justify-between items-center px-1">
                        <x-input-label for="password" :value="__('Password')" class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400" />
                        @if (Route::has('password.request'))
                            <a class="text-[10px] text-blue-600 hover:underline font-bold transition-all" href="{{ route('password.request') }}">
                                {{ __('Lupa Password?') }}
                            </a>
                        @endif
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors group-focus-within:text-blue-600">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <x-text-input id="password" 
                            class="block w-full pl-10 pr-10 py-3.5 bg-gray-50 dark:bg-gray-700/50 border-gray-200 dark:border-gray-600 rounded-2xl focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm" 
                            type="password" name="password" required placeholder="••••••••" />
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-500 transition-colors" onclick="togglePassword()">
                            <svg id="eye-icon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-[10px]" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center px-1">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                        <input id="remember_me" type="checkbox" class="h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 transition-all cursor-pointer" name="remember">
                        <span class="ml-2 text-xs font-medium text-gray-600 dark:text-gray-400 group-hover:text-blue-600 transition-colors">{{ __('Ingat saya di perangkat ini') }}</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" class="w-full flex justify-center items-center py-4 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-500/30 transition-all duration-300 transform hover:-translate-y-0.5 active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        {{ __('Otorisasi Masuk') }}
                    </button>
                </div>
            </form>

            <!-- Footer -->
            <div class="mt-10 pt-6 border-t border-gray-100 dark:border-gray-700 flex flex-col items-center gap-4 text-center">
                <p class="text-xs text-gray-500">
                    Sistem Operasional v2.0 &bull; FJS Auto Service &copy; {{ date('Y') }}
                </p>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    <span class="text-[10px] font-black uppercase text-gray-400 tracking-tighter">Server Connected</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Terpadu Toggle Password -->
    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            const isPassword = input.type === 'password';
            
            input.type = isPassword ? 'text' : 'password';
            
            icon.innerHTML = isPassword 
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L8.464 8.464m1.414 1.414l4.242 4.242m-4.242-4.242L12 12m-1.414-1.414L8.464 8.464m0 0L7.05 7.05m1.414 1.414L12 12"></path>'
                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
        }
    </script>
</x-guest-layout>